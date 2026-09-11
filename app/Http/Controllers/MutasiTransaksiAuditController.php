<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\DataSampling;
use App\Models\LogMutasiAudit;
use App\Models\Menu;
use App\Models\RencanaAudit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MutasiTransaksiAuditController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $roleId = $user->role_id;
        $userCodeQa = $user->code_qa;

        $menus = Menu::whereNull('parent_id')
            ->where(function ($query) use ($roleId) {
                $query->where('role_id', $roleId)
                    ->orWhereNull('role_id');
            })
            ->with(['children' => function ($query) use ($roleId) {
                $query->where('role_id', $roleId)
                    ->orWhereNull('role_id');
            }])
            ->orderBy('order')
            ->get();

        $title = 'Mutasi Transaksi Audit';

        // Ambil data branch untuk dropdown Pindah Unit
        if ($roleId == 3) {
            // QAM: Berdasarkan Region user
            $masterQa = DB::table('masterqa')->where('code_qa', $userCodeQa)->first();
            $kodeRegion = $masterQa ? $masterQa->kode_unit : null;
            $targetRegions = [$kodeRegion];
            if ($kodeRegion == '3333') {
                $targetRegions[] = '1111';
            }
            $branch = Branch::whereIn('code_region', $targetRegions)->orderBy('unit')->get();
            if ($branch->isEmpty()) {
                $branch = Branch::orderBy('unit')->get();
            }

            // QA list untuk QAM (semua QA kecuali 2220 & 3330)
            $qa = DB::table('users')
                ->join('masterqa', 'users.code_qa', '=', 'masterqa.code_qa')
                ->select('users.id', 'users.name', 'users.code_qa')
                ->whereNotIn('users.code_qa', ['2220', '3330'])
                ->orderBy('users.name')
                ->get();
        } else {
            // QAL: Berdasarkan Area user
            $masterQa = DB::table('masterqa')->where('code_qa', $userCodeQa)->first();
            $ambilArea = $masterQa ? $masterQa->kode_unit : null;
            $branch = Branch::where('code_area', $ambilArea)->orderBy('unit')->get();
            if ($branch->isEmpty()) {
                $branch = Branch::orderBy('unit')->get();
            }

            // QA list untuk QAL (bawahan langsung berdasarkan kolom atasan)
            $qa = DB::table('users')
                ->join('masterqa', 'users.code_qa', '=', 'masterqa.code_qa')
                ->where('masterqa.atasan', $userCodeQa)
                ->select('users.id', 'users.name', 'users.code_qa')
                ->orderBy('users.name')
                ->get();

            if ($qa->isEmpty()) {
                $qa = DB::table('users')
                    ->join('masterqa', 'users.code_qa', '=', 'masterqa.code_qa')
                    ->select('users.id', 'users.name', 'users.code_qa')
                    ->whereNotIn('users.code_qa', ['2220', '3330'])
                    ->orderBy('users.name')
                    ->get();
            }
        }

        $prefix = $roleId == 3 ? 'qam' : 'qal';
        $view = $roleId == 3 ? 'qam.mutasi_transaksi_audit.index' : 'qal.mutasi_transaksi_audit.index';

        return view($view, compact('menus', 'title', 'branch', 'qa', 'prefix'));
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();
            $userCodeQa = $user->code_qa;
            $roleId = $user->role_id;

            // 1. Ambil daftar bawahan langsung dari tabel masterqa
            $bawahanDirect = DB::table('masterqa')
                ->where('atasan', $userCodeQa)
                ->pluck('code_qa');

            // 2. Ambil semua kode_unit: milik user sendiri + bawahan langsung + bawahan tidak langsung
            $unitsUser = DB::table('masterqa')
                ->where('code_qa', $userCodeQa)
                ->orWhere('atasan', $userCodeQa)
                ->orWhereIn('atasan', $bawahanDirect)
                ->pluck('kode_unit')
                ->unique()
                ->filter()
                ->toArray();

            // 3. Query RencanaAudit dengan join ke branch
            $query = RencanaAudit::query()
                ->join('branch', 'rencana_audit.unit', '=', 'branch.kode_branch');

            if (!empty($unitsUser)) {
                $query->where(function ($q) use ($unitsUser) {
                    $q->whereIn('branch.code_area', $unitsUser)
                        ->orWhereIn('branch.code_region', $unitsUser);
                });
            }

            $query->select('rencana_audit.*', 'branch.area', 'branch.unit as nama_unit')
                ->orderBy('rencana_audit.created_at', 'desc');

            $prefix = $roleId == 3 ? 'qam' : 'qal';

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('area', function ($row) {
                    return $row->area ?? '-';
                })
                ->addColumn('nama_unit', function ($row) {
                    return ($row->nama_unit ?? '-');
                })
                ->addColumn('petugas', function ($row) {
                    $qaNames = DB::table('data_sampling')
                        ->join('users', 'data_sampling.user_id', '=', 'users.id')
                        ->where('data_sampling.id_ref_sampling', $row->id_ref_sampling)
                        ->distinct()
                        ->pluck('users.name')
                        ->toArray();

                    return !empty($qaNames) ? implode(', ', $qaNames) : '-';
                })
                ->addColumn('current_petugas_id', function ($row) {
                    return DB::table('data_sampling')
                        ->where('id_ref_sampling', $row->id_ref_sampling)
                        ->value('user_id') ?? '';
                })
                ->addColumn('status_badge', function ($row) {
                    $status = strtolower($row->status ?? '');
                    $badges = [
                        'done'    => '<span class="badge badge-success px-2 py-1">DONE</span>',
                        'proses'  => '<span class="badge badge-warning px-2 py-1 text-dark">PROSES</span>',
                        'pending' => '<span class="badge badge-secondary px-2 py-1">PENDING</span>',
                    ];
                    return $badges[$status] ?? '<span class="badge badge-info px-2 py-1">' . strtoupper($status ?: '-') . '</span>';
                })
                ->addColumn('aksi', function ($row) use ($prefix) {
                    $statusUrl = route($prefix . '.mutasi.transaksi.update-status', $row->id);
                    $petugasUrl = route($prefix . '.mutasi.transaksi.update-petugas', $row->id);
                    $unitUrl = route($prefix . '.mutasi.transaksi.update-unit', $row->id);
                    $logUrl = route($prefix . '.mutasi.transaksi.log', $row->id_ref_sampling);

                    return '
                        <div class="dropdown text-center">
                            <button class="btn btn-sm btn-outline-primary dropdown-toggle shadow-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-cog mr-1"></i> Aksi
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow border-0">
                                <a class="dropdown-item btn-ubah-status py-2" href="javascript:void(0)" 
                                    data-id="' . $row->id . '" 
                                    data-ref="' . $row->id_ref_sampling . '" 
                                    data-status="' . $row->status . '" 
                                    data-url="' . $statusUrl . '">
                                    <i class="fas fa-sync-alt text-primary mr-2"></i> Ubah Status
                                </a>
                                <a class="dropdown-item btn-ubah-petugas py-2" href="javascript:void(0)" 
                                    data-id="' . $row->id . '" 
                                    data-ref="' . $row->id_ref_sampling . '" 
                                    data-url="' . $petugasUrl . '">
                                    <i class="fas fa-user-edit text-warning mr-2"></i> Ubah Petugas
                                </a>
                                <a class="dropdown-item btn-pindah-unit py-2" href="javascript:void(0)" 
                                    data-id="' . $row->id . '" 
                                    data-ref="' . $row->id_ref_sampling . '" 
                                    data-unit="' . $row->unit . '" 
                                    data-url="' . $unitUrl . '">
                                    <i class="fas fa-map-marker-alt text-danger mr-2"></i> Pindah Unit
                                </a>
                                <div class="dropdown-divider my-1"></div>
                                <a class="dropdown-item btn-lihat-log py-2" href="javascript:void(0)" 
                                    data-ref="' . $row->id_ref_sampling . '" 
                                    data-url="' . $logUrl . '">
                                    <i class="fas fa-history text-info mr-2"></i> Riwayat Mutasi
                                </a>
                            </div>
                        </div>
                    ';
                })
                ->rawColumns(['status_badge', 'aksi'])
                ->make(true);
        }

        abort(404);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:proses,done,pending',
        ], [
            'status.required' => 'Status wajib dipilih',
            'status.in'       => 'Pilihan status tidak valid',
        ]);

        $rencana = is_numeric($id) ? RencanaAudit::find($id) : null;
        if (!$rencana) {
            $rencana = RencanaAudit::where('id_ref_sampling', $id)->firstOrFail();
        }

        $oldStatus = $rencana->status ?? 'pending';
        $newStatus = $request->status;

        if ($oldStatus === $newStatus) {
            return response()->json([
                'success' => false,
                'message' => 'Status yang dipilih sama dengan status saat ini (' . strtoupper($oldStatus) . ')',
            ], 422);
        }

        try {
            DB::transaction(function () use ($rencana, $oldStatus, $newStatus) {
                // 1. Update status pada rencana_audit
                $rencana->update(['status' => $newStatus]);

                // 2. Cascade update ke semua data_sampling dengan id_ref_sampling yang sama
                DataSampling::where('id_ref_sampling', $rencana->id_ref_sampling)
                    ->update(['status' => $newStatus]);

                // 3. Catat ke tabel log_mutasi_audit
                LogMutasiAudit::create([
                    'id_ref_sampling' => $rencana->id_ref_sampling,
                    'jenis_mutasi'    => 'status',
                    'nilai_lama'      => strtoupper($oldStatus),
                    'nilai_baru'      => strtoupper($newStatus),
                    'diubah_oleh'     => Auth::id(),
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Status audit berhasil diubah menjadi ' . strtoupper($newStatus),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updatePetugas(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ], [
            'user_id.required' => 'Petugas QA wajib dipilih',
            'user_id.exists'   => 'Petugas QA tidak ditemukan',
        ]);

        $rencana = is_numeric($id) ? RencanaAudit::find($id) : null;
        if (!$rencana) {
            $rencana = RencanaAudit::where('id_ref_sampling', $id)->firstOrFail();
        }

        $newUser = User::findOrFail($request->user_id);

        // Ambil nama petugas lama dari data_sampling
        $oldSampling = DataSampling::where('id_ref_sampling', $rencana->id_ref_sampling)->first();
        $oldUserName = '-';
        if ($oldSampling && $oldSampling->user_id) {
            $oldUser = User::find($oldSampling->user_id);
            $oldUserName = $oldUser ? $oldUser->name : "User ID: {$oldSampling->user_id}";
        }

        if ($oldSampling && $oldSampling->user_id == $newUser->id) {
            return response()->json([
                'success' => false,
                'message' => 'Petugas yang dipilih sama dengan petugas saat ini (' . $newUser->name . ')',
            ], 422);
        }

        try {
            DB::transaction(function () use ($rencana, $oldUserName, $newUser) {
                // 1. Update user_id di data_sampling
                DataSampling::where('id_ref_sampling', $rencana->id_ref_sampling)
                    ->update(['user_id' => $newUser->id]);

                // 2. Catat ke tabel log_mutasi_audit
                LogMutasiAudit::create([
                    'id_ref_sampling' => $rencana->id_ref_sampling,
                    'jenis_mutasi'    => 'petugas',
                    'nilai_lama'      => $oldUserName,
                    'nilai_baru'      => $newUser->name,
                    'diubah_oleh'     => Auth::id(),
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Petugas audit berhasil diubah menjadi ' . $newUser->name,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah petugas: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateUnit(Request $request, $id)
    {
        $request->validate([
            'unit' => 'required|exists:branch,kode_branch',
        ], [
            'unit.required' => 'Unit cabang baru wajib dipilih',
            'unit.exists'   => 'Unit cabang tidak ditemukan',
        ]);

        $rencana = is_numeric($id) ? RencanaAudit::find($id) : null;
        if (!$rencana) {
            $rencana = RencanaAudit::where('id_ref_sampling', $id)->firstOrFail();
        }

        $oldUnitCode = $rencana->unit;
        $oldBranch = Branch::where('kode_branch', $oldUnitCode)->first();
        $oldUnitName = $oldBranch ? ($oldBranch->unit . ' (' . $oldBranch->kode_branch . ')') : $oldUnitCode;

        $newUnitCode = $request->unit;
        $newBranch = Branch::where('kode_branch', $newUnitCode)->first();
        $newUnitName = $newBranch ? ($newBranch->unit . ' (' . $newBranch->kode_branch . ')') : $newUnitCode;

        if ($oldUnitCode === $newUnitCode) {
            return response()->json([
                'success' => false,
                'message' => 'Unit cabang yang dipilih sama dengan unit saat ini (' . $newUnitName . ')',
            ], 422);
        }

        try {
            DB::transaction(function () use ($rencana, $oldUnitName, $newUnitCode, $newUnitName) {
                // 1. Update unit pada rencana_audit
                $rencana->update(['unit' => $newUnitCode]);

                // 2. Cascade update ke semua data_sampling dengan id_ref_sampling yang sama
                DataSampling::where('id_ref_sampling', $rencana->id_ref_sampling)
                    ->update(['unit' => $newUnitCode]);

                // 3. Catat ke tabel log_mutasi_audit
                LogMutasiAudit::create([
                    'id_ref_sampling' => $rencana->id_ref_sampling,
                    'jenis_mutasi'    => 'unit',
                    'nilai_lama'      => $oldUnitName,
                    'nilai_baru'      => $newUnitName,
                    'diubah_oleh'     => Auth::id(),
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Unit audit berhasil dipindahkan ke ' . $newUnitName,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memindahkan unit: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getLog($id)
    {
        $idRefSampling = $id;
        if (is_numeric($id)) {
            $rencana = RencanaAudit::find($id);
            if ($rencana) {
                $idRefSampling = $rencana->id_ref_sampling;
            }
        }

        $logs = LogMutasiAudit::with('user:id,name')
            ->where('id_ref_sampling', $idRefSampling)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($log) {
                return [
                    'id'           => $log->id,
                    'jenis_mutasi' => ucfirst($log->jenis_mutasi),
                    'nilai_lama'   => $log->nilai_lama ?? '-',
                    'nilai_baru'   => $log->nilai_baru ?? '-',
                    'diubah_oleh'  => $log->user ? $log->user->name : 'User #' . $log->diubah_oleh,
                    'created_at'   => $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '-',
                ];
            });

        return response()->json([
            'success' => true,
            'data'    => $logs,
        ]);
    }
}

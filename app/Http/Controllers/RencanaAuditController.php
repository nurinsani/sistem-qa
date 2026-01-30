<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\DataSampling;
use App\Models\Menu;
use App\Models\RencanaAudit;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RencanaAuditController extends Controller
{
    public function index()
    {
        $roleId = Auth::user()->role_id;

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

        $title = 'Rencana Audit';
        $branch = Branch::all();

        return view('rencana_audit.index', compact('menus', 'title', 'branch'));
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(RencanaAudit::query())
                ->addIndexColumn()
                ->addColumn('status', function($row) {
                    $status = $row->status ?? 'done';
                    $badges = [
                        'done' => '<span class="badge bg-success">Done</span>',
                        'progress' => '<span class="badge bg-warning">Progress</span>',
                        'pending' => '<span class="badge bg-secondary">Pending</span>',
                    ];
                    return $badges[$status] ?? '<span class="badge bg-secondary">-</span>';
                })
                ->addColumn('aksi', function($row) {
                    $detail_url = route('rencana.audit.show', $row->id_ref_sampling);
                    $start_url = route('rencana.audit.start', $row->id);
                    
                    return '
                        <a href="'.$detail_url.'" class="btn btn-sm btn-primary">Detail</a>
                        <a href="'.$start_url.'" class="btn btn-sm btn-success">Start</a>
                    ';
                })
                ->rawColumns(['status', 'aksi'])
                ->make(true);
        }

        abort(404);
    }

    public function data_detail(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(DataSampling::query())
                ->addIndexColumn()
                ->addColumn('status_sampling', function($row) {
                    $status_sampling = $row->status_sampling;
                    $badges = [
                        '111' => '<span class="badge bg-danger">High</span>',
                        '112' => '<span class="badge bg-danger">High</span>',
                        '113' => '<span class="badge bg-danger">High</span>',
                        '117' => '<span class="badge bg-danger">High</span>',
                        '331' => '<span class="badge bg-danger">High</span>',
                        '221' => '<span class="badge bg-danger">High</span>',
                        '115' => '<span class="badge bg-warning">Medium</span>',
                        '441' => '<span class="badge bg-success">Low</span>',
                    ];
                    return $badges[$status_sampling] ?? '<span class="badge bg-secondary">-</span>';
                })
                ->addColumn('aksi', function($row) {
                    $detail_url = route('rencana.audit.start', $row->cif);
                    
                    return '
                        <a href="'.$detail_url.'" class="btn btn-sm btn-primary">Detail</a>
                    ';
                })
                ->rawColumns(['status_sampling', 'aksi'])
                ->make(true);
        }

        abort(404);
    }

    public function auditRutinStore(Request $request)
    {
        $validated = $request->validate([
            'unit' => 'required',
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            'jumlah_sampling' => 'nullable|numeric|min:0',
        ],
        [
            'unit.required' => 'AP harus dipilih',
            'tanggal_awal.required' => 'Tanggal awal harus diisi',
            'tanggal_akhir.required' => 'Tanggal akhir harus diisi',
            'tanggal_akhir.after_or_equal' => 'Tanggal akhir harus setelah atau sama dengan tanggal awal',
        ]);

        $tanggal = Carbon::parse($request->tanggal_awal);
        $tahun   = $tanggal->format('Y');
        $bulan   = $tanggal->format('m');

        $idRefSampling = $tahun . $bulan . $request->unit;

        try {

            DB::transaction(function () use ($validated, $idRefSampling) {

                // Simpan data
                RencanaAudit::create([
                    'unit' => $validated['unit'],
                    'id_ref_sampling' => $idRefSampling,
                    'tanggal_awal' => $validated['tanggal_awal'],
                    'tanggal_akhir' => $validated['tanggal_akhir'],
                    'jumlah_sampling' => $validated['jumlah_sampling'] ?? 0,
                    'status' => 'pending',
                ]);

                 // ambil data dari tabel sampling
                $sampling = DB::table('sampling')
                    ->join('data_loan_mob', 'sampling.cif', '=', 'data_loan_mob.cif')
                    ->where('sampling.unit', $validated['unit'])
                    ->inRandomOrder()
                    ->limit($validated['jumlah_sampling'])
                    ->select(
                        'sampling.unit',
                        'sampling.cif',
                        'sampling.kode_status as status_sampling',
                        'data_loan_mob.Cust_short_name as nama',
                        'data_loan_mob.code_kel as kode_kel',
                        'data_loan_mob.cao as cao',
                    )
                    ->get();

                if ($sampling->count() < $validated['jumlah_sampling']) {
                    throw new \Exception('Data sampling tidak mencukupi');
                }

                // insert ke data_sampling
                foreach ($sampling as $item) {
                    DB::table('data_sampling')->insert([
                        'unit' => $item->unit,
                        'cif' => $item->cif,
                        'id_ref_sampling' => $idRefSampling,
                        'nama' => $item->nama,
                        'kode_kel' => $item->kode_kel,
                        'cao' => $item->cao,
                        'jenis_audit' => 'audit_rutin',
                        'user_id' => Auth::id(),
                        'created_at' => now(),
                        'updated_at' => now(),
                        'status_sampling' => $item->status_sampling,
                    ]);
                }

                // hapus sampling
                DB::table('sampling')
                    ->whereIn('cif', $sampling->pluck('cif'))
                    ->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Data audit rutin berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($ref_sampling)
    {
        try {
            $roleId = Auth::user()->role_id;

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

            $title = 'Detail Rencana Audit';

            $data_sampling = DataSampling::where('id_ref_sampling', $ref_sampling)->firstOrFail();
            
            return view('rencana_audit.detail_rencana_audit', compact('data_sampling', 'title', 'menus'));
        } catch (\Exception $e) {
            return redirect()->route('rencana.audit.index')
                ->with('error', 'Data tidak ditemukan');
        }
    }
}

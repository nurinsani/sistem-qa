<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\DataLoanMob;
use App\Models\DataSampling;
use App\Models\Kelompok;
use App\Models\Menu;
use App\Models\RencanaAudit;
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
        
        $masterQa = DB::table('masterqa')
            ->join('branch', 'masterqa.kode_unit', '=', 'branch.code_area')
            ->where('code_qa', Auth::user()->code_qa)
            ->first();

        $ambilArea = $masterQa ? $masterQa->kode_unit : null;

        $branch = Branch::where('code_area', $ambilArea)->get();
        $kelompok = Kelompok::where('code_unit', $masterQa->kode_branch)->get();

        return view('rencana_audit.index', compact('menus', 'title', 'branch', 'kelompok'));
    }

    public function search(Request $request)
    {
        $q = $request->q;

        $data = Kelompok::where('nama_kel', 'like', "%$q%")
            ->limit(10)
            ->get();

        $result = [];
        foreach ($data as $item) {
            $result[] = [
                'id' => $item->code_kel,
                'text' => $item->nama_kel
            ];
        }

        return response()->json($result);
    }

    public function getCif(Request $request)
    {
        try {
            $kodeKel = $request->kode_kel;
            
            $kelompok = Kelompok::where('code_kel', $kodeKel)->first();

            $namaAo = DB::table('ao')
            ->where('cao', $kelompok->cao)
            ->value('nama_ao');
            
            if (!$kelompok) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelompok tidak ditemukan'
                ]);
            }
            
            $cifList = DB::table('data_loan_mob')
                ->where('code_kel', $kodeKel)
                ->leftJoin('ao', 'data_loan_mob.cao', '=', 'ao.cao')
                ->select('cif', 'Cust_Short_name')
                ->get()
                ->map(function($item) {
                    return [
                        'cif' => $item->cif,
                        'Cust_Short_name' => $item->Cust_Short_name ?? 'N/A'
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'nama_ao' => $namaAo ?? '-',
                    'cif_list' => $cifList
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $query = RencanaAudit::with('branch');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('area', function ($row) {
                    return $row->branch->area ?? '-';
                })
                ->addColumn('unit', function ($row) {
                    return $row->branch->unit ?? '-';
                })
                ->addColumn('status', function($row) {
                    $status = $row->status ?? 'done';
                    $badges = [
                        'done' => '<span class="badge bg-success">DONE</span>',
                        'proses' => '<span class="badge bg-warning">PROSES</span>',
                        'pending' => '<span class="badge bg-secondary">PENDING</span>',
                    ];
                    return $badges[$status] ?? '<span class="badge bg-secondary">-</span>';
                })
                ->addColumn('aksi', function ($row) {

                    $detail_url = route('rencana.audit.show', $row->id_ref_sampling);
                    $start_url  = route('rencana.audit.start', $row->id);

                    if ($row->status === 'proses') {
                        return '
                            <a href="'.$detail_url.'" class="btn btn-sm btn-primary">Detail</a>
                            <button class="btn btn-sm btn-secondary" disabled>
                                Start
                            </button>
                        ';
                    }

                    return '
                        <a href="'.$detail_url.'" class="btn btn-sm btn-primary">Detail</a>

                        <form action="'.$start_url.'" method="POST" style="display:inline;">
                            '.csrf_field().'
                            <button type="submit" class="btn btn-sm btn-success"
                                onclick="return confirm(\'Mulai audit ini?\')">
                                Start
                            </button>
                        </form>
                    ';
                })
                ->rawColumns(['status', 'aksi'])
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

        $idRefSampling = $tahun . $bulan . str_pad(rand(1, 99), 4, '0', STR_PAD_LEFT);

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

                 // ambil data dari tabel fraud_alerts
                $sampling = DB::table('fraud_alerts')
                    ->join('data_loan_mob', 'fraud_alerts.cif', '=', 'data_loan_mob.cif')
                    ->where('fraud_alerts.unit', $validated['unit'])
                    ->inRandomOrder()
                    ->limit($validated['jumlah_sampling'])
                    ->select(
                        'fraud_alerts.unit',
                        'fraud_alerts.cif',
                        'fraud_alerts.flag_status as status_sampling',
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
                        'status_sampling' => $item->status_sampling,
                        'status' => 'pending',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
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

    public function auditKhususStore(Request $request)
    {
        $validated = $request->validate([
            'code_kel' => 'required',
            'nama_kelompok' => 'required',
            'nama_ao' => 'nullable',
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            'cif' => 'required|array|min:1',
            'cif.*' => 'required|string',
        ],
        [
            'code_kel.required' => 'Kelompok harus dipilih',
            'nama_kelompok.required' => 'Nama kelompok harus diisi',
            'tanggal_awal.required' => 'Tanggal awal harus diisi',
            'tanggal_akhir.required' => 'Tanggal akhir harus diisi',
            'tanggal_akhir.after_or_equal' => 'Tanggal akhir harus setelah atau sama dengan tanggal awal',
            'cif.required' => 'CIF harus dipilih minimal 1',
            'cif.min' => 'CIF harus dipilih minimal 1',
        ]);

        // Generate ID Ref Sampling berdasarkan tanggal dan kode kelompok
        $tanggal = Carbon::parse($request->tanggal_awal);
        $tahun   = $tanggal->format('Y');
        $bulan   = $tanggal->format('m');
        
        $idRefSampling = $tahun . $bulan . str_pad(rand(1, 99), 4, '0', STR_PAD_LEFT);

        try {
            DB::transaction(function () use ($validated, $idRefSampling, $request) {

                // Ambil data unit dari salah satu CIF yang dipilih
                $firstCif = $validated['cif'][0];
                $unitData = DB::table('data_loan_mob')
                    ->where('cif', $firstCif)
                    ->where('code_kel', $validated['code_kel'])
                    ->first();

                if (!$unitData) {
                    throw new \Exception('Data CIF tidak ditemukan di database');
                }

                // Simpan data rencana audit
                RencanaAudit::create([
                    'unit' => $unitData->unit ?? '001',
                    'id_ref_sampling' => $idRefSampling,
                    'tanggal_awal' => $validated['tanggal_awal'],
                    'tanggal_akhir' => $validated['tanggal_akhir'],
                    'jumlah_sampling' => count($validated['cif']),
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Ambil data detail untuk setiap CIF yang dipilih
                foreach ($validated['cif'] as $cif) {
                    $dataCif = DB::table('data_loan_mob')
                        ->where('cif', $cif)
                        ->where('code_kel', $validated['code_kel'])
                        ->select([
                            'unit',
                            'cif',
                            'Cust_short_name as nama',
                            'code_kel as kode_kel',
                            'cao'
                        ])
                        ->first();

                    if ($dataCif) {
                        // Insert ke data_sampling
                        DB::table('data_sampling')->insert([
                            'unit' => $dataCif->unit,
                            'cif' => $dataCif->cif,
                            'id_ref_sampling' => $idRefSampling,
                            'nama' => $dataCif->nama,
                            'kode_kel' => $dataCif->kode_kel,
                            'cao' => $dataCif->cao,
                            'jenis_audit' => 'audit_khusus',
                            'user_id' => Auth::id(),
                            'created_at' => now(),
                            'updated_at' => now(),
                            'status_sampling' => 'KH01',
                            'status' => 'pending',
                        ]);
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Data audit khusus berhasil disimpan dengan ' . count($validated['cif']) . ' CIF'
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

            $data_sampling = DataSampling::where('id_ref_sampling', $ref_sampling)->get();
            
            return view('rencana_audit.detail_rencana_audit', compact('data_sampling', 'title', 'menus'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Data tidak ditemukan');
        }
    }

    public function detail_sampling($ref_sampling, $cif)
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

            $title = 'Detail Anggota';


            $data_sampling = DataSampling::where('id_ref_sampling', $ref_sampling)
                ->where('cif', $cif)
                ->firstOrFail();

            // api CIF
            $urlCif = "http://mobcoll.nurinsani.co.id/apimobcol/data-cif.php?function=get_saldo&cif=".$cif;

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $urlCif,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 10,
            ]);

            $responseCif = curl_exec($ch);

            if (curl_errno($ch)) {
                throw new \Exception('Gagal koneksi API CIF');
            }

            curl_close($ch);

            $data_api_raw = json_decode($responseCif, true);
            $data_api = $data_api_raw['data'][0] ?? [];

            // api RMC dokumen
            $urlDokumen = "http://mobcoll.nurinsani.co.id/apimobcol/rmc.php?cif=".$cif;

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $urlDokumen,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 10,
            ]);

            $responseDokumen = curl_exec($ch);

            if (curl_errno($ch)) {
                throw new \Exception('Gagal koneksi API Dokumen');
            }

            curl_close($ch);

            $dokumen_raw = json_decode($responseDokumen, true);
            $dokumen = $dokumen_raw['data'][0] ?? [];

            // Base URL file
            $baseFile = 'http://rmc.nurinsani.co.id:9373/berkas/';

            // dd($data_api, $dokumen);

            return view('rencana_audit.detail_data_sampling', compact(
                'data_sampling',
                'data_api',
                'dokumen',
                'baseFile',
                'title',
                'menus'
            ));

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function start($id)
    {
        DB::beginTransaction();

        try {

            // Ambil rencana audit
            $audit = RencanaAudit::findOrFail($id);

            // Update status rencana audit
            $audit->update([
                'status' => 'proses'
            ]);

            // Update data sampling berdasarkan id_ref_sampling
            DataSampling::where('id_ref_sampling', $audit->id_ref_sampling)
                ->update([
                    'status' => 'proses'
                ]);

            DB::commit();

            return redirect()->back()->with('success', 'Audit berhasil dimulai');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', 'Gagal memulai audit: ' . $e->getMessage());
        }
    }
}
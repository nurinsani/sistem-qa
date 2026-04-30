<?php

namespace App\Http\Controllers\Qal;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\DataSampling;
use App\Models\Kelompok;
use App\Models\Menu;
use App\Models\RencanaAudit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

        $kelompok = Kelompok::where('code_unit', $masterQa->kode_branch)->get();

        // 1. Ambil data user yang sedang login
        $userLogin = auth()->user();
        
        // 2. Ambil code_qa dari user yang login (misal: 2220)
        $myCodeQa = $userLogin->code_qa;

        // 3. Query ambil data dari tabel users, join ke masterqa untuk filter field 'atasan'
        $qa = DB::table('users')
            ->join('masterqa', 'users.code_qa', '=', 'masterqa.code_qa')
            ->where('masterqa.atasan', $myCodeQa)
            ->select('users.id', 'users.name')
            ->get();

        return view('qal.rencana_audit.index', compact('menus', 'title', 'kelompok', 'qa'));
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
                    $status = $row->status ?? 'selesai';
                    $badges = [
                        'done' => '<span class="badge bg-success">DONE</span>',
                        'proses' => '<span class="badge bg-warning">PROSES</span>',
                        'pending' => '<span class="badge bg-secondary">PENDING</span>',
                    ];
                    return $badges[$status] ?? '<span class="badge bg-secondary">-</span>';
                })
                ->addColumn('aksi', function ($row) {

                    $detail_url = route('qal.rencana.audit.show', $row->id_ref_sampling);

                    return '
                        <a href="'.$detail_url.'" class="btn btn-sm btn-primary">Detail</a>
                    ';
                })
                ->rawColumns(['status', 'aksi'])
                ->make(true);
        }

        abort(404);
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

    public function auditKhususStore(Request $request)
    {
        $validated = $request->validate([
            'code_kel' => 'required',
            'nama_kelompok' => 'required',
            'nama_ao' => 'nullable',
            'user_id' => 'required',
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
            'user_id.required' => 'QA harus dipilih',
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
                    'status' => 'proses',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Ambil data detail untuk setiap CIF yang dipilih
                foreach ($validated['cif'] as $cif) {
                    $dataCif = DB::table('data_loan_mob')
                        ->where('cif', $cif)
                        ->where('code_kel', $validated['code_kel'])
                        ->select(
                            'unit',
                            'cif',
                            'Cust_short_name as nama',
                            'code_kel as kode_kel',
                            'cao'
                        )
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
                            'user_id' => $validated['user_id'],
                            'created_at' => now(),
                            'updated_at' => now(),
                            'status_sampling' => 'KH01',
                            'status' => 'proses',
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
            
            return view('qal.rencana_audit.detail_rencana_audit', compact('data_sampling', 'title', 'menus'));
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

            return view('qal.rencana_audit.detail_data_sampling', compact(
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
}

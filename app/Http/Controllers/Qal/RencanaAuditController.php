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

        $branch = Branch::where('code_area', $ambilArea)->get();
        $kelompok = Kelompok::where('code_unit', $masterQa->kode_branch)->get();
            
        $userLogin = auth()->user(); 
        
        $myCodeQa = $userLogin->code_qa;

        $qa = DB::table('users')
            ->join('masterqa', 'users.code_qa', '=', 'masterqa.code_qa')
            ->where('masterqa.atasan', $myCodeQa)
            ->select('users.id', 'users.name')
            ->get();

        return view('qal.rencana_audit.index', compact('menus', 'title', 'branch', 'kelompok', 'qa'));
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {

            $userCodeQa = auth()->user()->code_qa;

            // 1. Ambil daftar 'kode_unit' yang dimiliki user dari tabel masterqa
            $unitsUser = DB::table('masterqa')
                ->where('code_qa', $userCodeQa)
                ->pluck('kode_unit');

            // 2. Query RencanaAudit dengan memfilter berdasarkan unit tersebut
            // join ke tabel 'branch' untuk memastikan data yang diambil 
            // adalah yang masuk dalam area/unit yang dimiliki user
            $query = RencanaAudit::query()
                ->join('branch', 'rencana_audit.unit', '=', 'branch.kode_branch')
                ->whereIn('branch.code_area', $unitsUser) // Filter berdasarkan area yang dimiliki user
                ->select('rencana_audit.*', 'branch.area', 'branch.unit as nama_unit');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('area', function ($row) {
                    return $row->area ?? '-';
                })
                ->addColumn('unit', function ($row) {
                    return $row->nama_unit ?? '-';
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
            
            // 1. Ambil data kelompok terlebih dahulu
            $kelompok = Kelompok::where('code_kel', $kodeKel)->first();
            
            // 2. Validasi jika kelompok tidak ditemukan (letakkan di atas sebelum diakses)
            if (!$kelompok) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelompok tidak ditemukan'
                ], 404);
            }
            
            // 3. Ambil nama AO berdasarkan cao kelompok
            $namaAo = DB::table('ao')
                ->where('cao', $kelompok->cao)
                ->value('nama_ao');
            
            // 4. Ambil list CIF berdasarkan CODE_KEL (sesuaikan dengan huruf besar/kecil di DB)
            $cifList = DB::table('anggota')
                ->where('CODE_KEL', $kodeKel) // Sesuaikan dengan screenshot DB: CODE_KEL
                ->select('cif', 'CUST_SHORT_NAME')
                ->get()
                ->map(function($item) {
                    return [
                        'cif' => $item->cif,
                        'Cust_Short_name' => $item->CUST_SHORT_NAME ?? 'N/A'
                    ];
                });
            
            return response()->json([
                // Ubah format response agar sesuai dengan yang biasa ditangkap dropdown frontend (biasanya langsung array atau properti data)
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
        // Tentukan aturan validasi berdasarkan input_method
        $isManual = $request->input_method === 'manual';

        $rules = [
            'input_method'  => 'required|in:kelompok,manual',
            'user_id'       => 'required',
            'tanggal_awal'  => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ];

        if ($isManual) {
            $rules['nik']          = 'required|string';
            $rules['nama_manual']  = 'required|string';
            $rules['unit']         = 'required|string';
        } else {
            $rules['code_kel']     = 'required';
            $rules['nama_kelompok']= 'required';
            $rules['cif']          = 'required|array|min:1';
            $rules['cif.*']        = 'required|string';
        }

        $messages = [
            'nik.required'           => 'NIK harus diisi',
            'nama_manual.required'   => 'Nama harus diisi',
            'code_kel.required'      => 'Kelompok harus dipilih',
            'cif.required'           => 'CIF harus dipilih minimal 1',
            'tanggal_akhir.after_or_equal' => 'Tanggal akhir harus setelah atau sama dengan tanggal awal',
            'user_id.required'       => 'QA harus dipilih',
        ];

        $validated = $request->validate($rules, $messages);

        // Generate ID Ref Sampling
        $tanggal = Carbon::parse($request->tanggal_awal);
        $tahun   = $tanggal->format('Y');
        $bulan   = $tanggal->format('m');
        
        // Jika manual, gunakan NIK sebagai pengganti kode kelompok untuk ID Ref
        $suffix = $isManual ? $validated['nik'] : $validated['code_kel'];
        $idRefSampling = $tahun . $bulan . $suffix . str_pad(rand(1, 99), 2, '0', STR_PAD_LEFT);

        try {
            DB::transaction(function () use ($validated, $idRefSampling, $isManual, $request) {
                
                $unit = null;
                $itemsToInsert = [];

                if (!$isManual) {
                    $firstCif = $validated['cif'][0];
                    $unitData = DB::table('data_loan_mob')
                        ->where('cif', $firstCif)
                        ->where('code_kel', $validated['code_kel'])
                        ->first();

                    if (!$unitData) throw new \Exception('Data CIF tidak ditemukan di database');
                    $unit = $unitData->unit;

                    foreach ($validated['cif'] as $cif) {
                        $dataCif = DB::table('data_loan_mob')
                            ->where('cif', $cif)
                            ->where('code_kel', $validated['code_kel'])
                            ->select('unit', 'cif', 'Cust_short_name as nama', 'code_kel', 'cao')
                            ->first();

                        if ($dataCif) {
                            $itemsToInsert[] = [
                                'unit'            => $dataCif->unit,
                                'cif'             => $dataCif->cif,
                                'id_ref_sampling' => $idRefSampling,
                                'nama'            => $dataCif->nama,
                                'kode_kel'        => $dataCif->code_kel,
                                'cao'             => $dataCif->cao,
                                'jenis_audit'     => 'audit_khusus',
                                'user_id'         => $validated['user_id'],
                                'created_at'      => now(),
                                'updated_at'      => now(),
                                'status_sampling' => 'KH01',
                                'status'          => 'proses',
                            ];
                        }
                    }
                } else {
                    $unit = $validated['unit']; 
                    
                    $itemsToInsert[] = [
                        'unit'            => $validated['unit'],
                        'cif'             => $validated['nik'],
                        'id_ref_sampling' => $idRefSampling,
                        'nama'            => $validated['nama_manual'],
                        'kode_kel'        => '-',
                        'cao'             => '-',
                        'jenis_audit'     => 'audit_khusus',
                        'user_id'         => $validated['user_id'],
                        'created_at'      => now(),
                        'updated_at'      => now(),
                        'status_sampling' => 'KH01',
                        'status'          => 'proses',
                    ];
                }
                
                // Simpan ke Rencana Audit
                RencanaAudit::create([
                    'unit'            => $unit,
                    'id_ref_sampling' => $idRefSampling,
                    'tanggal_awal'    => $validated['tanggal_awal'],
                    'tanggal_akhir'   => $validated['tanggal_akhir'],
                    'jumlah_sampling' => count($itemsToInsert),
                    'status'          => 'proses',
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);

                // Simpan data sampling ke tabel data_sampling
                DB::table('data_sampling')->insert($itemsToInsert);
            });

            return response()->json([
                'success' => true,
                'message' => 'Data audit khusus berhasil disimpan'
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

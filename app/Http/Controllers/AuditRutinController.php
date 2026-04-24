<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\AuditDetail;
use App\Models\DataSampling;
use App\Models\Menu;
use App\Models\ParamKetentuan;
use App\Models\ParamProfil;
use App\Models\Tanggapan;
use App\Models\Temuan;
use App\Models\Temuanlain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuditRutinController extends Controller
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

        $title = 'Audit Rutin';

        // $data_sampling = DataSampling::where('jenis_audit', 'audit_rutin')->get();

        return view('audit_rutin.index', compact('menus', 'title'));
    }

    public function getData()
    {
        $data_sampling = DataSampling::with(['branch','kelompok','ao'])
            ->where('jenis_audit', 'audit_rutin')
            ->where('status', 'proses')
            ->get();

        return response()->json(['data' => $data_sampling]);
    }

    public function detail($id, $cif)
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

        $title = 'Detail Audit Rutin';

        $data_sampling_detail = DataSampling::with(['branch','kelompok','ao'])
            ->where('jenis_audit', 'audit_rutin')
            ->where('id', $id)
            ->first();

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
        $dokumen_api = $dokumen_raw['data'][0] ?? [];

        // Base URL file
        $baseFile = 'http://rmc.nurinsani.co.id:9373/berkas/';

        $ketentuans = ParamKetentuan::orderBy('nomor_ketentuan')->get();
        $groupedKetentuan = $ketentuans->groupBy('sub_heading');
        
        $params = ParamProfil::orderBy('kategori_param')
            ->orderBy('level', 'desc')
            ->get()
            ->groupBy('kategori_param');

        $kategoriParams = ParamProfil::select('kategori_param')
            ->distinct()
            ->pluck('kategori_param');

        $history_audit = DB::table('audit')
            ->select(
                'audit.*',
            )
            ->where('audit.cif', $cif)
            ->orderBy('audit.created_at', 'desc')
            ->get();


        return view('audit_rutin.detail', compact(
            'menus',
            'title',
            'data_sampling_detail',
            'data_api',
            'dokumen_api',
            'baseFile',
            'ketentuans',
            'groupedKetentuan',
            'params',
            'kategoriParams',
            'history_audit'
        ));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'kondisi_usaha' => 'required|string',
            'kondisi_keluarga' => 'required|string',
            'kondisi_lingkungan' => 'required|string',
            'foto_wawancara_ketua' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto_wawancara_anggota' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto_usaha' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'temuan' => 'required|string',
            'tanggal_kunjungan' => 'required|date',
        ]);

        DB::beginTransaction();

        try {

            if ($request->hasFile('foto_wawancara_ketua')) {
                $file = $request->file('foto_wawancara_ketua');
                $namaFile = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/foto_ketua'), $namaFile);
                $fotoKetua = 'uploads/foto_ketua/' . $namaFile;
            } else {
                $fotoKetua = null;
            }

            if ($request->hasFile('foto_wawancara_anggota')) {
                $file = $request->file('foto_wawancara_anggota');
                $namaFile = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/foto_anggota'), $namaFile);
                $fotoAnggota = 'uploads/foto_anggota/' . $namaFile;
            } else {
                $fotoAnggota = null;
            }

            if ($request->hasFile('foto_usaha')) {
                $file = $request->file('foto_usaha');
                $namaFile = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/foto_usaha'), $namaFile);
                $fotoUsaha = 'uploads/foto_usaha/' . $namaFile;
            } else {
                $fotoUsaha = null;
            }

            // Ambil data sampling berdasarkan CIF
            $sampling = DataSampling::where('id', $id)->firstOrFail();

            $audit = Audit::create([
                'id_ref_sampling' => $sampling->id_ref_sampling,
                'cif'         => $sampling->cif,
                'tanggal'     => now(),
                'jenis_audit' => 'audit_rutin',
                'user_id'     => Auth::id(),
            ]);

            AuditDetail::create([
                'id_audit' => $audit->id,
                'kondisi_usaha' => $request->kondisi_usaha,
                'kondisi_keluarga' => $request->kondisi_keluarga,
                'kondisi_lingkungan' => $request->kondisi_lingkungan,
                'foto_wawancara_ketua' => $fotoKetua,
                'foto_wawancara_anggota' => $fotoAnggota,
                'foto_usaha' => $fotoUsaha,
                'temuan' => $request->temuan,
                'tanggal_kunjungan' => $request->tanggal_kunjungan,
            ]);

            $sampling->update([
                'status' => 'evaluasi'
            ]);

            DB::commit();

            return redirect()
                ->route('audit.rutin.index')
                ->with('success', 'Data audit berhasil disimpan.');

        } catch (\Exception $e) {

            DB::rollBack();

             Log::error('ERROR STORE AUDIT', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function getByParam($id)
    {
        return ParamKetentuan::where('id_param_profil', $id)->get();
    }

    public function storeKetentuan(Request $request, $id_ref_sampling, $cif)
    {
        try {

            $request->validate([
                'ketentuan' => 'required|array'
            ]);

            $data = [];

            foreach ($request->ketentuan as $ketentuanId) {

                $param = ParamKetentuan::find($ketentuanId);

                if (!$param) {
                    continue;
                }

                $data[] = [
                    'id_ref_sampling' => $id_ref_sampling,
                    'id_param_profil' => $param->id_param_profil,
                    'id_ketentuan'    => $param->id,
                    'cif'             => $cif,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];
            }

            if (!empty($data)) {
                Temuan::insert($data);
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'Ketentuan berhasil disimpan'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function storeTemuanLain(Request $request, $id_ref_sampling, $cif)
    {
        foreach ($request->hasil as $paramId => $status) {

            $temuan = $request->temuan[$paramId] ?? null;

            $param = ParamKetentuan::find($paramId);

                if (!$param) {
                    continue;
                }

            // hanya simpan jika benar-benar ada input
            if (empty($status) && empty($temuan)) {
                continue;
            }

            TemuanLain::create([
                'id_ref_sampling'  => $id_ref_sampling, // pastikan dikirim dari form
                'id_param_profil'  => $paramId,
                'id_ketentuan'     => $param->id,
                'cif'              => $cif,
                'status_audit'     => $status,
                'deskripsi_temuan' => $temuan,
            ]);

            // Simpan dokumen ceklis
            if ($request->dokumen) {
                foreach ($request->dokumen as $item) {
                    DB::table('dokumen_pendukung')->insert([
                        'id_ref_sampling' => $id_ref_sampling,
                        'cif'             => $cif,
                        'status'          => $item['status'],
                        'deskripsi'       => $item['deskripsi'],
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function historyAudit($cif)
    {
        $history_audit = DB::table('audit')
            ->select(
                'audit.*',
            )
            ->where('audit.cif', $cif)
            ->orderBy('audit.created_at', 'desc')
            ->get();

        return view('audit_rutin.detail', compact('history_audit'));
    }

    public function detail_history($id, $cif)
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

        $title = 'Tanggapan Detail';

        $audit = DB::table('audit')
            ->leftJoin('audit_detail', 'audit.id', '=', 'audit_detail.id_audit')
            ->where('audit.id', $id)
            ->first();
        
        $temuanLain = DB::table('temuan_lain')
            ->leftJoin('param_profil', 'param_profil.id', '=', 'temuan_lain.id_param_profil')
            ->leftJoin('param_ketentuan', 'param_ketentuan.id', '=', 'temuan_lain.id_ketentuan')
            ->where('temuan_lain.id_ref_sampling', $audit->id_ref_sampling)
            ->where('temuan_lain.cif', $audit->cif)
            ->select(
                'temuan_lain.*',
                'param_profil.deskripsi as pertanyaan',
                'param_ketentuan.*',
            )
            ->get();

        $temuan = DB::table('temuan')
            ->leftJoin('param_profil', 'param_profil.id', '=', 'temuan.id_param_profil')
            ->leftJoin('param_ketentuan', 'param_ketentuan.id', '=', 'temuan.id_ketentuan')
            ->where('temuan.id_ref_sampling', $audit->id_ref_sampling)
            ->where('temuan.cif', $audit->cif)
            ->select(
                'temuan.*',
                'param_profil.deskripsi as pertanyaan',
                'param_ketentuan.*',
            )
            ->get();

        $tanggapan = Tanggapan::where('id_audit', $audit->id)->first();

        // api CIF
        $urlCif = "http://mobcoll.nurinsani.co.id/apimobcol/data-cif.php?function=get_saldo&cif=" . $cif;

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
        $urlDokumen = "http://mobcoll.nurinsani.co.id/apimobcol/rmc.php?cif=" . $cif;

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
        $dokumen_api = $dokumen_raw['data'][0] ?? [];

        // Base URL file
        $baseFile = 'http://rmc.nurinsani.co.id:9373/berkas/';

        return view('tanggapan.detail', compact(
            'menus',
            'title',
            'audit',
            'data_api',
            'dokumen_api',
            'baseFile',
            'temuanLain',
            'temuan',
            'tanggapan'
        ));
    }

}

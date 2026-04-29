<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\DataSampling;
use App\Models\Menu;
use App\Models\ParamKetentuan;
use App\Models\ParamProfil;
use App\Models\Tanggapan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TanggapanController extends Controller
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

        $title = 'Tanggapan';

        return view('tanggapan.index', compact('menus', 'title'));
    }

    public function getData()
    {

        $data_sampling = DataSampling::with(['branch', 'kelompok', 'ao'])
            ->leftJoin('audit', 'data_sampling.cif', '=', 'audit.cif')
            ->where('data_sampling.status', 'tanggapan')
            ->select('data_sampling.*', 'audit.id as id_audit')
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

    public function edit($id, $cif)
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

        $title = 'Tanggapan Edit';

        // $audit = DB::table('audit')
        //     ->leftJoin('audit_detail', 'audit.id', '=', 'audit_detail.id_audit')
        //     ->where('audit.id', $id)
        //     ->first();

        $audit = DB::table('audit')
        ->leftJoin('audit_detail', 'audit.id', '=', 'audit_detail.id_audit')
        ->where('audit.id', $id)
        ->select(
            'audit.*',               // Ambil semua kolom dari tabel audit
            'audit.id as id',        // Pastikan 'id' merujuk ke audit.id
            'audit_detail.*',        // Ambil kolom dari audit_detail
            'audit_detail.id as id_detail' // Alias-kan id detail agar tidak menimpa
        )
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

        return view('tanggapan.edit', compact(
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

    public function store(Request $request, $id)
    {
        $request->validate([
            'tanggapan_ao'   => 'nullable|string',
            'tanggapan_mm'   => 'nullable|string',
            'tanggapan_bm'   => 'nullable|string',
            'tindak_lanjut'  => 'nullable|string',
            'due_date'       => 'nullable|date',
        ]);

        $data = [];

        if ($request->filled('tanggapan_ao')) {
            $data['tanggapan_ao'] = $request->tanggapan_ao;
        }

        if ($request->filled('tanggapan_mm')) {
            $data['tanggapan_mm'] = $request->tanggapan_mm;
        }

        if ($request->filled('tanggapan_bm')) {
            $data['tanggapan_bm'] = $request->tanggapan_bm;
        }

        if ($request->filled('tindak_lanjut')) {
            $data['tindak_lanjut'] = $request->tindak_lanjut;
        }

        if ($request->filled('due_date')) {
            $data['due_date'] = $request->due_date;
        }

        Tanggapan::updateOrCreate(
            ['id_audit' => $id],
            $data
        );

        $audit = Audit::findOrFail($id);

        // Pastikan $audit->id_ref_sampling dikonversi ke string agar MySQL tidak melakukan kalkulasi math
DataSampling::where('id_ref_sampling', (string) $audit->id_ref_sampling)
    ->where('cif', (string) $audit->cif)
    ->update([
        'status' => 'evaluasi',
    ]);

        return redirect()->route('tanggapan.index')->with('success', 'Tanggapan berhasil disimpan');
    }

    public function update(Request $request, $id)
    {
        
        $audit = DB::table('audit')->where('id', $id)->first();
        $auditDetail = DB::table('audit_detail')->where('id_audit', $id)->first();

        if (!$audit) {
            return redirect()->back()->with('error', 'Data Audit tidak ditemukan.');
        }

        $fotos = ['foto_wawancara_anggota', 'foto_wawancara_ketua', 'foto_usaha'];
        foreach ($fotos as $foto) {
            if ($request->hasFile($foto)) {
                $file = $request->file($foto);
                $nama_file = time() . '_' . $foto . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/audit'), $nama_file);
                $dataAudit[$foto] = 'uploads/audit/' . $nama_file;
            }
        }

        DB::table('audit_detail')->where('id_audit', $id)->update([
            'kondisi_usaha'     => $request->kondisi_usaha,
            'kondisi_keluarga'  => $request->kondisi_keluarga,
            'kondisi_lingkungan'=> $request->kondisi_lingkungan,
            'updated_at'          => now(),
        ]);

        return redirect()->route('tanggapan.index')->with('success', 'Data Audit berhasil diperbarui');
    }
}

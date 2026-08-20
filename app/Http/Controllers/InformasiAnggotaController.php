<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class InformasiAnggotaController extends Controller
{
    public function index(Request $request)
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

        $title = 'QA System';

        $cif = $request->cif;

        return view('informasi_anggota.informasi_anggota', compact('title', 'menus', 'cif'));
    }

    public function informasi_anggota($cif)
    {
        $roleId = Auth::user()->role_id ?? null;

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

        $title = 'QA System';

        //API Cif
        $url = "http://mobcoll.nurinsani.co.id/apimobcol/data-cif.php?function=get_saldo&cif=" . $cif;

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);

        $responseCif = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception('API Cif gagal terkoneksi');
        }

        curl_close($ch);

        $dataCifRaw = json_decode($responseCif, true);
        $dataCif = $dataCifRaw['data'][0] ?? [];

        $dataLocal = DB::table('data_loan_mob')
            ->where('cif', $cif)
            ->first();

        $kelompok = DB::table('kelompok')
            ->where('code_kel', $dataLocal->code_kel ?? null)
            ->value('nama_kel');

        $ao = DB::table('ao')
            ->where('cao', $dataLocal->cao ?? null)
            ->value('nama_ao');

        $loanWo = DB::table('loan_wo')
            ->where('cif', $cif)
            ->first();

        // gabungkan
        $dataCif['nama_kelompok'] = $kelompok ?? '-';
        $dataCif['nama_ao'] = $ao ?? '-';
        $dataCif['is_wo'] = !empty($loanWo);
        $dataCif['status_wo'] = $loanWo ? 'Pernah WO' : 'Tidak Pernah WO';
        $dataCif['saldo_wo'] = $loanWo->os ?? 0;
        $dataCif['data_wo'] = $loanWo;

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Response API bukan JSON valid');
        }

        // dd($dataCif);

        //API Dokumenlain
        $urlDokumen = "http://mobcoll.nurinsani.co.id/apimobcol/rmc.php?cif=" . $cif;

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $urlDokumen,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);

        $responseDokumen = curl_exec($ch);


        if (curl_errno($ch)) {
            throw new \Exception('API Dokumen gagal terkoneksi');
        }

        curl_close($ch);

        // decode JSON
        $dataDokumenRaw = json_decode($responseDokumen, true);

        // cek JSON valid
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Response API bukan JSON valid');
        }

        // ambil data
        $dataDokumen = $dataDokumenRaw['data'][0] ?? [];
        $linkRmc = 'http://rmc.nurinsani.co.id:8474/berkas/';

        //dd($dataDokumen);

        return view('informasi_anggota.informasi_anggota_detail', compact('title', 'menus', 'dataCif', 'dataDokumen', 'linkRmc'));
    }


    public function printMutasi($cif)
    {
        // ambil semua data (TANPA pagination)
        $urlMutasiPrint = "http://mobcoll.nurinsani.co.id/apimobcol/data.php?function=get_saldo_mutasi&cif=" . $cif;

        $response = file_get_contents($urlMutasiPrint);
        $dataMutasiRaw = json_decode($response, true);

        $mutasi = $dataMutasiRaw['data'] ?? [];

        // // ambil data CIF juga (biar header tetap ada)
        $urlCifMutasi = "http://mobcoll.nurinsani.co.id/apimobcol/data-cif.php?function=get_saldo&cif=" . $cif;
        $responseCif = file_get_contents($urlCifMutasi);
        $dataCifRaw = json_decode($responseCif, true);
        $dataCif = $dataCifRaw['data'][0] ?? [];

        $pdf = Pdf::loadView('informasi_anggota.cetak_mutasi_anggota', compact('mutasi', 'dataCif'));

        return $pdf->stream('Mutasi_Anggota' . $cif . '.pdf');
    }

    public function search(Request $request)
    {
        $q = $request->q;

        $data = DB::table('anggota as a')
            ->join('kelompok as k', 'a.code_kel', '=', 'k.code_kel')
            ->where(function ($query) use ($q) {
                $query->where('a.cif', 'like', "%{$q}%")
                    ->orWhere('a.cust_short_name', 'like', "%{$q}%");
            })
            ->select('a.cif', 'a.cust_short_name', 'k.nama_kel')
            ->get();

        return response()->json($data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

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
        $linkRmc = 'http://rmc.nurinsani.co.id:9373/berkas/';

        //dd($dataDokumen);

        return view('informasi_anggota.informasi_anggota_detail', compact('title', 'menus', 'dataCif', 'dataDokumen', 'linkRmc'));
    }
    public function mutasi_anggota($cif)
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

        // API Mutasi
        $urlMutasi = "http://mobcoll.nurinsani.co.id/apimobcol/data.php?function=get_saldo_mutasi&cif=" . $cif;

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $urlMutasi,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);

        $responseMutasi = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception('API Mutasi gagal terkoneksi');
        }

        curl_close($ch);

        $dataMutasiRaw = json_decode($responseMutasi, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Response API Mutasi bukan JSON valid');
        }

        $mutasiData = $dataMutasiRaw['data'] ?? [];

        $perPage = 25;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $currentItems = array_slice($mutasiData, ($currentPage - 1) * $perPage, $perPage);

        $mutasi = new LengthAwarePaginator(
            $currentItems,
            count($mutasiData), // ✅ pakai data asli
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'query' => request()->query()
            ]
        );

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


        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Response API bukan JSON valid');
        }

        return view(
            'informasi_anggota.mutasi_anggota',
            compact('title', 'menus', 'dataCif', 'mutasi')
        );
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

    return view('informasi_anggota.cetak_mutasi_anggota', compact('mutasi', 'dataCif'));
}


    public function searchAnggota(Request $request)
    {
        $term = $request->q;

        $data = DB::table('anggota as a')
            ->join('kelompok as k', 'a.code_kel', '=', 'k.id')
            ->select(
                'a.cif',
                'a.nama',
                'k.nama_kel'
            )
            ->where('a.nama', 'LIKE', '%' . $term . '%')
            ->limit(10)
            ->get();

        return response()->json($data);
    }
}

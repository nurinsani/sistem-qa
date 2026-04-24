<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\DataSampling;
use App\Models\Menu;
use App\Models\Tanggapan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QamController extends Controller
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

        $title = 'Dashboard';

        Carbon::setLocale('id');

        $year = now()->year;
        $dataBulanan = [];

        for ($bulan = 1; $bulan <= 12; $bulan++) {

            // hitung total data
            $total = DataSampling::whereMonth('created_at', $bulan)
                ->whereYear('created_at', $year)
                ->count();

            // hitung data yang sudah selesai (CURRENT)
            $selesai = DataSampling::whereMonth('created_at', $bulan)
                ->whereYear('created_at', $year)
                ->where('status', 'selesai')
                ->count();

            $dataBulanan[] = [
                'bulan'   => Carbon::create()->month($bulan)->translatedFormat('F'),
                'total'   => $total,
                'selesai' => $selesai,
            ];
        }

        return view('qam.dashboard', compact('title', 'menus', 'dataBulanan'));
    }

    public function detail(Request $request)
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

        $title = 'Detail Dashboard';

        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun', now()->year);

        $namaBulan = \Carbon\Carbon::create()->month((int)$bulan)->locale('id')->translatedFormat('F');

    $auditsGrouped = DataSampling::with('qa')
        ->whereMonth('created_at', $bulan)
        ->whereYear('created_at', $tahun)
        ->get()
        ->groupBy('user_id');

        return view('qam.dashboard.detail', compact('title', 'menus', 'auditsGrouped', 'bulan', 'tahun', 'namaBulan'));
    }

    public function detailByQa(Request $request, $user_id)
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

        $title = 'Detail Dashboard';

        $bulan = $request->query('bulan');
        $year = now()->year;

        $audits = Audit::with('dataSampling.ao') // Load data sampling dan relasi di dalamnya (misal: ao)
        ->whereHas('dataSampling.qa', function($query) use ($user_id) {
            $query->where('id', $user_id);
        })
        ->whereMonth('created_at', $bulan)
        ->whereYear('created_at', $year)
        ->get();

    //     $audits = DataSampling::with(['ao', 'qa'])
    // ->where('user_id', $user_id)
    // ->whereMonth('created_at', $bulan)
    // ->whereYear('created_at', $year)
    // ->get();

        return view('qam.dashboard.detail_by_qa', [
            'audits' => $audits,
            'qaEmail' => $user_id,
            'bulan' => $bulan,
            'title' => $title,
            'menus' => $menus,
        ]);
    }

    public function detailAudit($id, $cif)
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

        $title = 'Detail Audit';

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

        return view('qam.dashboard.detail_audit', compact(
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

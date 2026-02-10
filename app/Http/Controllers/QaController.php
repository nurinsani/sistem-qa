<?php

namespace App\Http\Controllers;

use App\Models\DataSampling;
use App\Models\Menu;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class QaController extends Controller
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
                ->where('status', 'done')
                ->count();

            $dataBulanan[] = [
                'bulan'   => Carbon::create()->month($bulan)->translatedFormat('F'),
                'total'   => $total,
                'selesai' => $selesai,
            ];
        }

        return view('qa.dashboard', compact('title', 'menus', 'dataBulanan'));
    }
}

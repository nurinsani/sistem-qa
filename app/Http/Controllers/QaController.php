<?php

namespace App\Http\Controllers;

use App\Models\DataSampling;
use App\Models\Menu;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class QaController extends Controller
{
    public function index()
    {        $roleId = Auth::user()->role_id;

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
                ->where('user_id', Auth::id())
                ->count();

            // hitung data yang sudah selesai (CURRENT)
            $selesai = DataSampling::whereMonth('created_at', $bulan)
                ->whereYear('created_at', $year)
                ->where('status', 'selesai')
                ->where('user_id', Auth::id())
                ->count();

            $dataBulanan[] = [
                'bulan'   => Carbon::create()->month($bulan)->translatedFormat('F'),
                'total'   => $total,
                'selesai' => $selesai,
            ];
        }

        return view('qa.dashboard', compact('title', 'menus', 'dataBulanan'));
    }

    public function detail(Request $request)
    {
        if ($request->ajax()) {
            $query = DataSampling::where('user_id', Auth::id());

            if ($request->filled('bulan')) {
                $query->whereMonth('created_at', $request->bulan)
                      ->whereYear('created_at', now()->year);
            }

            if ($request->filled('tanggal')) {
                $query->whereDate('created_at', $request->tanggal);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d') : '-';
                })
                ->make(true);
        }

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

        $bulan = $request->query('bulan');
        $namaBulan = $bulan ? \Carbon\Carbon::create()->month((int)$bulan)->locale('id')->translatedFormat('F') : '';
        
        $title = 'Detail Dashboard';

        return view('qa.dashboard-detail', compact('title', 'menus', 'bulan', 'namaBulan'));
    }
}

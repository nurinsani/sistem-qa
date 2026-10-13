<?php

namespace App\Http\Controllers\RekapMobcol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BuktiSetorExport;

class BuktiSetorController extends Controller
{
    /**
     * Cek apakah waktu saat ini berada dalam jam akses 12.00 - 14.00 WIB
     */
    private function isAccessAllowed(): bool
    {
        $now = now('Asia/Jakarta');
        $start = $now->copy()->setTime(12, 0, 0);
        $end = $now->copy()->setTime(14, 0, 0);

        return $now->between($start, $end);
    }

    public function index(Request $request)
    {
        $isAllowed = $this->isAccessAllowed();
        $currentTime = now('Asia/Jakarta')->format('H:i');

        if (!$isAllowed) {
            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Menu Bukti Setor hanya dapat diakses antara pukul 12.00 s/d 14.00 WIB.',
                    'data' => []
                ], 403);
            }

            $roleId = \Illuminate\Support\Facades\Auth::user()->role_id;
            $menus = \App\Models\Menu::whereNull('parent_id')
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

            return view('rekap-mobcol.bukti-setor.index', [
                'title' => 'Bukti Setor',
                'menus' => $menus,
                'aoList' => collect([]),
                'isAllowed' => false,
                'currentTime' => $currentTime
            ]);
        }

        if ($request->ajax()) {
            $query = DB::table('bukti_setor')
                ->leftJoin('ao', 'bukti_setor.cao', '=', 'ao.cao')
                ->select([
                    'bukti_setor.id',
                    'bukti_setor.tgl_setor',
                    'bukti_setor.cao',
                    'ao.nama_ao',
                    'bukti_setor.bank',
                    'bukti_setor.name as nominal', // name diubah alias jadi nominal
                    'bukti_setor.image'
                ]);

            $tanggal = $request->filled('tanggal') ? $request->tanggal : date('Y-m-d', strtotime('-1 day'));
            $query->whereDate('bukti_setor.tgl_setor', $tanggal);

            if ($request->filled('cao')) {
                $query->where('bukti_setor.cao', $request->cao);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('nominal', function ($row) {
                    return number_format($row->nominal, 0, ',', '.');
                })
                ->make(true);
        }

        $roleId = \Illuminate\Support\Facades\Auth::user()->role_id;
        $menus = \App\Models\Menu::whereNull('parent_id')
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

        $aoList = DB::table('ao')
            ->select('cao', 'nama_ao')
            ->orderBy('nama_ao')
            ->get();

        return view('rekap-mobcol.bukti-setor.index', [
            'title' => 'Bukti Setor',
            'menus' => $menus,
            'aoList' => $aoList,
            'isAllowed' => true,
            'currentTime' => $currentTime
        ]);
    }

    public function export(Request $request)
    {
        if (!$this->isAccessAllowed()) {
            return redirect()->route('rekap-mobcol.bukti-setor.index')
                ->with('error', 'Export Bukti Setor hanya dapat dilakukan antara pukul 12.00 s/d 14.00 WIB.');
        }

        return Excel::download(new BuktiSetorExport($request->tanggal, $request->cao), 'rekap_bukti_setor.xlsx');
    }
}

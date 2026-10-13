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
    public function index(Request $request)
    {
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
            'aoList' => $aoList
        ]);
    }

    public function export(Request $request)
    {
        return Excel::download(new BuktiSetorExport($request->tanggal, $request->cao), 'rekap_bukti_setor.xlsx');
    }
}

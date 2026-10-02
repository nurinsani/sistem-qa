<?php

namespace App\Http\Controllers\RekapMobcol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CsExport;

class CsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('cs')
                ->leftJoin('kelompok', 'cs.kode_kel', '=', 'kelompok.code_kel')
                ->select('cs.*');

            if ($request->filled('tanggal')) {
                $query->whereDate('cs.tgl_tagih', $request->tanggal);
            }

            if ($request->filled('kode_branch')) {
                $query->where('kelompok.code_unit', $request->kode_branch);
            }

            return DataTables::of($query)
                ->addIndexColumn()
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

        $units = DB::table('branch')
            ->join('kelompok', 'branch.kode_branch', '=', 'kelompok.code_unit')
            ->join('cs', 'kelompok.code_kel', '=', 'cs.kode_kel')
            ->select('branch.kode_branch', 'branch.unit')
            ->distinct()
            ->orderBy('branch.unit')
            ->get();

        return view('rekap-mobcol.cs.index', [
            'title' => 'Rekap CS',
            'menus' => $menus,
            'units' => $units
        ]);
    }

    public function export(Request $request)
    {
        return Excel::download(new CsExport($request->tanggal, $request->kode_branch), 'rekap_cs.xlsx');
    }
}

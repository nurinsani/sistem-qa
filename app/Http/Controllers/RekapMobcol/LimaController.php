<?php

namespace App\Http\Controllers\RekapMobcol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LimaExport;

class LimaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('lima')
                ->leftJoin('data_loan_report', function ($join) {
                    $join->on('lima.cif', '=', DB::raw("REPLACE(data_loan_report.cif, '''', '')"));
                })
                ->leftJoin('kelompok', function ($join) {
                    $join->on('kelompok.code_kel', '=', DB::raw("REPLACE(data_loan_report.code_kel, '''', '')"));
                })
                ->leftJoin('ao', 'lima.cao', '=', 'ao.cao')
                ->select([
                    'lima.tgl_tagih',
                    'kelompok.nama_kel',
                    'kelompok.code_kel',
                    'ao.nama_ao',
                    'lima.cif',
                    'data_loan_report.nama',
                    'data_loan_report.Plafond',
                    'data_loan_report.Os',
                    'data_loan_report.twm',
                    'data_loan_report.pokok',
                    'data_loan_report.sim_wajib',
                    'data_loan_report.run_tenor',
                    'data_loan_report.tenor',
                    'data_loan_report.angsuran',
                    'lima.nominal as nyata_setor',
                    DB::raw('0 as omzet'),
                    'lima.status_trans'
                ]);

            if ($request->filled('tanggal')) {
                $query->whereDate('lima.tgl_tagih', $request->tanggal);
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
            ->select('kode_branch', 'unit')
            ->distinct()
            ->orderBy('unit')
            ->get();

        return view('rekap-mobcol.lima.index', [
            'title' => 'Lima Persen',
            'menus' => $menus,
            'units' => $units
        ]);
    }

    public function export(Request $request)
    {
        return Excel::download(new LimaExport($request->tanggal, $request->kode_branch), 'rekap_lima_persen.xlsx');
    }
}

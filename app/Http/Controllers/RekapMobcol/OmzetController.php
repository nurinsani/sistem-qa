<?php

namespace App\Http\Controllers\RekapMobcol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OmzetExport;

class OmzetController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('omzet_mob')
                ->leftJoin('data_loan_report', function($join) {
                    $join->on('omzet_mob.cif', '=', DB::raw("REPLACE(data_loan_report.cif, '''', '')"));
                })
                ->leftJoin('kelompok', function($join) {
                    $join->on('kelompok.code_kel', '=', DB::raw("REPLACE(data_loan_report.code_kel, '''', '')"));
                })
                ->leftJoin('ao', 'omzet_mob.cao', '=', 'ao.cao')
                ->select([
                    'omzet_mob.tgl_tagih',
                    'kelompok.nama_kel',
                    'kelompok.code_kel',
                    'ao.nama_ao',
                    'omzet_mob.cif',
                    'data_loan_report.nama',
                    'data_loan_report.Plafond',
                    'data_loan_report.Os',
                    'data_loan_report.twm',
                    'data_loan_report.pokok',
                    'data_loan_report.sim_wajib',
                    'data_loan_report.run_tenor',
                    'data_loan_report.tenor',
                    'data_loan_report.angsuran',
                    'data_loan_report.nyata_setor',
                    'omzet_mob.omzet',
                    'omzet_mob.status_trans'
                ]);

            if ($request->filled('tanggal')) {
                $query->whereDate('omzet_mob.tgl_tagih', $request->tanggal);
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

        return view('rekap-mobcol.omzet.index', [
            'title' => 'Rekap Omzet',
            'menus' => $menus,
            'units' => $units
        ]);
    }

    public function export(Request $request)
    {
        return Excel::download(new OmzetExport($request->tanggal, $request->kode_branch), 'rekap_omzet.xlsx');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TunggakanController extends Controller
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

        $title = 'Data Tunggakan';

        // Filter Unit options
        $units = DB::table('tunggakan_eom')
            ->leftJoin('branch', 'tunggakan_eom.unit', '=', 'branch.kode_branch')
            ->select('tunggakan_eom.unit as kode_unit', 'branch.unit as nama_unit')
            ->distinct()
            ->orderBy('tunggakan_eom.unit')
            ->get();

        return view('tunggakan.index', compact('menus', 'title', 'units'));
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('tunggakan_eom')
                ->leftJoin('branch', 'tunggakan_eom.unit', '=', 'branch.kode_branch')
                ->leftJoin('kelompok', 'tunggakan_eom.code_kel', '=', 'kelompok.code_kel')
                ->leftJoin('ao', 'tunggakan_eom.cao', '=', 'ao.cao')
                ->select([
                    'tunggakan_eom.BUSS_DATE',
                    'tunggakan_eom.unit',
                    'branch.unit as nama_unit',
                    'tunggakan_eom.cif',
                    'tunggakan_eom.Cust_Short_name',
                    'tunggakan_eom.os',
                    'tunggakan_eom.saldo_margin',
                    'tunggakan_eom.ft',
                    'tunggakan_eom.nominal',
                    'tunggakan_eom.angsuran',
                    'tunggakan_eom.code_kel',
                    'kelompok.nama_kel',
                    'tunggakan_eom.cao',
                    'ao.nama_ao',
                    'tunggakan_eom.twm',
                    'tunggakan_eom.bulat',
                    'tunggakan_eom.plafond',
                ]);

            if ($request->filled('unit')) {
                $query->where('tunggakan_eom.unit', $request->unit);
            }

            if ($request->filled('buss_date')) {
                $query->whereDate('tunggakan_eom.BUSS_DATE', $request->buss_date);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('BUSS_DATE', function ($row) {
                    return $row->BUSS_DATE ? date('d-m-Y', strtotime($row->BUSS_DATE)) : '-';
                })
                ->editColumn('unit', function ($row) {
                    return $row->nama_unit ? $row->unit . ' - ' . $row->nama_unit : $row->unit;
                })
                ->editColumn('nama_kel', function ($row) {
                    return $row->nama_kel;
                })
                ->editColumn('nama_ao', function ($row) {
                    return $row->nama_ao;
                })
                ->editColumn('os', function ($row) {
                    return 'Rp ' . number_format($row->os, 0, ',', '.');
                })
                ->editColumn('saldo_margin', function ($row) {
                    return 'Rp ' . number_format($row->saldo_margin, 0, ',', '.');
                })
                ->editColumn('nominal', function ($row) {
                    return 'Rp ' . number_format($row->nominal, 0, ',', '.');
                })
                ->editColumn('angsuran', function ($row) {
                    return 'Rp ' . number_format($row->angsuran, 0, ',', '.');
                })
                ->editColumn('plafond', function ($row) {
                    return 'Rp ' . number_format($row->plafond ?? 0, 0, ',', '.');
                })
                ->editColumn('ft', function ($row) {
                    $badge = $row->ft > 6 ? 'badge-danger' : ($row->ft > 2 ? 'badge-warning' : 'badge-info');
                    return '<span class="badge ' . $badge . '">' . $row->ft . ' FT</span>';
                })
                ->rawColumns(['ft'])
                ->make(true);
        }
    }
}

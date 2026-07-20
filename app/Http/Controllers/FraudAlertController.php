<?php

namespace App\Http\Controllers;

use App\Exports\FraudAlertExport;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class FraudAlertController extends Controller
{
    public function qal(Request $request)
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

        $title = 'Fraud Alert';

        $data = collect(); // default kosong

        // hanya jalan kalau ada filter
        if ($request->filled('tgl_tagih')) {

            $loan = DB::table('data_loan_mob')
                ->select(['cif', DB::raw('SUM(os) as total_os')])
                ->groupBy('cif');

            $query = DB::table('fraud_alerts as fa')
                ->leftJoinSub($loan, 'l', function ($join) {
                    $join->on('fa.cif', '=', 'l.cif');
                })
                ->select([
                    'fa.tgl_tagih',
                    'fa.flag_status',
                    'fa.flag_reason',
                    DB::raw('COUNT(fa.cif) as jumlah_noa'),
                    DB::raw('SUM(l.total_os) as total_os')
                ])
                ->whereDate('fa.tgl_tagih', $request->tgl_tagih)
                ->groupBy(
                    'fa.tgl_tagih',
                    'fa.flag_status',
                    'fa.flag_reason'
                )
                ->orderBy('fa.flag_reason');

            $data = $query->get();
        }

        return view('qal.fraud_alert.index', compact('title', 'menus', 'data'));
    }

    public function qam(Request $request)
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

        $title = 'Fraud Alert';

        $data = collect(); // default kosong

        // hanya jalan kalau ada filter
        if ($request->filled('tgl_tagih')) {

            $loan = DB::table('data_loan_mob')
                ->select(['cif', DB::raw('SUM(os) as total_os')])
                ->groupBy('cif');

            $query = DB::table('fraud_alerts as fa')
                ->leftJoinSub($loan, 'l', function ($join) {
                    $join->on('fa.cif', '=', 'l.cif');
                })
                ->select([
                    'fa.tgl_tagih',
                    'fa.flag_status',
                    'fa.flag_reason',
                    DB::raw('COUNT(fa.cif) as jumlah_noa'),
                    DB::raw('SUM(l.total_os) as total_os')
                ])
                ->whereDate('fa.tgl_tagih', $request->tgl_tagih)
                ->groupBy(
                    'fa.tgl_tagih',
                    'fa.flag_status',
                    'fa.flag_reason'
                )
                ->orderBy('fa.flag_reason');

            $data = $query->get();
        }

        return view('qam.fraud_alert.index', compact('title', 'menus', 'data'));
    }

    public function export(Request $request)
    {
        return Excel::download(
            new FraudAlertExport(
                $request->tgl_tagih,
                $request->flag_status,
                $request->flag_reason
            ),
            'fraud_alert.xlsx'
        );
    }
}
<?php

namespace App\Http\Controllers\RekapMobcol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PelunasanExport;

class PelunasanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $url = 'http://mobcoll.nurinsani.co.id/apimobcol/data_report_mobcoll.php?function=pelunasan';

            if ($request->filled('kode_branch')) {
                $url .= '&unit=' . urlencode($request->kode_branch);
            }

            if ($request->filled('tanggal')) {
                $url .= '&tgl_tagih=' . urlencode($request->tanggal);
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);

            $data = [];
            if (!$error && $response) {
                $decoded = json_decode($response, true);
                if (is_array($decoded) && isset($decoded['data']) && is_array($decoded['data'])) {
                    $data = $decoded['data'];
                } elseif (is_array($decoded)) {
                    $data = $decoded;
                }
            }

            return DataTables::of($data)
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

        return view('rekap-mobcol.pelunasan.index', [
            'title' => 'Pelunasan',
            'menus' => $menus,
            'units' => $units
        ]);
    }

    public function export(Request $request)
    {
        return Excel::download(new PelunasanExport($request->tanggal, $request->kode_branch), 'rekap_pelunasan.xlsx');
    }
}

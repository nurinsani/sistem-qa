<?php

namespace App\Http\Controllers;

use App\Exports\LaporanAuditExport;
use App\Models\Ao;
use App\Models\Branch;
use App\Models\DataSampling;
use App\Models\Menu;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
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

        $title = 'Laporan Audit';

        // 1. Ambil daftar 'kode_unit' (code_area) yang dipegang user
        $userCodeQa = auth()->user()->code_qa;
        $unitsUser = DB::table('masterqa')
            ->where('code_qa', $userCodeQa)
            ->pluck('kode_unit'); // Hasilnya [1101, 1102, dst]

        // 2. Gunakan query yang clean
        $query = DataSampling::with(['branch', 'kelompok', 'ao'])
            // Join ke branch untuk memfilter berdasarkan code_area
            ->join('branch', 'data_sampling.unit', '=', 'branch.kode_branch')
            // Filter berdasarkan area yang dimiliki user
            ->whereIn('branch.code_area', $unitsUser)
            // Filter status
            ->where('data_sampling.status', 'selesai')
            // Pastikan select spesifik agar kolom tidak bentrok (karena join)
            ->select('data_sampling.*'); 

        // Eksekusi
        $data = $query->get();

        if ($request->filled('tgl_awal')) {
            $query->whereDate('created_at','>=',$request->tgl_awal);
        }

        if ($request->filled('tgl_akhir')) {
            $query->whereDate('created_at','<=',$request->tgl_akhir);
        }

        if ($request->filled('jenis_audit')) {
            $query->where('jenis_audit',$request->jenis_audit);
        }

        $data = $request->hasAny(['tgl_awal','tgl_akhir'])
            ? $query->get()
            : collect();

        return view('qal.laporan.index',compact(
            'title',
            'data',
            'menus'
        ));
    }

    public function pdf($id)
    {

    $data = DB::table('data_sampling')
        ->leftJoin('audit', 'data_sampling.id_ref_sampling', '=', 'audit.id_ref_sampling')
        ->leftJoin('audit_detail', 'audit.id', '=', 'audit_detail.id_audit')
        ->leftJoin('tanggapan', 'audit.id', '=', 'tanggapan.id_audit')
        ->leftJoin('branch', 'data_sampling.unit', '=', 'branch.kode_branch')
        ->leftJoin('kelompok', 'data_sampling.kode_kel', '=', 'kelompok.code_kel')
        ->leftJoin('ao', 'data_sampling.cao', '=', 'ao.cao')
        ->leftJoin('users', 'audit.user_id', '=', 'users.id')
        ->where('data_sampling.id', $id)
        ->select(
            'data_sampling.*',
            'branch.unit',
            'branch.area',
            'kelompok.nama_kel',
            'ao.nama_ao',
            'audit.*',
            'audit_detail.*',
            'tanggapan.*',
            'users.name as nama_user',
        )
        ->first();

    $temuan = DB::table('temuan')
        ->leftJoin('param_ketentuan', 'temuan.id_ketentuan', '=', 'param_ketentuan.id')
        ->where('temuan.id_ref_sampling', $data->id_ref_sampling)
        ->where('temuan.cif', $data->cif)
        ->select(
            'temuan.*',
            'param_ketentuan.*'
        )
        ->get();

        $renderer = new ImageRenderer(
            new RendererStyle(80),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);

        $ao = "Nama AO : ".$data->nama_ao."\nUnit : ".$data->unit;
        $qr_ao = base64_encode($writer->writeString($ao));

        $qa = "Nama QA : ".$data->nama_user."\nUnit : ".$data->unit;
        $qr_qa = base64_encode($writer->writeString($qa));

        $pdf = Pdf::loadView('laporan.pdf', compact('data', 'temuan', 'qr_ao', 'qr_qa'))
                ->setPaper('F4','landscape')
                ->setOptions(['isRemoteEnabled' => true]);

        $nama_file = 'Laporan-Audit-'.$data->cif.'.pdf';

        return $pdf->stream($nama_file);
    }

    public function export_excel()
    {
        return Excel::download(new LaporanAuditExport, 'laporan-audit.xlsx');
    }
}

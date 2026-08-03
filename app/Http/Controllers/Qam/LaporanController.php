<?php

namespace App\Http\Controllers\Qam;

use App\Exports\LaporanAuditExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataSampling;
use App\Models\Menu;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
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

        $userCodeQa = Auth::user()->code_qa;
        $bawahanDirect = DB::table('masterqa')
            ->where('atasan', $userCodeQa)
            ->pluck('code_qa');

        $unitsUser = DB::table('masterqa')
            ->where('code_qa', $userCodeQa)
            ->orWhere('atasan', $userCodeQa)
            ->orWhereIn('atasan', $bawahanDirect)
            ->pluck('kode_unit')
            ->unique()
            ->toArray();

        $baseQuery = DataSampling::with(['branch', 'kelompok', 'ao', 'qa'])
            ->join('branch', 'data_sampling.unit', '=', 'branch.kode_branch')
            ->leftJoin('users', 'data_sampling.user_id', '=', 'users.id')
            ->whereIn('branch.code_area', $unitsUser)
            ->where('data_sampling.status', 'selesai')
            ->select('data_sampling.*', 'users.name as nama_petugas');

        $rekapData = collect();
        $selectedUserId = $request->get('user_id');

        if ($request->hasAny(['tgl_awal', 'tgl_akhir'])) {
            $query = clone $baseQuery;

            if ($request->filled('tgl_awal')) {
                $query->whereDate('data_sampling.updated_at', '>=', $request->tgl_awal);
            }

            if ($request->filled('tgl_akhir')) {
                $query->whereDate('data_sampling.updated_at', '<=', $request->tgl_akhir);
            }

            if ($request->filled('jenis_audit')) {
                $query->where('data_sampling.jenis_audit', $request->jenis_audit);
            }

            $data = $query->get();
        } elseif ($request->hasAny(['rekap_tgl_awal', 'rekap_tgl_akhir']) || $request->filled('periode')) {
            if ($request->filled('rekap_tgl_awal') || $request->filled('rekap_tgl_akhir')) {
                $tanggalMulai = $request->filled('rekap_tgl_awal') ? Carbon::parse($request->rekap_tgl_awal)->startOfDay() : null;
                $tanggalSelesai = $request->filled('rekap_tgl_akhir') ? Carbon::parse($request->rekap_tgl_akhir)->endOfDay() : null;
            } else {
                $jumlahHari = (int) $request->periode;
                $tanggalMulai = Carbon::now()->subDays($jumlahHari - 1)->startOfDay();
                $tanggalSelesai = Carbon::now()->endOfDay();
            }

            // Ambil data rekap dikelompokkan berdasarkan user_id
            $rekapQuery = DataSampling::join('branch', 'data_sampling.unit', '=', 'branch.kode_branch')
                ->leftJoin('users', 'data_sampling.user_id', '=', 'users.id')
                ->leftJoin('masterqa as user_mq', 'users.code_qa', '=', 'user_mq.code_qa')
                ->leftJoin('masterqa as atasan_mq', 'user_mq.atasan', '=', 'atasan_mq.code_qa')
                ->whereIn('branch.code_area', $unitsUser)
                ->whereIn('data_sampling.status', ['proses', 'pending', 'tanggapan', 'evaluasi', 'selesai']);

            if ($tanggalMulai && $tanggalSelesai) {
                $rekapQuery->whereBetween('data_sampling.updated_at', [$tanggalMulai, $tanggalSelesai]);
            } elseif ($tanggalMulai) {
                $rekapQuery->where('data_sampling.updated_at', '>=', $tanggalMulai);
            } elseif ($tanggalSelesai) {
                $rekapQuery->where('data_sampling.updated_at', '<=', $tanggalSelesai);
            }

            $rekapData = $rekapQuery->select(
                'data_sampling.user_id',
                'users.name as nama_user',
                'atasan_mq.nama_qa as nama_atasan',
                DB::raw("SUM(CASE WHEN data_sampling.status IN ('proses', 'pending') THEN 1 ELSE 0 END) as total_proses"),
                DB::raw("SUM(CASE WHEN data_sampling.status = 'tanggapan' THEN 1 ELSE 0 END) as total_tanggapan"),
                DB::raw("SUM(CASE WHEN data_sampling.status = 'evaluasi' THEN 1 ELSE 0 END) as total_evaluasi"),
                DB::raw("SUM(CASE WHEN data_sampling.status = 'selesai' THEN 1 ELSE 0 END) as total_selesai"),
                DB::raw('COUNT(data_sampling.id) as total_sampling')
            )
                ->groupBy('data_sampling.user_id', 'users.name', 'atasan_mq.nama_qa')
                ->get();

            // Query data sampling sesuai periode rekap
            $dataQuery = DataSampling::with(['branch', 'kelompok', 'ao', 'qa'])
                ->join('branch', 'data_sampling.unit', '=', 'branch.kode_branch')
                ->leftJoin('users', 'data_sampling.user_id', '=', 'users.id')
                ->whereIn('branch.code_area', $unitsUser)
                ->whereIn('data_sampling.status', ['proses', 'pending', 'tanggapan', 'evaluasi', 'selesai'])
                ->select('data_sampling.*', 'users.name as nama_petugas');
            if ($tanggalMulai && $tanggalSelesai) {
                $dataQuery->whereBetween('data_sampling.updated_at', [$tanggalMulai, $tanggalSelesai]);
            } elseif ($tanggalMulai) {
                $dataQuery->where('data_sampling.updated_at', '>=', $tanggalMulai);
            } elseif ($tanggalSelesai) {
                $dataQuery->where('data_sampling.updated_at', '<=', $tanggalSelesai);
            }

            // Jika user_id diklik, filter data sampling berdasarkan user_id tersebut
            if ($selectedUserId) {
                $dataQuery->where('data_sampling.user_id', $selectedUserId);
            }

            $data = $dataQuery->get();
        } else {
            $data = collect();
        }

        return view('qam.laporan.index', compact(
            'title',
            'data',
            'rekapData',
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

        $ao = "Nama AO : " . $data->nama_ao . "\nUnit : " . $data->unit;
        $qr_ao = base64_encode($writer->writeString($ao));

        $qa = "Nama QA : " . $data->nama_user . "\nUnit : " . $data->unit;
        $qr_qa = base64_encode($writer->writeString($qa));

        $pdf = Pdf::loadView('qam.laporan.pdf', compact('data', 'temuan', 'qr_ao', 'qr_qa'))
            ->setPaper('F4', 'landscape')
            ->setOptions(['isRemoteEnabled' => true]);

        $nama_file = 'Laporan-Audit-' . $data->cif . '.pdf';

        return $pdf->stream($nama_file);
    }

    public function export_excel()
    {
        return Excel::download(new LaporanAuditExport, 'laporan-audit.xlsx');
    }
}

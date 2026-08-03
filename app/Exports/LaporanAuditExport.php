<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanAuditExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request = null)
    {
        $this->request = $request ?? request();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
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

        $query = DB::table('data_sampling')
            ->leftJoin('branch', 'data_sampling.unit', '=', 'branch.kode_branch')
            ->leftJoin('kelompok', 'data_sampling.kode_kel', '=', 'kelompok.code_kel')
            ->leftJoin('ao', 'data_sampling.cao', '=', 'ao.cao')
            ->leftJoin('users', 'data_sampling.user_id', '=', 'users.id')
            ->leftJoin('audit', 'data_sampling.cif', '=', 'audit.cif')
            ->leftJoin('audit_detail', 'audit.id', '=', 'audit_detail.id_audit')
            ->leftJoin('fraud_alerts', 'data_sampling.cif', '=', 'fraud_alerts.cif')
            ->whereIn('branch.code_area', $unitsUser);

        $request = $this->request;

        if ($request->hasAny(['tgl_awal', 'tgl_akhir'])) {
            $query->where('data_sampling.status', 'selesai');

            if ($request->filled('tgl_awal')) {
                $query->whereDate('data_sampling.updated_at', '>=', $request->tgl_awal);
            }

            if ($request->filled('tgl_akhir')) {
                $query->whereDate('data_sampling.updated_at', '<=', $request->tgl_akhir);
            }

            if ($request->filled('jenis_audit')) {
                $query->where('data_sampling.jenis_audit', $request->jenis_audit);
            }
        } elseif ($request->hasAny(['rekap_tgl_awal', 'rekap_tgl_akhir']) || $request->filled('periode')) {
            $query->whereIn('data_sampling.status', ['proses', 'pending', 'tanggapan', 'evaluasi', 'selesai']);

            if ($request->filled('rekap_tgl_awal') || $request->filled('rekap_tgl_akhir')) {
                if ($request->filled('rekap_tgl_awal')) {
                    $query->where('data_sampling.updated_at', '>=', Carbon::parse($request->rekap_tgl_awal)->startOfDay());
                }
                if ($request->filled('rekap_tgl_akhir')) {
                    $query->where('data_sampling.updated_at', '<=', Carbon::parse($request->rekap_tgl_akhir)->endOfDay());
                }
            } elseif ($request->filled('periode')) {
                $jumlahHari = (int) $request->periode;
                $tanggalMulai = Carbon::now()->subDays($jumlahHari - 1)->startOfDay();
                $tanggalSelesai = Carbon::now()->endOfDay();
                $query->whereBetween('data_sampling.updated_at', [$tanggalMulai, $tanggalSelesai]);
            }

            if ($request->filled('user_id')) {
                $query->where('data_sampling.user_id', $request->user_id);
            }
        } else {
            $query->where('data_sampling.status', 'selesai');
        }

        return $query->select(
            DB::raw("DATE_FORMAT(data_sampling.created_at, '%d-%m-%Y') as tanggal"),
            'data_sampling.id_ref_sampling',
            'data_sampling.cif',
            'data_sampling.nama',
            'branch.unit',
            'branch.area',
            'kelompok.nama_kel',
            'ao.nama_ao',
            'data_sampling.jenis_audit',
            'users.name as nama_petugas',
            DB::raw("COALESCE(NULLIF(fraud_alerts.flag_reason, ''), 'Audit Khusus') as fraud_alert"),
            'data_sampling.status'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Ref Sampling',
            'CIF',
            'Nama',
            'Unit',
            'Area',
            'Kelompok',
            'Nama AO',
            'Jenis Audit',
            'Nama Petugas',
            'Fraud Alert',
            'Status',
        ];
    }
}

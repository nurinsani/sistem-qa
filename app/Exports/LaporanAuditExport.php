<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanAuditExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('data_sampling')
            ->leftJoin('branch', 'data_sampling.unit', '=', 'branch.kode_branch')
            ->leftJoin('kelompok', 'data_sampling.kode_kel', '=', 'kelompok.code_kel')
            ->leftJoin('ao', 'data_sampling.cao', '=', 'ao.cao')
            ->leftJoin('users', 'data_sampling.user_id', '=', 'users.id')
            ->leftJoin('audit', 'data_sampling.cif', '=', 'audit.cif')
            ->leftJoin('audit_detail', 'audit.id', '=', 'audit_detail.id_audit')
            ->leftJoin('fraud_alerts', 'data_sampling.cif', '=', 'fraud_alerts.cif')
            ->select(
                'data_sampling.id_ref_sampling',
                'data_sampling.cif',
                'branch.unit',
                'branch.area',
                'kelompok.nama_kel',
                'ao.nama_ao',
                'data_sampling.jenis_audit',
                'users.name as nama_qa',
                'fraud_alerts.flag_reason as fraud_alert',

            )
            ->where('data_sampling.status', 'selesai')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Ref Sampling',
            'CIF',
            'Unit',
            'Area',
            'Kelompok',
            'Nama AO',
            'Jenis Audit',
            'Nama QA',
            'Fraud Alert',
        ];
    }
}

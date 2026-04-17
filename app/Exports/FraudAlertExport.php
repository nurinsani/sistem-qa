<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FraudAlertExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $tgl_tagih, $flag_status, $flag_reason;

    public function __construct($tgl_tagih, $flag_status, $flag_reason)
    {
        $this->tgl_tagih = $tgl_tagih;
        $this->flag_status = $flag_status;
        $this->flag_reason = $flag_reason;
    }

    public function collection()
    {
        return DB::table('fraud_alerts')
            ->leftJoin('data_loan_mob', 'data_loan_mob.cif', '=', 'fraud_alerts.cif')
            ->leftJoin('tunggakan_eom', 'tunggakan_eom.cif', '=', 'fraud_alerts.cif')
            ->leftJoin('data_loan_report', 'data_loan_report.cif', '=', 'fraud_alerts.cif')
            ->leftJoin('ao', 'ao.cao', '=', 'data_loan_mob.cao')
            ->leftJoin('kelompok', 'kelompok.code_kel', '=', 'data_loan_mob.code_kel')
            ->select(
                'fraud_alerts.tgl_tagih',
                'fraud_alerts.cif',
                'fraud_alerts.nama',
                'data_loan_mob.os',
                'ao.nama_ao',
                'kelompok.nama_kel as nama_kelompok',
                'data_loan_mob.run_tenor',
                'data_loan_mob.last_payment',
                'tunggakan_eom.ft',
                'data_loan_report.twm',
                'fraud_alerts.flag_reason',
                'fraud_alerts.flag_status'
            )
            ->whereDate('fraud_alerts.tgl_tagih', $this->tgl_tagih)
            ->where('fraud_alerts.flag_status', $this->flag_status)
            ->where('fraud_alerts.flag_reason', $this->flag_reason)
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal Tagih',
            'CIF',
            'Nama',
            'OS',
            'Nama AO',
            'Nama Kelompok',
            'Run Tenor',
            'Last Payment',
            'FT',
            'TWM',
            'Reason',
            'Status',
        ];
    }
}
<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FraudAlertExport implements FromCollection, WithHeadings
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
        return DB::table('fraud_alerts as fa')
            ->leftJoin('data_loan_mob as dlm', 'dlm.cif', '=', 'fa.cif')
            ->select(
                'fa.tgl_tagih',
                'fa.cif',
                'fa.nama',
                'dlm.os',
                'fa.flag_reason',
                'fa.flag_status'
            )
            ->whereDate('fa.tgl_tagih', $this->tgl_tagih)
            ->where('fa.flag_status', $this->flag_status)
            ->where('fa.flag_reason', $this->flag_reason)
            ->get();
    }

    // =========================
    // HEADER EXCEL
    // =========================
    public function headings(): array
    {
        return [
            'Tanggal Tagih',
            'CIF',
            'Nama',
            'OS',
            'Reason',
            'Status',
        ];
    }
}
<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LimaExport implements FromCollection, WithHeadings, WithMapping
{
    protected $tanggal;
    protected $kode_branch;
    protected $rowNumber = 0;

    public function __construct($tanggal, $kode_branch = null)
    {
        $this->tanggal = $tanggal;
        $this->kode_branch = $kode_branch;
    }

    public function collection()
    {
        $query = DB::table('lima')
            ->leftJoin('data_loan_report', function($join) {
                $join->on('lima.cif', '=', DB::raw("REPLACE(data_loan_report.cif, '''', '')"));
            })
            ->leftJoin('kelompok', function($join) {
                $join->on('kelompok.code_kel', '=', DB::raw("REPLACE(data_loan_report.code_kel, '''', '')"));
            })
            ->leftJoin('ao', 'lima.cao', '=', 'ao.cao')
            ->select([
                'lima.tgl_tagih',
                'kelompok.nama_kel',
                'kelompok.code_kel',
                'ao.nama_ao',
                'lima.cif',
                'data_loan_report.nama',
                'data_loan_report.Plafond',
                'data_loan_report.Os',
                'data_loan_report.twm',
                'data_loan_report.pokok',
                'data_loan_report.sim_wajib',
                'data_loan_report.run_tenor',
                'data_loan_report.tenor',
                'data_loan_report.angsuran',
                'lima.nominal as nyata_setor',
                DB::raw('0 as omzet'),
                'lima.status_trans'
            ]);

        if ($this->tanggal) {
            $query->whereDate('lima.tgl_tagih', $this->tanggal);
        }

        if ($this->kode_branch) {
            $query->where('kelompok.code_unit', $this->kode_branch);
        }

        return $query->get();
    }

    public function map($row): array
    {
        return [
            ++$this->rowNumber,
            $row->tgl_tagih,
            $row->nama_kel,
            $row->code_kel,
            $row->nama_ao,
            $row->cif,
            $row->nama,
            $row->Plafond,
            $row->Os,
            $row->twm,
            $row->pokok,
            $row->sim_wajib,
            $row->run_tenor,
            $row->tenor,
            $row->angsuran,
            $row->nyata_setor,
            $row->omzet,
            $row->status_trans,
        ];
    }

    public function headings(): array
    {
        return [
            ['REPORT NON CS LIMA PERSEN KSPPS NUR INSANI'],
            [],
            [
                'NOMOR',
                'Tgl Tagih',
                'KELOMPOK',
                'KOde Kelompok',
                'AO',
                'CIF',
                'NAMA',
                'Plafond',
                'Os',
                'twm',
                'pokok',
                'wajib',
                'KE',
                'PB',
                'Tagihan',
                'Nyata',
                'Omzet',
                'status Transaksi'
            ]
        ];
    }
}

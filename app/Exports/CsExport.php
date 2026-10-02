<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CsExport implements FromCollection, WithHeadings
{
    protected $tanggal;
    protected $kode_branch;

    public function __construct($tanggal, $kode_branch = null)
    {
        $this->tanggal = $tanggal;
        $this->kode_branch = $kode_branch;
    }

    public function collection()
    {
        $query = DB::table('cs')
            ->leftJoin('kelompok', 'cs.kode_kel', '=', 'kelompok.code_kel')
            ->select('cs.*');
        
        if ($this->tanggal) {
            $query->whereDate('cs.tgl_tagih', $this->tanggal);
        }

        if ($this->kode_branch) {
            $query->where('kelompok.code_unit', $this->kode_branch);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'CIF',
            'NAMA',
            'KODE KEL',
            'BULAT',
            'NAMA KEL',
            'CAO',
            'OS',
            'SALDO MARGIN',
            'STATUS TRANS',
            'NOMINAL',
            'STATUS APPROVE',
            'TGL TAGIH'
        ];
    }
}

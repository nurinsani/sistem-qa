<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BuktiSetorExport implements FromCollection, WithHeadings, WithMapping
{
    protected $tanggal;
    protected $rowNumber = 0;

    public function __construct($tanggal = null)
    {
        $this->tanggal = $tanggal;
    }

    public function collection()
    {
        $query = DB::table('bukti_setor')
            ->leftJoin('ao', 'bukti_setor.cao', '=', 'ao.cao')
            ->select([
                'bukti_setor.id',
                'bukti_setor.tgl_setor',
                'bukti_setor.cao',
                'ao.nama_ao',
                'bukti_setor.bank',
                'bukti_setor.name as nominal',
                'bukti_setor.image'
            ]);

        if ($this->tanggal) {
            $query->whereDate('bukti_setor.tgl_setor', $this->tanggal);
        }

        return $query->get();
    }

    public function map($row): array
    {
        return [
            ++$this->rowNumber,
            $row->tgl_setor,
            $row->cao,
            $row->nama_ao,
            $row->bank,
            $row->nominal,
            $row->image,
        ];
    }

    public function headings(): array
    {
        return [
            ['REPORT BUKTI SETOR KSPPS NUR INSANI'],
            [],
            [
                'NO',
                'TGL SETOR',
                'KODE AO',
                'NAMA AO',
                'BANK',
                'NOMINAL',
                'NAMA FILE GAMBAR'
            ]
        ];
    }
}

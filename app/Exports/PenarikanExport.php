<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PenarikanExport implements FromCollection, WithHeadings, WithMapping
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
        $url = 'http://mobcoll.nurinsani.co.id/apimobcol/data_report_mobcoll.php?function=penarikan';
        
        if ($this->kode_branch) {
            $url .= '&unit=' . urlencode($this->kode_branch);
        }
        
        if ($this->tanggal) {
            $url .= '&tgl_tagih=' . urlencode($this->tanggal);
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

        return collect($data);
    }

    public function map($row): array
    {
        return [
            ++$this->rowNumber,
            $row['tgl_tagih'] ?? '',
            $row['nama_kel'] ?? '',
            $row['kode_kel'] ?? '',
            $row['nama_ao'] ?? '',
            $row['cif'] ?? '',
            $row['nama'] ?? '',
            $row['twm'] ?? '',
            $row['simpanan_pokok'] ?? '',
            $row['simpanan_wajib'] ?? '',
            $row['ke'] ?? '',
            $row['pb'] ?? '',
            $row['bulat'] ?? '',
            $row['nominal'] ?? '',
            $row['status_trans'] ?? '',
        ];
    }

    public function headings(): array
    {
        return [
            ['REPORT NON CS PENARIKAN KSPPS NUR INSANI'],
            [],
            [
                'NOMOR',
                'TGL TAGIH',
                'KELOMPOK',
                'KODE KELOMPOK',
                'AO',
                'CIF',
                'NAMA',
                'TWM',
                'POKOK',
                'WAJIB',
                'KE',
                'PB',
                'TAGIHAN',
                'NYATA',
                'STATUS TRANSAKSI'
            ]
        ];
    }
}

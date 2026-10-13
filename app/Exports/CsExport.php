<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CsExport implements FromCollection, WithHeadings, WithMapping
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
        $url = 'http://mobcoll.nurinsani.co.id/apimobcol/data_report_mobcoll.php?function=cs';
        
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
            $row['tgl_tagih'] ?? '',
            $row['cif'] ?? '',
            $row['nama'] ?? '',
            $row['kode_kel'] ?? '',
            $row['nama_kel'] ?? '',
            $row['nama_ao'] ?? '',
            $row['pb'] ?? '',
            $row['ke'] ?? '',
            $row['twm'] ?? '',
            $row['simpanan_pokok'] ?? '',
            $row['simpanan_wajib'] ?? '',
            $row['bulat'] ?? '',
            $row['nominal'] ?? '',
            $row['status_trans'] ?? ''
        ];
    }

    public function headings(): array
    {
        return [
            'TGL TAGIH',
            'CIF',
            'NAMA',
            'KODE KEL',
            'NAMA KEL',
            'NAMA AO',
            'PB',
            'KE',
            'TWM',
            'SIMP POKOK',
            'SIMP WAJIB',
            'TAGIHAN',
            'NYATA',
            'STATUS TRANSAKSI'
        ];
    }
}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mutasi Simpanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0px;
        }
        .header {
            text-align: center;
            font-weight: bold;
        }
        .logo {
            display: block;
            margin: 0 auto;
            width: 240px;
        }
        .info {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .info-table, .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10pt;
        }
        .info-table td {
            padding: 5px;
        }
        .data-table th, .data-table td {
            border: 1px solid black;
            padding: 5px;
            /* text-align: center; */
        }
        .data-table th {
            background-color: #f0f0f0;
        }

        @page {
            margin: 1cm; /* Sesuaikan dengan kebutuhan */
        }
    </style>
</head>
<body>

    
    <div class="header">
        <img src="{{ public_path('images/logoni.jpeg') }}" alt="Logo Koperasi" class="logo">
        <p>
            KOPERASI SIMPAN PINJAM SYARIAH NUR INSANI<br>
            <h3>DAFTAR MUTASI SIMPANAN ANGGOTA</h3>
        </p>
    </div>

    <div class="info">
        <table class="info-table">
            <tr>
                <td>Nomor Rekening</td>
                <td>: {{ $saldo->norek ?? '-' }}</td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>: {{ $saldo->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td>Plafond Pembiayaan</td>
                <td>: {{ number_format($saldo->plafond ?? '0') }}</td>
            </tr>
            <tr>
                <td>Tanggal Akad</td>
                <td>: {{ date('d M Y', strtotime($saldo->tgl_join ?? '-')) }}</td>
            </tr>
            <tr>
                <td>Jangka Waktu</td>
                <td>: {{ $saldo->tenor ?? '-' }} Minggu</td>
            </tr>
            <tr>
                <td>Tanggal Jatuh Tempo</td>
                <td>: {{ date('d M Y', strtotime($saldo->maturity_date ?? '-')) }}</td>
            </tr>
            <tr>
                <td>Sisa Pembiayaan</td>
                <td>: {{ number_format($saldo->os ?? '0') }}</td>
            </tr>
            <tr>
                <td>Simpanan Pokok</td>
                <td>: {{ number_format($saldo->pokok ?? '0') }}</td>
            </tr>
            <tr>
                <td>Simpanan Wajib</td>
                <td colspan="3">: {{ number_format($saldo->wajib ?? '0') }}</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode Transaksi</th>
                <th>Keterangan</th>
                <th>Tipe</th>
                <th>Debet</th>
                <th>Kredit</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mutasi as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td style="width: 80px; text-align: left">{{ date('Y-m-d', strtotime($item->tanggal)) }}</td>
                <td style="text-align: left">{{ $item->kode_transaksi }}</td>
                @if (!empty($item->keterangan))
                    <td style="text-align: left">{{ $item->keterangan }}</td>
                @else
                    <td style="text-align: left">Realisasi Angsuran</td>
                @endif
                <td style="text-align: center">{{ $item->type }}</td>
                <td style="text-align: right">{{ number_format($item->debet) }}</td>
                <td style="text-align: right">{{ number_format($item->kredit) }}</td>
                <td style="text-align: right">{{number_format( $item->saldo) }}</td>
            </tr>
            @endforeach
            <!-- Tambahkan baris lain sesuai kebutuhan -->
        </tbody>
    </table>

</body>
</html>

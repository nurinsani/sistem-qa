<!DOCTYPE html>
<html>
<head>
    <title>Mutasi - {{ $dataCif['cif'] }} {{ $dataCif['nama'] }}</title>
    <style>
        /* Pengaturan Kertas */
        @page { size: A4; margin: 1cm; }
        
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; line-height: 1.4; margin: 0; padding: 0; }
        
        /* Header Center */
        .header { text-align: center; margin-bottom: 20px; }
        .header img { height: 60px; width: auto; margin-bottom: 5px; }
        .header h1 { font-size: 16px; margin: 0; text-transform: uppercase; font-weight: bold; }
        .header p { margin: 0; font-size: 11px; color: #555; }
        .header .title { margin-top: 15px; font-size: 14px; font-weight: bold; text-decoration: underline; text-transform: uppercase; }

        /* Container Informasi Anggota (2 Kolom Manual) */
        .info-wrapper { width: 100%; margin-bottom: 20px; display: table; border-spacing: 10px 0; }
        .info-column { display: table-cell; width: 50%; vertical-align: top; }
        
        .table-info { width: 100%; border-collapse: collapse; border: none !important; }
        .table-info td { border: none !important; padding: 3px 2px; text-align: left; vertical-align: top; }
        .table-info td.label { width: 40%; }
        .table-info td.sep { width: 3%; }
        .table-info td.val { width: 57%; font-weight: bold; }

        /* Tabel Mutasi Utama */
        .table-mutasi { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table-mutasi th { background-color: #f2f2f2; border: 1px solid #333; padding: 8px 5px; text-align: center; font-weight: bold; text-transform: uppercase; }
        .table-mutasi td { border: 1px solid #333; padding: 6px 5px; vertical-align: middle; }
        
        /* Helpers */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .no-print { margin-top: 20px; text-align: center; }

        @media print {
            .no-print { display: none; }
            body { -webkit-print-color-adjust: exact; }
            .table-mutasi th { background-color: #f2f2f2 !important; }
        }
    </style>
</head>
<body onload="window.print()">
    
    {{-- HEADER --}}
    <div class="header">
        <img src="{{ asset('images/logoni.jpeg') }}" alt="Logo">
        <h1>Koperasi Simpan Pinjam Syariah Nur Insani</h1>
        <p>Melayani Dengan Hati</p>
        <div class="title">Daftar Mutasi Simpanan Anggota</div>
    </div>

    {{-- INFORMASI ANGGOTA (2 KOLOM) --}}
    <div class="info-wrapper">
        <div class="info-column">
            <table class="table-info">
                <tr><td class="label">Nomor Rekening</td><td class="sep">:</td><td class="val">{{ $dataCif['norek'] }}</td></tr>
                <tr><td class="label">Nama</td><td class="sep">:</td><td class="val">{{ $dataCif['nama'] }}</td></tr>
                <tr><td class="label">Plafond Pembiayaan</td><td class="sep">:</td><td class="val">{{ number_format($dataCif['plafond']) }}</td></tr>
                <tr><td class="label">Tanggal Akad</td><td class="sep">:</td><td class="val">{{ $dataCif['tgl_join'] }}</td></tr>
            </table>
        </div>

        <div class="info-column">
            <table class="table-info">
                <tr><td class="label">Jangka Waktu</td><td class="sep">:</td><td class="val">{{ $dataCif['tenor'] }}</td></tr>
                <tr><td class="label">Tgl. Jatuh Tempo</td><td class="sep">:</td><td class="val">{{ $dataCif['maturity_date'] }}</td></tr>
                <tr><td class="label">Sisa Pembiayaan</td><td class="sep">:</td><td class="val">{{ number_format($dataCif['os']) }}</td></tr>
                <tr><td class="label">Simpanan Pokok</td><td class="sep">:</td><td class="val">{{ number_format($dataCif['pokok']) }}</td></tr>
                <tr><td class="label">Simpanan Wajib</td><td class="sep">:</td><td class="val">{{ number_format($dataCif['wajib']) }}</td></tr>
            </table>
        </div>
    </div>

    {{-- TABEL DATA --}}
    <table class="table-mutasi">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="15%">Tanggal</th>
                <th>Keterangan</th>
                <th width="13%">Debet</th>
                <th width="13%">Kredit</th>
                <th width="15%">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mutasi as $i => $row)
            <tr>
                <td class="text-center">{{ $i+1 }}</td>
                <td class="text-center">{{ $row['tanggal'] }}</td>
                <td>{{ $row['keterangan'] }}</td>
                <td class="text-right">{{ number_format($row['debet']) }}</td>
                <td class="text-right">{{ number_format($row['kredit']) }}</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($row['saldo']) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
     <div class="no-print container my-4">
        <button onclick="window.print()" class="btn btn-success btn-sm mb-3">🖨️ Cetak</button>
    </div>
</body>
</html>
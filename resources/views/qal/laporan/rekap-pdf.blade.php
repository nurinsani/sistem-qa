<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rekap Laporan Audit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .page-break {
            page-break-after: always;
        }

        .photo-container {
            text-align: center;
            padding: 10px;
        }

        .photo-container img {
            max-width: 100%;
            height: auto;
            max-height: 200px;
            object-fit: contain;
        }

        .qr {
            text-align: center;
        }
    </style>
</head>

<body>

    <h3 style="text-align: center; margin-bottom: 5px;">REKAPITULASI LAPORAN AUDIT</h3>
    <p style="text-align: center; margin-top: 0; margin-bottom: 15px;">
        Periode: <strong>{{ $periodeLabel ?? ($jumlahHari ?? '-') }}</strong> |
        Auditor: <strong>{{ $userTarget->name ?? 'User ID: ' . request('user_id') }}</strong> |
        Total: <strong>{{ count($rekapItems) }} Data Sampling</strong>
    </p>

    @foreach ($rekapItems as $item)
        @php
            $data = (object) $item['data'];
            $temuan = $item['temuan'];
            $qr_ao = $item['qr_ao'];
            $qr_qa = $item['qr_qa'];
        @endphp

        <!-- Header Kop Surat -->
        <table style="border:none; margin-bottom:5px;">
            <tr style="border:none;">
                <td style="border:none; width:15%;">
                    <img src="{{ asset('assets/dist/img/logo-with-text.png') }}" width="80">
                </td>
                <td style="border:none; text-align:center;">
                    <br>
                    <p>
                        Ruko Bintaro Sektor IX Blok G No. 6 Jl. Bintaro Utama Sektor IX
                        Kel. Pondok Pucung Kec. Pondok Aren
                        Tangerang Selatan Banten – (021) – 745 5352
                    </p>
                </td>
                <td style="border:none; width:15%; text-align:right;">
                    <img src="{{ asset('assets/dist/img/logo-koperasi.png') }}" width="80">
                </td>
            </tr>
        </table>

        <hr>

        <p style="font-weight: bold; margin-bottom: 5px;">
            Data Sampling #{{ $loop->iteration }} - NAMA: {{ $data->nama }} (CIF: {{ $data->cif }})
        </p>

        <table>
            <tr>
                <td colspan="2">Kantor Cabang : {{ $data->unit ?? '-' }}</td>
                <td>Area Pemasaran : {{ $data->area ?? '-' }}</td>
                <td>Tgl Kunjungan : {{ $data->tanggal_kunjungan ?? '-' }}</td>
            </tr>

            <tr>
                <th>Referensi (SOP/MI/MOA)</th>
                <th colspan="2">Isi Ketentuan</th>
                <th>Temuan</th>
            </tr>

            <tr>
                <td>
                    @foreach ($temuan as $t)
                        Nomor : {{ $t->nomor_ketentuan ?? '-' }} <br>
                    @endforeach
                </td>

                <td colspan="2">
                    @foreach ($temuan as $t)
                        <p>
                            <small>
                                {{ $t->heading }} <br>
                                {{ $t->sub_heading }} <br>
                                &nbsp;&nbsp;&nbsp;&nbsp;{{ $t->sub_sub_heading }} <br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $t->sub_sub_sub_heading }}
                            </small>
                        </p>
                    @endforeach
                </td>

                <td>
                    {{ $data->temuan ?? '-' }}
                </td>
            </tr>

            <tr>
                <th>Tanggapan AO, MM, BM, AL</th>
                <th>Paraf AO</th>
                <th>Tindak Lanjut</th>
                <th>Batas Waktu</th>
            </tr>

            <tr>
                <td>
                    Tanggapan AO: <br>
                    <small>{{ $data->tanggapan_ao ?? '-' }}</small>
                    <br><br>
                    Tanggapan MM: <br>
                    <small>{{ $data->tanggapan_mm ?? '-' }}</small>
                    <br><br>
                    Tanggapan BM: <br>
                    <small>{{ $data->tanggapan_bm ?? '-' }}</small>
                    <br><br>
                    Tanggapan AL: <br>
                    <small>{{ $data->tanggapan_al ?? '-' }}</small>
                </td>

                <td class="qr" style="text-align:center; width:20%;">
                    <br><br>
                    <img src="data:image/png;base64,{{ $qr_ao }}">
                    <br>
                    <small>Paraf AO</small>
                </td>

                <td>
                    {{ $data->tindak_lanjut ?? '-' }}
                </td>

                <td>
                    {{ $data->due_date ?? '-' }}
                </td>
            </tr>
        </table>

        <br>

        <table>
            <tr>
                <td class="qr">
                    Dibuat Oleh
                    <br><br>
                    <img src="data:image/png;base64,{{ $qr_qa }}">
                    <br>
                    QA
                </td>

                <td class="qr">
                    Diketahui Bisnis
                    <br><br>
                    <img src="data:image/png;base64,{{ $qr_qa }}">
                    <br>
                    BM
                </td>

                <td class="qr">
                    Diketahui Operational
                    <br><br>
                    <img src="data:image/png;base64,{{ $qr_qa }}">
                    <br>
                    AL
                </td>

                <td class="qr">
                    Disetujui
                    <br><br>
                    <img src="data:image/png;base64,{{ $qr_qa }}">
                    <br>
                    AM
                </td>
            </tr>
        </table>

        <!-- Hasil Observasi & Lampiran Foto -->
        <table style="border:none; margin-top:15px; margin-bottom:10px;">
            <tr>
                <td style="border:none; font-weight:bold; font-size: 13px;">
                    HASIL OBSERVASI
                </td>
                <td style="border:none; text-align:right;">
                    Cabang: {{ $data->unit ?? '-' }} | Tgl Kunjungan: {{ $data->tanggal_kunjungan ?? '-' }}
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <th style="width: 33%;">Kondisi Usaha</th>
                <th style="width: 33%;">Kondisi Keluarga</th>
                <th style="width: 33%;">Kondisi Lingkungan</th>
            </tr>
            <tr>
                <td>
                    <small>{{ $data->kondisi_usaha ?? '-' }}</small>
                </td>
                <td>
                    <small>{{ $data->kondisi_keluarga ?? '-' }}</small>
                </td>
                <td>
                    <small>{{ $data->kondisi_lingkungan ?? '-' }}</small>
                </td>
            </tr>
        </table>

        <br>

        <table style="border:none; margin-bottom:10px;">
            <tr>
                <td style="border:none; font-weight:bold; font-size: 13px;">
                    LAMPIRAN: FOTO-FOTO HASIL AUDIT
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <th style="width: 33%; text-align: center;">Foto Wawancara Anggota</th>
                <th style="width: 33%; text-align: center;">Foto Wawancara Ketua Kelompok</th>
                <th style="width: 33%; text-align: center;">Foto Usaha</th>
            </tr>
            <tr>
                <td class="photo-container">
                    @if (!empty($data->foto_wawancara_anggota))
                        <img src="{{ public_path($data->foto_wawancara_anggota) }}" alt="Foto Wawancara Anggota">
                        <p><small>Keterangan:
                                {{ $data->keterangan_foto_wawancara_anggota ?? 'Foto Wawancara Anggota' }}</small></p>
                    @else
                        <br>
                        <p style="color: #777;">Tidak ada foto</p><br>
                    @endif
                </td>
                <td class="photo-container">
                    @if (!empty($data->foto_wawancara_ketua))
                        <img src="{{ public_path($data->foto_wawancara_ketua) }}" alt="Foto Wawancara Ketua Kelompok">
                        <p><small>Keterangan:
                                {{ $data->keterangan_foto_wawancara_ketua ?? 'Foto Wawancara Ketua Kelompok' }}</small>
                        </p>
                    @else
                        <br>
                        <p style="color: #777;">Tidak ada foto</p><br>
                    @endif
                </td>
                <td class="photo-container">
                    @if (!empty($data->foto_usaha))
                        <img src="{{ public_path($data->foto_usaha) }}" alt="Foto Usaha">
                        <p><small>Keterangan: {{ $data->keterangan_foto_usaha ?? 'Foto Usaha' }}</small></p>
                    @else
                        <br>
                        <p style="color: #777;">Tidak ada foto</p><br>
                    @endif
                </td>
            </tr>
        </table>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

</body>

</html>

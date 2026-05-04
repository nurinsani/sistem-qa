<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
        }

        .header {
            text-align: center;
            font-weight: bold;
        }

        .qr {
            text-align: center;
        }
    </style>
</head>

<body>
    <table style="border:none; margin-bottom:5px;">
        <tr style="border:none;">

            <td style="border:none; width:15%;">
                <img src="{{ asset('assets/dist/img/logo-with-text.png') }}" width="80">
            </td>

            <td style="border:none; text-align:center;">

                {{-- <h2 style="margin:0; color:green;">
                    KSPPS NUR INSANI
                </h2>

                <small>
                    Melayani dengan hati
                </small> --}}

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

    <table>

        <tr>
            <td colspan="2">Kantor Cabang : {{ $data->unit }}</td>
            <td>Area Pemasaran : {{ $data->area ?? '-' }}</td>
            <td>Tgl Kunjungan : {{ $data->tanggal_kunjungan }}</td>
        </tr>

        <tr>
            <th>Referensi (SOP/MI/MOA)</th>
            <th colspan="2">Isi Ketentuan</th>
            <th>Temuan</th>
        </tr>

        <tr>

            <td>
                @foreach ($temuan as $item)
                    Nomor : {{ $item->nomor_ketentuan ?? '-' }} <br>
                @endforeach
            </td>

            <td colspan="2">
                @foreach ($temuan as $item)
                    <p>
                        <strong>
                            Nomor : {{ $item->nomor_ketentuan ?? '-' }} <br>

                        </strong>
                        <small>
                            {{ $item->heading }} <br>
                            {{ $item->sub_sub_sub_heading }}
                        </small>
                    </p>
                @endforeach
            </td>

            <td>
                {{ $data->temuan ?? '-' }}
            </td>

        </tr>

        <tr>
            <th>Tanggapan MM, AL, BM</th>
            <th>Paraf AO</th>
            <th>Tindak Lanjut</th>
            <th>Batas Waktu</th>
        </tr>

        <tr>

            <td>
                [tanggapan ao] <br>
                <small>
                    {{ $data->tanggapan_ao ?? '-' }}
                </small>
                <br><br>
                [tanggapan mm] <br>
                <small>
                    {{ $data->tanggapan_mm ?? '-' }}
                </small>
                <br><br>
                [tanggapan bm] <br>
                <small>
                    {{ $data->tanggapan_bm ?? '-' }}
                </small>
            </td>

                <!-- Paraf AO -->
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
                QA, QoorQA
            </td>

            <td class="qr">
                Diketahui Bisnis
                <br><br>
                <img src="data:image/png;base64,{{ $qr_qa }}">
                <br>
                QR
                MM
            </td>

            <td class="qr">
                Diketahui Operational
                <br><br>
                <img src="data:image/png;base64,{{ $qr_qa }}">
                <br>
                QR
                AL
            </td>

            <td class="qr">
                Disetujui
                <br><br>
                <img src="data:image/png;base64,{{ $qr_qa }}">
                <br>
                QR
                AM
            </td>

        </tr>

    </table>

</body>

</html>

<div class="col-md-5">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informasi Anggota</h3>
        </div>

        <div class="card-body">
            <div class="row">

                <div class="col-md-12">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td width="200">No Anggota</td>
                            <td width="10">:</td>
                            <td>{{ $data_api['norek'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Akad</td>
                            <td>:</td>
                            <td>{{ $data_api['tgl_join'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>NIK</td>
                            <td>:</td>
                            <td>{{ $data_api['ktp'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td>{{ $data_api['nama'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td>
                                {{ $data_api['alamat'] ?? '' }}
                                {{ $data_api['rt'] ?? '' }}
                                {{ $data_api['desa'] ?? '' }}
                                {{ $data_api['kecamatan'] ?? '' }}
                                {{ $data_api['kota'] ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>Kelompok</td>
                            <td>:</td>
                            <td>{{ $data_sampling_detail->kelompok->nama_kel ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Nama AO</td>
                            <td>:</td>
                            <td>{{ $data_sampling_detail->ao->nama_ao ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Jenis Pembiayaan</td>
                            <td>:</td>
                            <td>{{ $data_sampling_detail->jenis_pembiayaan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>OS</td>
                            <td>:</td>
                            <td>{{ number_format($data_api['os'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Setoran</td>
                            <td>:</td>
                            <td>{{ number_format($data_api['bulat'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Pembiayaan Ke</td>
                            <td>:</td>
                            <td>{{ $data_api['run_tenor'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Simpanan Wadiah</td>
                            <td>:</td>
                            <td>{{ number_format($data_sampling->simpanan_wadiah ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Simpanan Pokok</td>
                            <td>:</td>
                            <td>{{ number_format($data_api['pokok'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Simpanan Wajib</td>
                            <td>:</td>
                            <td>{{ number_format($data_api['wajib'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Omzet Awal</td>
                            <td>:</td>
                            <td>{{ number_format($data_sampling->omzet_awal ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Rata-rata Omzet</td>
                            <td>:</td>
                            <td>{{ number_format($data_sampling->rata_rata_omzet ?? 0, 0, ',', '.') }}</td>
                        </tr>

                        <tr>
                            <td>Flag Reason</td>
                            <td>:</td>
                            <td>{{ $data_sampling->flag_reason }}</td>
                        </tr>
                    </table>

                </div>

            </div>
        </div>

    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Dokumen PDF</h3>
        </div>

        <div class="card-body">

            @if (!empty($dokumen_api['form']))
                <a href="{{ $baseFile . $dokumen_api['form'] }}" target="_blank"
                    class="btn btn-primary btn-block mb-2">
                    <i class="fas fa-file-pdf"></i> Form Anggota
                </a>
            @endif

            @if (!empty($dokumen_api['murabahah']))
                <a href="{{ $baseFile . $dokumen_api['murabahah'] }}" target="_blank"
                    class="btn btn-success btn-block mb-2">
                    <i class="fas fa-file-pdf"></i> Akad Murabahah
                </a>
            @endif

            @if (!empty($dokumen_api['wakalah']))
                <a href="{{ $baseFile . $dokumen_api['wakalah'] }}" target="_blank" class="btn btn-warning btn-block">
                    <i class="fas fa-file-pdf"></i> Akad Wakalah
                </a>
            @endif

        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Informasi Anggota</h3>
    </div>

    <div class="card-body">
        <div class="row">

            @php
                $foto = $dokumen['poto'] ?? null;
                $isValid =
                    $foto && in_array(strtolower(pathinfo($foto, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp']);
            @endphp

            @if ($isValid)
                <div class="col-md-2 text-center">
                    <img src="{{ $baseFile . $dokumen['poto'] }}" class="img-thumbnail" style="max-width: 180px;"
                        alt="Foto Anggota">
                </div>
            @else
                <div class="col-md-2 text-center">
                    <img src="https://png.pngtree.com/png-vector/20221125/ourmid/pngtree-no-image-available-icon-flatvector-illustration-pic-design-profile-vector-png-image_40966566.jpg"
                        class="img-thumbnail" style="max-width: 180px;" alt="Foto Anggota Tidak Tersedia">
                </div>
            @endif
            {{-- Foto --}}

            {{-- Detail --}}
            <div class="col-md-10">
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
                        <td>{{ $data_sampling->kelompok->nama_kel ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Nama AO</td>
                        <td>:</td>
                        <td>{{ $data_sampling->ao->nama_ao ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Jenis Pembiayaan</td>
                        <td>:</td>
                        <td>{{ $data_sampling->jenis_pembiayaan ?? '-' }}</td>
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
                </table>

            </div>

        </div>
    </div>
</div>

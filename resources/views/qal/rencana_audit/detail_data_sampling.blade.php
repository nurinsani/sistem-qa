@extends('layouts.main')

@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ $title }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">

        {{-- Tombol Kembali --}}
        <a href="{{ url()->previous() }}" class="btn btn-primary mb-3">
            ← Kembali
        </a>

        {{-- Card Informasi Anggota --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Anggota</h3>
            </div>

            <div class="card-body">
                <div class="row">

                    @php
                        $foto = $dokumen['poto'] ?? null;
                        $isValid =
                            $foto &&
                            in_array(strtolower(pathinfo($foto, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp']);
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

        <style>
            .doc-img {
                max-height: 220px;
                object-fit: cover;
                cursor: zoom-in;
                transition: transform .2s;
            }

            .doc-img:hover {
                transform: scale(1.03);
            }

            .preview-toolbar {
                position: sticky;
                top: 0;
                background: #fff;
                z-index: 1056; /* di atas modal-body */
                border-bottom: 1px solid #ddd;
            }

            .image-container {
                height: 80vh;
                overflow: auto;          /* scroll kalau gambar besar */
                display: flex;
                justify-content: center;
                align-items: center;
                background: #f8f9fa;
            }

            #modalImage {
                max-width: none;         /* penting supaya zoom bener */
                max-height: none;
                transition: transform .2s;
                transform-origin: center center;
                cursor: grab;
            }
        </style>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Dokumen Anggota</h3>
            </div>

            <div class="card-body">
                <div class="row text-center">

                    {{-- KTP --}}
                    <div class="col-md-3 mb-3">
                        <h6>KTP</h6>
                        @if(!empty($dokumen['ktp']))
                            <img src="{{ $baseFile.$dokumen['ktp'] }}"
                                class="img-fluid img-thumbnail doc-img preview-img"
                                data-src="{{ $baseFile.$dokumen['ktp'] }}"
                                data-title="Dokumen KTP">
                        @else
                            <p class="text-muted">Tidak tersedia</p>
                        @endif
                    </div>


                    {{-- KK --}}
                    <div class="col-md-3 mb-3">
                        <h6>KK</h6>
                        @if (!empty($dokumen['kk']))
                            <img src="{{ $baseFile.$dokumen['kk'] }}"
                                class="img-fluid img-thumbnail doc-img preview-img"
                                data-src="{{ $baseFile.$dokumen['kk'] }}"
                                data-title="Dokumen KK">
                        @else
                            <p class="text-muted">Tidak tersedia</p>
                        @endif
                    </div>

                    {{-- Penjamin --}}
                    <div class="col-md-3 mb-3">
                        <h6>Penjamin</h6>
                        @if (!empty($dokumen['penjamin']))
                            <img src="{{ $baseFile.$dokumen['penjamin'] }}"
                                class="img-fluid img-thumbnail doc-img preview-img"
                                data-src="{{ $baseFile.$dokumen['penjamin'] }}"
                                data-title="Dokumen Penjamin">
                        @else
                            <p class="text-muted">Tidak tersedia</p>
                        @endif
                    </div>

                    {{-- Foto Usaha --}}
                    <div class="col-md-3 mb-3">
                        <h6>Foto Usaha</h6>
                        @if (!empty($dokumen['usaha']))
                            <img src="{{ $baseFile.$dokumen['usaha'] }}"
                                class="img-fluid img-thumbnail doc-img preview-img"
                                data-src="{{ $baseFile.$dokumen['usaha'] }}"
                                data-title="Dokumen Foto Usaha">
                        @else
                            <p class="text-muted">Tidak tersedia</p>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- Modal Preview Dokumen --}}
        <div class="modal fade" id="modalPreview" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Preview Dokumen</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    {{-- Toolbar --}}
                    <div class="preview-toolbar text-center py-2">
                        <button class="btn btn-sm btn-secondary" id="zoomIn">+ Zoom</button>
                        <button class="btn btn-sm btn-secondary" id="zoomOut">- Zoom</button>
                        <button class="btn btn-sm btn-secondary" id="rotateLeft">⟲ Rotate</button>
                        <button class="btn btn-sm btn-secondary" id="rotateRight">⟳ Rotate</button>
                        <button class="btn btn-sm btn-danger" id="resetImage">Reset</button>
                    </div>

                    <div class="modal-body text-center">
                        <img id="modalImage"
                            src=""
                            class="img-fluid"
                            style="max-height: 80vh; transition: transform .2s;">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Dokumen PDF</h3>
            </div>

            <div class="card-body">

                @if (!empty($dokumen['form']))
                    <a href="{{ $baseFile . $dokumen['form'] }}" target="_blank" class="btn btn-primary btn-block mb-2">
                        📄 Form Anggota
                    </a>
                @endif

                @if (!empty($dokumen['murabahah']))
                    <a href="{{ $baseFile . $dokumen['murabahah'] }}" target="_blank" class="btn btn-success btn-block mb-2">
                        📄 Akad Murabahah
                    </a>
                @endif

                @if (!empty($dokumen['wakalah']))
                    <a href="{{ $baseFile . $dokumen['wakalah'] }}" target="_blank" class="btn btn-warning btn-block">
                        📄 Akad Wakalah
                    </a>
                @endif

            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        let scale = 1;
        let rotate = 0;

        $(document).on('click', '.preview-img', function () {
            let src   = $(this).data('src');
            let title = $(this).data('title') ?? 'Preview Dokumen';

            scale = 1;
            rotate = 0;

            $('#modalImage')
                .attr('src', src)
                .css('transform', 'scale(1) rotate(0deg)');

            $('#modalTitle').text(title);
            $('#modalPreview').modal('show');
        });

        // Zoom In
        $('#zoomIn').click(function () {
            scale += 0.2;
            updateTransform();
        });

        // Zoom Out
        $('#zoomOut').click(function () {
            if (scale > 0.4) {
                scale -= 0.2;
                updateTransform();
            }
        });

        // Rotate Left
        $('#rotateLeft').click(function () {
            rotate -= 90;
            updateTransform();
        });

        // Rotate Right
        $('#rotateRight').click(function () {
            rotate += 90;
            updateTransform();
        });

        // Reset
        $('#resetImage').click(function () {
            scale = 1;
            rotate = 0;
            updateTransform();
        });

        function updateTransform() {
            $('#modalImage').css('transform', `scale(${scale}) rotate(${rotate}deg)`);
        }
    </script>
@endpush



@extends('layouts.main')

@section('content')
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('previewModal');
                const previewImage = document.getElementById('previewImage');

                modal.addEventListener('show.bs.modal', function(event) {
                    const trigger = event.relatedTarget;
                    const imgSrc = trigger.getAttribute('data-img');
                    previewImage.src = imgSrc;
                });
            });
        </script>
    @endpush

    <style>
.image-box {
    width: 180px;
    height: 180px;
    margin: auto;
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid #ddd;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
}

.image-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image {
    font-size: 14px;
    color: #999;
}
</style>

    <div class="container">
            <h4><strong>Informasi Anggota</strong></h4>
        <a href="{{ route('informasi_anggota', ['cif' => request()->route('cif')]) }}" class="btn btn-primary btn-sm mb-3">
    ← Kembali
</a>

        <div class="card">
            <div class="card-header">
                <h5>Informasi Anggota</h5>
            </div>

            <div class="card-body">
                <div class="row">
                    {{-- FOTO --}}
                    <div class="col-md-3 text-center">

                      <div class="col-md-3 text-center">

    <div class="image-box">
        @if(!empty($dataDokumen['poto']))
            <img src="{{ $linkRmc . $dataDokumen['poto'] }}"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">

            <span class="no-image" style="display:none;">
                Gambar tidak ada
            </span>
        @else
            <span class="no-image">
                Gambar tidak ada
            </span>
        @endif
    </div>

</div>
                    </div>

                    {{-- DATA --}}
                    {{-- @dd($dataCif) --}}
                    <div class="col-md-9">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="200">No Anggota</td>
                                <td>:</td>
                                <td>{{ $dataCif['norek'] }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Akad</td>
                                <td>:</td>
                                <td>{{ $dataCif['tgl_join'] }}</td>
                            </tr>
                            <tr>
                                <td>NIK</td>
                                <td>:</td>
                                <td>{{ $dataCif['ktp'] }}</td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td>:</td>
                                <td>{{ $dataCif['nama'] }}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td>{{ $dataCif['alamat'] }} </td>
                            </tr>
                            <tr>
                                <td>Kelompok</td>
                                <td>:</td>
                                <td>{{ $dataCif['kodekel'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Nama AO</td>
                                <td>:</td>
                                <td>{{ $dataCif['cao'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>No Telp</td>
                                <td>:</td>
                                <td>{{ $dataCif['tlp'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Nama Penjamin</td>
                                <td>:</td>
                                <td>{{ $dataCif['waris'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Jenis Pembiayaan</td>
                                <td>:</td>
                                <td>{{ $dataCif['jenis_pembiayaan'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Plafond</td>
                                <td>:</td>
                                <td>{{ number_format($dataCif['plafond']) }}</td>
                            </tr>
                            <tr>
                                <td>OS</td>
                                <td>:</td>
                                <td>{{ number_format($dataCif['os']) }}</td>
                            </tr>
                            <tr>
                                <td>Setoran</td>
                                <td>:</td>
                                <td>{{ number_format($dataCif['bulat']) }}</td>
                            </tr>
                            <tr>
                                <td>Pembiayaan Ke</td>
                                <td>:</td>
                                <td>{{ $dataCif['run_renor'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Simpanan Wadiah</td>
                                <td>:</td>
                                <td>{{ number_format($dataCif['twm']) }}</td>
                            </tr>
                            <tr>
                                <td>Simpanan Pokok</td>
                                <td>:</td>
                                <td>{{ number_format($dataCif['pokok']) }}</td>
                            </tr>
                            <tr>
                                <td>Simpanan Wajib</td>
                                <td>:</td>
                                <td>{{ number_format($dataCif['wajib']) }}</td>
                            </tr>
                            <tr>
                                <td>Omzet Awal</td>
                                <td>:</td>
                                <td>{{ $dataCif['omzet'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Rata-rata Omzet</td>
                                <td>:</td>
                                <td>{{ $dataCif['rata_omzet'] ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <div class="card w-100 shadow-sm">
            <div class="card-body">

                <h5 class="mb-4">Dokumen Lain</h5>

                <div class="row g-4">

                    <!-- KTP -->
                    <div class="col-md-3">
                        <div class="card h-30 text-center">
                            <img src="{{ $linkRmc . $dataDokumen['ktp'] }}"
                                class="img-fluid img-thumbnail doc-img preview-img"
                                data-src="{{ $linkRmc . $dataDokumen['ktp'] }}" data-title="Dokumen KTP">
                            <div class=" fw-semibold">KTP</div>
                        </div>
                    </div>


                    <!-- KTP Penjamin -->
                    <div class="col-md-3">
                        <div class="card h-30 text-center ">
                            <img src="{{ $linkRmc . $dataDokumen['penjamin'] }}"
                                class="img-fluid img-thumbnail doc-img preview-img"
                                data-src="{{ $linkRmc . $dataDokumen['penjamin'] }}" data-title="Dokumen KTP">
                            <div class=" fw-semibold">KTP Penjamin</div>
                        </div>
                    </div>

                    <!-- Kartu Keluarga -->
                    <div class="col-md-3">
                        <div class="card h-30 text-center">
                            <img src="{{ $linkRmc . $dataDokumen['kk'] }}"
                                class="img-fluid img-thumbnail doc-img preview-img"
                                data-src="{{ $linkRmc . $dataDokumen['kk'] }}" data-title="Dokumen KTP">
                            <div class=" fw-semibold">Kartu Keluarga</div>
                        </div>
                    </div>

                    <!-- Foto Usaha -->
                    <div class="col-md-3">
                        <div class="card h-30 text-center">
                            <img src="{{ $linkRmc . $dataDokumen['usaha'] }}"
                                class="img-fluid img-thumbnail doc-img preview-img"
                                data-src="{{ $linkRmc . $dataDokumen['usaha'] }}" data-title="Dokumen KTP">
                            <div class=" fw-semibold">Foto Usaha</div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        {{-- DOKUMEN AKAD --}}
        <div class="card w-100 shadow-sm mt-4">
            <div class="card-header fw-semibold">
                Dokumen Akad dan Mutasi
            </div>
            <div class="card-body text-center">
                <i class="bi bi-file-earmark-pdf fs-1 text-danger"></i>
                <a href="{{ $linkRmc . $dataDokumen['murabahah'] }}" target="_blank" class="btn btn-success"
                    data-bs-toggle="modal" data-bs-target="#akadModal" data-pdf="">
                    📄 Lihat Dokumen Akad
                </a>
                <a href="{{ route('mutasi_anggota', request('cif')) }}" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#akadModal" data-pdf="">
                    📄 Lihat Mutasi Anggota
                </a>
            </div>
        </div>

        {{-- Modal Preview Dokumen Lain --}}
        <div class="modal fade" id="modalPreview" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Preview Dokumen</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    {{-- TOOLBAR --}}
                    <div class="mb-3">
                        <button class="btn btn-sm btn-secondary" onclick="rotateImg(-90)">⟲ Rotate</button>
                        <button class="btn btn-sm btn-secondary" onclick="rotateImg(90)">⟳ Rotate</button>
                        <button class="btn btn-sm btn-secondary" onclick="zoomImg(0.1)">＋ Zoom In</button>
                        <button class="btn btn-sm btn-secondary" onclick="zoomImg(-0.1)">－ Zoom Out</button>
                        <button class="btn btn-sm btn-danger" onclick="resetImg()">Reset</button>
                    </div>

                    <div class="modal-body text-center">
                        <img id="modalImage" src="" class="img-fluid" style="max-height: 80vh;">
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @push('scripts')
        <script>
            $(document).on('click', '.preview-img', function() {
                let src = $(this).data('src');
                let title = $(this).data('title') ?? 'Preview Dokumen';

                $('#modalImage').attr('src', src);
                $('#modalTitle').text(title);
                $('#modalPreview').modal('show');
            });
        </script>

        @push('scripts')
            <script>
                let scale = 1;
                let rotate = 0;

                function applyTransform() {
                    $('#modalImage').css('transform', `scale(${scale}) rotate(${rotate}deg)`);
                }

                function rotateImg(deg) {
                    rotate += deg;
                    applyTransform();
                }

                function zoomImg(val) {
                    scale += val;
                    if (scale < 0.2) scale = 0.2;
                    applyTransform();
                }

                function resetImg() {
                    scale = 1;
                    rotate = 0;
                    applyTransform();
                }

                // reset otomatis saat modal ditutup
                $('#modalPreview').on('hidden.bs.modal', function() {
                    resetImg();
                });
            </script>
        @endpush
    @endpush

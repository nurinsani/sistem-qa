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
            z-index: 1056;
            /* di atas modal-body */
            border-bottom: 1px solid #ddd;
        }

        .image-container {
            height: 80vh;
            overflow: auto;
            /* scroll kalau gambar besar */
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f8f9fa;
        }

        #modalImage {
            max-width: none;
            /* penting supaya zoom bener */
            max-height: none;
            transition: transform .2s;
            transform-origin: center center;
            cursor: grab;
        }
    </style>

    <div class="container-fluid">

        <a href="{{ url()->previous() }}" class="btn btn-primary mb-3">
            ← Kembali
        </a>

        @include('tanggapan.form_hasil_audit')
        @include('tanggapan.modal_foto_hasil_audit')
        @include('tanggapan.modal_temuan_lain')
        @include('tanggapan.modal_ketentuan_temuan')

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Tanggapan</h3>
            </div>
            <div class="card-body">

                <form action="{{ route('tanggapan.store', $audit->id_audit) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggapan AO</label>
                                <textarea name="tanggapan_ao" id="tanggapan_ao" cols="10" rows="3"
                                    class="form-control @error('tanggapan_ao') is-invalid @enderror">{{ old('tanggapan_ao', $tanggapan->tanggapan_ao ?? '') }}</textarea>
                                @error('tanggapan_ao')
                                    <div class="invalid-feedback mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Tanggapan MM</label>
                                <textarea name="tanggapan_mm" id="tanggapan_mm" cols="10" rows="3"
                                    class="form-control @error('tanggapan_mm') is-invalid @enderror">{{ old('tanggapan_mm', $tanggapan->tanggapan_mm ?? '') }}</textarea>
                                @error('tanggapan_mm')
                                    <div class="invalid-feedback mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Tanggapan BM</label>
                                <textarea name="tanggapan_bm" id="tanggapan_bm" cols="10" rows="3"
                                    class="form-control @error('tanggapan_bm') is-invalid @enderror">{{ old('tanggapan_bm', $tanggapan->tanggapan_bm ?? '') }}</textarea>
                                @error('tanggapan_bm')
                                    <div class="invalid-feedback mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>


                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label>Tindak Lanjut</label>
                                <textarea name="tindak_lanjut" id="tindak_lanjut" cols="10" rows="3"
                                    class="form-control @error('tindak_lanjut') is-invalid @enderror">{{ old('tindak_lanjut', $tanggapan->tindak_lanjut ?? '') }}</textarea>
                                @error('tindak_lanjut')
                                    <div class="invalid-feedback mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Tgl Penyelesaian</label>
                                <input type="date" name="due_date" id="due_date"
                                    class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date', $tanggapan->due_date ?? '') }}">
                                @error('due_date')
                                    <div class="invalid-feedback mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div> 
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                    
                </form>
            </div>
        </div>

        @include('tanggapan.informasi_anggota')

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Dokumen Anggota</h3>
            </div>

            <div class="card-body">
                <div class="row text-center">

                    <div class="col-md-3 mb-3">
                        <h6>Foto</h6>
                        @if (!empty($dokumen_api['poto']))
                            <img src="{{ $baseFile . $dokumen_api['poto'] }}"
                                class="img-fluid img-thumbnail doc-img preview-img"
                                data-src="{{ $baseFile . $dokumen_api['poto'] }}" data-title="Dokumen Foto">
                        @else
                            <p class="text-muted">Tidak tersedia</p>
                        @endif
                    </div>

                    <div class="col-md-3 mb-3">
                        <h6>KTP</h6>
                        @if (!empty($dokumen_api['ktp']))
                            <img src="{{ $baseFile . $dokumen_api['ktp'] }}"
                                class="img-fluid img-thumbnail doc-img preview-img"
                                data-src="{{ $baseFile . $dokumen_api['ktp'] }}" data-title="Dokumen KTP">
                        @else
                            <p class="text-muted">Tidak tersedia</p>
                        @endif
                    </div>


                    <div class="col-md-3 mb-3">
                        <h6>KK</h6>
                        @if (!empty($dokumen_api['kk']))
                            <img src="{{ $baseFile . $dokumen_api['kk'] }}"
                                class="img-fluid img-thumbnail doc-img preview-img"
                                data-src="{{ $baseFile . $dokumen_api['kk'] }}" data-title="Dokumen KK">
                        @else
                            <p class="text-muted">Tidak tersedia</p>
                        @endif
                    </div>

                    <div class="col-md-3 mb-3">
                        <h6>Penjamin</h6>
                        @if (!empty($dokumen_api['penjamin']))
                            <img src="{{ $baseFile . $dokumen_api['penjamin'] }}"
                                class="img-fluid img-thumbnail doc-img preview-img"
                                data-src="{{ $baseFile . $dokumen_api['penjamin'] }}" data-title="Dokumen Penjamin">
                        @else
                            <p class="text-muted">Tidak tersedia</p>
                        @endif
                    </div>

                    <div class="col-md-3 mb-3">
                        <h6>Foto Usaha</h6>
                        @if (!empty($dokumen_api['usaha']))
                            <img src="{{ $baseFile . $dokumen_api['usaha'] }}"
                                class="img-fluid img-thumbnail doc-img preview-img"
                                data-src="{{ $baseFile . $dokumen_api['usaha'] }}" data-title="Dokumen Foto Usaha">
                        @else
                            <p class="text-muted">Tidak tersedia</p>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="modalPreview" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Preview Dokumen</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="preview-toolbar text-center py-2">
                        <button class="btn btn-sm btn-secondary" id="zoomIn">+ Zoom</button>
                        <button class="btn btn-sm btn-secondary" id="zoomOut">- Zoom</button>
                        <button class="btn btn-sm btn-secondary" id="rotateLeft">⟲ Rotate</button>
                        <button class="btn btn-sm btn-secondary" id="rotateRight">⟳ Rotate</button>
                        <button class="btn btn-sm btn-danger" id="resetImage">Reset</button>
                    </div>

                    <div class="modal-body text-center">
                        <img id="modalImage" src="" class="img-fluid"
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

                @if (!empty($dokumen_api['form']))
                    <a href="{{ $baseFile . $dokumen_api['form'] }}" target="_blank"
                        class="btn btn-primary btn-block mb-2">
                        📄 Form Anggota
                    </a>
                @endif

                @if (!empty($dokumen_api['murabahah']))
                    <a href="{{ $baseFile . $dokumen_api['murabahah'] }}" target="_blank"
                        class="btn btn-success btn-block mb-2">
                        📄 Akad Murabahah
                    </a>
                @endif

                @if (!empty($dokumen_api['wakalah']))
                    <a href="{{ $baseFile . $dokumen_api['wakalah'] }}" target="_blank"
                        class="btn btn-warning btn-block">
                        📄 Akad Wakalah
                    </a>
                @endif

            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let scale = 1;
        let rotate = 0;

        $(document).on('click', '.preview-img', function() {
            let src = $(this).data('src');
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
        $('#zoomIn').click(function() {
            scale += 0.2;
            updateTransform();
        });

        // Zoom Out
        $('#zoomOut').click(function() {
            if (scale > 0.4) {
                scale -= 0.2;
                updateTransform();
            }
        });

        // Rotate Left
        $('#rotateLeft').click(function() {
            rotate -= 90;
            updateTransform();
        });

        // Rotate Right
        $('#rotateRight').click(function() {
            rotate += 90;
            updateTransform();
        });

        // Reset
        $('#resetImage').click(function() {
            scale = 1;
            rotate = 0;
            updateTransform();
        });

        function updateTransform() {
            $('#modalImage').css('transform', `scale(${scale}) rotate(${rotate}deg)`);
        }
    </script>
@endpush

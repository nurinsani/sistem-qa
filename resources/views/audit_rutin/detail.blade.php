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
        <div class="row">
            @include('audit_rutin.informasi_anggota')

            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Form Audit</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('audit.rutin.tambah', $data_sampling_detail->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="id_ref_sampling" value="{{ $data_sampling_detail->id_ref_sampling }}">
                            <input type="hidden" name="cif" value="{{ $data_sampling_detail->cif }}">

                            <div class="form-group">
                                <label>Tanggal Kunjungan</label>
                                <input type="date" name="tanggal_kunjungan" id="tanggal_kunjungan"
                                    class="form-control @error('tanggal_kunjungan') is-invalid @enderror" value="{{ old('tanggal_kunjungan') }}">
                                @error('tanggal_kunjungan')
                                    <div class="invalid-feedback mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Observasi --}}
                            <h5>Observasi</h5>
                            <div class="form-group">
                                <label>Kondisi Usaha</label>
                                <textarea name="kondisi_usaha" id="kondisi_usaha" cols="10" rows="3"
                                    class="form-control @error('kondisi_usaha') is-invalid @enderror">{{ old('kondisi_usaha') }}</textarea>
                                @error('kondisi_usaha')
                                    <div class="invalid-feedback mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Kondisi Keluarga</label>
                                <textarea name="kondisi_keluarga" id="kondisi_keluarga" cols="10" rows="3"
                                    class="form-control @error('kondisi_keluarga') is-invalid @enderror">{{ old('kondisi_keluarga') }}</textarea>
                                @error('kondisi_keluarga')
                                    <div class="invalid-feedback mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Kondisi Lingkungan</label>
                                <textarea name="kondisi_lingkungan" id="kondisi_lingkungan" cols="10" rows="3"
                                    class="form-control @error('kondisi_lingkungan') is-invalid @enderror">{{ old('kondisi_lingkungan') }}</textarea>
                                @error('kondisi_lingkungan')
                                    <div class="invalid-feedback mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Wawancara --}}
                            <h5>Wawancara</h5>
                            <div class="form-group">
                                <label>Foto Wawancara Anggota</label>
                                <input type="file" name="foto_wawancara_anggota"
                                    id="foto_wawancara_anggota"class="form-control @error('foto_wawancara_anggota') is-invalid @enderror">
                                @error('foto_wawancara_anggota')
                                    <div class="invalid-feedback mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Foto Wawancara Ketua Kelompok</label>
                                <input type="file" name="foto_wawancara_ketua" id="foto_wawancara_ketua"
                                    class="form-control @error('foto_wawancara_ketua') is-invalid @enderror">
                                @error('foto_wawancara_ketua')
                                    <div class="invalid-feedback mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Foto Usaha</label>
                                <input type="file" name="foto_usaha" id="foto_usaha"
                                    class="form-control @error('foto_usaha') is-invalid @enderror">
                                @error('foto_usaha')
                                    <div class="invalid-feedback mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Temuan</label>

                                <div class="d-flex">
                                    <textarea name="temuan" id="temuan" class="form-control @error('temuan') is-invalid @enderror" rows="3">{{ old('temuan') }}</textarea>

                                    <button type="button" 
                                            class="btn btn-danger btn-sm ml-2 align-self-start"
                                            data-toggle="modal" 
                                            data-target="#modalKetentuan">
                                        Ketentuan
                                    </button>
                                </div>

                                @error('temuan')
                                    <div class="invalid-feedback d-block mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="button"
                                    class="btn btn-primary btn-sm"
                                    data-toggle="modal"
                                    data-target="#modalTemuanLain">
                                    Temuan Lain
                                </button>
                                <small class="form-text text-muted">*Jika ada temuan lainnya, klik button ini.</small>

                            </div>

                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <button type="reset" class="btn btn-warning">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @include('audit_rutin.modal_ketentuan')
        @include('audit_rutin.modal_temuan_lain')
        @include('audit_rutin.modal_ketentuan_temuan_lain')

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

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Dokumen Anggota</h3>
            </div>

            <div class="card-body">
                <div class="row text-center">

                    {{-- Foto --}}
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

                    {{-- KTP --}}
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


                    {{-- KK --}}
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

                    {{-- Penjamin --}}
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

                    {{-- Foto Usaha --}}
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

        {{-- histori audit --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">History Audit</h3>
            </div>

            <div class="card-body">
                @if ($history_audit->isEmpty())
                    <p class="text-muted">Belum ada history audit</p>
                @else
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>ID Ref Sampling</th>
                                <th>Jenis Audit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($history_audit as $item)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i') }}</td>
                                    <td>{{ $item->id_ref_sampling ?? '-' }}</td>
                                    <td>{{ $item->jenis_audit ?? '-' }}</td>
                                    <td>
                                        <a href="{{ url('/qa/audit-rutin/history/' . $item->id . '/' . $item->cif) }}" class="btn btn-info btn-sm">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
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
                        <img id="modalImage" src="" class="img-fluid"
                            style="max-height: 80vh; transition: transform .2s;">
                    </div>
                </div>
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

        function showKetentuan(id) {

            // sembunyikan semua group
            document.querySelectorAll('.ketentuan-group').forEach(el => {
                el.classList.add('d-none');
            });

            // tampilkan yang dipilih
            document.getElementById(id).classList.remove('d-none');

            // ubah active sidebar
            document.querySelectorAll('#sidebarKetentuan a').forEach(el => {
                el.classList.remove('active');
            });

            event.target.classList.add('active');
        }

        document.getElementById('searchKetentuan').addEventListener('keyup', function() {

            let keyword = this.value.toLowerCase();

            document.querySelectorAll('.ketentuan-item').forEach(function(item) {

                let text = item.innerText.toLowerCase();

                if (text.includes(keyword)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }

            });

        });

        function submitKetentuan() {

            let form = document.querySelector('#formKetentuan');
            let idRef = document.querySelector('input[name="id_ref_sampling"]').value;
            let cif = document.querySelector('input[name="cif"]').value;

            if (!form) {
                alert('Form tidak ditemukan');
                return;
            }

            let formData = new FormData(form);

            fetch("{{ route('audit.rutin.ketentuan.store', ['id_ref_sampling' => $data_sampling_detail->id_ref_sampling, 'cif' => $data_sampling_detail->cif]) }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {

                if (data.status === 'success') {

                    $('#modalKetentuan').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    });

                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Gagal',
                        text: data.message ?? 'Terjadi kesalahan'
                    });
                }

            })
            .catch(error => {
                console.error(error);

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan pada server'
                });
            });
        }

        $('#formTemuanLain').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serialize();

            Swal.fire({
                title: 'Menyimpan...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "{{ route('audit.rutin.temuan-lain.store', ['id_ref_sampling' => $data_sampling_detail->id_ref_sampling, 'cif' => $data_sampling_detail->cif]) }}",
                method: "POST",
                data: formData,
                success: function(response) {

                    // Tutup modal
                    $('#modalTemuanLain').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message ?? 'Data berhasil disimpan',
                        timer: 1500,
                        showConfirmButton: false
                    });

                },
                error: function(xhr) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan'
                    });
                }
            });
        });

        $(document).on('click', '.btnKetentuan', function() {

            let paramId = $(this).data('id');

            $.ajax({
                url: "{{ route('param.ketentuan.get', ':id') }}".replace(':id', paramId),
                method: 'GET',
                success: function(response) {

                    let html = '';

                    if (response.length === 0) {
                        html = '<p class="text-muted">Tidak ada ketentuan</p>';
                    } else {

                        response.forEach(function(item) {

                            html += `
                                <div class="mb-4">
                                    <h6 class="font-weight-bold">
                                        ${item.heading ?? ''}
                                    </h6>

                                    <p class="font-weight-bold mb-1">
                                        ${item.nomor_ketentuan ?? ''}
                                    </p>

                                    <p>
                                        ${item.sub_sub_sub_heading ?? ''}
                                    </p>

                                </div>
                            `;
                        });

                    }

                    $('#isiKetentuanContent').html(html);
                    $('#modalIsiKetentuan').modal('show');

                }
            });

        });
    </script>

@endpush

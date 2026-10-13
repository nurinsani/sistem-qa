@extends('layouts.main')

@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-bold">{{ $title }}</h1>
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

@push('styles')
    <style>
        /* Responsive adjustments for mobile */
        @media (max-width: 767.98px) {
            .filter-actions {
                margin-top: 10px;
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
            }
            .filter-actions .btn {
                flex: 1 1 auto;
            }
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_length {
                text-align: left !important;
                float: none !important;
            }
            .dataTables_wrapper .dataTables_filter input {
                width: 100% !important;
                margin-left: 0 !important;
                margin-top: 4px;
            }
        }
        #buktiSetorTable th,
        #buktiSetorTable td {
            white-space: nowrap;
            vertical-align: middle;
        }
        .img-preview-thumb {
            width: 45px;
            height: 45px;
            min-width: 45px;
            min-height: 45px;
            object-fit: cover;
            border-radius: 4px;
            display: block;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter & Export</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('rekap-mobcol.bukti-setor.export') }}" method="GET" id="form-filter">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
                            <div class="form-group mb-md-0">
                                <label for="filter_tanggal">Tanggal Setor:</label>
                                <input type="date" id="filter_tanggal" name="tanggal" class="form-control"
                                    value="{{ date('Y-m-d', strtotime('-1 day')) }}">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-2 mb-md-0">
                            <div class="form-group mb-md-0">
                                <label for="filter_cao">AO:</label>
                                <select id="filter_cao" name="cao" class="form-control select2" style="width: 100%;">
                                    <option value="">-- Semua AO --</option>
                                    @foreach ($aoList as $ao)
                                        <option value="{{ $ao->cao }}">{{ $ao->cao }} - {{ $ao->nama_ao }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5 d-flex align-items-end">
                            <div class="form-group mb-0 w-100 filter-actions">
                                <button type="button" id="btn-filter" class="btn btn-primary btn-sm mr-md-2">
                                    <i class="fas fa-search mr-1"></i> Filter
                                </button>
                                <button type="button" id="btn-reset" class="btn btn-secondary btn-sm mr-md-2">
                                    <i class="fas fa-undo mr-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-file-excel mr-1"></i> Export Excel
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-receipt mr-1"></i> Data Bukti Setor</h3>
            </div>
            <div class="card-body p-2 p-md-3">
                <div class="table-responsive">
                    <table id="buktiSetorTable" class="table table-bordered table-striped table-hover text-sm"
                        style="width:100%">
                        <thead class="bg-light">
                            <tr>
                                <th>NO</th>
                                <th>TGL SETOR</th>
                                <th>KODE AO</th>
                                <th>NAMA AO</th>
                                <th>BANK</th>
                                <th>NOMINAL</th>
                                <th>BUKTI SETOR</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Preview Gambar -->
    <div class="modal fade" id="modalPreviewGambar" tabindex="-1" role="dialog" aria-labelledby="modalPreviewLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPreviewLabel">Bukti Setor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center p-2">
                    <img id="imgPreview" src="" alt="Bukti Setor" class="img-fluid rounded shadow-sm"
                        style="max-height: 80vh; object-fit: contain;">
                </div>
                <div class="modal-footer flex-column flex-sm-row justify-content-end">
                    <button type="button" id="btnDownloadImg" class="btn btn-success btn-sm w-100 w-sm-auto mb-2 mb-sm-0">
                        <i class="fas fa-download mr-1"></i> Download Gambar
                    </button>
                    <a id="btnOpenNewTab" href="#" target="_blank" class="btn btn-primary btn-sm w-100 w-sm-auto mb-2 mb-sm-0 mr-sm-2">
                        <i class="fas fa-external-link-alt mr-1"></i> Buka di Tab Baru
                    </a>
                    <button type="button" class="btn btn-secondary btn-sm w-100 w-sm-auto" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4'
            });

            let table = $('#buktiSetorTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('rekap-mobcol.bukti-setor.index') }}",
                    data: function(d) {
                        d.tanggal = $('#filter_tanggal').val();
                        d.cao = $('#filter_cao').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'tgl_setor',
                        name: 'bukti_setor.tgl_setor',
                        className: 'text-center'
                    },
                    {
                        data: 'cao',
                        name: 'bukti_setor.cao',
                        className: 'text-center'
                    },
                    {
                        data: 'nama_ao',
                        name: 'ao.nama_ao'
                    },
                    {
                        data: 'bank',
                        name: 'bukti_setor.bank'
                    },
                    {
                        data: 'nominal',
                        name: 'nominal',
                        className: 'text-right'
                    },
                    {
                        data: 'image',
                        name: 'bukti_setor.image',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            if (!data) {
                                return '<span class="badge badge-secondary">Tidak ada gambar</span>';
                            }

                            let imageUrl = 'http://mobcoll.nurinsani.co.id/apimobcol/uploads/' + data;
                            return `
                                <div class="d-inline-flex align-items-center justify-content-center">
                                    <a href="javascript:void(0)" class="btn-preview-img mr-2" data-img="${imageUrl}" data-filename="${data}" title="Lihat Bukti Setor">
                                        <img src="${imageUrl}" alt="Bukti Setor" class="img-preview-thumb border" style="width: 42px; height: 42px; min-width: 42px; object-fit: cover; cursor: pointer;">
                                    </a>
                                    <button type="button" class="btn btn-outline-success btn-xs btn-download-file" data-url="${imageUrl}" data-filename="${data}" title="Download Gambar">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
                autoWidth: false,
                scrollX: true,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Semua"]
                ],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    zeroRecords: "Tidak ada data yang ditemukan",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    }
                }
            });

            $('#btn-filter').click(function() {
                table.draw();
            });

            $('#btn-reset').click(function() {
                $('#filter_tanggal').val("{{ date('Y-m-d', strtotime('-1 day')) }}");
                $('#filter_cao').val('').trigger('change');
                table.draw();
            });

            // Function helper untuk trigger download image
            function downloadImage(url, filename) {
                fetch(url)
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.blob();
                    })
                    .then(blob => {
                        const blobUrl = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.style.display = 'none';
                        a.href = blobUrl;
                        a.download = filename || 'bukti-setor.jpg';
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(blobUrl);
                        document.body.removeChild(a);
                    })
                    .catch(err => {
                        // Fallback jika terjadi CORS: buka download di tab baru atau window.open
                        const a = document.createElement('a');
                        a.href = url;
                        a.target = '_blank';
                        a.download = filename || 'bukti-setor.jpg';
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                    });
            }

            $(document).on('click', '.btn-preview-img', function() {
                let imgUrl = $(this).data('img');
                let fileName = $(this).data('filename');
                $('#imgPreview').attr('src', imgUrl);
                $('#btnOpenNewTab').attr('href', imgUrl);
                $('#btnDownloadImg').data('url', imgUrl).data('filename', fileName);
                $('#modalPreviewGambar').modal('show');
            });

            $(document).on('click', '#btnDownloadImg', function() {
                let url = $(this).data('url');
                let filename = $(this).data('filename');
                downloadImage(url, filename);
            });

            $(document).on('click', '.btn-download-file', function() {
                let url = $(this).data('url');
                let filename = $(this).data('filename');
                downloadImage(url, filename);
            });
        });
    </script>
@endpush

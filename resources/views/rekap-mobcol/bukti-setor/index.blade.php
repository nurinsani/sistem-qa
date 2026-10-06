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

@section('content')
    <div class="container-fluid">
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title">Filter & Export</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('rekap-mobcol.bukti-setor.export') }}" method="GET" id="form-filter">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group mb-md-0">
                                <label for="filter_tanggal">Tanggal Setor:</label>
                                <input type="date" id="filter_tanggal" name="tanggal" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-9 d-flex align-items-end">
                            <div class="form-group mb-0">
                                <button type="button" id="btn-filter" class="btn btn-primary btn-sm mr-2">
                                    <i class="fas fa-search mr-1"></i> Filter
                                </button>
                                <button type="button" id="btn-reset" class="btn btn-secondary btn-sm mr-2">
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
                <h3 class="card-title">Data Bukti Setor</h3>
            </div>
            <div class="card-body">
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let table = $('#buktiSetorTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('rekap-mobcol.bukti-setor.index') }}",
                    data: function(d) {
                        d.tanggal = $('#filter_tanggal').val();
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
                        name: 'bukti_setor.tgl_setor'
                    },
                    {
                        data: 'cao',
                        name: 'bukti_setor.cao'
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
                        name: 'nominal'
                    },
                    {
                        data: 'image',
                        name: 'bukti_setor.image'
                    }
                ],
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
                $('#filter_tanggal').val('');
                table.draw();
            });
        });
    </script>
@endpush

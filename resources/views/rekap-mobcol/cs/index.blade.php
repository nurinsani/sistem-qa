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
                <form action="{{ route('rekap-mobcol.cs.export') }}" method="GET" id="form-filter">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group mb-md-0">
                                <label for="filter_tanggal">Tanggal Tagih:</label>
                                <input type="date" id="filter_tanggal" name="tanggal" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-md-0">
                                <label for="filter_kode_branch">Unit:</label>
                                <select id="filter_kode_branch" name="kode_branch" class="form-control select2"
                                    style="width: 100%;">
                                    <option value="">-- Semua Unit --</option>
                                    @foreach ($units as $u)
                                        <option value="{{ $u->kode_branch }}">{{ $u->kode_branch }} - {{ $u->unit }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5 d-flex align-items-end">
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
                <h3 class="card-title">Data CS</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="csTable" class="table table-bordered table-striped table-hover text-sm" style="width:100%">
                        <thead class="bg-light">
                            <tr>
                                <th>No</th>
                                <th>CIF</th>
                                <th>NAMA</th>
                                <th>KODE KEL</th>
                                <th>BULAT</th>
                                <th>NAMA KEL</th>
                                <th>CAO</th>
                                <th>OS</th>
                                <th>SALDO MARGIN</th>
                                <th>STATUS TRANS</th>
                                <th>NOMINAL</th>
                                <th>STATUS APPROVE</th>
                                <th>TGL TAGIH</th>
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
            $('.select2').select2({
                theme: 'bootstrap4'
            });

            let table = $('#csTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('rekap-mobcol.cs.index') }}",
                    data: function(d) {
                        d.tanggal = $('#filter_tanggal').val();
                        d.kode_branch = $('#filter_kode_branch').val();
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
                        data: 'cif',
                        name: 'cif'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'kode_kel',
                        name: 'kode_kel'
                    },
                    {
                        data: 'bulat',
                        name: 'bulat',
                        className: 'text-right'
                    },
                    {
                        data: 'nama_kel',
                        name: 'nama_kel'
                    },
                    {
                        data: 'cao',
                        name: 'cao'
                    },
                    {
                        data: 'os',
                        name: 'os',
                        className: 'text-right'
                    },
                    {
                        data: 'saldo_margin',
                        name: 'saldo_margin',
                        className: 'text-right'
                    },
                    {
                        data: 'status_trans',
                        name: 'status_trans',
                        className: 'text-center'
                    },
                    {
                        data: 'nominal',
                        name: 'nominal',
                        className: 'text-right'
                    },
                    {
                        data: 'status_approve',
                        name: 'status_approve',
                        className: 'text-center'
                    },
                    {
                        data: 'tgl_tagih',
                        name: 'tgl_tagih',
                        className: 'text-center'
                    },
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
                $('#filter_kode_branch').val('').trigger('change');
                table.draw();
            });
        });
    </script>
@endpush

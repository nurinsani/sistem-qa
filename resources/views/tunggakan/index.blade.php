@extends('layouts.main')

@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-exclamation-triangle text-warning mr-2"></i>{{ $title }}</h1>
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
        <!-- Filter Section -->
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter Data</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-md-0">
                            <label for="filter_unit">Unit / Branch:</label>
                            <select id="filter_unit" class="form-control select2" style="width: 100%;">
                                <option value="">-- Semua Unit --</option>
                                @foreach ($units as $u)
                                    <option value="{{ $u->kode_unit }}">
                                        {{ $u->kode_unit }} {{ $u->nama_unit ? '- ' . $u->nama_unit : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-group mb-0">
                            <button type="button" id="btn-filter" class="btn btn-primary btn-sm mr-2">
                                <i class="fas fa-search mr-1"></i> Filter
                            </button>
                            <button type="button" id="btn-reset" class="btn btn-secondary btn-sm">
                                <i class="fas fa-undo mr-1"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card card-outline card-navy">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-table mr-1"></i> Data Tunggakan</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tunggakanTable" class="table table-bordered table-striped table-hover text-sm" style="width:100%">
                        <thead class="bg-light">
                            <tr>
                                <th>No</th>
                                <th>Unit</th>
                                <th>Kelompok</th>
                                <th>Nama AO</th>
                                <th>CIF</th>
                                <th>Nama</th>
                                <th>FT</th>
                                <th>Nominal</th>
                                <th>Plafond</th>
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

            let table = $('#tunggakanTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('tunggakan.data') }}",
                    data: function(d) {
                        d.unit = $('#filter_unit').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                    { data: 'unit', name: 'unit' },
                    { data: 'nama_kel', name: 'kelompok.nama_kel' },
                    { data: 'nama_ao', name: 'ao.nama_ao' },
                    { data: 'cif', name: 'cif', className: 'font-weight-bold text-center' },
                    { data: 'Cust_Short_name', name: 'Cust_Short_name' },
                    { data: 'ft', name: 'ft', className: 'text-center' },
                    { data: 'nominal', name: 'nominal', className: 'text-right font-weight-bold text-danger' },
                    { data: 'plafond', name: 'plafond', className: 'text-right' }
                ],
                order: [[1, 'desc']],
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
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
                $('#filter_unit').val('').trigger('change');
                table.draw();
            });
        });
    </script>
@endpush

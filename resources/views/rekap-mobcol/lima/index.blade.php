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
                <form action="{{ route('rekap-mobcol.lima.export') }}" method="GET" id="form-filter">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group mb-md-0">
                                <label for="filter_tanggal">Tanggal:</label>
                                <input type="date" id="filter_tanggal" name="tanggal" class="form-control" value="{{ date('Y-m-d', strtotime('-1 day')) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-md-0">
                                <label for="filter_kode_branch">Unit:</label>
                                <select id="filter_kode_branch" name="kode_branch" class="form-control select2"
                                    style="width: 100%;">
                                    <option value="">-- Semua Unit --</option>
                                    @foreach ($units as $u)
                                        <option value="{{ $u->kode_branch }}" {{ $u->kode_branch == '001' ? 'selected' : '' }}>{{ $u->kode_branch }} - {{ $u->unit }}
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
                <h3 class="card-title">Data 5%</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="limaTable" class="table table-bordered table-striped table-hover text-sm" style="width:100%">
                        <thead class="bg-light">
                            <tr>
                                <th>NOMOR</th>
                                <th>TGL TAGIH</th>
                                <th>KELOMPOK</th>
                                <th>KODE KELOMPOK</th>
                                <th>AO</th>
                                <th>CIF</th>
                                <th>NAMA</th>
                                <th>TWM</th>
                                <th>POKOK</th>
                                <th>WAJIB</th>
                                <th>KE</th>
                                <th>PB</th>
                                <th>TAGIHAN</th>
                                <th>NYATA</th>
                                <th>STATUS TRANSAKSI</th>
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

            let table = $('#limaTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('rekap-mobcol.lima.index') }}",
                    data: function(d) {
                        d.tanggal = $('#filter_tanggal').val();
                        d.kode_branch = $('#filter_kode_branch').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                    { data: 'tgl_tagih', name: 'tgl_tagih', className: 'text-center', defaultContent: '' },
                    { data: 'nama_kel', name: 'nama_kel', defaultContent: '' },
                    { data: 'kode_kel', name: 'kode_kel', defaultContent: '' },
                    { data: 'nama_ao', name: 'nama_ao', defaultContent: '' },
                    { data: 'cif', name: 'cif', defaultContent: '' },
                    { data: 'nama', name: 'nama', defaultContent: '' },
                    { data: 'twm', name: 'twm', render: $.fn.dataTable.render.number(',', '.', 0, ''), className: 'text-right', defaultContent: '' },
                    { data: 'simpanan_pokok', name: 'simpanan_pokok', render: $.fn.dataTable.render.number(',', '.', 0, ''), className: 'text-right', defaultContent: '' },
                    { data: 'simpanan_wajib', name: 'simpanan_wajib', render: $.fn.dataTable.render.number(',', '.', 0, ''), className: 'text-right', defaultContent: '' },
                    { data: 'ke', name: 'ke', className: 'text-center', defaultContent: '' },
                    { data: 'pb', name: 'pb', className: 'text-center', defaultContent: '' },
                    { data: 'bulat', name: 'bulat', render: $.fn.dataTable.render.number(',', '.', 0, ''), className: 'text-right', defaultContent: '' },
                    { data: 'nominal', name: 'nominal', render: $.fn.dataTable.render.number(',', '.', 0, ''), className: 'text-right', defaultContent: '' },
                    { data: 'status_trans', name: 'status_trans', className: 'text-center', defaultContent: '' }
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
                $('#filter_tanggal').val("{{ date('Y-m-d', strtotime('-1 day')) }}");
                $('#filter_kode_branch').val('001').trigger('change');
                table.draw();
            });
        });
    </script>
@endpush

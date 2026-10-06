@extends('layouts.main')

@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ $title }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('qa.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Filter & Datatables Section -->
        <div class="card card-outline card-success mt-4">
            <div class="card-header">
                <h3 class="card-title">Data Sampling <span id="label-bulan"
                        class="font-weight-bold">{{ $namaBulan ? '- Bulan ' . $namaBulan : '' }}</span></h3>
            </div>
            <div class="card-body">
                <form id="form-filter">
                    <input type="hidden" id="filter_bulan" value="{{ $bulan }}">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group mb-md-0">
                                <label for="filter_tanggal">Tanggal:</label>
                                <input type="date" id="filter_tanggal" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-md-0">
                                <label for="filter_status">Status:</label>
                                <select id="filter_status" class="form-control">
                                    <option value="">-- Semua Status --</option>
                                    <option value="pending">Pending</option>
                                    <option value="proses">Proses</option>
                                    <option value="tanggapan">Tanggapan</option>
                                    <option value="evaluasi">Evaluasi</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-group mb-0">
                                <button type="button" id="btn-filter" class="btn btn-primary btn-sm mr-2">
                                    <i class="fas fa-search mr-1"></i> Filter
                                </button>
                                <button type="button" id="btn-reset" class="btn btn-secondary btn-sm mr-2">
                                    <i class="fas fa-undo mr-1"></i> Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table id="samplingTable" class="table table-bordered table-striped table-hover text-sm"
                        style="width:100%">
                        <thead class="bg-light">
                            <tr>
                                <th>NO</th>
                                <th>TANGGAL SAMPLING</th>
                                <th>ID SAMPLING</th>
                                <th>NAMA NASABAH</th>
                                <th>JENIS AUDIT</th>
                                <th>STATUS</th>
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
            let table = $('#samplingTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('qa.dashboard.detail') }}",
                    data: function(d) {
                        d.bulan = $('#filter_bulan').val();
                        d.tanggal = $('#filter_tanggal').val();
                        d.status = $('#filter_status').val();
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
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'id_ref_sampling',
                        name: 'id_ref_sampling'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'jenis_audit',
                        name: 'jenis_audit',
                        render: function(data) {
                            return data ? data.replace(/_/g, ' ').toUpperCase() : '-';
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            return data ? data.toUpperCase() : '-';
                        }
                    }
                ],
                pageLength: 10,
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
                $('#filter_status').val('');
                table.draw();
            });
        });
    </script>
@endpush

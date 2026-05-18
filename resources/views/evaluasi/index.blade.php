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
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="dataTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Unit</th>
                                    <th>CIF</th>
                                    <th>Ref Sampling</th>
                                    <th>Nama</th>
                                    <th>Nama Kel</th>
                                    <th>Nama AO</th>
                                    <th>Kategori Audit</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: '{{ route("evaluasi.data") }}',
            columns: [
                { data: null, render: (data, type, row, meta) => meta.row + 1 },
                { data: 'branch.unit' },
                { data: 'cif' },
                { data: 'id_ref_sampling' },
                { data: 'nama' },
                { data: 'kelompok.nama_kel' },
                { data: 'ao.nama_ao', defaultContent: '-' },
                { data: 'status_sampling', render: function(data){
                    if(data === 'Low') return '<span class="badge badge-success">LOW</span>';
                    if(data === 'Moderat') return '<span class="badge badge-warning">MEDIUM</span>';
                    return '<span class="badge badge-danger">HIGH</span>';
                }},
                { data: null, render: function(data){
                    return `<a href="{{ url('/qa/evaluasi/detail/${data.id_audit}/${data.cif}') }}" class="btn btn-info btn-sm">Detail</a>`;
                }}
            ]
        });
    });
    </script>
@endpush
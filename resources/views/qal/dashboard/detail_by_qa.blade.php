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
                                    <th>Status Sampling</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($audits as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->dataSampling->branch->unit }}</td>
                                        <td>{{ $item->cif }}</td>
                                        <td>{{ $item->id_ref_sampling }}</td>
                                        <td>{{ $item->dataSampling->nama }}</td>
                                        <td>{{ $item->dataSampling->kelompok->nama_kel }}</td>
                                        <td>{{ $item->dataSampling->ao->nama_ao }}</td>
                                        <td>{{ $item->dataSampling->jenis_audit }}</td>
                                        <td>{{ $item->dataSampling->status_sampling }}</td>
                                        <td>{{ $item->dataSampling->status }}</td>
                                        <td>
                                            <a href="{{ route('qal.dashboard.detailAudit', ['id' => $item->id, 'cif' => $item->cif]) }}" class="btn btn-primary btn-sm">Lihat</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
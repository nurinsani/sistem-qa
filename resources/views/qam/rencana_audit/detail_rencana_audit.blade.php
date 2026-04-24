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

        <a href="{{ url('/qam/rencana-audit') }}" class="btn btn-primary mb-3">
            ← Kembali
        </a>

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
                            <tbody>
                                {{-- @dd($data_sampling) --}}
                                @foreach ($data_sampling as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->branch->unit }}</td>
                                        <td>{{ $item->cif }}</td>
                                        <td>{{ $item->id_ref_sampling }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->kelompok->nama_kel }}</td>
                                        <td>{{ $item->ao->nama_ao ?? '-' }}</td>
                                        <td>
                                            @if ($item->status_sampling === 'LOW')
                                                <span class="badge badge-success">LOW</span>
                                            @elseif ($item->status_sampling === 'MEDIUM')
                                                <span class="badge badge-warning">MEDIUM</span>
                                            @elseif ($item->status_sampling === 'HIGH')
                                                <span class="badge badge-danger">HIGH</span>
                                            @else
                                                <span class="badge badge-danger">HIGH</span>
                                            @endif
                                        <td>
                                            <a href="{{ route('qam.rencana.audit.detail_sampling', ['ref_sampling' => $item->id_ref_sampling, 'cif' => $item->cif]) }}" class="btn btn-info btn-sm">
                                                Detail
                                            </a>
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
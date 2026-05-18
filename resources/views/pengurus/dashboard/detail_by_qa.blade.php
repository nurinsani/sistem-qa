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
        <div class="col-12 col-sm">
            <div class="card card-success card-outline card-tabs">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill"
                                href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home"
                                aria-selected="true">Selesai</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill"
                                href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile"
                                aria-selected="false">Proses</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-three-tabContent">
                        {{-- tab selesai --}}
                        <div class="tab-pane fade show active" id="custom-tabs-three-home" role="tabpanel"
                            aria-labelledby="custom-tabs-three-home-tab">
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
                                            <td>{{ $item->unit }}</td>
                                            <td>{{ $item->cif }}</td>
                                            <td>{{ $item->id_ref_sampling }}</td>
                                            <td>{{ $item->nama }}</td>
                                            <td>{{ $item->nama_kel }}</td>
                                            <td>{{ $item->nama_ao }}</td>
                                            <td>
                                                @if ($item->jenis_audit === 'audit_khusus')
                                                    <span class="badge badge-primary">KHUSUS</span>
                                                @elseif ($item->jenis_audit === 'audit_rutin')
                                                    <span class="badge badge-secondary">RUTIN</span>
                                                @endif
                                            </td>
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
                                            </td>
                                            <td>
                                                @if ($item->status === 'proses')
                                                    <span class="badge badge-primary">PROSES</span>
                                                @elseif ($item->status === 'tanggapan')
                                                    <span class="badge badge-warning">TANGGAPAN</span>
                                                @elseif ($item->status === 'evaluasi')
                                                    <span class="badge badge-warning">EVALUASI</span>
                                                @else
                                                    <span class="badge badge-success">SELESAI</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('qam.dashboard.detailAudit', ['id' => $item->id, 'cif' => $item->cif]) }}"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- end tab selesai --}}
                        {{-- tab proses --}}
                        <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel"
                            aria-labelledby="custom-tabs-three-profile-tab">
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
                                    @foreach ($audit_proses as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->unit }}</td>
                                            <td>{{ $item->cif }}</td>
                                            <td>{{ $item->id_ref_sampling }}</td>
                                            <td>{{ $item->nama }}</td>
                                            <td>{{ $item->nama_kel }}</td>
                                            <td>{{ $item->nama_ao }}</td>
                                            <td>
                                                @if ($item->jenis_audit === 'audit_khusus')
                                                    <span class="badge badge-primary">KHUSUS</span>
                                                @elseif ($item->jenis_audit === 'audit_rutin')
                                                    <span class="badge badge-secondary">RUTIN</span>
                                                @endif
                                            </td>
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
                                            </td>
                                            <td>
                                                @if ($item->status === 'proses')
                                                    <span class="badge badge-primary">PROSES</span>
                                                @elseif ($item->status === 'tanggapan')
                                                    <span class="badge badge-warning">TANGGAPAN</span>
                                                @elseif ($item->status === 'evaluasi')
                                                    <span class="badge badge-warning">EVALUASI</span>
                                                @elseif ($item->status === 'pending')
                                                    <span class="badge badge-warning">PENDING</span>
                                                @else
                                                    <span class="badge badge-success">SELESAI</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('pengurus.dashboard.detailAudit', ['id' => $item->id, 'cif' => $item->cif]) }}"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- end tab proses --}}
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
@endsection

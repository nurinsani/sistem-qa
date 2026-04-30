@extends('layouts.main')

@section('content-header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>{{ $title }}</h1>
        </div>
    </div>
</div>
@endsection


@section('content')
<div class="container-fluid">

    {{-- FILTER --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Filter Laporan</h3>
        </div>

        <div class="card-body">

            <form method="GET" action="{{ route('qal.laporan.index') }}">

                <div class="row">

                    <div class="col-md-2">
                        <label>Tanggal Awal</label>
                        <input type="date" name="tgl_awal"
                            value="{{ request('tgl_awal') }}"
                            class="form-control" required>
                    </div>

                    <div class="col-md-2">
                        <label>Tanggal Akhir</label>
                        <input type="date" name="tgl_akhir"
                            value="{{ request('tgl_akhir') }}"
                            class="form-control" required>
                    </div>

                    <div class="col-md-2">
                        <label>Jenis Audit</label>
                        <select name="jenis_audit" class="form-control">
                            <option value="">Semua</option>
                            <option value="audit_rutin">Audit Rutin</option>
                            <option value="audit_khusus">Audit Khusus</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">

                        <button class="btn btn-primary mr-2">
                            <i class="fas fa-search"></i> Cari
                        </button>

                        <a href="{{ route('qal.laporan.index') }}"
                            class="btn btn-secondary">
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- TABLE --}}
    <div class="card">

        <div class="card-header">

            <h3 class="card-title">Data Audit</h3>

            <div class="card-tools">

                @if($data->count() > 0)
                <a href="{{ url('/qal/laporan/export-excel') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
                @endif

            </div>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped table-sm">

                    <thead class="text-center">

                        <tr>
                            <th>No</th>
                            <th>Unit</th>
                            <th>CIF</th>
                            <th>ID Ref Sampling</th>
                            <th>Nama</th>
                            <th>Kelompok</th>
                            <th>AO</th>
                            <th>Jenis Audit</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($data as $item)

                        <tr>

                            <td class="text-center">{{ $loop->iteration }}</td>

                            <td>{{ $item->branch->unit ?? '-' }}</td>

                            <td>{{ $item->cif }}</td>

                            <td>{{ $item->id_ref_sampling }}</td>

                            <td>{{ $item->nama }}</td>

                            <td>{{ $item->kelompok->nama_kel ?? '-' }}</td>

                            <td>{{ $item->ao->nama_ao ?? '-' }}</td>

                            <td class="text-center">

                                @if($item->jenis_audit == 'audit_rutin')
                                    <span class="badge badge-primary">Audit Rutin</span>
                                @elseif($item->jenis_audit == 'audit_khusus')
                                    <span class="badge badge-warning">Audit Khusus</span>
                                @else
                                    <span class="badge badge-secondary">{{ $item->jenis_audit }}</span>
                                @endif

                            </td>

                            <td class="text-center">

                                <a href="{{ route('qal.laporan.pdf',$item->id) }}"
                                target="_blank"
                                class="btn btn-info btn-sm">
                                Detail
                                </a>

                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="9" class="text-center text-muted">
                                Silakan lakukan filter terlebih dahulu
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>
@endsection
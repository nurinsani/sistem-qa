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

            <form method="GET" action="{{ route('fraud.alerts') }}">

                <div class="row">

                    <div class="col-md-2">
                        <label>Tanggal Tagih</label>
                        <input type="date" name="tgl_tagih"
                            value="{{ request('tgl_tagih') }}"
                            class="form-control" required>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">

                        <button class="btn btn-primary mr-2">
                            <i class="fas fa-search"></i> Cari
                        </button>

                        <a href="{{ route('fraud.alerts') }}" class="btn btn-secondary">
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

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped table-sm">

                    <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Tgl Tagih</th>
                        <th>Total OS</th>
                        <th>Jumlah NOA</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>

                    <tbody>
                @forelse ($data as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->tgl_tagih }}</td>
                    <td class="text-right">{{ number_format($row->total_os ?? 0) }}</td>
                    <td class="text-center">{{ $row->jumlah_noa }}</td>
                    <td>{{ $row->flag_reason }}</td>
                    <td>{{ $row->flag_status }}</td>
                    <td>
                        <a href="{{ route('fraud.alerts.export', [
                                'tgl_tagih' => $row->tgl_tagih,
                                'flag_status' => $row->flag_status,
                                'flag_reason' => $row->flag_reason
                            ]) }}" 
                            class="btn btn-info btn-sm">
                            Export
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Data tidak ditemukan</td>
                </tr>
                @endforelse
                </tbody>
                </table>

            </div>

        </div>

    </div>

</div>
@endsection
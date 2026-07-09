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

        {{-- TABLE --}}
        <div class="card">

            <div class="card-header">

                <h3 class="card-title">Data Approval</h3>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama QA</th>
                                <th>Unit</th>
                                <th>Deskripsi</th>
                                <th>Jenis Audit</th>
                                <th>Status Pengajuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataSamplings as $index => $sampling)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $sampling->qa->name ?? '-' }}</td>
                                    <td>{{ $sampling->branch->unit ?? '-' }}</td>
                                    <td>
                                        Ditugaskan oleh {{ $sampling->ketApprove->name ?? '' }}
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $sampling->jenis_audit ?? '-' }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = [
                                                '2220' => 'badge-warning',
                                                '3330' => 'badge-warning',
                                                'approved' => 'badge-success',
                                                'rejected' => 'badge-danger',
                                            ];
                                            $statusLabel = [
                                                '2220' => 'Pending',
                                                '3330' => 'Pending',
                                                'approved' => 'Disetujui',
                                                'rejected' => 'Ditolak',
                                            ];
                                        @endphp
                                        <span class="badge {{ $statusClass[$sampling->approval] ?? 'badge-secondary' }}">
                                            {{ $statusLabel[$sampling->approval] ?? ucfirst($sampling->approval) }} Oleh {{ $sampling->byApprove->name ?? '' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($sampling->approval == '2220' || $sampling->approval == '3330')
                                            <form action="{{ route('qam.approval.update', $sampling->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="approval" value="approved">
                                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Yakin ingin menyetujui?')">Approve</button>
                                            </form>
                                            
                                            <form action="{{ route('qam.approval.update', $sampling->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="approval" value="rejected">
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menolak?')">Reject</button>
                                            </form>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>

            </div>

        </div>

    </div>
@endsection
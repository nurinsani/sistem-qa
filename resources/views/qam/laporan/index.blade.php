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

        {{-- filter by tanggal --}}
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Filter Laporan</h3>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('qam.laporan.index') }}">

                    <div class="row">

                        <div class="col-md-2">
                            <label>Tanggal Awal</label>
                            <input type="date" name="tgl_awal" value="{{ request('tgl_awal') }}" class="form-control"
                                required>
                        </div>

                        <div class="col-md-2">
                            <label>Tanggal Akhir</label>
                            <input type="date" name="tgl_akhir" value="{{ request('tgl_akhir') }}" class="form-control"
                                required>
                        </div>

                        <div class="col-md-2">
                            <label>Jenis Audit</label>
                            <select name="jenis_audit" class="form-control">
                                <option value="">Semua</option>
                                <option value="audit_rutin" {{ request('jenis_audit') == 'audit_rutin' ? 'selected' : '' }}>
                                    Audit Rutin</option>
                                <option value="audit_khusus"
                                    {{ request('jenis_audit') == 'audit_khusus' ? 'selected' : '' }}>Audit Khusus</option>
                            </select>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">

                            <button class="btn btn-primary mr-2">
                                <i class="fas fa-search"></i> Cari
                            </button>

                            <a href="{{ route('qam.laporan.index') }}" class="btn btn-secondary">
                                Reset
                            </a>

                        </div>

                    </div>

                </form>
            </div>

        </div>

        {{-- rekap laporan --}}
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Rekap Laporan</h3>
            </div>

            <div class="card-body">
                <form action="{{ route('qam.laporan.index') }}" method="GET">
                    <div class="row">
                        <!-- Tanggal Awal Rekap -->
                        <div class="col-md-3">
                            <label>Tanggal Awal Rekap</label>
                            <input type="date" name="rekap_tgl_awal" value="{{ request('rekap_tgl_awal') }}"
                                class="form-control" required>
                        </div>

                        <!-- Tanggal Akhir Rekap -->
                        <div class="col-md-3">
                            <label>Tanggal Akhir Rekap</label>
                            <input type="date" name="rekap_tgl_akhir" value="{{ request('rekap_tgl_akhir') }}"
                                class="form-control" required>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-list"></i> Tampilkan Rekap
                            </button>
                            @if (request()->filled('rekap_tgl_awal') || request()->filled('rekap_tgl_akhir') || request()->filled('periode'))
                                <a href="{{ route('qam.laporan.index') }}" class="btn btn-secondary">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                @if (request()->filled('rekap_tgl_awal') || request()->filled('rekap_tgl_akhir') || request()->filled('periode'))
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="m-0">
                            <i class="fas fa-users text-primary mr-1"></i> Rekap User Sampling
                            @if (request()->filled('rekap_tgl_awal') && request()->filled('rekap_tgl_akhir'))
                                ({{ \Carbon\Carbon::parse(request('rekap_tgl_awal'))->format('d-m-Y') }} s/d
                                {{ \Carbon\Carbon::parse(request('rekap_tgl_akhir'))->format('d-m-Y') }})
                            @elseif (request()->filled('periode'))
                                ({{ request('periode') }} Hari Terakhir)
                            @endif
                        </h5>
                        @if (request()->filled('user_id'))
                            <a href="{{ route('qam.laporan.index', array_filter(['rekap_tgl_awal' => request('rekap_tgl_awal'), 'rekap_tgl_akhir' => request('rekap_tgl_akhir'), 'periode' => request('periode')])) }}"
                                class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-globe mr-1"></i> Tampilkan Semua User
                            </a>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="text-center bg-light">
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Nama User / QA Auditor</th>
                                    <th>Atasan</th>
                                    <th style="width: 90px;">Proses</th>
                                    <th style="width: 100px;">Tanggapan</th>
                                    <th style="width: 90px;">Evaluasi</th>
                                    <th style="width: 90px;">Selesai</th>
                                    <th style="width: 130px;">Total Sampling</th>
                                    <th style="width: 200px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rekapData as $rekap)
                                    <tr
                                        class="{{ request('user_id') == $rekap->user_id ? 'table-primary font-weight-bold' : '' }}">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $rekap->nama_user ?? 'User ID: ' . ($rekap->user_id ?? '-') }}</td>
                                        <td>{{ $rekap->nama_atasan ?? '-' }}</td>
                                        <td class="text-center">
                                            <span
                                                class="badge badge-primary px-2 py-1">{{ $rekap->total_proses ?? 0 }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge badge-warning px-2 py-1">{{ $rekap->total_tanggapan ?? 0 }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge badge-info px-2 py-1">{{ $rekap->total_evaluasi ?? 0 }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge badge-success px-2 py-1">{{ $rekap->total_selesai ?? 0 }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-dark px-2 py-1" style="font-size: 13px;">
                                                {{ $rekap->total_sampling }} Data
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('qam.laporan.index', array_filter(['rekap_tgl_awal' => request('rekap_tgl_awal'), 'rekap_tgl_akhir' => request('rekap_tgl_akhir'), 'periode' => request('periode'), 'user_id' => $rekap->user_id])) }}"
                                                class="btn btn-xs {{ request('user_id') == $rekap->user_id ? 'btn-primary' : 'btn-outline-primary' }} mr-1">
                                                <i class="fas fa-eye mr-1"></i>
                                                {{ request('user_id') == $rekap->user_id ? 'Dipilih' : 'Tampilkan Data' }}
                                            </a>
                                            <a href="{{ route('audit.rekap.pdf', array_filter(['rekap_tgl_awal' => request('rekap_tgl_awal'), 'rekap_tgl_akhir' => request('rekap_tgl_akhir'), 'periode' => request('periode'), 'user_id' => $rekap->user_id])) }}"
                                                target="_blank" class="btn btn-xs btn-danger">
                                                <i class="fas fa-file-pdf mr-1"></i> PDF Rekap
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-3">
                                            Tidak ada data sampling pada range tanggal/periode ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card">

            <div class="card-header">

                <h3 class="card-title">
                    Data Audit
                    @if (request()->filled('rekap_tgl_awal') && request()->filled('rekap_tgl_akhir'))
                        <small class="text-muted">(Rekap:
                            {{ \Carbon\Carbon::parse(request('rekap_tgl_awal'))->format('d-m-Y') }} s/d
                            {{ \Carbon\Carbon::parse(request('rekap_tgl_akhir'))->format('d-m-Y') }}
                            @if (request()->filled('user_id') && ($selectedUser = $rekapData->firstWhere('user_id', request('user_id'))))
                                - Petugas: <strong>{{ $selectedUser->nama_user }}</strong>
                            @endif
                            )
                        </small>
                    @elseif (request()->filled('periode'))
                        <small class="text-muted">(Periode {{ request('periode') }} Hari Terakhir
                            @if (request()->filled('user_id') && ($selectedUser = $rekapData->firstWhere('user_id', request('user_id'))))
                                - Pertugas: <strong>{{ $selectedUser->nama_user }}</strong>
                            @endif
                            )
                        </small>
                    @elseif(request()->filled('tgl_awal'))
                        <small class="text-muted">({{ \Carbon\Carbon::parse(request('tgl_awal'))->format('d-m-Y') }} s/d
                            {{ \Carbon\Carbon::parse(request('tgl_akhir'))->format('d-m-Y') }})</small>
                    @endif
                </h3>

                <div class="card-tools">

                    @if ($data->count() > 0)
                        <a href="{{ route('qam.laporan.export_excel', request()->all()) }}"
                            class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                    @endif

                </div>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="tableLaporan" class="table table-bordered table-striped table-sm">

                        <thead class="text-center">

                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Unit</th>
                                <th>CIF</th>
                                <th>Ref Sampling</th>
                                <th>Nama</th>
                                <th>Petugas QA</th>
                                <th>Kelompok</th>
                                <th>AO</th>
                                <th>Jenis Audit</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($data as $item)
                                <tr>

                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ date('d-m-Y', strtotime($item->updated_at)) ?? '-' }}</td>
                                    <td>{{ $item->branch->unit ?? '-' }}</td>
                                    <td>{{ $item->cif }}</td>
                                    <td>{{ $item->id_ref_sampling }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->nama_petugas ?? ($item->qa->name ?? '-') }}</td>
                                    <td>{{ $item->kelompok->nama_kel ?? '-' }}</td>
                                    <td>{{ $item->ao->nama_ao ?? '-' }}</td>
                                    <td class="text-center">

                                        @if ($item->jenis_audit == 'audit_rutin')
                                            <span class="badge badge-primary">Audit Rutin</span>
                                        @elseif($item->jenis_audit == 'audit_khusus')
                                            <span class="badge badge-warning">Audit Khusus</span>
                                        @else
                                            <span class="badge badge-secondary">{{ $item->jenis_audit }}</span>
                                        @endif

                                    </td>

                                    <td class="text-center">
                                        @if ($item->status == 'proses' || $item->status == 'pending')
                                            <span class="badge badge-primary">Proses</span>
                                        @elseif($item->status == 'tanggapan')
                                            <span class="badge badge-warning">Tanggapan</span>
                                        @elseif($item->status == 'evaluasi')
                                            <span class="badge badge-info">Evaluasi</span>
                                        @elseif($item->status == 'selesai')
                                            <span class="badge badge-success">Selesai</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($item->status ?? '-') }}</span>
                                        @endif
                                    </td>

                                    <td class="text-center">

                                        <a href="{{ route('qam.laporan.pdf', $item->id) }}" target="_blank"
                                            class="btn btn-danger btn-sm">
                                            <i class="fas fa-file-pdf"></i> PDF
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="12" class="text-center text-muted">
                                        Silakan lakukan filter atau pilih rekap terlebih dahulu
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

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#tableLaporan').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "paginate": {
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });
        });
    </script>
@endpush

@extends('layouts.main')

@section('content')
@section('content-header')
<div class="container-fluid">
    <div class="row mb-2">
        
        {{-- TITLE --}}
        <div class="col-sm-6">
            <h1>Informasi Anggota</h1>
        </div>

        {{-- BREADCRUMB --}}
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
    <a href="{{ url('/qa/dashboard') }}">Home</a>
</li>
                <li class="breadcrumb-item active">
                    Informasi Anggota
                </li>
            </ol>
        </div>

    </div>
</div>
@endsection
<div class="container my-4">
<a href="{{ route('informasi_anggota_detail', ['cif' => request()->route('cif')]) }}" class="btn btn-primary btn-sm mb-3">
    ← Kembali
</a>
<a href="{{ route('mutasi_anggota_print', $dataCif['cif']) }}" 
   target="_blank" 
   class="btn btn-success btn-sm mb-3">
   🖨️ Cetak
</a>
    {{-- HEADER --}}
    <div class="text-center mb-4">
        <img src="{{ asset('images/logoni.jpeg') }}" height="60" class="mb-2">
        <h6 class="fw-bold mb-0">KOPERASI SIMPAN PINJAM SYARIAH NUR INSANI</h6>
        <small class="text-muted">Melayani dengan hati</small>

        <h5 class="fw-bold mt-3 text-uppercase">
            Daftar Mutasi Simpanan Anggota
        </h5>
    </div>

    {{-- INFORMASI ANGGOTA --}}
<div class="row mb-4">
        <div class="col-md-6">
            <table class="table table-sm table-borderless">
                <tr><td>Nomor Rekening</td><td>:</td><td>{{ $dataCif['norek'] }}</td></tr>
                <tr><td>Nama</td><td>:</td><td>{{ $dataCif['nama'] }}</td></tr>
                <tr><td>Plafond Pembiayaan</td><td>:</td><td>{{ number_format($dataCif['plafond']) }}</td></tr>
                <tr><td>Tanggal Akad</td><td>:</td><td>{{ $dataCif['tgl_join'] }}</td></tr>
            </table>
        </div>

        <div class="col-md-6">
            <table class="table table-sm table-borderless">
                <tr><td>Jangka Waktu</td><td>:</td><td>{{ $dataCif['tenor'] }}</td></tr>
                <tr><td>Tanggal Jatuh Tempo</td><td>:</td><td>{{ $dataCif['maturity_date'] }}</td></tr>
                <tr><td>Sisa Pembiayaan</td><td>:</td><td>{{ number_format($dataCif['os']) }}</td></tr>
                <tr><td>Simpanan Pokok</td><td>:</td><td>{{ number_format($dataCif['pokok']) }}</td></tr>
                <tr><td>Simpanan Wajib</td><td>:</td><td>{{ number_format($dataCif['wajib']) }}</td></tr>
            </table>
        </div>
    </div>

    {{-- TABEL MUTASI --}}
    <div class="table-responsive">
        <table class="table table-bordered table-sm text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Kode Transaksi</th>
                    <th>Keterangan</th>
                    <th>Tipe</th>
                    <th>Debet</th>
                    <th>Kredit</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tbody>
@foreach ($mutasi as $i => $row)
    <tr>
        <td>{{ $mutasi->firstItem() + $i }}</td>
        <td>{{ $row['tanggal'] ?? '-' }}</td>
        <td>{{ $row['kode_transaksi'] ?? '-' }}</td>
        <td>{{ $row['keterangan'] ?? '-' }}</td>
        <td>{{ $row['type'] ?? '-' }}</td>
        <td>{{ number_format($row['debet'] ?? 0) }}</td>
        <td>{{ number_format($row['kredit'] ?? 0) }}</td>
        <td>{{ number_format($row['saldo'] ?? 0) }}</td>
    </tr>
@endforeach
</tbody>
</table>
   <style>
    /* Cara "kasar" untuk mengecilkan ikon panah raksasa tersebut */
    .pagination svg {
        width: 20px;
        display: inline;
    }
</style>

<div class="pagination">
    {{ $mutasi->links() }}
</div>
    </div>
</div>
@endsection

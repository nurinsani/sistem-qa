@extends('layouts.main')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Ubah Password</h3>
            </div>
            
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">
                    {{-- Pesan Sukses --}}
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- Pesan Peringatan --}}
                    @if (session('warning'))
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-exclamation-triangle"></i> Perhatian!</h5>
                            {{ session('warning') }}
                        </div>
                    @endif

                    {{-- Password Lama --}}
                    <div class="form-group">
                        <label>Password Lama</label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Masukkan password saat ini" required>
                        @error('current_password')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Password Baru --}}
                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password baru" required>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="form-group">
                        <label>Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru" required>
                        @error('password')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
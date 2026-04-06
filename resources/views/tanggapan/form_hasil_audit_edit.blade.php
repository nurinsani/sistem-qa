<form action="{{ route('tanggapan.update', $audit->id_audit) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Edit Audit</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Observasi</h5>
                    <div class="form-group">
                        <label>Kondisi Usaha</label>
                        <textarea name="kondisi_usaha" id="kondisi_usaha" cols="10" rows="3"
                            class="form-control @error('kondisi_usaha') is-invalid @enderror">{{ old('kondisi_usaha', $audit->kondisi_usaha) }}</textarea>
                        @error('kondisi_usaha')
                            <div class="invalid-feedback mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Kondisi Keluarga</label>
                        <textarea name="kondisi_keluarga" id="kondisi_keluarga" cols="10" rows="3"
                            class="form-control @error('kondisi_keluarga') is-invalid @enderror">{{ old('kondisi_keluarga', $audit->kondisi_keluarga) }}</textarea>
                        @error('kondisi_keluarga')
                            <div class="invalid-feedback mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Kondisi Lingkungan</label>
                        <textarea name="kondisi_lingkungan" id="kondisi_lingkungan" cols="10" rows="3"
                            class="form-control @error('kondisi_lingkungan') is-invalid @enderror">{{ old('kondisi_lingkungan', $audit->kondisi_lingkungan) }}</textarea>
                        @error('kondisi_lingkungan')
                            <div class="invalid-feedback mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Foto Hasil Audit (Klik untuk ganti)</label><br>
                        <div class="row text-center">
                            {{-- Foto Wawancara Anggota --}}
                            <div class="col-md-4">
                                @if (!empty($audit->foto_wawancara_anggota))
                                    <img src="{{ asset($audit->foto_wawancara_anggota) }}" class="img-fluid img-thumbnail mb-2">
                                @endif
                                <input type="file" name="foto_wawancara_anggota" class="form-control-file">
                                <p class="small mt-2">Wawancara Anggota</p>
                            </div>

                            {{-- Foto Wawancara Ketua --}}
                            <div class="col-md-4">
                                @if (!empty($audit->foto_wawancara_ketua))
                                    <img src="{{ asset($audit->foto_wawancara_ketua) }}" class="img-fluid img-thumbnail mb-2">
                                @endif
                                <input type="file" name="foto_wawancara_ketua" class="form-control-file">
                                <p class="small mt-2">Wawancara Ketua Kelompok</p>
                            </div>

                            {{-- Foto Usaha --}}
                            <div class="col-md-4">
                                @if (!empty($audit->foto_usaha))
                                    <img src="{{ asset($audit->foto_usaha) }}" class="img-fluid img-thumbnail mb-2">
                                @endif
                                <input type="file" name="foto_usaha" class="form-control-file">
                                <p class="small mt-2">Foto Usaha</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <h5>Wawancara</h5>
                    <div class="form-group">
                        <label>Wawancara Anggota</label>
                        <textarea name="wawancara_anggota" id="wawancara_anggota" cols="10" rows="3"
                            class="form-control @error('wawancara_anggota') is-invalid @enderror">{{ old('wawancara_anggota', $audit->wawancara_anggota ?? '') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Wawancara Ketua Kelompok</label>
                        <textarea name="wawancara_ketua_kel" id="wawancara_ketua_kel" cols="10" rows="3"
                            class="form-control @error('wawancara_ketua_kel') is-invalid @enderror">{{ old('wawancara_ketua_kel', $audit->wawancara_ketua_kel ?? '') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Temuan</label>
                        <div class="d-flex">
                            <textarea name="temuan" id="temuan" class="form-control @error('temuan') is-invalid @enderror" rows="3">{{ old('temuan', $audit->temuan) }}</textarea>
                            <button type="button" class="btn btn-danger btn-sm ml-2 align-self-start" data-toggle="modal" data-target="#modalKetentuanTemuan">
                                Ketentuan
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Temuan Lain</label><br>
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalTemuanLain">
                            Lihat Temuan Lain
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </div>
</form>
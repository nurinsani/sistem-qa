<div class="card">
    <div class="card-header">
        <h3 class="card-title">Form Audit</h3>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-md-6">
                <h5>Observasi</h5>
                <div class="form-group">
                    <label>Kondisi Usaha</label>
                    <textarea name="kondisi_usaha" id="kondisi_usaha" cols="10" rows="3"
                        class="form-control @error('kondisi_usaha') is-invalid @enderror" readonly>{{ old('kondisi_usaha', $audit->kondisi_usaha) }}</textarea>
                    @error('kondisi_usaha')
                        <div class="invalid-feedback mt-2">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Kondisi Keluarga</label>
                    <textarea name="kondisi_keluarga" id="kondisi_keluarga" cols="10" rows="3"
                        class="form-control @error('kondisi_keluarga') is-invalid @enderror" readonly>{{ old('kondisi_keluarga', $audit->kondisi_keluarga) }}</textarea>
                    @error('kondisi_keluarga')
                        <div class="invalid-feedback mt-2">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Kondisi Lingkungan</label>
                    <textarea name="kondisi_lingkungan" id="kondisi_lingkungan" cols="10" rows="3"
                        class="form-control @error('kondisi_lingkungan') is-invalid @enderror" readonly>{{ old('kondisi_lingkungan', $audit->kondisi_lingkungan) }}</textarea>
                    @error('kondisi_lingkungan')
                        <div class="invalid-feedback mt-2">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Temuan</label>

                    <div class="d-flex">
                        <textarea name="temuan" id="temuan" class="form-control @error('temuan') is-invalid @enderror" rows="3"
                            readonly>{{ old('temuan', $audit->temuan) }}</textarea>

                        <button type="button" class="btn btn-danger btn-sm ml-2 align-self-start" data-toggle="modal"
                            data-target="#modalKetentuanTemuan">
                            Ketentuan
                        </button>
                    </div>

                    @error('temuan')
                        <div class="invalid-feedback d-block mt-2">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Temuan Lain</label><br>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                        data-target="#modalTemuanLain">
                        lihat Temuan Lain
                    </button>
                </div>

            </div>
            
            <div class="col-md-6">
                <h5>Wawancara</h5>
                
                <div class="form-group">
                    <label>Foto Hasil Audit</label><br>
                    <div class="row text-center">
    
                        <div class="col-md-4">
                            @if (!empty($audit->foto_wawancara_anggota))
                                <img src="{{ asset($audit->foto_wawancara_anggota) }}"
                                    class="img-fluid img-thumbnail doc-img preview-img"
                                    data-src="{{ asset($audit->foto_wawancara_anggota) }}"
                                    data-title="Foto Wawancara Anggota">
                            @else
                                <p class="text-muted">Tidak tersedia</p>
                            @endif
                            <p class="mt-2">Wawancara Anggota</p>
                        </div>
    
                        <div class="col-md-4">
                            @if (!empty($audit->foto_wawancara_ketua))
                                <img src="{{ asset($audit->foto_wawancara_ketua) }}"
                                    class="img-fluid img-thumbnail doc-img preview-img"
                                    data-src="{{ asset($audit->foto_wawancara_ketua) }}"
                                    data-title="Foto Wawancara Ketua Kelompok">
                            @else
                                <p class="text-muted">Tidak tersedia</p>
                            @endif
                            <p class="mt-2">Wawancara Ketua Kelompok</p>
                        </div>
    
                        <div class="col-md-4">
                            @if (!empty($audit->foto_usaha))
                                <img src="{{ asset($audit->foto_usaha) }}"
                                    class="img-fluid img-thumbnail doc-img preview-img"
                                    data-src="{{ asset($audit->foto_usaha) }}" data-title="Foto Usaha">
                            @else
                                <p class="text-muted">Tidak tersedia</p>
                            @endif
                            <p class="mt-2">Foto Usaha</p>
                        </div>
    
                    </div>
                </div>

            </div>
            
        </div>
    </div>

    <div class="card-footer text-right">
        <a href="{{ route('evaluasi.edit', ['id' => $audit->id_audit, 'cif' => $audit->cif]) }}" class="btn btn-primary">
            Edit
        </a>
        <form action="{{ route('evaluasi.lanjut', $audit->id_audit) }}" method="POST" style="display: inline;">
            @csrf
            @method('PATCH')
            <input type="hidden" name="cif" value="{{ $audit->cif }}">
            <button type="submit" class="btn btn-success">
                Tandai Selesai
            </button>
        </form>
    </div>
    
</div>

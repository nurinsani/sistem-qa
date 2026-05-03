<div class="modal fade" id="modalAuditKhusus" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Audit Khusus</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form id="formAuditKhusus">
                @csrf
                <div class="modal-body">
                    <!-- Opsi Pilihan Input -->
                    <div class="form-group border-bottom pb-3">
                        <label class="d-block">Metode Input <span class="text-danger">*</span></label>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="method_kelompok" name="input_method" class="custom-control-input" value="kelompok" checked>
                            <label class="custom-control-label" for="method_kelompok">Berdasarkan Kelompok</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="method_manual" name="input_method" class="custom-control-input" value="manual">
                            <label class="custom-control-label" for="method_manual">Input Manual (NIK)</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Section Kelompok (Akan di-hide jika manual) -->
                            <div id="section-kelompok">
                                <div class="form-group">
                                    <label>Kelompok <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="code_kel" id="kode_kel" style="width: 100%;"></select>
                                </div>
                                <div class="form-group">
                                    <label>Nama Kelompok</label>
                                    <input type="text" name="nama_kelompok" id="nama_kelompok" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Nama AO</label>
                                    <input type="text" name="nama_ao" id="nama_ao" class="form-control" readonly>
                                </div>
                            </div>

                            <!-- Section Manual (Akan di-show jika manual) -->
                            <div id="section-manual" style="display: none;">
                                {{-- <div class="form-group">
                                    <label>AP</label>
                                    <select class="form-control select2" name="unit" required style="width: 100%;">
                                        <option value="">-- Pilih AP --</option>
                                        @foreach ($branch as $item)
                                            <option value="{{ $item->kode_branch }}">{{ $item->unit }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                
                                <div class="form-group">
                                    <label>NIK <span class="text-danger">*</span></label>
                                    <input type="text" name="nik" id="nik" class="form-control" placeholder="Masukkan NIK">
                                </div>
                                <div class="form-group">
                                    <label>Nama <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_manual" id="nama_manual" class="form-control" placeholder="Masukkan Nama Lengkap">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Nama QA <span class="text-danger">*</span></label>
                                <select name="user_id" id="user_id" class="form-control" required>
                                    <option value="">-- Pilih QA --</option>
                                    @foreach ($qa as $qaUser)
                                        <option value="{{ $qaUser->id }}">{{ $qaUser->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Tanggal Awal <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Tanggal Akhir <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <!-- Section CIF (Hanya tampil jika mode kelompok) -->
                        <div class="col-md-6" id="section-cif">
                            <div class="form-group">
                                <label>Pilih CIF <span class="text-danger">*</span></label>
                                <div id="list-cif" class="border rounded p-3" style="max-height:350px; overflow-y:auto; min-height: 200px;">
                                    <p class="text-muted text-center mb-0">
                                        <i class="fa fa-info-circle"></i> Pilih kelompok terlebih dahulu
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fa fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
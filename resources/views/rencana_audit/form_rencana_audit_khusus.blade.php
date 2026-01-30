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
                    <div class="row">
                        <div class="col">
                            
                            <div class="form-group">
                                <label>Kelompok</label>
                                <select class="form-control select2" name="kode_kel" required style="width: 100%;">
                                    <option value="">-- Kelompok --</option>
                                    <option value="001">LR</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Nama Kelompok</label>
                                <input type="text" name="nama_kelompok" class="form-control" readonly>
                            </div>

                            <div class="form-group">
                                <label>Nama AO</label>
                                <input type="text" name="nama_ao" class="form-control" readonly>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Tanggal Awal</label>
                                    <input type="date" name="tanggal_awal" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Tanggal Akhir</label>
                                    <input type="date" name="tanggal_akhir" class="form-control" required>
                                </div>
                            </div>

                        </div>
                        <div class="col">
                            <p><strong> Pilh CIF </strong></p>
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="radioPrimary3" name="r1">
                                    <label for="radioPrimary3">
                                    Sumiyati - 76536FG
                                    </label>
                                </div>
                            </div>
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="radioPrimary3" name="r1">
                                    <label for="radioPrimary3">
                                    Sumiyati - 76536FG
                                    </label>
                                </div>
                            </div>
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="radioPrimary3" name="r1">
                                    <label for="radioPrimary3">
                                    Sumiyati - 76536FG
                                    </label>
                                </div>
                            </div>
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="radioPrimary3" name="r1">
                                    <label for="radioPrimary3">
                                    Sumiyati - 76536FG
                                    </label>
                                </div>
                            </div>
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="radioPrimary3" name="r1">
                                    <label for="radioPrimary3">
                                    Sumiyati - 76536FG
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
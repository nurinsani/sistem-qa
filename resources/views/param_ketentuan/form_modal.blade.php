<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formData">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Form Parameter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Parameter Profil</label>
                                <select name="id_param_profil" id="id_param_profil" class="form-control select2" style="width: 100%;" required>
                                    <option value="">-- Pilih Profil --</option>
                                    @foreach($param_profil as $p)
                                        <option value="{{ $p->id }}">{{ $p->deskripsi }} ({{ $p->level }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No. Ketentuan</label>
                                <input type="text" name="nomor_ketentuan" id="nomor_ketentuan" class="form-control"
                                    required>
                            </div>
                            <div class="form-group">
                                <label>Heading</label>
                                <input type="text" name="heading" id="heading" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sub Heading</label>
                                <input type="text" name="sub_heading" id="sub_heading" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Sub Sub Heading (Kriteria)</label>
                                <input type="text" name="sub_sub_heading" id="sub_sub_heading" class="form-control">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Sub Sub Sub Heading (Detail Aturan)</label>
                                <textarea name="sub_sub_sub_heading" id="sub_sub_sub_heading" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Sub Sub Sub Sub Heading</label>
                                <textarea name="sub_sub_sub_sub_heading" id="sub_sub_sub_sub_heading" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Sub Sub Sub Sub Sub Heading</label>
                                <textarea name="sub_sub_sub_sub_sub_heading" id="sub_sub_sub_sub_sub_heading" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btn-save">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
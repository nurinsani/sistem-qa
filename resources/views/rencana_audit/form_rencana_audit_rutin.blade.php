<div class="modal fade" id="modalAuditRutin" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Audit Rutin</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form id="formAuditRutin">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>AP</label>
                        <select class="form-control select2" name="unit" required style="width: 100%;">
                            <option value="">-- Pilih AP --</option>
                            @foreach ($branch as $item)
                                <option value="{{ $item->kode_branch }}">{{ $item->unit }}</option>
                            @endforeach
                        </select>
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

                    <div class="form-group">
                        <label>Jumlah Sampling</label>
                        <input type="number" name="jumlah_sampling" class="form-control">
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
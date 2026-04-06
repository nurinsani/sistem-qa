<div class="modal fade" id="modalFotoWawancara" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Foto Hasil Audit</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <h6>Wawancara Anggota</h6>

                        @if (!empty($data_sampling_detail->foto_wawancara_anggota))
                            <img src="{{ asset($data_sampling_detail->foto_wawancara_anggota) }}"
                                class="img-fluid img-thumbnail doc-img preview-img"
                                data-src="{{ asset($data_sampling_detail->foto_wawancara_anggota) }}"
                                data-title="Foto Wawancara Anggota">
                        @else
                            <p class="text-muted">Tidak tersedia</p>
                        @endif
                    </div>

                    <div class="col-md-4 text-center">
                        <h6>Wawancara Ketua Kelompok</h6>

                        @if (!empty($data_sampling_detail->foto_wawancara_ketua))
                            <img src="{{ asset($data_sampling_detail->foto_wawancara_ketua) }}"
                                class="img-fluid img-thumbnail doc-img preview-img"
                                data-src="{{ asset($data_sampling_detail->foto_wawancara_ketua) }}"
                                data-title="Foto Wawancara Ketua Kelompok">
                        @else
                            <p class="text-muted">Tidak tersedia</p>
                        @endif
                    </div>

                    <div class="col-md-4 text-center">
                        <h6>Foto Usaha</h6>

                        @if (!empty($data_sampling_detail->foto_usaha))
                            <img src="{{ asset($data_sampling_detail->foto_usaha) }}"
                                class="img-fluid img-thumbnail doc-img preview-img"
                                data-src="{{ asset($data_sampling_detail->foto_usaha) }}" data-title="Foto Usaha">
                        @else
                            <p class="text-muted">Tidak tersedia</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

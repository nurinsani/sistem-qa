<div class="modal fade" id="modalTemuanLain" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <form id="formTemuanLain">
            @csrf

            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">
                        Temuan Lain
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <!-- BODY -->
                <div class="modal-body">

                    <div class="card card-success card-outline card-tabs">
                        <div class="card-header p-0 pt-1 border-bottom-0">
                            <ul class="nav nav-tabs" role="tablist">

                                @foreach ($kategoriParams as $index => $kategori)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $index == 0 ? 'active' : '' }}" data-toggle="pill"
                                            href="#tab-{{ Str::slug($kategori) }}" role="tab">
                                            {{ $kategori }}
                                        </a>
                                    </li>
                                @endforeach

                                {{-- Dokumen Ceklis tetap manual --}}
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="pill" href="#tab-dokumen-ceklis" role="tab">
                                        Dokumen Ceklis
                                    </a>
                                </li>

                            </ul>
                        </div>

                        <div class="card-body">
                            <div class="tab-content">

                                @foreach ($kategoriParams as $index => $kategori)
                                    <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}"
                                        id="tab-{{ Str::slug($kategori) }}" role="tabpanel">

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm">
                                                <thead class="text-center bg-light">
                                                    <tr>
                                                        <th width="40">No</th>
                                                        <th>Pertanyaan</th>
                                                        <th width="120">Hasil Audit</th>
                                                        <th width="320">Temuan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($params[$kategori] ?? [] as $i => $param)
                                                        <tr>
                                                            <td class="text-center">{{ $i + 1 }}</td>
                                                            <td>{{ $param->deskripsi }}</td>
                                                            <td>
                                                                <select name="hasil[{{ $param->id }}]"
                                                                    class="form-control form-control-sm">
                                                                    <option value="">Pilih</option>
                                                                    <option value="sesuai">Sesuai</option>
                                                                    <option value="tidak">Tidak Sesuai</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <textarea name="temuan[{{ $param->id }}]" class="form-control form-control-sm mr-2" rows="2"
                                                                        placeholder="Temuan..."></textarea>

                                                                    <button type="button"
                                                                        class="btn btn-danger btnKetentuan"
                                                                        data-id="{{ $param->id }}">
                                                                        Ketentuan
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center text-muted">
                                                                Tidak ada data
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                @endforeach

                                <div class="tab-pane fade" id="tab-dokumen-ceklis" role="tabpanel">

                                    @php
                                        $dokumenList = [
                                            'Aplikasi',
                                            'KTP',
                                            'KTP Penjamin',
                                            'Kartu Keluarga',
                                            'Form Checklist Dokumen',
                                            'Akad Wakalah',
                                            'Akad Murabahah',
                                            'Akad Laryswah',
                                            'Form Permohonan Anggota',
                                            'Kartu Angsuran',
                                            'Persyaratan Lainya',
                                        ];
                                    @endphp

                                    <input type="hidden" name="id_audit_detail" value="{{ $auditDetail->id ?? 1 }}">

                                    <table class="table table-bordered table-sm">
                                        <thead class="bg-light">
                                            <tr>
                                                <th width="60">#</th>
                                                <th width="500">Dokumen</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dokumenList as $i => $dok)
                                                <tr>
                                                    <td class="text-center">
                                                        <input type="hidden"
                                                            name="dokumen[{{ $i }}][status]"
                                                            value="tidak ada">

                                                        <input type="checkbox"
                                                            name="dokumen[{{ $i }}][status]" value="ada">

                                                        <input type="hidden"
                                                            name="dokumen[{{ $i }}][deskripsi]"
                                                            value="{{ $dok }}">
                                                    </td>
                                                    <td>{{ $dok }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <!-- END BODY -->

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        Simpan
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        Tutup
                    </button>
                </div>

            </div>
        </form>

    </div>
</div>

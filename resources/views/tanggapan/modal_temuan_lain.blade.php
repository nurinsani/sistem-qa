<div class="modal fade" id="modalTemuanLain" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Temuan Lain</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">

                        @if ($temuanLain->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Pertanyaan</th>
                                            <th width="120">Hasil Audit</th>
                                            <th>Deskripsi Temuan</th>
                                            <th>Ketentuan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($temuanLain as $key => $temuan)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $temuan->pertanyaan }}</td>
                                                <td>{{ $temuan->status_audit }}</td>
                                                <td>{{ $temuan->deskripsi_temuan }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-info"
                                                            data-toggle="collapse"
                                                            data-target="#ketentuan{{ $temuan->id }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="p-0 border-0">
                                                    <div class="collapse"
                                                        id="ketentuan{{ $temuan->id }}">
                                                        <div class="p-3 bg-light border">
                                                            <strong>
                                                                {{ $temuan->nomor_ketentuan }}
                                                            </strong><br>
                                                            <strong>{{ $temuan->heading }}</strong><br>
                                                            {{ $temuan->sub_heading }}<br>
                                                            {{ $temuan->sub_sub_heading }}<br>
                                                            {{ $temuan->sub_sub_sub_heading ?? '' }}<br>
                                                            {{ $temuan->sub_sub_sub_sub_heading ?? '' }}<br>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-secondary text-center">
                                Tidak ada temuan lain.
                            </div>
                        @endif

                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

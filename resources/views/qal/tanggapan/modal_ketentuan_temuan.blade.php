<div class="modal fade" id="modalKetentuanTemuan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ketentuan</h5>
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
                                    <tbody>

                                        @foreach ($temuan as $key => $t)
                                            <tr>
                                                <div class="p-3 bg-light border">
                                                    <strong>
                                                        {{ $t->nomor_ketentuan }}
                                                    </strong><br>
                                                    <strong>{{ $t->heading }}</strong><br>
                                                    {{ $t->sub_heading }}<br>
                                                    {{ $t->sub_sub_heading }}<br>
                                                    {{ $t->sub_sub_sub_heading ?? '' }}<br>
                                                    {{ $t->sub_sub_sub_sub_heading ?? '' }}<br>
                                                </div>
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

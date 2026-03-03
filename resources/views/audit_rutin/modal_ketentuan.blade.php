<div class="modal fade" id="modalKetentuan" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold">Ketentuan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <!-- Search -->
                <div class="input-group mb-3">
                    <input type="text" id="searchKetentuan" class="form-control" placeholder="Cari ketentuan...">

                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                            Cari
                        </button>
                    </div>
                </div>

                <div class="row">
                    {{-- sidebar --}}
                    <div class="col-md-3">
                        <div class="list-group" id="sidebarKetentuan">

                            @foreach ($groupedKetentuan as $subHeading => $items)
                                <a href="#"
                                    class="list-group-item list-group-item-action {{ $loop->first ? 'active' : '' }}"
                                    onclick="showKetentuan('{{ Str::slug($subHeading) }}')">
                                    {{ $subHeading }}
                                </a>
                            @endforeach

                        </div>
                    </div>

                    <!-- Konten Ketentuan -->
                    <div class="col-md-9">

                        <form id="formKetentuan">

                            @foreach ($groupedKetentuan as $subHeading => $items)
                                <div class="ketentuan-group {{ !$loop->first ? 'd-none' : '' }}"
                                    id="{{ Str::slug($subHeading) }}">

                                    @foreach ($items as $item)
                                        <div class="form-check mb-2 ketentuan-item">
                                            <input class="form-check-input" type="checkbox" name="ketentuan[]"
                                                value="{{ $item->id }}" id="ketentuan{{ $item->id }}">

                                            <label class="form-check-label" for="ketentuan{{ $item->id }}">
                                                <b>{{ $item->nomor_ketentuan }}.</b> <br>
                                                <b>{{ $item->heading }} </b>

                                                @if ($item->sub_heading)
                                                    {{-- <br><small>{{ $item->sub_heading }}</small> --}}
                                                <br><span class="badge badge-info">{{ $item->sub_heading }}</span>
                                                @endif

                                                @if ($item->sub_sub_heading)
                                                    <br><small><b>{{ $item->sub_sub_heading }}</b></small>
                                                @endif

                                                @if ($item->sub_sub_sub_heading)
                                                    <br><small>{{ $item->sub_sub_sub_heading }}</small>
                                                @endif

                                                @if ($item->sub_sub_sub_sub_heading)
                                                    <br><small>{{ $item->sub_sub_sub_sub_heading }}</small>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach

                                </div>
                            @endforeach

                            <div class="text-right mt-3">
                                <button type="button"
                                        class="btn btn-primary"
                                        onclick="submitKetentuan()">
                                    Simpan
                                </button>
                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

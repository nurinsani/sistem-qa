@extends('layouts.main')

@section('content')
    <style>
        .result {
            margin-top: 30px;
        }

        .result input {
            width: 100%;
            padding: 10px;
        }
    </style>

    <div class="row justify-content-center mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title mb-3">Cari Anggota</h5>
                    <form action="{{ route('informasi_anggota') }}" method="GET">
                        <div class="input-group">
                                <input type="text" name="cif" class="form-control" placeholder="masukkan nama anggota"
                                    value="{{ request('cif') }}">
                                @if (request('cif'))
                                <a href="{{ route('informasi_anggota_detail', request('cif')) }}"
                                    class="btn btn-primary">🔍</a>
                            @endif
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    {{-- @if (request('cif'))
                <div class="result">
                    <p>Hasil Pencarian</p>

                    <input type="text" value="{{ request('cif') }}" class="form-control mb-2">

                    <a href="{{ route('informasi_anggota_detail', request('cif')) }}"
                    class="btn btn-primary">
                        Detail
                    </a>
                </div>
                @endif
</div> --}}
    </div>
    </div>
    </div>
    </div>
@endsection

<script>
    $('#search').on('keyup', function() {
        let query = $(this).val();

        if (query.length < 2) {
            $('#result-box').hide();
            return;
        }

        $.ajax({
            url: '/search-anggota',
            method: 'GET',
            data: {
                q: query
            },
            success: function(data) {
                let html = '';

                data.forEach(function(item) {
                    html += `
                    <div class="result-item" data-cif="${item.cif}">
                        ${item.cif} &nbsp; ${item.nama} &nbsp; ${item.nama_kelompok}
                    </div>
                `;
                });

                $('#result-box').html(html).show();
            }
        });
    });


    // klik item
    $(document).on('click', '.result-item', function() {
        let nama = $(this).text();
        let cif = $(this).data('cif');

        $('#search').val(nama);
        $('#cif').val(cif);
        $('#result-box').hide();

        // redirect (opsional)
        window.location.href = "/informasi_anggota_detail/" + cif;
    });
</script>

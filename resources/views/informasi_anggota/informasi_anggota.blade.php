@extends('layouts.main')

@section('content')
@section('content-header')
<div class="container-fluid">
    <div class="row mb-2">
        
        {{-- TITLE --}}
        <div class="col-sm-6">
            <h1>Informasi Anggota</h1>
        </div>

        {{-- BREADCRUMB --}}
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
    <a href="{{ url('/qa/dashboard') }}">Home</a>
</li>
                <li class="breadcrumb-item active">
                    Informasi Anggota
                </li>
            </ol>
        </div>

    </div>
</div>
@endsection


    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
    <div class="row justify-content-center mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Cari Anggota</h5>
                    <form>
                        <div class="input-group">
                            <select id="search" class="form-control">
                                <option value=""></option>
                            </select>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>


<script>
$(document).ready(function() {

    $('#search').select2({
        width: '100%',
        placeholder: 'Masukkan nama anggota',
        allowClear: true,
        minimumInputLength: 1,
        dropdownParent: $('.card-body'),
        ajax: {
            url: "{{ route('search_anggota') }}",
            dataType: 'json',
            delay: 250,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: function(params) {
                return {
                    q: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: data.map(function(item) {
                        return {
                            id: item.cif,
                            text: item.cif + ' - ' + item.cust_short_name + ' - ' + item.nama_kel
                        };
                    })
                };
            }
        }
    });

    $('#search').on('select2:select', function (e) {
    let cif = e.params.data.id;
    window.location.href = "{{ url('informasi_anggota_detail') }}/" + cif;
});

});
</script>
@endpush
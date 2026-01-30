@extends('layouts.main')

@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ $title }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="dataTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Unit</th>
                                    <th>CIF</th>
                                    <th>Ref Sampling</th>
                                    <th>Nama</th>
                                    <th>Kode Kel</th>
                                    <th>Code AO</th>
                                    <th>Kategori Audit</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    
    let table;
    
    $(document).ready(function () {

        // Initialize DataTable dengan AJAX
        table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('rencana.audit.data_detail') }}",
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'unit' },
                { data: 'cif' },
                { data: 'id_ref_sampling' },
                { data: 'nama' },
                { data: 'kode_kel' },
                { data: 'cao' },
                { data: 'status_sampling' },
                { data: 'aksi', orderable: false, searchable: false }
            ]
        });
        
    });
</script>
@endpush
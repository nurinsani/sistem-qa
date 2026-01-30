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
                        <button type="button" class="btn btn-sm btn-primary mb-3 mr-2"
                                data-toggle="modal" data-target="#modalAuditRutin">
                            <i class="fas fa-plus"></i> Audit Rutin
                        </button>

                        <button type="button" class="btn btn-sm btn-primary mb-3"
                                data-toggle="modal" data-target="#modalAuditKhusus">
                            <i class="fas fa-plus"></i> Audit Khusus
                        </button>

                        <table id="dataTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Cabang</th>
                                    <th>Unit</th>
                                    <th>Ref Sampling</th>
                                    <th>Tanggal Awal</th>
                                    <th>Tanggal Akhir</th>
                                    <th>Status</th>
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

    @include('rencana_audit.form_rencana_audit_rutin')
    @include('rencana_audit.form_rencana_audit_khusus')

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    
    let table;
    
    $(document).ready(function () {

        // Initialize DataTable dengan AJAX
        table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('rencana.audit.data') }}",
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'unit' },
                { data: 'unit' },
                { data: 'id_ref_sampling' },
                { data: 'tanggal_awal' },
                { data: 'tanggal_akhir' },
                { data: 'status', orderable: false, searchable: false },
                { data: 'aksi', orderable: false, searchable: false }
            ]
        });

        // Initialize Select2
        $('#modalAuditRutin').on('shown.bs.modal', function () {
            $('.select2').select2({
                theme: 'bootstrap4',
                dropdownParent: $('#modalAuditRutin'),
                placeholder: '-- Pilih AP --',
                allowClear: true
            });
        });

        $('#modalAuditKhusus').on('shown.bs.modal', function () {
            $('.select2').select2({
                theme: 'bootstrap4',
                dropdownParent: $('#modalAuditKhusus'),
                placeholder: '-- Kelompok --',
                allowClear: true
            });
        });

        // AJAX Form Submit
        $('#formAuditRutin').on('submit', function(e) {
            e.preventDefault();
            
            let formData = new FormData(this);
            
            $.ajax({
                url: "{{ route('audit.rutin.store') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    // Disable button saat loading
                    $('#formAuditRutin button[type="submit"]').prop('disabled', true).html('Loading...');
                },
                success: function(response) {
                    // Close modal
                    $('#modalAuditRutin').modal('hide');
                    
                    // Reset form
                    $('#formAuditRutin')[0].reset();
                    $('.select2').val(null).trigger('change');
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message || 'Data berhasil disimpan',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    
                    // Reload DataTable jika ada
                    if ($.fn.DataTable.isDataTable('#dataTable')) {
                        $('#dataTable').DataTable().ajax.reload();
                    } else {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Terjadi kesalahan!';
                    
                    if (xhr.status === 422) {
                        // Validation errors
                        let errors = xhr.responseJSON.errors;
                        errorMessage = Object.values(errors).flat().join('<br>');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        html: errorMessage
                    });
                },
                complete: function() {
                    // Enable button kembali
                    $('#formAuditRutin button[type="submit"]').prop('disabled', false).html('Simpan');
                }
            });
        });

        $('#formAuditKhusus').on('submit', function(e) {
            e.preventDefault();
            
            let formData = new FormData(this);
            
            $.ajax({
                url: "{{ route('audit.khusus.store') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    // Disable button saat loading
                    $('#formAuditKhusus button[type="submit"]').prop('disabled', true).html('Loading...');
                },
                success: function(response) {
                    // Close modal
                    $('#modalAuditKhusus').modal('hide');
                    
                    // Reset form
                    $('#formAuditKhusus')[0].reset();
                    $('.select2').val(null).trigger('change');
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message || 'Data berhasil disimpan',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    
                    // Reload DataTable jika ada
                    if ($.fn.DataTable.isDataTable('#dataTable')) {
                        $('#dataTable').DataTable().ajax.reload();
                    } else {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Terjadi kesalahan!';
                    
                    if (xhr.status === 422) {
                        // Validation errors
                        let errors = xhr.responseJSON.errors;
                        errorMessage = Object.values(errors).flat().join('<br>');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        html: errorMessage
                    });
                },
                complete: function() {
                    // Enable button kembali
                    $('#formAuditKhusus button[type="submit"]').prop('disabled', false).html('Simpan');
                }
            });
        });
    });
</script>
@endpush
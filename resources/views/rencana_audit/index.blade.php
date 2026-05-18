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
                        <button type="button" class="btn btn-sm btn-primary mb-3 mr-2" data-toggle="modal"
                            data-target="#modalAuditRutin">
                            <i class="fas fa-plus"></i> Audit Rutin
                        </button>

                        <button type="button" class="btn btn-sm btn-primary mb-3" data-toggle="modal"
                            data-target="#modalAuditKhusus">
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

        $(document).ready(function() {

                    // Initialize DataTable dengan AJAX
                    table = $('#dataTable').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: "{{ route('rencana.audit.data') }}",
                        columns: [{
                                data: 'DT_RowIndex',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'area'
                            },
                            {
                                data: 'unit'
                            },
                            {
                                data: 'id_ref_sampling'
                            },
                            {
                                data: 'tanggal_awal'
                            },
                            {
                                data: 'tanggal_akhir'
                            },
                            {
                                data: 'status'
                            },
                            {
                                data: 'aksi'
                            }
                        ]
                    });

                    // Initialize Select2 untuk Audit Rutin
                    $('#modalAuditRutin').on('shown.bs.modal', function() {
                        $('.select2', this).select2({
                            theme: 'bootstrap4',
                            dropdownParent: $('#modalAuditRutin'),
                            placeholder: '-- Pilih AP --',
                            allowClear: true
                        });
                    });

                    // Form Submit Audit Rutin
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
                                $('#formAuditRutin button[type="submit"]').prop('disabled', true).html(
                                    '<i class="fa fa-spinner fa-spin"></i> Loading...');
                            },
                            success: function(response) {
                                $('#modalAuditRutin').modal('hide');
                                $('#formAuditRutin')[0].reset();
                                $('#modalAuditRutin .select2').val(null).trigger('change');

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message || 'Data berhasil disimpan',
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                let errorMessage = 'Terjadi kesalahan!';

                                if (xhr.status === 422) {
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
                                $('#formAuditRutin button[type="submit"]').prop('disabled', false).html(
                                    '<i class="fa fa-save"></i> Simpan');
                            }
                        });
                    });

                    $('#modalAuditKhusus').on('shown.bs.modal', function() {
                        $('#kode_kel').select2({
                            theme: 'bootstrap4',
                            dropdownParent: $('#modalAuditKhusus'),
                            placeholder: '-- Pilih Kelompok --',
                            allowClear: true,
                            minimumInputLength: 2,
                            ajax: {
                                url: "{{ route('kelompok.search') }}",
                                dataType: 'json',
                                delay: 300,
                                data: function(params) {
                                    return {
                                        q: params.term
                                    };
                                },
                                processResults: function(data) {
                                    return {
                                        results: data
                                    };
                                },
                                cache: true
                            }
                        });
                    });


                    // Reset form ketika modal ditutup
                    $('#modalAuditKhusus').on('hidden.bs.modal', function() {
                        $('#formAuditKhusus')[0].reset();
                        $('#kode_kel').val(null).trigger('change');
                        $('#nama_kelompok').val('');
                        $('#nama_ao').val('');
                        $('#list-cif').html(
                            '<p class="text-muted text-center mb-0"><i class="fa fa-info-circle"></i> Pilih kelompok terlebih dahulu</p>'
                            );
                        $('#selected-count').text('0');
                    });

                    // Ketika kelompok dipilih, ambil data CIF
                    $('#kode_kel').on('change', function() {
                        let kodeKel = $(this).val();

                        if (!kodeKel) {
                            $('#nama_kelompok').val('');
                            $('#nama_ao').val('');
                            $('#list-cif').html(
                                '<p class="text-muted text-center mb-0"><i class="fa fa-info-circle"></i> Pilih kelompok terlebih dahulu</p>'
                                );
                            $('#selected-count').text('0');
                            return;
                        }

                        // Set nama kelompok dari option yang dipilih
                        $('#nama_kelompok').val($(this).find('option:selected').text());

                        // Ambil data CIF via AJAX
                        $.ajax({
                            url: "{{ route('kelompok.get-cif') }}", // Sesuaikan dengan route Anda
                            type: 'GET',
                            data: {
                                kode_kel: kodeKel
                            },
                            beforeSend: function() {
                                $('#list-cif').html(
                                    '<p class="text-center"><i class="fa fa-spinner fa-spin"></i> Memuat data...</p>'
                                    );
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Set nama AO
                                    $('#nama_ao').val(response.data.nama_ao || '-');

                                    // Render checkbox CIF
                                    let html = '';
                                    if (response.data.cif_list && response.data.cif_list.length > 0) {
                                        response.data.cif_list.forEach(function(cif, index) {
                                            html += `
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input cif-checkbox" 
                                               id="cif_${index}" 
                                               name="cif[]" 
                                               value="${cif.cif}">
                                        <label class="custom-control-label" for="cif_${index}">
                                            ${cif.cif} - ${cif.Cust_Short_name || 'N/A'}
                                        </label>
                                    </div>
                                `;
                                        });
                                    } else {
                                        html =
                                            '<p class="text-muted text-center mb-0"><i class="fa fa-info-circle"></i> Tidak ada data CIF</p>';
                                    }

                                    $('#list-cif').html(html);
                                    updateSelectedCount();
                                } else {
                                    $('#list-cif').html(
                                        '<p class="text-danger text-center mb-0"><i class="fa fa-exclamation-circle"></i> ' +
                                        (response.message || 'Gagal memuat data') + '</p>');
                                }
                            },
                            error: function(xhr) {
                                $('#list-cif').html(
                                    '<p class="text-danger text-center mb-0"><i class="fa fa-exclamation-circle"></i> Terjadi kesalahan saat memuat data</p>'
                                    );
                            }
                        });
                    });

                    // Event handler untuk checkbox CIF (menggunakan event delegation)
                    $(document).on('change', '.cif-checkbox', function() {
                        updateSelectedCount();
                    });

                    // Form Submit Audit Khusus
                    $('#formAuditKhusus').on('submit', function(e) {
                        e.preventDefault();

                        // Ambil metode input yang dipilih (kelompok atau manual)
                        let inputMethod = $('input[name="input_method"]:checked').val();

                        // Validasi kondisional: Hanya cek CIF jika metodenya adalah 'kelompok'
                        if (inputMethod === 'kelompok') {
                            if ($('.cif-checkbox:checked').length === 0) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Perhatian!',
                                    text: 'Pilih minimal 1 CIF untuk audit khusus'
                                });
                                return;
                            }
                        } else {
                            // Validasi tambahan untuk mode manual di sisi client (opsional tapi disarankan)
                            if ($('#nik').val().trim() === '' || $('#nama_manual').val().trim() === '') {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Perhatian!',
                                    text: 'NIK dan Nama harus diisi untuk input manual'
                                });
                                return;
                            }
                        }

                        let formData = new FormData(this);

                        $.ajax({
                            url: "{{ route('audit.khusus.store') }}",
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            beforeSend: function() {
                                $('#btnSubmit').prop('disabled', true).html(
                                    '<i class="fa fa-spinner fa-spin"></i> Loading...');
                            },
                            success: function(response) {
                                $('#modalAuditKhusus').modal('hide');
                                $('#formAuditKhusus')[0].reset();

                                // Reset Select2 jika ada
                                if ($('#kode_kel').hasClass("select2-hidden-accessible")) {
                                    $('#kode_kel').val(null).trigger('change');
                                }

                                // Bersihkan list CIF
                                $('#list-cif').html(
                                    '<p class="text-muted text-center mb-0"><i class="fa fa-info-circle"></i> Pilih kelompok terlebih dahulu</p>'
                                    );

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message ||
                                        'Data audit khusus berhasil disimpan',
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                                // Reload datatable jika variabel 'table' terdefinisi
                                if (typeof table !== 'undefined') {
                                    table.ajax.reload();
                                }
                            },
                            error: function(xhr) {
                                let errorMessage = 'Terjadi kesalahan!';

                                if (xhr.status === 422) {
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
                                $('#btnSubmit').prop('disabled', false).html(
                                    '<i class="fa fa-save"></i> Simpan');
                            }
                        });
                    });

                    $(document).ready(function() {
                        $('input[name="input_method"]').on('change', function() {
                            let method = $(this).val();

                            if (method === 'manual') {
                                // Sembunyikan Kelompok & CIF
                                $('#section-kelompok').hide();
                                $('#section-cif').hide();
                                // Tampilkan Manual
                                $('#section-manual').fadeIn();

                                // Atur attribute required
                                $('#kode_kel').attr('required', false);
                                $('#nik').attr('required', true);
                                $('#nama_manual').attr('required', true);
                            } else {
                                // Tampilkan Kelompok & CIF
                                $('#section-kelompok').fadeIn();
                                $('#section-cif').fadeIn();
                                // Sembunyikan Manual
                                $('#section-manual').hide();

                                // Atur attribute required
                                $('#kode_kel').attr('required', true);
                                $('#nik').attr('required', false);
                                $('#nama_manual').attr('required', false);
                            }
                        });

                    });

                    function updateSelectedCount() {
                        let count = $('.cif-checkbox:checked').length;
                        $('#selected-count').text(count);
                    }
                });
    </script>
@endpush

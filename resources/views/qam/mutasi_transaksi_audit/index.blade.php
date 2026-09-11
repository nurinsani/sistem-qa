@extends('layouts.main')

@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-dark">
                    {{ $title }}
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">

        <!-- Main Card -->
        <div class="card card-outline shadow-sm">
            <div class="card-header">
                <h5 class="card-title m-0">Data Transaksi Audit</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableMutasi" class="table table-bordered table-hover w-100">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th>Cabang / Area</th>
                                <th>Unit</th>
                                <th>Ref Sampling</th>
                                <th>Tgl Awal</th>
                                <th>Tgl Akhir</th>
                                <th style="width: 8%">Jml Sampling</th>
                                <th>Petugas QA</th>
                                <th style="width: 8%">Status</th>
                                <th style="width: 8%; text-align: center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ubah Status -->
    <div class="modal fade" id="modalUbahStatus" tabindex="-1" role="dialog" aria-labelledby="modalUbahStatusLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUbahStatusLabel">Ubah Status Audit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formUbahStatus">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label>Ref Sampling</label>
                            <input type="text" class="form-control" id="status_ref_display" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label for="select_status">Pilih Status Baru <span class="text-danger">*</span></label>
                            <select class="form-control" id="select_status" name="status" required>
                                <option value="" disabled>-- Pilih Status --</option>
                                <option value="proses">PROSES (Sedang Berjalan)</option>
                                <option value="done">DONE (Selesai)</option>
                                <option value="pending">PENDING (Tertunda)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmitStatus">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Ubah Petugas -->
    <div class="modal fade" id="modalUbahPetugas" tabindex="-1" role="dialog" aria-labelledby="modalUbahPetugasLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUbahPetugasLabel">Ubah Petugas QA</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formUbahPetugas">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label>Ref Sampling</label>
                            <input type="text" class="form-control" id="petugas_ref_display" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label for="select_petugas">Pilih Petugas QA Baru <span class="text-danger">*</span></label>
                            <select class="form-control select2" id="select_petugas" name="user_id" style="width: 100%;" required>
                                <option value="">-- Pilih Petugas QA --</option>
                                @foreach ($qa as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->code_qa }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmitPetugas">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Pindah Unit -->
    <div class="modal fade" id="modalPindahUnit" tabindex="-1" role="dialog" aria-labelledby="modalPindahUnitLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPindahUnitLabel">Pindah Unit Cabang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formPindahUnit">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label>Ref Sampling</label>
                            <input type="text" class="form-control" id="unit_ref_display" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label for="select_unit">Pilih Unit Cabang Baru <span class="text-danger">*</span></label>
                            <select class="form-control select2" id="select_unit" name="unit" style="width: 100%;" required>
                                <option value="">-- Pilih Unit Cabang --</option>
                                @foreach ($branch as $b)
                                    <option value="{{ $b->kode_branch }}">
                                        {{ $b->unit }} ({{ $b->kode_branch }}) - {{ $b->area }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmitUnit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Riwayat Mutasi (Log) -->
    <div class="modal fade" id="modalLogMutasi" tabindex="-1" role="dialog" aria-labelledby="modalLogMutasiLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLogMutasiLabel">Riwayat Mutasi Audit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <span class="font-weight-bold">Ref Sampling:</span>
                        <span id="log_ref_display" class="ml-1"></span>
                    </div>

                    <div id="logLoading" class="text-center py-4">
                        <i class="fas fa-spinner fa-spin fa-2x text-primary mb-2"></i>
                        <p class="text-muted small mb-0">Memuat riwayat perubahan...</p>
                    </div>

                    <div id="logEmpty" class="text-center py-4 d-none">
                        <p class="text-muted mb-0">Belum ada riwayat mutasi untuk audit ini.</p>
                    </div>

                    <div class="table-responsive" id="logTableWrapper">
                        <table class="table table-bordered table-sm table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th style="width: 18%">Waktu</th>
                                    <th style="width: 15%">Jenis Mutasi</th>
                                    <th>Nilai Lama</th>
                                    <th>Nilai Baru</th>
                                    <th style="width: 18%">Diubah Oleh</th>
                                </tr>
                            </thead>
                            <tbody id="logTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let table;
        let activeUpdateUrl = '';

        $(document).ready(function() {
            // Setup CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'
                }
            });

            // Initialize DataTable
            table = $('#dataTableMutasi').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route($prefix . '.mutasi.transaksi.data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center align-middle'
                    },
                    {
                        data: 'area',
                        className: 'align-middle'
                    },
                    {
                        data: 'nama_unit',
                        className: 'align-middle'
                    },
                    {
                        data: 'id_ref_sampling',
                        className: 'align-middle font-weight-bold'
                    },
                    {
                        data: 'tanggal_awal',
                        className: 'align-middle'
                    },
                    {
                        data: 'tanggal_akhir',
                        className: 'align-middle'
                    },
                    {
                        data: 'jumlah_sampling',
                        className: 'text-center align-middle'
                    },
                    {
                        data: 'petugas',
                        className: 'align-middle'
                    },
                    {
                        data: 'status_badge',
                        className: 'text-center align-middle'
                    },
                    {
                        data: 'aksi',
                        orderable: false,
                        searchable: false,
                        className: 'text-center align-middle'
                    }
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari data audit...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Data tidak ditemukan",
                    emptyTable: "Tidak ada data transaksi audit",
                    zeroRecords: "Tidak ada data yang cocok",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    }
                }
            });

            // Inisialisasi Select2 di modal
            $('#modalUbahPetugas').on('shown.bs.modal', function() {
                $('#select_petugas').select2({
                    theme: 'bootstrap4',
                    dropdownParent: $('#modalUbahPetugas'),
                    placeholder: '-- Pilih Petugas QA --',
                    allowClear: true
                });
            });

            $('#modalPindahUnit').on('shown.bs.modal', function() {
                $('#select_unit').select2({
                    theme: 'bootstrap4',
                    dropdownParent: $('#modalPindahUnit'),
                    placeholder: '-- Pilih Unit Cabang --',
                    allowClear: true
                });
            });

            // 1. Event Klik Ubah Status
            $(document).on('click', '.btn-ubah-status', function() {
                const id = $(this).data('id');
                const ref = $(this).data('ref');
                const status = $(this).data('status');
                activeUpdateUrl = $(this).data('url');

                $('#status_ref_display').val(ref);
                $('#select_status').val(status);
                $('#modalUbahStatus').modal('show');
            });

            // Submit Ubah Status
            $('#formUbahStatus').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#btnSubmitStatus');
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');

                $.ajax({
                    url: activeUpdateUrl,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        $('#modalUbahStatus').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        table.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        const msg = xhr.responseJSON ? xhr.responseJSON.message :
                            'Terjadi kesalahan sistem';
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Mengubah Status',
                            text: msg
                        });
                    },
                    complete: function() {
                        btn.prop('disabled', false).text('Simpan');
                    }
                });
            });

            // 2. Event Klik Ubah Petugas
            $(document).on('click', '.btn-ubah-petugas', function() {
                const ref = $(this).data('ref');
                activeUpdateUrl = $(this).data('url');

                $('#petugas_ref_display').val(ref);
                $('#select_petugas').val('').trigger('change');
                $('#modalUbahPetugas').modal('show');
            });

            // Submit Ubah Petugas
            $('#formUbahPetugas').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#btnSubmitPetugas');
                btn.prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: activeUpdateUrl,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        $('#modalUbahPetugas').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        table.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        const msg = xhr.responseJSON ? xhr.responseJSON.message :
                            'Terjadi kesalahan sistem';
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Mengubah Petugas',
                            text: msg
                        });
                    },
                    complete: function() {
                        btn.prop('disabled', false).text('Simpan');
                    }
                });
            });

            // 3. Event Klik Pindah Unit
            $(document).on('click', '.btn-pindah-unit', function() {
                const ref = $(this).data('ref');
                const unit = $(this).data('unit');
                activeUpdateUrl = $(this).data('url');

                $('#unit_ref_display').val(ref);
                $('#select_unit').val(unit).trigger('change');
                $('#modalPindahUnit').modal('show');
            });

            // Submit Pindah Unit
            $('#formPindahUnit').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#btnSubmitUnit');
                btn.prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: activeUpdateUrl,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        $('#modalPindahUnit').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        table.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        const msg = xhr.responseJSON ? xhr.responseJSON.message :
                            'Terjadi kesalahan sistem';
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Pindah Unit',
                            text: msg
                        });
                    },
                    complete: function() {
                        btn.prop('disabled', false).text('Simpan');
                    }
                });
            });

            // 4. Event Klik Lihat Log Mutasi
            $(document).on('click', '.btn-lihat-log', function() {
                const ref = $(this).data('ref');
                const url = $(this).data('url');

                $('#log_ref_display').text(ref);
                $('#logLoading').removeClass('d-none');
                $('#logEmpty').addClass('d-none');
                $('#logTableWrapper').addClass('d-none');
                $('#logTableBody').empty();

                $('#modalLogMutasi').modal('show');

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(res) {
                        $('#logLoading').addClass('d-none');
                        if (res.data && res.data.length > 0) {
                            $('#logTableWrapper').removeClass('d-none');
                            let rows = '';
                            res.data.forEach((item, index) => {
                                rows += `
                                <tr>
                                    <td class="text-center align-middle">${index + 1}</td>
                                    <td class="align-middle">${item.created_at}</td>
                                    <td class="align-middle text-center">${item.jenis_mutasi}</td>
                                    <td class="align-middle">${item.nilai_lama}</td>
                                    <td class="align-middle">${item.nilai_baru}</td>
                                    <td class="align-middle">${item.diubah_oleh}</td>
                                </tr>
                            `;
                            });
                            $('#logTableBody').html(rows);
                        } else {
                            $('#logEmpty').removeClass('d-none');
                        }
                    },
                    error: function(xhr) {
                        $('#logLoading').addClass('d-none');
                        $('#logEmpty').removeClass('d-none').find('p').text(
                            'Gagal memuat log mutasi.');
                    }
                });
            });
        });
    </script>
@endpush

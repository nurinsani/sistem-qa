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

    <!-- Modal Kelola Status Sampling per CIF -->
    <div class="modal fade" id="modalStatusSampling" tabindex="-1" role="dialog"
        aria-labelledby="modalStatusSamplingLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content shadow border-0">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold" id="modalStatusSamplingLabel">
                        Kelola Status Sampling per CIF
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-3">
                    <!-- Info Header -->
                    <div class="card card-outline card-primary mb-3 bg-light">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="mr-4">
                                    <span class="text-muted d-block small">Ref Sampling:</span>
                                    <span class="font-weight-bold text-primary font-size-16" id="modalSamplingRef">-</span>
                                </div>
                                <div class="border-left pl-4">
                                    <span class="text-muted d-block small">Unit / Cabang:</span>
                                    <span class="font-weight-bold text-dark" id="modalSamplingUnit">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Toolbar: Search & Batch Action -->
                    <div class="card border-0 shadow-none mb-3">
                        <div class="card-body p-2 bg-white rounded border">
                            <div class="row align-items-center">
                                <div class="col-lg-5 col-md-6 mb-2 mb-lg-0">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="searchCifInput"
                                            placeholder="Cari CIF, Nama Nasabah, Kelompok, atau AO...">
                                    </div>
                                </div>
                                <div class="col-lg-7 col-md-6 text-md-right">
                                    <form id="formBatchStatus" class="form-inline justify-content-md-end"
                                        onsubmit="return false;">
                                        <span class="text-muted small mr-2 font-weight-bold" id="selectedCountText">0
                                            dipilih</span>
                                        <select class="form-control form-control-sm mr-2" id="batchSelectStatus"
                                            style="width: 150px;">
                                            <option value="" disabled selected>-- Ubah Status --</option>
                                            <option value="proses">PROSES</option>
                                            <option value="done">DONE</option>
                                            <option value="pending">PENDING</option>
                                            <option value="tanggapan">TANGGAPAN</option>
                                            <option value="evaluasi">EVALUASI</option>
                                        </select>
                                        <button type="button" class="btn btn-sm btn-primary" id="btnApplyBatchStatus"
                                            disabled>
                                            <i class="fas fa-check-circle mr-1"></i> Terapkan Massal
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Loading Spinner -->
                    <div id="samplingLoading" class="text-center py-5">
                        <i class="fas fa-spinner fa-spin fa-2x text-primary mb-2"></i>
                        <p class="text-muted small mb-0">Memuat data sampling nasabah...</p>
                    </div>

                    <!-- Empty State -->
                    <div id="samplingEmpty" class="text-center py-5 d-none">
                        <i class="fas fa-folder-open fa-3x text-muted mb-2"></i>
                        <p class="text-muted mb-0">Tidak ada data sampling untuk ref audit ini.</p>
                    </div>

                    <!-- Table List Sampling -->
                    <div class="table-responsive d-none" id="samplingTableWrapper" style="max-height: 480px;">
                        <table class="table table-bordered table-hover table-sm text-nowrap mb-0" id="tableSamplingList">
                            <thead class="thead-light" style="position: sticky; top: 0; z-index: 2;">
                                <tr>
                                    <th style="width: 3%; text-align: center;">
                                        <input type="checkbox" id="checkAllCif" title="Pilih Semua">
                                    </th>
                                    <th style="width: 4%; text-align: center;">No</th>
                                    <th>CIF</th>
                                    <th>Nama Nasabah</th>
                                    <th>Kelompok</th>
                                    <th>Nama AO</th>
                                    <th style="width: 8%; text-align: center;">Kategori</th>
                                    <th style="width: 12%; text-align: center;">Status Saat Ini</th>
                                    <th style="width: 20%; text-align: center;">Ganti Status</th>
                                </tr>
                            </thead>
                            <tbody id="tbodySamplingList">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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
                            <select class="form-control select2" id="select_petugas" name="user_id" style="width: 100%;"
                                required>
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
                            <select class="form-control select2" id="select_unit" name="unit" style="width: 100%;"
                                required>
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

    <!-- Modal Ubah Tanggal -->
    <div class="modal fade" id="modalUbahTanggal" tabindex="-1" role="dialog" aria-labelledby="modalUbahTanggalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUbahTanggalLabel">Ubah Tanggal Audit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formUbahTanggal">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label>Ref Sampling</label>
                            <input type="text" class="form-control" id="tanggal_ref_display" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label for="input_tanggal_awal">Tanggal Awal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="input_tanggal_awal" name="tanggal_awal"
                                required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="input_tanggal_akhir">Tanggal Akhir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="input_tanggal_akhir" name="tanggal_akhir"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmitTanggal">Simpan</button>
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

            const samplingUpdateUrl = "{{ route($prefix . '.mutasi.transaksi.sampling.update-status') }}";
            let activeSamplingFetchUrl = '';

            // Helper format badges
            function getStatusBadge(status) {
                const s = (status || 'pending').toLowerCase();
                const badges = {
                    'done': '<span class="badge badge-success px-2 py-1">DONE</span>',
                    'selesai': '<span class="badge badge-success px-2 py-1">DONE</span>',
                    'proses': '<span class="badge badge-warning px-2 py-1 text-dark">PROSES</span>',
                    'pending': '<span class="badge badge-secondary px-2 py-1">PENDING</span>',
                    'tanggapan': '<span class="badge badge-info px-2 py-1">TANGGAPAN</span>',
                    'evaluasi': '<span class="badge badge-primary px-2 py-1">EVALUASI</span>'
                };
                return badges[s] || `<span class="badge badge-light border px-2 py-1">${s.toUpperCase()}</span>`;
            }

            function getKategoriBadge(kat) {
                const k = (kat || 'MEDIUM').toUpperCase();
                if (k === 'LOW') return '<span class="badge badge-success">LOW</span>';
                if (k === 'HIGH') return '<span class="badge badge-danger">HIGH</span>';
                return '<span class="badge badge-warning text-dark">MEDIUM</span>';
            }

            // Function to load sampling data
            function loadSamplingData(url) {
                $('#samplingLoading').removeClass('d-none');
                $('#samplingEmpty').addClass('d-none');
                $('#samplingTableWrapper').addClass('d-none');
                $('#tbodySamplingList').empty();
                $('#checkAllCif').prop('checked', false);
                updateSelectedCount();

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(res) {
                        $('#samplingLoading').addClass('d-none');
                        if (res.success && res.data && res.data.length > 0) {
                            $('#modalSamplingRef').text(res.id_ref_sampling);
                            $('#modalSamplingUnit').text(res.unit);

                            // Update count badges
                            if (res.counts) {
                                $('#countTotal').text(res.counts.total || 0);
                                $('#countPending').text(res.counts.pending || 0);
                                $('#countProses').text(res.counts.proses || 0);
                                $('#countTanggapan').text(res.counts.tanggapan || 0);
                                $('#countEvaluasi').text(res.counts.evaluasi || 0);
                                $('#countDone').text(res.counts.done || 0);
                            }

                            let rows = '';
                            res.data.forEach((item, index) => {
                                const currentStatus = (item.status || 'pending').toLowerCase();
                                rows += `
                                    <tr data-id="${item.id}" data-cif="${item.cif}">
                                        <td class="text-center align-middle">
                                            <input type="checkbox" class="check-cif-item" value="${item.id}">
                                        </td>
                                        <td class="text-center align-middle">${index + 1}</td>
                                        <td class="align-middle font-weight-bold text-primary">${item.cif}</td>
                                        <td class="align-middle font-weight-bold">${item.nama}</td>
                                        <td class="align-middle">${item.nama_kel}</td>
                                        <td class="align-middle">${item.nama_ao}</td>
                                        <td class="text-center align-middle">${getKategoriBadge(item.status_sampling)}</td>
                                        <td class="text-center align-middle td-status-badge">${getStatusBadge(currentStatus)}</td>
                                        <td class="text-center align-middle">
                                            <div class="input-group input-group-sm">
                                                <select class="form-control form-control-sm select-cif-status" data-id="${item.id}" data-cif="${item.cif}" data-current="${currentStatus}">
                                                    <option value="proses" ${currentStatus === 'proses' ? 'selected' : ''}>PROSES</option>
                                                    <option value="done" ${currentStatus === 'done' || currentStatus === 'selesai' ? 'selected' : ''}>DONE</option>
                                                    <option value="pending" ${currentStatus === 'pending' ? 'selected' : ''}>PENDING</option>
                                                    <option value="tanggapan" ${currentStatus === 'tanggapan' ? 'selected' : ''}>TANGGAPAN</option>
                                                    <option value="evaluasi" ${currentStatus === 'evaluasi' ? 'selected' : ''}>EVALUASI</option>
                                                </select>
                                                <div class="input-group-append">
                                                    <button class="btn btn-sm btn-outline-primary btn-save-cif-status" data-id="${item.id}" title="Simpan Status">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                `;
                            });

                            $('#tbodySamplingList').html(rows);
                            $('#samplingTableWrapper').removeClass('d-none');
                        } else {
                            $('#samplingEmpty').removeClass('d-none');
                        }
                    },
                    error: function(xhr) {
                        $('#samplingLoading').addClass('d-none');
                        $('#samplingEmpty').removeClass('d-none').find('p').text(
                            'Gagal memuat data sampling.');
                    }
                });
            }

            // 0. Event Klik Ubah Status (Per CIF)
            $(document).on('click', '.btn-kelola-status-cif', function() {
                const ref = $(this).data('ref');
                const unit = $(this).data('unit');
                activeSamplingFetchUrl = $(this).data('url');

                $('#modalSamplingRef').text(ref);
                $('#modalSamplingUnit').text(unit);
                $('#searchCifInput').val('');
                $('#modalStatusSampling').modal('show');

                loadSamplingData(activeSamplingFetchUrl);
            });

            // Live search filter di modal CIF
            $('#searchCifInput').on('keyup', function() {
                const keyword = $(this).val().toLowerCase();
                $('#tbodySamplingList tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(keyword) > -1);
                });
            });

            // Checkbox All CIF
            $('#checkAllCif').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('#tbodySamplingList tr:visible .check-cif-item').prop('checked', isChecked);
                updateSelectedCount();
            });

            $(document).on('change', '.check-cif-item', function() {
                updateSelectedCount();
            });

            function updateSelectedCount() {
                const totalVisible = $('#tbodySamplingList tr:visible .check-cif-item').length;
                const checkedCount = $('#tbodySamplingList .check-cif-item:checked').length;
                $('#selectedCountText').text(checkedCount + ' dipilih');
                $('#btnApplyBatchStatus').prop('disabled', checkedCount === 0);

                if (totalVisible > 0 && checkedCount === totalVisible) {
                    $('#checkAllCif').prop('checked', true);
                } else {
                    $('#checkAllCif').prop('checked', false);
                }
            }

            // Simpan status per single CIF
            $(document).on('click', '.btn-save-cif-status', function() {
                const btn = $(this);
                const id = btn.data('id');
                const row = btn.closest('tr');
                const select = row.find('.select-cif-status');
                const newStatus = select.val();
                const currentStatus = select.data('current');

                if (newStatus === currentStatus) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Info',
                        text: 'Status yang dipilih sama dengan status saat ini (' + currentStatus
                            .toUpperCase() + ')',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    return;
                }

                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

                $.ajax({
                    url: samplingUpdateUrl,
                    type: 'POST',
                    data: {
                        _method: 'PUT',
                        id: id,
                        status: newStatus
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                        loadSamplingData(activeSamplingFetchUrl);
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
                        btn.prop('disabled', false).html('<i class="fas fa-check"></i>');
                    }
                });
            });

            // Terapkan Status Massal (Batch)
            $('#btnApplyBatchStatus').on('click', function() {
                const selectedIds = [];
                $('#tbodySamplingList .check-cif-item:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                const newStatus = $('#batchSelectStatus').val();
                if (!newStatus) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pilih Status',
                        text: 'Silakan pilih status baru terlebih dahulu pada dropdown!'
                    });
                    return;
                }

                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pilih CIF',
                        text: 'Silakan centang minimal satu nasabah/CIF!'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Ubah Status Massal?',
                    text: `Apakah Anda yakin ingin mengubah status ${selectedIds.length} data sampling terpilih menjadi ${newStatus.toUpperCase()}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#007bff',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Ubah!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const btn = $('#btnApplyBatchStatus');
                        btn.prop('disabled', true).html(
                            '<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');

                        $.ajax({
                            url: samplingUpdateUrl,
                            type: 'POST',
                            data: {
                                _method: 'PUT',
                                ids: selectedIds,
                                status: newStatus
                            },
                            success: function(res) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: res.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                $('#batchSelectStatus').val('');
                                loadSamplingData(activeSamplingFetchUrl);
                                table.ajax.reload(null, false);
                            },
                            error: function(xhr) {
                                const msg = xhr.responseJSON ? xhr.responseJSON
                                    .message : 'Terjadi kesalahan sistem';
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Mengubah Status',
                                    text: msg
                                });
                            },
                            complete: function() {
                                btn.prop('disabled', false).html(
                                    '<i class="fas fa-check-circle mr-1"></i> Terapkan Massal'
                                    );
                            }
                        });
                    }
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

            // Event Klik Ubah Tanggal
            $(document).on('click', '.btn-ubah-tanggal', function() {
                const ref = $(this).data('ref');
                const tglAwal = $(this).data('tgl-awal');
                const tglAkhir = $(this).data('tgl-akhir');
                activeUpdateUrl = $(this).data('url');

                $('#tanggal_ref_display').val(ref);
                $('#input_tanggal_awal').val(tglAwal);
                $('#input_tanggal_akhir').val(tglAkhir);
                $('#modalUbahTanggal').modal('show');
            });

            // Submit Ubah Tanggal
            $('#formUbahTanggal').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#btnSubmitTanggal');
                btn.prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: activeUpdateUrl,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        $('#modalUbahTanggal').modal('hide');
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
                            title: 'Gagal Mengubah Tanggal',
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

            // 5. Event Klik Hapus Audit
            $(document).on('click', '.btn-hapus-audit', function() {
                const ref = $(this).data('ref');
                const url = $(this).data('url');

                Swal.fire({
                    title: 'Hapus Transaksi Audit?',
                    html: `Apakah Anda yakin ingin menghapus data dengan Ref Sampling <strong>${ref}</strong>?<br><small class="text-danger font-weight-bold">Semua data sampling dan temuan terkait akan ikut dihapus secara permanen!</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Sedang Menghapus...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            success: function(res) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus!',
                                    text: res.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                table.ajax.reload(null, false);
                            },
                            error: function(xhr) {
                                const msg = xhr.responseJSON ? xhr.responseJSON
                                    .message :
                                    'Terjadi kesalahan saat menghapus data';
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Menghapus',
                                    text: msg
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush

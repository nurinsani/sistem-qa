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
                    <div class="card-header">
                        <button type="button" class="btn btn-primary btn-sm" onclick="addData()">
                            <i class="fas fa-plus"></i> Tambah Parameter
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="dataTable" class="table table-sm text-sm table-bordered table-hover dt-responsive"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>No. Ketentuan</th>
                                    <th>Heading</th>
                                    <th>Sub Heading</th>
                                    <th>Sub Sub Heading</th>
                                    <th>Sub Sub Sub Heading</th>
                                    <th>Sub Sub Sub Sub Heading</th>
                                    <th>Sub Sub Sub Sub Sub Heading</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('param_ketentuan.form_modal')
@endsection

@push('scripts')
    <script>
        let table;
        let idParam = null; // Tambahkan ini untuk menampung ID saat edit

        $(document).ready(function() {
            // Inisialisasi Select2 (Jika belum)
            $('.select2').select2({
                theme: 'bootstrap4',
                dropdownParent: $('#modal-form')
            });

            table = $('#dataTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: '{{ route('qam.param_ketentuan.data') }}',
                responsive: true,
                columns: [
                    { data: null, render: (data, type, row, meta) => meta.row + 1 },
                    { data: 'nomor_ketentuan' },
                    { data: 'heading' },
                    { data: 'sub_heading' },
                    { data: 'sub_sub_heading' },
                    { data: 'sub_sub_sub_heading' },
                    { data: 'sub_sub_sub_sub_heading' },
                    { data: 'sub_sub_sub_sub_sub_heading' },
                    {
                        data: null,
                        className: "text-center align-middle", // Tambahkan text-center agar rapi
                        render: function(data) {
                            return `
                                <div style="white-space: nowrap;">
                                    <button onclick="editData(${data.id})" class="btn btn-xs btn-warning">Edit</button>
                                    <button onclick="deleteData(${data.id})" class="btn btn-xs btn-danger">Hapus</button>
                                </div>
                            `;
                        }
                    }
                ]
            });

            $('#formData').on('submit', function(e) {
                e.preventDefault();
                
                // Tentukan URL berdasarkan apakah idParam ada (Edit) atau null (Tambah)
                let url = idParam 
                    ? `{{ url('qam/param-ketentuan/update') }}/${idParam}` 
                    : `{{ route('qam.param_ketentuan.store') }}`;

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        $('#modal-form').modal('hide');
                        table.ajax.reload();
                        Swal.fire('Berhasil!', res.message, 'success');
                        idParam = null; // Reset ID setelah berhasil
                    },
                    error: function(err) {
                        console.log(err); // Lihat error di console jika masih macet
                        let msg = 'Terjadi kesalahan sistem.';
                        if(err.responseJSON && err.responseJSON.errors){
                            msg = '';
                            $.each(err.responseJSON.errors, function(key, val) {
                                msg += val + '<br>';
                            });
                        }
                        Swal.fire('Error!', msg, 'error');
                    }
                });
            });
        });

        function addData() {
            idParam = null; // Pastikan null saat tambah
            $('#formData')[0].reset();
            $('#id_param_profil').val('').trigger('change');
            $('#modal-title').text('Tambah Parameter Ketentuan');
            $('#modal-form').modal('show');
        }

        function editData(id) {
            idParam = id; // Set ID global untuk digunakan saat submit
            $.get(`{{ url('qam/param-ketentuan/edit') }}/${id}`, function(data) {
                $('#id_param_profil').val(data.id_param_profil).trigger('change');
                $('#nomor_ketentuan').val(data.nomor_ketentuan);
                $('#heading').val(data.heading);
                $('#sub_heading').val(data.sub_heading);
                $('#sub_sub_heading').val(data.sub_sub_heading);
                $('#sub_sub_sub_heading').val(data.sub_sub_sub_heading);
                $('#sub_sub_sub_sub_heading').val(data.sub_sub_sub_sub_heading);
                $('#sub_sub_sub_sub_sub_heading').val(data.sub_sub_sub_sub_sub_heading);
                $('#modal-title').text('Edit Parameter Ketentuan');
                $('#modal-form').modal('show');
            }).fail(function() {
                Swal.fire('Error!', 'Gagal mengambil data', 'error');
            });
        }

        function deleteData(id) {
            Swal.fire({
                title: 'Yakin hapus?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`{{ url('qam/param-ketentuan/delete') }}/${id}`, {
                        _token: '{{ csrf_token() }}'
                    }, function(res) {
                        table.ajax.reload();
                        Swal.fire('Dihapus!', res.message, 'success');
                    });
                }
            });
        }
    </script>
@endpush

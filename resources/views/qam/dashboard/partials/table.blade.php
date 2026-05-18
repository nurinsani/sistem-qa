<table class="table table-bordered table-hover dataTableInstance">
    <thead>
        <tr>
            <th>No.</th>
            <th>Unit</th>
            <th>CIF</th>
            <th>Ref Sampling</th>
            <th>Nama</th>
            <th>Nama Kel</th>
            <th>Nama AO</th>
            <th>Kategori Audit</th>
            <th>Status Sampling</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->dataSampling->branch->unit }}</td>
                <td>{{ $item->cif }}</td>
                <td>{{ $item->id_ref_sampling }}</td>
                <td>{{ $item->dataSampling->nama }}</td>
                <td>{{ $item->dataSampling->kelompok->nama_kel }}</td>
                <td>{{ $item->dataSampling->ao->nama_ao }}</td>
                <td>{{ $item->dataSampling->jenis_audit }}</td>
                <td>{{ $item->dataSampling->status_sampling }}</td>
                <td>
                    <span class="badge {{ $item->dataSampling->status == 'selesai' ? 'badge-success' : 'badge-warning' }}">
                        {{ $item->dataSampling->status }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('qam.dashboard.detailAudit', ['id' => $item->id, 'cif' => $item->cif]) }}" class="btn btn-primary btn-sm">Lihat</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
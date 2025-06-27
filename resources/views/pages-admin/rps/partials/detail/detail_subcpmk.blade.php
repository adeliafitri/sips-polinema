<div class="table-responsive" id="tabel-datasubcpmk">
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th style="width: 10px">No</th>
                <th>Kode CPL</th>
                <th>Kode CPMK</th>
                <th>Kode Sub CPMK</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $datas)
            <tr>
                <td>{{ $startNumber++ }}</td>
                <td>{{ $datas->kode_cpl }}</td>
                <td>{{ $datas->kode_cpmk }}</td>
                <td>{{ $datas->kode_subcpmk }}</td>
                <td>{{ $datas->deskripsi }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-3 pt-3 d-flex justify-content-end">
        {!! $data->links('pagination::bootstrap-4') !!}
    </div>
</div>

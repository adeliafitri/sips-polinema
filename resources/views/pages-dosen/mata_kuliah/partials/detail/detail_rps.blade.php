<div class="table-responsive" id="tabel-datatugas">
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th style="width: 10px">No</th>
                <th>Waktu Pelaksanaan</th>
                <th>Kode CPL</th>
                <th>Indikator Kinerja CPL</th>
                <th>Kode CPMK</th>
                <th>Bentuk Soal</th>
                <th>Bobot Soal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $datas)
            <tr>
                <td>{{ $startNumber++ }}</td>
                <td>{{ $datas->waktu_pelaksanaan }}</td>
                <td>{{ $datas->kode_cpl }}</td>
                <td>{{ $datas->kode_cpmk }}</td>
                <td>{{ $datas->kode_subcpmk }}</td>
                <td>{{ $datas->bentuk_soal }}</td>
                <td>{{ $datas->bobot_soal }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-3 pt-3 d-flex justify-content-end">
        {!! $data->links('pagination::bootstrap-4') !!}
    </div>
</div>

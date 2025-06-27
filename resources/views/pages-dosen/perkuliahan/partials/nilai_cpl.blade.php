<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th style="width: 10px">No</th>
            <th>Kode CPL</th>
            <th>Bobot Soal</th>
            <th>Nilai</th>


        </tr>
    </thead>
    <tbody>
        @foreach ($data as $datas)
        <tr>
            <td></td>
            <td>{{ $datas->kode_cpl }}</td>
            <td>{{ $datas->bobot_soal }}%</td>
            <td>{{ round($datas->total_nilai, 1) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
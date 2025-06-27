
<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th style="width: 10px">No</th>
            <th>Waktu Pelaksanaan</th>
            <th>Kode Sub CPMK</th>
            <th>Bobot Soal</th>
            <th>Bentuk Soal</th>
            <th>Nilai</th>


        </tr>
    </thead>
    <tbody>
        @foreach ($data as $datas)
        <tr>
            {{-- <td>{{ $startNumber++ }}</td> --}}
            <td></td>
            <td>{{ $datas->waktu_pelaksanaan }}</td>
            <td>{{ $datas->kode_subcpmk }}</td>
            <td>{{ $datas->bobot_soal }}%</td>
            <td>{{ $datas->bentuk_soal }}</td>
            <td> {{ $datas->nilai }} </td>
                    
        </tr>
        @endforeach
    </tbody>
</table>

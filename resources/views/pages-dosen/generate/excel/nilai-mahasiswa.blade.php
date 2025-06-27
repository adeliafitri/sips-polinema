<table class="table table-bordered">
    <thead>
        <tr>
            <th style="width: 10px" rowspan="2" class="align-middle">No</th>
            <th style="width: 150px;" rowspan="2" class="align-middle">NIM</th>
            <th style="width: 200px;" rowspan="2" class="align-middle">Nama</th>
            @foreach ($info_soal as $bentuk_soal => $data)
                <th colspan="{{ count($data['bobot_soal']) }}" class="text-center align-middle">{{ $bentuk_soal }}</th>
            @endforeach
        </tr>
        <tr>
            {{-- @foreach ($info_soal as $data)
                <th class="p-1">{{ $data['bobot_soal'] }}%</th>
            @endforeach --}}
            @foreach ($info_soal as $data)
                @foreach ($data['bobot_soal'] as $bobot)
                    <th class="p-1 text-center">{{ $bobot }} %</th>
                @endforeach
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($mahasiswa_data as $key => $mhs)
            <tr>
                <td>{{ $mhs['nomor'] }}</td>
                <td>{{ $mhs['nim'] }}</td>
                <td>{{ $mhs['nama'] }}</td>
                @foreach ($info_soal as $bentuk_soal => $data)
                    @foreach ($data['bobot_soal'] as $bobot)
                        @php
                            $soal_key = $bentuk_soal . '_' . $bobot;
                        @endphp
                        @if (isset($mhs['nilai'][$soal_key]))
                            <td class="text-center">{{ $mhs['nilai'][$soal_key] }}</td>
                        @else
                            <td class="text-center">-</td>
                        @endif
                    @endforeach
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

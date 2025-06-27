<!DOCTYPE html>
<html>
<head>
    <title>Portfolio Penilaian</title>
    <style>
        /* Atur border untuk tabel */
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Portfolio Penilaian</h1>
    <table class="table table-bordered">
        <tr>
            <th>Kode Mata Kuliah</th>
            <td>{{ $matkul->kode_matkul }}</td>
        </tr>
        <tr>
            <th>Nama Mata Kuliah</th>
            <td>{{ $matkul->nama_matkul }}</td>
        </tr>
        <tr>
            <th>SKS</th>
            <td>{{ $matkul->sks }}</td>
        </tr>
        <tr>
            <th>Semester</th>
            <td>{{ $matkul->semester }}</td>
        </tr>
        <tr>
            <th>Tahun RPS</th>
            <td>{{ $matkul->tahun_rps }}</td>
        </tr>
    </table>
    <br>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Minggu</th>
                <th>CPL</th>
                <th>CPMK</th>
                <th>Sub-CPMK</th>
                <th>Bentuk Soal</th>
                <th>Bobot Sub-CPMK</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rps as $data)
                <tr>
                    <td>{{ $data->waktu_pelaksanaan }}</td>
                    <td>{{ $data->kode_cpl }}</td>
                    <td>{{ $data->kode_cpmk }}</td>
                    <td>{{ $data->kode_subcpmk }}</td>
                    <td>{{ $data->bentuk_soal }}</td>
                    <td>{{ $data->bobot_soal }}%</td>
                </tr>
            @endforeach
                <tr>
                    <td colspan="5">Total Bobot</td>
                    <td>{{ $total_bobot }}%</td>
                </tr>
        </tbody>
    </table>
</body>
</html>

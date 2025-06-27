<!DOCTYPE html>
<html>
<head>
    {{-- @include('partials.header') --}}
    <title>Portfolio Perkuliahan</title>
    <style>
        /* Atur border untuk tabel */
        body{
            /* margin: 20px; */
        }
        table {
            border-collapse: collapse;
            width: 100%;
            /* table-layout: fixed; */
            /* word-wrap: break-word; */
            font-size: 12px; /* Mengurangi ukuran font */
        }
        th, td {
            /* width: auto; */
            border: 1px solid black;
            padding: 4px; /* Mengurangi padding */
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h2{
            line-height: 1;
        }
        h3 {
            line-height: 0.5;
        }
        /* Skala tabel untuk menyesuaikan halaman */
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }
        .info-soal {
            table-layout: fixed;
            font-size: 9px;
            max-width: 30px;
            /* text-decoration: capitalize; Menetapkan lebar tetap untuk kolom info soal */
        }

        .img-chart{
            /* text-align: center; */
        }

        .info-cover{
            /* text-align: center;/ */
            margin: 30px 0px;
        }

        .no-column{
            font-size: 9px;
        }

        .nama-column{
            width: 50px;
        }

        .cover{
            margin: 100px 20px;
            text-align: center;
        }

        /* .chart-container {
            display: flex;
            justify-content: space-between;
            padding: 20px;
        } */
        /* .chart-column{
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }

        .chart-item{
            width: 48%;
            padding: 10px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
        } */

        /* .chart-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    padding: 20px;
} */

.chart-column {
    width: 100%;
    display: flex;
align-items: center;
gap: 20px;
}

.chart-item-left{
    width: 45%;
    margin-bottom: 20px;
    background-color: #f0f0f0;
    padding: 10px;
    border: 1px solid #ccc;
    text-align: center;
    float: left;
    height:300px;
}

.chart-item-right{
    width: 45%;
    margin-bottom: 20px;
    background-color: #f0f0f0;
    padding: 10px;
    border: 1px solid #ccc;
    text-align: center;
    float: right;
    height:300px;
}

.chart-item h5 {
    margin-bottom: 10px;
}

.chart-item-left img {
    width: 100%;
    height: 250px;
}

.chart-item-right img {
    width: 100%;
    height: 250px;
}


.clear {
    clear: both;
}
    </style>
</head>
<body>
    <div class="cover">
        <h2>Portfolio Perkuliahan</h2>
        <div class="img-cover">
            @php
                // phpinfo();
                $imagePath = public_path('dist/img/logo_uin.png');
                $imageData = base64_encode(file_get_contents($imagePath));
                $src = 'data:image/png;base64,' . $imageData;
            @endphp

             <img src="{{ $src }}" width="200px" alt="logo UIN Malang">
        </div>
        <div class="info-cover">
            <h3>Mata Kuliah {{ $kelas->nama_matkul }}</h3>
            <h3>Semester {{ $kelas->semester }}</h3>
            <h3>Tahun Ajaran {{ $kelas->tahun_ajaran }}</h3>
        </div>
        <br>
        <h2>Program Studi Teknik Arsitektur</h2>
        <h2>UIN Maulana Malik Ibrahim Malang</h2>
    </div>
    <table class="table table-bordered">
        <tr>
            <th>Kelas</th>
            <th>Dosen</th>
            <th>Jumlah Mahasiswa (Aktif)</th>
        </tr>
        <tr>
            <td>{{ $kelas->nama_kelas }}</td>
            <td>{{ $kelas->nama_dosen }}</td>
            <td>{{ $jml_mhs->jumlah_mahasiswa }}</td>
        </tr>
    </table>
    <br>
    <h3>Capaian Pembelajaran Lulusan (CPL)</h3>
    <table id="" class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 70px;">CPL</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cpl as $dataCpl)
                <tr>
                    <td style="width:70px;">{{ $dataCpl->kode_cpl }}</td>
                    <td>{{ $dataCpl->deskripsi }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <h3>Capaian Pembelajaran Mata Kuliah (CPMK)</h3>
    <table id="" class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 70px;">CPMK</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cpmk as $dataCpmk)
                <tr>
                    <td style="width:70px;">{{ $dataCpmk->kode_cpmk }}</td>
                    <td>{{ $dataCpmk->deskripsi }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <h3>Sub Capaian Pembelajaran Mata Kuliah (Sub-CPMK)</h3>
    <table id="" class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 70px;">Sub CPMK</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subcpmk as $dataSubCpmk)
                <tr>
                    <td style="width:70px;">{{ $dataSubCpmk->kode_subcpmk }}</td>
                    <td>{{ $dataSubCpmk->deskripsi }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <h3>Portfolio Penilaian</h3>
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
            @foreach($tugas as $dataTugas)
                <tr>
                    <td>{{ $dataTugas->waktu_pelaksanaan }}</td>
                    <td>{{ $dataTugas->kode_cpl }}</td>
                    <td>{{ $dataTugas->kode_cpmk }}</td>
                    <td>{{ $dataTugas->kode_subcpmk }}</td>
                    <td>{{ $dataTugas->bentuk_soal }}</td>
                    <td>{{ $dataTugas->bobot_soal }}%</td>
                </tr>
            @endforeach
                <tr>
                    <td colspan="5">Total Bobot</td>
                    <td>{{ $total_bobot }}%</td>
                </tr>
        </tbody>
    </table>
    <br>
    <h3>Hasil Penilaian Kelas</h3>
    {{-- <div class="chart-container"> --}}
        <div class="chart-column">
            <div class="chart-item-left">
                <h5>Nilai Tugas di Kelas</h5>
                <img src="{{ $chartUrls[0] }}" alt="ChartTugas">
            </div>
            <div class="chart-item-right">
                <h5>Penguasaan Sub CPMK di Kelas</h5>
            <img src="{{ $chartUrls[1] }}" alt="ChartSubcpmk">
            </div>
        </div>
        <div class="clear"></div>
        <div class="chart-column">
            <div class="chart-item-left">
                <h5>Penguasaan CPMK di Kelas</h5>
            <img src="{{ $chartUrls[2] }}" alt="ChartCpmk">
            </div>
            <div class="chart-item-right">
                <h5>Penguasaan CPL di Kelas</h5>
            <img src="{{ $chartUrls[3] }}" alt="ChartTCpl">
            </div>
        </div>
        <div class="clear"></div>
    {{-- </div> --}}
    <br>
    <h3>Nilai Mahasiswa</h3>
    <table id="" class="table table-soal">
        <thead>
          <tr>
            <th class="no-column" rowspan="4">No</th>
            <th rowspan="4" class="no-column">NIM</th>
            <th rowspan="4" class="no-column nama-column">Nama</th>
            @foreach ($info_soal as $data)
            {{-- @foreach ($data['waktu_pelaksanaan'] as $waktu) --}}
                <th class="info-soal">{{$data['waktu_pelaksanaan']}}</th>
            {{-- @endforeach --}}
            @endforeach
            <th rowspan="4" class="no-column">Nilai Akhir</th>
            <th rowspan="4" class="no-column">Huruf</th>
            <th rowspan="4" class="no-column">Keterangan</th>
          </tr>
          <tr>
              @foreach ($info_soal as $data)
                  {{-- @foreach ($data['kode_subcpmk'] as $kode) --}}
                      <th class="info-soal">{{$data['kode_subcpmk']}}</th>
                  {{-- @endforeach --}}
              @endforeach
          </tr>
          <tr>
              @foreach ($info_soal as $data)
                  {{-- @foreach ($data['bobot_soal'] as $bobot) --}}
                      <th class="info-soal">{{$data['bobot_soal']}} %</th>
                  {{-- @endforeach --}}
              @endforeach
          </tr>
          <tr>
              @foreach ($info_soal as $data)
                  {{-- @foreach ($data['bentuk_soal'] as $bentuk) --}}
                      <th class="info-soal">{{$data['bentuk_soal'] }}</th>
                  {{-- @endforeach --}}
              @endforeach
          </tr>
        </thead>
        <tbody>
          @foreach ($mahasiswa_data as $key => $mhs)
          {{-- @foreach ($data['mahasiswa'] as $mahasiswa) --}}
            <tr>
                <td class="no-column">{{ $mhs['nomor'] }}</td>
                <td class="no-column">{{ $mhs['nim'] }}</td>
                <td class="no-column nama-column">{{ $mhs['nama'] }}</td>
                  @foreach ($mhs['id_nilai'] as $id_nilai)
                  <td class="info-soal">
                      <div id="nilai-tugas-{{ $id_nilai }}">
                          @php
                              $nilai =  $mhs['nilai'][$loop->index];
                          @endphp
                          {{ $nilai }}
                          {{-- <i class="nav-icon fas fa-edit" onclick="editNilaiTugas({{ $id_nilai }})" style="cursor: pointer"></i> --}}
                      </div>
                  </td>
                  @endforeach
                <td class="no-column">{{ $mhs['nilai_akhir'] ?? '-' }}</td>
                <td class="no-column">{{ $mhs['nilai_huruf'] ?? '-' }}</td>
                <td class="no-column">{{ $mhs['keterangan'] ?? '-' }}</td>
            </tr>
          {{-- @endforeach --}}
        @endforeach
        </tbody>
    </table>
    <br>
    <h3>Evaluasi</h3>
    <p>{{ $kelas->evaluasi }}</p>
    <br>
    <h3>Rencana Perbaikan</h3>
    <p>{{ $kelas->rencana_perbaikan }}</p>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>

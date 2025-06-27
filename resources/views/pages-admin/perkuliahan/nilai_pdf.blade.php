<!DOCTYPE html>
<html>
<head>
    <title>Nilai Mahasiswa</title>
    <style>
        /* Atur border untuk tabel */
        table {
            border-collapse: collapse;
            width: 100%;
            /* table-layout: fixed;
            word-wrap: break-word; */
            font-size: 10px; /* Mengurangi ukuran font */
        }
        th, td {
            width: auto;
            border: 1px solid black;
            padding: 4px; /* Mengurangi padding */
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h2 {
            text-align: center;
        }

        /* Skala tabel untuk menyesuaikan halaman */
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }
        .info-soal {
            table-layout: fixed;
            font-size: 10px;
            width: 30px; /* Menetapkan lebar tetap untuk kolom info soal */
        }

        .no-column {
            font-size: 10px; /* Menetapkan lebar tetap untuk kolom "No" */
        }
    </style>
</head>
<body>
    <h2>Nilai Mahasiswa</h2>
    <table class="table table-bordered">
        <tr>
            <th>Mata Kuliah</th>
            <td>{{ $kelas->nama_matkul }}</td>
        </tr>
        <tr>
            <th>Kelas</th>
            <td>{{ $kelas->nama_kelas }}</td>
        </tr>
        <tr>
            <th>Dosen</th>
            <td>{{ $kelas->nama_dosen }}</td>
        </tr>
        <tr>
            <th>Jumlah Mahasiswa (Aktif)</th>
            <td>{{ $jml_mhs->jumlah_mahasiswa }}</td>
        </tr>
    </table>
    <br>
    <table id="" class="table table-bordered">
        <thead>
          <tr>
            <th class="no-column" rowspan="4">No</th>
            <th rowspan="4" class="no-column">NIM</th>
            <th rowspan="4" class="no-column">Nama</th>
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
                <td class="no-column">{{ $mhs['nama'] }}</td>
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
</body>
</html>

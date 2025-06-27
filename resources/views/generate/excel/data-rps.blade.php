<table class="table table-bordered">
    <thead>
      <tr>
        <th style="width: 10px" class="align-middle">No</th>
        <th style="width: 200px;" class="align-middle">Waktu Pelaksanaan</th>
        <th style="width: 150px;" class="align-middle">Kode CPL</th>
        <th style="width: 200px;" class="align-middle">Indikator Kinerja CPL</th>
        <th style="width: 200px;" class="align-middle">Deskripsi Indikator Kinerja CPL</th>
        <th style="width: 200px;" class="align-middle">Kode CPMK</th>
        <th style="width: 200px;" class="align-middle">Deskripsi CPMK</th>
        <th style="width: 200px;" class="align-middle">Bentuk Soal</th>
        <th style="width: 200px;" class="align-middle">Bobot</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data as $key => $rps)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $rps['waktu_pelaksanaan'] }}</td>
            <td>{{ $rps['kode_cpl'] }}</td>
            <td>{{ $rps['kode_cpmk'] }}</td>
            <td>{{ $rps['deskripsi'] }}</td>
            <td>{{ $rps['kode_subcpmk'] }}</td>
            <td>{{ $rps['deskripsi'] }}</td>
            <td>{{ $rps['bentuk_soal'] }}</td>
            <td>{{ $rps['bobot_soal'] }}</td>
        </tr>
    @endforeach
    </tbody>
  </table>

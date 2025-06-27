<table class="table table-bordered">
    <thead>
      <tr>
        <th style="width: 10px" class="align-middle">No</th>
        <th style="width: 150px;" class="align-middle">NIM</th>
        <th style="width: 200px;" class="align-middle">Nama</th>
        <th style="width: 200px;" class="align-middle">Nilai Akhir</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data as $key => $mhs)
      {{-- @foreach ($data['mahasiswa'] as $mahasiswa) --}}
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $mhs['nim'] }}</td>
            <td>{{ $mhs['nama'] }}</td>
        </tr>
      {{-- @endforeach --}}
    @endforeach
    </tbody>
  </table>

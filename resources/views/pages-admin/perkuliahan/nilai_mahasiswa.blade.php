@extends('layouts.admin.main')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Nilai Mahasiswa</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.kelaskuliah.show', $data->id_kelas) }}">{{ $data->nama_kelas }} - {{ $data->nama_matkul }}</a></li>
              <li class="breadcrumb-item active">Lihat Nilai</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header d-flex col-sm-12 justify-content-between">
                <div class="col-10">
                  <form action="" method="GET">
                    <div class="input-group col-sm-5 mr-3">
                      <input type="text" name="search" id="search" class="form-control" placeholder="Search">
                      <div class="input-group-append">
                          <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search fa-sm"></i>
                          </button>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="">
                    <a href="{{ route('admin.kelaskuliah.generatepdf', $data->id_kelas) }}" class="btn btn-primary w-100"><i class="nav-icon fas fa-download mr-2"></i> Download Nilai</a>
                </div>
              </div>
              <div class="card-body">
              <div class="col-sm-12 mt-3">
                @if (session('success'))
                    <div class="alert alert-success bg-success" role="alert">
                        {{ session('success') }}
                    </div>
                @elseif (session('error'))
                    <div class="alert alert-danger bg-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
              </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th style="width: 10px" rowspan="4" class="align-middle">No</th>
                            <th style="width: 150px;" rowspan="4" class="align-middle">NIM</th>
                            <th style="width: 200px;" rowspan="4" class="align-middle">Nama</th>
                            @foreach ($info_soal as $data)
                            {{-- @foreach ($data['waktu_pelaksanaan'] as $waktu) --}}
                                <th style="width: 180px;" class="p-1">{{$data['waktu_pelaksanaan']}}</th>
                            {{-- @endforeach --}}
                            @endforeach
                          </tr>
                          <tr>
                              @foreach ($info_soal as $data)
                                  {{-- @foreach ($data['kode_subcpmk'] as $kode) --}}
                                      <th class="p-1">{{$data['kode_subcpmk']}}</th>
                                  {{-- @endforeach --}}
                              @endforeach
                          </tr>
                          <tr>
                              @foreach ($info_soal as $data)
                                  {{-- @foreach ($data['bobot_soal'] as $bobot) --}}
                                      <th class="p-1">{{$data['bobot_soal']}} %</th>
                                  {{-- @endforeach --}}
                              @endforeach
                          </tr>
                          <tr>
                              @foreach ($info_soal as $data)
                                  {{-- @foreach ($data['bentuk_soal'] as $bentuk) --}}
                                      <th class="p-1">{{$data['bentuk_soal'] }}</th>
                                  {{-- @endforeach --}}
                              @endforeach
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($mahasiswa_data as $key => $mhs)
                          {{-- @foreach ($data['mahasiswa'] as $mahasiswa) --}}
                            <tr>
                                <td>{{ $mhs['nomor'] }}</td>
                                <td>{{ $mhs['nim'] }}</td>
                                <td>{{ $mhs['nama'] }}</td>
                                  @foreach ($mhs['id_nilai'] as $id_nilai)
                                  <td>
                                      <div id="nilai-tugas-{{ $id_nilai }}">
                                          @php
                                              $nilai =  $mhs['nilai'][$loop->index];
                                          @endphp
                                          {{ $nilai }}
                                          {{-- <i class="nav-icon fas fa-edit" onclick="editNilaiTugas({{ $id_nilai }})" style="cursor: pointer"></i> --}}
                                      </div>
                                      <form action="{{ route('dosen.kelaskuliah.editnilaitugas') }}" method="POST" class="d-flex justify-content-end fit-content ">
                                          @csrf
                                          <input type="hidden" name="id_nilai" value="{{ $id_nilai }}">
                                              <input type="hidden" class="form-control" type="number" name="matakuliah_kelasid" value="{{ $mhs['kelas_id'] }}">
                                              <input type="hidden" class="form-control" type="number" name="mahasiswa_id" value="{{ $mhs['id_mhs'] }}">
                                              <input type="number" step="0.01" id="edit-nilai-tugas-form-{{ $id_nilai }}" class="form-control" name="nilai" value="{{ $nilai }}" style="width: 75px; display: none;">
                                              <button style="display: none;" type="submit" id="edit-nilai-tugas-button-{{ $id_nilai }}" class="ml-2 btn btn-sm btn-primary"><i class="fas fa-check"></i></button>
                                      </form>
                                  </td>
                                  @endforeach
                            </tr>
                          {{-- @endforeach --}}
                        @endforeach
                        </tbody>
                    </table>
                </div>
              </div>
              <!-- /.card-body -->

              <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    <div class="float-right">
                        {{-- {{ $data->onEachSide(1)->links('pagination::bootstrap-4') }} --}}
                    </div>
                </ul>
              </div>
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
<script>
    function editNilaiTugas(id){
        document.getElementById('nilai-tugas-' + id).style.display = 'none';
        document.getElementById('edit-nilai-tugas-button-'+ id).style.display = 'block';
        document.getElementById('edit-nilai-tugas-form-'+ id).style.display = 'block';
    }
</script>

@extends('layouts.mahasiswa.main')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Data Kelas Perkuliahan</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <!-- <li class="breadcrumb-item"><a href="index.php?include=dashboard">Home</a></li> -->
              <li class="breadcrumb-item active">Data Kelas Perkuliahan</li>
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
                <div class="col-lg-10">
                  <form action="{{ route('mahasiswa.kelaskuliah') }}" method="GET">
                    <div class="input-group col-sm-4 mr-3">
                      <input type="text" name="search" id="search" class="form-control" placeholder="Search">
                      <div class="input-group-append">
                          <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search fa-sm"></i>
                          </button>
                      </div>
                    </div>
                  </form>
                </div>
                {{-- <div class=""> --}}
                    {{-- <form action="mahasiswa.nilai" method="GET"> --}}
                        <div class="col-lg-2">
                            <form action="{{ route('mahasiswa.kelaskuliah') }}" method="GET">
                                <div class="btn-group">
                                    <button type="submit" class="btn btn-secondary text-capitalize dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       {{ $title }}
                                    </button>
                                    <div class="dropdown-menu">
                                        @foreach ($semester as $key => $smt)
                                            <button class="dropdown-item" type="button" onclick="document.getElementById('tahun_ajaran').value = '{{ $smt->id }}'; this.form.submit();"> <p style="text-transform: uppercase;">{{ $smt->tahun_ajaran . ' ' . $smt->semester }}</p></button>
                                        @endforeach
                                    </div>
                                </div>
                                <input type="hidden" id="tahun_ajaran" name="tahun_ajaran">
                            </form>
                        </div>
                        {{-- <select class="form-control btn btn-secondary" id="tahun_ajaran" name="tahun_ajaran">
                            @foreach ($semester as $key => $smt)
                                <option value="{{ $smt->id }}" {{ $smt->is_active == '1' ? 'selected' : '' }}>{{ $smt->tahun_ajaran ." ". $smt->semester }}</option>
                            @endforeach
                        </select> --}}
                    {{-- </form> --}}
                {{-- </div> --}}
                <div></div>
                <!-- <h3 class="card-title col align-self-center">List Products</h3> -->
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
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">No</th>
                      <th>Mata Kuliah</th>
                      {{-- <th>Status</th> --}}
                      <th>Kelas</th>
                      <th>Dosen</th>
                      <th style="width: 200px;">Nilai Akhir</th>
                      <th style="width: 150px;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach ($data as $key => $datas)
                    <tr>
                        <td>{{ $startNumber++ }}</td>
                        <td>{{ $datas->nama_matkul }}</td>
                        {{-- <td>{{ $datas->status }}</td> --}}
                        <td>{{ $datas->kelas }}</td>
                        <td>{{ $datas->nama_dosen }}</td>
                        {{-- <td>{{ $datas->nama_dosen }}</td> --}}
                        {{-- <td>
                            <div class="row justify-content-center">
                                <form>
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="isActive-{{ $datas->id}}" name="koordinator" {{ $datas->koordinator == 1 ? 'checked' : '' }} onclick="changeKoordinator({{ $datas->id }})">
                                            <label class="custom-control-label" for="isActive-{{ $datas->id}}"></label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </td> --}}
                        <td>{{ $datas->nilai_akhir }}</td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('mahasiswa.kelaskuliah.nilaimahasiswa', $datas->id_kelas) }}" class="btn btn-info mr-2"><i class="nav-icon far fa-eye"></i></a>
                            </div>
                        </td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->

              <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    <div class="float-right">
                        {{ $data->onEachSide(1)->links('pagination::bootstrap-4') }}
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



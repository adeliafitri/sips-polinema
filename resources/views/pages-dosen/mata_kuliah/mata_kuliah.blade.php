@extends('layouts.dosen.main')

{{-- @section('form')
    @include('pages-dosen.mata_kuliah.partials.detail.detail_cpl')
@endsection --}}

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1 class="m-0">Data RPS</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <!-- <li class="breadcrumb-item"><a href="index.php?include=dashboard">Home</a></li> -->
                <li class="breadcrumb-item active">Data RPS</li>
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
                  <form action="{{ route('dosen.matakuliah') }}" method="GET">
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
                <!-- <h3 class="card-title col align-self-center">List Products</h3> -->
                {{-- <div class="col-sm-2">
                    <a href="{{ route('dosen.matakuliah.create.matkul') }}" class="btn btn-primary"><i class="nav-icon fas fa-plus mr-2"></i> Tambah Data</a>
                </div> --}}
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
                            <th style="width: 10px">No</th>
                            <th>Kode Mata Kuliah</th>
                            <th>Nama Mata Kuliah</th>
                            <th>SKS</th>
                            <th>Semester</th>
                            <th>Tahun RPS</th>
                            <th style="width: 150px;">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($data as $key => $datas)
                          <tr>
                              <td>{{ $startNumber++ }}</td>
                              <td>{{ $datas->kode_matkul }}</td>
                              <td>{{ $datas->nama_matkul }}</td>
                              <td>{{ $datas->sks }}</td>
                              <td>{{ $datas->semester }}</td>
                              <td>{{ $datas->tahun_rps }}</td>
                              <td class="d-flex justify-content-center">
                                  @if ($datas->koordinator == $dosen->id && $datas->status == 'aktif')
                                  <a href="{{ route('dosen.rps.create', $datas->id_rps) }}" class="btn btn-primary mr-1" data-toggle="tooltip" data-placement="top" title="Tambah data RPS"><i class="nav-icon fas fa-plus"></i></a>
                                  @endif
                                   <a href="{{ route('dosen.matakuliah.show', $datas->id_rps) }}" class="btn btn-info mr-1"><i class="nav-icon far fa-eye" ></i></a>
                              </td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                </div>
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
@section('JSMataKuliah')

  <script>
    function tesload(){
              console.log('ted');
          }


  </script>
@endsection

{{-- @yield('JSDetailMataKuliah'); --}}


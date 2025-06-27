@extends('layouts.admin.main')

@section('content')
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Data Nilai Mahasiswa</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <!-- <li class="breadcrumb-item"><a href="index.php?include=data-mahasiswa">Data Mahasiswa</a></li> -->
            <li class="breadcrumb-item active">Data Nilai Mahasiswa</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  @foreach($matakuliahs as $matakuliah)
    <section class="content">
        <!-- Default box -->
        <div class="card collapsed-card">
            <div class="card-header">
                <h3 class="card-title">{{ $matakuliah->nama_matkul }}</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="card card-primary card-outline card-tabs">
                    {{-- @include('pages-admin.penilaian.nilai_mahasiswa_show', ['id_matkul'=> $matakuliah->id]) --}}
                    <a class="btn btn-primary" href="{{ route('admin.nilai.show', $matakuliah->id) }}">Kelas A</a>
                </div>
            </div>
        </div>

        <!-- /.card -->

    </section>
  @endforeach
  <!-- /.content -->
@endsection

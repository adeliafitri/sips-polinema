@extends('layouts.admin.main')

@section('content')
<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Nilai {{ $nilai_subcpmk->kode_subcpmk }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.kelaskuliah.nilaimahasiswa',['id'=> $nilai_subcpmk->matakuliah_kelasid, 'id_mahasiswa' => $nilai_subcpmk->mahasiswa_id]) }}">Nilai Mahasiswa</a></li>
              <li class="breadcrumb-item active">Edit Nilai</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
                <div class="col-12 justify-content-center">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                <div class="card-header d-flex justify-content-end">
                    <h3 class="card-title col align-self-center">Form Edit Nilai</h3>
                    <!-- <div class="col-sm-2">
                        <a href="index.php?include=data-mahasiswa" class="btn btn-warning"><i class="nav-icon fas fa-arrow-left mr-2"></i> Kembali</a>
                    </div> -->
                </div>
                <form action="{{ route('admin.kelaskuliah.nilaimahasiswa.update',['id'=> $nilai_subcpmk->matakuliah_kelasid, 'id_mahasiswa' => $nilai_subcpmk->mahasiswa_id, 'id_subcpmk' => $nilai_subcpmk->id]) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nilai">Nilai</label>
                            <input type="number" class="form-control" id="nilai" name="nilai" placeholder="Nilai" step="0.01" value="{{ $nilai_subcpmk->nilai }}">
                        </div>
                    </div>
                    <div class="card-footer clearfix">
                        <a href="{{ route('admin.kelaskuliah.nilaimahasiswa',['id'=> $nilai_subcpmk->matakuliah_kelasid, 'id_mahasiswa' => $nilai_subcpmk->mahasiswa_id]) }}" class="btn btn-default">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
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

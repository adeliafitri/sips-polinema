@extends('layouts.admin.main')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Data Mahasiswa</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.mahasiswa') }}">Data Mahasiswa</a></li>
              <li class="breadcrumb-item active">Data Mahasiswa</li>
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
                <div class="card bg-light d-flex flex-fill">
                  <div class="card-header text-muted border-bottom-0">
                    Mahasiswa
                  </div>
                  <div class="card-body pt-0">
                    <div class="row">
                      <div class="col-7">
                        <h2 class="lead"><b>{{ $data->nama }}</b></h2>
                        @php
                                $formattedDate = $data->tanggal_lahir ? date('d F Y', strtotime($data->tanggal_lahir)) : '';
                            @endphp
                        {{-- <p class="text-muted text-sm"><b>About: </b> Web Designer / UX / Graphic Artist / Coffee Lover </p> --}}
                        <ul class="ml-4 mb-0 fa-ul text-muted">
                            <li ><span class="fa-li"><i class="fas fa-lg fa-user"></i></span> Jenis Kelamin     : {{ $data->jenis_kelamin }}</li>
                            <li ><span class="fa-li"><i class="fas fa-lg fa-calendar"></i></span> Tanggal Lahir : {{ $formattedDate }}</li>
                            <li ><span class="fa-li"><i class="fas fa-lg fa-envelope"></i></span> Email     : {{ $data->email }}</li>
                            <li ><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> Phone : {{ $data->telp }}</li>
                            <li ><span class="fa-li"><i class="fas fa-lg fa-home"></i></span> Alamat : {{ $data->alamat }}</li>
                        </ul>
                      </div>
                      <div class="col-5 text-right">
                        @php
                            $images = $data->image;
                        @endphp
                        {{-- @if ($images) --}}
                        <img src="{{ asset('storage/image/' . $images) }}" alt="user-avatar" class="img-circle img-fluid" width="150">
                        {{-- @endif --}}
                      </div>
                    </div>
                  </div>
                  <div class="card-footer">
                    <div class="text-right">
                      <a href="{{ route('admin.mahasiswa.edit', ['id' => $data->id]) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit"></i> Edit Profile
                      </a>
                    </div>
                  </div>
                </div>
              </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@extends('layouts.admin.main')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Data Capaian Pembelajaran Lulusan</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <!-- <li class="breadcrumb-item"><a href="index.php?include=dashboard">Home</a></li> -->
              <li class="breadcrumb-item active">Data CPL</li>
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
                <div class="col-sm-10">
                  <form action="{{ route('admin.cpl') }}" method="GET">
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
                <!-- <h3 class="card-title col align-self-center">List Products</h3> -->
                {{-- <div class="dropdown col-sm-2">
                    <button class="btn btn-success w-100 dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-file-excel mr-2"></i> Excel
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="{{ route('admin.cpl.download-excel') }}"><i class="fas fa-upload mr-2"></i> Export</a>
                      <a class="dropdown-item" href="#"><i class="fas fa-download mr-2"></i> Import</a>
                    </div>
                </div> --}}
                <div class="col-sm-2">
                    <a href="{{ route('admin.cpl.create') }}" class="btn btn-primary w-100"><i class="nav-icon fas fa-plus mr-2"></i> Tambah Data</a>
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
                            <th style="width: 10px">No</th>
                            <th>Kode CPL</th>
                            <th>Jenis CPL</th>
                            <th>Deskripsi</th>
                            <th style="width: 150px;">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($data as $key => $datas)
                          <tr>
                              <td>{{ $startNumber++ }}</td>
                              <td>{{ $datas->kode_cpl }}</td>
                              <td>{{ $datas->jenis_cpl }}</td>
                              <td>{{ $datas->deskripsi }}</td>
                              <td>
                                  <!-- <a href="index.php?include=detail-cpl" class="btn btn-info"><i class="nav-icon far fa-eye mr-2"></i>Detail</a> -->
                                  <div class="d-flex">
                                    <a href="{{ route('admin.cpl.edit', $datas->id) }}" class="btn btn-secondary mr-2"><i class="nav-icon fas fa-edit"></i></a>
                                    <a class="btn btn-danger" onclick="deleteCpl({{$datas->id}})"><i class="nav-icon fas fa-trash-alt"></i></a>
                                    {{-- <form action="{{ route('admin.cpl.destroy', $datas->id) }}" method="post" class="">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-danger" type="submit"><i class="nav-icon fas fa-trash-alt"></i></button>
                                    </form> --}}
                                  </div>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
       //content goes here
    });

          function deleteCpl(id){
            console.log(id);
            Swal.fire({
            title: "Konfirmasi Hapus",
            text: "Apakah anda yakin ingin menghapus data ini?",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: "Batal",
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus"
          }).then((result) => {
            if (result.isConfirmed) {
                    $.ajax({
                    url: "{{ url('admin/cpl') }}/" + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            console.log(response.message);

                            Swal.fire({
                            title: "Sukses!",
                            text: "data berhasil dihapus",
                            icon: "success"
                            }).then((result) => {
                                // Check if the user clicked "OK"
                                if (result.isConfirmed) {
                                    // Redirect to the desired URL
                                    window.location.reload();
                                };
                                    // window.location.href = "{{ route('admin.kelas') }}";
                            });
                        } else {
                            console.log(response.message);
                        }
                    },
                    error: function(error) {
                        console.error('Error during AJAX request:', error);
                    }
                });
            }
          });

          }
</script>

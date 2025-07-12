@extends('layouts.admin.main')

{{-- @section('form')
    @include('pages-admin.mata_kuliah.partials.detail.detail_cpl')
@endsection --}}

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1 class="m-0">Data Mata Kuliah</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <!-- <li class="breadcrumb-item"><a href="index.php?include=dashboard">Home</a></li> -->
                <li class="breadcrumb-item active">Data Mata Kuliah</li>
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
                  <form action="{{ route('admin.matakuliah') }}" method="GET">
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
                <div class="col-2">
                    <a href="{{ route('admin.matakuliah.create.matkul') }}" class="btn btn-primary w-100"><i class="nav-icon fas fa-plus mr-2"></i> Tambah Data</a>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th style="width: 10px">No</th>
                            <th>Kode Mata Kuliah</th>
                            <th>Nama Mata Kuliah</th>
                            <th>SKS</th>
                            <th>Jenis Mata Kuliah</th>
                            <th style="width: 150px;">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($data as $key => $datas)
                          <tr>
                              <td>{{ $startNumber++ }}</td>
                              <td>{{ $datas->kode_matkul }}</td>
                              <td>{{ $datas->nama_matkul }}
                                <div class="mt-2">
                                    <a href="{{ route('admin.rps.create', [
                                            'nama_matkul' => $datas['nama_matkul'],
                                            'id_matkul' => $datas->id
                                    ]) }}" class="btn-sm btn-primary">
                                        <i class="nav-icon fas fa-plus mr-2"></i> Tambah RPS
                                    </a>
                                </div>
                              </td>
                              <td>{{ $datas->sks }}</td>
                                <td>
                                    @if ($datas->is_pilihan)
                                        Pilihan
                                    @else
                                        Wajib
                                    @endif
                                </td>
                              <td class="d-flex justify-content-center">
                                  {{-- <a href="{{ route('admin.rps.create', $datas->id) }}" class="btn btn-primary mr-1" data-toggle="tooltip" data-placement="top" title="Tambah data RPS"><i class="nav-icon fas fa-plus"></i></a> --}}
                                   {{-- <a href="{{ route('admin.matakuliah.show', $datas->id) }}" class="btn btn-info mr-1"><i class="nav-icon far fa-eye" ></i></a> --}}
                                  <a href="{{ route('admin.matakuliah.edit', $datas->id) }}" class="btn btn-secondary ml-1 mr-1"><i class="nav-icon fas fa-edit"></i></a>
                                  <a class="btn btn-danger" onclick="deleteMatkul({{$datas->id}})"><i class="nav-icon fas fa-trash-alt"></i></a>
                                  {{-- <form action="{{ route('admin.matakuliah.destroy', $datas->id) }}" method="post">
                                      @csrf
                                      @method('delete')
                                      <button class="btn btn-danger ml-1" type="submit"><i class="nav-icon fas fa-trash-alt"></i></button>
                                  </form> --}}
                              </td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                </div>
                <h6 onclick="tesload()">
                </h6>
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
@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
       //content goes here
    });

          function deleteMatkul(id){
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
                    url: "{{ url('admin/mata-kuliah') }}/" + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            console.log(response.message);

                            Swal.fire({
                            title: "Sukses!",
                            text: response.message,
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
@endsection
{{-- @yield('JSDetailMataKuliah'); --}}


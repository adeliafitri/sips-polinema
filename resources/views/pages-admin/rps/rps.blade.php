@extends('layouts.admin.main')

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
                  <form action="{{ route('admin.rps') }}" method="GET">
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
                {{-- <div class="col-sm-2">
                    <a href="{{ route('admin.kelaskuliah.create') }}" class="btn btn-primary w-100"><i class="nav-icon fas fa-plus mr-2"></i> Tambah Data</a>
                </div> --}}
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
                            <th style="width: 100px;">Semester</th>
                            <th style="width: 100px;">Tahun RPS</th>
                            <th>Koordinator</th>
                            <th style="width: 150px;">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $key => $datas)
                        {{-- @dd($data) --}}
                        @php
                            $rowCount = count($datas['info_rps']);
                            $rowIndex = 0;
                        @endphp
                        @foreach ($datas['info_rps'] as $info)
                          <tr>
                            @if ($rowIndex == 0)
                                <td rowspan="{{ $rowCount }}">{{ $startNumber++ }}</td>
                                <td rowspan="{{ $rowCount }}">{{ $datas['kode_matkul'] }}</td>
                                <td rowspan="{{ $rowCount }}">{{ $datas['nama_matkul'] }}
                                    <div class="mt-2">
                                        <a href="{{ route('admin.rps.create', [
                                                'nama_matkul' => $datas['nama_matkul'],
                                                'id_matkul' => $datas['id_matkul']
                                        ]) }}" class="btn-sm btn-primary">
                                            <i class="nav-icon fas fa-plus mr-2"></i> Tambah RPS
                                        </a>
                                    </div>
                                </td>
                                <td rowspan="{{ $rowCount }}">{{ $datas['sks'] }}</td>
                            @endif
                            <td>{{ $info['semester'] }}</td>
                            <td>{{ $info['tahun_rps'] }}</td>
                            <td>{{ $info['koordinator'] ?? 'Tidak ada koordinator' }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('admin.rpsDetail.create', $info['id_rps']) }}" class="btn btn-primary mr-1" data-toggle="tooltip" data-placement="top" title="Tambah data RPS"><i class="nav-icon fas fa-plus"></i></a>
                                    <a href="{{ route('admin.rps.show', $info['id_rps']) }}" class="btn btn-info mr-2"><i class="nav-icon far fa-eye"></i></a>
                                    <a href="{{ route('admin.rps.edit', $info['id_rps']) }}" class="btn btn-secondary mr-2"><i class="nav-icon fas fa-edit"></i></a>
                                    <a class="btn btn-danger" onclick="deleteRps({{ $info['id_rps'] }})"><i class="nav-icon fas fa-trash-alt"></i></a>
                                </div>
                            </td>
                          </tr>
                          @php
                            $rowIndex++;
                          @endphp
                            @endforeach
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

          function deleteRps(id){
            Swal.fire({
            title: "Konfirmasi Hapus",
            text: "Apakah anda yakin ingin menghapus data ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus"
            }).then((result) => {
              if (result.isConfirmed) {
                      $.ajax({
                      url: "{{ url('admin/rps') }}/" + id,
                      type: 'DELETE',
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      },
                      success: function(response) {
                          if (response.status === 'success') {
                              console.log(response.message);

                              Swal.fire({
                              title: "Sukses!",
                              text: "Data berhasil dihapus",
                              icon: "success"
                              }).then((result) => {
                                  // Check if the user clicked "OK"
                                  if (result.isConfirmed) {
                                      // Redirect to the desired URL
                                      window.location.reload();
                                  };
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

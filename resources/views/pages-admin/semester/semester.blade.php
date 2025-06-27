@extends('layouts.admin.main')

@section('content')
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Data Semester</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <!-- <li class="breadcrumb-item"><a href="index.php?include=dashboard">Home</a></li> -->
              <li class="breadcrumb-item active">Data Semester</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header d-flex col-md-12 justify-content-between">
                <div class="col-md-10">
                  <form action="{{ route('admin.dosen') }}" method="GET">
                    <div class="input-group col-md-5">
                      <input type="text" name="search" id="search" class="form-control" placeholder="Search">
                      <div class="input-group-append">
                          <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search fa-sm"></i>
                          </button>
                      </div>
                    </div>
                  </form>
                </div>
                {{-- <div class=" col-md-4 d-flex align-items-end justify-content-end"> --}}
                  <div class="col-md-2">
                      <a href="{{ route('admin.semester.create') }}" class="btn btn-primary w-100"><i class="nav-icon fas fa-plus mr-2"></i> Tambah Data</a>
                  </div>
                {{-- </div> --}}
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
                            <th> Tahun Ajaran </th>
                            <th> Semester </th>
                            <th style="width: 100px;"> Aktif </th>
                            <th style="width: 150px;"> Action </th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($data as $i => $datas )
                            <tr>
                              <td class=""> {{ $startNumber++ }} </td>
                              <td> {{ $datas->tahun_ajaran }} </td>
                              <td>{{ ucfirst($datas->semester) }}</td>
                              <td>
                                <div class="row justify-content-center">
                                  <div class="form-group">
                                      <div class="custom-control custom-switch">
                                          <input type="checkbox" class="custom-control-input" id="isActive-{{ $datas->id}}" name="is-active" {{ $datas->is_active == '1' ? 'checked' : '' }} onclick="changeIsActive({{ $datas->id }})">
                                          <label class="custom-control-label" for="isActive-{{ $datas->id}}"></label>
                                      </div>
                                  </div>
                                </div>
                              </td>
                              <td class="d-flex">
                                <div>

                                  <a href="{{ route('admin.semester.edit', $datas->id) }}" class="btn btn-secondary mt-1 mr-2"><i class="nav-icon fas fa-edit"></i></a>
                                </div>
                                <a class="btn btn-danger" onclick="deleteSemester({{$datas->id}})"><i class="nav-icon fas fa-trash-alt"></i></a>
                                {{-- <form action="{{ route('admin.semester.destroy', $datas->id) }}" method="post" class="mt-1">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-danger" type="submit"><i class="nav-icon fas fa-trash-alt"></i></button>
                                </form> --}}
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                       </table>
                 </div>
              </div>
              <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    <div class="float-right">
                        {{ $data->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </div>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
   //content goes here
});

      function changeIsActive(id, value){
        console.log(id, value);
        Swal.fire({
        title: "Konfirmasi Semester Aktif",
        text: "Apakah anda yakin ingin mengaktifkan semester ini?",
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: "Batal",
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, aktifkan"
      }).then((result) => {
        if (result.isConfirmed) {

          var isActiveCheckbox = document.getElementById('isActive-' + id);
          var isActiveValue = isActiveCheckbox.checked ? 1 : 0;

                $.ajax({
                url: "{{ url('admin/semester/update-active') }}/" + id,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    is_active: isActiveValue
                },
                success: function(data) {
                  Swal.fire({
                      title: "Sukses!",
                      text: "Semester berhasil diaktifkan",
                      icon: "success"
                  }).then((result) => {
                      // Check if the user clicked "OK"
                      if (result.isConfirmed) {
                          // Redirect to the desired URL
                          window.location.href = "{{ route('admin.semester') }}";
                      }
                  });
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
        else {
            // If the user clicked "Cancel", revert the checkbox to its original position
            var isActiveCheckbox = document.getElementById('isActive-' + id);
            isActiveCheckbox.checked = !isActiveCheckbox.checked; // Toggle the checkbox state
        }
      });

      }

      function deleteSemester(id){
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
                      url: "{{ url('admin/semester') }}/" + id,
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

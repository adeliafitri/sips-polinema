@extends('layouts.admin.main')

@section('content')
<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Dosen</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.dosen') }}">Data Dosen</a></li>
              <li class="breadcrumb-item active">Edit Data</li>
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
                <h3 class="card-title col align-self-center">Form Edit Data Dosen</h3>
              </div>
                <div class="card-body">
                <form id="editDataForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                    <label for="nidn">NIDN</label>
                    <input type="text" class="form-control" id="nidn" name="nidn" placeholder="NIDN" value="{{ $data->nidn }}">
                    </div>
                    <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" value="{{ $data->nama }}">
                    </div>
                    <div class="form-group">
                      <label for="telp">No Telepon</label>
                      <input type="text" class="form-control" id="telp" name="telp" placeholder="No Telepon" value="{{ $data->telp }}">
                    </div>
                    <div class="form-group">
                        <label for="status">Status Dosen</label>
                        <div class="row col-3">
                            <div class="col-6" class="align-middle">
                                <input type="radio" id="aktif" name="status" value="aktif" {{ $data->status == 'aktif' ? 'checked' : '' }}>
                                <label for="aktif" class="radio-label text-box">
                                    Aktif
                                </label>
                            </div>
                            <div class="col-6" class="align-middle">
                                <input type="radio" id="non-aktif" name="status" value="non aktif" {{ $data->status == 'non aktif' ? 'checked' : '' }}>
                                <label for="non-aktif" class="radio-label text-box">
                                    Non Aktif
                                </label>
                            </div>
                        </div>
                        {{-- <input type="text" class="form-control" id="semester" name="semester" placeholder="Semester"> --}}
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input form-control" id="image" name="image">
                            <label class="custom-file-label" for="image">Choose file</label>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ $data->email }}" disabled>
                    </div>
                    <div class="form-group">
                      <label for="password">Password</label>
                      <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                      <small class="text-danger">Tidak wajib diisi jika tidak ingin mengubah password</small>
                    </div>
                </div>
                 <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <a href="{{ route('admin.dosen') }}" class="btn btn-default">Batal</a>
                    <button type="button" class="btn btn-primary" onclick="editData({{ $data->id }})">Simpan</button>
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

@section('script')
    <script>
        function editData(id){
            console.log(id);
            var form = $('#editDataForm');
            Swal.fire({
            title: "Konfirmasi Edit",
            text: "Apakah anda yakin ingin mengedit data ini?",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: "Batal",
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, edit"
            }).then((result) => {
            if (result.isConfirmed) {
                    $.ajax({
                    url: "{{ url('admin/dosen/edit') }}/" + id,
                    type: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: 'application/x-www-form-urlencoded',
                    data: form.serialize(),
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
                                    window.location.href = "{{ route('admin.dosen') }}";
                                };
                                    // window.location.href = "{{ route('admin.kelas') }}";
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status == 422) {
                            var errorMessage = xhr.responseJSON.message;
                            Swal.fire({
                            icon: "error",
                            title:"Validation Error",
                            text: errorMessage,
                        }).then((result) => {
                            // Check if the user clicked "OK"
                            if (result.isConfirmed) {
                                // Redirect to the desired URL
                                window.location.reload();
                            };
                        });
                        }
                        else{
                            var errorMessage = xhr.responseJSON.message;
                            Swal.fire({
                            icon: "error",
                            title:"Error!",
                            text: errorMessage,
                        }).then((result) => {
                            // Check if the user clicked "OK"
                            if (result.isConfirmed) {
                                // Redirect to the desired URL
                                window.location.reload();
                            };
                        });
                        }
                        // Handle error here
                        console.error(xhr.responseText);
                    }
                });
            }
            });
        }
    </script>
@endsection

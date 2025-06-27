@extends('layouts.admin.main')

@section('content')
<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Ubah Password</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                {{-- <li class="breadcrumb-item"><a href="{{ route('profile', $data->id) }}">Profile User</a></li> --}}
              {{-- <li class="breadcrumb-item"><a href="{{ route('profile.edit', ['id' => $data->id]) }}">Edit Profile</a></li> --}}
              <li class="breadcrumb-item active">Ubah Password</li>
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
                    @if (session('success'))
                    <div class="alert alert-success bg-success" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif
                </div>
              <div class="card-header d-flex justify-content-end">
                <h3 class="card-title col align-self-center">Form Ubah Password</h3>
              </div>
                <div class="card-body">
                    <form method="POST" id="editPasswordForm">
                        @csrf
                        <!-- Old Password -->
                        <div class="form-group">
                            <label for="old_password">Password Lama</label>
                            <input type="password" name="old_password" id="old_password" class="form-control" required>
                        </div>

                        <!-- New Password -->
                        <div class="form-group">
                            <label for="new_password">Password Baru</label>
                            <input type="password" name="new_password" id="new_password" class="form-control" required>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label for="confirm_password">Konfirmasi Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                        </div>

                        <button type="button" class="btn btn-primary" onclick="editPassword()">Ubah Password</button>
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
        function editPassword(){
            // console.log(id);
            var form = $('#editPasswordForm');
            Swal.fire({
            title: "Konfirmasi Edit",
            text: "Apakah anda yakin ingin mengubah password?",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: "Batal",
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, ubah"
            }).then((result) => {
            if (result.isConfirmed) {
                    $.ajax({
                    url: "{{ url('admin/user/profile/changePass') }}",
                    type: 'POST',
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
                                    window.location.href = "{{ route('admin.dashboard') }}";
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

@extends('layouts.admin.main')

@section('content')
<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Mata Kuliah</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.matakuliah') }}">Data Mata Kuliah</a></li>
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
                <h3 class="card-title col align-self-center">Form Edit Data Mata Kuliah</h3>
              </div>
                <div class="card-body">
                <form id="editDataForm">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                    <label for="kode_matkul">Kode Mata Kuliah</label>
                        <input type="text" class="form-control" id="kode_matkul" name="kode_matkul"
                        placeholder="Kode Mata Kuliah" value="{{ $data->kode_matkul }}">
                    </div>
                    <div class="form-group">
                    <label for="nama_matkul">Nama Mata Kuliah</label>
                        <input type="text" class="form-control" id="nama_matkul" name="nama_matkul" placeholder="Nama Mata Kuliah" value="{{ $data->nama_matkul }}">
                    </div>
                    <div class="form-group">
                    <label for="sks">SKS</label>
                        <input type="number" class="form-control" id="sks" rows="3" name="sks" placeholder="SKS" value="{{ $data->sks }}">
                    </div>
                </div>
                 <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <a href="{{ route('admin.matakuliah') }}" class="btn btn-default">Batal</a>
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
                    url: "{{ url('admin/mata-kuliah/edit') }}/" + id,
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
                                    window.location.href = "{{ route('admin.matakuliah') }}";
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

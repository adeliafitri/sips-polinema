@extends('layouts.admin.main')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bs-stepper/dist/css/bs-stepper.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Mata Kuliah</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.matakuliah') }}">Data Mata Kuliah</a></li>
                        <li class="breadcrumb-item active">Tambah Data</li>
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
                            @if ($errors->any())
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
                            <h3 class="card-title col align-self-center">Form Tambah Data Mata Kuliah</h3>
                            <!-- <div class="col-sm-2">
                                <a href="index.php?include=data-mahasiswa" class="btn btn-warning"><i class="nav-icon fas fa-arrow-left mr-2"></i> Kembali</a>
                            </div> -->
                        </div>
                        <div class="card-body">
                            <div class="container">
                                <form id="addDataForm" enctype="multipart/form-data">
                                    @CSRF
                                    <div id="test-l-1" class="content">
                                        <div class="form-group">
                                            <label for="kode_matkul">Kode Matkul</label>
                                            <input type="text" class="form-control" id="kode_matkul"
                                                name="kode_matkul" placeholder="Kode Mata Kuliah" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="nama_matkul">Nama Matkul</label>
                                            <input type="text" class="form-control" id="nama_matkul"
                                                name="nama_matkul" placeholder="Nama Mata Kuliah">
                                        </div>
                                        <div class="form-group">
                                            <label for="sks">SKS</label>
                                            <input type="number" class="form-control" id="sks" name="sks"
                                                placeholder="SKS">
                                        </div>
                                        <a href="{{ route('admin.matakuliah') }}" class="btn btn-default">Batal</a>
                                        <button type="button" class="btn btn-primary" onclick="addData()">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div><!-- /.card -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section><!-- /.content -->
@endsection

@section('script')
  <script>
    function addData() {
        var form = $('#addDataForm');
        $.ajax({
            type: 'POST',
            url: "{{ url('admin/mata-kuliah/create') }}",
            data: form.serialize(),
            success: function(response) {
                if (response.status == "success") {
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
                });
                }
                console.log(response);
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
  </script>
@endsection

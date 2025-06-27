@extends('layouts.admin.main')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1>Data Semester</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.semester') }}">Data Semester</a></li>
                <li class="breadcrumb-item active">Tambah Data</li>
            </ol>
            </div>
        </div>
        </div>
    </section>

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
                            <h3 class="card-title col align-self-center">Form Tambah Data Semester</h3>
                        </div>
                        <form id="addDataForm" enctype="multipart/form-data">
                            <div class="card-body">
                                @CSRF
                                <div class="form-group">
                                    <label for="tahun_ajaran">Tahun Ajaran </label>
                                    <input type="text" class="form-control" id="tahun_ajaran" name="tahun_ajaran" placeholder="Tahun Ajaran">
                                </div>
                                <div class="form-group">
                                    <label for="semester">Semester</label>
                                    <div class="row col-3">
                                        <div class="col-6" class="align-middle">
                                            <input type="radio" id="ganjil" name="semester" value="ganjil">
                                            <label for="ganjil" class="radio-label text-box">
                                                Ganjil
                                            </label>
                                        </div>
                                        <div class="col-6" class="align-middle">
                                            <input type="radio" id="genap" name="semester" value="genap">
                                            <label for="genap" class="radio-label text-box">
                                                Genap
                                            </label>
                                        </div>
                                    </div>
                                    {{-- <input type="text" class="form-control" id="semester" name="semester" placeholder="Semester"> --}}
                                </div>
                            </div>
                            <div class="card-footer clearfix">
                                <a href="{{ route('admin.semester') }}" class="btn btn-default">Batal</a>
                                <button type="button" class="btn btn-primary" onclick="addData()">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
  <script>
    function addData() {
        var form = $('#addDataForm');
        $.ajax({
            type: 'POST',
            url: "{{ url('admin/semester/store') }}",
            data: form.serialize(),
            success: function(response) {
                if (response.status == "success") {
                    Swal.fire({
                    title: "Success!",
                    text: response.message,
                    icon: "success"
                }).then((result) => {
                    // Check if the user clicked "OK"
                    if (result.isConfirmed) {
                        // Redirect to the desired URL
                        window.location.href = "{{ route('admin.semester') }}";
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

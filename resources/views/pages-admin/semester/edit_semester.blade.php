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
                <li class="breadcrumb-item active">Edit Data</li>
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
                            <h3 class="card-title col align-self-center">Form Edit Data Semester</h3>
                        </div>
                        <form id="editDataForm">
                            <div class="card-body">
                                @CSRF
                                <div class="form-group">
                                    <label for="tahun_ajaran">Tahun Ajaran </label>
                                    <input type="text" class="form-control" id="tahun_ajaran" name="tahun_ajaran" placeholder="Tahun Ajaran" value="{{ $data->tahun_ajaran }}">
                                </div>
                                <div class="form-group">
                                    <label for="semester">Semester</label>
                                    <div class="row col-3">
                                        <div class="col-6" class="align-middle">
                                            <input type="radio" id="ganjil" name="semester" value="ganjil" {{ $data->semester == 'ganjil' ? 'checked' : '' }}>
                                            <label for="ganjil" class="radio-label text-box">
                                                Ganjil
                                            </label>
                                        </div>
                                        <div class="col-6" class="align-middle">
                                            <input type="radio" id="genap" name="semester" value="genap" {{ $data->semester == 'genap' ? 'checked' : '' }}>
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
                                <button type="button" class="btn btn-primary" onclick="editData({{ $data->id }})">Simpan</button>
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
                    url: "{{ url('admin/semester/edit') }}/" + id,
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
                                    window.location.href = "{{ route('admin.semester') }}";
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

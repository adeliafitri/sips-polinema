@extends('layouts.admin.main')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Mahasiswa</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.mahasiswa') }}">Data Mahasiswa</a></li>
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
                            <h3 class="card-title col align-self-center">Form Edit Data Mahasiswa</h3>
                        </div>
                        <div class="card-body">
                            <form id="editDataForm" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="nim">NIM</label>
                                    <input type="text" class="form-control" id="nim" name="nim"
                                        placeholder="NIM" value="{{ $data->nim }}">
                                </div>
                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type="text" class="form-control" id="nama" name="nama"
                                        placeholder="Nama" value="{{ $data->nama }}">
                                </div>
                                <div class="form-group">
                                    <label for="angkatan">Angkatan</label>
                                    <input type="text" class="form-control" name="angkatan" id="angkatan" required
                                        placeholder="Angkatan" value="{{ $data->angkatan }}">
                                </div>
                                <div class="form-group">
                                    <label for="program_studi">Program Studi</label>
                                    <input type="text" class="form-control" id="program_studi" name="program_studi"
                                        placeholder="Program Studi" value="{{ $data->program_studi }}">
                                </div>
                                <div class="form-group">
                                    <label for="status">Status Mahasiswa</label>
                                    <select class="form-control select2bs4" id="status" name="status">
                                        <option value="aktif" {{ $data->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="yudisium" {{ $data->status == 'yudisium' ? 'selected' : '' }}>Yudisium</option>
                                        <option value="mutasi/pernah studi" {{ $data->status == 'mutasi/pernah studi' ? 'selected' : '' }}>Mutasi/ Pernah Studi</option>
                                        <option value="lulus" {{ $data->status == 'lulus' ? 'selected' : '' }}>Lulus</option>
                                        <option value="non aktif" {{ $data->status == 'non aktif' ? 'selected' : '' }}>Non Aktif</option>
                                    </select>
                                </div>
                                <!-- Hidden input field untuk tahun_lulus -->
                                {{-- @if ($data->status == 'lulus') --}}
                                <div class="form-group" id="tahun_lulus_div" style="display:{{ $data->status == 'lulus' ? 'block' : 'none' }};">
                                    <label for="tahun_lulus">Tahun Lulus</label>
                                    <input type="number" class="form-control" id="tahun_lulus" name="tahun_lulus" placeholder="Masukkan Tahun Lulus" value="{{ $data->tahun_lulus }}">
                                </div>
                                {{-- @endif --}}
                                <div class="form-group">
                                    <label for="telp">No Telepon</label>
                                    <input type="text" class="form-control" id="telp" name="telp"
                                        placeholder="No Telepon" value="{{ $data->telp }}">
                                </div>

                                {{-- <div class="form-group">
                                    <label for="image">Image</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input form-control" id="image"
                                            name="file">
                                        <label class="custom-file-label" for="image">Choose file</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" rows="3" name="email"
                                        placeholder="Email" value="{{ $data->email }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" rows="3"
                                        name="password" placeholder="Password">
                                    <small class="text-danger">Tidak wajib diisi jika tidak ingin mengubah password</small>
                                </div>  --}}
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            <a href="{{ route('admin.mahasiswa') }}" class="btn btn-default">Batal</a>
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
        $('#status').on('change', function () {
        var status = $(this).val();  // Mengambil value dari Select2
            console.log("Status selected: ", status); // Debugging

            var tahunLulusDiv = document.getElementById('tahun_lulus_div');

            if (status === 'lulus') {
                tahunLulusDiv.style.display = 'block';
            } else {
                tahunLulusDiv.style.display = 'none';
            }
        });

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
                    url: "{{ url('admin/mahasiswa/edit') }}/" + id,
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
                                    window.location.href = "{{ route('admin.mahasiswa') }}";
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

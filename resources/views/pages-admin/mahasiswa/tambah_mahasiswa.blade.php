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
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="card-header d-flex justify-content-end">
                            <h3 class="card-title col align-self-center">Form Tambah Data Mahasiswa</h3>
                        </div>
                        <div class="card-body">
                            <form id="addDataForm" enctype="multipart/form-data">
                                @CSRF
                                <div class="form-group">
                                    <label for="nim">NIM</label>
                                    <input type="text" class="form-control" id="nim" name="nim"
                                        placeholder="NIM">
                                </div>
                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type="text" class="form-control" id="nama" name="nama"
                                        placeholder="Nama">
                                </div>
                                <div class="form-group">
                                    <label for="telp">No Telepon</label>
                                    <input type="text" class="form-control" id="telp" name="telp"
                                        placeholder="No Telepon">
                                </div>
                                <div class="form-group">
                                    <label for="angkatan">Angkatan</label>
                                    <input type="text" class="form-control" name="angkatan" id="angkatan" required
                                        placeholder="Angkatan">
                                </div>
                                <div class="form-group">
                                    <label for="program_studi">Program Studi</label>
                                    <input type="text" class="form-control" id="program_studi" name="program_studi"
                                        placeholder="Program Studi">
                                </div>
                                <div class="form-group">
                                    <label for="status">Status Mahasiswa</label>
                                        <select class="form-control select2bs4" id="status" name="status">
                                        <option value="aktif">Aktif</option>
                                        <option value="yudisium">Yudisium</option>
                                        <option value="mutasi/pernah studi">Mutasi/ Pernah Studi</option>
                                        <option value="lulus">Lulus</option>
                                        <option value="non aktif">Non Aktif</option>
                                        </select>
                                </div>

                                <!-- Hidden input field untuk tahun_lulus -->
                                <div class="form-group" id="tahun_lulus_div" style="display:none;">
                                    <label for="tahun_lulus">Tahun Lulus</label>
                                    <input type="number" class="form-control" id="tahun_lulus" name="tahun_lulus" placeholder="Masukkan Tahun Lulus">
                                </div>
                                {{-- <div class="form-group">
                                    <label for="image">Image</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="image" name="image">
                                        <label class="custom-file-label" for="image">Choose file</label>
                                    </div>
                                </div> --}}
                                {{-- <div class="row">
                                    <div class="form-group col-8">
                                        <label for="tanggal_lahir">Tanggal Lahir</label>
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                id="tanggal_lahir" name="tanggal_lahir" placeholder="Tanggal Lahir"
                                                data-target="#reservationdate" />
                                            <div class="input-group-append" data-target="#reservationdate"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col">
                                        <label for="jenis_kelamin">Jenis Kelamin</label> <br>
                                        <input type="radio" name="jenis_kelamin" value="L"> Laki-laki
                                        <input type="radio" name="jenis_kelamin" value="P"> Perempuan
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <textarea type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat" rows="2"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="telp">No Telepon</label>
                                    <input type="text" class="form-control" id="telp" name="telp"
                                        placeholder="No Telepon">
                                </div>
                                <div class="form-group">
                                    <label for="angkatan">Angkatan</label>
                                    <input type="text" class="form-control" name="angkatan" id="angkatan" required
                                        placeholder="Angkatan">
                                </div>
                                <div class="form-group">
                                    <label for="image">Image</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="image" name="image">
                                        <label class="custom-file-label" for="image">Choose file</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" rows="3" name="email"
                                        placeholder="Email">
                                </div> --}}
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            <a href="{{ route('admin.mahasiswa') }}" class="btn btn-default">Batal</a>
                            <button type="button" class="btn btn-primary" onclick="addData()" id="button-simpan">Simpan</button>
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
    // Gunakan Select2 event listener
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

    function addData() {
        $('#button-simpan').disabled = true;
        var form = $('#addDataForm');
        $.ajax({
            type: 'POST',
            url: "{{ url('admin/mahasiswa/create') }}",
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
                        window.location.href = "{{ route('admin.mahasiswa') }}";
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

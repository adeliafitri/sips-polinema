@extends('layouts.admin.main')

@section('content')
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Data Kelas Perkuliahan</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.kelaskuliah') }}">Data Kelas Perkuliahan</a></li>
            <li class="breadcrumb-item active">Tambah Data Kelas</li>
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
            <div class="card-header d-flex justify-content-end">
              <h3 class="card-title col align-self-center">Form Tambah Data Kelas Perkuliahan</h3>
              <!-- <div class="col-sm-2">
                  <a href="index.php?include=data-mahasiswa" class="btn btn-warning"><i class="nav-icon fas fa-arrow-left mr-2"></i> Kembali</a>
              </div> -->
            </div>
              <div class="card-body">
              <form id="addDataForm">
                @csrf
                <div class="form-group">
                    <label for="semester">Semester</label>
                    <input type="hidden" name="semester" id="semester-id" value="{{ $idSemester }}">
                    <input type="text" id="semester-option" class="form-control" placeholder="Semester" value="{{ $tahunAjaran ." ". $semester }}" disabled>
                      {{-- <select class="form-control select2bs4" id="semester" name="semester" > --}}
                      {{-- <option value="">- Pilih Semester -</option> --}}
                      {{-- @foreach ($semester as $key => $data) --}}
                            {{-- <option value="{{ $idSemester }}">{{ $tahunAjaran ." ". $semester }}</option> --}}
                        {{-- @endforeach --}}
                      {{-- </select> --}}
                </div>
                <div class="form-group">
                  <label for="rps">Mata Kuliah</label>
                      <select class="form-control select2bs4" id="rps" name="rps" >
                      <option value="">- Pilih Mata Kuliah -</option>
                      @foreach ($rps as $key => $data)
                            <option value="{{ $data->id_rps }}">{{ $data->kode_matkul." - ".$data->nama_matkul ." ". $data->tahun_rps }}</option>
                        @endforeach
                      </select>
                </div>
                <div class="form-group">
                    <label for="kelas">Kelas</label>
                        <select class="form-control select2bs4" id="kelas" name="kelas">
                        <option value="">- Pilih Kelas -</option>
                        @foreach ($kelas as $id => $name)
                              <option value="{{ $id }}">{{ $name }}</option>
                          @endforeach
                        </select>
                  </div>
                <div class="form-group">
                  <label for="dosen">Dosen</label>
                      <select class="form-control select2bs4" id="dosen" name="dosen">
                      <option value="">- Pilih Dosen -</option>
                      @foreach ($dosen as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                      </select>
                </div>
               <!-- /.card-body -->
              <div class="card-footer clearfix">
                  <a href="{{ route('admin.kelaskuliah') }}" class="btn btn-default">Batal</a>
                  <button type="button" class="btn btn-primary" onclick="addData()">Simpan</button>
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
    function addData() {
        var form = $('#addDataForm');
        $.ajax({
            type: 'POST',
            url: "{{ url('admin/kelas-kuliah/create') }}",
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
                        window.location.href = "{{ route('admin.kelaskuliah') }}";
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

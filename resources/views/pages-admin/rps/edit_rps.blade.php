@extends('layouts.admin.main')

@section('content')
<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Data RPS</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.rps') }}">Data RPS</a></li>
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
                <h3 class="card-title col align-self-center">Form Edit Data RPS</h3>
              </div>
                <div class="card-body">
                <form id="editDataForm">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="mata_kuliah">Mata Kuliah</label>
                            <select class="form-control select2bs4" id="mata_kuliah" name="mata_kuliah" disabled>
                            <option value="">- Pilih Mata Kuliah -</option>
                                @foreach ($mata_kuliah as $id => $name)
                                    <option value="{{ $id }}" {{ $data->matakuliah_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                      </div>
                    <div class="form-group">
                        <label for="semester">Semester</label>
                        <input type="number" class="form-control" id="semester" name="semester"
                            placeholder="Semester" value="{{ $data->semester }}">
                    </div>
                    <div class="form-group">
                        <label for="tahun_rps">Tahun RPS</label>
                        <input type="text" class="form-control" id="tahun_rps" name="tahun_rps"
                            placeholder="Tahun RPS" value="{{ $data->tahun_rps }}">
                    </div>
                    <div class="form-group">
                        <label for="koordinator">Koordinator</label>
                            <select class="form-control select2bs4" id="koordinator" name="koordinator">
                            <option value="">- Pilih Koordinator -</option>
                                @foreach ($dosen as $id_dosen => $name)
                                    <option value="{{ $id_dosen }}" {{ $data->koordinator == $id_dosen ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                      </div>
                </div>
                 <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <a href="{{ route('admin.rps') }}" class="btn btn-default">Batal</a>
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
                    url: "{{ url('admin/rps/edit') }}/" + id,
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
                                    window.location.href = "{{ route('admin.rps') }}";
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

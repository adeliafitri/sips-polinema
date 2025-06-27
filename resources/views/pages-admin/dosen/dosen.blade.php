@extends('layouts.admin.main')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Data Dosen</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <!-- <li class="breadcrumb-item"><a href="index.php?include=dashboard">Home</a></li> -->
              <li class="breadcrumb-item active">Data Dosen</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header d-flex col-md-12 justify-content-between">
                <div class="col-md-8">
                  <form action="{{ route('admin.dosen') }}" method="GET">
                    <div class="input-group col-md-6">
                      <input type="text" name="search" id="search" class="form-control" placeholder="Search">
                      <div class="input-group-append">
                          <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search fa-sm"></i>
                          </button>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="col-md-4 d-flex align-items-end justify-content-end">
                  <div class="dropdown mr-4">
                      <button class="btn btn-success w-100 dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                          <i class="fas fa-file-excel mr-2"></i> Excel
                      </button>
                      <div class="dropdown-menu">
                        <button class="dropdown-item" data-toggle="modal" data-target="#importExcelModal">
                            <i class="fas fa-upload mr-2"></i> Import Excel
                        </button>
                        <a class="dropdown-item" href="{{ route('admin.dosen.download-excel') }}"><i class="fas fa-download mr-2"></i> Download Format</a>
                      </div>
                  </div>
                  {{-- modal import --}}
                  <div class="modal fade" id="importExcelModal" tabindex="-1" role="dialog" aria-labelledby="importExcelModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="importExcelModalLabel">Import Excel</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="callout callout-info">
                                    {{-- <h5>I am an info callout!</h5> --}}
                                    <p>Pastikan tidak ada NIDN dan Email yang sama</p>
                                    <p>Pastikan kolom No Telepon pada file excel diisi menggunakan petik satu di depan nol seperti berikut '081234567891</p>
                                </div>
                                <form id="formImport" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="excelFile">Choose Excel File</label>
                                        <input type="file" class="form-control-file" id="excelFile" name="file" required>
                                    </div>
                                    <button type="button" class="btn btn-primary" onclick="addFile()">Upload</button>
                                </form>
                            </div>
                        </div>
                    </div>
                  </div>

                  <div>
                      <a href="{{ route('admin.dosen.create') }}" class="btn btn-primary"><i class="nav-icon fas fa-plus mr-2"></i> Tambah Data</a>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="col-sm-12 mt-3">
                @if (session('success'))
                    <div class="alert alert-success bg-success" role="alert">
                        {{ session('success') }}
                    </div>
                @elseif (session('error'))
                    <div class="alert alert-danger bg-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th><input type="checkbox" id="checkAll"></th>
                            <th style="width: 10px">No</th>
                            <th>NIDN</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No Telp</th>
                            <th>Status</th>
                            <th style="width: 150px;">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($data as $key => $datas)
                          <tr>
                              <td><input type="checkbox" class="checkbox" data-id="{{ $datas->id_auth }}"></td>
                              <td>{{ $startNumber++ }}</td>
                              <td>{{ $datas->nidn }}</td>
                              <td>{{ $datas->nama }}</td>
                              <td>{{ $datas->email }}</td>
                              <td>{{ $datas->telp }}</td>
                              <td class="text-capitalize">{{ $datas->status }}</td>
                              <td class="d-flex">
                                  {{-- <a href="{{ route('admin.dosen.show', $datas->id) }}" class="btn btn-info"><i class="nav-icon far fa-eye mr-2"></i>Detail</a> --}}
                                  <a href="{{ route('admin.dosen.edit', $datas->id) }}" class="btn btn-secondary mr-1" title="Edit Data"><i class="nav-icon fas fa-edit "></i></a>
                                  <a  onclick="resetPassword({{ $datas->id }})" class="btn btn-warning mr-1" title="Reset Password"><i class="nav-icon fas fa-key" style="color: white"></i></a>
                                  <a class="btn btn-danger" onclick="deleteDosen({{$datas->id_auth}})" title="Hapus Data"><i class="nav-icon fas fa-trash-alt"></i></a>
                                  {{-- <form action="{{ route('admin.dosen.destroy', $datas->id) }}" method="post" class="mt-1">
                                      @csrf
                                      @method('delete')
                                      <button class="btn btn-danger" type="submit"><i class="nav-icon fas fa-trash-alt "></i></button>
                                  </form> --}}
                              </td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                </div>
                <button id="deleteSelected" class="btn btn-danger" style="display:none;">Delete Selected</button>
              </div>
              <!-- /.card-body -->


              <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    <div class="float-right">
                        {{ $data->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </div>
                </ul>
              </div>
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
    document.addEventListener('DOMContentLoaded', function () {
       //content goes here
    });

    function updateDeleteButtonVisibility() {
        let checkedCount = document.querySelectorAll('.checkbox:checked');
        let deleteButton = document.getElementById('deleteSelected');

        if (checkedCount.length > 1) {
            deleteButton.style.display = 'inline-block';
        } else {
            deleteButton.style.display = 'none';
        }
    }

    // Event listener untuk checkbox individual
    document.querySelectorAll('.checkbox').forEach((checkbox) => {
        checkbox.addEventListener('change', function() {
            updateDeleteButtonVisibility();
        });
    });

    // Event listener untuk checkbox "Select All"
    document.getElementById('checkAll').addEventListener('change', function() {
        console.log('Check All clicked');
        let checkboxes = document.querySelectorAll('.checkbox');
        console.log(checkboxes);
        checkboxes.forEach((checkbox) => {
            checkbox.checked = this.checked;
            console.log('Checkbox state:', checkbox.checked);
        });
        updateDeleteButtonVisibility();
    });

    // Hapus baris yang dipilih secara multiple
    document.getElementById('deleteSelected').addEventListener('click', function() {
        let selectedIds = [];
        document.querySelectorAll('.checkbox:checked').forEach((checkbox) => {
            selectedIds.push(checkbox.getAttribute('data-id'));
        });
        console.log('Selected IDs:', selectedIds)

        if (selectedIds.length > 0) {
            Swal.fire({
            title: "Konfirmasi Hapus",
            text: "Apakah anda yakin ingin menghapus data yang dipilih?",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: "Batal",
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus"
          }).then((result) => {
            if (result.isConfirmed) {
                    $.ajax({
                    url: "{{ url('admin/dosen/delete-multiple') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        // 'X-HTTP-Method-Override': 'DELETE'
                    },
                    data: JSON.stringify({ ids: selectedIds}),
                    contentType: 'application/json',
                    success: function(response) {
                        if (response.status === 'success') {
                            selectedIds.forEach(id => {
                                document.querySelector(`.checkbox[data-id="${id}"]`).closest('tr').remove();
                            });
                            updateDeleteButtonVisibility();
                            console.log(response.message);
                            Swal.fire({
                            title: "Sukses!",
                            text: response.message,
                            icon: "success"
                            }).then((result) => {
                                // Check if the user clicked "OK"
                                if (result.isConfirmed) {
                                    // Redirect to the desired URL
                                    window.location.reload();
                                };
                                    // window.location.href = "{{ route('admin.kelas') }}";
                            });
                        } else {
                            console.log(response.message);
                        }
                    },
                    error: function(error) {
                        console.error('Error during AJAX request:', error);
                    }
                });
            }
          });
        }
        //     if (confirm('Apakah anda yakin ingin menghapus data yang dipilih?')) {
        //         fetch('/delete-users', {
        //             method: 'POST',
        //             headers: {
        //                 'Content-Type': 'application/json',
        //                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //             },
        //             body: JSON.stringify({ ids: selectedIds })
        //         })
        //         .then(response => response.json())
        //         .then(data => {
        //             if (data.success) {
        //                 selectedIds.forEach(id => {
        //                     document.querySelector(`.checkbox[data-id="${id}"]`).closest('tr').remove();
        //                 });
        //                 updateDeleteButtonVisibility();
        //             } else {
        //                 alert('Failed to delete users.');
        //             }
        //         });
        //     }
        // } else {
        //     alert('Please select at least one user.');
        // }
    });

          function deleteDosen(id){
            console.log(id);
            Swal.fire({
            title: "Konfirmasi Hapus",
            text: "Apakah anda yakin ingin menghapus data ini?",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: "Batal",
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus"
          }).then((result) => {
            if (result.isConfirmed) {
                    $.ajax({
                    url: "{{ url('admin/dosen') }}/" + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
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
                                    window.location.reload();
                                };
                                    // window.location.href = "{{ route('admin.kelas') }}";
                            });
                        } else {
                            console.log(response.message);
                        }
                    },
                    error: function(error) {
                        console.error('Error during AJAX request:', error);
                    }
                });
            }
          });

          }
          function addFile() {
            // var form = $('#formImport');
            var form = $('#formImport')[0]; // Get the form element
            var formData = new FormData(form); // Create a FormData object
            $.ajax({
                type: 'POST',
                url: "{{ url('admin/dosen/import-excel') }}",
                // data: form.serialize(),
                data: formData,
                processData: false,
                contentType: false,
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
                            window.location.href = "{{ route('admin.dosen') }}";
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

        function resetPassword(id){
            console.log(id);
            Swal.fire({
            title: "Konfirmasi Reset Password",
            text: "Apakah anda yakin ingin mereset password data ini?",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: "Batal",
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya"
            }).then((result) => {
                if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.dosen.reset-password') }}",
                    type: 'POST',
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: id
                    },
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
                                window.location.reload();
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
            });
        }
        </script>
@endsection

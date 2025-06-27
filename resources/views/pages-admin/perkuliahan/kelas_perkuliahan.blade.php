@extends('layouts.admin.main')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Data Kelas Perkuliahan</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <!-- <li class="breadcrumb-item"><a href="index.php?include=dashboard">Home</a></li> -->
              <li class="breadcrumb-item active">Data Kelas Perkuliahan</li>
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
              <div class="card-header d-flex col-sm-12 justify-content-between">
                <div class="col-8">
                  <form action="{{ route('admin.kelaskuliah') }}" method="GET">
                    <div class="input-group col-sm-5 mr-3">
                      <input type="text" name="search" id="search" class="form-control" placeholder="Search">
                      <div class="input-group-append">
                          <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search fa-sm"></i>
                          </button>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="col-2">
                    <form action="{{ route('admin.kelaskuliah') }}" method="GET">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-secondary text-capitalize dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-filter fa-sm"></i> {{ $title }}
                            </button>
                            <div class="dropdown-menu">
                                @foreach ($getSemesters as $key => $smt)
                                {{-- @dd($getSemesters) --}}
                                    <button class="dropdown-item" type="button" onclick="document.getElementById('tahun_ajaran').value = '{{ $smt->id }}'; this.form.submit();"> <p style="text-transform: uppercase;">{{ $smt->tahun_ajaran . ' ' . $smt->semester }}</p></button>
                                @endforeach
                            </div>
                        </div>
                        <input type="hidden" id="tahun_ajaran" name="tahun_ajaran">
                    </form>
                </div>
                {{-- <div class="col-sm-2">
                    <a href="{{ route('admin.kelaskuliah.createKelas') }}" class="btn btn-primary w-100"><i class="nav-icon fas fa-plus mr-2"></i> Tambah Data</a>
                </div> --}}
                <div>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                             Tambah Data Kelas
                        </button>
                        <div class="dropdown-menu">
                          <a class="dropdown-item" href="{{ route('admin.kelaskuliah.excelKelas') }}"><i class="fas fa-download mr-2"></i> Unduh Format Excel</a>
                          <a class="dropdown-item" data-toggle="modal" data-target="#importExcelModal"><i class="fas fa-upload mr-2"></i> Impor Excel</a>
                          <div class="dropdown-divider"></div>
                          <a href="{{ route('admin.kelaskuliah.createKelas') }}" class="dropdown-item"><i class="fas fa-plus mr-2"></i>Tambah Data Manual</a>
                        </div>
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
                                    <p>Harap isi kolom tahun ajaran pada excel seperti ini <span class="text-bold">2023/2024</span> dan kolom semester isi dengan <span class="text-bold">ganjil atau genap</span></p>
                                    {{-- <p>Pastikan kolom No Telepon pada file excel diisi menggunakan petik satu di depan nol seperti berikut '081234567891</p> --}}
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
              </div>
              <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th style="width: 10px">No</th>
                            <th style="width: 150px;">Tahun Ajaran</th>
                            <th style="width: 100px;">Semester</th>
                            <th>Mata Kuliah</th>
                            <th>Tahun RPS</th>
                            <th>Kelas</th>
                            <th>Dosen</th>
                            {{-- <th style="width: 100px;">Koordinator</th> --}}
                            <th style="width: 100px;">Jumlah Mahasiswa</th>
                            <th style="width: 150px;">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $key => $datas)
                        @php
                            $rowCount = count($datas['info_kelas']);
                            $rowIndex = 0;
                        @endphp
                        @foreach ($datas['info_kelas'] as $info)
                          <tr>
                            @if ($rowIndex == 0)
                                <td rowspan="{{ $rowCount }}">{{ $startNumber++ }}</td>
                                <td rowspan="{{ $rowCount }}">{{ $datas['tahun_ajaran'] }}</td>
                                <td rowspan="{{ $rowCount }}" class="text-capitalize">{{ $datas['semester'] }}</td>
                                <td rowspan="{{ $rowCount }}">{{ $datas['nama_matkul'] }}
                                    <div class="mt-2">
                                        <a href="{{ route('admin.kelaskuliah.create', [
                                                'tahun_ajaran' => $datas['tahun_ajaran'],
                                                'semester' => $datas['semester'],
                                                'nama_matkul' => $datas['nama_matkul'],
                                                'id_smt' => $datas['id_smt'],
                                                'id_matkul' => $datas['id_matkul']
                                        ]) }}" class="btn-sm btn-primary">
                                            <i class="nav-icon fas fa-plus mr-2"></i> Tambah Kelas
                                        </a>
                                    </div>
                                </td>
                            @endif
                            <td>{{ $info['tahun_rps'] }}</td>
                            <td>{{ $info['nama_kelas'] }}</td>
                            <td>{{ $info['nama_dosen'] }}</td>
                            {{-- <td>
                                <div class="row justify-content-center">
                                    <form>
                                        <div class="form-group">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="isActive-{{ $info['id_kelas'] }}" name="koordinator" {{ $info['koordinator'] == 1 ? 'checked' : '' }} onclick="changeKoordinator({{ $info['id_kelas'] }})">
                                                <label class="custom-control-label" for="isActive-{{ $info['id_kelas'] }}"></label>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </td> --}}
                            <td>{{ $info['jumlah_mahasiswa'] }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('admin.kelaskuliah.show', $info['id_kelas']) }}" class="btn btn-info mr-2"><i class="nav-icon far fa-eye"></i></a>
                                    <a href="{{ route('admin.kelaskuliah.edit', $info['id_kelas']) }}" class="btn btn-secondary mr-2"><i class="nav-icon fas fa-edit"></i></a>
                                    <a class="btn btn-danger" onclick="deleteKelasKuliah({{ $info['id_kelas'] }})"><i class="nav-icon fas fa-trash-alt"></i></a>
                                </div>
                            </td>
                          </tr>
                          @php
                            $rowIndex++;
                          @endphp
                        @endforeach
                        @endforeach
                        </tbody>
                      </table>
                </div>
              </div>
              <!-- /.card-body -->
              {{-- @dd($data); --}}
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
       //content goes here
    });

          function changeKoordinator(id){
            console.log(id);
            Swal.fire({
            title: "Konfirmasi Koordinator",
            text: "Apakah anda yakin ingin mengaktifkan koordinator?",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: "Batal",
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, aktifkan"
            }).then((result) => {
              if (result.isConfirmed) {
                  var isActiveCheckbox = document.getElementById('isActive-' + id);
                  var isActiveValue = isActiveCheckbox.checked ? 1 : 0;

                      $.ajax({
                      url: "{{ url('admin/kelas-kuliah/update-koordinator') }}/" + id,
                      type: 'POST',
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      },
                      data: {
                          koordinator: isActiveValue
                      },
                      success: function(data) {
                          console.log('success');
                          Swal.fire({
                            title: "Sukses!",
                            text: "Koordinator berhasil diaktifkan",
                            icon: "success"
                          }).then((result) => {
                              // Check if the user clicked "OK"
                              if (result.isConfirmed) {
                                  // Redirect to the desired URL
                                  window.location.reload();
                              }
                          });
                      },
                      error: function(error) {
                          console.log(error);
                      }
                  });
              }
              else {
                // If the user clicked "Cancel", revert the checkbox to its original position
                var isActiveCheckbox = document.getElementById('isActive-' + id);
                isActiveCheckbox.checked = !isActiveCheckbox.checked; // Toggle the checkbox state
            }
            });
          }

          function deleteKelasKuliah(id){
            Swal.fire({
            title: "Konfirmasi Hapus",
            text: "Apakah anda yakin ingin menghapus data ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus"
            }).then((result) => {
              if (result.isConfirmed) {
                      $.ajax({
                      url: "{{ url('admin/kelas-kuliah') }}/" + id,
                      type: 'DELETE',
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      },
                      success: function(response) {
                          if (response.status === 'success') {
                              console.log(response.message);

                              Swal.fire({
                              title: "Sukses!",
                              text: "Data berhasil dihapus",
                              icon: "success"
                              }).then((result) => {
                                  // Check if the user clicked "OK"
                                  if (result.isConfirmed) {
                                      // Redirect to the desired URL
                                      window.location.reload();
                                  };
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
                url: "{{ url('admin/kelas-kuliah/import-kelas') }}",
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

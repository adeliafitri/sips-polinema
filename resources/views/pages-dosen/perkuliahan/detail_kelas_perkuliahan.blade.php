@extends('layouts.dosen.main')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Kelas {{ $data->kelas }} - {{ $data->nama_matkul }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dosen.kelaskuliah') }}">Data Kelas Perkuliahan</a></li>
              <li class="breadcrumb-item active">{{ $data->kelas }} - {{ $data->nama_matkul }}</li>
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
        <div class="card-header">
          <h3 class="card-title">Detail Kelas</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            <!-- <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
              <i class="fas fa-times"></i>
            </button> -->
          </div>
        </div>
        <div class="card-body">
            <div class="row d-flex">
                <div class="col-sm-9">
                    <p><span class="text-bold">Tahun Ajaran :</span> {{ $data->tahun_ajaran }}</p>
                    <p class="text-capitalize"><span class="text-bold">Semester :</span> {{ $data->semester }}</p>
                    <p><span class="text-bold">Dosen :</span> {{ $data->nama_dosen }}</p>
                    <p><span class="text-bold">Jumlah Mahasiswa(Aktif) :</span> {{ $jumlah_mahasiswa->jumlah_mahasiswa }}</p>
                </div>
                <div class="col-sm-3">
                    <a href="{{ route('dosen.kelaskuliah.generateportof', $data->id) }}" class="btn btn-sm btn-primary"><i class="nav-icon fas fa-download mr-2"></i> Download Portfolio Perkuliahan</a>
                </div>
            </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
      <div class="callout callout-info">
        {{-- <h5>I am an info callout!</h5> --}}
        <p>Sebelum menambahkan mahasiswa ke dalam kelas, pastikan tidak ada penambahan atau pengurangan data RPS {{ $data->nama_matkul. " (".$data->tahun_rps.")" }}</p>
    </div>
            <div class="card">
              <div class="card-header d-flex col-sm-12 justify-content-between">
                <div class="col-sm-7">
                  <form action="{{ route('dosen.kelaskuliah.show', $data->id) }}" method="GET">
                    <div class="input-group col-sm-6 mr-3">
                      <input type="text" name="search" id="search" class="form-control" placeholder="Search">
                      <div class="input-group-append">
                          <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search fa-sm"></i>
                          </button>
                      </div>
                    </div>
                  </form>
                </div>
                <!-- <h3 class="card-title col align-self-center">List Products</h3> -->
                <div class="col-sm-2">
                    <a href="{{ route('dosen.kelaskuliah.masukkannilai', $data->id) }}" class="btn btn-primary w-100"><i class="nav-icon fas fa-pen mr-2"></i> Lihat Nilai</a>
                </div>

                <div class="dropdown">
                    @if ($data->status == 'non aktif')
                    <button class="btn btn-success dropdown-toggle disabled" type="button" data-toggle="dropdown" aria-expanded="false">
                        Tambah Data Mahasiswa
                   </button>
                    @else
                    <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                        Tambah Data Mahasiswa
                    </button>
                    @endif
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="{{ route('dosen.kelaskuliah.mahasiswa.download-excel', $data->id) }}"><i class="fas fa-download mr-2"></i> Download Format</a>
                      <a class="dropdown-item" data-toggle="modal" data-target="#importExcelModal"><i class="fas fa-upload mr-2"></i> Impor Excel</a>
                      <div class="dropdown-divider"></div>
                      <a href="{{ route('dosen.kelaskuliah.createmahasiswa', $data->id) }}" class="dropdown-item"><i class="fas fa-plus mr-2"></i>Tambah Data Manual</a>
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
                                <form id="formImport" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="excelFile">Choose Excel File</label>
                                        <input type="file" class="form-control-file" id="excelFile" name="file" required>
                                    </div>
                                    <button type="button" class="btn btn-primary" onclick="addFile({{ $data->id }})">Upload</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-sm-2">
                    <a href="{{ route('dosen.kelaskuliah.createmahasiswa', $data->id) }}" class="btn btn-primary w-100"><i class="nav-icon fas fa-plus mr-2"></i> Tambah Data</a>
                </div> --}}
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
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Nilai Akhir</th>
                            <th>Huruf</th>
                            <th>Keterangan</th>
                            <th >Action</th>
                          </tr>
                        </thead>
                        <tbody>
                        @foreach ($mahasiswa as $key => $mahasiswas)
                          <tr>
                              <td><input type="checkbox" class="checkbox" data-id="{{ $mahasiswas->id }}"></td>
                              <td>{{ $startNumber++ }}</td>
                              <td>{{ $mahasiswas->nim }}</td>
                              <td>{{ $mahasiswas->nama }}</td>
                              <td>
                                  <div id="nilai-akhir-{{ $mahasiswas->id_nilai }}">
                                    @if ($data->status == 'non aktif')
                                        {{ $mahasiswas->nilai_akhir }}
                                    @else
                                    {{ $mahasiswas->nilai_akhir }}
                                    <i class="nav-icon fas fa-edit" onclick="editNilaiAkhir({{ $mahasiswas->id_nilai }})" style="cursor: pointer"></i>
                                    @endif
                                  </div>
                                  <form action="{{ route('dosen.kelaskuliah.editnilaiakhir') }}" method="POST" class="d-flex justify-content-end fit-content ">
                                      @csrf
                                      <input type="hidden" name="id_nilai" value="{{ $mahasiswas->id_nilai }}">
                                      <input type="hidden" class="form-control" name="matakuliah_kelasid" value="{{ $data->id }}">
                                      <input type="hidden" class="form-control" name="mahasiswa_id" value="{{ $mahasiswas->id }}">
                                      <input type="number" step="0.01" id="edit-nilai-akhir-form-{{ $mahasiswas->id_nilai }}" class="form-control" name="nilai_akhir" value="{{ $mahasiswas->nilai_akhir }}" style="width: 75px; display: none;">
                                      <button style="display: none;" type="submit" id="edit-nilai-akhir-button-{{ $mahasiswas->id_nilai }}" class="ml-2 btn btn-sm btn-primary"><i class="fas fa-check"></i></button>
                                  </form>
                              </td>
                              <td>{{ $huruf[$mahasiswas->id] }}</td>
                              <td>{{ $keterangan[$mahasiswas->id] }}</td>
                              <td>
                                  <div class="d-flex">
                                      {{-- <a href="{{ route('dosen.kelaskuliah.nilaimahasiswa', ['id' => $data->id, 'id_mahasiswa' => $mahasiswas->id]) }}" class="btn btn-info mr-2"><i class="nav-icon far fa-eye"></i></a> --}}
                                      @if ($data->status == 'non aktif')
                                      <a class="btn btn-danger disabled" onclick="deleteDataMahasiswa({{$data->id}}, {{ $mahasiswas->id }})"><i class="nav-icon fas fa-trash-alt"></i></a>
                                      @else
                                      <a class="btn btn-danger" onclick="deleteDataMahasiswa({{$data->id}}, {{ $mahasiswas->id }})"><i class="nav-icon fas fa-trash-alt"></i></a>
                                      @endif
                                      {{-- <form action="{{ route('dosen.kelaskuliah.destroymahasiswa',['id' => $data->id, 'id_mahasiswa' => $mahasiswas->id]) }}" method="post">
                                          @csrf
                                          @method('delete')
                                          <button class="btn btn-danger" type="submit"><i class="nav-icon fas fa-trash-alt"></i></button>
                                      </form> --}}

                                  </div>
                              </td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                </div>
                <div>
                    <input type="hidden" id="kelasId" value="{{ $data->id }}">
                </div>
                <button id="deleteSelected" class="btn btn-danger" style="display:none;">Delete Selected</button>
              </div>
              <!-- /.card-body -->

              <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    <div class="float-right">
                        {{ $mahasiswa->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </div>
                </ul>
              </div>
            </div>
            <!-- /.card -->

            <div class="row d-flex">
                <div class="col-md-6">
                    <div class="card card-info">
                        <div class="card-header">
                          <h3 class="card-title">Nilai Tugas Di Kelas</h3>
                          <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                              <i class="fas fa-minus"></i>
                            </button>
                            <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                              <i class="fas fa-times"></i>
                            </button> -->
                          </div>
                        </div>
                        <div class="card-body">
                          <canvas id="radarTugas" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <!-- /.card-body -->
                      </div>
                      <!-- /.card -->
                </div>
                <div class="col-md-6">
                    <div class="card card-info">
                        <div class="card-header">
                          <h3 class="card-title">Penguasaan Sub-CPMK Di Kelas</h3>

                          <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                              <i class="fas fa-minus"></i>
                            </button>
                            <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                              <i class="fas fa-times"></i>
                            </button> -->
                          </div>
                        </div>
                        <div class="card-body">
                          <canvas id="radarSubCPMK" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <!-- /.card-body -->
                      </div>
                      <!-- /.card -->
                </div>
            </div>
            <div class="row d-flex">
                <div class="col-md-6">
                    <div class="card card-info">
                        <div class="card-header">
                          <h3 class="card-title">Penguasaan CPMK Di Kelas</h3>
                          <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                              <i class="fas fa-minus"></i>
                            </button>
                            <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                              <i class="fas fa-times"></i>
                            </button> -->
                          </div>
                        </div>
                        <div class="card-body">
                          <canvas id="radarCPMK" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <!-- /.card-body -->
                      </div>
                      <!-- /.card -->
                </div>
                <div class="col-md-6">
                    <div class="card card-info">
                        <div class="card-header">
                          <h3 class="card-title">Penguasaan CPL Di Kelas</h3>

                          <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                              <i class="fas fa-minus"></i>
                            </button>
                            <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                              <i class="fas fa-times"></i>
                            </button> -->
                          </div>
                        </div>
                        <div class="card-body">
                          <canvas id="radarCPL" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <!-- /.card-body -->
                      </div>
                      <!-- /.card -->
                </div>
            </div>
          </div>
      </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Evaluasi dan Rencana Perbaikan</h3>
            </div>
            <div class="card-body">
                @if ($data->status == "non aktif")
                    <div class="col-12">
                        <label for="evaluasi">Evaluasi</label>
                        <p>{{ $data->evaluasi }}</p>
                    </div>
                    <div class="col-12">
                        <label for="rencana_perbaikan">Rencana Perbaikan</label>
                        <p>{{ $data->rencana_perbaikan }}</p>
                    </div>
                @else
                <form id="dataForm">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="evaluasi">Evaluasi</label>
                        <textarea class="form-control" id="evaluasi" name="evaluasi" rows="3">{{ $data->evaluasi }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="rencana_perbaikan">Rencana Perbaikan</label>
                        <textarea class="form-control" id="rencana_perbaikan" name="rencana_perbaikan" rows="3">{{ $data->rencana_perbaikan }}</textarea>
                    </div>
                    <div class="card-footer clearfix">
                        <button type="button" class="btn btn-primary" onclick="saveData({{$data->id}})">Simpan</button>
                    </div>
                </form>
                @endif
            </div>
        </div>
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
    function editNilaiAkhir(id){
        document.getElementById('nilai-akhir-'+ id).style.display = 'none';
        document.getElementById('edit-nilai-akhir-button-'+ id).style.display = 'block';
        document.getElementById('edit-nilai-akhir-form-'+ id).style.display = 'block';
    }

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
                    const kelasId = document.getElementById('kelasId').value;

                        $.ajax({
                        url: "{{ url('dosen/kelas-kuliah/delete-multiple') }}/" + kelasId,
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
        })

    function deleteDataMahasiswa(id, id_mhs){
            Swal.fire({
            title: "Konfirmasi Hapus",
            text: "Apakah anda yakin ingin menghapus mahasiswa dari kelas?",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: "Batal",
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus"
            }).then((result) => {
              if (result.isConfirmed) {
                      $.ajax({
                      url: "{{ url('dosen/kelas-kuliah') }}/" + id + "/" + id_mhs,
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

    $(document).ready(function() {
        // Fetch data from the controller
        $.ajax({
            url: "{{ url('dosen/kelas-kuliah/nilai/chart-tugas') }}",
            type: 'GET',
            data: {
                matakuliah_kelasid: {{ $data->id }},
            },
            success: function(response) {
                var ctx = document.getElementById('radarTugas').getContext('2d');
                var myRadarChart = new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels: response.labels,
                        datasets: [{
                            label: 'Nilai rata-rata tugas',
                            data: response.values,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scale: {
                            ticks: {
                                beginAtZero: true,
                                min: 0,
                                max: 100,
                            }
                        }
                    }
                });
            },
            error: function(error) {
                console.log(error);
            },
        });
    });

    $(document).ready(function() {
        // Fetch data from the controller
        $.ajax({
            url: "{{ url('dosen/kelas-kuliah/nilai/chart-sub-cpmk') }}",
            type: 'GET',
            data: {
                matakuliah_kelasid: {{ $data->id }},
            },
            success: function(response) {
                var ctx = document.getElementById('radarSubCPMK').getContext('2d');
                var myRadarChart = new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels: response.labels,
                        datasets: [{
                            label: 'Nilai rata-rata Sub-CPMK',
                            data: response.values,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scale: {
                            ticks: {
                                beginAtZero: true,
                                min: 0,
                                max: 100,
                            }
                        }
                    }
                });
            },
            error: function(error) {
                console.log(error);
            },
        });
    });

    $(document).ready(function() {
        // Fetch data from the controller
        $.ajax({
            url: "{{ url('dosen/kelas-kuliah/nilai/chart-cpmk') }}",
            type: 'GET',
            data: {
                matakuliah_kelasid: {{ $data->id }},
            },
            success: function(response) {
                var ctx = document.getElementById('radarCPMK').getContext('2d');
                var myRadarChart = new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels: response.labels,
                        datasets: [{
                            label: 'Nilai rata-rata CPMK',
                            data: response.values,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scale: {
                            ticks: {
                                beginAtZero: true,
                                min: 0,
                                max: 100,
                            }
                        }
                    }
                });
            },
            error: function(error) {
                console.log(error);
            },
        });
    });

    $(document).ready(function() {
        // Fetch data from the controller
        $.ajax({
            url: "{{ url('dosen/kelas-kuliah/nilai/chart-cpl') }}",
            type: 'GET',
            data: {
                matakuliah_kelasid: {{ $data->id }},
            },
            success: function(response) {
                var ctx = document.getElementById('radarCPL').getContext('2d');
                var myRadarChart = new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels: response.labels,
                        datasets: [{
                            label: 'Nilai rata-rata CPL',
                            data: response.values,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scale: {
                            ticks: {
                                beginAtZero: true,
                                min: 0,
                                max: 100,
                            }
                        }
                    }
                });
            },
            error: function(error) {
                console.log(error);
            },
        });
    });
        function addFile(id) {
            // var form = $('#formImport');
            var form = $('#formImport')[0]; // Get the form element
            var formData = new FormData(form); // Create a FormData object
            $.ajax({
                type: 'POST',
                url: "{{ url('dosen/kelas-kuliah') }}/" + id + "/mahasiswa/import-excel",
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
                            window.location.href = "{{ route('dosen.kelaskuliah.show', '') }}/" + id;
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
        function saveData(id) {
        var form = $('#dataForm');
        $.ajax({
            type: 'PUT',
            url: "{{ url('dosen/kelas-kuliah/updateEvaluasi') }}/" + id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            contentType: 'application/x-www-form-urlencoded',
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
</script>
@endsection





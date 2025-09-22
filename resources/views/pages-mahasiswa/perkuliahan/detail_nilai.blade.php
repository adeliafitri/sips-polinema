@extends('layouts.mahasiswa.main')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Nilai Mahasiswa</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('mahasiswa.kelaskuliah') }}">Data Kelas Perkuliahan</a></li>
            <li class="breadcrumb-item active">Nilai Mahasiswa</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

   <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Detail Nilai</h3>
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
                <div class="row">
                  <div class="col-6">
                    <p><span class="text-bold">Semester :</span> {{ $data->tahun_ajaran }} {{ $data->semester }}</p>
                    <p><span class="text-bold">Kode Mata Kuliah :</span> {{ $data->kode_matkul }}</p>
                    <p><span class="text-bold">Mata Kuliah :</span> {{ $data->nama_matkul }} </p>
                    <div class="dropdown col-sm-3">
                        <button class="btn btn-primary w-100 dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                             Lihat RPS
                        </button>
                        <div class="dropdown-menu">
                          <a class="dropdown-item" href="{{ route('mahasiswa.matakuliah.generatepdf', $data->id_rps) }}"><i class="fas fa-download mr-2"></i> Download PDF</a>
                          <div class="dropdown-divider"></div>
                          <a href="{{ route('mahasiswa.matakuliah.show', $data->id_rps) }}" class="dropdown-item"><i class="fas fa-eye mr-2"></i>Hanya Lihat</a>
                        </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <p><span class="text-bold">Kelas:</span> {{ $data->nama_kelas }} </p>
                    <p><span class="text-bold">Dosen :</span> {{ $data->nama }} </p>
                    <p><span class="text-bold">Nilai Akhir :</span> {{ $data->nilai_akhir }} </p>
                    {{-- <h5 class="text-bold">Suplementary File</h5>
                    <ul>
                        <li>Nama file <a href="" data-toggle="tooltip" data-placement="top" title="Download File"><i class="fas fa-file-pdf ml-2"></i></a></li>
                    </ul> --}}
                    {{-- <div class="box box-primary">
                        <div class="box-header with-border">
                            <h6 class="box-title">Capaian CPMK Mahasiswa</h6>
                        </div>
                        <div class="box-body">
                            <canvas id="radarCpmkMahasiswa" style="height: 100px; width:100px;"></canvas>
                        </div>
                    </div> --}}
                    {{-- <div class="text-center">
                        <button id="downloadButton" class="btn btn-sm btn-primary"><i class="fas fa-download"></i> Unduh</button>
                    </div> --}}
                  </div>
                </div>
            </div>
          </div>

        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs justify-content-center" >
                    <li class="nav-item">
                        <a class="nav-link active" role="tab" data-toggle="pill" href="#cpl-tab" aria-controls="cpl-tab" aria-selected="true" onclick="nilaiCpl({{ $data->matakuliah_kelasid }});" ><h6  style="font-weight: bold">Data CPL</h6></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" role="tab"  data-toggle="pill" href="#cpmk-tab" aria-controls="cpmk-tab" aria-selected="false" onclick="nilaiCpmk({{ $data->matakuliah_kelasid }});"><h6 style="font-weight: bold">Indikator Kinerja CPL</h6></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" role="tab"  data-toggle="pill" href="#tugas-tab" aria-controls="tugas-tab" aria-selected="false" onclick="nilaiTugas({{ $data->matakuliah_kelasid }});"><h6 style="font-weight: bold">Data Tugas</h6></a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content bg-white px-3">
                    <div class="tab-pane show fade active justify-content-center" id="cpl-tab" role="tabpanel">
                        <div id="nilai_cpl">
                        </div>
                    </div>

                    <div class="tab-pane fade justify-content-center" id="cpmk-tab" role="tabpanel">
                        <div id="nilai_cpmk">
                        </div>
                    </div>

                    <div class="tab-pane fade justify-content-center" id="tugas-tab" role="tabpanel">
                        <div id="nilai_tugas">
                        </div>
                    </div>
                </div>
            </div>
        </div>

          <div class="row d-flex">
            <div class="col-md-6">
                <div class="card card-info">
                    <div class="card-header">
                      <h3 class="card-title">Capaian Pembelajaran Lulusan</h3>

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
                      <canvas id="radarCPLMahasiswa" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
            </div>
            <div class="col-md-6">
                <div class="card card-info">
                    <div class="card-header">
                      <h3 class="card-title">Indikator Kinerja Capaian Pembelajaran Lulusan</h3>

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
                      <canvas id="radarCPMKMahasiswa" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('script')

<script>
  function nilaiCpl(matakuliah_kelasid,  page = null){
    $.ajax({
            url: "{{ url('mahasiswa/kelas-kuliah/nilai/cpl') }}",
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                page:page,
                matakuliah_kelasid: matakuliah_kelasid,
                // mahasiswa_id: mahasiswa_id
            },
            success: function(data) {
            // Insert the table into the #cpl-tab element
            $('#nilai_cpl').html(data);

            },
            error: function(error) {
                console.log(error);
            }
        });
  }

  function nilaiCpmk(matakuliah_kelasid,  page = null){
    $.ajax({
            url: "{{ url('mahasiswa/kelas-kuliah/nilai/cpmk') }}",
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                page: page,
                matakuliah_kelasid: matakuliah_kelasid,
                // mahasiswa_id: mahasiswa_id
            },
            success: function(data) {
            // Insert the table into the #cpl-tab element
            $('#nilai_cpmk').html(data);

            },
            error: function(error) {
                console.log(error);
            }
        });
  }

  function nilaiTugas(matakuliah_kelasid,  page = null){
    $.ajax({
            url: "{{ url('mahasiswa/kelas-kuliah/nilai/tugas') }}",
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                page: page,
                matakuliah_kelasid: matakuliah_kelasid,
                // mahasiswa_id: mahasiswa_id
            },
            success: function(data) {
            // Insert the table into the #cpl-tab element
            $('#nilai_tugas').html(data);

            },
            error: function(error) {
                console.log(error);
            }
        });
  }

  $(document).ready(function() {
    console.log('tes');
    nilaiCpl({{ $data->matakuliah_kelasid }}, {{ $data->mahasiswa_id }});
  });

  $(document).ready(function() {
        // Fetch data from the controller
        $.ajax({
            url: "{{ url('mahasiswa/kelas-kuliah/nilai/chart-cpl') }}",
            type: 'GET',
            data: {
                matakuliah_kelasid: {{ $data->matakuliah_kelasid }},
            },
            success: function(response) {
                var ctx = document.getElementById('radarCPLMahasiswa').getContext('2d');
                var myRadarChart = new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels: response.labels,
                        datasets: [{
                            label: 'Capaian Pembelajaran Lulusan',
                            data: response.values,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scale: {
                            ticks: {
                                beginAtZero: true,
                                min: 0,
                                max: 100
                            }
                        }
                    }
                });
            },
            error: function(error) {
                console.log(error);
            }
        });
    });

    $(document).ready(function() {
        // Fetch data from the controller
        $.ajax({
            url: "{{ url('mahasiswa/kelas-kuliah/nilai/chart-cpmk') }}",
            type: 'GET',
            data: {
                matakuliah_kelasid: {{ $data->matakuliah_kelasid }},
            },
            success: function(response) {
                var ctx = document.getElementById('radarCPMKMahasiswa').getContext('2d');
                var myRadarChart = new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels: response.labels,
                        datasets: [{
                            label: 'Indikator Kinerja Capaian Pembelajaran Lulusan',
                            data: response.values,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scale: {
                            ticks: {
                                beginAtZero: true,
                                min: 0,
                                max: 100
                            }
                        }
                    }
                });
            },
            error: function(error) {
                console.log(error);
            }
        });
    });

    $(document).ready(function() {
        nilaiCpl({{ $data->matakuliah_kelasid }});
    });

        $(document).on('click', '#tabel-datacpl .pagination a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            nilaiCpl({{ $data->matakuliah_kelasid }}, page);
        });

        $(document).on('click', '#tabel-datacpmk .pagination a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            nilaiCpmk({{ $data->matakuliah_kelasid }}, page);
        });

        // $(document).on('click', '#tabel-datasubcpmk .pagination a', function(e) {
        //     e.preventDefault();
        //     var page = $(this).attr('href').split('page=')[1];
        //     detailSubCpmk({{ $data->id_rps }}, page);
        // });

        $(document).on('click', '#tabel-datatugas .pagination a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            nilaiTugas({{ $data->matakuliah_kelasid }}, page);
        });
</script>
@endsection


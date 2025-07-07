@extends('layouts.admin.main')

<!-- Preloader -->
<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{ asset('dist/img/logo-tekkim-polinema.png') }}" alt="Logo Teknik Kimia Polinema" height="60" width="60">
  </div>

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <!-- <li class="breadcrumb-item"><a href="#">Home</a></li> -->
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
  <!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-6">
            <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                    <h3>{{ $jml_mahasiswa }}</h3>

                    <p>Jumlah Mahasiswa</p>
                    </div>
                    <div class="icon">
                    <i class="ion ion-ios-people"></i>
                    </div>
                    <a href="{{ route('admin.mahasiswa') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
            <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                    <h3>{{ $jml_dosen }}</h3>

                    <p>Jumlah Dosen</p>
                    </div>
                    <div class="icon">
                    <i class="ion ion-android-people"></i>
                    </div>
                    <a href="{{ route('admin.dosen') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                    <h3>{{ $jml_matkul }}</h3>

                    <p>Jumlah Mata Kuliah</p>
                    </div>
                    <div class="icon">
                    <i class="ion ion-person-add"></i>
                    </div>
                    <a href="{{ route('admin.matakuliah') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                    <h3>{{ $jml_kelas }}</h3>

                    <p>Jumlah Kelas Perkuliahan</p>
                    </div>
                    <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{ route('admin.kelaskuliah') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->
        {{-- <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between">
                <div class="align-self-center">
                    <h5>Capaian Pembelajaran Lulusan (Angkatan)</h5>
                </div>
                <div>
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#filterModal"><i class="fas fa-filter mr-2"></i> Filter Angkatan</button>
                </div>
                <!-- modal import -->
                <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="filterModalLabel">Filter CPL Angkatan</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="angkatanStart">Angkatan Mulai</label>
                                        <input type="number" class="form-control" id="angkatanStart" placeholder="2018">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="angkatanEnd">Angkatan Akhir</label>
                                        <input type="number" class="form-control" id="angkatanEnd" placeholder="2022">
                                    </div>
                                </div>
                                <button id="applyFilter" class="btn btn-sm btn-primary">Terapkan Filter</button>
                            </div>
                        </div>
                    </div>
                </div> --}}
                {{-- <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Filter CPL Angkatan</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="angkatanStart">Angkatan Mulai</label>
                                <input type="number" class="form-control" id="angkatanStart" placeholder="2018">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="angkatanEnd">Angkatan Akhir</label>
                                <input type="number" class="form-control" id="angkatanEnd" placeholder="2022">
                            </div>
                        </div>
                        <button id="applyFilter" class="btn btn-sm btn-primary">Terapkan Filter</button>
                    </div>
                </div> --}}
            {{-- </div>
        </div> --}}

        {{-- <div class="row clearfix" id="chartsContainer"></div> --}}

        <!-- </div> -->
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between">
            <div class="align-self-center">
                <h5>Capaian Pembelajaran Lulusan (Angkatan)</h5>
            </div>
            <div class="col-md-3">
                {{-- <label for="angkatanSelect">Pilih Angkatan</label> --}}
                <select id="angkatanOption" class="form-control">
                    <option value="">-- Pilih Angkatan --</option>
                    @foreach ($mahasiswa as $angkatan)
                        <option value="{{ $angkatan->angkatan }}" {{ $angkatan->angkatan == $defaultAngkatan ? 'selected' : '' }}>{{ $angkatan->angkatan }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- <div class="row mt-4">
        <div class="col-md-6">
            <h6>Prodi D3</h6>
            <canvas id="chartD3"></canvas>
        </div>
        <div class="col-md-6">
            <h6>Prodi D4</h6>
            <canvas id="chartD4"></canvas>
        </div>
    </div> --}}

    <div class="row clearfix" id="chartProdiContainer"></div>

      <!-- </div> -->
      <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between">
            <div class="align-self-center">
                <h5>Capaian Pembelajaran Lulusan (Kelas)</h5>
            </div>
            <div class="row mb-3">
                <div class="col-md-5">
                    {{-- <label for="angkatanSelect">Pilih Angkatan</label> --}}
                    <select id="angkatanSelect" class="form-control">
                        <option value="">-- Pilih Angkatan --</option>
                        @foreach ($mahasiswa as $angkatan)
                            <option value="{{ $angkatan->angkatan }}" {{ $angkatan->angkatan == $defaultAngkatan ? 'selected' : '' }}>{{ $angkatan->angkatan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-5">
                    {{-- <label for="prodiSelect">Pilih Prodi</label> --}}
                    <select id="prodiSelect" class="form-control">
                        <option value="">-- Pilih Prodi --</option>
                        @foreach ($listProdi as $prodi)
                            <option value="{{ $prodi->program_studi }}" {{ $prodi->program_studi == 'D4' ? 'selected' : '' }}>{{ $prodi->program_studi }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button id="applyClassFilter" class="btn btn-primary w-100">OK</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix" id="chartClassContainer"></div>
        <!-- RADAR CHART -->
        {{-- <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header">
                <h3 class="card-title text-capitalize">CPL Semester</h3>
                <div class="float-right">
                    <select class="form-control text-capitalize" id="semesterSelect">
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}">{{ $semester->tahun_ajaran }} {{ $semester->semester }}</option>
                        @endforeach
                    </select>
                </div>
                </div>
                <div class="card-body">
                <canvas id="radarCPLSemesterDashboard" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div> --}}
        {{-- <div style="clear: both"></div> --}}
      <!-- Main row -->

      <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
</section>
  <!-- /.content -->
@endsection
@section('script')
{{-- <script>
    $(document).ready(function() {
        // Function to fetch chart data
        function fetchChartData(angkatan) {
            $.ajax({
                url: "{{ route('admin.dashboard.chartcpl') }}",
                type: 'GET',
                data: {
                    angkatan: angkatan // Send selected angkatan to the server
                },
                success: function(response) {
                    var ctx = document.getElementById('radarCPLDashboard').getContext('2d');
                    var myRadarChart = new Chart(ctx, {
                        type: 'radar',
                        data: {
                            labels: response.labels,
                            datasets: [{
                                label: 'Capaian Pembelajaran Mata Kuliah',
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
        }

        // Event listener for dropdown change
        $('#selectAngkatan').change(function() {
            var selectedAngkatan = $(this).val();
            fetchChartData(selectedAngkatan);
        });

        // Trigger change event on page load to fetch initial data
        $('#selectAngkatan').trigger('change');
    });
</script> --}}
<script>
    $(document).ready(function() {

    // Fetch data from the controller
    function chartSmtData(semesterId) {
        $.ajax({
            url: "{{ url('/admin/dashboard/chart-cpl-smt') }}",
            type: 'GET',
            data: {
                semester_id: semesterId
            },
            success: function(response) {
                var ctx = document.getElementById('radarCPLSemesterDashboard').getContext('2d');
                var myRadarChart = new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels: response.labels,
                        datasets: [{
                            label: 'Capaian Pembelajaran Lulusan',
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
    }

    // Trigger saat semester diubah
    $('#semesterSelect').on('change', function() {
        var selectedSemesterId = $(this).val();
        chartSmtData(selectedSemesterId);
    });

    // Fetch data saat halaman pertama kali dimuat, menggunakan semester pertama dalam daftar
    var initialSemesterId = $('#semesterSelect').val();
    chartSmtData(initialSemesterId);

    const nilaiMinimum = 60;
    let chartInstances = {};

    function fetchChartDataByAngkatan(angkatan) {
        $.ajax({
            url: "{{ route('admin.dashboard.chartcplprodi') }}", // sesuaikan dengan route kamu
            type: 'GET',
            data: { angkatan: angkatan },
            success: function(response) {
                $('#chartProdiContainer').empty();

                Object.entries(response).forEach(([prodi, data]) => {
                    const chartId = 'radarCPL_' + prodi.replace(/\s+/g, '_'); // hindari spasi di ID

                    $('#chartProdiContainer').append(`
                        <div class="col-md-6">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">CPL Prodi ${prodi} - Angkatan ${angkatan}</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="${chartId}" style="min-height: 250px;"></canvas>
                                </div>
                            </div>
                        </div>
                    `);

                    const ctx = document.getElementById(chartId).getContext('2d');
                    new Chart(ctx, {
                        type: 'radar',
                        data: {
                            labels: data.labels,
                            datasets: [
                            {
                                label: 'Nilai Minimum',
                                data: Array(data.labels.length).fill(nilaiMinimum), // nilai sama untuk semua label
                                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                                borderColor: 'rgba(255, 99, 132, 1)',      // ungu solid
                                pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                                pointRadius: 3,
                                borderWidth: 1
                            },
                            {
                                label: `CPL ${prodi}`,
                                data: data.values,
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
                });
            },
            error: function(error) {
                console.log("Error:", error);
            }
        });
    }

    fetchChartDataByAngkatan('{{ $defaultAngkatan }}'); // Fetch data for default angkatan

    $('#angkatanOption').on('change', function () {
        const angkatan = $(this).val();
        if (angkatan) {
            fetchChartDataByAngkatan(angkatan);
        }
    });

    // Function to fetch chart data
    // function fetchChartData(startYear, endYear) {
    //     $.ajax({
    //         url: "{{ route('admin.dashboard.chartcpl') }}",
    //         type: 'GET',
    //         data: {
    //             angkatan_start: startYear,
    //             angkatan_end: endYear
    //         },
    //         success: function(response) {
    //             $('#chartsContainer').empty(); // Kosongkan container sebelum menampilkan chart baru

    //             response.forEach(function(result) {
    //                 var chartId = 'radarCPLDashboard' + result.angkatan;
    //                 $('#chartsContainer').append('<div class="col-md-6"><div class="card card-info"><div class="card-header"><h3 class="card-title">CPL Angkatan ' + result.angkatan + '</h3></div><div class="card-body"><canvas id="' + chartId + '" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas></div></div></div>');

    //                 var ctx = document.getElementById(chartId).getContext('2d');
    //                 new Chart(ctx, {
    //                     type: 'radar',
    //                     data: {
    //                         labels: result.labels,
    //                         datasets: [{
    //                             label: 'Capaian Pembelajaran Mata Kuliah Angkatan ' + result.angkatan,
    //                             data: result.values,
    //                             backgroundColor: 'rgba(255, 99, 132, 0.2)',
    //                             borderColor: 'rgba(255, 99, 132, 1)',
    //                             borderWidth: 1
    //                         }]
    //                     },
    //                     options: {
    //                         scale: {
    //                             ticks: {
    //                                 beginAtZero: true,
    //                                 min: 0,
    //                                 max: 100
    //                             }
    //                         }
    //                     }
    //                 });
    //             });
    //         },
    //         error: function(error) {
    //             console.log(error);
    //         }
    //     });
    // }

    // // Event listener for filter button click
    // $('#applyFilter').click(function() {
    //     var startYear = $('#angkatanStart').val();
    //     var endYear = $('#angkatanEnd').val();
    //     fetchChartData(startYear, endYear);
    //     $('#filterModal').modal('hide')
    // });

    // // Fetch default chart data on page load for current year and 3 years prior
    // var currentYear = new Date().getFullYear();
    // fetchChartData(currentYear - 3, currentYear);

    function fetchChartKelasData(angkatan, prodi){
        $.ajax({
            url: "{{ url('/admin/dashboard/chart-cpl-kelas') }}",
            type: 'GET',
            data: {
                angkatan: angkatan,
                prodi: prodi
            },
            success: function (data) {
                $('#chartClassContainer').empty(); // kosongkan container dulu

                data.forEach((item, index) => {
                    const chartId = `radarChart_${index}`;
                    const chartTitle = `Kelas ${item.kelas}`;

                    const chartHtml = '<div class="col-md-6"><div class="card card-info"><div class="card-header"><h3 class="card-title">Capaian Pembelajaran Lulusan ' + chartTitle + '</h3></div><div class="card-body"><canvas id="' + chartId + '" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas></div></div></div>';

                    $('#chartClassContainer').append(chartHtml);

                    const ctx = document.getElementById(chartId).getContext('2d');

                    new Chart(ctx, {
                        type: 'radar',
                        data: {
                            labels: item.labels,
                            datasets: [
                            {
                                label: 'Nilai Minimum',
                                data: Array(item.labels.length).fill(nilaiMinimum), // nilai sama untuk semua label
                                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                                borderColor: 'rgba(255, 99, 132, 1)',      // ungu solid
                                pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                                pointRadius: 3,
                                borderWidth: 1
                            },
                            {
                                label: chartTitle,
                                data: item.values,
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
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
                });
            },
            error: function (xhr, status, error) {
                console.error('Gagal ambil data:', error);
            }
        });
    }

    const defaultAngkatan = @json($defaultAngkatan);
    fetchChartKelasData(defaultAngkatan, 'D4');

    // Event listener for filter button click
    $('#applyClassFilter').click(function() {
        const angkatan = $('#angkatanSelect').val();
        const prodi = $('#prodiSelect').val();

        if (!angkatan || !prodi) {
            alert('Silakan pilih angkatan dan prodi terlebih dahulu');
            return;
        }

        fetchChartKelasData(angkatan, prodi);
    });

    });

</script>
@endsection

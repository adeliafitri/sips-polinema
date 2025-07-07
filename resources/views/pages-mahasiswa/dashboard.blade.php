@extends('layouts.mahasiswa.main')

<!-- Preloader -->
<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{ asset('dist/img/logo-tekkim-polinema.png') }}" alt="Logo Arsitektur UIN Maulana Malik Ibrahim Malang" height="60" width="60">
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
      <!-- <div class="row"> -->
        {{-- <div class="callout callout-info">
            <h5><i class="fas fa-info"></i> Pengumuman</h5>
            This page has been enhanced for printing. Click the print button at the bottom of the invoice to test.
        </div> --}}
      <!-- </div> -->
      <!-- /.row -->
      <!-- Main row -->
        <div class="row">
            <div class="col-md-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                <h3>{{ $total_kelas_kuliah }}</h3>

                <p>Jumlah Kelas Perkuliahan</p>
                </div>
                <div class="icon">
                <i class="ion ion-ios-people"></i>
                </div>
                <a href="{{ route('mahasiswa.kelaskuliah') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
            </div>
            <!-- ./col -->

            <div class="col-md-6 col-sm-12 col-12">
                <div class="info-box bg-success">
                  <span class="info-box-icon"><i class="far fa-thumbs-up"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">Jumlah SKS</span>
                    {{-- <span class="info-box-number">41,410</span> --}}

                    <div class="progress">
                      <div class="progress-bar" aria-valuenow="{{ $total_sks_lulus }}" aria-valuemin="0"
                      aria-valuemax="{{ $total_sks }}" style="width: {{ ($total_sks_lulus / $total_sks) * 100 }}%;"></div>
                    </div>
                    <span class="progress-description">
                        {{ $total_sks_lulus }} dari {{ $total_sks }}
                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
              <!-- /.col -->
            <!-- RADAR CHART -->
            <div class="col-md-6">
                <div class="card card-info">
                    <div class="card-header">
                    <h3 class="card-title">Capaian Pembelajaran Lulusan</h3>
                    {{-- <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div> --}}
                    </div>
                    <div class="card-body">
                    <canvas id="radarCPLDashboard" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                    <h3 class="card-title">Progress CPL</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                    @foreach ($data as $datas)
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <h6>{{ $datas['kode_cpl'] }}</h6>
                        </div>
                        <div class="col-md-10">
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="{{ $datas['persentase'] }}" aria-valuemin="0"
                                    aria-valuemax="100" style="width: {{ $datas['persentase'] }}%">
                                <span>{{ $datas['persentase'] }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- RADAR CHART -->
            <div class="col-md-6">
                <div class="card card-info">
                    <div class="card-header">
                    <h3 class="card-title">Capaian Pembelajaran Lulusan Angkatan {{ $mahasiswa->angkatan }} Program Studi {{ $mahasiswa->program_studi }}</h3>
                    {{-- <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div> --}}
                    </div>
                    <div class="card-body">
                    <canvas id="radarCPLAngkatanDashboard" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
      <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection

@section('script')
<script>
    $(document).ready(function() {
        const nilaiMinimum = 60;
        // Fetch data from the controller
        $.ajax({
            url: "{{ url('/mahasiswa/dashboard/chart-cpl') }}",
            type: 'GET',
            success: function(response) {
                var ctx = document.getElementById('radarCPLDashboard').getContext('2d');
                var myRadarChart = new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels: response.labels,
                        datasets: [
                            {
                                label: 'Nilai Minimum',
                                data: Array(response.labels.length).fill(nilaiMinimum), // nilai sama untuk semua label
                                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                                borderColor: 'rgba(255, 99, 132, 1)',      // ungu solid
                                pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                                pointRadius: 3,
                                borderWidth: 1
                            },
                            {
                                label: 'Capaian Pembelajaran Lulusan',
                                data: response.values,
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }
                        ]
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

        //CPL Angkatan
        $.ajax({
            url: "{{ url('/mahasiswa/dashboard/chart-cpl-angkatan') }}",
            type: 'GET',
            success: function(response) {
                var ctx = document.getElementById('radarCPLAngkatanDashboard').getContext('2d');
                var myRadarChart = new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels: response.labels,
                        datasets: [
                            {
                                label: 'Nilai Minimum',
                                data: Array(response.labels.length).fill(nilaiMinimum), // nilai sama untuk semua label
                                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                                borderColor: 'rgba(255, 99, 132, 1)',      // ungu solid
                                pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                                pointRadius: 3,
                                borderWidth: 1
                            },
                            {
                                label: 'Capaian Pembelajaran Lulusan Angkatan ' +  {{ $mahasiswa->angkatan }},
                                data: response.values,
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }
                        ]
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
</script>
@endsection

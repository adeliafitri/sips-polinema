@extends('layouts.admin.main')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Nilai Mahasiswa</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php?include=nilai-mahasiswa">Data Nilai</a></li>
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
              <h3 class="card-title">Detail Nilai {{ $data->nama_matkul }}</h3>
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
                    <p><span class="text-bold">Nama :</span> {{ $data->nama }}</p>
                    <p><span class="text-bold">NIM :</span> {{ $data->nim }}</p>
                    <p><span class="text-bold">Nilai Akhir :</span> 
                      {{ $data->nilai_akhir }}
                    </p>
                    
                  </div>
                  <div class="col-3">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h6 class="box-title">Capaian CPMK Mahasiswa</h6>
                        </div>
                        <div class="box-body">
                            <canvas id="radarCpmkMahasiswa" style="height: 100px; width:100px;"></canvas>
                        </div>
                    </div>
                    <div class="text-center">
                        <button id="downloadButton" class="btn btn-sm btn-primary"><i class="fas fa-download"></i> Unduh</button>
                    </div>
                  </div>
                </div>
            </div>
          </div>

          <div class="card bg-section pb-1 bg-white" style="border-top-left-radius: 8px; border-top-right-radius: 8px">
            <ul class="nav nav-tabs justify-content-center" style="background-color: #007bff; border-top-left-radius: 8px; border-top-right-radius: 8px">
                <li class="nav-item">
                    <a class="nav-link active" role="tab" data-toggle="pill" href="#cpl-tab" aria-controls="cpl-tab" aria-selected="true" onclick="nilaiCpl({{ $data->matakuliah_kelasid }}, {{ $data->mahasiswa_id }});" ><h6  style="color: black; font-weight: bold">Data CPL</h6></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" role="tab"  data-toggle="pill" href="#cpmk-tab" aria-controls="cpmk-tab" aria-selected="false" onclick="nilaiCpmk({{ $data->matakuliah_kelasid }}, {{ $data->mahasiswa_id }});"><h6 style="color: black; font-weight: bold">Data CPMK</h6></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" role="tab"  data-toggle="pill" href="#subcpmk-tab" aria-controls="subcpmk-tab" aria-selected="false" onclick="nilaiSubCpmk({{ $data->matakuliah_kelasid }}, {{ $data->mahasiswa_id }});"><h6 style="color: black; font-weight: bold">Data Sub CPMK</h6></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" role="tab"  data-toggle="pill" href="#tugas-tab" aria-controls="tugas-tab" aria-selected="false" onclick="nilaiTugas({{ $data->matakuliah_kelasid }}, {{ $data->mahasiswa_id }});"><h6 style="color: black; font-weight: bold">Tugas</h6></a>
                </li>
            </ul>
            

            <div class="tab-content bg-white px-3">
                <div class="tab-pane show fade active justify-content-center" id="cpl-tab" role="tabpanel">
                    <div id="nilai_cpl">
                    </div>
                </div>

                <div class="tab-pane fade justify-content-center" id="cpmk-tab" role="tabpanel">
                    <div id="nilai_cpmk">
                    </div>
                </div>

                <div class="tab-pane fade justify-content-center" id="subcpmk-tab" role="tabpanel">
                    <div id="nilai_sub_cpmk">
                    </div>
                </div>

                <div class="tab-pane fade justify-content-center" id="tugas-tab" role="tabpanel">
                    <div id="nilai_tugas">
                    </div>
                </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('script')
<script>
  function nilaiCpl(matakuliah_kelasid, mahasiswa_id){
    $.ajax({
            url: "{{ url('admin/kelas-kuliah/nilai/cpl') }}",
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                matakuliah_kelasid: matakuliah_kelasid,
                mahasiswa_id: mahasiswa_id
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

  function nilaiCpmk(matakuliah_kelasid, mahasiswa_id){
    $.ajax({
            url: "{{ url('admin/kelas-kuliah/nilai/cpmk') }}",
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                matakuliah_kelasid: matakuliah_kelasid,
                mahasiswa_id: mahasiswa_id
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

  function nilaiSubCpmk(matakuliah_kelasid, mahasiswa_id){
    $.ajax({
            url: "{{ url('admin/kelas-kuliah/nilai/sub-cpmk') }}",
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                matakuliah_kelasid: matakuliah_kelasid,
                mahasiswa_id: mahasiswa_id
            },
            success: function(data) {
            // Insert the table into the #cpl-tab element
            $('#nilai_sub_cpmk').html(data);

            },
            error: function(error) {
                console.log(error);
            }
        });
  }


  function nilaiTugas(matakuliah_kelasid, mahasiswa_id){
    $.ajax({
            url: "{{ url('admin/kelas-kuliah/nilai/tugas') }}",
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                matakuliah_kelasid: matakuliah_kelasid,
                mahasiswa_id: mahasiswa_id
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
    nilaiCpl({{ $data->matakuliah_kelasid }}, {{ $data->mahasiswa_id }});
  });


</script>
@endsection



<div class="card-header p-0 pt-1 border-bottom-0">
    <!-- Tautan Tab -->
    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
        @foreach($kelasData as $kelas)
        <li class="nav-item">
            <a class="nav-link" id="tab_{{ $kelas->id_kelas_kuliah }}" data-toggle="pill" href="#pane_{{ $kelas->id_kelas_kuliah }}" role="tab" aria-controls="pane_{{ $kelas->id_kelas_kuliah }}" aria-selected="true">Kelas {{ $kelas->nama_kelas }}</a>
        </li>
        @endforeach
    </ul>
</div>
<div class="card-body">
<!-- Tab Panes -->
    <div class="tab-content" id="custom-tabs-three-tabContent">

        @foreach($kelasData as $kelas)
        <div class="tab-pane fade" id="pane_{{ $kelas->id_kelas_kuliah }}" role="tabpanel" aria-labelledby="tab_{{ $kelas->id_kelas_kuliah }}">
            <p><span class="text-bold">Dosen :</span> </p>
            <p><span class="text-bold">Jumlah Mahasiswa(Aktif) :</span> 15</p>
            <div class="card">
                <div class="card-header">
                    <div class="col-10">
                        <form action="product.php?aksi=cari" method="post">
                            <div class="input-group col-sm-4 mr-3">
                                <input type="text" name="search" id="search" class="form-control" placeholder="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">

                    </ul>
                </div>
            </div>

            <h5 class="text-bold text-center mb-3">Nilai Rata-Rata Kelas</h5>
            <div class="row m-3">
                <div class="col-6">
                    <p class="text-muted m-0">Berdasarkan Tugas</p>
                    <p class="mb-0"><span class="text-bold">Survey & Measured Drawing: </span> 88.07</p>
                    <p class="mb-0"><span class="text-bold">Studi Preseden: </span> 85</p>
                </div>
                <div class="col-3">
                    <div class="box box-primary">
                        <!-- <div class="box-header with-border">
                            <h6 class="box-title">Berdasarkan Tugas</h6>
                        </div> -->
                        <div class="box-body">
                            <canvas id="radarChart" style="height: 100px; width:100px;"></canvas>
                        </div>
                    </div>
                    <div class="text-center">
                        <button id="downloadButton" class="btn btn-sm btn-primary"><i class="fas fa-download"></i> Unduh</button>
                    </div>
                </div>
                <!--
                <div class="col-3">
                    <p class="text-muted m-0">Berdasarkan CPMK</p>
                    <p class="mb-0"><span class="text-bold">CPMK1: </span> 88.07</p>
                    <p class="mb-0"><span class="text-bold">CPMK2: </span> 85</p>
                </div>
                <div class="col-2">
                    <p class="text-muted m-0">Berdasarkan CPL</p>
                    <p class="mb-0"><span class="text-bold">CPL1: </span> 88.07</p>
                    <p class="mb-0"><span class="text-bold">CPL2: </span> 85</p>
                </div> -->
            </div>
            <div class="row m-3">
                <div class="col-6">
                    <p class="text-muted m-0">Berdasarkan Sub-CPMK</p>
                    <p class="mb-0"><span class="text-bold">Sub-CPMK1: </span> 88.07</p>
                    <p class="mb-0"><span class="text-bold">Sub-CPMK2: </span> 85</p>
                </div>
                <div class="col-3">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h6 class="box-title">Berdasarkan Sub-CPMK</h6>
                        </div>
                        <div class="box-body">
                            <canvas id="radarChartSub" style="height: 100px; width:100px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
<!-- /.card -->


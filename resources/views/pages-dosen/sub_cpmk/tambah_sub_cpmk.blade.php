<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Data Sub Capaian Pembelajaran Mata Kuliah</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php?include=data-sub-cpmk">Data Sub CPMK</a></li>
            <li class="breadcrumb-item active">Tambah Data</li>
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
              <?php if((!empty($_GET['notif']))&&(!empty($_GET['jenis']))){?>
                    <?php if($_GET['notif']=="tambahkosong"){?>
                        <div class="alert alert-danger bg-danger" role="alert">Maaf data <?php echo $_GET['jenis'];?> wajib di isi</div>
                    <?php }?>
                <?php }?>
              </div>
            <div class="card-header d-flex justify-content-end">
              <h3 class="card-title col align-self-center">Form Tambah Data Sub CPMK</h3>
              <!-- <div class="col-sm-2">
                  <a href="index.php?include=data-mahasiswa" class="btn btn-warning"><i class="nav-icon fas fa-arrow-left mr-2"></i> Kembali</a>
              </div> -->
            </div>
              <div class="card-body">
              <form action="index.php?include=proses-tambah-sub-cpmk" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label for="waktu_pelaksanaan">Minggu</label>
                  <input type="text" class="form-control" id="waktu_pelaksanaan" name="minggu" placeholder="Minggu Pelaksanaan">
                </div>
                <div class="form-group">
                  <label for="cpmk">CPMK</label>
                      <select class="form-control select2bs4" id="cpmk" name="cpmk">
                      <option value="">- Pilih CPMK -</option>
                      <?php
                      $sql_k = "SELECT `k`.`id`,`k`.`kode_cpmk`,`m`.`nama_matkul` FROM `cpmk` `k` INNER JOIN `matakuliah` `m` ON `k`.`matakuliah_id` = `m`.`id` ORDER BY `m`.`nama_matkul`, `k`.`id`";
                      $query_k = mysqli_query($koneksi, $sql_k);
                      while($data_k = mysqli_fetch_row($query_k)){
                              $id_cpmk = $data_k[0];
                              $kode_cpmk = $data_k[1];
                              $nama_matkul = $data_k[2];
                      ?>
                      <option value="<?php echo $id_cpmk;?>"><?php echo "$nama_matkul - $kode_cpmk";?></option>
                      <?php }?>
                      </select>
                </div>
                <div class="form-group">
                  <label for="nama_subcpmk">Nama Sub CPMK</label>
                  <input type="text" class="form-control" id="nama_subcpmk" name="nama_subcpmk" placeholder="Nama Sub CPMK">
                </div>
                <div class="form-group">
                  <label for="deskripsi">Deskripsi</label>
                  <textarea class="form-control text-muted" id="deskripsi" name="deskripsi" rows="3"></textarea>
                </div>
                <div class="form-group">
                  <label for="bentuk_soal">Bentuk Soal</label>
                  <input type="text" class="form-control" id="bentuk_soal" name="bentuk_soal" placeholder="Bentuk Soal">
                </div>
                <div class="form-group">
                  <label for="bobot">Bobot</label>
                  <input type="number" class="form-control" id="bobot" name="bobot" placeholder="Bobot" step="0.01">
                </div>
              </div>
               <!-- /.card-body -->
              <div class="card-footer clearfix">
                  <a href="index.php?include=data-sub-cpmk" class="btn btn-default">Cancel</a>
                  <button type="submit" class="btn btn-primary">Save</button>
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

<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Data Capaian Pembelajaran Mata Kuliah</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php?include=data-cpmk">Data CPMK</a></li>
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
              <h3 class="card-title col align-self-center">Form Tambah Data CPMK</h3>
              <!-- <div class="col-sm-2">
                  <a href="index.php?include=data-mahasiswa" class="btn btn-warning"><i class="nav-icon fas fa-arrow-left mr-2"></i> Kembali</a>
              </div> -->
            </div>
              <div class="card-body">
              <form action="index.php?include=proses-tambah-cpmk" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label for="mata_kuliah">Mata Kuliah</label>
                      <select class="form-control select2bs4" id="mata_kuliah" name="mata_kuliah">
                      <option value="">- Pilih Mata Kuliah-</option>
                      <?php
                      $sql_k = "SELECT `id`,`nama_matkul` FROM `matakuliah` ORDER BY `nama_matkul`";
                      $query_k = mysqli_query($koneksi, $sql_k);
                      while($data_k = mysqli_fetch_row($query_k)){
                              $id_matkul = $data_k[0];
                              $nama_matkul = $data_k[1];
                      ?>
                      <option value="<?php echo $id_matkul;?>"><?php echo $nama_matkul;?></option>
                      <?php }?>
                      </select>
                </div>
                <div class="form-group">
                  <label for="cpl">CPL</label>
                      <select class="form-control select2bs4" id="cpl" name="cpl">
                      <option value="">- Pilih CPL -</option>
                      <?php
                      $sql_k = "SELECT `id`,`kode_cpl` FROM `cpl` ORDER BY `id`";
                      $query_k = mysqli_query($koneksi, $sql_k);
                      while($data_k = mysqli_fetch_row($query_k)){
                              $id_cpl = $data_k[0];
                              $kode_cpl = $data_k[1];
                      ?>
                      <option value="<?php echo $id_cpl;?>"><?php echo $kode_cpl;?></option>
                      <?php }?>
                      </select>
                </div>
                <div class="form-group">
                  <label for="kode_cpmk">Kode CPMK</label>
                  <input type="text" class="form-control" id="kode_cpmk" name="kode_cpmk" placeholder="Kode CPMK">
                </div>
                <div class="form-group">
                  <label for="deskripsi">Deskripsi</label>
                  <textarea class="form-control text-muted" id="deskripsi" name="deskripsi" rows="3" placeholder="Deskripsi"></textarea>
                </div>
              </div>
               <!-- /.card-body -->
              <div class="card-footer clearfix">
                  <a href="index.php?include=data-cpmk" class="btn btn-default">Cancel</a>
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

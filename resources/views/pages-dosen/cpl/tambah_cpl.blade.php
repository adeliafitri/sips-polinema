<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Data Capaian Pembelajaran Lulusan</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php?include=data-cpl">Data CPL</a></li>
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
              <h3 class="card-title col align-self-center">Form Tambah Data CPL</h3>
              <!-- <div class="col-sm-2">
                  <a href="index.php?include=data-mahasiswa" class="btn btn-warning"><i class="nav-icon fas fa-arrow-left mr-2"></i> Kembali</a>
              </div> -->
            </div>
              <div class="card-body">
              <form action="index.php?include=proses-tambah-cpl" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label for="kode_cpl">Nama CPL</label>
                  <input type="text" class="form-control" id="kode_cpl" name="kode_cpl" placeholder="Nama CPL">
                </div>
                <div class="form-group">
                  <label for="jenis_cpl">Jenis CPL</label>
                  <div class="col-12">
                    <select class="form-control" id="jenis_cpl" name="jenis_cpl">
                      <option value="">- Pilih Jenis CPL -</option>
                      <?php
                      $sql_k = "SELECT `id`,`nama_jenis` FROM `jenis_cpl` ORDER BY `nama_jenis`";
                      $query_k = mysqli_query($koneksi, $sql_k);
                      while($data_k = mysqli_fetch_row($query_k)){
                              $id_jenis = $data_k[0];
                              $nama_jenis = $data_k[1];
                      ?>
                      <option value="<?php echo $id_jenis;?>"><?php echo $nama_jenis;?></option>
                      <?php }?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="deskripsi">Deskripsi</label>
                  <textarea class="form-control text-muted" id="deskripsi" name="deskripsi" rows="3" placeholder="Deskripsi"></textarea>
                </div>
              </div>
               <!-- /.card-body -->
              <div class="card-footer clearfix">
                  <a href="index.php?include=data-cpl" class="btn btn-default">Cancel</a>
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

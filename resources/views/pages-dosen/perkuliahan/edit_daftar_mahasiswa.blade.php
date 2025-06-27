<?php
if((isset($_GET['data'])) && (isset($_GET['id_mahasiswa']))){
	$id_kelas_kuliah = $_GET['data'];
	$_SESSION['id_kelas_kuliah']=$id_kelas_kuliah;

  $id_mahasiswa = $_GET['id_mahasiswa'];
	$_SESSION['id_mahasiswa']=$id_mahasiswa;
}
?>

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Kelas Perkuliahan</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php?include=detail-kelas-perkuliahan&data=<?php echo $id_kelas_kuliah;?>">Detail Kelas Perkuliahan</a></li>
              <li class="breadcrumb-item active">Edit Data Mahasiswa</li>
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
                <!-- <div class="col-12 justify-content-center">
                <?php if((!empty($_GET['notif']))&&(!empty($_GET['jenis']))){?>
                      <?php if($_GET['notif']=="tambahkosong"){?>
                          <div class="alert alert-danger bg-danger" role="alert">Maaf data <?php echo $_GET['jenis'];?> wajib di isi</div>
                      <?php }?>
                  <?php }?>
                </div> -->
                <div class="card-header d-flex justify-content-end">
                    <h3 class="card-title col align-self-center">Form Edit Daftar Mahasiswa</h3>
                    <!-- <div class="col-sm-2">
                        <a href="index.php?include=data-mahasiswa" class="btn btn-warning"><i class="nav-icon fas fa-arrow-left mr-2"></i> Kembali</a>
                    </div> -->
                </div>
                <form action="proses-addproduct.php" method="post" enctype="multipart/form-data">
                    <div class="card-body">
                      <div class="form-group">
                          <label for="mahasiswa">Mahasiswa</label>
                          <select class="form-control select2bs4" disabled="disabled" id="mahasiswa" name="mahasiswa" style="width: 100%;">
                              <option value="">- Pilih Mahasiswa -</option>
                              <?php
                                $sql_k = "SELECT `id`, `nama` FROM `mahasiswa` ORDER BY `nama`";
                                $query_k = mysqli_query($koneksi, $sql_k);
                                while($data_k = mysqli_fetch_row($query_k)){
                                        $id_mahasiswa = $data_k[0];
                                        $nama_mahasiswa = $data_k[1];
                                ?>
                                <option value="<?php echo $id_mahasiswa;?>" <?php if($id_mahasiswa==$id_mahasiswa){?>
                                selected <?php }?>><?php echo "$nama_mahasiswa";?></option>
                              <?php }?>
                          </select>
                      </div>
                      <div class="form-group">
                          <label for="kelas_matkul">Kelas Mata Kuliah</label>
                          <select class="form-control select2bs4" id="kelas_matkul" name="kelas_matkul">
                              <option value="">- Pilih Kelas Mata Kuliah -</option>
                              <?php
                                $sql_k = "SELECT `mk`.`id`, `k`.`nama_kelas`, `m`.`nama_matkul` FROM `matakuliah_kelas` `mk` INNER JOIN `kelas` `k` ON `mk`.`kelas_id` = `k`.`id` INNER JOIN `matakuliah` `m` ON `mk`.`matakuliah_id` = `m`.`id` INNER JOIN `dosen` `d` ON `mk`.`dosen_id` = `d`.`id` ORDER BY `k`.`nama_kelas`, `m`.`nama_matkul`";
                                $query_k = mysqli_query($koneksi, $sql_k);
                                while($data_k = mysqli_fetch_row($query_k)){
                                        $id_kelas_matkul = $data_k[0];
                                        $nama_kelas= $data_k[1];
                                        $nama_matkul= $data_k[2];
                                ?>
                                <option value="<?php echo $id_kelas_matkul;?>" <?php if($id_kelas_matkul==$id_kelas_kuliah){?>
                                selected <?php }?>><?php echo "$nama_kelas - $nama_matkul";?></option>
                              <?php }?>
                          </select>
                      </div>
                    </div>
                    <div class="card-footer clearfix">
                        <a href="index.php?include=detail-kelas-perkuliahan&data=<?php echo $id_kelas_kuliah;?>" class="btn btn-default">Cancel</a>
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

    
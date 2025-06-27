<?php
if(isset($_GET['data'])){
	$id_subcpmk = $_GET['data'];
	$_SESSION['id_subcpmk']=$id_subcpmk;
	//get data sub_cpmk
	$sql_m = "SELECT `nama_subcpmk`,`deskripsi`, `cpmk_id`, `waktu_pelaksanaan`, `bentuk_soal`, `bobot_subcpmk` FROM `sub_cpmk` WHERE `id`='$id_subcpmk'";
	$query_m = mysqli_query($koneksi,$sql_m);
	while($data_m = mysqli_fetch_row($query_m)){
		$nama_subcpmk = $data_m[0];
		$deskripsi = $data_m[1];
    $cpmk_id = $data_m[2];
    $minggu = $data_m[3];
    $bentuk_soal = $data_m[4];
    $bobot = $data_m[5];
	}
}
?>

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Sub Capaian Pembelajaran Mata Kuliah</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php?include=data-sub-cpmk">Data Sub CPMK</a></li>
              <li class="breadcrumb-item active">Edit Data</li>
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
                <h3 class="card-title col align-self-center">Form Edit Data Sub CPMK</h3>
                <!-- <div class="col-sm-2">
                    <a href="index.php?include=data-mahasiswa" class="btn btn-warning"><i class="nav-icon fas fa-arrow-left mr-2"></i> Kembali</a>
                </div> -->
              </div>
                <div class="card-body">
                <form action="index.php?include=proses-edit-sub-cpmk" method="post" enctype="multipart/form-data">
                  <div class="form-group">
                    <label for="waktu_pelaksanaan">Minggu</label>
                    <input type="text" class="form-control" id="waktu_pelaksanaan" name="minggu" placeholder="Minggu Pelaksanaan" value="<?php echo $minggu?>">
                  </div>
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
                        <option value="<?php echo $id_cpmk;?>" <?php if($id_cpmk==$cpmk_id){?>
                        selected <?php }?>><?php echo "$nama_matkul - $kode_cpmk";?></option>
                        <?php }?>
                        </select>
                  <div class="form-group">
                    <label for="nama_subcpmk">Nama Sub CPMK</label>
                    <input type="text" class="form-control" id="nama_subcpmk" name="nama_subcpmk" placeholder="Nama Sub CPMK" value="<?php echo $nama_subcpmk?>">
                  </div>
                  <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea class="form-control text-muted" id="deskripsi" name="deskripsi" rows="3">
                      <?php echo $deskripsi;?>
                    </textarea>
                  </div>
                  <div class="form-group">
                    <label for="bentuk_soal">Bentuk Soal</label>
                    <input type="text" class="form-control" id="bentuk_soal" name="bentuk_soal" placeholder="Bentuk Soal" value="<?php echo $bentuk_soal;?>">
                  </div>
                  <div class="form-group">
                    <label for="bobot">Bobot</label>
                    <input type="number" class="form-control" id="bobot" name="bobot" placeholder="Bobot" value="<?php echo $bobot;?>">
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

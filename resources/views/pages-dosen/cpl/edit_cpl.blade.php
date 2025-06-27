<?php
if(isset($_GET['data'])){
	$id_cpl = $_GET['data'];
	$_SESSION['id_cpl']=$id_cpl;
	//get data cpl
	$sql_m = "SELECT `kode_cpl`,`deskripsi`, `jeniscpl_id` FROM `cpl` WHERE `id`='$id_cpl'";
	$query_m = mysqli_query($koneksi,$sql_m);
	while($data_m = mysqli_fetch_row($query_m)){
		$kode_cpl = $data_m[0];
		$deskripsi = $data_m[1];
    $id_jenis_cpl = $data_m[2];
	}
}
?>

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Capaian Pembelajaran Lulusan</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php?include=data-cpl">Data CPL</a></li>
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
                      <?php if($_GET['notif']=="editkosong"){?>
                          <div class="alert alert-danger bg-danger" role="alert">Maaf data <?php echo $_GET['jenis'];?> wajib di isi</div>
                      <?php }?>
                  <?php }?>
                </div>
              <div class="card-header d-flex justify-content-end">
                <h3 class="card-title col align-self-center">Form Edit Data CPL</h3>
              </div>
                <div class="card-body">
                <form action="index.php?include=proses-edit-cpl" method="post">
                    <div class="form-group">
                    <label for="kode_cpl">Kode CPL</label>
                    <input type="text" class="form-control" id="kode_cpl" name="kode_cpl" placeholder="Kode CPL" value="<?php echo $kode_cpl;?>">
                    </div>
                    <div class="form-group">
                        <label for="jenis_cpl" class="col-sm-2 col-form-label">Jenis cpl</label>
                        <div class="col-sm-8">
                        <select class="form-control" id="jenis_cpl" name="jenis_cpl">
                        <option value="">- Pilih Jenis cpl -</option>
                        <?php
                        $sql_k = "SELECT `id`,`nama_jenis` FROM
                        `jenis_cpl` ORDER BY `nama_jenis`";
                        $query_k = mysqli_query($koneksi, $sql_k);
                        while($data_k = mysqli_fetch_row($query_k)){
                        $id_jenis = $data_k[0];
                        $nama_jenis = $data_k[1];
                        ?>
                        <option value="<?php echo $id_jenis;?>"
                        <?php if($id_jenis==$id_jenis_cpl){?>
                        selected <?php }?>><?php echo $nama_jenis;?></option>
                        <?php }?>
                        </select>
                        </div>
                    </div>
                    <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?php echo $deskripsi;?></textarea>
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

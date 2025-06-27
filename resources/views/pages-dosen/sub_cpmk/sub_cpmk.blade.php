
<?php
if((isset($_GET['aksi']))&&(isset($_GET['data']))){
	if($_GET['aksi']=='hapus'){
		$id_subcpmk = $_GET['data'];
		//hapus data profil
		$sql_dh = "delete from `sub_cpmk` where `id` = '$id_subcpmk'";
		mysqli_query($koneksi,$sql_dh);
	}
}
if (isset($_GET['aksi']) && isset($_POST['search'])) {
    if ($_GET['aksi']=='cari') {
    $_SESSION['search'] = $_POST['search'];
    $search = $_SESSION['search'];
    }
}
if (isset($_SESSION['search'])) {
    $search = $_SESSION['search'];
}
?>

<!-- Content Header (Page header) -->
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Data Sub Capaian Pembelajaran Mata Kuliah</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <!-- <li class="breadcrumb-item"><a href="index.php?include=dashboard">Home</a></li> -->
              <li class="breadcrumb-item active">Data SUB CPMK</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header d-flex col-sm-12 justify-content-between">
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
                <!-- <h3 class="card-title col align-self-center">List Products</h3> -->
                <div class="col-sm-2">
                    <a href="index.php?include=tambah-sub-cpmk" class="btn btn-primary"><i class="nav-icon fas fa-plus mr-2"></i> Tambah Data</a>
                </div>
              </div>
              <div class="card-body">
              <div class="col-sm-12 mt-3">
                  <?php if(!empty($_GET['notif'])){?>
                      <?php if($_GET['notif']=="tambahberhasil"){?>
                          <div class="alert alert-success bg-success text-white" role="alert">
                          Data Berhasil Ditambahkan</div>
                      <?php } else if($_GET['notif']=="editberhasil"){?>
                          <div class="alert alert-success bg-success text-white" role="alert">
                          Data Berhasil Diubah</div>
                      <?php } else if($_GET['notif']=="hapusberhasil"){?>
                          <div class="alert alert-success bg-success text-white" role="alert">
                          Data Berhasil Dihapus</div>
                      <?php }?>
                  <?php }?>
              </div>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">No</th>
                      <th>Waktu Pelaksanaan</th>
                      <th>Mata Kuliah</th>
                      <th style="width: 100px;">CPMK</th>
                      <th style="width: 120px;">Nama Sub CPMK</th>
                      <!-- <th>Deskripsi</th> -->
                      <th>Bentuk Soal</th>
                      <th>Bobot</th>
                      <th style="width: 200px;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                    $batas = 10;
                    if(!isset($_GET['halaman'])){
                        $posisi = 0;
                        $halaman = 1;
                    }else{
                        $halaman = $_GET['halaman'];
                        $posisi = ($halaman-1) * $batas;
                    }
                    $sql_b = "SELECT `s`.`id`, `s`.`nama_subcpmk`, `s`.`deskripsi`, `s`.`waktu_pelaksanaan`, `s`.`bentuk_soal`, `s`.`bobot_subcpmk`, `k`.`kode_cpmk`, `m`.`nama_matkul` FROM `sub_cpmk` `s` INNER JOIN `cpmk` `k` ON `s`.`cpmk_id` = `k`.`id` INNER JOIN `matakuliah` `m` ON `k`.`matakuliah_id` = `m`.`id`";
                    if (isset($search) && !empty($search)) {
                        $sql_b .= " WHERE `k`.`kode_cpmk` LIKE '%$search%' || `m`.`nama_matkul` LIKE '%$search%' || `s`.`nama_subcpmk` LIKE '%$search%' ";
                    }
                    $sql_b .= " ORDER BY `m`.`nama_matkul`, `k`.`id`, `s`.`id` limit $posisi, $batas";
                    $query_b = mysqli_query($koneksi,$sql_b);
                    $no = $posisi+1;
                    while($data_b = mysqli_fetch_row($query_b)){
                        $id_subcpmk= $data_b[0];
                        $nama_subcpmk = $data_b[1];
                        $deskripsi = $data_b[2];
                        $minggu = $data_b[3];
                        $bentuk_soal = $data_b[4];
                        $bobot = $data_b[5];
                        $kode_cpmk = $data_b[6];
                        $nama_matkul = $data_b[7];
                    ?>
                    <tr>
                        <td><?php echo $no;?></td>
                        <td><?php echo $minggu;?></td>
                        <td><?php echo $nama_matkul;?></td>
                        <td><?php echo $kode_cpmk;?></td>
                        <td><?php echo $nama_subcpmk;?></td>
                        <!-- <td><?php echo $deskripsi;?></td> -->
                        <!-- <td>Analisis Situs dan Konteks: Mahasiswa dapat melakukan analisis mendalam terhadap lokasi proyek arsitektur, memahami karakteristik situs, dan mengidentifikasi faktor-faktor yang memengaruhi desain.</td> -->
                        <td><?php echo $bentuk_soal;?></td>
                        <td><?php echo $bobot;?>%</td>
                        <td>
                            <!-- <a href="index.php?include=detail-sub-cpmk" class="btn btn-info"><i class="nav-icon far fa-eye mr-2"></i>Detail</a> -->
                            <a href="index.php?include=edit-sub-cpmk&data=<?php echo $id_subcpmk;?>" class="btn btn-secondary mt-1"><i class="nav-icon fas fa-edit mr-2"></i>Edit</a>
                            <a href="javascript:if(confirm('Anda yakin ingin menghapus data?')) window.location.href = 'index.php?include=data-sub-cpmk&aksi=hapus&data=<?php echo $id_subcpmk;?>&notif=hapusberhasil'" class="btn btn-danger mt-1"><i class="nav-icon fas fa-trash-alt mr-2"></i>Delete</a>
                        </td>
                    </tr>
                    <?php $no++; }?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->

              <?php
                    $sql_b = "SELECT `s`.`id`, `s`.`nama_subcpmk`, `s`.`deskripsi`, `s`.`waktu_pelaksanaan`, `s`.`bentuk_soal`, `s`.`bobot_subcpmk`, `k`.`kode_cpmk`, `m`.`nama_matkul` FROM `sub_cpmk` `s` INNER JOIN `cpmk` `k` ON `s`.`cpmk_id` = `k`.`id` INNER JOIN `matakuliah` `m` ON `k`.`matakuliah_id` = `m`.`id`";
                    if (isset($search) && !empty($search)) {
                        $sql_b .= " WHERE `k`.`kode_cpmk` LIKE '%$search%' || `m`.`nama_matkul` LIKE '%$search%' || `s`.`nama_subcpmk` LIKE '%$search%' ";
                    }
                    $sql_b .= " ORDER BY `m`.`nama_matkul`, `k`.`id`, `s`.`id`";
                    // $query_b = mysqli_query($koneksi,$sql_b);
                    $query_jum = mysqli_query($koneksi,$sql_b);
                    $jum_data = mysqli_num_rows($query_jum);
                    $jum_halaman = ceil($jum_data/$batas);
              ?>

              <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                  <?php
                    if ($jum_halaman == 0) {
                      //nothing page
                    }elseif ($jum_halaman == 1) {
                      echo "<li class='page-item'><a class='page-link'>1</a></li>";
                    }else {
                      $prev = $halaman-1;
                      $next = $halaman+1;
                      if ($halaman!=1) {
                        echo "<li class='page-item'><a class='page-link' href='index.php?include=data-sub-cpmk&halaman=1'>First</a></li>";
                        echo "<li class='page-item'><a class='page-link' href='index.php?include=data-sub-cpmk&halaman=$prev'>&laquo;</a></li>";
                      }
                      for ($i=1; $i <= $jum_halaman; $i++) {
                        if ($i > $halaman - 5 and $i < $halaman + 5) {
                          if ($i != $halaman) {
                            echo "<li class='page-item'><a class='page-link' href='index.php?include=data-sub-cpmk&halaman=$i'>$i</a></li>";
                          } else {
                            echo "<li class='page-item'><a class='page-link'>$i</a></li>";
                          }
                        }
                      }
                      if ($halaman!=$jum_halaman) {
                        echo "<li class='page-item'><a class='page-link' href='index.php?include=data-sub-cpmk&halaman=$next'>&raquo;</a></li>";
                        echo "<li class='page-item'><a class='page-link' href='index.php?include=data-sub-cpmk&halaman=$jum_halaman'>Last</a></li>";
                      }
                    }
                  ?>
                </ul>
              </div>
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

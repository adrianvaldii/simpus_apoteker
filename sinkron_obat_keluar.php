<?php session_start();
  // error_reporting(0);
  include_once 'koneksi/mysql_lokal.php';
  include_once 'koneksi/mysql_pusat.php';

  // session login
  if(empty($_SESSION['user'])){
    header("Location: index.php");

    die("Redirecting to: index.php");
  }

  $status = "";

  // query
  // $query_lokal = "INSERT INTO obat_keluar (id_obat_keluar,jumlah,id_obat,id_daftar,tgl_keluar) SELECT id_obat_keluar,jumlah,id_obat,id_daftar,tgl_keluar FROM skripsi_pusat.obat_keluar
  //                 ON DUPLICATE KEY UPDATE
  //                 id_obat_keluar = values(id_obat_keluar),jumlah = values(jumlah),id_obat = values(id_obat),
  //                 id_daftar = values(id_daftar),tgl_keluar = values(tgl_keluar)";
  // $query_pusat = "INSERT INTO obat_keluar (id_obat_keluar,jumlah,id_obat,id_daftar,tgl_keluar) SELECT id_obat_keluar,jumlah,id_obat,id_daftar,tgl_keluar FROM skripsi_apoteker.obat_keluar
  //                 ON DUPLICATE KEY UPDATE
  //                 id_obat_keluar = values(id_obat_keluar),jumlah = values(jumlah),id_obat = values(id_obat),
  //                 id_daftar = values(id_daftar),tgl_keluar = values(tgl_keluar)";

  // dokter to pusat
  if (isset($_POST['submit_pusat'])) {
    $query = "select * from obat_keluar";
    $stmt = $mysqli_pusat->query($query);

    while ($row = $stmt->fetch_array(MYSQL_ASSOC)) {
      $mysqli_lokal->query("INSERT INTO obat_keluar (id_obat_keluar, jumlah, id_obat, id_daftar, tgl_keluar)
      VALUES ('$row[id_obat_keluar]','$row[jumlah]','$row[id_obat]','$row[id_daftar]','$row[tgl_keluar]') ON DUPLICATE KEY UPDATE
      id_obat_keluar = '$row[id_obat_keluar]',jumlah = '$row[jumlah]',id_obat = '$row[id_obat]',
      id_daftar = '$row[id_daftar]',tgl_keluar = '$row[tgl_keluar]'");
    }

    if ($stmt) {
      $status = "Good Job! Data obat keluar berhasil disinkronisasi.";
    } else {
      $status = "Bad News! Data obat keluar gagal disinkronisasi.";
    }

  }
  // pusat to dokter
  if (isset($_POST['submit_apoteker'])) {
    $query = "select * from obat_keluar";
    $stmt = $mysqli_lokal->query($query);

    while ($row = $stmt->fetch_array(MYSQL_ASSOC)) {
      $mysqli_pusat->query("INSERT INTO obat_keluar (id_obat_keluar, jumlah, id_obat, id_daftar, tgl_keluar)
      VALUES ('$row[id_obat_keluar]','$row[jumlah]','$row[id_obat]','$row[id_daftar]','$row[tgl_keluar]') ON DUPLICATE KEY UPDATE
      id_obat_keluar = '$row[id_obat_keluar]',jumlah = '$row[jumlah]',id_obat = '$row[id_obat]',
      id_daftar = '$row[id_daftar]',tgl_keluar = '$row[tgl_keluar]'");
    }

    if ($stmt) {
      $status = "Good Job! Data obat keluar berhasil disinkronisasi.";
    } else {
      $status = "Bad News! Data obat keluar gagal disinkronisasi.";
    }

  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width= device-width, inital-scale=1">

    <title>Poli Klinik UIN Sunan Kalijaga</title>

    <!-- css -->
    <?php include 'css.php'; ?>
  </head>
  <body>
    <nav class="navbar navbar-default navbar-poli">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">UIN SUKA HEALTH CENTER - APOTEKER</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <?php
            include "nav-top.php";
          ?>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>

    <div class="container-fluid isi">
      <div class="row">
        <?php
          include 'nav-side.php';
        ?>
        <div class="col-md-10 content">
          <h3>SINKRONISASI DATA OBAT KELUAR</h3>
          <hr>
          <div class="row">
            <!-- resepsionis sinkronisasi dengan pusat -->
            <div class="col-md-6">
              <fieldset class="sinkron">
                <legend class="sinkron">Sinkronisasi Data Server Apoteker - Server Pusat</legend>
                <h3>Tekan tombol 'Sinkronisasi' untuk sinkronisasi data</h3>
                <?php
                  if (isset($_POST['submit_pusat'])) {
                    ?><div class="alert alert-info" role="alert"><?php echo $status; ?></div><?php
                  }
                ?>
                <hr>
                <form action="sinkron_obat_keluar.php" method="post">
                  <input type="submit" class="btn btn-success btn-sinkron" name="submit_pusat" value="SINKRONISASI">
                </form>
                <!-- <button type="button" id="pustorep" class="btn btn-primary btn-sinkron" name="button">SINKRONISASI PUSAT KE RESEPSIONIS</button> -->
              </fieldset>
            </div>
            <!-- pusat sinkronisasi dengan resepsionis -->
            <div class="col-md-6">
              <fieldset class="sinkron">
                <legend class="sinkron">Sinkronisasi Data Server Pusat - Server Apoteker</legend>
                <h3>Tekan tombol 'Sinkronisasi' untuk sinkronisasi data</h3>
                <?php
                  if (isset($_POST['submit_apoteker'])) {
                    ?><div class="alert alert-info" role="alert"><?php echo $status; ?></div><?php
                  }
                ?>
                <hr>
                <form action="sinkron_obat_keluar.php" method="post">
                  <input type="submit" class="btn btn-success btn-sinkron" name="submit_apoteker" value="SINKRONISASI">
                </form>
                <!-- <button type="button" id="pustorep" class="btn btn-primary btn-sinkron" name="button">SINKRONISASI PUSAT KE RESEPSIONIS</button> -->
              </fieldset>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- js -->
    <?php include 'js.php'; ?>
  </body>
</html>

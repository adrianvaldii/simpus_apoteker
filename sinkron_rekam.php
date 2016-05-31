<?php session_start();
  // error_reporting(0);
  include_once 'koneksi/mysql_lokal.php';
  include_once 'koneksi/mysql_pusat.php';
  include_once 'koneksi/mysql_resepsionis.php';
  include_once 'koneksi/mysql_dokter.php';

  // session login
  if(empty($_SESSION['user'])){
    header("Location: index.php");

    die("Redirecting to: index.php");
  }

  $status = "";
  // query merge data
  // $query_pusat = "INSERT INTO rekam_medis (id_daftar,tgl_daftar,anamnesa,pemeriksaan,diagnosis,terapi,status,id_pasien,id_pelayanan,id_dokter,id_perawat,id_apoteker,hasil_lab)
  //                 SELECT id_daftar,tgl_daftar,anamnesa,pemeriksaan,diagnosis,terapi,status,id_pasien,id_pelayanan,id_dokter,id_perawat,id_apoteker,hasil_lab from skripsi_apoteker.rekam_medis
  //                 ON DUPLICATE KEY UPDATE
  //                 tgl_daftar=values(tgl_daftar),anamnesa=values(anamnesa),pemeriksaan=values(pemeriksaan),
  //                 diagnosis=values(diagnosis),terapi=values(terapi),status=values(status),id_pasien=values(id_pasien),
  //                 id_pelayanan=values(id_pelayanan),id_dokter=values(id_dokter),id_perawat=values(id_perawat),
  //                 id_apoteker=values(id_apoteker),hasil_lab=values(hasil_lab)";
  //
  // $query_apoteker = "INSERT INTO rekam_medis (id_daftar,tgl_daftar,anamnesa,pemeriksaan,diagnosis,terapi,status,id_pasien,id_pelayanan,id_dokter,id_perawat,id_apoteker,hasil_lab)
  //                 SELECT id_daftar,tgl_daftar,anamnesa,pemeriksaan,diagnosis,terapi,status,id_pasien,id_pelayanan,id_dokter,id_perawat,id_apoteker,hasil_lab from skripsi_pusat.rekam_medis
  //                 ON DUPLICATE KEY UPDATE
  //                 tgl_daftar=values(tgl_daftar),anamnesa=values(anamnesa),pemeriksaan=values(pemeriksaan),
  //                 diagnosis=values(diagnosis),terapi=values(terapi),status=values(status),id_pasien=values(id_pasien),
  //                 id_pelayanan=values(id_pelayanan),id_dokter=values(id_dokter),id_perawat=values(id_perawat),
  //                 id_apoteker=values(id_apoteker),hasil_lab=values(hasil_lab)";
  //
  // $query_resepsionis = "INSERT INTO rekam_medis (id_daftar,tgl_daftar,anamnesa,pemeriksaan,diagnosis,terapi,status,id_pasien,id_pelayanan,id_dokter,id_perawat,id_apoteker,hasil_lab)
  //                 SELECT id_daftar,tgl_daftar,anamnesa,pemeriksaan,diagnosis,terapi,status,id_pasien,id_pelayanan,id_dokter,id_perawat,id_apoteker,hasil_lab from skripsi_resepsionis.rekam_medis
  //                 ON DUPLICATE KEY UPDATE
  //                 tgl_daftar=values(tgl_daftar),anamnesa=values(anamnesa),pemeriksaan=values(pemeriksaan),
  //                 diagnosis=values(diagnosis),terapi=values(terapi),status=values(status),id_pasien=values(id_pasien),
  //                 id_pelayanan=values(id_pelayanan),id_dokter=values(id_dokter),id_perawat=values(id_perawat),
  //                 id_apoteker=values(id_apoteker),hasil_lab=values(hasil_lab)";
  //
  // $query_dokter = "INSERT INTO rekam_medis (id_daftar,tgl_daftar,anamnesa,pemeriksaan,diagnosis,terapi,status,id_pasien,id_pelayanan,id_dokter,id_perawat,id_apoteker,hasil_lab)
  //                 SELECT id_daftar,tgl_daftar,anamnesa,pemeriksaan,diagnosis,terapi,status,id_pasien,id_pelayanan,id_dokter,id_perawat,id_apoteker,hasil_lab from skripsi_dokter.rekam_medis
  //                 ON DUPLICATE KEY UPDATE
  //                 tgl_daftar=values(tgl_daftar),anamnesa=values(anamnesa),pemeriksaan=values(pemeriksaan),
  //                 diagnosis=values(diagnosis),terapi=values(terapi),status=values(status),id_pasien=values(id_pasien),
  //                 id_pelayanan=values(id_pelayanan),id_dokter=values(id_dokter),id_perawat=values(id_perawat),
  //                 id_apoteker=values(id_apoteker),hasil_lab=values(hasil_lab)";

  // apoteker to pusat
  if (isset($_POST['submit_pusat'])) {
    $query = "select * from rekam_medis";
    $stmt = $mysqli_pusat->query($query);

    while ($row = $stmt->fetch_array(MYSQL_ASSOC)) {
      $mysqli_lokal->query("INSERT INTO rekam_medis (id_daftar, tgl_daftar, anamnesa, pemeriksaan, diagnosis, terapi, status, id_pasien, id_pelayanan, id_dokter, id_perawat, id_apoteker, hasil_lab)
      VALUES ('$row[id_daftar]','$row[tgl_daftar]','$row[anamnesa]','$row[pemeriksaan]','$row[diagnosis]','$row[terapi]',
      '$row[status]','$row[id_pasien]','$row[id_pelayanan]','$row[id_dokter]','$row[id_perawat]','$row[id_apoteker]','$row[hasil_lab]') ON DUPLICATE KEY UPDATE
      id_daftar = '$row[id_daftar]', tgl_daftar = '$row[tgl_daftar]', anamnesa = '$row[anamnesa]', pemeriksaan = '$row[pemeriksaan]',
      diagnosis = '$row[diagnosis]', terapi = '$row[terapi]', status = '$row[status]', id_pelayanan = '$row[id_pelayanan]',
      id_perawat = '$row[id_perawat]', id_pasien = '$row[id_pasien]', id_dokter = '$row[id_dokter]', id_apoteker = '$row[id_apoteker]',
      hasil_lab = '$row[hasil_lab]'");
    }

    if ($stmt) {
      $status = "Good Job! Data rekam medis berhasil disinkronisasi.";
    } else {
      $status = "Bad News! Data rekam medis gagal disinkronisasi.";
    }

  }
  // pusat to apoteker
  if (isset($_POST['submit_apoteker'])) {
    $query = "select * from rekam_medis";
    $stmt = $mysqli_lokal->query($query);

    while ($row = $stmt->fetch_array(MYSQL_ASSOC)) {
      $mysqli_pusat->query("INSERT INTO rekam_medis (id_daftar, tgl_daftar, anamnesa, pemeriksaan, diagnosis, terapi, status, id_pasien, id_pelayanan, id_dokter, id_perawat, id_apoteker, hasil_lab)
      VALUES ('$row[id_daftar]','$row[tgl_daftar]','$row[anamnesa]','$row[pemeriksaan]','$row[diagnosis]','$row[terapi]',
      '$row[status]','$row[id_pasien]','$row[id_pelayanan]','$row[id_dokter]','$row[id_perawat]','$row[id_apoteker]','$row[hasil_lab]') ON DUPLICATE KEY UPDATE
      id_daftar = '$row[id_daftar]', tgl_daftar = '$row[tgl_daftar]', anamnesa = '$row[anamnesa]', pemeriksaan = '$row[pemeriksaan]',
      diagnosis = '$row[diagnosis]', terapi = '$row[terapi]', status = '$row[status]', id_pelayanan = '$row[id_pelayanan]',
      id_perawat = '$row[id_perawat]', id_pasien = '$row[id_pasien]', id_dokter = '$row[id_dokter]', id_apoteker = '$row[id_apoteker]',
      hasil_lab = '$row[hasil_lab]'");
    }

    if ($stmt) {
      $status = "Good Job! Data rekam medis berhasil disinkronisasi.";
    } else {
      $status = "Bad News! Data rekam medis gagal disinkronisasi.";
    }

  }
  // apoteker to resepsionis
  if (isset($_POST['submit_resepsionis'])) {
    $query = "select * from rekam_medis";
    $stmt = $mysqli_resepsionis->query($query);

    while ($row = $stmt->fetch_array(MYSQL_ASSOC)) {
      $mysqli_lokal->query("INSERT INTO rekam_medis (id_daftar, tgl_daftar, anamnesa, pemeriksaan, diagnosis, terapi, status, id_pasien, id_pelayanan, id_dokter, id_perawat, id_apoteker, hasil_lab)
      VALUES ('$row[id_daftar]','$row[tgl_daftar]','$row[anamnesa]','$row[pemeriksaan]','$row[diagnosis]','$row[terapi]',
      '$row[status]','$row[id_pasien]','$row[id_pelayanan]','$row[id_dokter]','$row[id_perawat]','$row[id_apoteker]','$row[hasil_lab]') ON DUPLICATE KEY UPDATE
      id_daftar = '$row[id_daftar]', tgl_daftar = '$row[tgl_daftar]', anamnesa = '$row[anamnesa]', pemeriksaan = '$row[pemeriksaan]',
      diagnosis = '$row[diagnosis]', terapi = '$row[terapi]', status = '$row[status]', id_pelayanan = '$row[id_pelayanan]',
      id_perawat = '$row[id_perawat]', id_pasien = '$row[id_pasien]', id_dokter = '$row[id_dokter]', id_apoteker = '$row[id_apoteker]',
      hasil_lab = '$row[hasil_lab]'");
    }

    if ($stmt) {
      $status = "Good Job! Data rekam medis berhasil disinkronisasi.";
    } else {
      $status = "Bad News! Data rekam medis gagal disinkronisasi.";
    }

  }
  // apoteker to dokter
  if (isset($_POST['submit_dokter'])) {
    $query = "select * from rekam_medis";
    $stmt = $mysqli_dokter->query($query);

    while ($row = $stmt->fetch_array(MYSQL_ASSOC)) {
      $mysqli_lokal->query("INSERT INTO rekam_medis (id_daftar, tgl_daftar, anamnesa, pemeriksaan, diagnosis, terapi, status, id_pasien, id_pelayanan, id_dokter, id_perawat, id_apoteker, hasil_lab)
      VALUES ('$row[id_daftar]','$row[tgl_daftar]','$row[anamnesa]','$row[pemeriksaan]','$row[diagnosis]','$row[terapi]',
      '$row[status]','$row[id_pasien]','$row[id_pelayanan]','$row[id_dokter]','$row[id_perawat]','$row[id_apoteker]','$row[hasil_lab]') ON DUPLICATE KEY UPDATE
      id_daftar = '$row[id_daftar]', tgl_daftar = '$row[tgl_daftar]', anamnesa = '$row[anamnesa]', pemeriksaan = '$row[pemeriksaan]',
      diagnosis = '$row[diagnosis]', terapi = '$row[terapi]', status = '$row[status]', id_pelayanan = '$row[id_pelayanan]',
      id_perawat = '$row[id_perawat]', id_pasien = '$row[id_pasien]', id_dokter = '$row[id_dokter]', id_apoteker = '$row[id_apoteker]',
      hasil_lab = '$row[hasil_lab]'");
    }

    if ($stmt) {
      $status = "Good Job! Data rekam medis berhasil disinkronisasi.";
    } else {
      $status = "Bad News! Data rekam medis gagal disinkronisasi.";
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
          <h3>SINKRONISASI DATA REKAM MEDIS</h3>
          <hr>
          <div class="row">
            <!-- resepsionis apoteker dengan pusat -->
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
                <form action="sinkron_rekam.php" method="post">
                  <input type="submit" class="btn btn-success btn-sinkron" name="submit_pusat" value="SINKRONISASI">
                </form>
                <!-- <button type="button" id="pustorep" class="btn btn-primary btn-sinkron" name="button">SINKRONISASI PUSAT KE RESEPSIONIS</button> -->
              </fieldset>
            </div>
            <!-- pusat dengan apoteker -->
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
                <form action="sinkron_rekam.php" method="post">
                  <input type="submit" class="btn btn-success btn-sinkron" name="submit_apoteker" value="SINKRONISASI">
                </form>
                <!-- <button type="button" id="pustorep" class="btn btn-primary btn-sinkron" name="button">SINKRONISASI PUSAT KE RESEPSIONIS</button> -->
              </fieldset>
            </div>
          </div>
          <div class="row">
            <!-- apoteker dengan resepsionis -->
            <div class="col-md-6">
              <fieldset class="sinkron">
                <legend class="sinkron">Sinkronisasi Data Server Apoteker - Server Resepsionis</legend>
                <h3>Tekan tombol 'Sinkronisasi' untuk sinkronisasi data</h3>
                <?php
                  if (isset($_POST['submit_resepsionis'])) {
                    ?><div class="alert alert-info" role="alert"><?php echo $status; ?></div><?php
                  }
                ?>
                <hr>
                <form action="sinkron_rekam.php" method="post">
                  <input type="submit" class="btn btn-success btn-sinkron" name="submit_resepsionis" value="SINKRONISASI">
                </form>
                <!-- <button type="button" id="pustorep" class="btn btn-primary btn-sinkron" name="button">SINKRONISASI PUSAT KE RESEPSIONIS</button> -->
              </fieldset>
            </div>
            <!-- apoteker dengan dokter -->
            <div class="col-md-6">
              <fieldset class="sinkron">
                <legend class="sinkron">Sinkronisasi Data Server Apoteker - Server Dokter</legend>
                <h3>Tekan tombol 'Sinkronisasi' untuk sinkronisasi data</h3>
                <?php
                  if (isset($_POST['submit_dokter'])) {
                    ?><div class="alert alert-info" role="alert"><?php echo $status; ?></div><?php
                  }
                ?>
                <hr>
                <form action="sinkron_rekam.php" method="post">
                  <input type="submit" class="btn btn-success btn-sinkron" name="submit_dokter" value="SINKRONISASI">
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

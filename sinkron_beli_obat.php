<?php session_start();
  // error_reporting(0);
  include_once 'koneksi/koneksi_lokal.php';
  include_once 'koneksi/koneksi_pusat.php';
  include_once 'koneksi/koneksi_resepsionis.php';
  include_once 'koneksi/koneksi_dokter.php';

  // session login
  if(empty($_SESSION['user'])){
    header("Location: index.php");

    die("Redirecting to: index.php");
  }

  $status = "";
  // dokter to pusat
  if (isset($_POST['submit_pusat'])) {
    $query = "MERGE INTO rekam_medis a USING (SELECT * FROM rekam_medis@to_pusat) p ON (a.id_daftar = p.id_daftar)
              WHEN MATCHED THEN UPDATE SET a.tgl_daftar = p.tgl_daftar, a.anamnesa = p.anamnesa, a.pemeriksaan = p.pemeriksaan, a.diagnosis = p.diagnosis, a.terapi = p.terapi, a.status = p.status, a.id_pasien = p.id_pasien, a.id_pelayanan = p.id_pelayanan, a.id_dokter = p.id_dokter, a.id_perawat = p.id_perawat, a.id_apoteker = p.id_apoteker, a.hasil_lab = p.hasil_lab
              WHEN NOT MATCHED THEN INSERT (id_daftar, tgl_daftar, anamnesa, pemeriksaan, diagnosis, terapi, status, id_pasien, id_pelayanan, id_dokter, id_perawat, id_apoteker, hasil_lab) VALUES (p.id_daftar, p.tgl_daftar, p.anamnesa, p.pemeriksaan, p.diagnosis, p.terapi, p.status, p.id_pasien, p.id_pelayanan, p.id_dokter, p.id_perawat, p.id_apoteker, p.hasil_lab)";
    $data_sinkron = oci_parse($conn_lokal, $query);
    $result = oci_execute($data_sinkron);
    oci_commit($conn_lokal);

    if ($result) {
      $status = "Good Job! Data rekam medis berhasil disinkronisasi.";
    } else {
      $status = "Bad News! Data rekam medis gagal disinkronisasi.";
    }
    oci_close($conn_lokal);
  }
  // pusat to dokter
  if (isset($_POST['submit_apoteker'])) {
    $query = "MERGE INTO rekam_medis p USING (SELECT * FROM rekam_medis@to_apoteker) a ON (p.id_daftar = a.id_daftar)
              WHEN MATCHED THEN UPDATE SET p.tgl_daftar = a.tgl_daftar, p.anamnesa = a.anamnesa, p.pemeriksaan = a.pemeriksaan, p.diagnosis = a.diagnosis, p.terapi = a.terapi, p.status = a.status, p.id_pasien = a.id_pasien, p.id_pelayanan = a.id_pelayanan, p.id_dokter = a.id_dokter, p.id_perawat = a.id_perawat, p.id_apoteker = a.id_apoteker, p.hasil_lab = a.hasil_lab
              WHEN NOT MATCHED THEN INSERT (id_daftar, tgl_daftar, anamnesa, pemeriksaan, diagnosis, terapi, status, id_pasien, id_pelayanan, id_dokter, id_perawat, id_apoteker, hasil_lab) VALUES (a.id_daftar, a.tgl_daftar, a.anamnesa, a.pemeriksaan, a.diagnosis, a.terapi, a.status, a.id_pasien, a.id_pelayanan, a.id_dokter, a.id_perawat, a.id_apoteker, a.hasil_lab)";
    $data_sinkron = oci_parse($conn_pusat, $query);
    $result = oci_execute($data_sinkron);
    oci_commit($conn_pusat);

    if ($result) {
      $status = "Good Job! Data rekam medis berhasil disinkronisasi.";
    } else {
      $status = "Bad News! Data rekam medis gagal disinkronisasi.";
    }
    oci_close($conn_pusat);
  }
  // dokter to resepsionis
  if (isset($_POST['submit_resepsionis'])) {
    $query = "MERGE INTO rekam_medis a USING (SELECT * FROM rekam_medis@to_resepsionis) r ON (a.id_daftar = r.id_daftar)
              WHEN MATCHED THEN UPDATE SET a.tgl_daftar = r.tgl_daftar, a.anamnesa = r.anamnesa, a.pemeriksaan = r.pemeriksaan, a.diagnosis = r.diagnosis, a.terapi = r.terapi, a.status = r.status, a.id_pasien = r.id_pasien, a.id_pelayanan = r.id_pelayanan, a.id_dokter = r.id_dokter, a.id_perawat = r.id_perawat, a.id_apoteker = r.id_apoteker, a.hasil_lab = r.hasil_lab
              WHEN NOT MATCHED THEN INSERT (id_daftar, tgl_daftar, anamnesa, pemeriksaan, diagnosis, terapi, status, id_pasien, id_pelayanan, id_dokter, id_perawat, id_apoteker, hasil_lab) VALUES (r.id_daftar, r.tgl_daftar, r.anamnesa, r.pemeriksaan, r.diagnosis, r.terapi, r.status, r.id_pasien, r.id_pelayanan, r.id_dokter, r.id_perawat, r.id_apoteker, r.hasil_lab)";
    $data_sinkron = oci_parse($conn_lokal, $query);
    $result = oci_execute($data_sinkron);
    oci_commit($conn_lokal);

    if ($result) {
      $status = "Good Job! Data rekam medis berhasil disinkronisasi.";
    } else {
      $status = "Bad News! Data rekam medis gagal disinkronisasi.";
    }
    oci_close($conn_lokal);
  }
  // resepsionis to apoteker
  if (isset($_POST['submit_dokter'])) {
    $query = "MERGE INTO rekam_medis a USING (SELECT * FROM rekam_medis@to_dokter) d ON (a.id_daftar = d.id_daftar)
              WHEN MATCHED THEN UPDATE SET a.tgl_daftar = d.tgl_daftar, a.anamnesa = d.anamnesa, a.pemeriksaan = d.pemeriksaan, a.diagnosis = d.diagnosis, a.terapi = d.terapi, a.status = d.status, a.id_pasien = d.id_pasien, a.id_pelayanan = d.id_pelayanan, a.id_dokter = d.id_dokter, a.id_perawat = d.id_perawat, a.id_apoteker = d.id_apoteker, a.hasil_lab = d.hasil_lab
              WHEN NOT MATCHED THEN INSERT (id_daftar, tgl_daftar, anamnesa, pemeriksaan, diagnosis, terapi, status, id_pasien, id_pelayanan, id_dokter, id_perawat, id_apoteker, hasil_lab) VALUES (d.id_daftar, d.tgl_daftar, d.anamnesa, d.pemeriksaan, d.diagnosis, d.terapi, d.status, d.id_pasien, d.id_pelayanan, d.id_dokter, d.id_perawat, d.id_apoteker, d.hasil_lab)";
    $data_sinkron = oci_parse($conn_lokal, $query);
    $result = oci_execute($data_sinkron);
    oci_commit($conn_lokal);

    if ($result) {
      $status = "Good Job! Data rekam medis berhasil disinkronisasi.";
    } else {
      $status = "Bad News! Data rekam medis gagal disinkronisasi.";
    }
    oci_close($conn_lokal);
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
          <a class="navbar-brand" href="index.php">UIN SUKA HEALTH CENTER - RESEPSIONIS</a>
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
                <form action="sinkron_rekam.php" method="post">
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
                <form action="sinkron_rekam.php" method="post">
                  <input type="submit" class="btn btn-success btn-sinkron" name="submit_apoteker" value="SINKRONISASI">
                </form>
                <!-- <button type="button" id="pustorep" class="btn btn-primary btn-sinkron" name="button">SINKRONISASI PUSAT KE RESEPSIONIS</button> -->
              </fieldset>
            </div>
          </div>
          <div class="row">
            <!-- resepsionis sinkronisasi dengan pusat -->
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
            <!-- pusat sinkronisasi dengan resepsionis -->
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

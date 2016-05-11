<?php session_start();
  // error_reporting(0);
  include_once 'koneksi/koneksi_lokal.php';
  include_once 'koneksi/koneksi_pusat.php';

  // session login
  if(empty($_SESSION['user'])){
    header("Location: index.php");

    die("Redirecting to: index.php");
  }

  $status = "";
  // dokter to pusat
  if (isset($_POST['submit_pusat'])) {
    $query = "MERGE INTO obat_keluar a USING (SELECT * FROM obat_keluar@to_pusat) p ON (a.id_obat_keluar = p.id_obat_keluar)
              WHEN MATCHED THEN UPDATE SET a.jumlah = p.jumlah, a.tgl_keluar = p.tgl_keluar, a.id_obat = p.id_obat, a.id_daftar = p.id_daftar
              WHEN NOT MATCHED THEN INSERT (id_obat_keluar, jumlah, tgl_keluar, id_obat, id_daftar) VALUES (p.id_obat_keluar, p.jumlah, p.tgl_keluar, p.id_obat, p.id_daftar)";
    $data_sinkron = oci_parse($conn_lokal, $query);
    $result = oci_execute($data_sinkron);
    oci_commit($conn_lokal);

    if ($result) {
      $status = "Good Job! Data obat keluar berhasil disinkronisasi.";
    } else {
      $status = "Bad News! Data obat keluar gagal disinkronisasi.";
    }
    oci_close($conn_lokal);
  }
  // pusat to dokter
  if (isset($_POST['submit_apoteker'])) {
    $query = "MERGE INTO obat_keluar p USING (SELECT * FROM obat_keluar@to_apoteker) a ON (p.id_obat_keluar = a.id_obat_keluar)
              WHEN MATCHED THEN UPDATE SET p.jumlah = a.jumlah, p.tgl_keluar = a.tgl_keluar, p.id_obat = a.id_obat, p.id_daftar = a.id_daftar
              WHEN NOT MATCHED THEN INSERT (id_obat_keluar, jumlah, tgl_keluar, id_obat, id_daftar) VALUES (a.id_obat_keluar, a.jumlah, a.tgl_keluar, a.id_obat, a.id_daftar)";
    $data_sinkron = oci_parse($conn_pusat, $query);
    $result = oci_execute($data_sinkron);
    oci_commit($conn_pusat);

    if ($result) {
      $status = "Good Job! Data obat keluar berhasil disinkronisasi.";
    } else {
      $status = "Bad News! Data obat keluar gagal disinkronisasi.";
    }
    oci_close($conn_pusat);
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

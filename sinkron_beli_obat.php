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
  // $query_lokal = "INSERT INTO beli_obat (id_beli,jumlah,id_obat,nama_pembeli,tgl_beli,telp)
  //                 SELECT id_beli,jumlah,id_obat,nama_pembeli,tgl_beli,telp FROM skripsi_pusat.beli_obat
  //                 ON DUPLICATE KEY UPDATE
  //                 id_beli = values(id_beli),jumlah = values(jumlah),id_obat = values(id_obat),
  //                 nama_pembeli = values(nama_pembeli),tgl_beli = values(tgl_beli),telp = values(telp)";
  // $query_pusat = "INSERT INTO beli_obat (id_beli,jumlah,id_obat,nama_pembeli,tgl_beli,telp)
  //                 SELECT id_beli,jumlah,id_obat,nama_pembeli,tgl_beli,telp FROM skripsi_apoteker.beli_obat
  //                 ON DUPLICATE KEY UPDATE
  //                 id_beli = values(id_beli),jumlah = values(jumlah),id_obat = values(id_obat),
  //                 nama_pembeli = values(nama_pembeli),tgl_beli = values(tgl_beli),telp = values(telp)";

  // dokter to pusat
  if (isset($_POST['submit_pusat'])) {
    $query = "select * from beli_obat";
    $stmt = $mysqli_pusat->query($query);

    while ($row = $stmt->fetch_array(MYSQL_ASSOC)) {
      $mysqli_lokal->query("INSERT INTO beli_obat (id_beli, jumlah, id_obat, nama_pembeli, tgl_beli, telp)
      VALUES ('$row[id_beli]','$row[jumlah]','$row[id_obat]','$row[nama_pembeli]','$row[tgl_beli]','$row[telp]') ON DUPLICATE KEY UPDATE
      id_beli = '$row[id_beli]',jumlah = '$row[jumlah]',id_obat = '$row[id_obat]',
      nama_pembeli = '$row[nama_pembeli]',tgl_beli = '$row[tgl_beli]','$row[telp]'");
    }

    if ($stmt) {
      $status = "Good Job! Data pembelian obat berhasil disinkronisasi.";
    } else {
      $status = "Bad News! Data pembelian obat gagal disinkronisasi.";
    }

  }
  // pusat to dokter
  if (isset($_POST['submit_apoteker'])) {
    $query = "select * from beli_obat";
    $stmt = $mysqli_lokal->query($query);

    while ($row = $stmt->fetch_array(MYSQL_ASSOC)) {
      $mysqli_pusat->query("INSERT INTO beli_obat (id_beli, jumlah, id_obat, nama_pembeli, tgl_beli, telp)
      VALUES ('$row[id_beli]','$row[jumlah]','$row[id_obat]','$row[nama_pembeli]','$row[tgl_beli]','$row[telp]') ON DUPLICATE KEY UPDATE
      id_beli = '$row[id_beli]',jumlah = '$row[jumlah]',id_obat = '$row[id_obat]',
      nama_pembeli = '$row[nama_pembeli]',tgl_beli = '$row[tgl_beli]','$row[telp]'");
    }

    if ($stmt) {
      $status = "Good Job! Data pembelian obat berhasil disinkronisasi.";
    } else {
      $status = "Bad News! Data pembelian obat gagal disinkronisasi.";
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
          <h3>SINKRONISASI DATA PEMBELIAN OBAT APOTEK</h3>
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
                <form action="sinkron_beli_obat.php" method="post">
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
                <form action="sinkron_beli_obat.php" method="post">
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

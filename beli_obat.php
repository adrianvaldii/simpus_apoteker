<?php session_start();
  // error_reporting(0);
  include_once 'koneksi/koneksi_lokal.php';
  include_once 'koneksi/koneksi_pusat.php';
  include_once 'koneksi/koneksi_resepsionis.php';
  include_once 'koneksi/koneksi_dokter.php';

  // timezone
  date_default_timezone_set('Asia/Jakarta');

  // session login
  if(empty($_SESSION['user'])){
    header("Location: index.php");

    die("Redirecting to: index.php");
  }

  $status_obat = "";
  $status_data_apoteker = "";

  if (isset($_POST['submit'])) {
    $nama_pembeli = $_POST['nama_pembeli'];
    $telp = $_POST['telp'];
    $tgl_beli = $_POST['tgl_beli'];
    $number = count($_POST['name']);

    if ($number > 0) {
      if ($status_lokal == "ON" && $status_pusat == "ON") {
        for ($i=0; $i<$number; $i++) {
           if (trim($_POST["name"][$i] != '')) {
              $query_obat_lokal = oci_parse($conn_lokal, "INSERT INTO beli_obat (id_beli, tgl_beli, nama_pembeli, telp, id_obat, jumlah) VALUES (id_beli.nextval, to_date(:tgl_beli, 'YYYY-MM-DD'), :nama_pembeli, :telp, :id_obat, :jumlah)");
              oci_bind_by_name($query_obat_lokal, ":tgl_beli", $tgl_beli);
              oci_bind_by_name($query_obat_lokal, ":nama_pembeli", $nama_pembeli);
              oci_bind_by_name($query_obat_lokal, ":telp", $telp);
              oci_bind_by_name($query_obat_lokal, ":id_obat", $_POST['name'][$i]);
              oci_bind_by_name($query_obat_lokal, ":jumlah", $_POST['jumlah'][$i]);

              oci_execute($query_obat_lokal);
              oci_commit($conn_lokal);

              $query_obat_pusat = oci_parse($conn_pusat, "INSERT INTO beli_obat (id_beli, tgl_beli, nama_pembeli, telp, id_obat, jumlah) VALUES (id_beli.nextval, to_date(:tgl_beli, 'YYYY-MM-DD'), :nama_pembeli, :telp, :id_obat, :jumlah)");
              oci_bind_by_name($query_obat_pusat, ":tgl_beli", $tgl_beli);
              oci_bind_by_name($query_obat_pusat, ":nama_pembeli", $nama_pembeli);
              oci_bind_by_name($query_obat_pusat, ":telp", $telp);
              oci_bind_by_name($query_obat_pusat, ":id_obat", $_POST['name'][$i]);
              oci_bind_by_name($query_obat_pusat, ":jumlah", $_POST['jumlah'][$i]);

              oci_execute($query_obat_pusat);
              oci_commit($conn_pusat);

              $status_obat = "Pembelian Berhasil dilakukan. Data berhasil diinputkan ke server Apoteker dan Pusat!";
           }
        }
      } elseif ($status_lokal == "ON" && $status_pusat == "OFF") {
          for ($i=0; $i<$number; $i++) {
             if (trim($_POST["name"][$i] != '')) {
               $query_obat_lokal = oci_parse($conn_lokal, "INSERT INTO beli_obat (id_beli, tgl_beli, nama_pembeli, telp, id_obat, jumlah) VALUES (id_beli.nextval, to_date(:tgl_beli, 'YYYY-MM-DD'), :nama_pembeli, :telp, :id_obat, :jumlah)");
               oci_bind_by_name($query_obat_lokal, ":tgl_beli", $tgl_beli);
               oci_bind_by_name($query_obat_lokal, ":nama_pembeli", $nama_pembeli);
               oci_bind_by_name($query_obat_lokal, ":telp", $telp);
               oci_bind_by_name($query_obat_lokal, ":id_obat", $_POST['name'][$i]);
               oci_bind_by_name($query_obat_lokal, ":jumlah", $_POST['jumlah'][$i]);

                oci_execute($query_obat_lokal);
                oci_commit($conn_lokal);

                $status_obat = "Pembelian Berhasil dilakukan. Data berhasil diinputkan ke server Apoteker!";
             }
          }
      } elseif ($status_lokal == "OFF" && $status_pusat == "ON") {
          for ($i=0; $i<$number; $i++) {
             if (trim($_POST["name"][$i] != '')) {
               $query_obat_pusat = oci_parse($conn_pusat, "INSERT INTO beli_obat (id_beli, tgl_beli, nama_pembeli, telp, id_obat, jumlah) VALUES (id_beli.nextval, to_date(:tgl_beli, 'YYYY-MM-DD'), :nama_pembeli, :telp, :id_obat, :jumlah)");
               oci_bind_by_name($query_obat_pusat, ":tgl_beli", $tgl_beli);
               oci_bind_by_name($query_obat_pusat, ":nama_pembeli", $nama_pembeli);
               oci_bind_by_name($query_obat_pusat, ":telp", $telp);
               oci_bind_by_name($query_obat_pusat, ":id_obat", $_POST['name'][$i]);
               oci_bind_by_name($query_obat_pusat, ":jumlah", $_POST['jumlah'][$i]);

                oci_execute($query_obat_pusat);
                oci_commit($conn_pusat);

                $status_obat = "Pembelian Berhasil dilakukan. Data berhasil diinputkan ke server Pusat!";
             }
          }
      }
    } else {
      $status_obat = "Gagal menambahkan data!";
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
        <div class="col-md-10 content main_baru">
          <h3>INPUT OBAT PASIEN</h3>
          <div class="clear"></div>
          <hr>
          <div class="row">
            <div class="col-md-12">
              <?php
                // menampilkan status
                if (isset($_POST['submit'])) {
                  ?><div class="alert alert-info alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo $status_obat; ?>
                  </div><?php
                }
              ?>
              <form action="beli_obat.php" method="post" autocomplete="off">
              <div class="row">
                <!-- form kiri -->
                <div class="col-md-8">
                  <div class="kotak">
                    <div class="form-group">
                      <label>Nama Pembeli</label>
                      <input type="text" name="nama_pembeli" class="form-control">
                      <small class="detail-form">Nama Pembeli. Harus diisi!</small>
                    </div>
                    <div id="dynamic_field">
                      <label>Detail Obat</label>
                      <div class="form-group">
                        <div class="row">
                          <div class="col-md-8">
                            <input type="text" name="name[]" id="id_obat" placeholder="Nama obat" class="form-control name_list" />
                          </div>
                          <div class="col-md-2">
                            <input type="text" name="jumlah[]" placeholder="Jumlah" class="form-control name_list" />
                          </div>
                          <div class="col-md-2">
                            <button type="button" name="add" id="add" class="btn btn-success">Tambah</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- end form kiri -->
                <!-- form kanan -->
                <div class="col-md-4">
                  <!-- form dokter -->
                  <div class="kotak">
                    <div class="form-group">
                      <label>Tanggal Beli</label>
                      <input type="date" name="tgl_beli" value="<?php echo date("Y-m-d") ?>" class="form-control" readonly="true">
                    </div>
                    <div class="form-group">
                      <label>Nomor Telepon</label>
                      <input type="text" name="telp" class="form-control">
                      <small class="detail-form">Nomor telepon pembeli. Harus diisi.</small>
                    </div>
                  </div>
                  <!-- button -->
                  <div class="btn-daftar">
                    <div class="form-group">
                      <button type="submit" name="submit" class="btn btn-primary btn-lg btn-block">Beli</button>
                    </div>
                  </div>
                  </div>
                </div>
                <!-- end of form kanan -->
              </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
  <!-- js -->
  <?php
    include 'js.php';
  ?>
</html>
<script>
  $(document).ready(function(){
    // autocomplete id_obat
    $(" .name_list ").autocomplete({
      minLength: 1,
      source: "dataobat.php",
    });

    var i=1;
    $('#add').click(function(){
      i++;
      // append input text
      $('#dynamic_field').append('<div class="form-group" id="baris'+i+'"><div class="row"><div class="col-md-8"><input type="text" name="name[]" id="id_obat" placeholder="Nama obat" class="form-control name_list" /></div><div class="col-md-2"><input type="text" name="jumlah[]" placeholder="Jumlah" class="form-control name_list" /></div><div class="col-md-2"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></div></div></div>');
      // autocomplete for append input
      $(" .name_list ").autocomplete({
        minLength: 1,
        source: "dataobat.php",
      });
    });

    $(document).on('click', '.btn_remove', function(){
      var button_id = $(this).attr("id");
      $('#baris'+button_id+'').remove();
    });

    //  $('#submit').click(function(){
    //       $.ajax({
    //            url:"name.php",
    //            method:"POST",
    //            data:$('#add_name').serialize(),
    //            success:function(data)
    //            {
    //                 alert(data);
    //                 $('#add_name')[0].reset();
    //            }
    //       });
    //  });
  });
</script>

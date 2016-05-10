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
    $id_daftar = $_POST['id_daftar'];
    $id_pasien = $_POST['id_pasien'];
    $id_apoteker = $_POST['daftar_apoteker'];
    $tgl_keluar = $_POST['tgl_keluar'];
    $number = count($_POST['name']);

    // update data rekam_medis dengan apoteker
    $query_lokal = oci_parse($conn_lokal, "MERGE INTO rekam_medis USING dual ON (id_daftar = :id_daftar)
                                            WHEN MATCHED THEN UPDATE SET id_pasien = :id_pasien, id_apoteker = :id_apoteker
                                            WHEN NOT MATCHED THEN INSERT (id_daftar, id_pasien, id_apoteker)
                                            VALUES (:id_daftar, :id_pasien, :id_apoteker)");
    $query_pusat = oci_parse($conn_pusat, "MERGE INTO rekam_medis USING dual ON (id_daftar = :id_daftar)
                                            WHEN MATCHED THEN UPDATE SET id_pasien = :id_pasien, id_apoteker = :id_apoteker
                                            WHEN NOT MATCHED THEN INSERT (id_daftar, id_pasien, id_apoteker)
                                            VALUES (:id_daftar, :id_pasien, :id_apoteker)");
    $query_dokter = oci_parse($conn_dokter, "MERGE INTO rekam_medis USING dual ON (id_daftar = :id_daftar)
                                            WHEN MATCHED THEN UPDATE SET id_pasien = :id_pasien, id_apoteker = :id_apoteker
                                            WHEN NOT MATCHED THEN INSERT (id_daftar, id_pasien, id_apoteker)
                                            VALUES (:id_daftar, :id_pasien, :id_apoteker)");
    $query_resepsionis = oci_parse($conn_resepsionis, "MERGE INTO rekam_medis USING dual ON (id_daftar = :id_daftar)
                                            WHEN MATCHED THEN UPDATE SET id_pasien = :id_pasien, id_apoteker = :id_apoteker
                                            WHEN NOT MATCHED THEN INSERT (id_daftar, id_pasien, id_apoteker)
                                            VALUES (:id_daftar, :id_pasien, :id_apoteker)");

    // -- bind all query rekam_medis --
    // ke lokal database
    oci_bind_by_name($query_lokal, ":id_daftar", $id_daftar);
    oci_bind_by_name($query_lokal, ":id_pasien", $id_pasien);
    oci_bind_by_name($query_lokal, ":id_apoteker", $id_apoteker);
    // ke pusat database
    oci_bind_by_name($query_pusat, ":id_daftar", $id_daftar);
    oci_bind_by_name($query_pusat, ":id_pasien", $id_pasien);
    oci_bind_by_name($query_pusat, ":id_apoteker", $id_apoteker);
    // ke dokter database
    oci_bind_by_name($query_dokter, ":id_daftar", $id_daftar);
    oci_bind_by_name($query_dokter, ":id_pasien", $id_pasien);
    oci_bind_by_name($query_dokter, ":id_apoteker", $id_apoteker);
    // ke resepsionis database
    oci_bind_by_name($query_resepsionis, ":id_daftar", $id_daftar);
    oci_bind_by_name($query_resepsionis, ":id_pasien", $id_pasien);
    oci_bind_by_name($query_resepsionis, ":id_apoteker", $id_apoteker);

    // logika basis data terdistribusi
    if ($status_lokal == "ON" && $status_pusat == "ON" && $status_resepsionis == "ON" && $status_dokter == "ON") {
        // commit ke server lokal
        $result_lokal = oci_execute($query_lokal);
        oci_commit($conn_lokal);
        // commit ke server pusat
        $result_pusat = oci_execute($query_pusat);
        oci_commit($conn_pusat);
        // commit ke server resepsionis
        $result_resepsionis = oci_execute($query_resepsionis);
        oci_commit($conn_resepsionis);
        // commit ke server dokter
        $result_dokter = oci_execute($query_dokter);
        oci_commit($conn_dokter);

        $status_data_apoteker = "Berhasil input data ke server Apoteker, Pusat, Dokter, dan server Resepsionis!";
    } elseif ($status_lokal == "ON" && $status_pusat == "OFF" && $status_resepsionis == "OFF" && $status_dokter == "OFF") {
        // commit ke server lokal
        $result_lokal = oci_execute($query_lokal);
        oci_commit($conn_lokal);

        $status_data_apoteker = "Berhasil input data ke server Apoteker!";

    } elseif ($status_lokal == "OFF" && $status_pusat == "ON" && $status_resepsionis == "OFF" && $status_dokter == "OFF") {
        // commit ke server pusat
        $result_pusat = oci_execute($query_pusat);
        oci_commit($conn_pusat);

        $status_data_apoteker = "Berhasil input data ke server Pusat!";

    } elseif ($status_lokal == "OFF" && $status_pusat == "OFF" && $status_resepsionis == "ON" && $status_dokter == "OFF") {
        // commit ke server resepsionis
        $result_resepsionis = oci_execute($query_resepsionis);
        oci_commit($conn_resepsionis);

        $status_data_apoteker = "Berhasil input data ke server Resepsionis!";

    } elseif ($status_lokal == "OFF" && $status_pusat == "OFF" && $status_resepsionis == "OFF" && $status_dokter == "ON") {
        // commit ke server dokter
        $result_dokter = oci_execute($query_dokter);
        oci_commit($conn_dokter);

        $status_data_apoteker = "Berhasil input data ke server Dokter!";

    } elseif ($status_lokal == "ON" && $status_pusat == "ON" && $status_resepsionis == "OFF" && $status_dokter == "OFF") {
        // commit ke server lokal
        $result_lokal = oci_execute($query_lokal);
        oci_commit($conn_lokal);
        // commit ke server pusat
        $result_pusat = oci_execute($query_pusat);
        oci_commit($conn_pusat);

        $status_data_apoteker = "Berhasil input data ke server Apoteker dan Pusat!";

    } elseif ($status_lokal == "ON" && $status_pusat == "OFF" && $status_resepsionis == "ON" && $status_dokter == "OFF") {
        // commit ke server lokal
        $result_lokal = oci_execute($query_lokal);
        oci_commit($conn_lokal);
        // commit ke server resepsionis
        $result_resepsionis = oci_execute($query_resepsionis);
        oci_commit($conn_resepsionis);

        $status_data_apoteker = "Berhasil input data ke server Apoteker dan Resepsionis!";

    } elseif ($status_lokal == "ON" && $status_pusat == "OFF" && $status_resepsionis == "OFF" && $status_dokter == "ON") {
        // commit ke server lokal
        $result_lokal = oci_execute($query_lokal);
        oci_commit($conn_lokal);
        // commit ke server dokter
        $result_dokter = oci_execute($query_dokter);
        oci_commit($conn_dokter);

        $status_data_apoteker = "Berhasil input data ke server Apoteker dan Dokter!";

    } elseif ($status_lokal == "OFF" && $status_pusat == "ON" && $status_resepsionis == "ON" && $status_dokter == "OFF") {
        // commit ke server pusat
        $result_pusat = oci_execute($query_pusat);
        oci_commit($conn_pusat);
        // commit ke server resepsionis
        $result_resepsionis = oci_execute($query_resepsionis);
        oci_commit($conn_resepsionis);

        $status_data_apoteker = "Berhasil input data ke server Pusat dan Resepsionis!";

    } elseif ($status_lokal == "OFF" && $status_pusat == "ON" && $status_resepsionis == "OFF" && $status_dokter == "ON") {
        // commit ke server pusat
        $result_pusat = oci_execute($query_pusat);
        oci_commit($conn_pusat);
        // commit ke server dokter
        $result_dokter = oci_execute($query_dokter);
        oci_commit($conn_dokter);

        $status_data_apoteker = "Berhasil input data ke server Pusat dan Dokter!";

    } elseif ($status_lokal == "OFF" && $status_pusat == "OFF" && $status_resepsionis == "ON" && $status_dokter == "ON") {
        // commit ke server resepsionis
        $result_resepsionis = oci_execute($query_resepsionis);
        oci_commit($conn_resepsionis);
        // commit ke server dokter
        $result_dokter = oci_execute($query_dokter);
        oci_commit($conn_dokter);

        $status_data_apoteker = "Berhasil input data ke server Resepsionis dan Dokter!";

    } elseif ($status_lokal == "ON" && $status_pusat == "OFF" && $status_resepsionis == "ON" && $status_dokter == "ON") {
        // commit ke server lokal
        $result_lokal = oci_execute($query_lokal);
        oci_commit($conn_lokal);
        // commit ke server resepsionis
        $result_resepsionis = oci_execute($query_resepsionis);
        oci_commit($conn_resepsionis);
        // commit ke server dokter
        $result_dokter = oci_execute($query_dokter);
        oci_commit($conn_dokter);

        $status_data_apoteker = "Berhasil input data ke server Apoteker, Resepsionis, dan Dokter!";

    } elseif ($status_lokal == "ON" && $status_pusat == "ON" && $status_resepsionis == "OFF" && $status_dokter == "ON") {
        // commit ke server lokal
        $result_lokal = oci_execute($query_lokal);
        oci_commit($conn_lokal);
        // commit ke server pusat
        $result_pusat = oci_execute($query_pusat);
        oci_commit($conn_pusat);
        // commit ke server dokter
        $result_dokter = oci_execute($query_dokter);
        oci_commit($conn_dokter);

        $status_data_apoteker = "Berhasil input data ke server Apoteker, Pusat, dan Dokter!";

    } elseif ($status_lokal == "ON" && $status_pusat == "ON" && $status_resepsionis == "ON" && $status_dokter == "OFF") {
        // commit ke server lokal
        $result_lokal = oci_execute($query_lokal);
        oci_commit($conn_lokal);
        // commit ke server pusat
        $result_pusat = oci_execute($query_pusat);
        oci_commit($conn_pusat);
        // commit ke server resepsionis
        $result_resepsionis = oci_execute($query_resepsionis);
        oci_commit($conn_resepsionis);

        $status_data_apoteker = "Berhasil input data ke server Apoteker, Pusat, dan Resepsionis!";

    } elseif ($status_lokal == "OFF" && $status_pusat == "ON" && $status_resepsionis == "ON" && $status_dokter == "ON") {
        // commit ke server pusat
        $result_pusat = oci_execute($query_pusat);
        oci_commit($conn_pusat);
        // commit ke server resepsionis
        $result_resepsionis = oci_execute($query_resepsionis);
        oci_commit($conn_resepsionis);
        // commit ke server dokter
        $result_dokter = oci_execute($query_dokter);
        oci_commit($conn_dokter);

        $status_data_apoteker = "Berhasil input data ke server Pusat, Resepsionis, dan Dokter!";

    }

    // input ke tabel obat_keluar
    include 'input_obat_keluar.php';
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
                    <ul>
                      <li><?php echo $status_obat; ?></li>
                      <li><?php echo $status_data_apoteker; ?></li>
                    </ul>
                  </div><?php
                }
              ?>
              <form action="input_obat.php" method="post" autocomplete="off">
              <div class="row">
                <!-- form kiri -->
                <div class="col-md-8">
                  <div class="kotak">
                    <div class="form-group">
                      <label>No. Pendaftaran</label>
                      <input type="text" name="id_daftar" class="form-control" id="id_daftar">
                      <small class="detail-form">Nomor pendaftaran pasien. Harus diisi.</small>
                    </div>
                    <div class="form-group">
                      <label>ID Pasien</label>
                      <input type="text" name="id_pasien" class="form-control">
                      <small class="detail-form">Nomor pendaftaran pasien. Harus diisi.</small>
                    </div>
                    <div class="form-group">
                      <label>Tanggal Obat Keluar</label>
                      <input type="date" name="tgl_keluar" value="<?php echo date("Y-m-d") ?>" class="form-control" readonly="true">
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
                      <label>Daftar Apoteker</label>
                      <select class="form-control" name="daftar_apoteker">
                        <option>-- Pilih Apoteker --</option>
                        <?php
                          $data_apoteker = "SELECT * FROM apoteker";

                          // logika distribusi
                          if ($status_lokal == "ON" && $status_pusat == "ON") {
                            $apoteker = oci_parse($conn_lokal, $data_apoteker);
                            oci_execute($apoteker);

                            while (($row = oci_fetch_array($apoteker, OCI_BOTH)) != false) {
                              ?><option value="<?php echo $row['ID_APOTEKER']; ?>"><?php echo $row['NAMA_APOTEKER']; ?></option> <?php
                            }
                          } elseif ($status_lokal == "ON" && $status_pusat == "OFF") {
                            $apoteker = oci_parse($conn_lokal, $data_apoteker);
                            oci_execute($apoteker);

                            while (($row = oci_fetch_array($apoteker, OCI_BOTH)) != false) {
                              ?><option value="<?php echo $row['ID_APOTEKER']; ?>"><?php echo $row['NAMA_APOTEKER']; ?></option> <?php
                            }
                          } elseif ($status_lokal == "OFF" && $status_pusat == "ON") {
                            $apoteker = oci_parse($conn_pusat, $data_apoteker);
                            oci_execute($apoteker);

                            while (($row = oci_fetch_array($apoteker, OCI_BOTH)) != false) {
                              ?><option value="<?php echo $row['ID_APOTEKER']; ?>"><?php echo $row['NAMA_APOTEKER']; ?></option> <?php
                            }
                          }

                        ?>
                      </select>
                    </div>
                  </div>
                  <!-- button -->
                  <div class="btn-daftar">
                    <div class="form-group">
                      <button type="submit" name="submit" class="btn btn-primary btn-lg btn-block">Update</button>
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
    // autocomplete id_daftar
    $("#id_daftar").autocomplete({
      minLength:2,
      source: "datapengobatan.php",
      select:function(event, data){
        $('input[name=id_pasien]').val(data.item.id_pasien);
      }
    });
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

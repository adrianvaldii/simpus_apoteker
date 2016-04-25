<?php
  include_once 'koneksi/koneksi_lokal.php';
  include_once 'koneksi/koneksi_pusat.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width= device-width, inital-scale=1">

    <title>Poli Klinik UIN Sunan Kalijaga</title>

    <!-- css -->
    <?php include 'css.php'; ?>
    <link href="assets\images\favicon.png" type="image/x-icon" rel="shortcut icon">
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
                            <input type="text" name="name[]" placeholder="Nama obat" class="form-control name_list" />
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
                      <select class="form-control" name="daftar_dokter">
                        <option>-- Pilih Dokter --</option>
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
                      <button type="submit" name="submit" class="btn btn-primary btn-lg btn-block">Daftar</button>
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
    <!-- js -->
    <?php
      include 'js.php';
    ?>
  </body>
</html>
<script type="text/javascript">
  $(function(){

  });
</script>
<script>
  $(document).ready(function(){
    $("#id_daftar").autocomplete({
      source: "datapengobatan.php",
      minLength:2,
      select:function(event, data){
        $('input[name=id_pasien]').val(data.item.id_pasien);
        $('input[name=tgl_daftar]').val(data.item.tgl_daftar);
        $('input[name=nama_pasien]').val(data.item.nama_pasien);
        $('input[name=umur]').val(data.item.umur);
        $('input[name=gol_darah]').val(data.item.gol_darah);
        $('input[name=nama_pelayanan]').val(data.item.nama_pelayanan);
        $('input[name=nama_perawat]').val(data.item.nama_perawat);
      }
    });

    var i=1;
    $('#add').click(function(){
      i++;
      $('#dynamic_field').append('<div class="form-group" id="baris'+i+'"><div class="row"><div class="col-md-8"><input type="text" name="name[]" placeholder="Nama obat" class="form-control name_list" /></div><div class="col-md-2"><input type="text" name="jumlah[]" placeholder="Jumlah" class="form-control name_list" /></div><div class="col-md-2"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></div></div></div>');
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

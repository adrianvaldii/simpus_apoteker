<?php if ($number > 0) {
  if ($status_lokal == "ON" && $status_pusat == "ON") {
    for ($i=0; $i<$number; $i++) {
       if (trim($_POST["name"][$i] != '')) {
          $query_obat_lokal = oci_parse($conn_lokal, "INSERT INTO obat_keluar (id_obat_keluar, tgl_keluar, id_daftar, id_obat, jumlah) VALUES (id_obat_keluar.nextval, to_date(:tgl_keluar, 'YYYY-MM-DD'), :id_daftar, :id_obat, :jumlah)");
          oci_bind_by_name($query_obat_lokal, ":tgl_keluar", $tgl_keluar);
          oci_bind_by_name($query_obat_lokal, ":id_daftar", $id_daftar);
          oci_bind_by_name($query_obat_lokal, ":id_obat", $_POST['name'][$i]);
          oci_bind_by_name($query_obat_lokal, ":jumlah", $_POST['jumlah'][$i]);

          oci_execute($query_obat_lokal);
          oci_commit($conn_lokal);

          $query_obat_pusat = oci_parse($conn_pusat, "INSERT INTO obat_keluar (id_obat_keluar, tgl_keluar, id_daftar, id_obat, jumlah) VALUES (id_obat_keluar.nextval, to_date(:tgl_keluar, 'YYYY-MM-DD'), :id_daftar, :id_obat, :jumlah)");
          oci_bind_by_name($query_obat_pusat, ":tgl_keluar", $tgl_keluar);
          oci_bind_by_name($query_obat_pusat, ":id_daftar", $id_daftar);
          oci_bind_by_name($query_obat_pusat, ":id_obat", $_POST['name'][$i]);
          oci_bind_by_name($query_obat_pusat, ":jumlah", $_POST['jumlah'][$i]);

          oci_execute($query_obat_pusat);
          oci_commit($conn_pusat);

          $status_obat = "Data obat pasien berhasil diinputkan ke server Apoteker dan Pusat!";
       }
    }
  } elseif ($status_lokal == "ON" && $status_pusat == "OFF") {
      for ($i=0; $i<$number; $i++) {
         if (trim($_POST["name"][$i] != '')) {
            $query_obat_lokal = oci_parse($conn_lokal, "INSERT INTO obat_keluar (id_obat_keluar, tgl_keluar, id_daftar, id_obat, jumlah) VALUES (id_obat_keluar.nextval, to_date(:tgl_keluar, 'YYYY-MM-DD'), :id_daftar, :id_obat, :jumlah)");
            oci_bind_by_name($query_obat_lokal, ":tgl_keluar", $tgl_keluar);
            oci_bind_by_name($query_obat_lokal, ":id_daftar", $id_daftar);
            oci_bind_by_name($query_obat_lokal, ":id_obat", $_POST['name'][$i]);
            oci_bind_by_name($query_obat_lokal, ":jumlah", $_POST['jumlah'][$i]);

            oci_execute($query_obat_lokal);
            oci_commit($conn_lokal);

            $status_obat = "Data obat pasien berhasil diinputkan ke server Apoteker!";
         }
      }
  } elseif ($status_lokal == "OFF" && $status_pusat == "ON") {
      for ($i=0; $i<$number; $i++) {
         if (trim($_POST["name"][$i] != '')) {
            $query_obat_pusat = oci_parse($conn_pusat, "INSERT INTO obat_keluar (id_obat_keluar, tgl_keluar, id_daftar, id_obat, jumlah) VALUES (id_obat_keluar.nextval, to_date(:tgl_keluar, 'YYYY-MM-DD'), :id_daftar, :id_obat, :jumlah)");
            oci_bind_by_name($query_obat_pusat, ":tgl_keluar", $tgl_keluar);
            oci_bind_by_name($query_obat_pusat, ":id_daftar", $id_daftar);
            oci_bind_by_name($query_obat_pusat, ":id_obat", $_POST['name'][$i]);
            oci_bind_by_name($query_obat_pusat, ":jumlah", $_POST['jumlah'][$i]);

            oci_execute($query_obat_pusat);
            oci_commit($conn_pusat);

            $status_obat = "Data obat pasien berhasil diinputkan ke server Pusat!";
         }
      }
  }
} else {
  $status_obat = "Gagal menambahkan data!";
} ?>

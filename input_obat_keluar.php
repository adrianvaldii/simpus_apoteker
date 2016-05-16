<?php

if ($number > 0) {
  if ($stat_mylokal == "ON" && $stat_mypusat == "ON") {
    for ($i=0; $i<$number; $i++) {
      include 'generate_id_obat.php';
       if (trim($_POST["name"][$i] != '')) {
          $query = "INSERT INTO obat_keluar (id_obat_keluar, tgl_keluar, id_daftar, id_obat, jumlah) VALUES ('$id_obat_keluar', STR_TO_DATE('$tgl_keluar', '%Y-%m-%d'), '$id_daftar', '$nama_obat[$i]', '$jumlah[$i]')";
          // input ke lokal server
          $mysqli_lokal->query($query);
          // input ke pusat server
          $mysqli_pusat->query($query);

          $status_obat = "Data obat pasien berhasil diinputkan ke server Apoteker dan Pusat!";
       }
    }
  } elseif ($stat_mylokal == "ON" && $stat_mypusat == "OFF") {
      for ($i=0; $i<$number; $i++) {
        include 'generate_id_obat.php';
         if (trim($_POST["name"][$i] != '')) {
             $query = "INSERT INTO obat_keluar (id_obat_keluar, tgl_keluar, id_daftar, id_obat, jumlah) VALUES ('$id_obat_keluar', STR_TO_DATE('$tgl_keluar', '%Y-%m-%d'), '$id_daftar', '$nama_obat[$i]', '$jumlah[$i]')";
             // input ke lokal server
             $mysqli_lokal->query($query);

             $status_obat = "Data obat pasien berhasil diinputkan ke server Apoteker!";
         }
      }
  } elseif ($stat_mylokal == "OFF" && $stat_mypusat == "ON") {
      for ($i=0; $i<$number; $i++) {
        include 'generate_id_obat.php';
         if (trim($_POST["name"][$i] != '')) {
           $query = "INSERT INTO obat_keluar (id_obat_keluar, tgl_keluar, id_daftar, id_obat, jumlah) VALUES ('$id_obat_keluar', STR_TO_DATE('$tgl_keluar', '%Y-%m-%d'), '$id_daftar', '$nama_obat[$i]', '$jumlah[$i]')";
           // input ke pusat server
           $mysqli_pusat->query($query);

           $status_obat = "Data obat pasien berhasil diinputkan ke server Pusat!";
         }
      }
  }
} else {
  $status_obat = "Gagal menambahkan data!";
} ?>

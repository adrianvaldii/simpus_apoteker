<?php
  error_reporting(0);
  // load connection
  include_once 'koneksi/mysql_lokal.php';
  include_once 'koneksi/mysql_pusat.php';
  // query untuk mencari data maksimal
  $query_data = "SELECT max(id_obat_keluar) FROM obat_keluar";

  if ($stat_mylokal == "ON" && $stat_mypusat == "ON") {
    // mengambil data dari server lokal
    $data_lokal = $mysqli_lokal->query($query_data);
    $cari_lokal = $data_lokal->fetch_array(MYSQLI_NUM);
    // mengambil data dari server pusat
    $data_pusat = $mysqli_pusat->query($query_data);
    $cari_pusat = $data_pusat->fetch_array(MYSQLI_NUM);

      if ($cari_lokal && $cari_pusat) {
        $nilai_max = max($cari_lokal[0], $cari_pusat[0]);
        $id_obat_keluar = $nilai_max + 1;
      } else {
        $id_obat_keluar = "701001";
      }
  } elseif ($stat_mylokal == "ON" && $stat_mypusat == "OFF") {
    // mengambil data dari server lokal
    $data_lokal = $mysqli_lokal->query($query_data);
    $cari_lokal = $data_lokal->fetch_array(MYSQLI_NUM);

      if ($cari_lokal) {
        $id_obat_keluar = $cari_lokal[0] + 1;
      } else {
        $id_obat_keluar = "701001";
      }
  } elseif ($stat_mylokal == "OFF" && $stat_mypusat == "ON") {
    // mengambil data dari server pusat
    $data_pusat = $mysqli_pusat->query($query_data);
    $cari_pusat = $data_pusat->fetch_array(MYSQLI_NUM);

      if ($cari_pusat) {
        $id_obat_keluar = $cari_pusat[0] + 1;
      } else {
        $id_obat_keluar = "701001";
      }
  }

?>

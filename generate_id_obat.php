<?php
  error_reporting(0);
  // load connection
  include_once 'koneksi/koneksi_lokal.php';
  include_once 'koneksi/koneksi_pusat.php';
  // query untuk mencari data maksimal
  $query = "SELECT max(id_obat_keluar) FROM obat_keluar";

  // mengambil data dari server lokal
  $data_lokal = oci_parse($conn_lokal, $query);
  oci_execute($data_lokal);
  $cari_lokal = oci_fetch_array($data_lokal, OCI_BOTH);

  // mengambil data dari server pusat
  $data_pusat = oci_parse($conn_pusat, $query);
  oci_execute($data_pusat);
  $cari_pusat = oci_fetch_array($data_pusat, OCI_BOTH);

  if ($status_lokal == "ON" && $status_pusat == "ON") {
      if ($cari_lokal && $cari_pusat) {
        $nilai_max = max($cari_lokal[0], $cari_pusat[0]);
        $id_obat_keluar = $nilai_max + 1;
      } else {
        $id_obat_keluar = "701001";
      }
  } elseif ($status_lokal == "ON" && $status_pusat == "OFF") {
      if ($cari_lokal) {
        $id_obat_keluar = $cari_lokal[0] + 1;
      } else {
        $id_obat_keluar = "701001";
      }
  } elseif ($status_lokal == "OFF" && $status_pusat == "ON") {
      if ($cari_pusat) {
        $id_obat_keluar = $cari_pusat[0] + 1;
      } else {
        $id_obat_keluar = "701001";
      }
  }
  echo $id_obat_keluar;
?>

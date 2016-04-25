<?php
  error_reporting(0);
  // connect to database lokal
  include_once 'koneksi/koneksi_lokal.php';
  // connect to database pusat
  include_once 'koneksi/koneksi_pusat.php';
  // connect to database resepsionis
  include_once 'koneksi/koneksi_resepsionis.php';
  // connect to database dokter
  include_once 'koneksi/koneksi_dokter.php';

  // sql
  $sql_lokal = "select * from rekam_medis";
  $sql_pusat = "select * from rekam_medis";
  $sql_resepsionis = "select * from rekam_medis";
  $sql_dokter = "select * from rekam_medis";

  // logika basis data terdistribusi id rekam_medis
  if ($status_lokal == "ON") {
      $data_lokal = oci_parse($conn_lokal, $sql_lokal);
      oci_execute($data_lokal);

      while ($data = oci_fetch_array($data_lokal, OCI_BOTH)) {
        $row['value'] = $data['ID_DAFTAR'];
        $row['id_pasien'] = $data['ID_PASIEN'];
        $row_set[] = $row;
      }
      echo json_encode($row_set);
  } elseif ($status_pusat == "ON") {
      $data_pusat = oci_parse($conn_pusat, $sql_pusat);
      oci_execute($data_pusat);

      while ($data = oci_fetch_array($data_pusat, OCI_BOTH)) {
        $row['value'] = $data['ID_DAFTAR'];
        $row['id_pasien'] = $data['ID_PASIEN'];
        $row_set[] = $row;
      }
      echo json_encode($row_set);
  } elseif($status_resepsionis == "ON") {
      $data_resepsionis = oci_parse($conn_resepsionis, $sql_resepsionis);
      oci_execute($data_resepsionis);

      while ($data = oci_fetch_array($data_resepsionis, OCI_BOTH)) {
        $row['value'] = $data['ID_DAFTAR'];
        $row['id_pasien'] = $data['ID_PASIEN'];
        $row_set[] = $row;
      }
      echo json_encode($row_set);
  } elseif ($status_dokter == "ON") {
      $data_dokter = oci_parse($conn_dokter, $sql_dokter);
      oci_execute($data_dokter);

      while ($data = oci_fetch_array($data_dokter, OCI_BOTH)) {
        $row['value'] = $data['ID_DAFTAR'];
        $row['id_pasien'] = $data['ID_PASIEN'];
        $row_set[] = $row;
      }
      echo json_encode($row_set);
  }
?>

<?php
  error_reporting(0);
  // connect to local database
  include_once 'koneksi/koneksi_lokal.php';
  include_once 'koneksi/koneksi_pusat.php';

  $term = trim(strip_tags(strtoupper($_GET['term'])));

  $sql_lokal = "SELECT * FROM obat WHERE id_obat LIKE '".$term."%' AND ROWNUM < 8";
  $sql_pusat = "SELECT * FROM obat WHERE id_obat LIKE '".$term."%' AND ROWNUM < 8";

  // logika basis data terdistribusi
  if ($status_lokal == "ON") {
    $data_lokal = oci_parse($conn_lokal, $sql_lokal);
    oci_execute($data_lokal);

    while ($data = oci_fetch_array($data_lokal, OCI_BOTH)) {
      $row['value'] = htmlentities(stripslashes($data['ID_OBAT']));
      $row['nama_obat'] = htmlentities(stripslashes($data['NAMA_OBAT']));
      $row_set[] = $row;
    }
    echo json_encode($row_set);
  } elseif ($status_pusat == "ON") {
      $data_pusat = oci_parse($conn_pusat, $sql_pusat);
      oci_execute($data_pusat);

      while ($data = oci_fetch_array($data_pusat, OCI_BOTH)) {
        $row['value'] = htmlentities(stripslashes($data['ID_OBAT']));
        $row['nama_obat'] = htmlentities(stripslashes($data['NAMA_OBAT']));
        $row_set[] = $row;
      }
      echo json_encode($row_set);
  }
?>

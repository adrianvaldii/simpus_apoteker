<?php
  error_reporting(0);
  // connect to database lokal
  include_once 'koneksi/mysql_lokal.php';
  // connect to database pusat
  include_once 'koneksi/mysql_pusat.php';
  // connect to database resepsionis
  include_once 'koneksi/mysql_resepsionis.php';
  // connect to database dokter
  include_once 'koneksi/mysql_dokter.php';

  // term
  $term = trim(strip_tags(strtoupper($_GET['term'])));
  // sql
  $query = "select * from rekam_medis WHERE id_daftar LIKE '".$term."%' limit 5";

  // logika basis data terdistribusi id rekam_medis
  if ($stat_mylokal == "ON") {
    $stmt = $mysqli_lokal->query($query);
    while ($data = $stmt->fetch_array(MYSQLI_ASSOC)) {
      $row['value'] = $data['id_daftar'];
      $row['id_pasien'] = $data['id_pasien'];
      $row_set[] = $row;
    }
    echo json_encode($row_set);
  } elseif ($stat_mypusat == "ON") {
    $stmt = $mysqli_pusat->query($query);
    while ($data = $stmt->fetch_array(MYSQLI_ASSOC)) {
      $row['value'] = $data['id_daftar'];
      $row['id_pasien'] = $data['id_pasien'];
      $row_set[] = $row;
    }
    echo json_encode($row_set);
  } elseif ($stat_myresepsionis == "ON") {
    $stmt = $mysqli_resepsionis->query($query);
    while ($data = $stmt->fetch_array(MYSQLI_ASSOC)) {
      $row['value'] = $data['id_daftar'];
      $row['id_pasien'] = $data['id_pasien'];
      $row_set[] = $row;
    }
    echo json_encode($row_set);
  } elseif ($stat_mydokter == "ON") {
    $stmt = $mysqli_dokter->query($query);
    while ($data = $stmt->fetch_array(MYSQLI_ASSOC)) {
      $row['value'] = $data['id_daftar'];
      $row['id_pasien'] = $data['id_pasien'];
      $row_set[] = $row;
    }
    echo json_encode($row_set);
  }
?>

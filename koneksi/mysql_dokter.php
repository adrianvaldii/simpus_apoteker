<?php
  // error_reporting(0);
  $host = "localhost";
  $username = "dokter";
  $password = "dokter";
  $db = "skripsi_dokter";

  $mysqli_dokter = new mysqli($host, $username, $password, $db);

  if ($mysqli_dokter->connect_errno) {
    $stat_mydokter = "OFF";
  } else {
    $stat_mydokter = "ON";
  }
?>

<?php
  // error_reporting(0);
  $host = "localhost";
  $username = "pusat";
  $password = "pusat";
  $db = "skripsi_pusat";

  $mysqli_pusat = new mysqli($host, $username, $password, $db);

  if ($mysqli_pusat->connect_errno) {
    $stat_mypusat = "OFF";
  } else {
    $stat_mypusat = "ON";
  }
?>

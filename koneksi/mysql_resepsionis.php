<?php
  // error_reporting(0);
  $host = "localhost";
  $username = "resepsionis";
  $password = "resepsionis";
  $db = "skripsi_resepsionis";

  $mysqli_resepsionis = new mysqli($host, $username, $password, $db);

  if ($mysqli_resepsionis->connect_errno) {
    $stat_myresepsionis = "OFF";
  } else {
    $stat_myresepsionis = "ON";
  }
?>

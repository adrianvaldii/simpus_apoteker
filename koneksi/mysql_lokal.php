<?php
  // error_reporting(0);
  $host = "localhost";
  $username = "apoteker";
  $password = "apoteker";
  $db = "skripsi_apoteker";

  $mysqli_apoteker = new mysqli($host, $username, $password, $db);

  if ($mysqli_apoteker->connect_errno) {
    $stat_myapoteker = "OFF";
  } else {
    $stat_myapoteker = "ON";
  }
?>

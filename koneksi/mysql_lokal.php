<?php
  // error_reporting(0);
  $host = "localhost";
  $username = "apoteker";
  $password = "apoteker";
  $db = "skripsi_apoteker";

  $mysqli_lokal = new mysqli($host, $username, $password, $db);

  if ($mysqli_lokal->connect_errno) {
    $stat_mylokal = "OFF";
  } else {
    $stat_mylokal = "ON";
  }
?>

<?php

  $username = "pusat";
  $password = "pusat";
  $database = "localhost/XE";

    // koneksi asli
  // $username = "resepsionis";
  // $password = "resepsionis";
  // $database = "192.168.1.3:1521/XE";

  $conn_pusat = oci_connect($username, $password, $database);

  if($conn_pusat){
    $status_pusat = "ON";
  }else{
    $status_pusat = "OFF";
  }
?>

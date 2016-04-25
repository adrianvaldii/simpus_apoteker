<?php

  $username = "dokter";
  $password = "dokter";
  $database = "localhost/XE";

  $conn_dokter = oci_connect($username, $password, $database);

  if($conn_dokter){
    $status_dokter = "ON";
  }else{
    $status_dokter = "OFF";
  }

?>

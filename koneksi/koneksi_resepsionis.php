<?php

  $username = "resepsionis";
  $password = "resepsionis";
  $database = "localhost/XE";

  $conn_resepsionis = oci_connect($username, $password, $database);

  if($conn_resepsionis){
    $status_resepsionis = "ON";
  }else{
    $status_resepsionis = "OFF";
  }

?>

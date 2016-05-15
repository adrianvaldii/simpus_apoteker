<?php

  $username = "pusat";
  $password = "pusat";
  $database = "localhost/XE";

  $conn_pusat = oci_connect($username, $password, $database);

  if($conn_pusat){
    $status_pusat = "ON";
  }else{
    $status_pusat = "OFF";
  }

?>

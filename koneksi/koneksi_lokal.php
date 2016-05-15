<?php

  $username = "apoteker";
  $password = "apoteker";
  $database = "localhost/xe";

  $conn_apoteker = oci_connect($username, $password, $database);

  if($conn_apoteker){
    $status_apoteker = "ON";
  }else{
    $status_apoteker = "OFF";
  }

?>

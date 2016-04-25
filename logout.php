<?php session_start();

  include 'koneksi/koneksi_lokal.php';

  unset($_SESSION['user']);

  session_destroy();

  header("Location: index.php");
  die("Redirecting to: index.php");

?>

<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: ../index.html");
  exit();
}
include '../koneksi.php';

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM peserta WHERE id='$id'");
header("Location: ../dashboard.php");
exit();
?>

<?php
include 'koneksi.php'; // koneksi ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nama = $_POST['nama'];
  $email = $_POST['email'];
  $no_telp = $_POST['no_telp'];
  $motor = $_POST['motor'];
  $kategori = $_POST['kategori'];

  $query = "INSERT INTO peserta (nama, email, no_telp, motor, kategori)
            VALUES ('$nama', '$email', '$no_telp', '$motor', '$kategori')";

  if (mysqli_query($conn, $query)) {
    header("Location: peserta_terdaftar.php"); // redirect ke daftar peserta
    exit;
  } else {
    echo "Gagal menyimpan data: " . mysqli_error($conn);
  }
}
?>

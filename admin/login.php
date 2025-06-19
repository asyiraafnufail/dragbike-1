<?php
session_start();
$username = $_POST['username'];
$password = $_POST['password'];

$koneksi = new mysqli("localhost", "root", "", "db_dragbike");
$result = $koneksi->query("SELECT * FROM admin WHERE username='$username' AND password='$password'");

if ($result->num_rows > 0) {
    $_SESSION['admin'] = $username;
    header("Location: peserta.php"); // Redirect ke halaman CRUD
} else {
    echo "Login gagal!";
}
?>
    
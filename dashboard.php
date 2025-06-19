<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
?>

session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: index.html");
  exit();   
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard Admin</title>
  <link rel="stylesheet" href="style.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h2>Dashboard Admin</h2>
    <p>Selamat datang, <?= $_SESSION['admin']; ?>!</p>
    <a href="admin/logout.php" class="btn btn-danger mb-4">Logout</a>

    <h4>Data Peserta</h4>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Nama</th>
          <th>Email</th>
          <th>No Telp</th>
          <th>Motor</th>
          <th>Kategori</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        include 'koneksi.php';
        $result = mysqli_query($conn, "SELECT * FROM peserta");
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr>
                  <td>{$row['nama']}</td>
                  <td>{$row['email']}</td>
                  <td>{$row['no_telp']}</td>
                  <td>{$row['motor']}</td>
                  <td>{$row['kategori']}</td>
                  <td>
                    <a href='admin/hapus_peserta.php?id={$row['id']}' class='btn btn-sm btn-danger'>Hapus</a>
                  </td>
                </tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>

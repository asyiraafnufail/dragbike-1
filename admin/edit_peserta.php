<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
  header("Location: login.php");
  exit;
}

require_once '../koneksi.php';

if (!isset($_GET['id'])) {
  header("Location: dashboard.php");
  exit;
}

$id = intval($_GET['id']);

// Ambil data peserta
$query = "SELECT * FROM peserta WHERE id = $id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
  echo "Peserta tidak ditemukan.";
  exit;
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = $_POST['nama'];
  $email = $_POST['email'];
  $no_telp = $_POST['no_telp'];
  $motor = $_POST['motor'];
  $kategori = $_POST['kategori'];

  $update = "UPDATE peserta SET 
              nama = '$nama',
              email = '$email',
              no_telp = '$no_telp',
              motor = '$motor',
              kategori = '$kategori'
              WHERE id = $id";
  mysqli_query($conn, $update);

  header("Location: dashboard.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Peserta</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
  <h2>Edit Data Peserta</h2>
  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Nama</label>
      <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($data['email']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">No Telepon</label>
      <input type="text" name="no_telp" class="form-control" value="<?= htmlspecialchars($data['no_telp']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Merk/Model Motor</label>
      <input type="text" name="motor" class="form-control" value="<?= htmlspecialchars($data['motor']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Kategori</label>
      <select name="kategori" class="form-select" required>
        <?php
        $kategori_list = [
          "FFA", "Bracket 6 detik", "Bracket 7 detik",
          "Bracket 8 detik", "Bracket 9 detik", "Bracket 10 detik"
        ];
        foreach ($kategori_list as $kategori) {
          $selected = ($kategori == $data['kategori']) ? "selected" : "";
          echo "<option value=\"$kategori\" $selected>$kategori</option>";
        }
        ?>
      </select>
    </div>
    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
  </form>
</body>
</html>

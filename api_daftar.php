<?php
// api_daftar.php
header('Content-Type: application/json');
require_once 'koneksi.php';

$response = ['success' => false, 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $no_telp = trim($_POST['no_telp'] ?? '');
    $motor = trim($_POST['motor'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');

    if (empty($nama) || empty($email) || empty($no_telp) || empty($motor) || empty($kategori)) {
        $response['message'] = 'Semua field wajib diisi.';
        echo json_encode($response);
        exit;
    }

    try {
        // Jika ada ID, berarti ini adalah proses UPDATE
        if (!empty($id)) {
            $sql = "UPDATE peserta SET nama = ?, email = ?, no_telp = ?, motor = ?, kategori = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nama, $email, $no_telp, $motor, $kategori, $id]);
            $response['message'] = 'Data peserta berhasil diperbarui.';
        } else {
        // Jika tidak ada ID, ini proses INSERT baru
            // Cek duplikasi email
            $stmt = $pdo->prepare("SELECT id FROM peserta WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                 $response['message'] = 'Email sudah terdaftar.';
                 echo json_encode($response);
                 exit;
            }

            $sql = "INSERT INTO peserta (nama, email, no_telp, motor, kategori) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nama, $email, $no_telp, $motor, $kategori]);
            $response['message'] = 'Pendaftaran berhasil.';
        }
        $response['success'] = true;
    } catch (\PDOException $e) {
        http_response_code(500);
        $response['message'] = 'Terjadi kesalahan pada server: ' . $e->getMessage();
    }
}

echo json_encode($response);
?>
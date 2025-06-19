<?php
// api_signup_admin.php
header('Content-Type: application/json');
require_once 'koneksi.php';

$response = ['success' => false, 'message' => 'Gagal membuat akun.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($nama) || empty($username) || empty($password)) {
        $response['message'] = 'Semua field wajib diisi!';
        echo json_encode($response);
        exit;
    }
    
    // Hash password untuk keamanan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO admins (nama, username, password) VALUES (?, ?, ?)");
        $stmt->execute([$nama, $username, $hashed_password]);
        $response = ['success' => true, 'message' => 'Sign up berhasil!'];
    } catch (\PDOException $e) {
        if ($e->getCode() == 23000) { // Kode error untuk duplicate entry
            $response['message'] = 'Username sudah digunakan!';
        } else {
            http_response_code(500);
            $response['message'] = 'Server error: ' . $e->getMessage();
        }
    }
}

echo json_encode($response);
?>
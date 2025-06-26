<?php
// api_login.php
session_start();
header('Content-Type: application/json');
require_once 'koneksi.php';

$response = ['success' => false, 'message' => 'Username atau password salah.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin['username'];
            $response = ['success' => true, 'message' => 'Welcome back, Admin!'];
        }
    } catch (\PDOException $e) {
        http_response_code(500);
        $response['message'] = 'Server error: ' . $e->getMessage();
    }
}

echo json_encode($response);
?>
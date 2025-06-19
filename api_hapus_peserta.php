<?php
// api_hapus_peserta.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak. Silakan login.']);
    exit;
}

require_once 'koneksi.php';
$response = ['success' => false, 'message' => 'ID tidak ditemukan.'];

if (isset($_POST['id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM peserta WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        $response = ['success' => true, 'message' => 'Peserta berhasil dihapus.'];
    } catch (\PDOException $e) {
        http_response_code(500);
        $response['message'] = 'Gagal menghapus data: ' . $e->getMessage();
    }
}
echo json_encode($response);
?>
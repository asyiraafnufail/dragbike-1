<?php
// api_get_peserta.php
header('Content-Type: application/json');
require_once 'koneksi.php';

try {
    // Mengambil semua data peserta dari database
    $stmt = $pdo->prepare("SELECT * FROM peserta ORDER BY id DESC");
    $stmt->execute();
    $peserta = $stmt->fetchAll();
    
    // Mengembalikan data dalam format JSON
    echo json_encode($peserta);
} catch (\PDOException $e) {
    // Jika terjadi error, kembalikan array kosong
    http_response_code(500);
    echo json_encode([]);
}
?>
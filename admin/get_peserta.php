<?php
session_start();
if (!isset($_SESSION['admin'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

include '../koneksi.php';
$query = "SELECT * FROM peserta";
$result = mysqli_query($conn, $query);

$peserta = [];
while ($row = mysqli_fetch_assoc($result)) {
    $peserta[] = $row;
}

echo json_encode($peserta);
?>

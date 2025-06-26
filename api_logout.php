<?php
// api_logout.php
session_start();
header('Content-Type: application/json');

// Hapus semua session data
$_SESSION = array();

// Hapus session cookie jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy session
session_destroy();

echo json_encode(['success' => true, 'message' => 'Logout berhasil']);
?>
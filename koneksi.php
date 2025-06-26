<?php
/**
 * File: koneksi.php
 * Deskripsi: Menghubungkan aplikasi ke database menggunakan PDO (lebih aman dan modern).
 */

// 1. Kredensial Database
$host = 'localhost';        // Server database, biasanya 'localhost'
$dbname = 'db_dragbike';    // Nama database yang sudah Anda buat di phpMyAdmin
$user = 'root';             // Username database, default XAMPP adalah 'root'
$pass = '';                 // Password database, default XAMPP adalah kosong

// 2. Konfigurasi DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

// 3. Opsi untuk koneksi PDO
$options = [
    // Tampilkan error sebagai exceptions, lebih mudah ditangani
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    // Atur mode fetch default menjadi associative array (['nama_kolom' => 'nilai'])
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // Nonaktifkan emulasi prepared statements untuk keamanan
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// 4. Membuat Koneksi Database
try {
    // Mencoba membuat objek koneksi PDO
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Jika koneksi gagal, hentikan eksekusi skrip dan tampilkan pesan error yang jelas
    // Di lingkungan produksi (online), sebaiknya error ini dicatat di log, bukan ditampilkan ke pengguna.
    die("Koneksi ke database gagal: " . $e->getMessage());
}

// Jika berhasil, variabel $pdo sekarang siap digunakan di file lain yang menyertakan file ini.
?>
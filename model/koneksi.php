<?php
// Konfigurasi Database (Sesuai data Railway Anda)
$host = 'yamanote.proxy.rlwy.net';
$user = 'root';
$pass = 'xrtsPyGvLEmDgErJSiaZxOBfptKbFSxy';
$db   = 'railway';
$port = '13629'; // Port sangat penting di Railway karena bukan port default 3306

// Data Source Name (DSN)
// Format: mysql:host=...;port=...;dbname=...;charset=...
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Menampilkan error jika ada masalah
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Hasil query menjadi array asosiatif
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Mencoba membuat koneksi
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    
} catch (\PDOException $e) {
   
    die("Koneksi Gagal: " . $e->getMessage()); 
}
?>
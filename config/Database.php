<?php
class Database {
    // Sesuaikan kredensial Railway Anda
    private $host = '1j8pl4.h.filess.io';
    private $user = 'perhotelan_islandsame';
    private $pass = 'f233af60e12348708637ab4f7cd2b8b8bdde631c';
    private $db   = 'perhotelan_islandsame';
    private $port = '61002';
    public $conn;

    public function __construct() {
        $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db};charset=utf8mb4";
        try {
            $this->conn = new PDO($dsn, $this->user, $this->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("Koneksi Database Gagal: " . $e->getMessage());
        }
    }

    public function query($sql) { return $this->conn->query($sql); }
    public function prepare($sql) { return $this->conn->prepare($sql); }
    public function lastInsertId() { return $this->conn->lastInsertId(); }
}
?>
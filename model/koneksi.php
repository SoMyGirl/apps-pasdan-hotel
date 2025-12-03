<?php
<<<<<<< HEAD
class Database {
    // Konfigurasi Railway Anda
    private $host = 'yamanote.proxy.rlwy.net';
    private $user = 'root';
    private $pass = 'xrtsPyGvLEmDgErJSiaZxOBfptKbFSxy';
    private $db   = 'railway';
    private $port = '13629'; // Port Railway
    
    public $conn;

    public function __construct() {
        // Data Source Name (DSN) untuk PDO
        $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db};charset=utf8mb4";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Error mode
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,      // Return array asosiatif
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->conn = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (\PDOException $e) {
            // Tampilkan error jika koneksi gagal (Untuk debugging)
            die("Koneksi Database Gagal: " . $e->getMessage()); 
        }
    }

    // ----------------------------------------------------------------
    // FUNGSI WRAPPER (Agar Controller tidak perlu diubah)
    // ----------------------------------------------------------------

    // 1. ESCAPE STRING (Pengganti real_escape_string di PDO)
    public function escape($data) {
        if ($data === null) return '';
        // PDO quote() mengembalikan string dengan tanda kutip (contoh: 'budi')
        // Karena logika controller kita menambahkan kutip manual, kita buang kutip dari PDO
        $quoted = $this->conn->quote($data);
        return substr($quoted, 1, -1); 
    }

    // 2. TAMPIL DATA (READ)
    public function tampil($tabel, $where = null, $order = null) {
        $sql = "SELECT * FROM $tabel";
        if ($where != null) $sql .= " WHERE $where";
        if ($order != null) $sql .= " ORDER BY $order";
        
        try {
            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(); // Mengembalikan semua baris
        } catch (PDOException $e) {
            die("Error Tampil: " . $e->getMessage());
        }
    }

    // 3. TAMBAH DATA (CREATE)
    public function tambah($tabel, $data) {
        // Susun kolom
        $columns = implode(", ", array_keys($data));
        
        // Susun values (dengan escape manual via fungsi di atas)
        $escaped_values = array_map([$this, 'escape'], array_values($data));
        $values  = "'" . implode("', '", $escaped_values) . "'";

        $sql = "INSERT INTO $tabel ($columns) VALUES ($values)";
        
        try {
            $this->conn->exec($sql);
            return $this->conn->lastInsertId(); // Ambil ID terakhir
        } catch (PDOException $e) {
            die("Error Tambah: " . $e->getMessage());
        }
    }

    // 4. UBAH DATA (UPDATE)
    public function ubah($tabel, $data, $where) {
        $cols = [];
        foreach ($data as $key => $val) {
            $val = $this->escape($val);
            $cols[] = "$key = '$val'";
        }
        $sql = "UPDATE $tabel SET " . implode(', ', $cols) . " WHERE $where";
        
        try {
            return $this->conn->exec($sql);
        } catch (PDOException $e) {
            die("Error Ubah: " . $e->getMessage());
        }
    }

    // 5. HAPUS DATA (DELETE)
    public function hapus($tabel, $where) {
        $sql = "DELETE FROM $tabel WHERE $where";
        try {
            return $this->conn->exec($sql);
        } catch (PDOException $e) {
            die("Error Hapus: " . $e->getMessage());
        }
    }
    
    // 6. CUSTOM QUERY (Untuk Report / Join)
    public function query($sql) {
        try {
            return $this->conn->query($sql);
        } catch (PDOException $e) {
            die("Error Query: " . $e->getMessage() . " | SQL: " . $sql);
        }
    }

}
?>
<?php
include_once 'model/koneksi.php';

class C_Dashboard {
    private $db;
    
    public function __construct() { 
        $this->db = new Database(); 
    }

    public function getStats() {
        // Query manual pakai count biar cepat
        // Kita gunakan try-catch agar jika tabel belum ada, tidak error fatal
        try {
            $sql = "SELECT 
                (SELECT COUNT(*) FROM kamar) as total,
                (SELECT COUNT(*) FROM kamar WHERE status='available') as kosong,
                (SELECT COUNT(*) FROM kamar WHERE status='occupied') as isi,
                (SELECT COUNT(*) FROM kamar WHERE status='dirty') as kotor
            ";
            
            // Jika Anda menggunakan koneksi PDO yang baru (Railway)
            $stmt = $this->db->conn->query($sql);
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            // Data dummy jika database kosong/error
            return ['total'=>0, 'kosong'=>0, 'isi'=>0, 'kotor'=>0];
        }
    }
}
?>
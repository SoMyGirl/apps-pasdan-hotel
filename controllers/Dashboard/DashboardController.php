<?php
include_once 'model/koneksi.php';

class C_Dashboard {
    private $db;
    
    public function __construct() { 
        $this->db = new Database(); 
    }

    public function getStats() {
        try {
            // Query hitung manual
            $sql = "SELECT 
                (SELECT COUNT(*) FROM kamar) as total,
                (SELECT COUNT(*) FROM kamar WHERE status='available') as kosong,
                (SELECT COUNT(*) FROM kamar WHERE status='occupied') as isi,
                (SELECT COUNT(*) FROM kamar WHERE status='dirty') as kotor
            ";
            
            // Karena ini custom query, kita ambil stmt-nya
            $stmt = $this->db->query($sql);
            return $stmt->fetch(PDO::FETCH_ASSOC); // Ambil 1 baris hasil

        } catch (Exception $e) {
            // Return 0 semua jika error/tabel kosong
            return ['total'=>0, 'kosong'=>0, 'isi'=>0, 'kotor'=>0];
        }
    }
}
?>
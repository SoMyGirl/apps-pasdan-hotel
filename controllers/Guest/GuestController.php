<?php

class GuestController {
    private $db;
    
    public function __construct() { 
        $this->db = new Database(); 
    }

    // Tambahkan method ini di dalam GuestController class Anda
public function history() {
    // 1. Ambil Filter dari URL (Opsional)
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
    
    // 2. Query Dasar
    $sql = "SELECT t.*, k.nomor_kamar 
            FROM transaksi t 
            JOIN kamar k ON t.id_kamar = k.id_kamar 
            WHERE 1=1"; // Dummy WHERE agar mudah append string

    // 3. Append Logic Filter
    if ($filter == 'finished') {
        $sql .= " AND status_transaksi = 'finished'";
    } elseif ($filter == 'active') {
        $sql .= " AND status_transaksi = 'active'";
    }
    
    $sql .= " ORDER BY tgl_checkin DESC";
    
    // 4. Eksekusi
    $tamu = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    
    // 5. Kirim data ke View
    $this->view('Guest/history', ['tamu' => $tamu, 'filter' => $filter]);
}
    public function index() {
        // Tamu In-House (Sedang Menginap)
        // Update: Join Tipe Kamar untuk info lebih lengkap
        $sql = "SELECT t.*, k.nomor_kamar, tk.nama_tipe 
                FROM transaksi t 
                JOIN kamar k ON t.id_kamar = k.id_kamar 
                JOIN tipe_kamar tk ON k.id_tipe = tk.id_tipe
                WHERE t.status_transaksi = 'active' 
                ORDER BY t.tgl_checkin DESC";
        
        $tamu = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        
        $this->view('Guest/inhouse', ['tamu' => $tamu]);
    }

    private function view($p, $d) { 
        extract($d); 
        include 'views/Layout/header.php'; 
        include 'views/Layout/sidebar.php'; 
        include "views/$p.php"; 
        include 'views/Layout/footer.php'; 
    }
}
?>
<?php

class GuestController {
    private $db;
    
    public function __construct() { 
        $this->db = new Database(); 
    }

    public function index() {
        // Cek Login
        if (!isset($_SESSION['status_login'])) {
            header("Location: index.php?modul=Auth&aksi=login");
            exit;
        }

        // QUERY OPTIMAL: 
        // Mengambil data tamu + menjumlahkan riwayat pembayaran mereka (total_terbayar)
        // Menggunakan COALESCE agar jika belum bayar hasilnya 0, bukan NULL
        $sql = "SELECT t.*, k.nomor_kamar, tk.nama_tipe, 
                       COALESCE(SUM(rp.jumlah_bayar), 0) as total_terbayar
                FROM transaksi t 
                JOIN kamar k ON t.id_kamar = k.id_kamar 
                JOIN tipe_kamar tk ON k.id_tipe = tk.id_tipe
                LEFT JOIN riwayat_pembayaran rp ON t.id_transaksi = rp.id_transaksi
                WHERE t.status_transaksi = 'active' 
                GROUP BY t.id_transaksi
                ORDER BY t.tgl_checkin DESC";
        
        $tamu = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        
        $this->view('Guest/inhouse', ['tamu' => $tamu]);
    }

    // Method history (Tetap sama, tidak diubah)
    public function history() {
        $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $sql = "SELECT t.*, k.nomor_kamar 
                FROM transaksi t 
                JOIN kamar k ON t.id_kamar = k.id_kamar 
                WHERE (status_transaksi = 'finished' OR status_transaksi = 'active') 
                AND YEAR(tgl_checkin) = '$tahun'
                ORDER BY tgl_checkin DESC LIMIT $limit OFFSET $offset";
        
        $tamu = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        
        $totalRow = $this->db->query("SELECT COUNT(*) as tot FROM transaksi WHERE YEAR(tgl_checkin) = '$tahun'")->fetch()['tot'];
        $totalPages = ceil($totalRow / $limit);

        $this->view('Guest/history', [
            'tamu' => $tamu, 
            'tahun' => $tahun, 
            'page' => $page, 
            'totalPages' => $totalPages
        ]);
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
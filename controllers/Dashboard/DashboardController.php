<?php
class DashboardController {
    private $db;
    public function __construct() { $this->db = new Database(); }

    public function index() {
        if (!isset($_SESSION['status_login'])) header("Location: index.php?modul=Auth&aksi=login");
        
        // Logic Room Rack
        $sql = "SELECT k.*, t.nama_tipe, tr.id_transaksi, tr.nama_tamu 
                FROM kamar k
                JOIN tipe_kamar t ON k.id_tipe = t.id_tipe
                LEFT JOIN transaksi tr ON k.id_kamar = tr.id_kamar AND tr.status_transaksi = 'active'
                ORDER BY k.nomor_kamar ASC";
        $rooms = $this->db->query($sql)->fetchAll();

        // Load View
        $this->view('Dashboard/index', ['rooms' => $rooms]);
    }

    // Aksi Cepat: Bersihkan Kamar
    public function clean() {
        $id = $_GET['id'];
        $this->db->query("UPDATE kamar SET status='available' WHERE id_kamar=$id");
        $_SESSION['flash_type'] = 'success';
        $_SESSION['flash_message'] = 'Kamar berhasil dibersihkan!';
        header("Location: index.php?modul=Dashboard&aksi=index");
    }

    private function view($p, $d=[]) { extract($d); include 'views/Layout/header.php'; include 'views/Layout/sidebar.php'; include "views/$p.php"; include 'views/Layout/footer.php'; }
}
?>
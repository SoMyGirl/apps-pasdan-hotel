<?php
class GuestController {
    private $db;
    public function __construct() { $this->db = new Database(); }

    public function index() {
        // Tamu In-House (Sedang Menginap)
        $sql = "SELECT t.*, k.nomor_kamar FROM transaksi t JOIN kamar k USING(id_kamar) WHERE status_transaksi = 'active' ORDER BY tgl_checkin DESC";
        $tamu = $this->db->query($sql)->fetchAll();
        $this->view('Guest/inhouse', ['tamu' => $tamu]);
    }

    private function view($p, $d) { extract($d); include 'views/Layout/header.php'; include 'views/Layout/sidebar.php'; include "views/$p.php"; include 'views/Layout/footer.php'; }
}
?>
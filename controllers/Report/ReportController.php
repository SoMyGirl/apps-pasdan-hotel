<?php
class ReportController {
    private $db;
    public function __construct() { $this->db = new Database(); }

    public function index() {
        // Total Pendapatan
        $total = $this->db->query("SELECT SUM(total_tagihan) as tot FROM transaksi WHERE status_bayar='lunas'")->fetch()['tot'] ?? 0;
        
        // Data Per Bulan
        $bulanan = $this->db->query("SELECT DATE_FORMAT(tgl_checkout, '%Y-%m') as periode, COUNT(*) as tamu, SUM(total_tagihan) as omset FROM transaksi WHERE status_bayar='lunas' GROUP BY periode ORDER BY periode DESC")->fetchAll();

        $this->view('Report/index', compact('total', 'bulanan'));
    }

    private function view($p, $d) { extract($d); include 'views/Layout/header.php'; include 'views/Layout/sidebar.php'; include "views/$p.php"; include 'views/Layout/footer.php'; }
}
?>
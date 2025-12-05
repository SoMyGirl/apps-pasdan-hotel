<?php

class ReportController {
    private $db;
    
    public function __construct() { 
        $this->db = new Database(); 
    }

    public function index() {
        // Security Check
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?modul=Dashboard&aksi=index");
            exit;
        }

        // 1. STATISTIK UTAMA (All Time)
        // Kita ambil Total Omset, Jumlah Transaksi Lunas, dan Rata-rata per Transaksi
        $sqlStats = "SELECT 
                        SUM(total_tagihan) as total_omset,
                        COUNT(*) as total_transaksi,
                        AVG(total_tagihan) as rata_rata
                     FROM transaksi 
                     WHERE status_bayar = 'lunas'";
        
        $stats = $this->db->query($sqlStats)->fetch(PDO::FETCH_ASSOC);

        // 2. DATA BULANAN (12 Bulan Terakhir)
        $sqlBulan = "SELECT DATE_FORMAT(tgl_checkout, '%Y-%m') as periode, 
                            COUNT(*) as jum_tamu, 
                            SUM(total_tagihan) as omset 
                     FROM transaksi 
                     WHERE status_bayar='lunas' 
                     GROUP BY periode 
                     ORDER BY periode DESC 
                     LIMIT 12";
        
        $bulanan = $this->db->query($sqlBulan)->fetchAll(PDO::FETCH_ASSOC);

        // 3. Logic Max Value untuk Grafik Batang Sederhana
        $maxOmset = 0;
        foreach($bulanan as $b) {
            if($b['omset'] > $maxOmset) $maxOmset = $b['omset'];
        }

        $this->view('Report/index', [
            'stats' => $stats, 
            'bulanan' => $bulanan,
            'maxOmset' => $maxOmset
        ]);
    }

    private function view($p, $d=[]) { 
        extract($d); 
        include 'views/Layout/header.php'; 
        include 'views/Layout/sidebar.php'; 
        include "views/$p.php"; 
        include 'views/Layout/footer.php'; 
    }
}
?>
<?php

class ReportController {
    private $db;
    
    public function __construct() { 
        $this->db = new Database(); 
    }

    public function index() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?modul=Dashboard&aksi=index"); exit;
        }

        $tahun = $_GET['year'] ?? date('Y');
        $detailBulan = $_GET['detail_bulan'] ?? null;

        // 1. STATISTIK GLOBAL
        $sqlStats = "SELECT SUM(total_tagihan) as total_omset, COUNT(*) as total_transaksi, AVG(total_tagihan) as rata_rata 
                     FROM transaksi 
                     WHERE status_bayar = 'lunas' AND YEAR(tgl_checkout) = '$tahun'";
        $stats = $this->db->query($sqlStats)->fetch(PDO::FETCH_ASSOC);

        // 2. GRAFIK BULANAN
        $sqlBulan = "SELECT * FROM v_laporan_bulanan 
                     WHERE tahun = '$tahun' 
                     ORDER BY periode_bulan DESC";
        $bulanan = $this->db->query($sqlBulan)->fetchAll(PDO::FETCH_ASSOC);

        $maxOmset = 0;
        foreach($bulanan as $b) {
            if($b['total_omset'] > $maxOmset) $maxOmset = $b['total_omset'];
        }

        // 3. DATA DETAIL TRANSAKSI
        $detailData = [];
        if ($detailBulan) {
            // Mengambil detail berdasarkan bulan checkout
            $sqlDetail = "SELECT * FROM v_invoice_list 
                          WHERE status_bayar = 'lunas' 
                          AND DATE_FORMAT(tgl_checkout, '%Y-%m') = '$detailBulan'
                          ORDER BY tgl_checkin DESC";
            $detailData = $this->db->query($sqlDetail)->fetchAll(PDO::FETCH_ASSOC);
        }

        $this->view('Report/index', [
            'stats' => $stats, 
            'bulanan' => $bulanan,
            'maxOmset' => $maxOmset,
            'selectedYear' => $tahun,
            'selectedMonth' => $detailBulan,
            'detailData' => $detailData
        ]);
    }

    // --- FUNGSI EXPORT EXCEL ---
    public function export() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') exit;

        $tahun = $_GET['year'] ?? date('Y');
        $bulan = $_GET['bulan'] ?? '';

        // Header agar browser download file Excel
        $filename = "Laporan_Keuangan_" . ($bulan ? $bulan : $tahun) . ".xls";
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        // Query Data (Pastikan filter menggunakan tgl_checkout yang sudah ada di View)
        $where = "WHERE status_bayar = 'lunas' AND status_transaksi = 'finished' AND YEAR(tgl_checkout) = '$tahun'";
        if($bulan) {
            $where .= " AND DATE_FORMAT(tgl_checkout, '%Y-%m') = '$bulan'";
        }
        
        $sql = "SELECT * FROM v_invoice_list $where ORDER BY tgl_checkin DESC";
        $data = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        // Output Table HTML (Excel bisa membaca ini)
        echo "<h3>LAPORAN KEUANGAN HOTEL SMK</h3>";
        echo "<p>Periode: " . ($bulan ? $bulan : "Tahun $tahun") . "</p>";
        echo "<table border='1'>";
        echo "<thead>
                <tr style='background-color:#f0f0f0;'>
                    <th>No Invoice</th>
                    <th>Tgl Check-in</th>
                    <th>Tgl Checkout</th>
                    <th>Nama Tamu</th>
                    <th>Kamar</th>
                    <th>Total Tagihan</th>
                    <th>Status</th>
                </tr>
              </thead>";
        echo "<tbody>";
        
        $total = 0;
        foreach($data as $d) {
            echo "<tr>";
            echo "<td>" . $d['no_invoice'] . "</td>";
            echo "<td>" . $d['tgl_checkin'] . "</td>";
            echo "<td>" . $d['tgl_checkout'] . "</td>"; // Kolom baru dari view
            echo "<td>" . $d['nama_tamu'] . "</td>";
            // PERBAIKAN: Gunakan 'nomor_kamar' (bukan list_kamar) sesuai View terbaru
            echo "<td>" . $d['nomor_kamar'] . "</td>"; 
            echo "<td>" . $d['total_tagihan'] . "</td>";
            echo "<td>" . strtoupper($d['status_bayar']) . "</td>";
            echo "</tr>";
            $total += $d['total_tagihan'];
        }

        echo "<tr>
                <td colspan='5' align='right'><strong>GRAND TOTAL</strong></td>
                <td><strong>" . $total . "</strong></td>
                <td></td>
              </tr>";
        echo "</tbody></table>";
        exit;
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
<?php

class DashboardController {
    private $db;
    
    public function __construct() { 
        $this->db = new Database(); 
    }

    public function index() {
        // 1. Cek Login
        if (!isset($_SESSION['status_login'])) {
            header("Location: index.php?modul=Auth&aksi=login");
            exit;
        }

        // 2. LOGIKA FILTER
        // Tangkap pilihan tipe kamar dari URL (jika ada)
        $tipeFilter = isset($_GET['tipe']) ? $_GET['tipe'] : '';
        
        // Siapkan bagian WHERE untuk query SQL
        $sqlWhere = "";
        if (!empty($tipeFilter)) {
            $sqlWhere = " WHERE k.id_tipe = '$tipeFilter' ";
        }

        // 3. AMBIL DATA KAMAR (Dengan Filter)
        // Kita gunakan LEFT JOIN agar bisa mengambil data tamu yang sedang aktif
        try {
            $sql = "SELECT k.*, t.nama_tipe, tr.id_transaksi, tr.nama_tamu, tr.tgl_checkin, tr.tgl_checkout 
                    FROM kamar k
                    JOIN tipe_kamar t ON k.id_tipe = t.id_tipe
                    LEFT JOIN transaksi tr ON k.id_kamar = tr.id_kamar AND tr.status_transaksi = 'active'
                    $sqlWhere
                    ORDER BY k.nomor_kamar ASC";
            
            $rooms = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $rooms = [];
        }

        // 4. AMBIL DATA TIPE KAMAR (Untuk Dropdown Filter)
        $listTipe = $this->db->query("SELECT * FROM tipe_kamar ORDER BY harga_dasar ASC")->fetchAll(PDO::FETCH_ASSOC);

        // 5. HITUNG STATISTIK (Tetap hitung dari total kamar, atau menyesuaikan filter)
        // Disini kita hitung berdasarkan data yang TAMPIL ($rooms) agar sinkron dengan grid
        $stats = [
            'total'     => count($rooms),
            'available' => 0,
            'occupied'  => 0,
            'dirty'     => 0,
            'occupancy' => 0
        ];

        foreach($rooms as $r) {
            if ($r['status'] == 'available') $stats['available']++;
            elseif ($r['status'] == 'occupied') $stats['occupied']++;
            elseif ($r['status'] == 'dirty') $stats['dirty']++;
        }

        if ($stats['total'] > 0) {
            $stats['occupancy'] = round(($stats['occupied'] / $stats['total']) * 100);
        }

        // 6. KIRIM KE VIEW
        $payload = [
            'stats' => $stats,
            'rooms' => $rooms,
            'listTipe' => $listTipe,      // Data untuk dropdown
            'selectedTipe' => $tipeFilter, // Tipe yang sedang dipilih
            'judul' => 'Dashboard Manager'
        ];

        $this->view('Dashboard/index', ['data' => $payload]);
    }

    // Aksi Bersihkan Kamar (Tetap sama)
    public function clean() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->db->query("UPDATE kamar SET status='available' WHERE id_kamar=$id");
            
            if(isset($_SESSION)) {
                $_SESSION['flash_type'] = 'success';
                $_SESSION['flash_message'] = 'Kamar berhasil dibersihkan!';
            }
        }
        header("Location: index.php?modul=Dashboard&aksi=index");
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
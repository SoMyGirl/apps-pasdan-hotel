<?php
class DashboardController {
    private $db;
    
    public function __construct() { 
        $this->db = new Database(); 
    }
    
    public function index() {
        if (!isset($_SESSION['status_login'])) { 
            header("Location: index.php?modul=Auth&aksi=login"); 
            exit; 
        }
        
        // 1. Filter Tipe Kamar
        $tipe = $_GET['tipe'] ?? '';
        $whr = "";
        $params = [];

        if (!empty($tipe)) {
            $whr = "AND k.id_tipe = :tipe";
            $params['tipe'] = $tipe;
        }
        
        // 2. Query Data Kamar (DIPERBAIKI: Tambah GROUP BY)
        // GROUP BY k.id_kamar = Mencegah duplikasi kamar meskipun banyak riwayat di transaksi_kamar
        $sql = "SELECT k.*, t.nama_tipe, 
                       MAX(tr.id_transaksi) as id_transaksi, 
                       MAX(tr.nama_tamu) as nama_tamu 
                FROM kamar k 
                JOIN tipe_kamar t ON k.id_tipe = t.id_tipe
                LEFT JOIN transaksi_kamar tk ON k.id_kamar = tk.id_kamar
                LEFT JOIN transaksi tr ON tk.id_transaksi = tr.id_transaksi AND tr.status_transaksi = 'active'
                WHERE 1=1 $whr 
                GROUP BY k.id_kamar 
                ORDER BY k.nomor_kamar ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 3. Hitung Statistik
        $stats = ['total'=>count($rooms), 'available'=>0, 'occupied'=>0, 'dirty'=>0, 'occupancy'=>0];
        foreach($rooms as $r) {
            // Pastikan key status ada untuk menghindari error undefined index
            if(isset($stats[$r['status']])) {
                $stats[$r['status']]++;
            }
        }
        
        if($stats['total'] > 0) {
            $stats['occupancy'] = round(($stats['occupied'] / $stats['total']) * 100);
        }

        $listTipe = $this->db->query("SELECT * FROM tipe_kamar")->fetchAll(PDO::FETCH_ASSOC);

        // 4. Kirim Data
        $payload = [
            'stats' => $stats,
            'rooms' => $rooms,
            'listTipe' => $listTipe,
            'selectedTipe' => $tipe
        ];

        $this->view('Dashboard/index', ['data' => $payload]);
    }

    public function clean() {
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $uid = $_SESSION['user_id'] ?? 1;

            // 1. Update Status Kamar
            $this->db->query("UPDATE kamar SET status='available' WHERE id_kamar=$id");
            
            // 2. Catat Log (Gunakan try-catch agar aman jika tabel belum ada)
            try {
                $this->db->prepare("INSERT INTO log_housekeeping (id_user, id_kamar, status_sebelum, status_sesudah) VALUES (?, ?, 'dirty', 'available')")
                         ->execute([$uid, $id]);
            } catch (Exception $e) {
                // Abaikan error log jika tabel belum siap
            }
            
            // Set Flash Message (Opsional, pastikan session_start di index.php sudah jalan)
            if (isset($_SESSION)) {
                $_SESSION['flash_type'] = 'success';
                $_SESSION['flash_message'] = 'Kamar berhasil dibersihkan!';
            }
        }
        header("Location: index.php?modul=Dashboard&aksi=index");
        exit;
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
<?php

class DashboardController {
    private $db;
    
    public function __construct() { 
        // Menggunakan koneksi database sesuai kode asli Anda
        $this->db = new Database(); 
    }

    public function index() {
        // 1. Cek Login (Sesuai kode asli)
        if (!isset($_SESSION['status_login'])) {
            header("Location: index.php?modul=Auth&aksi=login");
            exit;
        }

        // 2. AMBIL DATA KAMAR (Dioptimalkan)
        // Kita gunakan LEFT JOIN agar bisa mengambil data tamu yang sedang aktif
        // Logika ini PENTING agar pop-up modal bisa menampilkan nama tamu
        try {
            $sql = "SELECT k.*, t.nama_tipe, tr.id_transaksi, tr.nama_tamu, tr.tgl_checkin, tr.tgl_checkout 
                    FROM kamar k
                    JOIN tipe_kamar t ON k.id_tipe = t.id_tipe
                    LEFT JOIN transaksi tr ON k.id_kamar = tr.id_kamar AND tr.status_transaksi = 'active'
                    ORDER BY k.nomor_kamar ASC";
            
            // Eksekusi query sesuai gaya kode Anda
            $rooms = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $rooms = [];
        }

        // 3. HITUNG STATISTIK BARU (Untuk UI Dashboard)
        $stats = [
            'total'     => count($rooms),
            'available' => 0,
            'occupied'  => 0,
            'dirty'     => 0,
            'occupancy' => 0 // Tambahan untuk progress bar
        ];

        foreach($rooms as $r) {
            if ($r['status'] == 'available') $stats['available']++;
            elseif ($r['status'] == 'occupied') $stats['occupied']++;
            elseif ($r['status'] == 'dirty') $stats['dirty']++;
        }

        // Hitung Persentase Okupansi (agar terlihat mahal/enterprise)
        if ($stats['total'] > 0) {
            $stats['occupancy'] = round(($stats['occupied'] / $stats['total']) * 100);
        }

        // 4. KIRIM KE VIEW
        // Kita bungkus dalam array 'data' agar cocok dengan variabel $data di View baru
        $payload = [
            'stats' => $stats,
            'rooms' => $rooms,
            'judul' => 'Dashboard Manager'
        ];

        // Memanggil method view private di bawah
        $this->view('Dashboard/index', ['data' => $payload]);
    }

    // Aksi Bersihkan Kamar
    public function clean() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            // Query update sederhana sesuai kode asli
            $this->db->query("UPDATE kamar SET status='available' WHERE id_kamar=$id");
            
            // Set Flash Message (Opsional jika Anda punya handler flash)
            if(isset($_SESSION)) {
                $_SESSION['flash_type'] = 'success';
                $_SESSION['flash_message'] = 'Kamar berhasil dibersihkan!';
            }
        }
        header("Location: index.php?modul=Dashboard&aksi=index");
        exit;
    }

    // METHOD VIEW ASLI ANDA (Jangan Diubah)
    // Saya hanya menyesuaikan cara passing datanya
    private function view($p, $d=[]) { 
        extract($d); 
        // Variabel $data akan tercipta di sini karena kita passing ['data' => ...]
        
        include 'views/Layout/header.php'; 
        include 'views/Layout/sidebar.php'; 
        include "views/$p.php"; 
        include 'views/Layout/footer.php'; 
    }
}
?>
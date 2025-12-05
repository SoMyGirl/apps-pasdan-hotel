<?php

class LayananController {
    private $db;
    
    public function __construct() { 
        $this->db = new Database(); 
    }

    public function index() {
        // Cek Otoritas
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?modul=Dashboard&aksi=index");
            exit;
        }

        // A. HANDLE TAMBAH MENU
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['simpan'])) {
            $nama     = htmlspecialchars($_POST['nama']);
            $harga    = filter_input(INPUT_POST, 'harga', FILTER_SANITIZE_NUMBER_INT);
            $satuan   = htmlspecialchars($_POST['satuan']);
            $kategori = htmlspecialchars($_POST['kategori']);

            $stmt = $this->db->prepare("INSERT INTO master_layanan (nama_layanan, harga_satuan, satuan, kategori) VALUES (:nm, :hrg, :stn, :kat)");
            
            if ($stmt->execute(['nm' => $nama, 'hrg' => $harga, 'stn' => $satuan, 'kat' => $kategori])) {
                $this->flash('success', 'Menu baru berhasil ditambahkan!');
                header("Location: index.php?modul=Layanan&aksi=index"); 
                exit;
            }
        }

        // B. HANDLE HAPUS MENU
        if (isset($_GET['hapus'])) {
            $id = $_GET['hapus'];
            
            // Cek Dependensi: Jangan hapus jika sudah pernah dipesan di transaksi
            $cek = $this->db->prepare("SELECT id_detail FROM transaksi_layanan WHERE id_layanan = :id LIMIT 1");
            $cek->execute(['id' => $id]);
            
            if($cek->rowCount() > 0) {
                $this->flash('error', 'Gagal! Menu ini ada dalam riwayat transaksi.');
            } else {
                $stmt = $this->db->prepare("DELETE FROM master_layanan WHERE id_layanan = :id");
                $stmt->execute(['id' => $id]);
                $this->flash('success', 'Menu berhasil dihapus.');
            }
            header("Location: index.php?modul=Layanan&aksi=index"); 
            exit;
        }

        // C. AMBIL DATA
        $data = $this->db->query("SELECT * FROM master_layanan ORDER BY kategori ASC, nama_layanan ASC")->fetchAll(PDO::FETCH_ASSOC);
        
        $this->view('Layanan/index', ['data' => $data]);
    }

    private function flash($type, $msg) {
        if(session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['flash_type'] = $type;
        $_SESSION['flash_message'] = $msg;
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
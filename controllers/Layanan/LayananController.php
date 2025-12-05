<?php
class LayananController {
    private $db;
    public function __construct() { $this->db = new Database(); }

    public function index() {
        // --- LOGIKA CREATE (TAMBAH DATA) ---
        if (isset($_POST['simpan'])) {
            $nama     = $_POST['nama'];
            $harga    = $_POST['harga'];
            $satuan   = $_POST['satuan'];
            $kategori = $_POST['kategori'];

            $sql = "INSERT INTO master_layanan (nama_layanan, harga_satuan, satuan, kategori) 
                    VALUES ('$nama', '$harga', '$satuan', '$kategori')";
            
            $this->db->query($sql);

            // Notifikasi Sukses
            $_SESSION['flash_type'] = 'success';
            $_SESSION['flash_message'] = 'Menu layanan berhasil ditambahkan!';
            
            header("Location: index.php?modul=Layanan&aksi=index");
            exit;
        }

        // --- LOGIKA DELETE (HAPUS DATA) ---
        if (isset($_GET['hapus'])) {
            $this->db->query("DELETE FROM master_layanan WHERE id_layanan=" . $_GET['hapus']);
            
            $_SESSION['flash_type'] = 'success';
            $_SESSION['flash_message'] = 'Menu berhasil dihapus!';
            
            header("Location: index.php?modul=Layanan&aksi=index");
            exit;
        }

        // --- AMBIL DATA UNTUK DITAMPILKAN ---
        $data = $this->db->query("SELECT * FROM master_layanan ORDER BY id_layanan DESC")->fetchAll();
        
        $this->view('Layanan/index', ['data' => $data]);
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
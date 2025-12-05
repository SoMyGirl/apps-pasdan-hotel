<?php

class RoomController {
    private $db;
    
    public function __construct() { 
        $this->db = new Database(); 
    }

    public function index() {
        // Cek Role Admin
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?modul=Dashboard&aksi=index");
            exit;
        }

        // --- LOGIC HAPUS KAMAR ---
        if (isset($_GET['hapus'])) {
            $id = $_GET['hapus'];
            
            // Cek dulu apakah kamar sedang dipakai?
            $stmtCek = $this->db->prepare("SELECT status FROM kamar WHERE id_kamar = :id");
            $stmtCek->execute(['id' => $id]);
            $kamar = $stmtCek->fetch(PDO::FETCH_ASSOC);

            if($kamar && $kamar['status'] == 'occupied') {
                $this->flash('error', 'Gagal! Kamar sedang terisi tamu.');
            } else {
                // Proses Hapus Aman
                $stmt = $this->db->prepare("DELETE FROM kamar WHERE id_kamar = :id");
                $stmt->execute(['id' => $id]);
                $this->flash('success', 'Data kamar berhasil dihapus.');
            }
            
            header("Location: index.php?modul=Room&aksi=index"); 
            exit;
        }

        // --- AMBIL DATA ---
        $query = "SELECT k.*, t.nama_tipe, t.harga_dasar 
                  FROM kamar k 
                  JOIN tipe_kamar t ON k.id_tipe = t.id_tipe 
                  ORDER BY k.nomor_kamar ASC";
        
        $kamar = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
        
        $this->view('Room/index', ['kamar' => $kamar]);
    }

    public function create() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php"); exit;
        }

        // --- LOGIC SIMPAN ---
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['simpan'])) {
            $nomor = htmlspecialchars($_POST['nomor']);
            $tipe  = $_POST['tipe'];
            
            // 1. Validasi Duplikat (Prepared Statement)
            $stmtCek = $this->db->prepare("SELECT id_kamar FROM kamar WHERE nomor_kamar = :no");
            $stmtCek->execute(['no' => $nomor]);
            
            if ($stmtCek->rowCount() > 0) {
                $this->flash('error', "Nomor Kamar $nomor sudah ada!");
            } else {
                // 2. Insert Data
                $stmt = $this->db->prepare("INSERT INTO kamar (nomor_kamar, id_tipe, status) VALUES (:no, :tipe, 'available')");
                if($stmt->execute(['no' => $nomor, 'tipe' => $tipe])) {
                    $this->flash('success', "Kamar $nomor berhasil ditambahkan.");
                    header("Location: index.php?modul=Room&aksi=index"); 
                    exit;
                }
            }
        }

        // Ambil Data Tipe Kamar untuk Dropdown
        $tipe = $this->db->query("SELECT * FROM tipe_kamar ORDER BY harga_dasar ASC")->fetchAll(PDO::FETCH_ASSOC);
        
        $this->view('Room/create', ['tipe' => $tipe]);
    }

    // Helper Flash Message
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
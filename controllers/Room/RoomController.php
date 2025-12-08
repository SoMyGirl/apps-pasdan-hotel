<?php

class RoomController {
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

        // --- LOGIC HAPUS KAMAR ---
        if (isset($_GET['hapus'])) {
            if ($_SESSION['role'] !== 'admin') {
                $this->flash('error', 'Akses ditolak! Hanya Admin yang bisa menghapus.');
                header("Location: index.php?modul=Room&aksi=index"); 
                exit;
            }

            $id = $_GET['hapus'];
            
            $stmtCek = $this->db->prepare("SELECT status FROM kamar WHERE id_kamar = :id");
            $stmtCek->execute(['id' => $id]);
            $kamar = $stmtCek->fetch(PDO::FETCH_ASSOC);

            if($kamar && $kamar['status'] == 'occupied') {
                $this->flash('error', 'Gagal! Kamar sedang terisi tamu.');
            } else {
                $stmt = $this->db->prepare("DELETE FROM kamar WHERE id_kamar = :id");
                $stmt->execute(['id' => $id]);
                $this->flash('success', 'Data kamar berhasil dihapus.');
            }
            
            header("Location: index.php?modul=Room&aksi=index"); 
            exit;
        }

        // --- AMBIL DATA FILTER ---
        // Ambil semua tipe kamar untuk dropdown filter
        $listTipe = $this->db->query("SELECT * FROM tipe_kamar ORDER BY nama_tipe ASC")->fetchAll(PDO::FETCH_ASSOC);
        
        // Cek apakah ada filter tipe yang dipilih user
        $selectedTipe = isset($_GET['tipe']) ? $_GET['tipe'] : '';

        // --- BUILD QUERY ---
        $sql = "SELECT k.*, t.nama_tipe, t.harga_dasar 
                FROM kamar k 
                JOIN tipe_kamar t ON k.id_tipe = t.id_tipe";
        
        // Jika ada filter, tambahkan WHERE
        if (!empty($selectedTipe)) {
            $sql .= " WHERE k.id_tipe = :tipe";
        }
        
        $sql .= " ORDER BY k.nomor_kamar ASC";
        
        // Eksekusi Query
        if (!empty($selectedTipe)) {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['tipe' => $selectedTipe]);
            $kamar = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $kamar = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        }
        
        // Kirim data ke View (termasuk listTipe dan selectedTipe)
        $this->view('Room/index', [
            'kamar' => $kamar, 
            'listTipe' => $listTipe,
            'selectedTipe' => $selectedTipe
        ]);
    }

    public function create() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?modul=Error&aksi=forbidden"); 
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['simpan'])) {
            $nomor = htmlspecialchars($_POST['nomor']);
            $tipe  = $_POST['tipe'];
            
            $stmtCek = $this->db->prepare("SELECT id_kamar FROM kamar WHERE nomor_kamar = :no");
            $stmtCek->execute(['no' => $nomor]);
            
            if ($stmtCek->rowCount() > 0) {
                $this->flash('error', "Nomor Kamar $nomor sudah ada!");
            } else {
                $stmt = $this->db->prepare("INSERT INTO kamar (nomor_kamar, id_tipe, status) VALUES (:no, :tipe, 'available')");
                if($stmt->execute(['no' => $nomor, 'tipe' => $tipe])) {
                    $this->flash('success', "Kamar $nomor berhasil ditambahkan.");
                    header("Location: index.php?modul=Room&aksi=index"); 
                    exit;
                }
            }
        }

        $tipe = $this->db->query("SELECT * FROM tipe_kamar ORDER BY harga_dasar ASC")->fetchAll(PDO::FETCH_ASSOC);
        $this->view('Room/create', ['tipe' => $tipe]);
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
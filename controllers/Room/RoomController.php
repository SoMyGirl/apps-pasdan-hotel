<?php

class RoomController {
    private $db;
    
    public function __construct() { 
        $this->db = new Database();
        // Pastikan session aktif
        if (session_status() == PHP_SESSION_NONE) session_start();
    }

    public function index() {
        // 1. Cek Login
        if (!isset($_SESSION['status_login'])) {
            header("Location: index.php?modul=Auth&aksi=login");
            exit;
        }

        // Ambil Role & Bersihkan Spasi
        $role = trim(strtolower($_SESSION['role'] ?? ''));
        $allowedRoles = ['admin', 'administrator', 'general manager'];

        // --- HANDLE HAPUS ---
        if (isset($_GET['hapus'])) {
            // Cek Izin Hapus
            if (!in_array($role, $allowedRoles)) {
                $this->flash('error', 'Akses ditolak! Anda tidak memiliki izin.');
                header("Location: index.php?modul=Room&aksi=index"); 
                exit;
            }
            
            $id = filter_input(INPUT_GET, 'hapus', FILTER_SANITIZE_NUMBER_INT);
            
            // Cek status sebelum hapus (Gunakan Prepared Statement agar Aman)
            $stmtCek = $this->db->prepare("SELECT status FROM kamar WHERE id_kamar = ?");
            $stmtCek->execute([$id]);
            $cek = $stmtCek->fetch();

            if($cek && $cek['status'] == 'occupied') {
                $this->flash('error', 'Gagal! Kamar sedang terisi tamu.');
            } else {
                $stmtDel = $this->db->prepare("DELETE FROM kamar WHERE id_kamar = ?");
                $stmtDel->execute([$id]);
                $this->flash('success', 'Data kamar berhasil dihapus.');
            }
            header("Location: index.php?modul=Room&aksi=index"); 
            exit;
        }

        // --- FILTER DATA ---
        $tipe = $_GET['tipe'] ?? '';
        $sql = "SELECT * FROM v_kamar_list";
        $params = [];

        if (!empty($tipe)) {
            $sql .= " WHERE id_tipe = ?";
            $params[] = $tipe;
        }
        $sql .= " ORDER BY nomor_kamar ASC";

        // Eksekusi Query Kamar
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $kamar = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ambil List Tipe untuk Dropdown
        $listTipe = $this->db->query("SELECT * FROM tipe_kamar")->fetchAll(PDO::FETCH_ASSOC);

        $this->view('Room/index', ['kamar' => $kamar, 'listTipe' => $listTipe, 'selectedTipe' => $tipe]);
    }

    public function create() {
        // 1. Cek Role (SOLUSI MASALAH ANDA)
        // Kita gunakan trim dan array agar admin/general manager bisa masuk
        $role = trim(strtolower($_SESSION['role'] ?? ''));
        $allowedRoles = ['admin', 'administrator', 'general manager'];

        if (!in_array($role, $allowedRoles)) { 
            // Debugging: Jika masih gagal, uncomment baris bawah ini untuk melihat apa yang salah
            // die("Role Anda: " . $role . " tidak diizinkan.");
            header("Location: index.php?modul=Dashboard"); 
            exit; 
        }

        // 2. Handle Submit Form
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $no = htmlspecialchars(trim($_POST['nomor']));
            $tp = $_POST['tipe'];
            
            // Cek duplikat Nomor Kamar
            $cek = $this->db->prepare("SELECT id_kamar FROM kamar WHERE nomor_kamar = ?");
            $cek->execute([$no]);
            
            if ($cek->rowCount() > 0) {
                $this->flash('error', "Nomor Kamar $no sudah ada!");
            } else {
                $stmt = $this->db->prepare("INSERT INTO kamar (nomor_kamar, id_tipe, status) VALUES (?, ?, 'available')");
                if ($stmt->execute([$no, $tp])) {
                    $this->flash('success', "Kamar $no berhasil ditambahkan.");
                    header("Location: index.php?modul=Room&aksi=index"); 
                    exit;
                }
            }
        }

        // View Form Create
        $tipe = $this->db->query("SELECT * FROM tipe_kamar ORDER BY harga_dasar ASC")->fetchAll(PDO::FETCH_ASSOC);
        $this->view('Room/create', ['tipe' => $tipe]);
    }

    // --- HELPER METHODS ---
    private function flash($t, $m) {
        if(session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['flash_type'] = $t; 
        $_SESSION['flash_message'] = $m;
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
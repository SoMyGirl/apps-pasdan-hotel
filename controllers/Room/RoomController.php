<?php

class RoomController {
    private $db;
    
    public function __construct() { 
        $this->db = new Database(); 
    }

    public function index() {
        if (!isset($_SESSION['status_login'])) {
            header("Location: index.php?modul=Auth&aksi=login");
            exit;
        }

        // --- HANDLE HAPUS ---
        if (isset($_GET['hapus'])) {
            if ($_SESSION['role'] !== 'admin') {
                $this->flash('error', 'Akses ditolak!');
                header("Location: index.php?modul=Room&aksi=index"); exit;
            }
            
            $id = $_GET['hapus'];
            // Cek status sebelum hapus
            $cek = $this->db->query("SELECT status FROM kamar WHERE id_kamar=$id")->fetch();
            if($cek && $cek['status'] == 'occupied') {
                $this->flash('error', 'Gagal! Kamar sedang terisi.');
            } else {
                $this->db->prepare("DELETE FROM kamar WHERE id_kamar=?")->execute([$id]);
                $this->flash('success', 'Kamar berhasil dihapus.');
            }
            header("Location: index.php?modul=Room&aksi=index"); exit;
        }

        // --- FILTER ---
        $tipe = $_GET['tipe'] ?? '';
        $whr = $tipe ? "WHERE id_tipe = '$tipe'" : "";

        // Panggil View Database
        $kamar = $this->db->query("SELECT * FROM v_kamar_list $whr ORDER BY nomor_kamar ASC")->fetchAll(PDO::FETCH_ASSOC);
        $listTipe = $this->db->query("SELECT * FROM tipe_kamar")->fetchAll(PDO::FETCH_ASSOC);

        $this->view('Room/index', ['kamar' => $kamar, 'listTipe' => $listTipe, 'selectedTipe' => $tipe]);
    }

    public function create() {
        if ($_SESSION['role'] !== 'admin') { header("Location: index.php"); exit; }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $no = htmlspecialchars($_POST['nomor']);
            $tp = $_POST['tipe'];
            
            // Cek duplikat
            $cek = $this->db->prepare("SELECT id_kamar FROM kamar WHERE nomor_kamar=?");
            $cek->execute([$no]);
            
            if ($cek->rowCount() > 0) {
                $this->flash('error', "Nomor Kamar $no sudah ada!");
            } else {
                $stmt = $this->db->prepare("INSERT INTO kamar (nomor_kamar, id_tipe, status) VALUES (?, ?, 'available')");
                if ($stmt->execute([$no, $tp])) {
                    $this->flash('success', "Kamar $no berhasil ditambahkan.");
                    header("Location: index.php?modul=Room&aksi=index"); exit;
                }
            }
        }
        $tipe = $this->db->query("SELECT * FROM tipe_kamar ORDER BY harga_dasar ASC")->fetchAll(PDO::FETCH_ASSOC);
        $this->view('Room/create', ['tipe' => $tipe]);
    }

    private function flash($t, $m) {
        if(session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['flash_type'] = $t; $_SESSION['flash_message'] = $m;
    }
    private function view($p, $d=[]) { extract($d); include 'views/Layout/header.php'; include 'views/Layout/sidebar.php'; include "views/$p.php"; include 'views/Layout/footer.php'; }
}
?>
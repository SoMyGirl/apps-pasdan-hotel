<?php
class RoomController {
    private $db;
    public function __construct() { $this->db = new Database(); }

    // Halaman List Kamar
    public function index() {
        if ($_SESSION['role'] !== 'admin') header("Location: index.php");

        // Handle Hapus
        if (isset($_GET['hapus'])) {
            $this->db->query("DELETE FROM kamar WHERE id_kamar=".$_GET['hapus']);
            $_SESSION['flash_type'] = 'success';
            $_SESSION['flash_message'] = 'Kamar dihapus!';
            header("Location: index.php?modul=Room&aksi=index");
            exit;
        }

        $kamar = $this->db->query("SELECT k.*, t.nama_tipe, t.harga_dasar FROM kamar k JOIN tipe_kamar t USING(id_tipe) ORDER BY nomor_kamar ASC")->fetchAll();
        $this->view('Room/index', ['kamar' => $kamar]);
    }

    // Halaman Tambah Kamar
    public function create() {
        if ($_SESSION['role'] !== 'admin') header("Location: index.php");

        // Handle Simpan
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nomor = $_POST['nomor'];
            $tipe  = $_POST['tipe'];
            
            $this->db->query("INSERT INTO kamar (nomor_kamar, id_tipe, status) VALUES ('$nomor', '$tipe', 'available')");
            
            $_SESSION['flash_type'] = 'success';
            $_SESSION['flash_message'] = 'Kamar berhasil ditambahkan!';
            header("Location: index.php?modul=Room&aksi=index");
            exit;
        }

        $tipe = $this->db->query("SELECT * FROM tipe_kamar")->fetchAll();
        $this->view('Room/create', ['tipe' => $tipe]);
    }

    private function view($p, $d=[]) { extract($d); include 'views/Layout/header.php'; include 'views/Layout/sidebar.php'; include "views/$p.php"; include 'views/Layout/footer.php'; }
}
?>
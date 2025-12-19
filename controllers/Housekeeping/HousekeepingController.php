<?php
class HousekeepingController {
    private $db;
    public function __construct() { $this->db = new Database(); }

    public function index() {
        // PERBAIKAN: Mengizinkan admin, administrator, GM, dan housekeeping
        if(!in_array($_SESSION['role'], ['admin', 'administrator', 'general manager', 'housekeeping'])) {
            header("Location: index.php"); exit;
        }
        
        // Ambil data dari View Database
        $dirtyRooms = $this->db->query("SELECT * FROM v_hk_dirty ORDER BY nomor_kamar ASC")->fetchAll(PDO::FETCH_ASSOC);
        $checkoutToday = $this->db->query("SELECT * FROM v_hk_checkout_today")->fetchAll(PDO::FETCH_ASSOC);

        $this->view('Housekeeping/index', [
            'dirtyRooms' => $dirtyRooms,
            'checkoutToday' => $checkoutToday
        ]);
    }

    // --- HALAMAN DETAIL BARU ---
    public function detail() {
        // PERBAIKAN: Mengizinkan admin, administrator, GM, dan housekeeping
        if(!in_array($_SESSION['role'], ['admin', 'administrator', 'general manager', 'housekeeping'])) {
            header("Location: index.php"); exit;
        }
        
        $id = $_GET['id'] ?? null;
        if(!$id) { header("Location: index.php?modul=Housekeeping&aksi=index"); exit; }

        // Ambil Detail Kamar
        $room = $this->db->query("SELECT k.*, t.nama_tipe FROM kamar k JOIN tipe_kamar t USING(id_tipe) WHERE k.id_kamar=$id")->fetch(PDO::FETCH_ASSOC);
        
        if(!$room) { header("Location: index.php?modul=Housekeeping&aksi=index"); exit; }

        $this->view('Housekeeping/detail', ['room' => $room]);
    }

    public function clean() {
        if (!isset($_GET['id'])) header("Location: index.php");
        $id = $_GET['id'];
        
        $this->db->query("UPDATE kamar SET status='available' WHERE id_kamar=$id");
        $this->db->prepare("INSERT INTO log_housekeeping (id_user,id_kamar,status_sebelum,status_sesudah) VALUES (?,?,'dirty','available')")
                 ->execute([$_SESSION['user_id'], $id]);
                  
        $_SESSION['flash_type']='success'; $_SESSION['flash_message']='Kamar berhasil dibersihkan!';
        header("Location: index.php?modul=Housekeeping&aksi=index");
    }

    public function reportLostItem() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_kamar = $_POST['id_kamar'];
            $barang   = htmlspecialchars($_POST['nama_barang']);
            $ket      = htmlspecialchars($_POST['keterangan']);
            
            $foto = null;
            if (!empty($_FILES['foto']['name'])) {
                $targetDir = "assets/uploads/lostfound/";
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                
                $fileName = time() . "_" . basename($_FILES["foto"]["name"]);
                $targetFilePath = $targetDir . $fileName;
                
                if(move_uploaded_file($_FILES["foto"]["tmp_name"], $targetFilePath)){
                    $foto = $fileName;
                }
            }

            $sql = "INSERT INTO lost_found (id_kamar, id_user_penemu, nama_barang, deskripsi, foto_barang) VALUES (?, ?, ?, ?, ?)";
            $this->db->prepare($sql)->execute([$id_kamar, $_SESSION['user_id'], $barang, $ket, $foto]);

            $_SESSION['flash_type']='success'; $_SESSION['flash_message']='Barang tertinggal berhasil dilaporkan!';
            
            // Redirect kembali ke halaman DETAIL agar housekeeping bisa lanjut kerja
            header("Location: index.php?modul=Housekeeping&aksi=detail&id=$id_kamar");
        }
    }

    private function view($p, $d=[]) { extract($d); include 'views/Layout/header.php'; include 'views/Layout/sidebar.php'; include "views/$p.php"; include 'views/Layout/footer.php'; }
}
?>
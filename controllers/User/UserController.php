<?php
class UserController {
    private $db;
    public function __construct() { $this->db = new Database(); }

    public function index() {
        if ($_SESSION['role'] !== 'admin') { header("Location: index.php"); exit; }

        if (isset($_GET['hapus'])) {
            $id = $_GET['hapus'];
            if ($id == $_SESSION['user_id']) {
                $this->flash('error', 'Tidak bisa menghapus akun sendiri!');
            } else {
                $this->db->prepare("DELETE FROM users WHERE id_user=?")->execute([$id]);
                $this->flash('success', 'Staff dihapus.');
            }
            header("Location: index.php?modul=User&aksi=index"); exit;
        }

        // Ambil Data via VIEW
        $users = $this->db->query("SELECT * FROM v_user_list ORDER BY role ASC")->fetchAll(PDO::FETCH_ASSOC);
        $this->view('User/index', ['users' => $users]);
    }

    public function create() {
        if ($_SESSION['role'] !== 'admin') { header("Location: index.php"); exit; }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nm = htmlspecialchars($_POST['nama']);
            $us = strtolower(htmlspecialchars($_POST['username']));
            $pw = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $rl = $_POST['role'];
            $jk = $_POST['gender']; // Tangkap input Gender

            // Cek Username
            $cek = $this->db->prepare("SELECT id_user FROM users WHERE username = :us");
            $cek->execute(['us' => $us]);

            if ($cek->rowCount() > 0) {
                $this->flash('error', 'Username sudah digunakan!');
            } else {
                try {
                    // Update Query untuk memasukkan Gender
                    $sql = "INSERT INTO users (username, password, nama_lengkap, gender, role) VALUES (:us, :pw, :nm, :jk, :rl)";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute([
                        'us' => $us,
                        'pw' => $pw,
                        'nm' => $nm,
                        'jk' => $jk,
                        'rl' => $rl
                    ]);
                    
                    $this->flash('success', 'Staff baru berhasil ditambahkan.');
                    header("Location: index.php?modul=User&aksi=index"); exit;
                } catch (Exception $e) {
                    $this->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
                }
            }
        }
        $this->view('User/create');
    }

    private function flash($t, $m) { if(session_status()==PHP_SESSION_NONE)session_start(); $_SESSION['flash_type']=$t; $_SESSION['flash_message']=$m; }
    private function view($p, $d=[]) { extract($d); include 'views/Layout/header.php'; include 'views/Layout/sidebar.php'; include "views/$p.php"; include 'views/Layout/footer.php'; }
}
?>
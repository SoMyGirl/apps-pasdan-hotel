<?php
class UserController {
    private $db;
    public function __construct() { $this->db = new Database(); }

    public function index() {
        // Hanya Admin
        if ($_SESSION['role'] !== 'admin') {
            header("Location: index.php?modul=Dashboard&aksi=index");
            exit;
        }

        // Handle Hapus
        if (isset($_GET['hapus'])) {
            $this->db->query("DELETE FROM users WHERE id_user=" . $_GET['hapus']);
            $_SESSION['flash_type'] = 'success';
            $_SESSION['flash_message'] = 'User berhasil dihapus!';
            header("Location: index.php?modul=User&aksi=index");
            exit;
        }

        $users = $this->db->query("SELECT * FROM users ORDER BY id_user DESC")->fetchAll();
        $this->view('User/index', ['users' => $users]);
    }

    public function create() {
        // Hanya Admin
        if ($_SESSION['role'] !== 'admin') header("Location: index.php?modul=Dashboard&aksi=index");

        // Handle Simpan
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash Password
            $nama = $_POST['nama'];
            $role = $_POST['role'];

            $sql = "INSERT INTO users (username, password, nama_lengkap, role) VALUES ('$username', '$password', '$nama', '$role')";
            $this->db->query($sql);

            $_SESSION['flash_type'] = 'success';
            $_SESSION['flash_message'] = 'User baru berhasil ditambahkan!';
            header("Location: index.php?modul=User&aksi=index");
            exit;
        }

        $this->view('User/create');
    }

    private function view($p, $d=[]) { extract($d); include 'views/Layout/header.php'; include 'views/Layout/sidebar.php'; include "views/$p.php"; include 'views/Layout/footer.php'; }
}
?>
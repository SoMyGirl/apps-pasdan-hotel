<?php
class AuthController {
    private $db;
    public function __construct() { $this->db = new Database(); }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = $this->db->query("SELECT * FROM users WHERE username='{$_POST['username']}'")->fetch();
            
            if ($user && password_verify($_POST['password'], $user['password'])) {
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['nama'] = $user['nama_lengkap'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['status_login'] = true;
                header("Location: index.php?modul=Dashboard&aksi=index");
                exit;
            } else {
                $_SESSION['flash_type'] = 'error';
                $_SESSION['flash_message'] = 'Username atau Password salah!';
            }
        }
        include 'views/Auth/login.php';
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?modul=Auth&aksi=login");
    }
}
?>
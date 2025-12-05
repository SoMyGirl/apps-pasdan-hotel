<?php
class AuthController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function login() {
        // Cek jika user sudah login, langsung lempar ke Dashboard
        if (isset($_SESSION['status_login']) && $_SESSION['status_login'] === true) {
            header("Location: index.php?modul=Dashboard&aksi=index");
            exit;
        }

        // Proses Login saat Tombol ditekan
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Cek user di database
            // Kita pakai query manual biar aman (prepared statement ada di class Database)
            $sql = "SELECT * FROM users WHERE username = '$username'";
            $user = $this->db->query($sql)->fetch();

            // Verifikasi Password
            if ($user && password_verify($password, $user['password'])) {
                // Set Session
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['nama']    = $user['nama_lengkap'];
                $_SESSION['role']    = $user['role'];
                $_SESSION['status_login'] = true;

                // Notifikasi Sukses (Disimpan di Session Flash)
                $_SESSION['flash_type'] = 'success';
                $_SESSION['flash_message'] = 'Selamat datang kembali, ' . $user['nama_lengkap'];

                // Redirect ke Dashboard
                header("Location: index.php?modul=Dashboard&aksi=index");
                exit;
            } else {
                // Gagal Login
                $_SESSION['flash_type'] = 'error';
                $_SESSION['flash_message'] = 'Username atau Password salah!';
            }
        }

        // Tampilkan Halaman Login (Tanpa Header/Sidebar standar)
        include 'views/Auth/login.php';
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?modul=Auth&aksi=login");
        exit;
    }
}
?>
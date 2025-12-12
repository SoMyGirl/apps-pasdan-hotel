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

            // PERBAIKAN: Ambil langsung dari tabel users (tanpa join jabatan yang membingungkan)
            // Karena kolom 'role' sekarang sudah ada di tabel users
            $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :user");
            $stmt->execute(['user' => $username]);
            $user = $stmt->fetch();

            // Verifikasi Password
            if ($user && password_verify($password, $user['password'])) {
                
                // Set Session
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['nama']    = $user['nama_lengkap'];
                
                // PERBAIKAN: Ambil role langsung dari kolom database
                $_SESSION['role']    = $user['role']; 
                
                $_SESSION['status_login'] = true;

                // Notifikasi Sukses
                $_SESSION['flash_type'] = 'success';
                $_SESSION['flash_message'] = 'Selamat datang kembali, ' . $user['nama_lengkap'];

                // Redirect Khusus berdasarkan Role
                if ($user['role'] == 'housekeeping') {
                    // Housekeeping langsung ke halaman tugasnya
                    header("Location: index.php?modul=Housekeeping&aksi=index");
                } else {
                    // Admin & Resepsionis ke Dashboard
                    header("Location: index.php?modul=Dashboard&aksi=index");
                }
                exit;
            } else {
                // Gagal Login
                $_SESSION['flash_type'] = 'error';
                $_SESSION['flash_message'] = 'Username atau Password salah!';
            }
        }

        // Tampilkan Halaman Login
        include 'views/Auth/login.php';
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?modul=Auth&aksi=login");
        exit;
    }
}
?>
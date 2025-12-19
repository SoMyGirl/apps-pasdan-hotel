<?php
class AuthController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function login() {
        if (isset($_SESSION['status_login']) && $_SESSION['status_login'] === true) {
            header("Location: index.php?modul=Dashboard&aksi=index"); exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // JOIN untuk ambil nama_jabatan sebagai role
            $sql = "SELECT users.*, jabatan.nama_jabatan 
                    FROM users 
                    JOIN jabatan ON users.id_jabatan = jabatan.id_jabatan 
                    WHERE username = :user";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['user' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['nama']    = $user['nama_lengkap'];
                
                // Simpan nama_jabatan sebagai 'role' (lowercased)
                // Contoh: 'Administrator' -> 'administrator'
                $_SESSION['role']    = strtolower($user['nama_jabatan']); 
                
                $_SESSION['status_login'] = true;
                $_SESSION['flash_type'] = 'success';
                $_SESSION['flash_message'] = 'Selamat datang, ' . $user['nama_lengkap'];

                // Redirect
                if ($_SESSION['role'] == 'housekeeping') {
                    header("Location: index.php?modul=Housekeeping&aksi=index");
                } else {
                    header("Location: index.php?modul=Dashboard&aksi=index");
                }
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
        exit;
    }
}
?>
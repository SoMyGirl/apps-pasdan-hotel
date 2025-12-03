<?php
include_once 'model/koneksi.php';

class C_Auth {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function login($username, $password) {
        $username = $this->db->escape($username);
        
        // Cek user
        $data = $this->db->tampil('users', "username = '$username'");
        
        if (!empty($data)) {
            $user = $data[0]; // Ambil data pertama
            // Verifikasi Password Hash
            if (password_verify($password, $user['password'])) {
                // Set Session
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['nama'] = $user['nama_lengkap'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['status_login'] = true;
                return true;
            }
        }
        return false;
    }
}
?>
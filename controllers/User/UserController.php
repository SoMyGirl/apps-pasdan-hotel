<?php

class UserController {
    private $db;
    
    public function __construct() { 
        $this->db = new Database(); 
    }

    public function index() {
        // Security: Hanya Admin yang boleh masuk
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?modul=Dashboard&aksi=index");
            exit;
        }

        // --- HANDLE HAPUS ---
        if (isset($_GET['hapus'])) {
            $id = $_GET['hapus'];
            
            // 1. Validasi: Jangan hapus diri sendiri
            if ($id == $_SESSION['user_id']) {
                $this->flash('error', 'Anda tidak bisa menghapus akun sendiri!');
            } else {
                // 2. Proses Hapus Aman
                $stmt = $this->db->prepare("DELETE FROM users WHERE id_user = :id");
                $stmt->execute(['id' => $id]);
                $this->flash('success', 'Akun staff berhasil dihapus.');
            }
            header("Location: index.php?modul=User&aksi=index");
            exit;
        }

        // --- AMBIL DATA ---
        $stmt = $this->db->query("SELECT * FROM users ORDER BY role ASC, nama_lengkap ASC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $this->view('User/index', ['users' => $users]);
    }

    public function create() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php"); exit;
        }

        // --- HANDLE SIMPAN ---
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama     = htmlspecialchars($_POST['nama']);
            $username = strtolower(htmlspecialchars($_POST['username'])); // Force lowercase
            $password = $_POST['password'];
            $role     = $_POST['role'];

            // 1. Cek Username Kembar
            $stmtCek = $this->db->prepare("SELECT id_user FROM users WHERE username = :user");
            $stmtCek->execute(['user' => $username]);
            
            if ($stmtCek->rowCount() > 0) {
                $this->flash('error', "Username '$username' sudah digunakan staff lain!");
            } else {
                // 2. Hash Password & Insert
                $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
                
                $sql = "INSERT INTO users (username, password, nama_lengkap, role) VALUES (:user, :pass, :nama, :role)";
                $stmt = $this->db->prepare($sql);
                
                if($stmt->execute(['user' => $username, 'pass' => $hashed_pass, 'nama' => $nama, 'role' => $role])) {
                    $this->flash('success', "Staff baru ($nama) berhasil didaftarkan.");
                    header("Location: index.php?modul=User&aksi=index");
                    exit;
                }
            }
        }

        $this->view('User/create');
    }

    private function flash($type, $msg) {
        if(session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['flash_type'] = $type;
        $_SESSION['flash_message'] = $msg;
    }

    private function view($p, $d=[]) { 
        extract($d); 
        include 'views/Layout/header.php'; 
        include 'views/Layout/sidebar.php'; 
        include "views/$p.php"; 
        include 'views/Layout/footer.php'; 
    }
}
?>
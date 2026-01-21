<?php
class UserController {
    private $db;
    public function __construct() { $this->db = new Database(); }

    public function index() {
        // PERBAIKAN: Mengizinkan admin, administrator, dan general manager
        if (!in_array($_SESSION['role'], ['admin', 'administrator', 'general manager'])) { 
            header("Location: index.php"); exit; 
        }

        if (isset($_GET['hapus'])) {
            $id = $_GET['hapus'];
            if ($id == $_SESSION['user_id']) {
                $this->flash('error', 'Tidak bisa menghapus akun sendiri!');
            } else {
                try {
                    $this->db->prepare("DELETE FROM users WHERE id_user=?")->execute([$id]);
                    $this->flash('success', 'Staff dihapus.');
                } catch (Exception $e) {
                    $this->flash('error', 'Gagal hapus: ' . $e->getMessage());
                }
            }
            header("Location: index.php?modul=User&aksi=index"); exit;
        }

        // Ambil Data via VIEW
        $users = $this->db->query("SELECT * FROM v_user_list ORDER BY role ASC")->fetchAll(PDO::FETCH_ASSOC);
        $this->view('User/index', ['users' => $users]);
    }

    public function create() {
        // PERBAIKAN: Mengizinkan admin, administrator, dan general manager
        if (!in_array($_SESSION['role'], ['admin', 'administrator', 'general manager'])) { 
            header("Location: index.php"); exit; 
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nm = htmlspecialchars($_POST['nama']);
            $us = strtolower(htmlspecialchars($_POST['username']));
            $pw = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $jk = $_POST['gender'];
            $ij = $_POST['id_jabatan']; // Tangkap ID Jabatan

            // Cek Username
            $cek = $this->db->prepare("SELECT id_user FROM users WHERE username = :us");
            $cek->execute(['us' => $us]);

            if ($cek->rowCount() > 0) {
                $this->flash('error', 'Username sudah digunakan!');
            } else {
                try {
                    // INSERT ke id_jabatan (Tanpa kolom role)
                    $sql = "INSERT INTO users (username, password, nama_lengkap, gender, id_jabatan) 
                            VALUES (:us, :pw, :nm, :jk, :ij)";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute([
                        'us' => $us,
                        'pw' => $pw,
                        'nm' => $nm,
                        'jk' => $jk,
                        'ij' => $ij
                    ]);
                    
                    $this->flash('success', 'Staff baru berhasil ditambahkan.');
                    header("Location: index.php?modul=User&aksi=index"); exit;
                } catch (Exception $e) {
                    $this->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
                }
            }
        }

        // AMBIL LIST JABATAN DARI DATABASE
        $listJabatan = $this->db->query("SELECT * FROM jabatan ORDER BY level ASC")->fetchAll(PDO::FETCH_ASSOC);

        $this->view('User/create', ['listJabatan' => $listJabatan]);
    }

    private function flash($t, $m) { if(session_status()==PHP_SESSION_NONE)session_start(); $_SESSION['flash_type']=$t; $_SESSION['flash_message']=$m; }
    private function view($p, $d=[]) { extract($d); include 'views/Layout/header.php'; include 'views/Layout/sidebar.php'; include "views/$p.php"; include 'views/Layout/footer.php'; }
}
?>
<?php
include_once 'model/koneksi.php';
class C_User {
    private $db;
    public function __construct() { $this->db = new Database(); }

    public function index() { return $this->db->tampil('users'); }

    public function tambah($user, $pass, $nama, $role) {
        // PENTING: Hash Password
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $this->db->tambah('users', [
            'username' => $user,
            'password' => $hash,
            'nama_lengkap' => $nama,
            'role' => $role
        ]);
    }
    public function hapus($id) { $this->db->hapus('users', "id_user=$id"); }
}
?>
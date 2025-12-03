<?php
include_once 'model/koneksi.php';

class C_Kamar {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function index() {
        // Join dengan tipe kamar untuk ambil nama tipe & harga
        return $this->db->query("SELECT k.*, t.nama_tipe, t.harga_dasar 
                                 FROM kamar k 
                                 JOIN tipe_kamar t ON k.id_tipe = t.id_tipe 
                                 ORDER BY k.nomor_kamar ASC");
    }

    public function getTipe() {
        return $this->db->tampil('tipe_kamar');
    }

    public function tambah($nomor, $id_tipe) {
        $data = [
            'nomor_kamar' => $nomor,
            'id_tipe' => $id_tipe,
            'status' => 'available'
        ];
        $this->db->tambah('kamar', $data);
    }

    public function hapus($id) {
        $this->db->hapus('kamar', "id_kamar = $id");
    }
}
?>
<?php
include_once 'model/koneksi.php';

class C_Layanan {
    private $db;
    public function __construct() { $this->db = new Database(); }

    public function index() {
        return $this->db->tampil('master_layanan');
    }

    public function tambah($nama, $harga, $satuan, $kategori) {
        $this->db->tambah('master_layanan', [
            'nama_layanan' => $nama,
            'harga_satuan' => $harga,
            'satuan' => $satuan,
            'kategori' => $kategori
        ]);
    }

    public function hapus($id) {
        $this->db->hapus('master_layanan', "id_layanan=$id");
    }
}
?>
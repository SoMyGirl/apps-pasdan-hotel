<?php
include_once 'model/koneksi.php';

class C_Layanan {
    private $db;
    public function __construct() { $this->db = new Database(); }

    public function index() {
        return $this->db->tampil('master_layanan');
    }

    // Fungsi Baru: Mengambil 1 data untuk diedit
    public function satu_data($id) {
        // Asumsi method tampil bisa menerima parameter WHERE, atau query manual
        // Jika class Database Anda berbeda, sesuaikan bagian ini
        $data = $this->db->tampil('master_layanan', "id_layanan='$id'");
        return isset($data[0]) ? $data[0] : null; 
    }

    public function tambah($nama, $harga, $satuan, $kategori) {
        $this->db->tambah('master_layanan', [
            'nama_layanan' => $nama,
            'harga_satuan' => $harga,
            'satuan' => $satuan,
            'kategori' => $kategori
        ]);
    }

    // Fungsi Baru: Update data
    public function update($id, $nama, $harga, $satuan, $kategori) {
        $this->db->ubah('master_layanan', [
            'nama_layanan' => $nama,
            'harga_satuan' => $harga,
            'satuan' => $satuan,
            'kategori' => $kategori
        ], "id_layanan='$id'");
    }

    public function hapus($id) {
        $this->db->hapus('master_layanan', "id_layanan=$id");
    }
}
?>
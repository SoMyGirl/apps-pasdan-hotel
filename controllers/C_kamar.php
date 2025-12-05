<?php
include_once 'model/koneksi.php';

class C_Kamar {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // --- READ DATA ---
    public function index() {
        // Menggunakan JOIN untuk mengambil nama tipe kamar dan harga
        $sql = "SELECT k.*, t.nama_tipe, t.harga_dasar 
                FROM kamar k 
                JOIN tipe_kamar t ON k.id_tipe = t.id_tipe 
                ORDER BY k.nomor_kamar ASC";
                
        // Mengembalikan array data (PDO::FETCH_ASSOC)
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTipe() {
        // Mengambil data master tipe kamar untuk Dropdown
        return $this->db->tampil('tipe_kamar');
    }

    // --- CREATE DATA ---
    public function tambah($nomor, $id_tipe) {
        // Sanitasi sederhana (opsional, tergantung wrapper database Anda)
        $nomor = htmlspecialchars($nomor);
        
        $data = [
            'nomor_kamar' => $nomor,
            'id_tipe'     => $id_tipe,
            'status'      => 'available' // Default status saat buat baru
        ];
        $this->db->tambah('kamar', $data);
    }

    // --- UPDATE DATA (Digunakan oleh Modal Edit) ---
    public function update($id, $nomor, $id_tipe) {
        $data = [
            'nomor_kamar' => $nomor,
            'id_tipe'     => $id_tipe
        ];
        
        // Parameter ke-3 adalah klausa WHERE: "id_kamar = $id"
        $this->db->ubah('kamar', $data, "id_kamar = $id");
    }
}
?>
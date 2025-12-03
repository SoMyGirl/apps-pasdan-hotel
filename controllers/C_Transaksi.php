<?php
include_once 'model/koneksi.php';

class C_Transaksi {
    private $db;
    public function __construct() { $this->db = new Database(); }

    // --- FITUR CHECKIN ---
    public function getKamarAvailable() {
        return $this->db->query("SELECT * FROM kamar JOIN tipe_kamar USING(id_tipe) WHERE status='available'");
    }

    public function prosesCheckin($nama, $hp, $id_kamar, $tgl_masuk, $durasi) {
        // 1. Hitung total biaya kamar
        $kamar = $this->db->query("SELECT harga_dasar FROM kamar JOIN tipe_kamar USING(id_tipe) WHERE id_kamar=$id_kamar")->fetch_assoc();
        $total_biaya = $kamar['harga_dasar'] * $durasi;

        // 2. Insert Transaksi
        $data = [
            'no_invoice' => 'INV-' . time(),
            'nama_tamu' => $nama,
            'no_hp' => $hp,
            'id_kamar' => $id_kamar,
            'tgl_checkin' => $tgl_masuk,
            'durasi_malam' => $durasi,
            'harga_kamar_per_malam' => $kamar['harga_dasar'],
            'total_biaya_kamar' => $total_biaya,
            'total_tagihan' => $total_biaya, // Awalnya tagihan = biaya kamar
            'status_bayar' => 'belum_bayar',
            'status_transaksi' => 'active',
            'id_user_resepsionis' => $_SESSION['user_id']
        ];
        $id_transaksi = $this->db->tambah('transaksi', $data);

        // 3. Update status kamar jadi Occupied
        $this->db->ubah('kamar', ['status' => 'occupied'], "id_kamar=$id_kamar");
        
        return $id_transaksi;
    }

    // --- FITUR LIST TAMU & KASIR ---
    public function getTamuAktif() {
        return $this->db->query("SELECT t.*, k.nomor_kamar 
                                 FROM transaksi t 
                                 JOIN kamar k ON t.id_kamar = k.id_kamar 
                                 WHERE status_transaksi = 'active'");
    }

    public function getDetailTransaksi($id) {
        return $this->db->query("SELECT t.*, k.nomor_kamar, tp.nama_tipe 
                                 FROM transaksi t 
                                 JOIN kamar k ON t.id_kamar = k.id_kamar
                                 JOIN tipe_kamar tp ON k.id_tipe = tp.id_tipe 
                                 WHERE id_transaksi = $id")->fetch_assoc();
    }

    // --- FITUR LAYANAN TAMBAHAN (POS) ---
    public function getMenuLayanan() {
        return $this->db->tampil('master_layanan');
    }

    public function tambahLayanan($id_transaksi, $id_layanan, $jumlah) {
        // Ambil harga layanan
        $layanan = $this->db->tampil('master_layanan', "id_layanan=$id_layanan")[0];
        $subtotal = $layanan['harga_satuan'] * $jumlah;

        // Masukkan ke tabel detail
        $this->db->tambah('transaksi_layanan', [
            'id_transaksi' => $id_transaksi,
            'id_layanan' => $id_layanan,
            'jumlah' => $jumlah,
            'harga_saat_ini' => $layanan['harga_satuan'],
            'subtotal' => $subtotal
        ]);

        // Update Total Tagihan di Tabel Utama
        $this->db->query("UPDATE transaksi SET 
                          total_biaya_layanan = total_biaya_layanan + $subtotal,
                          total_tagihan = total_tagihan + $subtotal 
                          WHERE id_transaksi = $id_transaksi");
        
        // Panggil fungsi cek lunas (karena tagihan nambah, status bisa berubah jadi belum lunas)
        $this->cekStatusLunas($id_transaksi);
    }

    public function getListPesanan($id_transaksi) {
        return $this->db->query("SELECT tl.*, m.nama_layanan 
                                 FROM transaksi_layanan tl 
                                 JOIN master_layanan m ON tl.id_layanan = m.id_layanan 
                                 WHERE id_transaksi = $id_transaksi");
    }

    // --- FITUR PEMBAYARAN & CHECKOUT ---
    public function bayar($id_transaksi, $jumlah, $ket) {
        $this->db->tambah('riwayat_pembayaran', [
            'id_transaksi' => $id_transaksi,
            'jumlah_bayar' => $jumlah,
            'keterangan' => $ket,
            'id_user' => $_SESSION['user_id']
        ]);
        $this->cekStatusLunas($id_transaksi);
    }

    public function getRiwayatBayar($id_transaksi) {
        return $this->db->tampil('riwayat_pembayaran', "id_transaksi=$id_transaksi");
    }

    public function cekStatusLunas($id_transaksi) {
        $trans = $this->db->tampil('transaksi', "id_transaksi=$id_transaksi")[0];
        $total_tagihan = $trans['total_tagihan'];
        
        $bayar = $this->db->query("SELECT SUM(jumlah_bayar) as total FROM riwayat_pembayaran WHERE id_transaksi=$id_transaksi")->fetch_assoc();
        $total_bayar = $bayar['total'] ?? 0;

        if ($total_bayar >= $total_tagihan) $status = 'lunas';
        elseif ($total_bayar > 0) $status = 'dp';
        else $status = 'belum_bayar';

        $this->db->ubah('transaksi', ['status_bayar' => $status], "id_transaksi=$id_transaksi");
    }

    public function checkout($id_transaksi) {
        // Cek dulu udah lunas belum
        $trans = $this->db->tampil('transaksi', "id_transaksi=$id_transaksi")[0];
        if ($trans['status_bayar'] != 'lunas') {
            return false; // Gagal checkout kalau belum lunas
        }

        // Set finished
        $this->db->ubah('transaksi', ['status_transaksi' => 'finished', 'tgl_checkout' => date('Y-m-d H:i:s')], "id_transaksi=$id_transaksi");
        
        // Set kamar jadi Dirty
        $this->db->ubah('kamar', ['status' => 'dirty'], "id_kamar=" . $trans['id_kamar']);
        
        return true;
    }
}
?>
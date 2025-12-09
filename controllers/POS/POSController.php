<?php
class POSController {
    private $db;
    public function __construct() { $this->db = new Database(); }

    // --- PERBAIKAN: Menambahkan Index untuk Redirect ---
    public function index() {
        if (isset($_GET['id_transaksi'])) {
            $id = $_GET['id_transaksi'];
            // Redirect ke halaman Checkout Payment karena UI POS ada di sana
            header("Location: index.php?modul=Checkout&aksi=payment&id=$id");
            exit;
        } else {
            // Jika tidak ada ID, kembalikan ke dashboard atau guest list
            header("Location: index.php?modul=Guest&aksi=inhouse");
            exit;
        }
    }

    // Fungsi untuk menambah pesanan (Action: add)
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_transaksi = $_GET['id']; 
            $id_layanan   = $_POST['id_layanan'];
            $jumlah       = $_POST['jumlah'];

            // 1. Ambil Data Layanan (untuk tahu harganya)
            $layanan = $this->db->query("SELECT * FROM master_layanan WHERE id_layanan = $id_layanan")->fetch();
            
            if ($layanan) {
                $harga    = $layanan['harga_satuan'];
                $subtotal = $harga * $jumlah;

                // 2. Masukkan ke Tabel Detail (transaksi_layanan)
                // Kita gunakan prepare statement biar aman
                $sql_insert = "INSERT INTO transaksi_layanan (id_transaksi, id_layanan, jumlah, harga_saat_ini, subtotal) 
                               VALUES ('$id_transaksi', '$id_layanan', '$jumlah', '$harga', '$subtotal')";
                $this->db->query($sql_insert);

                // 3. Update Total Tagihan di Tabel Utama (transaksi)
                $sql_update = "UPDATE transaksi SET total_tagihan = total_tagihan + $subtotal WHERE id_transaksi = $id_transaksi";
                $this->db->query($sql_update);

                // 4. Update Status Pembayaran (Cek apakah jadi kurang bayar?)
                $this->updateStatusBayar($id_transaksi);

                // 5. Beri Notifikasi & Balik ke Halaman Bayar
                $_SESSION['flash_type'] = 'success';
                $_SESSION['flash_message'] = 'Pesanan berhasil ditambahkan!';
            } else {
                $_SESSION['flash_type'] = 'error';
                $_SESSION['flash_message'] = 'Layanan tidak ditemukan!';
            }

            // Redirect kembali ke halaman Invoice/Checkout
            header("Location: index.php?modul=Checkout&aksi=payment&id=$id_transaksi");
            exit;
        }
    }

    // Fungsi Helper untuk Cek Lunas/Belum (Private)
    private function updateStatusBayar($id) {
        $tagihan = $this->db->query("SELECT total_tagihan FROM transaksi WHERE id_transaksi=$id")->fetch()['total_tagihan'];
        $bayar   = $this->db->query("SELECT SUM(jumlah_bayar) as tot FROM riwayat_pembayaran WHERE id_transaksi=$id")->fetch()['tot'] ?? 0;
        
        $status = ($bayar >= $tagihan) ? 'lunas' : (($bayar > 0) ? 'dp' : 'belum_bayar');
        $this->db->query("UPDATE transaksi SET status_bayar='$status' WHERE id_transaksi=$id");
    }
}
?>
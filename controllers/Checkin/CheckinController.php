<?php
class CheckinController {
    private $db;
    public function __construct() { $this->db = new Database(); }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Proses Insert
            $invoice = 'INV-'.time();
            $kamar = $this->db->query("SELECT harga_dasar FROM kamar JOIN tipe_kamar USING(id_tipe) WHERE id_kamar=".$_POST['id_kamar'])->fetch();
            $total = $kamar['harga_dasar'] * $_POST['durasi'];

            $sql = "INSERT INTO transaksi (no_invoice, nama_tamu, no_hp, id_kamar, tgl_checkin, durasi_malam, harga_kamar_per_malam, total_biaya_kamar, total_tagihan, status_transaksi, status_bayar, id_user_resepsionis) 
                    VALUES ('$invoice', '{$_POST['nama']}', '{$_POST['hp']}', '{$_POST['id_kamar']}', '{$_POST['tgl']}', '{$_POST['durasi']}', '{$kamar['harga_dasar']}', '$total', '$total', 'active', 'belum_bayar', '{$_SESSION['user_id']}')";
            
            $this->db->query($sql);
            $id_transaksi = $this->db->lastInsertId();
            
            // Update Kamar
            $this->db->query("UPDATE kamar SET status='occupied' WHERE id_kamar=".$_POST['id_kamar']);

            $_SESSION['flash_type'] = 'success';
            $_SESSION['flash_message'] = 'Checkin Berhasil!';
            header("Location: index.php?modul=Checkout&aksi=payment&id=$id_transaksi");
            exit;
        }

        // Tampilkan Form
        $kamar = $this->db->query("SELECT * FROM kamar JOIN tipe_kamar USING(id_tipe) WHERE status='available'")->fetchAll();
        $this->view('Checkin/create', ['kamar' => $kamar]);
    }

    private function view($p, $d) { extract($d); include 'views/Layout/header.php'; include 'views/Layout/sidebar.php'; include "views/$p.php"; include 'views/Layout/footer.php'; }
}
?>
<?php
class CheckoutController {
    private $db;
    public function __construct() { $this->db = new Database(); }

    public function payment() {
        $id = $_GET['id'];
        
        // 1. Handle Tambah Layanan (POS)
        // if (isset($_POST['add_pos'])) {
        //     $layanan = $this->db->query("SELECT * FROM master_layanan WHERE id_layanan=".$_POST['id_layanan'])->fetch();
        //     $subtotal = $layanan['harga_satuan'] * $_POST['jumlah'];
        //     $this->db->query("INSERT INTO transaksi_layanan (id_transaksi, id_layanan, jumlah, harga_saat_ini, subtotal) VALUES ('$id', '{$_POST['id_layanan']}', '{$_POST['jumlah']}', '{$layanan['harga_satuan']}', '$subtotal')");
        //     $this->db->query("UPDATE transaksi SET total_tagihan = total_tagihan + $subtotal WHERE id_transaksi=$id");
        //     header("Location: index.php?modul=Checkout&aksi=payment&id=$id");
        // }

        // 2. Handle Bayar
        if (isset($_POST['pay'])) {
            $this->db->query("INSERT INTO riwayat_pembayaran (id_transaksi, jumlah_bayar, keterangan, id_user) VALUES ('$id', '{$_POST['uang']}', '{$_POST['ket']}', '{$_SESSION['user_id']}')");
            $this->cekLunas($id);
            header("Location: index.php?modul=Checkout&aksi=payment&id=$id");
        }

        // 3. Handle Checkout
        if (isset($_GET['process']) && $_GET['process'] == 'checkout') {
            $t = $this->db->query("SELECT * FROM transaksi WHERE id_transaksi=$id")->fetch();
            if ($t['status_bayar'] == 'lunas') {
                $this->db->query("UPDATE transaksi SET status_transaksi='finished', tgl_checkout=NOW() WHERE id_transaksi=$id");
                $this->db->query("UPDATE kamar SET status='dirty' WHERE id_kamar=".$t['id_kamar']);
                $_SESSION['flash_type'] = 'success';
                $_SESSION['flash_message'] = 'Checkout Berhasil!';
                header("Location: index.php?modul=Dashboard&aksi=index");
            } else {
                echo "<script>alert('Belum Lunas!'); window.location='index.php?modul=Checkout&aksi=payment&id=$id'</script>";
            }
            exit;
        }

        // Ambil Data View
        $transaksi = $this->db->query("SELECT t.*, k.nomor_kamar, tp.nama_tipe FROM transaksi t JOIN kamar k USING(id_kamar) JOIN tipe_kamar tp USING(id_tipe) WHERE id_transaksi=$id")->fetch();
        $items = $this->db->query("SELECT tl.*, m.nama_layanan FROM transaksi_layanan tl JOIN master_layanan m USING(id_layanan) WHERE id_transaksi=$id")->fetchAll();
        $history = $this->db->query("SELECT * FROM riwayat_pembayaran WHERE id_transaksi=$id")->fetchAll();
        $menu = $this->db->query("SELECT * FROM master_layanan")->fetchAll();

        // Hitung Sisa
        $terbayar = 0;
        foreach($history as $h) $terbayar += $h['jumlah_bayar'];
        $sisa = $transaksi['total_tagihan'] - $terbayar;

        $this->view('Checkout/payment', compact('transaksi', 'items', 'history', 'menu', 'sisa', 'terbayar'));
    }

    private function cekLunas($id) {
        $tagihan = $this->db->query("SELECT total_tagihan FROM transaksi WHERE id_transaksi=$id")->fetch()['total_tagihan'];
        $bayar = $this->db->query("SELECT SUM(jumlah_bayar) as tot FROM riwayat_pembayaran WHERE id_transaksi=$id")->fetch()['tot'];
        
        $status = ($bayar >= $tagihan) ? 'lunas' : (($bayar > 0) ? 'dp' : 'belum_bayar');
        $this->db->query("UPDATE transaksi SET status_bayar='$status' WHERE id_transaksi=$id");
    }

    private function view($p, $d) { extract($d); include 'views/Layout/header.php'; include 'views/Layout/sidebar.php'; include "views/$p.php"; include 'views/Layout/footer.php'; }
}
?>
<?php

class CheckoutController {
    private $db;
    
    public function __construct() { 
        $this->db = new Database(); 
    }

    // 1. HALAMAN DAFTAR TAGIHAN
    public function index() {
        if (!isset($_SESSION['status_login'])) {
            header("Location: index.php?modul=Auth&aksi=login");
            exit;
        }

        $sql = "SELECT t.*, k.nomor_kamar, k.id_kamar 
                FROM transaksi t 
                JOIN kamar k ON t.id_kamar = k.id_kamar 
                WHERE status_transaksi = 'active' 
                ORDER BY tgl_checkin DESC";
        
        $data = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        $this->view('Checkout/index', ['data' => $data]);
    }

    // 2. HALAMAN DETAIL PEMBAYARAN & INVOICE
    public function payment() {
        if (!isset($_GET['id'])) header("Location: index.php?modul=Checkout&aksi=index");
        
        $id = $_GET['id'];
        
        // A. HANDLE PEMBAYARAN (POST)
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pay'])) {
            $uang = filter_input(INPUT_POST, 'uang', FILTER_SANITIZE_NUMBER_FLOAT);
            $ket  = htmlspecialchars($_POST['ket']);
            $user = $_SESSION['user_id'];

            if($uang > 0) {
                $stmt = $this->db->prepare("INSERT INTO riwayat_pembayaran (id_transaksi, jumlah_bayar, keterangan, id_user, tgl_bayar) VALUES (:id, :jml, :ket, :user, NOW())");
                $stmt->execute(['id' => $id, 'jml' => $uang, 'ket' => $ket, 'user' => $user]);
                
                $this->cekLunas($id);
                $this->flash('success', 'Pembayaran senilai Rp '.number_format($uang).' diterima.');
            }
            header("Location: index.php?modul=Checkout&aksi=payment&id=$id");
            exit;
        }

        // B. HANDLE CHECKOUT FINAL
        if (isset($_GET['process']) && $_GET['process'] == 'checkout') {
            $stmt = $this->db->prepare("SELECT * FROM transaksi WHERE id_transaksi = :id");
            $stmt->execute(['id' => $id]);
            $t = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($t && $t['status_bayar'] == 'lunas') {
                // 1. Selesaikan Transaksi
                $this->db->query("UPDATE transaksi SET status_transaksi='checkin', tgl_checkout=NOW() WHERE id_transaksi=$id");
                // 2. Ubah Status Kamar jadi Dirty
                $this->db->query("UPDATE kamar SET status='dirty' WHERE id_kamar=".$t['id_kamar']);
                
                $this->flash('success', 'Checkout Berhasil! Kamar sekarang status Dirty.');
                header("Location: index.php?modul=Dashboard&aksi=index");
            } else {
                $this->flash('error', 'Gagal Checkout! Tagihan belum lunas.');
                header("Location: index.php?modul=Checkout&aksi=payment&id=$id");
            }
            exit;
        }

        // C. AMBIL DATA UNTUK VIEW
        // Detail Transaksi
        $stmtTrx = $this->db->prepare("SELECT t.*, k.nomor_kamar, tp.nama_tipe, tp.harga_dasar 
                                       FROM transaksi t 
                                       JOIN kamar k ON t.id_kamar = k.id_kamar 
                                       JOIN tipe_kamar tp ON k.id_tipe = tp.id_tipe 
                                       WHERE id_transaksi = :id");
        $stmtTrx->execute(['id' => $id]);
        $transaksi = $stmtTrx->fetch(PDO::FETCH_ASSOC);

        // Item Layanan (POS)
        $stmtItem = $this->db->prepare("SELECT tl.*, m.nama_layanan 
                                        FROM transaksi_layanan tl 
                                        JOIN master_layanan m ON tl.id_layanan = m.id_layanan 
                                        WHERE id_transaksi = :id");
        $stmtItem->execute(['id' => $id]);
        $items = $stmtItem->fetchAll(PDO::FETCH_ASSOC);

        // Riwayat Bayar
        $stmtHist = $this->db->prepare("SELECT * FROM riwayat_pembayaran WHERE id_transaksi = :id ORDER BY tgl_bayar DESC");
        $stmtHist->execute(['id' => $id]);
        $history = $stmtHist->fetchAll(PDO::FETCH_ASSOC);

        // Menu untuk Dropdown POS
        $menu = $this->db->query("SELECT * FROM master_layanan ORDER BY kategori ASC, nama_layanan ASC")->fetchAll(PDO::FETCH_ASSOC);

        // Hitung Kalkulasi
        $terbayar = 0;
        foreach($history as $h) $terbayar += $h['jumlah_bayar'];
        $sisa = $transaksi['total_tagihan'] - $terbayar;

        // Render View
        $data = [
            'transaksi' => $transaksi,
            'items'     => $items,
            'history'   => $history,
            'menu'      => $menu,
            'sisa'      => $sisa,
            'terbayar'  => $terbayar
        ];
        $this->view('Checkout/payment', $data);
    }

    // 3. TAMBAH ITEM POS (Digabung disini agar praktis)
    public function addItem() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_transaksi = $_POST['id_transaksi'];
            $id_layanan   = $_POST['id_layanan'];
            $jumlah       = $_POST['jumlah'];

            // Ambil Harga Satuan
            $stmtLayanan = $this->db->prepare("SELECT harga_satuan FROM master_layanan WHERE id_layanan = :id");
            $stmtLayanan->execute(['id' => $id_layanan]);
            $layanan = $stmtLayanan->fetch(PDO::FETCH_ASSOC);

            if ($layanan) {
                $subtotal = $layanan['harga_satuan'] * $jumlah;
                
                // Insert ke Transaksi Layanan
                $stmtIns = $this->db->prepare("INSERT INTO transaksi_layanan (id_transaksi, id_layanan, jumlah, subtotal) VALUES (:idt, :idl, :jml, :sub)");
                $stmtIns->execute(['idt' => $id_transaksi, 'idl' => $id_layanan, 'jml' => $jumlah, 'sub' => $subtotal]);

                // Update Total Tagihan di Tabel Transaksi
                $this->updateTotalTagihan($id_transaksi);
                $this->cekLunas($id_transaksi);

                $this->flash('success', 'Item berhasil ditambahkan.');
            }
            header("Location: index.php?modul=Checkout&aksi=payment&id=$id_transaksi");
            exit;
        }
    }

    // --- HELPER FUNCTIONS ---

    private function updateTotalTagihan($id) {
        // Hitung total POS
        $stmtPos = $this->db->prepare("SELECT SUM(subtotal) as total_pos FROM transaksi_layanan WHERE id_transaksi = :id");
        $stmtPos->execute(['id' => $id]);
        $pos = $stmtPos->fetch(PDO::FETCH_ASSOC);
        $total_pos = $pos['total_pos'] ?? 0;

        // Ambil Biaya Kamar
        $stmtKamar = $this->db->prepare("SELECT total_biaya_kamar FROM transaksi WHERE id_transaksi = :id");
        $stmtKamar->execute(['id' => $id]);
        $kamar = $stmtKamar->fetch(PDO::FETCH_ASSOC);
        
        $grand_total = $kamar['total_biaya_kamar'] + $total_pos;

        // Update
        $stmtUpd = $this->db->prepare("UPDATE transaksi SET total_tagihan = :total WHERE id_transaksi = :id");
        $stmtUpd->execute(['total' => $grand_total, 'id' => $id]);
    }

    private function cekLunas($id) {
        $stmt = $this->db->prepare("SELECT total_tagihan, (SELECT SUM(jumlah_bayar) FROM riwayat_pembayaran WHERE id_transaksi = :id) as total_bayar FROM transaksi WHERE id_transaksi = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        $tagihan = $data['total_tagihan'];
        $bayar   = $data['total_bayar'] ?? 0;

        $status = ($bayar >= $tagihan) ? 'lunas' : (($bayar > 0) ? 'dp' : 'belum_bayar');
        
        $stmtUpd = $this->db->prepare("UPDATE transaksi SET status_bayar = :stat WHERE id_transaksi = :id");
        $stmtUpd->execute(['stat' => $status, 'id' => $id]);
    }

    private function flash($type, $msg) {
        if(session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['flash_type'] = $type;
        $_SESSION['flash_message'] = $msg;
    }

    private function view($p, $d=[]) { 
        extract($d); 
        include 'views/Layout/header.php'; 
        include 'views/Layout/sidebar.php'; 
        include "views/$p.php"; 
        include 'views/Layout/footer.php'; 
    }
}
?>
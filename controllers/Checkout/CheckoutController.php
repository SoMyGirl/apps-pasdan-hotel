<?php
class CheckoutController {
    private $db;
    
    public function __construct() { 
        $this->db = new Database(); 
    }

    public function index() {
        if (!isset($_SESSION['status_login'])) {
            header("Location: index.php?modul=Auth&aksi=login");
            exit;
        }

        $filter = $_GET['filter'] ?? 'all'; 
        
        // Panggil VIEW v_invoice_list (yang sudah diperbaiki di Langkah 1)
        $sql = "SELECT * FROM v_invoice_list WHERE status_transaksi = 'active' ORDER BY tgl_checkin DESC";
        
        $raw_data = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        $data_to_view = [];
        $current_time = time();

        // Filter Logic
        foreach ($raw_data as $d) {
            // Kolom ini sekarang sudah ada di View, jadi tidak akan error lagi
            $checkout_timestamp = strtotime($d['tgl_estimasi_checkout']);
            
            $is_unpaid = ($d['status_bayar'] != 'lunas');
            $is_overdue = ($current_time > $checkout_timestamp && $is_unpaid);

            $pass_filter = false;
            if ($filter == 'overdue' && $is_overdue) {
                $pass_filter = true;
            } elseif ($filter == 'unpaid' && $is_unpaid) {
                $pass_filter = true;
            } elseif ($filter == 'all') {
                $pass_filter = true;
            }

            if ($pass_filter) {
                $data_to_view[] = $d;
            }
        }

        $this->view('Checkout/index', ['data' => $data_to_view, 'filter' => $filter]);
    }

    // ... (Fungsi payment, addItem, extend, cancel tetap sama seperti sebelumnya) ...
    
    public function payment() {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header("Location: index.php?modul=Checkout&aksi=index"); exit; 
        }
        $id = $_GET['id'];
        
        // Handle Pembayaran
        if(isset($_POST['pay'])) {
            $u = filter_input(INPUT_POST, 'uang', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $k = $_POST['ket'];
            
            // Validasi Backend: Ubah jadi DP jika uang kurang
            $trx = $this->db->query("SELECT total_tagihan FROM transaksi WHERE id_transaksi=$id")->fetch();
            $paid = $this->db->query("SELECT SUM(jumlah_bayar) FROM riwayat_pembayaran WHERE id_transaksi=$id")->fetchColumn() ?: 0;
            $sisa = $trx['total_tagihan'] - $paid;

            if ($k == 'Pelunasan' && $u < $sisa) $k = 'Deposit / DP';
            elseif ($k == 'Deposit / DP' && $u >= $sisa) $k = 'Pelunasan';
            
            $this->db->prepare("INSERT INTO riwayat_pembayaran (id_transaksi,id_user,jumlah_bayar,keterangan) VALUES (?,?,?,?)")
                     ->execute([$id, $_SESSION['user_id'], $u, $k]);
            
            $this->cekLunas($id);
            $_SESSION['flash_type'] = 'success';
            $_SESSION['flash_message'] = 'Pembayaran berhasil disimpan.';
            header("Location: index.php?modul=Checkout&aksi=payment&id=$id"); exit;
        }

        // Handle Checkout Final
        if(isset($_GET['process']) && $_GET['process']=='checkout') {
            $this->db->query("UPDATE transaksi SET status_transaksi='finished', tgl_checkout=NOW() WHERE id_transaksi=$id");
            $kms = $this->db->query("SELECT id_kamar FROM transaksi_kamar WHERE id_transaksi=$id")->fetchAll(PDO::FETCH_COLUMN);
            foreach($kms as $k) {
                $this->db->query("UPDATE kamar SET status='dirty' WHERE id_kamar=$k");
                $this->db->prepare("INSERT INTO log_housekeeping (id_user, id_kamar, status_sebelum, status_sesudah) VALUES (?, ?, 'occupied', 'dirty')")->execute([$_SESSION['user_id'], $k]);
            }
            $_SESSION['flash_type'] = 'success'; $_SESSION['flash_message'] = 'Checkout Berhasil!';
            header("Location: index.php?modul=Dashboard&aksi=index"); exit;
        }

        // Ambil Data Detail (Query Manual tetap dipakai di sini karena detail spesifik)
        $sql = "SELECT t.*, tm.no_hp, tm.no_identitas, 
               GROUP_CONCAT(DISTINCT k.nomor_kamar SEPARATOR ', ') as nomor_kamar, 
               GROUP_CONCAT(DISTINCT tp.nama_tipe SEPARATOR ', ') as nama_tipe, 
               MAX(tk.durasi_malam) as durasi_malam, 
               SUM(tk.subtotal_kamar) as total_biaya_kamar
               FROM transaksi t 
               JOIN tamu tm ON t.id_tamu=tm.id_tamu
               JOIN transaksi_kamar tk ON t.id_transaksi=tk.id_transaksi
               JOIN kamar k ON tk.id_kamar=k.id_kamar
               JOIN tipe_kamar tp ON k.id_tipe=tp.id_tipe
               WHERE t.id_transaksi = :id 
               GROUP BY t.id_transaksi";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $transaksi = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$transaksi) { header("Location: index.php?modul=Checkout&aksi=index"); exit; }

        $items = $this->db->query("SELECT tl.*, m.nama_layanan FROM transaksi_layanan tl JOIN master_layanan m USING(id_layanan) WHERE id_transaksi=$id")->fetchAll(PDO::FETCH_ASSOC);
        $history = $this->db->query("SELECT * FROM riwayat_pembayaran WHERE id_transaksi=$id ORDER BY tgl_bayar DESC")->fetchAll(PDO::FETCH_ASSOC);
        $menu = $this->db->query("SELECT * FROM master_layanan ORDER BY kategori ASC, nama_layanan ASC")->fetchAll(PDO::FETCH_ASSOC);
        
        $terbayar = 0; foreach($history as $h) $terbayar += $h['jumlah_bayar'];
        $sisa = $transaksi['total_tagihan'] - $terbayar;
        
        $this->view('Checkout/payment', compact('transaksi','items','history','menu','sisa'));
    }

    public function addItem() {
        if($_SERVER['REQUEST_METHOD']=='POST') {
            $id = $_POST['id_transaksi'];
            $idl = $_POST['id_layanan'];
            $jml = $_POST['jumlah'];
            $lay = $this->db->query("SELECT * FROM master_layanan WHERE id_layanan=$idl")->fetch();
            if ($lay) {
                $sub = $lay['harga_satuan']*$jml;
                $this->db->prepare("INSERT INTO transaksi_layanan (id_transaksi,id_layanan,jumlah,harga_saat_ini,subtotal) VALUES (?,?,?,?,?)")->execute([$id,$idl,$jml,$lay['harga_satuan'],$sub]);
                $this->updateTotal($id); $this->cekLunas($id);
                $_SESSION['flash_type'] = 'success'; $_SESSION['flash_message'] = 'Item ditambahkan.';
            }
            header("Location: index.php?modul=Checkout&aksi=payment&id=$id");
        }
    }

    public function extend() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_GET['id']; $add = $_POST['add_night'];
            $dets = $this->db->query("SELECT * FROM transaksi_kamar WHERE id_transaksi=$id")->fetchAll(PDO::FETCH_ASSOC);
            foreach($dets as $d) {
                $biaya = $d['harga_per_malam'] * $add; 
                $this->db->query("UPDATE transaksi_kamar SET durasi_malam=durasi_malam+$add, subtotal_kamar=subtotal_kamar+$biaya WHERE id_detail_transaksi=".$d['id_detail_transaksi']);
            }
            $this->updateTotal($id); $this->cekLunas($id);
            $_SESSION['flash_type'] = 'success'; $_SESSION['flash_message'] = 'Durasi diperpanjang.';
            header("Location: index.php?modul=Checkout&aksi=payment&id=$id");
        }
    }

    public function cancel() {
        if (!isset($_GET['id'])) { header("Location: index.php"); exit; }
        $id = $_GET['id'];
        $kamars = $this->db->query("SELECT id_kamar FROM transaksi_kamar WHERE id_transaksi=$id")->fetchAll(PDO::FETCH_COLUMN);
        $this->db->query("UPDATE transaksi SET status_transaksi='batal' WHERE id_transaksi=$id");
        foreach($kamars as $kid) $this->db->query("UPDATE kamar SET status='available' WHERE id_kamar=$kid");
        $_SESSION['flash_type'] = 'success'; $_SESSION['flash_message'] = 'Transaksi dibatalkan.';
        header("Location: index.php?modul=Dashboard&aksi=index"); exit;
    }

    private function updateTotal($id) {
        $kamar = $this->db->query("SELECT COALESCE(SUM(subtotal_kamar),0) FROM transaksi_kamar WHERE id_transaksi=$id")->fetchColumn();
        $layanan = $this->db->query("SELECT COALESCE(SUM(subtotal),0) FROM transaksi_layanan WHERE id_transaksi=$id")->fetchColumn();
        $this->db->query("UPDATE transaksi SET total_tagihan=".($kamar+$layanan)." WHERE id_transaksi=$id");
    }

    private function cekLunas($id) {
        $tag = $this->db->query("SELECT total_tagihan FROM transaksi WHERE id_transaksi=$id")->fetchColumn();
        $byr = $this->db->query("SELECT SUM(jumlah_bayar) FROM riwayat_pembayaran WHERE id_transaksi=$id")->fetchColumn() ?: 0;
        $st = ($byr >= $tag - 100) ? 'lunas' : (($byr>0)?'dp':'belum_bayar');
        $this->db->query("UPDATE transaksi SET status_bayar='$st' WHERE id_transaksi=$id");
    }
    
    private function view($p, $d) { extract($d); include 'views/Layout/header.php'; include 'views/Layout/sidebar.php'; include "views/$p.php"; include 'views/Layout/footer.php'; }
}
?>
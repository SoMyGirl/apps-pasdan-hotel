<?php
class POSController {
    private $db;
    
    public function __construct() { 
        $this->db = new Database(); 
    }

    // Redirect ke halaman pembayaran (UI POS ada di sana)
    public function index() {
        if (isset($_GET['id_transaksi'])) {
            $id = $_GET['id_transaksi'];
            header("Location: index.php?modul=Checkout&aksi=payment&id=$id");
        } else {
            header("Location: index.php?modul=Guest&aksi=inhouse");
        }
        exit;
    }

    // Action: Tambah Pesanan
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // PERBAIKAN 1: Ambil ID dari POST (lebih aman & konsisten dengan Form)
            // Pastikan di view ada <input type="hidden" name="id_transaksi" value="...">
            $id_transaksi = $_POST['id_transaksi'] ?? $_GET['id']; 
            $id_layanan   = $_POST['id_layanan'];
            $jumlah       = $_POST['jumlah'];

            // 1. Ambil Data Layanan (Gunakan Prepared Statement)
            $stmtLay = $this->db->prepare("SELECT * FROM master_layanan WHERE id_layanan = :id");
            $stmtLay->execute(['id' => $id_layanan]);
            $layanan = $stmtLay->fetch(PDO::FETCH_ASSOC); // Gunakan fetch assoc agar aman
            
            if ($layanan) {
                $harga    = $layanan['harga_satuan'];
                $subtotal = $harga * $jumlah;

                // 2. Insert ke Detail (PERBAIKAN 2: Anti SQL Injection)
                $sql_insert = "INSERT INTO transaksi_layanan (id_transaksi, id_layanan, jumlah, harga_saat_ini, subtotal) 
                               VALUES (:idt, :idl, :jml, :hrg, :sub)";
                
                $stmtIns = $this->db->prepare($sql_insert);
                $stmtIns->execute([
                    'idt' => $id_transaksi,
                    'idl' => $id_layanan,
                    'jml' => $jumlah,
                    'hrg' => $harga,
                    'sub' => $subtotal
                ]);

                // 3. Update Total Tagihan (Helper Function)
                $this->recalculateTotal($id_transaksi);

                // 4. Update Status Bayar
                $this->updateStatusBayar($id_transaksi);

                $_SESSION['flash_type'] = 'success';
                $_SESSION['flash_message'] = 'Pesanan berhasil ditambahkan!';
            } else {
                $_SESSION['flash_type'] = 'error';
                $_SESSION['flash_message'] = 'Layanan tidak ditemukan!';
            }

            header("Location: index.php?modul=Checkout&aksi=payment&id=$id_transaksi");
            exit;
        }
    }

    // Helper: Hitung Ulang Total Tagihan (Agar sinkron antara Kamar + Layanan)
    private function recalculateTotal($id) {
        // Hitung total kamar
        $kamar = $this->db->query("SELECT COALESCE(SUM(subtotal_kamar), 0) FROM transaksi_kamar WHERE id_transaksi=$id")->fetchColumn();
        // Hitung total layanan
        $layanan = $this->db->query("SELECT COALESCE(SUM(subtotal), 0) FROM transaksi_layanan WHERE id_transaksi=$id")->fetchColumn();
        
        $total = $kamar + $layanan;
        
        $this->db->prepare("UPDATE transaksi SET total_tagihan = :total WHERE id_transaksi = :id")
                 ->execute(['total' => $total, 'id' => $id]);
    }

    // Helper: Cek Status Lunas
    private function updateStatusBayar($id) {
        // Gunakan prepare statement untuk keamanan
        $stmt = $this->db->prepare("SELECT total_tagihan FROM transaksi WHERE id_transaksi = :id");
        $stmt->execute(['id' => $id]);
        $trx = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($trx) {
            $tagihan = $trx['total_tagihan'];
            
            $stmtBayar = $this->db->prepare("SELECT SUM(jumlah_bayar) as tot FROM riwayat_pembayaran WHERE id_transaksi = :id");
            $stmtBayar->execute(['id' => $id]);
            $bayar = $stmtBayar->fetch(PDO::FETCH_ASSOC)['tot'] ?? 0;
            
            $status = ($bayar >= $tagihan) ? 'lunas' : (($bayar > 0) ? 'dp' : 'belum_bayar');
            
            $this->db->prepare("UPDATE transaksi SET status_bayar = :stat WHERE id_transaksi = :id")
                     ->execute(['stat' => $status, 'id' => $id]);
        }
    }
}
?>
<?php
class CheckinController {
    private $db;
    public function __construct() { $this->db = new Database(); }

    public function create() {
        // --- HANDLE SUBMIT CHECK-IN (Tetap Sama) ---
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['process_checkin'])) {
            try {
                $this->db->conn->beginTransaction();

                $id_tamu = $_POST['id_tamu'];
                if (empty($id_tamu)) throw new Exception("Data tamu belum dipilih!");

                $tamuInfo = $this->db->query("SELECT * FROM tamu WHERE id_tamu = $id_tamu")->fetch();
                $tgl    = $_POST['tgl'];
                $durasi = $_POST['durasi'];
                $rooms  = explode(',', $_POST['id_kamar']);
                $uid    = $_SESSION['user_id'];

                $inv = 'INV-' . date('ymd') . rand(1000, 9999);
                $stmt = $this->db->prepare("INSERT INTO transaksi (no_invoice, id_user_resepsionis, id_tamu, nama_tamu, tgl_checkin, status_transaksi, status_bayar) VALUES (:inv, :uid, :tid, :nm, :tgl, 'active', 'belum_bayar')");
                $stmt->execute(['inv' => $inv, 'uid' => $uid, 'tid' => $id_tamu, 'nm' => $tamuInfo['nama_tamu'], 'tgl' => $tgl]);
                $id_trx = $this->db->lastInsertId();

                $total = 0;
                foreach($rooms as $rid) {
                    if(empty($rid)) continue;
                    $k = $this->db->query("SELECT harga_dasar FROM kamar JOIN tipe_kamar USING(id_tipe) WHERE id_kamar=$rid")->fetch();
                    $sub = $k['harga_dasar'] * $durasi;
                    $total += $sub;
                    $this->db->prepare("INSERT INTO transaksi_kamar (id_transaksi, id_kamar, harga_per_malam, durasi_malam, subtotal_kamar) VALUES (?,?,?,?,?)")->execute([$id_trx, $rid, $k['harga_dasar'], $durasi, $sub]);
                    $this->db->query("UPDATE kamar SET status='occupied' WHERE id_kamar=$rid");
                }

                $this->db->query("UPDATE transaksi SET total_tagihan=$total WHERE id_transaksi=$id_trx");
                $this->db->conn->commit();
                
                header("Location: index.php?modul=Checkout&aksi=payment&id=$id_trx");
                exit;

            } catch (Exception $e) {
                $this->db->conn->rollBack();
                echo "<script>alert('Gagal: ".$e->getMessage()."'); window.location.href='index.php?modul=Checkin&aksi=create';</script>";
                exit;
            }
        }

        // View Data
        $kamar = $this->db->query("SELECT * FROM kamar JOIN tipe_kamar USING(id_tipe) WHERE status='available'")->fetchAll(PDO::FETCH_ASSOC);
        $tipe  = $this->db->query("SELECT * FROM tipe_kamar")->fetchAll(PDO::FETCH_ASSOC);
        $tamu  = $this->db->query("SELECT * FROM tamu ORDER BY nama_tamu ASC")->fetchAll(PDO::FETCH_ASSOC);

        $this->view('Checkin/create', ['kamar'=>$kamar, 'tipe'=>$tipe, 'tamu'=>$tamu]);
    }

    // --- UPDATE: TAMBAH TAMU DENGAN JENIS IDENTITAS ---
    public function ajaxAddGuest() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama  = htmlspecialchars($_POST['nama']);
            $jenis = htmlspecialchars($_POST['jenis']); // Input Baru
            $nik   = htmlspecialchars($_POST['nik']);
            $hp    = htmlspecialchars($_POST['hp']);

            try {
                // Cek NIK Duplicate
                $cek = $this->db->query("SELECT id_tamu FROM tamu WHERE no_identitas='$nik'")->fetch();
                if ($cek) {
                    echo json_encode(['status'=>'error', 'message'=>'Nomor Identitas sudah terdaftar!']);
                    exit;
                }

                $stmt = $this->db->prepare("INSERT INTO tamu (nama_tamu, jenis_identitas, no_identitas, no_hp) VALUES (?,?,?,?)");
                $stmt->execute([$nama, $jenis, $nik, $hp]);
                $id = $this->db->lastInsertId();

                echo json_encode([
                    'status' => 'success', 
                    'data' => [
                        'id_tamu'=>$id, 
                        'nama_tamu'=>$nama, 
                        'jenis_identitas'=>$jenis,
                        'no_identitas'=>$nik, 
                        'no_hp'=>$hp
                    ]
                ]);
            } catch (Exception $e) {
                echo json_encode(['status'=>'error', 'message'=>$e->getMessage()]);
            }
        }
    }

    private function view($p, $d) { extract($d); include 'views/Layout/header.php'; include 'views/Layout/sidebar.php'; include "views/$p.php"; include 'views/Layout/footer.php'; }
}
?>
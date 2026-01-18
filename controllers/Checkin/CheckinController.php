<?php
class CheckinController {
    private $db;
    public function __construct() { 
        $this->db = new Database(); 
        if (session_status() == PHP_SESSION_NONE) session_start();
    }

    public function create() {
        // --- HANDLE SUBMIT CHECK-IN ---
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['process_checkin'])) {
            try {
                $this->db->conn->beginTransaction();

                // 1. Validasi & Sanitasi
                $id_tamu = filter_input(INPUT_POST, 'id_tamu', FILTER_SANITIZE_NUMBER_INT);
                if (empty($id_tamu)) throw new Exception("Data tamu belum dipilih!");

                // 2. Ambil Info Tamu
                $stmtTamu = $this->db->prepare("SELECT * FROM tamu WHERE id_tamu = ?");
                $stmtTamu->execute([$id_tamu]);
                $tamuInfo = $stmtTamu->fetch();
                if (!$tamuInfo) throw new Exception("Tamu tidak valid.");

                $tgl    = $_POST['tgl'];
                $durasi = (int)$_POST['durasi'];
                $rooms  = explode(',', $_POST['id_kamar']);
                $uid    = $_SESSION['user_id'] ?? 1; // Fallback jika session kosong

                // 3. Insert Transaksi Header
                $inv = 'INV-' . date('ymd') . rand(1000, 9999);
                $sqlTrx = "INSERT INTO transaksi (no_invoice, id_user_resepsionis, id_tamu, nama_tamu, no_hp, tgl_checkin, status_transaksi, status_bayar) 
                           VALUES (:inv, :uid, :tid, :nm, :hp, :tgl, 'active', 'belum_bayar')";
                $stmt = $this->db->prepare($sqlTrx);
                $stmt->execute([
                    'inv' => $inv, 'uid' => $uid, 'tid' => $id_tamu, 
                    'nm' => $tamuInfo['nama_tamu'], 'hp' => $tamuInfo['no_hp'], 'tgl' => $tgl
                ]);
                $id_trx = $this->db->lastInsertId();

                // 4. Insert Detail Kamar
                $total = 0;
                $stmtGetHarga = $this->db->prepare("SELECT harga_dasar FROM kamar JOIN tipe_kamar USING(id_tipe) WHERE id_kamar = ?");
                $stmtInsDetail = $this->db->prepare("INSERT INTO transaksi_kamar (id_transaksi, id_kamar, harga_per_malam, durasi_malam, subtotal_kamar) VALUES (?,?,?,?,?)");
                $stmtUpdKamar = $this->db->prepare("UPDATE kamar SET status='occupied' WHERE id_kamar = ?");

                foreach($rooms as $rid) {
                    if(empty($rid)) continue;
                    
                    $stmtGetHarga->execute([$rid]);
                    $k = $stmtGetHarga->fetch();
                    
                    $sub = $k['harga_dasar'] * $durasi;
                    $total += $sub;

                    $stmtInsDetail->execute([$id_trx, $rid, $k['harga_dasar'], $durasi, $sub]);
                    $stmtUpdKamar->execute([$rid]);
                }

                // 5. Update Total
                $this->db->prepare("UPDATE transaksi SET total_tagihan = ? WHERE id_transaksi = ?")->execute([$total, $id_trx]);
                
                $this->db->conn->commit();
                
                // REDIRECT KE PRINT REGISTRATION CARD
                header("Location: index.php?modul=Checkin&aksi=printRegistration&id=$id_trx");
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

    // --- FITUR TAMBAH TAMU LENGKAP + FOTO ---
    public function ajaxAddGuest() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $nama   = htmlspecialchars($_POST['nama']);
                $gender = htmlspecialchars($_POST['gender']);
                $jenis  = htmlspecialchars($_POST['jenis']); 
                $nik    = htmlspecialchars($_POST['nik']);
                $hp     = htmlspecialchars($_POST['hp']);
                $email  = htmlspecialchars($_POST['email']);
                $alamat = htmlspecialchars($_POST['alamat']);
                $tgl_lahir = htmlspecialchars($_POST['tgl_lahir']);

                // Cek NIK
                $stmtCek = $this->db->prepare("SELECT id_tamu FROM tamu WHERE no_identitas = ?");
                $stmtCek->execute([$nik]);
                if ($stmtCek->fetch()) throw new Exception('Nomor Identitas sudah terdaftar!');

                // Upload Foto
                $fotoName = null;
                if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                    if (!in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])) throw new Exception('Format foto harus JPG/PNG');
                    
                    $targetDir = "assets/uploads/tamu/";
                    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                    
                    $fotoName = time() . '_' . rand(100,999) . '.' . $ext;
                    move_uploaded_file($_FILES['foto']['tmp_name'], $targetDir . $fotoName);
                }

                $sql = "INSERT INTO tamu (nama_tamu, gender, jenis_identitas, no_identitas, no_hp, email, alamat, tanggal_lahir, foto_identitas) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$nama, $gender, $jenis, $nik, $hp, $email, $alamat, $tgl_lahir, $fotoName]);
                
                echo json_encode([
                    'status' => 'success', 
                    'data' => [
                        'id_tamu' => $this->db->lastInsertId(), 
                        'nama_tamu' => $nama,
                        'jenis_identitas' => $jenis,
                        'no_identitas' => $nik,
                        'no_hp' => $hp
                    ]
                ]);

            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['status'=>'error', 'message'=>$e->getMessage()]);
            }
        }
    }

    // --- PRINT REGISTRATION CARD ---
    public function printRegistration() {
        $id = $_GET['id'] ?? 0;
        
        $sql = "SELECT t.*, tm.alamat, tm.gender, tm.jenis_identitas, tm.no_identitas, tm.email, tm.tanggal_lahir 
                FROM transaksi t 
                JOIN tamu tm ON t.id_tamu = tm.id_tamu 
                WHERE t.id_transaksi = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $trx = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$trx) die("Data Transaksi tidak ditemukan.");

        $sqlKamar = "SELECT tk.*, k.nomor_kamar, tp.nama_tipe 
                     FROM transaksi_kamar tk 
                     JOIN kamar k ON tk.id_kamar = k.id_kamar 
                     JOIN tipe_kamar tp ON k.id_tipe = tp.id_tipe 
                     WHERE tk.id_transaksi = ?";
        $stmtKamar = $this->db->prepare($sqlKamar);
        $stmtKamar->execute([$id]);
        $detailKamar = $stmtKamar->fetchAll(PDO::FETCH_ASSOC);

        include 'views/Checkin/print_registration.php';
    }

    private function view($p, $d) { extract($d); include 'views/Layout/header.php'; include 'views/Layout/sidebar.php'; include "views/$p.php"; include 'views/Layout/footer.php'; }
}
?>
<?php

class CheckinController {
    private $db;
    
    public function __construct() { 
        $this->db = new Database(); 
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 1. SANITASI & VALIDASI DASAR
            $nama = htmlspecialchars($_POST['nama']);
            $hp = htmlspecialchars($_POST['hp']);
            $id_kamar = $_POST['id_kamar'];
            $tgl_checkin = $_POST['tgl'];
            $durasi = $_POST['durasi'];
            $user_id = $_SESSION['user_id']; // Pastikan session sudah start di index.php

            // 2. AMBIL DATA KAMAR (Harga & Validasi Status)
            $stmtKamar = $this->db->prepare("SELECT harga_dasar FROM kamar JOIN tipe_kamar USING(id_tipe) WHERE id_kamar = :id");
            $stmtKamar->execute(['id' => $id_kamar]);
            $dataKamar = $stmtKamar->fetch(PDO::FETCH_ASSOC);

            if (!$dataKamar) {
                // Handle error jika kamar tidak valid
                header("Location: index.php?modul=Checkin&aksi=create");
                exit;
            }

            // 3. HITUNG TOTAL
            $harga_per_malam = $dataKamar['harga_dasar'];
            $total_tagihan = $harga_per_malam * $durasi;
            $invoice = 'INV-' . time() . mt_rand(10,99); // Invoice lebih unik

            // 4. INSERT TRANSAKSI (Gunakan Prepared Statement agar AMAN)
            $sql = "INSERT INTO transaksi 
                    (no_invoice, nama_tamu, no_hp, id_kamar, tgl_checkin, durasi_malam, harga_kamar_per_malam, total_biaya_kamar, total_tagihan, status_transaksi, status_bayar, id_user_resepsionis) 
                    VALUES 
                    (:inv, :nama, :hp, :id_kamar, :tgl, :durasi, :harga, :total, :total, 'checkin', 'belum_bayar', :user)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'inv' => $invoice,
                'nama' => $nama,
                'hp' => $hp,
                'id_kamar' => $id_kamar,
                'tgl' => $tgl_checkin,
                'durasi' => $durasi,
                'harga' => $harga_per_malam,
                'total' => $total_tagihan,
                'user' => $user_id
            ]);

            $id_transaksi = $this->db->lastInsertId();
            
            // 5. UPDATE STATUS KAMAR
            $stmtUpdate = $this->db->prepare("UPDATE kamar SET status='occupied' WHERE id_kamar = :id");
            $stmtUpdate->execute(['id' => $id_kamar]);

            // 6. REDIRECT
            if(isset($_SESSION)) {
                $_SESSION['flash_type'] = 'success';
                $_SESSION['flash_message'] = 'Check-in Berhasil! Silakan lanjut ke pembayaran.';
            }
            header("Location: index.php?modul=Checkout&aksi=payment&id=$id_transaksi");
            exit;
        }

        // TAMPILKAN FORM
        // Ambil kamar available + join tipe untuk info harga di UI
        $query = "SELECT k.id_kamar, k.nomor_kamar, t.nama_tipe, t.harga_dasar, t.deskripsi 
                  FROM kamar k 
                  JOIN tipe_kamar t ON k.id_tipe = t.id_tipe 
                  WHERE k.status = 'available' 
                  ORDER BY k.nomor_kamar ASC";
        
        // Note: Sesuaikan method query/fetchAll dengan class Database Anda
        // Jika class Database Anda menggunakan PDO native:
        $kamar = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $this->view('Checkin/create', ['kamar' => $kamar]);
    }

    private function view($p, $d) { 
        extract($d); 
        include 'views/Layout/header.php'; 
        include 'views/Layout/sidebar.php'; 
        include "views/$p.php"; 
        include 'views/Layout/footer.php'; 
    }
}
?>
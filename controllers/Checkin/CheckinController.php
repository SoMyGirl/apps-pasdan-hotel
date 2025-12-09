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
            $nik = htmlspecialchars($_POST['nik']); // <<< NIK/Identitas Baru
            $hp = htmlspecialchars($_POST['hp']);
            $id_kamar = $_POST['id_kamar'];
            $tgl_checkin = $_POST['tgl'];
            $durasi = $_POST['durasi'];
            $user_id = $_SESSION['user_id']; 

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
            $invoice = 'INV-' . time() . mt_rand(10,99); 

            // 4. INSERT TRANSAKSI (Gunakan Prepared Statement agar AMAN)
            // Tambahkan kolom nik_tamu dan ubah status_transaksi ke 'active'
            $sql = "INSERT INTO transaksi 
                    (no_invoice, nama_tamu, nik_tamu, no_hp, id_kamar, tgl_checkin, durasi_malam, harga_kamar_per_malam, total_biaya_kamar, total_tagihan, status_transaksi, status_bayar, id_user_resepsionis) 
                    VALUES 
                    (:inv, :nama, :nik, :hp, :id_kamar, :tgl, :durasi, :harga, :total, :total, 'active', 'belum_bayar', :user)"; // 'active' for current checkins
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'inv' => $invoice,
                'nama' => $nama,
                'nik' => $nik, // <<< NIK Disimpan
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
        $queryKamar = "SELECT k.id_kamar, k.nomor_kamar, t.id_tipe, t.nama_tipe, t.harga_dasar 
                       FROM kamar k 
                       JOIN tipe_kamar t ON k.id_tipe = t.id_tipe 
                       WHERE k.status = 'available' 
                       ORDER BY k.nomor_kamar ASC";
        
        $kamar = $this->db->query($queryKamar)->fetchAll(PDO::FETCH_ASSOC);

        // Ambil semua tipe kamar untuk filter modal
        $listTipe = $this->db->query("SELECT id_tipe, nama_tipe FROM tipe_kamar ORDER BY nama_tipe ASC")->fetchAll(PDO::FETCH_ASSOC);

        $this->view('Checkin/create', ['kamar' => $kamar, 'listTipe' => $listTipe]);
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
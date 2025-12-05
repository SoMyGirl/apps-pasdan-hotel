<?php
// Load Koneksi
require_once 'model/koneksi.php';

echo "<h1>🕵️ MODE DETEKTIF DATABASE</h1>";
echo "<hr>";

try {
    $db = new Database();
    
    // 1. CEK KONEKSI SERVER
    echo "<h3>1. Cek Server yang Terhubung</h3>";
    // Tampilkan kita sedang connect ke mana
    $statusServer = $db->query("SELECT @@hostname as host, DATABASE() as db_name")->fetch(PDO::FETCH_ASSOC);
    echo "Terhubung ke Host: <strong>" . $statusServer['host'] . "</strong><br>";
    echo "Nama Database: <strong>" . $statusServer['db_name'] . "</strong><br>";
    echo "<p style='color:red'><em>*Pastikan ini server yang sama tempat kamu isi data! (Kalau ini Railway tapi kamu isi data di phpMyAdmin XAMPP, ya pasti kosong)</em></p>";

    // 2. CEK TABEL TIPE_KAMAR
    echo "<hr><h3>2. Cek Tabel Tipe Kamar</h3>";
    $tipe = $db->query("SELECT * FROM tipe_kamar")->fetchAll(PDO::FETCH_ASSOC);
    if(empty($tipe)) {
        echo "<span style='color:red'>[KOSONG] Tabel tipe_kamar kosong!</span>";
    } else {
        echo "<table border='1' cellpadding='5'><tr><th>ID</th><th>Nama</th></tr>";
        foreach($tipe as $t) {
            echo "<tr><td>{$t['id_tipe']}</td><td>{$t['nama_tipe']}</td></tr>";
        }
        echo "</table>";
    }

    // 3. CEK TABEL KAMAR (TANPA WHERE)
    echo "<hr><h3>3. Cek Semua Kamar (Raw Data)</h3>";
    $kamar = $db->query("SELECT id_kamar, nomor_kamar, id_tipe, status FROM kamar")->fetchAll(PDO::FETCH_ASSOC);
    
    if(empty($kamar)) {
        echo "<span style='color:red'>[KOSONG] Tabel kamar kosong melompong!</span>";
    } else {
        echo "<table border='1' cellpadding='5'><tr><th>No Kamar</th><th>ID Tipe</th><th>Status (Panjang Karakter)</th><th>Analisa</th></tr>";
        
        foreach($kamar as $k) {
            $statusRaw = $k['status'];
            $len = strlen($statusRaw);
            
            // Analisa Status
            $analisa = "";
            if($statusRaw === 'available') {
                $analisa = "<span style='color:green'>✅ OKE (Siap Tampil)</span>";
            } else if(trim(strtolower($statusRaw)) == 'available') {
                $analisa = "<span style='color:orange'>⚠️ MASALAH SPASI/HURUF BESAR (Perlu diperbaiki)</span>";
            } else {
                $analisa = "<span style='color:gray'>Status bukan available</span>";
            }

            // Analisa Relasi
            $tipeFound = false;
            foreach($tipe as $t) {
                if($t['id_tipe'] == $k['id_tipe']) $tipeFound = true;
            }
            if(!$tipeFound) {
                $analisa .= " <br><strong style='color:red'>❌ RELASI PUTUS (ID Tipe {$k['id_tipe']} tidak ada di tabel tipe_kamar)</strong>";
            }

            echo "<tr>";
            echo "<td>{$k['nomor_kamar']}</td>";
            echo "<td>{$k['id_tipe']}</td>";
            echo "<td>'{$statusRaw}' (Length: $len)</td>";
            echo "<td>{$analisa}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    // 4. TES QUERY JOIN FINAL
    echo "<hr><h3>4. Tes Query Aplikasi (Final Result)</h3>";
    $sqlFinal = "SELECT k.nomor_kamar, tk.nama_tipe 
                 FROM kamar k 
                 JOIN tipe_kamar tk ON k.id_tipe = tk.id_tipe 
                 WHERE k.status = 'available'"; // Query Original
                 
    $hasil = $db->query($sqlFinal)->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Jumlah data yang bisa diambil PHP: <strong>" . count($hasil) . "</strong><br>";
    if(count($hasil) > 0) {
        echo "<pre>"; print_r($hasil); echo "</pre>";
    } else {
        echo "<strong style='color:red'>HASIL 0. Form Check-in pasti kosong.</strong>";
    }

} catch (Exception $e) {
    echo "Error Fatal: " . $e->getMessage();
}
?>
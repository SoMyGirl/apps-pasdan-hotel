<?php
// Load file koneksi
require_once 'config/Database.php';
$db = new Database();

echo "<h2>Sedang Memperbaiki Database...</h2>";

try {
    // 1. Cek apakah kolom 'status_inap' ada?
    $check = $db->query("SHOW COLUMNS FROM transaksi LIKE 'status_inap'")->fetch();

    if ($check) {
        // Jika ada, kita UBAH namanya jadi status_transaksi
        $sql = "ALTER TABLE transaksi 
                CHANGE COLUMN status_inap status_transaksi 
                ENUM('active', 'finished', 'checkin', 'checkout') DEFAULT 'active'";
        $db->conn->exec($sql);
        echo "<p style='color:green'>✅ Berhasil mengubah 'status_inap' menjadi 'status_transaksi'.</p>";
    } else {
        echo "<p style='color:orange'>ℹ️ Kolom 'status_inap' tidak ditemukan (Mungkin sudah diubah).</p>";
    }

    // 2. Cek apakah kolom 'status_transaksi' sudah ada sekarang?
    $checkNew = $db->query("SHOW COLUMNS FROM transaksi LIKE 'status_transaksi'")->fetch();
    
    if (!$checkNew) {
        // Jika belum ada juga, kita BUAT BARU
        $sql = "ALTER TABLE transaksi ADD COLUMN status_transaksi ENUM('active', 'finished') DEFAULT 'active'";
        $db->conn->exec($sql);
        echo "<p style='color:green'>✅ Berhasil membuat kolom baru 'status_transaksi'.</p>";
    }

    // 3. Update Isinya (Konversi Data Lama)
    $db->conn->exec("UPDATE transaksi SET status_transaksi='active' WHERE status_transaksi='checkin'");
    $db->conn->exec("UPDATE transaksi SET status_transaksi='finished' WHERE status_transaksi='checkout' OR tgl_checkout IS NOT NULL");
    
    // 4. Rapikan Struktur
    $db->conn->exec("ALTER TABLE transaksi MODIFY COLUMN status_transaksi ENUM('active', 'finished') DEFAULT 'active'");

    echo "<hr><h3>🎉 PERBAIKAN SELESAI!</h3>";
    echo "<a href='index.php'>Klik Disini untuk Buka Dashboard</a>";

} catch (PDOException $e) {
    echo "<h3 style='color:red'>Gagal: " . $e->getMessage() . "</h3>";
}
?>
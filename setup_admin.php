<?php
// Panggil koneksi database yang baru
include 'model/koneksi.php';

echo "<h1>Setup Admin Database Railway</h1>";

try {
    $db = new Database();
    
    // 1. Cek apakah tabel users ada?
    // Jika error di sini, berarti Anda BELUM import struktur database (CREATE TABLE...)
    $cek = $db->query("SELECT count(*) FROM users");
    
    // 2. Buat Password Hash
    $username = 'admin';
    $password_asli = 'admin123';
    $password_hash = password_hash($password_asli, PASSWORD_DEFAULT);
    $nama = 'Administrator Railway';
    $role = 'admin';

    // 3. Cek apakah user admin sudah ada?
    $existing = $db->tampil('users', "username = '$username'");
    
    if (count($existing) > 0) {
        // Jika sudah ada, kita Update passwordnya biar yakin
        $db->query("UPDATE users SET password='$password_hash' WHERE username='$username'");
        echo "<div style='color:orange'>User 'admin' sudah ada. Password di-reset menjadi: <b>admin123</b></div>";
    } else {
        // Jika belum ada, kita Insert baru
        $sql = "INSERT INTO users (username, password, nama_lengkap, role) 
                VALUES ('$username', '$password_hash', '$nama', '$role')";
        
        $db->query($sql);
        echo "<div style='color:green'>BERHASIL! User admin berhasil dibuat.</div>";
    }

    echo "<hr>";
    echo "Username: <b>admin</b><br>";
    echo "Password: <b>admin123</b><br>";
    echo "<br><a href='index.php'>Klik disini untuk LOGIN</a>";

} catch (Exception $e) {
    echo "<div style='color:red'>ERROR: " . $e->getMessage() . "</div>";
    echo "<p>Kemungkinan tabel 'users' belum ada. Pastikan Anda sudah import SQL struktur database.</p>";
}
?>
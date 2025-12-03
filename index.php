<?php
session_start();

// ==========================================================
// 1. LOGIC AUTH (LOGIN & LOGOUT)
// ==========================================================

// Handle Logout
if (isset($_GET['aksi']) && $_GET['aksi'] == 'logout') {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Handle Login Process
if (isset($_GET['aksi']) && $_GET['aksi'] == 'login_proses') {
    include 'controllers/C_Auth.php';
    $auth = new C_Auth();
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if ($auth->login($username, $password)) {
        header("Location: index.php"); // Login Sukses
    } else {
        header("Location: index.php?msg=gagal"); // Login Gagal
    }
    exit;
}

// ==========================================================
// 2. SECURITY CHECK (WAJIB LOGIN)
// ==========================================================

// Jika belum login, selalu tampilkan halaman Login
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    include 'views/auth/login.php';
    exit;
}

// ==========================================================
// 3. ROUTING HALAMAN
// ==========================================================

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Load Layout Utama (Header & Sidebar)
include 'views/layout/header.php';
include 'views/layout/sidebar.php';

// Switch Content
switch ($page) {
    
    // --- DASHBOARD ---
    case 'dashboard':
        include 'views/dashboard.php';
        break;

    // --- MODUL RESEPSIONIS & UMUM ---
    case 'checkin':
        include 'views/resepsionis/checkin.php';
        break;

    case 'laporan':
        // Halaman List Tamu In-House
        include 'views/resepsionis/laporan.php';
        break;
        
    case 'pos':
        // POS diarahkan ke laporan dulu untuk pilih tamu
        include 'views/resepsionis/laporan.php'; 
        break;

    case 'bayar':
        // Halaman detail transaksi / invoice / pesan makan
        if (isset($_GET['id'])) {
            include 'views/transaksi/bayar.php';
        } else {
            echo "<div class='p-6 text-red-600 bg-red-50 border border-red-200 rounded-md'>Error: ID Transaksi tidak ditemukan.</div>";
        }
        break;

    // --- MODUL ADMIN (DIPROTEKSI) ---
    case 'kamar':
        if ($_SESSION['role'] !== 'admin') {
            include 'views/errors/403.php';
        } else {
            include 'views/admin/kamar_list.php';
        }
        break;

    case 'layanan': // <-- MODUL BARU (MASTER LAYANAN)
        if ($_SESSION['role'] !== 'admin') {
            include 'views/errors/403.php';
        } else {
            include 'views/admin/layanan_list.php';
        }
        break;

    case 'users':
        if ($_SESSION['role'] !== 'admin') {
            include 'views/errors/403.php';
        } else {
            include 'views/admin/users.php';
        }
        break;

    // --- DEFAULT 404 ---
    default:
        echo "<div class='flex flex-col items-center justify-center h-[50vh]'>
                <h1 class='text-6xl font-bold text-zinc-200'>404</h1>
                <p class='text-zinc-500'>Halaman tidak ditemukan.</p>
              </div>";
        break;
}

// Load Footer
include 'views/layout/footer.php';
?>
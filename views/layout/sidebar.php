<aside class="w-64 border-r border-zinc-200 bg-white hidden md:flex flex-col">
    
    <div class="h-16 flex items-center px-6 border-b border-zinc-100">
        <div class="flex items-center gap-2 font-bold text-xl tracking-tight text-zinc-900">
            <div class="w-8 h-8 bg-zinc-900 rounded-lg flex items-center justify-center text-white">
                <i data-lucide="building-2" class="h-5 w-5"></i>
            </div>
            <span>SMK HOTEL</span>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        <?php
        // --- PERBAIKAN ERROR DI SINI ---
        // Kita cek dulu apakah ada parameter 'page' di URL?
        // Jika tidak ada (null), kita set default ke 'dashboard'
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

        // Helper function biar kodingan menu rapi
        function menuItem($current, $target, $icon, $label) {
            // Bandingkan halaman saat ini dengan target
            $isActive = ($current == $target);
            
            // Style Active vs Inactive
            $class = $isActive 
                ? "bg-zinc-100 text-zinc-900 font-medium" 
                : "text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900";
            
            echo "<a href='index.php?page=$target' class='flex items-center gap-3 px-3 py-2 rounded-md text-sm transition-all $class'>
                    <i data-lucide='$icon' class='h-4 w-4'></i>
                    $label
                  </a>";
        }

        // --- GROUP 1: UTAMA ---
        echo "<div class='px-3 mb-2 mt-2 text-xs font-semibold text-zinc-400 uppercase tracking-wider'>Menu Utama</div>";
        // Gunakan variabel $currentPage yang sudah aman
        menuItem($currentPage, 'dashboard', 'layout-dashboard', 'Dashboard');
        
        // --- GROUP 2: OPERASIONAL ---
        echo "<div class='px-3 mb-2 mt-6 text-xs font-semibold text-zinc-400 uppercase tracking-wider'>Operasional</div>";
        menuItem($currentPage, 'checkin', 'key', 'Check In Baru');
        menuItem($currentPage, 'laporan', 'bed', 'Tamu In-House'); // List Tamu
        menuItem($currentPage, 'pos', 'utensils', 'Layanan (POS)');

        // --- GROUP 3: ADMIN ONLY ---
        // Cek session role juga pakai isset biar aman
        if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
            echo "<div class='px-3 mb-2 mt-6 text-xs font-semibold text-zinc-400 uppercase tracking-wider'>Master Data</div>";
            menuItem($currentPage, 'kamar', 'door-open', 'Kelola Kamar');
            menuItem($currentPage, 'layanan', 'coffee', 'Master Layanan');
            menuItem($currentPage, 'users', 'users', 'Kelola User');
        }
        ?>
    </nav>

    <div class="p-4 border-t border-zinc-100 bg-zinc-50/50">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-8 h-8 rounded-full bg-zinc-200 flex items-center justify-center text-xs font-bold text-zinc-600">
                <?= isset($_SESSION['nama']) ? substr($_SESSION['nama'], 0, 1) : 'U' ?>
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-medium text-zinc-900 truncate">
                    <?= isset($_SESSION['nama']) ? $_SESSION['nama'] : 'User' ?>
                </p>
                <p class="text-xs text-zinc-500 capitalize">
                    <?= isset($_SESSION['role']) ? $_SESSION['role'] : 'Guest' ?>
                </p>
            </div>
        </div>
        <a href="index.php?aksi=logout" class="flex w-full items-center justify-center gap-2 px-3 py-2 text-sm text-red-600 bg-white border border-red-100 hover:bg-red-50 rounded-md transition-colors">
            <i data-lucide="log-out" class="h-4 w-4"></i>
            Logout
        </a>
    </div>
</aside>

<main class="flex-1 flex flex-col h-screen overflow-hidden bg-zinc-50">
    <header class="h-16 border-b border-zinc-200 bg-white flex items-center justify-between px-8">
        <div class="flex items-center gap-2 text-sm breadcrumbs text-zinc-500">
            <i data-lucide="home" class="w-4 h-4"></i>
            <span class="mx-1">/</span> 
            <span class="font-medium text-zinc-900 capitalize">
                <?= isset($_GET['page']) ? $_GET['page'] : 'Dashboard' ?>
            </span>
        </div>
        
        <div class="text-xs text-zinc-400">
            <?= date('l, d F Y') ?>
        </div>
    </header>

    <div class="flex-1 overflow-auto p-8">
<aside class="w-64 bg-white border-r border-zinc-200 flex flex-col h-screen flex-shrink-0 transition-all no-print relative">
    
    <div class="h-16 flex items-center px-6 border-b border-zinc-100 shrink-0">
        <div class="flex items-center gap-3 font-bold text-xl tracking-tight text-zinc-900">
            <img src="assets/img/logo.png" class="h-10 w-auto" alt="Logo smk pasundan">
            <span>HOTEL SMK</span>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1 custom-scrollbar">
        <?php
        $m = $_GET['modul'] ?? 'Dashboard';
        $a = $_GET['aksi'] ?? 'index';

        function renderMenu($targetModul, $targetAksi, $label, $icon, $currModul, $currAksi) {
            // Logika aktif menu
            if ($targetModul == 'Dashboard') {
                $isActive = ($currModul == $targetModul);
            } else {
                $isActive = ($currModul == $targetModul && $currAksi == $targetAksi);
            }

            $class = $isActive 
                ? "bg-zinc-900 text-white shadow-md shadow-zinc-300" 
                : "text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900 hover:translate-x-1";

            echo "<a href='index.php?modul=$targetModul&aksi=$targetAksi' 
                     class='group flex items-center gap-3 px-3.5 py-2.5 mb-1 rounded-lg text-sm font-medium transition-all duration-200 ease-in-out $class'>
                    <i data-lucide='$icon' class='w-[18px] h-[18px] transition-colors'></i>
                    $label
                  </a>";
        }

        echo "<div class='px-4 mt-2 mb-2 text-[10px] font-bold text-zinc-400 uppercase tracking-wider'>Utama</div>";
        renderMenu('Dashboard', 'index', 'Dashboard', 'layout-grid', $m, $a);
        renderMenu('Room', 'index', 'List Kamar', 'door-open', $m, $a);

        echo "<div class='px-4 mt-6 mb-2 text-[10px] font-bold text-zinc-400 uppercase tracking-wider'>Front Office</div>";
        renderMenu('Checkin', 'create', 'Check-in Tamu', 'key', $m, $a);
        renderMenu('Guest', 'index', 'Tamu In-House', 'book-user', $m, $a);
        renderMenu('Guest', 'history', 'Riwayat Tamu', 'history', $m, $a);
        renderMenu('Checkout', 'index', 'Kasir & Invoice', 'receipt', $m, $a);

        if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
            echo "<div class='px-4 mt-6 mb-2 text-[10px] font-bold text-zinc-400 uppercase tracking-wider'>Back Office</div>";
            renderMenu('Layanan', 'index', 'Menu Layanan', 'coffee', $m, $a);
            renderMenu('User', 'index', 'Staff & User', 'users', $m, $a);
            renderMenu('Report', 'index', 'Laporan Keuangan', 'bar-chart-3', $m, $a);
        }
        ?>
    </nav>

    <div class="p-4 border-t border-zinc-100 bg-zinc-50/50 shrink-0">
        <div class="flex items-center gap-3 p-2 rounded-xl bg-white border border-zinc-200 shadow-sm">
            <div class="w-8 h-8 rounded-full bg-zinc-200 flex items-center justify-center text-xs font-bold text-zinc-600">
                <?= substr($_SESSION['nama'] ?? 'U', 0, 1) ?>
            </div>
            <div class="flex-1 overflow-hidden">
                <p class="text-xs font-bold text-zinc-900 truncate"><?= $_SESSION['nama'] ?? 'User' ?></p>
                <p class="text-[10px] text-zinc-500 capitalize"><?= $_SESSION['role'] ?? 'Staff' ?></p>
            </div>
            <a href="index.php?modul=Auth&aksi=logout" class="text-zinc-400 hover:text-red-600 transition-colors" title="Logout">
                <i data-lucide="log-out" class="w-4 h-4"></i>
            </a>
        </div>
    </div>
</aside>

<div class="flex-1 min-w-0 h-screen flex flex-col bg-zinc-50 overflow-hidden transition-all relative">
    
    <header class="h-16 bg-white/80 backdrop-blur-md border-b border-zinc-200 flex items-center justify-between px-8 shrink-0 no-print z-40 sticky top-0">
        <div class="flex items-center gap-2 text-sm text-zinc-500">
            <span class="flex items-center gap-1"><i data-lucide="layout" class="w-4 h-4"></i> Halaman</span>
            <span class="text-zinc-300">/</span>
            <span class="font-bold text-zinc-900"><?= ucfirst($_GET['modul'] ?? 'Dashboard') ?></span>
        </div>
        <div class="text-xs text-zinc-400 font-medium bg-zinc-100 px-3 py-1 rounded-full">
            <?= date('l, d F Y') ?>
        </div>
    </header>

    <main class="flex-1 w-full overflow-y-auto p-8 custom-scrollbar relative">
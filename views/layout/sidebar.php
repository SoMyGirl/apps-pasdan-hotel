<aside class="w-64 border-r border-zinc-200 bg-white hidden md:flex flex-col no-print">
    <div class="h-16 flex items-center px-6 border-b border-zinc-100 font-bold text-xl text-zinc-900">
        <i data-lucide="building-2" class="mr-2"></i> HOTEL SMK
    </div>
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        <?php
        $m = $_GET['modul'] ?? 'Dashboard';
        function menu($target, $label, $icon, $curr) {
            $active = ($curr == $target) ? "bg-zinc-100 text-zinc-900 font-medium" : "text-zinc-500 hover:bg-zinc-50";
            echo "<a href='index.php?modul=$target&aksi=index' class='flex items-center gap-3 px-3 py-2 rounded-md text-sm transition-all $active'>
                    <i data-lucide='$icon' class='w-4 h-4'></i> $label
                  </a>";
        }
        echo "<div class='px-3 text-xs font-semibold text-zinc-400 uppercase mt-2'>Utama</div>";
        menu('Dashboard', 'Dashboard', 'layout-dashboard', $m);
        
        echo "<div class='px-3 text-xs font-semibold text-zinc-400 uppercase mt-4'>Operasional</div>";
        echo "<a href='index.php?modul=Checkin&aksi=create' class='flex items-center gap-3 px-3 py-2 rounded-md text-sm text-zinc-500 hover:bg-zinc-50'>
                <i data-lucide='key' class='w-4 h-4'></i> Check In Baru
              </a>";
        menu('Guest', 'Tamu In-House', 'users', $m);
        
        if ($_SESSION['role'] == 'admin') {
            echo "<div class='px-3 text-xs font-semibold text-zinc-400 uppercase mt-4'>Admin</div>";
            menu('Room', 'Kelola Kamar', 'door-open', $m);
            menu('Report', 'Laporan', 'bar-chart', $m);
        }
        ?>
    </nav>
    <div class="p-4 border-t border-zinc-100">
        <a href="index.php?modul=Auth&aksi=logout" class="flex items-center justify-center gap-2 px-3 py-2 text-sm text-red-600 bg-red-50 rounded-md hover:bg-red-100">
            <i data-lucide="log-out" class="w-4 h-4"></i> Logout
        </a>
    </div>
</aside>
<main class="flex-1 flex flex-col h-screen overflow-hidden">
    <header class="h-16 border-b border-zinc-200 bg-white flex items-center justify-between px-8 no-print">
        <span class="text-sm text-zinc-500">Sistem Manajemen Hotel</span>
        <span class="font-medium"><?= $_SESSION['nama'] ?? 'User' ?></span>
    </header>
    <div class="flex-1 overflow-auto p-8">
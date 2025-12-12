<div x-show="sidebarOpen" @click="sidebarOpen = false" 
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-40 bg-black/50 lg:hidden backdrop-blur-sm"></div>

<aside x-data="{ hoveredMenu: null, menuTop: 0 }"
       class="fixed inset-y-0 left-0 z-50 flex flex-col bg-white border-r border-zinc-200 shadow-2xl lg:shadow-none transition-all duration-300 ease-in-out lg:static lg:translate-x-0 transform"
       :class="[
           sidebarOpen ? 'translate-x-0' : '-translate-x-full',
           (sidebarMinimized && isDesktop) ? 'lg:w-[90px]' : 'w-72 lg:w-72'
       ]">

    <div class="h-20 flex items-center justify-center border-b border-zinc-100 shrink-0 relative cursor-pointer hover:bg-zinc-50 transition-colors group"
         @click="toggleSidebar()"
         title="Klik untuk minimize/close">
        
        <div class="flex items-center gap-4 overflow-hidden whitespace-nowrap transition-all duration-300"
             :class="(sidebarMinimized && isDesktop) ? 'px-0 justify-center' : 'px-6 w-full'">
            
            <div class="relative w-12 h-12 flex items-center justify-center shrink-0">
                <img src="assets/img/logo.png" class="w-full h-full object-contain transition-all duration-300 filter drop-shadow-sm" 
                     :class="(sidebarMinimized && isDesktop) ? 'scale-110 group-hover:scale-90 group-hover:opacity-50' : ''" 
                     alt="Logo">
                <i data-lucide="chevrons-right" class="absolute inset-0 w-6 h-6 text-zinc-600 opacity-0 group-hover:opacity-100 transition-all duration-300 m-auto"
                   :class="(sidebarMinimized && isDesktop) ? 'scale-100' : 'scale-0'"></i>
            </div>

            <div x-show="!sidebarMinimized || !isDesktop" 
                 class="transition-all duration-300 origin-left overflow-hidden flex flex-col leading-none">
                <span class="font-black text-2xl text-zinc-900 tracking-tight group-hover:text-zinc-700 transition-colors">LUXURY</span>
                <span class="text-[10px] font-bold text-zinc-400 tracking-[0.3em] uppercase group-hover:text-zinc-500 transition-colors ml-0.5">PASS</span>
            </div>
            
            <button @click.stop="sidebarOpen = false" class="lg:hidden ml-auto text-zinc-400 hover:text-zinc-900">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto overflow-x-hidden py-6 space-y-1 custom-scrollbar" 
         :class="(sidebarMinimized && isDesktop) ? 'px-3' : 'px-5'">
        <?php
        $m = $_GET['modul'] ?? 'Dashboard';
        $a = $_GET['aksi'] ?? 'index';
        $role = $_SESSION['role'] ?? '';

        function renderMenu($targetModul, $targetAksi, $label, $icon, $currModul, $currAksi) {
            $isActive = ($targetModul == 'Dashboard') ? ($currModul == $targetModul) : ($currModul == $targetModul && $currAksi == $targetAksi);
            $bgClass = $isActive ? "bg-zinc-900 text-white shadow-lg shadow-zinc-200" : "text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900";
            
            echo "
            <div class='relative group/item' 
                 @mouseenter=\"if(sidebarMinimized && isDesktop) { hoveredMenu = '$label'; menuTop = \$el.getBoundingClientRect().top }\" 
                 @mouseleave=\"hoveredMenu = null\">
                 
                <a href='index.php?modul=$targetModul&aksi=$targetAksi' 
                   class='flex items-center gap-3 px-3 py-3 mb-1 rounded-xl transition-all duration-200 cursor-pointer $bgClass'
                   :class=\"(sidebarMinimized && isDesktop) ? 'justify-center' : ''\">
                    
                    <i data-lucide='$icon' class='w-5 h-5 shrink-0 transition-transform duration-300' 
                       :class=\"(sidebarMinimized && isDesktop && hoveredMenu == '$label') ? 'scale-110' : ''\"></i>
                    
                    <span x-show='!sidebarMinimized || !isDesktop' 
                          class='text-sm font-medium whitespace-nowrap overflow-hidden transition-all duration-300 origin-left'>
                          $label
                    </span>
                </a>
            </div>";
        }

        if ($role !== 'housekeeping') {
            echo "<div class='px-4 mt-2 mb-2 text-[10px] font-bold text-zinc-400 uppercase tracking-wider transition-all duration-300 truncate' x-show='!sidebarMinimized || !isDesktop'>Utama</div>";
            echo "<div class='h-px bg-zinc-100 mx-2 mb-2' x-show='sidebarMinimized && isDesktop'></div>"; 
            renderMenu('Dashboard', 'index', 'Dashboard', 'layout-grid', $m, $a);
            renderMenu('Room', 'index', 'List Kamar', 'door-open', $m, $a);

            echo "<div class='px-4 mt-6 mb-2 text-[10px] font-bold text-zinc-400 uppercase tracking-wider transition-all duration-300 truncate' x-show='!sidebarMinimized || !isDesktop'>Front Office</div>";
            echo "<div class='h-px bg-zinc-100 mx-2 my-2' x-show='sidebarMinimized && isDesktop'></div>";
            renderMenu('Checkin', 'create', 'Check-in', 'key', $m, $a);
            renderMenu('Guest', 'index', 'Tamu In-House', 'book-user', $m, $a);
            renderMenu('Guest', 'history', 'Riwayat', 'history', $m, $a);
            renderMenu('Checkout', 'index', 'Kasir', 'receipt', $m, $a);
        }

        if(in_array($role, ['admin', 'housekeeping'])) {
            echo "<div class='px-4 mt-6 mb-2 text-[10px] font-bold text-zinc-400 uppercase tracking-wider transition-all duration-300 truncate' x-show='!sidebarMinimized || !isDesktop'>Operasional</div>";
            echo "<div class='h-px bg-zinc-100 mx-2 my-2' x-show='sidebarMinimized && isDesktop'></div>";
            renderMenu('Housekeeping', 'index', 'Housekeeping', 'spray-can', $m, $a);
        }

        if ($role == 'admin') {
            echo "<div class='px-4 mt-6 mb-2 text-[10px] font-bold text-zinc-400 uppercase tracking-wider transition-all duration-300 truncate' x-show='!sidebarMinimized || !isDesktop'>Back Office</div>";
            echo "<div class='h-px bg-zinc-100 mx-2 my-2' x-show='sidebarMinimized && isDesktop'></div>";
            renderMenu('Layanan', 'index', 'Layanan', 'coffee', $m, $a);
            renderMenu('User', 'index', 'Staff', 'users', $m, $a);
            renderMenu('Report', 'index', 'Laporan', 'bar-chart-3', $m, $a);
        }
        ?>
    </nav>

    <div x-show="sidebarMinimized && isDesktop && hoveredMenu" 
         class="fixed left-[94px] z-[9999] px-3 py-2 bg-zinc-900 text-white text-xs font-bold rounded-lg shadow-xl pointer-events-none whitespace-nowrap border border-zinc-700"
         :style="'top: ' + (menuTop + 10) + 'px'">
        <span x-text="hoveredMenu"></span>
        <div class="absolute top-1/2 -left-1 -mt-1 w-2 h-2 bg-zinc-900 rotate-45 border-l border-b border-zinc-700"></div>
    </div>

    <div class="p-4 border-t border-zinc-100 bg-zinc-50/50 shrink-0 cursor-pointer hover:bg-zinc-100 transition-colors"
         :class="(sidebarMinimized && isDesktop) ? 'flex justify-center px-0' : ''">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-zinc-800 to-zinc-600 text-white flex items-center justify-center text-sm font-bold shrink-0 shadow-md">
                <?= substr($_SESSION['nama'] ?? 'U', 0, 1) ?>
            </div>
            
            <div class="flex-1 overflow-hidden" x-show="!sidebarMinimized || !isDesktop" x-transition>
                <p class="text-sm font-bold text-zinc-900 truncate"><?= $_SESSION['nama'] ?? 'User' ?></p>
                <p class="text-xs text-zinc-500 capitalize truncate"><?= $_SESSION['role'] ?? 'Staff' ?></p>
            </div>
            
            <button @click="sidebarOpen = false; confirmLogout()" class="text-zinc-400 hover:text-rose-600 transition-colors p-1" title="Logout" x-show="!sidebarMinimized || !isDesktop">
                <i data-lucide="log-out" class="w-5 h-5"></i>
            </button>
        </div>
    </div>
</aside>

<script>
function confirmLogout() {
    Swal.fire({
        title: '<span class="text-xl font-bold text-zinc-900">Yakin ingin keluar?</span>',
        text: "Sesi Anda akan diakhiri.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48', cancelButtonColor: '#f4f4f5',
        confirmButtonText: 'Ya, Logout', cancelButtonText: '<span class="text-zinc-600 font-bold">Batal</span>',
        reverseButtons: true,
        customClass: { popup: 'rounded-2xl p-6', confirmButton: 'rounded-lg px-6 py-2.5 font-bold', cancelButton: 'rounded-lg px-6 py-2.5 hover:bg-zinc-200' }
    }).then((result) => { if (result.isConfirmed) window.location.href = "index.php?modul=Auth&aksi=logout"; });
}
</script>

<div class="flex-1 min-w-0 h-screen flex flex-col bg-zinc-50 overflow-hidden relative transition-all duration-300">
    <header class="h-20 bg-white/80 backdrop-blur-md border-b border-zinc-200 flex items-center justify-between px-4 lg:px-8 shrink-0 z-30 sticky top-0">
        <div class="flex items-center gap-4">
            <button @click="sidebarOpen = true" class="lg:hidden p-2 text-zinc-500 hover:bg-zinc-100 rounded-lg">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
            <div class="flex items-center gap-2">
                <span class="font-black text-zinc-900 text-xl md:text-2xl tracking-tight line-clamp-1">
                    <?= ucfirst($_GET['modul'] ?? 'Dashboard') ?>
                </span>
            </div>
        </div>
        <div class="text-sm text-zinc-500 font-medium bg-zinc-100/80 px-4 py-2 rounded-full hidden sm:flex items-center gap-2">
            <i data-lucide="calendar" class="w-4 h-4"></i>
            <?= date('l, d F Y') ?>
        </div>
    </header>

    <main class="flex-1 w-full overflow-y-auto p-4 lg:p-8 custom-scrollbar">
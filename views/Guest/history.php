<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900">Riwayat Tamu</h1>
        <p class="text-zinc-500 mt-1 text-sm">Arsip lengkap data check-in dan check-out tamu hotel.</p>
    </div>
    
    <div class="relative">
        <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-zinc-400"></i>
        <input type="text" id="searchHistory" placeholder="Cari nama, invoice..." 
               class="pl-9 pr-4 py-2 w-64 rounded-lg border border-zinc-200 text-sm focus:border-zinc-900 focus:ring-zinc-900 transition-all shadow-sm">
    </div>
</div>

<?php
    // --- LOGIKA FILTER & PAGINATION ---
    
    // 1. Ambil Parameter Filter
    $f = $_GET['filter'] ?? 'all';      // Filter Status
    $year = $_GET['year'] ?? date('Y'); // Filter Tahun
    $q = $_GET['triwulan'] ?? 'all';    // Filter Triwulan (Baru)
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 10; 

    // 2. Filter Data (PHP Array Filter)
    $filteredTamu = array_filter($tamu, function($item) use ($year, $q, $f) {
        $date = strtotime($item['tgl_checkin']);
        $dbYear = date('Y', $date);
        $dbMonth = date('n', $date); // 1-12

        // Cek Tahun
        if ($dbYear != $year) return false;

        // Cek Triwulan
        if ($q != 'all') {
            if ($q == '1' && ($dbMonth < 1 || $dbMonth > 3)) return false; // Jan-Mar
            if ($q == '2' && ($dbMonth < 4 || $dbMonth > 6)) return false; // Apr-Jun
            if ($q == '3' && ($dbMonth < 7 || $dbMonth > 9)) return false; // Jul-Sep
            if ($q == '4' && ($dbMonth < 10 || $dbMonth > 12)) return false;// Oct-Dec
        }

        // Cek Status (Jika filtering status tidak dihandle di query SQL)
        if ($f == 'finished' && empty($item['tgl_checkout'])) return false;
        if ($f == 'active' && !empty($item['tgl_checkout'])) return false;

        return true;
    });

    // 3. Logika Pagination
    $totalData = count($filteredTamu);
    $totalPages = ceil($totalData / $limit);
    $offset = ($page - 1) * $limit;
    
    // Ambil data slice
    $displayData = array_slice($filteredTamu, $offset, $limit);

    // Helper function class tombol
    function btnClass($target, $current) {
        return $target == $current 
            ? "bg-white text-zinc-900 shadow-sm ring-1 ring-zinc-200 font-bold" 
            : "bg-transparent text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100 font-medium";
    }

    // Helper function membuat URL agar parameter tidak hilang
    function makeUrl($params = []) {
        $base = [
            'modul' => 'Guest', 
            'aksi' => 'history',
            'filter' => $_GET['filter'] ?? 'all',
            'year' => $_GET['year'] ?? date('Y'),
            'triwulan' => $_GET['triwulan'] ?? 'all',
            'page' => 1 // Default reset ke page 1 jika ganti filter
        ];
        $final = array_merge($base, $params);
        return "index.php?" . http_build_query($final);
    }
?>

<div class="bg-white rounded-xl border border-zinc-200 overflow-hidden shadow-sm">
    
    <div class="px-6 py-4 border-b border-zinc-100 bg-zinc-50/50 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        
        <div class="flex gap-2 overflow-x-auto">
            <a href="<?= makeUrl(['filter' => 'all']) ?>" 
               class="px-3 py-1.5 text-xs rounded-md transition-all whitespace-nowrap <?= btnClass('all', $f) ?>">
               Semua Data
            </a>
            <a href="<?= makeUrl(['filter' => 'finished']) ?>" 
               class="px-3 py-1.5 text-xs rounded-md transition-all whitespace-nowrap <?= btnClass('finished', $f) ?>">
               Checkout
            </a>
            <a href="<?= makeUrl(['filter' => 'active']) ?>" 
               class="px-3 py-1.5 text-xs rounded-md transition-all whitespace-nowrap <?= btnClass('active', $f) ?>">
               Masih Menginap
            </a>
        </div>

        <div class="flex items-center gap-3">
            
            <div class="flex items-center gap-2">
                <span class="text-xs text-zinc-500 font-medium hidden sm:inline">Periode:</span>
                <select onchange="window.location.href=this.value" 
                        class="pl-3 pr-8 py-1.5 text-xs rounded-md border border-zinc-200 bg-white focus:ring-zinc-900 focus:border-zinc-900 shadow-sm cursor-pointer">
                    <option value="<?= makeUrl(['triwulan' => 'all']) ?>" <?= $q == 'all' ? 'selected' : '' ?>>Semua Bulan</option>
                    <option value="<?= makeUrl(['triwulan' => '1']) ?>" <?= $q == '1' ? 'selected' : '' ?>>Triwulan I (Jan - Mar)</option>
                    <option value="<?= makeUrl(['triwulan' => '2']) ?>" <?= $q == '2' ? 'selected' : '' ?>>Triwulan II (Apr - Jun)</option>
                    <option value="<?= makeUrl(['triwulan' => '3']) ?>" <?= $q == '3' ? 'selected' : '' ?>>Triwulan III (Jul - Sep)</option>
                    <option value="<?= makeUrl(['triwulan' => '4']) ?>" <?= $q == '4' ? 'selected' : '' ?>>Triwulan IV (Okt - Des)</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs text-zinc-500 font-medium hidden sm:inline">Tahun:</span>
                <select onchange="window.location.href=this.value" 
                        class="pl-3 pr-8 py-1.5 text-xs rounded-md border border-zinc-200 bg-white focus:ring-zinc-900 focus:border-zinc-900 shadow-sm cursor-pointer">
                    <?php 
                    $currentYear = date('Y');
                    for($y = $currentYear; $y >= $currentYear - 3; $y--): ?>
                        <option value="<?= makeUrl(['year' => $y]) ?>" <?= $year == $y ? 'selected' : '' ?>>
                            <?= $y ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-zinc-50 border-b border-zinc-100">
                <tr>
                    <th class="px-6 py-3 font-semibold text-zinc-500 w-48">Invoice & Tamu</th>
                    <th class="px-6 py-3 font-semibold text-zinc-500 w-32">Kamar</th>
                    <th class="px-6 py-3 font-semibold text-zinc-500">Timeline Menginap</th>
                    <th class="px-6 py-3 font-semibold text-zinc-500 text-center">Status Pembayaran</th>
                    <th class="px-6 py-3 font-semibold text-zinc-500 text-right">Total</th>
                    <th class="px-6 py-3 font-semibold text-zinc-500 text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100" id="historyTable">
                <?php if(empty($displayData)): ?>
                    <tr>
                        <td colspan="6" class="p-12 text-center text-zinc-400">
                            <div class="flex flex-col items-center justify-center">
                                <i data-lucide="calendar-off" class="w-12 h-12 mb-3 opacity-20"></i>
                                <p class="font-medium">Tidak ada data ditemukan pada periode ini.</p>
                                <p class="text-xs mt-1">Filter: Tahun <?= $year ?>, Triwulan <?= $q == 'all' ? 'Semua' : $q ?></p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($displayData as $t): ?>
                    <tr class="hover:bg-zinc-50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-zinc-900 search-name"><?= $t['nama_tamu'] ?></span>
                                <a href="index.php?modul=Checkout&aksi=payment&id=<?= $t['id_transaksi'] ?>" 
                                   class="font-mono text-[10px] text-zinc-400 mt-1 hover:text-blue-600 hover:underline search-inv">
                                    #<?= $t['no_invoice'] ?>
                                </a>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-zinc-100 text-zinc-700 border border-zinc-200">
                                Room <?= $t['nomor_kamar'] ?>
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex flex-col text-xs">
                                <span class="flex items-center gap-1.5 text-zinc-600">
                                    <i data-lucide="log-in" class="w-3 h-3 text-emerald-500"></i>
                                    <?= date('d M Y, H:i', strtotime($t['tgl_checkin'])) ?>
                                </span>
                                <span class="flex items-center gap-1.5 mt-1.5 text-zinc-400">
                                    <i data-lucide="log-out" class="w-3 h-3 text-rose-400"></i>
                                    <?php if($t['tgl_checkout']): ?>
                                        <?= date('d M Y, H:i', strtotime($t['tgl_checkout'])) ?>
                                    <?php else: ?>
                                        <span class="text-emerald-600 font-bold bg-emerald-50 px-1 rounded">Aktif</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <?php if($t['status_bayar'] == 'lunas'): ?>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 border border-emerald-200">
                                    <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                                    Lunas
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 border border-amber-200">
                                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                    Belum Lunas
                                </span>
                            <?php endif; ?>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <span class="font-mono font-medium text-zinc-700">
                                Rp <?= number_format($t['total_tagihan']) ?>
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <a href="index.php?modul=Checkout&aksi=payment&id=<?= $t['id_transaksi'] ?>" 
                               class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-zinc-900 text-white text-xs font-medium rounded-lg hover:bg-zinc-800 transition-all shadow-sm w-full">
                                <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                                Payment
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="bg-zinc-50 border-t border-zinc-200 px-6 py-3 flex flex-col md:flex-row justify-between items-center text-xs text-zinc-500 gap-4">
        <div>
            Menampilkan <strong><?= $totalData > 0 ? $offset + 1 : 0 ?></strong> - <strong><?= min($offset + $limit, $totalData) ?></strong> dari <strong><?= $totalData ?></strong> data
        </div>
        
        <div class="flex items-center gap-2">
            <?php if($page > 1): ?>
                <a href="<?= makeUrl(['page' => $page - 1]) ?>" 
                   class="px-3 py-1.5 rounded bg-white border border-zinc-200 hover:bg-zinc-100 hover:text-zinc-900 transition-colors">
                    Previous
                </a>
            <?php else: ?>
                <span class="px-3 py-1.5 rounded bg-zinc-100 text-zinc-300 border border-zinc-200 cursor-not-allowed">Previous</span>
            <?php endif; ?>

            <span class="px-2 font-medium text-zinc-700">
                Page <?= $page ?> / <?= $totalPages > 0 ? $totalPages : 1 ?>
            </span>

            <?php if($page < $totalPages): ?>
                <a href="<?= makeUrl(['page' => $page + 1]) ?>" 
                   class="px-3 py-1.5 rounded bg-white border border-zinc-200 hover:bg-zinc-100 hover:text-zinc-900 transition-colors">
                    Next
                </a>
            <?php else: ?>
                <span class="px-3 py-1.5 rounded bg-zinc-100 text-zinc-300 border border-zinc-200 cursor-not-allowed">Next</span>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Client-side Search untuk data yang tampil di slide saat ini
    document.getElementById('searchHistory').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelectorAll("#historyTable tr");
        rows.forEach(row => {
            let name = row.querySelector(".search-name")?.innerText.toUpperCase() || "";
            let inv = row.querySelector(".search-inv")?.innerText.toUpperCase() || "";
            
            if(row.innerText.includes("Tidak ada data")) return;

            if (name.includes(filter) || inv.includes(filter)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900">Riwayat Kunjungan</h1>
        <p class="text-zinc-500 mt-1 text-sm">Arsip lengkap data check-in dan check-out tamu hotel.</p>
    </div>
    
    <div class="relative">
        <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-zinc-400"></i>
        <input type="text" id="searchHistory" placeholder="Cari nama, invoice..." 
               class="pl-9 pr-4 py-2 w-64 rounded-lg border border-zinc-200 text-sm focus:border-zinc-900 focus:ring-zinc-900 transition-all shadow-sm">
    </div>
    
</div>

<div class="bg-white rounded-xl border border-zinc-200 overflow-hidden shadow-sm">
    
    <div class="px-6 py-4 border-b border-zinc-100 bg-zinc-50/50 flex gap-2 overflow-x-auto">
        <?php
            $f = $data['filter'] ?? 'all'; // Mengambil variabel $filter dari controller
            
            // Helper function untuk class tombol active/inactive
            function btnClass($target, $current) {
                return $target == $current 
                    ? "bg-white text-zinc-900 shadow-sm ring-1 ring-zinc-200 font-bold" 
                    : "bg-transparent text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100 font-medium";
            }
        ?>
        <a href="index.php?modul=Guest&aksi=history&filter=all" 
           class="px-3 py-1.5 text-xs rounded-md transition-all whitespace-nowrap <?= btnClass('all', $f) ?>">
           Semua Data
        </a>
        <a href="index.php?modul=Guest&aksi=history&filter=finished" 
           class="px-3 py-1.5 text-xs rounded-md transition-all whitespace-nowrap <?= btnClass('finished', $f) ?>">
           Checkout Selesai
        </a>
        <a href="index.php?modul=Guest&aksi=history&filter=active" 
           class="px-3 py-1.5 text-xs rounded-md transition-all whitespace-nowrap <?= btnClass('active', $f) ?>">
           Masih Menginap
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-zinc-50 border-b border-zinc-100">
                <tr>
                    <th class="px-6 py-3 font-semibold text-zinc-500 w-48">Invoice & Tamu</th>
                    <th class="px-6 py-3 font-semibold text-zinc-500 w-32">Kamar</th>
                    <th class="px-6 py-3 font-semibold text-zinc-500">Timeline Menginap</th>
                    <th class="px-6 py-3 font-semibold text-zinc-500 text-center">Status</th>
                    <th class="px-6 py-3 font-semibold text-zinc-500 text-right">Total</th>
                    <th class="px-6 py-3 font-semibold text-zinc-500 text-right w-10"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100" id="historyTable">
                <?php if(empty($tamu)): ?>
                    <tr>
                        <td colspan="6" class="p-12 text-center text-zinc-400">
                            <div class="flex flex-col items-center justify-center">
                                <i data-lucide="history" class="w-12 h-12 mb-3 opacity-20"></i>
                                <p class="font-medium">Tidak ada data riwayat yang ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($tamu as $t): ?>
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
                                <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500 mx-auto" title="Lunas"></i>
                            <?php else: ?>
                                <i data-lucide="clock" class="w-5 h-5 text-amber-500 mx-auto" title="Belum Lunas"></i>
                            <?php endif; ?>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <span class="font-mono font-medium text-zinc-700">
                                Rp <?= number_format($t['total_tagihan']) ?>
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <a href="index.php?modul=Checkout&aksi=payment&id=<?= $t['id_transaksi'] ?>" 
                               class="text-zinc-300 hover:text-zinc-900 transition-colors">
                                <i data-lucide="chevron-right" class="w-5 h-5"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="bg-zinc-50 border-t border-zinc-200 px-6 py-3 flex justify-between items-center text-xs text-zinc-500">
        <span>Total Record: <strong><?= count($tamu) ?></strong></span>
        <span>Data diurutkan dari yang terbaru.</span>
    </div>
</div>

<script>
    // Simple Client-side Search
    document.getElementById('searchHistory').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelectorAll("#historyTable tr");
        rows.forEach(row => {
            let name = row.querySelector(".search-name")?.innerText.toUpperCase() || "";
            let inv = row.querySelector(".search-inv")?.innerText.toUpperCase() || "";
            
            if (name.includes(filter) || inv.includes(filter)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
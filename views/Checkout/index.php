<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-zinc-900">Billing & Checkout</h1>
        <p class="text-zinc-500 mt-1 text-sm">Kelola pembayaran dan checkout tamu.</p>
    </div>
    
    <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
        
        <div class="flex gap-2 overflow-x-auto w-full sm:w-auto pb-2 sm:pb-0 no-scrollbar">
            <?php
                $currentFilter = $filter ?? 'all';
                function getFilterClass($target, $current) {
                    return $target == $current 
                        ? "bg-zinc-900 text-white shadow-md font-bold" 
                        : "bg-white text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50 font-medium border border-zinc-200";
                }
            ?>
            <a href="index.php?modul=Checkout&aksi=index&filter=all" 
               class="px-3 py-2 text-xs rounded-lg transition-all whitespace-nowrap flex-shrink-0 <?= getFilterClass('all', $currentFilter) ?>">
               Semua
            </a>
            <a href="index.php?modul=Checkout&aksi=index&filter=unpaid" 
               class="px-3 py-2 text-xs rounded-lg transition-all whitespace-nowrap flex-shrink-0 <?= getFilterClass('unpaid', $currentFilter) ?>">
               Belum Lunas
            </a>
            <a href="index.php?modul=Checkout&aksi=index&filter=overdue" 
               class="px-3 py-2 text-xs rounded-lg transition-all whitespace-nowrap flex-shrink-0 <?= getFilterClass('overdue', $currentFilter) ?>">
               Overdue
            </a>
        </div>

        <div class="relative w-full sm:w-64">
            <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-zinc-400"></i>
            <input type="text" id="billSearch" placeholder="Cari No Kamar / Tamu..." 
                   class="pl-9 pr-4 py-2 w-full rounded-lg border border-zinc-200 text-sm focus:border-zinc-900 shadow-sm">
        </div>
    </div>
</div>

<div class="bg-white rounded-xl border border-zinc-200 overflow-hidden shadow-sm">
    <div class="overflow-x-auto w-full">
        <table class="w-full text-sm text-left whitespace-nowrap">
            <thead class="bg-zinc-50 border-b border-zinc-100">
                <tr>
                    <th class="px-6 py-4 font-semibold text-zinc-500">Kamar</th>
                    <th class="px-6 py-4 font-semibold text-zinc-500">Tamu & Invoice</th>
                    <th class="px-6 py-4 font-semibold text-zinc-500 text-right">Total Tagihan</th>
                    <th class="px-6 py-4 font-semibold text-zinc-500 text-center">Status</th>
                    <th class="px-6 py-4 font-semibold text-zinc-500 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100" id="billTable">
                <?php if(empty($data)): ?>
                    <tr>
                        <td colspan="5" class="py-12 text-center text-zinc-400">
                            <p>Tidak ada tagihan aktif.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data as $d): ?>
                    <?php 
                        $isOverdue = (strtotime(date('Y-m-d H:i:s')) > strtotime($d['tgl_estimasi_checkout']) && $d['status_bayar'] != 'lunas');
                        $rowClass = $isOverdue ? 'bg-rose-50/50 border-l-4 border-rose-500' : '';
                        $statusLabel = match($d['status_bayar']) { 'lunas'=>'Lunas', 'dp'=>'DP Masuk', default=>'Belum Lunas' };
                        $badgeClass = match($d['status_bayar']) {
                            'lunas' => 'bg-emerald-100 text-emerald-700',
                            'dp'    => 'bg-amber-100 text-amber-700',
                            default => 'bg-rose-100 text-rose-700'
                        };
                    ?>
                    <tr class="hover:bg-zinc-50 transition-colors group <?= $rowClass ?>">
                        <td class="px-6 py-4">
                            <span class="text-xl font-black text-zinc-800 search-room"><?= $d['nomor_kamar'] ?></span>
                            <?php if($isOverdue): ?><span class="text-[10px] font-bold text-rose-600 bg-rose-200 px-1 rounded ml-1">LATE</span><?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-zinc-900 search-name"><?= $d['nama_tamu'] ?></span>
                                <span class="text-xs text-zinc-400 font-mono">#<?= $d['no_invoice'] ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right font-mono font-bold text-zinc-700">Rp <?= number_format($d['total_tagihan']) ?></td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase <?= $badgeClass ?>"><?= $statusLabel ?></span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="index.php?modul=Checkout&aksi=payment&id=<?= $d['id_transaksi'] ?>" class="px-3 py-1.5 bg-zinc-900 text-white text-xs font-bold rounded-lg hover:bg-zinc-800 shadow-sm">Proses</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    lucide.createIcons();
    document.getElementById('billSearch').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        document.querySelectorAll("#billTable tr").forEach(row => {
            row.style.display = row.innerText.toUpperCase().includes(filter) ? "" : "none";
        });
    });
</script>
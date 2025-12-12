<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-zinc-900">In-House Guests</h1>
        <p class="text-zinc-500 mt-1 text-sm">Daftar tamu yang sedang menginap.</p>
    </div>
    
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto">
        <div class="relative w-full sm:w-auto">
            <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-zinc-400"></i>
            <input type="text" id="searchInput" placeholder="Cari nama/kamar..." 
                   class="pl-9 pr-4 py-2 w-full sm:w-64 rounded-lg border border-zinc-200 text-sm focus:border-zinc-900 shadow-sm">
        </div>
        
        <div class="flex gap-2">
            <a href="index.php?modul=Checkin&aksi=create" class="flex-1 sm:flex-none bg-zinc-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-zinc-800 shadow-sm flex items-center justify-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i> <span class="hidden sm:inline">Check-in</span>
            </a>
            <a href="index.php?modul=Guest&aksi=history" class="flex-1 sm:flex-none bg-white border border-zinc-200 text-zinc-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-zinc-50 shadow-sm flex items-center justify-center gap-2">
                <i data-lucide="history" class="w-4 h-4"></i> History
            </a>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
    <?php if(empty($tamu)): ?>
        <div class="p-12 text-center flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-zinc-50 rounded-full flex items-center justify-center mb-4">
                <i data-lucide="bed-double" class="w-8 h-8 text-zinc-300"></i>
            </div>
            <h3 class="text-lg font-bold text-zinc-900">Tidak ada tamu</h3>
            <p class="text-zinc-500 text-sm mt-1 mb-4">Semua kamar kosong.</p>
            <a href="index.php?modul=Checkin&aksi=create" class="text-sm font-bold text-zinc-900 underline">Check-in Sekarang</a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-sm whitespace-nowrap" id="guestTable">
                <thead class="bg-zinc-50 border-b border-zinc-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-zinc-900">Tamu</th>
                        <th class="px-6 py-4 font-semibold text-zinc-900">Kamar</th>
                        <th class="px-6 py-4 font-semibold text-zinc-900">Tagihan</th>
                        <th class="px-6 py-4 font-semibold text-zinc-900 text-center">Status</th>
                        <th class="px-6 py-4 text-right font-semibold text-zinc-900">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    <?php foreach($tamu as $t): ?>
                        <?php 
                            $initials = substr(strtoupper($t['nama_tamu']), 0, 2);
                            $sisa = $t['total_tagihan'] - $t['total_terbayar'];
                            
                            if ($sisa <= 0) {
                                $badge = "bg-emerald-50 text-emerald-700 ring-emerald-600/20"; $stText = "LUNAS";
                            } elseif ($t['total_terbayar'] > 0) {
                                $badge = "bg-amber-50 text-amber-700 ring-amber-600/20"; $stText = "KURANG";
                            } else {
                                $badge = "bg-rose-50 text-rose-700 ring-rose-600/20"; $stText = "BELUM BAYAR";
                            }
                        ?>
                        <tr class="hover:bg-zinc-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-zinc-900 flex items-center justify-center text-xs font-bold text-white tracking-widest shrink-0">
                                        <?= $initials ?>
                                    </div>
                                    <div>
                                        <p class="font-bold text-zinc-900 search-name"><?= $t['nama_tamu'] ?></p>
                                        <div class="flex items-center gap-1 text-xs text-zinc-500 mt-0.5">
                                            <i data-lucide="phone" class="w-3 h-3"></i> <?= $t['no_hp'] ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-base font-bold text-zinc-900 search-room">Room <?= $t['list_kamar'] ?></span>
                                    <span class="text-xs text-zinc-500 font-medium uppercase"><?= $t['list_tipe'] ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <span class="text-zinc-900 font-bold">Rp <?= number_format($t['total_tagihan']) ?></span>
                                    <span class="text-xs text-zinc-500">Sisa: <span class="<?= $sisa > 0 ? 'text-rose-600 font-bold' : 'text-emerald-600' ?>">Rp <?= number_format($sisa) ?></span></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-[10px] font-bold uppercase ring-1 ring-inset <?= $badge ?>"><?= $stText ?></span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="index.php?modul=POS&aksi=index&id_transaksi=<?= $t['id_transaksi'] ?>" class="p-2 text-zinc-400 hover:text-zinc-900 hover:bg-zinc-100 rounded-lg" title="POS"><i data-lucide="utensils" class="w-4 h-4"></i></a>
                                    <a href="index.php?modul=Checkout&aksi=payment&id=<?= $t['id_transaksi'] ?>" class="p-2 text-zinc-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg" title="Detail"><i data-lucide="arrow-right" class="w-4 h-4"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        document.querySelectorAll("#guestTable tbody tr").forEach(row => {
            let txt = row.innerText.toUpperCase();
            row.style.display = txt.indexOf(filter) > -1 ? "" : "none";
        });
    });
    lucide.createIcons();
</script>
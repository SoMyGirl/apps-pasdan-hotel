<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900">Billing & Checkout</h1>
        <p class="text-zinc-500 mt-1 text-sm">Kelola pembayaran dan checkout tamu.</p>
    </div>
    <div class="relative">
        <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-zinc-400"></i>
        <input type="text" id="billSearch" placeholder="Cari No Kamar / Tamu..." 
               class="pl-9 pr-4 py-2 w-64 rounded-lg border border-zinc-200 text-sm focus:border-zinc-900 focus:ring-zinc-900 transition-all shadow-sm">
    </div>
</div>

<div class="bg-white rounded-xl border border-zinc-200 overflow-hidden shadow-sm">
    <table class="w-full text-sm text-left">
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
                    <td colspan="5" class="py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-zinc-400">
                            <i data-lucide="receipt" class="w-12 h-12 mb-3 opacity-50"></i>
                            <p class="font-medium">Tidak ada tagihan aktif.</p>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach($data as $d): ?>
                <tr class="hover:bg-zinc-50 transition-colors group">
                    <td class="px-6 py-4">
                        <span class="text-xl font-black text-zinc-800 tracking-tight search-room"><?= $d['nomor_kamar'] ?></span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="font-bold text-zinc-900 search-name"><?= $d['nama_tamu'] ?></span>
                            <span class="text-xs text-zinc-400 font-mono mt-0.5">INV: #<?= $d['no_invoice'] ?></span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <span class="font-mono font-bold text-zinc-700">Rp <?= number_format($d['total_tagihan']) ?></span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php if($d['status_bayar'] == 'lunas'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-100 text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                Lunas
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-100 text-rose-700 ring-1 ring-inset ring-rose-600/20">
                                Belum Lunas
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="index.php?modul=Checkout&aksi=payment&id=<?= $d['id_transaksi'] ?>" 
                           class="inline-flex items-center justify-center px-4 py-2 bg-white border border-zinc-200 text-zinc-900 text-xs font-bold rounded-lg hover:bg-zinc-900 hover:text-white hover:border-zinc-900 shadow-sm transition-all group-hover:shadow-md">
                           <i data-lucide="credit-card" class="w-3 h-3 mr-2"></i> Proses
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    // Simple Search Filter
    document.getElementById('billSearch').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelectorAll("#billTable tr");
        rows.forEach(row => {
            let name = row.querySelector(".search-name")?.innerText.toUpperCase() || "";
            let room = row.querySelector(".search-room")?.innerText.toUpperCase() || "";
            if (name.includes(filter) || room.includes(filter)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
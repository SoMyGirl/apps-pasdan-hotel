<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900">Financial Report</h1>
        <p class="text-zinc-500 mt-1 text-sm">Rekapitulasi pendapatan dan performa bisnis.</p>
    </div>
    <div class="flex gap-2">
        <button onclick="window.print()" class="bg-white border border-zinc-200 text-zinc-700 px-4 py-2 rounded-lg text-sm font-bold hover:bg-zinc-50 hover:border-zinc-300 transition-all shadow-sm flex items-center gap-2">
            <i data-lucide="printer" class="w-4 h-4"></i> Cetak Laporan
        </button>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    
    <div class="bg-zinc-900 rounded-xl p-6 text-white shadow-xl shadow-zinc-200 relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity transform group-hover:scale-110 duration-500">
            <i data-lucide="wallet" class="w-24 h-24"></i>
        </div>
        <div class="relative z-10">
            <p class="text-zinc-400 text-xs font-bold uppercase tracking-widest mb-1">Total Pendapatan Bersih</p>
            <h3 class="text-3xl font-black tracking-tight mt-2">
                Rp <?= number_format($stats['total_omset'] ?? 0) ?>
            </h3>
            <p class="text-emerald-400 text-xs mt-4 flex items-center gap-1">
                <i data-lucide="check-circle" class="w-3 h-3"></i> Terverifikasi Lunas
            </p>
        </div>
    </div>

    <div class="bg-white rounded-xl p-6 border border-zinc-200 shadow-sm flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4">
                <p class="text-zinc-500 text-xs font-bold uppercase tracking-widest">Total Transaksi</p>
                <div class="p-2 bg-zinc-50 rounded-lg">
                    <i data-lucide="receipt" class="w-4 h-4 text-zinc-600"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-zinc-900">
                <?= number_format($stats['total_transaksi'] ?? 0) ?>
            </h3>
        </div>
        <p class="text-xs text-zinc-400 mt-2">Transaksi berhasil checkout</p>
    </div>

    <div class="bg-white rounded-xl p-6 border border-zinc-200 shadow-sm flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4">
                <p class="text-zinc-500 text-xs font-bold uppercase tracking-widest">Rata-Rata / Tamu</p>
                <div class="p-2 bg-zinc-50 rounded-lg">
                    <i data-lucide="bar-chart-3" class="w-4 h-4 text-zinc-600"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-zinc-900">
                Rp <?= number_format($stats['rata_rata'] ?? 0) ?>
            </h3>
        </div>
        <p class="text-xs text-zinc-400 mt-2">Average Revenue Per User (ARPU)</p>
    </div>
</div>

<div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-zinc-100 bg-zinc-50/50 flex items-center justify-between">
        <h4 class="font-bold text-zinc-800 flex items-center gap-2">
            <i data-lucide="calendar-days" class="w-4 h-4 text-zinc-500"></i>
            Performa Bulanan
        </h4>
        <span class="text-xs text-zinc-400">12 Bulan Terakhir</span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-zinc-50 border-b border-zinc-100">
                <tr>
                    <th class="px-6 py-3 font-semibold text-zinc-500 w-48">Periode</th>
                    <th class="px-6 py-3 font-semibold text-zinc-500 w-32 text-center">Tamu</th>
                    <th class="px-6 py-3 font-semibold text-zinc-500 w-1/3">Grafik Pendapatan</th>
                    <th class="px-6 py-3 font-semibold text-zinc-500 text-right">Total Omset</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100">
                <?php if(empty($bulanan)): ?>
                    <tr><td colspan="4" class="p-8 text-center text-zinc-400">Belum ada data transaksi lunas.</td></tr>
                <?php else: ?>
                    <?php foreach($bulanan as $b): ?>
                        <?php 
                            // Helper Bulan Indo
                            $time = strtotime($b['periode'] . '-01');
                            $bulanIndo = [
                                'January'=>'Januari','February'=>'Februari','March'=>'Maret','April'=>'April','May'=>'Mei','June'=>'Juni',
                                'July'=>'Juli','August'=>'Agustus','September'=>'September','October'=>'Oktober','November'=>'November','December'=>'Desember'
                            ];
                            $namaBulan = $bulanIndo[date('F', $time)] . ' ' . date('Y', $time);
                            
                            // Hitung Persentase Width Grafik
                            $percent = ($maxOmset > 0) ? round(($b['omset'] / $maxOmset) * 100) : 0;
                        ?>
                        <tr class="hover:bg-zinc-50 transition-colors group">
                            <td class="px-6 py-4 font-bold text-zinc-900">
                                <?= $namaBulan ?>
                            </td>
                            
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-zinc-100 text-zinc-600 border border-zinc-200">
                                    <?= $b['jum_tamu'] ?> Tamu
                                </span>
                            </td>

                            <td class="px-6 py-4 align-middle">
                                <div class="w-full bg-zinc-100 rounded-full h-2.5 overflow-hidden">
                                    <div class="bg-zinc-900 h-2.5 rounded-full transition-all duration-1000 ease-out group-hover:bg-emerald-600" style="width: <?= $percent ?>%"></div>
                                </div>
                                <p class="text-[10px] text-zinc-400 mt-1 text-right"><?= $percent ?>% dari performa terbaik</p>
                            </td>

                            <td class="px-6 py-4 text-right font-mono font-bold text-zinc-700 group-hover:text-zinc-900">
                                Rp <?= number_format($b['omset']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    @media print {
        @page { size: landscape; margin: 1cm; }
        body * { visibility: hidden; }
        main, main * { visibility: visible; }
        main { position: absolute; left: 0; top: 0; width: 100%; padding: 0; overflow: visible; }
        /* Sembunyikan tombol saat print */
        button, a, .no-print { display: none !important; }
        /* Paksa background color tercetak */
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    }
</style>
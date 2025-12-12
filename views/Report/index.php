<?php
    // Helper Format Rupiah
    function rp($angka){ return "Rp " . number_format($angka, 0, ',', '.'); }
?>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900">Laporan Keuangan</h1>
        <p class="text-zinc-500 mt-1 text-sm">Rekapitulasi pendapatan tahun <?= $selectedYear ?>.</p>
    </div>
    
    <div class="flex gap-3">
        <form method="GET" class="flex items-center">
            <input type="hidden" name="modul" value="Report">
            <input type="hidden" name="aksi" value="index">
            
            <div class="relative">
                <i data-lucide="calendar" class="absolute left-3 top-2.5 w-4 h-4 text-zinc-500 z-10"></i>
                <select name="year" onchange="this.form.submit()" 
                        class="pl-9 pr-8 py-2 bg-white border border-zinc-200 text-zinc-700 rounded-lg text-sm font-medium shadow-sm cursor-pointer hover:bg-zinc-50 appearance-none">
                    <?php 
                    $cur = date('Y');
                    for($y=$cur; $y>=$cur-5; $y--): ?>
                        <option value="<?= $y ?>" <?= $y == $selectedYear ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </form>

        <a href="index.php?modul=Report&aksi=export&year=<?= $selectedYear ?>" target="_blank" 
           class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-emerald-700 shadow-sm flex items-center gap-2">
            <i data-lucide="file-spreadsheet" class="w-4 h-4"></i> Export Tahun <?= $selectedYear ?>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-zinc-900 rounded-xl p-6 text-white shadow-lg relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-10"><i data-lucide="wallet" class="w-24 h-24"></i></div>
        <p class="text-zinc-400 text-xs font-bold uppercase tracking-widest mb-1">Total Omset</p>
        <h3 class="text-3xl font-black tracking-tight mt-2"><?= rp($stats['total_omset'] ?? 0) ?></h3>
        <p class="text-emerald-400 text-xs mt-4 flex items-center gap-1"><i data-lucide="check-circle" class="w-3 h-3"></i> Lunas</p>
    </div>

    <div class="bg-white rounded-xl p-6 border border-zinc-200 shadow-sm">
        <p class="text-zinc-500 text-xs font-bold uppercase tracking-widest mb-1">Total Transaksi</p>
        <h3 class="text-2xl font-bold text-zinc-900 mt-2"><?= number_format($stats['total_transaksi'] ?? 0) ?></h3>
        <p class="text-xs text-zinc-400 mt-2">Tamu checkout tahun ini</p>
    </div>

    <div class="bg-white rounded-xl p-6 border border-zinc-200 shadow-sm">
        <p class="text-zinc-500 text-xs font-bold uppercase tracking-widest mb-1">Rata-Rata / Tamu</p>
        <h3 class="text-2xl font-bold text-zinc-900 mt-2"><?= rp($stats['rata_rata'] ?? 0) ?></h3>
        <p class="text-xs text-zinc-400 mt-2">Average Revenue Per User</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
    <div class="lg:col-span-1 bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-zinc-100 bg-zinc-50 flex justify-between items-center">
            <h4 class="font-bold text-zinc-800 text-sm">Performa Bulanan</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <tbody class="divide-y divide-zinc-100">
                    <?php if(empty($bulanan)): ?>
                        <tr><td class="p-6 text-center text-zinc-400 text-xs">Belum ada data.</td></tr>
                    <?php else: ?>
                        <?php foreach($bulanan as $b): ?>
                            <?php 
                                $dt = DateTime::createFromFormat('!Y-m', $b['periode_bulan']);
                                $namaBulan = $dt->format('F');
                                $percent = ($maxOmset > 0) ? round(($b['total_omset'] / $maxOmset) * 100) : 0;
                                $isActive = ($selectedMonth == $b['periode_bulan']);
                            ?>
                            <tr class="group cursor-pointer transition-colors <?= $isActive ? 'bg-blue-50' : 'hover:bg-zinc-50' ?>" 
                                onclick="window.location.href='index.php?modul=Report&aksi=index&year=<?= $selectedYear ?>&detail_bulan=<?= $b['periode_bulan'] ?>'">
                                <td class="px-5 py-3">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="font-bold <?= $isActive ? 'text-blue-700' : 'text-zinc-700' ?>"><?= $namaBulan ?></span>
                                        <span class="text-xs font-mono text-zinc-500"><?= rp($b['total_omset']) ?></span>
                                    </div>
                                    <div class="w-full bg-zinc-100 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-zinc-900 h-1.5 rounded-full" style="width: <?= $percent ?>%"></div>
                                    </div>
                                </td>
                                <td class="px-2 py-3 text-right">
                                    <i data-lucide="chevron-right" class="w-4 h-4 text-zinc-300"></i>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="lg:col-span-2">
        <?php if($selectedMonth): ?>
            <?php 
                 $dtMonth = DateTime::createFromFormat('!Y-m', $selectedMonth);
                 $niceMonth = $dtMonth->format('F Y');
            ?>
            <div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-zinc-100 bg-zinc-50 flex justify-between items-center">
                    <h4 class="font-bold text-zinc-800 text-sm flex items-center gap-2">
                        <i data-lucide="list" class="w-4 h-4"></i> Detail: <?= $niceMonth ?>
                    </h4>
                    <a href="index.php?modul=Report&aksi=export&year=<?= $selectedYear ?>&bulan=<?= $selectedMonth ?>" 
                       target="_blank" class="text-xs font-bold text-emerald-600 hover:underline flex items-center gap-1">
                        <i data-lucide="download" class="w-3 h-3"></i> Export Excel
                    </a>
                </div>
                <div class="overflow-x-auto max-h-[500px] custom-scrollbar">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-zinc-50 border-b border-zinc-100 sticky top-0">
                            <tr>
                                <th class="px-6 py-3 font-semibold text-zinc-500">Tgl</th>
                                <th class="px-6 py-3 font-semibold text-zinc-500">Invoice</th>
                                <th class="px-6 py-3 font-semibold text-zinc-500">Tamu</th>
                                <th class="px-6 py-3 font-semibold text-zinc-500 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            <?php if(empty($detailData)): ?>
                                <tr><td colspan="4" class="p-8 text-center text-zinc-400">Tidak ada data.</td></tr>
                            <?php else: ?>
                                <?php foreach($detailData as $d): ?>
                                <tr class="hover:bg-zinc-50">
                                    <td class="px-6 py-3 text-zinc-500 text-xs">
                                        <?= date('d/m/Y', strtotime($d['tgl_checkin'])) ?>
                                    </td>
                                    <td class="px-6 py-3 font-mono text-xs text-zinc-600">
                                        <?= $d['no_invoice'] ?>
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="font-bold text-zinc-800"><?= $d['nama_tamu'] ?></div>
                                        <div class="text-xs text-zinc-400">Room: <?= $d['nomor_kamar'] ?></div>
                                    </td>
                                    <td class="px-6 py-3 text-right font-medium text-zinc-900">
                                        <?= rp($d['total_tagihan']) ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="h-full flex flex-col items-center justify-center text-zinc-400 border-2 border-dashed border-zinc-200 rounded-xl bg-zinc-50/50 p-12">
                <i data-lucide="bar-chart-2" class="w-12 h-12 mb-3 opacity-20"></i>
                <p class="text-sm">Klik salah satu bulan di samping<br>untuk melihat detail transaksi.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>lucide.createIcons();</script>
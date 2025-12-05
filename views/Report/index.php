<h2 class="text-2xl font-bold mb-6">Laporan Pendapatan</h2>

<div class="bg-zinc-900 text-white p-6 rounded-xl shadow-lg mb-8 inline-block min-w-[300px]">
    <p class="text-zinc-400 text-sm uppercase mb-1">Total Pendapatan Bersih</p>
    <h3 class="text-4xl font-bold">Rp <?= number_format($total) ?></h3>
</div>

<div class="bg-white rounded-xl border border-zinc-200 overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-zinc-50 border-b">
            <tr><th class="px-6 py-4">Periode</th><th class="px-6 py-4">Jumlah Tamu</th><th class="px-6 py-4 text-right">Omset</th></tr>
        </thead>
        <tbody class="divide-y divide-zinc-100">
            <?php foreach($bulanan as $b): ?>
            <tr>
                <td class="px-6 py-4 font-bold"><?= $b['periode'] ?></td>
                <td class="px-6 py-4"><?= $b['tamu'] ?> Orang</td>
                <td class="px-6 py-4 text-right font-mono text-green-600 font-bold">Rp <?= number_format($b['omset']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-zinc-900">Tamu In-House</h2>
    <a href="index.php?modul=Checkin&aksi=create" class="bg-zinc-900 text-white px-4 py-2 rounded-md text-sm hover:bg-zinc-800 flex items-center gap-2">
        <i data-lucide="plus" class="w-4 h-4"></i> Tamu Baru
    </a>
</div>

<div class="bg-white rounded-xl border border-zinc-200 overflow-hidden shadow-sm">
    <table class="w-full text-sm text-left">
        <thead class="bg-zinc-50 border-b">
            <tr>
                <th class="px-6 py-3 font-medium">Kamar</th>
                <th class="px-6 py-3 font-medium">Nama Tamu</th>
                <th class="px-6 py-3 font-medium">Check-in</th>
                <th class="px-6 py-3 font-medium">Status Bayar</th>
                <th class="px-6 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-zinc-100">
            <?php foreach($tamu as $t): ?>
            <tr class="hover:bg-zinc-50">
                <td class="px-6 py-4 font-bold"><?= $t['nomor_kamar'] ?></td>
                <td class="px-6 py-4">
                    <p class="font-medium"><?= $t['nama_tamu'] ?></p>
                    <p class="text-xs text-zinc-500"><?= $t['no_hp'] ?></p>
                </td>
                <td class="px-6 py-4 text-zinc-600"><?= date('d M H:i', strtotime($t['tgl_checkin'])) ?></td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded-full text-xs font-medium <?= $t['status_bayar']=='lunas'?'bg-green-100 text-green-700':'bg-yellow-100 text-yellow-700' ?>">
                        <?= strtoupper($t['status_bayar']) ?>
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="index.php?modul=Checkout&aksi=payment&id=<?= $t['id_transaksi'] ?>" class="text-zinc-900 font-medium hover:underline">Detail</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
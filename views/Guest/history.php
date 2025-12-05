<div class="mb-6">
    <h2 class="text-2xl font-bold text-zinc-900">Riwayat Semua Tamu</h2>
    <p class="text-zinc-500 text-sm">Data historis tamu yang pernah menginap (Check-in & Check-out).</p>
</div>

<div class="bg-white rounded-xl border border-zinc-200 overflow-hidden shadow-sm">
    <table class="w-full text-sm text-left">
        <thead class="bg-zinc-50 border-b border-zinc-100">
            <tr>
                <th class="px-6 py-3 font-medium">Invoice</th>
                <th class="px-6 py-3 font-medium">Nama Tamu</th>
                <th class="px-6 py-3 font-medium">Kamar</th>
                <th class="px-6 py-3 font-medium">Tgl Check-in</th>
                <th class="px-6 py-3 font-medium">Tgl Check-out</th>
                <th class="px-6 py-3 font-medium">Status Transaksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-zinc-100">
            <?php foreach($tamu as $t): ?>
            <tr class="hover:bg-zinc-50">
                <td class="px-6 py-4 font-mono text-xs text-zinc-500">
                    <a href="index.php?modul=Checkout&aksi=payment&id=<?= $t['id_transaksi'] ?>" class="text-blue-600 hover:underline">
                        #<?= $t['no_invoice'] ?>
                    </a>
                </td>
                <td class="px-6 py-4 font-medium text-zinc-900"><?= $t['nama_tamu'] ?></td>
                <td class="px-6 py-4 text-zinc-600"><?= $t['nomor_kamar'] ?></td>
                <td class="px-6 py-4 text-zinc-600"><?= date('d/m/Y H:i', strtotime($t['tgl_checkin'])) ?></td>
                <td class="px-6 py-4 text-zinc-600">
                    <?= $t['tgl_checkout'] ? date('d/m/Y H:i', strtotime($t['tgl_checkout'])) : '-' ?>
                </td>
                <td class="px-6 py-4">
                    <?php if($t['status_transaksi'] == 'active'): ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active (In-House)
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-zinc-100 text-zinc-600">
                            Finished
                        </span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
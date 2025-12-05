<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-zinc-900">Kelola Kamar</h2>
    <a href="index.php?modul=Room&aksi=create" class="bg-zinc-900 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-zinc-800 flex items-center gap-2">
        <i data-lucide="plus" class="w-4 h-4"></i> Tambah Kamar
    </a>
</div>

<div class="bg-white rounded-xl border border-zinc-200 overflow-hidden shadow-sm">
    <table class="w-full text-sm text-left">
        <thead class="bg-zinc-50 border-b">
            <tr><th class="px-6 py-3">No Kamar</th><th class="px-6 py-3">Tipe</th><th class="px-6 py-3">Harga Dasar</th><th class="px-6 py-3">Status</th><th class="px-6 py-3 text-right">Aksi</th></tr>
        </thead>
        <tbody class="divide-y divide-zinc-100">
            <?php foreach($kamar as $k): ?>
            <tr class="hover:bg-zinc-50">
                <td class="px-6 py-3 font-bold text-lg"><?= $k['nomor_kamar'] ?></td>
                <td class="px-6 py-3"><?= $k['nama_tipe'] ?></td>
                <td class="px-6 py-3">Rp <?= number_format($k['harga_dasar']) ?></td>
                <td class="px-6 py-3">
                    <span class="text-xs px-2 py-1 bg-zinc-100 rounded border border-zinc-200 uppercase font-medium"><?= $k['status'] ?></span>
                </td>
                <td class="px-6 py-3 text-right">
                    <a href="index.php?modul=Room&aksi=index&hapus=<?= $k['id_kamar'] ?>" class="btn-confirm text-red-600 hover:underline" data-pesan="Hapus kamar ini?">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
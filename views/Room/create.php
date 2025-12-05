<div class="max-w-md mx-auto">
    <div class="mb-6">
        <a href="index.php?modul=Room&aksi=index" class="text-sm text-zinc-500 hover:text-zinc-900 flex items-center gap-1 mb-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
        </a>
        <h2 class="text-2xl font-bold text-zinc-900">Tambah Kamar Baru</h2>
    </div>

    <div class="bg-white p-6 rounded-xl border border-zinc-200 shadow-sm">
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">Nomor Kamar</label>
                <input type="text" name="nomor" placeholder="Contoh: 101" required class="w-full rounded-md border-zinc-300 focus:ring-2 focus:ring-zinc-900">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">Tipe Kamar</label>
                <select name="tipe" required class="w-full rounded-md border-zinc-300 focus:ring-2 focus:ring-zinc-900">
                    <?php foreach($tipe as $t): ?>
                        <option value="<?= $t['id_tipe'] ?>"><?= $t['nama_tipe'] ?> (Rp <?= number_format($t['harga_dasar']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="w-full bg-zinc-900 text-white py-2 rounded-md font-medium hover:bg-zinc-800 transition-colors">
                Simpan
            </button>
        </form>
    </div>
</div>
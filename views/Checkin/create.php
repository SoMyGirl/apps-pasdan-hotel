<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl border border-zinc-200 shadow-sm">
    <h2 class="text-2xl font-bold mb-6">Formulir Check-in</h2>
    <form method="POST" class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1">Nama Tamu</label>
            <input type="text" name="nama" required class="w-full rounded-md border-zinc-300 focus:ring-zinc-900">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">No HP</label>
            <input type="text" name="hp" required class="w-full rounded-md border-zinc-300 focus:ring-zinc-900">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Pilih Kamar</label>
            <select name="id_kamar" required class="w-full rounded-md border-zinc-300 focus:ring-zinc-900">
                <option value="">-- Pilih Kamar --</option>
                <?php foreach($kamar as $k): ?>
                    <option value="<?= $k['id_kamar'] ?>" <?= (isset($_GET['id_kamar']) && $_GET['id_kamar'] == $k['id_kamar']) ? 'selected' : '' ?>>
                        <?= $k['nomor_kamar'] ?> - <?= $k['nama_tipe'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Tgl Checkin</label>
                <input type="datetime-local" name="tgl" value="<?= date('Y-m-d\TH:i') ?>" class="w-full rounded-md border-zinc-300">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Durasi (Malam)</label>
                <input type="number" name="durasi" value="1" min="1" class="w-full rounded-md border-zinc-300">
            </div>
        </div>
        <button type="submit" class="w-full bg-zinc-900 text-white py-2 rounded-md hover:bg-zinc-800">Proses Check-in</button>
    </form>
</div>
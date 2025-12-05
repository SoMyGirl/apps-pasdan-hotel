<div class="mb-6">
    <h2 class="text-2xl font-bold text-zinc-900">Master Layanan (POS)</h2>
    <p class="text-zinc-500 text-sm">Kelola daftar menu makanan, minuman, atau jasa laundry.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    
    <div class="md:col-span-1">
        <div class="bg-white p-6 rounded-xl border border-zinc-200 shadow-sm sticky top-4">
            <h3 class="font-bold text-lg mb-4 text-zinc-900 border-b pb-2">Tambah Menu Baru</h3>
            
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">Nama Item</label>
                    <input type="text" name="nama" placeholder="Contoh: Nasi Goreng" required 
                           class="w-full rounded-md border-zinc-300 focus:ring-2 focus:ring-zinc-900 focus:border-transparent text-sm">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">Harga Satuan (Rp)</label>
                    <input type="number" name="harga" placeholder="0" required 
                           class="w-full rounded-md border-zinc-300 focus:ring-2 focus:ring-zinc-900 focus:border-transparent text-sm">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">Satuan</label>
                        <input type="text" name="satuan" placeholder="porsi/pcs" required 
                               class="w-full rounded-md border-zinc-300 focus:ring-2 focus:ring-zinc-900 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">Kategori</label>
                        <select name="kategori" class="w-full rounded-md border-zinc-300 focus:ring-2 focus:ring-zinc-900 text-sm">
                            <option value="makanan">Makanan</option>
                            <option value="minuman">Minuman</option>
                            <option value="jasa">Jasa</option>
                        </select>
                    </div>
                </div>

                <button type="submit" name="simpan" 
                        class="w-full bg-zinc-900 text-white py-2.5 rounded-md text-sm font-medium hover:bg-zinc-800 transition-colors flex items-center justify-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> Simpan Menu
                </button>
            </form>
        </div>
    </div>

    <div class="md:col-span-2">
        <div class="bg-white rounded-xl border border-zinc-200 overflow-hidden shadow-sm">
            <table class="w-full text-sm text-left">
                <thead class="bg-zinc-50 border-b border-zinc-100">
                    <tr>
                        <th class="px-6 py-3 font-medium text-zinc-500">Nama Item</th>
                        <th class="px-6 py-3 font-medium text-zinc-500">Kategori</th>
                        <th class="px-6 py-3 font-medium text-zinc-500 text-right">Harga</th>
                        <th class="px-6 py-3 font-medium text-zinc-500 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    <?php if(empty($data)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-zinc-400 italic">
                                Belum ada menu layanan. Silakan tambah di form sebelah kiri.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($data as $d): ?>
                        <tr class="hover:bg-zinc-50 transition-colors">
                            <td class="px-6 py-3 font-medium text-zinc-900"><?= $d['nama_layanan'] ?></td>
                            <td class="px-6 py-3">
                                <?php 
                                $bg = match($d['kategori']) {
                                    'makanan' => 'bg-orange-100 text-orange-800',
                                    'minuman' => 'bg-blue-100 text-blue-800',
                                    default => 'bg-purple-100 text-purple-800'
                                };
                                ?>
                                <span class="px-2 py-1 rounded text-xs font-medium <?= $bg ?>">
                                    <?= ucfirst($d['kategori']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-3 text-right font-mono text-zinc-600">
                                Rp <?= number_format($d['harga_satuan']) ?> <span class="text-xs text-zinc-400">/<?= $d['satuan'] ?></span>
                            </td>
                            <td class="px-6 py-3 text-right">
                                <a href="index.php?modul=Layanan&aksi=index&hapus=<?= $d['id_layanan'] ?>" 
                                   class="btn-confirm text-red-600 hover:text-red-800 text-xs font-medium border border-red-100 px-2 py-1 rounded hover:bg-red-50"
                                   data-pesan="Hapus menu <?= $d['nama_layanan'] ?>?">
                                   Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
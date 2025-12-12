<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-zinc-900">Menu & Layanan</h1>
        <p class="text-zinc-500 mt-1 text-sm">Kelola daftar produk POS.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
    
    <div class="lg:col-span-1 lg:sticky lg:top-6">
        <div class="bg-white rounded-xl border border-zinc-200 shadow-lg overflow-hidden">
            <div class="bg-zinc-50 px-6 py-4 border-b border-zinc-100 flex items-center gap-3">
                <div class="bg-white p-1.5 rounded-md shadow-sm ring-1 ring-zinc-200"><i data-lucide="plus" class="w-4 h-4 text-zinc-900"></i></div>
                <h3 class="font-bold text-zinc-900 text-sm">Tambah Item</h3>
            </div>
            <div class="p-6">
                <form method="POST" class="space-y-4">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-500 mb-1">Nama Item</label>
                        <input type="text" name="nama" placeholder="Contoh: Nasi Goreng" required class="w-full h-10 px-3 rounded-lg border-zinc-200 text-sm focus:border-zinc-900 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-500 mb-1">Harga</label>
                        <input type="number" name="harga" placeholder="0" required class="w-full h-10 px-3 rounded-lg border-zinc-200 text-sm focus:border-zinc-900 shadow-sm">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-500 mb-1">Kategori</label>
                            <select name="kategori" class="w-full h-10 px-2 rounded-lg border-zinc-200 text-xs font-medium focus:border-zinc-900 bg-white">
                                <option value="makanan">Makanan</option>
                                <option value="minuman">Minuman</option>
                                <option value="jasa">Jasa</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-500 mb-1">Satuan</label>
                            <input type="text" name="satuan" placeholder="pcs" required class="w-full h-10 px-3 rounded-lg border-zinc-200 text-sm focus:border-zinc-900">
                        </div>
                    </div>
                    <button type="submit" name="simpan" class="w-full h-10 bg-zinc-900 text-white rounded-lg text-sm font-bold hover:bg-zinc-800 shadow-md mt-2">Simpan</button>
                </form>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="mb-4 flex justify-end">
            <div class="relative w-full md:w-64">
                <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-zinc-400"></i>
                <input type="text" id="searchMenu" placeholder="Cari menu..." class="w-full pl-9 pr-4 py-2 rounded-lg border border-zinc-200 text-sm focus:border-zinc-900 shadow-sm">
            </div>
        </div>

        <div class="bg-white rounded-xl border border-zinc-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm text-left whitespace-nowrap">
                    <thead class="bg-zinc-50 border-b border-zinc-100">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-zinc-500">Item</th>
                            <th class="px-6 py-4 font-semibold text-zinc-500">Kategori</th>
                            <th class="px-6 py-4 font-semibold text-zinc-500 text-right">Harga</th>
                            <th class="px-6 py-4 font-semibold text-zinc-500 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100" id="menuTable">
                        <?php if(empty($data)): ?>
                            <tr><td colspan="4" class="p-8 text-center text-zinc-400">Belum ada data.</td></tr>
                        <?php else: ?>
                            <?php foreach($data as $d): ?>
                                <tr class="hover:bg-zinc-50 group">
                                    <td class="px-6 py-4 font-bold text-zinc-900 search-name"><?= $d['nama_layanan'] ?></td>
                                    <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs font-bold bg-zinc-100 text-zinc-600 uppercase"><?= $d['kategori'] ?></span></td>
                                    <td class="px-6 py-4 text-right font-mono">Rp <?= number_format($d['harga_satuan']) ?> <span class="text-xs text-zinc-400">/<?= $d['satuan'] ?></span></td>
                                    <td class="px-6 py-4 text-right">
                                        <button onclick="confirmDelete(<?= $d['id_layanan'] ?>, '<?= $d['nama_layanan'] ?>')" class="p-2 text-zinc-400 hover:text-rose-600 rounded-lg transition-colors"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('searchMenu').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        document.querySelectorAll("#menuTable tr").forEach(row => {
            row.style.display = row.querySelector(".search-name").innerText.toUpperCase().indexOf(filter) > -1 ? "" : "none";
        });
    });
    function confirmDelete(id, nama) {
        Swal.fire({ title: 'Hapus?', text: nama, icon: 'warning', showCancelButton: true, confirmButtonColor: '#e11d48' })
        .then((r) => { if(r.isConfirmed) window.location.href="index.php?modul=Layanan&aksi=index&hapus="+id; });
    }
    lucide.createIcons();
</script>
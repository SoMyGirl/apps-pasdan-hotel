<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900">Menu & Layanan</h1>
        <p class="text-zinc-500 mt-1 text-sm">Kelola daftar produk untuk Point of Sales (POS).</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
    
    <div class="lg:col-span-1 sticky top-6">
        <div class="bg-white rounded-xl border border-zinc-200 shadow-lg shadow-zinc-200/50 overflow-hidden">
            
            <div class="bg-zinc-50 px-6 py-4 border-b border-zinc-100 flex items-center gap-3">
                <div class="bg-white p-1.5 rounded-md shadow-sm ring-1 ring-zinc-200 flex-shrink-0">
                    <i data-lucide="plus" class="w-4 h-4 text-zinc-900"></i>
                </div>
                <div>
                    <h3 class="font-bold text-zinc-900 text-sm leading-tight">Tambah Item</h3>
                    <p class="text-[10px] text-zinc-500">Menu baru untuk POS.</p>
                </div>
            </div>
            
            <div class="p-6">
                <form method="POST" class="space-y-5">
                    
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-500 mb-2">Nama Menu / Layanan</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="tag" class="w-4 h-4 text-zinc-400 group-focus-within:text-zinc-900 transition-colors"></i>
                            </div>
                            <input type="text" name="nama" placeholder="Cth: Nasi Goreng Spesial" required 
                                class="block w-full h-10 pl-10 pr-3 rounded-lg border-zinc-200 text-sm shadow-sm focus:border-zinc-900 focus:ring-zinc-900 placeholder:text-zinc-300 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-500 mb-2">Harga Satuan</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-zinc-400 text-xs font-bold group-focus-within:text-zinc-900 transition-colors">Rp</span>
                            </div>
                            <input type="number" name="harga" placeholder="0" required 
                                class="block w-full h-10 pl-10 pr-3 rounded-lg border-zinc-200 text-sm font-mono font-medium shadow-sm focus:border-zinc-900 focus:ring-zinc-900 placeholder:text-zinc-300 transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-500 mb-2">Kategori</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                    <i data-lucide="layout-grid" class="w-3.5 h-3.5 text-zinc-400 group-focus-within:text-zinc-900 transition-colors"></i>
                                </div>
                                <select name="kategori" class="block w-full h-10 pl-9 pr-8 rounded-lg border-zinc-200 text-xs font-medium shadow-sm focus:border-zinc-900 focus:ring-zinc-900 bg-white appearance-none cursor-pointer transition-all">
                                    <option value="makanan">Makanan</option>
                                    <option value="minuman">Minuman</option>
                                    <option value="jasa">Jasa / Laundry</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none">
                                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-zinc-400"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-500 mb-2">Satuan</label>
                            <div class="relative group">
                                <input type="text" name="satuan" placeholder="pcs / porsi" required 
                                    class="block w-full h-10 px-3 rounded-lg border-zinc-200 text-xs font-medium shadow-sm focus:border-zinc-900 focus:ring-zinc-900 placeholder:text-zinc-300 transition-all text-center">
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" name="simpan" class="w-full h-10 bg-zinc-900 text-white rounded-lg text-sm font-bold hover:bg-zinc-800 active:scale-[0.98] transition-all shadow-md flex justify-center items-center gap-2 group">
                            <i data-lucide="save" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                            Simpan Data
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2">
        
        <div class="mb-4 flex justify-end">
            <div class="relative w-full md:w-64">
                <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-zinc-400"></i>
                <input type="text" id="searchMenu" placeholder="Cari menu..." 
                       class="w-full pl-9 pr-4 py-2 rounded-lg border border-zinc-200 text-sm focus:border-zinc-900 focus:ring-zinc-900 transition-all shadow-sm bg-white">
            </div>
        </div>

        <div class="bg-white rounded-xl border border-zinc-200 overflow-hidden shadow-sm">
            <table class="w-full text-sm text-left">
                <thead class="bg-zinc-50/50 border-b border-zinc-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-zinc-500">Nama Item</th>
                        <th class="px-6 py-4 font-semibold text-zinc-500">Kategori</th>
                        <th class="px-6 py-4 font-semibold text-zinc-500 text-right">Harga</th>
                        <th class="px-6 py-4 font-semibold text-zinc-500 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100" id="menuTable">
                    <?php if(empty($data)): ?>
                        <tr>
                            <td colspan="4" class="p-12 text-center text-zinc-400">
                                <i data-lucide="utensils-crossed" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
                                <p>Belum ada data menu.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($data as $d): ?>
                            <?php 
                                // Logic Ikon & Warna Kategori
                                $icon = match($d['kategori']){
                                    'makanan' => 'utensils',
                                    'minuman' => 'coffee',
                                    default   => 'shirt' // Jasa/Laundry
                                };
                                $badgeClass = match($d['kategori']){
                                    'makanan' => 'bg-orange-50 text-orange-700 ring-orange-600/20',
                                    'minuman' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                    default   => 'bg-purple-50 text-purple-700 ring-purple-600/20'
                                };
                            ?>
                            <tr class="hover:bg-zinc-50 transition-colors group">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-zinc-900 search-name"><?= $d['nama_layanan'] ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase ring-1 ring-inset <?= $badgeClass ?>">
                                        <i data-lucide="<?= $icon ?>" class="w-3 h-3"></i>
                                        <?= $d['kategori'] ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex flex-col items-end">
                                        <span class="font-mono font-bold text-zinc-700">Rp <?= number_format($d['harga_satuan']) ?></span>
                                        <span class="text-[10px] text-zinc-400">per <?= $d['satuan'] ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button onclick="confirmDelete(<?= $d['id_layanan'] ?>, '<?= $d['nama_layanan'] ?>')" 
                                            class="inline-flex items-center justify-center p-2 text-zinc-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all"
                                            title="Hapus Menu">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // 1. Search Logic
    document.getElementById('searchMenu').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelectorAll("#menuTable tr");
        rows.forEach(row => {
            let txt = row.querySelector(".search-name").innerText;
            if (txt.toUpperCase().indexOf(filter) > -1) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });

    // 2. Delete Confirmation
    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'Hapus Menu?',
            text: nama + " akan dihapus permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#e4e4e7',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: '<span class="text-zinc-600">Batal</span>',
            customClass: { popup: 'rounded-xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "index.php?modul=Layanan&aksi=index&hapus=" + id;
            }
        })
    }
</script>
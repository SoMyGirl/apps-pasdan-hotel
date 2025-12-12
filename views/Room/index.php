<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-zinc-900">Master Kamar</h1>
        <p class="text-zinc-500 mt-1 text-sm">Kelola daftar kamar, tipe, dan status ketersediaan.</p>
    </div>
    
    <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3 w-full md:w-auto">
        
        <div class="relative w-full md:w-48">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="filter" class="h-4 w-4 text-zinc-400"></i>
            </div>
            <select onchange="window.location.href='index.php?modul=Room&aksi=index&tipe='+this.value" 
                    class="pl-10 pr-8 py-2 w-full rounded-lg border border-zinc-200 text-sm focus:border-zinc-900 focus:ring-zinc-900 transition-all shadow-sm appearance-none bg-white cursor-pointer">
                <option value="">Semua Tipe</option>
                <?php foreach($listTipe as $t): ?>
                    <option value="<?= $t['id_tipe'] ?>" <?= $selectedTipe == $t['id_tipe'] ? 'selected' : '' ?>>
                        <?= $t['nama_tipe'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <i data-lucide="chevron-down" class="h-4 w-4 text-zinc-400"></i>
            </div>
        </div>

        <div class="relative w-full md:w-64">
            <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-zinc-400"></i>
            <input type="text" id="searchRoom" placeholder="Cari nomor kamar..." 
                   class="pl-9 pr-4 py-2 w-full rounded-lg border border-zinc-200 text-sm focus:border-zinc-900 focus:ring-zinc-900 transition-all shadow-sm">
        </div>
        
        <?php if($_SESSION['role'] == 'admin'): ?>
            <a href="index.php?modul=Room&aksi=create" class="bg-zinc-900 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-zinc-800 transition-all shadow-sm flex items-center justify-center gap-2 whitespace-nowrap">
                <i data-lucide="plus" class="w-4 h-4"></i> <span class="md:hidden lg:inline">Tambah</span>
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto w-full">
        <table class="w-full text-sm text-left whitespace-nowrap">
            <thead class="bg-zinc-50 border-b border-zinc-100">
                <tr>
                    <th class="px-6 py-4 font-semibold text-zinc-500">Nomor Kamar</th>
                    <th class="px-6 py-4 font-semibold text-zinc-500">Tipe Kamar</th>
                    <th class="px-6 py-4 font-semibold text-zinc-500">Harga Dasar</th>
                    <th class="px-6 py-4 font-semibold text-zinc-500 text-center">Status</th>
                    <?php if($_SESSION['role'] == 'admin'): ?>
                        <th class="px-6 py-4 font-semibold text-zinc-500 text-right">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100" id="roomTable">
                <?php foreach($kamar as $k): ?>
                    <?php 
                        $statusClass = "";
                        $label = ucfirst($k['status']);
                        if($k['status'] == 'available') {
                            $statusClass = "bg-emerald-50 text-emerald-700 ring-emerald-600/20";
                        } elseif($k['status'] == 'occupied') {
                            $statusClass = "bg-rose-50 text-rose-700 ring-rose-600/20";
                        } else { 
                            $statusClass = "bg-amber-50 text-amber-700 ring-amber-600/20";
                        }
                    ?>
                    <tr class="hover:bg-zinc-50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-zinc-100 flex items-center justify-center text-zinc-500 group-hover:bg-zinc-900 group-hover:text-white transition-colors">
                                    <i data-lucide="key" class="w-5 h-5"></i>
                                </div>
                                <span class="text-lg font-bold text-zinc-900 search-key"><?= $k['nomor_kamar'] ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-medium text-zinc-700"><?= $k['nama_tipe'] ?></span>
                        </td>
                        <td class="px-6 py-4 font-mono text-zinc-600">
                            Rp <?= number_format($k['harga_dasar']) ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold ring-1 ring-inset <?= $statusClass ?>">
                                <?= $label ?>
                            </span>
                        </td>
                        
                        <?php if($_SESSION['role'] == 'admin'): ?>
                        <td class="px-6 py-4 text-right">
                            <button onclick="confirmDelete(<?= $k['id_kamar'] ?>, '<?= $k['nomor_kamar'] ?>')" 
                                    class="inline-flex items-center justify-center p-2 text-zinc-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <?php if(empty($kamar)): ?>
        <div class="p-12 text-center text-zinc-400">
            <i data-lucide="filter-x" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
            <p>Tidak ada kamar ditemukan.</p>
        </div>
    <?php endif; ?>
</div>

<script>
    document.getElementById('searchRoom').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelectorAll("#roomTable tr");
        rows.forEach(row => {
            let txt = row.querySelector(".search-key").innerText;
            row.style.display = txt.toUpperCase().indexOf(filter) > -1 ? "" : "none";
        });
    });
    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'Hapus Kamar '+nama+'?', text: "Data hilang permanen!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#e11d48', confirmButtonText: 'Hapus'
        }).then((result) => { if (result.isConfirmed) window.location.href = "index.php?modul=Room&aksi=index&hapus=" + id; })
    }
    lucide.createIcons();
</script>
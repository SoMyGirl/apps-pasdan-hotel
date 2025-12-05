<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900">In-House Guests</h1>
        <p class="text-zinc-500 mt-1 text-sm">Daftar tamu yang sedang menginap di hotel saat ini.</p>
    </div>
    <div class="flex items-center gap-3">
        <div class="relative">
            <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-zinc-400"></i>
            <input type="text" id="searchInput" placeholder="Cari nama atau kamar..." 
                   class="pl-9 pr-4 py-2 w-64 rounded-lg border border-zinc-200 text-sm focus:border-zinc-900 focus:ring-zinc-900 transition-all shadow-sm">
        </div>
        <a href="index.php?modul=Checkin&aksi=create" class="bg-zinc-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-zinc-800 transition-colors shadow-sm flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Check-in Baru
        </a>
        <a href="index.php?modul=Guest&aksi=history" class="bg-zinc-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-zinc-800 transition-colors shadow-sm flex items-center gap-2">
            history
        </a>
    </div>
</div>

<div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
    <?php if(empty($tamu)): ?>
        <div class="p-12 text-center flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-zinc-50 rounded-full flex items-center justify-center mb-4">
                <i data-lucide="bed-double" class="w-8 h-8 text-zinc-300"></i>
            </div>
            <h3 class="text-lg font-bold text-zinc-900">Tidak ada tamu menginap</h3>
            <p class="text-zinc-500 text-sm mt-1 max-w-xs mx-auto">Saat ini seluruh kamar dalam keadaan kosong. Silakan lakukan Check-in baru.</p>
            <a href="index.php?modul=Checkin&aksi=create" class="mt-4 text-sm font-bold text-zinc-900 underline hover:text-zinc-700">
                Buat Check-in Sekarang
            </a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm" id="guestTable">
                <thead class="bg-zinc-50/50 border-b border-zinc-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-zinc-900">Tamu</th>
                        <th class="px-6 py-4 font-semibold text-zinc-900">Kamar</th>
                        <th class="px-6 py-4 font-semibold text-zinc-900">Durasi / Tagihan</th>
                        <th class="px-6 py-4 font-semibold text-zinc-900">Status Pembayaran</th>
                        <th class="px-6 py-4 text-right font-semibold text-zinc-900">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    <?php foreach($tamu as $t): ?>
                        <?php 
                            // Inisial Nama untuk Avatar
                            $initials = collect(explode(' ', $t['nama_tamu']))->map(fn($word) => strtoupper(substr($word, 0, 1)))->take(2)->join('');
                            
                            // Styling Badge
                            $badgeClass = $t['status_bayar'] == 'lunas' 
                                ? "bg-emerald-50 text-emerald-700 ring-emerald-600/20" 
                                : "bg-amber-50 text-amber-700 ring-amber-600/20";
                        ?>
                        <tr class="hover:bg-zinc-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-zinc-900 flex items-center justify-center text-xs font-bold text-white tracking-widest shrink-0">
                                        <?= $initials ?>
                                    </div>
                                    <div>
                                        <p class="font-bold text-zinc-900 search-name"><?= $t['nama_tamu'] ?></p>
                                        <div class="flex items-center gap-1 text-xs text-zinc-500 mt-0.5">
                                            <i data-lucide="phone" class="w-3 h-3"></i> <?= $t['no_hp'] ?>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-base font-bold text-zinc-900 search-room">Room <?= $t['nomor_kamar'] ?></span>
                                    <span class="text-xs text-zinc-500 font-medium uppercase tracking-wide"><?= $t['nama_tipe'] ?></span>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <span class="text-zinc-900 font-medium flex items-center gap-2">
                                        <i data-lucide="calendar-clock" class="w-4 h-4 text-zinc-400"></i>
                                        <?= date('d M, H:i', strtotime($t['tgl_checkin'])) ?>
                                    </span>
                                    <span class="text-xs text-zinc-500">
                                        Est. Checkout: <?= date('d M', strtotime($t['tgl_checkin'] . ' + '.$t['durasi_malam'].' days')) ?>
                                    </span>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset <?= $badgeClass ?>">
                                    <?= $t['status_bayar'] == 'belum_bayar' ? 'Belum Lunas' : 'Lunas' ?>
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="index.php?modul=POS&aksi=index&id_transaksi=<?= $t['id_transaksi'] ?>" 
                                       class="p-2 text-zinc-400 hover:text-zinc-900 hover:bg-zinc-100 rounded-lg transition-all" 
                                       title="Tambah Layanan/Menu">
                                        <i data-lucide="utensils" class="w-4 h-4"></i>
                                    </a>
                                    <a href="index.php?modul=Checkout&aksi=payment&id=<?= $t['id_transaksi'] ?>" 
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-zinc-200 bg-white text-xs font-bold text-zinc-700 hover:border-zinc-900 hover:bg-zinc-50 transition-all shadow-sm">
                                        Manage
                                        <i data-lucide="chevron-right" class="w-3 h-3"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 bg-zinc-50 border-t border-zinc-200 flex items-center justify-between">
            <span class="text-xs text-zinc-500">Menampilkan <?= count($tamu) ?> tamu aktif</span>
        </div>
    <?php endif; ?>
</div>

<?php
// Function kecil jika Anda belum punya library 'collect'
function collect($array) {
    return new class($array) {
        private $arr;
        public function __construct($arr) { $this->arr = $arr; }
        public function map($cb) { return new self(array_map($cb, $this->arr)); }
        public function take($n) { return new self(array_slice($this->arr, 0, $n)); }
        public function join($glue) { return implode($glue, $this->arr); }
    };
}
?>

<script>
    // Simple Client-Side Search
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelectorAll("#guestTable tbody tr");

        rows.forEach(row => {
            let name = row.querySelector(".search-name").innerText.toUpperCase();
            let room = row.querySelector(".search-room").innerText.toUpperCase();
            
            if (name.indexOf(filter) > -1 || room.indexOf(filter) > -1) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
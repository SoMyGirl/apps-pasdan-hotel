<h2 class="text-3xl font-bold text-zinc-900 mb-6">Room Rack</h2>
<div class="grid grid-cols-2 md:grid-cols-5 gap-4">
    <?php foreach($rooms as $r): ?>
        <?php
        if ($r['status'] == 'available') {
            $cls = "bg-white border-emerald-500 text-emerald-700"; $ico = "door-open";
            $link = "index.php?modul=Checkin&aksi=create&id_kamar=".$r['id_kamar'];
        } elseif ($r['status'] == 'occupied') {
            $cls = "bg-rose-50 border-rose-500 text-rose-700"; $ico = "user";
            $link = "index.php?modul=Checkout&aksi=payment&id=".$r['id_transaksi'];
        } else {
            $cls = "bg-amber-50 border-amber-500 text-amber-700"; $ico = "spray-can";
            $link = "index.php?modul=Dashboard&aksi=clean&id=".$r['id_kamar'];
        }
        ?>
        <a href="<?= $link ?>" class="p-4 rounded-xl border-l-4 shadow-sm hover:shadow-md transition-all <?= $cls ?>">
            <div class="flex justify-between items-start">
                <span class="text-2xl font-bold"><?= $r['nomor_kamar'] ?></span>
                <i data-lucide="<?= $ico ?>" class="w-5 h-5"></i>
            </div>
            <p class="text-xs uppercase mt-1"><?= $r['nama_tipe'] ?></p>
            <p class="text-xs font-bold mt-2 truncate"><?= $r['nama_tamu'] ?? 'KOSONG' ?></p>
        </a>
    <?php endforeach; ?>
</div>
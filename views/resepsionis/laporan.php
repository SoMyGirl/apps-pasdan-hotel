<?php
// --- 1. LOGIC AREA (Page Controller) ---
// Kita handle logika di atas HTML agar rapi
include_once 'controllers/C_Transaksi.php';
$ctrl = new C_Transaksi();

// Handle Aksi Check Out
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi']) && $_POST['aksi'] === 'checkout') {
    $id_trx = $_POST['id_transaksi'];
    
    if ($ctrl->checkout($id_trx)) {
        echo "<script>alert('Check Out Berhasil! Kamar update menjadi Dirty.'); window.location.href='?page=laporan';</script>";
    } else {
        echo "<script>alert('GAGAL: Tamu belum melunasi tagihan!'); window.location.href='?page=laporan';</script>";
    }
}

// Ambil Data Terbaru
// Note: Variable $data ini akan dipakai di foreach bawah
$data = $ctrl->getTamuAktif(); 
?>

<div class="space-y-6">
    
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Laporan Tamu In-House</h2>
            <p class="text-sm text-zinc-500">Monitoring status tamu dan hunian kamar.</p>
        </div>
        <a href="?page=checkin" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white transition-colors bg-zinc-900 rounded-md hover:bg-zinc-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-950">
            <i data-lucide="plus" class="w-4 h-4"></i> Check In Baru
        </a>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-zinc-100 bg-white">
            <h3 class="font-semibold text-zinc-900">Daftar Tamu Aktif</h3>
        </div>
        
        <div class="w-full overflow-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-zinc-500 uppercase bg-zinc-50 border-b border-zinc-100">
                    <tr>
                        <th class="px-6 py-3 font-medium w-[100px]">Kamar</th>
                        <th class="px-6 py-3 font-medium">Tamu</th>
                        <th class="px-6 py-3 font-medium">Check-in Info</th>
                        <th class="px-6 py-3 font-medium">Status Bayar</th>
                        <th class="px-6 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    <?php 
                    if (!empty($data)) :
                        foreach($data as $d): 
                    ?>
                    <tr class="group hover:bg-zinc-50/60 transition-colors">
                        <td class="px-6 py-4 align-top">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-bold bg-zinc-100 text-zinc-800 border border-zinc-200">
                                <?= $d['nomor_kamar'] ?>
                            </span>
                        </td>

                        <td class="px-6 py-4 align-top">
                            <div class="font-medium text-zinc-900"><?= htmlspecialchars($d['nama_tamu']) ?></div>
                            <div class="text-xs text-zinc-500 mt-1 flex items-center gap-1">
                                <i data-lucide="phone" class="w-3 h-3"></i> <?= htmlspecialchars($d['no_hp']) ?>
                            </div>
                        </td>

                        <td class="px-6 py-4 align-top">
                            <div class="flex items-center gap-2 text-zinc-700">
                                <i data-lucide="calendar" class="w-3.5 h-3.5 text-zinc-400"></i>
                                <span><?= date('d M Y', strtotime($d['tgl_checkin'])) ?></span>
                            </div>
                            <div class="text-xs text-zinc-400 mt-1 pl-5">
                                Durasi: <?= $d['durasi_malam'] ?> Malam
                            </div>
                        </td>

                        <td class="px-6 py-4 align-top">
                            <?php 
                                $statusClass = match($d['status_bayar']) {
                                    'lunas' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'dp' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    default => 'bg-rose-50 text-rose-700 border-rose-200'
                                };
                            ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border <?= $statusClass ?>">
                                <?= strtoupper($d['status_bayar']) ?>
                            </span>
                            <div class="text-xs text-zinc-500 mt-1">
                                Tagihan: Rp <?= number_format($d['total_tagihan'], 0, ',', '.') ?>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-right align-top">
                            <div class="flex items-center justify-end gap-2">
                                <a href="index.php?page=edit_transaksi&id=<?= $d['id_transaksi'] ?>" 
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-md text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100 border border-transparent hover:border-zinc-200 transition-all"
                                   title="Edit Data">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </a>

                                <form method="POST" action="" onsubmit="return confirm('Yakin ingin Check Out tamu <?= htmlspecialchars($d['nama_tamu']) ?>?');">
                                    <input type="hidden" name="aksi" value="checkout">
                                    <input type="hidden" name="id_transaksi" value="<?= $d['id_transaksi'] ?>">
                                    
                                    <button type="submit" 
                                            class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-red-600 bg-white border border-zinc-200 rounded-md hover:bg-red-50 hover:border-red-200 hover:text-red-700 transition-all shadow-sm">
                                        <i data-lucide="log-out" class="w-3.5 h-3.5"></i> Check Out
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-10 h-10 bg-zinc-100 rounded-full flex items-center justify-center mb-3">
                                    <i data-lucide="inbox" class="w-5 h-5 text-zinc-400"></i>
                                </div>
                                <p class="text-zinc-500">Belum ada tamu yang check-in.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons();</script>
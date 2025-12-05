<?php
include_once 'controllers/C_Transaksi.php';
$tr = new C_Transaksi();
$id = $_GET['id']; 

// --- LOGIC POST HANDLER ---

// 1. Tambah Layanan/Makanan
if (isset($_POST['tambah_layanan'])) {
    $tr->tambahLayanan($id, $_POST['id_layanan'], $_POST['jumlah']);
    echo "<meta http-equiv='refresh' content='0'>"; // Refresh agar data update
}

// 2. Proses Bayar (DP/Lunas)
if (isset($_POST['proses_bayar'])) {
    $tr->bayar($id, $_POST['uang'], $_POST['ket']);
    echo "<meta http-equiv='refresh' content='0'>";
}

// 3. Proses Checkout
if (isset($_GET['aksi']) && $_GET['aksi'] == 'checkout') {
    if($tr->checkout($id)) {
        echo "<script>alert('Checkout Berhasil! Status kamar kini Dirty.'); window.location='index.php?page=laporan';</script>";
    } else {
        echo "<script>alert('Gagal! Harap lunasi total tagihan terlebih dahulu.');</script>";
    }
}

// --- AMBIL DATA ---
$t = $tr->getDetailTransaksi($id);       // Info Transaksi Utama
$layanan_list = $tr->getListPesanan($id); // List Makanan yg dipesan
$bayar_list = $tr->getRiwayatBayar($id);  // List Uang masuk
$menu = $tr->getMenuLayanan();            // Dropdown Menu Makanan

// Hitung Keuangan
$sudah_bayar = 0;
foreach($bayar_list as $b) $sudah_bayar += $b['jumlah_bayar'];
$sisa = $t['total_tagihan'] - $sudah_bayar;
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <div class="lg:col-span-2 space-y-6 print-full-width">
        <div class="card rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
            
            <div class="p-6 border-b border-zinc-100 flex justify-between items-start bg-zinc-50/50">
                <div>
                    <h2 class="text-xl font-bold text-zinc-900 flex items-center gap-2">
                        <i data-lucide="building-2" class="w-6 h-6"></i> HOTEL SMK
                    </h2>
                    <p class="text-sm font-mono text-zinc-500 mt-1">INVOICE #<?= $t['no_invoice'] ?></p>
                </div>
                <div class="text-right">
                    <button onclick="window.print()" class="no-print mb-2 inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-zinc-300 rounded-md text-xs font-medium hover:bg-zinc-50">
                        <i data-lucide="printer" class="w-3 h-3"></i> Cetak
                    </button>
                    
                    <div class="block">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            <?= ($t['status_bayar']=='lunas')?'bg-green-100 text-green-800':'bg-yellow-100 text-yellow-800' ?>">
                            <?= strtoupper($t['status_bayar']) ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-6 grid grid-cols-2 gap-4 text-sm border-b border-zinc-100">
                <div>
                    <p class="text-zinc-500 text-xs uppercase tracking-wider">Tamu</p>
                    <p class="font-medium text-zinc-900 text-lg"><?= $t['nama_tamu'] ?></p>
                    <p class="text-zinc-500"><?= $t['no_hp'] ?></p>
                </div>
                <div class="text-right">
                    <p class="text-zinc-500 text-xs uppercase tracking-wider">Kamar & Durasi</p>
                    <p class="font-medium text-zinc-900 text-lg">Kamar <?= $t['nomor_kamar'] ?> <span class="text-sm font-normal text-zinc-500">(<?= $t['nama_tipe'] ?>)</span></p>
                    <p class="text-zinc-500">Check-in: <?= date('d M Y, H:i', strtotime($t['tgl_checkin'])) ?></p>
                </div>
            </div>

            <table class="w-full text-sm text-left">
                <thead class="text-xs text-zinc-500 uppercase bg-zinc-50 border-b border-zinc-100">
                    <tr>
                        <th class="px-6 py-3 font-medium">Deskripsi Item</th>
                        <th class="px-6 py-3 font-medium text-right">Harga Satuan</th>
                        <th class="px-6 py-3 font-medium text-center">Qty</th>
                        <th class="px-6 py-3 font-medium text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    <tr>
                        <td class="px-6 py-4 font-medium text-zinc-900">Sewa Kamar (<?= $t['durasi_malam'] ?> Malam)</td>
                        <td class="px-6 py-4 text-right text-zinc-500"><?= number_format($t['harga_kamar_per_malam']) ?></td>
                        <td class="px-6 py-4 text-center text-zinc-500"><?= $t['durasi_malam'] ?></td>
                        <td class="px-6 py-4 text-right font-medium"><?= number_format($t['total_biaya_kamar']) ?></td>
                    </tr>

                    <?php foreach($layanan_list as $l): ?>
                    <tr>
                        <td class="px-6 py-4 text-zinc-600"><?= $l['nama_layanan'] ?></td>
                        <td class="px-6 py-4 text-right text-zinc-500"><?= number_format($l['harga_saat_ini']) ?></td>
                        <td class="px-6 py-4 text-center text-zinc-500"><?= $l['jumlah'] ?></td>
                        <td class="px-6 py-4 text-right font-medium"><?= number_format($l['subtotal']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                
                <tfoot class="bg-zinc-50/50 border-t border-zinc-200">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right text-zinc-500 font-medium">Total Tagihan</td>
                        <td class="px-6 py-4 text-right font-bold text-lg text-zinc-900">Rp <?= number_format($t['total_tagihan']) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="card rounded-xl border border-zinc-200 bg-white shadow-sm p-6">
            <h3 class="font-bold text-sm text-zinc-900 mb-4 border-b border-zinc-100 pb-2">Riwayat Pembayaran</h3>
            <ul class="space-y-3 text-sm">
                <?php if(empty($bayar_list)): ?>
                    <li class="text-zinc-400 italic text-center py-2">Belum ada pembayaran masuk.</li>
                <?php endif; ?>

                <?php foreach($bayar_list as $rb): ?>
                    <li class="flex justify-between items-center pb-2 border-b border-zinc-100 last:border-0">
                        <span class="text-zinc-600">
                            <?= date('d/m/Y H:i', strtotime($rb['tgl_bayar'])) ?> 
                            <span class="text-xs bg-zinc-100 px-2 py-0.5 rounded ml-2 border border-zinc-200"><?= $rb['keterangan'] ?></span>
                        </span>
                        <span class="font-medium text-green-600">- Rp <?= number_format($rb['jumlah_bayar']) ?></span>
                    </li>
                <?php endforeach; ?>
                
                <li class="flex justify-between items-center pt-2 mt-2 font-bold text-zinc-900 text-base">
                    <span>Sisa Kekurangan</span>
                    <span class="<?= ($sisa > 0) ? 'text-red-600' : 'text-green-600' ?>">
                        Rp <?= number_format($sisa) ?>
                    </span>
                </li>
            </ul>
        </div>
    </div>

    <div class="space-y-6 no-print">
        
        <?php if($t['status_transaksi'] == 'finished'): ?>
            <div class="rounded-xl border border-zinc-200 bg-zinc-100 p-6 text-center">
                <i data-lucide="check-circle-2" class="w-10 h-10 text-zinc-400 mx-auto mb-2"></i>
                <h3 class="font-bold text-zinc-500">Transaksi Selesai</h3>
                <p class="text-xs text-zinc-400 mt-1">Tamu sudah checkout pada <?= date('d M Y', strtotime($t['tgl_checkout'])) ?></p>
            </div>
        <?php endif; ?>

        <?php if($sisa <= 0 && $t['status_transaksi'] == 'active'): ?>
            <div class="rounded-xl border border-green-200 bg-green-50 p-6 text-center shadow-sm">
                <i data-lucide="check-circle" class="w-10 h-10 text-green-600 mx-auto mb-2"></i>
                <h3 class="font-bold text-green-800">Tagihan Lunas</h3>
                <p class="text-sm text-green-600 mb-4">Tamu siap untuk checkout.</p>
                <a href="index.php?page=bayar&id=<?= $id ?>&aksi=checkout" 
                   onclick="return confirm('Apakah Anda yakin Checkout tamu ini? Status kamar akan berubah menjadi Dirty.')"
                   class="flex items-center justify-center w-full py-2.5 bg-green-600 text-white rounded-md font-medium hover:bg-green-700 shadow-sm transition-all">
                   <i data-lucide="log-out" class="w-4 h-4 mr-2"></i> Proses Checkout
                </a>
            </div>
        <?php endif; ?>

        <?php if($t['status_transaksi'] == 'active'): ?>
            
            <div class="rounded-xl border border-zinc-200 bg-white shadow-sm p-6">
                <h3 class="font-bold text-sm mb-4 flex items-center gap-2 text-zinc-900">
                    <i data-lucide="utensils" class="w-4 h-4 text-zinc-500"></i> Tambah Pesanan (POS)
                </h3>
                <form method="POST" class="space-y-3">
                    <div>
                        <select name="id_layanan" class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-900 outline-none">
                            <option value="">-- Pilih Menu --</option>
                            <?php foreach($menu as $m): ?>
                                <option value="<?= $m['id_layanan'] ?>"><?= $m['nama_layanan'] ?> - Rp <?= number_format($m['harga_satuan']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <input type="number" name="jumlah" value="1" min="1" class="w-20 rounded-md border border-zinc-300 px-3 py-2 text-sm text-center focus:ring-2 focus:ring-zinc-900 outline-none">
                        <button type="submit" name="tambah_layanan" class="flex-1 bg-white border border-zinc-300 hover:bg-zinc-50 text-zinc-900 rounded-md text-sm font-medium transition-colors">
                            + Tambah
                        </button>
                    </div>
                </form>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white shadow-sm p-6">
                <h3 class="font-bold text-sm mb-4 flex items-center gap-2 text-zinc-900">
                    <i data-lucide="wallet" class="w-4 h-4 text-zinc-500"></i> Input Pembayaran
                </h3>
                <form method="POST" class="space-y-3">
                    <div>
                        <label class="text-xs text-zinc-500 font-medium">Jumlah Uang (Rp)</label>
                        <input type="number" name="uang" required class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-900 outline-none mt-1">
                    </div>
                    <div>
                        <label class="text-xs text-zinc-500 font-medium">Tipe Pembayaran</label>
                        <select name="ket" class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-900 outline-none mt-1">
                            <option value="DP">DP (Uang Muka)</option>
                            <option value="Pelunasan">Pelunasan</option>
                            <option value="Tambahan">Biaya Tambahan</option>
                        </select>
                    </div>
                    <button type="submit" name="proses_bayar" class="w-full h-10 bg-zinc-900 text-white rounded-md text-sm font-medium hover:bg-zinc-800 transition-colors shadow-sm mt-2">
                        Submit Pembayaran
                    </button>
                </form>
            </div>
        <?php endif; ?>

    </div>
</div>
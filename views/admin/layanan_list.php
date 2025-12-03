<?php
include_once 'controllers/C_Layanan.php';
$ctrl = new C_Layanan();

if (isset($_POST['simpan'])) {
    $ctrl->tambah($_POST['nama'], $_POST['harga'], $_POST['satuan'], $_POST['kategori']);
    echo "<script>window.location='index.php?page=layanan';</script>";
}
if (isset($_GET['hapus'])) {
    $ctrl->hapus($_GET['hapus']);
    echo "<script>window.location='index.php?page=layanan';</script>";
}
$data = $ctrl->index();
?>

<div class="flex flex-col md:flex-row gap-6">
    <div class="w-full md:w-1/3">
        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm sticky top-4">
            <div class="p-6 border-b border-zinc-100">
                <h3 class="font-bold text-lg">Tambah Layanan</h3>
                <p class="text-sm text-zinc-500">Menu makanan atau jasa hotel.</p>
            </div>
            <form method="POST" class="p-6 space-y-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium">Nama Layanan</label>
                    <input type="text" name="nama" required placeholder="Cth: Nasi Goreng" class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-900">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Harga (Rp)</label>
                        <input type="number" name="harga" required class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-900">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Satuan</label>
                        <input type="text" name="satuan" required placeholder="porsi/pcs" class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-900">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium">Kategori</label>
                    <select name="kategori" class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
                        <option value="makanan">Makanan</option>
                        <option value="minuman">Minuman</option>
                        <option value="jasa">Jasa (Laundry/Bed)</option>
                    </select>
                </div>
                <button type="submit" name="simpan" class="w-full h-10 bg-zinc-900 text-white rounded-md text-sm font-medium hover:bg-zinc-800">Simpan</button>
            </form>
        </div>
    </div>

    <div class="w-full md:w-2/3">
        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-zinc-50 border-b border-zinc-100">
                    <tr>
                        <th class="px-6 py-3 font-medium">Nama Item</th>
                        <th class="px-6 py-3 font-medium">Kategori</th>
                        <th class="px-6 py-3 font-medium text-right">Harga</th>
                        <th class="px-6 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    <?php foreach($data as $d): ?>
                    <tr class="hover:bg-zinc-50/50">
                        <td class="px-6 py-4 font-medium"><?= $d['nama_layanan'] ?></td>
                        <td class="px-6 py-4"><span class="px-2 py-1 bg-zinc-100 rounded text-xs"><?= $d['kategori'] ?></span></td>
                        <td class="px-6 py-4 text-right">Rp <?= number_format($d['harga_satuan']) ?> / <?= $d['satuan'] ?></td>
                        <td class="px-6 py-4 text-right">
                            <a href="index.php?page=layanan&hapus=<?= $d['id_layanan'] ?>" onclick="return confirm('Hapus?')" class="text-red-600 hover:text-red-800 text-xs font-medium">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
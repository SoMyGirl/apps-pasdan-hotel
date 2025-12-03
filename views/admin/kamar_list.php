<?php
include_once 'controllers/C_Kamar.php';
$ctrl = new C_Kamar();

// Logic Hapus
if (isset($_GET['hapus'])) {
    $ctrl->hapus($_GET['hapus']);
    echo "<script>window.location='index.php?page=kamar';</script>";
}
// Logic Tambah
if (isset($_POST['simpan'])) {
    $ctrl->tambah($_POST['nomor'], $_POST['tipe']);
    echo "<script>window.location='index.php?page=kamar';</script>";
}

$data_kamar = $ctrl->index();
$data_tipe = $ctrl->getTipe();
?>

<div class="flex flex-col md:flex-row gap-6">
    <div class="w-full md:w-1/3">
        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm sticky top-4">
            <div class="p-6 border-b border-zinc-100">
                <h3 class="font-bold text-lg">Tambah Kamar</h3>
                <p class="text-sm text-zinc-500">Daftarkan kamar baru.</p>
            </div>
            <form method="POST" class="p-6 space-y-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium">Nomor Kamar</label>
                    <input type="text" name="nomor" required placeholder="Contoh: 101"
                        class="flex h-10 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-900">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium">Tipe Kamar</label>
                    <select name="tipe" class="flex h-10 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-900">
                        <?php foreach($data_tipe as $t): ?>
                            <option value="<?= $t['id_tipe'] ?>"><?= $t['nama_tipe'] ?> (Rp <?= number_format($t['harga_dasar']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" name="simpan" class="w-full h-10 bg-zinc-900 text-white rounded-md text-sm font-medium hover:bg-zinc-800">
                    Simpan Data
                </button>
            </form>
        </div>
    </div>

    <div class="w-full md:w-2/3">
        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-zinc-500 uppercase bg-zinc-50 border-b border-zinc-100">
                    <tr>
                        <th class="px-6 py-3 font-medium">No Kamar</th>
                        <th class="px-6 py-3 font-medium">Tipe</th>
                        <th class="px-6 py-3 font-medium">Harga Dasar</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    <?php foreach($data_kamar as $k): ?>
                    <tr class="hover:bg-zinc-50/50">
                        <td class="px-6 py-4 font-bold text-zinc-900"><?= $k['nomor_kamar'] ?></td>
                        <td class="px-6 py-4"><?= $k['nama_tipe'] ?></td>
                        <td class="px-6 py-4">Rp <?= number_format($k['harga_dasar']) ?></td>
                        <td class="px-6 py-4">
                            <?php 
                                $color = match($k['status']) {
                                    'available' => 'bg-emerald-100 text-emerald-700',
                                    'occupied' => 'bg-rose-100 text-rose-700',
                                    'dirty' => 'bg-amber-100 text-amber-700',
                                };
                            ?>
                            <span class="inline-flex px-2 py-1 text-xs rounded-full font-medium <?= $color ?>">
                                <?= ucfirst($k['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="index.php?page=kamar&hapus=<?= $k['id_kamar'] ?>" onclick="return confirm('Hapus kamar ini?')" 
                               class="text-red-600 hover:text-red-800 font-medium text-xs">
                               Hapus
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
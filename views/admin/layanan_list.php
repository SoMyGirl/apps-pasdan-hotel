<?php
include_once 'controllers/C_Layanan.php';
$ctrl = new C_Layanan();

// Logic Simpan Baru
if (isset($_POST['simpan'])) {
    $ctrl->tambah($_POST['nama'], $_POST['harga'], $_POST['satuan'], $_POST['kategori']);
    echo "<script>window.location='index.php?page=layanan';</script>";
}

// Logic Update (Edit)
if (isset($_POST['update'])) {
    $ctrl->update($_POST['id'], $_POST['nama'], $_POST['harga'], $_POST['satuan'], $_POST['kategori']);
    echo "<script>window.location='index.php?page=layanan';</script>";
}

// Logic Hapus
if (isset($_GET['hapus'])) {
    $ctrl->hapus($_GET['hapus']);
    echo "<script>window.location='index.php?page=layanan';</script>";
}

// Logic Ambil Data untuk Form Edit
$edit = null;
if (isset($_GET['edit'])) {
    $edit = $ctrl->satu_data($_GET['edit']);
}

$data = $ctrl->index();
?>

<div class="flex flex-col md:flex-row gap-6 relative">
    
    <div class="w-full md:w-1/3">
        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm sticky top-4">
            <div class="p-6 border-b border-zinc-100 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-lg"><?= isset($edit) ? 'Edit Layanan' : 'Tambah Layanan' ?></h3>
                    <p class="text-sm text-zinc-500">Menu makanan atau jasa hotel.</p>
                </div>
                <?php if(isset($edit)): ?>
                    <a href="index.php?page=layanan" class="text-xs bg-zinc-200 px-2 py-1 rounded hover:bg-zinc-300">Batal</a>
                <?php endif; ?>
            </div>
            
            <form method="POST" class="p-6 space-y-4">
                <input type="hidden" name="id" value="<?= isset($edit) ? $edit['id_layanan'] : '' ?>">

                <div class="space-y-2">
                    <label class="text-sm font-medium">Nama Layanan</label>
                    <input type="text" name="nama" required 
                           value="<?= isset($edit) ? $edit['nama_layanan'] : '' ?>"
                           placeholder="Cth: Nasi Goreng" 
                           class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-900">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Harga (Rp)</label>
                        <input type="number" name="harga" required 
                               value="<?= isset($edit) ? $edit['harga_satuan'] : '' ?>"
                               class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-900">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Satuan</label>
                        <input type="text" name="satuan" required 
                               value="<?= isset($edit) ? $edit['satuan'] : '' ?>"
                               placeholder="porsi/pcs" 
                               class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-900">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium">Kategori</label>
                    <select name="kategori" class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
                        <?php 
                        $kat = isset($edit) ? $edit['kategori'] : '';
                        ?>
                        <option value="makanan" <?= $kat == 'makanan' ? 'selected' : '' ?>>Makanan</option>
                        <option value="minuman" <?= $kat == 'minuman' ? 'selected' : '' ?>>Minuman</option>
                        <option value="jasa" <?= $kat == 'jasa' ? 'selected' : '' ?>>Jasa (Laundry/Bed)</option>
                    </select>
                </div>
                
                <button type="submit" name="<?= isset($edit) ? 'update' : 'simpan' ?>" 
                        class="w-full h-10 <?= isset($edit) ? 'bg-amber-500 hover:bg-amber-600' : 'bg-zinc-900 hover:bg-zinc-800' ?> text-white rounded-md text-sm font-medium transition-colors">
                    <?= isset($edit) ? 'Update Perubahan' : 'Simpan' ?>
                </button>
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
                        <th class="px-6 py-3 font-medium text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    <?php foreach($data as $d): ?>
                    <tr class="hover:bg-zinc-50/50">
                        <td class="px-6 py-4 font-medium"><?= $d['nama_layanan'] ?></td>
                        <td class="px-6 py-4"><span class="px-2 py-1 bg-zinc-100 rounded text-xs capitalize"><?= $d['kategori'] ?></span></td>
                        <td class="px-6 py-4 text-right">Rp <?= number_format($d['harga_satuan']) ?> / <?= $d['satuan'] ?></td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="index.php?page=layanan&edit=<?= $d['id_layanan'] ?>" 
                                   class="px-3 py-1.5 bg-amber-100 text-amber-700 rounded-md text-xs font-medium hover:bg-amber-200 transition">
                                   Edit
                                </a>
                                
                                <button onclick="bukaModal('<?= $d['id_layanan'] ?>', '<?= $d['nama_layanan'] ?>')" 
                                        class="px-3 py-1.5 bg-red-100 text-red-700 rounded-md text-xs font-medium hover:bg-red-200 transition">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalHapus" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-zinc-900/75 transition-opacity backdrop-blur-sm" onclick="tutupModal()"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-zinc-200">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-zinc-900" id="modal-title">Hapus Layanan</h3>
                            <div class="mt-2">
                                <p class="text-sm text-zinc-500">
                                    Apakah Anda yakin ingin menghapus layanan <span id="namaItemHapus" class="font-bold text-zinc-800"></span>? 
                                    Data yang dihapus tidak dapat dikembalikan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-zinc-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-zinc-100">
                    <a id="btnKonfirmasiHapus" href="#" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                        Ya, Hapus
                    </a>
                    <button type="button" onclick="tutupModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 sm:mt-0 sm:w-auto">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function bukaModal(id, nama) {
        // Set nama item di teks modal
        document.getElementById('namaItemHapus').textContent = nama;
        // Set link href tombol hapus
        document.getElementById('btnKonfirmasiHapus').href = 'index.php?page=layanan&hapus=' + id;
        // Tampilkan modal
        document.getElementById('modalHapus').classList.remove('hidden');
    }

    function tutupModal() {
        document.getElementById('modalHapus').classList.add('hidden');
    }
</script>
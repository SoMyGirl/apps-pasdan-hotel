<?php
include_once 'controllers/C_Kamar.php';
$ctrl = new C_Kamar();

// Logic Tambah
if (isset($_POST['simpan'])) {
    $ctrl->tambah($_POST['nomor'], $_POST['tipe']);
    // Redirect dengan pesan 'saved'
    echo "<script>window.location='index.php?page=kamar&msg=saved';</script>";
}

// Logic Update / Edit
if (isset($_POST['update'])) {
    $ctrl->update($_POST['id_kamar'], $_POST['nomor'], $_POST['tipe']);
    // Redirect dengan pesan 'updated'
    echo "<script>window.location='index.php?page=kamar&msg=updated';</script>";
}

$data_kamar = $ctrl->index();
$data_tipe = $ctrl->getTipe();
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="flex flex-col md:flex-row gap-6 relative font-sans text-zinc-900">
    
    <div class="w-full md:w-1/3">
        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm sticky top-4">
            <div class="p-6 border-b border-zinc-100">
                <h3 class="font-bold text-lg">Tambah Kamar</h3>
                <p class="text-sm text-zinc-500">Daftarkan kamar baru.</p>
            </div>
            <form method="POST" class="p-6 space-y-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium text-zinc-700">Nomor Kamar</label>
                    <input type="text" name="nomor" required placeholder="Contoh: 101"
                        class="flex h-10 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-900 focus:outline-none placeholder:text-zinc-400">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium text-zinc-700">Tipe Kamar</label>
                    <select name="tipe" class="flex h-10 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-900 focus:outline-none bg-white">
                        <?php foreach($data_tipe as $t): ?>
                            <option value="<?= $t['id_tipe'] ?>"><?= $t['nama_tipe'] ?> (Rp <?= number_format($t['harga_dasar']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" name="simpan" class="w-full h-10 bg-zinc-900 text-white rounded-md text-sm font-medium hover:bg-zinc-800 transition-colors flex items-center justify-center gap-2 shadow-sm">
                    <i data-lucide="plus" class="w-4 h-4"></i> Simpan Data
                </button>
            </form>
        </div>
    </div>

    <div class="w-full md:w-2/3">
        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-zinc-500 uppercase bg-zinc-50/50 border-b border-zinc-100">
                        <tr>
                            <th class="px-6 py-3 font-semibold">No Kamar</th>
                            <th class="px-6 py-3 font-semibold">Tipe</th>
                            <th class="px-6 py-3 font-semibold">Harga</th>
                            <th class="px-6 py-3 font-semibold">Status</th>
                            <th class="px-6 py-3 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        <?php if(empty($data_kamar)): ?>
                            <tr><td colspan="5" class="p-6 text-center text-zinc-500">Belum ada data kamar.</td></tr>
                        <?php else: ?>
                            <?php foreach($data_kamar as $k): ?>
                            <tr class="hover:bg-zinc-50/60 transition-colors group">
                                <td class="px-6 py-4 font-bold text-zinc-900"><?= $k['nomor_kamar'] ?></td>
                                <td class="px-6 py-4 text-zinc-600"><?= $k['nama_tipe'] ?></td>
                                <td class="px-6 py-4 text-zinc-600">Rp <?= number_format($k['harga_dasar']) ?></td>
                                <td class="px-6 py-4">
                                    <?php 
                                        $color = match($k['status']) {
                                            'available' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'occupied' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            'dirty
                                            ' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            default => 'bg-zinc-50 text-zinc-700 border-zinc-200'
                                        };
                                    ?>
                                    <span class="inline-flex px-2.5 py-1 text-xs rounded-full font-medium border <?= $color ?>">
                                        <?= ucfirst($k['status']) ?>
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end">
                                        <button type="button" 
                                            onclick="openEditModal('<?= $k['id_kamar'] ?>', '<?= $k['nomor_kamar'] ?>', '<?= $k['id_tipe'] ?>')"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-zinc-700 bg-white border border-zinc-200 rounded-md hover:bg-zinc-50 hover:text-zinc-900 hover:border-zinc-300 transition-all shadow-sm">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i> 
                                            Edit
                                        </button>
                                    </div>
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

<div id="modalEdit" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4 transition-opacity duration-300">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden transform scale-100 transition-all">
        <div class="p-6 border-b border-zinc-100 flex justify-between items-center bg-white">
            <h3 class="font-bold text-lg text-zinc-900">Edit Data Kamar</h3>
            <button onclick="closeEditModal()" class="text-zinc-400 hover:text-zinc-600 transition-colors p-1 rounded hover:bg-zinc-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <form method="POST" class="p-6 space-y-4">
            <input type="hidden" name="id_kamar" id="edit_id">
            <div class="space-y-2">
                <label class="text-sm font-medium text-zinc-700">Nomor Kamar</label>
                <input type="text" name="nomor" id="edit_nomor" required
                    class="flex h-10 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-900 focus:outline-none">
            </div>
            <div class="space-y-2">
                <label class="text-sm font-medium text-zinc-700">Tipe Kamar</label>
                <select name="tipe" id="edit_tipe" class="flex h-10 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-900 focus:outline-none bg-white">
                    <?php foreach($data_tipe as $t): ?>
                        <option value="<?= $t['id_tipe'] ?>"><?= $t['nama_tipe'] ?> (Rp <?= number_format($t['harga_dasar']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeEditModal()" class="flex-1 h-10 bg-zinc-100 text-zinc-700 rounded-md text-sm font-medium hover:bg-zinc-200 transition-colors">
                    Batal
                </button>
                
                <button type="submit" name="update" class="flex-1 h-10 bg-zinc-900 text-white rounded-md text-sm font-medium hover:bg-zinc-800 transition-colors shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();

    // --- LOGIC EDIT MODAL ---
    function openEditModal(id, nomor, tipe) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nomor').value = nomor;
        document.getElementById('edit_tipe').value = tipe;
        document.getElementById('modalEdit').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('modalEdit').classList.add('hidden');
    }

    // --- CLOSE ON CLICK OUTSIDE ---
    window.addEventListener('click', function(e) {
        const modalEdit = document.getElementById('modalEdit');
        if (e.target === modalEdit) closeEditModal();
    });
</script>

<?php if (isset($_GET['msg'])): ?>
<script>
    const msg = "<?= $_GET['msg'] ?>";
    
    if (msg === 'updated') {
        Swal.fire({
            title: 'Berhasil!',
            text: 'Data kamar berhasil diperbarui.',
            icon: 'success',
            confirmButtonText: 'Oke',
            confirmButtonColor: '#18181b', // Warna Zinc-900
            timer: 3000 // Otomatis tutup 3 detik
        }).then(() => {
            // Bersihkan URL agar tidak muncul lagi saat refresh
            window.history.replaceState(null, null, window.location.pathname + "?page=kamar");
        });
    } else if (msg === 'saved') {
        Swal.fire({
            title: 'Berhasil!',
            text: 'Data kamar baru telah ditambahkan.',
            icon: 'success',
            confirmButtonText: 'Oke',
            confirmButtonColor: '#18181b',
            timer: 3000
        }).then(() => {
            window.history.replaceState(null, null, window.location.pathname + "?page=kamar");
        });
    }
</script>
<?php endif; ?>
<?php
include_once 'controllers/C_User.php';
$ctrl = new C_User();

if (isset($_POST['simpan'])) {
    $ctrl->tambah($_POST['username'], $_POST['password'], $_POST['nama'], $_POST['role']);
    echo "<script>window.location='index.php?page=users';</script>";
}
if (isset($_GET['hapus'])) {
    $ctrl->hapus($_GET['hapus']);
    echo "<script>window.location='index.php?page=users';</script>";
}
$users = $ctrl->index();
?>

<div class="flex flex-col md:flex-row gap-6">
    <div class="w-full md:w-1/3">
        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm sticky top-4">
            <div class="p-6 border-b border-zinc-100">
                <h3 class="font-bold text-lg">Tambah User</h3>
                <p class="text-sm text-zinc-500">Buat akun staf baru.</p>
            </div>
            <form method="POST" class="p-6 space-y-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium">Nama Lengkap</label>
                    <input type="text" name="nama" required class="flex h-10 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-900">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium">Username</label>
                    <input type="text" name="username" required class="flex h-10 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-900">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium">Password</label>
                    <input type="password" name="password" required class="flex h-10 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-900">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium">Role</label>
                    <select name="role" class="flex h-10 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
                        <option value="resepsionis">Resepsionis</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" name="simpan" class="w-full h-10 bg-zinc-900 text-white rounded-md text-sm font-medium hover:bg-zinc-800">Tambah User</button>
            </form>
        </div>
    </div>

    <div class="w-full md:w-2/3">
        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-zinc-50 border-b border-zinc-100">
                    <tr>
                        <th class="px-6 py-3 font-medium text-zinc-500">Nama</th>
                        <th class="px-6 py-3 font-medium text-zinc-500">Role</th>
                        <th class="px-6 py-3 font-medium text-zinc-500 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    <?php foreach($users as $u): ?>
                    <tr>
                        <td class="px-6 py-4">
                            <p class="font-medium text-zinc-900"><?= $u['nama_lengkap'] ?></p>
                            <p class="text-xs text-zinc-500">@<?= $u['username'] ?></p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs rounded-full font-medium bg-zinc-100 text-zinc-800">
                                <?= ucfirst($u['role']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="index.php?page=users&hapus=<?= $u['id_user'] ?>" onclick="return confirm('Hapus user ini?')" class="text-red-600 hover:text-red-800 text-xs font-medium">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
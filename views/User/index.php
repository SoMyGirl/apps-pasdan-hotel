<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-zinc-900">Kelola User</h2>
        <p class="text-zinc-500 text-sm">Daftar akun pegawai yang memiliki akses sistem.</p>
    </div>
    <a href="index.php?modul=User&aksi=create" class="bg-zinc-900 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-zinc-800 flex items-center gap-2">
        <i data-lucide="plus" class="w-4 h-4"></i> Tambah User
    </a>
</div>

<div class="bg-white rounded-xl border border-zinc-200 overflow-hidden shadow-sm">
    <table class="w-full text-sm text-left">
        <thead class="bg-zinc-50 border-b border-zinc-100">
            <tr>
                <th class="px-6 py-3 font-medium text-zinc-500">Nama Lengkap</th>
                <th class="px-6 py-3 font-medium text-zinc-500">Username</th>
                <th class="px-6 py-3 font-medium text-zinc-500">Role</th>
                <th class="px-6 py-3 font-medium text-zinc-500 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-zinc-100">
            <?php foreach($users as $u): ?>
            <tr class="hover:bg-zinc-50 transition-colors">
                <td class="px-6 py-4 font-medium text-zinc-900">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-zinc-100 flex items-center justify-center text-xs font-bold text-zinc-500">
                            <?= substr($u['nama_lengkap'], 0, 1) ?>
                        </div>
                        <?= $u['nama_lengkap'] ?>
                    </div>
                </td>
                <td class="px-6 py-4 text-zinc-600">@<?= $u['username'] ?></td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $u['role']=='admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' ?>">
                        <?= ucfirst($u['role']) ?>
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <?php if($_SESSION['user_id'] != $u['id_user']): ?>
                        <a href="index.php?modul=User&aksi=index&hapus=<?= $u['id_user'] ?>" class="btn-confirm text-red-600 hover:text-red-800 text-xs font-medium border border-red-200 px-3 py-1 rounded-md hover:bg-red-50" data-pesan="Hapus user ini?">Hapus</a>
                    <?php else: ?>
                        <span class="text-xs text-zinc-400 italic">Akun Sendiri</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
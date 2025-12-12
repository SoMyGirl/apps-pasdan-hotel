<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-zinc-900">Team Members</h1>
        <p class="text-zinc-500 mt-1 text-sm">Kelola akses staff.</p>
    </div>
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto">
        <div class="relative w-full sm:w-auto">
            <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-zinc-400"></i>
            <input type="text" id="searchUser" placeholder="Cari staff..." class="pl-9 pr-4 py-2 w-full sm:w-64 rounded-lg border border-zinc-200 text-sm focus:border-zinc-900 shadow-sm">
        </div>
        <a href="index.php?modul=User&aksi=create" class="bg-zinc-900 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-zinc-800 shadow-sm flex items-center justify-center gap-2">
            <i data-lucide="user-plus" class="w-4 h-4"></i> Tambah
        </a>
    </div>
</div>

<div class="bg-white rounded-xl border border-zinc-200 overflow-hidden shadow-sm">
    <div class="overflow-x-auto w-full">
        <table class="w-full text-sm text-left whitespace-nowrap">
            <thead class="bg-zinc-50 border-b border-zinc-100">
                <tr>
                    <th class="px-6 py-4 font-semibold text-zinc-500">Profil</th>
                    <th class="px-6 py-4 font-semibold text-zinc-500">Username</th>
                    <th class="px-6 py-4 font-semibold text-zinc-500">Role</th>
                    <th class="px-6 py-4 font-semibold text-zinc-500 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100" id="userTable">
                <?php foreach($users as $u): ?>
                    <?php 
                        $badge = $u['role']=='admin' ? 'bg-purple-50 text-purple-700' : ($u['role']=='housekeeping' ? 'bg-amber-50 text-amber-700' : 'bg-blue-50 text-blue-700');
                    ?>
                    <tr class="hover:bg-zinc-50 group">
                        <td class="px-6 py-4 font-bold text-zinc-900 search-name"><?= $u['nama_lengkap'] ?></td>
                        <td class="px-6 py-4 font-mono text-zinc-600 search-user">@<?= $u['username'] ?></td>
                        <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs font-bold uppercase <?= $badge ?>"><?= $u['role'] ?></span></td>
                        <td class="px-6 py-4 text-right">
                            <?php if($_SESSION['user_id'] != $u['id_user']): ?>
                                <button onclick="confirmDelete(<?= $u['id_user'] ?>, '<?= $u['nama_lengkap'] ?>')" class="p-2 text-zinc-400 hover:text-rose-600 rounded-lg"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                            <?php else: ?>
                                <span class="text-xs text-emerald-600 font-bold bg-emerald-50 px-2 py-1 rounded">YOU</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    document.getElementById('searchUser').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        document.querySelectorAll("#userTable tr").forEach(row => {
            row.style.display = row.innerText.toUpperCase().indexOf(filter) > -1 ? "" : "none";
        });
    });
    function confirmDelete(id, nama) {
        Swal.fire({ title: 'Hapus Staff?', text: nama, icon: 'warning', showCancelButton: true, confirmButtonColor: '#e11d48' })
        .then((r) => { if(r.isConfirmed) window.location.href="index.php?modul=User&aksi=index&hapus="+id; });
    }
    lucide.createIcons();
</script>
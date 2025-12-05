<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900">Team Members</h1>
        <p class="text-zinc-500 mt-1 text-sm">Kelola akses staff admin dan resepsionis.</p>
    </div>
    <div class="flex items-center gap-3">
        <div class="relative">
            <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-zinc-400"></i>
            <input type="text" id="searchUser" placeholder="Cari staff..." 
                   class="pl-9 pr-4 py-2 w-64 rounded-lg border border-zinc-200 text-sm focus:border-zinc-900 focus:ring-zinc-900 transition-all shadow-sm">
        </div>
        <a href="index.php?modul=User&aksi=create" class="bg-zinc-900 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-zinc-800 transition-all shadow-sm flex items-center gap-2">
            <i data-lucide="user-plus" class="w-4 h-4"></i> Tambah Staff
        </a>
    </div>
</div>

<div class="bg-white rounded-xl border border-zinc-200 overflow-hidden shadow-sm">
    <table class="w-full text-sm text-left">
        <thead class="bg-zinc-50 border-b border-zinc-100">
            <tr>
                <th class="px-6 py-4 font-semibold text-zinc-500">Profil Staff</th>
                <th class="px-6 py-4 font-semibold text-zinc-500">Username Login</th>
                <th class="px-6 py-4 font-semibold text-zinc-500">Role / Jabatan</th>
                <th class="px-6 py-4 font-semibold text-zinc-500 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-zinc-100" id="userTable">
            <?php foreach($users as $u): ?>
                <?php 
                    // Logic Avatar Inisial (Misal: "Siti Aminah" -> "SA")
                    $words = explode(" ", $u['nama_lengkap']);
                    $acronym = "";
                    foreach ($words as $w) {
                        $acronym .= strtoupper($w[0]);
                    }
                    $initials = substr($acronym, 0, 2); // Ambil maks 2 huruf

                    // Logic Warna Role
                    $roleBadge = $u['role'] == 'admin' 
                        ? 'bg-purple-50 text-purple-700 ring-purple-600/20' 
                        : 'bg-blue-50 text-blue-700 ring-blue-600/20';
                ?>
                <tr class="hover:bg-zinc-50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-zinc-100 flex items-center justify-center text-xs font-bold text-zinc-600 border border-zinc-200 tracking-widest group-hover:bg-zinc-900 group-hover:text-white transition-colors">
                                <?= $initials ?>
                            </div>
                            <div>
                                <p class="font-bold text-zinc-900 search-name"><?= $u['nama_lengkap'] ?></p>
                                <p class="text-[10px] text-zinc-400">ID: #<?= str_pad($u['id_user'], 4, '0', STR_PAD_LEFT) ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2 font-mono text-zinc-600 bg-zinc-50 w-fit px-2 py-1 rounded border border-zinc-100">
                            <i data-lucide="at-sign" class="w-3 h-3 text-zinc-400"></i>
                            <span class="search-user"><?= $u['username'] ?></span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase ring-1 ring-inset <?= $roleBadge ?>">
                            <?= $u['role'] ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <?php if($_SESSION['user_id'] != $u['id_user']): ?>
                            <button onclick="confirmDelete(<?= $u['id_user'] ?>, '<?= $u['nama_lengkap'] ?>')" 
                                    class="text-zinc-400 hover:text-rose-600 transition-colors p-2 hover:bg-rose-50 rounded-lg"
                                    title="Hapus Staff">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        <?php else: ?>
                            <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded">
                                You
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    // 1. Search Logic
    document.getElementById('searchUser').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelectorAll("#userTable tr");
        rows.forEach(row => {
            let name = row.querySelector(".search-name").innerText.toUpperCase();
            let user = row.querySelector(".search-user").innerText.toUpperCase();
            if (name.includes(filter) || user.includes(filter)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });

    // 2. SweetAlert Delete
    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'Hapus Akun?',
            text: "Staff " + nama + " tidak akan bisa login lagi.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#e4e4e7',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: '<span class="text-zinc-600">Batal</span>',
            customClass: { popup: 'rounded-xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "index.php?modul=User&aksi=index&hapus=" + id;
            }
        })
    }
</script>
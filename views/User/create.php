<div class="max-w-md mx-auto">
    <div class="mb-6">
        <a href="index.php?modul=User&aksi=index" class="text-sm text-zinc-500 hover:text-zinc-900 flex items-center gap-1 mb-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
        </a>
        <h2 class="text-2xl font-bold text-zinc-900">Tambah User Baru</h2>
    </div>

    <div class="bg-white p-6 rounded-xl border border-zinc-200 shadow-sm">
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">Nama Lengkap</label>
                <input type="text" name="nama" required class="w-full rounded-md border-zinc-300 focus:ring-2 focus:ring-zinc-900 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">Username</label>
                <input type="text" name="username" required class="w-full rounded-md border-zinc-300 focus:ring-2 focus:ring-zinc-900 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">Password</label>
                <input type="password" name="password" required class="w-full rounded-md border-zinc-300 focus:ring-2 focus:ring-zinc-900 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">Role / Hak Akses</label>
                <select name="role" class="w-full rounded-md border-zinc-300 focus:ring-2 focus:ring-zinc-900 focus:border-transparent">
                    <option value="resepsionis">Resepsionis (Kasir & Checkin)</option>
                    <option value="admin">Admin (Full Akses)</option>
                </select>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-zinc-900 text-white py-2 rounded-md font-medium hover:bg-zinc-800 transition-colors">
                    Simpan User
                </button>
            </div>
        </form>
    </div>
</div>
<div class="max-w-md mx-auto mt-10">
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-zinc-900 tracking-tight">Tambah Staff Baru</h2>
        <p class="text-sm text-zinc-500 mt-2">Buat akun untuk Admin, Resepsionis, atau Housekeeping.</p>
    </div>

    <div class="bg-white rounded-xl border border-zinc-200 shadow-xl shadow-zinc-200/50 overflow-hidden">
        <div class="p-8">
            <form method="POST" class="space-y-6">
                
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-500 mb-2">Nama Lengkap</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="user" class="w-4 h-4 text-zinc-400 group-focus-within:text-zinc-900 transition-colors"></i>
                        </div>
                        <input type="text" name="nama" required placeholder="Contoh: Siti Aminah" autofocus
                               class="block w-full h-11 pl-10 pr-3 rounded-lg border-zinc-200 text-sm shadow-sm focus:border-zinc-900 focus:ring-zinc-900 placeholder:text-zinc-300 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-500 mb-2">Jenis Kelamin</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="gender" value="L" class="peer sr-only" checked>
                            <div class="rounded-lg border border-zinc-200 p-3 hover:bg-zinc-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition-all text-center flex items-center justify-center gap-2">
                                <i data-lucide="user" class="w-4 h-4"></i>
                                <span class="text-sm font-bold">Laki-laki</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="gender" value="P" class="peer sr-only">
                            <div class="rounded-lg border border-zinc-200 p-3 hover:bg-zinc-50 peer-checked:border-pink-600 peer-checked:bg-pink-50 peer-checked:text-pink-700 transition-all text-center flex items-center justify-center gap-2">
                                <i data-lucide="user-check" class="w-4 h-4"></i>
                                <span class="text-sm font-bold">Perempuan</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-500 mb-2">Username Login</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="at-sign" class="w-4 h-4 text-zinc-400 group-focus-within:text-zinc-900 transition-colors"></i>
                        </div>
                        <input type="text" name="username" required placeholder="tanpa spasi" 
                               class="block w-full h-11 pl-10 pr-3 rounded-lg border-zinc-200 text-sm shadow-sm focus:border-zinc-900 focus:ring-zinc-900 placeholder:text-zinc-300 transition-all lowercase">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-500 mb-2">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="w-4 h-4 text-zinc-400 group-focus-within:text-zinc-900 transition-colors"></i>
                        </div>
                        <input type="password" name="password" required placeholder="••••••••" 
                               class="block w-full h-11 pl-10 pr-3 rounded-lg border-zinc-200 text-sm shadow-sm focus:border-zinc-900 focus:ring-zinc-900 placeholder:text-zinc-300 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-500 mb-2">Akses Role</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="resepsionis" class="peer sr-only" checked>
                            <div class="h-full rounded-lg border border-zinc-200 p-2 hover:bg-zinc-50 peer-checked:border-zinc-900 peer-checked:bg-zinc-900 peer-checked:text-white transition-all text-center flex flex-col justify-center">
                                <span class="text-xs font-bold block">Resepsionis</span>
                                <span class="text-[9px] opacity-70">Front Office</span>
                            </div>
                        </label>
                        
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="housekeeping" class="peer sr-only">
                            <div class="h-full rounded-lg border border-zinc-200 p-2 hover:bg-zinc-50 peer-checked:border-zinc-900 peer-checked:bg-zinc-900 peer-checked:text-white transition-all text-center flex flex-col justify-center">
                                <span class="text-xs font-bold block">Housekeeping</span>
                                <span class="text-[9px] opacity-70">Room Service</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="admin" class="peer sr-only">
                            <div class="h-full rounded-lg border border-zinc-200 p-2 hover:bg-zinc-50 peer-checked:border-zinc-900 peer-checked:bg-zinc-900 peer-checked:text-white transition-all text-center flex flex-col justify-center">
                                <span class="text-xs font-bold block">Admin</span>
                                <span class="text-[9px] opacity-70">Full Access</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="pt-4 border-t border-zinc-100 flex gap-3">
                    <a href="index.php?modul=User&aksi=index" class="w-1/3 py-3 rounded-lg border border-zinc-200 text-zinc-600 font-bold text-sm text-center hover:bg-zinc-50">
                        Batal
                    </a>
                    <button type="submit" class="w-2/3 bg-zinc-900 text-white py-3 rounded-lg font-bold hover:bg-zinc-800 transition-all shadow-lg flex items-center justify-center gap-2 group">
                        <i data-lucide="save" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                        Simpan Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
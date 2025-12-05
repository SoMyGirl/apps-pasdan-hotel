<div class="max-w-xl mx-auto mt-10">
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-zinc-900 tracking-tight">Tambah Kamar Baru</h2>
        <p class="text-sm text-zinc-500 mt-2">Masukkan detail kamar. Status awal akan diset sebagai <span class="font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">Available</span></p>
    </div>

    <div class="bg-white rounded-xl border border-zinc-200 shadow-xl shadow-zinc-200/50 overflow-hidden">
        
        <div class="h-1 w-full bg-zinc-100">
            <div class="h-full w-1/3 bg-zinc-900 rounded-r-full"></div>
        </div>

        <div class="p-8">
            <form method="POST" class="space-y-6">
                
                <div>
                    <label class="block text-sm font-bold text-zinc-700 mb-2">Nomor Kamar</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-zinc-400 font-bold group-focus-within:text-zinc-900 transition-colors">#</span>
                        </div>
                        <input type="number" name="nomor" placeholder="Contoh: 101, 205..." required autofocus
                               class="w-full pl-8 pr-4 py-3 rounded-lg border border-zinc-200 focus:border-zinc-900 focus:ring-zinc-900 transition-all font-mono font-bold text-lg text-zinc-900 placeholder:font-normal placeholder:text-zinc-400">
                    </div>
                    <p class="text-xs text-zinc-400 mt-1">Pastikan nomor kamar unik dan belum terdaftar.</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-zinc-700 mb-2">Tipe & Harga</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="bed-double" class="h-5 w-5 text-zinc-400 group-focus-within:text-zinc-900 transition-colors"></i>
                        </div>
                        <select name="tipe" required 
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-zinc-200 focus:border-zinc-900 focus:ring-zinc-900 transition-all text-sm bg-white appearance-none cursor-pointer">
                            <option value="">-- Pilih Tipe Kamar --</option>
                            <?php foreach($tipe as $t): ?>
                                <option value="<?= $t['id_tipe'] ?>">
                                    <?= $t['nama_tipe'] ?> — Rp <?= number_format($t['harga_dasar']) ?> / malam
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i data-lucide="chevron-down" class="h-4 w-4 text-zinc-400"></i>
                        </div>
                    </div>
                </div>

                <div class="border-t border-zinc-100 pt-4">
                    <div class="flex gap-4">
                        <a href="index.php?modul=Room&aksi=index" 
                           class="w-1/3 py-3 rounded-lg border border-zinc-200 text-zinc-600 font-bold text-sm hover:bg-zinc-50 hover:text-zinc-900 transition-all text-center">
                            Batal
                        </a>
                        <button type="submit" name="simpan" 
                                class="w-2/3 py-3 rounded-lg bg-zinc-900 text-white font-bold text-sm hover:bg-zinc-800 hover:shadow-lg hover:shadow-zinc-200 transition-all flex justify-center items-center gap-2 group">
                            <i data-lucide="save" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                            Simpan Data
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
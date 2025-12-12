<div class="max-w-2xl mx-auto">
    
    <div class="mb-6">
        <a href="index.php?modul=Housekeeping&aksi=index" class="inline-flex items-center gap-2 text-zinc-500 hover:text-zinc-900 font-bold transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-zinc-200 shadow-xl overflow-hidden flex flex-col">
        
        <div class="bg-zinc-900 p-6 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i data-lucide="bed-double" class="w-32 h-32"></i>
            </div>
            <div class="relative z-10 flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-zinc-400 mb-1">Room Service</p>
                    <h2 class="text-5xl font-black tracking-tighter"><?= $room['nomor_kamar'] ?></h2>
                    <span class="inline-block mt-2 px-3 py-1 bg-white/10 rounded-full text-xs font-medium text-white backdrop-blur-sm">
                        <?= $room['nama_tipe'] ?>
                    </span>
                </div>
                <div class="bg-white/10 p-2 rounded-lg backdrop-blur-md">
                    <i data-lucide="spray-can" class="w-6 h-6 text-white"></i>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-8">
            
            <div>
                <h3 class="text-sm font-bold text-zinc-900 mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-md bg-emerald-100 flex items-center justify-center text-emerald-600">
                        <i data-lucide="check-square" class="w-3.5 h-3.5"></i>
                    </span>
                    Checklist Kebersihan
                </h3>
                <div class="space-y-3" id="checklistContainer">
                    <?php 
                    $todos = ['Ganti Sprei & Bed Cover', 'Bersihkan Kamar Mandi', 'Vacuum Lantai', 'Cek Amenities', 'Buang Sampah', 'Semprot Disinfektan'];
                    foreach($todos as $index => $task): 
                    ?>
                    <label class="flex items-start gap-3 p-3 rounded-xl border border-zinc-100 bg-zinc-50 hover:bg-white hover:border-zinc-300 cursor-pointer transition-all group select-none">
                        <input type="checkbox" class="task-checkbox mt-0.5 w-4 h-4 rounded border-zinc-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer transition-all">
                        <span class="text-sm text-zinc-600 group-hover:text-zinc-900 font-medium leading-tight"><?= $task ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div x-data="{ openLF: false }" class="border-t border-zinc-100 pt-6">
                <button @click="openLF = !openLF" 
                        class="w-full py-3 px-4 flex items-center justify-between text-xs font-bold text-amber-700 bg-amber-50 rounded-xl hover:bg-amber-100 transition-colors">
                    <span class="flex items-center gap-2">
                        <i data-lucide="search" class="w-4 h-4"></i> Lapor Barang Tertinggal
                    </span>
                    <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-300" :class="openLF ? 'rotate-180' : ''"></i>
                </button>
                
                <div x-show="openLF" x-collapse class="mt-3">
                    <form action="index.php?modul=Housekeeping&aksi=reportLostItem" method="POST" enctype="multipart/form-data" class="space-y-4 p-4 bg-white border border-amber-100 rounded-xl shadow-sm">
                        <input type="hidden" name="id_kamar" value="<?= $room['id_kamar'] ?>">
                        
                        <div>
                            <label class="text-[10px] font-bold text-zinc-500 uppercase mb-1 block">Nama Barang</label>
                            <input type="text" name="nama_barang" required class="w-full text-sm px-3 py-2 rounded-lg border border-zinc-200 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
                        </div>
                        
                        <div>
                            <label class="text-[10px] font-bold text-zinc-500 uppercase mb-1 block">Foto Barang</label>
                            <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-zinc-200 border-dashed rounded-lg cursor-pointer bg-zinc-50 hover:bg-white transition-colors group">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i data-lucide="camera" class="w-6 h-6 text-zinc-400 group-hover:text-amber-500 transition-colors mb-1"></i>
                                    <p class="text-[10px] text-zinc-400">Klik untuk upload foto</p>
                                </div>
                                <input type="file" name="foto" accept="image/*" class="hidden" />
                            </label>
                        </div>
                        
                        <div>
                            <label class="text-[10px] font-bold text-zinc-500 uppercase mb-1 block">Deskripsi</label>
                            <textarea name="keterangan" rows="2" class="w-full text-sm px-3 py-2 rounded-lg border border-zinc-200 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all"></textarea>
                        </div>
                        
                        <button type="submit" class="w-full py-2.5 bg-amber-600 text-white text-xs font-bold rounded-lg hover:bg-amber-700 shadow-md shadow-amber-200 transition-all">
                            Simpan Laporan
                        </button>
                    </form>
                </div>
            </div>

        </div>

        <div class="p-6 border-t border-zinc-100 bg-zinc-50/50 sticky bottom-0 z-10 backdrop-blur-sm">
            <button onclick="confirmFinish(<?= $room['id_kamar'] ?>)"
               class="w-full py-4 bg-zinc-900 text-white font-bold rounded-xl hover:bg-zinc-800 shadow-xl shadow-zinc-200 flex items-center justify-center gap-3 group transition-all active:scale-[0.98]">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-400 group-hover:scale-110 transition-transform"></i>
                Selesai Dibersihkan
            </button>
        </div>

    </div>
</div>

<script>
    lucide.createIcons();

    // Fungsi Validasi & Konfirmasi
    function confirmFinish(id) {
        // 1. Validasi Checklist
        const checkboxes = document.querySelectorAll('.task-checkbox');
        let allChecked = true;
        
        checkboxes.forEach(cb => {
            if (!cb.checked) allChecked = false;
        });

        if (!allChecked) {
            Swal.fire({
                icon: 'error',
                title: '<span class="text-zinc-900 font-bold">Tugas Belum Selesai</span>',
                html: '<p class="text-zinc-500 text-sm">Harap centang semua checklist kebersihan sebelum menyelesaikan status kamar.</p>',
                confirmButtonColor: '#18181b', // Zinc-900
                confirmButtonText: 'Oke, Saya Cek Lagi',
                customClass: {
                    popup: 'rounded-2xl p-6',
                    confirmButton: 'rounded-lg px-6 py-2.5 font-bold'
                }
            });
            return;
        }

        // 2. Modal Konfirmasi (Jika lolos validasi)
        Swal.fire({
            title: '<span class="text-xl font-bold text-zinc-900">Selesaikan Tugas?</span>',
            html: '<p class="text-zinc-500 text-sm">Status kamar akan diubah menjadi <strong class="text-emerald-600">Available</strong> dan siap digunakan kembali.</p>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981', // Emerald-500
            cancelButtonColor: '#f4f4f5',  // Zinc-100
            confirmButtonText: 'Ya, Selesai',
            cancelButtonText: '<span class="text-zinc-600 font-bold">Batal</span>',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl p-6',
                confirmButton: 'rounded-lg px-6 py-2.5 font-bold shadow-lg shadow-emerald-100',
                cancelButton: 'rounded-lg px-6 py-2.5 hover:bg-zinc-200'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "index.php?modul=Housekeeping&aksi=clean&id=" + id;
            }
        });
    }
</script>
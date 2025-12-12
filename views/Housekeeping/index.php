<div class="flex flex-col md:flex-row justify-between items-end mb-6 gap-4">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900">Housekeeping</h1>
        <p class="text-zinc-500 mt-1 text-sm">Daftar kamar yang memerlukan perhatian.</p>
    </div>
    
    <div class="flex bg-white p-1 rounded-xl border border-zinc-200 shadow-sm w-full md:w-auto" x-data="{ tab: 'dirty' }">
        <button @click="tab = 'dirty'; document.getElementById('sec-dirty').classList.remove('hidden'); document.getElementById('sec-checkout').classList.add('hidden');" 
                :class="tab === 'dirty' ? 'bg-zinc-900 text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-900'"
                class="flex-1 md:flex-none px-4 py-2 text-sm font-bold rounded-lg transition-all">
            Dirty (<?= count($dirtyRooms) ?>)
        </button>
        <button @click="tab = 'checkout'; document.getElementById('sec-dirty').classList.add('hidden'); document.getElementById('sec-checkout').classList.remove('hidden');"
                :class="tab === 'checkout' ? 'bg-zinc-900 text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-900'"
                class="flex-1 md:flex-none px-4 py-2 text-sm font-bold rounded-lg transition-all">
            Checkout (<?= count($checkoutToday) ?>)
        </button>
    </div>
</div>

<div class="space-y-6">
    
    <div id="sec-dirty" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        <?php if(empty($dirtyRooms)): ?>
            <div class="col-span-full py-12 text-center text-zinc-400 bg-white rounded-xl border border-dashed border-zinc-200">
                <i data-lucide="check-circle" class="w-12 h-12 mx-auto mb-3 opacity-50 text-emerald-500"></i>
                <p>Semua kamar bersih!</p>
            </div>
        <?php else: ?>
            <?php foreach($dirtyRooms as $d): ?>
            <a href="index.php?modul=Housekeeping&aksi=detail&id=<?= $d['id_kamar'] ?>" 
               class="bg-white p-5 rounded-2xl border border-zinc-200 shadow-sm hover:ring-2 hover:ring-zinc-900 transition-all group relative overflow-hidden block">
                
                <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i data-lucide="spray-can" class="w-20 h-20 rotate-12"></i>
                </div>
                
                <div class="flex justify-between items-start mb-3">
                    <span class="text-3xl font-black text-zinc-900"><?= $d['nomor_kamar'] ?></span>
                    <span class="bg-amber-100 text-amber-700 text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-wide">Dirty</span>
                </div>
                
                <p class="text-xs text-zinc-500 font-bold uppercase tracking-wider mb-4"><?= $d['nama_tipe'] ?></p>
                
                <div class="flex items-center gap-2 text-xs text-rose-500 font-bold bg-rose-50 w-fit px-3 py-1.5 rounded-lg">
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i> Mulai Bersihkan
                </div>
            </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div id="sec-checkout" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        <?php if(empty($checkoutToday)): ?>
            <div class="col-span-full py-12 text-center text-zinc-400 bg-white rounded-xl border border-dashed border-zinc-200">
                <i data-lucide="calendar-check" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
                <p>Tidak ada checkout hari ini.</p>
            </div>
        <?php else: ?>
            <?php foreach($checkoutToday as $c): ?>
            <div class="bg-white p-5 rounded-2xl border border-zinc-200 shadow-sm opacity-75">
                <div class="flex justify-between items-start mb-3">
                    <span class="text-2xl font-bold text-zinc-700"><?= $c['list_kamar'] ?></span>
                    <span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2 py-1 rounded-full uppercase">Occupied</span>
                </div>
                <p class="text-sm text-zinc-900 font-bold mb-1"><?= $c['nama_tamu'] ?></p>
                <p class="text-xs text-zinc-500">Checkout Hari Ini</p>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<script>lucide.createIcons();</script>
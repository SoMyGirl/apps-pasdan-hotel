<div class="max-w-2xl mx-auto">
    <div class="rounded-xl border border-zinc-200 bg-white shadow-sm">
        <div class="p-6 border-b border-zinc-100">
            <h3 class="font-bold text-lg">Check-in Baru</h3>
            <p class="text-sm text-zinc-500">Isi data tamu untuk memulai transaksi.</p>
        </div>
        
        <form method="POST" class="p-6 space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Nama Tamu</label>
                    <input type="text" name="nama" class="flex h-10 w-full rounded-md border border-zinc-300 bg-transparent px-3 py-2 text-sm placeholder:text-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" required placeholder="Sesuai KTP">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium leading-none">No HP</label>
                    <input type="text" name="hp" class="flex h-10 w-full rounded-md border border-zinc-300 bg-transparent px-3 py-2 text-sm placeholder:text-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:ring-offset-2" required>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" name="btn_checkin" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-zinc-900 text-zinc-50 hover:bg-zinc-900/90 h-11 px-8 w-full">
                    Proses Check-in
                </button>
            </div>
        </form>
    </div>
</div>
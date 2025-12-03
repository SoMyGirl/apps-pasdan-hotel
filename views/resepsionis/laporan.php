<div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
    <div class="p-6 border-b border-zinc-100">
        <h3 class="font-bold text-lg">Tamu In-House</h3>
        <p class="text-sm text-zinc-500">Daftar tamu yang sedang menginap saat ini.</p>
    </div>
    
    <div class="w-full overflow-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-zinc-500 uppercase bg-zinc-50/50 border-b border-zinc-100">
                <tr>
                    <th class="px-6 py-3 font-medium">Kamar</th>
                    <th class="px-6 py-3 font-medium">Nama Tamu</th>
                    <th class="px-6 py-3 font-medium">Check-in</th>
                    <th class="px-6 py-3 font-medium">Status Bayar</th>
                    <th class="px-6 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100">
                <?php foreach($data as $d): ?>
                <tr class="hover:bg-zinc-50/50 transition-colors">
                    <td class="px-6 py-4 font-medium text-zinc-900"><?= $d['nomor_kamar'] ?></td>
                    <td class="px-6 py-4 text-zinc-600"><?= $d['nama_tamu'] ?></td>
                    <td class="px-6 py-4 text-zinc-600"><?= date('d M, H:i', strtotime($d['tgl_checkin'])) ?></td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            <?= ($d['status_bayar']=='lunas') ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                            <?= strtoupper($d['status_bayar']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="index.php?page=bayar&id=<?= $d['id_transaksi'] ?>" 
                           class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-zinc-200 bg-white hover:bg-zinc-100 h-9 px-4 py-2">
                           Detail
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
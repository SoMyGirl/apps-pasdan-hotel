<div class="grid grid-cols-3 gap-6">
    <div class="col-span-2 bg-white p-8 rounded-xl border border-zinc-200 shadow-sm print:w-full">
        <div class="flex justify-between items-start border-b pb-4 mb-4">
            <div>
                <h1 class="text-2xl font-bold">INVOICE</h1>
                <p class="text-sm text-zinc-500">#<?= $transaksi['no_invoice'] ?></p>
            </div>
            <div class="text-right">
                <button onclick="window.print()" class="no-print bg-zinc-100 px-3 py-1 rounded text-xs mb-2 border">Cetak</button>
                <div class="text-sm font-bold uppercase <?= $transaksi['status_bayar']=='lunas'?'text-green-600':'text-red-600' ?>">
                    <?= $transaksi['status_bayar'] ?>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 text-sm mb-6">
            <div>
                <p class="text-zinc-500">Tamu:</p>
                <p class="font-bold"><?= $transaksi['nama_tamu'] ?></p>
            </div>
            <div class="text-right">
                <p class="text-zinc-500">Kamar:</p>
                <p class="font-bold"><?= $transaksi['nomor_kamar'] ?> (<?= $transaksi['nama_tipe'] ?>)</p>
            </div>
        </div>

        <table class="w-full text-sm mb-6">
            <thead class="bg-zinc-50 border-y">
                <tr><th class="py-2 text-left">Item</th><th class="text-right">Total</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td class="py-2">Sewa Kamar (<?= $transaksi['durasi_malam'] ?> mlm)</td>
                    <td class="text-right"><?= number_format($transaksi['total_biaya_kamar']) ?></td>
                </tr>
                <?php foreach($items as $i): ?>
                <tr>
                    <td class="py-2"><?= $i['nama_layanan'] ?> (x<?= $i['jumlah'] ?>)</td>
                    <td class="text-right"><?= number_format($i['subtotal']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="border-t font-bold">
                <tr><td class="py-2">Total Tagihan</td><td class="text-right"><?= number_format($transaksi['total_tagihan']) ?></td></tr>
                <tr><td class="py-2 text-green-600">Sudah Dibayar</td><td class="text-right text-green-600"><?= number_format($terbayar) ?></td></tr>
                <tr><td class="py-2 text-red-600">Sisa Kekurangan</td><td class="text-right text-red-600"><?= number_format($sisa) ?></td></tr>
            </tfoot>
        </table>
    </div>

    <div class="col-span-1 space-y-4 no-print">
        <?php if($transaksi['status_transaksi'] == 'active'): ?>
        <div class="bg-white p-4 rounded-xl border border-zinc-200">
            <h3 class="font-bold mb-3">Tambah Layanan (POS)</h3>
            <form method="POST" action="index.php?modul=POS&aksi=add&id=<?= $transaksi['id_transaksi'] ?>">
                <select name="id_layanan" class="w-full mb-2 border-zinc-300 rounded text-sm">
                    <?php foreach($menu as $m): ?>
                        <option value="<?= $m['id_layanan'] ?>"><?= $m['nama_layanan'] ?> - <?= $m['harga_satuan'] ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="jumlah" value="1" class="w-full mb-2 border-zinc-300 rounded text-sm">
                <button type="submit" name="add_pos" class="w-full bg-white border border-zinc-300 py-1 rounded text-sm hover:bg-zinc-50">Tambah</button>
            </form>
        </div>

        <div class="bg-white p-4 rounded-xl border border-zinc-200">
            <h3 class="font-bold mb-3">Pembayaran</h3>
            <form method="POST">
                <input type="number" name="uang" placeholder="Jumlah Uang" class="w-full mb-2 border-zinc-300 rounded text-sm">
                <select name="ket" class="w-full mb-2 border-zinc-300 rounded text-sm">
                    <option value="DP">DP</option>
                    <option value="Pelunasan">Pelunasan</option>
                </select>
                <button type="submit" name="pay" class="w-full bg-zinc-900 text-white py-1 rounded text-sm hover:bg-zinc-800">Bayar</button>
            </form>
        </div>

        <?php if($sisa <= 0): ?>
            <a href="index.php?modul=Checkout&aksi=payment&id=<?= $transaksi['id_transaksi'] ?>&process=checkout" onclick="return confirm('Yakin checkout?')" class="block w-full bg-green-600 text-white text-center py-3 rounded-xl font-bold hover:bg-green-700">
                PROSES CHECKOUT
            </a>
        <?php endif; ?>
        <?php else: ?>
            <div class="bg-zinc-100 p-4 rounded text-center text-zinc-500 font-bold">TRANSAKSI SELESAI</div>
        <?php endif; ?>
    </div>
</div>
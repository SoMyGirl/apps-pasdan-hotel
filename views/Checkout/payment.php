<style>
    /* Print Styles: Hanya mencetak area Invoice */
    @media print {
        @page { size: auto; margin: 0mm; }
        body * { visibility: hidden; }
        #invoice-paper, #invoice-paper * { visibility: visible; }
        #invoice-paper {
            position: absolute; left: 0; top: 0; width: 100%; margin: 0; padding: 20mm;
            border: none; box-shadow: none; background: white;
        }
        .no-print { display: none !important; }
    }
</style>

<div class="flex flex-col lg:flex-row gap-8 items-start">
    
    <div class="w-full lg:w-2/3">
        <div id="invoice-paper" class="bg-white p-10 rounded-xl border border-zinc-200 shadow-lg relative min-h-[600px]">
            
            <div class="flex justify-between items-start border-b border-zinc-100 pb-8 mb-8">
                <div>
                    <h1 class="text-2xl font-black text-zinc-900 tracking-tighter uppercase flex items-center gap-2">
                        <i data-lucide="building-2" class="w-6 h-6"></i> HOTEL SMK
                    </h1>
                    <p class="text-xs text-zinc-500 mt-2 w-48">Jl. Pendidikan No. 123, Kota Bandung, Jawa Barat (022) 123-4567</p>
                </div>
                <div class="text-right">
                    <h2 class="text-4xl font-black text-zinc-100 uppercase tracking-widest">INVOICE</h2>
                    <p class="font-mono text-zinc-500 font-bold mt-1">#<?= $transaksi['no_invoice'] ?></p>
                    <span class="inline-block mt-2 px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded border 
                        <?= $transaksi['status_bayar']=='lunas' ? 'border-emerald-200 text-emerald-700 bg-emerald-50' : 'border-rose-200 text-rose-700 bg-rose-50' ?>">
                        Status: <?= $transaksi['status_bayar'] ?>
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8 mb-8">
                <div>
                    <p class="text-[10px] uppercase font-bold text-zinc-400 tracking-wider mb-1">Bill To</p>
                    <p class="font-bold text-lg text-zinc-900"><?= $transaksi['nama_tamu'] ?></p>
                    <p class="text-sm text-zinc-500"><?= $transaksi['no_hp'] ?></p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] uppercase font-bold text-zinc-400 tracking-wider mb-1">Room Details</p>
                    <p class="font-bold text-lg text-zinc-900">Room <?= $transaksi['nomor_kamar'] ?></p>
                    <p class="text-sm text-zinc-500"><?= $transaksi['nama_tipe'] ?></p>
                    <p class="text-xs text-zinc-400 mt-1">
                        In: <?= date('d M Y', strtotime($transaksi['tgl_checkin'])) ?>
                    </p>
                </div>
            </div>

            <table class="w-full text-sm mb-8">
                <thead>
                    <tr class="border-b-2 border-zinc-900">
                        <th class="py-3 text-left font-bold text-zinc-900 uppercase text-xs">Description</th>
                        <th class="py-3 text-center font-bold text-zinc-900 uppercase text-xs w-20">Qty</th>
                        <th class="py-3 text-right font-bold text-zinc-900 uppercase text-xs w-32">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    <tr>
                        <td class="py-3 text-zinc-700 font-medium">Room Charge (<?= $transaksi['durasi_malam'] ?> Nights)</td>
                        <td class="py-3 text-center text-zinc-500"><?= $transaksi['durasi_malam'] ?></td>
                        <td class="py-3 text-right font-bold text-zinc-700"><?= number_format($transaksi['total_biaya_kamar']) ?></td>
                    </tr>
                    <?php foreach($items as $i): ?>
                    <tr>
                        <td class="py-3 text-zinc-600"><?= $i['nama_layanan'] ?></td>
                        <td class="py-3 text-center text-zinc-500"><?= $i['jumlah'] ?></td>
                        <td class="py-3 text-right font-medium text-zinc-700"><?= number_format($i['subtotal']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="flex justify-end mb-12">
                <div class="w-64 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-zinc-500 font-medium">Subtotal</span>
                        <span class="font-bold text-zinc-900"><?= number_format($transaksi['total_tagihan']) ?></span>
                    </div>
                    <?php foreach($history as $h): ?>
                    <div class="flex justify-between text-xs text-emerald-600">
                        <span>Paid (<?= date('d/m', strtotime($h['tgl_bayar'])) ?>)</span>
                        <span>- <?= number_format($h['jumlah_bayar']) ?></span>
                    </div>
                    <?php endforeach; ?>
                    
                    <div class="border-t-2 border-zinc-900 pt-2 flex justify-between items-center mt-2">
                        <span class="font-black text-base text-zinc-900 uppercase">Total Due</span>
                        <span class="font-black text-2xl <?= $sisa > 0 ? 'text-zinc-900' : 'text-emerald-600' ?>">
                            Rp <?= number_format($sisa) ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="text-center text-[10px] text-zinc-400 uppercase tracking-widest mt-auto">
                Thank you for your visit
            </div>
        </div>
    </div>

    <div class="w-full lg:w-1/3 space-y-6 no-print">
        
        <button onclick="window.print()" class="w-full py-4 bg-zinc-900 text-white rounded-xl font-bold hover:bg-zinc-800 transition-all shadow-lg flex items-center justify-center gap-2 group">
            <i data-lucide="printer" class="w-5 h-5 group-hover:scale-110 transition-transform"></i> Print Invoice
        </button>

        <?php if($transaksi['status_transaksi'] == 'active'): ?>
            
            <div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
                <div class="bg-zinc-50 p-4 border-b border-zinc-100 flex items-center gap-2">
                    <div class="bg-white p-1.5 rounded-md shadow-sm ring-1 ring-zinc-200">
                        <i data-lucide="coffee" class="w-4 h-4 text-zinc-700"></i>
                    </div>
                    <h3 class="font-bold text-sm text-zinc-900">Add Service / Menu</h3>
                </div>
                <div class="p-4">
                    <form method="POST" action="index.php?modul=Checkout&aksi=addItem">
                        <input type="hidden" name="id_transaksi" value="<?= $transaksi['id_transaksi'] ?>">
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs font-bold text-zinc-500 mb-1 block">Pilih Item</label>
                                <select name="id_layanan" class="w-full rounded-lg border-zinc-200 text-sm focus:ring-zinc-900 bg-zinc-50">
                                    <?php foreach($menu as $m): ?>
                                        <option value="<?= $m['id_layanan'] ?>">
                                            <?= $m['nama_layanan'] ?> (Rp <?= number_format($m['harga_satuan']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="flex gap-2">
                                <div class="w-1/3">
                                    <label class="text-xs font-bold text-zinc-500 mb-1 block">Qty</label>
                                    <input type="number" name="jumlah" value="1" min="1" class="w-full rounded-lg border-zinc-200 text-sm text-center focus:ring-zinc-900">
                                </div>
                                <button type="submit" class="flex-1 mt-auto bg-white border border-zinc-300 text-zinc-900 rounded-lg text-sm font-bold hover:bg-zinc-50 h-[38px] self-end">
                                    + Add Item
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
                <div class="bg-zinc-50 p-4 border-b border-zinc-100 flex items-center gap-2">
                    <div class="bg-white p-1.5 rounded-md shadow-sm ring-1 ring-zinc-200">
                        <i data-lucide="wallet" class="w-4 h-4 text-zinc-700"></i>
                    </div>
                    <h3 class="font-bold text-sm text-zinc-900">Input Payment</h3>
                </div>
                <div class="p-4">
                    <?php if($sisa > 0): ?>
                        <form method="POST">
                            <div class="space-y-3">
                                <div>
                                    <label class="text-xs font-bold text-zinc-500 mb-1 block">Nominal Bayar</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2.5 text-zinc-400 text-sm font-bold">Rp</span>
                                        <input type="number" name="uang" required max="<?= $sisa ?>" placeholder="<?= $sisa ?>" class="w-full pl-10 rounded-lg border-zinc-200 text-sm font-bold text-zinc-900 focus:ring-zinc-900">
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-zinc-500 mb-1 block">Keterangan</label>
                                    <select name="ket" class="w-full rounded-lg border-zinc-200 text-sm focus:ring-zinc-900 bg-zinc-50">
                                        <option value="Pelunasan">Pelunasan</option>
                                        <option value="Deposit / DP">Deposit / DP</option>
                                    </select>
                                </div>
                                <button type="submit" name="pay" class="w-full py-3 bg-emerald-600 text-white rounded-lg font-bold hover:bg-emerald-700 shadow-md shadow-emerald-100 transition-all">
                                    Process Payment
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i data-lucide="check-circle-2" class="w-12 h-12 text-emerald-500 mx-auto mb-2"></i>
                            <p class="text-sm font-bold text-emerald-700">Tagihan Lunas</p>
                            <p class="text-xs text-zinc-500">Siap untuk checkout.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($sisa <= 0): ?>
                <a href="index.php?modul=Checkout&aksi=payment&id=<?= $transaksi['id_transaksi'] ?>&process=checkout" 
                   onclick="return confirm('Konfirmasi Checkout? Kamar akan diset menjadi Dirty.')"
                   class="block w-full py-4 bg-rose-600 text-white text-center rounded-xl font-bold hover:bg-rose-700 shadow-lg shadow-rose-200 transition-all animate-pulse">
                   FINALIZE CHECKOUT <i data-lucide="log-out" class="inline w-4 h-4 ml-1"></i>
                </a>
            <?php endif; ?>

        <?php else: ?>
            <div class="bg-zinc-100 border border-zinc-200 rounded-xl p-6 text-center">
                <p class="font-bold text-zinc-500">Transaksi Selesai</p>
                <p class="text-xs text-zinc-400 mt-1">Tamu sudah checkout pada <?= date('d M H:i', strtotime($transaksi['tgl_checkout'])) ?></p>
            </div>
        <?php endif; ?>

    </div>
</div>
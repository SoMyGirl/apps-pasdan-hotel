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
    
    /* Hide arrows in number input */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
</style>

<div class="flex flex-col lg:flex-row gap-6 items-start">
    
    <div class="w-full lg:w-2/3">
        <div id="invoice-paper" class="bg-white p-10 rounded-xl border border-zinc-200 shadow-sm relative min-h-[600px]">
            
            <div class="flex justify-between items-start border-b border-zinc-100 pb-8 mb-8 gap-6">
                <div>
                    <h1 class="text-2xl font-black text-zinc-900 tracking-tighter uppercase flex items-center gap-2">
                        <i data-lucide="building-2" class="w-6 h-6"></i> PASUNDAN HOTEL
                    </h1>
                    <p class="text-xs text-zinc-500 mt-2 w-48">Jl. Bagus Yabin No.06, Cigadung, Kec. Subang, Kabupaten Subang, Jawa Barat 41211 (0260) 411778</p>
                </div>
                <div class="text-left md:text-right">
                    <h2 class="text-4xl font-black text-zinc-100 uppercase tracking-widest">INVOICE</h2>
                    <p class="font-mono text-zinc-500 font-bold mt-1">#<?= $transaksi['no_invoice'] ?></p>
                    <?php
                        $statusText = match($transaksi['status_bayar']) {
                            'lunas' => 'LUNAS',
                            'dp'    => 'DP MASUK',
                            default => 'BELUM BAYAR'
                        };
                        $statusClass = match($transaksi['status_bayar']) {
                            'lunas' => 'border-emerald-200 text-emerald-700 bg-emerald-50',
                            'dp'    => 'border-amber-200 text-amber-700 bg-amber-50',
                            default => 'border-rose-200 text-rose-700 bg-rose-50'
                        };
                    ?>
                    <span class="inline-block mt-3 px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded border <?= $statusClass ?>">
                        Status: <?= $statusText ?>
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8 mb-8">
                <div>
                    <p class="text-[10px] uppercase font-bold text-zinc-400 tracking-wider mb-1.5">Bill To</p>
                    <p class="font-bold text-lg text-zinc-900"><?= $transaksi['nama_tamu'] ?></p>
                    <p class="text-sm text-zinc-500 mt-1"><?= $transaksi['no_hp'] ?></p>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-[10px] uppercase font-bold text-zinc-400 tracking-wider mb-1.5">Stay Details</p>
                    <p class="font-bold text-lg text-zinc-900">Room <?= $transaksi['nomor_kamar'] ?></p>
                    <p class="text-sm text-zinc-500"><?= $transaksi['nama_tipe'] ?? 'Kamar Hotel' ?></p>
                    <p class="text-xs text-zinc-400 mt-1">
                        In: <?= date('d M Y', strtotime($transaksi['tgl_checkin'])) ?> | 
                        Durasi: <?= $transaksi['durasi_malam'] ?> Malam
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
                        <td class="py-4 text-zinc-700 font-medium">Room Charge (<?= $transaksi['durasi_malam'] ?> Nights)</td>
                        <td class="py-4 text-center text-zinc-500">-</td>
                        <td class="py-4 text-right font-bold text-zinc-700"><?= number_format($transaksi['total_biaya_kamar']) ?></td>
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
                <div class="w-full md:w-72 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-zinc-500 font-medium">Subtotal</span>
                        <span class="font-bold text-zinc-900"><?= number_format($transaksi['total_tagihan']) ?></span>
                    </div>
                    <?php foreach($history as $h): ?>
                    <div class="flex justify-between text-xs text-emerald-600 bg-emerald-50 px-2 py-1 rounded border border-emerald-100">
                        <span>Paid (<?= date('d/m H:i', strtotime($h['tgl_bayar'])) ?>)</span>
                        <span>- <?= number_format($h['jumlah_bayar']) ?></span>
                    </div>
                    <?php endforeach; ?>
                    
                    <div class="border-t-2 border-zinc-900 pt-3 flex justify-between items-center mt-2">
                        <span class="font-black text-base text-zinc-900 uppercase tracking-tight">Total Due</span>
                        <span class="font-black text-2xl <?= $sisa > 0 ? 'text-zinc-900' : 'text-emerald-600' ?>">
                            Rp <?= number_format(abs($sisa)) ?>
                        </span>
                    </div>
                </div>
            </div>

            <div style="margin-top: 60px; display: flex; justify-content: space-between; padding: 0 10px;">
                <div style="text-align: center; width: 35%;">
                    <p style="margin-bottom: 60px; font-weight: bold; font-size: 12px; text-transform: uppercase; color: #000;">Guest Signature</p>
                    <div style="border-bottom: 1px solid #000;"></div>
                    <p style="margin-top: 5px; font-size: 12px; font-weight: bold; color: #000;"><?= $transaksi['nama_tamu'] ?></p>
                </div>
                <div style="text-align: center; width: 35%;">
                    <p style="margin-bottom: 60px; font-weight: bold; font-size: 12px; text-transform: uppercase; color: #000;">Received By</p>
                    <div style="border-bottom: 1px solid #000;"></div>
                    <p style="margin-top: 5px; font-size: 12px; font-weight: bold; color: #000;"><?= $_SESSION['nama_lengkap'] ?? 'Receptionist' ?></p>
                </div>
            </div>

            <div class="text-center text-[10px] text-zinc-400 uppercase tracking-widest mt-12 pt-4 border-t border-zinc-50">
                Thank you for your visit - Pasundan Hotel
            </div>
        </div>
    </div>

    <div class="w-full lg:w-1/3 space-y-6 no-print">
        
        <button onclick="window.print()" class="w-full py-4 bg-white border-2 border-zinc-100 text-zinc-600 rounded-2xl font-bold hover:border-zinc-300 hover:text-zinc-900 transition-all shadow-sm flex items-center justify-center gap-3 group">
            <div class="p-2 bg-zinc-100 rounded-lg group-hover:bg-zinc-200 transition-colors">
                <i data-lucide="printer" class="w-5 h-5"></i>
            </div>
            <span>Cetak Invoice</span>
        </button>

        <?php if($transaksi['status_transaksi'] == 'active'): ?>
            
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-3">
                         <div class="p-1.5 bg-blue-50 text-blue-600 rounded-lg"><i data-lucide="calendar-plus" class="w-4 h-4"></i></div>
                         <h3 class="font-bold text-sm text-zinc-900">Perpanjang Durasi</h3>
                    </div>
                    <form method="POST" action="index.php?modul=Checkout&aksi=extend&id=<?= $transaksi['id_transaksi'] ?>" id="formExtend">
                        <div class="flex gap-2">
                            <input type="number" name="add_night" id="addNightInput" value="1" min="1" 
                                   class="w-20 rounded-xl border-zinc-200 text-sm text-center font-bold focus:ring-blue-500 focus:border-blue-500 bg-zinc-50">
                            <button type="button" onclick="confirmExtend()" 
                                    class="flex-1 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition-all shadow-md hover:shadow-lg shadow-blue-200 active:scale-95 transform">
                                + Tambah Malam
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-3">
                         <div class="p-1.5 bg-amber-50 text-amber-600 rounded-lg"><i data-lucide="coffee" class="w-4 h-4"></i></div>
                         <h3 class="font-bold text-sm text-zinc-900">Layanan / F&B</h3>
                    </div>
                    <form method="POST" action="index.php?modul=Checkout&aksi=addItem">
                        <input type="hidden" name="id_transaksi" value="<?= $transaksi['id_transaksi'] ?>">
                        <div class="space-y-3">
                            <select name="id_layanan" class="w-full pl-3 pr-8 py-2.5 rounded-xl border-zinc-200 text-sm focus:ring-amber-500 focus:border-amber-500 bg-zinc-50 cursor-pointer">
                                <?php foreach($menu as $m): ?>
                                    <option value="<?= $m['id_layanan'] ?>">
                                        <?= $m['nama_layanan'] ?> — Rp <?= number_format($m['harga_satuan']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="flex gap-2">
                                <input type="number" name="jumlah" value="1" min="1" placeholder="Qty"
                                       class="w-20 rounded-xl border-zinc-200 text-sm text-center font-bold focus:ring-amber-500 focus:border-amber-500 bg-zinc-50">
                                <button type="submit" class="flex-1 bg-amber-500 text-white rounded-xl text-sm font-bold hover:bg-amber-600 transition-all shadow-md hover:shadow-lg shadow-amber-200 active:scale-95 transform">
                                    + Tambah
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-zinc-200 shadow-lg shadow-zinc-100 overflow-hidden ring-1 ring-zinc-900/5">
                <div class="bg-gradient-to-r from-zinc-50 to-white px-5 py-4 border-b border-zinc-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="p-1.5 bg-emerald-100 text-emerald-700 rounded-lg"><i data-lucide="wallet" class="w-4 h-4"></i></div>
                        <h3 class="font-bold text-sm text-zinc-900">Input Pembayaran</h3>
                    </div>
                </div>
                
                <div class="p-5">
                    <?php if($sisa > 0): ?>
                        <form method="POST" id="paymentForm">
                            <input type="hidden" name="pay" value="1">
                            
                            <div class="space-y-5">
                                <div>
                                    <label class="text-xs font-bold text-zinc-500 mb-2 block uppercase tracking-wide">Nominal (Rp)</label>
                                    <input type="number" name="uang" id="inputUang" required max="<?= $sisa ?>" placeholder="<?= $sisa ?>" 
                                           class="block w-full py-3 px-4 rounded-xl border-2 border-zinc-100 text-xl font-bold text-zinc-900 focus:border-emerald-500 focus:ring-0 placeholder:text-zinc-300 transition-all bg-zinc-50 focus:bg-white text-center">
                                </div>

                                <div>
                                    <label class="text-xs font-bold text-zinc-500 mb-2 block uppercase tracking-wide">Jenis Bayar</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <label class="cursor-pointer group relative">
                                            <input type="radio" name="ket" value="Pelunasan" checked class="peer sr-only">
                                            <div class="rounded-xl border-2 border-zinc-100 p-3 text-center hover:bg-zinc-50 peer-checked:border-emerald-500 peer-checked:bg-emerald-50/50 peer-checked:text-emerald-700 transition-all">
                                                <span class="block text-sm font-bold">Pelunasan</span>
                                            </div>
                                            <div class="absolute top-[-8px] right-[-8px] hidden peer-checked:block bg-emerald-500 text-white rounded-full p-1 shadow-sm"><i data-lucide="check" class="w-3 h-3"></i></div>
                                        </label>

                                        <label class="cursor-pointer group relative">
                                            <input type="radio" name="ket" value="Deposit / DP" class="peer sr-only">
                                            <div class="rounded-xl border-2 border-zinc-100 p-3 text-center hover:bg-zinc-50 peer-checked:border-blue-500 peer-checked:bg-blue-50/50 peer-checked:text-blue-700 transition-all">
                                                <span class="block text-sm font-bold">Deposit / DP</span>
                                            </div>
                                            <div class="absolute top-[-8px] right-[-8px] hidden peer-checked:block bg-blue-500 text-white rounded-full p-1 shadow-sm"><i data-lucide="check" class="w-3 h-3"></i></div>
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="w-full py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-xl font-bold shadow-lg shadow-emerald-200 hover:shadow-xl hover:shadow-emerald-300 hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.98] transition-all flex justify-center items-center gap-2 group">
                                    <i data-lucide="banknote" class="w-5 h-5 group-hover:rotate-12 transition-transform"></i> 
                                    TERIMA PEMBAYARAN
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="flex flex-col items-center justify-center py-8 text-center bg-emerald-50/50 rounded-xl border border-dashed border-emerald-200">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mb-3 shadow-sm border border-emerald-100">
                                <i data-lucide="check-check" class="w-8 h-8 text-emerald-500"></i>
                            </div>
                            <h4 class="text-emerald-800 font-bold text-lg">Tagihan Lunas!</h4>
                            <p class="text-emerald-600/70 text-xs mt-1">Siap untuk checkout.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($sisa <= 0): ?>
                <button onclick="confirmCheckout('<?= $transaksi['id_transaksi'] ?>')" 
                   class="w-full py-4 bg-zinc-900 text-white text-center rounded-2xl font-bold hover:bg-black shadow-xl shadow-zinc-300 transition-all flex items-center justify-center gap-2 transform hover:-translate-y-1 active:scale-95">
                   <i data-lucide="log-out" class="w-5 h-5"></i>
                   FINALIZE CHECKOUT
                </button>
            <?php endif; ?>

            <div class="mt-8 pt-6 border-t border-zinc-200 text-center">
                <button onclick="cancelTransaction('<?= $transaksi['id_transaksi'] ?>')" 
                        class="px-6 py-2 text-sm font-bold text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-all flex items-center justify-center gap-2 mx-auto">
                    <i data-lucide="x-circle" class="w-4 h-4"></i> Batalkan Transaksi
                </button>
            </div>

        <?php else: ?>
            <div class="bg-zinc-50 border border-zinc-200 rounded-2xl p-8 text-center">
                <div class="inline-flex p-4 bg-white rounded-full shadow-sm mb-4 border border-zinc-100">
                    <i data-lucide="archive" class="w-8 h-8 text-zinc-400"></i>
                </div>
                <p class="font-bold text-zinc-900 text-lg">Transaksi Selesai</p>
                <p class="text-xs text-zinc-500 mt-2">
                    Checkout pada <span class="font-mono font-medium text-zinc-700"><?= date('d M Y, H:i', strtotime($transaksi['tgl_checkout'])) ?></span>
                </p>
            </div>
        <?php endif; ?>

    </div>
</div>

<script>
    lucide.createIcons();

    function confirmCheckout(id) {
        Swal.fire({
            title: '<span class="text-xl font-bold text-zinc-900">Konfirmasi Checkout?</span>',
            html: '<p class="text-zinc-500 text-sm">Transaksi akan ditutup dan status kamar menjadi <strong class="text-amber-600">DIRTY</strong>.</p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#18181b', // Zinc-900
            cancelButtonColor: '#f4f4f5',
            confirmButtonText: 'Ya, Checkout',
            cancelButtonText: '<span class="text-zinc-600 font-bold">Batal</span>',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl p-6',
                confirmButton: 'rounded-xl px-6 py-3 font-bold shadow-lg',
                cancelButton: 'rounded-xl px-6 py-3 hover:bg-zinc-200'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "index.php?modul=Checkout&aksi=payment&id=" + id + "&process=checkout";
            }
        })
    }

    function confirmExtend() {
        // ... (Logic sama dengan sebelumnya, hanya style Swal bisa disesuaikan jika mau) ...
        document.getElementById('formExtend').submit();
    }

    // Logic Uang Kurang & Cancel (Sama seperti sebelumnya)
    const sisaTagihan = <?= $sisa ?>;
    const formPay = document.getElementById('paymentForm');

    if (formPay) {
        formPay.addEventListener('submit', function(e) {
            const inputUang = parseFloat(document.getElementById('inputUang').value);
            const tipeBayar = document.querySelector('input[name="ket"]:checked').value;

            if (tipeBayar === 'Pelunasan' && inputUang < sisaTagihan) {
                e.preventDefault();
                Swal.fire({
                    title: 'Uang Kurang',
                    text: 'Jadikan sebagai Deposit/DP?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, DP',
                    confirmButtonColor: '#3b82f6'
                }).then((res) => {
                    if(res.isConfirmed) {
                        document.querySelector('input[value="Deposit / DP"]').checked = true;
                        formPay.submit();
                    }
                });
            }
        });
    }

    function cancelTransaction(id) {
        Swal.fire({
            title: 'Batalkan Transaksi?',
            text: 'Data tidak dapat dikembalikan.',
            icon: 'error',
            showCancelButton: true,
            confirmButtonText: 'Ya, Batalkan',
            confirmButtonColor: '#e11d48'
        }).then((res) => {
            if(res.isConfirmed) window.location.href = "index.php?modul=Checkout&aksi=cancel&id=" + id;
        });
    }
</script>
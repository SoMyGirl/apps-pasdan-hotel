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
                        <i data-lucide="building-2" class="w-6 h-6"></i> HOTEL SMK
                    </h1>
                    <p class="text-xs text-zinc-500 mt-2 w-48">Jl. Pendidikan No. 123, Kota Bandung, Jawa Barat (022) 123-4567</p>
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
                    <?php if(!empty($transaksi['no_identitas'])): ?>
                        <p class="text-xs text-zinc-400 mt-0.5">ID: <?= $transaksi['no_identitas'] ?></p>
                    <?php endif; ?>
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
                    <div class="flex justify-between text-xs text-emerald-600 bg-emerald-50 px-2 py-1 rounded">
                        <span>Paid (<?= date('d/m H:i', strtotime($h['tgl_bayar'])) ?>) - <span class="capitalize"><?= $h['keterangan'] ?></span></span>
                        <span>- <?= number_format($h['jumlah_bayar']) ?></span>
                    </div>
                    <?php endforeach; ?>
                    
                    <div class="border-t-2 border-zinc-900 pt-2 flex justify-between items-center mt-2">
                        <span class="font-black text-base text-zinc-900 uppercase tracking-tight">Total Due</span>
                        <span class="font-black text-2xl <?= $sisa > 0 ? 'text-zinc-900' : 'text-emerald-600' ?>">
                            Rp <?= number_format(abs($sisa)) ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="text-center text-[10px] text-zinc-400 uppercase tracking-widest mt-auto pt-8 border-t border-zinc-50">
                Thank you for your visit
            </div>
        </div>
    </div>

    <div class="w-full lg:w-1/3 space-y-6 no-print">
        
        <button onclick="window.print()" class="w-full py-3.5 bg-white border border-zinc-200 text-zinc-700 rounded-xl font-bold hover:bg-zinc-50 hover:border-zinc-300 transition-all shadow-sm flex items-center justify-center gap-2 group transform hover:-translate-y-0.5">
            <i data-lucide="printer" class="w-4 h-4 group-hover:scale-110 transition-transform"></i> 
            Print Invoice
        </button>

        <?php if($transaksi['status_transaksi'] == 'active'): ?>
            
            <div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
                <div class="bg-zinc-50 px-5 py-3 border-b border-zinc-100 flex items-center gap-2">
                    <i data-lucide="calendar-plus" class="w-4 h-4 text-zinc-500"></i>
                    <h3 class="font-bold text-xs text-zinc-900 uppercase tracking-wider">Extend Stay</h3>
                </div>
                <div class="p-5">
                    <form method="POST" action="index.php?modul=Checkout&aksi=extend&id=<?= $transaksi['id_transaksi'] ?>" id="formExtend">
                        <label class="text-xs font-medium text-zinc-500 mb-1.5 block">Tambah Malam</label>
                        <div class="flex gap-2">
                            <input type="number" name="add_night" id="addNightInput" value="1" min="1" 
                                   class="w-20 rounded-lg border-zinc-200 text-sm text-center font-bold focus:ring-zinc-900 focus:border-zinc-900 py-2.5">
                            <button type="button" onclick="confirmExtend()" 
                                    class="flex-1 bg-zinc-900 text-white rounded-lg text-sm font-bold hover:bg-zinc-800 transition-all shadow-md transform hover:-translate-y-0.5">
                                Perpanjang
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
                <div class="bg-zinc-50 px-5 py-3 border-b border-zinc-100 flex items-center gap-2">
                    <i data-lucide="coffee" class="w-4 h-4 text-zinc-500"></i>
                    <h3 class="font-bold text-xs text-zinc-900 uppercase tracking-wider">Add Service / Menu</h3>
                </div>
                <div class="p-5">
                    <form method="POST" action="index.php?modul=Checkout&aksi=addItem">
                        <input type="hidden" name="id_transaksi" value="<?= $transaksi['id_transaksi'] ?>">
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs font-medium text-zinc-500 mb-1.5 block">Pilih Item</label>
                                <div class="relative">
                                    <select name="id_layanan" class="w-full pl-3 pr-8 py-2.5 rounded-lg border-zinc-200 text-sm focus:ring-zinc-900 focus:border-zinc-900 bg-white appearance-none cursor-pointer">
                                        <?php foreach($menu as $m): ?>
                                            <option value="<?= $m['id_layanan'] ?>">
                                                <?= $m['nama_layanan'] ?> — Rp <?= number_format($m['harga_satuan']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <i data-lucide="chevron-down" class="absolute right-3 top-3 w-4 h-4 text-zinc-400 pointer-events-none"></i>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <div class="w-20">
                                    <input type="number" name="jumlah" value="1" min="1" placeholder="Qty"
                                           class="w-full rounded-lg border-zinc-200 text-sm text-center font-bold focus:ring-zinc-900 focus:border-zinc-900 py-2.5">
                                </div>
                                <button type="submit" class="flex-1 bg-white border border-zinc-200 text-zinc-700 rounded-lg text-sm font-bold hover:bg-zinc-50 hover:border-zinc-300 transition-all shadow-sm flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                                    <i data-lucide="plus" class="w-3 h-3"></i> Tambah
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden ring-1 ring-zinc-900/5">
                <div class="bg-zinc-50 px-5 py-3 border-b border-zinc-100 flex items-center gap-2">
                    <i data-lucide="wallet" class="w-4 h-4 text-zinc-500"></i>
                    <h3 class="font-bold text-xs text-zinc-900 uppercase tracking-wider">Input Payment</h3>
                </div>
                <div class="p-5">
                    <?php if($sisa > 0): ?>
                        <form method="POST" id="paymentForm">
                            <input type="hidden" name="pay" value="1">
                            
                            <div class="space-y-5">
                                <div>
                                    <label class="text-xs font-bold text-zinc-700 mb-2 block uppercase tracking-wide">Nominal Bayar</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-zinc-400 font-bold text-sm group-focus-within:text-zinc-900 transition-colors">Rp</span>
                                        </div>
                                        <input type="number" name="uang" id="inputUang" required max="<?= $sisa ?>" placeholder="<?= $sisa ?>" 
                                               class="block w-full pl-12 pr-4 py-3 rounded-xl border border-zinc-200 text-lg font-bold text-zinc-900 focus:border-zinc-900 focus:ring-zinc-900 placeholder:text-zinc-300 transition-all shadow-sm">
                                    </div>
                                    <p class="text-[10px] text-zinc-400 mt-1.5 text-right">Sisa Tagihan: Rp <?= number_format($sisa) ?></p>
                                </div>

                                <div>
                                    <label class="text-xs font-bold text-zinc-700 mb-2 block uppercase tracking-wide">Jenis Pembayaran</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <label class="cursor-pointer group relative">
                                            <input type="radio" name="ket" value="Pelunasan" checked class="peer sr-only">
                                            <div class="rounded-xl border border-zinc-200 p-3 text-center hover:bg-zinc-50 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 transition-all">
                                                <span class="block text-sm font-bold">Pelunasan</span>
                                                <span class="block text-[10px] text-zinc-400 group-hover:text-zinc-500 peer-checked:text-emerald-600/70 mt-0.5">Bayar Full</span>
                                            </div>
                                            <div class="absolute top-1.5 right-1.5 hidden peer-checked:block text-emerald-500">
                                                <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                                            </div>
                                        </label>

                                        <label class="cursor-pointer group relative">
                                            <input type="radio" name="ket" value="Deposit / DP" class="peer sr-only">
                                            <div class="rounded-xl border border-zinc-200 p-3 text-center hover:bg-zinc-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition-all">
                                                <span class="block text-sm font-bold">Deposit / DP</span>
                                                <span class="block text-[10px] text-zinc-400 group-hover:text-zinc-500 peer-checked:text-blue-600/70 mt-0.5">Bayar Sebagian</span>
                                            </div>
                                            <div class="absolute top-1.5 right-1.5 hidden peer-checked:block text-blue-500">
                                                <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="w-full py-3.5 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-700 shadow-md shadow-emerald-100 hover:shadow-lg transition-all flex justify-center items-center gap-2 transform active:scale-[0.98]">
                                    <i data-lucide="banknote" class="w-5 h-5"></i> 
                                    Terima Pembayaran
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="flex flex-col items-center justify-center py-6 text-center">
                            <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mb-3 border border-emerald-100">
                                <i data-lucide="check-circle-2" class="w-8 h-8 text-emerald-500"></i>
                            </div>
                            <h4 class="text-emerald-700 font-bold text-lg">Lunas!</h4>
                            <p class="text-zinc-500 text-xs mt-1 max-w-[200px]">Semua tagihan telah dibayar. Silakan lakukan checkout.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($sisa <= 0): ?>
                <button onclick="confirmCheckout('<?= $transaksi['id_transaksi'] ?>')" 
                   class="w-full py-4 bg-rose-600 text-white text-center rounded-xl font-bold hover:bg-rose-700 shadow-lg shadow-rose-200 transition-all animate-pulse flex items-center justify-center gap-2 transform hover:-translate-y-1">
                   <i data-lucide="log-out" class="w-5 h-5"></i>
                   FINALIZE CHECKOUT
                </button>
            <?php endif; ?>

            <div class="mt-8 pt-6 border-t border-zinc-200 text-center">
                <button onclick="cancelTransaction('<?= $transaksi['id_transaksi'] ?>')" 
                        class="w-full py-3 text-sm font-bold text-rose-500 bg-rose-50 border border-rose-100 rounded-xl hover:bg-rose-100 transition-all flex items-center justify-center gap-2">
                    <i data-lucide="x-circle" class="w-4 h-4"></i> Batalkan Transaksi
                </button>
            </div>

        <?php else: ?>
            <div class="bg-zinc-100 border border-zinc-200 rounded-xl p-8 text-center">
                <div class="inline-flex p-3 bg-white rounded-full shadow-sm mb-4">
                    <i data-lucide="archive" class="w-6 h-6 text-zinc-400"></i>
                </div>
                <p class="font-bold text-zinc-900">Transaksi Selesai</p>
                <p class="text-xs text-zinc-500 mt-1">
                    Tamu checkout pada <br>
                    <span class="font-mono font-medium text-zinc-700"><?= date('d M Y, H:i', strtotime($transaksi['tgl_checkout'])) ?></span>
                </p>
            </div>

        <?php endif; ?>

    </div>
</div>

<script>
    // Inisialisasi Icon
    lucide.createIcons();

    // 1. MODAL CHECKOUT
    function confirmCheckout(id) {
        Swal.fire({
            title: '<span class="text-xl font-bold text-zinc-900">Konfirmasi Checkout?</span>',
            html: '<p class="text-zinc-500 text-sm">Transaksi akan ditutup dan status kamar akan berubah menjadi <strong class="text-amber-600">DIRTY</strong>.</p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48', // Rose-600
            cancelButtonColor: '#f4f4f5', // Zinc-100
            confirmButtonText: 'Ya, Checkout',
            cancelButtonText: '<span class="text-zinc-600 font-bold">Batal</span>',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl p-6',
                confirmButton: 'rounded-lg px-6 py-2.5 font-bold shadow-lg shadow-rose-200',
                cancelButton: 'rounded-lg px-6 py-2.5 hover:bg-zinc-200'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "index.php?modul=Checkout&aksi=payment&id=" + id + "&process=checkout";
            }
        })
    }

    // 2. MODAL EXTEND
    function confirmExtend() {
        const newDurationInput = document.getElementById('addNightInput');
        const addNights = parseInt(newDurationInput.value);
        
        if (isNaN(addNights) || addNights < 1) {
            Swal.fire({
                 title: 'Invalid Input',
                 text: 'Minimal tambah 1 malam.',
                 icon: 'error',
                 confirmButtonColor: '#18181b',
                 customClass: { popup: 'rounded-xl' }
            });
            return;
        }

        Swal.fire({
            title: '<span class="text-xl font-bold text-zinc-900">Perpanjang Menginap?</span>',
            html: `<p class="text-zinc-500 text-sm">Durasi akan ditambah <strong class="text-blue-600">+${addNights} malam</strong>.<br>Tagihan akan otomatis disesuaikan.</p>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb', 
            cancelButtonColor: '#f4f4f5',
            confirmButtonText: 'Ya, Perpanjang',
            cancelButtonText: '<span class="text-zinc-600 font-bold">Batal</span>',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl p-6',
                confirmButton: 'rounded-lg px-6 py-2.5 font-bold shadow-lg shadow-blue-200',
                cancelButton: 'rounded-lg px-6 py-2.5 hover:bg-zinc-200'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formExtend').submit();
            }
        });
    }

    // 3. VALIDASI PEMBAYARAN (UANG KURANG)
    const sisaTagihan = <?= $sisa ?>;
    const formPay = document.getElementById('paymentForm');

    if (formPay) {
        formPay.addEventListener('submit', function(e) {
            const inputUang = parseFloat(document.getElementById('inputUang').value);
            const tipeBayar = document.querySelector('input[name="ket"]:checked').value;

            // Jika pilih Lunas tapi uang kurang
            if (tipeBayar === 'Pelunasan' && inputUang < sisaTagihan) {
                e.preventDefault(); // Stop submit asli
                
                Swal.fire({
                    title: '<span class="text-xl font-bold text-rose-600">Uang Tidak Cukup!</span>',
                    html: '<p class="text-zinc-500 text-sm">Nominal kurang dari total tagihan. Apakah anda ingin mencatatnya sebagai <strong>Deposit / DP</strong>?</p>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2563eb',
                    cancelButtonColor: '#f4f4f5',
                    confirmButtonText: 'Ya, Jadikan DP',
                    cancelButtonText: '<span class="text-zinc-600 font-bold">Batal</span>',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-2xl p-6',
                        confirmButton: 'rounded-lg px-6 py-2.5 font-bold',
                        cancelButton: 'rounded-lg px-6 py-2.5 hover:bg-zinc-200'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Ubah radio button ke DP
                        document.querySelector('input[value="Deposit / DP"]').checked = true;
                        // Submit ulang form secara manual menggunakan method form asli
                        formPay.submit(); 
                    }
                });
            }
        });
    }

    // 4. BATALKAN TRANSAKSI
    function cancelTransaction(id) {
        Swal.fire({
            title: '<span class="text-xl font-bold text-zinc-900">Batalkan Transaksi?</span>',
            html: '<p class="text-zinc-500 text-sm">Transaksi akan diubah menjadi <strong class="text-rose-600">BATAL</strong>.<br>Status kamar akan dikembalikan menjadi <strong>Available</strong>.</p>',
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#f4f4f5',
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: '<span class="text-zinc-600 font-bold">Tidak</span>',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl p-6',
                confirmButton: 'rounded-lg px-6 py-2.5 font-bold shadow-lg shadow-rose-200',
                cancelButton: 'rounded-lg px-6 py-2.5 hover:bg-zinc-200'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "index.php?modul=Checkout&aksi=cancel&id=" + id;
            }
        });
    }
</script>
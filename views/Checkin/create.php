<div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900">New Check-in</h1>
        <p class="text-zinc-500 mt-1 text-sm">Registrasi tamu baru dan penetapan kamar.</p>
    </div>
    <a href="index.php?modul=Dashboard&aksi=index" class="inline-flex items-center gap-2 text-sm font-medium text-zinc-500 hover:text-zinc-900 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Dashboard
    </a>
</div>

<form method="POST" id="checkinForm">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
                <div class="bg-zinc-50/50 px-6 py-4 border-b border-zinc-200">
                    <h2 class="text-sm font-bold text-zinc-900 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="user" class="w-4 h-4 text-zinc-500"></i> Informasi Tamu
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-zinc-700">Nama Lengkap</label>
                            <div class="relative">
                                <i data-lucide="user-circle" class="absolute left-3 top-2.5 w-5 h-5 text-zinc-400"></i>
                                <input type="text" name="nama" required placeholder="Sesuai KTP/Identitas" 
                                    class="w-full pl-10 rounded-lg border-zinc-200 focus:border-zinc-900 focus:ring-zinc-900 transition-all text-sm py-2.5 shadow-sm">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-zinc-700">Nomor Handphone</label>
                            <div class="relative">
                                <i data-lucide="smartphone" class="absolute left-3 top-2.5 w-5 h-5 text-zinc-400"></i>
                                <input type="number" name="hp" required placeholder="08..." 
                                    class="w-full pl-10 rounded-lg border-zinc-200 focus:border-zinc-900 focus:ring-zinc-900 transition-all text-sm py-2.5 shadow-sm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
                <div class="bg-zinc-50/50 px-6 py-4 border-b border-zinc-200">
                    <h2 class="text-sm font-bold text-zinc-900 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="bed-double" class="w-4 h-4 text-zinc-500"></i> Detail Reservasi
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-zinc-700">Pilih Kamar (Available)</label>
                        <div class="relative">
                            <i data-lucide="door-open" class="absolute left-3 top-3 w-5 h-5 text-zinc-400"></i>
                            <select name="id_kamar" id="kamarSelect" required 
                                class="w-full pl-10 rounded-lg border-zinc-200 focus:border-zinc-900 focus:ring-zinc-900 transition-all text-sm py-2.5 shadow-sm appearance-none bg-white">
                                <option value="" data-harga="0" data-tipe="-">-- Pilih Kamar Tersedia --</option>
                                <?php foreach($kamar as $k): ?>
                                    <option value="<?= $k['id_kamar'] ?>" 
                                            data-harga="<?= $k['harga_dasar'] ?>" 
                                            data-tipe="<?= $k['nama_tipe'] ?>"
                                            data-nomor="<?= $k['nomor_kamar'] ?>"
                                            <?= (isset($_GET['id_kamar']) && $_GET['id_kamar'] == $k['id_kamar']) ? 'selected' : '' ?>>
                                        Room <?= $k['nomor_kamar'] ?> - <?= $k['nama_tipe'] ?> (Rp <?= number_format($k['harga_dasar'],0,',','.') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-3 top-3 w-4 h-4 text-zinc-400 pointer-events-none"></i>
                        </div>
                        <p class="text-xs text-zinc-500 mt-1">*Hanya menampilkan kamar dengan status Available.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-zinc-700">Tanggal Check-in</label>
                            <input type="datetime-local" name="tgl" value="<?= date('Y-m-d\TH:i') ?>" 
                                class="w-full rounded-lg border-zinc-200 focus:border-zinc-900 focus:ring-zinc-900 transition-all text-sm py-2.5 shadow-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-zinc-700">Durasi Inap (Malam)</label>
                            <div class="flex items-center">
                                <button type="button" onclick="adjustDuration(-1)" class="bg-zinc-100 hover:bg-zinc-200 border border-zinc-300 rounded-l-lg p-2.5 transition-colors">
                                    <i data-lucide="minus" class="w-4 h-4 text-zinc-600"></i>
                                </button>
                                <input type="number" name="durasi" id="durasiInput" value="1" min="1" required 
                                    class="w-full text-center border-y border-zinc-200 focus:border-zinc-900 focus:ring-0 py-2.5 text-sm z-10">
                                <button type="button" onclick="adjustDuration(1)" class="bg-zinc-100 hover:bg-zinc-200 border border-zinc-300 rounded-r-lg p-2.5 transition-colors">
                                    <i data-lucide="plus" class="w-4 h-4 text-zinc-600"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="sticky top-6">
                <div class="bg-zinc-900 text-white rounded-xl shadow-xl overflow-hidden ring-1 ring-zinc-900/5">
                    <div class="p-6 border-b border-zinc-700">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <i data-lucide="receipt" class="w-5 h-5 text-zinc-400"></i> Booking Summary
                        </h3>
                        <p class="text-zinc-400 text-xs mt-1">Estimasi biaya awal.</p>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-start">
                            <div class="text-sm text-zinc-400">Room Type</div>
                            <div class="text-right">
                                <div class="font-bold text-white text-lg" id="summaryRoom">No Room Selected</div>
                                <div class="text-xs text-zinc-400" id="summaryType">-</div>
                            </div>
                        </div>
                        
                        <div class="h-px bg-zinc-800 my-2"></div>

                        <div class="flex justify-between items-center text-sm">
                            <span class="text-zinc-400">Price / Night</span>
                            <span class="font-medium" id="summaryPrice">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-zinc-400">Duration</span>
                            <span class="font-medium"><span id="summaryDuration">1</span> Malam</span>
                        </div>

                        <div class="h-px bg-zinc-700 my-4 border-dashed border-t"></div>

                        <div class="flex justify-between items-end">
                            <span class="text-sm font-bold text-zinc-300">ESTIMATED TOTAL</span>
                            <span class="text-2xl font-black text-emerald-400" id="summaryTotal">Rp 0</span>
                        </div>
                    </div>

                    <div class="p-4 bg-zinc-800">
                        <button type="submit" class="w-full bg-white text-zinc-900 font-bold py-3.5 rounded-lg hover:bg-zinc-200 transition-all shadow-lg flex items-center justify-center gap-2 group">
                            <i data-lucide="check-circle-2" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                            Confirm Check-in
                        </button>
                    </div>
                </div>

                <div class="mt-4 bg-amber-50 border border-amber-200 rounded-lg p-4 flex gap-3">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5"></i>
                    <p class="text-xs text-amber-800 leading-relaxed">
                        Pastikan data tamu sudah sesuai dengan kartu identitas (KTP/SIM/Paspor). Status kamar akan langsung berubah menjadi <strong>Occupied</strong> setelah proses ini.
                    </p>
                </div>
            </div>
        </div>

    </div>
</form>

<script>
    // Initialize Lucide Icons
    lucide.createIcons();

    const kamarSelect = document.getElementById('kamarSelect');
    const durasiInput = document.getElementById('durasiInput');
    
    // Summary Elements
    const summaryRoom = document.getElementById('summaryRoom');
    const summaryType = document.getElementById('summaryType');
    const summaryPrice = document.getElementById('summaryPrice');
    const summaryDuration = document.getElementById('summaryDuration');
    const summaryTotal = document.getElementById('summaryTotal');

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    }

    function updateSummary() {
        // Ambil data dari Option yang dipilih
        const selectedOption = kamarSelect.options[kamarSelect.selectedIndex];
        
        if (selectedOption.value) {
            const harga = parseInt(selectedOption.getAttribute('data-harga'));
            const tipe = selectedOption.getAttribute('data-tipe');
            const nomor = selectedOption.getAttribute('data-nomor');
            const durasi = parseInt(durasiInput.value) || 1;

            // Update UI Text
            summaryRoom.innerText = "Room " + nomor;
            summaryType.innerText = tipe;
            summaryPrice.innerText = formatRupiah(harga);
            summaryDuration.innerText = durasi;
            
            // Calculate Total
            const total = harga * durasi;
            summaryTotal.innerText = formatRupiah(total);
        } else {
            // Reset jika tidak ada yang dipilih
            summaryRoom.innerText = "No Room Selected";
            summaryType.innerText = "-";
            summaryPrice.innerText = "Rp 0";
            summaryTotal.innerText = "Rp 0";
        }
    }

    function adjustDuration(amount) {
        let current = parseInt(durasiInput.value) || 1;
        let newValue = current + amount;
        if (newValue < 1) newValue = 1;
        durasiInput.value = newValue;
        updateSummary();
    }

    // Event Listeners
    kamarSelect.addEventListener('change', updateSummary);
    durasiInput.addEventListener('input', updateSummary);

    // Run on Load (if data is pre-selected)
    updateSummary();
</script>
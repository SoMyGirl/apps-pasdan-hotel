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
    <input type="hidden" name="id_kamar" id="selectedRoomId" required> 
    
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
                    
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-zinc-700">Nomor NIK/Identitas</label>
                        <div class="relative">
                            <i data-lucide="credit-card" class="absolute left-3 top-2.5 w-5 h-5 text-zinc-400"></i>
                            <input type="number" name="nik" placeholder="16 Digit NIK atau Nomor ID Lainnya" 
                                class="w-full pl-10 rounded-lg border-zinc-200 focus:border-zinc-900 focus:ring-zinc-900 transition-all text-sm py-2.5 shadow-sm">
                        </div>
                        <p class="text-xs text-zinc-500 mt-1">Opsional, untuk kelengkapan data registrasi.</p>
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
                        
                        <button type="button" id="openRoomModalBtn" 
                            class="w-full text-left inline-flex justify-between items-center pl-4 pr-3 py-2.5 rounded-lg border border-zinc-200 focus:border-zinc-900 focus:ring-zinc-900 transition-all text-sm shadow-sm bg-white hover:bg-zinc-50">
                            <span id="selectedRoomInfo" class="font-medium text-zinc-500 flex items-center gap-2">
                                <i data-lucide="door-open" class="w-5 h-5 text-zinc-400"></i>
                                -- Klik untuk Pilih Kamar Tersedia --
                            </span>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-zinc-400"></i>
                        </button>
                        
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
                        <button type="submit" id="confirmCheckinBtn" disabled class="w-full bg-zinc-600 text-zinc-400 font-bold py-3.5 rounded-lg transition-all shadow-lg flex items-center justify-center gap-2 group">
                            <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                            Confirm Check-in
                        </button>
                    </div>
                </div>

                <div class="mt-4 bg-amber-50 border border-amber-200 rounded-lg p-4 flex gap-3">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5"></i>
                    <p class="text-xs text-amber-800 leading-relaxed">
                        Pastikan data tamu sudah sesuai dengan kartu identitas (KTP/SIM/Paspor). Status kamar akan langsung berubah menjadi <strong>Occupied</strong> setelah proses ini.
                        <br><strong class="mt-1 block">Saat ini hanya mendukung check-in untuk satu kamar per transaksi.</strong>
                    </p>
                </div>
            </div>
        </div>

    </div>
</form>

<div id="roomModal" class="hidden fixed inset-0 z-50">
    <div class="absolute inset-0 bg-zinc-900/40 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop"></div>
    
    <div class="flex min-h-full items-center justify-center p-4 text-center">
        <div id="modalContent" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl scale-95 opacity-0">
            <div class="bg-zinc-50 px-6 py-4 border-b border-zinc-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-zinc-900">Pilih Kamar Tersedia</h3>
                <button onclick="closeModal()" class="rounded-full p-1 hover:bg-zinc-200 transition-colors">
                    <i data-lucide="x" class="w-5 h-5 text-zinc-500"></i>
                </button>
            </div>
            
            <div class="p-4 border-b border-zinc-100 flex items-center gap-3">
                <div class="relative w-1/3">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="filter" class="h-4 w-4 text-zinc-400"></i>
                    </div>
                    <select id="tipeFilter" class="pl-10 pr-4 py-2 w-full rounded-lg border border-zinc-200 text-sm focus:border-zinc-900 focus:ring-zinc-900 transition-all shadow-sm appearance-none bg-white cursor-pointer hover:border-zinc-300">
                        <option value="">Semua Tipe</option>
                        <?php foreach($listTipe as $t): ?>
                            <option value="<?= $t['id_tipe'] ?>">
                                <?= $t['nama_tipe'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                     <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i data-lucide="chevron-down" class="h-4 w-4 text-zinc-400"></i>
                    </div>
                </div>

                <div class="relative flex-1">
                    <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-zinc-400"></i>
                    <input type="text" id="roomSearch" placeholder="Cari nomor kamar..." 
                           class="pl-9 pr-4 py-2 w-full rounded-lg border border-zinc-200 text-sm focus:border-zinc-900 focus:ring-zinc-900 transition-all shadow-sm">
                </div>
            </div>
            
            <div class="max-h-96 overflow-y-auto p-4 custom-scrollbar">
                <div id="roomListContainer" class="grid grid-cols-2 gap-4">
                    </div>
                <div id="noRoomMessage" class="hidden p-8 text-center text-zinc-500">
                    <i data-lucide="filter-x" class="w-10 h-10 mx-auto mb-3"></i>
                    <p>Tidak ada kamar tersedia dengan filter ini.</p>
                </div>
            </div>

            <div class="bg-zinc-50 px-6 py-4 text-right">
                <button type="button" onclick="closeModal()" class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-100">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Inisialisasi Lucide Icons
    lucide.createIcons();

    // Data Kamar dari PHP
    const availableRooms = <?= json_encode($kamar) ?>;

    const selectedRoomIdInput = document.getElementById('selectedRoomId');
    const openRoomModalBtn = document.getElementById('openRoomModalBtn');
    const selectedRoomInfoSpan = document.getElementById('selectedRoomInfo');
    const durasiInput = document.getElementById('durasiInput');
    const roomListContainer = document.getElementById('roomListContainer');
    const roomModal = document.getElementById('roomModal');
    const modalBackdrop = document.getElementById('modalBackdrop');
    const roomSearchInput = document.getElementById('roomSearch');
    const tipeFilter = document.getElementById('tipeFilter');
    const noRoomMessage = document.getElementById('noRoomMessage');
    const confirmCheckinBtn = document.getElementById('confirmCheckinBtn');
    
    // Summary Elements
    const summaryRoom = document.getElementById('summaryRoom');
    const summaryType = document.getElementById('summaryType');
    const summaryPrice = document.getElementById('summaryPrice');
    const summaryDuration = document.getElementById('summaryDuration');
    const summaryTotal = document.getElementById('summaryTotal');

    let currentSelectedRoomData = null;

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    }

    function updateSummary() {
        if (currentSelectedRoomData) {
            const harga = parseInt(currentSelectedRoomData.harga_dasar);
            const tipe = currentSelectedRoomData.nama_tipe;
            const nomor = currentSelectedRoomData.nomor_kamar;
            const durasi = parseInt(durasiInput.value) || 1;

            // Update UI Text
            summaryRoom.innerText = "Room " + nomor;
            summaryType.innerText = tipe;
            summaryPrice.innerText = formatRupiah(harga);
            summaryDuration.innerText = durasi;
            
            // Calculate Total
            const total = harga * durasi;
            summaryTotal.innerText = formatRupiah(total);
            
            // Enable button
            confirmCheckinBtn.disabled = false;
            confirmCheckinBtn.classList.remove('bg-zinc-600', 'text-zinc-400');
            confirmCheckinBtn.classList.add('bg-white', 'text-zinc-900', 'hover:bg-zinc-200');

        } else {
            // Reset jika tidak ada yang dipilih
            summaryRoom.innerText = "No Room Selected";
            summaryType.innerText = "-";
            summaryPrice.innerText = "Rp 0";
            summaryTotal.innerText = "Rp 0";
            
            // Disable button
            confirmCheckinBtn.disabled = true;
            confirmCheckinBtn.classList.remove('bg-white', 'text-zinc-900', 'hover:bg-zinc-200');
            confirmCheckinBtn.classList.add('bg-zinc-600', 'text-zinc-400');
        }
    }

    function adjustDuration(amount) {
        let current = parseInt(durasiInput.value) || 1;
        let newValue = current + amount;
        if (newValue < 1) newValue = 1;
        durasiInput.value = newValue;
        updateSummary();
    }
    
    // --- MODAL & ROOM SELECTION LOGIC ---

    function openModal() {
        roomModal.classList.remove('hidden');
        renderRoomList();
        // Animation
        void roomModal.offsetWidth; 
        modalBackdrop.classList.remove('opacity-0');
        document.getElementById('modalContent').classList.remove('scale-95', 'opacity-0');
        document.getElementById('modalContent').classList.add('scale-100', 'opacity-100');
    }

    function closeModal() {
        // Animation
        modalBackdrop.classList.add('opacity-0');
        document.getElementById('modalContent').classList.remove('scale-100', 'opacity-100');
        document.getElementById('modalContent').classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            roomModal.classList.add('hidden');
        }, 200);
    }

    function selectRoom(id_kamar) {
        currentSelectedRoomData = availableRooms.find(r => r.id_kamar == id_kamar);
        
        if (currentSelectedRoomData) {
            // Set Hidden Input Value
            selectedRoomIdInput.value = id_kamar;
            
            // Update Teks di Tombol Utama
            selectedRoomInfoSpan.innerHTML = `
                <i data-lucide="door-open" class="w-5 h-5 text-zinc-900"></i>
                <span class="font-bold text-zinc-900">Room ${currentSelectedRoomData.nomor_kamar}</span>
                <span class="text-zinc-500">- ${currentSelectedRoomData.nama_tipe}</span>
            `;

            // Rerun Lucide Icons for the new span content
            lucide.createIcons();

            // Update Summary
            updateSummary();
            
            // Close Modal
            closeModal();
            
        } else {
            // Should not happen if data is correctly structured
            alert('Error: Kamar tidak ditemukan!');
        }
    }

    function renderRoomList() {
        const searchText = roomSearchInput.value.toLowerCase();
        const filterTipe = tipeFilter.value;
        let filteredRooms = availableRooms.filter(room => {
            const matchesSearch = room.nomor_kamar.toLowerCase().includes(searchText);
            const matchesType = !filterTipe || room.id_tipe == filterTipe;
            return matchesSearch && matchesType;
        });
        
        if (filteredRooms.length === 0) {
            roomListContainer.innerHTML = '';
            noRoomMessage.classList.remove('hidden');
            return;
        }

        noRoomMessage.classList.add('hidden');
        let html = '';
        filteredRooms.forEach(room => {
            const isSelected = currentSelectedRoomData && currentSelectedRoomData.id_kamar == room.id_kamar;
            const cardClass = isSelected 
                ? 'bg-zinc-900 text-white ring-2 ring-emerald-400' 
                : 'bg-white text-zinc-900 ring-1 ring-zinc-200 hover:ring-zinc-400 hover:shadow-md';
            const priceColor = isSelected ? 'text-emerald-400' : 'text-zinc-700';
            
            html += `
                <div data-id="${room.id_kamar}" onclick="selectRoom(${room.id_kamar})"
                     class="p-4 rounded-xl cursor-pointer transition-all duration-200 ${cardClass}">
                    <div class="flex justify-between items-start">
                        <span class="text-xl font-bold tracking-tight">
                            ${room.nomor_kamar}
                        </span>
                        ${isSelected ? '<i data-lucide="check-circle" class="w-5 h-5 text-emerald-400"></i>' : ''}
                    </div>
                    <div class="mt-2">
                        <p class="text-[10px] uppercase font-bold ${isSelected ? 'text-zinc-400' : 'text-zinc-500'} tracking-wider">
                            ${room.nama_tipe}
                        </p>
                        <p class="text-lg font-black ${priceColor} mt-0.5">
                            Rp ${formatRupiah(room.harga_dasar).replace('Rp', '').trim()}
                        </p>
                    </div>
                </div>
            `;
        });

        roomListContainer.innerHTML = html;
        // Rerun Lucide Icons for the new list content
        lucide.createIcons();
    }

    // --- Event Listeners ---
    openRoomModalBtn.addEventListener('click', openModal);
    roomSearchInput.addEventListener('input', renderRoomList);
    tipeFilter.addEventListener('change', renderRoomList);
    durasiInput.addEventListener('input', updateSummary);
    modalBackdrop.addEventListener('click', closeModal);

    // Run on Load (optional check if room ID is passed via URL)
    updateSummary();

    // Check if a room was pre-selected (e.g., from Dashboard Quick Checkin)
    const urlParams = new URLSearchParams(window.location.search);
    const preselectedRoomId = urlParams.get('id_kamar');
    if (preselectedRoomId) {
        selectRoom(preselectedRoomId);
    }
</script>
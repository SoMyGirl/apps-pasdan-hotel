<div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900">Check-in</h1>
        <p class="text-zinc-500 mt-1 text-sm">Pilih tamu dari database dan tetapkan kamar.</p>
    </div>
    <a href="index.php?modul=Dashboard&aksi=index" class="inline-flex items-center gap-2 text-sm font-medium text-zinc-500 hover:text-zinc-900 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Dashboard
    </a>
</div>

<form method="POST" id="checkinForm">
    <input type="hidden" name="process_checkin" value="1">
    <input type="hidden" name="id_kamar" id="selectedRoomId" required> 
    <input type="hidden" name="id_tamu" id="selectedGuestId" required>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
                <div class="bg-zinc-50/50 px-6 py-4 border-b border-zinc-200 flex justify-between items-center">
                    <h2 class="text-sm font-bold text-zinc-900 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="user" class="w-4 h-4 text-zinc-500"></i> Data Tamu
                    </h2>
                    <button type="button" onclick="openGuestModal()" class="text-xs bg-zinc-900 text-white px-3 py-1.5 rounded-lg font-medium hover:bg-zinc-700 transition-colors shadow-sm">
                        Cari / Tambah Tamu
                    </button>
                </div>
                <div class="p-6">
                    <div id="emptyGuestState" class="text-center py-8 border-2 border-dashed border-zinc-200 rounded-xl hover:border-zinc-300 transition-colors cursor-pointer group" onclick="openGuestModal()">
                        <div class="w-14 h-14 bg-zinc-50 rounded-full flex items-center justify-center mx-auto mb-3 text-zinc-400 group-hover:scale-110 transition-transform">
                            <i data-lucide="user-plus" class="w-7 h-7"></i>
                        </div>
                        <p class="text-zinc-500 text-sm font-medium">Belum ada tamu dipilih.</p>
                        <span class="text-blue-600 font-bold text-xs mt-1 block group-hover:underline">Klik untuk cari database</span>
                    </div>

                    <div id="selectedGuestCard" class="hidden flex items-center gap-5 bg-blue-50 border border-blue-100 p-5 rounded-xl shadow-sm">
                        <div class="w-14 h-14 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-xl shadow-md" id="guestAvatar">
                            A
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-blue-900 text-lg tracking-tight" id="guestNameDisplay">Nama Tamu</h3>
                            <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-blue-700 mt-1 font-medium">
                                <span class="flex items-center gap-1.5 bg-white/50 px-2 py-0.5 rounded-md">
                                    <i data-lucide="credit-card" class="w-3.5 h-3.5"></i> <span id="guestNikDisplay">-</span>
                                </span>
                                <span class="flex items-center gap-1.5 bg-white/50 px-2 py-0.5 rounded-md">
                                    <i data-lucide="phone" class="w-3.5 h-3.5"></i> <span id="guestHpDisplay">-</span>
                                </span>
                            </div>
                        </div>
                        <button type="button" onclick="resetGuest()" class="p-2 hover:bg-white rounded-full text-blue-400 hover:text-rose-500 transition-all shadow-sm ring-1 ring-transparent hover:ring-zinc-100">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
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
                        <label class="text-xs font-bold text-zinc-500 uppercase tracking-wide">Pilih Kamar (Available)</label>
                        <button type="button" onclick="openRoomModal()" class="w-full text-left inline-flex justify-between items-center px-4 py-3 rounded-xl border border-zinc-200 hover:border-zinc-400 bg-white transition-all shadow-sm group">
                            <span id="selectedRoomInfo" class="font-medium text-zinc-500 flex items-center gap-2 text-sm">
                                <i data-lucide="door-open" class="w-5 h-5 text-zinc-400 group-hover:text-zinc-600"></i>
                                -- Klik untuk Pilih Kamar --
                            </span>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-zinc-400"></i>
                        </button>
                        <div id="roomListDisplay" class="mt-3 space-y-2"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-zinc-500 uppercase tracking-wide">Check-in</label>
                            <input type="datetime-local" name="tgl" value="<?= date('Y-m-d\TH:i') ?>" class="w-full rounded-xl border-zinc-200 text-sm py-2.5 focus:ring-zinc-900 focus:border-zinc-900 font-medium">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-zinc-500 uppercase tracking-wide">Durasi (Malam)</label>
                            <div class="flex items-center">
                                <button type="button" onclick="adjustDuration(-1)" class="bg-zinc-100 hover:bg-zinc-200 border border-zinc-300 rounded-l-xl p-2.5 transition-colors"><i data-lucide="minus" class="w-4 h-4 text-zinc-600"></i></button>
                                <input type="number" name="durasi" id="durasiInput" value="1" min="1" class="w-full text-center border-y border-zinc-200 py-2.5 text-sm z-10 font-bold text-zinc-900 focus:ring-0 focus:border-zinc-200">
                                <button type="button" onclick="adjustDuration(1)" class="bg-zinc-100 hover:bg-zinc-200 border border-zinc-300 rounded-r-xl p-2.5 transition-colors"><i data-lucide="plus" class="w-4 h-4 text-zinc-600"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="sticky top-6">
                <div class="bg-zinc-900 text-white rounded-2xl shadow-xl overflow-hidden ring-1 ring-zinc-900/5">
                    <div class="p-6 border-b border-zinc-800">
                        <h3 class="font-bold flex items-center gap-2"><i data-lucide="receipt" class="w-5 h-5 text-zinc-400"></i> Summary</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div id="summaryItems" class="space-y-2 text-sm min-h-[50px]">
                            <span class="text-zinc-500 italic text-xs">Belum ada kamar dipilih.</span>
                        </div>
                        <div class="h-px bg-zinc-800 border-t border-dashed border-zinc-700 my-4"></div>
                        <div class="flex justify-between items-end">
                            <span class="text-xs text-zinc-400 font-bold uppercase tracking-widest">Total Estimasi</span>
                            <span class="text-2xl font-black text-emerald-400" id="summaryTotal">Rp 0</span>
                        </div>
                    </div>
                    <div class="p-4 bg-black/20 backdrop-blur-sm space-y-3">
                        <button type="submit" id="confirmBtn" disabled 
                                class="w-full py-3.5 rounded-xl font-bold text-sm flex justify-center items-center gap-2 transition-all transform active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed bg-zinc-700 text-zinc-400">
                            <i data-lucide="check-circle" class="w-5 h-5"></i> Proses Check-in
                        </button>

                        <button type="button" onclick="cancelTransaction()" 
                                class="w-full py-3 rounded-xl font-bold text-xs text-rose-400 hover:text-white hover:bg-rose-900/50 transition-all flex justify-center items-center gap-2">
                            <i data-lucide="x-circle" class="w-4 h-4"></i> Batalkan Transaksi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div id="guestModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-900/60 backdrop-blur-sm transition-all">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl flex flex-col max-h-[85vh] overflow-hidden" onclick="event.stopPropagation()">
        
        <div class="grid grid-cols-2 text-center border-b border-zinc-100 bg-zinc-50/50">
            <button onclick="switchTab('search')" id="tabSearch" class="py-4 text-sm font-bold text-zinc-900 border-b-2 border-zinc-900 transition-colors">
                Cari Database
            </button>
            <button onclick="switchTab('add')" id="tabAdd" class="py-4 text-sm font-medium text-zinc-400 hover:text-zinc-600 transition-colors">
                Tambah Baru
            </button>
        </div>

        <div id="viewSearch" class="flex-1 flex flex-col overflow-hidden h-full">
            <div class="p-4 border-b border-zinc-100">
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-zinc-400"></i>
                    <input type="text" id="inputSearchGuest" placeholder="Cari Nama atau NIK..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border-zinc-200 text-sm focus:border-zinc-900 focus:ring-zinc-900 transition-all bg-zinc-50 hover:bg-white focus:bg-white">
                </div>
            </div>
            <div class="flex-1 overflow-y-auto p-2 space-y-1 custom-scrollbar" id="guestListContainer">
                </div>
            <div class="p-4 border-t border-zinc-100">
                <button onclick="closeGuestModal()" class="w-full py-3 bg-rose-600 text-white font-bold rounded-xl hover:bg-rose-700 shadow-md shadow-rose-100 text-sm transition-all active:scale-[0.98]">
                    Batal
                </button>
            </div>
        </div>

        <div id="viewAdd" class="hidden flex-1 overflow-y-auto p-6">
            <form id="formAddGuest" onsubmit="submitNewGuest(event)" class="space-y-5">
                <div>
                    <label class="block text-xs font-bold uppercase text-zinc-500 mb-1.5 ml-1">Nama Lengkap</label>
                    <input type="text" id="newNama" required class="w-full px-4 py-2.5 rounded-xl border border-zinc-200 focus:border-zinc-900 focus:ring-zinc-900 text-sm transition-all shadow-sm" placeholder="Nama sesuai ID">
                </div>
                <div class="grid grid-cols-5 gap-3">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold uppercase text-zinc-500 mb-1.5 ml-1">Jenis ID</label>
                        <div class="relative">
                            <select id="newJenis" class="w-full pl-3 pr-8 py-2.5 rounded-xl border border-zinc-200 text-sm focus:border-zinc-900 focus:ring-zinc-900 bg-white appearance-none cursor-pointer shadow-sm">
                                <option value="KTP">KTP</option>
                                <option value="SIM">SIM</option>
                                <option value="PASSPORT">Passport</option>
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-3 top-3 w-4 h-4 text-zinc-400 pointer-events-none"></i>
                        </div>
                    </div>
                    <div class="col-span-3">
                        <label class="block text-xs font-bold uppercase text-zinc-500 mb-1.5 ml-1">Nomor Identitas</label>
                        <input type="number" id="newNik" required class="w-full px-4 py-2.5 rounded-xl border border-zinc-200 focus:border-zinc-900 focus:ring-zinc-900 text-sm transition-all shadow-sm" placeholder="NIK / No Paspor">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-zinc-500 mb-1.5 ml-1">No. Handphone</label>
                    <input type="number" id="newHp" required class="w-full px-4 py-2.5 rounded-xl border border-zinc-200 focus:border-zinc-900 focus:ring-zinc-900 text-sm transition-all shadow-sm" placeholder="Contoh: 0812...">
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="closeGuestModal()" class="flex-1 py-3 bg-rose-600 border border-zinc-200 text-white font-bold rounded-xl hover:bg-zinc-50 text-sm transition-all">Batal</button>
                    <button type="submit" class="flex-[2] py-3 bg-zinc-900 text-white font-bold rounded-xl hover:bg-zinc-800 shadow-lg shadow-zinc-200 text-sm transition-all transform active:scale-95">Simpan & Pilih</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="roomModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-900/60 backdrop-blur-sm transition-all">
    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl flex flex-col max-h-[85vh] overflow-hidden">
        
        <div class="p-4 border-b flex justify-between items-center bg-zinc-50">
            <h3 class="font-bold text-zinc-900 flex items-center gap-2">
                <i data-lucide="door-open" class="w-5 h-5 text-zinc-500"></i> Pilih Kamar Tersedia
            </h3>
            <button onclick="document.getElementById('roomModal').classList.add('hidden')" class="p-1 hover:bg-zinc-200 rounded-full transition-colors"><i data-lucide="x" class="w-5 h-5 text-zinc-500"></i></button>
        </div>
        
        <div class="p-4 border-b border-zinc-100 flex items-center gap-3 bg-white">
            <div class="relative w-1/3">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="filter" class="w-4 h-4 text-zinc-400"></i>
                </div>
                <select id="filterRoomType" class="w-full pl-9 pr-8 py-2.5 rounded-xl border border-zinc-200 text-sm focus:border-zinc-900 focus:ring-zinc-900 bg-zinc-50 hover:bg-white appearance-none cursor-pointer transition-all shadow-sm">
                    <option value="">Semua Tipe</option>
                    <?php foreach($tipe as $t): ?>
                        <option value="<?= $t['id_tipe'] ?>"><?= $t['nama_tipe'] ?></option>
                    <?php endforeach; ?>
                </select>
                <i data-lucide="chevron-down" class="absolute right-3 top-3 w-4 h-4 text-zinc-400 pointer-events-none"></i>
            </div>
            
            <div class="relative flex-1">
                <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-zinc-400"></i>
                <input type="text" id="searchRoomNo" placeholder="Cari nomor kamar..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-zinc-200 text-sm focus:border-zinc-900 focus:ring-zinc-900 transition-all bg-zinc-50 hover:bg-white shadow-sm">
            </div>
        </div>

        <div class="p-4 overflow-y-auto custom-scrollbar bg-zinc-50/50 flex-1">
            <div id="modalRoomGrid" class="grid grid-cols-2 md:grid-cols-3 gap-3">
                </div>
            
            <div id="roomEmptyState" class="hidden flex flex-col items-center justify-center py-12 text-zinc-400">
                <div class="w-12 h-12 bg-zinc-100 rounded-full flex items-center justify-center mb-3">
                    <i data-lucide="filter-x" class="w-6 h-6 opacity-50"></i>
                </div>
                <p class="text-sm font-medium">Tidak ada kamar yang cocok.</p>
            </div>
        </div>
        
        <div class="p-4 border-t border-zinc-100 bg-white text-right">
             <button onclick="document.getElementById('roomModal').classList.add('hidden')" class="px-5 py-2.5 bg-zinc-100 text-zinc-600 rounded-xl text-sm font-bold hover:bg-zinc-200 transition-colors">Tutup</button>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
    
    // --- DATA ---
    const allGuests = <?= json_encode($tamu) ?>;
    const allRooms = <?= json_encode($kamar) ?>;
    let selectedRooms = [];

    // --- 1. LOGIC TAMU ---
    
    // Auto Close Modal on Backdrop Click
    document.getElementById('guestModal').addEventListener('click', function(e) {
        // Cek jika yang diklik adalah element 'guestModal' itu sendiri (backdropnya), bukan child-nya
        if (e.target === this) {
            closeGuestModal();
        }
    });

    function renderGuestList(filterText = '') {
        const container = document.getElementById('guestListContainer');
        container.innerHTML = '';
        
        const filtered = allGuests.filter(g => {
            const searchStr = (g.nama_tamu + ' ' + g.no_identitas).toLowerCase();
            return searchStr.includes(filterText.toLowerCase());
        });

        if (filtered.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8 text-zinc-400">
                    <p class="text-sm">Tamu tidak ditemukan.</p>
                    <button onclick="switchTab('add')" class="text-blue-600 text-xs font-bold mt-1 hover:underline">Tambah Baru?</button>
                </div>`;
            return;
        }

        filtered.forEach(t => {
            const div = document.createElement('div');
            div.className = "guest-item flex justify-between items-center p-3 rounded-lg hover:bg-zinc-50 cursor-pointer transition-colors group border-b border-zinc-50 last:border-0";
            div.onclick = () => selectGuest(t);
            div.innerHTML = `
                <div>
                    <p class="font-bold text-zinc-900 text-sm group-hover:text-blue-600 transition-colors">${t.nama_tamu}</p>
                    <p class="text-xs text-zinc-500 mt-0.5">
                        <span class="inline-block bg-zinc-100 px-1.5 rounded text-[10px] uppercase font-bold text-zinc-600 mr-1">${t.jenis_identitas ?? 'ID'}</span>
                        ${t.no_identitas}
                    </p>
                </div>
                <i data-lucide="chevron-right" class="w-4 h-4 text-zinc-300 group-hover:text-zinc-900"></i>
            `;
            container.appendChild(div);
        });
        lucide.createIcons();
    }

    function openGuestModal() {
        document.getElementById('guestModal').classList.remove('hidden');
        renderGuestList();
        document.getElementById('inputSearchGuest').value = '';
        document.getElementById('inputSearchGuest').focus();
    }
    function closeGuestModal() { document.getElementById('guestModal').classList.add('hidden'); }

    function switchTab(tab) {
        const tabSearch = document.getElementById('tabSearch');
        const tabAdd = document.getElementById('tabAdd');
        if(tab === 'search') {
            document.getElementById('viewSearch').classList.remove('hidden');
            document.getElementById('viewAdd').classList.add('hidden');
            tabSearch.className = "py-4 text-sm font-bold text-zinc-900 border-b-2 border-zinc-900 transition-colors";
            tabAdd.className = "py-4 text-sm font-medium text-zinc-400 hover:text-zinc-600 transition-colors";
        } else {
            document.getElementById('viewSearch').classList.add('hidden');
            document.getElementById('viewAdd').classList.remove('hidden');
            tabAdd.className = "py-4 text-sm font-bold text-zinc-900 border-b-2 border-zinc-900 transition-colors";
            tabSearch.className = "py-4 text-sm font-medium text-zinc-400 hover:text-zinc-600 transition-colors";
        }
    }

    document.getElementById('inputSearchGuest').addEventListener('keyup', (e) => renderGuestList(e.target.value));

    function selectGuest(tamu) {
        document.getElementById('selectedGuestId').value = tamu.id_tamu;
        document.getElementById('emptyGuestState').classList.add('hidden');
        document.getElementById('selectedGuestCard').classList.remove('hidden');
        document.getElementById('selectedGuestCard').classList.add('flex');
        
        document.getElementById('guestNameDisplay').innerText = tamu.nama_tamu;
        document.getElementById('guestNikDisplay').innerText = `${tamu.jenis_identitas ?? 'ID'}: ${tamu.no_identitas}`;
        document.getElementById('guestHpDisplay').innerText = tamu.no_hp;
        document.getElementById('guestAvatar').innerText = tamu.nama_tamu.charAt(0).toUpperCase();
        
        closeGuestModal();
        validateForm();
    }

    function resetGuest() {
        document.getElementById('selectedGuestId').value = '';
        document.getElementById('emptyGuestState').classList.remove('hidden');
        document.getElementById('selectedGuestCard').classList.add('hidden');
        document.getElementById('selectedGuestCard').classList.remove('flex');
        validateForm();
    }

    function submitNewGuest(e) {
        e.preventDefault();
        const btn = e.target.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.innerHTML = "Menyimpan...";
        btn.disabled = true;

        const formData = new FormData();
        formData.append('nama', document.getElementById('newNama').value);
        formData.append('jenis', document.getElementById('newJenis').value);
        formData.append('nik', document.getElementById('newNik').value);
        formData.append('hp', document.getElementById('newHp').value);

        fetch('index.php?modul=Checkin&aksi=ajaxAddGuest', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success') {
                allGuests.push(res.data);
                selectGuest(res.data);
                document.getElementById('formAddGuest').reset();
                switchTab('search'); 
            } else {
                alert(res.message);
            }
        })
        .catch(err => alert("Terjadi kesalahan koneksi."))
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    // --- 2. LOGIC KAMAR ---
    
    function openRoomModal() {
        document.getElementById('roomModal').classList.remove('hidden');
        renderModalRooms();
    }

    function renderModalRooms() {
        const container = document.getElementById('modalRoomGrid');
        const emptyState = document.getElementById('roomEmptyState');
        const typeFilter = document.getElementById('filterRoomType').value;
        const searchFilter = document.getElementById('searchRoomNo').value.toLowerCase();

        const filteredRooms = allRooms.filter(room => {
            const matchType = typeFilter === '' || room.id_tipe == typeFilter;
            const matchSearch = room.nomor_kamar.toLowerCase().includes(searchFilter);
            return matchType && matchSearch;
        });

        container.innerHTML = '';
        
        if (filteredRooms.length === 0) {
            emptyState.classList.remove('hidden');
        } else {
            emptyState.classList.add('hidden');
            
            filteredRooms.forEach(k => {
                const isSelected = selectedRooms.some(r => r.id_kamar == k.id_kamar);
                const activeClass = isSelected ? 'ring-2 ring-blue-500 bg-blue-50 border-blue-200' : 'hover:border-zinc-400 bg-white border-zinc-200';
                
                const div = document.createElement('div');
                div.className = `border p-3 rounded-xl cursor-pointer transition-all text-center group relative ${activeClass}`;
                div.onclick = () => toggleRoom(k);
                
                div.innerHTML = `
                    <div class="font-black text-xl text-zinc-800 group-hover:text-zinc-900">${k.nomor_kamar}</div>
                    <div class="text-[10px] text-zinc-500 uppercase font-bold tracking-wider mt-1 truncate">${k.nama_tipe}</div>
                    <div class="text-xs font-bold text-emerald-600 mt-2 bg-emerald-50 py-1 rounded-lg">
                        Rp ${(k.harga_dasar/1000).toLocaleString()}k
                    </div>
                    ${isSelected ? '<div class="absolute top-2 right-2 text-blue-600"><i data-lucide="check-circle-2" class="w-4 h-4"></i></div>' : ''}
                `;
                container.appendChild(div);
            });
            lucide.createIcons();
        }
    }

    function toggleRoom(kamar) {
        const idx = selectedRooms.findIndex(r => r.id_kamar == kamar.id_kamar);
        if (idx > -1) selectedRooms.splice(idx, 1);
        else selectedRooms.push(kamar);
        renderModalRooms();
        renderSelectedRooms();
    }

    function renderSelectedRooms() {
        const container = document.getElementById('roomListDisplay');
        const summary = document.getElementById('summaryItems');
        const inputIds = document.getElementById('selectedRoomId');
        const grandTotalEl = document.getElementById('summaryTotal');
        const durasi = parseInt(document.getElementById('durasiInput').value) || 1;
        const infoBtn = document.getElementById('selectedRoomInfo');
        
        container.innerHTML = ''; summary.innerHTML = '';
        let total = 0;

        if (selectedRooms.length > 0) {
            infoBtn.innerHTML = `<i data-lucide="check-circle" class="w-5 h-5 text-emerald-500"></i><span class="text-zinc-900 font-bold">${selectedRooms.length} Kamar Dipilih</span>`;
            
            selectedRooms.forEach((room, i) => {
                container.innerHTML += `
                    <div class="flex justify-between items-center bg-zinc-50 p-3 rounded-xl border border-zinc-200 text-sm">
                        <div>
                            <span class="font-bold text-zinc-900">Room ${room.nomor_kamar}</span>
                            <span class="text-xs text-zinc-500 ml-2 font-medium uppercase tracking-wide">${room.nama_tipe}</span>
                        </div>
                        <button type="button" onclick="toggleRoom({id_kamar:${room.id_kamar}})" class="text-rose-500 hover:bg-rose-50 p-1.5 rounded-lg transition-colors"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                    </div>
                `;
                let subtotal = room.harga_dasar * durasi;
                total += subtotal;
                summary.innerHTML += `
                    <div class="flex justify-between items-center">
                        <span class="text-zinc-400">R.${room.nomor_kamar} (${durasi} mlm)</span>
                        <span class="text-white font-medium text-right">Rp ${subtotal.toLocaleString()}</span>
                    </div>
                `;
            });
        } else {
            infoBtn.innerHTML = `<i data-lucide="door-open" class="w-5 h-5 text-zinc-400 group-hover:text-zinc-600"></i><span class="text-zinc-500">-- Klik untuk Pilih Kamar --</span>`;
            summary.innerHTML = `<span class="text-zinc-500 italic text-xs">Belum ada kamar.</span>`;
        }

        inputIds.value = selectedRooms.map(r => r.id_kamar).join(',');
        grandTotalEl.innerText = 'Rp ' + total.toLocaleString();
        lucide.createIcons();
        validateForm();
    }

    document.getElementById('filterRoomType').addEventListener('change', renderModalRooms);
    document.getElementById('searchRoomNo').addEventListener('keyup', renderModalRooms);

    const durasiInput = document.getElementById('durasiInput');
    function adjustDuration(n) {
        let val = parseInt(durasiInput.value) || 1;
        val = Math.max(1, val + n);
        durasiInput.value = val;
        renderSelectedRooms();
    }
    durasiInput.addEventListener('input', renderSelectedRooms);

    function validateForm() {
        const guestId = document.getElementById('selectedGuestId').value;
        const roomsVal = document.getElementById('selectedRoomId').value;
        const btn = document.getElementById('confirmBtn');

        if (guestId && roomsVal) {
            btn.disabled = false;
            btn.className = "w-full py-3.5 bg-white border border-zinc-200 text-zinc-900 rounded-xl font-bold shadow-lg hover:bg-zinc-50 transition-all flex justify-center items-center gap-2 transform active:scale-[0.98]";
        } else {
            btn.disabled = true;
            btn.className = "w-full py-3.5 rounded-xl font-bold text-sm flex justify-center items-center gap-2 transition-all bg-zinc-700 text-zinc-500 cursor-not-allowed opacity-50";
        }
    }

    // --- 3. LOGIC BATALKAN TRANSAKSI ---
    function cancelTransaction() {
        Swal.fire({
            title: '<span class="text-zinc-900 font-bold">Batalkan Transaksi?</span>',
            text: "Semua data yang sudah diisi akan hilang.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48', // Red
            cancelButtonColor: '#e4e4e7', // Zinc
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: '<span class="text-zinc-600 font-bold">Lanjut Mengisi</span>',
            customClass: {
                popup: 'rounded-2xl p-6',
                confirmButton: 'rounded-lg px-6 py-2.5 font-bold',
                cancelButton: 'rounded-lg px-6 py-2.5 hover:bg-zinc-100'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Refresh halaman untuk reset form
                window.location.reload();
            }
        });
    }
</script>
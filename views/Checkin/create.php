<div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900">Check-in</h1>
        <p class="text-zinc-500 mt-1 text-sm">Registrasi tamu dan penetapan kamar.</p>
    </div>
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
                    <button type="button" onclick="openGuestModal()" class="text-xs bg-zinc-900 text-white px-3 py-1.5 rounded-lg font-medium hover:bg-zinc-700 shadow-sm">
                        Cari / Tambah Tamu
                    </button>
                </div>
                <div class="p-6">
                    <div id="emptyGuestState" class="text-center py-8 border-2 border-dashed border-zinc-200 rounded-xl hover:border-blue-400 transition-colors cursor-pointer group" onclick="openGuestModal()">
                        <div class="w-14 h-14 bg-zinc-50 rounded-full flex items-center justify-center mx-auto mb-3 text-zinc-400 group-hover:text-blue-400 group-hover:scale-110 transition-transform">
                            <i data-lucide="user-plus" class="w-7 h-7"></i>
                        </div>
                        <p class="text-zinc-500 text-sm font-medium group-hover:text-blue-400">Belum ada tamu dipilih.</p>
                    </div>

                    <div id="selectedGuestCard" class="hidden flex items-center gap-5 bg-blue-50 border border-blue-100 p-5 rounded-xl shadow-sm">
                        <div class="w-14 h-14 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-xl shadow-md" id="guestAvatar">A</div>
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
                        <button type="button" onclick="resetGuest()" class="p-2 hover:bg-white rounded-full text-blue-400 hover:text-rose-500 transition-all">
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
                                <i data-lucide="door-open" class="w-5 h-5 text-zinc-400"></i> -- Klik untuk Pilih Kamar --
                            </span>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-zinc-400"></i>
                        </button>
                        <div id="roomListDisplay" class="mt-3 space-y-2"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-zinc-500 uppercase tracking-wide">Check-in</label>
                            <input type="datetime-local" name="tgl" value="<?= date('Y-m-d\TH:i') ?>" class="w-full rounded-xl border-zinc-200 text-sm py-2.5 font-medium">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-zinc-500 uppercase tracking-wide">Durasi (Malam)</label>
                            <div class="flex items-center">
                                <button type="button" onclick="adjustDuration(-1)" class="bg-zinc-100 border border-zinc-300 rounded-l-xl p-2.5"><i data-lucide="minus" class="w-4 h-4"></i></button>
                                <input type="number" name="durasi" id="durasiInput" value="1" min="1" class="w-full text-center border-y border-zinc-200 py-2.5 text-sm font-bold">
                                <button type="button" onclick="adjustDuration(1)" class="bg-zinc-100 border border-zinc-300 rounded-r-xl p-2.5"><i data-lucide="plus" class="w-4 h-4"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="sticky top-6">
                <div class="bg-zinc-900 text-white rounded-2xl shadow-xl overflow-hidden">
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
                        <button type="submit" id="confirmBtn" disabled class="w-full py-3.5 rounded-xl font-bold text-sm flex justify-center items-center gap-2 bg-zinc-700 text-zinc-500 cursor-not-allowed">
                            <i data-lucide="check-circle" class="w-5 h-5"></i> Proses Check-in
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div id="guestModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-900/60 backdrop-blur-sm">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl flex flex-col max-h-[85vh] overflow-hidden">
        <div class="grid grid-cols-2 text-center border-b border-zinc-100 bg-zinc-50/50">
            <button onclick="switchTab('search')" id="tabSearch" class="py-4 text-sm font-bold text-zinc-900 border-b-2 border-zinc-900">Cari Database</button>
            <button onclick="switchTab('add')" id="tabAdd" class="py-4 text-sm font-medium text-zinc-400">Tambah Baru</button>
        </div>

        <div id="viewSearch" class="flex-1 flex flex-col overflow-hidden h-full">
            <div class="p-4 border-b border-zinc-100">
                <input type="text" id="inputSearchGuest" placeholder="Cari Nama atau NIK..." class="w-full px-4 py-2.5 rounded-xl border-zinc-200 text-sm focus:ring-zinc-900">
            </div>
            <div class="flex-1 overflow-y-auto p-2 space-y-1 custom-scrollbar" id="guestListContainer"></div>
            <div class="p-4 border-t border-zinc-100">
                <button onclick="closeGuestModal()" class="w-full py-3 bg-rose-600 text-white font-bold rounded-xl hover:bg-rose-700 text-sm">Batal</button>
            </div>
        </div>

        <div id="viewAdd" class="hidden flex-1 overflow-y-auto p-6">
            <form id="formAddGuest" onsubmit="submitNewGuest(event)" enctype="multipart/form-data" class="space-y-4">
                
                <div class="bg-yellow-50 p-3 rounded-xl border border-yellow-200">
                    <label class="block text-xs font-bold uppercase text-yellow-700 mb-1">Foto Identitas (KTP/Passport)</label>
                    <input type="file" id="newFoto" accept="image/*" class="w-full text-sm text-yellow-800">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold uppercase text-zinc-500 mb-1 ml-1">Nama Lengkap</label>
                        <input type="text" id="newNama" required class="w-full px-4 py-2 rounded-xl border border-zinc-200 text-sm" placeholder="Sesuai KTP">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-500 mb-1 ml-1">Gender</label>
                        <select id="newGender" class="w-full px-3 py-2 rounded-xl border border-zinc-200 text-sm bg-white">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-500 mb-1 ml-1">Tanggal Lahir</label>
                        <input type="date" id="newTglLahir" required class="w-full px-4 py-2 rounded-xl border border-zinc-200 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-500 mb-1 ml-1">Jenis ID</label>
                        <select id="newJenis" class="w-full px-3 py-2 rounded-xl border border-zinc-200 text-sm bg-white">
                            <option value="KTP">KTP</option>
                            <option value="PASSPORT">Passport</option>
                            <option value="SIM">SIM</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-bold uppercase text-zinc-500 mb-1 ml-1">Nomor Identitas</label>
                        <input type="text" id="newNik" required class="w-full px-4 py-2 rounded-xl border border-zinc-200 text-sm" placeholder="NIK / No Paspor">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-500 mb-1 ml-1">No. HP</label>
                        <input type="text" id="newHp" required class="w-full px-4 py-2 rounded-xl border border-zinc-200 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-500 mb-1 ml-1">Email</label>
                        <input type="email" id="newEmail" class="w-full px-4 py-2 rounded-xl border border-zinc-200 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-zinc-500 mb-1 ml-1">Alamat Lengkap</label>
                    <textarea id="newAlamat" rows="2" required class="w-full px-4 py-2 rounded-xl border border-zinc-200 text-sm"></textarea>
                </div>

                <div class="pt-2 flex gap-3">
                    <button type="button" onclick="closeGuestModal()" class="flex-1 py-3 bg-zinc-100 text-zinc-600 font-bold rounded-xl text-sm">Batal</button>
                    <button type="submit" class="flex-[2] py-3 bg-zinc-900 text-white font-bold rounded-xl shadow-lg text-sm">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="roomModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-900/60 backdrop-blur-sm">
    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl flex flex-col max-h-[85vh] overflow-hidden">
        <div class="p-4 border-b flex justify-between items-center bg-zinc-50">
            <h3 class="font-bold text-zinc-900">Pilih Kamar Tersedia</h3>
            <button onclick="document.getElementById('roomModal').classList.add('hidden')"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <div class="p-4 border-b border-zinc-100 flex items-center gap-3 bg-white">
            <select id="filterRoomType" class="w-1/3 py-2.5 rounded-xl border border-zinc-200 text-sm">
                <option value="">Semua Tipe</option>
                <?php foreach($tipe as $t): ?>
                    <option value="<?= $t['id_tipe'] ?>"><?= $t['nama_tipe'] ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" id="searchRoomNo" placeholder="Cari nomor..." class="flex-1 py-2.5 rounded-xl border border-zinc-200 text-sm">
        </div>
        <div class="p-4 overflow-y-auto custom-scrollbar bg-zinc-50/50 flex-1">
            <div id="modalRoomGrid" class="grid grid-cols-2 md:grid-cols-3 gap-3"></div>
        </div>
        <div class="p-4 border-t border-zinc-100 bg-white text-right">
             <button onclick="document.getElementById('roomModal').classList.add('hidden')" class="px-5 py-2.5 bg-zinc-100 font-bold rounded-xl text-sm">Tutup</button>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
    const allGuests = <?= json_encode($tamu) ?>;
    const allRooms = <?= json_encode($kamar) ?>;
    let selectedRooms = [];

    // --- LOGIC TAMU ---
    function renderGuestList(filterText = '') {
        const container = document.getElementById('guestListContainer');
        container.innerHTML = '';
        const filtered = allGuests.filter(g => (g.nama_tamu + ' ' + g.no_identitas).toLowerCase().includes(filterText.toLowerCase()));

        if (filtered.length === 0) {
            container.innerHTML = `<p class="text-center py-4 text-xs text-zinc-400">Tidak ditemukan.</p>`; return;
        }

        filtered.forEach(t => {
            const div = document.createElement('div');
            div.className = "flex justify-between items-center p-3 rounded-lg hover:bg-zinc-50 cursor-pointer border-b border-zinc-50";
            div.onclick = () => selectGuest(t);
            div.innerHTML = `<div><p class="font-bold text-sm">${t.nama_tamu}</p><p class="text-xs text-zinc-500">${t.no_identitas}</p></div>`;
            container.appendChild(div);
        });
    }

    function openGuestModal() { document.getElementById('guestModal').classList.remove('hidden'); renderGuestList(); }
    function closeGuestModal() { document.getElementById('guestModal').classList.add('hidden'); }
    function switchTab(tab) {
        if(tab === 'search') {
            document.getElementById('viewSearch').classList.remove('hidden');
            document.getElementById('viewAdd').classList.add('hidden');
            document.getElementById('tabSearch').className = "py-4 text-sm font-bold text-zinc-900 border-b-2 border-zinc-900";
            document.getElementById('tabAdd').className = "py-4 text-sm font-medium text-zinc-400";
        } else {
            document.getElementById('viewSearch').classList.add('hidden');
            document.getElementById('viewAdd').classList.remove('hidden');
            document.getElementById('tabAdd').className = "py-4 text-sm font-bold text-zinc-900 border-b-2 border-zinc-900";
            document.getElementById('tabSearch').className = "py-4 text-sm font-medium text-zinc-400";
        }
    }
    document.getElementById('inputSearchGuest').addEventListener('keyup', (e) => renderGuestList(e.target.value));

    function selectGuest(tamu) {
        document.getElementById('selectedGuestId').value = tamu.id_tamu;
        document.getElementById('emptyGuestState').classList.add('hidden');
        document.getElementById('selectedGuestCard').classList.remove('hidden');
        document.getElementById('selectedGuestCard').classList.add('flex');
        document.getElementById('guestNameDisplay').innerText = tamu.nama_tamu;
        document.getElementById('guestNikDisplay').innerText = tamu.no_identitas;
        document.getElementById('guestHpDisplay').innerText = tamu.no_hp;
        document.getElementById('guestAvatar').innerText = tamu.nama_tamu.charAt(0).toUpperCase();
        closeGuestModal(); validateForm();
    }
    function resetGuest() {
        document.getElementById('selectedGuestId').value = '';
        document.getElementById('emptyGuestState').classList.remove('hidden');
        document.getElementById('selectedGuestCard').classList.add('hidden');
        document.getElementById('selectedGuestCard').classList.remove('flex');
        validateForm();
    }

    // --- SUBMIT TAMU BARU (DENGAN FOTO) ---
    function submitNewGuest(e) {
        e.preventDefault();
        const btn = e.target.querySelector('button[type="submit"]');
        btn.innerHTML = "Menyimpan..."; btn.disabled = true;

        const formData = new FormData();
        formData.append('nama', document.getElementById('newNama').value);
        formData.append('gender', document.getElementById('newGender').value);
        formData.append('tgl_lahir', document.getElementById('newTglLahir').value);
        formData.append('jenis', document.getElementById('newJenis').value);
        formData.append('nik', document.getElementById('newNik').value);
        formData.append('hp', document.getElementById('newHp').value);
        formData.append('email', document.getElementById('newEmail').value);
        formData.append('alamat', document.getElementById('newAlamat').value);
        
        const fileInput = document.getElementById('newFoto');
        if(fileInput.files[0]) formData.append('foto', fileInput.files[0]);

        fetch('index.php?modul=Checkin&aksi=ajaxAddGuest', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success') {
                allGuests.push(res.data);
                selectGuest(res.data);
                document.getElementById('formAddGuest').reset();
                switchTab('search'); 
            } else { alert(res.message); }
        })
        .catch(err => alert("Error koneksi."))
        .finally(() => { btn.innerHTML = "Simpan Data"; btn.disabled = false; });
    }

    // --- LOGIC KAMAR ---
    function openRoomModal() { document.getElementById('roomModal').classList.remove('hidden'); renderModalRooms(); }
    function renderModalRooms() {
        const container = document.getElementById('modalRoomGrid');
        const typeFilter = document.getElementById('filterRoomType').value;
        const searchFilter = document.getElementById('searchRoomNo').value.toLowerCase();
        
        const filteredRooms = allRooms.filter(room => {
            const matchType = typeFilter === '' || room.id_tipe == typeFilter;
            const matchSearch = room.nomor_kamar.toLowerCase().includes(searchFilter);
            return matchType && matchSearch;
        });

        container.innerHTML = '';
        filteredRooms.forEach(k => {
            const isSelected = selectedRooms.some(r => r.id_kamar == k.id_kamar);
            const activeClass = isSelected ? 'ring-2 ring-blue-500 bg-blue-50 border-blue-200' : 'bg-white border-zinc-200';
            const div = document.createElement('div');
            div.className = `border p-3 rounded-xl cursor-pointer text-center relative ${activeClass}`;
            div.onclick = () => toggleRoom(k);
            div.innerHTML = `<div class="font-black text-xl">${k.nomor_kamar}</div><div class="text-[10px] uppercase font-bold text-emerald-600">Rp ${(k.harga_dasar/1000).toLocaleString()}k</div>`;
            container.appendChild(div);
        });
    }

    function toggleRoom(kamar) {
        const idx = selectedRooms.findIndex(r => r.id_kamar == kamar.id_kamar);
        if (idx > -1) selectedRooms.splice(idx, 1); else selectedRooms.push(kamar);
        renderModalRooms(); renderSelectedRooms();
    }

    function renderSelectedRooms() {
        const container = document.getElementById('roomListDisplay');
        const summary = document.getElementById('summaryItems');
        const durasi = parseInt(document.getElementById('durasiInput').value) || 1;
        let total = 0; container.innerHTML = ''; summary.innerHTML = '';

        if (selectedRooms.length > 0) {
            document.getElementById('selectedRoomInfo').innerHTML = `<span class="text-zinc-900 font-bold">${selectedRooms.length} Kamar Dipilih</span>`;
            selectedRooms.forEach(room => {
                container.innerHTML += `<div class="flex justify-between items-center bg-zinc-50 p-2 rounded-lg text-sm"><span>Room ${room.nomor_kamar}</span><button onclick="toggleRoom({id_kamar:${room.id_kamar}})" class="text-rose-500"><i data-lucide="trash-2" class="w-4 h-4"></i></button></div>`;
                let subtotal = room.harga_dasar * durasi;
                total += subtotal;
                summary.innerHTML += `<div class="flex justify-between"><span class="text-zinc-400">R.${room.nomor_kamar} (${durasi}x)</span><span class="text-white">Rp ${subtotal.toLocaleString()}</span></div>`;
            });
        } else {
            document.getElementById('selectedRoomInfo').innerHTML = `<span class="text-zinc-500">-- Klik untuk Pilih Kamar --</span>`;
            summary.innerHTML = `<span class="text-zinc-500 italic text-xs">Belum ada kamar.</span>`;
        }
        document.getElementById('selectedRoomId').value = selectedRooms.map(r => r.id_kamar).join(',');
        document.getElementById('summaryTotal').innerText = 'Rp ' + total.toLocaleString();
        lucide.createIcons(); validateForm();
    }

    document.getElementById('filterRoomType').addEventListener('change', renderModalRooms);
    document.getElementById('searchRoomNo').addEventListener('keyup', renderModalRooms);
    document.getElementById('durasiInput').addEventListener('input', renderSelectedRooms);
    function adjustDuration(n) {
        let val = parseInt(document.getElementById('durasiInput').value) || 1;
        document.getElementById('durasiInput').value = Math.max(1, val + n); renderSelectedRooms();
    }

    function validateForm() {
        const ok = document.getElementById('selectedGuestId').value && document.getElementById('selectedRoomId').value;
        const btn = document.getElementById('confirmBtn');
        btn.disabled = !ok;
        btn.className = ok ? "w-full py-3.5 bg-white border border-zinc-200 text-zinc-900 rounded-xl font-bold hover:bg-zinc-50" : "w-full py-3.5 bg-zinc-700 text-zinc-500 cursor-not-allowed";
    }
</script>
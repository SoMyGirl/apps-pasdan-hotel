<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900">Dashboard</h1>
        <p class="text-zinc-500 mt-1 text-sm">Overview operasional hotel hari ini.</p>
    </div>
    <div class="bg-white border border-zinc-200 px-4 py-2 rounded-lg shadow-sm flex items-center gap-3">
        <div class="bg-zinc-100 p-1.5 rounded-md">
            <i data-lucide="calendar" class="w-4 h-4 text-zinc-600"></i>
        </div>
        <span class="text-sm font-medium text-zinc-700">
            <?= date('l, d F Y') ?>
        </span>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white p-6 rounded-xl border border-zinc-200 shadow-sm hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium text-zinc-500">Total Asset</span>
            <i data-lucide="building-2" class="w-4 h-4 text-zinc-400"></i>
        </div>
        <div class="flex items-end gap-2">
            <h3 class="text-3xl font-bold text-zinc-900 tracking-tight"><?= $data['stats']['total'] ?></h3>
            <span class="text-sm text-zinc-500 mb-1">Kamar</span>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl border border-zinc-200 shadow-sm hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium text-zinc-500">Occupancy Rate</span>
            <i data-lucide="pie-chart" class="w-4 h-4 text-zinc-400"></i>
        </div>
        <div class="flex items-end gap-2">
            <h3 class="text-3xl font-bold text-zinc-900 tracking-tight"><?= $data['stats']['occupancy'] ?>%</h3>
            <span class="text-sm <?= $data['stats']['occupancy'] > 70 ? 'text-emerald-600' : 'text-zinc-500' ?> mb-1">
                <?= $data['stats']['occupancy'] > 70 ? 'High' : 'Normal' ?>
            </span>
        </div>
        <div class="w-full bg-zinc-100 rounded-full h-1.5 mt-3">
            <div class="bg-zinc-900 h-1.5 rounded-full" style="width: <?= $data['stats']['occupancy'] ?>%"></div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl border border-zinc-200 shadow-sm hover:shadow-md transition-shadow duration-200 relative overflow-hidden">
        <div class="absolute right-0 top-0 h-full w-1 bg-emerald-500"></div>
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium text-zinc-500">Available</span>
            <span class="flex h-2 w-2 rounded-full bg-emerald-500"></span>
        </div>
        <h3 class="text-3xl font-bold text-zinc-900 tracking-tight"><?= $data['stats']['available'] ?></h3>
    </div>

    <div class="bg-white p-6 rounded-xl border border-zinc-200 shadow-sm hover:shadow-md transition-shadow duration-200 relative overflow-hidden">
        <div class="absolute right-0 top-0 h-full w-1 bg-amber-500"></div>
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium text-zinc-500">Needs Cleaning</span>
            <span class="flex h-2 w-2 rounded-full bg-amber-500"></span>
        </div>
        <h3 class="text-3xl font-bold text-zinc-900 tracking-tight"><?= $data['stats']['dirty'] ?></h3>
    </div>
</div>

<div class="bg-white border border-zinc-200 rounded-xl shadow-sm overflow-hidden">
    <div class="p-6 border-b border-zinc-100 flex flex-col md:flex-row justify-between items-center gap-4">
        <h2 class="text-lg font-semibold text-zinc-900 flex items-center gap-2">
            <i data-lucide="layout-grid" class="w-5 h-5 text-zinc-500"></i>
            Room Status
        </h2>
        
        <div class="flex gap-2">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                Available
            </span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">
                Occupied
            </span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                Dirty
            </span>
        </div>
    </div>

    <div class="p-6 bg-zinc-50/50">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            <?php foreach($data['rooms'] as $r): ?>
                <?php
                // LOGIC UI BERDASARKAN STATUS
                if ($r['status'] == 'available') {
                    // Modern Clean Look
                    $cardClass = "bg-white ring-1 ring-zinc-200 hover:ring-emerald-400 hover:shadow-md group";
                    $badgeClass = "bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20";
                    $icon = "check-circle-2";
                    $statusLabel = "Available";
                    $subLabel = "Ready to Check-in";
                    $action = "onclick=\"window.location.href='index.php?modul=Checkin&aksi=create&id_kamar={$r['id_kamar']}'\"";
                } elseif ($r['status'] == 'occupied') {
                    // Active Look
                    $cardClass = "bg-white ring-1 ring-rose-200 hover:ring-rose-400 hover:shadow-md group";
                    $badgeClass = "bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-600/20";
                    $icon = "user";
                    $statusLabel = "Occupied";
                    // Potong nama jika terlalu panjang
                    $subLabel = strlen($r['nama_tamu']) > 12 ? substr($r['nama_tamu'], 0, 12) . '..' : $r['nama_tamu'];
                    $action = "onclick=\"openRoomModal('{$r['nomor_kamar']}', '{$r['nama_tamu']}', '{$r['id_transaksi']}', '{$r['nama_tipe']}')\"";
                } else {
                    // Warning Look
                    $cardClass = "bg-zinc-50 ring-1 ring-amber-200 hover:ring-amber-400 hover:bg-white hover:shadow-md group";
                    $badgeClass = "bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20";
                    $icon = "spray-can";
                    $statusLabel = "Dirty";
                    $subLabel = "Needs Housekeeping";
                    $action = "onclick=\"confirmClean('{$r['id_kamar']}', '{$r['nomor_kamar']}')\"";
                }
                ?>

                <div <?= $action ?> class="relative rounded-xl p-4 flex flex-col justify-between h-36 cursor-pointer transition-all duration-200 <?= $cardClass ?>">
                    <div class="flex justify-between items-start">
                        <span class="text-2xl font-bold text-zinc-900 tracking-tight group-hover:scale-105 transition-transform">
                            <?= $r['nomor_kamar'] ?>
                        </span>
                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium <?= $badgeClass ?>">
                            <?= $statusLabel ?>
                        </span>
                    </div>

                    <div>
                        <p class="text-[10px] uppercase font-bold text-zinc-400 tracking-wider">
                            <?= $r['nama_tipe'] ?>
                        </p>
                    </div>

                    <div class="flex items-center gap-2 mt-2 pt-3 border-t border-zinc-100/50">
                        <i data-lucide="<?= $icon ?>" class="w-4 h-4 text-zinc-400 group-hover:text-zinc-600"></i>
                        <span class="text-xs font-medium text-zinc-600 truncate">
                            <?= $subLabel ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div id="roomModal" class="hidden fixed inset-0 z-50">
    <div class="absolute inset-0 bg-zinc-900/40 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop"></div>
    
    <div class="flex min-h-full items-center justify-center p-4 text-center">
        <div id="modalContent" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg scale-95 opacity-0">
            
            <div class="bg-zinc-50 px-6 py-4 border-b border-zinc-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-zinc-900" id="modalKamar">Room 101</h3>
                    <p class="text-xs text-zinc-500 font-medium uppercase tracking-wide" id="modalTipe">Deluxe Room</p>
                </div>
                <button onclick="closeModal()" class="rounded-full p-1 hover:bg-zinc-200 transition-colors">
                    <i data-lucide="x" class="w-5 h-5 text-zinc-500"></i>
                </button>
            </div>

            <div class="px-6 py-6">
                <div class="flex items-center gap-4 mb-6 bg-white border border-zinc-100 p-3 rounded-xl shadow-sm">
                    <div class="h-12 w-12 rounded-full bg-zinc-900 flex items-center justify-center text-white">
                        <i data-lucide="user" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-xs text-zinc-400 font-bold uppercase">Current Guest</p>
                        <h4 class="text-lg font-bold text-zinc-900" id="modalTamu">Guest Name</h4>
                    </div>
                    <div class="ml-auto">
                        <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Active</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <a id="btnPOS" href="#" class="group relative flex flex-col items-center justify-center gap-2 rounded-xl border border-zinc-200 bg-white p-4 text-sm font-medium text-zinc-900 hover:bg-zinc-50 hover:border-zinc-300 transition-all">
                        <div class="rounded-full bg-zinc-100 p-2 group-hover:bg-white ring-1 ring-zinc-200">
                            <i data-lucide="utensils" class="w-5 h-5 text-zinc-600"></i>
                        </div>
                        Layanan / POS
                    </a>
                    <a id="btnDetail" href="#" class="group relative flex flex-col items-center justify-center gap-2 rounded-xl border border-zinc-200 bg-white p-4 text-sm font-medium text-zinc-900 hover:bg-zinc-50 hover:border-zinc-300 transition-all">
                        <div class="rounded-full bg-zinc-100 p-2 group-hover:bg-white ring-1 ring-zinc-200">
                            <i data-lucide="receipt" class="w-5 h-5 text-zinc-600"></i>
                        </div>
                        Detail Tagihan
                    </a>
                </div>
            </div>

            <div class="bg-zinc-50 px-6 py-4 flex flex-row-reverse">
                <a id="btnCheckout" href="#" class="inline-flex w-full justify-center rounded-lg bg-zinc-900 px-3 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 transition-all sm:w-auto sm:ml-3">
                    <i data-lucide="log-out" class="w-4 h-4 mr-2"></i> Proses Checkout
                </a>
                <button type="button" onclick="closeModal()" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2.5 text-sm font-semibold text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 sm:mt-0 sm:w-auto">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize Lucide Icons
    lucide.createIcons();

    const modal = document.getElementById('roomModal');
    const modalBackdrop = document.getElementById('modalBackdrop');
    const modalContent = document.getElementById('modalContent');

    function openRoomModal(no, nama, id, tipe) {
        // Set Content
        document.getElementById('modalKamar').innerText = "Room " + no;
        document.getElementById('modalTamu').innerText = nama;
        document.getElementById('modalTipe').innerText = tipe;
        
        let link = "index.php?modul=Checkout&aksi=payment&id=" + id;
        document.getElementById('btnDetail').href = link;
        document.getElementById('btnPOS').href = "index.php?modul=POS&aksi=index&id_transaksi=" + id; // Asumsi POS butuh ID Transaksi
        document.getElementById('btnCheckout').href = link;

        // Show Logic (Animation)
        modal.classList.remove('hidden');
        // Trigger reflow
        void modal.offsetWidth; 
        
        modalBackdrop.classList.remove('opacity-0');
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }

    function closeModal() {
        // Hide Animation
        modalBackdrop.classList.add('opacity-0');
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200); // Sesuaikan dengan duration transition CSS (default Tailwind ~150-300ms)
    }

    // Close on click outside
    modalBackdrop.addEventListener('click', closeModal);

    function confirmClean(id, no) {
        // SweetAlert2 Modern Styling
        Swal.fire({
            title: `<span class="text-xl font-bold text-zinc-900">Room ${no} Cleaned?</span>`,
            text: "Kamar akan diubah statusnya menjadi Available.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#18181b', // Zinc-900
            cancelButtonColor: '#e4e4e7', // Zinc-200
            confirmButtonText: 'Yes, Confirm',
            cancelButtonText: '<span class="text-zinc-600">Cancel</span>',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-lg',
                cancelButton: 'rounded-lg'
            }
        }).then((result) => {
            if(result.isConfirmed) window.location.href = "index.php?modul=Dashboard&aksi=clean&id=" + id;
        })
    }
</script>
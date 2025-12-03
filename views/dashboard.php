<?php
include_once 'controllers/C_Dashboard.php';
$dash = new C_Dashboard();
$stats = $dash->getStats();
?>

<div class="mb-8">
    <h2 class="text-3xl font-bold tracking-tight text-zinc-900">Dashboard</h2>
    <p class="text-zinc-500 mt-1">Ringkasan operasional hotel hari ini.</p>
</div>

<div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4 mb-8">
    <div class="rounded-xl border border-zinc-200 bg-white text-zinc-950 shadow-sm">
        <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
            <h3 class="tracking-tight text-sm font-medium text-zinc-500">Kamar Tersedia</h3>
            <i data-lucide="door-open" class="h-4 w-4 text-zinc-500"></i>
        </div>
        <div class="p-6 pt-0">
            <div class="text-2xl font-bold"><?= $stats['kosong'] ?></div>
            <p class="text-xs text-zinc-500 mt-1">Siap untuk check-in</p>
        </div>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white text-zinc-950 shadow-sm">
        <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
            <h3 class="tracking-tight text-sm font-medium text-zinc-500">Kamar Terisi</h3>
            <i data-lucide="users" class="h-4 w-4 text-zinc-500"></i>
        </div>
        <div class="p-6 pt-0">
            <div class="text-2xl font-bold"><?= $stats['isi'] ?></div>
            <p class="text-xs text-zinc-500 mt-1">Tamu in-house</p>
        </div>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white text-zinc-950 shadow-sm">
        <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
            <h3 class="tracking-tight text-sm font-medium text-zinc-500">Kamar Kotor</h3>
            <i data-lucide="spray-can" class="h-4 w-4 text-orange-500"></i>
        </div>
        <div class="p-6 pt-0">
            <div class="text-2xl font-bold text-orange-600"><?= $stats['kotor'] ?></div>
            <p class="text-xs text-zinc-500 mt-1">Perlu housekeeping</p>
        </div>
    </div>
</div>

<div class="rounded-lg border border-blue-200 bg-blue-50 p-4 text-sm text-blue-900 flex items-center gap-3">
    <i data-lucide="info" class="h-4 w-4"></i>
    <span>Selamat Datang, <b><?= $_SESSION['nama'] ?></b>. Pastikan mengecek kamar kotor sebelum check-in tamu baru.</span>
</div>
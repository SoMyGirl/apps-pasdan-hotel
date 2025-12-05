<?php
// Pastikan tidak ada spasi sebelum tag PHP
ob_start(); 

include_once 'controllers/C_Checkin.php';
$checkinCtrl = new C_Checkin();

// ----------------------
// 1. Handle Tambah Checkin (POST)
// ----------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_simpan'])) {
    $nama = $_POST['nama'] ?? '';
    $hp = $_POST['hp'] ?? '';
    $identitas = $_POST['identitas'] ?? '';
    $id_kamar = intval($_POST['id_kamar'] ?? 0);
    $durasi_malam = intval($_POST['durasi_malam'] ?? 1);

    $checkinCtrl->prosesCheckin($nama, $hp, $identitas, $id_kamar, $durasi_malam);
    echo "<script>window.location='index.php?page=checkin';</script>";
    exit;
}

// ----------------------
// 2. Handle Tombol BAYAR (Checkout)
// ----------------------
if (isset($_GET['aksi']) && $_GET['aksi'] == 'checkout') {
    $id_trx = intval($_GET['id']);
    
    // Panggil fungsi checkout di controller
    if($checkinCtrl->checkoutTamu($id_trx)) {
        echo "<script>
            alert('Pembayaran Lunas & Tamu berhasil Check-out.');
            window.location='index.php?page=checkin';
        </script>";
    } else {
        echo "<script>alert('Gagal memproses data.'); window.location='index.php?page=checkin';</script>";
    }
    exit;


}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_update'])) {
    $id_transaksi = $_POST['id_transaksi'] ?? 0;
    $nama         = $_POST['nama'] ?? '';
    $hp           = $_POST['hp'] ?? '';
    $identitas    = $_POST['identitas'] ?? '';
    $durasi       = $_POST['durasi_malam'] ?? 1;

    if ($checkinCtrl->updateCheckin($id_transaksi, $nama, $hp, $identitas, $durasi)) {
        echo "<script>alert('Data berhasil diperbarui.'); window.location='index.php?page=checkin';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data.'); window.location='index.php?page=checkin';</script>";
    }
    exit;
}

// ----------------------
// 2a. Handle Pembayaran DP / Lunas
// ----------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_bayar'])) {
    $id_transaksi = intval($_POST['id_transaksi'] ?? 0);
    $status_bayar = $_POST['status_bayar'] ?? 'belum_bayar';
    $jumlah_bayar = $_POST['jumlah_bayar'] ?? null;

    if ($checkinCtrl->updateStatusBayar($id_transaksi, $status_bayar, $jumlah_bayar)) {
        echo "<script>alert('Status pembayaran berhasil diperbarui.'); window.location='index.php?page=checkin';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui status pembayaran.'); window.location='index.php?page=checkin';</script>";
    }
    exit;
}

// ----------------------
// Ambil data untuk view
// ----------------------
$kamarTersedia = $checkinCtrl->getKamarAvailable();
$dataCheckin  = $checkinCtrl->getTamuAktif();


?>

<!-- =======================
HTML VIEW
======================= -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900">Check-in Tamu</h1>
        <p class="text-zinc-500 mt-1">Kelola tamu yang sedang menginap (Active Guest).</p>
    </div>
    <button onclick="toggleModal('modalCheckin')" class="inline-flex items-center justify-center rounded-lg bg-zinc-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 transition-all focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:ring-offset-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        Check-in Baru
    </button>
</div>

<div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm mt-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-zinc-600">
            <thead class="bg-zinc-50 text-xs uppercase font-semibold text-zinc-500 border-b border-zinc-100">
                <tr>
                    <th class="px-6 py-4">Invoice</th>
                    <th class="px-6 py-4">Tamu</th>
                    <th class="px-6 py-4">Kamar</th>
                    <th class="px-6 py-4">Check-in</th>
                    <th class="px-6 py-4">Tagihan</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100">
                <?php if (!empty($dataCheckin)): ?>
                    <?php foreach($dataCheckin as $row): ?>
                    <tr class="hover:bg-zinc-50/60 transition-colors">
                        <td class="px-6 py-4 font-mono text-zinc-900 font-medium"><?= htmlspecialchars($row['no_invoice']) ?></td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-zinc-900"><?= htmlspecialchars($row['nama_tamu']) ?></div>
                            <div class="text-xs text-zinc-400"><?= htmlspecialchars($row['no_hp']) ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                <?= htmlspecialchars($row['nomor_kamar']) ?> (<?= htmlspecialchars($row['nama_tipe']) ?>)
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-zinc-900"><?= date('d M Y', strtotime($row['tgl_checkin'])) ?></div>
                            <div class="text-xs text-zinc-400"><?= date('H:i', strtotime($row['tgl_checkin'])) ?> WIB</div>
                        </td>
                        <td class="px-6 py-4 font-medium text-zinc-900">Rp <?= number_format(floatval($row['total_tagihan']),0,',','.') ?></td>
                        
                        <!-- KOLOM AKSI (Hanya Bayar & Edit) -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                
                                <!-- 1. Tombol EDIT -->
                              <button 
    class="editBtn inline-flex items-center justify-center rounded-md bg-amber-50 px-3 py-1.5 text-xs font-medium text-amber-700 border border-amber-200 hover:bg-amber-100 transition-colors"
    data-id="<?= $row['id_transaksi'] ?>"
    data-nama="<?= htmlspecialchars($row['nama_tamu']) ?>"
    data-hp="<?= htmlspecialchars($row['no_hp']) ?>"
    data-identitas="<?= htmlspecialchars($row['no_identitas']) ?>"
    data-durasi="<?= $row['durasi_malam'] ?>"
    data-status="<?= $row['status_inap'] ?>"
>
    Edit
</button>


                                <!-- 2. Tombol BAYAR (Checkout) -->
                             <?php 
            // Ambil status bayar, default ke 'belum_bayar' jika null
            $statusBayar = $row['status_bayar'] ?? 'belum_bayar'; 
        ?>

        <?php if ($statusBayar == 'lunas'): ?>
            
            <div class="inline-flex items-center rounded-md bg-green-50 px-2.5 py-1.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 cursor-default">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Lunas
            </div>

        <?php elseif ($statusBayar == 'dp'): ?>

            <button 
                class="btnBayar inline-flex items-center justify-center rounded-md bg-orange-500 px-3 py-1.5 text-xs font-medium text-white shadow-sm hover:bg-orange-600 transition-colors"
                data-id="<?= $row['id_transaksi'] ?>"
                data-status="dp"
                title="Lunasi Sisa Tagihan"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-1.5 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Pelunasan
            </button>

        <?php else: ?>

            <button 
                class="btnBayar inline-flex items-center justify-center rounded-md bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors"
                data-id="<?= $row['id_transaksi'] ?>"
                data-status="belum"
                title="Proses Pembayaran"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-1.5 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Bayar
            </button>

        <?php endif; ?>

                            </div>
                        </td>
                        <!-- End Kolom Aksi -->

                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-zinc-400 italic">Belum ada data check-in aktif.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- =======================
Modal Check-in Baru (SAMA SEPERTI SEBELUMNYA)
======================= -->
<div id="modalCheckin" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity" onclick="toggleModal('modalCheckin')"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-zinc-200">
                <div class="bg-zinc-50 px-4 py-4 border-b border-zinc-100 flex justify-between items-center">
                    <h3 class="text-base font-semibold leading-6 text-zinc-900">Form Check-in Baru</h3>
                    <button onclick="toggleModal('modalCheckin')" class="text-zinc-400 hover:text-zinc-600 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form action="" method="POST" class="p-6 space-y-5">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 mb-1">Nama Tamu</label>
                            <input type="text" name="nama" required placeholder="Sesuai KTP" class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm placeholder-zinc-400 shadow-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 mb-1">No. HP</label>
                                <input type="text" name="hp" required class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm shadow-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 mb-1">No. Identitas (KTP)</label>
                                <input type="text" name="identitas" required class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm shadow-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                            </div>
                        </div>
                    </div>

                    <hr class="border-zinc-100">

                    <div class="space-y-4">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-zinc-700 mb-1">Pilih Kamar</label>
                            <select name="id_kamar" id="pilihKamar" onchange="hitungLive()" class="w-full border rounded px-3 py-2">
                                <option value="" data-harga="0" selected>-- Pilih Kamar --</option>
                                <?php foreach($kamarTersedia as $tipe => $listKamar): ?>
                                    <optgroup label="<?= $tipe ?>">
                                        <?php foreach($listKamar as $kmr): ?>
                                            <option value="<?= $kmr['id_kamar'] ?>" data-harga="<?= $kmr['harga_dasar'] ?>">
                                                <?= $kmr['nomor_kamar'] ?> - Rp <?= number_format($kmr['harga_dasar'],0,',','.') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-zinc-700 mb-1">Durasi (Malam)</label>
                            <input type="number" name="durasi_malam" id="durasiMalam" value="1" min="1" oninput="hitungLive()" class="w-full border rounded px-3 py-2">
                        </div>
                        
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 flex justify-between items-center">
                            <span class="text-blue-800 font-medium">Total Estimasi:</span>
                            <span id="labelTotal" class="text-xl font-bold text-blue-900">Rp 0</span>
                        </div>
                    </div>

                    <div class="pt-4 mt-2 flex gap-3">
                        <button type="button" onclick="toggleModal('modalCheckin')" class="flex-1 rounded-md border border-zinc-300 bg-white px-3 py-2.5 text-sm font-semibold text-zinc-700 hover:bg-zinc-50 transition-colors">Batal</button>
                        <button type="submit" name="btn_simpan" class="flex-1 rounded-md bg-zinc-900 px-3 py-2.5 text-sm font-semibold text-white hover:bg-zinc-800 shadow-sm transition-colors">Proses Check-in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="modalBayar" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity" onclick="toggleModal('modalBayar')"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 relative z-10">
        
        <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-zinc-200" onclick="event.stopPropagation()">
            
            <div class="bg-zinc-50 px-4 py-3 border-b border-zinc-100 flex justify-between items-center">
                <h3 class="text-base font-semibold leading-6 text-zinc-900">Pembayaran</h3>
                <button type="button" onclick="toggleModal('modalBayar')" class="text-zinc-400 hover:text-zinc-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <form id="formBayar" method="POST">
                    <input type="hidden" name="id_transaksi" id="bayarIdTransaksi">

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-zinc-700 mb-2">Metode Pembayaran</label>
                        <div class="flex gap-4">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="status_bayar" value="dp" class="jenisBayarRadio text-zinc-900 focus:ring-zinc-900">
                                <span class="ml-2 text-sm text-zinc-700">DP (Uang Muka)</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="status_bayar" value="lunas" class="jenisBayarRadio text-zinc-900 focus:ring-zinc-900">
                                <span class="ml-2 text-sm text-zinc-700">Bayar Lunas</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-5" id="inputDpContainer" style="display:none;">
                        <label class="block text-sm font-medium text-zinc-700 mb-1">Jumlah DP (Rp)</label>
                        <input type="number" name="jumlah_bayar" class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900" placeholder="Contoh: 500000">
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="button" onclick="toggleModal('modalBayar')" class="flex-1 rounded-md border border-zinc-300 bg-white px-3 py-2 text-sm font-semibold text-zinc-700 hover:bg-zinc-50">Batal</button>
                        <button type="submit" name="update_bayar" class="flex-1 rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-500 shadow-sm">Proses Bayar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="modalEditCheckin" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity" onclick="toggleModal('modalEditCheckin')"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 relative z-10">
        
        <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-zinc-200" onclick="event.stopPropagation()">
            
            <div class="bg-zinc-50 px-5 py-4 border-b border-zinc-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold leading-6 text-zinc-900">Edit Data Tamu</h3>
                    <p class="text-xs text-zinc-500 mt-0.5">Perbarui informasi tamu atau durasi menginap.</p>
                </div>
                <button type="button" onclick="toggleModal('modalEditCheckin')" class="text-zinc-400 hover:text-zinc-600 transition-colors rounded-full p-1 hover:bg-zinc-100">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form action="" method="POST" class="p-6">
                <input type="hidden" name="id_transaksi" id="editIdTransaksi">
                
                <div class="space-y-5">
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-1.5">Nama Lengkap</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="h-4 w-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input type="text" name="nama" id="editNama" class="block w-full rounded-md border-zinc-300 pl-10 focus:border-zinc-900 focus:ring-zinc-900 sm:text-sm py-2" placeholder="Nama sesuai KTP">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-1.5">No. HP / WA</label>
                                <input type="text" name="hp" id="editHp" class="block w-full rounded-md border-zinc-300 focus:border-zinc-900 focus:ring-zinc-900 sm:text-sm py-2">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-1.5">No. Identitas</label>
                                <input type="text" name="identitas" id="editIdentitas" class="block w-full rounded-md border-zinc-300 focus:border-zinc-900 focus:ring-zinc-900 sm:text-sm py-2">
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t border-zinc-200"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="bg-white px-2 text-xs text-zinc-400">Detail Inap</span>
                        </div>
                    </div>

                    <div class="bg-amber-50 rounded-lg p-4 border border-amber-100 flex items-center justify-between">
                        <div>
                            <label class="block text-sm font-medium text-amber-900">Durasi Menginap</label>
                            <p class="text-xs text-amber-700 mt-0.5">Ubah jumlah malam.</p>
                        </div>
                        <div class="flex items-center">
                            <input type="number" name="durasi_malam" id="editDurasi" min="1" class="block w-20 text-center rounded-md border-amber-300 focus:border-amber-500 focus:ring-amber-500 sm:text-sm font-semibold text-amber-900" value="1">
                            <span class="ml-2 text-sm text-amber-800">Malam</span>
                        </div>
                    </div>
                    
                    <input type="hidden" id="editStatus"> 

                </div>

                <div class="mt-8 flex items-center justify-end gap-3 pt-4 border-t border-zinc-100">
                    <button type="button" onclick="toggleModal('modalEditCheckin')" class="rounded-md bg-white px-3.5 py-2 text-sm font-semibold text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" name="btn_update" class="inline-flex items-center rounded-md bg-zinc-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-600 transition-all">
                        <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Edit Check-in -->




<script>
// Fungsi Toggle Modal (Buka/Tutup)
function toggleModal(modalID){
    const modal = document.getElementById(modalID);
    if(modal) modal.classList.toggle("hidden");
}

// Fungsi Hitung Realtime (Form Checkin)
function hitungLive() {
    let elKamar  = document.getElementById('pilihKamar');
    let elDurasi = document.getElementById('durasiMalam');
    let elTotal  = document.getElementById('labelTotal');

    let harga = 0;
    if(elKamar.selectedIndex >= 0) {
        let selectedOption = elKamar.options[elKamar.selectedIndex];
        harga = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
    }

    let durasi = parseInt(elDurasi.value) || 0;
    let total = harga * durasi;

    let formatRupiah = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(total);

    elTotal.innerText = formatRupiah;
}

// Pastikan DOM sudah siap sebelum menjalankan listener
document.addEventListener('DOMContentLoaded', () => {

    // 1. LOGIKA TOMBOL EDIT
    document.querySelectorAll('.editBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id      = this.dataset.id;
            const nama    = this.dataset.nama;
            const hp      = this.dataset.hp;
            const ident   = this.dataset.identitas;
            const durasi  = this.dataset.durasi;
            const status  = this.dataset.status;

            document.getElementById('editIdTransaksi').value = id;
            document.getElementById('editNama').value = nama;
            document.getElementById('editHp').value = hp;
            document.getElementById('editIdentitas').value = ident;
            document.getElementById('editDurasi').value = durasi;
            document.getElementById('editStatus').value = status;

            toggleModal('modalEditCheckin');
        });
    });

    // 2. LOGIKA RADIO BUTTON (Munculkan Input DP)
    document.querySelectorAll('.jenisBayarRadio').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('inputDpContainer').style.display = (this.value === 'dp') ? 'block' : 'none';
        });
    });

    // 3. LOGIKA TOMBOL BAYAR (HANYA SATU BLOK KODE INI SAJA)
    document.querySelectorAll('.btnBayar').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const statusCurrent = this.dataset.status; // Ambil status (dp/belum)

            document.getElementById('bayarIdTransaksi').value = id;
            
            // --- LOGIKA CERDAS ---
            // Jika statusnya 'dp', otomatis pilih radio 'lunas'
            if(statusCurrent === 'dp'){
                const radioLunas = document.querySelector('input[name="status_bayar"][value="lunas"]');
                if(radioLunas) {
                    radioLunas.checked = true;
                    // Trigger event change agar input manual DP tersembunyi
                    radioLunas.dispatchEvent(new Event('change'));
                }
            } else {
                // Reset radio button jika checkin baru/belum bayar
                // Bersihkan pilihan radio
                document.querySelectorAll('input[name="status_bayar"]').forEach(el => el.checked = false);
                // Sembunyikan input DP
                document.getElementById('inputDpContainer').style.display = 'none';
            }
            // ---------------------

            toggleModal('modalBayar');
        });
    });

});
</script>
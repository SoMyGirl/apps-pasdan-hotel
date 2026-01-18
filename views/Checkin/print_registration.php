<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registration Card - Pasundan Hotel</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 20px; }
        .container { width: 100%; max-width: 800px; margin: 0 auto; border: 2px solid #000; }
        .header { text-align: center; padding: 10px; border-bottom: 2px solid #000; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; letter-spacing: 2px; }
        .header h2 { margin: 5px 0; font-size: 14px; font-weight: normal; font-style: italic; }
        .grid-table { width: 100%; border-collapse: collapse; }
        .grid-table td { border: 1px solid #000; padding: 5px; vertical-align: top; }
        .label { font-size: 10px; font-weight: bold; text-transform: uppercase; display: block; margin-bottom: 2px; color: #333; }
        .value { font-size: 13px; font-weight: bold; }
        .terms { font-size: 10px; padding: 10px; text-align: justify; line-height: 1.4; border-bottom: 1px solid #000; }
        .terms ul { margin: 0; padding-left: 15px; }
        .signatures { display: flex; border-top: 1px solid #000; }
        .sig-box { flex: 1; border-right: 1px solid #000; padding: 10px; height: 100px; position: relative; }
        .sig-box:last-child { border-right: none; }
        .sig-line { position: absolute; bottom: 10px; left: 10px; right: 10px; border-bottom: 1px dotted #000; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px;">🖨️ Cetak</button>
        <a href="index.php?modul=Checkin&aksi=create" style="margin-left: 10px;">[ Kembali ]</a>
    </div>

    <div class="container">
        <div class="header">
            <h1>PASUNDAN HOTEL</h1>
            <h2>WELCOME - SELAMAT DATANG</h2>
        </div>
        <table class="grid-table">
            <tr>
                <td width="50%">
                    <span class="label">Nama Tamu</span>
                    <span class="value"><?= strtoupper($trx['nama_tamu']) ?> (<?= $trx['gender'] ?>)</span>
                </td>
                <td width="25%">
                    <span class="label">Check-In</span>
                    <span class="value"><?= date('d M Y H:i', strtotime($trx['tgl_checkin'])) ?></span>
                </td>
                <td width="25%">
                    <span class="label">Durasi</span>
                    <span class="value"><?= $detailKamar[0]['durasi_malam'] ?> Malam</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">Alamat</span>
                    <span class="value"><?= $trx['alamat'] ?? '-' ?></span>
                </td>
                <td>
                    <span class="label">Tgl Lahir</span>
                    <span class="value"><?= isset($trx['tanggal_lahir']) ? date('d-m-Y', strtotime($trx['tanggal_lahir'])) : '-' ?></span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">ID Type / No</span>
                    <span class="value"><?= $trx['jenis_identitas'] ?> - <?= $trx['no_identitas'] ?></span>
                </td>
                <td>
                    <span class="label">No HP</span>
                    <span class="value"><?= $trx['no_hp'] ?></span>
                </td>
                <td>
                    <span class="label">Email</span>
                    <span class="value"><?= $trx['email'] ?? '-' ?></span>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <span class="label">Info Kamar</span>
                    <div style="margin-top:5px;">
                        <?php foreach($detailKamar as $k): ?>
                            Room: <b><?= $k['nomor_kamar'] ?></b> (<?= $k['nama_tipe'] ?>) - Rp <?= number_format($k['harga_per_malam']) ?><br>
                        <?php endforeach; ?>
                    </div>
                </td>
            </tr>
        </table>

        <div class="terms">
            <strong>PERNYATAAN:</strong>
            <ul>
                <li>Check-in mulai jam 14.00 dan Check-out jam 12.00.</li>
                <li>Hotel tidak bertanggung jawab atas barang berharga yang tertinggal.</li>
                <li>Tamu menyetujui rate dan biaya yang tercantum di atas.</li>
            </ul>
        </div>

        <div class="signatures">
            <div class="sig-box">
                <span class="label">Rate Total</span>
                <span class="value">Rp <?= number_format($trx['total_tagihan']) ?></span>
            </div>
            <div class="sig-box">
                <span class="label">Receptionist</span>
                <div class="sig-line" style="text-align: center;">Petugas: <?= $trx['id_user_resepsionis'] ?></div>
            </div>
            <div class="sig-box">
                <span class="label">Guest Signature</span>
                <div class="sig-line" style="text-align: center;"><?= strtoupper($trx['nama_tamu']) ?></div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
include_once 'model/koneksi.php';

class C_Checkin {
    private $db;
    public function __construct() {
        date_default_timezone_set('Asia/Jakarta');
        $this->db = new Database();

    }

    // Ambil kamar tersedia (group per tipe)
    public function getKamarAvailable() {
        $rows = $this->db->query("
            SELECT k.id_kamar, k.nomor_kamar, tk.nama_tipe, tk.harga_dasar
            FROM kamar k
            JOIN tipe_kamar tk ON k.id_tipe = tk.id_tipe
            WHERE k.status = 'available'
            ORDER BY tk.harga_dasar DESC, k.nomor_kamar ASC
        ");
        $result = [];
        foreach ($rows as $row) {
            $result[$row['nama_tipe']][] = $row;
        }
        return $result;
    }

    // Proses check-in
    public function prosesCheckin($nama, $no_hp, $no_identitas, $id_kamar, $durasi_malam) {
    $id_kamar = intval($id_kamar);
    $durasi_malam = intval($durasi_malam);

    // Ambil harga kamar
    $stmt = $this->db->query("
        SELECT tk.harga_dasar
        FROM kamar k
        JOIN tipe_kamar tk ON k.id_tipe = tk.id_tipe
        WHERE k.id_kamar = $id_kamar
    ");
    $kamarArr = $stmt->fetch(PDO::FETCH_ASSOC); // <-- pakai fetch PDO

    if (!$kamarArr) die("Kamar tidak ditemukan.");
    
    $harga_per_malam = floatval($kamarArr['harga_dasar']);
    $total_biaya = $harga_per_malam * $durasi_malam;

    $data = [
        'no_invoice' => 'INV-' . time(),
        'nama_tamu' => $nama,
        'no_hp' => $no_hp,
        'no_identitas' => $no_identitas,
        'id_kamar' => $id_kamar,
        'tgl_checkin' => date('Y-m-d H:i:s'),
        'durasi_malam' => $durasi_malam,
        'harga_kamar_per_malam' => $harga_per_malam,
        'total_biaya_kamar' => $total_biaya,
        'total_biaya_layanan' => 0,
        'total_tagihan' => $total_biaya,
        'status_inap' => 'checkin',
        'status_bayar' => 'belum_bayar',
        'id_user_resepsionis' => $_SESSION['user_id'] ?? 0
    ];

    $id_transaksi = $this->db->tambah('transaksi', $data);

    $this->db->ubah('kamar', ['status' => 'occupied'], "id_kamar=$id_kamar");

    return $id_transaksi;
}


    // Ambil tamu aktif
    public function getTamuAktif() {
        return $this->db->query("
            SELECT t.*, k.nomor_kamar, tk.nama_tipe
            FROM transaksi t
            JOIN kamar k ON t.id_kamar = k.id_kamar
            JOIN tipe_kamar tk ON k.id_tipe = tk.id_tipe
            WHERE t.status_inap = 'checkin'
            ORDER BY t.tgl_checkin DESC
        ");
    }

    public function batalkanCheckin($id_transaksi, $id_kamar) {
        $id_transaksi = intval($id_transaksi);
        $id_kamar = intval($id_kamar);

        // 1. Kamar tetap harus dikosongkan
        $this->db->ubah('kamar', ['status' => 'available'], "id_kamar=$id_kamar");

        // 2. Transaksi TIDAK DIHAPUS, tapi statusnya diubah jadi 'batal'
        // Pastikan kolom 'status_inap' di database enum-nya mendukung value 'batal'
        $data_update = [
            'status_inap'  => 'batal', 
            // Opsional: tambah catatan
        ];
        
        $this->db->ubah('transaksi', $data_update, "id_transaksi = $id_transaksi");
    }


    public function updateCheckin($id_transaksi, $nama, $no_hp, $no_identitas, $durasi_malam) {
    $id_transaksi = intval($id_transaksi);
    $durasi_malam = intval($durasi_malam);

    // Ambil data transaksi lama untuk menghitung ulang total_tagihan
    $row = $this->db->query("SELECT id_kamar FROM transaksi WHERE id_transaksi=$id_transaksi")->fetch(PDO::FETCH_ASSOC);
    if (!$row) return false;

    $id_kamar = intval($row['id_kamar']);

    // Ambil harga kamar
    $kamar = $this->db->query("
        SELECT tk.harga_dasar
        FROM kamar k
        JOIN tipe_kamar tk ON k.id_tipe = tk.id_tipe
        WHERE k.id_kamar=$id_kamar
    ")->fetch(PDO::FETCH_ASSOC);

    $harga_per_malam = floatval($kamar['harga_dasar']);
    $total_biaya = $harga_per_malam * $durasi_malam;

    $data_update = [
        'nama_tamu' => $nama,
        'no_hp' => $no_hp,
        'no_identitas' => $no_identitas,
        'durasi_malam' => $durasi_malam,
        'total_biaya_kamar' => $total_biaya,
        'total_tagihan' => $total_biaya // kalau mau include layanan, bisa tambahkan
        // status_inap tidak diubah
    ];

    return $this->db->ubah('transaksi', $data_update, "id_transaksi=$id_transaksi");
}

    public function updateStatusBayar($id_transaksi, $status_bayar) {
        $id_transaksi = intval($id_transaksi);
        $allowed = ['belum_bayar', 'dp', 'lunas'];
        if (!in_array($status_bayar, $allowed)) return false;

        return $this->db->ubah('transaksi', ['status_bayar' => $status_bayar], "id_transaksi=$id_transaksi");
}

}
?>

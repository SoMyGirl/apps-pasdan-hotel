<?php
class GuestController {
    private $db;
    public function __construct() { $this->db = new Database(); }

    public function index() { // InHouse Guest
        if (!isset($_SESSION['status_login'])) {
            header("Location: index.php?modul=Auth&aksi=login"); 
            exit;
        }

        // Panggil View Database
        $tamu = $this->db->query("SELECT * FROM v_guest_inhouse ORDER BY tgl_checkin DESC")->fetchAll(PDO::FETCH_ASSOC);
        
        $this->view('Guest/inhouse', ['tamu' => $tamu]);
    }

    public function history() {
        $thn = $_GET['tahun'] ?? date('Y');
        $pg  = $_GET['page'] ?? 1;
        $lim = 10; $off = ($pg-1)*$lim;

        // Gunakan View History (jika sudah dibuat) atau Query Manual yang dioptimalkan
        $sql = "SELECT t.*, 
                       GROUP_CONCAT(DISTINCT k.nomor_kamar SEPARATOR ', ') as list_kamar,
                       GROUP_CONCAT(DISTINCT tp.nama_tipe SEPARATOR ', ') as list_tipe
                FROM transaksi t 
                JOIN transaksi_kamar tk ON t.id_transaksi = tk.id_transaksi
                JOIN kamar k ON tk.id_kamar = k.id_kamar
                JOIN tipe_kamar tp ON k.id_tipe = tp.id_tipe
                WHERE (status_transaksi='finished' OR status_transaksi='active') AND YEAR(tgl_checkin)='$thn'
                GROUP BY t.id_transaksi 
                ORDER BY t.tgl_checkin DESC LIMIT $lim OFFSET $off";
                
        $tamu = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        $tot = $this->db->query("SELECT COUNT(*) FROM transaksi WHERE YEAR(tgl_checkin)='$thn'")->fetchColumn();
        
        $this->view('Guest/history', ['tamu'=>$tamu,'tahun'=>$thn,'page'=>$pg,'totalPages'=>ceil($tot/$lim)]);
    }

    private function view($p, $d) { extract($d); include 'views/Layout/header.php'; include 'views/Layout/sidebar.php'; include "views/$p.php"; include 'views/Layout/footer.php'; }
}
?>
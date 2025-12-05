<?php

class ErrorController {
    // Tidak butuh database connection di sini

    public function notFound() {
        // Halaman 404
        http_response_code(404);
        $this->view('Error/404');
    }

    public function forbidden() {
        // Halaman 403
        http_response_code(403);
        $this->view('Error/403');
    }

    // Custom View Loader (Tanpa Sidebar/Header Utama)
    // Kita buat layout khusus di dalam file view-nya agar bersih
    private function view($path) {
        include "views/$path.php";
    }
}
?>
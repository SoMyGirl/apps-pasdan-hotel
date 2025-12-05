<?php
session_start();
require_once 'config/Database.php';

// Base URL (Sesuaikan dengan folder htdocs anda)
define('BASE_URL', '/hotel-pasundan-sistem/'); 

$modul = isset($_GET['modul']) ? ucfirst($_GET['modul']) : 'Dashboard';
$aksi  = isset($_GET['aksi'])  ? $_GET['aksi'] : 'index';

$controllerName = $modul . 'Controller';
$fileController = "controllers/$modul/$controllerName.php";

if (file_exists($fileController)) {
    require_once $fileController;
    if (class_exists($controllerName)) {
        $controller = new $controllerName();
        if (method_exists($controller, $aksi)) {
            $controller->$aksi();
        } else {
            die("Error: Method $aksi tidak ditemukan di $controllerName");
        }
    } else {
        die("Error: Class $controllerName tidak ditemukan");
    }
} else {
    // Redirect ke Login jika modul tidak ketemu (atau belum login)
    header("Location: index.php?modul=Auth&aksi=login");
}
?>
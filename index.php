<?php
// Aktifkan pelaporan error untuk debugging dan mencegah layar putih (blank screen)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Routing Sederhana MVC
$controllerName = isset($_GET['c']) ? ucfirst($_GET['c']) . 'Controller' : 'HomeController';
$actionName = isset($_GET['a']) ? $_GET['a'] : 'index';

$controllerPath = 'Controllers/' . $controllerName . '.php';

if (file_exists($controllerPath)) {
    require_once $controllerPath;
    if (class_exists($controllerName)) {
        $controller = new $controllerName();
        if (method_exists($controller, $actionName)) {
            $controller->$actionName();
            exit;
        }
    }
}

// Fallback jika controller/action tidak ditemukan
require_once 'Controllers/HomeController.php';
$controller = new HomeController();
$controller->index();

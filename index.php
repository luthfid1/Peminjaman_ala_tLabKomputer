<?php
// Aktifkan pelaporan error untuk debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Router MVC Switch Case
$controller = isset($_GET['c']) ? strtolower(trim($_GET['c'])) : '';
$action     = isset($_GET['a']) ? strtolower(trim($_GET['a'])) : '';

// Routing default jika parameter kosong
if (empty($controller)) {
    if (isset($_SESSION['user'])) {
        $userRole = strtolower(trim($_SESSION['user']['role'] ?? ''));
        if (in_array($userRole, ['pengelola', 'admin'])) {
            $controller = 'admin';
            $action = 'dashboard';
        } else {
            $controller = 'auth';
            $action = 'login';
        }
    } else {
        $controller = 'auth';
        $action = 'login';
    }
}

switch ($controller) {
    case 'admin':
        require_once __DIR__ . '/controllers/admincontroller.php';
        $admin = new AdminController();

        switch ($action) {
            case 'dashboard':
                $admin->dashboard();
                break;

            // CRUD ALAT
            case 'alat':
                $admin->alat();
                break;
            case 'tambah_alat':
                $admin->tambah_alat();
                break;
            case 'ubah_alat':
                $admin->ubah_alat();
                break;
            case 'hapus_alat':
                $admin->hapus_alat();
                break;

            // CRUD KATEGORI
            case 'kategori':
                $admin->kategori();
                break;
            case 'tambah_kategori':
                $admin->tambah_kategori();
                break;
            case 'ubah_kategori':
                $admin->ubah_kategori();
                break;
            case 'hapus_kategori':
                $admin->hapus_kategori();
                break;

            // CRUD PENGGUNA (USER)
            case 'pengguna':
                $admin->pengguna();
                break;
            case 'tambah_pengguna':
                $admin->tambah_pengguna();
                break;
            case 'ubah_pengguna':
                $admin->ubah_pengguna();
                break;
            case 'hapus_pengguna':
                $admin->hapus_pengguna();
                break;

            // CRUD PEMINJAMAN
            case 'peminjaman':
                $admin->peminjaman();
                break;
            case 'tambah_peminjaman':
                $admin->tambah_peminjaman();
                break;
            case 'ubah_peminjaman':
                $admin->ubah_peminjaman();
                break;
            case 'hapus_peminjaman':
                $admin->hapus_peminjaman();
                break;

            // CRUD PENGEMBALIAN
            case 'pengembalian':
                $admin->pengembalian();
                break;
            case 'tambah_pengembalian':
                $admin->tambah_pengembalian();
                break;
            case 'ubah_pengembalian':
                $admin->ubah_pengembalian();
                break;
            case 'hapus_pengembalian':
                $admin->hapus_pengembalian();
                break;

            // LOG AKTIVITAS
            case 'log':
                $admin->log();
                break;

            default:
                $admin->dashboard();
                break;
        }
        break;

    case 'auth':
        require_once __DIR__ . '/controllers/authcontroller.php';
        $auth = new AuthController();

        switch ($action) {
            case 'login':
                $auth->login();
                break;
            case 'logout':
                $auth->logout();
                break;
            default:
                $auth->login();
                break;
        }
        break;

    default:
        header('Location: index.php?c=auth&a=login');
        exit;
}

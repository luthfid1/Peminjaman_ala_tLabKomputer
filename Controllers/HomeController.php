<?php

require_once __DIR__ . '/../models/alat.php';

class HomeController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $alatModel = new Alat();
        $daftarAlat = $alatModel->getAllAlat();

        require_once __DIR__ . '/../views/landing.php';
    }
}

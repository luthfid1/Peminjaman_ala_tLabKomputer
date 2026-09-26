<?php

require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/alat.php';
require_once __DIR__ . '/../models/kategori.php';
require_once __DIR__ . '/../models/peminjam.php';
require_once __DIR__ . '/../models/peminjaman.php';
require_once __DIR__ . '/../models/pengembalian.php';

class KejurController {
    private $userModel;
    private $alatModel;
    private $kategoriModel;
    private $peminjamModel;
    private $peminjamanModel;
    private $pengembalianModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: index.php?c=auth&a=login');
            exit;
        }

        $role = strtolower(trim($_SESSION['user']['role'] ?? ''));
        // Hak akses Ketua Jurusan (role: admin) dan Pengelola (role: pengelola)
        if (!in_array($role, ['admin', 'pengelola'])) {
            if ($role === 'petugas') {
                header('Location: index.php?c=petugas&a=dashboard');
                exit;
            } elseif ($role === 'peminjam') {
                header('Location: index.php?c=peminjam&a=dashboard');
                exit;
            } else {
                header('Location: index.php');
                exit;
            }
        }

        $this->userModel         = new User();
        $this->alatModel         = new Alat();
        $this->kategoriModel     = new Kategori();
        $this->peminjamModel     = new Peminjam();
        $this->peminjamanModel   = new Peminjaman();
        $this->pengembalianModel = new Pengembalian();
    }

    // ==========================================
    // 1. DASHBOARD MONITORING KETUA JURUSAN
    // ==========================================
    public function dashboard() {
        $hariArr = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $bulanArr = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $tglStr = strtoupper($hariArr[date('w')] . ', ' . date('j') . ' ' . $bulanArr[(int)date('n')] . ' ' . date('Y'));

        $totalSiswa = count($this->peminjamModel->getAllPeminjam());
        $totalAlat = count($this->alatModel->getAllAlat());
        $totalPeminjaman = count($this->peminjamanModel->getAllPeminjaman());
        $totalPengembalian = count($this->pengembalianModel->getAllPengembalian());

        $peminjamanTerbaru = array_slice($this->peminjamanModel->getAllPeminjaman(), 0, 5);
        $alatAktifDipinjam = $this->peminjamanModel->getActivePeminjaman();

        require_once __DIR__ . '/../views/kejur_dashboard.php';
    }

    // ==========================================
    // 2. USE CASE: MELIHAT LAPORAN PEMINJAM
    // ==========================================
    public function laporan_peminjam() {
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $daftarPeminjam = $this->peminjamModel->getAllPeminjam($keyword);

        require_once __DIR__ . '/../views/kejur_laporan_peminjam.php';
    }

    // ==========================================
    // 3. USE CASE: MELIHAT LAPORAN ALAT LAB
    // ==========================================
    public function laporan_alat() {
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $idKategori = isset($_GET['kategori']) ? (int)$_GET['kategori'] : 0;

        $daftarAlat = $this->alatModel->getAllAlat($keyword);
        if ($idKategori > 0) {
            $daftarAlat = array_values(array_filter($daftarAlat, function($a) use ($idKategori) {
                return (int)$a['id_kategori'] === $idKategori;
            }));
        }
        $daftarKategori = $this->kategoriModel->getAllKategori();

        require_once __DIR__ . '/../views/kejur_laporan_alat.php';
    }

    // ==========================================
    // 4. USE CASE: MELIHAT LAPORAN PEMINJAMAN
    // ==========================================
    public function laporan_peminjaman() {
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $statusFilter = isset($_GET['status_filter']) ? trim($_GET['status_filter']) : '';

        $daftarPeminjaman = $this->peminjamanModel->getAllPeminjaman($keyword, $statusFilter);

        require_once __DIR__ . '/../views/kejur_laporan_peminjaman.php';
    }

    // ==========================================
    // 5. USE CASE: MELIHAT LAPORAN PENGEMBALIAN
    // ==========================================
    public function laporan_pengembalian() {
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $daftarPengembalian = $this->pengembalianModel->getAllPengembalian($keyword);

        require_once __DIR__ . '/../views/kejur_laporan_pengembalian.php';
    }
}

<?php

require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/alat.php';
require_once __DIR__ . '/../models/kategori.php';
require_once __DIR__ . '/../models/peminjaman.php';
require_once __DIR__ . '/../models/pengembalian.php';
require_once __DIR__ . '/../models/peminjam.php';

class PeminjamController {
    private $userModel;
    private $alatModel;
    private $kategoriModel;
    private $peminjamanModel;
    private $pengembalianModel;
    private $peminjamProfileModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: index.php?c=auth&a=login');
            exit;
        }

        $role = strtolower(trim($_SESSION['user']['role'] ?? ''));
        if ($role !== 'peminjam') {
            if ($role === 'pengelola' || $role === 'admin') {
                header('Location: index.php?c=admin&a=dashboard');
                exit;
            } elseif ($role === 'petugas') {
                header('Location: index.php?c=admin&a=peminjaman');
                exit;
            } else {
                header('Location: index.php');
                exit;
            }
        }

        $this->userModel            = new User();
        $this->alatModel            = new Alat();
        $this->kategoriModel        = new Kategori();
        $this->peminjamanModel      = new Peminjaman();
        $this->pengembalianModel    = new Pengembalian();
        $this->peminjamProfileModel = new Peminjam();
    }

    private function getUserId() {
        return (int)($_SESSION['user']['id_user'] ?? $_SESSION['user']['id_users'] ?? 0);
    }

    private function getPeminjamProfile() {
        $userId = $this->getUserId();
        $nama   = $_SESSION['user']['nama'] ?? $_SESSION['user']['nama_lengkap'] ?? 'Siswa';

        $profile = $this->peminjamProfileModel->getPeminjamByUserId($userId);
        if (!$profile) {
            $profile = $this->peminjamProfileModel->getPeminjamByNama($nama);
        }

        // Jika siswa belum ada di tabel peminjam (misal akun demo bawaan), buatkan profil otomatis
        if (!$profile) {
            $newId = $this->peminjamProfileModel->createPeminjam(
                $nama,
                '1023' . rand(1000, 9999),
                'XII RPL 1',
                'Rekayasa Perangkat Lunak',
                '08123456789',
                '',
                $userId
            );
            $profile = $this->peminjamProfileModel->getPeminjamById($newId);
        }

        return $profile;
    }

    // ==========================================
    // 1. DASHBOARD SISWA
    // ==========================================
    public function dashboard() {
        $profile = $this->getPeminjamProfile();
        $idPeminjam = (int)$profile['id'];

        $totalMenunggu  = $this->peminjamanModel->countPeminjamPeminjamanByStatus($idPeminjam, 'menunggu');
        $totalDipinjam  = $this->peminjamanModel->countPeminjamPeminjamanByStatus($idPeminjam, ['disetujui', 'dipinjam']);
        $totalSelesai   = $this->peminjamanModel->countPeminjamPeminjamanByStatus($idPeminjam, 'dikembalikan');
        $totalDenda     = $this->pengembalianModel->getTotalDendaByPeminjamId($idPeminjam);

        $daftarPinjamTerbaru = $this->peminjamanModel->getPeminjamanByPeminjamId($idPeminjam);
        $alatTersedia = $this->alatModel->getAllAlat();

        $tglStr = date('l, d F Y');

        require_once __DIR__ . '/../views/peminjam_dashboard.php';
    }

    // ==========================================
    // 2. KATALOG ALAT LAB KOMPUTER
    // ==========================================
    public function daftar_alat() {
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $idKategori = isset($_GET['kategori']) ? (int)$_GET['kategori'] : 0;

        $daftarAlat = $this->alatModel->getAllAlat($keyword);
        if ($idKategori > 0) {
            $daftarAlat = array_values(array_filter($daftarAlat, function($a) use ($idKategori) {
                return (int)$a['id_kategori'] === $idKategori;
            }));
        }
        $daftarKategori = $this->kategoriModel->getAllKategori();
        $profile = $this->getPeminjamProfile();

        require_once __DIR__ . '/../views/peminjam_alat.php';
    }

    // ==========================================
    // 3. DAFTAR PEMINJAMAN SAYA
    // ==========================================
    public function peminjaman() {
        $profile = $this->getPeminjamProfile();
        $idPeminjam = (int)$profile['id'];
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $statusFilter = isset($_GET['status_filter']) ? trim($_GET['status_filter']) : '';

        $message = '';
        $error = '';
        if (isset($_GET['status'])) {
            if ($_GET['status'] === 'requested') $message = 'Pengajuan peminjaman alat berhasil diajukan! Menunggu persetujuan petugas lab.';
            if ($_GET['status'] === 'cancelled') $message = 'Permohonan peminjaman berhasil dibatalkan.';
            if ($_GET['status'] === 'stok_kurang') $error = 'Stok alat yang diminta tidak mencukupi atau sedang kosong!';
            if ($_GET['status'] === 'waktu_invalid') $error = 'Waktu rencana kembali tidak boleh sama atau mendahului waktu pinjam!';
            if ($_GET['status'] === 'error') $error = 'Terjadi kesalahan sistem saat memproses peminjaman.';
        }

        $daftarPeminjaman = $this->peminjamanModel->getPeminjamanByPeminjamId($idPeminjam, $keyword, $statusFilter);
        $daftarAlat = $this->alatModel->getAllAlat();

        require_once __DIR__ . '/../views/peminjam_peminjaman.php';
    }

    // ==========================================
    // 4. PROSES AJUKAN PEMINJAMAN
    // ==========================================
    public function ajukan_peminjaman() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $profile               = $this->getPeminjamProfile();
            $idPeminjam            = (int)$profile['id'];
            $id_alat               = (int)($_POST['id_alat'] ?? 0);
            $jumlah                = max(1, (int)($_POST['jumlah'] ?? 1));
            $waktu_pinjam          = trim($_POST['waktu_pinjam'] ?? date('H:i'));
            $waktu_rencana_kembali = trim($_POST['waktu_rencana_kembali'] ?? date('H:i', strtotime('+2 hours')));
            $jenis_peminjaman      = trim($_POST['jenis_peminjaman'] ?? 'Praktek Lab');
            $keperluan             = trim($_POST['keperluan'] ?? '');

            if ($id_alat > 0) {
                $alat = $this->alatModel->getAlatById($id_alat);
                if (!$alat || $alat['jumlah'] < $jumlah) {
                    header('Location: index.php?c=peminjam&a=peminjaman&status=stok_kurang');
                    exit;
                }

                // Format waktu ke format TIME MySQL: HH:MM:SS
                if (strlen($waktu_pinjam) === 5) $waktu_pinjam .= ':00';
                if (strlen($waktu_rencana_kembali) === 5) $waktu_rencana_kembali .= ':00';

                try {
                    $newId = $this->peminjamanModel->createPeminjaman(
                        $idPeminjam,
                        null, // Belum disetujui petugas (id_user petugas null)
                        $waktu_pinjam,
                        $waktu_rencana_kembali,
                        $jenis_peminjaman,
                        $keperluan,
                        $id_alat,
                        $jumlah,
                        'menunggu'
                    );

                    $this->userModel->recordLog(
                        $this->getUserId(),
                        'Pengajuan Peminjaman Alat',
                        "Siswa {$profile['nama']} mengajukan peminjaman: {$alat['nama_alat']} ({$jumlah} unit)"
                    );

                    header('Location: index.php?c=peminjam&a=peminjaman&status=requested');
                    exit;
                } catch (Exception $e) {
                    header('Location: index.php?c=peminjam&a=peminjaman&status=error');
                    exit;
                }
            }
        }
        header('Location: index.php?c=peminjam&a=peminjaman');
        exit;
    }

    // ==========================================
    // 5. BATALKAN PEMINJAMAN
    // ==========================================
    public function batalkan_peminjaman() {
        $id = (int)($_GET['id'] ?? 0);
        $profile = $this->getPeminjamProfile();
        $idPeminjam = (int)$profile['id'];

        if ($id > 0) {
            try {
                $pmj = $this->peminjamanModel->getPeminjamanById($id);
                if ($pmj && (int)$pmj['id_peminjam'] === $idPeminjam && $pmj['status'] === 'menunggu') {
                    $this->peminjamanModel->batalkanPeminjamanByPeminjam($id, $idPeminjam);
                    $this->userModel->recordLog(
                        $this->getUserId(),
                        'Batalkan Peminjaman',
                        "Siswa membatalkan permohonan #{$pmj['kode_peminjaman']}"
                    );
                    header('Location: index.php?c=peminjam&a=peminjaman&status=cancelled');
                    exit;
                }
            } catch (Exception $e) {
                header('Location: index.php?c=peminjam&a=peminjaman&status=error');
                exit;
            }
        }
        header('Location: index.php?c=peminjam&a=peminjaman');
        exit;
    }

    // ==========================================
    // 6. RIWAYAT PENGEMBALIAN SAYA
    // ==========================================
    public function pengembalian() {
        $profile = $this->getPeminjamProfile();
        $idPeminjam = (int)$profile['id'];
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';

        $daftarPengembalian = $this->pengembalianModel->getPengembalianByPeminjamId($idPeminjam, $keyword);
        $totalDenda = $this->pengembalianModel->getTotalDendaByPeminjamId($idPeminjam);

        require_once __DIR__ . '/../views/peminjam_pengembalian.php';
    }
}

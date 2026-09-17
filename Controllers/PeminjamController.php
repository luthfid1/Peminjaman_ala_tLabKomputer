<?php

require_once 'Models/User.php';
require_once 'Models/Alat.php';
require_once 'Models/Kategori.php';
require_once 'Models/Peminjaman.php';
require_once 'Models/Pengembalian.php';

class PeminjamController {
    private $userModel;
    private $alatModel;
    private $kategoriModel;
    private $peminjamanModel;
    private $pengembalianModel;

    public function __construct() {
        // Proteksi hak akses role Peminjam
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?c=auth&a=login');
            exit;
        }

        $role = strtolower(trim($_SESSION['user']['role'] ?? ''));
        if ($role !== 'peminjam') {
            if ($role === 'admin') {
                header('Location: index.php?c=admin&a=dashboard');
                exit;
            } elseif ($role === 'petugas') {
                header('Location: index.php?c=petugas&a=dashboard');
                exit;
            } else {
                header('Location: index.php');
                exit;
            }
        }

        $this->userModel         = new User();
        $this->alatModel         = new Alat();
        $this->kategoriModel     = new Kategori();
        $this->peminjamanModel   = new Peminjaman();
        $this->pengembalianModel = new Pengembalian();
    }

    // ==========================================
    // DASHBOARD PEMINJAM
    // ==========================================
    public function dashboard() {
        $userId = $_SESSION['user']['id_users'];

        $totalPengajuan = $this->peminjamanModel->countUserPeminjamanByStatus($userId, 'menunggu');
        $totalDipinjam  = $this->peminjamanModel->countUserPeminjamanByStatus($userId, 'dipinjam');
        $totalKembali   = $this->peminjamanModel->countUserPeminjamanByStatus($userId, 'dikembalikan');
        $totalDenda     = $this->pengembalianModel->getTotalDendaByUser($userId);

        $daftarPinjamTerbaru = $this->peminjamanModel->getPeminjamanByUser($userId);
        $alatPopuler = $this->alatModel->getAllAlat();

        require_once 'Views/peminjam_dashboard.php';
    }

    // ==========================================
    // KATALOG ALAT MUSIK & FORM PENGAJUAN
    // ==========================================
    public function katalog() {
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $idKategori = isset($_GET['kategori']) ? (int)$_GET['kategori'] : 0;

        $daftarAlat = $this->alatModel->getAllAlat($keyword, $idKategori > 0 ? $idKategori : null);
        $daftarKategori = $this->kategoriModel->getAllKategori();

        $message = '';
        $error = '';
        if (isset($_GET['status'])) {
            if ($_GET['status'] === 'requested') $message = 'Pengajuan peminjaman berhasil dikirim! Mohon menunggu persetujuan petugas.';
            if ($_GET['status'] === 'stok_kurang') $error = 'Stok alat tidak mencukupi untuk jumlah yang Anda minta!';
            if ($_GET['status'] === 'error') $error = 'Terjadi kesalahan saat memproses pengajuan.';
        }

        require_once 'Views/peminjam_katalog.php';
    }

    // ==========================================
    // RIWAYAT & DAFTAR PEMINJAMAN SAYA
    // ==========================================
    public function peminjaman() {
        $userId = $_SESSION['user']['id_users'];
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';

        $message = '';
        $error = '';
        if (isset($_GET['status'])) {
            if ($_GET['status'] === 'requested') $message = 'Pengajuan peminjaman berhasil dibuat dan sedang menunggu verifikasi.';
            if ($_GET['status'] === 'cancelled') $message = 'Pengajuan peminjaman berhasil dibatalkan.';
            if ($_GET['status'] === 'error') $error = 'Gagal memproses permintaan.';
        }

        $daftarPeminjaman = $this->peminjamanModel->getPeminjamanByUser($userId, $keyword);
        $daftarAlat = $this->alatModel->getAllAlat();

        require_once 'Views/peminjam_peminjaman.php';
    }

    // ==========================================
    // PROSES AJUKAN PEMINJAMAN (ONLINE)
    // ==========================================
    public function ajukan_peminjaman() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId          = $_SESSION['user']['id_users'];
            $id_alat         = (int)($_POST['id_alat'] ?? 0);
            $jumlah          = (int)($_POST['jumlah'] ?? 1);
            $tanggal_pinjam  = trim($_POST['tanggal_pinjam'] ?? date('Y-m-d'));
            $tanggal_kembali = trim($_POST['tanggal_kembali'] ?? '');

            if ($id_alat > 0 && $jumlah > 0 && !empty($tanggal_kembali)) {
                $alat = $this->alatModel->getAlatById($id_alat);
                if (!$alat || $alat['jumlah_stok'] < $jumlah) {
                    header('Location: index.php?c=peminjam&a=katalog&status=stok_kurang');
                    exit;
                }

                try {
                    // Simpan peminjaman dengan status default 'menunggu'
                    $result = $this->peminjamanModel->createPeminjaman($userId, $id_alat, $jumlah, $tanggal_pinjam, $tanggal_kembali, 'menunggu');
                    if ($result) {
                        $this->userModel->recordLog($userId, 'Mengajukan peminjaman alat: ' . ($alat['nama_alat'] ?? "Alat ID $id_alat") . ' (' . $jumlah . ' unit)');
                        header('Location: index.php?c=peminjam&a=peminjaman&status=requested');
                        exit;
                    } else {
                        header('Location: index.php?c=peminjam&a=katalog&status=error');
                        exit;
                    }
                } catch (Exception $e) {
                    header('Location: index.php?c=peminjam&a=katalog&status=error');
                    exit;
                }
            }
        }
        header('Location: index.php?c=peminjam&a=katalog');
        exit;
    }

    // ==========================================
    // BATALKAN PENGAJUAN PEMINJAMAN
    // ==========================================
    public function batalkan_peminjaman() {
        $id = (int)($_GET['id'] ?? 0);
        $userId = $_SESSION['user']['id_users'];

        if ($id > 0) {
            try {
                $peminjaman = $this->peminjamanModel->getPeminjamanById($id);
                if ($peminjaman && $peminjaman['id_user'] == $userId && $peminjaman['status'] === 'menunggu') {
                    $this->peminjamanModel->batalkanPeminjamanByUser($id, $userId);
                    $this->userModel->recordLog($userId, 'Membatalkan pengajuan peminjaman #' . $id);
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
    // RIWAYAT PENGEMBALIAN SAYA & INFO DENDA
    // ==========================================
    public function pengembalian() {
        $userId = $_SESSION['user']['id_users'];
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';

        $daftarPengembalian = $this->pengembalianModel->getPengembalianByUser($userId, $keyword);
        $totalDenda = $this->pengembalianModel->getTotalDendaByUser($userId);

        require_once 'Views/peminjam_pengembalian.php';
    }

    // ==========================================
    // PROFIL SAYA
    // ==========================================
    public function profil() {
        $userId = $_SESSION['user']['id_users'];
        $user = $this->userModel->getUserById($userId);

        $message = '';
        $error = '';
        if (isset($_GET['status'])) {
            if ($_GET['status'] === 'updated') $message = 'Data profil Anda berhasil diperbarui.';
            if ($_GET['status'] === 'password_mismatch') $error = 'Konfirmasi kata sandi baru tidak cocok!';
            if ($_GET['status'] === 'error') $error = 'Gagal memperbarui profil.';
        }

        require_once 'Views/peminjam_profil.php';
    }

    public function ubah_profil() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId           = $_SESSION['user']['id_users'];
            $nama_lengkap     = trim($_POST['nama_lengkap'] ?? '');
            $username         = trim($_POST['username'] ?? '');
            $no_hp            = trim($_POST['no_hp'] ?? '');
            $alamat           = trim($_POST['alamat'] ?? '');
            $password_baru    = trim($_POST['password_baru'] ?? '');
            $konfirmasi_pass  = trim($_POST['konfirmasi_password'] ?? '');

            if (!empty($password_baru) && $password_baru !== $konfirmasi_pass) {
                header('Location: index.php?c=peminjam&a=profil&status=password_mismatch');
                exit;
            }

            if (!empty($nama_lengkap) && !empty($username)) {
                try {
                    $currentUser = $this->userModel->getUserById($userId);
                    $pass = !empty($password_baru) ? $password_baru : null;

                    $this->userModel->updateUser($userId, $nama_lengkap, $username, $currentUser['role'], $pass, $alamat, $no_hp);

                    // Update session
                    $_SESSION['user']['nama_lengkap'] = $nama_lengkap;
                    $_SESSION['user']['username']     = $username;

                    $this->userModel->recordLog($userId, 'Memperbarui profil diri');
                    header('Location: index.php?c=peminjam&a=profil&status=updated');
                    exit;
                } catch (Exception $e) {
                    header('Location: index.php?c=peminjam&a=profil&status=error');
                    exit;
                }
            }
        }
        header('Location: index.php?c=peminjam&a=profil');
        exit;
    }
}

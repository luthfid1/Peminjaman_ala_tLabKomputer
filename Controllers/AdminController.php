<?php

require_once __DIR__ . '/../models/alat.php';
require_once __DIR__ . '/../models/kategori.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/peminjaman.php';
require_once __DIR__ . '/../models/pengembalian.php';
require_once __DIR__ . '/../models/peminjam.php';

class AdminController {
    private $alatModel;
    private $kategoriModel;
    private $userModel;
    private $peminjamanModel;
    private $pengembalianModel;
    private $peminjamModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $role = strtolower(trim($_SESSION['user']['role'] ?? ''));
        // Admin pengelola lab memiliki akses penuh
        if (!isset($_SESSION['user']) || !in_array($role, ['admin pengelola', 'pengelola', 'admin'])) {
            header('Location: index.php?c=auth&a=login');
            exit;
        }

        $this->alatModel = new Alat();
        $this->kategoriModel = new Kategori();
        $this->userModel = new User();
        $this->peminjamanModel = new Peminjaman();
        $this->pengembalianModel = new Pengembalian();
        $this->peminjamModel = new Peminjam();
    }

    private function getUserId() {
        return $_SESSION['user']['id_user'] ?? $_SESSION['user']['id_users'] ?? 1;
    }

    // ==========================================
    // DASHBOARD
    // ==========================================
    public function dashboard() {
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        $stats = $this->alatModel->getDashboardStats();
        $totalPengguna = $this->userModel->countUsers();

        $inventaris = $this->alatModel->getAllAlat($keyword);

        $hariArr = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $bulanArr = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $tglStr = strtoupper($hariArr[date('w')] . ', ' . date('j') . ' ' . $bulanArr[(int)date('n')] . ' ' . date('Y'));

        require_once __DIR__ . '/../views/admin_dashboard.php';
    }

    // ==========================================
    // CRUD DATA ALAT LAB
    // ==========================================
    public function alat() {
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $message = '';
        $error = '';

        if (isset($_GET['status'])) {
            if ($_GET['status'] === 'added') $message = 'Data alat laboratorium berhasil ditambahkan.';
            if ($_GET['status'] === 'updated') $message = 'Data alat laboratorium berhasil diperbarui.';
            if ($_GET['status'] === 'deleted') $message = 'Data alat laboratorium berhasil dihapus.';
            if ($_GET['status'] === 'error') $error = 'Terjadi kesalahan saat memproses data alat.';
        }

        $daftarAlat = $this->alatModel->getAllAlat($keyword);
        $daftarKategori = $this->kategoriModel->getAllKategori();

        require_once __DIR__ . '/../views/admin_alat.php';
    }

    public function tambah_alat() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kode        = trim($_POST['kode'] ?? '');
            $nama_alat   = trim($_POST['nama_alat'] ?? '');
            $id_kategori = (int)($_POST['id_kategori'] ?? 0);
            $jumlah      = (int)($_POST['jumlah'] ?? 0);
            $kondisi     = trim($_POST['kondisi'] ?? 'Baik');
            $deskripsi   = trim($_POST['deskripsi'] ?? '');

            if (empty($kode)) {
                $kode = 'ALT-' . strtoupper(substr(uniqid(), -5));
            }

            if (!empty($nama_alat) && $id_kategori > 0) {
                try {
                    $this->alatModel->createAlat($kode, $id_kategori, $nama_alat, $jumlah, $kondisi, $deskripsi);
                    $this->userModel->recordLog($this->getUserId(), 'Menambahkan Alat Lab', "Alat: {$nama_alat} (Kode: {$kode}, Jumlah: {$jumlah})");
                    header('Location: index.php?c=admin&a=alat&status=added');
                    exit;
                } catch (Exception $e) {
                    header('Location: index.php?c=admin&a=alat&status=error');
                    exit;
                }
            }
        }
        header('Location: index.php?c=admin&a=alat');
        exit;
    }

    public function ubah_alat() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id          = (int)($_POST['id'] ?? 0);
            $kode        = trim($_POST['kode'] ?? '');
            $nama_alat   = trim($_POST['nama_alat'] ?? '');
            $id_kategori = (int)($_POST['id_kategori'] ?? 0);
            $jumlah      = (int)($_POST['jumlah'] ?? 0);
            $kondisi     = trim($_POST['kondisi'] ?? 'Baik');
            $deskripsi   = trim($_POST['deskripsi'] ?? '');

            if ($id > 0 && !empty($nama_alat) && $id_kategori > 0) {
                try {
                    $this->alatModel->updateAlat($id, $kode, $id_kategori, $nama_alat, $jumlah, $kondisi, $deskripsi);
                    $this->userModel->recordLog($this->getUserId(), 'Mengubah Data Alat Lab', "ID: {$id}, Nama: {$nama_alat}");
                    header('Location: index.php?c=admin&a=alat&status=updated');
                    exit;
                } catch (Exception $e) {
                    header('Location: index.php?c=admin&a=alat&status=error');
                    exit;
                }
            }
        }
        header('Location: index.php?c=admin&a=alat');
        exit;
    }

    public function hapus_alat() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            try {
                $alat = $this->alatModel->getAlatById($id);
                $nama = $alat['nama_alat'] ?? "ID #{$id}";
                $this->alatModel->deleteAlat($id);
                $this->userModel->recordLog($this->getUserId(), 'Menghapus Alat Lab', "Menghapus alat: {$nama}");
                header('Location: index.php?c=admin&a=alat&status=deleted');
                exit;
            } catch (Exception $e) {
                header('Location: index.php?c=admin&a=alat&status=error');
                exit;
            }
        }
        header('Location: index.php?c=admin&a=alat');
        exit;
    }

    // ==========================================
    // CRUD DATA KATEGORI
    // ==========================================
    public function kategori() {
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $message = '';
        $error = '';

        if (isset($_GET['status'])) {
            if ($_GET['status'] === 'added') $message = 'Kategori baru berhasil ditambahkan.';
            if ($_GET['status'] === 'updated') $message = 'Data kategori berhasil diperbarui.';
            if ($_GET['status'] === 'deleted') $message = 'Kategori berhasil dihapus.';
            if ($_GET['status'] === 'error') $error = 'Terjadi kesalahan pada data kategori.';
        }

        $daftarKategori = $this->kategoriModel->getAllKategori($keyword);
        require_once __DIR__ . '/../views/admin_kategori.php';
    }

    public function tambah_kategori() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_kategori = trim($_POST['nama_kategori'] ?? '');
            if (!empty($nama_kategori)) {
                try {
                    $this->kategoriModel->createKategori($nama_kategori);
                    $this->userModel->recordLog($this->getUserId(), 'Menambah Kategori', "Kategori: {$nama_kategori}");
                    header('Location: index.php?c=admin&a=kategori&status=added');
                    exit;
                } catch (Exception $e) {
                    header('Location: index.php?c=admin&a=kategori&status=error');
                    exit;
                }
            }
        }
        header('Location: index.php?c=admin&a=kategori');
        exit;
    }

    public function ubah_kategori() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $nama_kategori = trim($_POST['nama_kategori'] ?? '');

            if ($id > 0 && !empty($nama_kategori)) {
                try {
                    $this->kategoriModel->updateKategori($id, $nama_kategori);
                    $this->userModel->recordLog($this->getUserId(), 'Mengubah Kategori', "ID: {$id}, Nama: {$nama_kategori}");
                    header('Location: index.php?c=admin&a=kategori&status=updated');
                    exit;
                } catch (Exception $e) {
                    header('Location: index.php?c=admin&a=kategori&status=error');
                    exit;
                }
            }
        }
        header('Location: index.php?c=admin&a=kategori');
        exit;
    }

    public function hapus_kategori() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            try {
                $kat = $this->kategoriModel->getKategoriById($id);
                $nama = $kat['nama_kategori'] ?? "ID #{$id}";
                $this->kategoriModel->deleteKategori($id);
                $this->userModel->recordLog($this->getUserId(), 'Menghapus Kategori', "Menghapus kategori: {$nama}");
                header('Location: index.php?c=admin&a=kategori&status=deleted');
                exit;
            } catch (Exception $e) {
                header('Location: index.php?c=admin&a=kategori&status=error');
                exit;
            }
        }
        header('Location: index.php?c=admin&a=kategori');
        exit;
    }

    // ==========================================
    // CRUD DATA PENGGUNA (USER)
    // ==========================================
    public function pengguna() {
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $message = '';
        $error = '';

        if (isset($_GET['status'])) {
            if ($_GET['status'] === 'added') $message = 'Data pengguna baru berhasil dibuat.';
            if ($_GET['status'] === 'updated') $message = 'Data pengguna berhasil diperbarui.';
            if ($_GET['status'] === 'deleted') $message = 'Data pengguna berhasil dihapus.';
            if ($_GET['status'] === 'error') $error = 'Terjadi kesalahan saat memproses pengguna.';
        }

        $daftarPengguna = $this->userModel->getAllUsers($keyword);
        require_once __DIR__ . '/../views/admin_pengguna.php';
    }

    public function tambah_pengguna() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama     = trim($_POST['nama'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $role     = trim($_POST['role'] ?? 'peminjam');

            if (!empty($nama) && !empty($username) && !empty($password)) {
                try {
                    $this->userModel->createUser($nama, $username, $password, $role);
                    $this->userModel->recordLog($this->getUserId(), 'Menambah Pengguna', "User: {$username}, Role: {$role}");
                    header('Location: index.php?c=admin&a=pengguna&status=added');
                    exit;
                } catch (Exception $e) {
                    header('Location: index.php?c=admin&a=pengguna&status=error');
                    exit;
                }
            }
        }
        header('Location: index.php?c=admin&a=pengguna');
        exit;
    }

    public function ubah_pengguna() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_user  = (int)($_POST['id_user'] ?? 0);
            $nama     = trim($_POST['nama'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $role     = trim($_POST['role'] ?? 'peminjam');

            if ($id_user > 0 && !empty($nama) && !empty($username)) {
                try {
                    $this->userModel->updateUser($id_user, $nama, $username, $role, !empty($password) ? $password : null);
                    $this->userModel->recordLog($this->getUserId(), 'Mengubah Pengguna', "User ID: {$id_user}, Username: {$username}");
                    header('Location: index.php?c=admin&a=pengguna&status=updated');
                    exit;
                } catch (Exception $e) {
                    header('Location: index.php?c=admin&a=pengguna&status=error');
                    exit;
                }
            }
        }
        header('Location: index.php?c=admin&a=pengguna');
        exit;
    }

    public function hapus_pengguna() {
        $id_user = (int)($_GET['id'] ?? 0);
        if ($id_user > 0) {
            // Cegah menghapus akun yang sedang login
            if ($id_user == $this->getUserId()) {
                header('Location: index.php?c=admin&a=pengguna&status=error');
                exit;
            }

            try {
                $user = $this->userModel->getUserById($id_user);
                $uname = $user['username'] ?? "ID #{$id_user}";
                $this->userModel->deleteUser($id_user);
                $this->userModel->recordLog($this->getUserId(), 'Menghapus Pengguna', "Menghapus akun: {$uname}");
                header('Location: index.php?c=admin&a=pengguna&status=deleted');
                exit;
            } catch (Exception $e) {
                header('Location: index.php?c=admin&a=pengguna&status=error');
                exit;
            }
        }
        header('Location: index.php?c=admin&a=pengguna');
        exit;
    }

    // ==========================================
    // CRUD DATA PEMINJAMAN
    // ==========================================
    public function peminjaman() {
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $message = '';
        $error = '';

        if (isset($_GET['status'])) {
            if ($_GET['status'] === 'added') $message = 'Peminjaman alat lab berhasil dicatat.';
            if ($_GET['status'] === 'updated') $message = 'Data peminjaman berhasil diperbarui.';
            if ($_GET['status'] === 'deleted') $message = 'Data peminjaman berhasil dihapus.';
            if ($_GET['status'] === 'error') $error = 'Terjadi kesalahan saat memproses data peminjaman.';
        }

        $daftarPeminjaman = $this->peminjamanModel->getAllPeminjaman($keyword);
        $daftarPeminjam = $this->peminjamModel->getAllPeminjam();
        $daftarAlat = $this->alatModel->getAllAlat();

        require_once __DIR__ . '/../views/admin_peminjaman.php';
    }

    public function tambah_peminjaman() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_peminjam             = (int)($_POST['id_peminjam'] ?? 0);
            $id_alat                 = (int)($_POST['id_alat'] ?? 0);
            $jumlah                  = (int)($_POST['jumlah'] ?? 1);
            $waktu_pinjam            = trim($_POST['waktu_pinjam'] ?? date('H:i:s'));
            $waktu_rencana_kembali   = trim($_POST['waktu_rencana_kembali'] ?? date('H:i:s', strtotime('+2 hours')));
            $jenis_peminjaman        = trim($_POST['jenis_peminjaman'] ?? 'Praktek Lab');
            $keperluan               = trim($_POST['keperluan'] ?? '');
            $status                  = trim($_POST['status'] ?? 'disetujui');

            if ($id_peminjam > 0 && $id_alat > 0 && $jumlah > 0) {
                try {
                    $newId = $this->peminjamanModel->createPeminjaman(
                        $id_peminjam,
                        $this->getUserId(),
                        $waktu_pinjam,
                        $waktu_rencana_kembali,
                        $jenis_peminjaman,
                        $keperluan,
                        $id_alat,
                        $jumlah,
                        $status
                    );
                    $this->userModel->recordLog($this->getUserId(), 'Mencatat Peminjaman', "Peminjaman ID #{$newId} (Status: {$status})");
                    header('Location: index.php?c=admin&a=peminjaman&status=added');
                    exit;
                } catch (Exception $e) {
                    header('Location: index.php?c=admin&a=peminjaman&status=error');
                    exit;
                }
            }
        }
        header('Location: index.php?c=admin&a=peminjaman');
        exit;
    }

    public function ubah_peminjaman() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id                      = (int)($_POST['id'] ?? 0);
            $id_peminjam             = (int)($_POST['id_peminjam'] ?? 0);
            $id_alat                 = (int)($_POST['id_alat'] ?? 0);
            $jumlah                  = (int)($_POST['jumlah'] ?? 1);
            $waktu_pinjam            = trim($_POST['waktu_pinjam'] ?? '');
            $waktu_rencana_kembali   = trim($_POST['waktu_rencana_kembali'] ?? '');
            $jenis_peminjaman        = trim($_POST['jenis_peminjaman'] ?? 'Praktek Lab');
            $keperluan               = trim($_POST['keperluan'] ?? '');
            $status                  = trim($_POST['status'] ?? 'menunggu');

            if ($id > 0 && $id_peminjam > 0) {
                try {
                    $this->peminjamanModel->updatePeminjaman(
                        $id,
                        $id_peminjam,
                        $waktu_pinjam,
                        $waktu_rencana_kembali,
                        $jenis_peminjaman,
                        $keperluan,
                        $status,
                        $id_alat > 0 ? $id_alat : null,
                        $jumlah > 0 ? $jumlah : null
                    );
                    $this->userModel->recordLog($this->getUserId(), 'Mengubah Data Peminjaman', "Peminjaman ID #{$id}");
                    header('Location: index.php?c=admin&a=peminjaman&status=updated');
                    exit;
                } catch (Exception $e) {
                    header('Location: index.php?c=admin&a=peminjaman&status=error');
                    exit;
                }
            }
        }
        header('Location: index.php?c=admin&a=peminjaman');
        exit;
    }

    public function hapus_peminjaman() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            try {
                $this->peminjamanModel->deletePeminjaman($id);
                $this->userModel->recordLog($this->getUserId(), 'Menghapus Peminjaman', "ID Peminjaman #{$id}");
                header('Location: index.php?c=admin&a=peminjaman&status=deleted');
                exit;
            } catch (Exception $e) {
                header('Location: index.php?c=admin&a=peminjaman&status=error');
                exit;
            }
        }
        header('Location: index.php?c=admin&a=peminjaman');
        exit;
    }

    // ==========================================
    // CRUD DATA PENGEMBALIAN
    // ==========================================
    public function pengembalian() {
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $message = '';
        $error = '';

        if (isset($_GET['status'])) {
            if ($_GET['status'] === 'added') $message = 'Pengembalian alat lab berhasil dicatat.';
            if ($_GET['status'] === 'updated') $message = 'Data pengembalian berhasil diperbarui.';
            if ($_GET['status'] === 'deleted') $message = 'Data pengembalian berhasil dihapus.';
            if ($_GET['status'] === 'error') $error = 'Terjadi kesalahan saat memproses data pengembalian.';
        }

        $daftarPengembalian = $this->pengembalianModel->getAllPengembalian($keyword);
        $peminjamanAktif = $this->peminjamanModel->getActivePeminjaman();

        require_once __DIR__ . '/../views/admin_pengembalian.php';
    }

    public function tambah_pengembalian() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_peminjaman   = (int)($_POST['id_peminjaman'] ?? 0);
            $waktu_kembali   = trim($_POST['waktu_kembali'] ?? date('H:i:s'));
            $kondisi_kembali = trim($_POST['kondisi_kembali'] ?? 'Baik');
            $denda           = (float)($_POST['denda'] ?? 0);

            if ($id_peminjaman > 0) {
                try {
                    $newId = $this->pengembalianModel->createPengembalian(
                        $id_peminjaman,
                        $waktu_kembali,
                        $kondisi_kembali,
                        $denda,
                        $this->getUserId()
                    );
                    $this->userModel->recordLog($this->getUserId(), 'Mencatat Pengembalian', "Pengembalian ID #{$newId} (Peminjaman #{$id_peminjaman})");
                    header('Location: index.php?c=admin&a=pengembalian&status=added');
                    exit;
                } catch (Exception $e) {
                    header('Location: index.php?c=admin&a=pengembalian&status=error');
                    exit;
                }
            }
        }
        header('Location: index.php?c=admin&a=pengembalian');
        exit;
    }

    public function ubah_pengembalian() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id              = (int)($_POST['id'] ?? 0);
            $waktu_kembali   = trim($_POST['waktu_kembali'] ?? date('H:i:s'));
            $kondisi_kembali = trim($_POST['kondisi_kembali'] ?? 'Baik');
            $denda           = (float)($_POST['denda'] ?? 0);

            if ($id > 0) {
                try {
                    $this->pengembalianModel->updatePengembalian($id, $waktu_kembali, $kondisi_kembali, $denda);
                    $this->userModel->recordLog($this->getUserId(), 'Mengubah Pengembalian', "Pengembalian ID #{$id}");
                    header('Location: index.php?c=admin&a=pengembalian&status=updated');
                    exit;
                } catch (Exception $e) {
                    header('Location: index.php?c=admin&a=pengembalian&status=error');
                    exit;
                }
            }
        }
        header('Location: index.php?c=admin&a=pengembalian');
        exit;
    }

    public function hapus_pengembalian() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            try {
                $this->pengembalianModel->deletePengembalian($id);
                $this->userModel->recordLog($this->getUserId(), 'Menghapus Pengembalian', "Pengembalian ID #{$id}");
                header('Location: index.php?c=admin&a=pengembalian&status=deleted');
                exit;
            } catch (Exception $e) {
                header('Location: index.php?c=admin&a=pengembalian&status=error');
                exit;
            }
        }
        header('Location: index.php?c=admin&a=pengembalian');
        exit;
    }

    // ==========================================
    // LOG AKTIVITAS
    // ==========================================
    public function log() {
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $daftarLog = $this->userModel->getAllLogs($keyword);
        $totalPeminjamanAktif = count($this->peminjamanModel->getActivePeminjaman());
        $daftarPeminjamanAktif = $this->peminjamanModel->getActivePeminjaman();

        require_once __DIR__ . '/../views/admin_log.php';
    }
}

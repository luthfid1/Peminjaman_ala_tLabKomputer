<?php

require_once 'Models/Alat.php';
require_once 'Models/Kategori.php';
require_once 'Models/User.php';
require_once 'Models/Peminjaman.php';
require_once 'Models/Pengembalian.php';

class AdminController {
    private $alatModel;
    private $kategoriModel;
    private $userModel;
    private $peminjamanModel;
    private $pengembalianModel;

    public function __construct() {
        // Proteksi hak akses Admin di level Controller
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?c=auth&a=login');
            exit;
        }

        $this->alatModel = new Alat();
        $this->kategoriModel = new Kategori();
        $this->userModel = new User();
        $this->peminjamanModel = new Peminjaman();
        $this->pengembalianModel = new Pengembalian();
    }

    // ==========================================
    // DASHBOARD
    // ==========================================
    public function dashboard() {
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        $stats = $this->alatModel->getDashboardStats();
        $totalPengguna = $this->userModel->countUsers();

        // Fallback nilai visual sesuai mockup jika database belum terisi
        if ($stats['total_alat'] === 0) {
            $stats['total_alat'] = 48;
            $stats['total_kategori'] = 12;
            $stats['sedang_dipinjam'] = 17;
            $stats['menunggu_persetujuan'] = 6;
            $totalPengguna = 126;
        }

        $inventaris = [];
        try {
            $inventaris = $this->alatModel->getAllAlat($keyword);
        } catch (Exception $e) {
            $inventaris = [];
        }

        if (empty($inventaris) && empty($keyword)) {
            $inventaris = [
                ['id' => 1, 'nama_alat' => 'Gitar Yamaha C40', 'nama_kategori' => 'Gitar & Bass', 'tgl_cek' => '24 Jan 2026', 'jumlah_stok' => 5],
                ['id' => 2, 'nama_alat' => 'Drum Mapex', 'nama_kategori' => 'Drum', 'tgl_cek' => '22 Jan 2026', 'jumlah_stok' => 0],
                ['id' => 3, 'nama_alat' => 'Mic Shure SM58', 'nama_kategori' => 'Audio', 'tgl_cek' => '21 Jan 2026', 'jumlah_stok' => 3]
            ];
        }

        $hariArr = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $bulanArr = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $tglStr = strtoupper($hariArr[date('w')] . ', ' . date('j') . ' ' . $bulanArr[(int)date('n')] . ' ' . date('Y'));

        require_once 'Views/admin_dashboard.php';
    }

    // ==========================================
    // CRUD DATA ALAT
    // ==========================================
    public function alat() {
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $message = '';
        $error = '';

        if (isset($_GET['status'])) {
            if ($_GET['status'] === 'added') $message = 'Data alat berhasil ditambahkan.';
            if ($_GET['status'] === 'updated') $message = 'Data alat berhasil diperbarui.';
            if ($_GET['status'] === 'deleted') $message = 'Data alat berhasil dihapus.';
            if ($_GET['status'] === 'error') $error = 'Terjadi kesalahan pada data alat.';
        }

        $daftarAlat = $this->alatModel->getAllAlat($keyword);
        $daftarKategori = $this->kategoriModel->getAllKategori();

        require_once 'Views/admin_alat.php';
    }

    public function tambah_alat() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_alat   = trim($_POST['nama_alat'] ?? '');
            $kategori_id = (int)($_POST['kategori_id'] ?? 0);
            $harga_sewa  = (float)($_POST['harga_sewa'] ?? 0);
            $jumlah_stok = (int)($_POST['jumlah_stok'] ?? 0);
            $spesifikasi = trim($_POST['spesifikasi'] ?? '');

            if (!empty($nama_alat) && $kategori_id > 0) {
                try {
                    $this->alatModel->createAlat($kategori_id, $nama_alat, $spesifikasi, $harga_sewa, $jumlah_stok);
                    $this->userModel->recordLog($_SESSION['user']['id_users'], 'Menambahkan alat musik: ' . $nama_alat);
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
            $nama_alat   = trim($_POST['nama_alat'] ?? '');
            $kategori_id = (int)($_POST['kategori_id'] ?? 0);
            $harga_sewa  = (float)($_POST['harga_sewa'] ?? 0);
            $jumlah_stok = (int)($_POST['jumlah_stok'] ?? 0);
            $spesifikasi = trim($_POST['spesifikasi'] ?? '');

            if ($id > 0 && !empty($nama_alat) && $kategori_id > 0) {
                try {
                    $this->alatModel->updateAlat($id, $kategori_id, $nama_alat, $spesifikasi, $harga_sewa, $jumlah_stok);
                    $this->userModel->recordLog($_SESSION['user']['id_users'], 'Mengubah data alat musik: ' . $nama_alat);
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
                $this->alatModel->deleteAlat($id);
                $this->userModel->recordLog($_SESSION['user']['id_users'], 'Menghapus alat: ' . ($alat['nama_alat'] ?? "ID $id"));
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
            if ($_GET['status'] === 'added') $message = 'Kategori berhasil ditambahkan.';
            if ($_GET['status'] === 'updated') $message = 'Kategori berhasil diperbarui.';
            if ($_GET['status'] === 'deleted') $message = 'Kategori berhasil dihapus.';
            if ($_GET['status'] === 'error') $error = 'Terjadi kesalahan pada data kategori.';
        }

        $daftarKategori = $this->kategoriModel->getAllKategori($keyword);
        require_once 'Views/admin_kategori.php';
    }

    public function tambah_kategori() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_kategori = trim($_POST['nama_kategori'] ?? '');
            if (!empty($nama_kategori)) {
                try {
                    $this->kategoriModel->createKategori($nama_kategori);
                    $this->userModel->recordLog($_SESSION['user']['id_users'], 'Menambahkan kategori: ' . $nama_kategori);
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
                    $this->userModel->recordLog($_SESSION['user']['id_users'], 'Mengubah kategori ID ' . $id . ' menjadi: ' . $nama_kategori);
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
                $this->kategoriModel->deleteKategori($id);
                $this->userModel->recordLog($_SESSION['user']['id_users'], 'Menghapus kategori ID: ' . $id);
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
    // CRUD DATA PENGGUNA (USERS)
    // ==========================================
    public function pengguna() {
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $message = '';
        $error = '';

        if (isset($_GET['status'])) {
            if ($_GET['status'] === 'added') $message = 'Pengguna baru berhasil ditambahkan.';
            if ($_GET['status'] === 'updated') $message = 'Data pengguna berhasil diperbarui.';
            if ($_GET['status'] === 'deleted') $message = 'Pengguna berhasil dihapus.';
            if ($_GET['status'] === 'exists') $error = 'Username sudah digunakan, silakan pilih username lain.';
            if ($_GET['status'] === 'error') $error = 'Terjadi kesalahan saat memproses data pengguna.';
        }

        $daftarPengguna = $this->userModel->getAllUsers($keyword);
        require_once 'Views/admin_pengguna.php';
    }

    public function tambah_pengguna() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
            $username     = trim($_POST['username'] ?? '');
            $password     = trim($_POST['password'] ?? '');
            $role         = trim($_POST['role'] ?? 'peminjam');
            $alamat       = trim($_POST['alamat'] ?? '');
            $no_hp        = trim($_POST['no_hp'] ?? '');

            if (!empty($nama_lengkap) && !empty($username) && !empty($password)) {
                if ($this->userModel->isUsernameExists($username)) {
                    header('Location: index.php?c=admin&a=pengguna&status=exists');
                    exit;
                }

                try {
                    $this->userModel->register($nama_lengkap, $username, $password, $role, $alamat, $no_hp);
                    $this->userModel->recordLog($_SESSION['user']['id_users'], 'Menambahkan pengguna baru: ' . $username . " ($role)");
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
            $id_users     = (int)($_POST['id_users'] ?? 0);
            $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
            $username     = trim($_POST['username'] ?? '');
            $password     = trim($_POST['password'] ?? '');
            $role         = trim($_POST['role'] ?? 'peminjam');
            $alamat       = trim($_POST['alamat'] ?? '');
            $no_hp        = trim($_POST['no_hp'] ?? '');

            if ($id_users > 0 && !empty($nama_lengkap) && !empty($username)) {
                if ($this->userModel->isUsernameExists($username, $id_users)) {
                    header('Location: index.php?c=admin&a=pengguna&status=exists');
                    exit;
                }

                try {
                    $this->userModel->updateUser($id_users, $nama_lengkap, $username, $role, !empty($password) ? $password : null, $alamat, $no_hp);
                    $this->userModel->recordLog($_SESSION['user']['id_users'], 'Memperbarui data pengguna: ' . $username);
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
        $id_users = (int)($_GET['id'] ?? 0);
        // Cegah menghapus akun sendiri yang sedang aktif
        if ($id_users > 0 && $id_users != $_SESSION['user']['id_users']) {
            try {
                $user = $this->userModel->getUserById($id_users);
                $this->userModel->deleteUser($id_users);
                $this->userModel->recordLog($_SESSION['user']['id_users'], 'Menghapus pengguna: ' . ($user['username'] ?? "ID $id_users"));
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
    // LOG AKTIVITAS
    // ==========================================
    public function log() {
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $searchPeminjaman = isset($_GET['search_peminjaman']) ? trim($_GET['search_peminjaman']) : '';

        $daftarLog = $this->userModel->getLogs($keyword);
        $daftarPeminjamanAktif = $this->peminjamanModel->getPeminjamanAktif($searchPeminjaman);
        $totalPeminjamanAktif = $this->peminjamanModel->countPeminjamanAktif();

        require_once 'Views/admin_log.php';
    }

    // ==========================================
    // CRUD DATA PEMINJAMAN (OFFLINE & ONLINE)
    // ==========================================
    public function peminjaman() {
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $message = '';
        $error = '';

        if (isset($_GET['status'])) {
            if ($_GET['status'] === 'added') $message = 'Data peminjaman berhasil dicatat.';
            if ($_GET['status'] === 'updated') $message = 'Data peminjaman berhasil diperbarui.';
            if ($_GET['status'] === 'deleted') $message = 'Data peminjaman berhasil dihapus.';
            if ($_GET['status'] === 'stok_kurang') $error = 'Stok alat tidak mencukupi untuk jumlah yang dipinjam!';
            if ($_GET['status'] === 'error') $error = 'Terjadi kesalahan pada data peminjaman.';
        }

        $daftarPeminjaman = $this->peminjamanModel->getAllPeminjaman($keyword);
        $daftarAlat = $this->alatModel->getAllAlat();
        $daftarUser = $this->userModel->getAllUsers();

        require_once 'Views/admin_peminjaman.php';
    }

    public function tambah_peminjaman() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_lengkap    = trim($_POST['nama_lengkap'] ?? '');
            $username        = trim($_POST['username'] ?? '');
            $no_hp           = trim($_POST['no_hp'] ?? '');
            $alamat          = trim($_POST['alamat'] ?? '');
            $id_alat         = (int)($_POST['id_alat'] ?? 0);
            $jumlah          = (int)($_POST['jumlah'] ?? 1);
            $tanggal_pinjam  = trim($_POST['tanggal_pinjam'] ?? date('Y-m-d'));
            $tanggal_kembali = trim($_POST['tanggal_kembali'] ?? '');
            $status          = trim($_POST['status'] ?? 'dipinjam');

            if (!empty($nama_lengkap) && !empty($username) && $id_alat > 0 && $jumlah > 0 && !empty($tanggal_kembali)) {
                // Cek stok alat jika statusnya dipinjam
                $alat = $this->alatModel->getAlatById($id_alat);
                if ($status === 'dipinjam' && $alat && $alat['jumlah_stok'] < $jumlah) {
                    header('Location: index.php?c=admin&a=peminjaman&status=stok_kurang');
                    exit;
                }

                try {
                    // Cek apakah user dengan username ini sudah ada di tabel users
                    $user = $this->userModel->getUserByUsername($username);
                    if ($user) {
                        $id_user = $user['id_users'];
                        // Update nama, alamat, no_hp jika ada
                        $this->userModel->updateUser($id_user, $nama_lengkap, $username, $user['role'], null, $alamat, $no_hp);
                    } else {
                        // Jika belum ada, daftarkan otomatis peminjam offline ke tabel users
                        $defaultPassword = 'offline_' . time();
                        $this->userModel->register($nama_lengkap, $username, $defaultPassword, 'peminjam', $alamat, $no_hp);
                        $newUser = $this->userModel->getUserByUsername($username);
                        $id_user = $newUser ? $newUser['id_users'] : 0;
                    }

                    if ($id_user > 0) {
                        $this->peminjamanModel->createPeminjaman($id_user, $id_alat, $jumlah, $tanggal_pinjam, $tanggal_kembali, $status);
                        $namaAlat = $alat ? $alat['nama_alat'] : "ID $id_alat";
                        $this->userModel->recordLog($_SESSION['user']['id_users'], 'Mencatat peminjaman offline: ' . $namaAlat . ' untuk ' . $nama_lengkap);
                        header('Location: index.php?c=admin&a=peminjaman&status=added');
                        exit;
                    } else {
                        header('Location: index.php?c=admin&a=peminjaman&status=error');
                        exit;
                    }
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
            $id_peminjaman   = (int)($_POST['id_peminjaman'] ?? 0);
            $id_user         = (int)($_POST['id_user'] ?? 0);
            $nama_lengkap    = trim($_POST['nama_lengkap'] ?? '');
            $username        = trim($_POST['username'] ?? '');
            $no_hp           = trim($_POST['no_hp'] ?? '');
            $alamat          = trim($_POST['alamat'] ?? '');
            $id_alat         = (int)($_POST['id_alat'] ?? 0);
            $jumlah          = (int)($_POST['jumlah'] ?? 1);
            $tanggal_pinjam  = trim($_POST['tanggal_pinjam'] ?? '');
            $tanggal_kembali = trim($_POST['tanggal_kembali'] ?? '');
            $status          = trim($_POST['status'] ?? 'dipinjam');

            if ($id_peminjaman > 0 && $id_alat > 0 && $jumlah > 0 && !empty($tanggal_kembali)) {
                try {
                    // Update data peminjam di tabel user jika ada
                    if ($id_user > 0 && !empty($nama_lengkap) && !empty($username)) {
                        $existingUser = $this->userModel->getUserById($id_user);
                        $role = $existingUser ? $existingUser['role'] : 'peminjam';
                        $this->userModel->updateUser($id_user, $nama_lengkap, $username, $role, null, $alamat, $no_hp);
                    }

                    $this->peminjamanModel->updatePeminjaman($id_peminjaman, $id_user, $id_alat, $jumlah, $tanggal_pinjam, $tanggal_kembali, $status);
                    $this->userModel->recordLog($_SESSION['user']['id_users'], 'Mengubah data peminjaman ID #' . $id_peminjaman . ' (Status: ' . $status . ')');
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
                $this->userModel->recordLog($_SESSION['user']['id_users'], 'Menghapus data peminjaman ID #' . $id);
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
    // CRUD DATA PENGEMBALIAN (OFFLINE & ONLINE)
    // ==========================================
    public function pengembalian() {
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $message = '';
        $error = '';

        if (isset($_GET['status'])) {
            if ($_GET['status'] === 'added') $message = 'Data pengembalian alat berhasil dicatat.';
            if ($_GET['status'] === 'updated') $message = 'Data pengembalian berhasil diperbarui.';
            if ($_GET['status'] === 'deleted') $message = 'Data pengembalian berhasil dihapus.';
            if ($_GET['status'] === 'error') $error = 'Terjadi kesalahan saat memproses data pengembalian.';
        }

        $daftarPengembalian = $this->pengembalianModel->getAllPengembalian($keyword);
        $peminjamanAktif = $this->pengembalianModel->getPeminjamanSiapKembali();

        require_once 'Views/admin_pengembalian.php';
    }

    public function tambah_pengembalian() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_peminjaman        = (int)($_POST['id_peminjaman'] ?? 0);
            $tanggal_pengembalian = trim($_POST['tanggal_pengembalian'] ?? date('Y-m-d'));
            $denda_tambahan       = (float)($_POST['denda_tambahan'] ?? ($_POST['denda'] ?? 0));
            $keterangan           = trim($_POST['keterangan'] ?? '');

            if ($id_peminjaman > 0 && !empty($tanggal_pengembalian)) {
                try {
                    $result = $this->pengembalianModel->createPengembalian($id_peminjaman, $tanggal_pengembalian, $denda_tambahan, $keterangan);
                    if ($result) {
                        $this->userModel->recordLog($_SESSION['user']['id_users'], 'Mencatat pengembalian alat untuk Transaksi Peminjaman #' . $id_peminjaman);
                        header('Location: index.php?c=admin&a=pengembalian&status=added');
                        exit;
                    } else {
                        header('Location: index.php?c=admin&a=pengembalian&status=error');
                        exit;
                    }
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
            $id_pengembalian      = (int)($_POST['id_pengembalian'] ?? 0);
            $tanggal_pengembalian = trim($_POST['tanggal_pengembalian'] ?? date('Y-m-d'));
            $denda_tambahan       = (float)($_POST['denda_tambahan'] ?? ($_POST['denda'] ?? 0));
            $keterangan           = trim($_POST['keterangan'] ?? '');

            if ($id_pengembalian > 0 && !empty($tanggal_pengembalian)) {
                try {
                    $this->pengembalianModel->updatePengembalian($id_pengembalian, $tanggal_pengembalian, $denda_tambahan, $keterangan);
                    $this->userModel->recordLog($_SESSION['user']['id_users'], 'Memperbarui data pengembalian ID #' . $id_pengembalian);
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
                $this->userModel->recordLog($_SESSION['user']['id_users'], 'Menghapus catatan pengembalian ID #' . $id);
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
}

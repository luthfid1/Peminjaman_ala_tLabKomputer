<?php

require_once 'Models/User.php';
require_once 'Models/Alat.php';
require_once 'Models/Kategori.php';
require_once 'Models/Peminjaman.php';
require_once 'Models/Pengembalian.php';
require_once 'Models/Pembayaran.php';

class PeminjamController {
    private $userModel;
    private $alatModel;
    private $kategoriModel;
    private $peminjamanModel;
    private $pengembalianModel;
    private $pembayaranModel;

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
        $this->pembayaranModel   = new Pembayaran();
    }

    // ==========================================
    // 1. DASHBOARD PEMINJAM
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
    // 2. DAFTAR ALAT MUSIK (KATALOG LENGKAP)
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

        require_once 'Views/peminjam_alat.php';
    }

    public function katalog() {
        $this->daftar_alat();
    }

    // ==========================================
    // 3. PEMINJAMAN SAYA & RIWAYAT PENGAJUAN
    // ==========================================
    public function peminjaman() {
        $userId = $_SESSION['user']['id_users'];
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';

        $message = '';
        $error = '';
        if (isset($_GET['status'])) {
            if ($_GET['status'] === 'requested') $message = 'Pengajuan peminjaman dan catatan pembayaran berhasil dibuat! Mohon menunggu persetujuan petugas.';
            if ($_GET['status'] === 'bukti_uploaded') $message = 'Bukti pembayaran transfer berhasil diunggah! Status pembayaran sedang menunggu konfirmasi admin/petugas.';
            if ($_GET['status'] === 'stok_kurang') $error = 'Stok alat tidak mencukupi untuk jumlah yang Anda minta!';
            if ($_GET['status'] === 'cancelled') $message = 'Pengajuan peminjaman berhasil dibatalkan.';
            if ($_GET['status'] === 'error') $error = 'Gagal memproses permintaan peminjaman.';
        }

        $daftarPeminjaman = $this->peminjamanModel->getPeminjamanByUser($userId, $keyword);
        $daftarAlat = $this->alatModel->getAllAlat();

        require_once 'Views/peminjam_peminjaman.php';
    }

    // ==========================================
    // PROSES AJUKAN PEMINJAMAN & PEMBAYARAN
    // ==========================================
    public function ajukan_peminjaman() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId           = $_SESSION['user']['id_users'];
            $id_alat          = (int)($_POST['id_alat'] ?? 0);
            $jumlah           = (int)($_POST['jumlah'] ?? 1);
            $tanggal_pinjam   = trim($_POST['tanggal_pinjam'] ?? date('Y-m-d'));
            $tanggal_kembali  = trim($_POST['tanggal_kembali'] ?? '');
            $metodePembayaran = trim($_POST['metode_pembayaran'] ?? 'di_tempat');

            if (!in_array($metodePembayaran, ['di_tempat', 'website'])) {
                $metodePembayaran = 'di_tempat';
            }

            if ($id_alat > 0 && $jumlah > 0 && !empty($tanggal_kembali)) {
                $alat = $this->alatModel->getAlatById($id_alat);
                if (!$alat || $alat['jumlah_stok'] < $jumlah) {
                    header('Location: index.php?c=peminjam&a=peminjaman&status=stok_kurang');
                    exit;
                }

                // Kalkulasi durasi dan total biaya sewa
                $d1 = strtotime($tanggal_pinjam);
                $d2 = strtotime($tanggal_kembali);
                $diffDays = ($d2 >= $d1) ? max(1, (int)round(($d2 - $d1) / 86400)) : 1;
                $hargaPerHari = (float)($alat['harga_sewa'] ?? 0);
                $totalBayar = $hargaPerHari * $jumlah * $diffDays;

                // Handle upload bukti pembayaran jika online (website)
                $namaFileBukti = null;
                $statusPembayaran = 'menunggu_pembayaran';
                $tglPembayaran = null;

                if ($metodePembayaran === 'website' && isset($_FILES['bukti_pembayaran']) && $_FILES['bukti_pembayaran']['error'] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($_FILES['bukti_pembayaran']['name'], PATHINFO_EXTENSION));
                    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'webp'];
                    if (in_array($ext, $allowed)) {
                        $targetDir = 'Assets/uploads/bukti/';
                        if (!is_dir($targetDir)) {
                            mkdir($targetDir, 0777, true);
                        }
                        $namaFileBukti = 'bukti_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                        if (move_uploaded_file($_FILES['bukti_pembayaran']['tmp_name'], $targetDir . $namaFileBukti)) {
                            $statusPembayaran = 'menunggu_konfirmasi';
                            $tglPembayaran = date('Y-m-d H:i:s');
                        } else {
                            $namaFileBukti = null;
                        }
                    }
                }

                try {
                    // Simpan peminjaman dengan status default 'menunggu'
                    $idPeminjaman = $this->peminjamanModel->createPeminjaman($userId, $id_alat, $jumlah, $tanggal_pinjam, $tanggal_kembali, 'menunggu');
                    if ($idPeminjaman) {
                        // Catat pembayaran transaksi
                        $this->pembayaranModel->createPembayaran(
                            $idPeminjaman,
                            $totalBayar,
                            $metodePembayaran,
                            $statusPembayaran,
                            $namaFileBukti,
                            $tglPembayaran
                        );

                        $metodeLabel = ($metodePembayaran === 'website') ? 'Transfer Website' : 'Di Tempat (Offline)';
                        $this->userModel->recordLog($userId, 'Mengajukan peminjaman alat: ' . ($alat['nama_alat'] ?? "Alat ID $id_alat") . " ($jumlah unit, $metodeLabel)");
                        header('Location: index.php?c=peminjam&a=peminjaman&status=requested');
                        exit;
                    } else {
                        header('Location: index.php?c=peminjam&a=peminjaman&status=error');
                        exit;
                    }
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
    // UPLOAD BUKTI PEMBAYARAN TRANSFER (SUSULAN)
    // ==========================================
    public function upload_bukti() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId       = $_SESSION['user']['id_users'];
            $idPeminjaman = (int)($_POST['id_peminjaman'] ?? 0);

            if ($idPeminjaman > 0 && isset($_FILES['bukti_transfer']) && $_FILES['bukti_transfer']['error'] === UPLOAD_ERR_OK) {
                // Pastikan transaksi ini milik user
                $peminjaman = $this->peminjamanModel->getPeminjamanById($idPeminjaman);
                if ($peminjaman && (int)$peminjaman['id_user'] === (int)$userId) {
                    $ext = strtolower(pathinfo($_FILES['bukti_transfer']['name'], PATHINFO_EXTENSION));
                    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'webp'];
                    if (in_array($ext, $allowed)) {
                        $targetDir = 'Assets/uploads/bukti/';
                        if (!is_dir($targetDir)) {
                            mkdir($targetDir, 0777, true);
                        }
                        $namaFile = 'bukti_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                        if (move_uploaded_file($_FILES['bukti_transfer']['tmp_name'], $targetDir . $namaFile)) {
                            $this->pembayaranModel->uploadBukti($idPeminjaman, $namaFile);
                            $this->userModel->recordLog($userId, "Mengunggah bukti pembayaran untuk peminjaman #$idPeminjaman");
                            header('Location: index.php?c=peminjam&a=peminjaman&status=bukti_uploaded');
                            exit;
                        }
                    }
                }
            }
        }
        header('Location: index.php?c=peminjam&a=peminjaman');
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
    // KEMBALIKAN ALAT MUSIK (PEMINJAM)
    // ==========================================
    public function kembalikan_alat() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId            = $_SESSION['user']['id_users'];
            $id_peminjaman     = (int)($_POST['id_peminjaman'] ?? 0);
            $tanggalPengembalian = trim($_POST['tanggal_pengembalian'] ?? date('Y-m-d'));
            $keterangan        = trim($_POST['keterangan'] ?? 'Pengembalian oleh peminjam');

            if ($id_peminjaman > 0) {
                try {
                    $peminjaman = $this->peminjamanModel->getPeminjamanById($id_peminjaman);
                    if ($peminjaman && $peminjaman['id_user'] == $userId && $peminjaman['status'] === 'dipinjam') {
                        $result = $this->pengembalianModel->createPengembalian($id_peminjaman, $tanggalPengembalian, 0, $keterangan);
                        if ($result) {
                            $this->userModel->recordLog($userId, 'Mengembalikan alat musik: ' . ($peminjaman['nama_alat'] ?? "Alat ID {$peminjaman['id_alat']}"));
                            header('Location: index.php?c=peminjam&a=pengembalian&status=returned');
                            exit;
                        }
                    }
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
    // RIWAYAT PENGEMBALIAN SAYA & INFO DENDA
    // ==========================================
    public function pengembalian() {
        $userId = $_SESSION['user']['id_users'];
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';

        $message = '';
        if (isset($_GET['status']) && $_GET['status'] === 'returned') {
            $message = 'Alat musik berhasil dikembalikan! Riwayat transaksi pengembalian Anda telah diperbarui.';
        }

        $daftarPengembalian = $this->pengembalianModel->getPengembalianByUser($userId, $keyword);
        $totalDenda = $this->pengembalianModel->getTotalDendaByUser($userId);

        require_once 'Views/peminjam_pengembalian.php';
    }

}

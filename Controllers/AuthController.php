<?php

require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/peminjam.php';

class AuthController {
    private $userModel;
    private $peminjamModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = new User();
        $this->peminjamModel = new Peminjam();
    }

    public function login() {
        if (isset($_SESSION['user']) && $_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_GET['switch'])) {
            $this->redirectByRole($_SESSION['user']['role']);
            return;
        }

        $error = '';
        $success = '';

        if (isset($_GET['status']) && $_GET['status'] === 'registered') {
            $success = 'Pendaftaran berhasil! Akun Anda telah aktif, silakan masuk.';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($username) || empty($password)) {
                $error = 'Harap isi username dan kata sandi!';
            } else {
                try {
                    $user = $this->userModel->getUserByUsername($username);

                    if ($user) {
                        $isPasswordValid = password_verify($password, $user['password']) 
                                           || $user['password'] === md5($password) 
                                           || $user['password'] === sha1($password)
                                           || $user['password'] === $password;

                        if ($isPasswordValid) {
                            $userId = (int)($user['id_user'] ?? $user['id_users'] ?? 0);
                            $namaUser = $user['nama'] ?? $user['nama_lengkap'] ?? $user['username'];
                            $role = strtolower(trim($user['role'] ?? 'peminjam'));

                            unset($_SESSION['user']);
                            $_SESSION['user'] = [
                                'id_user'      => $userId,
                                'id_users'     => $userId,
                                'username'     => $user['username'],
                                'nama'         => $namaUser,
                                'nama_lengkap' => $namaUser,
                                'role'         => $role
                            ];

                            $this->userModel->recordLog($userId, 'Login ke Sistem', "Login berhasil sebagai {$role}");
                            $this->redirectByRole($role);
                            return;
                        } else {
                            $error = 'Kata sandi yang Anda masukkan salah!';
                        }
                    } else {
                        $error = 'Username "' . htmlspecialchars($username) . '" tidak ditemukan!';
                    }
                } catch (Exception $e) {
                    $error = 'Terjadi kesalahan sistem: ' . $e->getMessage();
                }
            }
        }

        require_once __DIR__ . '/../views/login.php';
    }

    public function register() {
        if (isset($_SESSION['user']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectByRole($_SESSION['user']['role']);
            return;
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama             = trim($_POST['nama'] ?? '');
            $nis              = trim($_POST['nis'] ?? '');
            $kelas            = trim($_POST['kelas'] ?? '');
            $jurusan          = trim($_POST['jurusan'] ?? '');
            $no_telp          = trim($_POST['no_telp'] ?? '');
            $username         = trim($_POST['username'] ?? '');
            $password         = trim($_POST['password'] ?? '');
            $confirm_password = trim($_POST['confirm_password'] ?? '');

            if (empty($nama) || empty($nis) || empty($kelas) || empty($jurusan) || empty($username) || empty($password)) {
                $error = 'Semua bidang formulir wajib diisi!';
            } elseif (strlen($username) < 3) {
                $error = 'Username minimal 3 karakter!';
            } elseif (strlen($password) < 5) {
                $error = 'Kata sandi minimal 5 karakter!';
            } elseif ($password !== $confirm_password) {
                $error = 'Konfirmasi kata sandi tidak cocok!';
            } elseif ($this->userModel->getUserByUsername($username)) {
                $error = 'Username "' . htmlspecialchars($username) . '" sudah digunakan. Silakan pilih username lain!';
            } else {
                try {
                    // Upload foto kartu pelajar jika diunggah
                    $foto_kartu = '';
                    if (isset($_FILES['foto_kartu_pelajar']) && $_FILES['foto_kartu_pelajar']['error'] === UPLOAD_ERR_OK) {
                        $fileTmp  = $_FILES['foto_kartu_pelajar']['tmp_name'];
                        $fileName = $_FILES['foto_kartu_pelajar']['name'];
                        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

                        if (in_array($ext, $allowed)) {
                            $newFileName = 'kartu_' . time() . '_' . rand(100, 999) . '.' . $ext;
                            $uploadDir = __DIR__ . '/../Assets/uploads/kartu/';
                            if (!is_dir($uploadDir)) {
                                mkdir($uploadDir, 0777, true);
                            }
                            if (move_uploaded_file($fileTmp, $uploadDir . $newFileName)) {
                                $foto_kartu = $newFileName;
                            }
                        }
                    }

                    // 1. Simpan akun ke tabel user
                    $userId = $this->userModel->createUser($nama, $username, $password, 'peminjam');

                    // 2. Simpan profil siswa ke tabel peminjam
                    $this->peminjamModel->createPeminjam($nama, $nis, $kelas, $jurusan, $no_telp, $foto_kartu);

                    // 3. Catat riwayat log aktivitas
                    if ($userId) {
                        $this->userModel->recordLog($userId, 'Registrasi Akun Siswa', "Pendaftaran siswa baru: {$nama} (NIS: {$nis})");
                    }

                    header('Location: index.php?c=auth&a=login&status=registered');
                    exit;
                } catch (Exception $e) {
                    $error = 'Terjadi kesalahan database: ' . $e->getMessage();
                }
            }
        }

        require_once __DIR__ . '/../views/register.php';
    }

    public function logout() {
        if (isset($_SESSION['user'])) {
            $uid = $_SESSION['user']['id_user'] ?? $_SESSION['user']['id_users'] ?? null;
            if ($uid) {
                $this->userModel->recordLog($uid, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna');
            }
        }
        session_unset();
        session_destroy();
        header('Location: index.php?c=auth&a=login');
        exit;
    }

    private function redirectByRole($role) {
        $role = strtolower(trim($role ?? ''));
        if ($role === 'admin pengelola' || $role === 'pengelola') {
            header('Location: index.php?c=admin&a=dashboard');
            exit;
        } elseif ($role === 'admin') {
            header('Location: index.php?c=kejur&a=dashboard');
            exit;
        } elseif ($role === 'petugas') {
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
}

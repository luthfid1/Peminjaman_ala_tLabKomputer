<?php

require_once 'Models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
        // Siapkan akun admin default (Alya Rahma / admin / admin123) jika belum ada
        $this->userModel->createDefaultAdminIfNone();
    }

    // Menampilkan & memproses form login
    public function login() {
        if (isset($_SESSION['user'])) {
            $this->redirectByRole($_SESSION['user']['role']);
            return;
        }

        $error = '';
        $success = '';

        if (isset($_GET['status']) && $_GET['status'] === 'registered') {
            $success = 'Pendaftaran berhasil! Silakan masuk dengan akun baru Anda.';
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
                        // Verifikasi password hash atau fallback
                        $isPasswordValid = password_verify($password, $user['password']) 
                                           || $user['password'] === md5($password) 
                                           || $user['password'] === $password;

                        if ($isPasswordValid) {
                            // Simpan data login ke session
                            $_SESSION['user'] = [
                                'id_users'     => $user['id_users'],
                                'username'     => $user['username'],
                                'nama_lengkap' => $user['nama_lengkap'],
                                'role'         => $user['role']
                            ];

                            // Catat log aktivitas login
                            $this->userModel->recordLog($user['id_users'], 'Login ke sistem');

                            // Redirect sesuai role
                            $this->redirectByRole($user['role']);
                            return;
                        } else {
                            $error = 'Kata sandi yang Anda masukkan salah!';
                        }
                    } else {
                        $error = 'Username tidak ditemukan!';
                    }
                } catch (Exception $e) {
                    $error = 'Terjadi kesalahan sistem: ' . $e->getMessage();
                }
            }
        }

        require_once 'Views/login.php';
    }

    // Menampilkan & memproses form registrasi
    public function register() {
        if (isset($_SESSION['user'])) {
            $this->redirectByRole($_SESSION['user']['role']);
            return;
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_lengkap     = trim($_POST['nama_lengkap'] ?? '');
            $username         = trim($_POST['username'] ?? '');
            $password         = trim($_POST['password'] ?? '');
            $confirm_password = trim($_POST['confirm_password'] ?? '');
            $alamat           = trim($_POST['alamat'] ?? '');

            if (empty($nama_lengkap) || empty($username) || empty($password) || empty($confirm_password) || empty($alamat)) {
                $error = 'Semua bidang formulir wajib diisi!';
            } elseif (strlen($username) < 4) {
                $error = 'Username minimal 4 karakter!';
            } elseif (strlen($password) < 5) {
                $error = 'Kata sandi minimal 5 karakter!';
            } elseif ($password !== $confirm_password) {
                $error = 'Konfirmasi kata sandi tidak cocok!';
            } elseif ($this->userModel->isUsernameExists($username)) {
                $error = 'Username sudah digunakan, silakan pilih username lain!';
            } else {
                try {
                    $success = $this->userModel->register($nama_lengkap, $username, $password, 'peminjam', $alamat);
                    if ($success) {
                        header('Location: index.php?c=auth&a=login&status=registered');
                        exit;
                    } else {
                        $error = 'Gagal mendaftar akun. Silakan coba lagi.';
                    }
                } catch (Exception $e) {
                    $error = 'Terjadi kesalahan database: ' . $e->getMessage();
                }
            }
        }

        require_once 'Views/register.php';
    }

    // Proses logout
    public function logout() {
        if (isset($_SESSION['user'])) {
            $this->userModel->recordLog($_SESSION['user']['id_users'], 'Logout dari sistem');
        }
        session_unset();
        session_destroy();
        header('Location: index.php?c=auth&a=login');
        exit;
    }

    // Pengalihan hak akses / proteksi halaman sesuai role
    private function redirectByRole($role) {
        if ($role === 'admin' && file_exists('Controllers/AdminController.php')) {
            header('Location: index.php?c=admin&a=dashboard');
            exit;
        } elseif ($role === 'petugas' && file_exists('Controllers/PetugasController.php')) {
            header('Location: index.php?c=petugas&a=dashboard');
            exit;
        } elseif ($role === 'peminjam' && file_exists('Controllers/PeminjamController.php')) {
            header('Location: index.php?c=peminjam&a=dashboard');
            exit;
        } else {
            header('Location: index.php');
            exit;
        }
    }
}

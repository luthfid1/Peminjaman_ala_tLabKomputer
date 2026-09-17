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
        // Jika sudah login dan bukan submit form login, redirect ke halaman sesuai role
        if (isset($_SESSION['user']) && $_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_GET['switch'])) {
            $this->redirectByRole($_SESSION['user']['role']);
            return;
        }

        $error = '';
        $success = '';

        if (isset($_GET['status']) && $_GET['status'] === 'registered') {
            $success = 'Pendaftaran berhasil! Silakan masuk dengan akun yang baru Anda daftarkan.';
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
                        // Verifikasi password hash atau fallback format lama
                        $isPasswordValid = password_verify($password, $user['password']) 
                                           || $user['password'] === md5($password) 
                                           || $user['password'] === sha1($password)
                                           || $user['password'] === $password;

                        if ($isPasswordValid) {
                            // Re-hash password jika sebelumnya md5/plaintext atau hash lama
                            if (password_needs_rehash($user['password'], PASSWORD_DEFAULT) || $user['password'] === $password || $user['password'] === md5($password)) {
                                $newHash = password_hash($password, PASSWORD_DEFAULT);
                                $this->userModel->updatePasswordOnly($user['id_users'], $newHash);
                            }

                            // Bersihkan session lama dan simpan data login baru
                            unset($_SESSION['user']);
                            $_SESSION['user'] = [
                                'id_users'     => (int)$user['id_users'],
                                'username'     => $user['username'],
                                'nama_lengkap' => $user['nama_lengkap'],
                                'role'         => strtolower(trim($user['role'] ?? 'peminjam'))
                            ];

                            // Catat log aktivitas login
                            $this->userModel->recordLog($user['id_users'], 'Login ke sistem');

                            // Redirect sesuai role
                            $this->redirectByRole($_SESSION['user']['role']);
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

        require_once 'Views/login.php';
    }

    // Menampilkan & memproses form registrasi
    public function register() {
        if (isset($_SESSION['user']) && $_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_GET['new'])) {
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
            $no_hp            = trim($_POST['no_hp'] ?? '');

            if (empty($nama_lengkap) || empty($username) || empty($password) || empty($confirm_password) || empty($alamat)) {
                $error = 'Semua bidang formulir bertanda wajib diisi!';
            } elseif (strlen($username) < 3) {
                $error = 'Username minimal 3 karakter!';
            } elseif (strlen($password) < 4) {
                $error = 'Kata sandi minimal 4 karakter!';
            } elseif ($password !== $confirm_password) {
                $error = 'Konfirmasi kata sandi tidak cocok!';
            } elseif ($this->userModel->isUsernameExists($username)) {
                $error = 'Username "' . htmlspecialchars($username) . '" sudah digunakan, silakan pilih username lain!';
            } else {
                try {
                    // Reset session jika ada
                    if (isset($_SESSION['user'])) {
                        unset($_SESSION['user']);
                    }

                    $success = $this->userModel->register($nama_lengkap, $username, $password, 'peminjam', $alamat, $no_hp);
                    if ($success) {
                        header('Location: index.php?c=auth&a=login&status=registered');
                        exit;
                    } else {
                        $error = 'Gagal mendaftar akun. Silakan periksa data Anda.';
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
        $role = strtolower(trim($role ?? ''));
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

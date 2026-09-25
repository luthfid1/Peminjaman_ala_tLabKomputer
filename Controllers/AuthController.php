<?php

require_once __DIR__ . '/../Models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = new User();
    }

    public function login() {
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

        require_once __DIR__ . '/../Views/login.php';
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
        if ($role === 'pengelola' || $role === 'admin') {
            header('Location: index.php?c=admin&a=dashboard');
            exit;
        } elseif ($role === 'petugas') {
            header('Location: index.php?c=admin&a=peminjaman');
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

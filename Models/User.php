<?php

require_once 'Models/Database.php';

class User {
    private $conn;
    private $table_name = "users";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Mengambil user berdasarkan username
    public function getUserByUsername($username) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mengambil user berdasarkan ID
    public function getUserById($id_users) {
        $query = "SELECT id_users, username, nama_lengkap, role FROM " . $this->table_name . " WHERE id_users = :id_users LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_users', $id_users, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mengambil semua user dengan pencarian
    public function getAllUsers($keyword = null) {
        $query = "SELECT id_users, username, nama_lengkap, role FROM " . $this->table_name;
        if (!empty($keyword)) {
            $query .= " WHERE username LIKE :keyword OR nama_lengkap LIKE :keyword OR role LIKE :keyword";
        }
        $query .= " ORDER BY id_users DESC";

        $stmt = $this->conn->prepare($query);
        if (!empty($keyword)) {
            $kw = "%" . $keyword . "%";
            $stmt->bindParam(':keyword', $kw);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cek apakah username sudah ada (opsional mengecualikan ID tertentu untuk update)
    public function isUsernameExists($username, $excludeId = null) {
        $query = "SELECT id_users FROM " . $this->table_name . " WHERE username = :username";
        if ($excludeId !== null) {
            $query .= " AND id_users != :exclude_id";
        }
        $query .= " LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        if ($excludeId !== null) {
            $stmt->bindParam(':exclude_id', $excludeId, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Registrasi / Tambah user baru
    public function register($nama_lengkap, $username, $password, $role = 'peminjam', $alamat = '') {
        $query = "INSERT INTO " . $this->table_name . " (nama_lengkap, username, password, role, Alamat) 
                  VALUES (:nama_lengkap, :username, :password, :role, :alamat)";
        $stmt = $this->conn->prepare($query);

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt->bindParam(':nama_lengkap', $nama_lengkap);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':alamat', $alamat);
            return $stmt->execute();
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), '22001') !== false || strpos($e->getMessage(), 'Data too long') !== false) {
                $md5Pass = md5($password);
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':nama_lengkap', $nama_lengkap);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $md5Pass);
                $stmt->bindParam(':role', $role);
                $stmt->bindParam(':alamat', $alamat);
                return $stmt->execute();
            }
            throw $e;
        }
    }

    // Update user
    public function updateUser($id_users, $nama_lengkap, $username, $role, $password = null) {
        if (!empty($password)) {
            $query = "UPDATE " . $this->table_name . " 
                      SET nama_lengkap = :nama_lengkap, username = :username, password = :password, role = :role 
                      WHERE id_users = :id_users";
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nama_lengkap', $nama_lengkap);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':id_users', $id_users, PDO::PARAM_INT);

            try {
                return $stmt->execute();
            } catch (PDOException $e) {
                if (strpos($e->getMessage(), '22001') !== false || strpos($e->getMessage(), 'Data too long') !== false) {
                    $md5Pass = md5($password);
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(':nama_lengkap', $nama_lengkap);
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':password', $md5Pass);
                    $stmt->bindParam(':role', $role);
                    $stmt->bindParam(':id_users', $id_users, PDO::PARAM_INT);
                    return $stmt->execute();
                }
                throw $e;
            }
        } else {
            $query = "UPDATE " . $this->table_name . " 
                      SET nama_lengkap = :nama_lengkap, username = :username, role = :role 
                      WHERE id_users = :id_users";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nama_lengkap', $nama_lengkap);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':id_users', $id_users, PDO::PARAM_INT);
            return $stmt->execute();
        }
    }

    // Hapus user
    public function deleteUser($id_users) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_users = :id_users";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_users', $id_users, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Catat log aktivitas user ke tabel log_aktivitas
    public function recordLog($userId, $aktivitas) {
        try {
            $query = "INSERT INTO log_aktivitas (id_user, aktivitas) VALUES (:user_id, :aktivitas)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':aktivitas', $aktivitas);
            $stmt->execute();
        } catch (Exception $e) {
            // Abaikan error log agar tidak mengganggu transaksi
        }
    }

    // Ambil seluruh data log aktivitas
    public function getLogs($keyword = null) {
        $query = "SELECT l.*, u.username, u.nama_lengkap, u.role, DATE_FORMAT(l.waktu, '%d %b %Y %H:%i') as waktu_format 
                  FROM log_aktivitas l 
                  LEFT JOIN users u ON l.id_user = u.id_users";

        if (!empty($keyword)) {
            $query .= " WHERE l.aktivitas LIKE :keyword OR u.username LIKE :keyword OR u.nama_lengkap LIKE :keyword";
        }

        $query .= " ORDER BY l.id_log_aktifitas DESC";

        $stmt = $this->conn->prepare($query);
        if (!empty($keyword)) {
            $kw = "%" . $keyword . "%";
            $stmt->bindParam(':keyword', $kw);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Hitung total pengguna
    public function countUsers() {
        try {
            $query = "SELECT COUNT(*) FROM " . $this->table_name;
            return (int) $this->conn->query($query)->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }

    // Buat akun admin default jika belum ada di database
    public function createDefaultAdminIfNone() {
        try {
            $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE role = 'admin'";
            $count = (int) $this->conn->query($query)->fetchColumn();
            if ($count === 0) {
                $this->register('Alya Rahma', 'admin', 'admin123', 'admin');
            }
        } catch (Exception $e) {
            // Database belum siap
        }
    }
}

<?php

require_once __DIR__ . '/Database.php';

class User {
    private $conn;
    private $table_name = "user";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->ensureSeedUsers();
    }

    private function ensureSeedUsers() {
        try {
            $stmt = $this->conn->query("SELECT COUNT(*) as cnt FROM " . $this->table_name);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row['cnt'] == 0) {
                $hash = password_hash('password123', PASSWORD_BCRYPT);
                $initSql = "INSERT INTO " . $this->table_name . " (`nama`, `username`, `password`, `role`) VALUES
                    ('Admin Pengelola Lab', 'pengelola', '$hash', 'pengelola'),
                    ('Ketua Jurusan RPL', 'kejur', '$hash', 'admin'),
                    ('Petugas Laboran Lab Komputer', 'petugas', '$hash', 'petugas'),
                    ('Siswa Peminjam Lab', 'siswa', '$hash', 'peminjam')";
                $this->conn->exec($initSql);
            }
        } catch (Exception $e) {}
    }

    public function getUserByUsername($username) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE LOWER(username) = LOWER(:username) LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserById($id_user) {
        $query = "SELECT id_user, nama, username, role FROM " . $this->table_name . " WHERE id_user = :id_user LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllUsers($keyword = null) {
        $query = "SELECT * FROM " . $this->table_name;
        if (!empty($keyword)) {
            $query .= " WHERE nama LIKE :keyword OR username LIKE :keyword OR role LIKE :keyword";
        }
        $query .= " ORDER BY id_user DESC";

        $stmt = $this->conn->prepare($query);
        if (!empty($keyword)) {
            $searchTerm = "%" . $keyword . "%";
            $stmt->bindParam(':keyword', $searchTerm);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countUsers() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['total'] ?? 0);
    }

    public function createUser($nama, $username, $password, $role) {
        $query = "INSERT INTO " . $this->table_name . " (nama, username, password, role) 
                  VALUES (:nama, :username, :password, :role)";
        $stmt = $this->conn->prepare($query);
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed);
        $stmt->bindParam(':role', $role);
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function updateUser($id_user, $nama, $username, $role, $password = null) {
        if (!empty($password)) {
            $query = "UPDATE " . $this->table_name . " 
                      SET nama = :nama, username = :username, role = :role, password = :password 
                      WHERE id_user = :id_user";
            $stmt = $this->conn->prepare($query);
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $hashed);
        } else {
            $query = "UPDATE " . $this->table_name . " 
                      SET nama = :nama, username = :username, role = :role 
                      WHERE id_user = :id_user";
            $stmt = $this->conn->prepare($query);
        }

        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteUser($id_user) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_user = :id_user";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function recordLog($id_user, $aktivitas, $deskripsi = '') {
        try {
            $query = "INSERT INTO log_aktivitas (id_user, aktivitas, deskripsi, waktu) 
                      VALUES (:id_user, :aktivitas, :deskripsi, NOW())";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $stmt->bindParam(':aktivitas', $aktivitas);
            $stmt->bindParam(':deskripsi', $deskripsi);
            $stmt->execute();
        } catch (Exception $e) {}
    }

    public function getAllLogs($keyword = null) {
        $query = "SELECT l.*, u.nama as nama_user, u.username, u.role 
                  FROM log_aktivitas l 
                  LEFT JOIN " . $this->table_name . " u ON l.id_user = u.id_user";
        if (!empty($keyword)) {
            $query .= " WHERE l.aktivitas LIKE :keyword OR l.deskripsi LIKE :keyword OR u.nama LIKE :keyword OR u.username LIKE :keyword";
        }
        $query .= " ORDER BY l.id DESC LIMIT 100";

        $stmt = $this->conn->prepare($query);
        if (!empty($keyword)) {
            $searchTerm = "%" . $keyword . "%";
            $stmt->bindParam(':keyword', $searchTerm);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

<?php

class Database {
    private $host = "localhost";
    private $db_name = "db_peminjaman_alat";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // Coba koneksi langsung ke db_peminjaman_alat
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Jika database belum dibuat, coba koneksi ke MySQL server dan buat db_peminjaman_alat
            try {
                $rootConn = new PDO(
                    "mysql:host=" . $this->host . ";charset=utf8mb4",
                    $this->username,
                    $this->password
                );
                $rootConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $rootConn->exec("CREATE DATABASE IF NOT EXISTS `" . $this->db_name . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                
                $this->conn = new PDO(
                    "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                    $this->username,
                    $this->password
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $exception) {
                die("Koneksi Database Gagal: " . $exception->getMessage());
            }
        }

        $this->initSchemaIfEmpty();

        return $this->conn;
    }

    private function initSchemaIfEmpty() {
        if (!$this->conn) return;

        try {
            // Periksa apakah tabel user sudah ada
            $check = $this->conn->query("SHOW TABLES LIKE 'user'");
            if ($check->rowCount() === 0) {
                $sqlPath = __DIR__ . '/../database.sql';
                if (file_exists($sqlPath)) {
                    $sql = file_get_contents($sqlPath);
                    $this->conn->exec($sql);
                }
            }
        } catch (Exception $e) {
            // Lanjut jika sudah terinisialisasi
        }
    }
}

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
            } else {
                // Migrasi kolom tanggal -> waktu jika database sudah terlanjur ada tabel lama
                try {
                    $colsP = $this->conn->query("SHOW COLUMNS FROM `peminjaman` LIKE 'tanggal_pinjam'");
                    if ($colsP->rowCount() > 0) {
                        $this->conn->exec("ALTER TABLE `peminjaman` CHANGE `tanggal_pinjam` `waktu_pinjam` TIME NOT NULL");
                    }
                    $colsR = $this->conn->query("SHOW COLUMNS FROM `peminjaman` LIKE 'tanggal_rencana_kembali'");
                    if ($colsR->rowCount() > 0) {
                        $this->conn->exec("ALTER TABLE `peminjaman` CHANGE `tanggal_rencana_kembali` `waktu_rencana_kembali` TIME NOT NULL");
                    }
                    $colsK = $this->conn->query("SHOW COLUMNS FROM `peminjaman` LIKE 'tanggal_kembali'");
                    if ($colsK->rowCount() > 0) {
                        $this->conn->exec("ALTER TABLE `peminjaman` CHANGE `tanggal_kembali` `waktu_kembali` TIME NULL");
                    }
                } catch (Exception $e) {}

                try {
                    $colsPg = $this->conn->query("SHOW COLUMNS FROM `pengembalian` LIKE 'tanggal_kembali'");
                    if ($colsPg->rowCount() > 0) {
                        $this->conn->exec("ALTER TABLE `pengembalian` CHANGE `tanggal_kembali` `waktu_kembali` TIME NOT NULL");
                    }
                } catch (Exception $e) {}
            }
        } catch (Exception $e) {
            // Lanjut jika sudah terinisialisasi
        }
    }
}

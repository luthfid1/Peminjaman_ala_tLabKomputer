<?php

require_once 'Models/Database.php';

class Pembayaran {
    private $conn;
    private $table_name = "pembayaran";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->ensureTable();
    }

    // Pastikan skema tabel pembayaran sesuai ERD sistem
    public function ensureTable() {
        try {
            $sql = "CREATE TABLE IF NOT EXISTS " . $this->table_name . " (
                id_pembayaran INT(11) AUTO_INCREMENT PRIMARY KEY,
                id_peminjaman INT(11) NOT NULL,
                total_bayar DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                metode_pembayaran ENUM('di_tempat','website') NOT NULL DEFAULT 'di_tempat',
                status_pembayaran ENUM('menunggu_pembayaran','menunggu_konfirmasi','lunas','gagal') NOT NULL DEFAULT 'menunggu_pembayaran',
                bukti_pembayaran VARCHAR(255) NULL,
                tanggal_pembayaran DATETIME NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX (id_peminjaman)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $this->conn->exec($sql);
        } catch (Exception $e) {}
    }

    // Catat data pembayaran transaksi peminjaman
    public function createPembayaran($id_peminjaman, $total_bayar, $metode_pembayaran, $status_pembayaran = 'menunggu_pembayaran', $bukti_pembayaran = null, $tanggal_pembayaran = null) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (id_peminjaman, total_bayar, metode_pembayaran, status_pembayaran, bukti_pembayaran, tanggal_pembayaran)
                  VALUES (:id_peminjaman, :total_bayar, :metode_pembayaran, :status_pembayaran, :bukti_pembayaran, :tanggal_pembayaran)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_peminjaman', $id_peminjaman, PDO::PARAM_INT);
        $stmt->bindParam(':total_bayar', $total_bayar);
        $stmt->bindParam(':metode_pembayaran', $metode_pembayaran);
        $stmt->bindParam(':status_pembayaran', $status_pembayaran);
        $stmt->bindParam(':bukti_pembayaran', $bukti_pembayaran);
        $stmt->bindParam(':tanggal_pembayaran', $tanggal_pembayaran);
        return $stmt->execute();
    }

    // Ambil info pembayaran berdasarkan id_peminjaman
    public function getPembayaranByPeminjaman($id_peminjaman) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_peminjaman = :id ORDER BY id_pembayaran DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id_peminjaman, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update status pembayaran (misal oleh admin: lunas / gagal)
    public function updateStatusPembayaran($id_pembayaran, $status_pembayaran) {
        $query = "UPDATE " . $this->table_name . " SET status_pembayaran = :status WHERE id_pembayaran = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status_pembayaran);
        $stmt->bindParam(':id', $id_pembayaran, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Upload / update bukti pembayaran online
    public function uploadBukti($id_peminjaman, $nama_file) {
        $tgl = date('Y-m-d H:i:s');
        $query = "UPDATE " . $this->table_name . " 
                  SET bukti_pembayaran = :bukti, status_pembayaran = 'menunggu_konfirmasi', tanggal_pembayaran = :tgl 
                  WHERE id_peminjaman = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':bukti', $nama_file);
        $stmt->bindParam(':tgl', $tgl);
        $stmt->bindParam(':id', $id_peminjaman, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

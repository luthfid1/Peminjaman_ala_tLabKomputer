<?php

require_once __DIR__ . '/Database.php';

class Notifikasi {
    private $conn;
    private $table_name = "notifikasi";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function createNotifikasi($idPeminjam, $idPeminjaman, $judul, $pesan) {
        try {
            $query = "INSERT INTO " . $this->table_name . " (id_peminjam, id_peminjaman, judul, pesan, status, waktu)
                      VALUES (:id_peminjam, :id_peminjaman, :judul, :pesan, 'belum_dibaca', NOW())";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_peminjam', $idPeminjam, PDO::PARAM_INT);
            $stmt->bindParam(':id_peminjaman', $idPeminjaman, PDO::PARAM_INT);
            $stmt->bindParam(':judul', $judul);
            $stmt->bindParam(':pesan', $pesan);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function getNotifikasiByPeminjam($idPeminjam, $onlyUnread = false) {
        try {
            $query = "SELECT n.*, p.kode_peminjaman 
                      FROM " . $this->table_name . " n
                      LEFT JOIN peminjaman p ON n.id_peminjaman = p.id
                      WHERE n.id_peminjam = :idPeminjam";
            if ($onlyUnread) {
                $query .= " AND n.status = 'belum_dibaca'";
            }
            $query .= " ORDER BY n.id DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':idPeminjam', $idPeminjam, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    public function markAsReadByPeminjaman($idPeminjaman) {
        try {
            $query = "UPDATE " . $this->table_name . " SET status = 'dibaca' WHERE id_peminjaman = :idPeminjaman";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':idPeminjaman', $idPeminjaman, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function getActiveReminderLoanIds($idPeminjam) {
        try {
            $query = "SELECT DISTINCT id_peminjaman FROM " . $this->table_name . " 
                      WHERE id_peminjam = :idPeminjam AND status = 'belum_dibaca'";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':idPeminjam', $idPeminjam, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            return [];
        }
    }

    public function hasUnreadReminder($idPeminjaman) {
        try {
            $query = "SELECT id FROM " . $this->table_name . " 
                      WHERE id_peminjaman = :idPeminjaman AND status = 'belum_dibaca' LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':idPeminjaman', $idPeminjaman, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }
}

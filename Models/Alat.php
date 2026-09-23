<?php

require_once 'Models/Database.php';

class Alat {
    private $conn;
    private $table_name = "alat";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->ensureRealisticPrices();
    }

    // Naikkan harga sewa yang terlalu kecil agar sesuai standar rental studio profesional
    public function ensureRealisticPrices() {
        try {
            $this->conn->exec("UPDATE " . $this->table_name . " SET harga_sewa = CASE 
                WHEN harga_sewa < 20000 THEN 125000
                WHEN harga_sewa < 50000 THEN 175000
                WHEN harga_sewa < 100000 THEN 225000
                ELSE harga_sewa
            END WHERE harga_sewa < 100000 AND harga_sewa > 0");
        } catch (Exception $e) {}
    }

    // Mengambil semua data alat beserta nama kategorinya
    public function getAllAlat($keyword = null) {
        $query = "SELECT a.*, k.nama_kategori, DATE_FORMAT(a.created_at, '%d %b %Y') as tgl_cek 
                  FROM " . $this->table_name . " a 
                  LEFT JOIN kategori k ON a.id_kategori = k.id_kategori";

        if (!empty($keyword)) {
            $query .= " WHERE a.nama_alat LIKE :keyword OR k.nama_kategori LIKE :keyword";
        }

        $query .= " ORDER BY a.id_alat DESC";

        $stmt = $this->conn->prepare($query);

        if (!empty($keyword)) {
            $searchTerm = "%" . $keyword . "%";
            $stmt->bindParam(':keyword', $searchTerm);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mengambil satu data alat berdasarkan ID
    public function getAlatById($id) {
        $query = "SELECT a.*, k.nama_kategori 
                  FROM " . $this->table_name . " a 
                  LEFT JOIN kategori k ON a.id_kategori = k.id_kategori 
                  WHERE a.id_alat = :id 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tambah alat baru
    public function createAlat($kategori_id, $nama_alat, $spesifikasi, $harga_sewa, $jumlah_stok) {
        $query = "INSERT INTO " . $this->table_name . " (id_kategori, nama_alat, spesifikasi, harga_sewa, jumlah_stok) 
                  VALUES (:kategori_id, :nama_alat, :spesifikasi, :harga_sewa, :jumlah_stok)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':kategori_id', $kategori_id, PDO::PARAM_INT);
        $stmt->bindParam(':nama_alat', $nama_alat);
        $stmt->bindParam(':spesifikasi', $spesifikasi);
        $stmt->bindParam(':harga_sewa', $harga_sewa);
        $stmt->bindParam(':jumlah_stok', $jumlah_stok, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Update data alat
    public function updateAlat($id, $kategori_id, $nama_alat, $spesifikasi, $harga_sewa, $jumlah_stok) {
        $query = "UPDATE " . $this->table_name . " 
                  SET id_kategori = :kategori_id, nama_alat = :nama_alat, spesifikasi = :spesifikasi, 
                      harga_sewa = :harga_sewa, jumlah_stok = :jumlah_stok 
                  WHERE id_alat = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':kategori_id', $kategori_id, PDO::PARAM_INT);
        $stmt->bindParam(':nama_alat', $nama_alat);
        $stmt->bindParam(':spesifikasi', $spesifikasi);
        $stmt->bindParam(':harga_sewa', $harga_sewa);
        $stmt->bindParam(':jumlah_stok', $jumlah_stok, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Hapus data alat
    public function deleteAlat($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_alat = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Mengambil statistik dashboard untuk Admin
    public function getDashboardStats() {
        $stats = [
            'total_alat'          => 0,
            'total_kategori'      => 0,
            'sedang_dipinjam'     => 0,
            'menunggu_persetujuan'=> 0
        ];

        try {
            $stats['total_alat'] = (int) $this->conn->query("SELECT COUNT(*) FROM alat")->fetchColumn();
            $stats['total_kategori'] = (int) $this->conn->query("SELECT COUNT(*) FROM kategori")->fetchColumn();
            $stats['sedang_dipinjam'] = (int) $this->conn->query("SELECT COUNT(*) FROM peminjaman WHERE status = 'dipinjam'")->fetchColumn();
            $stats['menunggu_persetujuan'] = (int) $this->conn->query("SELECT COUNT(*) FROM peminjaman WHERE status = 'menunggu'")->fetchColumn();
        } catch (Exception $e) {
            // Abaikan jika tabel belum siap
        }

        return $stats;
    }
}

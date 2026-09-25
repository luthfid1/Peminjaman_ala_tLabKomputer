<?php

require_once __DIR__ . '/Database.php';

class Alat {
    private $conn;
    private $table_name = "alat";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllAlat($keyword = null) {
        $query = "SELECT a.*, k.nama_kategori 
                  FROM " . $this->table_name . " a 
                  LEFT JOIN kategori k ON a.id_kategori = k.id";

        if (!empty($keyword)) {
            $query .= " WHERE a.nama_alat LIKE :keyword OR a.kode LIKE :keyword OR k.nama_kategori LIKE :keyword OR a.kondisi LIKE :keyword";
        }

        $query .= " ORDER BY a.id DESC";
        $stmt = $this->conn->prepare($query);

        if (!empty($keyword)) {
            $searchTerm = "%" . $keyword . "%";
            $stmt->bindParam(':keyword', $searchTerm);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAlatById($id) {
        $query = "SELECT a.*, k.nama_kategori 
                  FROM " . $this->table_name . " a 
                  LEFT JOIN kategori k ON a.id_kategori = k.id 
                  WHERE a.id = :id 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createAlat($kode, $id_kategori, $nama_alat, $jumlah, $kondisi, $deskripsi) {
        $query = "INSERT INTO " . $this->table_name . " (kode, id_kategori, nama_alat, jumlah, kondisi, deskripsi) 
                  VALUES (:kode, :id_kategori, :nama_alat, :jumlah, :kondisi, :deskripsi)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':kode', $kode);
        $stmt->bindParam(':id_kategori', $id_kategori, PDO::PARAM_INT);
        $stmt->bindParam(':nama_alat', $nama_alat);
        $stmt->bindParam(':jumlah', $jumlah, PDO::PARAM_INT);
        $stmt->bindParam(':kondisi', $kondisi);
        $stmt->bindParam(':deskripsi', $deskripsi);
        return $stmt->execute();
    }

    public function updateAlat($id, $kode, $id_kategori, $nama_alat, $jumlah, $kondisi, $deskripsi) {
        $query = "UPDATE " . $this->table_name . " 
                  SET kode = :kode, id_kategori = :id_kategori, nama_alat = :nama_alat, 
                      jumlah = :jumlah, kondisi = :kondisi, deskripsi = :deskripsi 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':kode', $kode);
        $stmt->bindParam(':id_kategori', $id_kategori, PDO::PARAM_INT);
        $stmt->bindParam(':nama_alat', $nama_alat);
        $stmt->bindParam(':jumlah', $jumlah, PDO::PARAM_INT);
        $stmt->bindParam(':kondisi', $kondisi);
        $stmt->bindParam(':deskripsi', $deskripsi);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteAlat($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getDashboardStats() {
        $stats = [
            'total_alat' => 0,
            'total_kategori' => 0,
            'sedang_dipinjam' => 0,
            'menunggu_persetujuan' => 0
        ];

        try {
            $q1 = $this->conn->query("SELECT COUNT(*) as total FROM " . $this->table_name);
            $stats['total_alat'] = (int)($q1->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

            $q2 = $this->conn->query("SELECT COUNT(*) as total FROM kategori");
            $stats['total_kategori'] = (int)($q2->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

            $q3 = $this->conn->query("SELECT COUNT(*) as total FROM peminjaman WHERE status = 'disetujui' OR status = 'dipinjam'");
            $stats['sedang_dipinjam'] = (int)($q3->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

            $q4 = $this->conn->query("SELECT COUNT(*) as total FROM peminjaman WHERE status = 'menunggu'");
            $stats['menunggu_persetujuan'] = (int)($q4->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
        } catch (Exception $e) {}

        return $stats;
    }
}

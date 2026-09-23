<?php

require_once 'Models/Database.php';

class Kategori {
    private $conn;
    private $table_name = "kategori";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Ambil semua data kategori
    public function getAllKategori($keyword = null) {
        $query = "SELECT * FROM " . $this->table_name;
        if (!empty($keyword)) {
            $query .= " WHERE nama_kategori LIKE :keyword";
        }
        $query .= " ORDER BY id_kategori DESC";

        $stmt = $this->conn->prepare($query);
        if (!empty($keyword)) {
            $kw = "%" . $keyword . "%";
            $stmt->bindParam(':keyword', $kw);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ambil semua data kategori beserta jumlah alat musiknya
    public function getKategoriWithCount($keyword = null) {
        $query = "SELECT k.id_kategori, k.nama_kategori, k.created_at, 
                         COUNT(a.id_alat) as total_alat,
                         COALESCE(SUM(a.jumlah_stok), 0) as total_stok
                  FROM " . $this->table_name . " k
                  LEFT JOIN alat a ON k.id_kategori = a.id_kategori";
        if (!empty($keyword)) {
            $query .= " WHERE k.nama_kategori LIKE :keyword";
        }
        $query .= " GROUP BY k.id_kategori, k.nama_kategori, k.created_at ORDER BY k.id_kategori DESC";

        $stmt = $this->conn->prepare($query);
        if (!empty($keyword)) {
            $kw = "%" . $keyword . "%";
            $stmt->bindParam(':keyword', $kw);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ambil kategori berdasarkan ID
    public function getKategoriById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_kategori = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tambah kategori baru
    public function createKategori($nama_kategori) {
        $query = "INSERT INTO " . $this->table_name . " (nama_kategori) VALUES (:nama_kategori)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama_kategori', $nama_kategori);
        return $stmt->execute();
    }

    // Ubah kategori
    public function updateKategori($id, $nama_kategori) {
        $query = "UPDATE " . $this->table_name . " SET nama_kategori = :nama_kategori WHERE id_kategori = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama_kategori', $nama_kategori);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Hapus kategori
    public function deleteKategori($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_kategori = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

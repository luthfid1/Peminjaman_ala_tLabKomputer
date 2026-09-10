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
        $query .= " ORDER BY id DESC";

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
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
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
        $query = "UPDATE " . $this->table_name . " SET nama_kategori = :nama_kategori WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama_kategori', $nama_kategori);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Hapus kategori
    public function deleteKategori($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

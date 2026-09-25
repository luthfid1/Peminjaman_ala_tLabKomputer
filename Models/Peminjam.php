<?php

require_once __DIR__ . '/Database.php';

class Peminjam {
    private $conn;
    private $table_name = "peminjam";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllPeminjam($keyword = null) {
        $query = "SELECT * FROM " . $this->table_name;
        if (!empty($keyword)) {
            $query .= " WHERE nama LIKE :keyword OR nis LIKE :keyword OR kelas LIKE :keyword OR jurusan LIKE :keyword";
        }
        $query .= " ORDER BY id DESC";

        $stmt = $this->conn->prepare($query);
        if (!empty($keyword)) {
            $searchTerm = "%" . $keyword . "%";
            $stmt->bindParam(':keyword', $searchTerm);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPeminjamById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPeminjamByUserId($userId) {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id_user = :userId LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($res) return $res;
        } catch (Exception $e) {}
        return null;
    }

    public function getPeminjamByNama($nama) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE nama = :nama ORDER BY id DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama', $nama);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createPeminjam($nama, $nis, $kelas, $jurusan, $no_telp = '', $foto_kartu_pelajar = '', $id_user = null) {
        try {
            $query = "INSERT INTO " . $this->table_name . " (id_user, nama, nis, kelas, jurusan, no_telp, foto_kartu_pelajar) 
                      VALUES (:id_user, :nama, :nis, :kelas, :jurusan, :no_telp, :foto_kartu_pelajar)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $stmt->bindParam(':nama', $nama);
            $stmt->bindParam(':nis', $nis);
            $stmt->bindParam(':kelas', $kelas);
            $stmt->bindParam(':jurusan', $jurusan);
            $stmt->bindParam(':no_telp', $no_telp);
            $stmt->bindParam(':foto_kartu_pelajar', $foto_kartu_pelajar);
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            // Fallback jika kolom id_user belum tersedia
            $query = "INSERT INTO " . $this->table_name . " (nama, nis, kelas, jurusan, no_telp, foto_kartu_pelajar) 
                      VALUES (:nama, :nis, :kelas, :jurusan, :no_telp, :foto_kartu_pelajar)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nama', $nama);
            $stmt->bindParam(':nis', $nis);
            $stmt->bindParam(':kelas', $kelas);
            $stmt->bindParam(':jurusan', $jurusan);
            $stmt->bindParam(':no_telp', $no_telp);
            $stmt->bindParam(':foto_kartu_pelajar', $foto_kartu_pelajar);
            $stmt->execute();
            return $this->conn->lastInsertId();
        }
    }

    public function updatePeminjam($id, $nama, $nis, $kelas, $jurusan, $no_telp = '', $foto_kartu_pelajar = null) {
        if ($foto_kartu_pelajar !== null) {
            $query = "UPDATE " . $this->table_name . " 
                      SET nama = :nama, nis = :nis, kelas = :kelas, jurusan = :jurusan, 
                          no_telp = :no_telp, foto_kartu_pelajar = :foto_kartu_pelajar 
                      WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':foto_kartu_pelajar', $foto_kartu_pelajar);
        } else {
            $query = "UPDATE " . $this->table_name . " 
                      SET nama = :nama, nis = :nis, kelas = :kelas, jurusan = :jurusan, no_telp = :no_telp 
                      WHERE id = :id";
            $stmt = $this->conn->prepare($query);
        }

        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':nis', $nis);
        $stmt->bindParam(':kelas', $kelas);
        $stmt->bindParam(':jurusan', $jurusan);
        $stmt->bindParam(':no_telp', $no_telp);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deletePeminjam($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

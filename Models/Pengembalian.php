<?php

require_once __DIR__ . '/Database.php';

class Pengembalian {
    private $conn;
    private $table_name = "pengembalian";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllPengembalian($keyword = null) {
        $query = "SELECT pg.*, 
                         p.kode_peminjaman, p.waktu_pinjam, p.waktu_rencana_kembali,
                         pm.nama as nama_peminjam, pm.nis, pm.kelas, pm.jurusan, pm.no_telp,
                         u.nama as nama_petugas,
                         dp.id_alat, dp.jumlah,
                         a.nama_alat, a.kode as kode_alat,
                         k.nama_kategori
                  FROM " . $this->table_name . " pg
                  JOIN peminjaman p ON pg.id_peminjaman = p.id
                  JOIN peminjam pm ON p.id_peminjam = pm.id
                  LEFT JOIN user u ON pg.id_user = u.id_user
                  LEFT JOIN detail_peminjaman dp ON dp.id_peminjaman = p.id
                  LEFT JOIN alat a ON dp.id_alat = a.id
                  LEFT JOIN kategori k ON a.id_kategori = k.id
                  WHERE 1=1";

        if (!empty($keyword)) {
            $query .= " AND (p.kode_peminjaman LIKE :keyword 
                        OR pm.nama LIKE :keyword 
                        OR pm.nis LIKE :keyword 
                        OR a.nama_alat LIKE :keyword 
                        OR pg.kondisi_kembali LIKE :keyword)";
        }

        $query .= " ORDER BY pg.id DESC";

        $stmt = $this->conn->prepare($query);
        if (!empty($keyword)) {
            $searchTerm = "%" . $keyword . "%";
            $stmt->bindParam(':keyword', $searchTerm);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPengembalianById($id) {
        $query = "SELECT pg.*, 
                         p.kode_peminjaman, p.waktu_pinjam, p.waktu_rencana_kembali,
                         pm.nama as nama_peminjam, pm.nis, pm.kelas, pm.jurusan,
                         u.nama as nama_petugas,
                         dp.id_alat, dp.jumlah,
                         a.nama_alat, a.kode as kode_alat
                  FROM " . $this->table_name . " pg
                  JOIN peminjaman p ON pg.id_peminjaman = p.id
                  JOIN peminjam pm ON p.id_peminjam = pm.id
                  LEFT JOIN user u ON pg.id_user = u.id_user
                  LEFT JOIN detail_peminjaman dp ON dp.id_peminjaman = p.id
                  LEFT JOIN alat a ON dp.id_alat = a.id
                  WHERE pg.id = :id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createPengembalian($id_peminjaman, $waktu_kembali, $kondisi_kembali, $denda, $id_user) {
        $this->conn->beginTransaction();
        try {
            // 1. Simpan data pengembalian
            $query = "INSERT INTO " . $this->table_name . " 
                      (id_peminjaman, waktu_kembali, kondisi_kembali, denda, id_user) 
                      VALUES (:id_peminjaman, :waktu_kembali, :kondisi_kembali, :denda, :id_user)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_peminjaman', $id_peminjaman, PDO::PARAM_INT);
            $stmt->bindParam(':waktu_kembali', $waktu_kembali);
            $stmt->bindParam(':kondisi_kembali', $kondisi_kembali);
            $stmt->bindParam(':denda', $denda);
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $stmt->execute();

            $pengembalianId = $this->conn->lastInsertId();

            // 2. Update status peminjaman jadi 'dikembalikan' dan isi waktu_kembali
            $queryPeminjaman = "UPDATE peminjaman 
                                SET status = 'dikembalikan', waktu_kembali = :waktu_kembali 
                                WHERE id = :id_peminjaman";
            $stmtPeminjaman = $this->conn->prepare($queryPeminjaman);
            $stmtPeminjaman->bindParam(':waktu_kembali', $waktu_kembali);
            $stmtPeminjaman->bindParam(':id_peminjaman', $id_peminjaman, PDO::PARAM_INT);
            $stmtPeminjaman->execute();

            // 3. Kembalikan stok alat
            $queryDetail = "SELECT id_alat, jumlah FROM detail_peminjaman WHERE id_peminjaman = :id_peminjaman";
            $stmtDetail = $this->conn->prepare($queryDetail);
            $stmtDetail->bindParam(':id_peminjaman', $id_peminjaman, PDO::PARAM_INT);
            $stmtDetail->execute();
            $details = $stmtDetail->fetchAll(PDO::FETCH_ASSOC);

            foreach ($details as $d) {
                $queryStock = "UPDATE alat SET jumlah = jumlah + :jumlah WHERE id = :id_alat";
                $stmtStock = $this->conn->prepare($queryStock);
                $stmtStock->bindParam(':jumlah', $d['jumlah'], PDO::PARAM_INT);
                $stmtStock->bindParam(':id_alat', $d['id_alat'], PDO::PARAM_INT);
                $stmtStock->execute();
            }

            $this->conn->commit();
            return $pengembalianId;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    public function updatePengembalian($id, $waktu_kembali, $kondisi_kembali, $denda) {
        $query = "UPDATE " . $this->table_name . " 
                  SET waktu_kembali = :waktu_kembali, kondisi_kembali = :kondisi_kembali, denda = :denda 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':waktu_kembali', $waktu_kembali);
        $stmt->bindParam(':kondisi_kembali', $kondisi_kembali);
        $stmt->bindParam(':denda', $denda);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deletePengembalian($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

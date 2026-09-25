<?php

require_once __DIR__ . '/Database.php';

class Peminjaman {
    private $conn;
    private $table_name = "peminjaman";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function generateKodePeminjaman() {
        $prefix = "PMJ-" . date('Ymd') . "-";
        try {
            $query = "SELECT kode_peminjaman FROM " . $this->table_name . " 
                      WHERE kode_peminjaman LIKE :prefix 
                      ORDER BY id DESC LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $p = $prefix . "%";
            $stmt->bindParam(':prefix', $p);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $lastNum = (int)substr($row['kode_peminjaman'], -4);
                $newNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $newNum = "0001";
            }
            return $prefix . $newNum;
        } catch (Exception $e) {
            return $prefix . rand(1000, 9999);
        }
    }

    public function getAllPeminjaman($keyword = null, $statusFilter = null) {
        $query = "SELECT p.*, 
                         pm.nama as nama_peminjam, pm.nis, pm.kelas, pm.jurusan, pm.no_telp,
                         u.nama as nama_petugas,
                         dp.id_alat, dp.jumlah,
                         a.nama_alat, a.kode as kode_alat,
                         k.nama_kategori
                  FROM " . $this->table_name . " p
                  LEFT JOIN peminjam pm ON p.id_peminjam = pm.id
                  LEFT JOIN user u ON p.id_user = u.id_user
                  LEFT JOIN detail_peminjaman dp ON dp.id_peminjaman = p.id
                  LEFT JOIN alat a ON dp.id_alat = a.id
                  LEFT JOIN kategori k ON a.id_kategori = k.id
                  WHERE 1=1";

        if (!empty($statusFilter)) {
            $query .= " AND p.status = :statusFilter";
        }

        if (!empty($keyword)) {
            $query .= " AND (p.kode_peminjaman LIKE :keyword 
                        OR pm.nama LIKE :keyword 
                        OR pm.nis LIKE :keyword 
                        OR a.nama_alat LIKE :keyword 
                        OR p.keperluan LIKE :keyword)";
        }

        $query .= " ORDER BY p.id DESC";

        $stmt = $this->conn->prepare($query);

        if (!empty($statusFilter)) {
            $stmt->bindParam(':statusFilter', $statusFilter);
        }

        if (!empty($keyword)) {
            $searchTerm = "%" . $keyword . "%";
            $stmt->bindParam(':keyword', $searchTerm);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPeminjamanById($id) {
        $query = "SELECT p.*, 
                         pm.nama as nama_peminjam, pm.nis, pm.kelas, pm.jurusan, pm.no_telp,
                         u.nama as nama_petugas,
                         dp.id_alat, dp.jumlah,
                         a.nama_alat, a.kode as kode_alat,
                         k.nama_kategori
                  FROM " . $this->table_name . " p
                  LEFT JOIN peminjam pm ON p.id_peminjam = pm.id
                  LEFT JOIN user u ON p.id_user = u.id_user
                  LEFT JOIN detail_peminjaman dp ON dp.id_peminjaman = p.id
                  LEFT JOIN alat a ON dp.id_alat = a.id
                  LEFT JOIN kategori k ON a.id_kategori = k.id
                  WHERE p.id = :id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createPeminjaman($id_peminjam, $id_user, $waktu_pinjam, $waktu_rencana_kembali, $jenis_peminjaman, $keperluan, $id_alat, $jumlah, $status = 'menunggu') {
        $kode_peminjaman = $this->generateKodePeminjaman();

        $this->conn->beginTransaction();
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                      (kode_peminjaman, id_peminjam, id_user, waktu_pinjam, waktu_rencana_kembali, jenis_peminjaman, keperluan, status) 
                      VALUES (:kode, :id_peminjam, :id_user, :waktu_pinjam, :waktu_rencana, :jenis, :keperluan, :status)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':kode', $kode_peminjaman);
            $stmt->bindParam(':id_peminjam', $id_peminjam, PDO::PARAM_INT);
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $stmt->bindParam(':waktu_pinjam', $waktu_pinjam);
            $stmt->bindParam(':waktu_rencana', $waktu_rencana_kembali);
            $stmt->bindParam(':jenis', $jenis_peminjaman);
            $stmt->bindParam(':keperluan', $keperluan);
            $stmt->bindParam(':status', $status);
            $stmt->execute();

            $peminjamanId = $this->conn->lastInsertId();

            $queryDetail = "INSERT INTO detail_peminjaman (id_peminjaman, id_alat, jumlah) 
                            VALUES (:id_peminjaman, :id_alat, :jumlah)";
            $stmtDetail = $this->conn->prepare($queryDetail);
            $stmtDetail->bindParam(':id_peminjaman', $peminjamanId, PDO::PARAM_INT);
            $stmtDetail->bindParam(':id_alat', $id_alat, PDO::PARAM_INT);
            $stmtDetail->bindParam(':jumlah', $jumlah, PDO::PARAM_INT);
            $stmtDetail->execute();

            // Kurangi stok jika status disetujui atau dipinjam
            if (in_array(strtolower($status), ['disetujui', 'dipinjam'])) {
                $queryStock = "UPDATE alat SET jumlah = GREATEST(0, jumlah - :jumlah) WHERE id = :id_alat";
                $stmtStock = $this->conn->prepare($queryStock);
                $stmtStock->bindParam(':jumlah', $jumlah, PDO::PARAM_INT);
                $stmtStock->bindParam(':id_alat', $id_alat, PDO::PARAM_INT);
                $stmtStock->execute();
            }

            $this->conn->commit();
            return $peminjamanId;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    public function updatePeminjaman($id, $id_peminjam, $waktu_pinjam, $waktu_rencana_kembali, $jenis_peminjaman, $keperluan, $status, $id_alat = null, $jumlah = null) {
        $this->conn->beginTransaction();
        try {
            $query = "UPDATE " . $this->table_name . " 
                      SET id_peminjam = :id_peminjam, waktu_pinjam = :waktu_pinjam, 
                          waktu_rencana_kembali = :waktu_rencana, jenis_peminjaman = :jenis, 
                          keperluan = :keperluan, status = :status 
                      WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_peminjam', $id_peminjam, PDO::PARAM_INT);
            $stmt->bindParam(':waktu_pinjam', $waktu_pinjam);
            $stmt->bindParam(':waktu_rencana', $waktu_rencana_kembali);
            $stmt->bindParam(':jenis', $jenis_peminjaman);
            $stmt->bindParam(':keperluan', $keperluan);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($id_alat !== null && $jumlah !== null) {
                $queryDetail = "UPDATE detail_peminjaman 
                                SET id_alat = :id_alat, jumlah = :jumlah 
                                WHERE id_peminjaman = :id";
                $stmtDetail = $this->conn->prepare($queryDetail);
                $stmtDetail->bindParam(':id_alat', $id_alat, PDO::PARAM_INT);
                $stmtDetail->bindParam(':jumlah', $jumlah, PDO::PARAM_INT);
                $stmtDetail->bindParam(':id', $id, PDO::PARAM_INT);
                $stmtDetail->execute();
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    public function updateStatus($id, $status, $id_user = null) {
        $current = $this->getPeminjamanById($id);
        if (!$current) return false;

        $this->conn->beginTransaction();
        try {
            $oldStatus = strtolower($current['status']);
            $newStatus = strtolower($status);

            $query = "UPDATE " . $this->table_name . " SET status = :status";
            if ($id_user !== null) {
                $query .= ", id_user = :id_user";
            }
            $query .= " WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':status', $status);
            if ($id_user !== null) {
                $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            }
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Atur stok berdasarkan transisi status
            if (!in_array($oldStatus, ['disetujui', 'dipinjam']) && in_array($newStatus, ['disetujui', 'dipinjam'])) {
                // Kurangi stok
                $st = $this->conn->prepare("UPDATE alat SET jumlah = GREATEST(0, jumlah - :jml) WHERE id = :id_alat");
                $st->bindParam(':jml', $current['jumlah'], PDO::PARAM_INT);
                $st->bindParam(':id_alat', $current['id_alat'], PDO::PARAM_INT);
                $st->execute();
            } elseif (in_array($oldStatus, ['disetujui', 'dipinjam']) && in_array($newStatus, ['ditolak', 'dikembalikan', 'dibatalkan'])) {
                // Kembalikan stok
                $st = $this->conn->prepare("UPDATE alat SET jumlah = jumlah + :jml WHERE id = :id_alat");
                $st->bindParam(':jml', $current['jumlah'], PDO::PARAM_INT);
                $st->bindParam(':id_alat', $current['id_alat'], PDO::PARAM_INT);
                $st->execute();
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    public function deletePeminjaman($id) {
        $current = $this->getPeminjamanById($id);
        if (!$current) return false;

        $this->conn->beginTransaction();
        try {
            if (in_array(strtolower($current['status']), ['disetujui', 'dipinjam'])) {
                $st = $this->conn->prepare("UPDATE alat SET jumlah = jumlah + :jml WHERE id = :id_alat");
                $st->bindParam(':jml', $current['jumlah'], PDO::PARAM_INT);
                $st->bindParam(':id_alat', $current['id_alat'], PDO::PARAM_INT);
                $st->execute();
            }

            $delDetail = $this->conn->prepare("DELETE FROM detail_peminjaman WHERE id_peminjaman = :id");
            $delDetail->bindParam(':id', $id, PDO::PARAM_INT);
            $delDetail->execute();

            $del = $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE id = :id");
            $del->bindParam(':id', $id, PDO::PARAM_INT);
            $del->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    public function getActivePeminjaman() {
        $query = "SELECT p.*, 
                         pm.nama as nama_peminjam, pm.nis, pm.kelas, pm.jurusan,
                         dp.id_alat, dp.jumlah,
                         a.nama_alat, a.kode as kode_alat
                  FROM " . $this->table_name . " p
                  JOIN peminjam pm ON p.id_peminjam = pm.id
                  JOIN detail_peminjaman dp ON dp.id_peminjaman = p.id
                  JOIN alat a ON dp.id_alat = a.id
                  WHERE p.status IN ('disetujui', 'dipinjam')
                  ORDER BY p.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

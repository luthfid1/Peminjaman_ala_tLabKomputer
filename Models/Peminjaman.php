<?php

require_once 'Models/Database.php';

class Peminjaman {
    private $conn;
    private $table_name = "peminjaman";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Ambil semua peminjaman yang masih aktif (menunggu, disetujui, dipinjam)
    public function getPeminjamanAktif($keyword = null) {
        $query = "SELECT p.id, p.jumlah, p.tanggal_pinjam, p.tanggal_kembali, p.status, p.created_at,
                         u.id_users, u.nama_lengkap, u.username, u.role,
                         a.id AS alat_id, a.nama_alat, a.harga_sewa,
                         k.nama_kategori
                  FROM " . $this->table_name . " p
                  LEFT JOIN users u ON p.user_id = u.id_users
                  LEFT JOIN alat a ON p.alat_id = a.id
                  LEFT JOIN kategori k ON a.kategori_id = k.id
                  WHERE p.status IN ('menunggu', 'disetujui', 'dipinjam')";

        if (!empty($keyword)) {
            $query .= " AND (u.nama_lengkap LIKE :keyword OR u.username LIKE :keyword OR a.nama_alat LIKE :keyword)";
        }

        $query .= " ORDER BY p.created_at DESC";

        $stmt = $this->conn->prepare($query);
        if (!empty($keyword)) {
            $kw = "%" . $keyword . "%";
            $stmt->bindParam(':keyword', $kw);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Hitung total peminjaman aktif
    public function countPeminjamanAktif() {
        try {
            $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE status IN ('menunggu', 'disetujui', 'dipinjam')";
            return (int) $this->conn->query($query)->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }
}

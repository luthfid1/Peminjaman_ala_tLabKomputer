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
        $query = "SELECT p.id_peminjaman, p.jumlah, p.tanggal_pinjam, p.tanggal_kembali, p.status, p.created_at,
                         u.id_users, u.nama_lengkap, u.username, u.Alamat, u.no_hp, u.role,
                         a.id_alat, a.nama_alat, a.harga_sewa,
                         k.nama_kategori
                  FROM " . $this->table_name . " p
                  LEFT JOIN users u ON p.id_user = u.id_users
                  LEFT JOIN alat a ON p.id_alat = a.id_alat
                  LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
                  WHERE p.status IN ('menunggu', 'disetujui', 'dipinjam')";

        if (!empty($keyword)) {
            $query .= " AND (u.nama_lengkap LIKE :keyword OR u.username LIKE :keyword OR u.no_hp LIKE :keyword OR a.nama_alat LIKE :keyword)";
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

    // Ambil SEMUA data peminjaman (untuk Kelola Peminjaman oleh Admin)
    public function getAllPeminjaman($keyword = null) {
        $query = "SELECT p.id_peminjaman, p.id_user, p.id_alat, p.jumlah, p.tanggal_pinjam, p.tanggal_kembali, p.status, p.created_at,
                         u.nama_lengkap, u.username, u.Alamat, u.no_hp, u.role,
                         a.nama_alat, a.harga_sewa,
                         k.nama_kategori
                  FROM " . $this->table_name . " p
                  LEFT JOIN users u ON p.id_user = u.id_users
                  LEFT JOIN alat a ON p.id_alat = a.id_alat
                  LEFT JOIN kategori k ON a.id_kategori = k.id_kategori";

        if (!empty($keyword)) {
            $query .= " WHERE (u.nama_lengkap LIKE :keyword OR u.username LIKE :keyword OR u.no_hp LIKE :keyword OR a.nama_alat LIKE :keyword OR p.status LIKE :keyword)";
        }

        $query .= " ORDER BY p.id_peminjaman DESC";

        $stmt = $this->conn->prepare($query);
        if (!empty($keyword)) {
            $kw = "%" . $keyword . "%";
            $stmt->bindParam(':keyword', $kw);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ambil satu data peminjaman berdasarkan ID
    public function getPeminjamanById($id) {
        $query = "SELECT p.*, u.nama_lengkap, u.username, a.nama_alat, a.harga_sewa 
                  FROM " . $this->table_name . " p
                  LEFT JOIN users u ON p.id_user = u.id_users
                  LEFT JOIN alat a ON p.id_alat = a.id_alat
                  WHERE p.id_peminjaman = :id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tambah data peminjaman baru (offline / manual oleh Admin)
    public function createPeminjaman($id_user, $id_alat, $jumlah, $tanggal_pinjam, $tanggal_kembali, $status = 'dipinjam') {
        $query = "INSERT INTO " . $this->table_name . " (id_user, id_alat, jumlah, tanggal_pinjam, tanggal_kembali, status) 
                  VALUES (:id_user, :id_alat, :jumlah, :tanggal_pinjam, :tanggal_kembali, :status)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindParam(':id_alat', $id_alat, PDO::PARAM_INT);
        $stmt->bindParam(':jumlah', $jumlah, PDO::PARAM_INT);
        $stmt->bindParam(':tanggal_pinjam', $tanggal_pinjam);
        $stmt->bindParam(':tanggal_kembali', $tanggal_kembali);
        $stmt->bindParam(':status', $status);
        $result = $stmt->execute();

        // Jika statusnya langsung 'dipinjam', kurangi stok alat
        if ($result && $status === 'dipinjam') {
            $this->reduceStock($id_alat, $jumlah);
        }

        return $result ? (int)$this->conn->lastInsertId() : false;
    }

    // Ubah data peminjaman
    public function updatePeminjaman($id, $id_user, $id_alat, $jumlah, $tanggal_pinjam, $tanggal_kembali, $status) {
        // Ambil data lama untuk kalkulasi stok
        $oldData = $this->getPeminjamanById($id);

        $query = "UPDATE " . $this->table_name . " 
                  SET id_user = :id_user, id_alat = :id_alat, jumlah = :jumlah,
                      tanggal_pinjam = :tanggal_pinjam, tanggal_kembali = :tanggal_kembali, status = :status
                  WHERE id_peminjaman = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindParam(':id_alat', $id_alat, PDO::PARAM_INT);
        $stmt->bindParam(':jumlah', $jumlah, PDO::PARAM_INT);
        $stmt->bindParam(':tanggal_pinjam', $tanggal_pinjam);
        $stmt->bindParam(':tanggal_kembali', $tanggal_kembali);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $result = $stmt->execute();

        if ($result && $oldData) {
            // Jika berubah dari bukan 'dipinjam' ke 'dipinjam'
            if ($oldData['status'] !== 'dipinjam' && $status === 'dipinjam') {
                $this->reduceStock($id_alat, $jumlah);
            }
            // Jika berubah dari 'dipinjam' ke status lain ('dikembalikan', 'ditolak', dll)
            elseif ($oldData['status'] === 'dipinjam' && $status !== 'dipinjam') {
                $this->restoreStock($oldData['id_alat'], $oldData['jumlah']);
            }
            // Jika tetap 'dipinjam' tapi jumlah unit atau alat berubah
            elseif ($oldData['status'] === 'dipinjam' && $status === 'dipinjam') {
                if ($oldData['id_alat'] == $id_alat) {
                    $selisih = $jumlah - $oldData['jumlah'];
                    if ($selisih > 0) {
                        $this->reduceStock($id_alat, $selisih);
                    } elseif ($selisih < 0) {
                        $this->restoreStock($id_alat, abs($selisih));
                    }
                } else {
                    $this->restoreStock($oldData['id_alat'], $oldData['jumlah']);
                    $this->reduceStock($id_alat, $jumlah);
                }
            }
        }

        return $result;
    }

    // Hapus data peminjaman
    public function deletePeminjaman($id) {
        $oldData = $this->getPeminjamanById($id);
        
        $query = "DELETE FROM " . $this->table_name . " WHERE id_peminjaman = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $result = $stmt->execute();

        // Kembalikan stok jika data yang dihapus berstatus 'dipinjam'
        if ($result && $oldData && $oldData['status'] === 'dipinjam') {
            $this->restoreStock($oldData['id_alat'], $oldData['jumlah']);
        }

        return $result;
    }

    // Kurangi stok alat
    private function reduceStock($id_alat, $jumlah) {
        try {
            $query = "UPDATE alat SET jumlah_stok = GREATEST(0, jumlah_stok - :jumlah) WHERE id_alat = :id_alat";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':jumlah', $jumlah, PDO::PARAM_INT);
            $stmt->bindParam(':id_alat', $id_alat, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {}
    }

    // Pulihkan stok alat
    private function restoreStock($id_alat, $jumlah) {
        try {
            $query = "UPDATE alat SET jumlah_stok = jumlah_stok + :jumlah WHERE id_alat = :id_alat";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':jumlah', $jumlah, PDO::PARAM_INT);
            $stmt->bindParam(':id_alat', $id_alat, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {}
    }

    // Ambil riwayat peminjaman khusus milik satu user (Peminjam)
    public function getPeminjamanByUser($id_user, $keyword = null) {
        $query = "SELECT p.id_peminjaman, p.id_alat, p.jumlah, p.tanggal_pinjam, p.tanggal_kembali, p.status, p.created_at,
                         a.nama_alat, a.harga_sewa, a.spesifikasi,
                         k.nama_kategori,
                         pb.id_pembayaran, pb.total_bayar, pb.metode_pembayaran, pb.status_pembayaran, pb.bukti_pembayaran, pb.tanggal_pembayaran
                  FROM " . $this->table_name . " p
                  LEFT JOIN alat a ON p.id_alat = a.id_alat
                  LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
                  LEFT JOIN pembayaran pb ON p.id_peminjaman = pb.id_peminjaman
                  WHERE p.id_user = :id_user";

        if (!empty($keyword)) {
            $query .= " AND (a.nama_alat LIKE :keyword OR k.nama_kategori LIKE :keyword OR p.status LIKE :keyword)";
        }

        $query .= " ORDER BY p.id_peminjaman DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        if (!empty($keyword)) {
            $kw = "%" . $keyword . "%";
            $stmt->bindParam(':keyword', $kw);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Hitung total peminjaman user berdasarkan status tertentu
    public function countUserPeminjamanByStatus($id_user, $status = null) {
        try {
            if ($status) {
                $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE id_user = :id_user AND status = :status";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
                $stmt->bindParam(':status', $status);
            } else {
                $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE id_user = :id_user";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            }
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }

    // Batalkan pengajuan peminjaman oleh peminjam (hanya jika masih 'menunggu')
    public function batalkanPeminjamanByUser($id_peminjaman, $id_user) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_peminjaman = :id AND id_user = :id_user AND status = 'menunggu'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id_peminjaman, PDO::PARAM_INT);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        return $stmt->execute();
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

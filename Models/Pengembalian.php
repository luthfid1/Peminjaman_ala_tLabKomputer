<?php

require_once 'Models/Database.php';

class Pengembalian {
    private $conn;
    private $table_name = "pengembalian";
    
    // Tarif denda keterlambatan default: Rp 10.000 / hari per unit alat
    const TARIF_DENDA_PER_HARI = 10000;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Menghitung denda keterlambatan otomatis berdasarkan selisih hari
     */
    public static function hitungDendaOtomatis($tanggal_kembali_seharusnya, $tanggal_pengembalian_aktual, $jumlah = 1) {
        $tglSeharusnya = new DateTime(date('Y-m-d', strtotime($tanggal_kembali_seharusnya)));
        $tglAktual     = new DateTime(date('Y-m-d', strtotime($tanggal_pengembalian_aktual)));

        if ($tglAktual > $tglSeharusnya) {
            $selisih = $tglSeharusnya->diff($tglAktual);
            $hariTerlambat = (int)$selisih->days;
            $totalDenda = $hariTerlambat * self::TARIF_DENDA_PER_HARI * max(1, (int)$jumlah);
            return [
                'hari_terlambat' => $hariTerlambat,
                'tarif_per_hari' => self::TARIF_DENDA_PER_HARI,
                'total_denda'    => $totalDenda
            ];
        }

        return [
            'hari_terlambat' => 0,
            'tarif_per_hari' => self::TARIF_DENDA_PER_HARI,
            'total_denda'    => 0
        ];
    }

    // Mengambil semua data pengembalian dengan relasi peminjaman, users, dan alat
    public function getAllPengembalian($keyword = null) {
        $query = "SELECT pg.*, 
                         p.jumlah, p.tanggal_pinjam, p.tanggal_kembali, p.status as status_peminjaman,
                         u.id_users, u.nama_lengkap, u.username, u.no_hp, u.Alamat,
                         a.id_alat, a.nama_alat, a.harga_sewa,
                         k.nama_kategori
                  FROM " . $this->table_name . " pg
                  LEFT JOIN peminjaman p ON pg.id_peminjaman = p.id_peminjaman
                  LEFT JOIN users u ON p.id_user = u.id_users
                  LEFT JOIN alat a ON p.id_alat = a.id_alat
                  LEFT JOIN kategori k ON a.id_kategori = k.id_kategori";

        if (!empty($keyword)) {
            $query .= " WHERE (u.nama_lengkap LIKE :keyword OR u.username LIKE :keyword OR u.no_hp LIKE :keyword OR a.nama_alat LIKE :keyword OR pg.keterangan LIKE :keyword)";
        }

        $query .= " ORDER BY pg.id_pengembalian DESC";

        $stmt = $this->conn->prepare($query);
        if (!empty($keyword)) {
            $kw = "%" . $keyword . "%";
            $stmt->bindParam(':keyword', $kw);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mengambil satu data pengembalian berdasarkan ID
    public function getPengembalianById($id) {
        $query = "SELECT pg.*, 
                         p.id_peminjaman, p.id_alat, p.jumlah, p.tanggal_pinjam, p.tanggal_kembali, p.status as status_peminjaman,
                         u.id_users, u.nama_lengkap, u.username, u.no_hp,
                         a.nama_alat, a.harga_sewa
                  FROM " . $this->table_name . " pg
                  LEFT JOIN peminjaman p ON pg.id_peminjaman = p.id_peminjaman
                  LEFT JOIN users u ON p.id_user = u.id_users
                  LEFT JOIN alat a ON p.id_alat = a.id_alat
                  WHERE pg.id_pengembalian = :id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mengambil daftar peminjaman yang aktif/sedang dipinjam (untuk dropdown transaksi yang mau dikembalikan)
    public function getPeminjamanSiapKembali() {
        $query = "SELECT p.id_peminjaman, p.id_alat, p.jumlah, p.tanggal_pinjam, p.tanggal_kembali, p.status,
                         u.nama_lengkap, u.username, u.no_hp,
                         a.nama_alat, a.harga_sewa
                  FROM peminjaman p
                  LEFT JOIN users u ON p.id_user = u.id_users
                  LEFT JOIN alat a ON p.id_alat = a.id_alat
                  WHERE p.status = 'dipinjam'
                  ORDER BY p.id_peminjaman DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tambah data pengembalian baru (proses pengembalian offline/online)
    public function createPengembalian($id_peminjaman, $tanggal_pengembalian, $denda_tambahan = 0, $keterangan = '') {
        // Ambil info transaksi peminjaman terkait
        $queryP = "SELECT * FROM peminjaman WHERE id_peminjaman = :id LIMIT 1";
        $stmtP = $this->conn->prepare($queryP);
        $stmtP->bindParam(':id', $id_peminjaman, PDO::PARAM_INT);
        $stmtP->execute();
        $peminjaman = $stmtP->fetch(PDO::FETCH_ASSOC);

        if (!$peminjaman) {
            return false;
        }

        // Hitung denda keterlambatan otomatis
        $kalkulasiDenda = self::hitungDendaOtomatis($peminjaman['tanggal_kembali'], $tanggal_pengembalian, $peminjaman['jumlah']);
        $dendaKeterlambatan = (float)$kalkulasiDenda['total_denda'];
        $dendaTambahan = max(0, (float)$denda_tambahan);
        $totalDenda = $dendaKeterlambatan + $dendaTambahan;

        // Otomatis tambahkan catatan keterlambatan jika telat
        $keteranganFinal = trim($keterangan);
        if ($kalkulasiDenda['hari_terlambat'] > 0) {
            $catatanTelat = "[Terlambat " . $kalkulasiDenda['hari_terlambat'] . " hari (Denda: Rp " . number_format($dendaKeterlambatan, 0, ',', '.') . ")]";
            if (!empty($keteranganFinal)) {
                $keteranganFinal = $catatanTelat . " " . $keteranganFinal;
            } else {
                $keteranganFinal = $catatanTelat;
            }
        }

        // Insert ke tabel pengembalian
        $query = "INSERT INTO " . $this->table_name . " (id_peminjaman, tanggal_pengembalian, denda, keterangan) 
                  VALUES (:id_peminjaman, :tanggal_pengembalian, :denda, :keterangan)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_peminjaman', $id_peminjaman, PDO::PARAM_INT);
        $stmt->bindParam(':tanggal_pengembalian', $tanggal_pengembalian);
        $stmt->bindParam(':denda', $totalDenda);
        $stmt->bindParam(':keterangan', $keteranganFinal);
        $result = $stmt->execute();

        if ($result) {
            // Update status di peminjaman menjadi 'dikembalikan'
            $queryUpd = "UPDATE peminjaman SET status = 'dikembalikan' WHERE id_peminjaman = :id_peminjaman";
            $stmtUpd = $this->conn->prepare($queryUpd);
            $stmtUpd->bindParam(':id_peminjaman', $id_peminjaman, PDO::PARAM_INT);
            $stmtUpd->execute();

            // Kembalikan stok fisik alat musik
            if ($peminjaman['status'] === 'dipinjam') {
                $queryStok = "UPDATE alat SET jumlah_stok = jumlah_stok + :jumlah WHERE id_alat = :id_alat";
                $stmtStok = $this->conn->prepare($queryStok);
                $stmtStok->bindParam(':jumlah', $peminjaman['jumlah'], PDO::PARAM_INT);
                $stmtStok->bindParam(':id_alat', $peminjaman['id_alat'], PDO::PARAM_INT);
                $stmtStok->execute();
            }
        }

        return $result;
    }

    // Ubah data pengembalian
    public function updatePengembalian($id_pengembalian, $tanggal_pengembalian, $denda_tambahan = 0, $keterangan = '') {
        $data = $this->getPengembalianById($id_pengembalian);
        if (!$data) {
            return false;
        }

        // Hitung ulang denda keterlambatan otomatis berdasarkan tanggal kembali asal
        $kalkulasiDenda = self::hitungDendaOtomatis($data['tanggal_kembali'], $tanggal_pengembalian, $data['jumlah']);
        $dendaKeterlambatan = (float)$kalkulasiDenda['total_denda'];
        $dendaTambahan = max(0, (float)$denda_tambahan);
        $totalDenda = $dendaKeterlambatan + $dendaTambahan;

        $query = "UPDATE " . $this->table_name . " 
                  SET tanggal_pengembalian = :tanggal_pengembalian, denda = :denda, keterangan = :keterangan
                  WHERE id_pengembalian = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tanggal_pengembalian', $tanggal_pengembalian);
        $stmt->bindParam(':denda', $totalDenda);
        $stmt->bindParam(':keterangan', $keterangan);
        $stmt->bindParam(':id', $id_pengembalian, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Hapus data pengembalian (dan kembalikan status peminjaman ke 'dipinjam' jika perlu)
    public function deletePengembalian($id_pengembalian) {
        $data = $this->getPengembalianById($id_pengembalian);

        $query = "DELETE FROM " . $this->table_name . " WHERE id_pengembalian = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id_pengembalian, PDO::PARAM_INT);
        $result = $stmt->execute();

        if ($result && $data) {
            // Kembalikan status peminjaman jadi 'dipinjam'
            $queryUpd = "UPDATE peminjaman SET status = 'dipinjam' WHERE id_peminjaman = :id_peminjaman";
            $stmtUpd = $this->conn->prepare($queryUpd);
            $stmtUpd->bindParam(':id_peminjaman', $data['id_peminjaman'], PDO::PARAM_INT);
            $stmtUpd->execute();

            // Kurangi kembali stok alat musik karena status kembali 'dipinjam'
            $queryStok = "UPDATE alat SET jumlah_stok = GREATEST(0, jumlah_stok - :jumlah) WHERE id_alat = :id_alat";
            $stmtStok = $this->conn->prepare($queryStok);
            $stmtStok->bindParam(':jumlah', $data['jumlah'], PDO::PARAM_INT);
            $stmtStok->bindParam(':id_alat', $data['id_alat'], PDO::PARAM_INT);
            $stmtStok->execute();
        }

        return $result;
    }

    // Ambil data riwayat pengembalian khusus milik satu user (Peminjam)
    public function getPengembalianByUser($id_user, $keyword = null) {
        $query = "SELECT pg.*, 
                         p.jumlah, p.tanggal_pinjam, p.tanggal_kembali, p.status as status_peminjaman,
                         a.id_alat, a.nama_alat, a.harga_sewa,
                         k.nama_kategori
                  FROM " . $this->table_name . " pg
                  INNER JOIN peminjaman p ON pg.id_peminjaman = p.id_peminjaman
                  LEFT JOIN alat a ON p.id_alat = a.id_alat
                  LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
                  WHERE p.id_user = :id_user";

        if (!empty($keyword)) {
            $query .= " AND (a.nama_alat LIKE :keyword OR k.nama_kategori LIKE :keyword OR pg.keterangan LIKE :keyword)";
        }

        $query .= " ORDER BY pg.id_pengembalian DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        if (!empty($keyword)) {
            $kw = "%" . $keyword . "%";
            $stmt->bindParam(':keyword', $kw);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Total denda akumulasi milik satu user
    public function getTotalDendaByUser($id_user) {
        try {
            $query = "SELECT SUM(pg.denda) 
                      FROM " . $this->table_name . " pg
                      INNER JOIN peminjaman p ON pg.id_peminjaman = p.id_peminjaman
                      WHERE p.id_user = :id_user";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $stmt->execute();
            return (float)($stmt->fetchColumn() ?? 0);
        } catch (Exception $e) {
            return 0;
        }
    }
}

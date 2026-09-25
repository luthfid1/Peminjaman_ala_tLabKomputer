-- ==========================================================
-- Database: db_peminjaman_alat
-- Proyek: Aplikasi Peminjaman Alat Lab Komputer
-- UKK Rekayasa Perangkat Lunak 2025/2026 Paket 1
-- Studi Kasus: Lingkungan Sekolah (Format Waktu: TIME)
-- ==========================================================

CREATE DATABASE IF NOT EXISTS `db_peminjaman_alat`;
USE `db_peminjaman_alat`;

-- ----------------------------------------------------------
-- 1. Tabel: user
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` INT(11) NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(100) NOT NULL,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'pengelola', 'petugas', 'peminjam') NOT NULL DEFAULT 'peminjam',
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------
-- 2. Tabel: peminjam
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `peminjam` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(100) NOT NULL,
  `nis` VARCHAR(30) NOT NULL,
  `kelas` VARCHAR(20) NOT NULL,
  `jurusan` VARCHAR(100) NOT NULL,
  `no_telp` VARCHAR(20) DEFAULT NULL,
  `foto_kartu_pelajar` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------
-- 3. Tabel: kategori
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `kategori` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------
-- 4. Tabel: alat
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `alat` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `kode` VARCHAR(50) NOT NULL,
  `id_kategori` INT(11) NOT NULL,
  `nama_alat` VARCHAR(100) NOT NULL,
  `jumlah` INT(11) NOT NULL DEFAULT 0,
  `kondisi` VARCHAR(50) NOT NULL DEFAULT 'Baik',
  `deskripsi` VARCHAR(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_alat_kategori` (`id_kategori`),
  CONSTRAINT `fk_alat_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------
-- 5. Tabel: peminjaman (Menggunakan Waktu: TIME)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `peminjaman` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `kode_peminjaman` VARCHAR(50) NOT NULL,
  `id_peminjam` INT(11) NOT NULL,
  `id_user` INT(11) DEFAULT NULL,
  `waktu_pinjam` TIME NOT NULL,
  `waktu_rencana_kembali` TIME NOT NULL,
  `waktu_kembali` TIME DEFAULT NULL,
  `jenis_peminjaman` VARCHAR(30) DEFAULT 'Praktek Lab',
  `keperluan` TEXT DEFAULT NULL,
  `status` VARCHAR(30) NOT NULL DEFAULT 'menunggu',
  PRIMARY KEY (`id`),
  KEY `fk_peminjaman_peminjam` (`id_peminjam`),
  KEY `fk_peminjaman_user` (`id_user`),
  CONSTRAINT `fk_peminjaman_peminjam` FOREIGN KEY (`id_peminjam`) REFERENCES `peminjam` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_peminjaman_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------
-- 6. Tabel: detail_peminjaman
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `detail_peminjaman` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_peminjaman` INT(11) NOT NULL,
  `id_alat` INT(11) NOT NULL,
  `jumlah` INT(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `fk_detail_peminjaman` (`id_peminjaman`),
  KEY `fk_detail_alat` (`id_alat`),
  CONSTRAINT `fk_detail_peminjaman` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_detail_alat` FOREIGN KEY (`id_alat`) REFERENCES `alat` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------
-- 7. Tabel: pengembalian (Menggunakan Waktu: TIME)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pengembalian` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_peminjaman` INT(11) NOT NULL,
  `waktu_kembali` TIME NOT NULL,
  `kondisi_kembali` VARCHAR(100) NOT NULL DEFAULT 'Baik',
  `denda` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `id_user` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pengembalian_peminjaman` (`id_peminjaman`),
  KEY `fk_pengembalian_user` (`id_user`),
  CONSTRAINT `fk_pengembalian_peminjaman` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pengembalian_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------
-- 8. Tabel: log_aktivitas
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `log_aktivitas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_user` INT(11) DEFAULT NULL,
  `aktivitas` VARCHAR(100) NOT NULL,
  `deskripsi` TEXT DEFAULT NULL,
  `waktu` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_log_user` (`id_user`),
  CONSTRAINT `fk_log_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------
-- SEED DATA
-- ----------------------------------------------------------
INSERT INTO `user` (`id_user`, `nama`, `username`, `password`, `role`) VALUES
(1, 'Admin Pengelola Lab', 'pengelola', '$2y$10$wT0lQn8w8a8J.lZ/B8y/8eX1Q7YQ5tFh2gCqL.N4Z6M7s4Q7kP6eS', 'pengelola'),
(2, 'Ketua Jurusan RPL', 'kejur', '$2y$10$wT0lQn8w8a8J.lZ/B8y/8eX1Q7YQ5tFh2gCqL.N4Z6M7s4Q7kP6eS', 'admin'),
(3, 'Petugas Laboran Lab Komputer', 'petugas', '$2y$10$wT0lQn8w8a8J.lZ/B8y/8eX1Q7YQ5tFh2gCqL.N4Z6M7s4Q7kP6eS', 'petugas'),
(4, 'Siswa Peminjam Lab', 'siswa', '$2y$10$wT0lQn8w8a8J.lZ/B8y/8eX1Q7YQ5tFh2gCqL.N4Z6M7s4Q7kP6eS', 'peminjam')
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`);

INSERT INTO `peminjam` (`id`, `nama`, `nis`, `kelas`, `jurusan`, `no_telp`, `foto_kartu_pelajar`) VALUES
(1, 'Luthfi Diandi Rusmana', '10238491', 'XII RPL 4', 'Rekayasa Perangkat Lunak', '081234567890', 'kartu_luthfi.jpg'),
(2, 'Muhammad Rayhan', '10238492', 'XII RPL 4', 'Rekayasa Perangkat Lunak', '081298765432', 'kartu_rayhan.jpg'),
(3, 'Siti Nurhaliza', '10238493', 'XII TKJ 2', 'Teknik Komputer dan Jaringan', '085712345678', 'kartu_siti.jpg')
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`);

INSERT INTO `kategori` (`id`, `nama_kategori`) VALUES
(1, 'Perangkat Jaringan (Networking)'),
(2, 'Komputer & Laptop'),
(3, 'Tools & Pengkabelan'),
(4, 'Multimedia & Presentasi'),
(5, 'Microcontroller & IoT')
ON DUPLICATE KEY UPDATE `nama_kategori` = VALUES(`nama_kategori`);

INSERT INTO `alat` (`id`, `kode`, `id_kategori`, `nama_alat`, `jumlah`, `kondisi`, `deskripsi`) VALUES
(1, 'ALT-NET-001', 1, 'MikroTik RouterBoard RB750r2', 15, 'Baik', 'Routerboard 5 port Ethernet 10/100 Mbps untuk simulasi routing dan hotspot.'),
(2, 'ALT-NET-002', 1, 'Cisco Catalyst Switch 2960 24-Port', 6, 'Baik', 'Managed switch 24 port fast ethernet untuk topologi VLAN.'),
(3, 'ALT-TOL-001', 3, 'Crimping Tool RJ45 RJ11 Cat5/Cat6', 20, 'Baik', 'Tang crimping kabel UTP RJ45 & RJ11 gagang ergonomis.'),
(4, 'ALT-TOL-002', 3, 'Network Cable Tester RJ45 RJ11', 18, 'Baik', 'Tester sambungan kabel LAN UTP dengan indikator LED.'),
(5, 'ALT-PC-001', 2, 'PC Desktop Lab Intel Core i7 16GB', 35, 'Baik', 'PC lab workstation untuk pengembangan perangkat lunak dan kompilasi sistem.'),
(6, 'ALT-MLT-001', 4, 'Proyektor Epson EB-X500 XGA 3600 Lumens', 4, 'Baik', 'Proyektor ruang presentasi laboratorium dengan port HDMI/VGA.'),
(7, 'ALT-IOT-001', 5, 'Arduino Uno R3 Starter Kit Lengkap', 25, 'Baik', 'Paket modul sensor, breadboard, kabel jumper, dan mikrokontroler Uno R3.')
ON DUPLICATE KEY UPDATE `nama_alat` = VALUES(`nama_alat`);

INSERT INTO `log_aktivitas` (`id_user`, `aktivitas`, `deskripsi`, `waktu`) VALUES
(1, 'Inisialisasi Sistem', 'Database dan inventaris lab komputer berhasil diinisialisasi', NOW());

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 19 Okt 2026 pada 04.33
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_peminjaman_alat`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `alat`
--

CREATE TABLE `alat` (
  `id_alat` int(11) NOT NULL,
  `kode` varchar(50) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `nama_alat` varchar(100) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 0,
  `kondisi` varchar(50) NOT NULL,
  `deskripsi` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `alat`
--

INSERT INTO `alat` (`id_alat`, `kode`, `id_kategori`, `nama_alat`, `jumlah`, `kondisi`, `deskripsi`) VALUES
(2, 'ALT002', 2, 'Laptop ASUS VivoBook', 10, 'Baik', 'Laptop untuk kegiatan pembelajaran'),
(3, 'ALT003', 2, 'Laptop Lenovo ThinkPad', 8, 'Baik', 'Laptop untuk praktik pemrograman'),
(4, 'ALT004', 3, 'Mouse Wireless', 15, 'Baik', 'Mouse tambahan untuk komputer dan laptop'),
(5, 'ALT005', 3, 'Keyboard USB', 15, 'Baik', 'Keyboard tambahan untuk komputer'),
(6, 'ALT006', 3, 'Webcam', 8, 'Baik', 'Webcam untuk kegiatan pembelajaran dan video conference'),
(7, 'ALT007', 4, 'Proyektor Epson', 5, 'Baik', 'Proyektor untuk presentasi'),
(8, 'ALT008', 4, 'Kamera Digital', 3, 'Baik', 'Kamera untuk dokumentasi kegiatan'),
(9, 'ALT009', 4, 'Tripod Kamera', 5, 'Baik', 'Tripod untuk mendukung penggunaan kamera'),
(10, 'ALT010', 4, 'Microphone', 6, 'Baik', 'Microphone untuk kegiatan presentasi dan dokumentasi'),
(12, 'ALT012', 5, 'Kabel LAN', 30, 'Baik', 'Kabel jaringan untuk praktik jaringan'),
(13, 'ALT013', 5, 'Router TP-Link', 11, 'Baik', 'Router untuk praktik jaringan'),
(14, 'ALT014', 5, 'Switch', 8, 'Baik', 'Switch untuk praktik jaringan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_peminjaman`
--

CREATE TABLE `detail_peminjaman` (
  `id_detail_peminjaman` int(11) NOT NULL,
  `id_peminjaman` int(11) NOT NULL,
  `id_alat` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_peminjaman`
--

INSERT INTO `detail_peminjaman` (`id_detail_peminjaman`, `id_peminjaman`, `id_alat`, `jumlah`) VALUES
(3, 3, 4, 1),
(15, 15, 9, 1),
(16, 16, 13, 1),
(17, 17, 12, 1),
(18, 17, 5, 1),
(19, 18, 3, 1),
(20, 18, 13, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(2, 'Laptop'),
(3, 'Perangkat'),
(4, 'Multimedia'),
(5, 'Jaringan'),
(6, 'Proyektor');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id_log_aktivitas` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `aktivitas` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `waktu` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id_log_aktivitas`, `id_user`, `aktivitas`, `deskripsi`, `waktu`) VALUES
(10, 4, 'Registrasi Akun Siswa', 'Pendaftaran siswa baru: ismiayu (NIS: 863411)', '2026-09-25 20:02:04'),
(11, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-09-25 20:02:11'),
(12, 4, 'Pengajuan Peminjaman Alat', 'Siswa ismiayu mengajukan peminjaman: Macbook (1 unit)', '2026-09-25 20:02:50'),
(13, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-25 20:13:06'),
(14, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-09-25 20:18:22'),
(15, 3, 'Menyetujui Peminjaman', 'Petugas menyetujui peminjaman #PMJ-20260925-0001 (ismiayu)', '2026-09-25 20:18:45'),
(17, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-26 14:25:05'),
(18, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-09-26 14:25:18'),
(19, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-26 14:30:08'),
(20, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-09-26 14:35:07'),
(21, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-26 15:07:07'),
(22, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-09-26 15:07:18'),
(23, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-09-26 15:08:02'),
(24, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-09-26 15:10:03'),
(25, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-26 15:10:10'),
(26, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-09-26 15:10:18'),
(27, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-26 15:10:22'),
(28, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-09-26 15:10:29'),
(29, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-26 15:28:49'),
(30, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-09-26 15:33:53'),
(31, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-26 15:45:16'),
(32, 5, 'Registrasi Akun Siswa', 'Pendaftaran siswa baru: luthfi diandi Rusmana (NIS: 7351878157)', '2026-09-26 15:46:03'),
(33, 6, 'Registrasi Akun Siswa', 'Pendaftaran siswa baru dengan jaminan foto: diandi (NIS: 009706740)', '2026-09-26 15:58:40'),
(34, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-09-26 15:59:30'),
(35, 3, 'Menyerahkan Alat Lab', 'Alat untuk peminjaman #PMJ-20260925-0001 telah diserahkan fisik kepada siswa', '2026-09-26 15:59:42'),
(36, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-26 16:00:33'),
(37, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-09-26 16:00:47'),
(38, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-09-26 16:22:08'),
(39, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-26 16:24:22'),
(40, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-09-26 16:24:31'),
(41, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-26 16:28:58'),
(42, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-09-26 16:29:18'),
(43, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-26 16:31:33'),
(44, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-09-26 16:31:48'),
(45, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-26 16:42:04'),
(46, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-09-26 16:42:58'),
(47, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-26 16:44:41'),
(48, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-09-26 16:44:48'),
(49, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-26 16:46:14'),
(50, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-09-26 16:46:21'),
(51, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-09-26 16:46:22'),
(52, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-26 17:04:37'),
(53, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-09-26 17:05:35'),
(54, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-26 17:09:29'),
(55, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-09-26 17:09:35'),
(56, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-26 17:10:12'),
(57, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-09-26 17:10:20'),
(58, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-26 17:11:50'),
(59, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-09-26 17:11:52'),
(60, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-26 17:51:00'),
(61, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-09-26 17:51:06'),
(62, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-26 18:04:34'),
(63, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-09-26 18:04:42'),
(64, 2, 'Menghapus Alat Lab', 'Menghapus alat: Speaker Portable', '2026-09-26 18:04:50'),
(65, 2, 'Mengubah Data Alat Lab', 'ID: 13, Nama: Router TP-Link', '2026-09-26 18:04:59'),
(66, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-26 18:15:29'),
(67, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-09-26 18:15:37'),
(68, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-26 18:37:16'),
(69, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-09-26 18:37:25'),
(70, 2, 'Menambah Kategori', 'Kategori: Proyektor', '2026-09-26 18:38:52'),
(71, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-09-27 00:48:59'),
(72, 4, 'Pengajuan Peminjaman Alat', 'Siswa ismiayu mengajukan peminjaman: Switch (1 unit)', '2026-09-27 00:49:41'),
(73, 4, 'Batalkan Peminjaman', 'Siswa membatalkan permohonan #PMJ-20260926-0001', '2026-09-27 00:50:37'),
(74, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-27 00:57:40'),
(75, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-09-27 01:06:19'),
(76, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-27 01:07:10'),
(77, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-09-27 01:07:14'),
(78, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-27 01:28:48'),
(79, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-09-27 01:28:53'),
(80, 7, 'Registrasi Akun Siswa', 'Pendaftaran siswa baru dengan jaminan foto: luthfid (NIS: 8319272)', '2026-09-27 14:37:26'),
(81, 7, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-09-27 14:37:33'),
(82, 7, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-27 14:38:31'),
(83, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-09-27 14:38:38'),
(84, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-27 14:38:49'),
(85, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-09-27 14:38:53'),
(86, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-27 14:39:10'),
(87, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-09-27 14:39:17'),
(88, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-27 19:29:48'),
(89, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-09-27 19:32:03'),
(90, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-27 19:44:28'),
(91, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-09-27 19:44:32'),
(92, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-27 20:12:42'),
(93, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-09-27 20:12:46'),
(94, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-09-28 09:03:43'),
(95, 2, 'Menambah Kategori', 'Kategori: kabel', '2026-09-28 09:03:57'),
(96, 2, 'Menghapus Kategori', 'Menghapus kategori: kabel', '2026-09-28 09:04:01'),
(97, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-28 09:12:37'),
(98, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-09-28 09:12:43'),
(99, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-28 09:35:33'),
(100, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-09-28 09:35:36'),
(101, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-09-30 10:23:24'),
(102, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-09-30 14:06:22'),
(103, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-30 15:17:15'),
(104, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-09-30 15:17:22'),
(105, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-30 15:35:51'),
(106, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-09-30 15:35:57'),
(107, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-30 15:47:11'),
(108, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-09-30 15:47:15'),
(109, 4, 'Pengajuan Peminjaman Alat', 'Siswa ismiayu mengajukan peminjaman: Mouse Wireless (1 unit)', '2026-09-30 15:55:42'),
(110, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-30 16:01:13'),
(111, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-09-30 16:01:16'),
(112, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-30 16:06:34'),
(113, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-09-30 16:06:37'),
(114, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-30 16:07:11'),
(115, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-09-30 16:07:14'),
(116, 3, 'Menyetujui Peminjaman', 'Petugas menyetujui peminjaman #PMJ-20260930-0001 (ismiayu)', '2026-09-30 16:07:30'),
(117, 3, 'Menyerahkan Alat Lab', 'Alat untuk peminjaman #PMJ-20260930-0001 telah diserahkan fisik kepada siswa', '2026-09-30 16:07:35'),
(118, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-09-30 16:07:39'),
(119, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-09-30 16:07:42'),
(120, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-01 09:56:46'),
(121, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-01 09:57:01'),
(122, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-01 09:57:07'),
(123, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-01 10:00:17'),
(124, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-01 10:00:24'),
(125, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-01 10:08:09'),
(126, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-01 10:08:21'),
(127, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-01 10:11:38'),
(128, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-01 10:11:48'),
(129, 4, 'Pengajuan Peminjaman Alat', 'Siswa ismiayu mengajukan peminjaman: PC Laboratorium (1 unit)', '2026-10-01 10:12:00'),
(130, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-01 10:12:03'),
(131, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-01 10:12:10'),
(132, 3, 'Menolak Peminjaman', 'Petugas menolak peminjaman #PMJ-20261001-0001 (ismiayu)', '2026-10-01 10:14:35'),
(133, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-01 10:35:00'),
(134, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-01 10:35:03'),
(135, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-01 10:35:14'),
(136, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-01 10:35:17'),
(137, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-01 10:35:30'),
(138, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-01 10:35:32'),
(139, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-01 10:35:48'),
(140, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-01 10:35:54'),
(141, 3, 'Mencatat Pengembalian', 'Petugas memproses pengembalian ID #1 (Peminjaman #3)', '2026-10-01 10:36:18'),
(142, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-01 10:36:21'),
(143, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-01 10:36:26'),
(144, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-02 06:24:10'),
(145, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-02 06:24:20'),
(146, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-02 06:24:24'),
(147, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-02 06:24:32'),
(148, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-04 11:23:20'),
(149, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-04 11:23:50'),
(150, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-10-04 11:23:56'),
(151, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-04 11:24:02'),
(152, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-04 11:24:06'),
(153, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-04 13:08:52'),
(154, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-04 13:08:55'),
(155, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-04 14:17:24'),
(156, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-04 14:21:50'),
(157, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-04 16:15:30'),
(158, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-04 16:15:40'),
(159, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-04 16:15:58'),
(160, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-04 16:16:02'),
(161, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-04 16:17:52'),
(162, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-04 16:17:56'),
(163, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-04 17:44:31'),
(164, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-04 17:44:34'),
(165, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-05 18:59:59'),
(166, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-07 16:37:03'),
(167, 4, 'Pengajuan Peminjaman Alat', 'Siswa ismiayu mengajukan peminjaman: Switch (1 unit)', '2026-10-07 16:37:26'),
(168, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-07 16:37:30'),
(169, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-07 16:37:33'),
(170, 3, 'Menyetujui Peminjaman', 'Petugas menyetujui peminjaman #PMJ-20261007-0001 (ismiayu)', '2026-10-07 16:37:37'),
(171, 3, 'Menyerahkan Alat Lab', 'Alat untuk peminjaman #PMJ-20261007-0001 telah diserahkan fisik kepada siswa', '2026-10-07 16:37:46'),
(172, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-07 16:38:37'),
(173, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-07 16:38:41'),
(174, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-07 16:52:12'),
(175, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-07 16:52:20'),
(176, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-07 16:53:00'),
(177, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-07 16:53:12'),
(178, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-07 17:22:29'),
(179, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-07 17:22:34'),
(180, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-07 17:22:37'),
(181, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-07 17:22:39'),
(182, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-07 17:34:23'),
(183, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-07 17:34:32'),
(184, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-07 17:35:47'),
(185, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-07 17:35:51'),
(186, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-09 07:49:15'),
(187, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-09 09:42:37'),
(188, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-12 18:25:26'),
(189, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-12 18:25:42'),
(190, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-12 18:25:48'),
(191, 3, 'Mencatat Pengembalian', 'Petugas memproses pengembalian ID #2 (Peminjaman #5)', '2026-10-12 18:26:04'),
(192, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-12 18:26:10'),
(193, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-12 18:42:32'),
(194, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-12 18:51:58'),
(195, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-12 18:58:56'),
(196, 4, 'Pengajuan Peminjaman Alat', 'Siswa ismiayu mengajukan peminjaman: Microphone (1 unit)', '2026-10-12 18:59:10'),
(197, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-12 18:59:14'),
(198, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-12 18:59:19'),
(199, 3, 'Menyetujui Peminjaman', 'Petugas menyetujui peminjaman #PMJ-20261012-0001 (ismiayu)', '2026-10-12 18:59:26'),
(200, 3, 'Menyerahkan Alat Lab', 'Alat untuk peminjaman #PMJ-20261012-0001 telah diserahkan fisik kepada siswa', '2026-10-12 18:59:31'),
(201, 3, 'Kirim Pengingat Pengembalian', 'Petugas mengirim notifikasi pengembalian alat #PMJ-20261012-0001 kepada siswa ismiayu', '2026-10-12 18:59:42'),
(202, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-12 18:59:45'),
(203, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-12 18:59:48'),
(204, 4, 'Pengajuan Pengembalian Alat', 'Siswa ismiayu mengajukan pengembalian alat #PMJ-20261012-0001 (Microphone) - Status diubah menjadi Menunggu Validasi Petugas', '2026-10-12 19:05:19'),
(205, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-12 19:05:25'),
(206, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-12 19:05:30'),
(207, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-12 19:53:46'),
(208, 8, 'Registrasi Akun Siswa', 'Pendaftaran siswa baru: anggota1 (NIS: 00987616161)', '2026-10-12 19:54:26'),
(209, 8, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-12 19:54:32'),
(210, 8, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-12 20:40:48'),
(211, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-10-12 20:40:53'),
(212, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-12 20:41:24'),
(213, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-12 20:41:27'),
(214, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-12 21:14:24'),
(215, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-10-12 21:16:08'),
(216, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-12 21:18:18'),
(217, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-12 21:18:27'),
(218, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-12 21:31:16'),
(219, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-10-12 21:31:21'),
(220, 2, 'Menambah Pengguna', 'User: budiaman, Role: admin pengelola', '2026-10-12 21:31:54'),
(221, 2, 'Menghapus Pengguna', 'Menghapus akun: budiaman', '2026-10-12 21:32:02'),
(222, 2, 'Menambah Pengguna', 'User: didin, Role: peminjam', '2026-10-12 21:32:46'),
(223, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-12 22:09:02'),
(224, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-10-12 22:09:08'),
(225, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-10-13 15:43:04'),
(226, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-13 18:37:55'),
(227, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-13 18:52:00'),
(228, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-13 18:52:05'),
(229, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-13 18:52:13'),
(230, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-10-13 18:52:19'),
(231, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-10-13 18:52:19'),
(232, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-13 20:59:24'),
(233, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-13 20:59:32'),
(234, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-13 21:28:58'),
(235, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-10-13 21:29:02'),
(236, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-14 06:24:26'),
(237, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-14 08:04:03'),
(238, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 08:04:18'),
(239, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-14 08:04:24'),
(240, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 08:04:28'),
(241, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-10-14 08:04:31'),
(242, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 08:44:04'),
(243, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-14 08:44:20'),
(244, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 08:45:11'),
(245, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-14 08:45:29'),
(246, 4, 'Pengajuan Peminjaman Alat', 'Siswa ismiayu mengajukan peminjaman: Kamera Digital (1 unit)', '2026-10-14 08:45:56'),
(247, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 08:46:02'),
(248, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-14 08:46:09'),
(249, 3, 'Menyetujui Peminjaman', 'Petugas menyetujui peminjaman #PMJ-20261014-0001 (ismiayu)', '2026-10-14 08:46:22'),
(250, 3, 'Menyerahkan Alat Lab', 'Alat untuk peminjaman #PMJ-20261014-0001 telah diserahkan fisik kepada siswa', '2026-10-14 08:46:28'),
(251, 3, 'Kirim Pengingat Pengembalian', 'Petugas mengirim notifikasi pengembalian alat #PMJ-20261014-0001 kepada siswa ismiayu', '2026-10-14 08:46:35'),
(252, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 08:46:37'),
(253, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-14 08:46:44'),
(254, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 08:47:00'),
(255, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-14 08:47:07'),
(256, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 08:47:22'),
(257, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-14 08:47:26'),
(258, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 08:47:58'),
(259, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-14 08:48:03'),
(260, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-14 08:58:52'),
(261, 4, 'Pengajuan Peminjaman Alat', 'Siswa ismiayu mengajukan peminjaman: Switch (1 unit)', '2026-10-14 08:59:01'),
(262, 4, 'Pengajuan Peminjaman Alat', 'Siswa ismiayu mengajukan peminjaman: Router TP-Link (1 unit)', '2026-10-14 08:59:08'),
(263, 4, 'Batalkan Peminjaman', 'Siswa membatalkan permohonan #PMJ-20261014-0003', '2026-10-14 08:59:19'),
(264, 4, 'Batalkan Peminjaman', 'Siswa membatalkan permohonan #PMJ-20261014-0002', '2026-10-14 08:59:22'),
(265, 4, 'Pengajuan Peminjaman Alat', 'Siswa ismiayu mengajukan peminjaman: Switch (1 unit)', '2026-10-14 08:59:34'),
(266, 4, 'Batalkan Peminjaman', 'Siswa membatalkan permohonan #PMJ-20261014-0004', '2026-10-14 09:00:01'),
(267, 4, 'Pengajuan Pengembalian Alat', 'Siswa ismiayu mengajukan pengembalian alat #PMJ-20261014-0001 (Kamera Digital (1 unit)) - Status diubah menjadi Menunggu Validasi Petugas', '2026-10-14 09:50:18'),
(268, 4, 'Pengajuan Pengembalian Alat', 'Siswa ismiayu mengajukan pengembalian alat #PMJ-20260925-0001 (Macbook (1 unit)) - Status diubah menjadi Menunggu Validasi Petugas', '2026-10-14 09:50:22'),
(269, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 09:50:28'),
(270, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-14 09:50:32'),
(271, 3, 'Verifikasi Pengembalian Alat', 'Petugas Petugas telah memvalidasi fisik & menerima pengembalian Kamera Digital (1 unit) (1 unit) dari siswa ismiayu (Kode: PMJ-20261014-0001). Kondisi: Baik & Lengkap, Denda: Rp15.000 | Stok alat laboratorium berhasil dipulihkan.', '2026-10-14 09:50:47'),
(272, 3, 'Verifikasi Pengembalian Alat', 'Petugas Petugas telah memvalidasi fisik & menerima pengembalian Microphone (1 unit) (1 unit) dari siswa ismiayu (Kode: PMJ-20261012-0001). Kondisi: Baik & Lengkap, Denda: Rp15.000 | Stok alat laboratorium berhasil dipulihkan.', '2026-10-14 09:50:50'),
(273, 3, 'Verifikasi Pengembalian Alat', 'Petugas Petugas telah memvalidasi fisik & menerima pengembalian Macbook (1 unit) (1 unit) dari siswa ismiayu (Kode: PMJ-20260925-0001). Kondisi: Baik & Lengkap, Denda: Rp0 | Stok alat laboratorium berhasil dipulihkan.', '2026-10-14 09:50:52'),
(274, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 09:52:32'),
(275, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-14 10:15:56'),
(276, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 10:16:14'),
(277, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-14 10:16:17'),
(278, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 10:16:51'),
(279, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-14 10:17:00'),
(280, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 10:17:02'),
(281, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-10-14 10:17:05'),
(282, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 10:17:19'),
(283, 8, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-14 10:17:26'),
(284, 8, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 10:52:30'),
(285, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-10-14 10:52:40'),
(286, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 10:52:56'),
(287, 11, 'Registrasi Akun Siswa', 'Pendaftaran siswa baru: shodik (NIS: 00098877655)', '2026-10-14 10:55:06'),
(288, 11, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-14 10:55:18'),
(289, 11, 'Pengajuan Peminjaman Alat', 'Siswa shodik mengajukan peminjaman 1 jenis alat dalam 1 transaksi: Macbook (1 unit)', '2026-10-14 10:55:57'),
(290, 11, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 10:56:00'),
(291, 11, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-14 10:56:06'),
(292, 11, 'Batalkan Peminjaman', 'Siswa membatalkan permohonan #PMJ-20261014-0005', '2026-10-14 10:56:11'),
(293, 11, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 10:56:13'),
(294, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-14 11:07:36'),
(295, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 11:07:48'),
(296, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-14 11:07:52'),
(297, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-14 11:09:19'),
(298, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 11:09:30'),
(299, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-14 11:09:33'),
(300, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 11:09:40'),
(301, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-14 11:09:43'),
(302, 4, 'Pengajuan Peminjaman Alat', 'Siswa ismiayu mengajukan peminjaman 1 jenis alat dalam 1 transaksi: Router TP-Link (1 unit)', '2026-10-14 11:11:04'),
(303, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 11:11:30'),
(304, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-14 11:11:35'),
(305, 3, 'Menyetujui Peminjaman', 'Petugas menyetujui peminjaman #PMJ-20261014-0006 (ismiayu)', '2026-10-14 11:11:43'),
(306, 3, 'Menyerahkan Alat Lab', 'Alat untuk peminjaman #PMJ-20261014-0006 telah diserahkan fisik kepada siswa', '2026-10-14 11:11:49'),
(307, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 11:12:20'),
(308, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-14 11:12:31'),
(309, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 11:12:35'),
(310, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-14 11:12:39'),
(311, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 11:12:55'),
(312, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-10-14 11:13:01'),
(313, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 11:14:27'),
(314, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-14 18:28:29'),
(315, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 18:28:48'),
(316, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-10-14 18:28:55'),
(317, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 18:38:01'),
(318, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-14 18:38:04'),
(319, 4, 'Pengajuan Pengembalian Alat', 'Siswa ismiayu mengajukan pengembalian alat #PMJ-20261014-0006 (Router TP-Link (1 unit)) - Status diubah menjadi Menunggu Validasi Petugas', '2026-10-14 18:38:33'),
(320, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 18:38:46'),
(321, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-14 18:38:54'),
(322, 3, 'Verifikasi Pengembalian Alat', 'Petugas Petugas telah memvalidasi fisik & menerima pengembalian Router TP-Link (1 unit) (1 unit) dari siswa ismiayu (Kode: PMJ-20261014-0006). Kondisi: Baik & Lengkap, Denda: Rp60.000 | Catatan QC: kabel lengkap | Stok alat laboratorium berhasil dipulihkan.', '2026-10-14 18:39:20'),
(323, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 18:39:26'),
(324, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-14 18:39:30'),
(325, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 18:46:03'),
(326, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-14 18:46:16'),
(327, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 18:47:20'),
(328, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-14 18:47:26'),
(329, 4, 'Pengajuan Peminjaman Alat', 'Siswa ismiayu mengajukan peminjaman 1 jenis alat dalam 1 transaksi: Switch (1 unit)', '2026-10-14 18:48:24'),
(330, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 18:48:36'),
(331, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-14 18:48:41'),
(332, 3, 'Menyetujui Peminjaman', 'Petugas menyetujui peminjaman #PMJ-20261014-0007 (ismiayu)', '2026-10-14 18:48:45'),
(333, 3, 'Menyerahkan Alat Lab', 'Alat untuk peminjaman #PMJ-20261014-0007 telah diserahkan fisik kepada siswa', '2026-10-14 18:48:49'),
(334, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 18:48:52'),
(335, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-14 18:48:55'),
(336, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 19:45:47'),
(337, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-14 19:45:51'),
(338, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 20:15:10'),
(339, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-10-14 20:15:14'),
(340, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 20:15:23'),
(341, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-14 20:15:29'),
(342, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 20:15:37'),
(343, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-10-14 20:15:44'),
(344, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-14 20:16:24'),
(345, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-15 07:18:42'),
(346, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-15 08:13:34'),
(347, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-15 08:13:37'),
(348, 4, 'Pengajuan Pengembalian Alat', 'Siswa ismiayu mengajukan pengembalian alat #PMJ-20261014-0007 (Switch (1 unit)) - Status diubah menjadi Menunggu Validasi Petugas', '2026-10-15 08:32:28'),
(349, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-15 10:03:37'),
(350, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-15 10:29:54'),
(351, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-15 10:37:22'),
(352, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-15 10:42:52'),
(353, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-10-15 10:42:58'),
(354, 2, 'Mengubah Data Alat Lab', 'ID: 15, Nama: Macbook', '2026-10-15 10:43:21'),
(355, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-15 10:43:27'),
(356, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-15 10:43:30'),
(357, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-15 10:52:54'),
(358, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-10-15 10:52:59'),
(359, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-10-15 17:03:27'),
(360, 2, 'Menghapus Pengembalian', 'Pengembalian ID #6', '2026-10-15 17:03:41'),
(361, 2, 'Menghapus Pengembalian', 'Pengembalian ID #5', '2026-10-15 17:03:44'),
(362, 2, 'Menghapus Pengembalian', 'Pengembalian ID #2', '2026-10-15 17:03:49'),
(363, 2, 'Menghapus Pengembalian', 'Pengembalian ID #4', '2026-10-15 17:03:51'),
(364, 2, 'Menghapus Pengembalian', 'Pengembalian ID #3', '2026-10-15 17:03:55'),
(365, 2, 'Menghapus Peminjaman', 'ID Peminjaman #2', '2026-10-15 17:04:20'),
(366, 2, 'Menghapus Peminjaman', 'ID Peminjaman #13', '2026-10-15 17:04:24'),
(367, 2, 'Menghapus Peminjaman', 'ID Peminjaman #12', '2026-10-15 17:04:27'),
(368, 2, 'Menghapus Peminjaman', 'ID Peminjaman #11', '2026-10-15 17:04:30'),
(369, 2, 'Menghapus Peminjaman', 'ID Peminjaman #10', '2026-10-15 17:04:33'),
(370, 2, 'Menghapus Peminjaman', 'ID Peminjaman #9', '2026-10-15 17:04:36'),
(371, 2, 'Menghapus Peminjaman', 'ID Peminjaman #8', '2026-10-15 17:04:39'),
(372, 2, 'Menghapus Peminjaman', 'ID Peminjaman #7', '2026-10-15 17:04:43'),
(373, 2, 'Menghapus Peminjaman', 'ID Peminjaman #6', '2026-10-15 17:04:46'),
(374, 2, 'Menghapus Peminjaman', 'ID Peminjaman #5', '2026-10-15 17:04:49'),
(375, 2, 'Menghapus Peminjaman', 'ID Peminjaman #4', '2026-10-15 17:04:52'),
(376, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-15 17:05:17'),
(377, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-15 17:05:23'),
(378, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-15 17:06:14'),
(379, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-10-15 17:06:17'),
(380, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-15 17:10:01'),
(381, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-10-15 17:22:33'),
(382, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-10-16 19:17:42'),
(383, 2, 'Mengubah Pengguna', 'User ID: 4, Username: ismiayn', '2026-10-16 19:34:01'),
(384, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-16 19:43:56'),
(385, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-16 19:44:00'),
(386, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-16 19:44:54'),
(387, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-16 19:45:01'),
(388, 4, 'Pengajuan Peminjaman Alat', 'Siswa ismiayu mengajukan peminjaman 1 jenis alat dalam 1 transaksi: PC Laboratorium (1 unit)', '2026-10-16 19:45:18'),
(389, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-16 19:45:23'),
(390, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-16 19:45:28'),
(391, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-16 20:23:04'),
(392, 8, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-16 20:23:13'),
(393, 8, 'Pengajuan Peminjaman Alat', 'Siswa anggota1 mengajukan peminjaman 1 jenis alat dalam 1 transaksi: Tripod Kamera (1 unit)', '2026-10-16 20:30:28'),
(394, 8, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-16 20:34:33'),
(395, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-16 20:34:36'),
(396, 3, 'Menyetujui Peminjaman', 'Petugas menyetujui peminjaman #PMJ-20261016-0002 (anggota1)', '2026-10-16 20:34:41'),
(397, 3, 'Menyerahkan Alat Lab', 'Alat untuk peminjaman #PMJ-20261016-0002 telah diserahkan fisik kepada siswa', '2026-10-16 20:34:45'),
(398, 3, 'Kirim Pengingat Pengembalian', 'Petugas mengirim notifikasi pengembalian alat #PMJ-20261016-0002 kepada siswa anggota1', '2026-10-16 20:35:06'),
(399, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-16 20:35:08'),
(400, 8, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-16 20:35:19'),
(401, 8, 'Pengajuan Pengembalian Alat', 'Siswa anggota1 mengajukan pengembalian alat #PMJ-20261016-0002 (Tripod Kamera (1 unit)) - Status diubah menjadi Menunggu Validasi Petugas', '2026-10-16 20:35:27'),
(402, 8, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-16 20:35:33'),
(403, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-16 20:35:41'),
(404, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-16 20:35:48'),
(405, 8, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-16 20:35:59'),
(406, 8, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-16 20:36:11'),
(407, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-16 20:36:16'),
(408, 3, 'Mencatat Pengembalian', 'Petugas Petugas mencatat pengembalian Tripod Kamera (1 unit) dari anggota1. Kondisi: Baik & Lengkap, Denda: Rp45.000. Stok telah dipulihkan.', '2026-10-16 20:36:39'),
(409, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-16 20:37:02'),
(410, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-16 20:37:12'),
(411, 4, 'Batalkan Peminjaman', 'Siswa membatalkan permohonan #PMJ-20261016-0001', '2026-10-16 20:37:20'),
(412, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-16 20:38:07'),
(413, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-16 20:39:58'),
(414, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-16 20:40:03'),
(415, 4, 'Pengajuan Peminjaman Alat', 'Siswa ismiayu mengajukan peminjaman 1 jenis alat dalam 1 transaksi: Router TP-Link (1 unit)', '2026-10-16 20:40:19'),
(416, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-16 20:40:23'),
(417, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-16 20:40:27'),
(418, 3, 'Menyetujui Peminjaman', 'Petugas menyetujui peminjaman #PMJ-20261016-0003 (ismiayu)', '2026-10-16 20:40:33'),
(419, 3, 'Menyerahkan Alat Lab', 'Alat untuk peminjaman #PMJ-20261016-0003 telah diserahkan fisik kepada siswa', '2026-10-16 20:40:37'),
(420, 3, 'Verifikasi Pengembalian Alat', 'Petugas Petugas telah memvalidasi fisik & menerima pengembalian Router TP-Link (1 unit) (1 unit) dari siswa ismiayu (Kode: PMJ-20261016-0003). Kondisi: Baik & Lengkap, Denda: Rp0 | Catatan QC: okee barang aman | Stok alat laboratorium berhasil dipulihkan.', '2026-10-16 20:41:14'),
(421, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-16 20:41:17'),
(422, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-16 20:41:20'),
(423, 4, 'Pengajuan Peminjaman Alat', 'Siswa ismiayu mengajukan peminjaman 2 jenis alat dalam 1 transaksi: Kabel LAN (1 unit), Keyboard USB (1 unit)', '2026-10-16 20:41:52'),
(424, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-16 20:41:54'),
(425, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-16 20:42:06'),
(426, 3, 'Menyetujui Peminjaman', 'Petugas menyetujui peminjaman #PMJ-20261016-0004 (ismiayu)', '2026-10-16 20:42:12'),
(427, 3, 'Menyerahkan Alat Lab', 'Alat untuk peminjaman #PMJ-20261016-0004 telah diserahkan fisik kepada siswa', '2026-10-16 20:42:15'),
(428, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-16 20:42:18'),
(429, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-16 20:42:21'),
(430, 4, 'Pengajuan Pengembalian Alat', 'Siswa ismiayu mengajukan pengembalian alat #PMJ-20261016-0004 (Kabel LAN (1 unit), Keyboard USB (1 unit)) - Status diubah menjadi Menunggu Validasi Petugas', '2026-10-16 20:44:53'),
(431, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-16 20:44:58'),
(432, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-17 18:40:18'),
(433, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-17 18:40:51'),
(434, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-10-17 18:40:57'),
(435, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-17 18:41:11'),
(436, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-17 18:41:14'),
(437, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-17 18:41:19'),
(438, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-17 18:41:24'),
(439, 3, 'Mencatat Pengembalian', 'Petugas Petugas mencatat pengembalian Kabel LAN (1 unit), Keyboard USB (1 unit) dari ismiayu. Kondisi: Baik & Lengkap, Denda: Rp0. Stok telah dipulihkan.', '2026-10-17 18:42:08'),
(440, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-17 18:42:12'),
(441, 4, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-17 18:42:20'),
(442, 4, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-17 18:58:11'),
(443, 12, 'Registrasi Akun Siswa', 'Pendaftaran siswa baru: peminjam2 (NIS: 009876552)', '2026-10-17 19:20:14'),
(444, 12, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-17 19:20:21'),
(445, 12, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-17 19:20:37'),
(446, 1, 'Login ke Sistem', 'Login berhasil sebagai admin', '2026-10-17 19:20:45'),
(447, 1, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-17 19:22:48'),
(448, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-10-17 19:22:53'),
(449, 2, 'Menambah Pengguna', 'User: Peminjam3, Role: peminjam', '2026-10-17 19:23:30'),
(450, 2, 'Mengubah Pengguna', 'User ID: 13, Username: Peminjam33', '2026-10-17 19:23:46'),
(451, 2, 'Menghapus Pengguna', 'Menghapus akun: Peminjam33', '2026-10-17 19:23:51'),
(452, 2, 'Menambahkan Alat Lab', 'Alat: Mikrotik (Kode: ALT-NET-008, Jumlah: 1)', '2026-10-17 19:25:10'),
(453, 2, 'Mengubah Data Alat Lab', 'ID: 16, Nama: Mikrotik', '2026-10-17 19:25:17'),
(454, 2, 'Menghapus Alat Lab', 'Menghapus alat: Mikrotik', '2026-10-17 19:25:21'),
(455, 2, 'Menghapus Alat Lab', 'Menghapus alat: Macbook', '2026-10-17 19:35:19'),
(456, 2, 'Menambah Pengguna', 'User: peminjam2, Role: peminjam', '2026-10-17 19:35:50'),
(457, 2, 'Mengubah Pengguna', 'User ID: 14, Username: peminjam2', '2026-10-17 19:35:54'),
(458, 2, 'Menghapus Pengguna', 'Menghapus akun: peminjam2', '2026-10-17 19:36:00'),
(459, 2, 'Mengubah Kategori', 'ID: 1, Nama: Komputer2', '2026-10-17 19:36:07'),
(460, 2, 'Menambah Kategori', 'Kategori: cici', '2026-10-17 19:36:12'),
(461, 2, 'Menghapus Kategori', 'Menghapus kategori: cici', '2026-10-17 19:36:16'),
(462, 2, 'Mengubah Kategori', 'ID: 1, Nama: Komputer', '2026-10-17 19:36:22'),
(463, 2, 'Menghapus Kategori', 'Menghapus kategori: Komputer', '2026-10-17 19:36:28'),
(464, 2, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-17 19:36:36'),
(465, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-17 19:36:42'),
(466, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-17 19:36:44'),
(467, 12, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-17 19:36:53'),
(468, 12, 'Pengajuan Peminjaman Alat', 'Siswa peminjam2 mengajukan peminjaman 2 jenis alat dalam 1 transaksi: Laptop Lenovo ThinkPad (1 unit), Router TP-Link (1 unit)', '2026-10-17 19:37:16'),
(469, 12, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-17 19:37:23'),
(470, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-17 19:37:33'),
(471, 3, 'Menyetujui Peminjaman', 'Petugas menyetujui peminjaman #PMJ-20261017-0001 (peminjam2)', '2026-10-17 19:37:39'),
(472, 3, 'Menyerahkan Alat Lab', 'Alat untuk peminjaman #PMJ-20261017-0001 telah diserahkan fisik kepada siswa', '2026-10-17 19:37:43'),
(473, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-17 19:37:48'),
(474, 12, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-17 19:37:54'),
(475, 12, 'Pengajuan Pengembalian Alat', 'Siswa peminjam2 mengajukan pengembalian alat #PMJ-20261017-0001 (Laptop Lenovo ThinkPad (1 unit), Router TP-Link (1 unit)) - Status diubah menjadi Menunggu Validasi Petugas', '2026-10-17 19:38:05'),
(476, 12, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-17 19:38:07'),
(477, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-17 19:38:13'),
(478, 3, 'Mencatat Pengembalian', 'Petugas Petugas mencatat pengembalian Laptop Lenovo ThinkPad (1 unit), Router TP-Link (1 unit) dari peminjam2. Kondisi: Baik & Lengkap, Denda: Rp40.000. Stok telah dipulihkan.', '2026-10-17 19:38:31'),
(479, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-17 19:38:38'),
(480, 12, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-17 19:38:41'),
(481, 12, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-17 19:41:18'),
(482, 3, 'Login ke Sistem', 'Login berhasil sebagai petugas', '2026-10-17 19:41:26'),
(483, 3, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-17 19:41:38'),
(484, 8, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-17 19:54:31'),
(485, 8, 'Logout dari Sistem', 'Sesi diakhiri oleh pengguna', '2026-10-17 19:54:34'),
(486, 15, 'Registrasi Akun Siswa', 'Pendaftaran siswa baru: peminjam44 (NIS: 0087622)', '2026-10-17 19:55:32'),
(487, 15, 'Login ke Sistem', 'Login berhasil sebagai peminjam', '2026-10-17 19:55:46'),
(488, 2, 'Login ke Sistem', 'Login berhasil sebagai admin pengelola', '2026-10-18 18:51:26'),
(489, 16, 'Registrasi Akun Siswa', 'Pendaftaran siswa baru: budi (NIS: 0099882)', '2026-10-19 09:31:51');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id_notifikasi` int(11) NOT NULL,
  `id_peminjam` int(11) NOT NULL,
  `id_peminjaman` int(11) NOT NULL,
  `judul` varchar(100) NOT NULL,
  `pesan` text NOT NULL,
  `status` enum('belum_dibaca','dibaca') NOT NULL DEFAULT 'belum_dibaca',
  `waktu` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `notifikasi`
--

INSERT INTO `notifikasi` (`id_notifikasi`, `id_peminjam`, `id_peminjaman`, `judul`, `pesan`, `status`, `waktu`) VALUES
(5, 4, 1, 'Pengembalian Diajukan (Menunggu Validasi)', 'Permohonan pengembalian alat Macbook (1 unit) (Kode: PMJ-20260925-0001) berhasil diajukan. Status: Menunggu Validasi. Silakan serahkan fisik alat ke meja Petugas Lab.', 'dibaca', '2026-10-14 09:50:22'),
(8, 4, 1, 'Pengembalian Alat Telah Divalidasi', 'Perangkat Macbook (1 unit) (Kode: PMJ-20260925-0001) telah diterima & diverifikasi fisik oleh Petugas. Kondisi: Baik & Lengkap, Denda: Rp0. Terima kasih telah menjaga fasilitas lab!', 'belum_dibaca', '2026-10-14 09:50:52'),
(12, 8, 15, 'Peringatan Pengembalian Alat Lab', 'Petugas Lab meminta Anda untuk segera mengembalikan perangkat Tripod Kamera (1 unit) (Kode: PMJ-20261016-0002) ke ruang laboratorium komputer.', 'dibaca', '2026-10-16 20:35:06'),
(13, 8, 15, 'Pengembalian Diajukan (Menunggu Validasi)', 'Permohonan pengembalian alat Tripod Kamera (1 unit) (Kode: PMJ-20261016-0002) berhasil diajukan. Status: Menunggu Validasi. Silakan serahkan fisik alat ke meja Petugas Lab.', 'dibaca', '2026-10-16 20:35:27'),
(14, 8, 15, 'Pengembalian Alat Selesai', 'Perangkat Tripod Kamera (1 unit) (Kode: PMJ-20261016-0002) telah diterima oleh Petugas. Kondisi: Baik & Lengkap, Denda: Rp45.000.', 'belum_dibaca', '2026-10-16 20:36:39'),
(15, 4, 16, 'Pengembalian Alat Telah Divalidasi', 'Perangkat Router TP-Link (1 unit) (Kode: PMJ-20261016-0003) telah diterima & diverifikasi fisik oleh Petugas. Kondisi: Baik & Lengkap, Denda: Rp0. Terima kasih telah menjaga fasilitas lab!', 'belum_dibaca', '2026-10-16 20:41:14'),
(16, 4, 17, 'Pengembalian Diajukan (Menunggu Validasi)', 'Permohonan pengembalian alat Kabel LAN (1 unit), Keyboard USB (1 unit) (Kode: PMJ-20261016-0004) berhasil diajukan. Status: Menunggu Validasi. Silakan serahkan fisik alat ke meja Petugas Lab.', 'dibaca', '2026-10-16 20:44:53'),
(17, 4, 17, 'Pengembalian Alat Selesai', 'Perangkat Kabel LAN (1 unit), Keyboard USB (1 unit) (Kode: PMJ-20261016-0004) telah diterima oleh Petugas. Kondisi: Baik & Lengkap, Denda: Rp0.', 'belum_dibaca', '2026-10-17 18:42:08'),
(18, 10, 18, 'Pengembalian Diajukan (Menunggu Validasi)', 'Permohonan pengembalian alat Laptop Lenovo ThinkPad (1 unit), Router TP-Link (1 unit) (Kode: PMJ-20261017-0001) berhasil diajukan. Status: Menunggu Validasi. Silakan serahkan fisik alat ke meja Petugas Lab.', 'dibaca', '2026-10-17 19:38:05'),
(19, 10, 18, 'Pengembalian Alat Selesai', 'Perangkat Laptop Lenovo ThinkPad (1 unit), Router TP-Link (1 unit) (Kode: PMJ-20261017-0001) telah diterima oleh Petugas. Kondisi: Baik & Lengkap, Denda: Rp40.000.', 'belum_dibaca', '2026-10-17 19:38:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjam`
--

CREATE TABLE `peminjam` (
  `id_peminjam` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `nis` varchar(30) NOT NULL,
  `kelas` varchar(20) NOT NULL,
  `jurusan` varchar(100) NOT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `foto_kartu_pelajar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peminjam`
--

INSERT INTO `peminjam` (`id_peminjam`, `id_user`, `nama`, `nis`, `kelas`, `jurusan`, `no_telp`, `foto_kartu_pelajar`) VALUES
(1, NULL, 'Ahmad Fauzan', '10001', 'XII RPL 1', 'Rekayasa Perangkat Lunak', '081234567801', 'default.jpg'),
(2, NULL, 'Dinda Permata', '10002', 'XII RPL 1', 'Rekayasa Perangkat Lunak', '081234567802', 'default.jpg'),
(3, NULL, 'Rian Saputra', '10003', 'XII RPL 2', 'Rekayasa Perangkat Lunak', '081234567803', 'default.jpg'),
(4, NULL, 'ismiayu', '863411', 'XII RPL 4', 'Rekayasa Perangkat Lunak', '181141249', ''),
(5, NULL, 'luthfi diandi Rusmana', '7351878157', 'X RPL 2', 'Rekayasa Perangkat Lunak', '013828281', ''),
(6, 6, 'diandi', '009706740', 'XII RPL 3', 'Rekayasa Perangkat Lunak', '08773171', 'jaminan_1790413120_840.jpeg'),
(7, 7, 'luthfid', '8319272', 'XII RPL 3', 'Rekayasa Perangkat Lunak', '0836261', 'jaminan_1790494646_193.png'),
(8, 8, 'anggota1', '00987616161', 'X RPL 4', 'Rekayasa Perangkat Lunak', '088272551', ''),
(9, 11, 'shodik', '00098877655', 'X RPL 4', 'Rekayasa Perangkat Lunak', '09217837615', ''),
(10, 12, 'peminjam2', '009876552', 'XII RPL 3', 'Rekayasa Perangkat Lunak', '08975642', 'kartu_1792239614_437.jpeg'),
(11, 15, 'peminjam44', '0087622', 'X RPL 3', 'Rekayasa Perangkat Lunak', '089766221', 'kartu_1792241732_211.jpeg'),
(12, 16, 'budi', '0099882', 'X RPL 3', 'Rekayasa Perangkat Lunak', '089725412', 'kartu_1792377111_432.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int(11) NOT NULL,
  `kode_peminjaman` varchar(50) NOT NULL,
  `id_peminjam` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `waktu_pinjam` time DEFAULT NULL,
  `waktu_rencana_kembali` time DEFAULT NULL,
  `waktu_kembali` time DEFAULT NULL,
  `jenis_peminjaman` varchar(100) NOT NULL DEFAULT 'Praktek Lab',
  `keperluan` text DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'Menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peminjaman`
--

INSERT INTO `peminjaman` (`id_peminjaman`, `kode_peminjaman`, `id_peminjam`, `id_user`, `waktu_pinjam`, `waktu_rencana_kembali`, `waktu_kembali`, `jenis_peminjaman`, `keperluan`, `status`) VALUES
(1, 'PMJ-20260925-0001', 4, 3, '15:02:00', '17:02:00', '09:50:00', 'Lainnya', 'untuk mengerjakan tugas akhir', 'dikembalikan'),
(3, 'PMJ-20260930-0001', 4, 3, '06:30:00', '07:10:00', '05:36:00', 'Kebutuhan Rapat', 'ytess', 'dikembalikan'),
(14, 'PMJ-20261016-0001', 4, NULL, '06:30:00', '07:10:00', NULL, 'Kebutuhan untuk Pembelajaran di Kelas', 'testing', 'dibatalkan'),
(15, 'PMJ-20261016-0002', 8, 3, '06:30:00', '07:10:00', '15:36:00', 'Kebutuhan untuk Pembelajaran di Kelas', '11', 'dikembalikan'),
(16, 'PMJ-20261016-0003', 4, 3, '07:10:00', '08:30:00', '08:30:00', 'Kebutuhan untuk Pembelajaran di Kelas', 'tess', 'dikembalikan'),
(17, 'PMJ-20261016-0004', 4, 3, '06:30:00', '07:10:00', '07:10:00', 'Kebutuhan untuk Pembelajaran di Kelas', 'testing', 'dikembalikan'),
(18, 'PMJ-20261017-0001', 10, 3, '06:30:00', '07:10:00', '14:38:00', 'Kebutuhan untuk Pembelajaran di Kelas', 'testing', 'dikembalikan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengembalian`
--

CREATE TABLE `pengembalian` (
  `id` int(11) NOT NULL,
  `id_peminjaman` int(11) NOT NULL,
  `waktu_kembali` time DEFAULT NULL,
  `kondisi_kembali` varchar(100) DEFAULT NULL,
  `denda` decimal(12,2) NOT NULL DEFAULT 0.00,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengembalian`
--

INSERT INTO `pengembalian` (`id`, `id_peminjaman`, `waktu_kembali`, `kondisi_kembali`, `denda`, `id_user`) VALUES
(1, 3, '05:36:00', 'Baik & Lengkap', 0.00, 3),
(7, 15, '15:36:00', 'Baik & Lengkap', 45000.00, 3),
(8, 16, '08:30:00', 'Baik & Lengkap', 0.00, 3),
(9, 17, '07:10:00', 'Baik & Lengkap', 0.00, 3),
(10, 18, '14:38:00', 'Baik & Lengkap', 40000.00, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','admin pengelola','petugas','peminjam') NOT NULL DEFAULT 'peminjam'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `nama`, `username`, `password`, `role`) VALUES
(1, 'Administrator', 'Kejur', '5e805bdae0b8155536c9edb9ebd66ec7', 'admin'),
(2, 'Administrator\r\n\r\n', 'pengelola', '19539a09b6f6bd269cb86d66127da3ee', 'admin pengelola'),
(3, 'Petugas', 'petugas', 'petugas123', 'petugas'),
(4, 'ismiayu', 'ismiayn', '$2y$10$FD0qIxJPFR2yTZj8/ibnv.75NVE4QFLwNoCmKXxPT0UVzAT/tE9UG', 'peminjam'),
(5, 'luthfi diandi Rusmana', 'luthfid', '$2y$10$2tFslBWZKRFKcOLfj544keruln/J28GIv0jS/xUwpEMrEMeTiw97W', 'peminjam'),
(6, 'diandi', 'diandir', '$2y$10$qc9eDgXHXYjc0zR0L82NQ.lBaV57//4SOxMFNnhU83xVHBzUnLl6e', 'peminjam'),
(7, 'luthfid', 'luthfid12', '$2y$10$xVV/oCFTcJ3Sje5GyVd/j.NmWqzm4QkrDe8HODSh4NeuY0hmCn0Hu', 'peminjam'),
(8, 'anggota1', 'anggota11', '$2y$10$RmpnulrVWmlMGzrgU.RN.OxPmW8sXHy6bfxgr3Eo62AFgQ/fNYMui', 'peminjam'),
(10, 'udin', 'didin', '$2y$10$ZkNZQsop6b8e/aMwRKde6u6nWx0mJUOgcdzjFP2x0G08zEdzRBL8i', 'peminjam'),
(11, 'shodik', 'shodik', '$2y$10$hDhVqPpk0gybgjJvHQDd1uS7IcV3j6eTMYUDW6J2wpzuzgKGEfsZK', 'peminjam'),
(12, 'peminjam2', 'peminjammm', '$2y$10$NlaHBT3GloyS9YBvsfCwVOs58Nw9roM/QDuLBi1NlWih580lhcUbi', 'peminjam'),
(15, 'peminjam44', 'Peminjam4', '$2y$10$katiRDzffSf6pOkBhES4j.zOtUMhFHI4c7ZLcDV6llOmHz4WVc0rC', 'peminjam'),
(16, 'budi', 'budi01', '$2y$10$1Oa5.ZxSTFQDVn0HKYatCeg8VF6g7nmbu2eMimR6hbd9wW7XOR0CS', 'peminjam');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `alat`
--
ALTER TABLE `alat`
  ADD PRIMARY KEY (`id_alat`),
  ADD UNIQUE KEY `kode` (`kode`),
  ADD KEY `fk_alat_kategori` (`id_kategori`);

--
-- Indeks untuk tabel `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD PRIMARY KEY (`id_detail_peminjaman`),
  ADD KEY `fk_detail_peminjaman` (`id_peminjaman`),
  ADD KEY `fk_detail_alat` (`id_alat`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id_log_aktivitas`),
  ADD KEY `fk_log_user` (`id_user`);

--
-- Indeks untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id_notifikasi`),
  ADD KEY `fk_notif_peminjam` (`id_peminjam`),
  ADD KEY `fk_notif_peminjaman` (`id_peminjaman`);

--
-- Indeks untuk tabel `peminjam`
--
ALTER TABLE `peminjam`
  ADD PRIMARY KEY (`id_peminjam`),
  ADD UNIQUE KEY `nis` (`nis`);

--
-- Indeks untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD UNIQUE KEY `kode_peminjaman` (`kode_peminjaman`),
  ADD KEY `fk_peminjaman_peminjam` (`id_peminjam`),
  ADD KEY `fk_peminjaman_user` (`id_user`);

--
-- Indeks untuk tabel `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_pengembalian_peminjaman` (`id_peminjaman`),
  ADD KEY `fk_pengembalian_user` (`id_user`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `alat`
--
ALTER TABLE `alat`
  MODIFY `id_alat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  MODIFY `id_detail_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log_aktivitas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=490;

--
-- AUTO_INCREMENT untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id_notifikasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `peminjam`
--
ALTER TABLE `peminjam`
  MODIFY `id_peminjam` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `pengembalian`
--
ALTER TABLE `pengembalian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `alat`
--
ALTER TABLE `alat`
  ADD CONSTRAINT `fk_alat_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD CONSTRAINT `fk_detail_alat` FOREIGN KEY (`id_alat`) REFERENCES `alat` (`id_alat`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_peminjaman` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id_peminjaman`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `fk_notifikasi_peminjam` FOREIGN KEY (`id_peminjam`) REFERENCES `peminjam` (`id_peminjam`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_notifikasi_peminjaman` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id_peminjaman`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `fk_peminjaman_peminjam` FOREIGN KEY (`id_peminjam`) REFERENCES `peminjam` (`id_peminjam`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_peminjaman_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD CONSTRAINT `fk_pengembalian_peminjaman` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id_peminjaman`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pengembalian_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 07 Okt 2025 pada 17.46
-- Versi server: 8.0.30
-- Versi PHP: 8.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_produksi_report`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `input_harian`
--

CREATE TABLE `input_harian` (
  `id` int NOT NULL,
  `line_id` int DEFAULT NULL,
  `tanggal` date NOT NULL,
  `batch_count` decimal(10,2) DEFAULT NULL,
  `productivity` decimal(10,2) DEFAULT NULL,
  `production_speed` decimal(10,2) DEFAULT NULL,
  `batch_weight` decimal(10,2) DEFAULT NULL,
  `operation_factor` decimal(5,2) DEFAULT NULL,
  `cycle_time` decimal(10,2) DEFAULT NULL,
  `grade_change_sequence` decimal(10,2) DEFAULT NULL,
  `grade_change_time` decimal(10,2) DEFAULT NULL,
  `feed_raw_material` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `input_harian`
--

INSERT INTO `input_harian` (`id`, `line_id`, `tanggal`, `batch_count`, `productivity`, `production_speed`, `batch_weight`, `operation_factor`, `cycle_time`, `grade_change_sequence`, `grade_change_time`, `feed_raw_material`, `created_at`, `updated_at`) VALUES
(9, 1, '2025-10-07', 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, '2025-10-07 09:50:27', NULL),
(10, 1, '2025-01-06', 1.00, 1.00, 1.00, 12.00, 12.00, 12.00, 12.00, 12.00, 22.00, '2025-10-07 09:50:39', NULL),
(11, 1, '2025-10-05', 1.00, 3.00, 3.00, 3.00, 3.00, 3.00, 3.00, 3.00, 3.00, '2025-10-07 09:50:49', NULL),
(12, 1, '2025-10-04', 1.00, 3.00, 3.00, 3.00, 3.00, 3.00, 3.00, 3.00, 3.00, '2025-10-07 09:51:29', NULL),
(13, 1, '2025-10-03', 9.00, 9.00, 9.00, 9.00, 9.00, 9.00, 9.00, 9.00, 9.00, '2025-10-07 09:51:38', NULL),
(14, 1, '2025-10-02', 8.00, 8.00, 8.00, 8.00, 8.00, 8.00, 8.00, 8.00, 8.00, '2025-10-07 09:51:46', NULL),
(15, 1, '2025-10-01', 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, '2025-10-07 09:52:30', NULL),
(16, 1, '2025-09-30', 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, '2025-10-07 09:59:48', NULL),
(17, 1, '2025-09-28', 2.00, 3.00, 3.00, 4.00, 4.00, 4.00, 4.00, 4.00, 5.00, '2025-10-07 17:41:39', NULL),
(19, 1, '2025-09-28', 2.00, 3.00, 3.00, 4.00, 4.00, 4.00, 4.00, 4.00, 5.00, '2025-10-07 17:41:56', NULL),
(20, 1, '2025-09-27', 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, '2025-10-07 17:41:56', NULL),
(21, 1, '2025-09-01', 3.00, 4.00, 5.00, 3.00, 4.00, 5.00, 2.00, 3.00, 4.00, '2025-10-07 17:43:36', NULL),
(22, 1, '2025-09-02', 2.00, 3.00, 3.00, 4.00, 4.00, 3.00, 3.00, 4.00, 5.00, '2025-10-07 17:43:36', NULL),
(23, 1, '2025-09-03', 4.00, 5.00, 4.00, 3.00, 5.00, 4.00, 4.00, 3.00, 4.00, '2025-10-07 17:43:36', NULL),
(24, 1, '2025-09-04', 3.00, 3.00, 4.00, 4.00, 3.00, 4.00, 3.00, 4.00, 4.00, '2025-10-07 17:43:36', NULL),
(25, 1, '2025-09-05', 5.00, 4.00, 3.00, 5.00, 5.00, 3.00, 3.00, 4.00, 5.00, '2025-10-07 17:43:36', NULL),
(26, 1, '2025-09-06', 4.00, 3.00, 4.00, 4.00, 3.00, 4.00, 4.00, 3.00, 4.00, '2025-10-07 17:43:36', NULL),
(27, 1, '2025-09-07', 3.00, 5.00, 4.00, 5.00, 4.00, 5.00, 3.00, 4.00, 5.00, '2025-10-07 17:43:36', NULL),
(28, 1, '2025-09-08', 2.00, 3.00, 4.00, 3.00, 3.00, 4.00, 3.00, 3.00, 4.00, '2025-10-07 17:43:36', NULL),
(29, 1, '2025-09-09', 5.00, 4.00, 5.00, 4.00, 4.00, 5.00, 4.00, 5.00, 4.00, '2025-10-07 17:43:36', NULL),
(30, 1, '2025-09-10', 4.00, 3.00, 3.00, 3.00, 3.00, 4.00, 4.00, 3.00, 3.00, '2025-10-07 17:43:36', NULL),
(31, 1, '2025-09-11', 3.00, 4.00, 5.00, 4.00, 5.00, 3.00, 4.00, 4.00, 5.00, '2025-10-07 17:43:36', NULL),
(32, 1, '2025-09-12', 2.00, 2.00, 3.00, 3.00, 3.00, 2.00, 2.00, 3.00, 3.00, '2025-10-07 17:43:36', NULL),
(33, 1, '2025-09-13', 5.00, 5.00, 5.00, 5.00, 4.00, 5.00, 5.00, 4.00, 5.00, '2025-10-07 17:43:36', NULL),
(34, 1, '2025-09-14', 4.00, 4.00, 4.00, 4.00, 4.00, 4.00, 4.00, 4.00, 4.00, '2025-10-07 17:43:36', NULL),
(35, 1, '2025-09-15', 3.00, 3.00, 4.00, 4.00, 3.00, 4.00, 3.00, 3.00, 4.00, '2025-10-07 17:43:36', NULL),
(36, 1, '2025-09-16', 2.00, 4.00, 3.00, 4.00, 4.00, 3.00, 3.00, 3.00, 3.00, '2025-10-07 17:43:36', NULL),
(37, 1, '2025-09-17', 5.00, 5.00, 4.00, 5.00, 5.00, 4.00, 4.00, 4.00, 5.00, '2025-10-07 17:43:36', NULL),
(38, 1, '2025-09-18', 4.00, 4.00, 3.00, 4.00, 3.00, 4.00, 4.00, 3.00, 3.00, '2025-10-07 17:43:36', NULL),
(39, 1, '2025-09-19', 3.00, 3.00, 3.00, 3.00, 4.00, 3.00, 3.00, 4.00, 3.00, '2025-10-07 17:43:36', NULL),
(40, 1, '2025-09-20', 5.00, 5.00, 4.00, 5.00, 4.00, 5.00, 5.00, 4.00, 5.00, '2025-10-07 17:43:36', NULL),
(41, 1, '2025-09-21', 3.00, 4.00, 3.00, 4.00, 3.00, 3.00, 3.00, 4.00, 4.00, '2025-10-07 17:43:36', NULL),
(42, 1, '2025-09-22', 4.00, 5.00, 4.00, 4.00, 5.00, 5.00, 4.00, 5.00, 5.00, '2025-10-07 17:43:36', NULL),
(43, 1, '2025-09-23', 2.00, 3.00, 3.00, 3.00, 3.00, 3.00, 3.00, 3.00, 3.00, '2025-10-07 17:43:36', NULL),
(44, 1, '2025-09-24', 3.00, 4.00, 4.00, 4.00, 3.00, 4.00, 4.00, 4.00, 4.00, '2025-10-07 17:43:36', NULL),
(45, 1, '2025-09-25', 4.00, 3.00, 3.00, 3.00, 3.00, 3.00, 4.00, 4.00, 3.00, '2025-10-07 17:43:36', NULL),
(46, 1, '2025-09-26', 5.00, 4.00, 5.00, 5.00, 4.00, 5.00, 5.00, 4.00, 5.00, '2025-10-07 17:43:36', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `line_produksi`
--

CREATE TABLE `line_produksi` (
  `id` int NOT NULL,
  `kode_line` varchar(10) NOT NULL,
  `nama_line` varchar(100) DEFAULT NULL,
  `dibuat_pada` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `line_produksi`
--

INSERT INTO `line_produksi` (`id`, `kode_line`, `nama_line`, `dibuat_pada`) VALUES
(1, '01', 'Line A', '2025-09-29 07:29:57'),
(2, '02', 'LINE B', '2025-10-01 04:53:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `target`
--

CREATE TABLE `target` (
  `id` int NOT NULL,
  `tahun_target` int NOT NULL,
  `line_id` int DEFAULT NULL,
  `target_batch_count` decimal(10,2) DEFAULT NULL,
  `target_productivity` decimal(10,2) DEFAULT NULL,
  `target_production_speed` decimal(10,2) DEFAULT NULL,
  `target_batch_weight` decimal(10,2) DEFAULT NULL,
  `target_operation_factor` decimal(5,2) DEFAULT NULL,
  `target_cycle_time` decimal(10,2) DEFAULT NULL,
  `target_grade_change_sequence` decimal(10,2) DEFAULT NULL,
  `target_grade_change_time` decimal(10,2) DEFAULT NULL,
  `target_feed_raw_material` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `target`
--

INSERT INTO `target` (`id`, `tahun_target`, `line_id`, `target_batch_count`, `target_productivity`, `target_production_speed`, `target_batch_weight`, `target_operation_factor`, `target_cycle_time`, `target_grade_change_sequence`, `target_grade_change_time`, `target_feed_raw_material`, `created_at`, `update_at`) VALUES
(39, 2025, 1, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, '2025-10-07 09:50:16', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `input_harian`
--
ALTER TABLE `input_harian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `line_id` (`line_id`),
  ADD KEY `idx_tanggal` (`tanggal`),
  ADD KEY `idx_tanggal_line` (`tanggal`,`line_id`);

--
-- Indeks untuk tabel `line_produksi`
--
ALTER TABLE `line_produksi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_line` (`kode_line`);

--
-- Indeks untuk tabel `target`
--
ALTER TABLE `target`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_tahun_line` (`tahun_target`,`line_id`),
  ADD KEY `line_id` (`line_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `input_harian`
--
ALTER TABLE `input_harian`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT untuk tabel `line_produksi`
--
ALTER TABLE `line_produksi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `target`
--
ALTER TABLE `target`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `input_harian`
--
ALTER TABLE `input_harian`
  ADD CONSTRAINT `input_harian_ibfk_2` FOREIGN KEY (`line_id`) REFERENCES `line_produksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `target`
--
ALTER TABLE `target`
  ADD CONSTRAINT `target_ibfk_2` FOREIGN KEY (`line_id`) REFERENCES `line_produksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

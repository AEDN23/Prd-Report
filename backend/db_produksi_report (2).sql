-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 13, 2025 at 07:48 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.8

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
-- Table structure for table `info`
--

CREATE TABLE `info` (
  `id` int NOT NULL,
  `judul` varchar(50) NOT NULL,
  `isi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `deskripsi` varchar(50) NOT NULL,
  `file` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `info`
--

INSERT INTO `info` (`id`, `judul`, `isi`, `deskripsi`, `file`, `created_at`, `updated_at`) VALUES
(18, 'informasi test', 'jasdo', 'jasd', NULL, '2025-10-13 14:12:29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `input_harian`
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
-- Dumping data for table `input_harian`
--

INSERT INTO `input_harian` (`id`, `line_id`, `tanggal`, `batch_count`, `productivity`, `production_speed`, `batch_weight`, `operation_factor`, `cycle_time`, `grade_change_sequence`, `grade_change_time`, `feed_raw_material`, `created_at`, `updated_at`) VALUES
(117, 1, '2025-10-01', 9.00, 9.00, 9.00, 9.00, 9.00, 9.00, 9.00, 9.00, 9.00, '2025-10-13 07:04:30', NULL),
(118, 1, '2025-10-02', 2.00, 2.00, 2.00, 2.00, 2.00, 2.00, 2.00, 2.00, 2.00, '2025-10-13 07:04:53', NULL),
(119, 1, '2025-10-13', 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, '2025-10-13 07:06:17', NULL),
(120, 2, '2025-10-13', 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, '2025-10-13 07:09:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `line_produksi`
--

CREATE TABLE `line_produksi` (
  `id` int NOT NULL,
  `kode_line` varchar(10) NOT NULL,
  `nama_line` varchar(100) DEFAULT NULL,
  `dibuat_pada` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `line_produksi`
--

INSERT INTO `line_produksi` (`id`, `kode_line`, `nama_line`, `dibuat_pada`) VALUES
(1, '01', 'Line A', '2025-09-29 07:29:57'),
(2, '02', 'LINE B', '2025-10-01 04:53:53');

-- --------------------------------------------------------

--
-- Table structure for table `target`
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
-- Dumping data for table `target`
--

INSERT INTO `target` (`id`, `tahun_target`, `line_id`, `target_batch_count`, `target_productivity`, `target_production_speed`, `target_batch_weight`, `target_operation_factor`, `target_cycle_time`, `target_grade_change_sequence`, `target_grade_change_time`, `target_feed_raw_material`, `created_at`, `update_at`) VALUES
(45, 2025, 1, 100.00, 100.00, 100.00, 100.00, 100.00, 100.00, 100.00, 100.00, 100.00, '2025-10-13 06:55:54', NULL),
(46, 2025, 2, 100.00, 100.00, 100.00, 100.00, 100.00, 100.00, 100.00, 100.00, 100.00, '2025-10-13 06:56:33', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `info`
--
ALTER TABLE `info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `input_harian`
--
ALTER TABLE `input_harian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_tanggal_line` (`tanggal`,`line_id`),
  ADD KEY `line_id` (`line_id`),
  ADD KEY `idx_tanggal` (`tanggal`),
  ADD KEY `idx_tanggal_line` (`tanggal`,`line_id`);

--
-- Indexes for table `line_produksi`
--
ALTER TABLE `line_produksi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_line` (`kode_line`);

--
-- Indexes for table `target`
--
ALTER TABLE `target`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_tahun_line` (`tahun_target`,`line_id`),
  ADD KEY `line_id` (`line_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `info`
--
ALTER TABLE `info`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `input_harian`
--
ALTER TABLE `input_harian`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `line_produksi`
--
ALTER TABLE `line_produksi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `target`
--
ALTER TABLE `target`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `input_harian`
--
ALTER TABLE `input_harian`
  ADD CONSTRAINT `input_harian_ibfk_2` FOREIGN KEY (`line_id`) REFERENCES `line_produksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `target`
--
ALTER TABLE `target`
  ADD CONSTRAINT `target_ibfk_2` FOREIGN KEY (`line_id`) REFERENCES `line_produksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

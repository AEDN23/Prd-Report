-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 17, 2025 at 01:43 AM
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
(18, 'informasi test', 'jasdo', 'jasd', NULL, '2025-10-13 14:12:29', NULL),
(19, 'INFORMASI SAFETY', 'Keselamatan dan Kesehatan Kerja (K3) adalah segala upaya untuk menjamin dan melindungi keselamatan serta kesehatan tenaga kerja melalui pencegahan kecelakaan kerja dan penyakit akibat kerja. Penerapan K3 meliputi berbagai kegiatan seperti pencegahan bahaya, penyediaan alat pelindung diri (APD), pelatihan, hingga penanganan keadaan darurat. Tujuan utamanya adalah menciptakan lingkungan kerja yang aman, sehat, dan produktif bagi semua orang yang terlibat. \r\nTujuan K3\r\nMencegah kecelakaan kerja dan penyakit akibat kerja.\r\nMelindungi keselamatan, kesehatan, dan kesejahteraan pekerja di lingkungan kerja.\r\nMeningkatkan produktivitas dan kinerja karyawan dengan menciptakan lingkungan yang aman dan termotivasi.\r\nMemastikan kepatuhan terhadap peraturan dan standar yang berlaku. \r\nImplementasi K3\r\nPencegahan Bahaya: Mengidentifikasi dan mengendalikan potensi bahaya, seperti kebisingan, debu, uap, dan radiasi. \r\nAlat Pelindung Diri (APD): Menyediakan dan memastikan penggunaan APD yang sesuai untuk melindungi pekerja dari risiko pekerjaan. \r\nPelatihan K3: Memberikan pelatihan keselamatan kerja kepada semua karyawan. \r\nManajemen Keadaan Darurat: Mempersiapkan prosedur dan jalur evakuasi yang jelas untuk situasi darurat seperti kebakaran atau gempa bumi. \r\nPerawatan dan Kesehatan: Menyediakan fasilitas P3K dan memastikan kondisi kebersihan, kesehatan, dan ketertiban lingkungan kerja terjaga dengan baik. \r\nTeknik Pengendalian: Menggunakan metode pengendalian teknik (misalnya ventilasi, isolasi) dan administratif (misalnya prosedur kerja aman, pengawasan). \r\nErgonomi: Menyesuaikan lingkungan kerja, peralatan, dan cara kerja agar sesuai dengan pekerja. \r\nContoh penerapan K3\r\nEvakuasi saat Darurat: Mengenali jalur evakuasi, tidak panik saat alarm berbunyi, dan tidak menggunakan lift saat terjadi kebakaran. \r\nPenggunaan APD: Memakai helm, kacamata pengaman, dan sepatu keselamatan sesuai dengan jenis pekerjaan. \r\nPenanganan Bahan Kimia: Menyimpan bahan kimia dengan benar dan menggunakan ventilasi yang memadai untuk menghindari keracunan. ', 'Keselamatan dan Kesehatan kerja', NULL, '2025-10-16 11:54:06', NULL),
(20, 'Training Produksi', 'Training produksi\" dapat merujuk pada berbagai jenis program pelatihan di industri manufaktur, seperti Pelatihan Produksi Berbasis Produksi (Production Based Training) yang menekankan praktik langsung untuk membangun kompetensi teknis dan kerja tim. Selain itu, bisa juga berarti Training Manajemen Produksi yang bertujuan meningkatkan efisiensi, kualitas, dan profitabilitas melalui pemahaman konsep seperti MRP II dan Lean Manufacturing. Jenis pelatihan lainnya termasuk pelatihan untuk Operator Produksi sertifikasi BNSP dan Pelatihan Produk untuk karyawan guna memastikan mereka memahami dan dapat menggunakan produk secara efektif\r\n\r\nJenis-jenis training produksi:\r\n- Pelatihan Produksi Berbasis Produksi (Production Based Training):\r\n- Fokus pada praktik langsung menggunakan bahan dan alat kerja dalam lingkungan yang disimulasikan. \r\n- Tujuan: Menyiapkan peserta agar memiliki kompetensi teknis dan kemampuan kerja sama sesuai tuntutan pekerjaan. \r\n- Tahapannya meliputi: perencanaan produk, pelaksanaan produksi, evaluasi produk, dan pengembangan rencana pemasaran. \r\n\r\nTraining Manajemen Produksi dan Operasi:\r\n- Fokus pada konsep dan strategi manajerial produksi modern seperti Manufacturing Resource Planning (MRP II), Total Quality Management (TQM), Just-in-Time (JIT), dan ISO 9000. \r\n- Tujuan: Meningkatkan efisiensi, mengurangi biaya produksi, mengoptimalkan sumber daya, dan meningkatkan daya saing perusahaan. \r\n- Dapat menggunakan metode studi kasus untuk analisis dan pemecahan masalah.', 'Training untuk produksi', 'training_produksi_690af43a7a92d.png', '2025-10-16 11:58:29', '2025-11-05 13:52:42'),
(22, 'test', 'test', 'test', 'helo_690aed88c3648.jpg', '2025-11-05 13:24:08', '2025-11-05 13:24:40');

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
(121, 1, '2025-09-01', 239.00, 10.80, 31.50, 133.00, 81.70, 4.20, 44.00, 3.00, 31715.50, '2025-10-13 21:11:59', NULL),
(122, 1, '2025-09-02', 212.00, 9.50, 29.00, 135.00, 74.90, 4.60, 33.00, 3.00, 28512.09, '2025-10-13 21:19:04', NULL),
(123, 1, '2025-09-03', 225.00, 10.30, 31.30, 138.00, 78.60, 4.40, 55.00, 3.00, 30910.29, '2025-10-13 21:24:46', NULL),
(124, 2, '2025-09-01', 146.00, 8.70, 22.70, 132.00, 87.00, 5.80, 26.00, 1.80, 19153.80, '2025-10-13 21:30:16', NULL),
(125, 2, '2025-09-02', 158.00, 8.80, 22.58, 108.00, 86.30, 4.80, 19.00, 1.60, 16955.98, '2025-10-13 21:32:11', NULL),
(126, 2, '2025-09-03', 159.00, 9.10, 22.59, 100.00, 89.10, 4.40, 13.00, 1.50, 15903.92, '2025-10-13 21:35:09', NULL),
(127, 1, '2025-09-04', 242.00, 10.80, 31.50, 134.00, 81.60, 4.30, 42.00, 3.00, 32430.75, '2025-10-13 21:35:09', NULL),
(128, 1, '2025-09-05', 78.00, 11.40, 34.20, 78.00, 78.90, 4.30, 8.00, 3.00, 11379.56, '2025-10-13 21:35:09', NULL),
(129, 1, '2025-09-08', 216.00, 9.50, 31.10, 132.00, 70.70, 4.20, 54.00, 2.90, 28466.42, '2025-10-14 21:35:09', NULL),
(130, 1, '2025-09-09', 256.00, 11.80, 32.50, 139.00, 87.30, 4.30, 36.00, 2.90, 35537.29, '2025-10-15 21:35:09', NULL),
(131, 1, '2025-09-10', 206.00, 9.20, 29.40, 134.00, 74.80, 4.50, 47.00, 3.00, 27567.32, '2025-10-16 21:35:09', NULL),
(132, 1, '2025-09-11', 204.00, 9.40, 32.80, 139.00, 68.90, 4.20, 38.00, 3.00, 28339.23, '2025-10-17 21:35:09', NULL),
(133, 1, '2025-09-12', 193.00, 10.20, 29.50, 159.00, 84.50, 5.40, 15.00, 3.00, 30531.47, '2025-10-18 21:35:09', NULL),
(134, 1, '2025-09-13', 80.00, 10.20, 30.40, 128.00, 70.40, 4.20, 22.00, 3.00, 10246.56, '2025-10-19 21:35:09', NULL),
(135, 1, '2025-09-15', 245.00, 11.10, 32.51, 136.00, 78.60, 4.20, 43.00, 3.00, 33294.74, '2025-10-19 21:35:09', NULL),
(136, 1, '2025-09-16', 247.00, 11.04, 32.28, 134.00, 80.90, 4.20, 55.00, 3.00, 33121.23, '2025-10-19 21:35:09', NULL),
(137, 1, '2025-09-17', 260.00, 12.44, 34.35, 144.00, 86.70, 4.20, 38.00, 3.00, 37334.82, '2025-10-19 21:35:09', NULL),
(138, 1, '2025-09-18', 242.00, 11.36, 32.47, 141.00, 82.70, 4.30, 37.00, 2.90, 34088.43, '2025-10-19 21:35:09', NULL),
(139, 1, '2025-09-19', 238.00, 11.36, 33.11, 143.00, 81.40, 4.30, 36.00, 3.00, 34069.39, '2025-10-19 21:35:09', NULL),
(140, 1, '2025-09-22', 244.00, 11.40, 33.00, 140.00, 79.00, 4.20, 45.00, 3.00, 34131.68, '2025-10-20 21:35:09', NULL),
(141, 1, '2025-09-23', 214.00, 10.30, 34.70, 144.00, 72.60, 4.10, 30.00, 2.90, 30776.71, '2025-10-21 21:35:09', NULL),
(142, 1, '2025-09-24', 191.00, 9.90, 27.20, 156.00, 85.40, 5.70, 22.00, 3.00, 29572.10, '2025-10-22 21:35:09', NULL),
(143, 1, '2025-09-25', 222.00, 10.20, 30.50, 138.00, 79.00, 4.50, 52.00, 3.00, 30464.99, '2025-10-23 21:35:09', NULL),
(144, 1, '2025-09-26', 219.00, 9.40, 29.00, 130.00, 76.60, 4.50, 57.00, 3.00, 28288.88, '2025-10-24 21:35:09', NULL),
(145, 1, '2025-09-29', 235.00, 10.70, 31.60, 138.00, 78.50, 4.30, 42.00, 3.00, 32242.58, '2025-09-03 21:35:09', NULL),
(146, 1, '2025-09-30', 232.00, 10.60, 31.70, 138.00, 79.90, 4.30, 43.00, 2.90, 31870.47, '2025-09-04 21:35:09', NULL),
(147, 2, '2025-09-04', 96.00, 7.70, 22.10, 133.00, 77.40, 6.00, 26.00, 2.00, 12732.94, '2025-09-04 21:35:09', NULL),
(148, 2, '2025-09-05', 118.00, 8.70, 23.30, 134.00, 83.90, 5.70, 24.00, 1.40, 15711.26, '2025-09-04 21:35:09', NULL),
(149, 2, '2025-09-08', 121.00, 8.39, 22.34, 134.00, 83.49, 6.00, 27.00, 1.40, 16148.71, '2025-09-05 21:35:09', NULL),
(150, 2, '2025-09-09', 90.00, 8.59, 23.08, 129.00, 82.73, 5.60, 15.00, 1.30, 11609.28, '2025-09-05 21:35:09', NULL),
(151, 2, '2025-09-10', 119.00, 8.32, 21.26, 132.00, 86.98, 6.20, 25.00, 1.70, 15622.81, '2025-09-05 21:35:09', NULL),
(152, 2, '2025-09-11', 102.00, 8.33, 22.73, 134.00, 81.41, 5.90, 27.00, 1.70, 13635.49, '2025-09-05 21:35:09', NULL),
(153, 2, '2025-09-12', 151.00, 8.48, 23.24, 135.00, 81.65, 5.70, 22.00, 1.60, 20262.02, '2025-09-05 21:35:09', NULL),
(154, 2, '2025-09-15', 150.00, 8.67, 23.34, 135.00, 83.30, 5.70, 33.00, 1.60, 20115.25, '2025-09-06 21:35:09', NULL),
(155, 2, '2025-09-16', 84.00, 9.37, 23.53, 134.00, 88.50, 5.70, 11.00, 1.50, 11198.21, '2025-09-06 21:35:09', NULL),
(156, 2, '2025-09-17', 101.00, 8.30, 22.33, 132.00, 82.50, 5.90, 20.00, 1.60, 13309.37, '2025-09-06 21:35:09', NULL),
(157, 2, '2025-09-18', 146.00, 8.36, 22.23, 132.00, 84.50, 5.90, 35.00, 1.60, 19231.16, '2025-09-06 21:35:09', NULL),
(158, 2, '2025-09-19', 128.00, 8.80, 23.09, 133.00, 85.88, 5.80, 20.00, 1.30, 16995.88, '2025-09-06 21:35:09', NULL),
(159, 2, '2025-09-22', 139.00, 7.85, 21.66, 131.00, 81.18, 6.00, 32.00, 1.60, 18125.35, '2025-09-06 21:35:09', NULL),
(160, 2, '2025-09-23', 106.00, 8.56, 23.42, 135.00, 81.26, 5.70, 22.00, 1.40, 14214.79, '2025-09-06 21:35:09', NULL),
(161, 2, '2025-09-24', 105.00, 8.20, 21.47, 131.00, 84.88, 6.10, 22.00, 1.40, 13740.52, '2025-09-06 21:35:09', NULL),
(162, 2, '2025-09-25', 104.00, 7.50, 21.49, 132.00, 77.90, 6.10, 21.00, 2.00, 13624.32, '2025-09-06 21:35:09', NULL),
(163, 2, '2025-09-26', 124.00, 8.66, 23.48, 134.00, 83.41, 5.70, 23.00, 1.60, 16526.82, '2025-09-06 21:35:09', NULL),
(164, 2, '2025-09-29', 158.00, 8.28, 22.18, 133.00, 84.90, 6.00, 41.00, 1.60, 20876.02, '2025-09-06 21:35:09', NULL),
(165, 2, '2025-09-30', 101.00, 9.08, 22.93, 133.00, 88.00, 5.80, 16.00, 1.50, 13412.82, '2025-09-06 21:35:09', NULL),
(166, 2, '2025-10-03', 130.00, 8.50, 23.21, 133.00, 85.90, 5.70, 25.00, 1.60, 17266.06, '2025-10-14 21:35:09', NULL),
(167, 2, '2025-10-06', 117.00, 7.78, 22.38, 133.00, 77.20, 5.90, 25.00, 1.60, 15552.75, '2025-10-14 21:35:09', NULL),
(168, 2, '2025-10-07', 103.00, 8.45, 22.68, 133.00, 82.80, 5.80, 20.00, 1.60, 13630.40, '2025-10-14 21:35:09', NULL),
(169, 2, '2025-10-08', 128.00, 8.83, 22.84, 133.00, 85.80, 5.80, 25.00, 1.70, 16925.23, '2025-10-14 21:35:09', NULL),
(170, 2, '2025-10-09', 125.00, 8.60, 22.52, 134.00, 84.90, 5.90, 26.00, 1.50, 16662.75, '2025-10-14 21:35:09', NULL),
(171, 2, '2025-10-10', 106.00, 7.20, 22.07, 133.00, 80.90, 6.00, 21.00, 1.80, 13990.59, '2025-10-14 21:35:09', NULL),
(172, 2, '2025-10-13', 143.00, 8.30, 21.81, 133.00, 85.10, 6.10, 29.00, 1.60, 18928.98, '2025-10-14 21:35:09', NULL),
(173, 1, '2025-10-01', 193.00, 8.49, 29.49, 132.00, 73.10, 4.50, 37.00, 2.90, 25475.58, '2025-10-14 21:35:09', NULL),
(174, 1, '2025-10-02', 241.00, 10.90, 31.28, 136.00, 82.60, 4.30, 45.00, 2.90, 32654.57, '2025-10-14 21:35:09', NULL),
(175, 1, '2025-10-03', 236.00, 10.58, 31.20, 135.00, 80.60, 4.30, 38.00, 2.90, 31732.44, '2025-10-14 21:35:09', NULL),
(176, 1, '2025-10-06', 246.00, 11.15, 32.48, 136.00, 79.10, 4.20, 52.00, 3.00, 33450.91, '2025-10-14 21:35:09', NULL),
(177, 1, '2025-10-07', 246.00, 11.29, 32.22, 138.00, 83.40, 4.30, 36.00, 3.00, 33861.66, '2025-10-14 21:35:09', NULL),
(178, 1, '2025-10-08', 207.00, 9.60, 29.75, 139.00, 73.00, 4.70, 38.00, 2.90, 28772.33, '2025-10-14 21:35:09', NULL),
(179, 1, '2025-10-09', 200.00, 9.90, 28.51, 149.00, 84.60, 5.20, 22.00, 3.00, 29619.72, '2025-10-14 21:35:09', NULL),
(180, 1, '2025-10-10', 229.00, 11.17, 31.99, 140.00, 86.80, 4.40, 32.00, 2.90, 31958.51, '2025-10-14 21:35:09', NULL),
(181, 1, '2025-10-13', 214.00, 9.69, 29.49, 136.00, 77.80, 4.60, 54.00, 3.00, 29074.28, '2025-10-14 21:35:09', NULL),
(182, 1, '2025-10-14', 236.00, 10.91, 30.39, 138.00, 82.90, 4.50, 40.00, 3.00, 32460.46, '2025-10-15 21:35:09', NULL),
(183, 1, '2025-10-15', 240.00, 10.91, 30.85, 136.00, 84.50, 4.40, 40.00, 3.00, 32705.62, '2025-10-15 21:35:09', NULL),
(184, 1, '2025-10-16', 227.00, 10.90, 32.28, 139.00, 80.90, 4.30, 40.00, 2.90, 31439.96, '2025-10-15 21:35:09', NULL),
(185, 1, '2025-10-17', 229.00, 11.00, 32.64, 141.00, 79.10, 4.30, 44.00, 3.00, 32252.36, '2025-10-15 21:35:09', NULL),
(186, 1, '2025-10-20', 240.00, 11.07, 32.80, 139.00, 78.20, 4.20, 47.00, 2.90, 33197.33, '2025-10-16 21:35:09', NULL),
(187, 1, '2025-10-21', 228.00, 10.30, 30.88, 136.00, 79.40, 4.40, 39.00, 2.90, 30912.24, '2025-10-17 21:35:09', NULL),
(188, 1, '2025-10-22', 207.00, 9.77, 31.91, 142.00, 75.10, 4.40, 38.00, 2.90, 29297.51, '2025-10-18 21:35:09', NULL),
(189, 1, '2025-10-23', 219.00, 10.84, 31.70, 149.00, 84.00, 4.70, 28.00, 2.90, 32524.49, '2025-10-18 21:35:09', NULL),
(190, 1, '2025-10-24', 239.00, 11.32, 33.62, 142.00, 80.20, 4.20, 45.00, 3.00, 33954.42, '2025-10-18 21:35:09', NULL),
(191, 2, '2025-10-14', 107.00, 8.84, 23.61, 135.00, 83.20, 5.70, 18.00, 1.50, 14376.83, '2025-10-19 21:35:09', NULL),
(192, 2, '2025-10-15', 97.00, 7.88, 21.56, 131.00, 81.20, 6.10, 22.00, 1.30, 12678.76, '2025-10-20 21:35:09', NULL),
(193, 2, '2025-10-16', 116.00, 8.57, 22.06, 133.00, 86.40, 6.00, 28.00, 1.50, 15394.98, '2025-10-21 21:35:09', NULL),
(194, 2, '2025-10-17', 136.00, 7.72, 21.65, 133.00, 80.90, 6.10, 25.00, 1.20, 17994.51, '2025-10-21 21:35:09', NULL),
(195, 2, '2025-10-20', 126.00, 8.64, 22.91, 133.00, 83.80, 5.80, 23.00, 1.50, 16726.89, '2025-10-21 21:35:09', NULL),
(196, 2, '2025-10-21', 111.00, 8.48, 22.75, 134.00, 82.80, 5.90, 23.00, 1.50, 14810.84, '2025-10-21 21:35:09', NULL),
(197, 2, '2025-10-22', 108.00, 7.14, 21.48, 129.00, 73.90, 6.00, 20.00, 1.50, 13835.68, '2025-10-21 21:35:09', NULL),
(198, 2, '2025-10-23', 98.00, 7.99, 22.14, 132.00, 80.20, 6.00, 27.00, 1.90, 12930.40, '2025-10-22 21:35:09', NULL),
(199, 2, '2025-10-24', 123.00, 8.50, 22.57, 134.00, 85.50, 5.90, 25.00, 1.50, 16387.84, '2025-10-23 21:35:09', NULL),
(200, 2, '2025-08-01', 156.00, 8.46, 22.25, 132.00, 85.70, 5.90, 34.00, 1.40, 20474.36, '2025-08-06 21:35:09', NULL),
(201, 2, '2025-08-04', 139.00, 8.43, 22.24, 135.00, 84.91, 6.10, 25.00, 1.50, 18766.52, '2025-08-06 21:35:09', NULL),
(202, 2, '2025-08-05', 110.00, 8.43, 22.19, 132.00, 84.40, 5.90, 21.00, 1.40, 14513.59, '2025-08-06 21:35:09', NULL),
(203, 2, '2025-08-06', 110.00, 7.86, 20.70, 129.00, 84.40, 6.20, 34.00, 1.60, 14155.82, '2025-08-06 21:35:09', NULL),
(204, 2, '2025-08-07', 131.00, 7.96, 22.28, 133.00, 80.50, 5.90, 33.00, 1.50, 17330.72, '2025-08-06 21:35:09', NULL),
(205, 2, '2025-08-08', 117.00, 8.56, 23.05, 133.00, 83.90, 5.80, 23.00, 1.40, 15536.75, '2025-08-06 21:35:09', NULL),
(206, 2, '2025-08-11', 117.00, 8.11, 22.22, 134.00, 81.20, 6.00, 24.00, 1.60, 15597.17, '2025-08-06 21:35:09', NULL),
(207, 2, '2025-08-12', 121.00, 8.44, 21.73, 133.00, 86.30, 6.10, 23.00, 1.50, 16060.95, '2025-08-06 21:35:09', NULL),
(208, 2, '2025-08-13', 111.00, 8.40, 22.27, 132.00, 83.80, 5.90, 22.00, 1.90, 14654.28, '2025-08-06 21:35:09', NULL),
(209, 2, '2025-08-14', 132.00, 8.60, 22.80, 134.00, 83.10, 5.90, 32.00, 1.60, 17671.70, '2025-08-06 21:35:09', NULL),
(210, 2, '2025-08-15', 143.00, 8.61, 23.31, 134.00, 84.40, 5.70, 22.00, 1.80, 19115.57, '2025-08-06 21:35:09', NULL),
(211, 2, '2025-08-18', 138.00, 7.96, 22.01, 133.00, 81.30, 6.00, 28.00, 1.70, 18309.68, '2025-08-07 21:35:09', NULL),
(212, 2, '2025-08-19', 102.00, 8.80, 21.94, 129.00, 88.70, 5.90, 16.00, 1.30, 13098.36, '2025-08-08 21:35:09', NULL),
(213, 2, '2025-08-20', 103.00, 7.70, 21.66, 132.00, 79.47, 6.10, 23.00, 1.40, 13583.50, '2025-08-09 21:35:09', NULL),
(214, 2, '2025-08-21', 117.00, 7.94, 22.19, 131.00, 79.60, 5.90, 26.00, 1.60, 15286.86, '2025-08-10 21:35:09', NULL),
(215, 2, '2025-08-22', 116.00, 8.59, 22.74, 134.00, 85.40, 5.90, 24.00, 1.20, 15509.27, '2025-08-11 21:35:09', NULL),
(216, 2, '2025-08-25', 121.00, 8.40, 22.98, 135.00, 81.30, 5.90, 24.00, 1.70, 16296.12, '2025-08-12 21:35:09', NULL),
(217, 2, '2025-08-26', 107.00, 8.85, 22.87, 134.00, 85.99, 5.90, 16.00, 1.60, 14314.71, '2025-08-13 21:35:09', NULL),
(218, 2, '2025-08-27', 103.00, 7.22, 20.36, 128.00, 79.31, 6.30, 29.00, 1.50, 13111.32, '2025-08-14 21:35:09', NULL),
(219, 2, '2025-08-28', 106.00, 7.98, 22.16, 132.00, 82.55, 5.90, 29.00, 1.40, 13915.56, '2025-08-15 21:35:09', NULL),
(220, 2, '2025-08-29', 117.00, 7.70, 21.42, 134.00, 80.60, 6.20, 27.00, 1.40, 15659.68, '2025-08-16 21:35:09', NULL),
(221, 1, '2025-08-01', 225.00, 11.10, 32.89, 137.00, 80.70, 4.10, 34.00, 2.90, 30689.43, '2025-08-06 21:35:09', NULL),
(222, 1, '2025-08-04', 254.00, 12.40, 35.41, 146.00, 80.60, 4.10, 37.00, 3.00, 37105.80, '2025-08-06 21:35:09', NULL),
(223, 1, '2025-08-05', 195.00, 8.09, 27.93, 125.00, 67.20, 4.50, 43.00, 3.00, 24273.27, '2025-08-06 21:35:09', NULL),
(224, 1, '2025-08-06', 245.00, 11.50, 33.79, 142.00, 82.80, 4.20, 38.00, 3.00, 34632.79, '2025-08-06 21:35:09', NULL),
(225, 1, '2025-08-07', 219.00, 9.70, 31.01, 134.00, 75.20, 4.30, 47.00, 3.00, 29184.59, '2025-08-06 21:35:09', NULL),
(226, 1, '2025-08-08', 223.00, 9.89, 31.72, 134.00, 74.30, 4.20, 54.00, 3.00, 29658.46, '2025-08-06 21:35:09', NULL),
(227, 1, '2025-08-11', 257.00, 12.15, 33.64, 142.00, 83.70, 4.20, 35.00, 2.90, 36463.38, '2025-08-07 21:35:09', NULL),
(228, 1, '2025-08-12', 245.00, 11.25, 32.73, 138.00, 80.60, 4.20, 41.00, 3.00, 33749.47, '2025-08-08 21:35:09', NULL),
(229, 1, '2025-08-13', 223.00, 10.11, 31.13, 136.00, 77.60, 4.40, 37.00, 2.90, 30319.19, '2025-08-09 21:35:09', NULL),
(230, 1, '2025-08-14', 204.00, 9.03, 31.30, 133.00, 66.60, 4.20, 45.00, 3.00, 27101.50, '2025-08-10 21:35:09', NULL),
(231, 1, '2025-08-15', 192.00, 9.51, 28.21, 149.00, 80.20, 5.30, 26.00, 3.00, 28520.03, '2025-08-11 21:35:09', NULL),
(232, 1, '2025-08-18', 259.00, 12.10, 33.35, 140.00, 81.00, 4.20, 36.00, 2.90, 36313.62, '2025-08-30 21:35:09', NULL),
(233, 1, '2025-08-19', 249.00, 11.64, 33.77, 141.00, 82.80, 4.20, 39.00, 2.90, 34918.41, '2025-08-30 21:35:09', NULL),
(234, 1, '2025-08-20', 168.00, 8.00, 33.00, 144.00, 78.30, 4.30, 23.00, 3.00, 24089.65, '2025-08-30 21:35:09', NULL),
(235, 1, '2025-08-21', 178.00, 11.69, 33.34, 139.00, 81.90, 4.20, 32.00, 3.00, 24741.40, '2025-08-30 21:35:09', NULL),
(236, 1, '2025-08-22', 216.00, 9.98, 30.78, 139.00, 79.80, 4.50, 48.00, 3.00, 29948.77, '2025-08-30 21:35:09', NULL),
(237, 1, '2025-08-25', 222.00, 10.10, 31.50, 137.00, 74.80, 4.30, 28.00, 2.90, 30303.56, '2025-08-30 21:35:09', NULL),
(238, 1, '2025-08-26', 127.00, 8.18, 25.35, 167.00, 74.60, 6.50, 4.00, 3.00, 21086.57, '2025-08-30 21:35:09', NULL),
(239, 1, '2025-08-27', 200.00, 8.90, 29.63, 134.00, 70.40, 4.50, 56.00, 2.90, 26698.46, '2025-08-30 21:35:09', NULL),
(240, 1, '2025-08-28', 220.00, 10.08, 30.92, 138.00, 77.50, 4.40, 45.00, 3.00, 30237.68, '2025-08-30 21:35:09', NULL),
(241, 1, '2025-08-29', 219.00, 10.43, 31.07, 143.00, 81.00, 4.60, 31.00, 2.90, 31284.21, '2025-08-30 21:35:09', NULL),
(242, 1, '2025-08-30', 161.00, 10.71, 32.45, 133.00, 77.30, 4.10, 29.00, 2.90, 21417.08, '2025-08-30 21:35:09', NULL),
(243, 1, '2025-11-03', 0.01, 0.01, 0.01, 0.01, 1.01, 1.01, 1.01, 1.01, 1.01, '2025-11-03 21:45:22', NULL),
(244, 1, '2025-10-27', 238.00, 10.90, 30.70, 137.00, 82.10, 4.50, 37.00, 3.00, 32594.00, '2025-11-03 21:54:17', NULL),
(245, 1, '2025-10-28', 248.00, 11.10, 31.80, 134.00, 83.50, 4.20, 44.00, 2.90, 33238.00, '2025-11-03 21:57:33', NULL),
(246, 1, '2025-10-29', 217.00, 9.90, 30.20, 138.00, 78.40, 4.50, 46.00, 2.90, 29775.00, '2025-11-03 22:02:10', NULL),
(247, 1, '2025-10-30', 224.00, 10.30, 31.40, 138.00, 77.50, 4.40, 42.00, 3.00, 30772.00, '2025-11-03 22:03:28', NULL),
(248, 1, '2025-10-31', 216.00, 9.60, 30.90, 134.00, 76.20, 4.30, 37.00, 3.00, 29859.00, '2025-11-03 22:04:36', NULL),
(249, 2, '2025-11-05', 99.00, 99.00, 99.00, 99.00, 99.00, 99.00, 99.00, 99.00, 12.00, '2025-11-05 01:16:45', NULL),
(250, 1, '2025-10-25', 12.00, 12.00, 12.00, 12.00, 12.00, 12.00, 12.00, 12.00, 12.00, '2025-11-05 03:43:06', NULL);

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
(1, '01', 'LINE A', '2025-10-20 01:18:40'),
(2, '02', 'LINE B', '2025-10-20 01:18:40'),
(3, '03', 'LINE C', '2025-10-20 01:18:40');

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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `target`
--

INSERT INTO `target` (`id`, `tahun_target`, `line_id`, `target_batch_count`, `target_productivity`, `target_production_speed`, `target_batch_weight`, `target_operation_factor`, `target_cycle_time`, `target_grade_change_sequence`, `target_grade_change_time`, `target_feed_raw_material`, `created_at`, `updated_at`) VALUES
(58, 2025, 2, 99.00, 99.00, 99.00, 99.00, 99.00, 99.00, 99.00, 99.00, 99.00, '2025-10-20 06:52:16', NULL),
(59, 2026, 1, 88.00, 88.00, 8.00, 8.00, 88.00, 88.00, 8.00, 8.00, 8.00, '2025-10-23 08:27:26', '2025-11-07 07:35:37'),
(61, 2025, 1, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, '2025-11-07 07:35:10', NULL);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `input_harian`
--
ALTER TABLE `input_harian`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;

--
-- AUTO_INCREMENT for table `line_produksi`
--
ALTER TABLE `line_produksi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `target`
--
ALTER TABLE `target`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

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

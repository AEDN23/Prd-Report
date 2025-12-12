
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `info` (
  `id` int NOT NULL,
  `judul` varchar(50) NOT NULL,
  `isi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `deskripsi` varchar(50) NOT NULL,
  `file` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


INSERT INTO `info` (`id`, `judul`, `isi`, `deskripsi`, `file`, `created_at`, `updated_at`) VALUES
(19, 'DONOR DARAH', 'KIM Medical Clinic kembali mengadakan kegiatan Sosial Donor Darah, Untuk membantu Sesama yang membutuhkan Darah.\r\n\r\nKegiatan akan diadakan \r\nJum\'at ,17 Oktober 2025.\r\njam 08:00-12:00, \r\nDi KIM Medical Clinic.\r\n\r\nAgar petugas PMI bisa menyiapkan kebutuhannya dengan baik, mohon Segera daftarkan diri anda melalui Link dibawah ini :\r\n\r\nbit.ly/3KyhVh8\r\n\r\nMohon bantuannya untuk mengajak Karyawan, teman atau saudara untuk berpartisipasi ya\r\n\r\nInformasi lebih lanjut\r\nZahra 08111829080\r\n\r\nTerima kasih.', 'DONOR DARAH', NULL, '2025-10-17 10:52:08', NULL),
(23, 'Digital MSDS', 'Membuat mini system untuk MSDS dengan menyimpan data di PC weighting dan jika mau dilihat tinggal klik search, jadi mempermudah pencarian dan paper less', 'Dibuatkan MSDS digital', 'digital_msds_692699ef54318.xlsx', '2025-11-26 13:10:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `input_harian`
--

CREATE TABLE `input_harian` (
  `id` int NOT NULL,
  `line_id` int DEFAULT NULL,
  `shift_id` int DEFAULT NULL,
  `tanggal` date NOT NULL,
  `batch_count` decimal(7,2) DEFAULT NULL,
  `productivity` decimal(7,2) DEFAULT NULL,
  `production_speed` decimal(7,2) DEFAULT NULL,
  `batch_weight` decimal(7,2) DEFAULT NULL,
  `operation_factor` decimal(7,2) DEFAULT NULL,
  `cycle_time` decimal(7,2) DEFAULT NULL,
  `grade_change_sequence` decimal(7,2) DEFAULT NULL,
  `grade_change_time` decimal(7,2) DEFAULT NULL,
  `feed_raw_material` decimal(10,2) DEFAULT NULL,
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `line_produksi` (
  `id` int NOT NULL,
  `kode_line` varchar(10) NOT NULL,
  `nama_line` varchar(100) DEFAULT NULL,
  `dibuat_pada` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
 

INSERT INTO `line_produksi` (`id`, `kode_line`, `nama_line`, `dibuat_pada`) VALUES
(1, '01', 'Line A', '2025-11-24 03:56:23'),
(2, '02', 'Line B', '2025-11-24 03:56:23');
 

CREATE TABLE `master_shift` (
  `id` int NOT NULL,
  `kode_shift` varchar(10) NOT NULL,
  `nama_shift` varchar(50) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `dibuat_pada` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

 

INSERT INTO `master_shift` (`id`, `kode_shift`, `nama_shift`, `jam_mulai`, `jam_selesai`, `dibuat_pada`) VALUES
(1, 'S1', 'Shift 1', '07:00:00', '15:00:00', '2025-11-17 03:13:36'),
(2, 'S2', 'Shift 2', '15:00:00', '23:00:00', '2025-11-17 03:13:36'),
(3, 'S3', 'Shift 3', '23:00:00', '07:00:00', '2025-11-17 03:13:36');

 

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

 
-- Dumping data for table `target`
 

INSERT INTO `target` (`id`, `tahun_target`, `line_id`, `target_batch_count`, `target_productivity`, `target_production_speed`, `target_batch_weight`, `target_operation_factor`, `target_cycle_time`, `target_grade_change_sequence`, `target_grade_change_time`, `target_feed_raw_material`, `created_at`, `updated_at`) VALUES
(62, 2025, 1, 243.00, 11.30, 32.70, 140.00, 82.50, 4.28, 0.00, 3.10, 0.00, '2025-11-24 04:01:18', NULL),
(63, 2025, 2, 116.00, 8.30, 22.30, 134.00, 22.30, 6.00, 24.30, 2.00, 0.00, '2025-11-24 04:02:07', NULL);

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
  ADD UNIQUE KEY `unique_tanggal_line_shift` (`tanggal`,`line_id`,`shift_id`),
  ADD KEY `line_id` (`line_id`),
  ADD KEY `idx_tanggal` (`tanggal`),
  ADD KEY `idx_tanggal_line` (`tanggal`,`line_id`),
  ADD KEY `input_harian_ibfk_shift` (`shift_id`);

--
-- Indexes for table `line_produksi`
--
ALTER TABLE `line_produksi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_line` (`kode_line`);

--
-- Indexes for table `master_shift`
--
ALTER TABLE `master_shift`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `input_harian`
--
ALTER TABLE `input_harian`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=539;

--
-- AUTO_INCREMENT for table `line_produksi`
--
ALTER TABLE `line_produksi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `target`
--
ALTER TABLE `target`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `input_harian`
--
ALTER TABLE `input_harian`
  ADD CONSTRAINT `input_harian_ibfk_2` FOREIGN KEY (`line_id`) REFERENCES `line_produksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `input_harian_ibfk_shift` FOREIGN KEY (`shift_id`) REFERENCES `master_shift` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `target`
--
ALTER TABLE `target`
  ADD CONSTRAINT `target_ibfk_2` FOREIGN KEY (`line_id`) REFERENCES `line_produksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

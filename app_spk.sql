-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 27, 2021 at 07:52 AM
-- Server version: 5.7.24
-- PHP Version: 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `app_spk`
--

-- --------------------------------------------------------

--
-- Table structure for table `alternatif`
--

CREATE TABLE `alternatif` (
  `id_alternatif` int(5) NOT NULL,
  `id_santri_alternatif` int(5) NOT NULL,
  `id_kriteria_alternatif` int(5) NOT NULL,
  `id_subkriteria_alternatif` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `alternatif`
--

INSERT INTO `alternatif` (`id_alternatif`, `id_santri_alternatif`, `id_kriteria_alternatif`, `id_subkriteria_alternatif`) VALUES
(25, 2, 23, 25),
(26, 2, 24, 29),
(27, 2, 25, 33),
(28, 2, 26, 38),
(29, 3, 23, 23),
(30, 3, 24, 28),
(31, 3, 25, 33),
(32, 3, 26, 37),
(33, 4, 23, 22),
(34, 4, 24, 28),
(35, 4, 25, 32),
(36, 4, 26, 38),
(37, 5, 23, 25),
(38, 5, 24, 28),
(39, 5, 25, 32),
(40, 5, 26, 38);

-- --------------------------------------------------------

--
-- Table structure for table `bobot_saw`
--

CREATE TABLE `bobot_saw` (
  `id_bobot_saw` int(5) NOT NULL,
  `id_kriteria_bobot_saw` int(5) NOT NULL,
  `nilai_bobot_saw` float DEFAULT NULL,
  `keterangan_bobot_saw` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bobot_saw`
--

INSERT INTO `bobot_saw` (`id_bobot_saw`, `id_kriteria_bobot_saw`, `nilai_bobot_saw`, `keterangan_bobot_saw`) VALUES
(2, 23, 0.15, 'benefit'),
(3, 24, 0.35, 'benefit'),
(4, 25, 0.35, 'benefit'),
(5, 26, 0.15, 'benefit');

-- --------------------------------------------------------

--
-- Table structure for table `hasil`
--

CREATE TABLE `hasil` (
  `id_hasil` int(5) NOT NULL,
  `id_santri` int(5) NOT NULL,
  `nilaiCf` float NOT NULL,
  `nilaiSf` float NOT NULL,
  `total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hasil`
--

INSERT INTO `hasil` (`id_hasil`, `id_santri`, `nilaiCf`, `nilaiSf`, `total`) VALUES
(57, 2, 1.5, 1.5, 1.1),
(58, 3, 2, 2, 1.4),
(59, 4, 2.5, 2.5, 1.8),
(60, 5, 2.5, 2.5, 1.8);

-- --------------------------------------------------------

--
-- Table structure for table `hasil_saw`
--

CREATE TABLE `hasil_saw` (
  `id_hasil_saw` int(5) NOT NULL,
  `id_santri_hasil_saw` int(5) NOT NULL,
  `total_hasil_saw` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hasil_saw`
--

INSERT INTO `hasil_saw` (`id_hasil_saw`, `id_santri_hasil_saw`, `total_hasil_saw`) VALUES
(52, 2, 1),
(53, 3, 0.8),
(54, 4, 0.7125),
(55, 5, 0.825);

-- --------------------------------------------------------

--
-- Table structure for table `jenis_kriteria`
--

CREATE TABLE `jenis_kriteria` (
  `id_jenis` int(5) NOT NULL,
  `nama_jenis` varchar(150) NOT NULL,
  `nilai_jenis` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jenis_kriteria`
--

INSERT INTO `jenis_kriteria` (`id_jenis`, `nama_jenis`, `nilai_jenis`) VALUES
(5, 'Core Factor (CF)', 0.7),
(7, 'Seoncary Factor (SF)', 0.3);

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` int(5) NOT NULL,
  `nama_kriteria` varchar(150) NOT NULL,
  `id_jenis` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `nama_kriteria`, `id_jenis`) VALUES
(23, 'Penguasaan Bahasa Arab', 7),
(24, 'Penguasaan ilmu tahsin', 5),
(25, 'Penguasaan Ilmu Tajwid', 5),
(26, 'Penguasaan iIlmu Kecakapan', 7);

-- --------------------------------------------------------

--
-- Table structure for table `santri`
--

CREATE TABLE `santri` (
  `id_santri` int(5) NOT NULL,
  `nama_santri` varchar(150) NOT NULL,
  `ttl_santri` date NOT NULL,
  `jk_santri` varchar(50) NOT NULL,
  `kelas_santri` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(50) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `santri`
--

INSERT INTO `santri` (`id_santri`, `nama_santri`, `ttl_santri`, `jk_santri`, `kelas_santri`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(2, 'Rizal', '1993-06-16', 'Laki-Laki', 'IPA 10', '2021-06-25 10:33:51', 'coba', NULL, NULL),
(3, 'Soleh', '1994-10-18', 'Laki-Laki', 'IPA 10', '2021-06-25 12:31:09', 'coba', NULL, NULL),
(4, 'Faizar', '1998-07-14', 'Laki-Laki', 'IPA 10', '2021-06-25 12:35:18', 'coba', NULL, NULL),
(5, 'Rahman', '1993-02-02', 'Laki-Laki', 'IPA 10', '2021-06-25 12:37:37', 'coba', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `selisih`
--

CREATE TABLE `selisih` (
  `id_selisih` int(5) NOT NULL,
  `nilai_selisih` int(2) NOT NULL,
  `bobot_selisih` float NOT NULL,
  `keterangan_selisih` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `selisih`
--

INSERT INTO `selisih` (`id_selisih`, `nilai_selisih`, `bobot_selisih`, `keterangan_selisih`) VALUES
(12, 0, 4, 'Tidak ada selisih (kompetensi sesuai dengan yang dibutuhkan)'),
(13, 1, 3.5, 'Kompetensi individu kelebihan 1 tingkat'),
(14, -1, 3, 'Kompetensi individu kekurangan 1 tingkat'),
(15, 2, 2.5, 'Kompetensi individu kelebihan 2 tingkat'),
(16, -2, 2, 'Kompetensi individu kekurangan 2 tingkat'),
(17, 3, 1.5, 'Kompetensi individu kelebihan 3 tingkat'),
(18, -3, 1, 'Kompetensi individu kekurangan 3 tingkat');

-- --------------------------------------------------------

--
-- Table structure for table `subkriteria`
--

CREATE TABLE `subkriteria` (
  `id_subkriteria` int(5) NOT NULL,
  `id_kriteria_sub` int(5) NOT NULL,
  `nama_subkriteria` varchar(150) NOT NULL,
  `nilai_subkriteria` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subkriteria`
--

INSERT INTO `subkriteria` (`id_subkriteria`, `id_kriteria_sub`, `nama_subkriteria`, `nilai_subkriteria`) VALUES
(22, 23, 'Buruk', 1),
(23, 23, 'Cukup Baik', 2),
(24, 23, 'Baik', 3),
(25, 23, 'Mahir', 4),
(26, 24, 'Buruk', 1),
(27, 24, 'Cukup Baik', 2),
(28, 24, 'Baik', 3),
(29, 24, 'Mahir', 4),
(30, 25, 'Buruk', 1),
(31, 25, 'Cukup Baik', 2),
(32, 25, 'Baik', 3),
(33, 25, 'Mahir', 4),
(35, 26, 'Buruk', 1),
(36, 26, 'Cukup Baik', 2),
(37, 26, 'Baik', 3),
(38, 26, 'Mahir', 4);

-- --------------------------------------------------------

--
-- Table structure for table `temp_saw`
--

CREATE TABLE `temp_saw` (
  `id_temp_saw` int(5) NOT NULL,
  `id_santri_temp_saw` int(5) NOT NULL,
  `id_kriteria_temp_saw` int(5) NOT NULL,
  `nilai_temp_saw` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `temp_saw`
--

INSERT INTO `temp_saw` (`id_temp_saw`, `id_santri_temp_saw`, `id_kriteria_temp_saw`, `nilai_temp_saw`) VALUES
(877, 2, 23, 4),
(878, 2, 24, 4),
(879, 2, 25, 4),
(880, 2, 26, 4),
(881, 3, 23, 2),
(882, 3, 24, 3),
(883, 3, 25, 4),
(884, 3, 26, 3),
(885, 4, 23, 1),
(886, 4, 24, 3),
(887, 4, 25, 3),
(888, 4, 26, 4),
(889, 5, 23, 4),
(890, 5, 24, 3),
(891, 5, 25, 3),
(892, 5, 26, 4),
(893, 6, 23, 3),
(894, 6, 24, 3),
(895, 6, 25, 4),
(896, 6, 26, 2);

-- --------------------------------------------------------

--
-- Table structure for table `ulasan`
--

CREATE TABLE `ulasan` (
  `id_ulasan` int(5) NOT NULL,
  `id_objek_ulasan` int(5) NOT NULL,
  `nama_ulasan` varchar(150) NOT NULL,
  `email_ulasan` varchar(150) NOT NULL,
  `isi_ulasan` text NOT NULL,
  `rating_ulasan` int(3) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_users` int(5) NOT NULL,
  `uname_users` varchar(150) NOT NULL,
  `pass_users` varchar(255) NOT NULL,
  `nama_users` varchar(150) NOT NULL,
  `jk_users` varchar(50) NOT NULL,
  `hp_users` varchar(12) NOT NULL,
  `role_users` int(4) NOT NULL,
  `status` int(2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(150) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_users`, `uname_users`, `pass_users`, `nama_users`, `jk_users`, `hp_users`, `role_users`, `status`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(4, 'admin', '$2y$10$JXwKvuOpzJJaoiXZePEZpu/6nQGNDEXjRRGOpenvycne49jpHqWHu', 'admin', 'Perempuan', '087627362737', 1, 1, '2021-06-24 12:31:40', 'admin', '2021-06-25 02:34:26', 'admin 12'),
(5, 'user', '$2y$10$FGvpsV7yGuGqE7bZZsCne.X1YzbB7YK94fGeGO8o04sSI8plR73PK', 'coba', 'Perempuan', '089887876677', 2, 1, '2021-06-24 15:04:09', 'admin', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alternatif`
--
ALTER TABLE `alternatif`
  ADD PRIMARY KEY (`id_alternatif`);

--
-- Indexes for table `bobot_saw`
--
ALTER TABLE `bobot_saw`
  ADD PRIMARY KEY (`id_bobot_saw`);

--
-- Indexes for table `hasil`
--
ALTER TABLE `hasil`
  ADD PRIMARY KEY (`id_hasil`);

--
-- Indexes for table `hasil_saw`
--
ALTER TABLE `hasil_saw`
  ADD PRIMARY KEY (`id_hasil_saw`);

--
-- Indexes for table `jenis_kriteria`
--
ALTER TABLE `jenis_kriteria`
  ADD PRIMARY KEY (`id_jenis`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `santri`
--
ALTER TABLE `santri`
  ADD PRIMARY KEY (`id_santri`);

--
-- Indexes for table `selisih`
--
ALTER TABLE `selisih`
  ADD PRIMARY KEY (`id_selisih`);

--
-- Indexes for table `subkriteria`
--
ALTER TABLE `subkriteria`
  ADD PRIMARY KEY (`id_subkriteria`);

--
-- Indexes for table `temp_saw`
--
ALTER TABLE `temp_saw`
  ADD PRIMARY KEY (`id_temp_saw`);

--
-- Indexes for table `ulasan`
--
ALTER TABLE `ulasan`
  ADD PRIMARY KEY (`id_ulasan`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alternatif`
--
ALTER TABLE `alternatif`
  MODIFY `id_alternatif` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `bobot_saw`
--
ALTER TABLE `bobot_saw`
  MODIFY `id_bobot_saw` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hasil`
--
ALTER TABLE `hasil`
  MODIFY `id_hasil` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `hasil_saw`
--
ALTER TABLE `hasil_saw`
  MODIFY `id_hasil_saw` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `jenis_kriteria`
--
ALTER TABLE `jenis_kriteria`
  MODIFY `id_jenis` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `santri`
--
ALTER TABLE `santri`
  MODIFY `id_santri` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `selisih`
--
ALTER TABLE `selisih`
  MODIFY `id_selisih` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `subkriteria`
--
ALTER TABLE `subkriteria`
  MODIFY `id_subkriteria` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `temp_saw`
--
ALTER TABLE `temp_saw`
  MODIFY `id_temp_saw` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=897;

--
-- AUTO_INCREMENT for table `ulasan`
--
ALTER TABLE `ulasan`
  MODIFY `id_ulasan` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

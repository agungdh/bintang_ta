-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2018 at 07:37 AM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bintang_ta`
--

-- --------------------------------------------------------

--
-- Table structure for table `bidang`
--

CREATE TABLE `bidang` (
  `id` int(11) NOT NULL,
  `bidang` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bidang`
--

INSERT INTO `bidang` (`id`, `bidang`) VALUES
(1, 'Perekonomian dan Sumber Daya Alam'),
(2, 'Sosial Budaya dan Pemerintahan'),
(3, 'Infrastruktur dan Pengembangan Wilayah'),
(4, 'Pengendalian dan Litbang');

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `judul_aplikasi` varchar(255) NOT NULL,
  `judul_menu` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`judul_aplikasi`, `judul_menu`) VALUES
('Bintang Paok', 'KEMPLO');

-- --------------------------------------------------------

--
-- Table structure for table `disposisi`
--

CREATE TABLE `disposisi` (
  `id` int(11) NOT NULL,
  `surat_id` int(11) NOT NULL,
  `waktu_disposisi` datetime NOT NULL,
  `bidang_id` int(11) NOT NULL,
  `isi` varchar(255) NOT NULL,
  `waktu_baca` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `disposisi`
--

INSERT INTO `disposisi` (`id`, `surat_id`, `waktu_disposisi`, `bidang_id`, `isi`, `waktu_baca`, `status`) VALUES
(1, 1, '2018-06-09 00:00:00', 3, 'cok, gasaak', '2018-06-10 00:00:00', 1),
(2, 1, '2018-06-11 00:00:00', 2, 'cok, aku raiso... bantuin', '2018-06-15 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `proses`
--

CREATE TABLE `proses` (
  `id` int(11) NOT NULL,
  `disposisi_id` int(11) NOT NULL,
  `waktu_proses` datetime NOT NULL,
  `waktu_selesai` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `proses`
--

INSERT INTO `proses` (`id`, `disposisi_id`, `waktu_proses`, `waktu_selesai`) VALUES
(1, 2, '2018-06-16 00:00:00', '2018-06-17 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `surat`
--

CREATE TABLE `surat` (
  `id` int(11) NOT NULL,
  `kode` varchar(255) NOT NULL,
  `waktu_terima` datetime NOT NULL,
  `pengirim` varchar(255) NOT NULL,
  `perihal` varchar(255) NOT NULL,
  `waktu_baca` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `surat`
--

INSERT INTO `surat` (`id`, `kode`, `waktu_terima`, `pengirim`, `perihal`, `waktu_baca`, `status`) VALUES
(1, 'asdf', '2018-06-07 03:16:25', 'bintang paok', 'emboh iki cok', '2018-06-08 06:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` int(11) NOT NULL,
  `bidang_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nama`, `username`, `password`, `level`, `bidang_id`) VALUES
(1, 'Administrator', 'admin', 'c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec', 1, NULL),
(2, 'Admin Test', 'admintest', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db', 1, NULL),
(3, 'test sekertaris', 'sekertaris', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db', 3, NULL),
(4, 'test kadis', 'kadis', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db', 2, NULL),
(7, 'sosmdut 1221', 'sosmbut', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db', 4, 2),
(8, 'test operator', 'testop', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db', 5, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bidang`
--
ALTER TABLE `bidang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disposisi`
--
ALTER TABLE `disposisi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bidang_id` (`bidang_id`),
  ADD KEY `disposisi_ibfk_2` (`surat_id`);

--
-- Indexes for table `proses`
--
ALTER TABLE `proses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `disposisi_id` (`disposisi_id`);

--
-- Indexes for table `surat`
--
ALTER TABLE `surat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `bidang_id` (`bidang_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bidang`
--
ALTER TABLE `bidang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `disposisi`
--
ALTER TABLE `disposisi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `proses`
--
ALTER TABLE `proses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `surat`
--
ALTER TABLE `surat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `disposisi`
--
ALTER TABLE `disposisi`
  ADD CONSTRAINT `disposisi_ibfk_1` FOREIGN KEY (`bidang_id`) REFERENCES `bidang` (`id`),
  ADD CONSTRAINT `disposisi_ibfk_2` FOREIGN KEY (`surat_id`) REFERENCES `surat` (`id`);

--
-- Constraints for table `proses`
--
ALTER TABLE `proses`
  ADD CONSTRAINT `proses_ibfk_1` FOREIGN KEY (`disposisi_id`) REFERENCES `disposisi` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`bidang_id`) REFERENCES `bidang` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2020 at 06:51 AM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `monitoring`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_operator`
--

CREATE TABLE `activity_operator` (
  `id` int(11) NOT NULL,
  `no_unit` varchar(10) NOT NULL,
  `nrp` varchar(10) NOT NULL,
  `shift` int(11) NOT NULL,
  `days_of` int(11) NOT NULL,
  `hm_start` float NOT NULL,
  `hm_stop` float DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `validation` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_user`
--

CREATE TABLE `app_user` (
  `UID` int(11) NOT NULL,
  `UNAMA` varchar(50) NOT NULL,
  `UDISTRIK` varchar(50) NOT NULL,
  `ULEVEL` varchar(50) NOT NULL,
  `UPASS` varchar(50) NOT NULL,
  `rowguid` int(11) NOT NULL,
  `rowguid7` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_user`
--

INSERT INTO `app_user` (`UID`, `UNAMA`, `UDISTRIK`, `ULEVEL`, `UPASS`, `rowguid`, `rowguid7`) VALUES
(1, 'danang', '1', '2', 'danang', 1, 1),
(3, 'tasran', '5', '1', 'tasran', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `detail_user`
--

CREATE TABLE `detail_user` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nrp` varchar(10) NOT NULL,
  `UID` int(11) DEFAULT NULL,
  `divisi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `detail_user`
--

INSERT INTO `detail_user` (`id`, `nama`, `nrp`, `UID`, `divisi`) VALUES
(1, 'Danang Kuriawan', '0109076', 1, 1),
(2, 'Muhammad Haykal Karamy', '0113938', NULL, 2),
(3, 'Musliman', '0409095', NULL, 3),
(4, 'Supriyadi', '0409009', NULL, 3),
(5, 'Agus Winarto', '0411499', NULL, 3),
(6, 'Imam Maliki', '0111448', NULL, 3),
(7, 'Kendar Sulistiyo', '0113974', NULL, 3),
(8, 'Fajar Hidayat', '0110190', NULL, 3),
(9, 'Karyanto', '0409093', NULL, 3),
(10, 'Karyanto', '0409093', NULL, 3),
(11, 'Argi Saputra', '0116032', NULL, 3),
(12, 'Hamzah', '0810003', NULL, 3),
(13, 'Akhmad Prayoga', '0118005', NULL, 3),
(14, 'Giferi Hendrayana', '0118042', NULL, 3),
(15, 'Alpian Permana Putra', '0118047', NULL, 3),
(16, 'Rahmant Nugroho', '0410185', NULL, 3),
(17, 'Sri Johney Christianto', '0111552', NULL, 3),
(18, 'Kevin Gardo Bangkit', '0118075', NULL, 3),
(19, 'Henny Novita Sari', '0415007', NULL, 4),
(20, 'Rinda Ramadianti', '0415006', NULL, 4),
(21, 'Yohanis Manggasa', '0411798', NULL, 5),
(22, 'Roguna Siregar', '0409072', NULL, 5),
(23, 'Isramsyah', '0410205', NULL, 5),
(24, 'Tasran', '0410184', 3, 5),
(25, 'Nur Faim', '0409030', NULL, 5),
(26, 'Agus Dudianto', '0409045', NULL, 5),
(27, 'Subiyanto', '0409123', NULL, 5),
(28, 'Asep Mulyana', '0410179', NULL, 5),
(29, 'Ismid Rizani', '0412907', NULL, 5),
(30, 'Heriadi Idrus', '0410166', NULL, 5),
(31, 'Haryono', '0409012', NULL, 5),
(32, 'Agung Prayogo', '0409132', NULL, 5),
(33, 'Januar Aji Setiyoko', '0113959', NULL, 5),
(34, 'Adi Triyanto', '0113960', NULL, 5),
(35, 'Sahman', '0409073', NULL, 5),
(36, 'Yulia Fansisco', '0411638', NULL, 5),
(37, 'Sadri', '0414003', NULL, 5),
(38, 'Agus Setiono', '0415001', NULL, 5),
(39, 'Kisman', '0417015', NULL, 5),
(40, 'Edi Prantiyo', '0418003', NULL, 5),
(41, 'I Wayan Partaayasa', '0418006', NULL, 5),
(42, 'Kamaruddin', '0418008', NULL, 5),
(43, 'I Ketut Kaltim', '0418009', NULL, 5),
(44, 'Iswansyah', '0418010', NULL, 5),
(45, 'Krisganti', '0419014', NULL, 5),
(46, 'Aji Sutrisno', '0118003', NULL, 5);

-- --------------------------------------------------------

--
-- Table structure for table `divisi`
--

CREATE TABLE `divisi` (
  `id` int(11) NOT NULL,
  `nama_divisi` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `divisi`
--

INSERT INTO `divisi` (`id`, `nama_divisi`) VALUES
(1, 'Production Departmen Head'),
(2, 'Production Section Head'),
(3, 'Production Group Leader'),
(4, 'Production Admin'),
(5, 'Heavy Equipment Operator');

-- --------------------------------------------------------

--
-- Table structure for table `engine_operator`
--

CREATE TABLE `engine_operator` (
  `id` int(11) NOT NULL,
  `id_activity_operator` int(11) NOT NULL,
  `status_engine` int(11) NOT NULL,
  `activity_now` int(11) DEFAULT NULL,
  `status_now` int(11) DEFAULT NULL,
  `activity_time` time DEFAULT NULL,
  `status_time` time DEFAULT NULL,
  `all_productivity_unit` float DEFAULT NULL,
  `activity_productivity_unit` float DEFAULT NULL,
  `effectivness` float DEFAULT NULL,
  `metode` int(11) DEFAULT NULL,
  `muatan` int(11) DEFAULT NULL,
  `ritase_sebelum` float DEFAULT NULL,
  `ritase_sekarang` float DEFAULT NULL,
  `jam_engine` time NOT NULL,
  `jam_sekarang` time NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `material`
--

CREATE TABLE `material` (
  `kode_material` int(11) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `display` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `material`
--

INSERT INTO `material` (`kode_material`, `jenis`, `display`) VALUES
(1, 'NON RIPPING', 'NR'),
(2, 'NORMAL RIPPING', ' NORMAL RP'),
(3, 'BOULDER/HARD', 'HARD RP'),
(4, 'MUD', 'LUMPUR'),
(5, 'COAL', ' COAL'),
(6, 'NON RIPPING', 'NR ORI'),
(7, 'TOP SOIL', 'TOP SOIL');

-- --------------------------------------------------------

--
-- Table structure for table `metode`
--

CREATE TABLE `metode` (
  `id` int(11) NOT NULL,
  `metode` varchar(50) NOT NULL,
  `display` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `metode`
--

INSERT INTO `metode` (`id`, `metode`, `display`) VALUES
(1, 'NORMAL BENCH', 'NORMAL LOADING'),
(2, 'TOP LOADING', 'TOP LOADING'),
(3, 'DOUBLE BENCH', 'DOUBLE BENCH'),
(4, 'DOUBLE SIDE', 'DOUBLE SIDE');

-- --------------------------------------------------------

--
-- Table structure for table `muatan`
--

CREATE TABLE `muatan` (
  `id` int(11) NOT NULL,
  `kode` varchar(2) NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `muatan`
--

INSERT INTO `muatan` (`id`, `kode`, `status`) VALUES
(1, '11', '11 BCM'),
(2, '12', '12 BCM'),
(3, '14', '14 BCM');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `kode` varchar(4) NOT NULL,
  `status` varchar(50) NOT NULL,
  `activity` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `kode`, `status`, `activity`) VALUES
(1, '001', 'NO LOADING', 'BREAKDOWN DT'),
(2, '002', 'NO LOADING', 'BREAKDOWN EXCA'),
(3, '003', 'NO LOADING', 'EXCA DAILY MAINTENANCE'),
(4, '004', 'NO LOADING', 'CEK MEKANIK'),
(5, '005', 'NO LOADING', 'EXCA ISI FUEL'),
(6, '006', 'NO LOADING', 'PRAYING'),
(7, '007', 'NO LOADING', 'REST'),
(8, '008', 'NO LOADING', 'TUNGGU RIPPING'),
(9, '009', 'NO LOADING', 'DEMO WARGA'),
(10, '010', 'NO LOADING', 'FOGGY'),
(11, '011', 'NO LOADING', 'HUJAN'),
(12, '012', 'NO LOADING', 'SLIPPERY'),
(13, '013', 'NO LOADING', 'DEBU'),
(14, '014', 'NO LOADING', 'TUNGGU DT MUNDUR'),
(15, '015', 'NO LOADING', 'WASHING'),
(16, '016', 'NO LOADING', 'OPERATOR BAB'),
(17, '017', 'NO LOADING', 'ACCIDENT'),
(18, '018', 'NO LOADING', 'TUNGGU SURVEY'),
(19, '019', 'NO LOADING', 'LAINNYA'),
(20, '020', 'NO LOADING', 'LIPAT SEMENTARA'),
(21, '021', 'NO LOADING', 'PERBAIKAN FRONT - DOZER'),
(22, '022', 'NO LOADING', 'REHANDLING FRONT - DOZER'),
(23, '023', 'NO LOADING', 'PERBAIKAN FRONT - SENDIR'),
(24, '024', 'NO LOADING', 'REHANDLING FRONT - SENDIRI'),
(25, '025', 'NO LOADING', 'EVAKUASI UNIT'),
(26, '026', 'NO LOADING', 'TRAVEL (JAUH)'),
(27, '027', 'NO LOADING', 'PINDAH FRONT (DEKAT)'),
(28, '028', 'NO LOADING', 'WAIT DT'),
(29, '029', 'NO LOADING', 'TUNGGU LOKASI'),
(30, '030', 'NO LOADING', 'SAMBIL  GENERAL'),
(31, '031', 'NO LOADING', 'SAMBIL BLENDING'),
(32, '101', 'STATUS', 'DT KEROK VESEL'),
(33, '102', 'STATUS', 'DT MUNDUR JAUH'),
(34, '103', 'STATUS', 'DT REFUEL'),
(35, '104', 'STATUS', 'FRONT CROWDED'),
(36, '105', 'STATUS', 'FRONT LEMBEK'),
(37, '106', 'STATUS', 'FRONT MENANJAK'),
(38, '107', 'STATUS', 'FRONT SEMPIT'),
(39, '108', 'STATUS', 'EXCA PENGGANTI'),
(40, '109', 'STATUS', 'TEST FATIGUE'),
(41, '110', 'STATUS', 'ENGINE MATI/LIPAT'),
(42, '111', 'STATUS', 'GROUND TEST'),
(43, '112', 'STATUS', 'SAMBIL KEROK VESEL'),
(44, '113', 'STATUS', 'NORMAL LOADING'),
(45, '114', 'STATUS', 'DOUBLE BENCH'),
(46, '115', 'STATUS', 'TOP LOADING'),
(47, '116', 'STATUS', 'HARD MATERIAL'),
(48, '117', 'STATUS', 'LUMPUR'),
(49, '118', 'STATUS', 'STANDBY');

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE `unit` (
  `id` int(11) NOT NULL,
  `kode` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `unit`
--

INSERT INTO `unit` (`id`, `kode`) VALUES
(1, 'EX448'),
(2, 'EX423'),
(3, 'EX430'),
(4, 'EX432'),
(5, 'EX440'),
(6, 'EX443'),
(7, 'EX447');

-- --------------------------------------------------------

--
-- Table structure for table `user_level`
--

CREATE TABLE `user_level` (
  `id` int(11) NOT NULL,
  `ULEVEL` varchar(50) NOT NULL,
  `type` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_level`
--

INSERT INTO `user_level` (`id`, `ULEVEL`, `type`) VALUES
(1, '1', 'operator'),
(2, '2', 'pengawas'),
(3, '3', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_operator`
--
ALTER TABLE `activity_operator`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `app_user`
--
ALTER TABLE `app_user`
  ADD PRIMARY KEY (`UID`),
  ADD KEY `ULEVEL` (`ULEVEL`);

--
-- Indexes for table `detail_user`
--
ALTER TABLE `detail_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `divisi` (`divisi`),
  ADD KEY `fk_uid` (`UID`);

--
-- Indexes for table `divisi`
--
ALTER TABLE `divisi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `engine_operator`
--
ALTER TABLE `engine_operator`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`kode_material`);

--
-- Indexes for table `metode`
--
ALTER TABLE `metode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `muatan`
--
ALTER TABLE `muatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_level`
--
ALTER TABLE `user_level`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ULEVEL` (`ULEVEL`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_operator`
--
ALTER TABLE `activity_operator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `app_user`
--
ALTER TABLE `app_user`
  MODIFY `UID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `detail_user`
--
ALTER TABLE `detail_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `divisi`
--
ALTER TABLE `divisi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `engine_operator`
--
ALTER TABLE `engine_operator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material`
--
ALTER TABLE `material`
  MODIFY `kode_material` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `metode`
--
ALTER TABLE `metode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `muatan`
--
ALTER TABLE `muatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_level`
--
ALTER TABLE `user_level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_operator`
--
ALTER TABLE `activity_operator`
  ADD CONSTRAINT `activity_operator_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `app_user` (`UID`);

--
-- Constraints for table `app_user`
--
ALTER TABLE `app_user`
  ADD CONSTRAINT `app_user_ibfk_1` FOREIGN KEY (`ULEVEL`) REFERENCES `user_level` (`ULEVEL`);

--
-- Constraints for table `detail_user`
--
ALTER TABLE `detail_user`
  ADD CONSTRAINT `detail_user_ibfk_1` FOREIGN KEY (`divisi`) REFERENCES `divisi` (`id`),
  ADD CONSTRAINT `fk_uid` FOREIGN KEY (`UID`) REFERENCES `app_user` (`UID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

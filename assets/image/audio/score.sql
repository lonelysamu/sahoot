-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 09, 2021 at 03:25 AM
-- Server version: 10.5.12-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u772156354_mcmc`
--

-- --------------------------------------------------------

--
-- Table structure for table `score`
--

CREATE TABLE `score` (
  `s_id` int(11) NOT NULL,
  `s_phone` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `s_name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `s_score` int(11) NOT NULL DEFAULT 0,
  `s_set` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `s_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `score`
--

INSERT INTO `score` (`s_id`, `s_phone`, `s_name`, `s_score`, `s_set`, `s_date`) VALUES
(22, ' 60146012556', 'Quah Jit', 2, 'set1', '2021-12-09 02:03:52'),
(23, '0179148176', 'NOR HANIS ELLINNA BINTI ROSLY', 3, 'set1', '2021-12-09 02:06:59'),
(24, '0194437826', 'Intan zulaikha binti anuar', 0, 'set1', '2021-12-09 02:07:11'),
(25, '0193627276', 'Syafina', 4, 'set1', '2021-12-09 02:10:53'),
(26, '0123938740', 'Ellyssa Edrena', 2, 'set1', '2021-12-09 02:11:37'),
(27, '01126527288', 'Muhammad fakhrullah', 5, 'set1', '2021-12-09 02:19:16'),
(28, '0123448416', 'Shiela', 6, 'set1', '2021-12-09 02:23:36'),
(29, '0132645040', 'Zuriati binti Abdul Samat', 5, 'set1', '2021-12-09 02:26:48'),
(30, '0176294124', 'zafirah zafrullah', 5, 'set1', '2021-12-09 02:31:41'),
(31, '0192611068', 'Monday Rahmat Bin Khamis', 6, 'set1', '2021-12-09 02:32:03'),
(32, '01120761918', 'Maryam', 6, 'set1', '2021-12-09 02:35:33'),
(33, '0163191742', 'Reza', 6, 'set1', '2021-12-09 02:38:05'),
(34, '0163607193', 'Khalilurrahman', 6, 'set1', '2021-12-09 02:38:44'),
(35, '0182938891', 'Fatin athirah binti Ahmad roslan', 4, 'set1', '2021-12-09 02:43:58'),
(36, '0173628074', 'Muqri hakimi', 5, 'set1', '2021-12-09 02:44:24'),
(37, '0169035478', 'Nisa', 3, 'set1', '2021-12-09 02:47:43'),
(38, '01123589401', 'Amy hazirah', 4, 'set1', '2021-12-09 02:49:03'),
(39, '0137285859', 'Ainu Zahirah binti Ezmil Fikry', 2, 'set1', '2021-12-09 02:51:56'),
(40, '0188737220', 'Nur Alya Natasha', 1, 'set1', '2021-12-09 02:52:28'),
(41, '01430663378', 'Putri Azlan', 3, 'set1', '2021-12-09 02:52:48'),
(42, '01139169028', 'Nurul A’liah Balqis', 4, 'set1', '2021-12-09 02:53:00'),
(43, ' 60 17 708 0640', 'Noor atiqah', 7, 'set1', '2021-12-09 02:54:19'),
(44, '0182061284', 'Nurfaizah', 9, 'set1', '2021-12-09 02:54:33'),
(45, '0195429043', 'Yasmin', 3, 'set1', '2021-12-09 02:57:09'),
(46, '0125859841', 'Nur eizzaty batrisyia bt zaizalnizam', 4, 'set1', '2021-12-09 02:57:37'),
(47, '01140668781', 'Aina Batrisya', 7, 'set1', '2021-12-09 02:58:07'),
(48, '0133743450', 'Ainur Adani', 0, 'set1', '2021-12-09 02:58:23'),
(49, '0104307428', 'Nor Azizah', 5, 'set1', '2021-12-09 02:58:59'),
(50, '0182374846', 'Nur afifah', 5, 'set1', '2021-12-09 03:02:26'),
(51, '0183941927', 'Izzah Jefri', 6, 'set1', '2021-12-09 03:03:03'),
(52, '0172305080', 'nurul ashiqin', 7, 'set1', '2021-12-09 03:03:24'),
(53, '0102009425', 'Aleesa', 9, 'set1', '2021-12-09 03:03:54'),
(54, '0182411435', 'putri adlina', 9, 'set1', '2021-12-09 03:03:54'),
(55, '0194606136', 'Nur Dania ellysha', 5, 'set1', '2021-12-09 03:05:18'),
(56, '01133973680', 'Nur humairah hani', 6, 'set1', '2021-12-09 03:05:51'),
(57, '01116806585', 'Nik umairah hannan', 4, 'set1', '2021-12-09 03:06:25'),
(58, '01163950158', 'Muhammad Emirzaidi Bin Muhammad Effandi', 6, 'set1', '2021-12-09 03:08:39'),
(59, '0169638631', 'Nursahira', 5, 'set1', '2021-12-09 03:10:02'),
(60, '01398600995', 'Farahnwadhihah', 6, 'set1', '2021-12-09 03:10:57'),
(61, '01123433473', 'Aleeya maisara', 2, 'set1', '2021-12-09 03:15:03'),
(62, '0123135412', 'Shirley', 9, 'set1', '2021-12-09 03:15:52'),
(63, '0178843559', 'Nuraleeya Batrisyia Bt Zulkifli', 9, 'set1', '2021-12-09 03:16:01'),
(64, '01133475676', 'Norhaziqah Maisarah Bt Ahmad Hazarudin', 9, 'set1', '2021-12-09 03:16:01'),
(65, '0188744614', 'Nurul syifa binti ariffin', 9, 'set1', '2021-12-09 03:16:03'),
(66, '0165611189', 'Fadhilah bt azib', 8, 'set1', '2021-12-09 03:18:46'),
(67, '01165454964', 'Nur Adriana', 4, 'set1', '2021-12-09 03:19:46'),
(68, '0193640846', 'Harith', 4, 'set1', '2021-12-09 03:21:02'),
(69, '0109081424', 'NURUL AIMAN BINTI ABDUR RAHMAN', 8, 'set1', '2021-12-09 03:21:56'),
(70, '0193791621', 'Isma', 5, 'set1', '2021-12-09 03:22:48'),
(71, '01121172077', 'Danish', 5, 'set1', '2021-12-09 03:22:52'),
(72, '0178384351', 'NUR AZNIZA BINTI SUDIRMAN', 9, 'set1', '2021-12-09 03:23:10'),
(73, '01128112494', 'Nur Fatihah', 9, 'set1', '2021-12-09 03:23:11'),
(74, '0186628615', 'Nur sabrina binti saidi', 4, 'set1', '2021-12-09 03:23:58'),
(75, '0112489011', 'Khairun nisa azimi', 5, 'set1', '2021-12-09 03:24:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `score`
--
ALTER TABLE `score`
  ADD PRIMARY KEY (`s_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `score`
--
ALTER TABLE `score`
  MODIFY `s_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

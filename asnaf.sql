-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 12, 2023 at 05:54 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zis-app`
--

-- --------------------------------------------------------

--
-- Table structure for table `asnaf`
--

CREATE TABLE `asnaf` (
  `id` bigint UNSIGNED NOT NULL,
  `golongan_zakat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `asnaf`
--

INSERT INTO `asnaf` (`id`, `golongan_zakat`, `total`, `created_at`, `updated_at`) VALUES
(1, 'Zakat Fakir', 281250, NULL, '2023-08-12 05:53:38'),
(2, 'Zakat Miskin', 281250, NULL, '2023-08-12 05:53:38'),
(3, 'Zakat Amil', 281250, NULL, '2023-08-12 05:53:38'),
(4, 'Zakat mu\'allaf', 281250, NULL, '2023-08-12 05:53:38'),
(5, 'Zakat Riqab', 281250, NULL, '2023-08-12 05:53:38'),
(6, 'Zakat Gharimin', 281250, NULL, '2023-08-12 05:53:38'),
(7, 'Zakat Fisabilillah', 281250, NULL, '2023-08-12 05:53:38'),
(8, 'Zakat Ibnu Sabil', 281250, NULL, '2023-08-12 05:53:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `asnaf`
--
ALTER TABLE `asnaf`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `asnaf`
--
ALTER TABLE `asnaf`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

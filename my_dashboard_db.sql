-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 28, 2026 at 02:25 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_dashboard_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(5, '2026-01-27-051855', 'App\\Database\\Migrations\\CreateResearchesTable', 'default', 'App', 1769502772, 1),
(6, '2026-01-27-063038', 'App\\Database\\Migrations\\AddIsArchivedColumn', 'default', 'App', 1769502772, 1),
(7, '2026-01-27-070456', 'App\\Database\\Migrations\\AddAuthorColumn', 'default', 'App', 1769502772, 1),
(8, '2026-01-27-075724', 'App\\Database\\Migrations\\CreateUsersTable', 'default', 'App', 1769502772, 1);

-- --------------------------------------------------------

--
-- Table structure for table `researches`
--

CREATE TABLE `researches` (
  `id` int(5) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `abstract` text NOT NULL,
  `crop_type` varchar(100) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Under Review',
  `deadline_date` date DEFAULT NULL,
  `submitter_id` int(5) NOT NULL,
  `pdf_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_archived` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `researches`
--

INSERT INTO `researches` (`id`, `title`, `author`, `abstract`, `crop_type`, `status`, `deadline_date`, `submitter_id`, `pdf_path`, `created_at`, `is_archived`) VALUES
(1, 'admin', 'Dr. Santos', 're', 'Sweet Potato', 'Published', '2026-01-21', 0, '1769503405_7cb3ad84bbc0c7eebb7e.pdf', '2026-01-27 16:43:27', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `role` enum('Admin','Researcher','User') NOT NULL DEFAULT 'User',
  `department` varchar(150) DEFAULT NULL COMMENT 'e.g. Plant Breeding Dept, Soil Science',
  `bio` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL COMMENT 'Path to profile image',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `full_name`, `role`, `department`, `bio`, `avatar`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', 'admin@bsu.edu.ph', '$2y$10$7Idk93Y8DEyZ5LzcvJZ3ge3uDqmvgn1Lw.7yoYJAJB3rQP68RCZ4u', 'System Administrator', 'Admin', 'IT Department', 'Maintains the Root Crops Research Portal.', NULL, '2026-01-27 08:33:21', '2026-01-27 16:33:21', NULL),
(2, 'researcher', 'researcher@bsu.edu.ph', '$2y$10$qKLL/Uvqaua1Jt.XJ1/cI.JugesdH1kxJvs5egcqkIWr.wsR3QL/.', 'Dr. Juan Dela Cruz', 'Researcher', 'Root Crops Institute', 'Specialist in sweet potato genetic diversity.', NULL, '2026-01-27 08:33:21', '2026-01-27 16:33:21', NULL),
(3, 'student', 'student@bsu.edu.ph', '$2y$10$9p6okFCJ4U2stPjlvmZRi.WAhhrKx578EPUwqetApgjt9Or6oY1EK', 'Maria Clara', 'User', 'College of Agriculture', 'Undergraduate student researching taro pests.', NULL, '2026-01-27 08:33:21', '2026-01-27 16:33:21', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `researches`
--
ALTER TABLE `researches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `researches`
--
ALTER TABLE `researches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

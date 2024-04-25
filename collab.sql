-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2024 at 12:45 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `collab`
--

-- --------------------------------------------------------

--
-- Table structure for table `meeting_schedule`
--

CREATE TABLE `meeting_schedule` (
  `id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `meeting_date` int(11) DEFAULT NULL,
  `meeting_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meeting_schedule`
--

INSERT INTO `meeting_schedule` (`id`, `user_email`, `meeting_date`, `meeting_name`) VALUES
(9, 'mohdtaha9901@gmail.com', 25, 'Devday'),
(10, 'mohdtaha9901@gmail.com', 25, 'Devday'),
(11, 'kh9100820@gmail.com', 9, 'devday');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `org_name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `role` varchar(50) NOT NULL,
  `member_email` varchar(255) DEFAULT NULL,
  `invitation_code` int(11) DEFAULT NULL,
  `admin_email` varchar(255) NOT NULL,
  `completion_status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `project_name`, `org_name`, `date`, `role`, `member_email`, `invitation_code`, `admin_email`, `completion_status`) VALUES
(1, 'Devday3', 'FAST', '2024-04-24', 'admin', 'mohdtaha9901@gmail.com', 574691, 'kh9100820@gmail.com', NULL),
(2, 'weekly test', 'Aptech', '2024-04-24', 'admin', 'mohdtaha9901@gmail.com', NULL, 'kh9100820@gmail.com', NULL),
(3, 'weekly test 2', 'Aptech', '2024-04-24', 'admin', NULL, NULL, 'kh9100820@gmail.com', NULL),
(4, 'weekly test 3', 'Aptech', '2024-04-24', 'admin', NULL, NULL, 'kh9100820@gmail.com', NULL),
(5, 'weekly test 4', 'Aptech', '2024-04-24', 'admin', NULL, NULL, 'kh9100820@gmail.com', NULL),
(6, 'Devday3', 'FAST', '2024-04-24', 'member', 'mohdtaha9901@gmail.com', NULL, 'kh9100820@gmail.com', NULL),
(7, 'Devday', 'FAST111', '2024-04-24', 'admin', NULL, NULL, 'mohdtaha9901@gmail.com', '1'),
(11, 'Devday24', 'FAST', '2024-04-25', 'admin', NULL, NULL, 'mohdtaha9901@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project_detail`
--

CREATE TABLE `project_detail` (
  `id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `files` text DEFAULT NULL,
  `images` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_detail`
--

INSERT INTO `project_detail` (`id`, `project_id`, `user_email`, `comments`, `files`, `images`) VALUES
(1, 0, 'mohdtaha9901@gmail.com', 'Hello Everyone.', NULL, NULL),
(4, 7, 'mohdtaha9901@gmail.com', 'to rori waly or kia lega re', NULL, NULL),
(5, 7, 'mohdtaha9901@gmail.com', 'albele tange wale', NULL, NULL),
(7, 7, 'mohdtaha9901@gmail.com', 'I am the BOSS', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project_detail_backup`
--

CREATE TABLE `project_detail_backup` (
  `id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `files` text DEFAULT NULL,
  `images` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_detail_backup`
--

INSERT INTO `project_detail_backup` (`id`, `project_id`, `user_email`, `comments`, `files`, `images`) VALUES
(1, 0, 'mohdtaha9901@gmail.com', 'Hello Everyone.', NULL, NULL),
(4, 7, 'mohdtaha9901@gmail.com', 'to rori waly or kia lega re', NULL, NULL),
(5, 7, 'mohdtaha9901@gmail.com', 'albele tange wale', NULL, NULL),
(7, 7, 'mohdtaha9901@gmail.com', 'I am the BOSS', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `otp` int(6) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `google_id`, `created_at`, `otp`, `verified`) VALUES
(1, 'Taha Farooqui', 'mohdtaha9901@gmail.com', '$2y$10$UWjXs6uyGa03AZbJ9jlRZO1oLWAMhkV.xwVJg4wRegJjz1ztWeZby', NULL, '2024-04-23 11:50:32', 0, 1),
(2, 'ahzam', 'maaniahzam@outlook.com', '$2y$10$kEaE15cBZJoLFggpsYPPXOS7Hnd5frQUpQU8JUK222uDg0RX8Rkta', NULL, '2024-04-24 06:17:46', NULL, 0),
(3, 'Khizar', 'kh9100820@gmail.com', '$2y$10$RiLc2oXWnG1f3iCgLgurOO71WHoO2T5Rmu00Muw70oJ5f8lEG.H3S', NULL, '2024-04-24 16:53:06', 118892, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `meeting_schedule`
--
ALTER TABLE `meeting_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_detail`
--
ALTER TABLE `project_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_detail_backup`
--
ALTER TABLE `project_detail_backup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `meeting_schedule`
--
ALTER TABLE `meeting_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `project_detail`
--
ALTER TABLE `project_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `project_detail_backup`
--
ALTER TABLE `project_detail_backup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

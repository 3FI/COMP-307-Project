-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 29, 2023 at 02:21 AM
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
-- Database: `comp307-project`
--

-- --------------------------------------------------------

--
-- Table structure for table `boards`
--

CREATE TABLE `boards` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(150) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `color` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `boards`
--

INSERT INTO `boards` (`id`, `name`, `description`, `admin_id`, `color`) VALUES
(69, 'COMP 307', 'Principles Of Web Development', 8, '#000000'),
(70, 'COMP 421', 'Database Systems', 8, '#000000'),
(77, 'Hockey Fantasy', 'Trades, Matchups And More!', 21, '#9d9933'),
(78, 'Ligue Des Anciens', 'None', 21, '#91bc56');

-- --------------------------------------------------------

--
-- Table structure for table `board_access`
--

CREATE TABLE `board_access` (
  `user_id` int(11) NOT NULL,
  `board_id` int(11) NOT NULL,
  `subscribed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `board_access`
--

INSERT INTO `board_access` (`user_id`, `board_id`, `subscribed`) VALUES
(8, 69, 0),
(8, 70, 0),
(21, 77, 0),
(21, 78, 0);

-- --------------------------------------------------------

--
-- Table structure for table `channels`
--

CREATE TABLE `channels` (
  `id` int(11) NOT NULL,
  `board_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `channels`
--

INSERT INTO `channels` (`id`, `board_id`, `name`) VALUES
(1, 69, 'MyChannel'),
(2, 69, 'MyChannel'),
(3, 69, 'my channel'),
(4, 69, 'my channel'),
(5, 70, 'my channel'),
(6, 70, 'TESTNAME'),
(7, 70, 'TESTNAME'),
(8, 70, 'TESTNAME'),
(9, 70, 'TESTNAME'),
(10, 70, 'TESTNAME'),
(11, 70, 'TESTNAME'),
(12, 70, 'TESTNAME'),
(13, 70, 'TESTNAME'),
(14, 70, 'TESTNAME'),
(15, 77, 'trades'),
(16, 77, 'players'),
(18, 77, 'teams'),
(19, 78, 'General');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(2000) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `is_pinned` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `channel_id`, `user_id`, `message`, `date`, `is_pinned`) VALUES
(1, 5, 8, 'test message', '2023-11-26 11:40:05', 0),
(2, 5, 8, 'test message', '2023-11-26 11:40:10', 0),
(3, 1, 9, 'abc', '2023-11-26 11:41:13', 0),
(4, 2, 8, 'aaaa', '2023-11-26 11:53:47', 0),
(5, 5, 8, 'abcdefghijk', '2023-11-26 12:07:28', 0),
(6, 5, 8, 'TESTMESSAGE', '2023-11-26 12:28:41', 0),
(7, 15, 21, 'hey', '2023-11-28 17:50:38', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `ticket` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `username`, `ticket`) VALUES
(8, 'test@test', '$2y$10$GpzPGuMXcp/8pxTCBeibgeqitsYLujLM8O6tjyLqeZMqQmeQbiE0O', 'test@test', 41355254),
(9, 'abc@abc', '$2y$10$Dwict0UMqLFmObpmGKoHpOHtE8Pq4HWL6OxG0dQ2GBbfF91heVpf.', 'abc@abc', NULL),
(21, 'louisantoine.habre@gmail.com', '$2y$10$fo0iQfoq57Ed/ctx5xJuM.Va0THKWQiF.0FEofwEKt.yrIliPhe82', 'louisantoine.habre@gmail.com', 49717012);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `boards`
--
ALTER TABLE `boards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `board_access`
--
ALTER TABLE `board_access`
  ADD PRIMARY KEY (`user_id`,`board_id`),
  ADD KEY `board-access_ibfk_1` (`board_id`);

--
-- Indexes for table `channels`
--
ALTER TABLE `channels`
  ADD PRIMARY KEY (`id`,`board_id`),
  ADD KEY `board` (`board_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`,`channel_id`) USING BTREE,
  ADD KEY `channel_id` (`channel_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `boards`
--
ALTER TABLE `boards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `channels`
--
ALTER TABLE `channels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `boards`
--
ALTER TABLE `boards`
  ADD CONSTRAINT `boards_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `board_access`
--
ALTER TABLE `board_access`
  ADD CONSTRAINT `board_access_ibfk_1` FOREIGN KEY (`board_id`) REFERENCES `boards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `board_access_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `channels`
--
ALTER TABLE `channels`
  ADD CONSTRAINT `channels_ibfk_1` FOREIGN KEY (`board_id`) REFERENCES `boards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

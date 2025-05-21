-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2025 at 02:10 PM
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
-- Database: `accgroup8`
--

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tripName` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `notes` text NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `region` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `activity` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trips`
--

INSERT INTO `trips` (`id`, `user_id`, `tripName`, `destination`, `start_date`, `end_date`, `notes`, `create_at`, `region`, `city`, `activity`) VALUES
(10, 25, 'Philippine Adventure', 'Boracay', '2025-05-24', '2025-06-11', '', '2025-05-20 08:44:17', 'Asia', 'Manila', 'Paragliding, Hot Air Balloon, Wildlife Safari, Jungle Trekking'),
(11, 25, 'Philippine Adventure', 'Boracay', '2025-06-12', '2025-07-11', '', '2025-05-20 09:46:53', 'Asia', 'Manila', 'Scuba Diving, Paragliding, Survival Training');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `failed_attempts` int(11) NOT NULL DEFAULT 0,
  `last_failed_login` datetime DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `failed_attempts`, `last_failed_login`, `reset_token`, `reset_expires`) VALUES
(15, 'Tester12345', 'Dec12345@gmail.com', '$2y$10$jXumFh.7No6NHGbnqxCw7.lSyWHHPuPO7hngUKywOTB4rFLlG.2Z.', '2025-05-15 11:18:15', 1, '2025-05-15 19:25:45', 'd46c5f46d60b42c9abe8edda2dbc06f2', '2025-05-15 20:24:05'),
(17, 'KurtObciana', 'kurtobciana@yahoo.com', '$2y$10$dCXE36wZva9cj/NTKFAq2uYwNxGxirSgp.XnXTU8VMx1wx9plqypm', '2025-05-15 11:32:37', 0, NULL, 'a44936e4e13d4df924f29f99a569af06', '2025-05-20 02:47:43'),
(18, 'IgyB30', 'asd@gmial.com', '$2y$10$XGhuFoS7hayno4R5Efw9WO3BSA3Wp6y0/rLgzxVEekJXCh6xY20gq', '2025-05-15 11:33:00', 0, NULL, NULL, NULL),
(19, 'Tester1', 'tester@gmail.com', '$2y$10$kmxIyfrkuNYn1PYhPZ5JAOR5uMlU1A9vvD30ENBKUKh9oKoXv2C/.', '2025-05-15 11:37:05', 0, NULL, NULL, NULL),
(20, 'Tester2', 'tester2@gmail.com', '$2y$10$iabeChonOYxfELDwZVRyAerfrueLfbsgvTn6toMBxr7Y06tsjCBd2', '2025-05-15 11:37:42', 0, NULL, NULL, NULL),
(24, 'Tester3', 'tester3@gmil.com', '$2y$10$HB77eYT8L.R6gg769/I/0uvJOVK83T9DUydr36If5UeJARJjxuWTW', '2025-05-15 11:49:31', 0, NULL, NULL, NULL),
(25, 'Tester5', 'tester5@gmail.com', '$2y$10$DYc.4/7U/.hfq/.N3xbaqeDQ/Oih68ObQSQ/i6F27Ojm2E9AvEAjS', '2025-05-15 12:34:43', 0, NULL, NULL, NULL),
(26, 'Darz1Panot', 'darzpanot@gmail.com', '$2y$10$u1EpFjtP5Wa..3jSsaduxew/ifX/ptL1h21Pgdaezw1hC4.yytrZS', '2025-05-19 17:46:28', 0, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

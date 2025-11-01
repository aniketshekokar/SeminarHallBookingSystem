-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 01, 2025 at 07:32 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `seminar_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hall_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `hall_id`, `date`, `start_time`, `end_time`, `status`, `created_at`) VALUES
(1, 6, 1, '2025-11-01', '08:09:00', '09:00:00', 'approved', '2025-10-31 06:40:06'),
(2, 6, 2, '2025-10-10', '10:00:00', '12:00:00', 'pending', '2025-10-31 06:44:00'),
(3, 6, 3, '2025-10-10', '11:00:00', '12:00:00', 'rejected', '2025-10-31 06:51:19'),
(4, 6, 1, '2025-10-03', '11:00:00', '12:00:00', 'pending', '2025-10-31 08:00:10'),
(5, 6, 2, '2025-10-10', '10:00:00', '12:00:00', 'pending', '2025-10-31 12:53:11');

-- --------------------------------------------------------

--
-- Table structure for table `halls`
--

CREATE TABLE `halls` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `capacity` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `halls`
--

INSERT INTO `halls` (`id`, `name`, `capacity`, `location`, `description`, `created_at`) VALUES
(1, 'Auditorium A', 200, 'Main Building', 'Main seminar hall for large events', '2025-10-31 06:05:13'),
(2, 'Conference Room 1', 50, 'Block B', 'Room for departmental meetings', '2025-10-31 06:05:13'),
(3, 'Mini Hall', 30, 'Block C', 'For student seminars or small gatherings', '2025-10-31 06:05:13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(2, 'Priya Sharma', 'priya.sharma@example.com', '$2y$10$LhQZzGh4fCkRjhSrlKHPae5kz.63U9T0eK0/6DL57HZ2iz5iDbF/K', 'user', '2025-10-31 06:11:32'),
(3, 'Rahul Verma', 'rahul.verma@example.com', '$2y$10$LhQZzGh4fCkRjhSrlKHPae5kz.63U9T0eK0/6DL57HZ2iz5iDbF/K', 'user', '2025-10-31 06:11:32'),
(4, 'Neha Patil', 'neha.patil@example.com', '$2y$10$LhQZzGh4fCkRjhSrlKHPae5kz.63U9T0eK0/6DL57HZ2iz5iDbF/K', 'user', '2025-10-31 06:11:32'),
(5, 'Admin User', 'aniketshekokar92@gmail.com', '$2y$10$wWM6p3/cLht52s5Sq.wP0.d9ygtg06Szb9iLlONIBY6ria9stM28m', 'admin', '2025-10-31 06:24:47'),
(6, 'Yuvraj Surshetwar', 'yuvrajsurshetwar@gmail.com', '$2y$10$6vdLgbZQnakAy9p0GCsPfuuVkZkjwziV98RJYwlUf58L7EVBIHteO', 'user', '2025-10-31 06:39:20'),
(7, 'sakshi jagtap', 'sakshi@example.com', '$2y$10$CY5O9BB7zlnjW7rF/zC0M.Y892qNADigfRD4tzo88jtjZKJnp4PA.', 'user', '2025-10-31 06:42:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `hall_id` (`hall_id`);

--
-- Indexes for table `halls`
--
ALTER TABLE `halls`
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
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `halls`
--
ALTER TABLE `halls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`hall_id`) REFERENCES `halls` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

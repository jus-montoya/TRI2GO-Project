-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Mar 27, 2026 at 04:36 AM
-- Server version: 12.2.2-MariaDB-ubu2404
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tri2go_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `pickup_lat` decimal(11,8) NOT NULL,
  `pickup_lng` decimal(11,8) NOT NULL,
  `dest_lat` decimal(11,8) NOT NULL,
  `dest_lng` decimal(11,8) NOT NULL,
  `pax_count` int(11) DEFAULT 1,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('searching','accepted','completed','cancelled') DEFAULT 'searching',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `driver_id`, `pickup_lat`, `pickup_lng`, `dest_lat`, `dest_lng`, `pax_count`, `total_price`, `status`, `created_at`) VALUES
(1, 1, 1, 15.06910000, 120.53900000, 15.06910000, 120.54300000, 1, 20.00, 'completed', '2026-03-27 04:07:23'),
(2, 3, 3, 15.06910000, 120.53900000, 15.06910000, 120.54300000, 1, 20.00, 'completed', '2026-03-27 04:17:43');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `brand_id` int(11) NOT NULL,
  `brand_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brand_id`, `brand_name`) VALUES
(1, 'Honda'),
(3, 'Kawasaki'),
(4, 'Suzuki'),
(2, 'Yamaha');

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `driver_id` int(11) NOT NULL,
  `toda_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `plate_number` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`driver_id`, `toda_id`, `brand_id`, `full_name`, `phone_number`, `plate_number`, `password`, `created_at`) VALUES
(1, 1, 1, 'Iyan Aliman', '0123456', 'ABC 123', '$2y$10$IQXNyTCnKNQfoWM.uctDTeIBRuqw7QNY/Lv8v7sujgem6P5RRZSti', '2026-03-27 04:07:12'),
(3, 1, 2, 'Rainier Evaristo', '0123456789', 'ROV 123', '$2y$10$lmlhzMm5I5ZM.fPwBzI4Ru2Uw4rQzISCmNIbs9W8ZP24/Q2AbcUQS', '2026-03-27 04:17:19');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `booking_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 5, 'NICE', '2026-03-27 04:07:44'),
(2, 2, 5, 'NICE', '2026-03-27 04:17:58');

-- --------------------------------------------------------

--
-- Table structure for table `todas`
--

CREATE TABLE `todas` (
  `toda_id` int(11) NOT NULL,
  `toda_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `todas`
--

INSERT INTO `todas` (`toda_id`, `toda_name`) VALUES
(2, 'Cutcut'),
(3, 'Lourdes Sur East'),
(1, 'Tokwing');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `phone_number`, `password`, `created_at`) VALUES
(1, 'iyan aliman', '0123456', '$2y$10$.yN7fbY8Cz29jHDbe/YKg.vu9GLO/aDnFxerWg5RymLX2NrdBBbRe', '2026-03-27 04:06:51'),
(2, 'Steven Timbol', '0987654321', '$2y$10$ByrnXGjdHvOIY28APc4/NOD6ni3tCSOofkLC7xWuK3o3HAi3Stfma', '2026-03-27 04:13:31'),
(3, 'Rainier Evarista', '0123456789', '$2y$10$33sOYvEVAcTO4XoqHxCMV.CwSghPEYnmk31COVzvjPEvuhd6Ab2gu', '2026-03-27 04:15:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`),
  ADD UNIQUE KEY `brand_name` (`brand_name`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`driver_id`),
  ADD UNIQUE KEY `phone_number` (`phone_number`),
  ADD UNIQUE KEY `plate_number` (`plate_number`),
  ADD KEY `toda_id` (`toda_id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD UNIQUE KEY `booking_id` (`booking_id`);

--
-- Indexes for table `todas`
--
ALTER TABLE `todas`
  ADD PRIMARY KEY (`toda_id`),
  ADD UNIQUE KEY `toda_name` (`toda_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `phone_number` (`phone_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `driver_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `todas`
--
ALTER TABLE `todas`
  MODIFY `toda_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `2` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`driver_id`);

--
-- Constraints for table `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `1` FOREIGN KEY (`toda_id`) REFERENCES `todas` (`toda_id`),
  ADD CONSTRAINT `2` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`brand_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

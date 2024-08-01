-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 01, 2024 at 08:28 AM
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
-- Database: `bike_company`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin1', 'password1'),
(2, 'admin2', 'password2');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `model_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `dl_info` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `engine_specs`
--

CREATE TABLE `engine_specs` (
  `id` int(11) NOT NULL,
  `model_id` int(11) DEFAULT NULL,
  `cc` int(11) DEFAULT NULL,
  `bhp` int(11) DEFAULT NULL,
  `torque` varchar(50) DEFAULT NULL,
  `cylinders` int(11) DEFAULT NULL,
  `transmission` varchar(50) DEFAULT NULL,
  `fuel_tank_capacity` float DEFAULT NULL,
  `kerb_weight` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `engine_specs`
--

INSERT INTO `engine_specs` (`id`, `model_id`, `cc`, `bhp`, `torque`, `cylinders`, `transmission`, `fuel_tank_capacity`, `kerb_weight`) VALUES
(1, 14, 998, 208, '116', 4, '6', 16, 210),
(4, 72, 998, 208, '116', 4, '6 speed manual', 16, 186),
(5, 73, 798, 148, '88', 3, '6 speed manual', 16, 173),
(6, 74, 798, 148, '88', 3, '6 speed manual', 16, 173),
(7, 75, 998, 208, '116', 4, '6 speed manual', 16, 196),
(8, 76, 798, 110, '80', 3, '6 speed manual', 22, 199),
(9, 77, 798, 140, '87', 3, '6 speed manual', 16, 175),
(10, 78, 798, 150, '87', 3, '6 speed manual', 16, 168);

-- --------------------------------------------------------

--
-- Table structure for table `models`
--

CREATE TABLE `models` (
  `id` int(11) NOT NULL,
  `model_name` varchar(255) NOT NULL,
  `model_details` text NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `models`
--

INSERT INTO `models` (`id`, `model_name`, `model_details`, `image_path`) VALUES
(14, 'Rush', 'Feel the rush', 'assets/images/rush.jpg'),
(72, 'Brutale 1000 RR', 'The Hyper Naked Bike', 'assets/images/brutale1000rr.jpg'),
(73, 'F3 RC 800', 'The Supersport Revolution', 'assets/images/f3rc800.jpg'),
(74, 'Superveloce S', 'Classic Racing Spirit', 'assets/images/superveloces.jpeg'),
(75, 'Superveloce 1000', 'Ultimate Retro Performance', 'assets/images/superveloce1000.jpeg'),
(76, 'Turismo Veloce Lusso', 'Luxury Touring Redefined', 'assets/images/velocelusso.jpg'),
(77, 'Brutale 800 RR', 'Power and Precision', 'assets/images/brutalerr800.jpg'),
(78, 'Dragster 800 RC SCS', 'Racing Technology for the Street', 'assets/images/dragsterrcscs.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_ibfk_1` (`model_id`);

--
-- Indexes for table `engine_specs`
--
ALTER TABLE `engine_specs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `model_id` (`model_id`);

--
-- Indexes for table `models`
--
ALTER TABLE `models`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `engine_specs`
--
ALTER TABLE `engine_specs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `models`
--
ALTER TABLE `models`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`model_id`) REFERENCES `models` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `engine_specs`
--
ALTER TABLE `engine_specs`
  ADD CONSTRAINT `engine_specs_ibfk_1` FOREIGN KEY (`model_id`) REFERENCES `models` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

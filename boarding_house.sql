-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2024 at 11:00 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `boarding_house`
--

-- --------------------------------------------------------

--
-- Table structure for table `outstanding_balance`
--

CREATE TABLE `outstanding_balance` (
  `id` int(11) NOT NULL,
  `rental_balance` decimal(10,2) NOT NULL,
  `appliance_balance` decimal(10,2) NOT NULL,
  `total_balance` decimal(10,2) NOT NULL,
  `tenant_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `outstanding_balance`
--

INSERT INTO `outstanding_balance` (`id`, `rental_balance`, `appliance_balance`, `total_balance`, `tenant_id`) VALUES
(657, -600.00, 150.00, -450.00, 58),
(658, -600.00, 150.00, -450.00, 58),
(659, -600.00, 150.00, -450.00, 58),
(660, 0.00, 150.00, 150.00, 59),
(661, -600.00, 150.00, -450.00, 58),
(662, -600.00, 150.00, -450.00, 58),
(663, 0.00, 150.00, 150.00, 59),
(664, -600.00, 150.00, -450.00, 58),
(665, 0.00, 150.00, 150.00, 59),
(666, -600.00, 150.00, -450.00, 58),
(667, 0.00, 150.00, 150.00, 59),
(668, -600.00, 150.00, -450.00, 58),
(669, 0.00, 150.00, 150.00, 59),
(670, -600.00, 150.00, -450.00, 58),
(671, 0.00, 150.00, 150.00, 59),
(672, -600.00, 150.00, -450.00, 58),
(673, 0.00, 150.00, 150.00, 59),
(674, -600.00, 150.00, -450.00, 58),
(675, 0.00, 150.00, 150.00, 59),
(676, -600.00, 150.00, -450.00, 58),
(677, 0.00, 150.00, 150.00, 59),
(678, -600.00, 150.00, -450.00, 58),
(679, 0.00, 150.00, 150.00, 59),
(680, -600.00, 150.00, -450.00, 58),
(681, 0.00, 150.00, 150.00, 59),
(682, -600.00, 150.00, -450.00, 58),
(683, 0.00, 150.00, 150.00, 59),
(684, -600.00, 150.00, -450.00, 58),
(685, 0.00, 150.00, 150.00, 59),
(686, -600.00, 150.00, -450.00, 58),
(687, 0.00, 150.00, 150.00, 59),
(688, -600.00, 150.00, -450.00, 58),
(689, 0.00, 150.00, 150.00, 59),
(690, -600.00, 150.00, -450.00, 58),
(691, 0.00, 150.00, 150.00, 59),
(692, -600.00, 150.00, -450.00, 58),
(693, 0.00, 150.00, 150.00, 59),
(694, -600.00, 150.00, -450.00, 58),
(695, 0.00, 150.00, 150.00, 59),
(696, -600.00, 150.00, -450.00, 58),
(697, 0.00, 150.00, 150.00, 59),
(698, -600.00, 150.00, -450.00, 58),
(699, 0.00, 150.00, 150.00, 59),
(700, -600.00, 150.00, -450.00, 58),
(701, 0.00, 150.00, 150.00, 59),
(702, -600.00, 150.00, -450.00, 58),
(703, 0.00, 150.00, 150.00, 59),
(704, -600.00, 150.00, -450.00, 58),
(705, 0.00, 150.00, 150.00, 59);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `room_no` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `due_date` datetime NOT NULL,
  `appliance_fee` double NOT NULL,
  `rent_payment` double NOT NULL,
  `total_amount` double NOT NULL,
  `price` double NOT NULL,
  `appliance_payment` double NOT NULL,
  `appliance_paid` double NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `room_no`, `tenant_id`, `date_created`, `due_date`, `appliance_fee`, `rent_payment`, `total_amount`, `price`, `appliance_payment`, `appliance_paid`, `start_date`, `end_date`) VALUES
(46, 0, 58, '2024-05-16 14:07:28', '0000-00-00 00:00:00', 0, 600, 750, 0, 150, 0, '2024-04-16', '2024-05-16'),
(47, 0, 59, '2024-05-16 14:53:29', '0000-00-00 00:00:00', 0, 1200, 1350, 0, 150, 0, '2024-04-23', '2024-05-23');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `id` int(11) NOT NULL,
  `room_no` int(11) NOT NULL,
  `price` double NOT NULL,
  `occupancy` int(11) NOT NULL,
  `availability` int(11) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`id`, `room_no`, `price`, `occupancy`, `availability`, `status`) VALUES
(1, 1, 1200, 0, 0, ''),
(2, 2, 1200, 0, 0, ''),
(4, 3, 1200, 0, 0, ''),
(7, 4, 1200, 0, 0, ''),
(8, 5, 1200, 0, 0, ''),
(12, 6, 1200, 0, 0, ''),
(13, 7, 1200, 0, 0, ''),
(15, 8, 1200, 0, 0, ''),
(16, 9, 1200, 0, 0, ''),
(19, 10, 1200, 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`) VALUES
(1, 'Susan\'s Boarding House Rental Management System', 'susansboardinghouse@gmail.com', '+6948 8542 623');

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `address` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = active, 0= inactive',
  `appliances` varchar(100) NOT NULL,
  `total_items` int(11) NOT NULL,
  `date_in` date NOT NULL,
  `due_day` int(2) NOT NULL,
  `email_sent` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `room_id`, `firstname`, `middlename`, `lastname`, `gender`, `address`, `email`, `status`, `appliances`, `total_items`, `date_in`, `due_day`, `email_sent`) VALUES
(58, 2, 'Ji', 'Jun', 'Hyun', 'Female', 'Brgy. Lamcaliaf, Polomolok, South Cotabato', 'junjihyun699@gmail.com', 1, 'rice cooker, electric fan', 2, '2024-04-25', 25, 'Sent'),
(59, 1, 'cutie', 'dela rosa', 'dagum', 'Female', 'Brgy. Lamcaliaf, Polomolok, South Cotabato', 'sagarinosheprilheart@gmail.com', 1, 'rice cooker', 1, '2024-04-25', 25, 'Sent'),
(60, 7, 'gddhd', 'suus', 'josheyy', 'Female', 'aedvsg', 'sagarinosheprilheart@gmail.com', 0, 'rice cooker', 1, '2024-04-25', 25, '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1=Admin,2=Staff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `type`) VALUES
(1, 'Administrator', 'admin', '0192023a7bbd73250516f069df18b500', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `outstanding_balance`
--
ALTER TABLE `outstanding_balance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `outstanding_balance`
--
ALTER TABLE `outstanding_balance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=706;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

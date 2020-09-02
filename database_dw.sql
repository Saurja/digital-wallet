-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 02, 2020 at 04:39 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `digital-wallet`
--

-- --------------------------------------------------------

--
-- Table structure for table `credit_requests`
--

CREATE TABLE `credit_requests` (
  `req_id` int(11) NOT NULL,
  `req_from` int(11) NOT NULL,
  `send_from` int(11) NOT NULL,
  `credits_requested` int(11) NOT NULL,
  `req_dateTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_table`
--

CREATE TABLE `transaction_table` (
  `transaction_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `transaction_date` datetime NOT NULL,
  `transaction_amount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaction_table`
--

INSERT INTO `transaction_table` (`transaction_id`, `sender_id`, `receiver_id`, `transaction_date`, `transaction_amount`) VALUES
(1, 2, 1, '2020-08-28 10:32:40', 160),
(2, 1, 2, '2020-08-28 10:36:21', 65),
(3, 1, 2, '2020-08-28 11:05:42', 5),
(4, 1, 2, '2020-08-28 03:37:43', 160),
(5, 2, 1, '2020-08-28 03:38:01', 165),
(6, 1, 2, '2020-08-28 03:39:30', 65),
(7, 1, 2, '2020-08-28 06:01:40', 160),
(8, 1, 2, '2020-08-28 06:01:43', 33),
(9, 1, 2, '2020-08-28 06:01:54', 32),
(10, 2, 1, '2020-08-28 06:08:48', 5),
(11, 1, 2, '2020-08-28 06:09:13', 160),
(12, 2, 1, '2020-08-28 06:40:42', 160),
(13, 1, 2, '2020-08-28 11:47:19', 160),
(14, 1, 2, '2020-08-28 11:50:57', 160),
(15, 1, 2, '2020-08-28 11:51:43', 80),
(16, 1, 2, '2020-08-28 11:51:46', 65),
(17, 1, 2, '2020-08-28 11:51:47', 35),
(18, 1, 2, '2020-08-28 11:54:26', 160),
(19, 2, 1, '2020-08-28 11:55:01', 245),
(20, 1, 2, '2020-08-28 11:55:32', 65);

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `user_ID` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `contact_no` varchar(12) NOT NULL,
  `email_id` varchar(255) NOT NULL,
  `credits` float NOT NULL,
  `last_activity` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `counter` int(14) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`user_ID`, `user_name`, `contact_no`, `email_id`, `credits`, `last_activity`, `counter`) VALUES
(1, 'takki', '8334050361', 'Takki@gmail.com', 5, '2020-08-28 18:25:32', 15),
(2, 'Mitsua', '09445217451', 'Mitsua@gmail.com', 70, '2020-08-28 18:25:32', 9);

-- --------------------------------------------------------

--
-- Table structure for table `voucher_table`
--

CREATE TABLE `voucher_table` (
  `voucher_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `voucher_amount` float NOT NULL,
  `voucher_code` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `voucher_table`
--

INSERT INTO `voucher_table` (`voucher_id`, `sender_id`, `voucher_amount`, `voucher_code`) VALUES
(46, 2, 160, 'eXobOBqp'),
(47, 2, 160, 'dQqSysvG');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `credit_requests`
--
ALTER TABLE `credit_requests`
  ADD PRIMARY KEY (`req_id`),
  ADD KEY `req_from` (`req_from`),
  ADD KEY `send_from` (`send_from`);

--
-- Indexes for table `transaction_table`
--
ALTER TABLE `transaction_table`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`user_ID`),
  ADD UNIQUE KEY `user_name` (`user_name`),
  ADD UNIQUE KEY `contact_no` (`contact_no`),
  ADD UNIQUE KEY `email_id` (`email_id`);

--
-- Indexes for table `voucher_table`
--
ALTER TABLE `voucher_table`
  ADD PRIMARY KEY (`voucher_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `credit_requests`
--
ALTER TABLE `credit_requests`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `transaction_table`
--
ALTER TABLE `transaction_table`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `user_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `voucher_table`
--
ALTER TABLE `voucher_table`
  MODIFY `voucher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `credit_requests`
--
ALTER TABLE `credit_requests`
  ADD CONSTRAINT `credit_requests_ibfk_1` FOREIGN KEY (`req_from`) REFERENCES `user_details` (`user_ID`),
  ADD CONSTRAINT `credit_requests_ibfk_2` FOREIGN KEY (`send_from`) REFERENCES `user_details` (`user_ID`);

--
-- Constraints for table `transaction_table`
--
ALTER TABLE `transaction_table`
  ADD CONSTRAINT `transaction_table_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `user_details` (`user_ID`),
  ADD CONSTRAINT `transaction_table_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `user_details` (`user_ID`);

--
-- Constraints for table `voucher_table`
--
ALTER TABLE `voucher_table`
  ADD CONSTRAINT `voucher_table_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `user_details` (`user_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

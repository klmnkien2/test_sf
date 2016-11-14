-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2016 at 10:59 AM
-- Server version: 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `c5`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachment`
--

CREATE TABLE IF NOT EXISTS `attachment` (
  `id` int(11) NOT NULL,
  `name` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `refer_id` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '1-dispute'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bank_acc_log`
--

CREATE TABLE IF NOT EXISTS `bank_acc_log` (
  `id` int(11) NOT NULL,
  `vcb_acc_number` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `count_gd` int(11) DEFAULT NULL,
  `count_pd` int(11) DEFAULT NULL,
  `total_gd_amount` int(11) DEFAULT NULL,
  `total_pd_amount` int(11) DEFAULT NULL,
  `total_sent_amount` int(11) DEFAULT NULL,
  `total_receive_amount` int(11) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 normal, 1 blocked'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dispute`
--

CREATE TABLE IF NOT EXISTS `dispute` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pd_id` int(11) DEFAULT NULL,
  `gd_id` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-waiting 1-approved 2-unapproved',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gd`
--

CREATE TABLE IF NOT EXISTS `gd` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pd_id` int(11) NOT NULL,
  `gd_amount` int(11) DEFAULT NULL,
  `pd_amount` int(11) DEFAULT NULL,
  `ref_amount` int(11) DEFAULT NULL,
  `pin_id` int(11) NOT NULL,
  `pin_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0 waiting, 1 receiving, 2 done',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gd`
--

INSERT INTO `gd` (`id`, `user_id`, `pd_id`, `gd_amount`, `pd_amount`, `ref_amount`, `pin_id`, `pin_number`, `status`, `created`) VALUES
(1, 5, -1, NULL, 5000, 0, 2, '9fc80b223d', 0, '2016-11-14 04:53:09');

-- --------------------------------------------------------

--
-- Table structure for table `pd`
--

CREATE TABLE IF NOT EXISTS `pd` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pd_amount` int(11) DEFAULT NULL,
  `applied_interest_rate` int(11) NOT NULL COMMENT 'percentage %',
  `pin_id` int(11) NOT NULL,
  `pin_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'status: 0 waiting, 1 sending, 2 done',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pd`
--

INSERT INTO `pd` (`id`, `user_id`, `pd_amount`, `applied_interest_rate`, `pin_id`, `pin_number`, `status`, `created`) VALUES
(1, 4, NULL, 35, 1, '563867c48e', 0, '2016-11-11 08:39:37');

-- --------------------------------------------------------

--
-- Table structure for table `pin`
--

CREATE TABLE IF NOT EXISTS `pin` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `pin_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `used` tinyint(1) NOT NULL,
  `pd_id` int(11) DEFAULT NULL,
  `gd_id` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pin`
--

INSERT INTO `pin` (`id`, `user_id`, `pin_number`, `used`, `pd_id`, `gd_id`) VALUES
(1, 4, '563867c48e', 1, 1, NULL),
(2, 5, '9fc80b223d', 1, NULL, 1),
(3, NULL, '6942938acc', 0, NULL, NULL),
(4, NULL, 'd02b673676', 0, NULL, NULL),
(5, NULL, 'b14ae48049', 0, NULL, NULL),
(6, NULL, '28afe3dbd2', 0, NULL, NULL),
(7, NULL, 'd1fddfc4f0', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE IF NOT EXISTS `transaction` (
  `id` int(11) NOT NULL,
  `pd_id` int(11) DEFAULT NULL,
  `gd_id` int(11) DEFAULT NULL,
  `pd_user_id` int(11) DEFAULT NULL,
  `gd_user_id` int(11) DEFAULT NULL,
  `gd_acc_number` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pd_acc_number` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approved_date` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `status` int(11) NOT NULL COMMENT '0 pending, 1 approved'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `pd_id`, `gd_id`, `pd_user_id`, `gd_user_id`, `gd_acc_number`, `pd_acc_number`, `amount`, `created`, `approved_date`, `status`) VALUES
(1, 1, 1, 4, 5, '90000', '12345', 5000, '2016-11-14 07:09:27', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `ref_id` int(11) DEFAULT NULL,
  `creator_id` int(11) DEFAULT NULL,
  `full_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vcb_acc_number` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` char(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `salt` char(100) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT NULL,
  `first_pd_done` int(11) DEFAULT NULL,
  `pd_gd_state` char(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '''Pending'',''PD_Requested'',''PD_Matched'',''PD_Done'',''GD_Requested'',''GD_Matched'',''GD_Done''',
  `last_state_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `pd_count` int(11) DEFAULT NULL,
  `pd_total` int(11) DEFAULT NULL,
  `gd_count` int(11) DEFAULT NULL,
  `gd_total` int(11) DEFAULT NULL,
  `outstanding_pd` int(11) DEFAULT NULL,
  `outstanding_gd` int(11) DEFAULT NULL,
  `blocked` tinyint(1) DEFAULT NULL,
  `current_interest_rate` int(11) DEFAULT NULL,
  `c_level` int(1) DEFAULT NULL,
  `outstanding_ref_amount` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `ref_id`, `creator_id`, `full_name`, `vcb_acc_number`, `phone`, `email`, `username`, `password`, `salt`, `last_login`, `email_verified`, `first_pd_done`, `pd_gd_state`, `last_state_update`, `pd_count`, `pd_total`, `gd_count`, `gd_total`, `outstanding_pd`, `outstanding_gd`, `blocked`, `current_interest_rate`, `c_level`, `outstanding_ref_amount`) VALUES
(4, NULL, 1, 'Nguyen van test', '12345', '0988', 'test1@c5.com', 'test1', '2b1b9b42953522143d6e0c101c0be835aef6f5effce5aa15f699dd2ca1a8ad33', '47471161058256748afad1', NULL, 1, 1, 'PD_Requested', '2016-11-14 03:43:40', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, NULL),
(5, NULL, 1, 'trần thị đép', '90000', '096969', 'test2@c5.com', 'test2', '8c403e9d9cca409c5ad1a04071afa0d0c95e6e1bfa8ff13a5c70ff35fcbdf976', '104057893558256748dadf6', NULL, 1, 1, 'GD_Requested', '2016-11-14 04:53:09', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, NULL),
(6, NULL, 1, NULL, NULL, NULL, 'test3@c5.com', 'test3', 'c7b1b09d4de8316e71ffb4992531fca79154d4aea666191fa4ff15269ac5888f', '95161589358256748f3541', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attachment`
--
ALTER TABLE `attachment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_acc_log`
--
ALTER TABLE `bank_acc_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dispute`
--
ALTER TABLE `dispute`
  ADD PRIMARY KEY (`id`), ADD FULLTEXT KEY `message` (`message`);

--
-- Indexes for table `gd`
--
ALTER TABLE `gd`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pd`
--
ALTER TABLE `pd`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pin`
--
ALTER TABLE `pin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
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
-- AUTO_INCREMENT for table `attachment`
--
ALTER TABLE `attachment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bank_acc_log`
--
ALTER TABLE `bank_acc_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dispute`
--
ALTER TABLE `dispute`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gd`
--
ALTER TABLE `gd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `pd`
--
ALTER TABLE `pd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `pin`
--
ALTER TABLE `pin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2016 at 11:28 AM
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
  `message` text COLLATE utf8_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-waiting 1-approved 2-unapproved',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gd`
--

CREATE TABLE IF NOT EXISTS `gd` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pd_id` int(11) NOT NULL,
  `gd_amount` int(11) NOT NULL,
  `pd_amount` int(11) NOT NULL,
  `ref_amount` int(11) NOT NULL,
  `pin_id` int(11) NOT NULL,
  `pin_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0 waiting, 1 receiving, 2 done',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pd`
--

CREATE TABLE IF NOT EXISTS `pd` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pd_amount` int(11) NOT NULL,
  `applied_interest_rate` int(11) NOT NULL COMMENT 'percentage %',
  `pin_id` int(11) NOT NULL,
  `pin_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT 'status: 0 waiting, 1 sending, 2 done',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pin`
--

CREATE TABLE IF NOT EXISTS `pin` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pin_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `used` tinyint(1) NOT NULL,
  `pd_id` int(11) DEFAULT NULL,
  `gd_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
  `hash` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `ref_id`, `creator_id`, `full_name`, `vcb_acc_number`, `phone`, `email`, `username`, `password`, `salt`, `hash`, `email_verified`, `first_pd_done`, `pd_gd_state`, `last_state_update`, `pd_count`, `pd_total`, `gd_count`, `gd_total`, `outstanding_pd`, `outstanding_gd`, `blocked`, `current_interest_rate`, `c_level`, `outstanding_ref_amount`) VALUES
(1, NULL, NULL, NULL, NULL, NULL, 'klmnkien2@gmail.com', 'test', '2188392e862c37070598bee2f0e457ed17132ce4db0da1bb8dfb0a5edcdd9c97', '154049790057ff462d0a2d5', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bank_acc_log`
--
ALTER TABLE `bank_acc_log`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `bank_acc_log`
--
ALTER TABLE `bank_acc_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gd`
--
ALTER TABLE `gd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pd`
--
ALTER TABLE `pd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pin`
--
ALTER TABLE `pin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

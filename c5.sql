-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 03, 2017 at 10:36 AM
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
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL,
  `creator_id` int(11) DEFAULT NULL,
  `full_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` char(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `salt` char(100) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `current_login` timestamp NULL DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT NULL,
  `blocked` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `creator_id`, `full_name`, `phone`, `email`, `username`, `password`, `role`, `salt`, `last_login`, `current_login`, `email_verified`, `blocked`) VALUES
(1, NULL, 'Super Admin', '989', 'kien.dv@altplus.com.vn', 'super', 'b89de2044c3fc48db9b54444ee463b73cd8d9a36a4b9d5a278cfa631ba0bb81d', 'ROLE_SUPER_ADMIN', '4546794325858a3f4bf4d4', '2016-12-13 04:05:18', '2016-12-15 03:32:56', 1, 0),
(2, 1, 'do viet kien', '12345', 'kien.dv@altplus.com.vn', 'admin1', '3c2a47820ad6b51ceadd027842678f6cc2e5043929cda7da4efa909465890d9e', 'ROLE_ADMIN', '1710786759585b4d8fed01a', NULL, '2016-12-21 21:57:48', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `attachment`
--

CREATE TABLE IF NOT EXISTS `attachment` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `refer_id` int(11) DEFAULT NULL,
  `type` char(10) COLLATE utf8_unicode_ci NOT NULL
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
  `transaction_id` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-waiting 1-approved 2-unapproved',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dispute`
--

INSERT INTO `dispute` (`id`, `user_id`, `transaction_id`, `status`, `created`, `message`) VALUES
(2, 4, 4, 0, '2016-12-07 09:42:27', 'test da gui va sua lai');

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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gd`
--

INSERT INTO `gd` (`id`, `user_id`, `pd_id`, `gd_amount`, `pd_amount`, `ref_amount`, `pin_id`, `pin_number`, `status`, `created`) VALUES
(21, 11, 0, 4400000, 0, 0, 1, 'bot', 1, '2017-01-03 07:34:33'),
(22, 12, 0, 4400000, 0, 0, 1, 'bot', 1, '2017-01-03 07:34:33'),
(23, 13, 0, 4400000, 0, 0, 1, 'bot', 1, '2017-01-03 07:34:33');

-- --------------------------------------------------------

--
-- Table structure for table `interest_log`
--

CREATE TABLE IF NOT EXISTS `interest_log` (
  `id` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `pd_amount` int(11) NOT NULL,
  `interest_amount` int(11) NOT NULL,
  `from_user_level` int(11) NOT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pd`
--

INSERT INTO `pd` (`id`, `user_id`, `pd_amount`, `applied_interest_rate`, `pin_id`, `pin_number`, `status`, `created`) VALUES
(31, 4, 2200000, 30, 1, 'test', 1, '2017-01-03 07:34:13'),
(32, 5, 2200000, 30, 1, 'test', 1, '2017-01-03 07:34:13'),
(33, 6, 2200000, 30, 1, 'test', 1, '2017-01-03 07:34:13'),
(34, 7, 2200000, 30, 1, 'test', 1, '2017-01-03 07:34:13'),
(35, 8, 2200000, 30, 1, 'test', 1, '2017-01-03 07:34:13'),
(36, 10, 2200000, 30, 1, 'test', 1, '2017-01-03 07:34:13');

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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pin`
--

INSERT INTO `pin` (`id`, `user_id`, `pin_number`, `used`, `pd_id`, `gd_id`) VALUES
(1, NULL, 'test', 1, NULL, NULL),
(18, NULL, '50cccfca4b', 0, NULL, NULL),
(19, NULL, '6cf24cb25c', 0, NULL, NULL),
(20, NULL, '5e9844a132', 0, NULL, NULL),
(21, NULL, 'a8fdbb9059', 0, NULL, NULL),
(22, NULL, '4743586aa7', 0, NULL, NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `pd_id`, `gd_id`, `pd_user_id`, `gd_user_id`, `gd_acc_number`, `pd_acc_number`, `amount`, `created`, `approved_date`, `status`) VALUES
(59, 23, 19, 8, 13, NULL, NULL, 733334, '2017-01-03 07:29:40', NULL, 0),
(60, 23, 18, 8, 12, NULL, NULL, 733333, '2017-01-03 07:29:40', NULL, 0),
(61, 23, 17, 8, 11, NULL, NULL, 733333, '2017-01-03 07:29:40', NULL, 0),
(62, 24, 19, 10, 13, NULL, NULL, 733334, '2017-01-03 07:29:40', NULL, 0),
(63, 24, 18, 10, 12, NULL, NULL, 733333, '2017-01-03 07:29:40', NULL, 0),
(64, 24, 17, 10, 11, NULL, NULL, 733333, '2017-01-03 07:29:40', NULL, 0),
(65, 22, 19, 7, 13, NULL, NULL, 733334, '2017-01-03 07:29:40', NULL, 0),
(66, 22, 18, 7, 12, NULL, NULL, 733333, '2017-01-03 07:29:40', NULL, 0),
(67, 22, 17, 7, 11, NULL, NULL, 733333, '2017-01-03 07:29:40', NULL, 0),
(68, 21, 19, 6, 13, NULL, NULL, 733334, '2017-01-03 07:29:40', NULL, 0),
(69, 21, 18, 6, 12, NULL, NULL, 733333, '2017-01-03 07:29:40', NULL, 0),
(70, 21, 17, 6, 11, NULL, NULL, 733333, '2017-01-03 07:29:40', NULL, 0),
(71, 20, 19, 5, 13, NULL, NULL, 733334, '2017-01-03 07:29:40', NULL, 0),
(72, 20, 18, 5, 12, NULL, NULL, 733333, '2017-01-03 07:29:40', NULL, 0),
(73, 20, 17, 5, 11, NULL, NULL, 733333, '2017-01-03 07:29:40', NULL, 0),
(74, 19, 19, 4, 13, NULL, NULL, 733330, '2017-01-03 07:29:40', NULL, 0),
(75, 19, 18, 4, 12, NULL, NULL, 733335, '2017-01-03 07:29:40', NULL, 0),
(76, 19, 17, 4, 11, NULL, NULL, 733335, '2017-01-03 07:29:40', NULL, 0),
(77, 35, 23, 8, 13, NULL, NULL, 733334, '2017-01-03 07:34:34', '2017-01-03 01:44:53', 1),
(78, 35, 22, 8, 12, NULL, NULL, 733333, '2017-01-03 07:34:34', '2017-01-03 01:44:53', 1),
(79, 35, 21, 8, 11, NULL, NULL, 733333, '2017-01-03 07:34:34', '2017-01-03 01:44:53', 1),
(80, 36, 23, 10, 13, NULL, NULL, 733334, '2017-01-03 07:34:34', '2017-01-03 01:44:53', 1),
(81, 36, 22, 10, 12, NULL, NULL, 733333, '2017-01-03 07:34:34', '2017-01-03 01:44:53', 1),
(82, 36, 21, 10, 11, NULL, NULL, 733333, '2017-01-03 07:34:34', '2017-01-03 01:44:53', 1),
(83, 34, 23, 7, 13, NULL, NULL, 733334, '2017-01-03 07:34:34', '2017-01-03 01:44:53', 1),
(84, 34, 22, 7, 12, NULL, NULL, 733333, '2017-01-03 07:34:34', '2017-01-03 01:44:53', 1),
(85, 34, 21, 7, 11, NULL, NULL, 733333, '2017-01-03 07:34:34', '2017-01-03 01:44:53', 1),
(86, 33, 23, 6, 13, NULL, NULL, 733334, '2017-01-03 07:34:34', '2017-01-03 01:44:53', 1),
(87, 33, 22, 6, 12, NULL, NULL, 733333, '2017-01-03 07:34:34', '2017-01-03 01:44:53', 1),
(88, 33, 21, 6, 11, NULL, NULL, 733333, '2017-01-03 07:34:34', '2017-01-03 01:44:53', 1),
(89, 32, 23, 5, 13, NULL, NULL, 733334, '2017-01-03 07:34:34', '2017-01-03 01:44:53', 1),
(90, 32, 22, 5, 12, NULL, NULL, 733333, '2017-01-03 07:34:34', '2017-01-03 01:44:53', 1),
(91, 32, 21, 5, 11, NULL, NULL, 733333, '2017-01-03 07:34:34', '2017-01-03 01:44:53', 1),
(92, 31, 23, 4, 13, NULL, NULL, 733330, '2017-01-03 07:34:34', '2017-01-03 01:44:53', 1),
(93, 31, 22, 4, 12, NULL, NULL, 733335, '2017-01-03 07:34:34', '2017-01-03 01:44:53', 1),
(94, 31, 21, 4, 11, NULL, NULL, 733335, '2017-01-03 07:34:34', '2017-01-03 01:44:53', 1);

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
  `current_login` timestamp NULL DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `ref_id`, `creator_id`, `full_name`, `vcb_acc_number`, `phone`, `email`, `username`, `password`, `salt`, `last_login`, `current_login`, `email_verified`, `first_pd_done`, `pd_gd_state`, `last_state_update`, `pd_count`, `pd_total`, `gd_count`, `gd_total`, `outstanding_pd`, `outstanding_gd`, `blocked`, `current_interest_rate`, `c_level`, `outstanding_ref_amount`) VALUES
(4, NULL, 1, 'Nguyen Van Test', '123534746', '0988', 'test1@c5.com', 'test1', '2a3266aa2df94e18939dedcdf00baa9448e722c6996e06cc465d689a8338c319', '163935315858f623d214b', '0000-00-00 00:00:00', '2016-12-05 21:25:40', 1, NULL, 'PD_Done', '2017-01-03 07:44:53', NULL, NULL, NULL, NULL, 31, NULL, 0, 30, 10, NULL),
(5, NULL, 1, 'Trần Thị Dép', '999999', '+8496969', 'test2@c5.com.vn', 'test2', '35c6c60f12ccab4ce7215cf5a7ab46acdac2f84b06ae46c1c3879546490769f4', '104057893558256748dadf6', '2016-12-04 22:40:44', '2016-12-09 03:09:39', 1, NULL, 'PD_Done', '2017-01-03 07:44:53', NULL, NULL, NULL, NULL, 32, NULL, 0, 30, 10, NULL),
(6, NULL, 1, 'trung gian ', NULL, NULL, 'test3@c5.com', 'test3', 'c7b1b09d4de8316e71ffb4992531fca79154d4aea666191fa4ff15269ac5888f', '95161589358256748f3541', NULL, '0000-00-00 00:00:00', 1, NULL, 'PD_Done', '2017-01-03 07:44:53', NULL, NULL, NULL, NULL, 33, NULL, 0, 30, 10, NULL),
(7, NULL, 1, 'pd user', '666666', '0988999', 'test4@c5.com', 'test4', '2b1b9b42953522143d6e0c101c0be835aef6f5effce5aa15f699dd2ca1a8ad33', '47471161058256748afad1', '2016-11-23 03:58:14', '0000-00-00 00:00:00', 1, NULL, 'PD_Done', '2017-01-03 07:44:53', NULL, NULL, NULL, NULL, 34, NULL, 0, 30, 10, NULL),
(8, NULL, 1, 'gd user', '2222', '+8496969', 'test5@c5.com.vn', 'test5', '35c6c60f12ccab4ce7215cf5a7ab46acdac2f84b06ae46c1c3879546490769f4', '104057893558256748dadf6', '2016-11-30 21:51:35', '0000-00-00 00:00:00', 1, NULL, 'PD_Done', '2017-01-03 07:44:53', NULL, NULL, NULL, NULL, 35, NULL, 0, 30, 10, NULL),
(10, NULL, 1, 'Toi la test7', '123', '123', 'kien.dv@altplus.com.vn', 'test7', '4be41991355a7d691d844b18a1a7d047ec1c9bfbc20079cdeed7028278266fba', '4072318145858fec5be3ca', NULL, NULL, 1, NULL, 'PD_Done', '2017-01-03 07:44:53', NULL, NULL, NULL, NULL, 36, NULL, 0, 30, 10, NULL),
(11, NULL, 2, 'bot1', 'vcb1', '1', 'bot1@gmail.com', 'bot1', 'e481784edd0ac226449e68ef0a11055e1d0b9be3432536489f06a9fab7df6000', '215757284586606ea20243', NULL, NULL, 1, NULL, 'GD_Done', '2017-01-03 07:44:53', NULL, NULL, NULL, NULL, NULL, NULL, 0, 30, 10, NULL),
(12, NULL, 2, 'bot2', 'vcb2', '2', 'bot2@gmail.com', 'bot2', 'e481784edd0ac226449e68ef0a11055e1d0b9be3432536489f06a9fab7df6000', '215757284586606ea20243', NULL, NULL, 1, NULL, 'GD_Done', '2017-01-03 07:44:53', NULL, NULL, NULL, NULL, NULL, NULL, 0, 30, 10, NULL),
(13, NULL, 2, 'bot3', 'vcb3', '3', 'bot3@gmail.com', 'bot3', 'e481784edd0ac226449e68ef0a11055e1d0b9be3432536489f06a9fab7df6000', '215757284586606ea20243', NULL, NULL, 1, NULL, 'GD_Done', '2017-01-03 07:44:53', NULL, NULL, NULL, NULL, NULL, NULL, 0, 30, 10, NULL),
(14, NULL, 2, 'bot4', 'vcb4', '4', 'bot4@gmail.com', 'bot4', 'e481784edd0ac226449e68ef0a11055e1d0b9be3432536489f06a9fab7df6000', '215757284586606ea20243', NULL, NULL, 1, NULL, NULL, '2017-01-03 07:31:56', NULL, NULL, NULL, NULL, NULL, NULL, 0, 30, 10, NULL),
(15, NULL, 2, 'bot5', 'vcb5', '5', 'bot5@gmail.com', 'bot5', 'e481784edd0ac226449e68ef0a11055e1d0b9be3432536489f06a9fab7df6000', '215757284586606ea20243', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 30, 10, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `interest_log`
--
ALTER TABLE `interest_log`
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
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `gd`
--
ALTER TABLE `gd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `interest_log`
--
ALTER TABLE `interest_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pd`
--
ALTER TABLE `pd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT for table `pin`
--
ALTER TABLE `pin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=95;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

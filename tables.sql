-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 05, 2019 at 10:25 AM
-- Server version: 5.5.62-0+deb8u1
-- PHP Version: 5.6.40-0+deb8u7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `General`
--

-- --------------------------------------------------------

--
-- Table structure for table `iplog`
--

CREATE TABLE `iplog` (
  `id` int(11) NOT NULL,
  `netmon_id` int(11) NOT NULL,
  `mac` varchar(20) NOT NULL,
  `vendor` varchar(50) NOT NULL,
  `status` varchar(5) NOT NULL,
  `timestr` datetime NOT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `netmon`
--

CREATE TABLE `netmon` (
  `id` int(11) NOT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `host` varchar(50) DEFAULT NULL,
  `mac` varchar(20) DEFAULT NULL,
  `vendor` varchar(50) NOT NULL,
  `status` varchar(5) DEFAULT NULL,
  `timestr` datetime NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `iplog`
--
ALTER TABLE `iplog`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `netmon_id` (`netmon_id`);

--
-- Indexes for table `netmon`
--
ALTER TABLE `netmon`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `iplog`
--
ALTER TABLE `iplog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `netmon`
--
ALTER TABLE `netmon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

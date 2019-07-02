-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 14, 2019 at 07:15 PM
-- Server version: 10.3.8-MariaDB
-- PHP Version: 5.6.37

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dev_eapteka`
--

-- --------------------------------------------------------

--
-- Table structure for table `oc_translate`
--

CREATE TABLE `oc_translate` (
  `translate_id` int(11) NOT NULL,
  `translate_group_id` int(11) NOT NULL,
  `sort_order` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `oc_translate_description`
--

CREATE TABLE `oc_translate_description` (
  `translate_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `1c_name` varchar(255) NOT NULL,
  `mf_tooltip` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `oc_translate`
--
ALTER TABLE `oc_translate`
  ADD PRIMARY KEY (`translate_id`);

--
-- Indexes for table `oc_translate_description`
--
ALTER TABLE `oc_translate_description`
  ADD PRIMARY KEY (`translate_id`,`language_id`),
  ADD KEY `1c_name` (`1c_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `oc_translate`
--
ALTER TABLE `oc_translate`
  MODIFY `translate_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
ALTER TABLE `oc_translate` CHANGE `translate_group_id` `translate_group_id` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;


ALTER TABLE `oc_translate` ADD `status` INT(1) NOT NULL AFTER `sort_order`;

ALTER TABLE `oc_translate` ADD `lower` INT(1) NOT NULL AFTER `status`;

ALTER TABLE `oc_translate` ADD `layout_id` INT(3) NOT NULL AFTER `lower`;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

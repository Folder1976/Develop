-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2013 at 07:47 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `uraaw347_opencart`
--

-- --------------------------------------------------------

--
-- Table structure for table `oc_footerlink`
--

CREATE TABLE IF NOT EXISTS `oc_footerlink` (
  `footerlink_id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(255) NOT NULL,
  `selectheading` varchar(255) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`footerlink_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `oc_footerlink`
--

INSERT INTO `oc_footerlink` (`footerlink_id`, `link`, `selectheading`, `status`, `sort_order`) VALUES
(22, 'index.php?route=information/information&amp;information_id=4', '9', 1, 0),
(2, '', '4', 1, 0),
(3, '', '4', 1, 0),
(4, '', '4', 1, 0),
(5, '', '4', 1, 0),
(6, '', '4', 1, 0),
(7, 'sdf', '5', 1, 0),
(8, '', '4', 1, 0),
(24, 'index.php?route=common/home', '12', 1, 0),
(25, 'index.php?route=information/contact', '12', 1, 0),
(23, '#', '9', 1, 1),
(26, '#', '11', 1, 0),
(27, '#', '9', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `oc_footerlink_description`
--

CREATE TABLE IF NOT EXISTS `oc_footerlink_description` (
  `footerlink_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `oc_footerlink_description`
--

INSERT INTO `oc_footerlink_description` (`footerlink_id`, `language_id`, `title`) VALUES
(23, 1, 'FAQ'),
(22, 1, 'About'),
(24, 1, 'demo'),
(25, 1, 'demo2'),
(26, 1, 'demo3'),
(27, 1, 'demo4');

-- --------------------------------------------------------

--
-- Table structure for table `oc_footertitle`
--

CREATE TABLE IF NOT EXISTS `oc_footertitle` (
  `footertitle_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`footertitle_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `oc_footertitle`
--

INSERT INTO `oc_footertitle` (`footertitle_id`, `status`, `sort_order`) VALUES
(10, 1, 1),
(11, 1, 2),
(9, 1, 0),
(12, 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `oc_footertitle_description`
--

CREATE TABLE IF NOT EXISTS `oc_footertitle_description` (
  `footertitle_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`footertitle_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `oc_footertitle_description`
--

INSERT INTO `oc_footertitle_description` (`footertitle_id`, `language_id`, `title`) VALUES
(12, 1, 'Custmomer Service'),
(11, 1, 'Health Food'),
(9, 1, 'Our Company'),
(10, 1, 'Customize');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 11, 2016 at 01:01 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `quaktis`
--

-- --------------------------------------------------------

--
-- Table structure for table `info_tbl`
--

CREATE TABLE IF NOT EXISTS `info_tbl` (
`infoID` int(100) NOT NULL,
  `firstName` varchar(199) DEFAULT NULL,
  `lastName` varchar(199) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `info_tbl`
--

INSERT INTO `info_tbl` (`infoID`, `firstName`, `lastName`) VALUES
(11, 'kumar', 'Deepak');

-- --------------------------------------------------------

--
-- Table structure for table `user_tbl`
--

CREATE TABLE IF NOT EXISTS `user_tbl` (
`userID` int(5) unsigned NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `user_tbl`
--

INSERT INTO `user_tbl` (`userID`, `username`, `password`) VALUES
(3, 'admin', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `info_tbl`
--
ALTER TABLE `info_tbl`
 ADD PRIMARY KEY (`infoID`);

--
-- Indexes for table `user_tbl`
--
ALTER TABLE `user_tbl`
 ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `info_tbl`
--
ALTER TABLE `info_tbl`
MODIFY `infoID` int(100) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `user_tbl`
--
ALTER TABLE `user_tbl`
MODIFY `userID` int(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

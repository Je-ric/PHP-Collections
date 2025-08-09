-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 09, 2025 at 02:58 PM
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
-- Database: `oop`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_students`
--

CREATE TABLE `tbl_students` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `course_year_section` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_students`
--

INSERT INTO `tbl_students` (`id`, `full_name`, `email`, `course_year_section`) VALUES
(1, 'Je-ric', 'jeric@gmail.com', 'BSIT 4-2'),
(2, 'Larababes', 'larababes@gmail.com', 'BSIT 4-3'),
(3, 'Aso', 'aso', 'aso'),
(4, 'pusa', 'pusa', 'pusa'),
(6, 'pusaso', 'pusaso', 'pusaso'),
(8, 'daga', 'daga', 'daga'),
(9, 'kambing', 'kambing', 'kambing'),
(10, 'kabayo', 'kabayo', 'kabayo'),
(11, 'kalabaw', 'kalabaw', 'kalabaw'),
(31, 'Je-rics', 'jeric@gmail.coms', 'N/As'),
(32, 'adgjmpsvy', 'behknqtwz', 'cfilorux');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_students`
--
ALTER TABLE `tbl_students`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_students`
--
ALTER TABLE `tbl_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

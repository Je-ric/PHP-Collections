-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Mar 16, 2024 at 10:58 AM
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
-- Database: `studentdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'adminpassword');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `age` int(2) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `gpa` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_id`, `name`, `age`, `email`, `gpa`) VALUES
(1, '202401', 'Jeric Dela Cruz', 18, 'jeric@gmail.com', 1.25),
(2, '202402', 'Gwen De Guzman', 19, 'gwen@gmail.com', 0),
(3, '202403', 'Jodilyn Sarmiento', 20, 'jodilyn@gmail.com', 0),
(4, '202404', 'Menard Macaraeg', 21, 'menard@gmail.com', 2.52),
(5, '202405', 'Kiel Palaad', 18, 'kiel@gmail.com', 0),
(6, '202406', 'Ejay Basinga', 19, 'ejay@gmail.com', 0),
(7, '202407', 'Franz Harry Daniel Eda', 20, 'franz@gmail.com', 0),
(8, '202408', 'Ronnel Baldovino', 21, 'ronnel@gmail.com', 0),
(9, '202409', 'Francis Vengazo', 18, 'francis@gmail.com', 0),
(10, '202410', 'Liane Tomas', 19, 'liane@gmail.com', 0),
(11, '202411', 'Joshua Bautista', 20, 'joshua@gmail.com', 0),
(12, '202412', 'Jan Bernard Masinag', 21, 'jan@gmail.com', 0),
(13, '202413', 'Nino Emmanuel', 18, 'nino@gmail.com', 0),
(14, '202414', 'Hazel De Leon', 19, 'hazel@gmail.com', 0),
(15, '202415', 'Ghieverlyn Callo', 20, 'ghieverlyn@gmail.com', 0),
(16, '202416', 'Jhanice Abracia', 21, 'jhanice@gmail.com', 0),
(17, '202417', 'Alliah Kate Taban', 18, 'alliah@gmail.com', 0),
(18, '202418', 'Christine Joy Duatin', 19, 'christine@gmail.com', 0),
(19, '202419', 'Jozen Agustin', 20, 'jozen@gmail.com', 0),
(20, '202420', 'Krisha Manahan', 21, 'krisha@gmail.com', 0),
(21, '202421', 'Melgie Alata', 18, 'melgie@gmail.com', 0),
(22, '202422', 'Mac Cacho', 19, 'mac@gmail.com', 0),
(23, '202423', 'Lorenz Bocatot', 20, 'lorenz@gmail.com', 0),
(24, '202424', 'Aaron Palad', 21, 'aaron@gmail.com', 0),
(25, '202425', 'Rogelio Pagay', 18, 'rogelio@gmail.com', 0),
(26, '202426', 'Victor Sam', 19, 'victor@gmail.com', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `login_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

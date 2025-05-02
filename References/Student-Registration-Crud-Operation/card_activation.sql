-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2022 at 05:23 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.2.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `user`
--

-- --------------------------------------------------------

--
-- Table structure for table `card_activation`
--

CREATE TABLE `card_activation` (
  `id` int(10) NOT NULL,
  `u_card` varchar(12) NOT NULL,
  `u_f_name` text NOT NULL,
  `u_l_name` text NOT NULL,
  `u_father` text NOT NULL,
  `u_aadhar` varchar(12) NOT NULL,
  `u_birthday` text NOT NULL,
  `u_gender` varchar(6) NOT NULL,
  `u_email` text NOT NULL,
  `u_phone` varchar(10) NOT NULL,
  `u_dist` text NOT NULL,
  `u_village` text NOT NULL,
  `u_police` text NOT NULL,
  `u_pincode` text NOT NULL,
  `file` longblob NOT NULL,
  `u_mother` varchar(30) NOT NULL,
  `u_family` text NOT NULL,
  `staff_id` varchar(12) NOT NULL,
  `image` varchar(150) NOT NULL,
  `uploaded` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `card_activation`
--

INSERT INTO `card_activation` (`id`, `u_card`, `u_f_name`, `u_l_name`, `u_father`, `u_aadhar`, `u_birthday`, `u_gender`, `u_email`, `u_phone`, `u_dist`, `u_village`, `u_police`, `u_pincode`, `file`, `u_mother`, `u_family`, `staff_id`, `image`, `uploaded`) VALUES
(102, '2341412', 'Lavkush', 'singh', 'asdf', '', '', 'Male', '', '9655324253', '', '', '', '', '', 'aadsf', '', '', '', '2021-01-16 12:47:17'),
(103, '123333333333', 'Kush ', 'singh', '', '', '', 'Choose', '', '1222222222', '', '', '', '', '', '', '', '', 'vandana_photo.jpeg', '2021-01-16 12:52:59'),
(104, 'ABCD12345677', 'Kush ', 'Singh', '', 'xxxxxxxxxxxx', '2021-01-22', 'Male', 'emmyluckytech@gmail.com', '1234567890', 'Rohtas', 'Dehri', 'Dehri', '111111111', '', '', '', '111111111111', 'Webp.net-resizeimage.jpg', '2021-01-16 13:06:03'),
(105, '2341412', 'Prakash', 'Bhattarai', '', '', '', 'Male', 'desort@gmail.com', '9802851472', 'pokhara', '', '', '', '', '', '', '323', 'images.png', '2022-08-23 22:14:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `card_activation`
--
ALTER TABLE `card_activation`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `card_activation`
--
ALTER TABLE `card_activation`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

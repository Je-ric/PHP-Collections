-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2025 at 03:23 AM
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
-- Database: `pos_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `attendance_status` enum('Present','Absent') DEFAULT 'Present'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `user_id`, `attendance_date`, `time_in`, `time_out`, `attendance_status`) VALUES
(19, 3, '2024-10-27', '21:55:29', '23:58:02', 'Present'),
(20, 4, '2024-10-27', '21:55:39', '22:38:58', 'Present'),
(21, 8, '2024-10-27', '21:55:39', '22:38:59', 'Present'),
(22, 10, '2024-10-27', '21:55:40', '22:38:59', 'Present'),
(23, 5, '2024-10-27', NULL, NULL, 'Absent'),
(24, 3, '2024-10-28', '13:15:57', '23:21:37', 'Present'),
(25, 4, '2024-10-28', '13:15:58', '17:07:48', 'Present'),
(26, 8, '2024-10-28', '13:15:58', '17:07:49', 'Present'),
(27, 10, '2024-10-28', '13:15:59', '17:07:50', 'Present'),
(28, 3, '2024-10-29', '16:24:28', '22:12:51', 'Present'),
(29, 4, '2024-10-29', '16:24:27', '18:34:36', 'Present'),
(30, 8, '2024-10-29', '16:24:26', '18:34:35', 'Present'),
(31, 9, '2024-10-29', '16:24:26', NULL, 'Present'),
(32, 10, '2024-10-29', '16:24:26', '18:34:35', 'Present'),
(34, 3, '2024-10-30', '20:21:55', '23:25:12', 'Present'),
(35, 4, '2024-10-30', '20:21:56', '22:25:12', 'Present'),
(36, 8, '2024-10-30', '20:21:56', '22:25:12', 'Present'),
(37, 10, '2024-10-30', '20:21:57', '22:25:12', 'Present'),
(39, 3, '2024-11-01', '10:37:53', '20:51:34', 'Present'),
(40, 4, '2024-11-01', '10:37:54', '18:56:01', 'Present'),
(41, 8, '2024-11-01', '10:37:54', '18:56:02', 'Present'),
(42, 10, '2024-11-01', '10:37:55', '18:56:03', 'Present'),
(44, 3, '2024-11-02', '09:35:38', '18:34:03', 'Present'),
(45, 4, '2024-11-02', '09:35:38', '16:37:11', 'Present'),
(46, 8, '2024-11-02', '09:35:39', '16:37:12', 'Present'),
(47, 10, '2024-11-02', '09:35:40', '16:37:12', 'Present'),
(49, 3, '2024-11-03', '15:34:48', '23:49:52', 'Present'),
(50, 4, '2024-11-03', '15:34:49', '15:59:44', 'Present'),
(51, 8, '2024-11-03', '15:34:50', '15:59:45', 'Present'),
(52, 10, '2024-11-03', '15:34:51', '15:59:44', 'Present'),
(54, 3, '2024-11-05', '12:06:20', '21:12:51', 'Present'),
(55, 4, '2024-11-05', '12:06:21', '12:06:28', 'Present'),
(56, 8, '2024-11-05', '12:06:22', '14:06:28', 'Present'),
(57, 10, '2024-11-05', '12:06:26', '14:06:28', 'Present'),
(59, 3, '2024-11-06', NULL, NULL, 'Absent'),
(60, 4, '2024-11-06', NULL, NULL, 'Absent'),
(61, 8, '2024-11-06', NULL, NULL, 'Absent'),
(62, 10, '2024-11-06', NULL, NULL, 'Absent'),
(64, 3, '2024-11-08', NULL, NULL, 'Absent'),
(65, 4, '2024-11-08', NULL, NULL, 'Absent'),
(66, 8, '2024-11-08', NULL, NULL, 'Absent'),
(67, 10, '2024-11-08', NULL, NULL, 'Absent'),
(69, 5, '2024-11-08', NULL, NULL, 'Absent'),
(70, 6, '2024-11-08', NULL, NULL, 'Absent'),
(71, 7, '2024-11-08', NULL, NULL, 'Absent'),
(72, 3, '2024-11-09', '15:50:16', NULL, 'Present'),
(73, 4, '2024-11-09', NULL, NULL, 'Absent'),
(74, 8, '2024-11-09', NULL, NULL, 'Absent'),
(75, 10, '2024-11-09', NULL, NULL, 'Absent'),
(77, 3, '2024-11-10', '10:13:47', '23:34:14', 'Present'),
(78, 4, '2024-11-10', NULL, NULL, 'Absent'),
(79, 8, '2024-11-10', '12:00:29', NULL, 'Present'),
(80, 10, '2024-11-10', NULL, NULL, 'Absent'),
(82, 3, '2024-11-11', NULL, NULL, 'Absent'),
(83, 4, '2024-11-11', NULL, NULL, 'Absent'),
(84, 8, '2024-11-11', NULL, NULL, 'Absent'),
(85, 10, '2024-11-11', NULL, NULL, 'Absent'),
(87, 3, '2024-11-12', NULL, NULL, 'Absent'),
(88, 4, '2024-11-12', NULL, NULL, 'Absent'),
(89, 8, '2024-11-12', NULL, NULL, 'Absent'),
(90, 10, '2024-11-12', NULL, NULL, 'Absent'),
(92, 3, '2024-11-13', NULL, NULL, 'Absent'),
(101, 4, '2024-11-13', NULL, NULL, 'Absent'),
(109, 5, '2024-11-13', NULL, NULL, 'Absent'),
(110, 6, '2024-11-13', NULL, NULL, 'Absent'),
(111, 7, '2024-11-13', NULL, NULL, 'Absent'),
(112, 8, '2024-11-13', NULL, NULL, 'Absent'),
(113, 9, '2024-11-13', '21:22:50', '21:26:37', 'Present'),
(114, 10, '2024-11-13', '21:22:49', '21:26:39', 'Present'),
(116, 3, '2024-11-14', '17:56:44', '23:47:00', 'Present'),
(117, 4, '2024-11-14', '12:19:14', '12:19:21', 'Present'),
(118, 5, '2024-11-14', '17:56:38', '20:21:34', 'Present'),
(119, 6, '2024-11-14', '12:11:32', '12:18:38', 'Present'),
(120, 8, '2024-11-14', '12:09:03', '17:56:33', 'Present'),
(121, 10, '2024-11-14', '12:06:28', '17:56:34', 'Present'),
(123, 3, '2024-11-15', NULL, NULL, 'Absent'),
(124, 4, '2024-11-15', NULL, NULL, 'Absent'),
(125, 5, '2024-11-15', NULL, NULL, 'Absent'),
(126, 6, '2024-11-15', NULL, NULL, 'Absent'),
(127, 8, '2024-11-15', NULL, NULL, 'Absent'),
(128, 10, '2024-11-15', NULL, NULL, 'Absent'),
(130, 3, '2024-11-16', '13:54:03', NULL, 'Present'),
(131, 4, '2024-11-16', '14:33:01', '14:34:25', 'Present'),
(132, 5, '2024-11-16', '14:33:01', '14:34:12', 'Present'),
(133, 6, '2024-11-16', '14:32:59', '14:34:08', 'Present'),
(134, 8, '2024-11-16', '14:34:14', '14:34:21', 'Present'),
(135, 10, '2024-11-16', '12:01:36', '14:05:41', 'Present'),
(137, 3, '2024-11-17', '20:29:33', '23:46:00', 'Present'),
(138, 4, '2024-11-17', NULL, NULL, 'Absent'),
(139, 5, '2024-11-17', NULL, NULL, 'Absent'),
(140, 6, '2024-11-17', NULL, NULL, 'Absent'),
(141, 8, '2024-11-17', NULL, NULL, 'Absent'),
(142, 10, '2024-11-17', '21:24:19', NULL, 'Present'),
(144, 9, '2024-11-17', NULL, NULL, 'Absent'),
(145, 3, '2024-11-18', '16:03:38', '23:37:00', 'Present'),
(146, 4, '2024-11-18', '16:03:42', NULL, 'Present'),
(147, 6, '2024-11-18', '16:03:43', '19:26:15', 'Present'),
(148, 8, '2024-11-18', '16:03:44', '19:26:14', 'Present'),
(149, 10, '2024-11-18', '12:37:44', '19:26:13', 'Present'),
(151, 7, '2024-11-18', '19:28:12', '19:29:39', 'Present'),
(152, 9, '2024-11-18', '19:28:09', '19:29:34', 'Present'),
(153, 3, '2024-11-19', '12:02:48', NULL, 'Present'),
(168, 4, '2024-11-19', '17:01:49', NULL, 'Present'),
(169, 5, '2024-11-19', '14:14:01', '14:30:54', 'Present'),
(170, 6, '2024-11-19', '14:13:28', '14:13:59', 'Present'),
(171, 7, '2024-11-19', '14:13:24', '14:13:26', 'Present'),
(172, 8, '2024-11-19', '14:13:20', '14:13:23', 'Present'),
(173, 9, '2024-11-19', '14:11:56', '14:13:15', 'Present'),
(174, 10, '2024-11-19', '14:09:19', '14:13:08', 'Present'),
(185, 3, '2024-11-20', NULL, NULL, 'Absent'),
(186, 4, '2024-11-20', NULL, NULL, 'Absent'),
(187, 5, '2024-11-20', NULL, NULL, 'Absent'),
(188, 9, '2024-11-20', NULL, NULL, 'Absent'),
(189, 10, '2024-11-20', NULL, NULL, 'Absent'),
(193, 3, '2024-11-21', '22:19:45', '21:24:00', 'Present'),
(194, 4, '2024-11-21', NULL, NULL, 'Absent'),
(195, 5, '2024-11-21', NULL, NULL, 'Absent'),
(196, 9, '2024-11-21', NULL, NULL, 'Absent'),
(197, 10, '2024-11-21', NULL, NULL, 'Absent'),
(201, 6, '2024-11-21', NULL, NULL, 'Absent'),
(202, 3, '2024-11-22', '20:04:08', '22:32:00', 'Present'),
(203, 4, '2024-11-22', NULL, NULL, 'Absent'),
(204, 5, '2024-11-22', NULL, NULL, 'Absent'),
(205, 6, '2024-11-22', NULL, NULL, 'Absent'),
(206, 9, '2024-11-22', NULL, NULL, 'Absent'),
(207, 10, '2024-11-22', NULL, NULL, 'Absent'),
(211, 3, '2024-11-23', '09:13:30', '23:39:00', 'Present'),
(212, 4, '2024-11-23', NULL, NULL, 'Absent'),
(213, 5, '2024-11-23', NULL, NULL, 'Absent'),
(214, 6, '2024-11-23', NULL, NULL, 'Absent'),
(215, 9, '2024-11-23', NULL, NULL, 'Absent'),
(216, 10, '2024-11-23', NULL, NULL, 'Absent'),
(220, 3, '2024-11-24', '15:55:29', '23:52:00', 'Present'),
(221, 4, '2024-11-24', NULL, NULL, 'Absent'),
(222, 5, '2024-11-24', NULL, NULL, 'Absent'),
(223, 6, '2024-11-24', NULL, NULL, 'Absent'),
(224, 9, '2024-11-24', NULL, NULL, 'Absent'),
(225, 10, '2024-11-24', NULL, NULL, 'Absent'),
(229, 3, '2024-11-25', NULL, NULL, 'Absent'),
(230, 4, '2024-11-25', NULL, NULL, 'Absent'),
(231, 5, '2024-11-25', NULL, NULL, 'Absent'),
(232, 6, '2024-11-25', NULL, NULL, 'Absent'),
(233, 9, '2024-11-25', NULL, NULL, 'Absent'),
(234, 10, '2024-11-25', NULL, NULL, 'Absent'),
(238, 3, '2024-12-01', '17:48:03', '17:59:24', 'Present'),
(239, 4, '2024-12-01', '17:48:08', '17:59:23', 'Present'),
(240, 5, '2024-12-01', '17:48:10', '17:59:21', 'Present'),
(241, 6, '2024-12-01', '17:48:12', '17:59:20', 'Present'),
(242, 9, '2024-12-01', '17:48:13', '17:59:19', 'Present'),
(243, 10, '2024-12-01', '17:48:15', '17:59:17', 'Present'),
(247, 3, '2024-12-06', NULL, NULL, 'Absent'),
(248, 4, '2024-12-06', NULL, NULL, 'Absent'),
(249, 5, '2024-12-06', NULL, NULL, 'Absent'),
(250, 6, '2024-12-06', NULL, NULL, 'Absent'),
(251, 9, '2024-12-06', NULL, NULL, 'Absent'),
(252, 10, '2024-12-06', NULL, NULL, 'Absent'),
(256, 3, '2024-12-07', NULL, NULL, 'Absent'),
(257, 4, '2024-12-07', NULL, NULL, 'Absent'),
(258, 5, '2024-12-07', NULL, NULL, 'Absent'),
(259, 6, '2024-12-07', NULL, NULL, 'Absent'),
(260, 9, '2024-12-07', NULL, NULL, 'Absent'),
(261, 10, '2024-12-07', NULL, NULL, 'Absent'),
(268, 3, '2024-12-08', '19:52:33', NULL, 'Present'),
(269, 4, '2024-12-08', '19:52:36', NULL, 'Present'),
(270, 5, '2024-12-08', '19:52:38', NULL, 'Present'),
(271, 6, '2024-12-08', '19:52:41', NULL, 'Present'),
(272, 9, '2024-12-08', NULL, NULL, 'Absent'),
(273, 10, '2024-12-08', NULL, NULL, 'Absent'),
(280, 3, '2024-12-12', '12:37:49', '12:37:52', 'Present'),
(281, 4, '2024-12-12', '13:06:38', '13:06:42', 'Present'),
(282, 5, '2024-12-12', NULL, NULL, 'Absent'),
(283, 6, '2024-12-12', NULL, NULL, 'Absent'),
(284, 9, '2024-12-12', NULL, NULL, 'Absent'),
(285, 10, '2024-12-12', NULL, NULL, 'Absent'),
(292, 3, '2024-12-13', NULL, NULL, 'Absent'),
(293, 4, '2024-12-13', NULL, NULL, 'Absent'),
(294, 5, '2024-12-13', NULL, NULL, 'Absent'),
(295, 6, '2024-12-13', NULL, NULL, 'Absent'),
(296, 9, '2024-12-13', NULL, NULL, 'Absent'),
(297, 10, '2024-12-13', NULL, NULL, 'Absent'),
(304, 3, '2024-12-14', '11:40:26', '18:52:05', 'Present'),
(305, 4, '2024-12-14', '11:41:56', '12:06:42', 'Present'),
(306, 5, '2024-12-14', '11:49:13', '11:50:06', 'Present'),
(307, 6, '2024-12-14', '11:49:39', '12:06:44', 'Present'),
(308, 9, '2024-12-14', '12:06:48', '14:16:15', 'Present'),
(309, 10, '2024-12-14', '12:06:46', '14:16:17', 'Present'),
(316, 3, '2024-12-16', '21:35:35', '22:36:53', 'Present'),
(317, 4, '2024-12-16', NULL, NULL, 'Absent'),
(318, 5, '2024-12-16', NULL, NULL, 'Absent'),
(319, 6, '2024-12-16', NULL, NULL, 'Absent'),
(320, 9, '2024-12-16', NULL, NULL, 'Absent'),
(321, 10, '2024-12-16', NULL, NULL, 'Absent'),
(328, 3, '2024-12-17', NULL, NULL, 'Absent'),
(329, 4, '2024-12-17', NULL, NULL, 'Absent'),
(330, 5, '2024-12-17', NULL, NULL, 'Absent'),
(331, 6, '2024-12-17', NULL, NULL, 'Absent'),
(332, 9, '2024-12-17', NULL, NULL, 'Absent'),
(333, 10, '2024-12-17', NULL, NULL, 'Absent');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `category_image`) VALUES
(1, 'Shirt', '../../upload_images/category_images/1734430122_1729826231_casual-t-shirt-.png'),
(2, 'Hoodie', '../../upload_images/category_images/1734430128_1729826238_jacket.png'),
(3, 'Shorts', '../../upload_images/category_images/1734430137_1729826246_pants.png'),
(4, 'Pants', '../../upload_images/category_images/1734430143_1729826251_trousers.png'),
(5, 'Cap', '../../upload_images/category_images/1734430186_1729826262_cap.png'),
(6, 'Womens Top', '../../upload_images/category_images/1734430196_1729826290_tank-top.png'),
(7, 'Socks', '../../upload_images/category_images/1734430233_1729826268_socks.png'),
(8, 'Slippers', '../../upload_images/category_images/1734430225_1729826275_slippers.png'),
(9, 'Shoes', '../../upload_images/category_images/1734430215_1729826283_sneaker.png'),
(10, 'Bags', '../../upload_images/category_images/1734430205_1729826301_school-bag.png');

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `color_id` int(11) NOT NULL,
  `color_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`color_id`, `color_name`) VALUES
(19, 'Aquamarine'),
(6, 'Beige'),
(7, 'Black'),
(26, 'Black. green'),
(2, 'Blue'),
(29, 'Bright Yellow'),
(10, 'Brown'),
(17, 'Burlywood'),
(28, 'Charcoal Gray'),
(12, 'Cream'),
(20, 'Crimson'),
(13, 'Cyan'),
(9, 'Dark Navy'),
(21, 'DarkOrchid'),
(15, 'Gold'),
(3, 'Green'),
(4, 'Grey'),
(11, 'Khaki'),
(16, 'LightBlue'),
(18, 'LightSalmon'),
(8, 'Maroon'),
(30, 'Navy Blue'),
(27, 'Olive'),
(24, 'PaleGoldenRod'),
(25, 'PaleVioletRed'),
(31, 'Purple'),
(1, 'Red'),
(23, 'RosyBrown'),
(22, 'SeaGreen'),
(14, 'Sky Blue'),
(5, 'White');

-- --------------------------------------------------------

--
-- Table structure for table `discountpercentages`
--

CREATE TABLE `discountpercentages` (
  `id` int(11) NOT NULL,
  `discount_id` int(11) NOT NULL,
  `percentage` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discountpercentages`
--

INSERT INTO `discountpercentages` (`id`, `discount_id`, `percentage`) VALUES
(1841, 1, 0.00),
(1859, 2, 5.00),
(1860, 2, 10.00),
(1861, 2, 15.00),
(1862, 2, 20.00),
(1839, 3, 50.00),
(1866, 5, 5.00),
(1867, 5, 10.00);

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `promo_status` enum('Available','Expired','Soon') NOT NULL,
  `promo_type` enum('None','BOGO','BOGO Free','Holiday Sales') NOT NULL DEFAULT 'None'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`id`, `name`, `start_date`, `end_date`, `promo_status`, `promo_type`) VALUES
(1, 'No Discount', '2020-11-01', '2034-12-31', 'Available', 'BOGO'),
(2, 'Christmas Sale', '2024-12-14', '2024-12-15', 'Expired', 'BOGO'),
(3, 'Buy 1 Take 1 - Pasko Sale', '2024-12-02', '2024-12-07', 'Soon', 'BOGO'),
(5, 'Test', '2024-12-17', '2024-12-19', 'Available', 'BOGO');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `item_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `size` varchar(50) NOT NULL,
  `color` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL,
  `investment_price` decimal(10,2) NOT NULL,
  `is_bogo` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`item_id`, `name`, `brand`, `size`, `color`, `price`, `quantity`, `category_id`, `isActive`, `investment_price`, `is_bogo`) VALUES
(1, 'Basic Crew Neck T-Shirt', 'Uniqlo', 'S-L', '', 299.00, 16, 1, 1, 199.00, 1),
(2, 'Graphic T-Shirt', 'H&M', 'M-L', '', 399.00, 14, 1, 1, 350.00, 0),
(3, 'Nike Free Size', 'Nike', 'One Size', '', 120.00, 7, 7, 1, 99.00, 0),
(4, 'Fobe Bag', 'Fobe', 'One Size', '', 520.00, 0, 10, 1, 300.00, 1),
(5, 'Adizero Boston 10', 'Adidas', '40-46', '', 6500.00, 19, 9, 1, 6000.00, 0),
(6, 'Graphic Tee', 'H&M', 'M-XL', '', 800.00, 15, 1, 0, 650.00, 1),
(7, 'Basic Pullover Hoodie', 'H&M', 'L-XL', '', 1200.00, 12, 2, 1, 999.00, 0),
(8, 'Vintage Cap', 'Ralph Lauren', 'One Size', '', 1000.00, 20, 5, 1, 899.00, 0),
(9, 'Zip-Up Hoodie', 'Puma', 'XL', '', 2200.00, 14, 2, 1, 1999.00, 0),
(11, 'Warmth Fleece Hoodie', 'Columbia', 'XL', '', 3000.00, 19, 2, 0, 2879.00, 0),
(13, 'Authentic Slim Stretch Chino', 'Banana Republic', '33-38', '', 1200.00, 14, 4, 1, 999.00, 1),
(14, 'Floral Print Blouse', 'Fashionista', 'Medium', '', 1299.99, 0, 6, 1, 1999.00, 0),
(15, 'Classic Canvas Tote', 'Levi\'s', 'Oversize', '', 549.00, 0, 2, 0, 449.00, 0),
(16, 'Classic Polo Shirt', 'Bench', 'S-XL', '', 549.00, 0, 1, 0, 499.00, 0),
(19, 'Basic Cotton Crew Neck Shirt	', 'Uniqlo', 'S-XL', '', 590.00, 17, 1, 1, 399.00, 1),
(20, 'Cotton Twill Shorts', 'Uniqlo', 'S-L', '', 500.00, 17, 3, 1, 350.00, 0),
(21, 'Basic Ankle Socks (3-Pack)', 'Nike', 'One-Size', '', 350.00, 19, 7, 1, 299.00, 0),
(22, ' Essential Jersey Shorts', 'H&M', 'XS-XL', '', 899.00, 18, 3, 1, 799.00, 1),
(26, 'Graphic Tee', 'Penshoppe', 'X-L', '', 499.00, 16, 1, 1, 300.00, 1),
(27, 'Stretchable Walking Pants', 'Bench', 'S-L', '', 1299.00, 16, 4, 1, 999.00, 0),
(28, 'Performance Dry-Fit Shirt', 'Adidas', 'M-XL', '', 1495.00, 3, 1, 1, 900.00, 0),
(29, 'Logo Tee', 'Nike', 'S-L', '', 1795.00, 11, 1, 1, 1050.00, 0),
(31, 'Oversized Cotton Shirt', 'H&M', 'S-XL', '', 699.00, 49, 1, 1, 420.00, 0),
(32, 'Dri-Fit Cotton Blend Shirt', 'Under Armour', 'M-XL', '', 1895.00, 29, 1, 1, 1100.00, 0),
(33, 'Plain White Shirt (3-Pack)', 'Giordano', 'M-XL', '', 1199.00, 39, 1, 1, 750.00, 0),
(34, 'Slim Fit Printed Tee', 'Levi\'s', 'S-L', '', 1090.00, 35, 1, 1, 650.00, 0),
(35, 'Essentials Box Logo Shirt', 'The North Face', 'S-L', '', 1650.00, 24, 1, 1, 950.00, 0),
(36, 'Classic Pullover Hoodie', 'Uniqlo', 'S-XL', '', 1490.00, 38, 2, 1, 900.00, 0),
(37, 'Essentials Oversized Hoodie', 'H&M', 'XS-XXL', '', 1590.00, 32, 2, 1, 950.00, 0),
(38, 'Graphic Fleece Hoodie', 'Penshoppe', 'S-L', '', 899.00, 50, 2, 1, 550.00, 0),
(39, 'Adicolor Classics Trefoil Hoodie', 'Adidas', 'M-XL', '', 3300.00, 25, 2, 1, 2200.00, 0),
(40, 'Club Fleece Pullover Hoodie', 'Nike', 'S-XL', '', 2595.00, 30, 2, 1, 1800.00, 0),
(41, 'UA Rival Fleece Hoodie', 'Under Armour', 'M-XXL', '', 2995.00, 20, 2, 1, 2000.00, 0),
(42, 'Logo Pullover Hoodie', 'Levi\'s', 'S-XL', '', 2499.00, 25, 2, 1, 1500.00, 0),
(43, 'Fleece Hoodie', 'Bench', 'XS-XL', '', 799.00, 59, 2, 1, 500.00, 0),
(44, 'Heritage Oversized Hoodie', 'The North Face', 'M-XL', '', 3800.00, 15, 2, 1, 2500.00, 0),
(45, 'Oversized Hoodie', 'Zara', 'S-L', '', 2795.00, 20, 2, 1, 1800.00, 0),
(46, 'Cotton Twill Shorts', 'Uniqlo', 'S-XL', '', 990.00, 49, 3, 1, 650.00, 0),
(47, 'Essential Jersey Shorts', 'H&M', 'XS-XL', '', 899.00, 58, 3, 1, 550.00, 0),
(48, 'Printed Drawstring Shorts', 'Penshoppe', 'S-L', '', 499.00, 69, 3, 1, 300.00, 0),
(49, '3-Stripes Woven Shorts', 'Adidas', 'M-XL', '', 1800.00, 29, 3, 1, 1200.00, 0),
(50, 'Dri-FIT Training Shorts', 'Nike', 'S-XXL', '', 1750.00, 34, 3, 1, 1100.00, 0),
(51, 'Rival Fleece Shorts', 'Under Armour', 'M-XXL', '', 2495.00, 19, 3, 1, 1600.00, 0),
(52, '5-Pocket Denim Shorts', 'Levi\'s', 'S-L', '', 1799.00, 24, 3, 1, 1100.00, 0),
(53, 'Stretchable Walking Shorts', 'Bench', 'XS-XL', '', 799.00, 77, 3, 1, 500.00, 0),
(54, 'Heritage Shorts', 'The North Face', 'M-XL', '', 2800.00, 14, 3, 1, 1900.00, 0),
(55, 'Relaxed Fit Cotton Shorts', 'Zara', 'S-L', '', 1495.00, 39, 3, 1, 900.00, 0),
(56, 'Slim Fit Chino Pants', 'Uniqlo', '28-36', '', 1499.00, 37, 4, 1, 1000.00, 0),
(57, 'Straight Leg Denim Pants', 'Levi\'s', '30-40', '', 1999.00, 27, 4, 1, 1200.00, 0),
(58, 'Tailored Wool Pants', 'Zara', 'S-L', '', 2895.00, 19, 4, 1, 1700.00, 0),
(59, 'Cargo Pants', 'H&M', '28-34', '', 999.00, 49, 4, 1, 650.00, 0),
(60, 'Stretch Jogger Pants', 'Adidas', 'S-XL', '', 1795.00, 39, 4, 1, 1200.00, 0),
(61, 'Tapered Fit Denim', 'Uniqlo', '28-34', '', 1699.00, 34, 4, 1, 1100.00, 0),
(62, 'Chino Slim Fit Pants', 'Penshoppe', '28-36', '', 1199.00, 45, 4, 1, 800.00, 0),
(63, 'Relaxed Fit Linen Pants', 'H&M', 'S-XL', '', 1399.00, 58, 4, 1, 950.00, 0),
(64, 'Black Dress Pants', 'Banana Republic', '30-40', '', 2999.00, 23, 4, 1, 2000.00, 0),
(65, 'Jogger Pants', 'Nike', 'S-XXL', '', 2299.00, 28, 4, 1, 1500.00, 0),
(66, 'Baseball Cap', 'Nike', 'One Size', '', 999.00, 39, 5, 1, 600.00, 0),
(67, 'Snapback Cap', 'Adidas', 'One Size', '', 1299.00, 34, 5, 1, 800.00, 0),
(68, 'Trucker Cap', 'New Era', 'One Size', '', 899.00, 50, 5, 1, 500.00, 0),
(69, 'Bucket Hat', 'Puma', 'One Size', '', 799.00, 44, 5, 1, 450.00, 0),
(70, 'Visor Cap', 'Under Armour', 'One Size', '', 749.00, 60, 5, 1, 400.00, 0),
(71, 'Floral Print Blouse', 'Fashionista', 'S-M', '', 1299.00, 28, 6, 1, 899.00, 0),
(72, 'V-Neck T-Shirt', 'Zara', 'S-L', '', 799.00, 44, 6, 1, 500.00, 0),
(73, 'Off-Shoulder Top', 'H&M', 'M-L', '', 999.00, 39, 6, 1, 600.00, 0),
(74, 'Ruffle Sleeve Blouse', 'Uniqlo', 'S-XL', '', 1399.00, 35, 6, 1, 900.00, 0),
(75, 'Basic Tank Top', 'H&M', 'S-XL', '', 499.00, 48, 6, 1, 350.00, 0),
(76, 'Peplum Blouse', 'Mango', 'M-L', '', 1499.00, 24, 6, 1, 1000.00, 0),
(77, 'Chiffon Blouse', 'Forever 21', 'S-XL', '', 1199.00, 29, 6, 1, 800.00, 0),
(78, 'Button-Up Shirt', 'Uniqlo', 'S-XL', '', 1299.00, 39, 6, 1, 800.00, 0),
(79, 'Cotton Ankle Socks', 'Uniqlo', 'S-L', '', 299.00, 100, 7, 1, 150.00, 0),
(80, 'Sports Crew Socks', 'Nike', 'M-L', '', 399.00, 79, 7, 1, 250.00, 0),
(81, 'Compression Socks', 'Adidas', 'S-XL', '', 799.00, 59, 7, 1, 500.00, 0),
(82, 'Wool Blend Socks', 'H&M', 'M-L', '', 599.00, 49, 7, 1, 400.00, 0),
(83, 'Classic Flip-Flops', 'Havaianas', 'S-XL', '', 799.00, 119, 8, 1, 500.00, 0),
(84, 'Sport Slides', 'Adidas', 'M-XL', '', 1299.00, 79, 8, 1, 850.00, 0),
(85, 'Beach Slippers', 'Uniqlo', 'S-L', '', 499.00, 148, 8, 1, 350.00, 0),
(86, 'Velcro Strap Slippers', 'Nike', 'S-XL', '', 999.00, 89, 8, 1, 650.00, 0),
(87, 'Comfort Foam Slippers', 'Crocs', 'M-L', '', 1599.00, 59, 8, 1, 1000.00, 0),
(88, 'Air Max 90', 'Nike', '7-13', '', 6999.00, 49, 9, 1, 5000.00, 0),
(89, 'UltraBoost 22', 'Adidas', '6-12', '', 8999.00, 39, 9, 1, 6500.00, 0),
(90, 'Classic Leather Sneakers', 'Puma', '6-12', '', 3499.00, 78, 9, 1, 2500.00, 0),
(91, 'Cortez Basic', 'Nike', '5-11', '', 2999.00, 58, 9, 1, 2000.00, 0),
(92, 'Superstar Sneakers', 'Adidas', '5-11', '', 5499.00, 69, 9, 1, 4000.00, 0),
(93, 'Horizon Low', 'Under Armour', '7-13', '', 4999.00, 49, 9, 1, 3500.00, 0),
(94, 'Iconic Chuck Taylor', 'Converse', '6-12', '', 3999.00, 98, 9, 1, 2700.00, 0),
(95, 'Fury 2.0 Running Shoes', 'Reebok', '7-13', '', 4999.00, 89, 9, 1, 3500.00, 0),
(96, 'Leather Tote Bag', 'Coach', 'One Size', '', 8999.00, 49, 10, 1, 7000.00, 0),
(97, 'Canvas Backpack', 'Herschel', 'One Size', '', 3499.00, 78, 10, 1, 2500.00, 0),
(98, 'Vintage Messenger Bag', 'Timberland', 'One Size', '', 4999.00, 59, 10, 1, 3500.00, 0),
(99, 'Duffel Sports Bag', 'Nike', 'One Size', '', 2999.00, 89, 10, 1, 2000.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `itemcolors`
--

CREATE TABLE `itemcolors` (
  `item_id` int(11) NOT NULL,
  `color_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `itemcolors`
--

INSERT INTO `itemcolors` (`item_id`, `color_id`) VALUES
(1, 3),
(2, 7),
(3, 1),
(3, 2),
(3, 3),
(4, 1),
(4, 2),
(4, 3),
(5, 1),
(5, 4),
(6, 5),
(6, 6),
(7, 7),
(7, 8),
(8, 3),
(9, 1),
(11, 7),
(11, 9),
(11, 10),
(11, 11),
(11, 12),
(11, 13),
(13, 7),
(13, 11),
(14, 2),
(14, 14),
(15, 3),
(16, 10),
(19, 1),
(19, 15),
(20, 2),
(20, 7),
(20, 16),
(21, 1),
(21, 15),
(22, 6),
(22, 10),
(22, 17),
(22, 18),
(26, 19),
(26, 20),
(26, 21),
(27, 22),
(27, 23),
(27, 24),
(27, 25),
(28, 26),
(29, 1),
(29, 2),
(29, 3),
(29, 9),
(31, 3),
(31, 7),
(32, 2),
(32, 4),
(32, 5),
(33, 4),
(33, 5),
(34, 1),
(34, 9),
(35, 2),
(35, 8),
(36, 1),
(36, 5),
(37, 2),
(37, 3),
(37, 9),
(38, 4),
(38, 5),
(38, 7),
(39, 1),
(39, 3),
(39, 9),
(40, 1),
(40, 7),
(41, 3),
(41, 5),
(41, 6),
(42, 2),
(42, 4),
(42, 5),
(43, 4),
(43, 5),
(43, 7),
(44, 3),
(44, 5),
(44, 9),
(45, 3),
(45, 7),
(45, 9),
(46, 4),
(46, 5),
(46, 7),
(47, 2),
(47, 4),
(47, 7),
(48, 5),
(48, 6),
(48, 8),
(49, 1),
(49, 4),
(49, 9),
(50, 3),
(50, 5),
(50, 7),
(51, 2),
(51, 5),
(51, 7),
(52, 3),
(52, 4),
(52, 6),
(53, 5),
(53, 6),
(53, 7),
(54, 1),
(54, 3),
(54, 9),
(55, 5),
(55, 7),
(55, 9),
(56, 4),
(56, 5),
(56, 7),
(57, 5),
(57, 8),
(57, 9),
(58, 4),
(58, 6),
(58, 7),
(59, 3),
(59, 6),
(59, 7),
(60, 2),
(60, 5),
(60, 7),
(61, 5),
(61, 7),
(61, 9),
(62, 3),
(62, 6),
(62, 7),
(63, 4),
(63, 5),
(63, 6),
(64, 4),
(64, 5),
(64, 7),
(65, 2),
(65, 3),
(65, 7),
(66, 4),
(66, 5),
(66, 7),
(67, 2),
(67, 7),
(67, 9),
(68, 3),
(68, 6),
(68, 7),
(69, 6),
(69, 7),
(69, 8),
(70, 2),
(70, 5),
(70, 7),
(71, 6),
(71, 8),
(71, 19),
(72, 3),
(72, 5),
(72, 7),
(73, 7),
(73, 9),
(73, 12),
(74, 2),
(74, 5),
(74, 6),
(75, 5),
(75, 6),
(75, 7),
(76, 1),
(76, 3),
(76, 4),
(77, 2),
(77, 6),
(77, 8),
(78, 5),
(78, 7),
(78, 9),
(79, 5),
(79, 7),
(79, 9),
(80, 4),
(80, 5),
(80, 7),
(81, 3),
(81, 6),
(81, 7),
(82, 6),
(82, 9),
(82, 12),
(83, 3),
(83, 5),
(83, 7),
(84, 5),
(84, 6),
(84, 7),
(85, 5),
(85, 9),
(85, 14),
(86, 5),
(86, 7),
(86, 8),
(87, 4),
(87, 5),
(87, 6),
(88, 3),
(88, 5),
(88, 7),
(89, 4),
(89, 5),
(89, 7),
(90, 5),
(90, 7),
(90, 8),
(91, 3),
(91, 5),
(91, 7),
(92, 5),
(92, 6),
(92, 7),
(93, 5),
(93, 6),
(93, 7),
(94, 5),
(94, 7),
(94, 9),
(95, 4),
(95, 5),
(95, 7),
(96, 5),
(96, 6),
(96, 7),
(97, 3),
(97, 5),
(97, 7),
(98, 5),
(98, 7),
(98, 8),
(99, 4),
(99, 5),
(99, 7);

-- --------------------------------------------------------

--
-- Table structure for table `itemhistory`
--

CREATE TABLE `itemhistory` (
  `history_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `action` enum('add','restock','delete','restore') NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `itemhistory`
--

INSERT INTO `itemhistory` (`history_id`, `item_id`, `action`, `user_id`, `details`, `timestamp`) VALUES
(2, 1, 'restock', 3, 'Restocked item with 1 units', '2024-10-25 12:07:05'),
(3, 1, 'delete', 3, 'Item Deleted', '0000-00-00 00:00:00'),
(4, 1, 'restore', 3, 'Restored item', '0000-00-00 00:00:00'),
(5, 1, 'restock', 3, 'Restocked item with 1 units', '0000-00-00 00:00:00'),
(6, 1, 'restock', 3, 'Restocked item with 1 units', '2024-10-25 12:07:05'),
(7, 2, 'add', 3, 'Added new item', '2024-10-25 12:11:18'),
(8, 2, 'restock', 3, 'Restocked item with 1 units', '2024-10-25 12:11:22'),
(9, 2, 'restock', 3, 'Restocked item with 1 units', '2024-10-25 12:11:26'),
(10, 2, 'restock', 3, 'Restocked item with 1 units', '2024-10-25 12:11:29'),
(11, 2, 'restock', 3, 'Restocked item with 1 units', '2024-10-25 12:11:32'),
(12, 2, 'delete', 3, 'Item Deleted', '2024-10-25 12:11:36'),
(13, 2, 'restore', 3, 'Restored item', '2024-10-25 12:11:39'),
(14, 2, 'delete', 3, 'Item Deleted', '2024-10-25 12:11:42'),
(15, 2, 'restore', 3, 'Restored item', '2024-10-25 12:11:45'),
(20, 2, 'restock', 3, 'Restocked item with 1 units', '2024-10-25 12:42:07'),
(21, 2, 'restock', 3, 'Restocked item with 1 units', '2024-10-25 12:43:18'),
(22, 2, 'restock', 3, 'Restocked item with 1 units', '2024-10-25 12:43:41'),
(23, 2, 'restock', 3, 'Restocked item with 1 units', '2024-10-25 12:48:14'),
(24, 2, 'restock', 3, 'Restocked item with 1 units', '2024-10-25 12:48:36'),
(25, 2, 'restock', 3, 'Restocked item with 1 units', '2024-10-25 12:49:49'),
(26, 2, 'restock', 3, 'Restocked item with 1 units', '2024-10-25 12:51:13'),
(27, 2, 'delete', 3, 'Item Deleted', '2024-10-25 13:31:32'),
(28, 2, 'restore', 3, 'Restored item', '2024-10-25 13:31:35'),
(29, 3, 'add', 3, 'Added new item: Bag1', '2024-10-25 14:59:11'),
(30, 3, 'delete', 3, 'Item Deleted', '2024-10-25 15:02:18'),
(31, 3, 'restore', 3, 'Restored item', '2024-10-25 15:02:21'),
(32, 4, 'add', 3, 'Added new item: Bag2', '2024-10-25 15:10:19'),
(33, 4, 'delete', 3, 'Item Deleted', '2024-10-25 15:10:50'),
(34, 4, 'restore', 3, 'Restored item', '2024-10-25 15:10:54'),
(35, 5, 'add', 3, 'Added new item: Adizero Boston 10', '2024-10-25 16:16:07'),
(36, 6, 'add', 3, 'Added new item: Graphic Tee', '2024-10-25 16:19:00'),
(37, 4, 'delete', 3, 'Item Deleted', '2024-10-25 16:36:51'),
(38, 4, 'restore', 3, 'Restored item', '2024-10-25 16:36:54'),
(39, 7, 'add', 3, 'Added new item: Basic Pullover Hoodie', '2024-10-25 17:07:29'),
(40, 8, 'add', 3, 'Added new item: Vintage Cap', '2024-10-25 17:09:07'),
(41, 9, 'add', 3, 'Added new item: Zip-Up Hoodie', '2024-10-25 19:02:43'),
(43, 11, 'add', 3, 'Added new item: Warmth Fleece Hoodie', '2024-10-25 19:17:31'),
(44, 1, 'restock', 3, 'Restocked item with 1 units', '2024-10-25 19:25:31'),
(45, 6, 'delete', 3, 'Item Deleted', '2024-10-25 19:25:35'),
(46, 6, 'restore', 3, 'Restored item', '2024-10-25 19:25:38'),
(47, 7, 'delete', 3, 'Item Deleted', '2024-10-26 12:06:07'),
(48, 7, 'restore', 3, 'Restored item', '2024-10-26 12:50:49'),
(49, 8, 'restock', 3, 'Restocked item with 1 units', '2024-10-26 14:24:51'),
(50, 8, 'delete', 3, 'Item Deleted', '2024-10-26 14:24:55'),
(51, 8, 'restore', 3, 'Restored item', '2024-10-26 14:25:04'),
(53, 13, 'add', 3, 'Added new item: Authentic Slim Stretch Chino', '2024-10-26 14:39:31'),
(54, 1, 'restock', 3, 'Restocked item with 1 units', '2024-10-26 21:22:52'),
(55, 1, 'delete', 3, 'Item Deleted', '2024-10-26 21:23:28'),
(56, 1, 'restore', 3, 'Restored item', '2024-10-26 21:23:35'),
(57, 3, 'restock', 4, 'Restocked item with 1 units', '2024-10-26 21:37:31'),
(58, 4, 'delete', 3, 'Item Deleted', '2024-10-26 21:38:12'),
(59, 4, 'restore', 3, 'Restored item', '2024-10-26 21:38:16'),
(60, 1, 'delete', 3, 'Item Deleted', '2024-10-27 19:44:11'),
(61, 1, 'restore', 3, 'Restored item', '2024-10-27 19:44:15'),
(62, 1, 'restock', 3, 'Restocked item with 1 units', '2024-10-28 13:19:18'),
(63, 1, 'restock', 3, 'Restocked item with 1 units', '2024-10-28 13:19:23'),
(64, 13, 'delete', 3, 'Item Deleted', '2024-10-28 13:49:01'),
(65, 13, 'restore', 3, 'Restored item', '2024-10-28 13:57:41'),
(66, 11, 'delete', 3, 'Item Deleted', '2024-10-28 15:08:06'),
(67, 11, 'restore', 3, 'Restored item', '2024-10-28 15:09:47'),
(68, 6, 'delete', 3, 'Item Deleted', '2024-10-28 15:42:33'),
(69, 6, 'restore', 3, 'Restored item', '2024-10-28 15:42:37'),
(70, 1, 'restock', 3, 'Restocked item with 1 units', '2024-10-28 16:07:54'),
(71, 1, 'delete', 3, 'Item Deleted', '2024-10-28 16:08:14'),
(72, 1, 'restore', 3, 'Restored item', '2024-10-28 16:08:19'),
(73, 1, 'delete', 3, 'Item Deleted', '2024-10-28 16:10:36'),
(74, 1, 'restore', 3, 'Restored item', '2024-10-28 16:10:57'),
(75, 1, 'restock', 3, 'Restocked item with 1 units', '2024-10-28 16:13:06'),
(76, 14, 'add', 3, 'Added new item: Floral Print Blouse', '2024-10-28 16:16:27'),
(77, 5, 'delete', 3, 'Item Deleted', '2024-10-28 16:19:36'),
(78, 5, 'restore', 3, 'Restored item', '2024-10-28 16:19:41'),
(79, 5, 'delete', 3, 'Item Deleted', '2024-10-28 16:21:48'),
(80, 5, 'restore', 3, 'Restored item', '2024-10-28 16:21:59'),
(81, 11, 'restock', 3, 'Restocked item with 1 units', '2024-10-28 17:02:56'),
(82, 9, 'restock', 3, 'Restocked item with 1 units', '2024-10-28 17:03:03'),
(83, 9, 'delete', 3, 'Item Deleted', '2024-10-28 17:03:07'),
(84, 9, 'restore', 3, 'Restored item', '2024-10-28 17:03:10'),
(85, 11, 'delete', 3, 'Item Deleted', '2024-10-28 17:03:25'),
(86, 11, 'restore', 3, 'Restored item', '2024-10-28 17:03:29'),
(87, 1, 'restock', 3, 'Restocked item with 1 units', '2024-10-28 17:07:32'),
(88, 1, 'restock', 3, 'Restocked item with 1 units', '2024-10-28 17:07:36'),
(89, 6, 'delete', 3, 'Item Deleted', '2024-10-28 17:07:39'),
(90, 6, 'restore', 3, 'Restored item', '2024-10-28 17:07:44'),
(91, 1, 'restock', 3, 'Restocked item with 1 units', '2024-10-28 18:03:11'),
(92, 1, 'restock', 3, 'Restocked item with 1 units', '2024-10-28 18:03:14'),
(93, 1, 'restock', 3, 'Restocked item with 1 units', '2024-10-28 18:03:16'),
(94, 1, 'restock', 3, 'Restocked item with 1 units', '2024-10-28 18:03:40'),
(95, 9, 'restock', 3, 'Restocked item with 1 units', '2024-10-28 18:03:52'),
(96, 7, 'restock', 3, 'Restocked item with 1 units', '2024-10-28 18:04:35'),
(97, 7, 'delete', 3, 'Item Deleted', '2024-10-28 18:04:46'),
(98, 7, 'restore', 3, 'Restored item', '2024-10-28 18:04:53'),
(99, 1, 'restock', 3, 'Restocked item with 1 units', '2024-10-28 18:07:55'),
(100, 7, 'restock', 3, 'Restocked item with 1 units', '2024-10-28 18:09:18'),
(101, 1, 'restock', 3, 'Restocked item with 1 units', '2024-10-28 18:14:38'),
(102, 1, 'restock', 3, 'Restocked item with 1 units', '2024-10-28 18:14:38'),
(103, 1, 'restock', 3, 'Restocked item with 1 units', '2024-10-28 18:14:43'),
(104, 1, 'restock', 3, 'Restocked item with 1 units', '2024-10-28 18:14:43'),
(105, 1, 'restock', 3, 'Restocked item with 1 units', '2024-10-28 18:16:23'),
(106, 1, 'restock', 3, 'Restocked item with 1 units', '2024-10-28 18:16:26'),
(107, 7, 'delete', 3, 'Item Deleted', '2024-10-28 18:16:53'),
(108, 7, 'restore', 3, 'Restored item', '2024-10-28 18:16:57'),
(109, 11, 'delete', 3, 'Item Deleted', '2024-10-28 18:19:15'),
(110, 11, 'restore', 3, 'Restored item', '2024-10-28 18:19:18'),
(111, 9, 'delete', 3, 'Item Deleted', '2024-10-28 18:19:22'),
(112, 9, 'restore', 3, 'Restored item', '2024-10-28 18:19:26'),
(113, 15, 'add', 3, 'Added new item: Test1', '2024-10-28 18:20:53'),
(114, 16, 'add', 3, 'Added new item: try', '2024-10-28 20:22:39'),
(115, 2, 'restock', 3, 'Restocked item with 1 units', '2024-11-03 15:37:01'),
(116, 16, 'delete', 3, 'Item Deleted', '2024-11-09 19:38:02'),
(117, 6, 'restock', 3, 'Restocked item with 1 units', '2024-11-10 18:05:30'),
(118, 6, 'restock', 3, 'Restocked item with 1 units', '2024-11-10 18:05:35'),
(119, 6, 'restock', 3, 'Restocked item with 13 units', '2024-11-10 18:05:40'),
(120, 6, 'restock', 3, 'Restocked item with 13 units', '2024-11-10 18:05:43'),
(121, 2, 'restock', 3, 'Restocked item with 1 units', '2024-11-10 18:05:46'),
(122, 2, 'restock', 3, 'Restocked item with 1 units', '2024-11-10 18:05:51'),
(123, 2, 'restock', 3, 'Restocked item with 1 units', '2024-11-10 18:05:55'),
(124, 2, 'restock', 3, 'Restocked item with 1 units', '2024-11-10 18:05:59'),
(125, 2, 'restock', 3, 'Restocked item with 1 units', '2024-11-10 18:06:18'),
(126, 1, 'restock', 3, 'Restocked item with 1 units', '2024-11-10 18:07:33'),
(127, 1, 'restock', 3, 'Restocked item with 1 units', '2024-11-10 18:07:36'),
(128, 1, 'restock', 3, 'Restocked item with 1 units', '2024-11-10 18:07:38'),
(129, 1, 'restock', 3, 'Restocked item with 1 units', '2024-11-10 18:07:41'),
(130, 1, 'restock', 3, 'Restocked item with 1 units', '2024-11-10 18:07:42'),
(131, 11, 'restock', 3, 'Restocked item with 1 units', '2024-11-10 18:08:00'),
(132, 11, 'restock', 3, 'Restocked item with 1 units', '2024-11-10 18:09:21'),
(133, 11, 'delete', 3, 'Item Deleted', '2024-11-10 18:09:35'),
(134, 19, 'add', 3, 'Added new item: Ferdinand De Lesseps', '2024-11-10 18:20:00'),
(135, 6, 'delete', 3, 'Item Deleted', '2024-11-10 18:26:09'),
(136, 6, 'restore', 3, 'Restored item', '2024-11-10 18:49:06'),
(137, 6, 'restore', 3, 'Restored item', '2024-11-10 18:49:22'),
(138, 6, 'delete', 3, 'Item Deleted', '2024-11-10 18:49:30'),
(139, 6, 'restore', 3, 'Restored item', '2024-11-10 18:49:33'),
(140, 6, 'restore', 3, 'Restored item', '2024-11-10 18:50:08'),
(141, 6, 'delete', 3, 'Item Deleted', '2024-11-10 18:50:10'),
(142, 6, 'restore', 3, 'Restored item', '2024-11-10 18:50:12'),
(143, 6, 'restore', 3, 'Restored item', '2024-11-10 18:50:17'),
(144, 6, 'delete', 3, 'Item Deleted', '2024-11-10 18:50:25'),
(145, 6, 'restore', 3, 'Restored item', '2024-11-10 18:51:34'),
(146, 11, 'restore', 3, 'Restored item', '2024-11-10 18:51:36'),
(147, 11, 'restore', 3, 'Restored item', '2024-11-10 18:52:49'),
(148, 6, 'delete', 3, 'Item Deleted', '2024-11-10 18:52:51'),
(149, 6, 'restore', 3, 'Restored item', '2024-11-10 18:52:54'),
(150, 6, 'delete', 3, 'Item Deleted', '2024-11-10 18:53:05'),
(151, 19, 'delete', 3, 'Item Deleted', '2024-11-10 18:53:07'),
(152, 6, 'restore', 3, 'Restored item', '2024-11-10 18:53:25'),
(153, 6, 'restore', 3, 'Restored item', '2024-11-10 18:54:04'),
(154, 1, 'restock', 3, 'Restocked item with 1 units', '2024-11-10 18:57:07'),
(155, 1, 'delete', 3, 'Item Deleted', '2024-11-10 18:57:10'),
(157, 2, 'delete', 3, 'Item Deleted', '2024-11-14 18:36:24'),
(162, 7, 'delete', 3, 'Item Deleted', '2024-11-14 18:42:11'),
(163, 9, 'delete', 3, 'Item Deleted', '2024-11-14 18:42:13'),
(164, 11, 'delete', 3, 'Item Deleted', '2024-11-14 18:42:15'),
(167, 1, 'delete', 3, 'Item Deleted', '2024-11-14 18:44:07'),
(168, 2, 'delete', 3, 'Item Deleted', '2024-11-14 18:44:09'),
(169, 16, 'delete', 3, 'Item Deleted', '2024-11-14 18:44:11'),
(170, 19, 'delete', 3, 'Item Deleted', '2024-11-14 18:44:13'),
(171, 7, 'delete', 3, 'Item Deleted', '2024-11-14 18:44:17'),
(176, 19, 'restore', NULL, 'Restored item with ID: 19', '2024-11-14 18:51:21'),
(177, 1, 'delete', 3, 'Item Deleted', '2024-11-14 18:51:30'),
(178, 2, 'delete', 3, 'Item Deleted', '2024-11-14 18:51:32'),
(179, 1, 'restore', NULL, 'Restored item with ID: 1', '2024-11-14 18:51:42'),
(180, 2, 'restore', NULL, 'Restored item with ID: 2', '2024-11-14 18:52:17'),
(181, 16, 'restore', NULL, 'Restored item with ID: 16', '2024-11-14 18:54:06'),
(182, 1, 'delete', 3, 'Item Deleted', '2024-11-14 18:54:33'),
(183, 6, 'delete', 3, 'Item Deleted', '2024-11-14 18:54:34'),
(184, 2, 'delete', 3, 'Item Deleted', '2024-11-14 18:54:36'),
(185, 1, 'restore', NULL, 'Restored item with ID: 1', '2024-11-14 18:54:40'),
(186, 2, 'restore', NULL, 'Restored item with ID: 2', '2024-11-14 18:54:50'),
(187, 1, 'delete', 3, 'Item Deleted', '2024-11-14 19:03:35'),
(188, 1, 'restore', NULL, 'Restored item with ID: 1', '2024-11-14 19:03:40'),
(189, 2, 'delete', 3, 'Item Deleted', '2024-11-14 19:25:36'),
(190, 16, 'delete', 3, 'Item Deleted', '2024-11-14 19:25:39'),
(191, 2, 'restore', NULL, 'Restored item with ID: 2', '2024-11-14 19:25:44'),
(192, 19, 'restock', 3, 'Restocked item with 1 units', '2024-11-14 21:03:33'),
(193, 20, 'add', 3, 'Added new item: Logia De Acacia', '2024-11-15 19:07:22'),
(194, 21, 'add', 3, 'Added new item: Salvadora', '2024-11-15 19:12:44'),
(195, 22, 'add', 3, 'Added new item: Consuelo Ortiga y Perez', '2024-11-15 19:16:46'),
(199, 3, 'delete', 3, 'Item Deleted', '2024-11-15 20:55:59'),
(200, 3, 'delete', 3, 'Item Deleted', '2024-11-15 20:56:04'),
(201, 13, 'delete', 3, 'Item Deleted', '2024-11-15 22:26:44'),
(202, 5, 'delete', 3, 'Item Deleted', '2024-11-15 22:26:50'),
(203, 14, 'delete', 3, 'Item Deleted', '2024-11-15 22:29:39'),
(204, 14, 'delete', 3, 'Item Deleted', '2024-11-15 22:29:57'),
(205, 4, 'restock', 3, 'Restocked item with 1 units', '2024-11-15 22:30:56'),
(206, 4, 'delete', 3, 'Item Deleted', '2024-11-15 22:30:59'),
(207, 5, 'restore', NULL, 'Restored item with ID: 5', '2024-11-15 22:35:18'),
(208, 19, 'restock', 3, 'Restocked item with 1 units', '2024-11-16 21:46:00'),
(209, 6, 'restore', NULL, 'Restored item with ID: 6', '2024-11-17 15:52:28'),
(210, 5, 'delete', 3, 'Item Deleted', '2024-11-18 12:48:27'),
(211, 5, 'delete', 3, 'Item Deleted', '2024-11-18 12:48:55'),
(212, 16, 'delete', 3, 'Item Deleted', '2024-11-18 12:49:12'),
(213, 15, 'delete', 3, 'Item Deleted', '2024-11-18 12:49:17'),
(214, 15, 'delete', 3, 'Item Deleted', '2024-11-18 12:49:30'),
(215, 16, 'delete', 3, 'Item Deleted', '2024-11-18 12:49:54'),
(216, 16, 'delete', 3, 'Item Deleted', '2024-11-18 12:49:59'),
(217, 16, 'delete', 3, 'Item Deleted', '2024-11-18 12:51:29'),
(218, 2, 'restock', 3, 'Restocked item with 1 units', '2024-11-18 12:55:12'),
(219, 2, 'delete', 3, 'Item Deleted', '2024-11-18 12:55:16'),
(220, 16, 'delete', 3, 'Item Deleted', '2024-11-18 12:55:29'),
(221, 16, 'delete', 3, 'Item Deleted', '2024-11-18 12:56:05'),
(222, 16, 'delete', 3, 'Item Deleted', '2024-11-18 12:56:34'),
(223, 16, 'delete', 3, 'Item Deleted', '2024-11-18 13:00:05'),
(224, 16, 'delete', 3, 'Item Deleted', '2024-11-18 13:02:11'),
(225, 16, 'delete', 3, 'Item Deleted', '2024-11-18 13:03:42'),
(226, 5, 'delete', 3, 'Item Deleted', '2024-11-18 13:04:38'),
(227, 16, 'delete', 3, 'Item Deleted', '2024-11-18 13:04:43'),
(228, 16, 'delete', 3, 'Item Deleted', '2024-11-18 13:08:21'),
(229, 14, 'delete', 3, 'Item Deleted', '2024-11-18 13:09:20'),
(230, 14, 'delete', 3, 'Item Deleted', '2024-11-18 13:09:26'),
(231, 14, 'delete', 3, 'Item Deleted', '2024-11-18 13:10:52'),
(232, 14, 'delete', 3, 'Item Deleted', '2024-11-18 13:11:27'),
(233, 2, 'restore', NULL, 'Restored item with ID: 2', '2024-11-18 13:27:42'),
(234, 5, 'restore', NULL, 'Restored item with ID: 5', '2024-11-18 13:27:49'),
(235, 5, 'restock', 3, 'Restocked item with 1 units', '2024-11-18 15:40:40'),
(236, 19, 'delete', 3, 'Item Deleted', '2024-11-18 15:55:34'),
(237, 6, 'delete', 3, 'Item Deleted', '2024-11-18 15:55:37'),
(238, 26, 'add', 3, 'Added new item: Diariong Tagalog', '2024-11-18 15:58:17'),
(239, 26, 'restock', 3, 'Restocked item with 1 units', '2024-11-18 15:58:27'),
(240, 6, 'restore', NULL, 'Restored item with ID: 6', '2024-11-18 15:58:33'),
(241, 13, 'restore', NULL, 'Restored item with ID: 13', '2024-11-18 15:58:36'),
(242, 27, 'add', 3, 'Added new item: Amor Patrio', '2024-11-18 17:53:10'),
(243, 19, 'restore', NULL, 'Restored item with ID: 19', '2024-11-18 18:18:05'),
(244, 13, 'restock', 3, 'Restocked item with 10 units', '2024-11-22 21:33:02'),
(245, 27, 'restock', 3, 'Restocked item with 20 units', '2024-11-22 21:33:06'),
(246, 20, 'restock', 3, 'Restocked item with 8 units', '2024-11-22 21:33:10'),
(247, 22, 'restock', 3, 'Restocked item with 12 units', '2024-11-22 21:33:16'),
(248, 2, 'restock', 3, 'Restocked item with 23 units', '2024-11-22 21:33:23'),
(249, 7, 'restock', 3, 'Restocked item with 21 units', '2024-11-23 18:00:13'),
(250, 9, 'restock', 3, 'Restocked item with 21 units', '2024-11-23 18:00:15'),
(251, 11, 'restock', 3, 'Restocked item with 21 units', '2024-11-23 18:00:17'),
(252, 1, 'restock', 3, 'Restocked item with 21 units', '2024-11-23 18:00:21'),
(253, 2, 'restock', 3, 'Restocked item with 21 units', '2024-11-23 18:00:23'),
(254, 6, 'restock', 3, 'Restocked item with 21 units', '2024-11-23 18:00:26'),
(255, 19, 'restock', 3, 'Restocked item with 21 units', '2024-11-23 18:00:30'),
(256, 26, 'restock', 3, 'Restocked item with 21 units', '2024-11-23 18:00:33'),
(257, 13, 'restock', 3, 'Restocked item with 21 units', '2024-11-23 18:00:37'),
(258, 27, 'restock', 3, 'Restocked item with 16 units', '2024-11-23 18:00:39'),
(259, 20, 'restock', 3, 'Restocked item with 21 units', '2024-11-23 18:00:45'),
(260, 22, 'restock', 3, 'Restocked item with 21 units', '2024-11-23 18:00:47'),
(261, 8, 'restock', 3, 'Restocked item with 21 units', '2024-11-23 18:00:52'),
(262, 21, 'restock', 3, 'Restocked item with 19 units', '2024-11-23 18:00:58'),
(263, 5, 'restock', 3, 'Restocked item with 15 units', '2024-11-23 18:01:03'),
(264, 4, 'restock', 3, 'Restocked item with 1 units', '2024-12-06 20:32:48'),
(265, 1, 'restock', 3, 'Restocked item with 1 units', '2024-12-08 18:40:06'),
(266, 11, 'restock', 3, 'Restocked item with 1 units', '2024-12-08 18:40:21'),
(267, 1, 'restock', 3, 'Restocked item with 1 units', '2024-12-08 18:45:37'),
(268, 14, 'restore', NULL, 'Restored item with ID: 14', '2024-12-12 11:39:40'),
(269, 28, 'add', 3, 'Added new item: Noli Me Tangere', '2024-12-12 13:13:34'),
(270, 29, 'add', 3, 'Added new item: Test1', '2024-12-12 13:14:23'),
(271, 29, 'restock', 3, 'Restocked item with 5 units', '2024-12-12 13:14:55'),
(272, 29, 'delete', 3, 'Item Deleted', '2024-12-12 13:14:59'),
(273, 29, 'restore', NULL, 'Restored item with ID: 29', '2024-12-12 13:15:04'),
(274, 6, 'delete', 3, 'Item Deleted', '2024-12-14 08:58:28'),
(275, 11, 'delete', 3, 'Item Deleted', '2024-12-14 08:58:34');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `sale_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sale_date` datetime NOT NULL DEFAULT current_timestamp(),
  `original_total` decimal(10,2) DEFAULT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `payment` decimal(10,2) NOT NULL,
  `changed` decimal(10,2) NOT NULL,
  `discount_percentage` decimal(5,2) DEFAULT 0.00,
  `discount_id` int(11) DEFAULT NULL,
  `sale_status` enum('Completed','Voided') NOT NULL DEFAULT 'Completed',
  `payment_method` enum('Cash','E-Wallet','Card','Other') NOT NULL DEFAULT 'Cash'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`sale_id`, `user_id`, `sale_date`, `original_total`, `discount_amount`, `total_amount`, `payment`, `changed`, `discount_percentage`, `discount_id`, `sale_status`, `payment_method`) VALUES
(98, 3, '2024-10-15 09:33:12', 7090.00, 0.00, 7090.00, 7100.00, 10.00, 0.00, 1, 'Completed', 'Cash'),
(99, 3, '2024-10-15 09:52:50', 5044.00, 0.00, 5044.00, 5100.00, 56.00, 0.00, 1, 'Completed', 'Cash'),
(100, 3, '2024-10-15 11:41:17', 1558.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 'Voided', 'Cash'),
(101, 3, '2024-10-16 16:18:40', 1699.00, 0.00, 1699.00, 1700.00, 1.00, 0.00, 1, 'Completed', 'Cash'),
(102, 3, '2024-10-16 17:32:04', 3097.00, 0.00, 3097.00, 3100.00, 3.00, 0.00, 1, 'Completed', 'Cash'),
(103, 3, '2024-10-16 18:25:39', 4999.00, 0.00, 4999.00, 5000.00, 1.00, 0.00, 1, 'Completed', 'Cash'),
(104, 3, '2024-10-17 08:36:04', 2097.00, 0.00, 2097.00, 2100.00, 3.00, 0.00, 1, 'Completed', 'Card'),
(105, 3, '2024-10-17 08:45:27', 2698.00, 0.00, 2698.00, 2700.00, 2.00, 0.00, 1, 'Completed', 'Cash'),
(106, 3, '2024-10-17 00:03:55', 1397.00, 0.00, 1397.00, 1400.00, 3.00, 0.00, 1, 'Completed', 'E-Wallet'),
(107, 3, '2024-10-17 14:23:17', 7998.00, 0.00, 7998.00, 8000.00, 2.00, 0.00, 1, 'Completed', 'Card'),
(108, 3, '2024-10-18 11:36:41', 5674.00, 0.00, 5674.00, 6000.00, 326.00, 0.00, 1, 'Completed', 'Cash'),
(109, 3, '2024-10-19 11:00:04', 3794.00, 0.00, 3794.00, 3800.00, 6.00, 0.00, 1, 'Completed', 'E-Wallet'),
(110, 3, '2024-10-19 17:14:35', 10396.00, 0.00, 10396.00, 11500.00, 1104.00, 0.00, 1, 'Completed', 'Card'),
(111, 3, '2024-10-27 17:15:00', 3588.00, 0.00, 3588.00, 3600.00, 12.00, 0.00, 1, 'Completed', 'Cash'),
(112, 3, '2024-10-31 09:37:22', 15997.00, 0.00, 15997.00, 16000.00, 3.00, 0.00, 1, 'Completed', 'Card'),
(113, 3, '2024-11-04 09:37:41', 598.00, 0.00, 598.00, 600.00, 2.00, 0.00, 1, 'Voided', 'Cash'),
(114, 3, '2024-11-11 10:54:05', 4397.00, 0.00, 4397.00, 5000.00, 603.00, 0.00, 1, 'Completed', 'E-Wallet'),
(115, 3, '2024-11-16 16:30:32', 20287.00, 0.00, 20287.00, 25000.00, 4713.00, 0.00, 1, 'Completed', 'Card'),
(116, 3, '2024-11-22 09:15:51', 999.00, 0.00, 999.00, 1000.00, 1.00, 0.00, 1, 'Completed', 'Cash'),
(117, 3, '2024-11-29 10:29:17', 6488.00, 0.00, 6488.00, 6500.00, 12.00, 0.00, 1, 'Completed', 'Cash'),
(118, 3, '2024-12-01 09:47:37', 4698.00, 0.00, 4698.00, 5000.00, 302.00, 0.00, 1, 'Completed', 'Cash'),
(119, 3, '2024-12-01 10:40:08', 14198.00, 0.00, 14198.00, 15000.00, 802.00, 0.00, 1, 'Completed', 'Card'),
(120, 3, '2024-12-01 11:40:30', 5089.00, 0.00, 5089.00, 5100.00, 11.00, 0.00, 1, 'Completed', 'Cash'),
(121, 3, '2024-12-01 13:54:11', 5748.00, 0.00, 5748.00, 6000.00, 252.00, 0.00, 1, 'Completed', 'E-Wallet'),
(122, 3, '2024-12-03 10:34:33', 3798.00, 0.00, 3798.00, 4000.00, 202.00, 0.00, 1, 'Completed', 'Cash'),
(123, 3, '2024-12-03 16:32:56', 6998.00, 0.00, 6998.00, 7000.00, 2.00, 0.00, 1, 'Completed', 'Cash'),
(124, 3, '2024-12-03 17:02:16', 2200.00, 0.00, 2200.00, 2300.00, 100.00, 0.00, 1, 'Completed', 'E-Wallet'),
(125, 3, '2024-12-03 17:02:37', 2988.00, 0.00, 2988.00, 3000.00, 12.00, 0.00, 1, 'Completed', 'Cash'),
(126, 3, '2024-12-06 08:17:01', 2598.00, 0.00, 2598.00, 3000.00, 402.00, 0.00, 1, 'Completed', 'Cash'),
(127, 3, '2024-12-06 10:43:27', 2598.00, 0.00, 2598.00, 3000.00, 402.00, 0.00, 1, 'Completed', 'Cash'),
(128, 3, '2024-12-09 13:10:55', 7518.00, 0.00, 7518.00, 7700.00, 182.00, 0.00, 1, 'Completed', 'Card'),
(129, 3, '2024-12-10 10:11:25', 6200.00, 0.00, 6200.00, 6200.00, 0.00, 0.00, 1, 'Completed', 'Cash'),
(130, 3, '2024-12-11 09:28:38', 1499.00, 0.00, 1499.00, 1500.00, 1.00, 0.00, 1, 'Completed', 'Cash'),
(131, 3, '2024-12-11 10:52:22', 2098.00, 0.00, 2098.00, 2100.00, 2.00, 0.00, 1, 'Completed', 'Cash'),
(133, 4, '2024-12-16 21:52:11', 299.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 'Voided', 'Cash'),
(134, 4, '2024-12-16 21:52:53', 6690.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 'Voided', 'Cash'),
(135, 3, '2024-12-17 14:51:09', 4180.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 'Voided', 'Cash'),
(136, 3, '2024-12-17 15:03:52', 9400.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 'Voided', 'Cash'),
(137, 3, '2024-12-17 15:40:45', 1200.00, 60.00, 1140.00, 1300.00, 160.00, 5.00, 5, 'Completed', 'Cash'),
(138, 3, '2024-12-17 19:03:32', 8696.00, 0.00, 8696.00, 9000.00, 304.00, 0.00, 1, 'Completed', 'Cash');

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `sale_item_id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount_percentage` decimal(5,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`sale_item_id`, `sale_id`, `item_id`, `quantity`, `price`, `discount_percentage`) VALUES
(292, 98, 5, 1, 6500.00, 0.00),
(293, 98, 19, 1, 590.00, 0.00),
(294, 99, 43, 1, 799.00, 0.00),
(295, 99, 51, 1, 2495.00, 0.00),
(296, 99, 50, 1, 1750.00, 0.00),
(297, 100, 3, 3, 120.00, 0.00),
(298, 100, 82, 2, 599.00, 0.00),
(299, 101, 61, 1, 1699.00, 0.00),
(300, 102, 73, 1, 999.00, 0.00),
(301, 102, 72, 1, 799.00, 0.00),
(302, 102, 78, 1, 1299.00, 0.00),
(303, 103, 98, 1, 4999.00, 0.00),
(304, 104, 26, 1, 499.00, 0.00),
(305, 104, 2, 1, 399.00, 0.00),
(306, 104, 33, 1, 1199.00, 0.00),
(307, 105, 67, 1, 1299.00, 0.00),
(308, 105, 63, 1, 1399.00, 0.00),
(309, 106, 85, 2, 499.00, 0.00),
(310, 106, 80, 1, 399.00, 0.00),
(311, 107, 91, 1, 2999.00, 0.00),
(312, 107, 95, 1, 4999.00, 0.00),
(313, 108, 31, 1, 699.00, 0.00),
(314, 108, 32, 1, 1895.00, 0.00),
(315, 108, 36, 1, 1490.00, 0.00),
(316, 108, 37, 1, 1590.00, 0.00),
(317, 109, 65, 1, 2299.00, 0.00),
(318, 109, 55, 1, 1495.00, 0.00),
(319, 110, 53, 2, 799.00, 0.00),
(320, 110, 52, 1, 1799.00, 0.00),
(321, 110, 88, 1, 6999.00, 0.00),
(322, 111, 7, 1, 1200.00, 0.00),
(323, 111, 47, 1, 899.00, 0.00),
(324, 111, 46, 1, 990.00, 0.00),
(325, 111, 48, 1, 499.00, 0.00),
(326, 112, 90, 2, 3499.00, 0.00),
(327, 112, 89, 1, 8999.00, 0.00),
(328, 113, 79, 2, 299.00, 0.00),
(329, 114, 13, 1, 1200.00, 0.00),
(330, 114, 75, 1, 499.00, 0.00),
(331, 114, 77, 1, 1199.00, 0.00),
(332, 114, 76, 1, 1499.00, 0.00),
(333, 115, 94, 1, 3999.00, 0.00),
(334, 115, 93, 1, 4999.00, 0.00),
(335, 115, 92, 1, 5499.00, 0.00),
(336, 115, 11, 1, 3000.00, 0.00),
(337, 115, 7, 1, 1200.00, 0.00),
(338, 115, 37, 1, 1590.00, 0.00),
(339, 116, 66, 1, 999.00, 0.00),
(340, 117, 81, 1, 799.00, 0.00),
(341, 117, 58, 1, 2895.00, 0.00),
(342, 117, 60, 1, 1795.00, 0.00),
(343, 117, 59, 1, 999.00, 0.00),
(344, 118, 13, 1, 1200.00, 0.00),
(345, 118, 57, 1, 1999.00, 0.00),
(346, 118, 56, 1, 1499.00, 0.00),
(347, 119, 96, 1, 8999.00, 0.00),
(348, 119, 99, 1, 2999.00, 0.00),
(349, 119, 9, 1, 2200.00, 0.00),
(350, 120, 82, 1, 599.00, 0.00),
(351, 120, 36, 1, 1490.00, 0.00),
(352, 120, 11, 1, 3000.00, 0.00),
(353, 121, 57, 1, 1999.00, 0.00),
(354, 121, 1, 1, 299.00, 0.00),
(355, 121, 35, 1, 1650.00, 0.00),
(356, 121, 49, 1, 1800.00, 0.00),
(357, 122, 69, 1, 799.00, 0.00),
(358, 122, 64, 1, 2999.00, 0.00),
(359, 123, 91, 1, 2999.00, 0.00),
(360, 123, 94, 1, 3999.00, 0.00),
(361, 124, 9, 1, 2200.00, 0.00),
(362, 125, 75, 1, 499.00, 0.00),
(363, 125, 37, 1, 1590.00, 0.00),
(364, 125, 47, 1, 899.00, 0.00),
(365, 126, 87, 1, 1599.00, 0.00),
(366, 126, 86, 1, 999.00, 0.00),
(367, 127, 71, 2, 1299.00, 0.00),
(368, 128, 97, 2, 3499.00, 0.00),
(369, 128, 4, 1, 520.00, 0.00),
(370, 129, 54, 1, 2800.00, 0.00),
(371, 129, 7, 1, 1200.00, 0.00),
(372, 129, 9, 1, 2200.00, 0.00),
(373, 130, 56, 1, 1499.00, 0.00),
(374, 131, 83, 1, 799.00, 0.00),
(375, 131, 84, 1, 1299.00, 0.00),
(394, 133, 1, 1, 299.00, 0.00),
(395, 134, 36, 1, 1490.00, 0.00),
(396, 134, 11, 1, 3000.00, 0.00),
(397, 134, 9, 1, 2200.00, 0.00),
(398, 135, 7, 1, 1200.00, 0.00),
(399, 135, 36, 2, 1490.00, 0.00),
(400, 136, 7, 1, 1200.00, 0.00),
(401, 136, 11, 2, 3000.00, 0.00),
(402, 136, 9, 1, 2200.00, 0.00),
(403, 137, 7, 1, 1200.00, 5.00),
(404, 138, 7, 1, 1200.00, 0.00),
(405, 138, 53, 1, 799.00, 0.00),
(406, 138, 63, 1, 1399.00, 0.00),
(407, 138, 65, 1, 2299.00, 0.00),
(408, 138, 64, 1, 2999.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `shopinfo`
--

CREATE TABLE `shopinfo` (
  `shop_id` int(11) NOT NULL,
  `shop_name` varchar(255) NOT NULL,
  `shop_address` text NOT NULL,
  `shop_contact` varchar(50) NOT NULL,
  `shop_email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shopinfo`
--

INSERT INTO `shopinfo` (`shop_id`, `shop_name`, `shop_address`, `shop_contact`, `shop_email`) VALUES
(1, 'Urban Grail', 'Science City of Munoz, Nueva Ecija, Philippines', '09757229124', 'urbangrail_scm@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `role` enum('Admin','Employee') NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `user_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `username`, `password`, `email`, `phone`, `role`, `status`, `user_image`) VALUES
(3, 'Jeric Dela Cruz', 'jericjdc', '$2y$10$mCQWLFXGAvrhKSmvVrjJRu963yOA47/jGxLeicUO7f6yNkKEq5NPC', 'jericjdc@gmail.com', '12345678900', 'Admin', 'active', '../../upload_images/user_images/1734430268_Baymax.jpeg'),
(4, 'Jozen Agustin', 'jozena', '$2y$10$DzrGZGqc/s7/TyOakzyEaOAVnCXMWZeZvALoYn.UW9PeNrE7zEg3O', 'jozena@example.com', '12345678900', 'Admin', 'active', '../../upload_images/user_images/Jozen.jpg'),
(5, 'Melgie Alata', 'melgiea', '$2y$10$mNna70K5MMzfXVexfiLjoOVKSWr3oUdHCIqj4pAV71fE5AIUBwYWK', 'melgiea@example.com', '12345678900', 'Admin', 'active', '../../upload_images/user_images/Thor.jpeg'),
(6, 'Krisha Manahan', 'krisham', '$2y$10$YtVybVgyu/FXQTjO5vCGe.6XOBDner5ROUVjxItMrZDZ6wLMcIkMy', 'krisham@example.com', '12345678900', 'Admin', 'active', '../../upload_images/user_images/Hulk.jpeg'),
(7, 'Ronnel Baldovino', 'ronnelb', '$2y$10$fJ0Fy8F4P402HjNHfOzkZuqGoXgSHrXlwyCdNRieQhWvkyxMsan0G', 'ronnelb@example.com', '12345678900', 'Employee', 'inactive', '../../upload_images/user_images/Ronnel.jpg'),
(8, 'Franz Eda', 'franze', '$2y$10$k0LAol0R3jJL7hZpwydYC.hu3lj4eAipq4aW5ZLg.a.txftlveH6u', 'franze@example.com', '12345678900', 'Employee', 'inactive', '../../upload_images/user_images/Capt.jpeg'),
(9, 'Ejay Basinga', 'ejayb', '$2y$10$zEjJfzmSUKl.w19yuEDB8eBqUFnsV0dhMuhjfPptrjgTaFKbL9eqK', 'ejayb@example.com', '12345678900', 'Employee', 'active', '../../upload_images/user_images/Ejay.jpg'),
(10, 'Christine Duatin', 'christined', '$2y$10$jFlaB08DhSSCrFUrgy2GS.mWCWkPQZEMNz4vTEkGBAs4zqtGEoiLq', 'christined@example.com', '12345678900', 'Admin', 'active', '../../upload_images/user_images/Ironman.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `void_actions`
--

CREATE TABLE `void_actions` (
  `void_id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `void_date` datetime DEFAULT current_timestamp(),
  `voided_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `void_actions`
--

INSERT INTO `void_actions` (`void_id`, `sale_id`, `user_id`, `void_date`, `voided_by`) VALUES
(19, 113, 9, '2024-11-07 10:54:10', 9),
(21, 133, 4, '2024-12-16 21:52:19', 4),
(22, 134, 4, '2024-12-16 21:53:23', 4),
(23, 135, 3, '2024-12-17 14:52:29', 3),
(24, 136, 3, '2024-12-17 15:24:12', 3),
(25, 100, 9, '2024-12-17 18:45:01', 9);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`color_id`),
  ADD UNIQUE KEY `color_name` (`color_name`);

--
-- Indexes for table `discountpercentages`
--
ALTER TABLE `discountpercentages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_percentage` (`discount_id`,`percentage`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`,`start_date`,`end_date`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `itemcolors`
--
ALTER TABLE `itemcolors`
  ADD PRIMARY KEY (`item_id`,`color_id`),
  ADD KEY `color_id` (`color_id`);

--
-- Indexes for table `itemhistory`
--
ALTER TABLE `itemhistory`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`sale_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `discount_id` (`discount_id`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`sale_item_id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `shopinfo`
--
ALTER TABLE `shopinfo`
  ADD PRIMARY KEY (`shop_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `void_actions`
--
ALTER TABLE `void_actions`
  ADD PRIMARY KEY (`void_id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `voided_by` (`voided_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=334;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `color_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `discountpercentages`
--
ALTER TABLE `discountpercentages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1868;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `itemhistory`
--
ALTER TABLE `itemhistory`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=276;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `sale_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=409;

--
-- AUTO_INCREMENT for table `shopinfo`
--
ALTER TABLE `shopinfo`
  MODIFY `shop_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `void_actions`
--
ALTER TABLE `void_actions`
  MODIFY `void_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `discountpercentages`
--
ALTER TABLE `discountpercentages`
  ADD CONSTRAINT `discountpercentages_ibfk_1` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `itemcolors`
--
ALTER TABLE `itemcolors`
  ADD CONSTRAINT `itemcolors_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `itemcolors_ibfk_2` FOREIGN KEY (`color_id`) REFERENCES `colors` (`color_id`) ON DELETE CASCADE;

--
-- Constraints for table `itemhistory`
--
ALTER TABLE `itemhistory`
  ADD CONSTRAINT `itemhistory_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`),
  ADD CONSTRAINT `itemhistory_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`id`);

--
-- Constraints for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sale_items_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`sale_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE;

--
-- Constraints for table `void_actions`
--
ALTER TABLE `void_actions`
  ADD CONSTRAINT `void_actions_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`sale_id`),
  ADD CONSTRAINT `void_actions_ibfk_2` FOREIGN KEY (`voided_by`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

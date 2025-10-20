-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2025 at 03:50 PM
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
-- Database: `eams`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_attendance`
--

CREATE TABLE `tbl_attendance` (
  `id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `date` date NOT NULL DEFAULT curdate(),
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_attendance`
--

INSERT INTO `tbl_attendance` (`id`, `emp_id`, `date`, `time_in`, `time_out`) VALUES
(2, 4, '2025-08-13', '16:31:43', '17:26:50'),
(3, 1, '2025-08-13', '19:20:56', '19:21:16'),
(4, 6, '2025-08-13', '19:21:00', '19:21:23'),
(9, 8, '2025-08-17', '08:16:45', '08:16:52'),
(10, 2, '2025-08-17', '08:17:00', '13:27:07'),
(12, 12, '2025-08-17', '11:57:01', '11:57:29'),
(14, 5, '2025-08-17', '14:39:52', '14:39:57'),
(15, 1, '2025-08-17', '15:32:58', '16:56:16'),
(16, 3, '2025-08-17', '17:15:42', '17:16:10'),
(17, 6, '2025-08-17', '17:40:00', '17:40:11'),
(18, 4, '2025-08-17', '17:43:15', '17:44:24'),
(19, 1, '2025-08-18', '08:43:58', '09:17:48'),
(20, 16, '2025-08-18', '09:17:53', '15:57:17'),
(21, 12, '2025-08-18', '09:20:24', '09:21:33'),
(22, 5, '2025-08-18', '15:22:42', '15:57:42'),
(23, 14, '2025-08-18', '17:21:44', '23:23:13'),
(24, 12, '2025-08-14', '08:41:17', '16:57:03'),
(25, 12, '2025-08-15', '08:36:06', '16:56:10'),
(26, 12, '2025-08-16', '09:34:03', '17:12:44'),
(27, 8, '2025-08-14', '09:09:35', '17:43:14'),
(28, 8, '2025-08-15', '08:27:34', '16:55:30'),
(29, 8, '2025-08-16', '09:27:23', '17:32:38'),
(30, 4, '2025-08-14', '08:07:04', '16:32:23'),
(31, 4, '2025-08-15', '08:06:36', '17:30:44'),
(32, 4, '2025-08-16', '09:37:18', '16:24:50'),
(33, 2, '2025-08-14', '08:46:47', '16:17:12'),
(34, 2, '2025-08-15', '08:36:25', '17:45:20'),
(35, 2, '2025-08-16', '09:31:04', '16:43:08'),
(36, 1, '2025-08-14', '09:02:45', '16:06:28'),
(37, 1, '2025-08-15', '08:41:07', '17:13:15'),
(38, 1, '2025-08-16', '08:35:05', '16:46:29'),
(39, 14, '2025-08-14', '08:16:39', '17:01:34'),
(40, 14, '2025-08-15', '08:24:47', '17:10:17'),
(41, 14, '2025-08-16', '09:34:17', '16:00:23'),
(42, 6, '2025-08-14', '08:34:15', '16:54:03'),
(43, 6, '2025-08-15', '09:15:16', '17:53:53'),
(44, 16, '2025-08-14', '09:31:50', '16:49:32'),
(45, 16, '2025-08-15', '09:12:14', '17:24:42'),
(46, 16, '2025-08-16', '08:59:49', '16:38:46'),
(47, 33, '2025-08-14', '09:15:42', '17:35:20'),
(48, 33, '2025-08-15', '08:01:42', '17:39:53'),
(49, 33, '2025-08-16', '08:11:54', '16:16:36'),
(50, 3, '2025-08-14', '09:06:36', '17:11:13'),
(51, 3, '2025-08-15', '09:27:42', '17:29:41'),
(52, 3, '2025-08-16', '08:57:45', '17:32:39'),
(53, 5, '2025-08-14', '09:32:11', '17:10:52'),
(54, 5, '2025-08-15', '08:56:15', '16:47:59'),
(55, 5, '2025-08-16', '09:32:00', '16:06:25'),
(56, 7, '2025-08-15', '08:58:17', '16:47:29'),
(57, 7, '2025-08-16', '08:50:21', '17:04:05'),
(87, 2, '2025-08-18', '23:21:49', '23:21:53'),
(88, 8, '2025-08-19', '00:04:29', '00:18:55'),
(89, 3, '2025-08-19', '00:20:52', '00:21:20'),
(90, 6, '2025-08-19', '00:22:34', '00:22:55'),
(91, 4, '2025-08-19', '00:30:07', '00:30:19'),
(92, 5, '2025-08-19', '00:41:15', '01:13:34'),
(93, 12, '2025-08-19', '00:51:11', '01:13:40'),
(94, 7, '2025-08-19', '01:00:19', '01:13:30'),
(95, 14, '2025-08-19', '01:04:04', '01:12:52'),
(96, 16, '2025-08-19', '01:27:06', '01:27:10'),
(97, 33, '2025-08-19', '20:34:41', '22:33:31'),
(98, 2, '2025-08-19', '22:32:12', '22:33:59'),
(99, 1, '2025-08-19', '23:11:58', '23:21:33'),
(106, 1, '2025-08-21', '22:09:48', '22:10:09'),
(107, 2, '2025-08-21', '23:05:00', '23:41:00'),
(108, 2, '2025-08-22', '01:55:08', '09:38:01'),
(109, 6, '2025-08-22', '09:25:07', '18:14:22'),
(110, 1, '2025-08-22', '10:44:43', '18:13:23'),
(111, 3, '2025-08-22', '10:49:20', '18:13:43'),
(112, 5, '2025-08-22', '10:50:36', '18:14:04'),
(113, 1, '2025-08-23', '21:28:55', '23:12:41'),
(114, 2, '2025-08-23', '21:29:08', '23:12:57'),
(115, 3, '2025-08-23', '21:29:18', '22:13:22'),
(116, 5, '2025-08-23', '21:29:32', '22:13:37'),
(117, 7, '2025-08-23', '21:29:44', '23:13:49'),
(118, 12, '2025-08-23', '21:47:16', '23:10:56'),
(119, 16, '2025-08-23', '21:52:22', '22:11:45'),
(120, 8, '2025-08-23', '22:01:51', '22:12:06'),
(121, 33, '2025-08-23', '22:03:33', '23:12:21'),
(122, 2, '2025-08-24', '18:14:15', '21:03:38'),
(123, 1, '2025-08-24', '18:15:36', '18:16:09'),
(124, 5, '2025-08-24', '18:15:48', '22:04:00'),
(125, 7, '2025-08-24', '18:15:59', '22:04:17'),
(126, 48, '2025-08-24', '18:16:24', '20:50:26'),
(127, 3, '2025-08-24', '20:49:40', '21:04:37'),
(128, 6, '2025-08-24', '20:49:55', '22:04:51'),
(129, 14, '2025-08-24', '20:50:11', '22:05:10'),
(130, 50, '2025-08-25', '00:33:48', '23:38:58'),
(131, 7, '2025-08-25', '14:31:57', '23:36:22'),
(132, 6, '2025-08-25', '14:32:11', '23:36:30'),
(133, 4, '2025-08-25', '14:32:22', '23:36:53'),
(134, 5, '2025-08-25', '14:32:35', '23:36:43'),
(135, 2, '2025-08-25', '14:32:54', '15:55:47'),
(136, 3, '2025-08-25', '14:33:24', '23:37:07'),
(137, 1, '2025-08-25', '14:33:42', '15:28:35'),
(138, 47, '2025-08-25', '14:34:37', '23:38:47'),
(139, 8, '2025-08-25', '14:35:01', '23:37:23'),
(140, 14, '2025-08-25', '14:35:13', '23:37:48'),
(141, 16, '2025-08-25', '14:35:31', '23:38:17'),
(142, 12, '2025-08-25', '14:35:43', '23:37:36'),
(143, 33, '2025-08-25', '14:36:49', '23:38:28'),
(144, 44, '2025-08-25', '14:37:02', '23:38:38'),
(146, 1, '2025-08-26', '15:15:52', '22:59:44'),
(147, 8, '2025-08-26', '15:16:30', '23:00:42'),
(148, 2, '2025-08-26', '15:18:01', '22:59:51'),
(149, 3, '2025-08-26', '15:19:04', '23:00:00'),
(150, 4, '2025-08-26', '15:19:36', '23:00:06'),
(151, 5, '2025-08-26', '15:19:59', '23:00:14'),
(152, 6, '2025-08-26', '15:20:40', '23:00:21'),
(153, 7, '2025-08-26', '15:20:50', '23:00:31'),
(154, 12, '2025-08-26', '15:21:10', '23:00:52'),
(155, 14, '2025-08-26', '15:21:22', '23:01:02'),
(156, 16, '2025-08-26', '15:21:38', '23:01:14'),
(157, 33, '2025-08-26', '15:21:54', '23:01:23'),
(158, 44, '2025-08-26', '15:22:05', '23:01:31'),
(159, 47, '2025-08-26', '15:22:34', '23:01:41'),
(160, 48, '2025-08-26', '15:22:55', '22:59:34'),
(161, 50, '2025-08-26', '15:23:16', '22:59:23'),
(162, 2, '2025-09-01', '09:12:00', '10:30:00'),
(163, 6, '2025-09-01', '09:12:48', NULL),
(164, 44, '2025-09-01', '09:13:03', NULL),
(165, 3, '2025-09-01', '09:13:00', '10:30:00'),
(166, 4, '2025-09-01', '10:47:56', NULL),
(167, 1, '2025-09-01', '13:40:17', NULL),
(168, 14, '2025-09-01', '14:01:00', '15:30:00'),
(169, 5, '2025-09-01', '14:16:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_employees`
--

CREATE TABLE `tbl_employees` (
  `id` int(11) NOT NULL,
  `emp_pic` varchar(255) DEFAULT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `position` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_employees`
--

INSERT INTO `tbl_employees` (`id`, `emp_pic`, `full_name`, `email`, `position`, `user_id`) VALUES
(1, '1756697393_1756034208_john.png', 'John Dela Cruz', 'john@example.com', 'Engineer', 2),
(2, '1755797239_AdobeStock_1369626232_Preview.png', 'Jane De Leon', 'jane@example.com', 'HR Manager', 4),
(3, '1755831164_AdobeStock_1369634244_Preview.png', 'Michael Jackson', 'michael@example.com', 'Accountant', 5),
(4, '1755857860_AdobeStock_1610037031_Preview.png', 'Emily Hernandez', 'emily@example.com', 'Marketing Specialist', 6),
(5, '1755857877_AdobeStock_1610036473_Preview.png', 'Robert Lopez', 'robert@example.com', 'Team Lead', 7),
(6, '1755857893_AdobeStock_1610037186_Preview.png', 'Linda Wilson', 'linda.wilson@example.com', 'Customer Support', 8),
(7, '1755857906_AdobeStock_1610036084_Preview.png', 'William Santos', 'william@example.com', 'Business Analyst', 9),
(8, '1755857933_AdobeStock_1610039028_Preview.png', 'Elizabeth Anderson', 'elizabeth@example.com', 'Project Manager', 10),
(12, '1755857992_AdobeStock_1369630768_Preview.png', 'Melgie Alata', 'melgie@clsu2.edu.ph', 'Mop Handler', 11),
(14, '1755857978_AdobeStock_1369626664_Preview.png', 'Krisha Manahan', 'krisha@gmail.com', 'CEO', 3),
(16, '1755858045_AdobeStock_1651645225_Preview.png', 'Allyssa Nicole Mercado', 'allyssa@clsu2.edu.ph', 'OJT', 12),
(33, '1755858147_AdobeStock_1610037735_Preview.png', 'Mary Joy', 'mary@gmail.com', 'Accountant', 14),
(44, '1755858078_AdobeStock_1651645259_Preview.png', 'Jade Umipig', 'jade@gmail.com', 'HR Manager', 21),
(47, NULL, 'many', 'many@gmail.com', 'OJT', 25),
(48, NULL, 'jozen', 'jozen@gmail.com', 'Project Manager', 26),
(50, NULL, 'jun cruz', 'jun@gmail.com', 'position', 28);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','employee') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `email`, `password`, `role`) VALUES
(1, 'admin@gmail.com', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 'admin'),
(2, 'john@example.com', '96d9632f363564cc3032521409cf22a852f2032eec099ed5967c0d000cec607a', 'employee'),
(3, 'krisha@gmail.com', 'ef7ecb3974a749dcd26ea05423d9985375f35c919b4022e3b5bdb622ab87e402', 'employee'),
(4, 'jane@example.com', '81f8f6dde88365f3928796ec7aa53f72820b06db8664f5fe76a7eb13e24546a2', 'employee'),
(5, 'michael@example.com', '34550715062af006ac4fab288de67ecb44793c3a05c475227241535f6ef7a81b', 'employee'),
(6, 'emily@example.com', 'e8e9689deac5bac977b64e85c1105bd1419608f1223bdafb8e5fbdf6cf939879', 'employee'),
(7, 'robert@example.com', '4007d46292298e83da10d0763d95d5139fe0c157148d0587aa912170414ccba6', 'employee'),
(8, 'linda.wilson@example.com', '6bab3007f56e2a9175ff1222c2654ddcd08fa7981a1ddc42f1d95cfbd80ede47', 'employee'),
(9, 'william@example.com', 'd0784c6b1785dcd474688d46b1fe99792ff66f6b56bebf26dda0c08516bac22e', 'employee'),
(10, 'elizabeth@example.com', 'b54f08623ae4039f55bcecba4961037fb4513d2ba9cb2b0667c5db970ac94911', 'employee'),
(11, 'melgie@clsu2.edu.ph', 'db1ce64fdd1c2ee14391e5a47e66c197f98bb7a5f250402d3ea1114b2d6b8ae6', 'employee'),
(12, 'allyssa@clsu2.edu.ph', '4d2e45f57ae0ba2493f8830d086ea95375de6c1c0d0db849632088341015ee3c', 'employee'),
(13, 'melgiealata23@gmail.com', 'db1ce64fdd1c2ee14391e5a47e66c197f98bb7a5f250402d3ea1114b2d6b8ae6', 'employee'),
(14, 'mary@gmail.com', '6915771be1c5aa0c886870b6951b03d7eafc121fea0e80a5ea83beb7c449f4ec', 'employee'),
(21, 'jade@gmail.com', '5db6810e7cf9ff073c275265cfed5712b136fee26d7281715196e55f36f00279', 'employee'),
(25, 'many@gmail.com', '1137b15c7797aa84ec24e8dca5cb966dd016624374a09cb2ecaa9ac3229f5ccc', 'employee'),
(26, 'jozen@gmail.com', '2e470051e42e2ae7a8c14032931e70d5e81ec9b607d0a51a80a35d82ccd73779', 'employee'),
(28, 'jun@gmail.com', 'eb687afb0f4823e8eb80b3c1c1fa6519cdc916cd8e31c63106d039ac5b0fa907', 'employee');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_attendance`
--
ALTER TABLE `tbl_attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tbl_attendance_ibfk_1` (`emp_id`);

--
-- Indexes for table `tbl_employees`
--
ALTER TABLE `tbl_employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_employee_user` (`user_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_attendance`
--
ALTER TABLE `tbl_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT for table `tbl_employees`
--
ALTER TABLE `tbl_employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_attendance`
--
ALTER TABLE `tbl_attendance`
  ADD CONSTRAINT `tbl_attendance_ibfk_1` FOREIGN KEY (`emp_id`) REFERENCES `tbl_employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_employees`
--
ALTER TABLE `tbl_employees`
  ADD CONSTRAINT `fk_employee_user` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

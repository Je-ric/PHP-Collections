-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Apr 18, 2024 at 05:44 AM
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
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_accounts`
--

CREATE TABLE `admin_accounts` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_accounts`
--

INSERT INTO `admin_accounts` (`id`, `username`, `password`) VALUES
(1, 'admin', 'adminpassword');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `category_image`) VALUES
(1, 'Guitar and Basses', 'Guitar_Category.png'),
(2, 'Drums & Percussion', 'Drum_Category.png'),
(3, 'Keyboard and Piano', 'Piano_Category.png'),
(4, 'Wind Instruments', 'Wind_Category.png'),
(5, 'Speaker & Mic', 'Speaker_Category.png'),
(6, 'Studio & Recording', 'Studio-Recording_Category.png'),
(7, 'Accessories', 'Accessories_Category.png'),
(14, 'Others', 'logo-black.png'),
(16, 'Drumssss', 'Drum_Category.png');

-- --------------------------------------------------------

--
-- Table structure for table `client_accounts`
--

CREATE TABLE `client_accounts` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client_accounts`
--

INSERT INTO `client_accounts` (`id`, `username`, `password`, `name`, `age`, `phone`, `address`) VALUES
(2, 'user', 'password', 'jeric', 18, '1241421421', 'Bantug'),
(3, 'Jeric', 'Jeric', 'Jeric J. Dela Cruz', 18, '09145107251', 'SCM'),
(4, 'rose', 'rose', 'rose', 20, '12345', 'munoz'),
(5, 'bago', 'bago', 'bago', 18, '09251865189', 'bago'),
(6, 'juan_dc', 'juan_dc', 'Juan Dela Cruz', 20, '09876543211', 'Munoz');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `item_image` varchar(255) DEFAULT NULL,
  `average_rating` decimal(3,2) DEFAULT 0.00,
  `total_reviews` int(11) DEFAULT 0,
  `sold` int(11) DEFAULT 0,
  `shipping_fee` decimal(10,2) DEFAULT 0.00,
  `quantity` int(11) DEFAULT 0,
  `status` varchar(10) DEFAULT 'In Stock',
  `quantity_available` int(11) DEFAULT 0
) ;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `description`, `price`, `item_image`, `average_rating`, `total_reviews`, `sold`, `shipping_fee`, `quantity`, `status`, `quantity_available`) VALUES
(1, 'Fender Stratocaster Electric Guitar', 'Classic electric guitar known for its versatile sound and iconic design.', 25000.00, 'Fender Stratocaster.jpg', 0.00, 0, 62, 100.00, 63, 'In Stock', 0),
(2, 'Ibanez RG Series Electric Guitar', 'Sleek and fast-playing electric guitar, perfect for shredding and heavy riffing.', 20000.00, 'Ibanez RG Series .jpg', 0.00, 0, 1, 80.00, 124, 'In Stock', 0),
(3, 'Martin D-28 Acoustic Guitar', 'Legendary acoustic guitar with rich, balanced tone and solid construction.', 40000.00, 'Martin D-28.png', 0.00, 0, 2, 80.00, 48, 'In Stock', 0),
(4, 'Yamaha Pacifica 112V Electric Guitar', 'Versatile and affordable electric guitar suitable for various playing styles.', 15000.00, 'Yamaha Pacifica 112V.jpg', 0.00, 0, 0, 70.00, 100, 'In Stock', 0),
(5, 'Epiphone Les Paul Standard Electric Guitar', 'Classic rock guitar with a warm, powerful tone and stunning finish.', 22000.00, 'Epiphone Les Paul.jpg', 0.00, 0, 1, 80.00, 54, 'In Stock', 0),
(6, 'Fender Jazz Bass', 'Iconic bass guitar known for its smooth playability and distinct tone.', 30000.00, 'Fender Jazz.png', 0.00, 0, 0, 90.00, 0, 'In Stock', 0),
(7, 'Squier Affinity Series Precision Bass', 'Affordable yet reliable bass guitar with solid construction and punchy sound.', 18000.00, 'Squier Affinity Series Precision.png', 0.00, 0, 0, 70.00, 0, 'In Stock', 0),
(8, 'PRS SE Custom 24 Electric Guitar', 'High-quality electric guitar with beautiful aesthetics and versatile tone options.', 35000.00, 'PRS SE Custom 24.jpg', 0.00, 0, 0, 100.00, 30, 'In Stock', 0),
(9, 'Gibson SG Standard Electric Guitar', 'Classic rock guitar with a lightweight design and powerful, aggressive tone.', 50000.00, 'Gibson SG Standard .jpg', 0.00, 0, 0, 80.00, 0, 'In Stock', 0),
(10, 'Schecter Omen Extreme-6 Electric Guitar', 'Modern electric guitar with a striking appearance and versatile sound capabilities.', 28000.00, 'Schecter Omen Extreme-6 .png', 0.00, 0, 1, 100.00, 69, 'In Stock', 0),
(11, 'Mapex Saturn Evolution Drum Shell', 'A hybrid design with a combination of maple and walnut wood, resulting in a balanced and dynamic sound profile. ', 150000.00, 'Mapex Saturn Evolution Shell.JPG', 0.00, 0, 0, 250.00, 25, 'In Stock', 0),
(12, 'Roland TD-17KV Electronic Drum Kit', 'High-quality electronic drum set with realistic feel and responsive pads.', 55000.00, 'Roland TD-17KV.jpg', 0.00, 0, 0, 300.00, 45, 'In Stock', 0),
(13, 'Zildjian A Custom Cymbal Pack', 'Premium cymbal set known for its bright, cutting sound and excellent projection.', 28000.00, 'Zildjian A Custom Cymbal Pack.jpg', 0.00, 0, 0, 250.00, 0, 'In Stock', 0),
(14, 'LP Aspire Conga Set', 'Traditional conga drums with rich, warm tones and sturdy construction.', 15000.00, 'LP Aspire Conga Set.jpg', 0.00, 0, 0, 250.00, 15, 'In Stock', 0),
(15, 'Meinl Cajon Drum', 'Versatile percussion instrument with adjustable snare wires for various playing styles.', 8000.00, 'Meinl Cajon .png', 0.00, 0, 0, 250.00, 20, 'In Stock', 0),
(16, 'Sabian HHX Evolution Crash Cymbal', ' Professional-grade crash cymbal with dark, complex tones and quick response.', 12000.00, 'Sabian HHX Evolution Crash Cymbal.jpg', 0.00, 0, 2, 300.00, 53, 'In Stock', 0),
(17, 'Tama Iron Cobra Double Bass Pedal', 'High-performance bass drum pedal known for its speed, power, and reliability.', 10000.00, 'Tama Iron Cobra Double Bass Pedal.png', 0.00, 0, 0, 250.00, 25, 'In Stock', 0),
(18, 'Remo Djembe', 'Handcrafted djembe drum with authentic African design and deep, resonant sound.', 7000.00, 'Remo Djembe.jpg', 0.00, 0, 3, 270.00, 27, 'In Stock', 0),
(19, 'Mapex Armory Snare Drum', 'Versatile snare drum with crisp, articulate sound and durable construction.', 9000.00, 'Mapex Armory Snare Drum.png', 0.00, 0, 1, 250.00, 39, 'In Stock', 0),
(20, 'LP Giovanni Series Bongos', 'Professional-grade bongos with premium materials and excellent tone quality.', 18000.00, 'LP Giovanni Series Bongos.png', 0.00, 0, 1, 370.00, 34, 'In Stock', 0),
(21, 'Taylor 214ce Acoustic-Electric Guitar', ' High-quality acoustic-electric guitar with a solid Sitka spruce top and versatile onboard electronics.', 60000.00, 'Taylor 214ce .png', 0.00, 0, 0, 100.00, 75, 'In Stock', 0),
(22, 'Gretsch G5420T Electromatic Hollow Body Guitar', ' Stylish hollow-body electric guitar with a Bigsby tremolo system and distinctive \"twangy\" sound.', 45000.00, 'Gretsch G5420T.png', 0.00, 0, 0, 150.00, 10, 'In Stock', 0),
(23, 'Sabian AA Holy China Cymbal', 'Explosive and aggressive china cymbal with raw bell and high-profile design, ideal for cutting through loud music.', 16000.00, 'Sabian AA Holy China Cymbal.jpg', 0.00, 0, 0, 230.00, 20, 'In Stock', 0),
(24, 'Yamaha YFL-222 Flute', ' High-quality beginner flute with a rich, warm tone and durable construction.', 20000.00, 'Yamaha YFL-222.jpg', 0.00, 0, 0, 145.00, 15, 'In Stock', 0),
(25, 'Jupiter JTR700 Trumpet', 'Entry-level trumpet with a bright, clear tone and easy playability for beginners.', 25000.00, 'Jupiter JTR700 .jpg', 0.00, 0, 0, 90.00, 0, 'In Stock', 0),
(26, 'Buffet Crampon E11 Bb Clarinet', 'Intermediate clarinet with a rich, focused tone and ergonomic key design.', 400000.00, 'Buffet Crampon E11.jpg', 0.00, 0, 3, 50.00, 7, 'In Stock', 0),
(27, 'Conn-Selmer Prelude Student Trombone', 'Lightweight and durable trombone designed for beginner students.', 30000.00, 'Conn-Selmer Prelude Student Trombone.jpg', 0.00, 0, 4, 100.00, 11, 'In Stock', 0),
(28, 'Jupiter JFL700WD Flute', 'Intermediate flute with a sterling silver headjoint for enhanced tonal clarity and projection.', 35000.00, 'Jupiter JFL700WD.png', 0.00, 0, 1, 250.00, 24, 'In Stock', 0),
(29, 'Yamaha YTR-2330 Bb Trumpet', 'Student trumpet with excellent intonation and easy playability, perfect for beginners.', 28000.00, 'Yamaha YTR-2330.jpg', 0.00, 0, 0, 100.00, 15, 'In Stock', 0),
(30, 'Jean Paul USA CL-300 Clarinet', ' Entry-level clarinet with a balanced tone and durable construction, suitable for students.', 18000.00, 'Jean Paul USA CL-300 .jpg', 0.00, 0, 1, 250.00, 54, 'In Stock', 0),
(31, 'Hohner Blues Harp Harmonica', ' Classic harmonica with a warm, bluesy tone and airtight construction for easy bending and expressive playing.', 3000.00, 'Hohner Blues.jpg', 0.00, 0, 7, 50.00, 83, 'In Stock', 0),
(35, 'Sticker - White', 'Sticker', 10.00, 'logo-no-background.png', 0.00, 0, 0, 10.00, 10, 'In Stock', 0),
(36, 'Sticker - Black', 'Sticker - Black', 10.00, 'logo-white.png', 0.00, 0, 0, 10.00, 10, 'In Stock', 0);

-- --------------------------------------------------------

--
-- Table structure for table `item_categories`
--

CREATE TABLE `item_categories` (
  `item_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_categories`
--

INSERT INTO `item_categories` (`item_id`, `category_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 2),
(12, 2),
(13, 2),
(14, 2),
(15, 2),
(16, 2),
(17, 2),
(18, 2),
(19, 2),
(20, 2),
(21, 1),
(22, 1),
(23, 2),
(24, 4),
(25, 4),
(26, 4),
(27, 4),
(28, 4),
(29, 4),
(30, 4),
(31, 4),
(35, 14),
(36, 14);

-- --------------------------------------------------------

--
-- Table structure for table `item_reviews`
--

CREATE TABLE `item_reviews` (
  `id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `client_name` varchar(100) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'Pending',
  `shipping_fee` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `client_id`, `total_price`, `order_date`, `status`, `shipping_fee`) VALUES
(1, 2, 60000.00, '2024-04-05 06:04:03', 'Received', 200.00),
(2, 2, 3000.00, '2024-04-05 07:26:32', 'Received', 50.00),
(3, 2, 0.00, '2024-04-05 07:26:51', 'Cancelled', 0.00),
(4, 2, 0.00, '2024-04-05 07:29:41', 'Pending', 0.00),
(5, 2, 80000.00, '2024-04-05 07:39:25', 'Cancelled', 160.00),
(6, 2, 0.00, '2024-04-05 07:39:31', 'Cancelled', 0.00),
(7, 2, 18000.00, '2024-04-05 07:42:00', 'Received', 250.00),
(8, 2, 40000.00, '2024-04-05 07:47:10', 'Cancelled', 50.00),
(9, 2, 22000.00, '2024-04-05 07:53:21', 'Approved', 80.00),
(10, 2, 20000.00, '2024-04-05 07:59:55', 'Approved', 80.00),
(11, 2, 21000.00, '2024-04-07 11:29:12', 'Received', 810.00),
(12, 2, 0.00, '2024-04-09 13:31:12', 'Pending', 0.00),
(13, 2, 775000.00, '2024-04-09 13:31:57', 'Cancelled', 3100.00),
(14, 2, 775000.00, '2024-04-09 13:32:05', 'Cancelled', 3100.00),
(15, 2, 0.00, '2024-04-09 13:32:11', 'Pending', 0.00),
(16, 2, 0.00, '2024-04-09 13:32:16', 'Pending', 0.00),
(17, 2, 0.00, '2024-04-09 13:32:25', 'Pending', 0.00),
(18, 2, 0.00, '2024-04-09 13:33:02', 'Pending', 0.00),
(19, 2, 0.00, '2024-04-09 13:33:23', 'Pending', 0.00),
(20, 2, 0.00, '2024-04-09 13:33:27', 'Pending', 0.00),
(21, 2, 0.00, '2024-04-09 13:34:01', 'Pending', 0.00),
(22, 2, 0.00, '2024-04-09 13:34:04', 'Pending', 0.00),
(23, 2, 0.00, '2024-04-09 13:34:07', 'Pending', 0.00),
(24, 2, 0.00, '2024-04-09 13:34:10', 'Pending', 0.00),
(25, 2, 0.00, '2024-04-09 13:34:53', 'Pending', 0.00),
(26, 2, 0.00, '2024-04-09 13:36:43', 'Pending', 0.00),
(27, 2, 0.00, '2024-04-09 13:37:26', 'Pending', 0.00),
(28, 2, 35000.00, '2024-04-09 13:40:25', 'Pending', 250.00),
(29, 2, 9000.00, '2024-04-09 13:41:30', 'Cancelled', 250.00),
(30, 2, 0.00, '2024-04-09 13:41:56', 'Pending', 0.00),
(31, 2, 0.00, '2024-04-09 13:42:06', 'Pending', 0.00),
(32, 2, 0.00, '2024-04-09 13:42:16', 'Pending', 0.00),
(33, 2, 28000.00, '2024-04-09 13:42:28', 'Pending', 100.00),
(34, 2, 0.00, '2024-04-09 13:45:06', 'Pending', 0.00),
(35, 2, 0.00, '2024-04-09 13:45:17', 'Pending', 0.00),
(36, 2, 0.00, '2024-04-09 13:45:38', 'Pending', 0.00),
(37, 2, 0.00, '2024-04-09 13:45:41', 'Pending', 0.00),
(38, 2, 0.00, '2024-04-09 13:46:21', 'Pending', 0.00),
(39, 2, 0.00, '2024-04-09 13:46:24', 'Pending', 0.00),
(40, 2, 40000.00, '2024-04-09 13:46:44', 'Received', 50.00),
(41, 2, 40000.00, '2024-04-09 13:52:34', 'Pending', 50.00),
(42, 2, 24000.00, '2024-04-15 04:51:18', 'Received', 600.00),
(43, 2, 18000.00, '2024-04-15 13:37:57', 'Pending', 370.00),
(44, 2, 60000.00, '2024-04-15 13:43:36', 'Cancelled', 200.00),
(45, 6, 9000.00, '2024-04-18 01:55:19', 'Received', 150.00),
(46, 6, 9000.00, '2024-04-18 03:11:13', 'Received', 150.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `item_id`, `quantity`, `price`) VALUES
(1, 1, 27, 2, 30000.00),
(2, 2, 31, 1, 3000.00),
(3, 3, 9, 0, 50000.00),
(4, 5, 3, 2, 40000.00),
(5, 6, 6, 0, 30000.00),
(6, 7, 30, 1, 18000.00),
(7, 8, 26, 1, 40000.00),
(8, 9, 5, 1, 22000.00),
(9, 10, 2, 1, 20000.00),
(10, 11, 18, 3, 7000.00),
(11, 13, 1, 31, 25000.00),
(12, 14, 1, 31, 25000.00),
(13, 28, 28, 1, 35000.00),
(14, 29, 19, 1, 9000.00),
(15, 33, 10, 1, 28000.00),
(16, 40, 26, 1, 40000.00),
(17, 41, 26, 1, 40000.00),
(18, 42, 16, 2, 12000.00),
(19, 43, 20, 1, 18000.00),
(20, 44, 27, 2, 30000.00),
(22, 46, 31, 3, 3000.00);

-- --------------------------------------------------------

--
-- Table structure for table `shopping_cart`
--

CREATE TABLE `shopping_cart` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shopping_cart`
--

INSERT INTO `shopping_cart` (`id`, `client_id`, `item_id`, `quantity`, `status`) VALUES
(33, 2, 25, 2, 'Pending');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_accounts`
--
ALTER TABLE `admin_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_accounts`
--
ALTER TABLE `client_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_categories`
--
ALTER TABLE `item_categories`
  ADD PRIMARY KEY (`item_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `item_reviews`
--
ALTER TABLE `item_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `item_id` (`item_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_accounts`
--
ALTER TABLE `admin_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `client_accounts`
--
ALTER TABLE `client_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_reviews`
--
ALTER TABLE `item_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `item_categories`
--
ALTER TABLE `item_categories`
  ADD CONSTRAINT `item_categories_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_reviews`
--
ALTER TABLE `item_reviews`
  ADD CONSTRAINT `item_reviews_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD CONSTRAINT `shopping_cart_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shopping_cart_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

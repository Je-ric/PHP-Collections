-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2025 at 03:25 AM
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
-- Database: `arch-live`
--

-- --------------------------------------------------------

--
-- Table structure for table `active_users`
--

CREATE TABLE `active_users` (
  `id` int(11) NOT NULL,
  `research_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `last_active` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `active_users`
--

INSERT INTO `active_users` (`id`, `research_id`, `user_id`, `last_active`) VALUES
(3489, 4, 2, '2025-02-24 04:40:26'),
(3513, 4, 1, '2025-02-24 04:40:27');

-- --------------------------------------------------------

--
-- Table structure for table `research`
--

CREATE TABLE `research` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `status` enum('draft','published','archived') DEFAULT 'draft',
  `year` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `author_id` int(11) DEFAULT NULL,
  `adviser_id` int(11) DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `research`
--

INSERT INTO `research` (`id`, `title`, `content`, `status`, `year`, `created_at`, `updated_at`, `author_id`, `adviser_id`, `last_updated`) VALUES
(1, 'Untitled Research', 'Testing', 'draft', 2025, '2025-02-10 12:04:37', '2025-02-10 12:09:44', 1, NULL, '2025-02-10 13:03:27'),
(2, 'Untitled Research', '', 'draft', 2025, '2025-02-10 12:04:56', '2025-02-10 12:04:56', 1, NULL, '2025-02-10 13:03:27'),
(3, 'Untitled Research', '', 'draft', 2025, '2025-02-10 12:09:22', '2025-02-10 12:09:22', 2, NULL, '2025-02-10 13:03:27'),
(4, 'Testing lang', 'Ano nanaman, naulit again. \n\nBat ganon. \nPabigatttt\nAno na. \n\nWorking na ulit.\nKaasarrrrr.  Working \nAno Ngayon palang. \nWorking using server.php two vendor\nRoot display using chat.php.\nAgain, root is fixed, var username used. Again still working.\n', 'draft', 2025, '2025-02-10 12:16:13', '2025-02-24 04:40:31', 1, NULL, '2025-02-24 04:40:31'),
(5, 'Untitled Research', '', 'draft', 2025, '2025-02-10 12:16:43', '2025-02-10 12:16:48', 1, NULL, '2025-02-10 13:03:27');

-- --------------------------------------------------------

--
-- Table structure for table `research_chat`
--

CREATE TABLE `research_chat` (
  `id` int(11) NOT NULL,
  `research_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `research_chat`
--

INSERT INTO `research_chat` (`id`, `research_id`, `user_id`, `message`, `created_at`) VALUES
(1, 4, 1, 'testing', '2025-02-19 13:15:38'),
(2, 4, 1, 'test', '2025-02-19 13:19:57'),
(3, 4, 1, 'test', '2025-02-19 13:19:57'),
(4, 4, 2, 'test', '2025-02-19 13:20:00'),
(5, 4, 2, 'test', '2025-02-19 13:20:00'),
(6, 4, 1, 'test', '2025-02-19 13:20:14'),
(7, 4, 1, 'test', '2025-02-19 13:20:14'),
(8, 4, 1, 'test', '2025-02-19 13:22:54'),
(9, 4, 1, 'ok na?', '2025-02-19 13:23:00'),
(10, 4, 2, 'di naman real-time', '2025-02-19 13:23:07'),
(11, 4, 2, 'hello', '2025-02-24 04:24:29');

-- --------------------------------------------------------

--
-- Table structure for table `research_recommendations`
--

CREATE TABLE `research_recommendations` (
  `id` int(11) NOT NULL,
  `research_id` int(11) DEFAULT NULL,
  `adviser_id` int(11) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `research_sharing`
--

CREATE TABLE `research_sharing` (
  `id` int(11) NOT NULL,
  `research_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `access_level` enum('view','edit') DEFAULT 'view'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `research_sharing`
--

INSERT INTO `research_sharing` (`id`, `research_id`, `user_id`, `access_level`) VALUES
(1, 1, 1, 'edit'),
(2, 2, 1, 'edit'),
(3, 3, 2, 'edit'),
(4, 4, 1, 'edit'),
(5, 5, 1, 'edit'),
(6, 4, 2, 'edit');

-- --------------------------------------------------------

--
-- Table structure for table `typing_status`
--

CREATE TABLE `typing_status` (
  `research_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('typing','idle') DEFAULT 'idle',
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `typing_status`
--

INSERT INTO `typing_status` (`research_id`, `user_id`, `status`, `last_updated`) VALUES
(4, 1, 'idle', '2025-02-10 13:23:33'),
(4, 2, 'idle', '2025-02-10 13:36:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('researcher','adviser','administrator') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_active` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `last_active`) VALUES
(1, 'jericjdc', 'jeric@gmail.com', '$2y$10$M10MRk/8JSQikZvDXDrrwe..imUsLoofVBVEnZZOx1QmZouB.aZY6', 'researcher', '2025-02-10 11:46:24', '2025-02-24 04:40:27'),
(2, 'hiro hamada', 'hiro@gmail.com', '$2y$10$jLrY1227CA3lOM9Mg.HJ6.L3.vSJLFi6TaXzpZO10AEAvzU3xVnZa', 'researcher', '2025-02-10 11:53:48', '2025-02-24 04:40:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `active_users`
--
ALTER TABLE `active_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_editor` (`research_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `research`
--
ALTER TABLE `research`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `adviser_id` (`adviser_id`);

--
-- Indexes for table `research_chat`
--
ALTER TABLE `research_chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `research_id` (`research_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `research_recommendations`
--
ALTER TABLE `research_recommendations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `research_id` (`research_id`),
  ADD KEY `adviser_id` (`adviser_id`);

--
-- Indexes for table `research_sharing`
--
ALTER TABLE `research_sharing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `research_id` (`research_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `typing_status`
--
ALTER TABLE `typing_status`
  ADD PRIMARY KEY (`research_id`,`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `active_users`
--
ALTER TABLE `active_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3514;

--
-- AUTO_INCREMENT for table `research`
--
ALTER TABLE `research`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `research_chat`
--
ALTER TABLE `research_chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `research_recommendations`
--
ALTER TABLE `research_recommendations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `research_sharing`
--
ALTER TABLE `research_sharing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `active_users`
--
ALTER TABLE `active_users`
  ADD CONSTRAINT `active_users_ibfk_1` FOREIGN KEY (`research_id`) REFERENCES `research` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `active_users_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `research`
--
ALTER TABLE `research`
  ADD CONSTRAINT `research_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `research_ibfk_2` FOREIGN KEY (`adviser_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `research_chat`
--
ALTER TABLE `research_chat`
  ADD CONSTRAINT `research_chat_ibfk_1` FOREIGN KEY (`research_id`) REFERENCES `research` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `research_chat_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `research_recommendations`
--
ALTER TABLE `research_recommendations`
  ADD CONSTRAINT `research_recommendations_ibfk_1` FOREIGN KEY (`research_id`) REFERENCES `research` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `research_recommendations_ibfk_2` FOREIGN KEY (`adviser_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `research_sharing`
--
ALTER TABLE `research_sharing`
  ADD CONSTRAINT `research_sharing_ibfk_1` FOREIGN KEY (`research_id`) REFERENCES `research` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `research_sharing_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

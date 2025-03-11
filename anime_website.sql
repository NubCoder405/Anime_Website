-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 11, 2025 at 10:08 AM
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
-- Database: `anime_website`
--

-- --------------------------------------------------------

--
-- Table structure for table `anime_details`
--

CREATE TABLE `anime_details` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `genre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `anime_details`
--

INSERT INTO `anime_details` (`id`, `name`, `description`, `image_url`, `video_url`, `genre`) VALUES
(1, 'Fate / Stay Night: Unlimited Blade Works', 'A group of mages summon legendary heroes to compete in a war for the Holy Grail. It features intense battles and complex characters.', 'Slider1.jpg', 'Fate.mp4', 'Action'),
(2, 'Solo Leveling Season 2', 'Chin woo keeps on leveling up his chin length and keeps on aura farming each episode', 'Solo-Leveling.jpg', 'episode1.mp4', 'Action,Fantasy'),
(3, 'Demon Slayer: Kimetsu no Yaiba', 'Tanjiro Kamado embarks on a quest to rid the world of demons while trying to cure his demon-turned sister, Nezuko.', 'Slider2.jpg', 'Fate.mp4', 'Action,Fantasy,Shounen'),
(4, 'Attack on Titan: The Final Season', 'Humanity’s fight against the terrifying Titans continues as secrets about the world and the Titans unfold.', 'AOT.jpg', 'game.mp4', 'Psycological Horror'),
(5, 'Naruto Shipuden', 'An orphan ninja with a demon inside him becomes a hokage', '1386697.png', 'Benimaru vs Demon Infernal .mp4', 'Action,Shounen'),
(6, 'Zenshu', 'Animator dies and goes to story world', 'Zenshu.jpg', 'game.mp4', 'Action, Fantasy, Isekai'),
(7, 'Random', 'Random', 'Random.jpg', 'Snow_Coffee.mp4', 'Random'),
(8, 'bishal', 'aada', 'Aboutus.jpg', 'Benimaru vs Demon Infernal .mp4', 'hsia'),
(9, 'Test', 'Hekllo', 'Aboutus.jpg', 'Benimaru vs Demon Infernal .mp4', 'Action, Fantasy, Isekai');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `anime_id` int(11) NOT NULL,
  `episode_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `likes` int(11) DEFAULT 0,
  `dislikes` int(11) DEFAULT 0,
  `parent_id` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`anime_id`, `episode_id`, `user_id`, `comment_text`, `created_at`, `likes`, `dislikes`, `parent_id`, `id`) VALUES
(1, 0, 1, 'Hello', '2025-03-08 14:43:29', 0, 0, NULL, 1),
(1, 0, 1, 'Test Tes', '2025-03-08 14:43:37', 0, 0, NULL, 2),
(2, 0, 1, 'Sung Jin Woo be aura farming every episode HAHA\r\n', '2025-03-08 14:44:18', 0, 0, NULL, 3),
(2, 0, 1, 'Test', '2025-03-08 14:44:39', 23, 1, NULL, 4),
(1, 0, 2, 'Very gud', '2025-03-09 00:46:28', 0, 0, NULL, 5),
(1, 0, 2, 'ad', '2025-03-09 00:57:00', 0, 0, 0, 6),
(2, 0, 2, 'Very good anime', '2025-03-09 02:59:52', 4, 0, NULL, 9),
(2, 0, 2, 'hi', '2025-03-09 03:33:24', 0, 0, NULL, 10),
(1, 0, 1, 'test', '2025-03-09 04:30:23', 0, 0, NULL, 11),
(1, 0, 1, 'Hello', '2025-03-09 05:05:25', 0, 0, 11, 12),
(1, 0, 1, 'Reply2', '2025-03-09 05:05:44', 0, 0, 11, 13),
(1, 0, 1, 'Rajib', '2025-03-09 05:05:54', 0, 0, NULL, 14),
(1, 0, 1, 'Is', '2025-03-09 05:06:06', 0, 0, 14, 15),
(1, 0, 1, 'Cheater', '2025-03-09 05:07:48', 0, 0, 14, 16),
(1, 0, 1, '.', '2025-03-09 05:09:46', 0, 0, NULL, 17),
(1, 0, 1, 'What?', '2025-03-09 05:11:55', 0, 0, 17, 18),
(1, 0, 1, 'shut up', '2025-03-09 05:16:05', 0, 0, 17, 19),
(1, 0, 1, 'ok', '2025-03-09 05:16:18', 0, 0, 17, 20),
(1, 0, 1, 'Hi', '2025-03-09 05:16:32', 0, 0, NULL, 21),
(1, 0, 1, 'Bye', '2025-03-09 05:16:40', 0, 0, 21, 22),
(1, 0, 1, 'ok', '2025-03-09 05:18:02', 0, 0, 21, 23),
(1, 0, 1, 'Last comment', '2025-03-09 05:18:10', 0, 0, NULL, 24),
(1, 0, 1, 'hi', '2025-03-09 05:19:00', 0, 0, NULL, 25),
(1, 0, 1, 'bye', '2025-03-09 05:19:05', 0, 0, NULL, 26),
(1, 0, 1, 'Great anime', '2025-03-09 05:20:39', 0, 0, NULL, 27),
(1, 0, 1, 'hi', '2025-03-09 05:20:47', 0, 0, NULL, 28),
(1, 0, 1, 'i know', '2025-03-09 05:20:54', 0, 0, 27, 29),
(1, 0, 1, 'test#15253', '2025-03-09 05:22:27', 0, 0, NULL, 30),
(1, 0, 1, 'test#15253', '2025-03-09 05:22:29', 0, 0, NULL, 31),
(1, 0, 1, 'test#15253', '2025-03-09 05:22:31', 0, 0, NULL, 32),
(1, 0, 1, 'test#15254', '2025-03-09 05:25:07', 0, 0, NULL, 33),
(2, 0, 3, 'Needs more improvization\r\n', '2025-03-09 06:01:00', 0, 0, NULL, 35),
(1, 0, 3, 'jbj', '2025-03-09 06:12:25', 0, 0, NULL, 36),
(1, 0, 4, 'hello', '2025-03-09 06:38:42', 0, 0, NULL, 37),
(1, 1, 2, 'hi', '2025-03-09 23:52:41', 0, 0, NULL, 38),
(1, 1, 2, 'Great fight scene!', '2025-03-10 00:25:33', 0, 0, NULL, 39),
(1, 1, 2, 'WOW i have been waiting for ep 2!!!!!!!!', '2025-03-10 00:25:50', 0, 0, NULL, 40),
(1, 2, 2, 'test', '2025-03-10 00:30:27', 0, 0, NULL, 41),
(1, 3, 2, 'EPISODE 3', '2025-03-10 00:30:44', 0, 0, NULL, 42),
(1, 2, 2, 'ep 2', '2025-03-10 00:33:31', 0, 0, NULL, 43),
(1, 1, 2, 'test test', '2025-03-10 00:33:42', 0, 0, NULL, 44),
(1, 1, 2, 'hi', '2025-03-10 00:40:34', 0, 0, 44, 45),
(2, 1, 2, 'CHIN WOOO', '2025-03-10 00:51:30', 0, 0, NULL, 46),
(4, 1, 2, 'AOT', '2025-03-10 01:09:41', 0, 0, NULL, 47),
(4, 1, 2, 'lets go eren', '2025-03-10 01:14:34', 0, 0, NULL, 48),
(4, 2, 2, 'Ep 2 lets goo', '2025-03-10 01:14:43', 0, 0, NULL, 49),
(4, 1, 2, 'ji', '2025-03-10 01:24:17', 0, 0, 48, 50),
(2, 1, 2, 'YES', '2025-03-10 01:58:00', 0, 0, 46, 51),
(1, 1, 2, 'hi', '2025-03-10 03:09:09', 0, 0, NULL, 52),
(1, 1, 2, 'hi', '2025-03-10 03:17:28', 0, 0, 52, 53),
(1, 5, 2, 'ep 6', '2025-03-10 03:38:36', 0, 0, NULL, 54),
(1, 1, 2, 'hi', '2025-03-10 03:38:41', 0, 0, NULL, 55),
(1, 1, 2, 'TEST#12', '2025-03-10 03:38:52', 0, 0, NULL, 56),
(1, 1, 2, 'test#13', '2025-03-10 03:40:07', 0, 0, NULL, 57),
(1, 1, 2, 'Test#14', '2025-03-10 03:44:02', 0, 0, NULL, 58),
(1, 1, 2, 'Test#15', '2025-03-10 03:50:51', 0, 0, NULL, 59),
(3, 0, 1, 'DEMON SLAYER', '2025-03-10 06:08:17', 0, 0, NULL, 60),
(1, 0, 2, 'thiss anime sucks\r\n', '2025-03-11 01:24:53', 0, 0, NULL, 61),
(1, 0, 1, 'Hello', '2025-03-11 02:27:22', 0, 0, NULL, 62);

-- --------------------------------------------------------

--
-- Table structure for table `comment_likes`
--

CREATE TABLE `comment_likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `type` enum('like','dislike') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comment_likes`
--

INSERT INTO `comment_likes` (`id`, `user_id`, `comment_id`, `type`) VALUES
(38, 2, 6, 'like'),
(39, 2, 11, 'like'),
(40, 1, 11, 'like'),
(41, 1, 14, 'like'),
(42, 1, 33, 'like'),
(43, 3, 35, 'like'),
(44, 2, 44, 'like'),
(46, 2, 37, 'like'),
(47, 1, 61, 'like');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `user_id`, `name`, `message`, `created_at`, `email`) VALUES
(1, 2, 'Sumit', 'HI, this is a test', '2025-03-10 01:42:24', 'Owner@email.com'),
(2, 2, 'Sumit', 'HI, this is a test', '2025-03-10 01:46:13', 'Owner@email.com'),
(3, 2, 'Bishal', 'It might need some polishing up but over all it\'s good', '2025-03-10 02:05:06', 'bishal@gmail.com'),
(4, 1, 'Ajit', 'Hello, mic test mic test', '2025-03-10 02:06:03', 'Ajit@email.com'),
(5, 2, 'bishal', 'Test test', '2025-03-10 21:22:06', 'sumitshrestha42069@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin','owner') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_picture` varchar(255) DEFAULT NULL,
  `banned` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `profile_picture`, `banned`) VALUES
(1, 'Sumit', 'Sumit@email.com', '$2y$10$0LYsVseHC3IweH0sqbZyDepFo7BUsJBbLSa8HW4HXsiy/S8aY44Uy', 'user', '2025-03-08 09:16:07', NULL, 0),
(2, 'OwnerKing', 'Owner@email.com', '$2y$10$0xJe2zhj00/94dzY2YmBiedCkh3E7ESDEhfAsD5MQL3HHtF/BCVwu', 'admin', '2025-03-08 09:31:04', 'Aboutus2.jpg', 0),
(3, 'Bishal', 'bishal@gmail.com', '$2y$10$Lf.12scFSPdl1Ozd7MW5ouCCS5ikB1S3uTnLNiIWBxieITG2RAQA2', 'user', '2025-03-09 05:59:41', NULL, 0),
(4, 'Bishal', 'bishal1@gmail.com', '$2y$10$z20kN/2nOH.TMqQJgLqSU.o23axV5yHXiHUWaY3e9mW81FCG0IP..', 'user', '2025-03-09 06:28:03', NULL, 1),
(5, 'Ajit', 'Ajit@email.com', '$2y$10$faq7TnUnPLjcwl94.J0Qne7jctNrW95EqtEo1BBuj4gOeYWAmQFCC', 'user', '2025-03-10 07:22:16', NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anime_details`
--
ALTER TABLE `anime_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anime_id` (`anime_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `comment_likes`
--
ALTER TABLE `comment_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`user_id`,`comment_id`),
  ADD KEY `comment_id` (`comment_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anime_details`
--
ALTER TABLE `anime_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `comment_likes`
--
ALTER TABLE `comment_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`anime_id`) REFERENCES `anime_details` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comment_likes`
--
ALTER TABLE `comment_likes`
  ADD CONSTRAINT `comment_likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_likes_ibfk_2` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD CONSTRAINT `contact_messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

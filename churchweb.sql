-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 01, 2025 at 02:40 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `churchweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_section`
--

CREATE TABLE `about_section` (
  `id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about_section`
--

INSERT INTO `about_section` (`id`, `image_url`, `title`, `description`) VALUES
(1, 'assets/images/Hero.jpg', 'Our purpose as a church', 'Covenant Baptist Church Tisnga Yaounde is dedicated to discipleship and missions, fostering a community rooted in biblical truth and spiritual growth. Covenant Baptist Church Tisnga Yaounde is dedicated to discipleship and missions, fostering a community rooted in biblical truth and spiritual growth. Covenant Baptist Church Tisnga Yaounde is dedicated to discipleship and missions, fostering a community rooted in biblical truth and spiritual growth. Covenant Baptist Church Tisnga Yaounde is dedicated to discipleship and missions, fostering a community rooted in biblical truth and spiritual growth.');

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` datetime DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT 'assets/images/avatar.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `name`, `username`, `email`, `password`, `is_active`, `created_at`, `last_login`, `profile_image`) VALUES
(1, 'BASIGHA BLISS', 'BLISS', 'bhenrybliss@gmail.com', '$2y$10$jyP90g99Vx1871AIe1M7lOhkT8Z4PhKzOvRS6zGxRKnR57EoDBMa6', 1, '2025-07-01 00:10:25', '2025-07-01 01:29:02', 'assets/images/avatar.png');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `image_url`, `title`, `date`, `description`) VALUES
(1, 'assets/images/events/event.jpg', 'Youth Rally', '2025-06-06', 'Join our young people for worship, teaching, and fellowship.'),
(2, 'assets/images/events/event.jpg', 'Drama Night', '2025-06-14', 'An evening of gospel drama performances by our team.'),
(3, 'assets/images/events/event.jpg', 'Children’s Ministry', '2025-06-01', 'Fun lessons, crafts, and worship designed just for kids.');

-- --------------------------------------------------------

--
-- Table structure for table `hero_section`
--

CREATE TABLE `hero_section` (
  `id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hero_section`
--

INSERT INTO `hero_section` (`id`, `image_url`, `title`, `subtitle`) VALUES
(1, 'assets/images/Hero.jpg', 'Welcome to Covenant Baptist Church Tsinga Yaounde', 'Center of discipleship and missions.'),
(2, 'assets/images/Hero.jpg', 'Welcome to Covenant Baptist Church Tsinga Yaounde', 'Center of discipleship and missions.'),
(3, 'assets/images/Hero.jpg', 'Welcome to Covenant Baptist Church Tsinga Yaounde', 'Center of discipleship and missions.');

-- --------------------------------------------------------

--
-- Table structure for table `leaders`
--

CREATE TABLE `leaders` (
  `id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leaders`
--

INSERT INTO `leaders` (`id`, `image_url`, `name`, `position`) VALUES
(1, 'assets/images/leaders/senior-Pastor.jpg', 'Rev. Donatus Ngamsiha', 'Senior Pastor'),
(2, 'assets/images/leaders/youthPresident.jpg', 'Mr. Shey Wilson', 'Appointed Deacon'),
(3, 'assets/images/leaders/vise-ad.jpg', '...', 'Vise Appointed Deacon'),
(4, 'assets/images/leaders/church-secetary.jpg', 'Br. Napoleon', 'Church Secretary'),
(5, 'assets/images/leaders/men\'s-president.jpg', 'Mr. Paul', 'Men\'s President');

-- --------------------------------------------------------

--
-- Table structure for table `ministries`
--

CREATE TABLE `ministries` (
  `id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ministries`
--

INSERT INTO `ministries` (`id`, `image_url`, `title`, `description`) VALUES
(1, 'assets/images/Hero.jpg', 'Ministries One', 'Short description of ministries one.'),
(2, 'assets/images/Hero.jpg', 'Ministries Two', 'Short description of ministries two.'),
(3, 'assets/images/Hero.jpg', 'Ministries Three', 'Short description of ministries three.'),
(4, 'assets/images/Hero.jpg', 'Ministries Four', 'Short description of ministries four.'),
(5, 'assets/images/Hero.jpg', 'Ministries Four', 'Short description of ministries four.'),
(6, 'assets/images/Hero.jpg', 'Ministries Four', 'Short description of ministries four.');

-- --------------------------------------------------------

--
-- Table structure for table `navigation`
--

CREATE TABLE `navigation` (
  `id` int(11) NOT NULL,
  `link_name` varchar(255) NOT NULL,
  `link_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `navigation`
--

INSERT INTO `navigation` (`id`, `link_name`, `link_url`) VALUES
(1, 'Home', '#home'),
(2, 'About', '#about'),
(3, 'Leaders', '#leaders'),
(4, 'Ministries', '#ministries'),
(5, 'Organizations', '#organizations'),
(6, 'Events', '#events'),
(7, 'Contact', '#contact');

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`id`, `image_url`, `name`, `description`) VALUES
(1, 'assets/images/organisations/school.jpg', 'Church School', 'Faith-based education for our children and youth.'),
(2, 'assets/images/organisations/school.jpg', 'Community Hospital', 'Providing compassionate healthcare services.'),
(3, 'assets/images/organisations/school.jpg', 'Outreach Center', 'Supporting families in need across our city.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_section`
--
ALTER TABLE `about_section`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hero_section`
--
ALTER TABLE `hero_section`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leaders`
--
ALTER TABLE `leaders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ministries`
--
ALTER TABLE `ministries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `navigation`
--
ALTER TABLE `navigation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_section`
--
ALTER TABLE `about_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hero_section`
--
ALTER TABLE `hero_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `leaders`
--
ALTER TABLE `leaders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ministries`
--
ALTER TABLE `ministries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `navigation`
--
ALTER TABLE `navigation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

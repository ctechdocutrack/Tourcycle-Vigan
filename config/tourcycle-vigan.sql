-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2026 at 10:34 AM
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
-- Database: `tourcycle-vigan`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` enum('admin','driver') NOT NULL DEFAULT 'admin',
  `qr_token` char(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`, `role`, `qr_token`) VALUES
(1, 'Tourcycle Admin', 'admin', 'admin', NULL),
(2, 'Owell', '628667', 'driver', '295c2947c96f0070eeff6dee4bd94b00c7a4a7de4fdb9f4d1069d07ba2cb1771'),
(3, 'Seguban', '237231', 'driver', 'd8d33b0fff8bc2b0353bd3a428762a1459c711e2ea0902db4e8b2cc47b2b7720');

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE `destinations` (
  `destinations_id` int(11) NOT NULL,
  `destination_name` varchar(150) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `audio_en` varchar(255) DEFAULT NULL,
  `audio_tl` varchar(255) DEFAULT NULL,
  `audio_ilo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` (`destinations_id`, `destination_name`, `category`, `description`, `latitude`, `longitude`, `image`, `status`, `audio_en`, `audio_tl`, `audio_ilo`, `created_at`, `updated_at`) VALUES
(1, 'Calle Crisologo', 'Heritage Street', 'Calle Crisologo owes its name to the illustrious Ilocano poet, writer, and playwright Governor Marcelino “Mena” Crisologo. Previously, the street was called Calle de Escolta de Vigan, whose residents were mostly families who profited from the galleon trade that included Ilocos as a key port. When Governor Crisologo died in 1927, the street was renamed Calle Crisologo in his honor.\r\nThe street is part of Vigan’s picturesque Heritage Village, a UNESCO World Heritage Site. This consists of about 200 beautifully restored houses dating back to the 16th century. The buildings on Crisologo particularly display a blend of indigenous Filipino and colonial European construction.\r\nCalle Crisologo packs a lot of charming sights and local action that will make every visit worthwhile. The cobblestone streets are lined with ancestral houses on both sides, some housing restaurants that offer Vigan’s most delectable and distinct dishes such as the empanadas, bagnet, and Vigan longganisa.\r\nSouvenir shops and stalls also dot almost every part of the street, selling local handicrafts such as the town’s iconic traditional weaves and fabrics, pottery, wines, wooden crafts, and antique items. These stalls provide a burst of colors and patterns that make for perfect photo backdrops.\r\nCalle Crisologo is also off-limits to vehicles, except for horse-drawn carriages called calesas which were the only means of transportation during the colonial period. Hop on one to immerse yourself in the town’s culture while marveling at the street’s wonderful sights.\r\nAt night, the street transforms into a romantic place with charming lights and a more still, relaxed vibe than that of its daytime dynamism. Dine and drink in one of the street’s many al fresco restaurants.', 17.5677889, 120.3876416, 'uploads/destinations/e1a6c720dc8fa715.webp', 'Active', '', '', NULL, '2026-11-13 14:02:32', '2026-11-14 13:12:00'),
(2, 'Plaza Salcedo', 'Historical Landmark', 'Welcome to Plaza Salcedo, one of the most famous historical landmarks in Vigan City. It was named after Juan de Salcedo, the Spanish conquistador who established Villa Fernandina in 1572, which later became known as Vigan.\r\nLocated in front of Saint Paul Metropolitan Cathedral, the plaza is surrounded by important historical buildings, including the Archbishop’s Palace and the Ilocos Sur Provincial Capitol. At its center stands the Salcedo Obelisk, which represents the city’s Spanish colonial influence.\r\nPlaza Salcedo is also historically significant as the execution site of Gabriela Silang in September 1763. She is remembered as the first Filipina woman to lead a major revolt against Spanish colonial rule.\r\nToday, the plaza is a popular gathering place for locals and tourists. It is especially known for its dancing fountain display, making it one of the must-visit attractions in Vigan.', 17.5753378, 120.3876101, 'uploads/destinations/5d7714e424240fa4.jpg', 'Active', NULL, NULL, NULL, '2026-11-13 14:07:24', '2026-11-13 14:07:24'),
(5, 'Pagburnayan', 'Cultural Heritage', 'Pagburnayan is a place where burnay is made. Bunary is an Ilocano term for tapayan or water container. Before the Spanish colonization of the Philippines in the 16th century, Chinese merchants were already trading with natives from Vigan. Eventually, a number of Chinese immigrants chose to live in Vigan after seeing the rich natural supply of premium grade clay in western parts of Vigan, pioneered the craft of jar making or pagbuburnay. \r\nBurnay jars are handcrafted by local potters\' skillful hands who have mastered the art of kneading, molding, and baking the clay along with water and sand to form the finished product with the help of potter’s wheel and a kiln.\r\nBurnay jars have small openings, and its earlier use were for tea drinking, storage for water, rice grains and as container for salt, brown sugar, local wine called basi and bagoong or the fermented fish. The potter uses a grade A clay that is widely available in the western area of Vigan. Fine sand is used as a tempering material to achieve the desired shape, afterwards, it will be placed inside a high-temperature ground kiln made of brick and clay.  Because of this, people say that burnay jars are harder and durable compared to terra cotta', 17.5736396, 120.3834339, 'uploads/destinations/7452dbd19a9f4ae5.jpg', 'Active', 'uploads/audio/fb34d76d8be6182d.mp3', 'uploads/audio/9643e75e8b7639e5.mp3', 'uploads/audio/a325cdf588ff7349.mp3', '2026-11-14 06:45:43', '2026-11-14 13:53:16'),
(6, 'Bantay Church and Bell Tower', 'Church & Religious Site', 'Bantay got its name from the ilokano word “Bantay” which literally means to guard. Bantay Church or the parish of St Augustine of Hippo is one of the oldest surviving churches in the region dating back to 1590. Bantay Church is also famed for its grand design and architecture inspired by Western and European cultures. It is home for the miraculous image of Nuestra Senora De la Caridad De Bantay or famously known as Apo Caridad, the queen of Ilocandia, Canonically Crowned on January 12, 1956.The church were severely damaged in 1945during the WWII, and reconstructed during the 19590’s.\r\nAdjacent to the church is the Bantay bell tower, located on a hilltop where it offers a panoramic view of the surrounding pastureland and the mountains of Abra. One of the views of the tower is the historic Bantay Cemetery located at the back of the church. The tower serves its purpose as watchtower and a defensive stronghold during both World War I and II which adds a layer of historical depth, illustrating the region\'s turbulent past and the tower\'s role in safeguarding the community. The Bell tower is approximately 3 storey building height and have a distance of 15-20 meters between the church. The bell tower have also 5 bells in which 4 small bells are use for regular mass, baptism, funeral and wedding. While the 5th and largest bell is used to alert and alarm people of Bantay.\r\nLast July 27, 2022, when the destructive magnitude 7.0 earthquake damaged the vicinity of both the Bantay Church and Bantay Bell Tower, closing this tourist attraction to the public for about a year. But the church is now renovated and reopened for tourists, but unfortunately the bell tower is still closed at the moment.', 17.5821628, 120.3915475, 'uploads/destinations/945a0e2ab9caff75.jpg', 'Active', 'uploads/audio/421f4d6e3ec4aa31.mp3', 'uploads/audio/c2e54a937624fcf3.mp3', 'uploads/audio/41705faeed5d5642.mp3', '2026-11-14 13:46:24', '2026-11-14 13:46:24');

-- --------------------------------------------------------

--
-- Table structure for table `destination_images`
--

CREATE TABLE `destination_images` (
  `images_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destination_images`
--

INSERT INTO `destination_images` (`images_id`, `destination_id`, `image`, `created_at`) VALUES
(1, 1, 'uploads/destinations/91e4369fc4031eb0.png', '2026-11-13 14:02:32'),
(2, 1, 'uploads/destinations/535281a05b4ce765.jpg', '2026-11-13 14:02:32'),
(3, 1, 'uploads/destinations/d419535533e3a03e.jpg', '2026-11-13 14:02:32'),
(4, 2, 'uploads/destinations/9450a25457a07b6f.webp', '2026-11-13 14:07:24'),
(6, 5, 'uploads/destinations/75ba7060af3ece2b.jpg', '2026-11-14 06:45:43'),
(7, 6, 'uploads/destinations/99d0ca60c34037c8.jpg', '2026-11-14 13:46:24');

-- --------------------------------------------------------

--
-- Table structure for table `tour_sessions`
--

CREATE TABLE `tour_sessions` (
  `tour_sessions_id` int(11) NOT NULL,
  `tour_id` varchar(30) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `access_code` varchar(30) NOT NULL,
  `device_token_hash` char(64) NOT NULL,
  `device_type` varchar(100) DEFAULT NULL,
  `browser` varchar(100) DEFAULT NULL,
  `status` enum('Pending','Active','Inactive','Ended','Expired') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `activated_at` datetime DEFAULT NULL,
  `ended_at` datetime DEFAULT NULL,
  `expired_at` datetime DEFAULT NULL,
  `last_activity_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tour_sessions`
--

INSERT INTO `tour_sessions` (`tour_sessions_id`, `tour_id`, `admin_id`, `access_code`, `device_token_hash`, `device_type`, `browser`, `status`, `created_at`, `activated_at`, `ended_at`, `expired_at`, `last_activity_at`, `updated_at`) VALUES
(1, 'TV-F750121D', NULL, '5124EEA4', 'dcdbd71291caf855b684ad8571133cf67734db1df0ba0b5f3164571d28dee82e', 'Desktop', 'Google Chrome', 'Ended', '2026-11-14 14:56:05', '2026-11-14 23:50:29', '2026-11-14 23:50:35', NULL, '2026-11-14 23:50:30', '2026-11-14 15:50:35'),
(2, 'TV-BA542847', NULL, '7492831F', '06c6482ad510102f8c46488928564b9d53f8d177b7f964637c004355eac73801', 'Desktop', 'Google Chrome', 'Pending', '2026-11-14 15:26:26', NULL, NULL, NULL, '2026-11-14 23:26:26', '2026-11-14 16:01:19'),
(3, 'TV-89F48C4C', NULL, '6AFBA1F9', 'e3f05665404d15ce286c6b5cd16a4318597ac03be0d37853f521c2d26c8704a3', 'Desktop', 'Google Chrome', 'Ended', '2026-11-14 16:01:38', '2026-11-15 00:02:35', '2026-11-15 00:37:49', NULL, '2026-11-15 00:02:36', '2026-11-14 16:37:49'),
(4, 'TV-9DE0A425', NULL, '945B42BC', 'd9e94cfec6053d451d8bca7cc6a5c17129abb9f66e45e071c556486a967fa32e', 'Desktop', 'Google Chrome', 'Active', '2026-11-14 16:27:19', '2026-11-15 00:38:11', NULL, NULL, '2026-11-15 00:27:19', '2026-11-14 16:38:11'),
(5, 'TV-C377F57D', NULL, '6877FB6C', '61a8fc36569856292e90233d1296f6e5d360f42cdbb5771453d58327e39af57a', 'Desktop', 'Google Chrome', 'Expired', '2026-11-14 16:32:08', NULL, NULL, '2026-11-15 00:37:57', '2026-11-15 00:32:08', '2026-11-14 16:37:57'),
(6, 'TV-B30130F4', NULL, '2755BB70', '8ce94bab95d04ad403598f9519887be7c5c78640d1ed11f3c89045ec74aaf905', 'Desktop', 'Google Chrome', 'Inactive', '2026-11-14 16:32:40', '2026-11-15 00:37:34', NULL, NULL, '2026-11-15 00:32:40', '2026-11-14 16:37:42'),
(7, 'TV-595E3B84', NULL, '5B96C9C1', 'c1b83ee82c5531ac28c738cb7cb5f645f0745ede9bab680f28c3f3ab4f1a8f6b', 'Desktop', 'Google Chrome', 'Active', '2026-11-15 07:54:08', '2026-11-15 15:54:54', NULL, NULL, '2026-11-15 16:52:48', '2026-11-15 08:52:48'),
(8, 'TV-9CE6D844', 2, '5EF1FCF7', '1c0add29be13c247a2c708e335a2d39cc187fde15bbdf53b2ec1ea113332b86d', 'Desktop', 'Google Chrome', 'Active', '2026-11-15 08:53:22', '2026-11-15 17:01:04', NULL, NULL, '2026-11-15 16:53:22', '2026-11-15 09:01:04');

-- --------------------------------------------------------

--
-- Table structure for table `tour_session_logs`
--

CREATE TABLE `tour_session_logs` (
  `tour_session_logs_id` int(11) NOT NULL,
  `tour_sessions_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `previous_status` varchar(20) DEFAULT NULL,
  `new_status` varchar(20) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tour_session_logs`
--

INSERT INTO `tour_session_logs` (`tour_session_logs_id`, `tour_sessions_id`, `action`, `previous_status`, `new_status`, `remarks`, `created_at`) VALUES
(1, 1, 'Access Activated', 'Pending', 'Active', NULL, '2026-11-14 15:25:24'),
(2, 2, 'Access Expired', 'Pending', 'Expired', NULL, '2026-11-14 15:27:07'),
(3, 1, 'Access Deactivated', 'Active', 'Inactive', NULL, '2026-11-14 15:41:31'),
(4, 1, 'Access Reactivated', 'Inactive', 'Active', NULL, '2026-11-14 15:46:08'),
(5, 1, 'Access Deactivated', 'Active', 'Inactive', NULL, '2026-11-14 15:47:10'),
(6, 1, 'Access Reactivated', 'Inactive', 'Active', NULL, '2026-11-14 15:50:29'),
(7, 1, 'Tour Session Ended', 'Active', 'Ended', NULL, '2026-11-14 15:50:35'),
(8, 2, 'Access Restored to Pending', 'Expired', 'Pending', NULL, '2026-11-14 16:01:19'),
(9, 3, 'Access Activated', 'Pending', 'Active', NULL, '2026-11-14 16:02:04'),
(10, 3, 'Access Deactivated', 'Active', 'Inactive', NULL, '2026-11-14 16:02:26'),
(11, 3, 'Access Reactivated', 'Inactive', 'Active', NULL, '2026-11-14 16:02:35'),
(12, 6, 'Access Activated', 'Pending', 'Active', NULL, '2026-11-14 16:37:34'),
(13, 6, 'Access Deactivated', 'Active', 'Inactive', NULL, '2026-11-14 16:37:42'),
(14, 3, 'Tour Session Ended', 'Active', 'Ended', NULL, '2026-11-14 16:37:49'),
(15, 5, 'Access Expired', 'Pending', 'Expired', NULL, '2026-11-14 16:37:57'),
(16, 4, 'Access Activated', 'Pending', 'Active', NULL, '2026-11-14 16:38:11'),
(17, 7, 'Access Activated', 'Pending', 'Active', NULL, '2026-11-15 07:54:54'),
(18, 8, 'Access Activated', 'Pending', 'Active', NULL, '2026-11-15 09:01:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `unique_username` (`username`),
  ADD UNIQUE KEY `unique_qr_token` (`qr_token`);

--
-- Indexes for table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`destinations_id`);

--
-- Indexes for table `destination_images`
--
ALTER TABLE `destination_images`
  ADD PRIMARY KEY (`images_id`);

--
-- Indexes for table `tour_sessions`
--
ALTER TABLE `tour_sessions`
  ADD PRIMARY KEY (`tour_sessions_id`),
  ADD UNIQUE KEY `tour_id` (`tour_id`),
  ADD UNIQUE KEY `access_code` (`access_code`),
  ADD UNIQUE KEY `device_token_hash` (`device_token_hash`);

--
-- Indexes for table `tour_session_logs`
--
ALTER TABLE `tour_session_logs`
  ADD PRIMARY KEY (`tour_session_logs_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `destinations_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `destination_images`
--
ALTER TABLE `destination_images`
  MODIFY `images_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tour_sessions`
--
ALTER TABLE `tour_sessions`
  MODIFY `tour_sessions_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tour_session_logs`
--
ALTER TABLE `tour_session_logs`
  MODIFY `tour_session_logs_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

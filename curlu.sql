-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 30, 2025 at 02:04 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `curlu`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_us`
--

CREATE TABLE `about_us` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `about_us`
--

INSERT INTO `about_us` (`id`, `title`, `description`, `created_at`, `updated_at`) VALUES
(1, 'About Us', '<p><em>hii how are you</em></p>', '2025-04-28 04:36:32', '2025-05-05 05:31:41');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `category_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `category_image`, `created_at`, `updated_at`) VALUES
(1, 'Afro hair care', 'adminAsset/category_image/1344183802.png', '2025-03-19 04:29:28', '2025-05-01 02:20:50'),
(2, 'Haircut (Men / Women / Kids)', 'adminAsset/category_image/35417685.jpg', '2025-04-28 02:54:38', '2025-04-28 02:54:38'),
(3, 'Hair Highlights', 'adminAsset/category_image/1052631442.jpg', '2025-04-28 02:55:10', '2025-04-28 02:55:10'),
(4, 'Hair Styling', 'adminAsset/category_image/636530488.jpg', '2025-04-28 02:55:52', '2025-04-28 02:55:52'),
(5, 'Basic Facial', 'adminAsset/category_image/1227732862.jpg', '2025-04-28 02:58:52', '2025-04-28 02:58:52'),
(6, 'Skin Whitening Facial', 'adminAsset/category_image/1565011326.jpg', '2025-04-28 02:59:26', '2025-04-28 02:59:26'),
(7, 'Manicure', 'adminAsset/category_image/908507131.jpg', '2025-04-28 03:04:23', '2025-04-28 03:04:23');

-- --------------------------------------------------------

--
-- Table structure for table `ch_favorites`
--

CREATE TABLE `ch_favorites` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint NOT NULL,
  `favorite_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ch_messages`
--

CREATE TABLE `ch_messages` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_id` bigint NOT NULL,
  `to_id` bigint NOT NULL,
  `body` varchar(5000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint UNSIGNED NOT NULL,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `created_at`, `updated_at`) VALUES
(3, 'How are you ?', 'I am fine . What are doing now ?', '2025-05-01 00:51:24', '2025-05-01 00:51:24');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `salon_id` bigint UNSIGNED DEFAULT NULL,
  `payment_detail_id` bigint UNSIGNED DEFAULT NULL,
  `comments` text COLLATE utf8mb4_unicode_ci,
  `review` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint UNSIGNED NOT NULL,
  `sender_id` bigint UNSIGNED NOT NULL,
  `receiver_id` bigint UNSIGNED NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `image`, `created_at`, `updated_at`) VALUES
(1, 5, 6, 'hhhhh', NULL, '2025-03-19 04:44:33', '2025-03-19 04:44:33'),
(2, 6, 5, 'helow User', NULL, '2025-03-19 05:04:13', '2025-03-19 05:04:13'),
(3, 2, 3, 'Ut modi rem atque aut.', NULL, '2025-03-19 05:28:44', '2025-03-19 05:28:44'),
(4, 3, 2, 'Voluptate repudiandae voluptas qui molestiae molestiae facilis sint.', NULL, '2025-03-19 05:29:14', '2025-03-19 05:29:14'),
(5, 2, 3, 'Itaque voluptatem dolore at natus.', NULL, '2025-03-19 05:40:54', '2025-03-19 05:40:54'),
(6, 3, 2, 'Est quo dolor aut officiis perspiciatis libero.', NULL, '2025-03-19 05:44:45', '2025-03-19 05:44:45'),
(7, 2, 3, 'Id ea quidem voluptas iste ex ipsa dolorem corrupti.', NULL, '2025-03-19 05:44:58', '2025-03-19 05:44:58'),
(8, 3, 2, 'Aut sit nesciunt nostrum officia natus sint neque eum.', NULL, '2025-03-19 05:50:25', '2025-03-19 05:50:25'),
(9, 2, 3, 'Hic quod rerum autem sint nesciunt illo aut.', NULL, '2025-03-19 05:50:37', '2025-03-19 05:50:37'),
(10, 5, 6, 'useerrrr', NULL, '2025-03-19 05:51:55', '2025-03-19 05:51:55'),
(11, 5, 6, 'hi', NULL, '2025-03-19 06:00:27', '2025-03-19 06:00:27'),
(12, 6, 5, 'hii', NULL, '2025-03-19 06:19:50', '2025-03-19 06:19:50'),
(13, 5, 6, 'hh', NULL, '2025-03-19 06:22:05', '2025-03-19 06:22:05'),
(14, 5, 6, 'hle', NULL, '2025-03-19 06:22:16', '2025-03-19 06:22:16'),
(15, 5, 6, 'hug', NULL, '2025-03-19 06:25:10', '2025-03-19 06:25:10'),
(16, 6, 5, 'trrtyrty rt', NULL, '2025-03-21 02:46:33', '2025-03-21 02:46:33'),
(17, 6, 5, 'llklk', NULL, '2025-03-21 02:48:20', '2025-03-21 02:48:20'),
(18, 6, 5, 'mhh', NULL, '2025-03-21 02:49:10', '2025-03-21 02:49:10'),
(19, 6, 5, 'jgfjj', NULL, '2025-03-21 02:52:21', '2025-03-21 02:52:21'),
(20, 6, 5, 'iu ou', NULL, '2025-03-21 02:53:36', '2025-03-21 02:53:36'),
(21, 6, 5, 'cgnngngf', NULL, '2025-03-21 02:55:48', '2025-03-21 02:55:48'),
(22, 6, 5, 'jjyuj', NULL, '2025-03-21 02:56:38', '2025-03-21 02:56:38'),
(23, 6, 5, 'rbuu tuktu6lyly', NULL, '2025-03-21 02:56:53', '2025-03-21 02:56:53'),
(24, 6, 5, 'hello', NULL, '2025-03-21 02:57:13', '2025-03-21 02:57:13'),
(25, 6, 5, '56u56u5', NULL, '2025-03-21 02:58:11', '2025-03-21 02:58:11'),
(26, 6, 5, 'io78io78io78', NULL, '2025-03-21 03:00:38', '2025-03-21 03:00:38'),
(27, 6, 5, 'ffffffff', NULL, '2025-03-21 03:00:46', '2025-03-21 03:00:46'),
(28, 6, 5, 'dfsf', NULL, '2025-03-21 03:05:30', '2025-03-21 03:05:30'),
(29, 6, 5, 'fgsdhgh fj', NULL, '2025-03-21 03:08:33', '2025-03-21 03:08:33'),
(30, 6, 5, 'hi', NULL, '2025-03-21 03:11:35', '2025-03-21 03:11:35'),
(31, 5, 6, 'hlw', NULL, '2025-03-21 03:13:20', '2025-03-21 03:13:20'),
(32, 6, 5, 'ggg', NULL, '2025-03-21 03:14:04', '2025-03-21 03:14:04'),
(33, 5, 6, 'goodnight', NULL, '2025-03-21 03:16:46', '2025-03-21 03:16:46'),
(34, 6, 5, 'good moring', NULL, '2025-03-21 03:17:17', '2025-03-21 03:17:17'),
(35, 5, 6, 'hle', NULL, '2025-03-21 03:19:42', '2025-03-21 03:19:42'),
(36, 6, 5, 'Hlw Jubayer', NULL, '2025-03-21 03:20:01', '2025-03-21 03:20:01'),
(37, 5, 6, 'good morning', NULL, '2025-03-21 03:20:15', '2025-03-21 03:20:15'),
(38, 5, 6, 'hey', NULL, '2025-03-21 04:40:37', '2025-03-21 04:40:37'),
(39, 6, 5, 'ogg', NULL, '2025-03-21 04:41:09', '2025-03-21 04:41:09'),
(40, 5, 6, 'book', NULL, '2025-03-21 04:43:04', '2025-03-21 04:43:04'),
(41, 5, 6, 'hh', NULL, '2025-03-21 05:01:32', '2025-03-21 05:01:32'),
(42, 6, 5, 'gggg', NULL, '2025-03-21 05:01:53', '2025-03-21 05:01:53'),
(43, 6, 5, 'hiiii', NULL, '2025-03-21 05:04:37', '2025-03-21 05:04:37'),
(44, 6, 5, 'dddd', NULL, '2025-03-21 05:05:07', '2025-03-21 05:05:07'),
(45, 5, 6, 'hhhh', NULL, '2025-03-21 05:05:25', '2025-03-21 05:05:25'),
(46, 6, 5, 'I am professional', NULL, '2025-03-21 23:23:05', '2025-03-21 23:23:05'),
(47, 5, 6, 'wow , Hope you are doing well', NULL, '2025-03-21 23:36:42', '2025-03-21 23:36:42'),
(48, 6, 5, 'Yes I am fine', NULL, '2025-03-21 23:36:57', '2025-03-21 23:36:57'),
(49, 5, 6, 'good', NULL, '2025-03-21 23:37:37', '2025-03-21 23:37:37'),
(50, 6, 5, 'oo', NULL, '2025-03-21 23:37:50', '2025-03-21 23:37:50'),
(51, 5, 6, 'hii', NULL, '2025-03-21 23:39:44', '2025-03-21 23:39:44'),
(52, 6, 5, 'hello', NULL, '2025-03-21 23:40:09', '2025-03-21 23:40:09'),
(53, 6, 5, 'hey user', NULL, '2025-03-21 23:45:38', '2025-03-21 23:45:38'),
(54, 6, 5, 'hey', NULL, '2025-03-21 23:46:51', '2025-03-21 23:46:51'),
(55, 6, 5, 'goood', NULL, '2025-03-21 23:50:25', '2025-03-21 23:50:25'),
(56, 5, 6, 'ddd', NULL, '2025-03-21 23:50:57', '2025-03-21 23:50:57'),
(57, 6, 5, 'ghh', NULL, '2025-03-21 23:51:02', '2025-03-21 23:51:02'),
(58, 5, 6, 'ggg', NULL, '2025-03-21 23:51:23', '2025-03-21 23:51:23'),
(59, 6, 5, 'ryhtrf', NULL, '2025-03-21 23:51:36', '2025-03-21 23:51:36'),
(60, 5, 6, 'good morning', NULL, '2025-03-21 23:54:42', '2025-03-21 23:54:42'),
(61, 6, 5, 'hii', NULL, '2025-03-21 23:55:44', '2025-03-21 23:55:44'),
(62, 5, 6, 'hhh', NULL, '2025-03-21 23:55:51', '2025-03-21 23:55:51'),
(63, 6, 5, 'hhii', NULL, '2025-03-22 02:06:18', '2025-03-22 02:06:18'),
(64, 6, 5, 'hhii', NULL, '2025-03-22 02:06:19', '2025-03-22 02:06:19'),
(65, 6, 5, 'hhii', NULL, '2025-03-22 02:06:19', '2025-03-22 02:06:19'),
(66, 6, 5, 'hhii', NULL, '2025-03-22 02:06:19', '2025-03-22 02:06:19'),
(67, 6, 5, 'hhii', NULL, '2025-03-22 02:06:19', '2025-03-22 02:06:19'),
(68, 6, 5, 'hello', NULL, '2025-03-22 03:00:08', '2025-03-22 03:00:08'),
(69, 5, 6, 'hii', NULL, '2025-03-22 03:00:37', '2025-03-22 03:00:37'),
(70, 5, 6, 'what are doing now', NULL, '2025-03-22 03:24:18', '2025-03-22 03:24:18'),
(71, 6, 5, 'work', NULL, '2025-03-22 03:25:10', '2025-03-22 03:25:10'),
(72, 6, 5, 'who are you', NULL, '2025-03-22 03:25:24', '2025-03-22 03:25:24'),
(73, 5, 6, 'I am owner of the shop', NULL, '2025-03-22 03:26:00', '2025-03-22 03:26:00'),
(74, 6, 5, 'oh yes', NULL, '2025-03-22 03:26:29', '2025-03-22 03:26:29'),
(75, 6, 5, 'where are you now', NULL, '2025-03-22 03:28:45', '2025-03-22 03:28:45'),
(76, 5, 6, 'Dhaka', NULL, '2025-03-22 03:29:02', '2025-03-22 03:29:02'),
(77, 5, 6, 'gulistan', NULL, '2025-03-22 04:03:11', '2025-03-22 04:03:11'),
(78, 5, 6, 'ok', NULL, '2025-03-22 04:03:31', '2025-03-22 04:03:31'),
(79, 5, 6, 'thanks', NULL, '2025-03-22 04:05:57', '2025-03-22 04:05:57'),
(80, 6, 5, 'welcome', NULL, '2025-03-22 04:06:06', '2025-03-22 04:06:06'),
(81, 6, 5, 'hii', NULL, '2025-03-22 04:06:48', '2025-03-22 04:06:48'),
(82, 5, 6, 'hello', NULL, '2025-03-22 04:07:11', '2025-03-22 04:07:11'),
(83, 6, 5, 'hh', NULL, '2025-03-22 04:19:30', '2025-03-22 04:19:30'),
(84, 5, 6, 'hii', NULL, '2025-03-22 04:22:29', '2025-03-22 04:22:29'),
(85, 5, 6, 'hello', NULL, '2025-03-22 04:22:39', '2025-03-22 04:22:39'),
(86, 6, 5, 'hi', NULL, '2025-03-22 04:24:49', '2025-03-22 04:24:49'),
(87, 5, 6, 'hii', NULL, '2025-03-22 04:25:01', '2025-03-22 04:25:01'),
(88, 6, 5, 'hhh', NULL, '2025-03-22 04:25:09', '2025-03-22 04:25:09'),
(89, 6, 5, 'kk', NULL, '2025-03-22 04:25:16', '2025-03-22 04:25:16'),
(90, 5, 6, 'hii', NULL, '2025-03-22 04:27:57', '2025-03-22 04:27:57'),
(91, 6, 5, 'hii', NULL, '2025-03-22 04:28:06', '2025-03-22 04:28:06'),
(92, 5, 6, 'kl', NULL, '2025-03-22 04:28:25', '2025-03-22 04:28:25'),
(93, 5, 6, 'kl', NULL, '2025-03-22 04:28:25', '2025-03-22 04:28:25'),
(94, 5, 6, 'hii', NULL, '2025-03-22 04:37:19', '2025-03-22 04:37:19'),
(95, 5, 6, 'went', NULL, '2025-03-22 04:37:45', '2025-03-22 04:37:45'),
(96, 5, 6, 'vbsbsb', NULL, '2025-03-22 04:37:48', '2025-03-22 04:37:48'),
(97, 6, 5, 'hi', NULL, '2025-03-22 05:29:19', '2025-03-22 05:29:19'),
(98, 6, 5, 'hi', NULL, '2025-04-07 05:41:06', '2025-04-07 05:41:06'),
(99, 5, 6, 'hii', NULL, '2025-04-08 02:22:23', '2025-04-08 02:22:23'),
(100, 5, 6, 'hii', NULL, '2025-04-08 02:22:26', '2025-04-08 02:22:26'),
(101, 5, 6, 'what 😦😦', NULL, '2025-04-08 02:22:36', '2025-04-08 02:22:36'),
(102, 5, 6, 'hello', NULL, '2025-04-22 03:45:38', '2025-04-22 03:45:38'),
(103, 6, 5, 'Hii', NULL, '2025-04-22 03:46:17', '2025-04-22 03:46:17'),
(104, 6, 5, 'What\'s up', NULL, '2025-04-22 03:46:22', '2025-04-22 03:46:22'),
(105, 5, 6, 'How are you', NULL, '2025-04-22 04:11:12', '2025-04-22 04:11:12'),
(106, 6, 5, 'now I am fine', NULL, '2025-04-22 04:11:33', '2025-04-22 04:11:33'),
(107, 6, 5, 'hello', NULL, '2025-04-22 04:35:16', '2025-04-22 04:35:16'),
(108, 6, 5, 'hii', NULL, '2025-04-22 04:35:20', '2025-04-22 04:35:20'),
(109, 6, 5, 'now I am professional', NULL, '2025-04-22 04:35:48', '2025-04-22 04:35:48'),
(110, 5, 6, 'hi', NULL, '2025-04-26 22:06:03', '2025-04-26 22:06:03'),
(111, 6, 5, 'test', NULL, '2025-04-28 03:07:49', '2025-04-28 03:07:49'),
(112, 6, 5, 'koi', NULL, '2025-04-28 04:10:16', '2025-04-28 04:10:16'),
(113, 5, 6, 'i am here', NULL, '2025-04-30 02:32:05', '2025-04-30 02:32:05'),
(114, 6, 5, 'ok got it', NULL, '2025-04-30 02:32:18', '2025-04-30 02:32:18'),
(115, 5, 6, 'great', NULL, '2025-04-30 02:33:09', '2025-04-30 02:33:09'),
(116, 6, 5, 'fine', NULL, '2025-04-30 02:38:44', '2025-04-30 02:38:44'),
(117, 5, 6, 'thanks', NULL, '2025-04-30 02:39:56', '2025-04-30 02:39:56'),
(118, 6, 5, 'okk', NULL, '2025-04-30 02:41:19', '2025-04-30 02:41:19'),
(119, 5, 6, 'well', NULL, '2025-04-30 02:44:58', '2025-04-30 02:44:58'),
(120, 6, 5, 'hii', NULL, '2025-04-30 02:45:26', '2025-04-30 02:45:26'),
(121, 5, 6, 'hello', NULL, '2025-04-30 02:49:45', '2025-04-30 02:49:45'),
(122, 6, 5, 'cutting', NULL, '2025-04-30 02:52:02', '2025-04-30 02:52:02'),
(123, 5, 6, 'hair ??', NULL, '2025-04-30 03:00:03', '2025-04-30 03:00:03'),
(124, 6, 5, 'yes', NULL, '2025-04-30 03:00:14', '2025-04-30 03:00:14'),
(125, 5, 6, 'ok come fast', NULL, '2025-04-30 03:00:35', '2025-04-30 03:00:35'),
(126, 6, 5, 'sure', NULL, '2025-04-30 03:06:13', '2025-04-30 03:06:13'),
(127, 5, 6, 'hii', NULL, '2025-05-01 02:29:20', '2025-05-01 02:29:20'),
(128, 6, 5, 'how are you', NULL, '2025-05-01 02:29:35', '2025-05-01 02:29:35'),
(129, 5, 6, 'hii', NULL, '2025-05-01 02:54:05', '2025-05-01 02:54:05'),
(130, 6, 5, 'hii', NULL, '2025-05-01 02:54:27', '2025-05-01 02:54:27'),
(131, 5, 6, 'kit', NULL, '2025-05-01 02:56:19', '2025-05-01 02:56:19'),
(132, 5, 6, 'hii', NULL, '2025-05-01 03:11:33', '2025-05-01 03:11:33'),
(133, 5, 6, 'hii', NULL, '2025-05-01 03:24:59', '2025-05-01 03:24:59'),
(134, 5, 6, 'hii', NULL, '2025-05-01 03:25:03', '2025-05-01 03:25:03'),
(135, 5, 6, 'hello', NULL, '2025-05-01 23:38:30', '2025-05-01 23:38:30'),
(136, 6, 5, 'hi', NULL, '2025-05-03 00:39:49', '2025-05-03 00:39:49'),
(137, 6, 5, '☺️☺️', NULL, '2025-05-03 00:40:45', '2025-05-03 00:40:45'),
(138, 5, 6, 'hello', NULL, '2025-05-03 00:40:53', '2025-05-03 00:40:53'),
(139, 6, 5, 'hii', NULL, '2025-05-17 05:31:27', '2025-05-17 05:31:27'),
(140, 6, 5, 'hello', NULL, '2025-05-17 05:35:26', '2025-05-17 05:35:26'),
(141, 5, 6, 'hello', NULL, '2025-05-17 05:35:59', '2025-05-17 05:35:59'),
(142, 6, 5, 'hhh', NULL, '2025-05-17 05:36:58', '2025-05-17 05:36:58'),
(143, 6, 5, 'hey', NULL, '2025-05-17 05:37:35', '2025-05-17 05:37:35'),
(144, 5, 6, 'yes 🙌', NULL, '2025-05-17 05:38:11', '2025-05-17 05:38:11'),
(145, 5, 6, 'yes 🙌', NULL, '2025-05-17 05:38:11', '2025-05-17 05:38:11'),
(146, 5, 6, 'yes 🙌', NULL, '2025-05-17 05:38:11', '2025-05-17 05:38:11'),
(147, 5, 6, 'yes 🙌', NULL, '2025-05-17 05:38:13', '2025-05-17 05:38:13'),
(148, 5, 6, 'yes 🙌', NULL, '2025-05-17 05:38:16', '2025-05-17 05:38:16'),
(149, 6, 5, 'i8', NULL, '2025-05-17 05:39:34', '2025-05-17 05:39:34'),
(150, 6, 5, 'hii', NULL, '2025-05-20 22:39:37', '2025-05-20 22:39:37'),
(151, 6, 5, 'hello', NULL, '2025-05-20 22:39:42', '2025-05-20 22:39:42'),
(152, 5, 6, 'hii', NULL, '2025-05-20 22:39:48', '2025-05-20 22:39:48'),
(153, 6, 5, 'hello', NULL, '2025-05-20 23:01:51', '2025-05-20 23:01:51'),
(154, 6, 5, 'hii', NULL, '2025-05-20 23:02:09', '2025-05-20 23:02:09'),
(155, 5, 6, 'hii', NULL, '2025-05-20 23:03:09', '2025-05-20 23:03:09'),
(156, 6, 5, 'hello', NULL, '2025-05-20 23:03:23', '2025-05-20 23:03:23'),
(157, 6, 5, 'what are you doing now', NULL, '2025-05-20 23:03:40', '2025-05-20 23:03:40'),
(158, 5, 6, 'working', NULL, '2025-05-20 23:03:54', '2025-05-20 23:03:54'),
(159, 6, 5, 'hhj', NULL, '2025-05-20 23:05:24', '2025-05-20 23:05:24'),
(160, 6, 5, 'hhh', NULL, '2025-05-20 23:17:32', '2025-05-20 23:17:32'),
(161, 5, 6, 'hello', NULL, '2025-05-20 23:29:42', '2025-05-20 23:29:42'),
(162, 6, 5, 'hii', NULL, '2025-05-20 23:32:10', '2025-05-20 23:32:10'),
(163, 5, 6, 'hii', NULL, '2025-05-20 23:32:16', '2025-05-20 23:32:16'),
(164, 5, 6, 'how', NULL, '2025-05-20 23:39:04', '2025-05-20 23:39:04'),
(165, 6, 5, 'hh', NULL, '2025-05-20 23:52:16', '2025-05-20 23:52:16'),
(166, 5, 6, 'gg', NULL, '2025-05-20 23:55:20', '2025-05-20 23:55:20'),
(167, 6, 5, 'hii', NULL, '2025-05-20 23:55:37', '2025-05-20 23:55:37'),
(168, 6, 5, 'hii', NULL, '2025-05-20 23:58:10', '2025-05-20 23:58:10'),
(169, 6, 5, 'hii', NULL, '2025-05-20 23:58:37', '2025-05-20 23:58:37'),
(170, 5, 6, 'hello', NULL, '2025-05-20 23:58:44', '2025-05-20 23:58:44'),
(171, 5, 6, 'how much', NULL, '2025-05-20 23:58:49', '2025-05-20 23:58:49'),
(172, 4, 3, 'Aliquid possimus quo tenetur cum harum et voluptatem aut ut.', NULL, '2025-05-21 23:35:07', '2025-05-21 23:35:07'),
(173, 4, 3, 'Perferendis ipsum officia aliquid minima maxime magnam.', NULL, '2025-05-21 23:35:08', '2025-05-21 23:35:08'),
(174, 4, 3, 'Aut et sed.', NULL, '2025-05-21 23:35:09', '2025-05-21 23:35:09'),
(175, 4, 3, 'Eum sunt sunt optio blanditiis perspiciatis qui incidunt dignissimos.', NULL, '2025-05-21 23:35:55', '2025-05-21 23:35:55'),
(176, 4, 3, 'Et asperiores neque.', NULL, '2025-05-21 23:37:18', '2025-05-21 23:37:18'),
(177, 4, 3, 'Necessitatibus ut voluptas aut non.', NULL, '2025-05-21 23:37:24', '2025-05-21 23:37:24'),
(178, 4, 3, 'Illum et animi dolores nisi illum provident.', NULL, '2025-05-22 00:01:40', '2025-05-22 00:01:40'),
(179, 4, 3, 'Ad non voluptas non.', 'adminAsset/message_image/640023579.png', '2025-05-22 00:03:35', '2025-05-22 00:03:35'),
(180, 5, 2, 'Ut deleniti repudiandae ut.', 'adminAsset/message_image/222287296.png', '2025-06-21 00:12:03', '2025-06-21 00:12:03'),
(181, 5, 2, 'Hello I am nadim', 'adminAsset/message_image/270764666.png', '2025-06-21 00:13:24', '2025-06-21 00:13:24'),
(182, 5, 2, 'Hello I am nadim', 'adminAsset/message_image/909124938.png', '2025-06-21 00:34:19', '2025-06-21 00:34:19');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_06_26_065222_create_salons_table', 1),
(6, '2024_06_29_102008_create_payment_details_table', 1),
(7, '2024_07_01_101303_create_categories_table', 1),
(8, '2024_07_02_042505_create_salon_services_table', 1),
(9, '2024_07_02_110842_create_shop_categories_table', 1),
(10, '2024_07_02_113403_create_products_table', 1),
(11, '2024_07_03_051113_create_sliders_table', 1),
(12, '2024_07_05_035207_create_about_us_table', 1),
(13, '2024_07_05_043119_create_terms_conditions_table', 1),
(14, '2024_07_05_043927_create_privacy_policies_table', 1),
(15, '2024_07_05_051140_create_faqs_table', 1),
(16, '2024_08_22_999999_add_active_status_to_users', 1),
(17, '2024_08_22_999999_add_avatar_to_users', 1),
(18, '2024_08_22_999999_add_dark_mode_to_users', 1),
(19, '2024_08_22_999999_add_messenger_color_to_users', 1),
(20, '2024_08_22_999999_create_chatify_favorites_table', 1),
(21, '2024_08_22_999999_create_chatify_messages_table', 1),
(22, '2024_11_07_084704_add_popular_to_salon_services_table', 1),
(23, '2024_11_08_053740_create_salon_invoices_table', 1),
(24, '2024_11_09_034004_create_notifications_table', 1),
(25, '2024_11_09_034318_create_salon_schedule_times_table', 1),
(26, '2024_11_09_055353_create_feedback_table', 1),
(27, '2024_11_09_091402_add_latitude_longitude_to_users_table', 1),
(28, '2024_11_10_052410_add_wishlist_to_salon_services_table', 1),
(29, '2024_11_10_062528_create_orders_table', 1),
(30, '2024_11_22_054828_create_messages_table', 1),
(31, '2024_12_06_121311_create_schedules_table', 1),
(32, '2024_12_22_112451_create_reviews_table', 1),
(33, '2024_12_23_060207_create_service_wishlists_table', 1),
(34, '2024_12_23_060238_create_product_wishlists_table', 1),
(36, '2025_05_16_091140_create_platform_fees_table', 2),
(37, '2025_05_22_053135_add_column_to_messages_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('0c82aacc-c73c-4d6a-a6a5-5ad079676a61', 'App\\Notifications\\OrderConfirmNotification', 'App\\Models\\User', 5, '{\"message\":\"Your order has been completed\",\"order\":\"Chingnon from Pro Master\",\"created_at\":\"2025-05-01T08:24:50.312481Z\"}', NULL, '2025-05-01 02:24:50', '2025-05-01 02:24:50'),
('127f1362-be84-4274-82af-01e166b037e2', 'App\\Notifications\\NewSalonNotification', 'App\\Models\\User', 1, '{\"message\":\"A new Salon has arrived\",\"name\":\"Sazzat Hossen\",\"address\":\"Banasree , B block , dhaka 120\",\"created_at\":\"2025-05-22T08:40:29.545541Z\"}', NULL, '2025-05-22 02:40:29', '2025-05-22 02:40:29'),
('144dd30f-ac50-4c9a-ac56-6f6a95b415cd', 'App\\Notifications\\NewOrder', 'App\\Models\\User', 6, '{\"message\":\"You have a new order.\",\"order\":\"Nadim Khan placed a new order.\",\"created_at\":\"2025-05-01T08:24:18.630341Z\"}', NULL, '2025-05-01 02:24:18', '2025-05-01 02:24:18'),
('307538c2-7234-4730-b7c2-bdf6697bc542', 'App\\Notifications\\OrderConfirmNotification', 'App\\Models\\User', 5, '{\"message\":\"Your order has been completed\",\"order\":\"Chingnon from Pro Master\",\"created_at\":\"2025-05-01T08:24:51.008582Z\"}', NULL, '2025-05-01 02:24:51', '2025-05-01 02:24:51'),
('330e2194-164e-4c0d-a918-cd7fdd8551d8', 'App\\Notifications\\NewSalonNotification', 'App\\Models\\User', 4, '{\"message\":\"A new Salon has arrived\",\"name\":\"Sazzat Hossen\",\"address\":\"Banasree , B block , dhaka 120\",\"created_at\":\"2025-05-22T08:38:40.282830Z\"}', NULL, '2025-05-22 02:38:40', '2025-05-22 02:38:40'),
('37d94eba-7611-4c7a-afc8-17ec68935a62', 'App\\Notifications\\OrderConfirmNotification', 'App\\Models\\User', 5, '{\"message\":\"Your order has been completed\",\"order\":\"Chingnon from Pro Master\",\"created_at\":\"2025-05-01T08:24:51.851697Z\"}', NULL, '2025-05-01 02:24:51', '2025-05-01 02:24:51'),
('38b4749c-5afd-44df-92c8-cb90277f021a', 'App\\Notifications\\NewOrder', 'App\\Models\\User', 6, '{\"message\":\"You have a new order.\",\"order\":\"Soheb Hasan placed a new order.\",\"created_at\":\"2025-05-16T09:48:18.646135Z\"}', NULL, '2025-05-16 03:48:18', '2025-05-16 03:48:18'),
('49ffa677-34a4-419c-98db-0ac770d01b6b', 'App\\Notifications\\NewSalonNotification', 'App\\Models\\User', 4, '{\"message\":\"A new Salon has arrived\",\"name\":\"Sazzat Hossen\",\"address\":\"Banasree , B block , dhaka 120\",\"created_at\":\"2025-05-22T08:40:29.547988Z\"}', NULL, '2025-05-22 02:40:29', '2025-05-22 02:40:29'),
('4a481451-7c83-431e-bd96-8bf1735c16c4', 'App\\Notifications\\NewSalonNotification', 'App\\Models\\User', 4, '{\"message\":\"A new Salon has arrived\",\"name\":\"Sazzat Hossen\",\"address\":\"Banasree , B block , dhaka 120\",\"created_at\":\"2025-05-22T05:27:13.925973Z\"}', NULL, '2025-05-21 23:27:13', '2025-05-21 23:27:13'),
('4b7bb185-608c-4d80-b358-e3a23e62d63f', 'App\\Notifications\\OrderConfirmNotification', 'App\\Models\\User', 5, '{\"message\":\"Your order has been completed\",\"order\":\"Chingnon from Pro Master\",\"created_at\":\"2025-05-01T08:24:49.244772Z\"}', NULL, '2025-05-01 02:24:49', '2025-05-01 02:24:49'),
('4b947dc8-a81c-4fcc-894a-479347cf17c3', 'App\\Notifications\\NewOrder', 'App\\Models\\User', 6, '{\"message\":\"You have a new order.\",\"order\":\"Soheb Hasan placed a new order.\",\"created_at\":\"2025-05-05T12:54:58.622199Z\"}', NULL, '2025-05-05 06:54:58', '2025-05-05 06:54:58'),
('573b204f-1324-4ead-ba2d-1e9d3208ee31', 'App\\Notifications\\NewSalonNotification', 'App\\Models\\User', 1, '{\"message\":\"A new Salon has arrived\",\"name\":\"Sazzat Hossen\",\"address\":\"Banasree , B block , dhaka 120\",\"created_at\":\"2025-05-22T05:24:18.050002Z\"}', NULL, '2025-05-21 23:24:18', '2025-05-21 23:24:18'),
('5aa7da20-c1fe-4dca-bdf0-03773279ca34', 'App\\Notifications\\OrderConfirmNotification', 'App\\Models\\User', 5, '{\"message\":\"Your order has been completed\",\"order\":\"Chingnon from Pro Master\",\"created_at\":\"2025-05-01T08:24:51.738878Z\"}', NULL, '2025-05-01 02:24:51', '2025-05-01 02:24:51'),
('62b4bfab-290c-44be-810d-222cb14dd964', 'App\\Notifications\\OtherPaidNotification', 'App\\Models\\User', 5, '{\"message\":\"Your order paid by other person\",\"order\":\"Chingnon from Pro Cutter\",\"created_at\":\"2025-06-20T10:20:16.874551Z\"}', NULL, '2025-06-20 04:20:16', '2025-06-20 04:20:16'),
('6b71ca6c-8495-4e85-9ca3-05ff7914a375', 'App\\Notifications\\OrderConfirmNotification', 'App\\Models\\User', 5, '{\"message\":\"Your order has been completed\",\"order\":\"Chingnon from Pro Master\",\"created_at\":\"2025-05-01T08:24:51.115216Z\"}', NULL, '2025-05-01 02:24:51', '2025-05-01 02:24:51'),
('6ffa74ba-2c9c-4719-aa8b-f798773e0f12', 'App\\Notifications\\NewSalonNotification', 'App\\Models\\User', 1, '{\"message\":\"A new Salon has arrived\",\"name\":\"Sazzat Hossen\",\"address\":\"Banasree , B block , dhaka 120\",\"created_at\":\"2025-04-30T09:07:02.732333Z\"}', NULL, '2025-04-30 03:07:02', '2025-04-30 03:07:02'),
('7425532a-8a3e-4560-a308-fee4df3a589f', 'App\\Notifications\\NewOrder', 'App\\Models\\User', 6, '{\"message\":\"You have a new order.\",\"order\":\"Nadim Hasan placed a new order.\",\"created_at\":\"2025-04-29T11:56:39.887138Z\"}', NULL, '2025-04-29 05:56:39', '2025-04-29 05:56:39'),
('74e29b32-ea8d-4309-817c-18da9fff8c8e', 'App\\Notifications\\NewSalonNotification', 'App\\Models\\User', 1, '{\"message\":\"A new Salon has arrived\",\"name\":\"Sazzat Hossen\",\"address\":\"Banasree , B block , dhaka 120\",\"created_at\":\"2025-05-22T05:25:55.481832Z\"}', NULL, '2025-05-21 23:25:55', '2025-05-21 23:25:55'),
('7f0e335e-aa0b-4a0b-80a4-e34ecdb11c5d', 'App\\Notifications\\NewSalonNotification', 'App\\Models\\User', 1, '{\"message\":\"A new Salon has arrived\",\"name\":\"Sazzat Hossen\",\"address\":\"Banasree , B block , dhaka 120\",\"created_at\":\"2025-05-22T05:27:13.923460Z\"}', NULL, '2025-05-21 23:27:13', '2025-05-21 23:27:13'),
('81c6cba1-f083-4980-b7a3-ce0b3ffca18a', 'App\\Notifications\\NewOrder', 'App\\Models\\User', 6, '{\"message\":\"You have a new order.\",\"order\":\"Soheb Hasan placed a new order.\",\"created_at\":\"2025-05-16T09:47:30.227295Z\"}', NULL, '2025-05-16 03:47:30', '2025-05-16 03:47:30'),
('8b69b031-f353-4f09-ad9f-a62c209c0246', 'App\\Notifications\\OrderConfirmNotification', 'App\\Models\\User', 5, '{\"message\":\"Your order has been completed\",\"order\":\"Chingnon from Pro Master\",\"created_at\":\"2025-05-01T08:24:49.736516Z\"}', NULL, '2025-05-01 02:24:49', '2025-05-01 02:24:49'),
('8e10d31d-27fc-4b25-b496-efc0590af7cf', 'App\\Notifications\\NewSalonNotification', 'App\\Models\\User', 1, '{\"message\":\"A new Salon has arrived\",\"name\":\"Sazzat Hossen\",\"address\":\"Banasree , B block , dhaka 120\",\"created_at\":\"2025-04-30T09:08:33.570614Z\"}', NULL, '2025-04-30 03:08:33', '2025-04-30 03:08:33'),
('ae78e8f7-dfe7-4774-9036-9c348a0c580e', 'App\\Notifications\\NewSalonNotification', 'App\\Models\\User', 4, '{\"message\":\"A new Salon has arrived\",\"name\":\"Sazzat Hossen\",\"address\":\"Banasree , B block , dhaka 120\",\"created_at\":\"2025-04-30T09:08:33.572859Z\"}', NULL, '2025-04-30 03:08:33', '2025-04-30 03:08:33'),
('b5ad7422-0b71-4a4b-8a54-6e5b9cd8be4e', 'App\\Notifications\\NewSalonNotification', 'App\\Models\\User', 4, '{\"message\":\"A new Salon has arrived\",\"name\":\"Sazzat Hossen\",\"address\":\"Banasree , B block , dhaka 120\",\"created_at\":\"2025-05-22T05:24:18.081131Z\"}', NULL, '2025-05-21 23:24:18', '2025-05-21 23:24:18'),
('cde91658-4a4e-47c5-8169-efc4ee8b7bce', 'App\\Notifications\\OrderConfirmNotification', 'App\\Models\\User', 5, '{\"message\":\"Your order has been completed\",\"order\":\"Chingnon from Pro Master\",\"created_at\":\"2025-05-01T08:24:49.848756Z\"}', NULL, '2025-05-01 02:24:49', '2025-05-01 02:24:49'),
('d268b08a-cb55-4297-a8c0-585a8a684391', 'App\\Notifications\\OrderConfirmNotification', 'App\\Models\\User', 5, '{\"message\":\"Your order has been completed\",\"order\":\"Chingnon from Pro Master\",\"created_at\":\"2025-05-01T08:24:49.119128Z\"}', NULL, '2025-05-01 02:24:49', '2025-05-01 02:24:49'),
('d6e4801f-0729-4612-9de4-b508d3595c8f', 'App\\Notifications\\OrderConfirmNotification', 'App\\Models\\User', 5, '{\"message\":\"Your order has been completed\",\"order\":\"Chingnon from Pro Master\",\"created_at\":\"2025-05-01T08:24:50.422455Z\"}', NULL, '2025-05-01 02:24:50', '2025-05-01 02:24:50'),
('d7484b5f-11e4-414d-a879-c7aa21452305', 'App\\Notifications\\NewSalonNotification', 'App\\Models\\User', 4, '{\"message\":\"A new Salon has arrived\",\"name\":\"Sazzat Hossen\",\"address\":\"Banasree , B block , dhaka 120\",\"created_at\":\"2025-04-30T09:07:02.748603Z\"}', NULL, '2025-04-30 03:07:02', '2025-04-30 03:07:02'),
('d800bd87-7b2e-468b-8e0d-96d2dd7f8f4f', 'App\\Notifications\\NewSalonNotification', 'App\\Models\\User', 4, '{\"message\":\"A new Salon has arrived\",\"name\":\"Sazzat Hossen\",\"address\":\"Banasree , B block , dhaka 120\",\"created_at\":\"2025-05-22T05:25:55.484840Z\"}', NULL, '2025-05-21 23:25:55', '2025-05-21 23:25:55'),
('da3e3001-8a5d-4db8-af69-4254be2464e8', 'App\\Notifications\\NewOrder', 'App\\Models\\User', 6, '{\"message\":\"You have a new order.\",\"order\":\"Soheb Hasan placed a new order.\",\"created_at\":\"2025-05-05T12:50:01.631076Z\"}', NULL, '2025-05-05 06:50:01', '2025-05-05 06:50:01'),
('ec338680-374a-4ee8-a8dd-cb34d4d46610', 'App\\Notifications\\NewSalonNotification', 'App\\Models\\User', 1, '{\"message\":\"A new Salon has arrived\",\"name\":\"Sazzat Hossen\",\"address\":\"Banasree , B block , dhaka 120\",\"created_at\":\"2025-05-22T08:38:40.275463Z\"}', NULL, '2025-05-22 02:38:40', '2025-05-22 02:38:40');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `salon_id` bigint UNSIGNED NOT NULL,
  `service_id` bigint UNSIGNED NOT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double(8,2) NOT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `curlu_earning` double DEFAULT NULL,
  `salon_earning` double DEFAULT NULL,
  `status` enum('pending','processing','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `description` text COLLATE utf8mb4_unicode_ci,
  `schedule_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `schedule_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `salon_id`, `service_id`, `invoice_number`, `amount`, `completed_at`, `curlu_earning`, `salon_earning`, `status`, `description`, `schedule_date`, `schedule_time`, `created_at`, `updated_at`) VALUES
(2, 5, 1, 7, '77681074', 100.00, '2025-04-26 23:20:21', 3, 97, 'completed', 'N/A', '2025-02-28', '10:00 AM', '2025-04-26 23:20:21', '2025-04-26 23:20:21'),
(3, 5, 1, 5, '41719802', 100.00, '2025-04-26 23:21:59', 3, 97, 'completed', 'N/A', '2025-04-22', '9:30 am', '2025-04-26 23:21:59', '2025-04-27 04:01:04'),
(4, 5, 1, 5, '4170324', 100.00, '2025-04-28 04:52:29', 3, 97, 'completed', 'N/A', '2025-04-23', '9:30 am', '2025-04-28 04:52:29', '2025-04-28 04:53:13'),
(5, 5, 1, 12, '86892221', 100.00, '2025-04-28 05:04:20', 3, 97, 'completed', 'N/A', '2025-04-30', '9:30', '2025-04-28 05:04:20', '2025-04-29 04:14:37'),
(8, 5, 1, 12, '44686017', 100.00, '2025-04-29 05:56:39', 3, 97, 'pending', 'N/A', '2025-04-30', '9:30 pm', '2025-04-29 05:56:39', '2025-04-29 05:56:39'),
(9, 5, 1, 5, '7425697', 100.00, '2025-05-01 02:24:17', 3, 97, 'completed', 'N/A', '2025-05-27', '10:30 ', '2025-05-01 02:24:17', '2025-05-01 02:24:49'),
(14, 5, 1, 5, '22660735', 10.00, '2025-06-20 04:20:16', 0.3, 9.7, 'completed', 'N/A', '2025-01-15', '11:12', '2025-06-20 04:20:16', '2025-06-20 04:20:16');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_details`
--

CREATE TABLE `payment_details` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `due_date` date NOT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paid` tinyint(1) NOT NULL DEFAULT '0',
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_payment_id` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_details`
--

INSERT INTO `payment_details` (`id`, `user_id`, `email`, `amount`, `description`, `due_date`, `invoice_number`, `paid`, `link`, `stripe_payment_id`, `created_at`, `updated_at`) VALUES
(1, 5, 'jubayer6596@gmail.com', 333, 'N/A', '2025-04-24', '25749624', 1, 'N/A', 'pi_3RHN43KOpUtqOuW101qrV6zy', '2025-04-24 04:32:19', '2025-04-24 04:32:19'),
(2, 5, 'jubayer6596@gmail.com', 100, 'N/A', '2025-04-27', '77681074', 1, 'N/A', 'pi_3RINd7KOpUtqOuW1043wXQ8L', '2025-04-26 23:20:21', '2025-04-26 23:20:21'),
(3, 5, 'jubayer6596@gmail.com', 100, 'N/A', '2025-04-27', '41719802', 1, 'N/A', 'pi_3RINerKOpUtqOuW10KSTRlrI', '2025-04-26 23:21:59', '2025-04-26 23:21:59'),
(4, 5, 'jubayer6596@gmail.com', 100, 'N/A', '2025-04-28', '4170324', 1, 'N/A', 'pi_3RIpI9KOpUtqOuW11l4uO25J', '2025-04-28 04:52:29', '2025-04-28 04:52:29'),
(5, 5, 'jubayer6596@gmail.com', 100, 'N/A', '2025-04-28', '86892221', 1, 'N/A', 'pi_3RIpTgKOpUtqOuW10Q221lW4', '2025-04-28 05:04:20', '2025-04-28 05:04:20'),
(6, 5, 'jubayer6596@gmail.com', 100, 'N/A', '2025-04-29', '38654853', 1, 'N/A', 'pi_3Qpp5cKOpUtqOuW10isOzigL', '2025-04-29 04:09:58', '2025-04-29 04:09:58'),
(7, 5, 'jubayer6596@gmail.com', 10, 'N/A', '2025-04-29', '56575116', 1, 'N/A', 'pi_3RJBFzKOpUtqOuW11xVMGB7i', '2025-04-29 04:19:21', '2025-04-29 04:19:21'),
(8, 5, 'jubayer6596@gmail.com', 100, 'N/A', '2025-04-29', '44686017', 1, 'N/A', 'pi_3RJClkKOpUtqOuW11VhzOtfT', '2025-04-29 05:56:39', '2025-04-29 05:56:39'),
(9, 5, 'jubayer6596@gmail.com', 100, 'N/A', '2025-05-01', '7425697', 1, 'N/A', 'pi_3RJsP9KOpUtqOuW10glkh9bO', '2025-05-01 02:24:17', '2025-05-01 02:24:17'),
(10, 5, 'jubayer6596@gmail.com', 100, 'N/A', '2025-05-05', '59147114', 1, 'N/A', 'pi_3Qpp5cKOpUtqOuW10isOzigL', '2025-05-05 06:50:00', '2025-05-05 06:50:00'),
(11, 5, 'jubayer6596@gmail.com', 100, 'N/A', '2025-05-05', '54954091', 1, 'N/A', 'pi_3Qpp5cKOpUtqOuW10isOzigL', '2025-05-05 06:54:58', '2025-05-05 06:54:58'),
(12, 5, 'jubayer6596@gmail.com', 100.2, 'N/A', '2025-05-16', '26234022', 1, 'N/A', 'pi_3Qpp5cKOpUtqOuW10isOzigL', '2025-05-16 03:47:29', '2025-05-16 03:47:29'),
(13, 5, 'jubayer6596@gmail.com', 100.2, 'N/A', '2025-05-16', '74219568', 1, 'N/A', 'pi_3Qpp5cKOpUtqOuW10isOzigL', '2025-05-16 03:48:18', '2025-05-16 03:48:18'),
(14, 5, 'jubayer6596@gmail.com', 10, 'N/A', '2025-06-20', '22660735', 1, 'N/A', 'pi_3Rc23MKOpUtqOuW10L6PnM6T', '2025-06-20 04:20:15', '2025-06-20 04:20:15');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `platform_fees`
--

CREATE TABLE `platform_fees` (
  `id` bigint UNSIGNED NOT NULL,
  `curlu_earning` double(8,2) NOT NULL DEFAULT '3.00' COMMENT 'in percentage',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `platform_fees`
--

INSERT INTO `platform_fees` (`id`, `curlu_earning`, `created_at`, `updated_at`) VALUES
(1, 3.24, '2025-05-16 04:03:38', '2025-05-21 00:06:17');

-- --------------------------------------------------------

--
-- Table structure for table `privacy_policies`
--

CREATE TABLE `privacy_policies` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `privacy_policies`
--

INSERT INTO `privacy_policies` (`id`, `title`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Privacy Policy 1', 'Welcome to [Salon App Name] (“we”, “our”, “us”). This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our mobile application (“App”).', '2025-04-28 04:36:38', '2025-04-28 04:36:38');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `shop_category_id` bigint UNSIGNED NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_details` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `shop_category_id`, `product_name`, `product_image`, `product_link`, `product_details`, `created_at`, `updated_at`) VALUES
(1, 7, 'Eye Makeup Brushes and Tools', 'adminAsset/product_image/203730767.jpg', 'https://www.google.com', 'this is deteails', '2025-04-28 04:50:03', '2025-04-28 04:50:03'),
(2, 7, 'Eye Makeup Brushes', 'adminAsset/product_image/1794809028.jpg', 'https://www.google.com', 'this is deteails', '2025-04-28 04:50:27', '2025-04-28 04:50:27'),
(3, 7, 'Eye Makeup', 'adminAsset/product_image/1929424063.jpg', 'https://www.google.com', 'this is deteails', '2025-04-28 04:51:16', '2025-04-28 04:51:16'),
(4, 8, 'French Crop', 'adminAsset/product_image/523620534.png', 'https://unsplash.com/photos/person-holding-white-and-gold-hair-comb-sS3qRFsKZlg', 'good', '2025-04-28 05:48:54', '2025-04-28 05:48:54'),
(5, 8, 'French Crop', 'adminAsset/product_image/1962999633.png', 'https://unsplash.com/photos/person-holding-white-and-gold-hair-comb-sS3qRFsKZlg', 'good', '2025-04-28 05:48:54', '2025-04-28 05:48:54'),
(7, 8, 'Bro Flow', 'adminAsset/product_image/1781781310.png', 'https://unsplash.com/photos/person-holding-white-and-gold-hair-comb-sS3qRFsKZlg', 'good', '2025-04-28 05:50:18', '2025-04-28 05:50:18');

-- --------------------------------------------------------

--
-- Table structure for table `product_wishlists`
--

CREATE TABLE `product_wishlists` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_wishlists`
--

INSERT INTO `product_wishlists` (`id`, `product_id`, `user_id`, `created_at`, `updated_at`) VALUES
(2, 2, 5, '2025-04-28 06:27:28', '2025-04-28 06:27:28');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `salon_id` bigint UNSIGNED NOT NULL,
  `service_id` bigint UNSIGNED NOT NULL,
  `salon_invoice_id` bigint UNSIGNED DEFAULT NULL,
  `rating` bigint UNSIGNED DEFAULT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `salon_id`, `service_id`, `salon_invoice_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 5, 7, 5, 3, 5, 'Fantastic', '2025-04-26 23:23:54', '2025-04-26 23:23:54'),
(2, 5, 7, 5, 3, 4, 'Good', '2025-04-27 04:53:30', '2025-04-27 04:53:30'),
(3, 5, 2, 2, 3, 4, 'Awsome service yet new', '2025-05-01 00:38:29', '2025-05-01 00:38:29'),
(4, 5, 1, 5, 9, 5, 'excellent', '2025-05-21 01:03:35', '2025-05-21 01:03:35'),
(5, 5, 1, 12, 5, 5, 'good job', '2025-05-21 01:03:54', '2025-05-21 01:03:54'),
(6, 5, 1, 5, 3, 5, 'wow , excellent cutting 🥰', '2025-05-22 03:00:20', '2025-05-22 03:00:20');

-- --------------------------------------------------------

--
-- Table structure for table `salons`
--

CREATE TABLE `salons` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `experience` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salon_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salon_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_card` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iban_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kbis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_account_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salons`
--

INSERT INTO `salons` (`id`, `user_id`, `experience`, `salon_type`, `salon_description`, `id_card`, `iban_number`, `kbis`, `cover_image`, `stripe_account_id`, `created_at`, `updated_at`) VALUES
(1, 6, '5 years', 'haircut', 'good', 'adminAsset/id_card/1150966798.webp', '21242540d', '000999', NULL, NULL, '2025-03-19 04:21:14', '2025-05-16 03:07:14'),
(2, 7, NULL, NULL, NULL, 'adminAsset/id_card/1613948876.png', '123568', NULL, NULL, NULL, '2025-03-22 04:46:35', '2025-03-22 04:46:35'),
(3, 13, NULL, NULL, NULL, 'adminAsset/id_card/710196736.jpg', '13456', NULL, NULL, NULL, '2025-04-22 03:35:17', '2025-04-22 03:35:17'),
(4, 15, '5 years', 'jani na', 'this is all about salon description', NULL, '4556456', '65456456', NULL, NULL, '2025-04-29 04:12:10', '2025-04-29 04:12:10'),
(5, 16, '5 years', 'jani na', 'this is all about salon description', NULL, '4556456', '65456456', NULL, NULL, '2025-04-30 03:07:02', '2025-04-30 03:07:02'),
(6, 17, '5 years', 'jani na', 'this is all about salon description', NULL, '4556456', '65456456', NULL, NULL, '2025-04-30 03:08:33', '2025-04-30 03:08:33'),
(7, 23, '5 years', 'jani na', 'this is all about salon description', NULL, '4556456', 'adminAsset/kbis/1205069843.pdf', NULL, NULL, '2025-05-21 23:24:16', '2025-05-21 23:24:16'),
(8, 24, '5 years', 'jani na', 'this is all about salon description', 'adminAsset/id_card/288033965.png', '4556456', 'adminAsset/kbis/773829731.pdf', NULL, NULL, '2025-05-21 23:25:55', '2025-05-21 23:25:55'),
(9, 25, '5 years', 'jani na', 'this is all about salon description', 'adminAsset/id_card/254334788.png', '4556456', 'adminAsset/kbis/1428500052.pdf', NULL, NULL, '2025-05-21 23:27:13', '2025-05-21 23:27:13'),
(10, 26, '5 years', 'jani na', 'this is all about salon description', NULL, '4556456', NULL, NULL, NULL, '2025-05-22 02:38:40', '2025-05-22 02:38:40'),
(11, 27, '5 years', 'jani na', 'this is all about salon description', NULL, '4556456', 'adminAsset/kbis/1756256180.pdf', NULL, NULL, '2025-05-22 02:40:29', '2025-05-22 02:40:29');

-- --------------------------------------------------------

--
-- Table structure for table `salon_invoices`
--

CREATE TABLE `salon_invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `salon_id` bigint UNSIGNED DEFAULT NULL,
  `payment_detail_id` bigint UNSIGNED DEFAULT NULL,
  `service_id` bigint UNSIGNED DEFAULT NULL,
  `order_confirmation_date` timestamp NOT NULL,
  `payment` double NOT NULL,
  `curlu_earning` double NOT NULL,
  `salon_earning` double NOT NULL,
  `status` enum('Upcoming','Past') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Upcoming',
  `schedule_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `schedule_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salon_invoices`
--

INSERT INTO `salon_invoices` (`id`, `user_id`, `salon_id`, `payment_detail_id`, `service_id`, `order_confirmation_date`, `payment`, `curlu_earning`, `salon_earning`, `status`, `schedule_date`, `schedule_time`, `invoice_number`, `created_at`, `updated_at`) VALUES
(2, 5, 1, 2, 7, '2025-04-26 23:20:21', 100, 3, 97, 'Upcoming', '2025-02-28', '10:00 AM', '77681074', '2025-04-26 23:20:21', '2025-04-26 23:20:21'),
(3, 5, 1, 3, 5, '2025-04-26 23:21:59', 100, 3, 97, 'Past', '2025-04-22', '9:30 am', '41719802', '2025-04-26 23:21:59', '2025-04-28 01:02:47'),
(4, 5, 1, 4, 5, '2025-04-28 04:52:29', 100, 3, 97, 'Past', '2025-04-23', '9:30 am', '4170324', '2025-04-28 04:52:29', '2025-04-28 04:53:13'),
(5, 5, 1, 5, 12, '2025-04-28 05:04:20', 100, 3, 97, 'Past', '2025-04-30', '9:30 am', '86892221', '2025-04-28 05:04:20', '2025-04-29 04:14:37'),
(8, 5, 1, 8, 12, '2025-04-29 05:56:39', 100, 3, 97, 'Upcoming', '2025-04-30', '9:30 am', '44686017', '2025-04-29 05:56:39', '2025-04-29 05:56:39'),
(9, 5, 1, 9, 5, '2025-05-01 02:24:17', 100, 3, 97, 'Past', '2025-05-27', '10:30 am', '7425697', '2025-05-01 02:24:17', '2025-05-01 02:24:49'),
(14, 5, 1, 14, 5, '2025-06-20 04:20:16', 10, 0.3, 9.7, 'Upcoming', '2025-01-15', '11:12', '22660735', '2025-06-20 04:20:16', '2025-06-20 04:20:16');

-- --------------------------------------------------------

--
-- Table structure for table `salon_schedule_times`
--

CREATE TABLE `salon_schedule_times` (
  `id` bigint UNSIGNED NOT NULL,
  `salon_id` bigint UNSIGNED NOT NULL,
  `schedule` json NOT NULL,
  `booking_time` json NOT NULL,
  `capacity` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salon_schedule_times`
--

INSERT INTO `salon_schedule_times` (`id`, `salon_id`, `schedule`, `booking_time`, `capacity`, `created_at`, `updated_at`) VALUES
(16, 1, '\"[{\\\"day\\\":\\\"sunday\\\",\\\"open_time\\\":\\\"4:29 PM\\\",\\\"close_time\\\":\\\"3:30 PM\\\"},{\\\"day\\\":\\\"monday\\\",\\\"open_time\\\":\\\"10:31 PM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"tuesday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"wednesday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"thursday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"friday\\\",\\\"open_time\\\":\\\"10:00 AM\\\",\\\"close_time\\\":\\\"4:00 PM\\\"},{\\\"day\\\":\\\"saturday\\\",\\\"open_time\\\":\\\"8:55 PM\\\",\\\"close_time\\\":\\\"7:57 PM\\\"}]\"', '[\"9.00am\", \"4.00pm\", \"9.30am\"]', 5, '2025-04-30 04:32:10', '2025-05-01 02:31:54'),
(17, 7, '\"[{\\\"day\\\":\\\"Sunday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"Monday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"Tuesday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"Wednesday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"Thursday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"Friday\\\",\\\"open_time\\\":\\\"10:00 AM\\\",\\\"close_time\\\":\\\"4:00 PM\\\"},{\\\"day\\\":\\\"Saturday\\\",\\\"open_time\\\":\\\"Closed\\\",\\\"close_time\\\":\\\"Closed\\\"}]\"', '[\"9.00am\", \"4.00pm\", \"9.30am\"]', 1, '2025-05-21 23:24:18', '2025-05-21 23:24:18'),
(18, 8, '\"[{\\\"day\\\":\\\"Sunday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"Monday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"Tuesday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"Wednesday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"Thursday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"Friday\\\",\\\"open_time\\\":\\\"10:00 AM\\\",\\\"close_time\\\":\\\"4:00 PM\\\"},{\\\"day\\\":\\\"Saturday\\\",\\\"open_time\\\":\\\"Closed\\\",\\\"close_time\\\":\\\"Closed\\\"}]\"', '[\"9.00am\", \"4.00pm\", \"9.30am\"]', 1, '2025-05-21 23:25:55', '2025-05-21 23:25:55'),
(19, 9, '\"[{\\\"day\\\":\\\"Sunday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"Monday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"Tuesday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"Wednesday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"Thursday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"Friday\\\",\\\"open_time\\\":\\\"10:00 AM\\\",\\\"close_time\\\":\\\"4:00 PM\\\"},{\\\"day\\\":\\\"Saturday\\\",\\\"open_time\\\":\\\"Closed\\\",\\\"close_time\\\":\\\"Closed\\\"}]\"', '[\"9.00am\", \"4.00pm\", \"9.30am\"]', 1, '2025-05-21 23:27:13', '2025-05-21 23:27:13'),
(20, 10, '\"[{\\\"day\\\":\\\"Sunday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"Monday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"Tuesday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"Wednesday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"Thursday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"Friday\\\",\\\"open_time\\\":\\\"10:00 AM\\\",\\\"close_time\\\":\\\"4:00 PM\\\"},{\\\"day\\\":\\\"Saturday\\\",\\\"open_time\\\":\\\"Closed\\\",\\\"close_time\\\":\\\"Closed\\\"}]\"', '[\"9.00am\", \"4.00pm\", \"9.30am\"]', 1, '2025-05-22 02:38:40', '2025-05-22 02:38:40'),
(21, 11, '\"[{\\\"day\\\":\\\"Sunday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"Monday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"Tuesday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"Wednesday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"Thursday\\\",\\\"open_time\\\":\\\"9:00 AM\\\",\\\"close_time\\\":\\\"5:00 PM\\\"},{\\\"day\\\":\\\"Friday\\\",\\\"open_time\\\":\\\"10:00 AM\\\",\\\"close_time\\\":\\\"4:00 PM\\\"},{\\\"day\\\":\\\"Saturday\\\",\\\"open_time\\\":\\\"Closed\\\",\\\"close_time\\\":\\\"Closed\\\"}]\"', '[\"9.00am\", \"4.00pm\", \"9.30am\"]', 1, '2025-05-22 02:40:29', '2025-05-22 02:40:29');

-- --------------------------------------------------------

--
-- Table structure for table `salon_services`
--

CREATE TABLE `salon_services` (
  `id` bigint UNSIGNED NOT NULL,
  `salon_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `service_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_price` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `popular` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salon_services`
--

INSERT INTO `salon_services` (`id`, `salon_id`, `category_id`, `service_name`, `service_description`, `price`, `discount_price`, `service_image`, `service_status`, `popular`, `created_at`, `updated_at`) VALUES
(2, 2, 1, 'hair cutt', NULL, '100', '10', 'adminAsset/service_image/1531809557.png', 'with appointment', 0, '2025-03-22 04:48:18', '2025-03-22 04:48:27'),
(5, 1, 1, 'Chingnon', NULL, '100', '10', 'adminAsset/service_image/1276562186.jpg', 'with appointment', 0, '2025-04-22 03:42:35', '2025-04-22 03:42:35'),
(6, 1, 1, 'Cornrows', NULL, '100', '10', 'adminAsset/service_image/707435519.jpg', 'without appointment', 0, '2025-04-22 03:43:04', '2025-04-22 03:43:04'),
(7, 1, 1, 'Lace wig', NULL, '100', '10', 'adminAsset/service_image/305047812.jpg', 'without appointment', 0, '2025-04-22 03:43:37', '2025-04-22 03:43:37'),
(8, 1, 1, 'waves', NULL, '100', '10', 'adminAsset/service_image/452724793.jpg', 'avec rendez-vous', 0, '2025-04-22 03:55:11', '2025-04-22 03:55:11'),
(9, 1, 1, 'wash and go', NULL, '100', '10', 'adminAsset/service_image/528156553.jpg', 'without appointment', 0, '2025-04-22 03:55:47', '2025-04-22 03:55:47'),
(10, 1, 1, 'Vanilies', NULL, '100', '10', 'adminAsset/service_image/1736412704.png', 'without appointment', 0, '2025-04-22 03:56:16', '2025-04-22 03:56:16'),
(11, 1, 1, 'Twists', NULL, '100', '10', 'adminAsset/service_image/1807621836.jpg', 'without appointment', 0, '2025-04-22 03:57:43', '2025-04-22 03:57:43'),
(12, 1, 1, 'Hair', NULL, '100', '20', 'adminAsset/service_image/701737663.png', 'with appointment', 0, '2025-04-22 04:24:59', '2025-04-22 04:24:59');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `schedule` json NOT NULL,
  `total_seats` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_wishlists`
--

CREATE TABLE `service_wishlists` (
  `id` bigint UNSIGNED NOT NULL,
  `service_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_wishlists`
--

INSERT INTO `service_wishlists` (`id`, `service_id`, `user_id`, `created_at`, `updated_at`) VALUES
(4, 11, 5, '2025-04-22 04:13:05', '2025-04-22 04:13:05'),
(7, 6, 5, '2025-04-22 04:17:25', '2025-04-22 04:17:25'),
(12, 5, 5, '2025-04-26 22:14:00', '2025-04-26 22:14:00');

-- --------------------------------------------------------

--
-- Table structure for table `shop_categories`
--

CREATE TABLE `shop_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `category_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shop_categories`
--

INSERT INTO `shop_categories` (`id`, `category_name`, `category_image`, `created_at`, `updated_at`) VALUES
(7, 'Buzz Cut', 'adminAsset/category_image/271990934.png', '2025-04-28 04:48:36', '2025-04-28 05:34:52'),
(8, 'bati cut', 'adminAsset/category_image/1995618195.png', '2025-04-28 05:33:39', '2025-04-28 05:33:39'),
(9, 'Crew Cut', 'adminAsset/category_image/1400921856.png', '2025-04-28 05:34:34', '2025-04-28 05:34:34'),
(10, 'Taper Cut', 'adminAsset/category_image/1570846773.png', '2025-04-28 05:35:59', '2025-04-28 05:35:59'),
(11, 'crew', 'adminAsset/category_image/1726440735.png', '2025-05-01 02:42:27', '2025-05-01 02:42:27');

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` bigint UNSIGNED NOT NULL,
  `slider_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slider_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slider_description` varchar(2000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `slider_name`, `slider_image`, `slider_description`, `created_at`, `updated_at`) VALUES
(2, 'demo slider 2', 'adminAsset/slider_image/1833847431.jpg', 'Voluptatibus rerum dolores porro est consequatur at saepe enim excepturi.', '2025-03-22 04:45:55', '2025-03-22 04:45:55'),
(3, 'demo slider 3', 'adminAsset/slider_image/625413941.jpg', 'Non ad exercitationem et quia sed.', '2025-03-22 04:46:01', '2025-03-22 04:46:01'),
(4, 'demo slider 4', 'adminAsset/slider_image/1192647445.jpg', 'Labore delectus ipsa velit accusamus nesciunt nam eveniet qui aperiam.', '2025-03-22 04:46:10', '2025-03-22 04:46:10'),
(5, 'demo slider 5', 'adminAsset/slider_image/975712607.jpg', 'Quaerat magnam itaque id asperiores quod.', '2025-03-22 04:46:17', '2025-03-22 04:46:17'),
(6, 'Cutter', 'adminAsset/slider_image/69583475.png', '<p>Good cutter</p>', '2025-05-01 02:20:08', '2025-05-01 02:20:08');

-- --------------------------------------------------------

--
-- Table structure for table `terms_conditions`
--

CREATE TABLE `terms_conditions` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `terms_conditions`
--

INSERT INTO `terms_conditions` (`id`, `title`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Terms and Condition1', 'Welcome to [Your Salon App Name]. By downloading, accessing, or using our app, you agree to comply with and be bound by the following terms and conditions. Please read these terms carefully before using our app.', '2025-04-28 04:36:43', '2025-04-28 04:36:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `otp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apple_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_account_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `active_status` tinyint(1) NOT NULL DEFAULT '0',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'avatar.png',
  `dark_mode` tinyint(1) NOT NULL DEFAULT '0',
  `messenger_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `last_name`, `email`, `image`, `address`, `phone`, `date_of_birth`, `role_type`, `gender`, `user_status`, `otp`, `google_id`, `apple_id`, `stripe_account_id`, `latitude`, `longitude`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `active_status`, `avatar`, `dark_mode`, `messenger_color`) VALUES
(1, 'Admin', 'User', 'admin@gmail.com', NULL, NULL, NULL, NULL, 'ADMIN', NULL, 'active', '0', NULL, NULL, NULL, NULL, NULL, '2025-03-19 04:04:41', '$2y$12$yalILrQhtdga.DV66gZK3.DTqBEMsakRSnRlvEuwqfo4A487TA0AK', 'ogv6ferdBv', '2025-03-19 04:04:41', '2025-03-19 04:04:41', 0, 'avatar.png', 0, NULL),
(2, 'Professional', 'User', 'professional@gmail.com', NULL, NULL, NULL, NULL, 'PROFESSIONAL', NULL, 'active', '0', NULL, NULL, NULL, NULL, NULL, '2025-03-19 04:04:41', '$2y$10$cGK8PLDP0gn7WbISV4odVO3XqRtjXp4Vzj/G20EN9/35WS3kM/cXe', 'IXUimjSiLl', '2025-03-19 04:04:41', '2025-03-19 04:04:41', 0, 'avatar.png', 0, NULL),
(3, 'Regular', 'User', 'user@gmail.com', NULL, NULL, NULL, NULL, 'USER', NULL, 'active', '0', NULL, NULL, NULL, NULL, NULL, '2025-03-19 04:04:41', '$2y$10$Lq6n/P0cZDNm8ZR8FeFl4uxN2oNSZLBVQfZfvLNpLCSK9U0LXw4Oq', 'ROOa3IyMyO', '2025-03-19 04:04:41', '2025-05-13 02:43:24', 0, 'avatar.png', 0, NULL),
(4, 'Super Admin', 'Super Admin', 'superadmin@gmail.com', NULL, NULL, NULL, NULL, 'SUPER ADMIN', NULL, 'active', '0', NULL, NULL, NULL, NULL, NULL, '2025-03-19 04:04:41', '$2y$10$xJj07yT8KBs.oZsH4ZOp2.f.dnvfX51Qc.InZ2NjYMH0Nr.sUo2jO', 'gxWw4CyAIC', '2025-03-19 04:04:41', '2025-03-19 04:04:41', 0, 'avatar.png', 0, NULL),
(5, 'Soheb', 'Hasan', 'jubayer6596@gmail.com', 'adminAsset/image/97567314.jpg', 'Dubai - United Arab Emirates', '01774403378', 'May 1, 2025', 'USER', 'male', 'active', '0', NULL, NULL, NULL, NULL, NULL, '2025-03-19 04:18:10', '$2y$10$.ZmuvG.MEnXiait8JImuauiKdHG3hAVgOME9h2pb8D2QLXjXRvMsO', NULL, '2025-03-19 04:16:54', '2025-05-01 02:30:25', 0, 'avatar.png', 0, NULL),
(6, 'Pro', 'Cutter', 'rekobe1625@doishy.com', 'adminAsset/image/1054283785.png', 'USA', '01774403322', NULL, 'PROFESSIONAL', NULL, 'active', '0', NULL, NULL, 'acct_1RHMqDQRte96kLKZ', 46.227638, 2.213749, '2025-03-19 04:22:33', '$2y$10$uPydPigaktA13uTU75FHnOitrRxJF1reR3p/NZU4ULW9r9MM/UVQ.', NULL, '2025-03-19 04:21:14', '2025-05-16 03:06:06', 0, 'avatar.png', 0, NULL),
(7, 'salon', 'another', 'ciyanef275@birige.com', NULL, 'Dhaka, Bangladesh', '01923647795', NULL, 'PROFESSIONAL', NULL, 'active', '0', NULL, NULL, NULL, 23.804092999999998, 90.4152376, '2025-03-22 04:47:10', '$2y$10$6MLNbaP2vj9iS0YoNbdSaOy2r1FuOvWOGAEfCUfHU9n7ifK/VFlBm', NULL, '2025-03-22 04:46:35', '2025-04-28 02:48:13', 0, 'avatar.png', 0, NULL),
(11, 'Cecil Jacobi', NULL, 'social@gmail.com', 'adminAsset/image/1745230360.png', NULL, NULL, NULL, 'USER', NULL, 'active', NULL, '3424234', '34234234', NULL, NULL, NULL, '2025-04-21 04:12:40', '$2y$10$rvOscmIGt9rJnBdnHFPuZeue5BdNlznGAC1hs7IQS4ANzzjkKWTJO', NULL, '2025-04-21 04:12:40', '2025-05-13 02:29:17', 0, 'avatar.png', 0, NULL),
(12, 'Nadim Hasan', NULL, 'nadimhasannh48@gmail.com', NULL, NULL, NULL, NULL, 'USER', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-21 22:38:14', '$2y$10$PjKEHhnc1n5jDat476wkeOaLQiJ4khxdQ7lnNZ52HIOtLIcfcT2R.', NULL, '2025-04-21 22:38:14', '2025-04-21 22:38:14', 0, 'avatar.png', 0, NULL),
(13, 'Neymar', 'Hasan', 'bocelo7319@f5url.com', NULL, 'India', '01923649797', NULL, 'PROFESSIONAL', NULL, 'active', '0', NULL, NULL, NULL, 20.593684, 78.96288, '2025-04-22 03:36:00', '$2y$10$L7s/o4hHDcHmnt7ZDIKFkuKFFdHvTEuHG4uVCLLpBTeCmUyDdzJZO', NULL, '2025-04-22 03:35:17', '2025-04-22 03:36:00', 0, 'avatar.png', 0, NULL),
(14, 'Bdcalling', NULL, 'bdcalling554@gmail.com', NULL, NULL, NULL, NULL, 'USER', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-28 03:52:14', '$2y$10$ET65hJ8LS37ZiYGe0u4zL.yNaN5d7K8.tQ0J1.0.ncHxxWxp2GpMe', NULL, '2025-04-28 03:52:14', '2025-04-28 03:52:14', 0, 'avatar.png', 0, NULL),
(15, 'Sazzat Hossen', 'Name', 'asikuli@yopmail.com', NULL, 'Banasree , B block , dhaka 120', '12345678944', '2024-06-11', 'PROFESSIONAL', 'Male', 'active', 'tXN8aB', NULL, NULL, NULL, NULL, NULL, '2025-04-30 03:03:38', '$2y$10$svjEaKF1tji.0/UwQdXn6O.1geV42gpKbmVItMNXtC7mxd44Z0teW', NULL, '2025-04-29 04:12:10', '2025-04-30 03:03:38', 0, 'avatar.png', 0, NULL),
(16, 'Sazzat Hossen', 'Name', 'asiksuli@yopmail.com', NULL, 'Banasree , B block , dhaka 120', '12345678944', '2024-06-11', 'PROFESSIONAL', 'Male', 'active', 'KsguCh', NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$5bge0P.pgALCkeQWoXY24.5X/SSGWx3vSkSOjFZR2ZLSR44V1.YYa', NULL, '2025-04-30 03:07:02', '2025-04-30 03:07:02', 0, 'avatar.png', 0, NULL),
(17, 'Sazzat Hossen', 'Name', 'asisssksuli@yopmail.com', NULL, 'Banasree , B block , dhaka 120', '12345678944', '2024-06-11', 'PROFESSIONAL', 'Male', 'active', 'o3wVlh', NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$Ln/rQxYWqE8JC7XDUXOiaeePiAO1dmzZIyuNMuRCnGo1sF1Q7Yj.O', NULL, '2025-04-30 03:08:33', '2025-04-30 03:08:33', 0, 'avatar.png', 0, NULL),
(18, 'Asik', 'banaya', 'jejebig659@ingitel.com', NULL, NULL, '01923649788', NULL, 'USER', NULL, 'active', '0', NULL, NULL, NULL, NULL, NULL, '2025-04-30 03:17:11', '$2y$10$atswSp6NVaQGckhtuTC5lekl3Hrjwb3ELLoIQZD9W0Mpurlx7EvPS', NULL, '2025-04-30 03:16:31', '2025-04-30 03:17:11', 0, 'avatar.png', 0, NULL),
(19, 'Asik', 'banaya', 'nacobey687@miracle3.com', NULL, NULL, '019264697989', NULL, 'USER', NULL, 'active', '0', NULL, NULL, NULL, NULL, NULL, '2025-04-30 03:20:14', '$2y$10$12CPSBiDskdnq1MYNwsBI.8opFYPWAXDZQI37QGLnxOCMqYnAT2lS', NULL, '2025-04-30 03:19:26', '2025-04-30 03:20:14', 0, 'avatar.png', 0, NULL),
(20, 'ghhh', 'jjjjj', 'hhjj@gmail.com', NULL, NULL, '016565564554', NULL, 'USER', NULL, 'active', 'FHcRtp', NULL, NULL, NULL, NULL, NULL, '2025-04-30 03:30:12', '$2y$10$BYtavA88L//rt4tExWxNke5ZFcbcWB4ZnFKeDYPdi/wsA3moa98ly', NULL, '2025-04-30 03:23:23', '2025-04-30 03:30:12', 0, 'avatar.png', 0, NULL),
(23, 'Sazzat Hossen', 'Name', 'asiksduli@yopmail.com', NULL, 'Banasree , B block , dhaka 120', '12345678944', '2024-06-11', 'PROFESSIONAL', 'Male', 'active', 'WKYzwb', NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$L/2vnqBMge1kba7E3wMsRuKtZddEt9D9NFDwRfOYb7v/k1NbPt6DS', NULL, '2025-05-21 23:24:16', '2025-05-21 23:24:16', 0, 'avatar.png', 0, NULL),
(24, 'Sazzat Hossen', 'Name', 'ssdsd@yopmail.com', NULL, 'Banasree , B block , dhaka 120', '12345678944', '2024-06-11', 'PROFESSIONAL', 'Male', 'active', 'pWhiSG', NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$UU999iA4GmksRCjgNIWSxOuoXx5KxsABQ7pOeYwrN1ldqdM2riUL6', NULL, '2025-05-21 23:25:55', '2025-05-21 23:25:55', 0, 'avatar.png', 0, NULL),
(25, 'Sazzat Hossen', 'Name', 'ssddsd@yopmail.com', NULL, 'Banasree , B block , dhaka 120', '12345678944', '2024-06-11', 'PROFESSIONAL', 'Male', 'active', 'AChsFe', NULL, NULL, NULL, NULL, NULL, '2025-05-22 02:37:54', '$2y$10$oBOhvvqVUZc0QtYsCwrRXujzh2BXoXH0S.EEdTnppbGp91d9UZDFW', NULL, '2025-05-21 23:27:13', '2025-05-22 02:37:54', 0, 'avatar.png', 0, NULL),
(26, 'Sazzat Hossen', 'Name', 'vawerot755@neuraxo.com', NULL, 'Banasree , B block , dhaka 120', '12345678944', '2024-06-11', 'PROFESSIONAL', 'Male', 'active', 'cUbv3s', NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$RKxljeyeUAt0r6M9DYXczuSty1k6TTiAy8tx2SPFsVamSW1avh93a', NULL, '2025-05-22 02:38:40', '2025-05-22 02:38:40', 0, 'avatar.png', 0, NULL),
(27, 'Sazzat Hossen', 'Name', 'rosinor553@ofular.com', NULL, 'Banasree , B block , dhaka 120', '12345678944', '2024-06-11', 'PROFESSIONAL', 'Male', 'active', '0', NULL, NULL, NULL, NULL, NULL, '2025-05-22 02:40:52', '$2y$10$74yHuVAdDufJ1AkY3U/MgekkFirPEKtStHlqeItO7pk3s5C5B9/L2', NULL, '2025-05-22 02:40:29', '2025-05-22 02:40:52', 0, 'avatar.png', 0, NULL),
(28, 'hasan', 'miaa', 'utshow556@gmail.com', NULL, 'Dhaka, Bangladesh', '01923647795', NULL, 'USER', NULL, 'active', '0', NULL, NULL, NULL, NULL, NULL, '2025-06-23 06:12:42', '$2y$10$2WKVADM1XNNNKkW0LqOGB.oq9vFA.XQqEyRfL5UiSy1mAUUJ/Pp0m', NULL, '2025-06-23 06:11:24', '2025-06-23 06:12:42', 0, 'avatar.png', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_us`
--
ALTER TABLE `about_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ch_favorites`
--
ALTER TABLE `ch_favorites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ch_messages`
--
ALTER TABLE `ch_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feedback_user_id_foreign` (`user_id`),
  ADD KEY `feedback_salon_id_foreign` (`salon_id`),
  ADD KEY `feedback_payment_detail_id_foreign` (`payment_detail_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_sender_id_foreign` (`sender_id`),
  ADD KEY `messages_receiver_id_foreign` (`receiver_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_invoice_number_unique` (`invoice_number`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_salon_id_foreign` (`salon_id`),
  ADD KEY `orders_service_id_foreign` (`service_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payment_details`
--
ALTER TABLE `payment_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_details_invoice_number_unique` (`invoice_number`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `platform_fees`
--
ALTER TABLE `platform_fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `privacy_policies`
--
ALTER TABLE `privacy_policies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_shop_category_id_foreign` (`shop_category_id`);

--
-- Indexes for table `product_wishlists`
--
ALTER TABLE `product_wishlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_wishlists_product_id_foreign` (`product_id`),
  ADD KEY `product_wishlists_user_id_foreign` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`),
  ADD KEY `reviews_salon_id_foreign` (`salon_id`),
  ADD KEY `reviews_service_id_foreign` (`service_id`),
  ADD KEY `reviews_salon_invoice_id_foreign` (`salon_invoice_id`);

--
-- Indexes for table `salons`
--
ALTER TABLE `salons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salons_user_id_foreign` (`user_id`);

--
-- Indexes for table `salon_invoices`
--
ALTER TABLE `salon_invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salon_invoices_user_id_foreign` (`user_id`),
  ADD KEY `salon_invoices_salon_id_foreign` (`salon_id`),
  ADD KEY `salon_invoices_payment_detail_id_foreign` (`payment_detail_id`),
  ADD KEY `salon_invoices_service_id_foreign` (`service_id`);

--
-- Indexes for table `salon_schedule_times`
--
ALTER TABLE `salon_schedule_times`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salon_schedule_times_salon_id_foreign` (`salon_id`);

--
-- Indexes for table `salon_services`
--
ALTER TABLE `salon_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salon_services_salon_id_foreign` (`salon_id`),
  ADD KEY `salon_services_category_id_foreign` (`category_id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedules_user_id_foreign` (`user_id`);

--
-- Indexes for table `service_wishlists`
--
ALTER TABLE `service_wishlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_wishlists_service_id_foreign` (`service_id`),
  ADD KEY `service_wishlists_user_id_foreign` (`user_id`);

--
-- Indexes for table `shop_categories`
--
ALTER TABLE `shop_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terms_conditions`
--
ALTER TABLE `terms_conditions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_us`
--
ALTER TABLE `about_us`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `payment_details`
--
ALTER TABLE `payment_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `platform_fees`
--
ALTER TABLE `platform_fees`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `privacy_policies`
--
ALTER TABLE `privacy_policies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_wishlists`
--
ALTER TABLE `product_wishlists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `salons`
--
ALTER TABLE `salons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `salon_invoices`
--
ALTER TABLE `salon_invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `salon_schedule_times`
--
ALTER TABLE `salon_schedule_times`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `salon_services`
--
ALTER TABLE `salon_services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_wishlists`
--
ALTER TABLE `service_wishlists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `shop_categories`
--
ALTER TABLE `shop_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `terms_conditions`
--
ALTER TABLE `terms_conditions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_payment_detail_id_foreign` FOREIGN KEY (`payment_detail_id`) REFERENCES `payment_details` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_salon_id_foreign` FOREIGN KEY (`salon_id`) REFERENCES `salons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_salon_id_foreign` FOREIGN KEY (`salon_id`) REFERENCES `salons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `salon_services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_shop_category_id_foreign` FOREIGN KEY (`shop_category_id`) REFERENCES `shop_categories` (`id`);

--
-- Constraints for table `product_wishlists`
--
ALTER TABLE `product_wishlists`
  ADD CONSTRAINT `product_wishlists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `product_wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_salon_id_foreign` FOREIGN KEY (`salon_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_salon_invoice_id_foreign` FOREIGN KEY (`salon_invoice_id`) REFERENCES `salon_invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `salon_services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `salons`
--
ALTER TABLE `salons`
  ADD CONSTRAINT `salons_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `salon_invoices`
--
ALTER TABLE `salon_invoices`
  ADD CONSTRAINT `salon_invoices_payment_detail_id_foreign` FOREIGN KEY (`payment_detail_id`) REFERENCES `payment_details` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `salon_invoices_salon_id_foreign` FOREIGN KEY (`salon_id`) REFERENCES `salons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `salon_invoices_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `salon_services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `salon_invoices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `salon_schedule_times`
--
ALTER TABLE `salon_schedule_times`
  ADD CONSTRAINT `salon_schedule_times_salon_id_foreign` FOREIGN KEY (`salon_id`) REFERENCES `salons` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `salon_services`
--
ALTER TABLE `salon_services`
  ADD CONSTRAINT `salon_services_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `salon_services_salon_id_foreign` FOREIGN KEY (`salon_id`) REFERENCES `salons` (`id`);

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `service_wishlists`
--
ALTER TABLE `service_wishlists`
  ADD CONSTRAINT `service_wishlists_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `salon_services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

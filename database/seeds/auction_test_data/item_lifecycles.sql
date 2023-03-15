-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 08, 2020 at 05:36 AM
-- Server version: 5.7.24
-- PHP Version: 7.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hotlotz`
--

-- --------------------------------------------------------

--
-- Table structure for table `item_lifecycles`
--

DROP TABLE IF EXISTS `item_lifecycles`;
CREATE TABLE `item_lifecycles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(15,4) NOT NULL,
  `period` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `second_period` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_indefinite_period` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `reference_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entered_date` datetime DEFAULT NULL,
  `sold_date` datetime DEFAULT NULL,
  `sold_price` decimal(15,4) DEFAULT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `withdrawn_date` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `updated_by` int(11) NOT NULL DEFAULT '1',
  `deleted_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_lifecycles`
--

INSERT INTO `item_lifecycles` (`id`, `item_id`, `type`, `price`, `period`, `second_period`, `is_indefinite_period`, `reference_id`, `status`, `action`, `entered_date`, `sold_date`, `sold_price`, `buyer_id`, `withdrawn_date`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(6, 'a7e166d3-d9bd-4746-8e0c-a61692ee4bea', 'auction', '270.0000', NULL, NULL, 'N', '5fdfa6fb-5264-496c-8e1f-b2f0a89f23a2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-07-14 05:24:26', '2020-09-03 14:47:27', NULL),
(7, 'a7e166d3-d9bd-4746-8e0c-a61692ee4bea', 'marketplace', '500.0000', '30', NULL, 'N', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-07-14 05:24:26', '2020-09-03 14:47:27', NULL),
(34, '5bba6903-0dbf-4dc5-977a-3794c2181f03', 'auction', '180.0000', NULL, NULL, NULL, '00c99d52-2a4e-435e-ab18-8e6d1fec8c58', '', 'Processing', '2020-09-07 08:12:01', NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-08-12 12:51:23', '2020-09-07 01:42:01', NULL),
(35, '5bba6903-0dbf-4dc5-977a-3794c2181f03', 'marketplace', '300.0000', '30', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-08-12 12:51:23', '2020-09-07 00:44:32', NULL),
(36, '5bba6903-0dbf-4dc5-977a-3794c2181f03', 'clearance', '100.0000', '30', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-08-12 12:51:23', '2020-09-07 00:44:32', NULL),
(37, '5bba6903-0dbf-4dc5-977a-3794c2181f03', 'storage', '5.0000', '7', '14', 'N', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-08-12 12:51:23', '2020-09-07 00:44:32', NULL),
(43, 'e2485577-1bb8-490c-9f8a-bd17407041ba', 'auction', '180.0000', NULL, NULL, NULL, '00c99d52-2a4e-435e-ab18-8e6d1fec8c58', NULL, 'Processing', '2020-09-07 08:12:02', NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-09-03 10:04:20', '2020-09-07 01:42:02', NULL),
(44, 'e2485577-1bb8-490c-9f8a-bd17407041ba', 'marketplace', '300.0000', '30', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-09-03 10:04:20', '2020-09-07 00:39:39', NULL),
(45, 'e2485577-1bb8-490c-9f8a-bd17407041ba', 'clearance', '100.0000', '30', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-09-03 10:04:20', '2020-09-07 00:39:39', NULL),
(46, 'e2485577-1bb8-490c-9f8a-bd17407041ba', 'storage', '5.0000', '7', '14', 'N', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-09-03 10:04:20', '2020-09-07 00:39:39', NULL),
(59, '8fdafe44-862d-4a95-871e-8b3c68e47fa1', 'auction', '180.0000', NULL, NULL, NULL, 'cf7f220f-f225-490d-944b-e66d190e4f84', NULL, 'Processing', '2020-09-07 08:12:02', NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-09-06 10:19:10', '2020-09-07 01:42:02', NULL),
(60, '8fdafe44-862d-4a95-871e-8b3c68e47fa1', 'marketplace', '250.0000', '30', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-09-06 10:19:10', '2020-09-07 00:35:12', NULL),
(61, '8fdafe44-862d-4a95-871e-8b3c68e47fa1', 'clearance', '100.0000', '30', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-09-06 10:19:10', '2020-09-07 00:35:12', NULL),
(62, '8fdafe44-862d-4a95-871e-8b3c68e47fa1', 'storage', '5.0000', '7', '14', 'N', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-09-06 10:19:10', '2020-09-07 00:35:12', NULL),
(63, '0293f06e-e45a-4623-99c5-3504b612585a', 'auction', '180.0000', NULL, NULL, NULL, 'cf7f220f-f225-490d-944b-e66d190e4f84', NULL, 'Processing', '2020-09-07 08:11:58', NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-09-06 10:26:01', '2020-09-07 01:41:58', NULL),
(64, '0293f06e-e45a-4623-99c5-3504b612585a', 'storage', '5.0000', '7', '14', 'N', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-09-06 10:26:01', '2020-09-07 00:32:56', NULL),
(65, 'b36d3427-721c-4ce9-9aa0-703fb2edb95b', 'auction', '180.0000', NULL, NULL, NULL, '5fdfa6fb-5264-496c-8e1f-b2f0a89f23a2', 'Sold', 'Finished', '2020-09-07 08:12:02', '2020-09-07 08:27:11', '60.0000', 14, NULL, 1, 1, NULL, '2020-09-07 01:08:08', '2020-09-07 01:57:12', NULL),
(66, 'b36d3427-721c-4ce9-9aa0-703fb2edb95b', 'marketplace', '250.0000', '30', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-09-07 01:08:08', '2020-09-07 01:08:08', NULL),
(67, 'b36d3427-721c-4ce9-9aa0-703fb2edb95b', 'clearance', '100.0000', '30', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-09-07 01:08:08', '2020-09-07 01:08:08', NULL),
(68, 'b36d3427-721c-4ce9-9aa0-703fb2edb95b', 'storage', '5.0000', '7', '14', 'N', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-09-07 01:08:08', '2020-09-07 01:08:08', NULL),
(69, '827a9d5b-aa24-490e-8bcd-2e4365fa5805', 'auction', '90.0000', NULL, NULL, NULL, '5fdfa6fb-5264-496c-8e1f-b2f0a89f23a2', 'Sold', 'Finished', '2020-09-07 08:12:01', '2020-09-07 08:27:51', '60.0000', 15, NULL, 1, 1, NULL, '2020-09-07 01:12:04', '2020-09-07 01:57:52', NULL),
(70, '827a9d5b-aa24-490e-8bcd-2e4365fa5805', 'marketplace', '200.0000', '30', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-09-07 01:12:04', '2020-09-07 01:12:04', NULL),
(71, '827a9d5b-aa24-490e-8bcd-2e4365fa5805', 'storage', '5.0000', '7', '14', 'N', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-09-07 01:12:04', '2020-09-07 01:12:04', NULL),
(72, '88c5a0da-a953-4fed-ab53-370244f98141', 'auction', '180.0000', NULL, NULL, NULL, '5fdfa6fb-5264-496c-8e1f-b2f0a89f23a2', 'Sold', 'Finished', '2020-09-07 08:12:02', '2020-09-07 08:28:38', '80.0000', 16, NULL, 1, 1, NULL, '2020-09-07 01:14:22', '2020-09-07 01:58:39', NULL),
(73, '88c5a0da-a953-4fed-ab53-370244f98141', 'marketplace', '350.0000', '30', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-09-07 01:14:22', '2020-09-07 01:14:22', NULL),
(74, '88c5a0da-a953-4fed-ab53-370244f98141', 'storage', '5.0000', '7', '14', 'N', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-09-07 01:14:22', '2020-09-07 01:14:22', NULL),
(75, 'a5de5451-59ef-4904-9d84-877cdf890d02', 'auction', '180.0000', NULL, NULL, 'N', '5fdfa6fb-5264-496c-8e1f-b2f0a89f23a2', 'Sold', 'Finished', '2020-09-07 08:12:02', '2020-09-07 08:28:57', '1000000.0000', 16, NULL, 1, 1, NULL, '2020-09-07 01:18:12', '2020-09-07 01:58:57', NULL),
(76, 'a5de5451-59ef-4904-9d84-877cdf890d02', 'auction', '140.0000', NULL, NULL, 'N', 'cf7f220f-f225-490d-944b-e66d190e4f84', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-09-07 01:18:12', '2020-09-07 01:37:10', NULL),
(77, 'a5de5451-59ef-4904-9d84-877cdf890d02', 'storage', '5.0000', '7', '14', 'N', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-09-07 01:18:12', '2020-09-07 01:37:10', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `item_lifecycles`
--
ALTER TABLE `item_lifecycles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `item_lifecycles`
--
ALTER TABLE `item_lifecycles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

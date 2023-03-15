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
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL DEFAULT '0',
  `package_id` int(11) NOT NULL DEFAULT '0',
  `is_new` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `fee_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'sales_commission, fixed_cost_sales_fee, hotlotz_owned_stock',
  `lifecycle_id` int(11) NOT NULL DEFAULT '0',
  `valuer_id` int(11) NOT NULL DEFAULT '0',
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lifecycle_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `long_description` longtext COLLATE utf8mb4_unicode_ci,
  `item_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permission_to_sell` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `receipt_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_time_utc` datetime DEFAULT NULL,
  `is_pro_photo_need` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `quantity` int(11) NOT NULL DEFAULT '1',
  `sub_category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_data` longtext COLLATE utf8mb4_unicode_ci,
  `cataloguing_needed` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `low_estimate` decimal(15,4) DEFAULT NULL,
  `high_estimate` decimal(15,4) DEFAULT NULL,
  `reserve` decimal(15,4) DEFAULT NULL,
  `is_reserve` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Y',
  `opening_price` decimal(15,4) DEFAULT NULL,
  `buy_it_now_price` decimal(15,4) DEFAULT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SGD',
  `vat_tax_rate` decimal(15,4) DEFAULT NULL,
  `buyers_premium_vat_rate` decimal(15,4) DEFAULT NULL,
  `buyers_premium_percent` decimal(15,4) DEFAULT NULL,
  `buyers_premium_ceiling` decimal(15,4) DEFAULT NULL,
  `internet_surcharge_vat_rate` decimal(15,4) DEFAULT NULL,
  `internet_surcharge_percent` decimal(15,4) DEFAULT NULL,
  `internet_surcharge_ceiling` decimal(15,4) DEFAULT NULL,
  `increment` decimal(15,4) DEFAULT NULL,
  `sale_section` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_bulk` tinyint(1) NOT NULL DEFAULT '0',
  `artist_resale_rights` tinyint(1) NOT NULL DEFAULT '0',
  `sequence_number` int(11) DEFAULT NULL,
  `is_potentially_offensive` tinyint(1) DEFAULT '0',
  `address1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address4` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `town_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `county_state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `updated_by` int(11) NOT NULL DEFAULT '1',
  `deleted_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `currently_in_hotlotz_warehouse` tinyint(1) DEFAULT '0',
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_hotlotz_own_stock` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `supplier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_cost` decimal(15,4) DEFAULT NULL,
  `supplier_gst` int(11) DEFAULT NULL,
  `condition` longtext COLLATE utf8mb4_unicode_ci,
  `specific_condition_value` text COLLATE utf8mb4_unicode_ci,
  `provenance` longtext COLLATE utf8mb4_unicode_ci,
  `dimensions` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_dimension` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `weight` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_weight` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `additional_notes` longtext COLLATE utf8mb4_unicode_ci,
  `internal_notes` longtext COLLATE utf8mb4_unicode_ci,
  `registration_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `seller_agreement_signed_date` datetime DEFAULT NULL,
  `saleroom_receipt_date` datetime DEFAULT NULL,
  `entered_auction1_date` datetime DEFAULT NULL,
  `entered_auction2_date` datetime DEFAULT NULL,
  `entered_marketplace_date` datetime DEFAULT NULL,
  `entered_clearance_date` datetime DEFAULT NULL,
  `sold_date` datetime DEFAULT NULL,
  `sold_price` decimal(15,4) DEFAULT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `settled_date` datetime DEFAULT NULL,
  `paid_date` datetime DEFAULT NULL,
  `dispatched_or_collected_date` datetime DEFAULT NULL,
  `dispatched_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dispatched_remark` text COLLATE utf8mb4_unicode_ci,
  `withdrawn_date` datetime DEFAULT NULL,
  `storage_date` datetime DEFAULT NULL,
  `declined_date` datetime DEFAULT NULL,
  `pending_flag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `declined_flag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `in_auction_flag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `in_marketplace_flag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sold_flag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settled_flag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_flag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `withdrawn_flag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `storage_flag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_be_collected_flag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dispatched_flag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_tree_planted` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `cataloguing_approver_id` int(11) DEFAULT NULL,
  `is_cataloguing_approved` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `cataloguing_approval_date` datetime DEFAULT NULL,
  `valuation_approver_id` int(11) DEFAULT NULL,
  `is_valuation_approved` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `valuation_approval_date` datetime DEFAULT NULL,
  `is_fee_structure_needed` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Y',
  `invoice_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `consignment_flag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fee_structure_approver_id` int(11) DEFAULT NULL,
  `is_fee_structure_approved` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `fee_structure_approval_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `customer_id`, `category_id`, `country_id`, `package_id`, `is_new`, `fee_type`, `lifecycle_id`, `valuer_id`, `status`, `lifecycle_status`, `title`, `long_description`, `item_number`, `permission_to_sell`, `receipt_no`, `end_time_utc`, `is_pro_photo_need`, `quantity`, `sub_category`, `category_data`, `cataloguing_needed`, `low_estimate`, `high_estimate`, `reserve`, `is_reserve`, `opening_price`, `buy_it_now_price`, `currency`, `vat_tax_rate`, `buyers_premium_vat_rate`, `buyers_premium_percent`, `buyers_premium_ceiling`, `internet_surcharge_vat_rate`, `internet_surcharge_percent`, `internet_surcharge_ceiling`, `increment`, `sale_section`, `is_bulk`, `artist_resale_rights`, `sequence_number`, `is_potentially_offensive`, `address1`, `address2`, `address3`, `address4`, `town_city`, `postcode`, `country_code`, `county_state`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`, `currently_in_hotlotz_warehouse`, `location`, `is_hotlotz_own_stock`, `supplier`, `purchase_cost`, `supplier_gst`, `condition`, `specific_condition_value`, `provenance`, `dimensions`, `is_dimension`, `weight`, `is_weight`, `additional_notes`, `internal_notes`, `registration_date`, `seller_agreement_signed_date`, `saleroom_receipt_date`, `entered_auction1_date`, `entered_auction2_date`, `entered_marketplace_date`, `entered_clearance_date`, `sold_date`, `sold_price`, `buyer_id`, `settled_date`, `paid_date`, `dispatched_or_collected_date`, `dispatched_person`, `dispatched_remark`, `withdrawn_date`, `storage_date`, `declined_date`, `pending_flag`, `declined_flag`, `in_auction_flag`, `in_marketplace_flag`, `sold_flag`, `settled_flag`, `paid_flag`, `withdrawn_flag`, `storage_flag`, `to_be_collected_flag`, `dispatched_flag`, `brand`, `is_tree_planted`, `cataloguing_approver_id`, `is_cataloguing_approved`, `cataloguing_approval_date`, `valuation_approver_id`, `is_valuation_approved`, `valuation_approval_date`, `is_fee_structure_needed`, `invoice_id`, `bill_id`, `consignment_flag`, `fee_structure_approver_id`, `is_fee_structure_approved`, `fee_structure_approval_date`) VALUES
('0293f06e-e45a-4623-99c5-3504b612585a', 'Item 6', 10, 1, 0, 0, 'N', 'fixed_cost_sales_fee', 8, 1, 'In Auction', 'Auction', 'Item 6', 'Storage add  into Lifecycle Tab', 'A00005/00001', 'Y', NULL, NULL, 'N', 1, 'Worldwide', '{\"Subject Area\\/country\":null,\"Country of manufacture\":\"France\",\"Date of manufacture\":null,\"Cartographer\":null,\"Publisher\":null,\"Framed\":null,\"Dimensions without frame\":null,\"Certification\":null}', 'Completed', '200.0000', '300.0000', '24.0000', 'Y', NULL, NULL, 'SGD', '7.0000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-08-05 10:22:19', '2020-09-07 11:06:47', NULL, 0, 'Saleroom', 'N', NULL, NULL, NULL, 'specific_condition', 'Minor signs of wear comensurate with age and use, test.', NULL, NULL, 'N', NULL, 'N', NULL, NULL, '2020-08-05 16:52:18', NULL, NULL, '2020-09-07 08:11:58', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 1, 'Y', '2020-08-26 12:57:29', 1, 'Y', '2020-08-26 12:57:23', 'N', NULL, NULL, 'complete', 1, 'Y', NULL),
('5bba6903-0dbf-4dc5-977a-3794c2181f03', 'mc test from local', 3, 1, 0, 0, 'N', 'hotlotz_owned_stock', 5, 1, 'In Auction', 'Auction', 'mc test from local', 'mc test from local', 'A00003/00001', 'Y', NULL, NULL, 'Y', 1, 'Regional', '{\"Subject Area\\/country\":null,\"Country of manufacture\":\"France\",\"Date of manufacture\":null,\"Cartographer\":null,\"Publisher\":null,\"Framed\":null,\"Dimensions without frame\":null,\"Certification\":null}', 'Completed', '200.0000', '400.0000', NULL, 'N', NULL, NULL, 'SGD', '7.0000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-08-11 07:32:54', '2020-09-07 01:42:01', NULL, 0, 'Saleroom', 'Y', 'Supplier A', '150.0000', NULL, 'specific_condition', 'Minor signs of wear comensurate with age and use, test', NULL, NULL, 'N', NULL, 'N', NULL, NULL, '2020-08-11 14:02:54', NULL, NULL, '2020-09-07 08:12:01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 1, 'Y', '2020-08-25 19:46:55', 1, 'Y', '2020-08-25 19:46:48', 'Y', NULL, NULL, NULL, 1, 'Y', '2020-09-08 00:00:00'),
('827a9d5b-aa24-490e-8bcd-2e4365fa5805', 'Item 2', 1, 6, 0, 0, 'N', 'sales_commission', 6, 1, 'Sold', 'Auction', 'Item 2', 'Item 2', 'A00001/00002', 'Y', NULL, NULL, 'N', 1, 'testSC', '{\"Type\":\"Fine Jewellery\",\"Period\":\"Vintage\",\"Material\":\"Platinum,testMaterial\",\"Stone\":null,\"Brand\":\"Bulgari\",\"Packaging\":\"With box and papers\",\"Certification\":null}', 'Completed', '100.0000', '300.0000', '50.0000', 'Y', NULL, NULL, 'SGD', '7.0000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-07-09 04:54:09', '2020-09-07 01:57:52', NULL, 0, 'Saleroom', 'Y', 'Supplier B', '20.0000', NULL, NULL, NULL, NULL, NULL, 'Y', NULL, 'Y', NULL, NULL, '2020-07-09 11:24:09', NULL, NULL, '2020-09-07 08:12:01', NULL, NULL, NULL, '2020-09-07 08:27:51', '60.0000', 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 1, 'Y', '2020-08-25 18:44:31', 1, 'Y', '2020-08-25 18:44:41', 'N', NULL, NULL, NULL, 1, 'Y', '2020-09-08 00:00:00'),
('88c5a0da-a953-4fed-ab53-370244f98141', 'Item 4', 4, 5, 0, 0, 'N', 'hotlotz_owned_stock', 6, 1, 'Sold', 'Auction', 'Item 4', NULL, 'A00004/00002', 'Y', NULL, NULL, 'N', 1, 'Table', '{\"Period\":null,\"Material\":null,\"Style\":\"Asian,European,testStyle\",\"Certification\":null}', 'Completed', '200.0000', '500.0000', '20.0000', 'Y', NULL, NULL, 'SGD', '7.0000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-07-14 05:28:24', '2020-09-07 01:58:39', NULL, 0, 'Saleroom', 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', NULL, 'Y', NULL, NULL, '2020-07-14 11:58:24', NULL, NULL, '2020-09-07 08:12:02', NULL, NULL, NULL, '2020-09-07 08:28:38', '80.0000', 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 1, 'Y', '2020-08-25 19:44:05', 1, 'Y', '2020-08-25 19:44:12', 'Y', NULL, NULL, NULL, 1, 'Y', '2020-09-08 00:00:00'),
('8fdafe44-862d-4a95-871e-8b3c68e47fa1', 'Item 5', 2, 1, 0, 0, 'N', NULL, 5, 1, 'In Auction', 'Auction', 'Item 5', 'description of Item 5', 'A00002/00001', 'Y', NULL, NULL, 'Y', 1, 'Worldwide', '{\"Subject Area\\/country\":\"Any\",\"Country of manufacture\":\"France\",\"Date of manufacture\":null,\"Cartographer\":\"Any\",\"Publisher\":\"Any\",\"Framed\":null,\"Dimensions without frame\":null,\"Certification\":null}', NULL, '200.0000', '300.0000', '50.0000', 'Y', NULL, NULL, 'SGD', '7.0000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-07-20 19:53:52', '2020-09-07 01:42:02', NULL, 0, 'Saleroom', 'N', NULL, NULL, NULL, 'no_condition', 'Minor signs of wear comensurate with age and use,', 'provenance of Item 5', NULL, 'N', NULL, 'N', 'additional notes of Item 5', 'internal notes of Item 5', '2020-07-21 02:23:51', NULL, NULL, '2020-09-07 08:12:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 1, 'Y', '2020-08-25 18:48:06', 1, 'Y', '2020-08-25 18:48:15', 'Y', NULL, NULL, NULL, 1, 'Y', '2020-09-08 00:00:00'),
('a5de5451-59ef-4904-9d84-877cdf890d02', 'Item 7', 2, 11, 0, 0, 'N', NULL, 4, 1, 'Sold', 'Auction', 'Item 7', 'Item 7 description', 'A00002/00002', 'Y', NULL, NULL, 'Y', 1, 'Wine', '{\"Number of Bottles\":null,\"Year\":null,\"Type\":null,\"Certification\":null,\"Come with a box\":null,\"Provenance\":null,\"Storage\":null,\"Producer\":null,\"Vintage\":null,\"Country of Origin\":null,\"Region\":null,\"ABV\":null}', NULL, '200.0000', '400.0000', '50.0000', 'Y', NULL, NULL, 'SGD', '7.0000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-08-06 06:53:07', '2020-09-07 01:58:57', NULL, 0, 'Saleroom', 'N', NULL, NULL, NULL, 'no_condition', 'Minor signs of wear comensurate with age and use,', 'provenance of Item 7', NULL, 'N', NULL, 'N', 'additional notes of Item 7', 'internal notes of Item 7', '2020-08-06 13:23:06', NULL, NULL, '2020-09-07 08:12:02', NULL, NULL, NULL, '2020-09-07 08:28:57', '1000000.0000', 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 1, 'Y', '2020-08-25 18:57:57', 1, 'Y', '2020-08-25 18:58:04', 'Y', NULL, NULL, NULL, 1, 'Y', '2020-09-08 00:00:00'),
('b36d3427-721c-4ce9-9aa0-703fb2edb95b', 'Item 1', 1, 1, 0, 0, 'N', NULL, 5, 1, 'Sold', 'Auction', 'Item 1', 'description of Item 1', 'A00001/00001', 'Y', NULL, NULL, 'Y', 1, 'Worldwide', '{\"Subject Area\\/country\":null,\"Country of manufacture\":\"France\",\"Date of manufacture\":null,\"Cartographer\":null,\"Publisher\":null,\"Framed\":null,\"Dimensions without frame\":null,\"Certification\":null}', NULL, '200.0000', '300.0000', '20.0000', 'Y', NULL, NULL, 'SGD', '7.0000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-07-09 04:52:30', '2020-09-07 01:57:12', NULL, 0, 'Saleroom', 'Y', 'Supplier A', '20.0000', NULL, 'no_condition', 'Minor signs of wear comensurate with age and use,', NULL, NULL, 'N', NULL, 'N', NULL, NULL, '2020-07-09 11:22:29', NULL, NULL, '2020-09-07 08:12:02', NULL, NULL, NULL, '2020-09-07 08:27:11', '60.0000', 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 1, 'Y', '2020-08-25 19:02:22', 1, 'Y', '2020-08-25 19:02:12', 'Y', NULL, NULL, NULL, 1, 'Y', '2020-09-08 00:00:00'),
('e2485577-1bb8-490c-9f8a-bd17407041ba', 'mc test', 3, 1, 0, 0, 'N', 'hotlotz_owned_stock', 5, 1, 'In Auction', 'Auction', 'mc test', 'mc test from local', 'A00003/00002', 'Y', NULL, NULL, 'Y', 1, 'Regional', '{\"Subject Area\\/country\":null,\"Country of manufacture\":\"France\",\"Date of manufacture\":null,\"Cartographer\":null,\"Publisher\":null,\"Framed\":null,\"Dimensions without frame\":null,\"Certification\":null}', NULL, '200.0000', '400.0000', '80.0000', 'Y', NULL, NULL, 'SGD', '7.0000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2020-08-12 13:23:23', '2020-09-07 01:42:02', NULL, 0, 'Saleroom', 'Y', 'Supplier A', '150.0000', NULL, 'specific_condition', 'Minor signs of wear comensurate with age and use, test', NULL, NULL, 'N', NULL, 'N', NULL, NULL, '2020-08-11 14:02:54', NULL, NULL, '2020-09-07 08:12:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Brand 1', 'Y', 1, 'Y', '2020-08-25 19:48:03', 1, 'Y', '2020-08-25 19:47:56', 'Y', NULL, NULL, NULL, 1, 'Y', '2020-09-08 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

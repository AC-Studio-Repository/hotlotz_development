-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 08, 2020 at 05:42 AM
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
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'organization',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ref_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(22) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hash_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'SHA1HashedPassword',
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'First name',
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Last name',
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vat_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `platform_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time_zone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified` tinyint(1) NOT NULL DEFAULT '0',
  `phone_number_verified` tinyint(1) NOT NULL DEFAULT '0',
  `tax_nr` varchar(17) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tax/VAT Identification Number',
  `registration_nr` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Company/Trade Registration Number',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `salutation` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `county` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_phone` varchar(22) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_internal_note` tinyint(1) NOT NULL DEFAULT '0',
  `internal_note` text COLLATE utf8mb4_unicode_ci,
  `fixed_commission_amount` decimal(15,4) DEFAULT NULL,
  `sellers_commission` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `override_category` tinyint(1) NOT NULL DEFAULT '0',
  `sellers_commission_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sellers_commission_amount` decimal(15,4) DEFAULT NULL,
  `vat_rate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `withhold_vat` tinyint(1) NOT NULL DEFAULT '0',
  `bank_account_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_payable_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iban` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `swift` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_currency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_address` text COLLATE utf8mb4_unicode_ci,
  `note_to_appear_on_statement` text COLLATE utf8mb4_unicode_ci,
  `buyer_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `export_buyer` tinyint(1) NOT NULL DEFAULT '0',
  `buyer_premium_override` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dealers_collectors_invoice` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note_to_appear_on_invoice` text COLLATE utf8mb4_unicode_ci,
  `buyers_premium` int(11) DEFAULT NULL,
  `marketing_preference` tinyint(1) NOT NULL DEFAULT '0',
  `category_interests` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `documents` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seller_gst_registered` tinyint(1) NOT NULL DEFAULT '1',
  `buyer_gst_registered` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL DEFAULT '1',
  `updated_by` int(11) NOT NULL DEFAULT '1',
  `deleted_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `last_purchase_at` datetime DEFAULT NULL,
  `sg_uen_number` int(11) DEFAULT '0',
  `reg_gst_sg` tinyint(1) DEFAULT '0',
  `gst_number` int(11) DEFAULT '0',
  `marketing_auction` tinyint(1) DEFAULT '0',
  `marketing_marketplace` tinyint(1) DEFAULT '0',
  `marketing_chk_events` tinyint(1) DEFAULT '0',
  `marketing_chk_congsignment_valuation` tinyint(1) DEFAULT '0',
  `marketing_hotlotz_quarterly` tinyint(1) DEFAULT '0',
  `last_login_at` datetime DEFAULT NULL,
  `login_count` int(10) UNSIGNED DEFAULT '0',
  `dialling_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sr_customer_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sr_customer_data` longtext COLLATE utf8mb4_unicode_ci,
  `shipping_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_cost` decimal(15,4) DEFAULT NULL,
  `shipping_notes` longtext COLLATE utf8mb4_unicode_ci,
  `contact_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_customer_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `type`, `title`, `ref_no`, `email`, `phone`, `password`, `hash_password`, `firstname`, `lastname`, `company_name`, `website`, `fax_number`, `vat_number`, `platform_code`, `time_zone`, `email_verified`, `phone_number_verified`, `tax_nr`, `registration_nr`, `is_active`, `salutation`, `fullname`, `id_number`, `address1`, `address2`, `address3`, `city`, `county`, `country_id`, `state`, `postal_code`, `mobile_phone`, `display_internal_note`, `internal_note`, `fixed_commission_amount`, `sellers_commission`, `override_category`, `sellers_commission_type`, `sellers_commission_amount`, `vat_rate`, `withhold_vat`, `bank_account_name`, `bank_account_number`, `sort_code`, `payment_type`, `cheque_payable_name`, `iban`, `swift`, `account_currency`, `bank_name`, `bank_address`, `note_to_appear_on_statement`, `buyer_number`, `export_buyer`, `buyer_premium_override`, `dealers_collectors_invoice`, `note_to_appear_on_invoice`, `buyers_premium`, `marketing_preference`, `category_interests`, `documents`, `seller_gst_registered`, `buyer_gst_registered`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`, `last_purchase_at`, `sg_uen_number`, `reg_gst_sg`, `gst_number`, `marketing_auction`, `marketing_marketplace`, `marketing_chk_events`, `marketing_chk_congsignment_valuation`, `marketing_hotlotz_quarterly`, `last_login_at`, `login_count`, `dialling_code`, `sr_customer_id`, `sr_customer_data`, `shipping_type`, `shipping_cost`, `shipping_notes`, `contact_id`, `stripe_customer_id`) VALUES
(1, 'individual', 'Mrs', 'A00001', 'kenetaqo@mailinator.com', '+1 (572) 259-7853', '$2y$10$EOMeMv5P.ekBf2KXaUGazO4i6ar8I6F7nfNJCkoweRELLOXb9VaVi', '$2y$10$TPbHcqpsN2uk4MggHplnQOuWxaWn1Bqi4cy6JdWPht5s6iJd.vR1K', 'Adrian', 'Morse', 'Park and Keller Co', NULL, '+1 (882) 878-4663', '839', NULL, '73', 0, 0, NULL, NULL, 1, 'Dr', 'Adrian Morse', NULL, '519 Oak Drive', 'Temporibus suscipit', 'Rerum ut dolore impe', 'Ea eu ut velit nemo', 'Et voluptates deseru', 232, NULL, '25264365', '+1 (854) 233-3022', 1, 'Facilis dolor deleni', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'none', '0', NULL, 25, 0, NULL, NULL, 1, 1, 1, 1, NULL, '2020-06-23 05:43:33', '2020-09-08 05:40:51', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, '2020-09-08 12:10:51', 7, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'organization', 'Mrs', 'A00002', 'seqycedy@mailinator.com', '+1 (169) 987-9465', '$2y$10$EOMeMv5P.ekBf2KXaUGazO4i6ar8I6F7nfNJCkoweRELLOXb9VaVi', '$2y$10$TPbHcqpsN2uk4MggHplnQOuWxaWn1Bqi4cy6JdWPht5s6iJd.vR1K', 'Odysseus', 'Wells', 'Prof Co., Ltd.', NULL, NULL, NULL, NULL, '208', 0, 0, NULL, NULL, 1, 'Prof', 'Odysseus Wells', NULL, '95 Cowley Avenue', NULL, NULL, 'Singapore', NULL, 702, NULL, '2342343', NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'none', '0', NULL, 25, 0, NULL, NULL, 1, 1, 1, 1, NULL, '2020-07-06 18:28:26', '2020-09-08 06:34:34', NULL, NULL, 0, 0, 0, 1, 0, 0, 0, 0, '2020-09-08 13:04:34', 3, '', NULL, '[]', NULL, NULL, NULL, NULL, NULL),
(3, 'organization', 'Mr', 'A00003', 'zolawowy@mailinator.com', '+1 (149) 303-7231', '$2y$10$74MwccA9y5ktQ/U/zfhQ/.9NT3xa6YvM2zfqLK9kbDumKgXB7l3ku', '$2y$10$pKJLNhxDoBmBwinvUXhzMeU7lWOvX./rb2blCsQF7pS2W8mCbbE0u', 'Justina', 'Baxter', 'Avila and Farley Co', NULL, '+1 (529) 868-4267', '917', NULL, '60', 0, 0, NULL, NULL, 1, 'Ms', 'Justina Baxter', NULL, '49 Nobel Parkway', 'Id quod in numquam s', 'Praesentium in labor', 'Assumenda eu volupta', 'Quo voluptate doloru', 450, NULL, '3546346', '+1 (375) 204-3407', 1, 'Repudiandae sit sit', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'none', '0', NULL, 25, 0, NULL, NULL, 1, 1, 1, 1, NULL, '2020-07-06 18:47:20', '2020-08-18 13:03:13', NULL, NULL, NULL, 1, NULL, 0, 0, 0, 0, 0, '2020-08-18 19:33:13', 2, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'individual', 'Mr', 'A00004', 'dubacewyde@mailinator.com', '+1 (224) 971-8358', '$2y$10$LchPd3.Q9EtDfnIC7Sqgz.C3khGrWdC2BTZ3OKKSdJc5DwQeIoUT2', '$2y$10$dxHrxvkikFPXKwMq1qLYuudC6nnLjS1k8IYkCWL2dJBTvp7lfo99m', 'Catherine', 'Ford', 'Hatfield Humphrey Plc', NULL, '+1 (967) 638-3389', '402', NULL, '78', 0, 0, NULL, NULL, 1, 'Prof', 'Catherine Ford', NULL, '497 Milton Freeway', 'Nostrum tenetur aliq', 'Molestiae et hic ex', 'Cupiditate aut sit', 'Voluptas culpa ut s', 356, NULL, '4635342', '+1 (947) 348-8225', 1, 'Ea magna velit dolo', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'none', '0', NULL, 25, 0, NULL, NULL, 1, 1, 1, 1, NULL, '2020-07-06 18:49:19', '2020-07-06 18:49:19', NULL, NULL, NULL, 1, NULL, 0, 0, 0, 0, 0, NULL, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'organization', 'Mrs', 'A00005', 'tewuzacum@mailinator.com', '+1 (305) 608-1333', '$2y$10$xtYYWQ8QXCIzrwJgLBqQwuGtZOpLEOKr9nFqcF3sy8szMmZc/Hcn.', '$2y$10$RnoNqQMkwbBWb3Pjzp1jcurxyiLV3DAJcxJPJY//ruxQOhRgQgo6q', 'Nolan', 'Salinas', 'Cervantes and Jones Inc', NULL, '+1 (282) 916-2018', '94', NULL, '166', 0, 0, NULL, NULL, 1, 'Prof', 'Nolan Salinas', NULL, '22 South Clarendon Drive', 'Aperiam aut irure qu', 'Id pariatur Sunt n', 'Ut aliquid ea culpa', 'Corrupti pariatur', 512, NULL, 'Aut voluptatum aliqu', '+1 (942) 937-5274', 1, 'Esse fugiat ipsam es', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'none', '0', NULL, 25, 0, NULL, NULL, 1, 1, 1, 1, NULL, '2020-07-06 19:10:18', '2020-09-08 05:47:02', NULL, NULL, NULL, 1, NULL, 0, 0, 0, 0, 0, '2020-09-08 12:17:02', 1, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 'organization', 'Mrs', 'A00011', 'test@hotlotz.com', '34546876', '$2y$10$NLikAm9SjxrvQ5baw/gGlO7ZSansYes38O5BKKTkVW1TsYpjfa2YS', '$2y$10$ue9p1700SwQGt492Z391WO0vHgBvem7a.bXPVlCLAhtacqNSdBJnq', 'TEST', 'test', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 1, 'Mrs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, 1, 1, 1, NULL, '2020-08-06 06:57:56', '2020-08-06 06:57:56', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 'organization', 'Mrs', 'A00012', 'testwells@gmail.com', '5658453532', '$2y$10$xPwfIGqUOXa3Jw87OU.iB.Wlp7orkl557yt11/GvFsvcwYAXX.yWC', '$2y$10$67vNxTcqpe8lGNMWpKtjMuaK3gBfbFwOODXK8c34bO9KwZuzDHJEm', 'test', 'Wells', 'Prof Co., Ltd.', NULL, NULL, NULL, NULL, '3', 0, 0, NULL, NULL, 1, 'Mrs', 'test Wells', NULL, '95 Cowley Avenue', NULL, NULL, NULL, NULL, 4, NULL, NULL, '456786563', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'none', '0', NULL, 25, 0, NULL, NULL, 1, 1, 1, 1, NULL, '2020-08-20 10:24:33', '2020-08-20 10:24:33', NULL, NULL, NULL, 1, NULL, 0, 0, 0, 0, 0, NULL, 0, '+65', NULL, '[]', NULL, NULL, NULL, NULL, NULL),
(14, 'organization', 'Mr', 'A00014', 'hein@nexlabs.co', '+959975802166', '$2y$10$EOMeMv5P.ekBf2KXaUGazO4i6ar8I6F7nfNJCkoweRELLOXb9VaVi', '$2y$10$EOMeMv5P.ekBf2KXaUGazO4i6ar8I6F7nfNJCkoweRELLOXb9VaVi', 'Tanner', 'Wooten', 'England and Vinson Plc', NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 1, 'Mr', NULL, NULL, 'The Card Factory', '1107-1109 Whitgift Centre', NULL, 'Croydon', NULL, NULL, NULL, '11111', NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, 1, 1, 1, NULL, '2020-09-07 01:56:10', '2020-09-07 01:56:10', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, '+95', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 'organization', 'Mr', 'A00015', 'maychothet@nexlabs.co', '9797163324', '$2y$10$EOMeMv5P.ekBf2KXaUGazO4i6ar8I6F7nfNJCkoweRELLOXb9VaVi', '$2y$10$EOMeMv5P.ekBf2KXaUGazO4i6ar8I6F7nfNJCkoweRELLOXb9VaVi', 'May Cho', 'Thet', 'Nexlabs Co., Ltd.', NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 1, 'Ms', NULL, NULL, 'Yangon', NULL, NULL, 'Yangon', NULL, NULL, NULL, '11111', NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, 1, 1, 1, NULL, '2020-09-07 01:57:51', '2020-09-08 05:21:20', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, '2020-09-08 11:51:20', 2, '+95', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 'individual', NULL, 'A00016', 'testbidtwo@gmail.com', '+959976274935', '$2y$10$EOMeMv5P.ekBf2KXaUGazO4i6ar8I6F7nfNJCkoweRELLOXb9VaVi', '$2y$10$EOMeMv5P.ekBf2KXaUGazO4i6ar8I6F7nfNJCkoweRELLOXb9VaVi', 'test bid', 'two', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 1, 'Ms', NULL, NULL, '65 East First Road', 'Itaque quisquam at nemo accusamus animi quae sunt', 'Est occaecat ducimus alias soluta sint dolores e', 'Odio quisquam est perferendis autem sed obcaecati ', NULL, NULL, NULL, 'Ea alias asperiores ', NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, 1, 1, 1, NULL, '2020-09-07 01:58:37', '2020-09-07 01:58:37', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, '+95', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

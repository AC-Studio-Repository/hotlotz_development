-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 08, 2020 at 05:35 AM
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
-- Table structure for table `auctions`
--

DROP TABLE IF EXISTS `auctions`;
CREATE TABLE `auctions` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `legeacy_id` int(11) NOT NULL DEFAULT '0',
  `auction_created_date_time_utc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time_is_already_utc` tinyint(1) NOT NULL DEFAULT '0',
  `created_by_user` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_approved` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `is_published` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `is_closed` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `is_submitted` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `is_ready_invoice` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `is_invoiced` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `sr_auction_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sr_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sr_auction_data` longtext COLLATE utf8mb4_unicode_ci,
  `bidders_list` longtext COLLATE utf8mb4_unicode_ci,
  `winners_list` longtext COLLATE utf8mb4_unicode_ci,
  `sr_sale_result` longtext COLLATE utf8mb4_unicode_ci,
  `sr_category_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `post_sale_advice_email_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'to be sent out email after Auction colse',
  `pre_sale_advice_email_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'to be sent out email after add Item to Auction as Lot / before Auction begins',
  `client_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auction_listings` longtext COLLATE utf8mb4_unicode_ci,
  `timezone_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_required` tinyint(1) NOT NULL DEFAULT '0',
  `address1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `town_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_state_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `post_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paddle_seed` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approval_type` enum('Automatic','Manual') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Manual',
  `important_information` text COLLATE utf8mb4_unicode_ci,
  `terms` text COLLATE utf8mb4_unicode_ci,
  `shipping_info` text COLLATE utf8mb4_unicode_ci,
  `telephone_number` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'https://www.hotlotz.com',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirmation_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registration_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_receive_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `increment_set_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `minimum_deposite` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `automatic_deposite` tinyint(1) NOT NULL DEFAULT '0',
  `automatic_refund` tinyint(1) NOT NULL DEFAULT '0',
  `vat_rate` int(11) NOT NULL DEFAULT '20',
  `buyers_premium_vat_rate` decimal(15,4) NOT NULL DEFAULT '0.2000',
  `internet_surcharge_vat_rate` decimal(15,4) NOT NULL DEFAULT '0.2000',
  `buyers_premium` int(11) NOT NULL DEFAULT '0',
  `internet_surcharge_rate` int(11) NOT NULL DEFAULT '5',
  `winner_notification_note` text COLLATE utf8mb4_unicode_ci,
  `timed_start` datetime DEFAULT NULL,
  `timed_first_lot_ends` datetime DEFAULT NULL,
  `sale_dates` longtext COLLATE utf8mb4_unicode_ci,
  `viewing_dates` longtext COLLATE utf8mb4_unicode_ci,
  `auction_card_types` longtext COLLATE utf8mb4_unicode_ci,
  `piece_meal` tinyint(1) NOT NULL DEFAULT '0',
  `publish_post_sale_results` tinyint(1) NOT NULL DEFAULT '0',
  `international_debit_card_fixed_fee` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `international_debit_card_percentage_fee` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `international_debit_card_fee_excluded_country_list` longtext COLLATE utf8mb4_unicode_ci,
  `projected_spend_required` tinyint(1) NOT NULL DEFAULT '0',
  `linked_auctions` longtext COLLATE utf8mb4_unicode_ci,
  `atg_commission` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `atg_commission_ceiling` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `clients_auction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hammer_excess` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hide_venue_address_for_lot_locations` tinyint(1) NOT NULL DEFAULT '0',
  `advanced_time_bidding_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT '1',
  `updated_by` int(11) NOT NULL DEFAULT '1',
  `deleted_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `auctions`
--

INSERT INTO `auctions` (`id`, `legeacy_id`, `auction_created_date_time_utc`, `time_is_already_utc`, `created_by_user`, `type`, `title`, `status`, `is_approved`, `is_published`, `is_closed`, `is_submitted`, `is_ready_invoice`, `is_invoiced`, `sr_auction_id`, `sr_reference`, `sr_auction_data`, `bidders_list`, `winners_list`, `sr_sale_result`, `sr_category_name`, `post_sale_advice_email_flag`, `pre_sale_advice_email_flag`, `client_id`, `auction_listings`, `timezone_id`, `card_required`, `address1`, `town_city`, `country_state_name`, `post_code`, `country`, `country_code`, `currency`, `paddle_seed`, `approval_type`, `important_information`, `terms`, `shipping_info`, `telephone_number`, `website`, `email`, `confirmation_email`, `registration_email`, `payment_receive_email`, `increment_set_name`, `minimum_deposite`, `automatic_deposite`, `automatic_refund`, `vat_rate`, `buyers_premium_vat_rate`, `internet_surcharge_vat_rate`, `buyers_premium`, `internet_surcharge_rate`, `winner_notification_note`, `timed_start`, `timed_first_lot_ends`, `sale_dates`, `viewing_dates`, `auction_card_types`, `piece_meal`, `publish_post_sale_results`, `international_debit_card_fixed_fee`, `international_debit_card_percentage_fee`, `international_debit_card_fee_excluded_country_list`, `projected_spend_required`, `linked_auctions`, `atg_commission`, `atg_commission_ceiling`, `clients_auction_id`, `hammer_excess`, `hide_venue_address_for_lot_locations`, `advanced_time_bidding_enabled`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
('00c99d52-2a4e-435e-ab18-8e6d1fec8c58', 0, '', 0, '', 'Timed', 'HL Auction 21', 'Published', 'N', 'N', 'N', 'N', 'N', 'N', '502148d0-e2b4-4f59-8db0-abf00061ce6c', 'hotzlo10050', '{\n    \"AuctionId\": \"502148d0-e2b4-4f59-8db0-abf00061ce6c\",\n    \"LegacyId\": 0,\n    \"AuctionReference\": \"hotzlo10050\",\n    \"Title\": \"HL Auction 21\",\n    \"ClientId\": \"a20b82bf-5262-464d-b961-a7a200c35d3c\",\n    \"AuctionType\": \"Timed\",\n    \"AuctionTypeCode\": 3,\n    \"ClientName\": \"Hotlotz Singapore\",\n    \"ClientUrl\": \"HotLotz\",\n    \"AuctionListings\": [\n        {\n            \"PlatformCode\": \"SR\",\n            \"AuctionTypeAndListing\": \"Timed\",\n            \"CategoryName\": \"Asian Art\",\n            \"Private\": true\n        }\n    ],\n    \"IsApproved\": true,\n    \"IsPublished\": true,\n    \"AuctionStatus\": \"Created\",\n    \"AuctionStatusCode\": 0,\n    \"TimezoneId\": \"Singapore Standard Time\",\n    \"CardRequired\": false,\n    \"Address1\": \"\",\n    \"Address2\": \"\",\n    \"TownCity\": \"\",\n    \"Postcode\": \"\",\n    \"Country\": \"Singapore\",\n    \"CountryCode\": \"SG\",\n    \"Currency\": \"SGD\",\n    \"BuyersPremium\": 0,\n    \"InternetSurchargeRate\": 5,\n    \"VatRate\": 20,\n    \"BuyersPremiumVatRate\": 0.2,\n    \"InternetSurchargeVatRate\": 0.2,\n    \"BuyersPremiumCeiling\": 0,\n    \"InternetSurchargeCeiling\": 0,\n    \"ApprovalType\": \"Automatic\",\n    \"ApprovalTypeCode\": 1,\n    \"ImportantInformation\": \"Tests\",\n    \"Terms\": \"Testtt\",\n    \"ShippingInfo\": \"<p>PACKING, SHIPPING &amp; INSURANCE<\\/p>\\n<p>HotLotz collaborates with professional art handling companies and other specialist shippers to provide cost effective, bespoke packing and domestic and international insured door-to-door shipping.<\\/p>\\n<p>We can provide indicative quotes within 24 hours, on request.<\\/p>\\n<p>Please contact&nbsp;<a href=\\\"mailto:hello@hotlotz.com\\\">hello@hotlotz.com<\\/a>&nbsp;if you would like further information on either service.<\\/p>\",\n    \"TelephoneNumber\": \"4508796545\",\n    \"Website\": \"www.hotlotz.com\",\n    \"Email\": \"hello@hotlotz.com\",\n    \"ConfirmationEmail\": \"hello@hotlotz.com\",\n    \"RegistrationEmail\": \"hello@hotlotz.com\",\n    \"RequestConfirmationEmail\": true,\n    \"RequestRegistrationEmail\": true,\n    \"IncrementSetName\": \"HotLotz Increment Table\",\n    \"SaleDates\": [],\n    \"ViewingDates\": [],\n    \"TimedStart\": \"\\/Date(1598853600000-0000)\\/\",\n    \"TimedFirstLotEnds\": \"\\/Date(1604260800000-0000)\\/\",\n    \"ReconciliationDeadline\": \"\\/Date(1606046400000-0000)\\/\",\n    \"MinimumDeposit\": 0,\n    \"AutomaticDeposit\": false,\n    \"AutomaticRefund\": false,\n    \"AuctionCardTypes\": [],\n    \"PieceMeal\": false,\n    \"ImplementationType\": \"Self\",\n    \"InventoryReceived\": true,\n    \"SalePushedLive\": false,\n    \"WinnersNotificationNotes\": \"Test\",\n    \"PublishPostSaleResults\": false,\n    \"AutoBidsDisabled\": false,\n    \"InternationalDebitCardFixedFee\": 0,\n    \"InternationalDebitCardPercentageFee\": 0,\n    \"LotCount\": 2,\n    \"ClientsAuctionId\": \"\",\n    \"ImplementationTypeCode\": 2,\n    \"ListingType\": 1,\n    \"GbpExchangeRate\": 0.481498,\n    \"UsdExchangeRate\": 0.74131,\n    \"SequenceNumber\": 0,\n    \"IncrementSetId\": \"e3097e309ab34a88b251a7b0009a0880\",\n    \"RequestPaymentReceivedEmail\": true,\n    \"PaymentReceivedEmail\": \"hello@hotlotz.com\",\n    \"BuyItNowEnabled\": false,\n    \"AuctionDateTimeUtc\": \"\\/Date(1604232000000-0000)\\/\",\n    \"DeliveryOffered\": false,\n    \"PortalLotListPreferences\": 0,\n    \"DisablePhoneVerification\": true,\n    \"EndCurrentDisplayUtc\": \"\\/Date(1598972400000-0000)\\/\",\n    \"PrivateListing\": false,\n    \"HammerExcess\": \"\",\n    \"HideVenueAddressForLotLocations\": false\n}', NULL, NULL, NULL, 'Asian Art', 0, 0, '', '{\"PlatformCode\":\"SR\",\"AuctionTypeAndListing\":\"Timed\",\"CategoryName\":\"Asian Art\",\"Private\":true}', '', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'SGD', NULL, 'Automatic', 'Tests', 'Testtt', '', '4508796545', 'www.hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'HotLotz Increment Table', '0.0000', 0, 0, 20, '0.2000', '0.2000', 0, 5, '', '2020-08-31 07:00:00', '2020-10-01 20:00:00', '[]', '[]', '[]', 0, 0, '0.0000', '0.0000', '[]', 0, '[]', '0.0000', '0.0000', '', '', 0, 0, 1, 1, NULL, '2020-07-06 06:26:04', '2020-09-03 09:55:05', NULL),
('02713e36-3805-4792-ba1d-9f3c923fd993', 0, '', 0, '', 'Timed', 'HL Auction 14', 'Approved', 'N', 'N', 'N', 'N', 'N', 'N', 'f5835a60-2a47-4573-bb7c-abed00518494', 'hotzlo10043', NULL, NULL, NULL, NULL, 'Furniture', 0, 0, '', '{\"PlatformCode\":\"SR\",\"AuctionTypeAndListing\":\"Timed\",\"CategoryName\":\"Furniture\",\"Private\":true}', '', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'SGD', NULL, 'Automatic', 'Test', 'Test', '', '987654233', 'www.hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'Hotlotz Increment Table', '0.0000', 0, 0, 20, '0.2000', '0.2000', 0, 5, '', '2020-07-09 10:00:00', '2020-11-01 18:30:00', '[]', '[]', '[]', 0, 0, '0.0000', '0.0000', '[]', 0, '[]', '0.0000', '0.0000', '', '', 1, 0, 1, 1, NULL, '2020-07-03 05:26:46', '2020-09-02 08:13:50', NULL),
('12b50863-a3da-439f-8490-dfb2672fa1e2', 0, '', 0, '', 'Timed', 'HL Auction 5', 'Approved', 'N', 'N', 'N', 'N', 'N', 'N', 'c23c8f59-62a4-4bad-9a68-abe5013546f4', NULL, NULL, NULL, NULL, NULL, 'Collectables', 0, 0, '', '{\"PlatformCode\":\"SR\",\"AuctionTypeAndListing\":\"Timed\",\"CategoryName\":\"Collectables\",\"Private\":true}', '', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'SGD', NULL, 'Automatic', 'Numquam similique ve', 'Esse aliqua Maxime', '', '4456786753443', 'https://www.sazuryty.co.uk', 'suta@mailinator.com', 'suta@mailinator.com', 'momav@mailinator.com', 'sasijekoru@mailinator.com', 'HotLotz Increment Table', '0.0000', 0, 0, 20, '0.2000', '0.2000', 0, 5, '', '2020-07-08 09:00:00', '2020-09-30 17:00:00', '[]', '[]', '[]', 0, 0, '0.0000', '0.0000', '[]', 0, '[]', '0.0000', '0.0000', '', '', 1, 0, 1, 1, NULL, '2020-06-25 19:15:00', '2020-07-08 18:21:22', NULL),
('20083e02-a779-4acb-a3ed-58f1051a7543', 0, '', 0, '', 'Timed', 'QTestbyMct', 'Awaiting approval', 'N', 'N', 'N', 'N', 'N', 'N', 'f3d0f4e5-e6ff-4a56-9137-ac250172f588', 'hotzlo10082', NULL, NULL, NULL, NULL, 'Furniture', 0, 0, '', '{\"PlatformCode\":\"SR\",\"AuctionTypeAndListing\":\"Timed\",\"CategoryName\":\"Furniture\",\"Private\":true}', '', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'SGD', NULL, 'Automatic', 'Est quis libero quas', 'Est iste aliquip cul', '', '+1 (506) 923-8436', 'https://www.sysaqixut.com.au', 'vowufisex@mailinator.com', 'vowufisex@mailinator.com', 'cykiso@mailinator.com', 'kanabigywa@mailinator.com', 'Summer Snow', '0.0000', 0, 0, 20, '0.2000', '0.2000', 0, 5, '', '2020-08-29 06:00:00', '2020-08-29 08:00:00', '[]', '[]', '[]', 0, 0, '0.0000', '0.0000', '[]', 0, '[]', '0.0000', '0.0000', '', '', 1, 0, 1, 1, NULL, '2020-08-28 23:41:12', '2020-08-29 01:05:25', NULL),
('20ecdb04-c527-43c6-8257-33369ae0703b', 0, '', 0, '', 'Timed', 'HL Auction 10', 'Approved', 'N', 'N', 'N', 'N', 'N', 'N', 'e1ceb74f-5e88-4ca9-8419-abed00504d7c', NULL, NULL, NULL, NULL, NULL, 'Fine Art', 0, 0, '', '{\"PlatformCode\":\"SR\",\"AuctionTypeAndListing\":\"Timed\",\"CategoryName\":\"Fine Art\",\"Private\":true}', '', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'SGD', NULL, 'Automatic', 'Test', 'Test', '', '56854342', 'www.hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'Hotlotz Increment Table', '0.0000', 0, 0, 20, '0.2000', '0.2000', 0, 5, '', '2020-07-08 07:00:00', '2020-09-30 20:00:00', '[]', '[]', '[]', 0, 0, '0.0000', '0.0000', '[]', 0, '[]', '0.0000', '0.0000', '', '', 0, 0, 1, 1, NULL, '2020-07-03 05:22:21', '2020-07-08 18:21:23', NULL),
('2dd7d4af-9ada-4406-9620-69145e7390ea', 0, '', 0, '', 'Timed', 'HL Auction 19', 'Approved', 'N', 'N', 'N', 'N', 'N', 'N', 'f109f4b6-c0d1-45a5-87af-abed00532587', NULL, NULL, NULL, NULL, NULL, 'Furniture', 0, 0, '', '{\"PlatformCode\":\"SR\",\"AuctionTypeAndListing\":\"Timed\",\"CategoryName\":\"Furniture\",\"Private\":true}', '', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'SGD', NULL, 'Automatic', 'test', 'testt', '', '85453242', 'www.hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'Holtlotz Increment Table', '0.0000', 0, 0, 20, '0.2000', '0.2000', 0, 5, '', '2020-07-07 12:30:00', '2020-09-30 14:30:00', '[]', '[]', '[]', 0, 0, '0.0000', '0.0000', '[]', 0, '[]', '0.0000', '0.0000', '', '', 0, 0, 1, 1, NULL, '2020-07-03 05:32:42', '2020-07-08 18:21:24', NULL),
('3a743dff-2e6d-4767-b943-cc502e77a084', 0, '', 0, '', 'Timed', 'HL Auction 16', 'Approved', 'N', 'N', 'N', 'N', 'N', 'N', '48f11b89-d5b6-4751-bf6e-abed0052190f', NULL, NULL, NULL, NULL, NULL, 'Asian Art', 0, 0, '', '{\"PlatformCode\":\"SR\",\"AuctionTypeAndListing\":\"Timed\",\"CategoryName\":\"Asian Art\",\"Private\":true}', '', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'SGD', NULL, 'Automatic', 'test', 'test', '', '65635322', 'www.hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'Hotlotz Increment Table', '0.0000', 0, 0, 20, '0.2000', '0.2000', 0, 5, '', '2020-07-06 12:00:00', '2020-09-30 14:00:00', '[]', '[]', '[]', 0, 0, '0.0000', '0.0000', '[]', 0, '[]', '0.0000', '0.0000', '', '', 0, 0, 1, 1, NULL, '2020-07-03 05:28:53', '2020-07-08 18:21:26', NULL),
('42768385-1e61-48f8-b782-d9c62633885a', 0, '', 0, '', 'Timed', 'HL Auction 8', 'Approved', 'N', 'N', 'N', 'N', 'N', 'N', '70994d75-c849-41df-a2b5-abed004fa331', NULL, NULL, NULL, NULL, NULL, 'Clocks, Watches & Jewellery', 0, 0, '', '{\"PlatformCode\":\"SR\",\"AuctionTypeAndListing\":\"Timed\",\"CategoryName\":\"Clocks, Watches & Jewellery\",\"Private\":true}', '', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'SGD', NULL, 'Automatic', 'Test', 'Teest', '', '546545345', 'www.hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'Hotlotz Increment Table', '0.0000', 0, 0, 20, '0.2000', '0.2000', 0, 5, '', '2020-07-10 10:00:00', '2020-09-30 16:00:00', '[]', '[]', '[]', 0, 0, '0.0000', '0.0000', '[]', 0, '[]', '0.0000', '0.0000', '', '', 0, 0, 1, 1, NULL, '2020-07-03 05:19:55', '2020-07-08 18:21:27', NULL),
('50cd667e-a847-4493-a904-d5e59a905ddb', 0, '', 0, '', 'Timed', 'HL Auction 13', 'Approved', 'N', 'N', 'N', 'N', 'N', 'N', '9c9bc45d-e5f6-485f-a16c-abed00513551', NULL, NULL, NULL, NULL, NULL, 'Asian Art', 0, 0, '', '{\"PlatformCode\":\"SR\",\"AuctionTypeAndListing\":\"Timed\",\"CategoryName\":\"Asian Art\",\"Private\":true}', '', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'SGD', NULL, 'Automatic', 'Test', 'Test', '', '753325665', 'www.hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'Hotlotz Increment Table', '0.0000', 0, 0, 20, '0.2000', '0.2000', 0, 5, '', '2020-07-07 09:30:00', '2020-09-30 13:00:00', '[]', '[]', '[]', 0, 0, '0.0000', '0.0000', '[]', 0, '[]', '0.0000', '0.0000', '', '', 0, 0, 1, 1, NULL, '2020-07-03 05:25:38', '2020-07-08 18:21:28', NULL),
('52def76f-c134-4463-87fd-bcff644f59a4', 0, '', 0, '', 'Timed', 'HL Auction 17', 'Approved', 'N', 'N', 'N', 'N', 'N', 'N', '6b8911da-2b94-4705-98be-abed005269b2', NULL, NULL, NULL, NULL, NULL, 'Decorative Art', 0, 0, '', '{\"PlatformCode\":\"SR\",\"AuctionTypeAndListing\":\"Timed\",\"CategoryName\":\"Decorative Art\",\"Private\":true}', '', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'SGD', NULL, 'Automatic', 'test', 'test', '', '437568678', 'www.hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'Hotlotz Increment Table', '0.0000', 0, 0, 20, '0.2000', '0.2000', 0, 5, '', '2020-07-06 13:00:00', '2020-09-30 15:30:00', '[]', '[]', '[]', 0, 0, '0.0000', '0.0000', '[]', 0, '[]', '0.0000', '0.0000', '', '', 0, 0, 1, 1, NULL, '2020-07-03 05:30:02', '2020-07-08 18:21:29', NULL),
('5af95800-c1c9-4a56-929e-ed5e703d012d', 0, '', 0, '', 'Timed', 'HL Auction 4', 'Approved', 'N', 'N', 'N', 'N', 'N', 'N', '5499894d-3d8c-49fc-9932-abe5012e971f', NULL, NULL, NULL, NULL, NULL, 'Asian Art', 0, 0, '', '{\"PlatformCode\":\"SR\",\"AuctionTypeAndListing\":\"Timed\",\"CategoryName\":\"Asian Art\",\"Private\":true}', '', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'SGD', NULL, 'Automatic', 'Laudantium et quia', 'Optio quidem esse s', '', '445676543256', 'https://www.taxeryrysyrame.cm', 'lugaz@mailinator.com', 'lugaz@mailinator.com', 'dujof@mailinator.com', 'dufel@mailinator.com', 'HotLotz Increment Table', '0.0000', 0, 0, 20, '0.2000', '0.2000', 0, 5, '', '2020-07-07 10:30:00', '2020-09-30 19:00:00', '[]', '[]', '[]', 0, 0, '0.0000', '0.0000', '[]', 0, '[]', '0.0000', '0.0000', '', '', 1, 0, 1, 1, NULL, '2020-06-25 18:41:39', '2020-07-08 18:21:33', NULL),
('5fdfa6fb-5264-496c-8e1f-b2f0a89f23a2', 0, '', 0, '', 'Timed', 'HL Auction 1', 'Invoiced', 'N', 'N', 'N', 'N', 'N', 'N', '07e29feb-b60f-466c-bf72-abe20034e61e', 'hotzlo10029', '{\n    \"AuctionId\": \"07e29feb-b60f-466c-bf72-abe20034e61e\",\n    \"LegacyId\": 0,\n    \"AuctionReference\": \"hotzlo10029\",\n    \"Title\": \"HL Auction 1\",\n    \"ClientId\": \"a20b82bf-5262-464d-b961-a7a200c35d3c\",\n    \"AuctionType\": \"Timed\",\n    \"AuctionTypeCode\": 3,\n    \"ClientName\": \"Hotlotz Singapore\",\n    \"ClientUrl\": \"HotLotz\",\n    \"AuctionListings\": [\n        {\n            \"PlatformCode\": \"SR\",\n            \"AuctionTypeAndListing\": \"Timed\",\n            \"CategoryName\": \"Asian Art\",\n            \"Private\": true\n        }\n    ],\n    \"IsApproved\": true,\n    \"IsPublished\": true,\n    \"AuctionStatus\": \"Invoiced\",\n    \"AuctionStatusCode\": 12,\n    \"TimezoneId\": \"Singapore Standard Time\",\n    \"CardRequired\": false,\n    \"Address1\": \"\",\n    \"Address2\": \"\",\n    \"TownCity\": \"\",\n    \"Postcode\": \"\",\n    \"Country\": \"Singapore\",\n    \"CountryCode\": \"SG\",\n    \"Currency\": \"SGD\",\n    \"BuyersPremium\": 0,\n    \"InternetSurchargeRate\": 5,\n    \"VatRate\": 20,\n    \"BuyersPremiumVatRate\": 0.2,\n    \"InternetSurchargeVatRate\": 0.2,\n    \"BuyersPremiumCeiling\": 0,\n    \"InternetSurchargeCeiling\": 0,\n    \"ApprovalType\": \"Automatic\",\n    \"ApprovalTypeCode\": 1,\n    \"ImportantInformation\": \"Temporibus consequat\",\n    \"Terms\": \"Sed eum magni impedi\",\n    \"ShippingInfo\": \"<p>PACKING, SHIPPING &amp; INSURANCE<\\/p>\\n<p>HotLotz collaborates with professional art handling companies and other specialist shippers to provide cost effective, bespoke packing and domestic and international insured door-to-door shipping.<\\/p>\\n<p>We can provide indicative quotes within 24 hours, on request.<\\/p>\\n<p>Please contact&nbsp;<a href=\\\"mailto:hello@hotlotz.com\\\">hello@hotlotz.com<\\/a>&nbsp;if you would like further information on either service.<\\/p>\",\n    \"TelephoneNumber\": \"49574895893\",\n    \"Website\": \"https:\\/\\/www.sybokiwa.co.uk\",\n    \"Email\": \"lilynosog@mailinator.com\",\n    \"ConfirmationEmail\": \"wijecobop@mailinator.com\",\n    \"RegistrationEmail\": \"wijecobop@mailinator.com\",\n    \"RequestConfirmationEmail\": true,\n    \"RequestRegistrationEmail\": true,\n    \"IncrementSetName\": \"HotLotz Increment Table\",\n    \"SaleDates\": [],\n    \"ViewingDates\": [],\n    \"TimedStart\": \"\\/Date(1594018800000-0000)\\/\",\n    \"TimedFirstLotEnds\": \"\\/Date(1597950000000-0000)\\/\",\n    \"ReconciliationDeadline\": \"\\/Date(1599822000000-0000)\\/\",\n    \"ReconciliationSubmittedDate\": \"\\/Date(1598092399577-0000)\\/\",\n    \"ReconciliationSubmittedBy\": \"maychothet@nexlabs.co\",\n    \"MinimumDeposit\": 0,\n    \"AutomaticDeposit\": false,\n    \"AutomaticRefund\": false,\n    \"AuctionCardTypes\": [],\n    \"PieceMeal\": false,\n    \"ImplementationType\": \"Self\",\n    \"InventoryReceived\": true,\n    \"SalePushedLive\": false,\n    \"WinnersNotificationNotes\": \"Test\",\n    \"PublishPostSaleResults\": false,\n    \"AutoBidsDisabled\": false,\n    \"InternationalDebitCardFixedFee\": 0,\n    \"InternationalDebitCardPercentageFee\": 0,\n    \"LotCount\": 4,\n    \"ClientsAuctionId\": \"\",\n    \"ImplementationTypeCode\": 2,\n    \"ListingType\": 1,\n    \"GbpExchangeRate\": 0.481498,\n    \"UsdExchangeRate\": 0.74131,\n    \"SequenceNumber\": 0,\n    \"IncrementSetId\": \"e3097e309ab34a88b251a7b0009a0880\",\n    \"RequestPaymentReceivedEmail\": true,\n    \"PaymentReceivedEmail\": \"cijulony@mailinator.com\",\n    \"BuyItNowEnabled\": false,\n    \"AuctionDateTimeUtc\": \"\\/Date(1597921200000-0000)\\/\",\n    \"DeliveryOffered\": false,\n    \"PortalLotListPreferences\": 0,\n    \"DisablePhoneVerification\": true,\n    \"EndCurrentDisplayUtc\": \"\\/Date(1593529200000-0000)\\/\",\n    \"PrivateListing\": false,\n    \"HammerExcess\": \"\",\n    \"HideVenueAddressForLotLocations\": false\n}', '[{},{},{},{},{}]', '[{},{},{}]', '[{},{},{},{}]', 'Asian Art', 0, 0, '', '{\"PlatformCode\":\"SR\",\"AuctionTypeAndListing\":\"Timed\",\"CategoryName\":\"Asian Art\",\"Private\":true}', '', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'SGD', NULL, 'Automatic', 'Temporibus consequat', 'Sed eum magni impedi', '', '49574895893', 'https://www.sybokiwa.co.uk', 'lilynosog@mailinator.com', 'wijecobop@mailinator.com', 'wijecobop@mailinator.com', 'cijulony@mailinator.com', 'HotLotz Increment Table', '0.0000', 0, 0, 20, '0.2000', '0.2000', 0, 5, '', '2020-07-06 08:00:00', '2020-08-20 20:00:00', '[]', '[]', '[]', 0, 0, '0.0000', '0.0000', '[]', 0, '[]', '0.0000', '0.0000', '', '', 1, 0, 1, 1, NULL, '2020-06-22 03:42:34', '2020-09-07 04:16:05', NULL),
('89d8dfdb-948d-427a-a901-ff38eef90b37', 0, '', 0, '', 'Timed', 'HL Auction 3', 'Approved', 'N', 'N', 'N', 'N', 'N', 'N', 'c78e5623-bed0-41af-ac9b-abe50102817b', NULL, NULL, NULL, NULL, NULL, 'Collectables', 0, 0, '', '{\"PlatformCode\":\"SR\",\"AuctionTypeAndListing\":\"Timed\",\"CategoryName\":\"Collectables\",\"Private\":true}', '', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'SGD', NULL, 'Automatic', 'Et aut est aut odio', 'Esse inventore exerc', '', '3456754435435', 'https://www.max.co.uk', 'fisanowyf@mailinator.com', 'piwicuxav@mailinator.com', 'piwicuxav@mailinator.com', 'selu@mailinator.com', 'HotLotz Increment Table', '0.0000', 0, 0, 20, '0.2000', '0.2000', 0, 5, '', '2020-07-06 10:00:00', '2020-09-30 20:00:00', '[]', '[]', '[]', 0, 0, '0.0000', '0.0000', '[]', 0, '[]', '0.0000', '0.0000', '', '', 1, 0, 1, 1, NULL, '2020-06-25 16:11:09', '2020-07-08 18:21:35', NULL),
('a219fc5e-242e-4735-9160-61881af9db63', 0, '', 0, '', 'Timed', 'HL Auction 7', 'Approved', 'N', 'N', 'N', 'N', 'N', 'N', '5e56ee20-69f0-48f8-afa2-abed004f4d63', NULL, NULL, NULL, NULL, NULL, 'Fine Art & Antiques', 0, 0, '', '{\"PlatformCode\":\"SR\",\"AuctionTypeAndListing\":\"Timed\",\"CategoryName\":\"Fine Art & Antiques\",\"Private\":true}', '', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'SGD', NULL, 'Automatic', 'Test', 'Test', '', '863534322', 'www.hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'Hotlotz Increment Table', '0.0000', 0, 0, 20, '0.2000', '0.2000', 0, 5, '', '2020-07-04 09:00:00', '2020-09-30 17:00:00', '[]', '[]', '[]', 0, 0, '0.0000', '0.0000', '[]', 0, '[]', '0.0000', '0.0000', '', '', 0, 0, 1, 1, NULL, '2020-07-03 05:18:42', '2020-07-08 18:21:36', NULL),
('a6bd17b0-39ff-4cfe-837d-61f17f80c15a', 0, '', 0, '', 'Timed', 'HL Auction 9', 'Approved', 'N', 'N', 'N', 'N', 'N', 'N', '097bc64b-52c4-41a4-a1b6-abed004ff2ab', NULL, NULL, NULL, NULL, NULL, 'Asian Art', 0, 0, '', '{\"PlatformCode\":\"SR\",\"AuctionTypeAndListing\":\"Timed\",\"CategoryName\":\"Asian Art\",\"Private\":true}', '', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'SGD', NULL, 'Automatic', 'Test', 'Test', '', '5758685', 'www.hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'Hotlotz Increment Table', '0.0000', 0, 0, 20, '0.2000', '0.2000', 0, 5, '', '2020-07-07 09:00:00', '2020-09-30 19:30:00', '[]', '[]', '[]', 0, 0, '0.0000', '0.0000', '[]', 0, '[]', '0.0000', '0.0000', '', '', 0, 0, 1, 1, NULL, '2020-07-03 05:21:03', '2020-07-08 18:21:37', NULL),
('b65689cd-43ec-4e66-ac96-6cbc8ceaa79a', 0, '', 0, '', 'Timed', 'HL Auction 2', 'Approved', 'N', 'N', 'N', 'N', 'N', 'N', 'a16dc20a-5167-4a9d-93b1-abe20064f20c', 'hotzlo10030', '{\n    \"AuctionId\": \"a16dc20a-5167-4a9d-93b1-abe20064f20c\",\n    \"LegacyId\": 0,\n    \"AuctionReference\": \"hotzlo10030\",\n    \"Title\": \"HL Auction 2\",\n    \"ClientId\": \"a20b82bf-5262-464d-b961-a7a200c35d3c\",\n    \"AuctionType\": \"Timed\",\n    \"AuctionTypeCode\": 3,\n    \"ClientName\": \"Hotlotz Singapore\",\n    \"ClientUrl\": \"HotLotz\",\n    \"AuctionListings\": [\n        {\n            \"PlatformCode\": \"SR\",\n            \"AuctionTypeAndListing\": \"Timed\",\n            \"CategoryName\": \"Clocks, Watches & Jewellery\",\n            \"Private\": true\n        }\n    ],\n    \"IsApproved\": true,\n    \"IsPublished\": false,\n    \"AuctionStatus\": \"Created\",\n    \"AuctionStatusCode\": 0,\n    \"TimezoneId\": \"Singapore Standard Time\",\n    \"CardRequired\": false,\n    \"Address1\": \"\",\n    \"Address2\": \"\",\n    \"TownCity\": \"\",\n    \"Postcode\": \"\",\n    \"Country\": \"Singapore\",\n    \"CountryCode\": \"SG\",\n    \"Currency\": \"SGD\",\n    \"BuyersPremium\": 0,\n    \"InternetSurchargeRate\": 5,\n    \"VatRate\": 20,\n    \"BuyersPremiumVatRate\": 0.2,\n    \"InternetSurchargeVatRate\": 0.2,\n    \"BuyersPremiumCeiling\": 0,\n    \"InternetSurchargeCeiling\": 0,\n    \"ApprovalType\": \"Automatic\",\n    \"ApprovalTypeCode\": 1,\n    \"ImportantInformation\": \"Quas reprehenderit d\",\n    \"Terms\": \"Dolores rem velit q\",\n    \"ShippingInfo\": \"<p>PACKING, SHIPPING &amp; INSURANCE<\\/p>\\n<p>HotLotz collaborates with professional art handling companies and other specialist shippers to provide cost effective, bespoke packing and domestic and international insured door-to-door shipping.<\\/p>\\n<p>We can provide indicative quotes within 24 hours, on request.<\\/p>\\n<p>Please contact&nbsp;<a href=\\\"mailto:hello@hotlotz.com\\\">hello@hotlotz.com<\\/a>&nbsp;if you would like further information on either service.<\\/p>\",\n    \"TelephoneNumber\": \"45469856754\",\n    \"Website\": \"https:\\/\\/www.rirajygorasymyq.org\",\n    \"Email\": \"nuduzowi@mailinator.com\",\n    \"ConfirmationEmail\": \"sonitygob@mailinator.com\",\n    \"RegistrationEmail\": \"sonitygob@mailinator.com\",\n    \"RequestConfirmationEmail\": true,\n    \"RequestRegistrationEmail\": true,\n    \"IncrementSetName\": \"HotLotz Increment Table\",\n    \"SaleDates\": [],\n    \"ViewingDates\": [],\n    \"TimedStart\": \"\\/Date(1592895600000-0000)\\/\",\n    \"TimedFirstLotEnds\": \"\\/Date(1604260800000-0000)\\/\",\n    \"ReconciliationDeadline\": \"\\/Date(1603627200000-0000)\\/\",\n    \"MinimumDeposit\": 0,\n    \"AutomaticDeposit\": false,\n    \"AutomaticRefund\": false,\n    \"AuctionCardTypes\": [],\n    \"PieceMeal\": false,\n    \"ImplementationType\": \"Self\",\n    \"InventoryReceived\": false,\n    \"SalePushedLive\": false,\n    \"WinnersNotificationNotes\": \"Test\",\n    \"PublishPostSaleResults\": false,\n    \"AutoBidsDisabled\": false,\n    \"InternationalDebitCardFixedFee\": 0,\n    \"InternationalDebitCardPercentageFee\": 0,\n    \"LotCount\": 0,\n    \"ClientsAuctionId\": \"\",\n    \"ImplementationTypeCode\": 2,\n    \"ListingType\": 1,\n    \"GbpExchangeRate\": 0.481498,\n    \"UsdExchangeRate\": 0.74131,\n    \"SequenceNumber\": 0,\n    \"IncrementSetId\": \"e3097e309ab34a88b251a7b0009a0880\",\n    \"RequestPaymentReceivedEmail\": true,\n    \"PaymentReceivedEmail\": \"gifajineri@mailinator.com\",\n    \"BuyItNowEnabled\": false,\n    \"AuctionDateTimeUtc\": \"\\/Date(1604232000000-0000)\\/\",\n    \"DeliveryOffered\": false,\n    \"PortalLotListPreferences\": 0,\n    \"DisablePhoneVerification\": true,\n    \"EndCurrentDisplayUtc\": \"\\/Date(1593529200000-0000)\\/\",\n    \"PrivateListing\": false,\n    \"HammerExcess\": \"\",\n    \"HideVenueAddressForLotLocations\": false\n}', NULL, NULL, NULL, 'Clocks, Watches & Jewellery', 0, 0, '', '{\"PlatformCode\":\"SR\",\"AuctionTypeAndListing\":\"Timed\",\"CategoryName\":\"Clocks, Watches & Jewellery\",\"Private\":true}', '', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'SGD', NULL, 'Automatic', 'Quas reprehenderit d', 'Dolores rem velit q', '', '45469856754', 'https://www.rirajygorasymyq.org', 'nuduzowi@mailinator.com', 'sonitygob@mailinator.com', 'sonitygob@mailinator.com', 'gifajineri@mailinator.com', 'HotLotz Increment Table', '0.0000', 0, 0, 20, '0.2000', '0.2000', 0, 5, '', '2020-06-23 08:00:00', '2020-11-01 20:00:00', '[]', '[]', '[]', 0, 0, '0.0000', '0.0000', '[]', 0, '[]', '0.0000', '0.0000', '', '', 1, 0, 1, 1, NULL, '2020-06-22 06:37:25', '2020-09-06 06:33:11', NULL),
('b78402c6-eccb-4e2c-8c9a-143a37efc1f7', 0, '', 0, '', 'Timed', 'HL Auction 12', 'Approved', 'N', 'N', 'N', 'N', 'N', 'N', 'a021b840-91c8-4cd6-b919-abed0050f941', NULL, NULL, NULL, NULL, NULL, 'Fine Art & Antiques', 0, 0, '', '{\"PlatformCode\":\"SR\",\"AuctionTypeAndListing\":\"Timed\",\"CategoryName\":\"Fine Art & Antiques\",\"Private\":true}', '', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'SGD', NULL, 'Automatic', 'Test', 'Test', '', '35476677', 'www.hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'Hotlotz Increment Table', '0.0000', 0, 0, 20, '0.2000', '0.2000', 0, 5, '', '2020-07-10 08:00:00', '2020-09-30 21:00:00', '[]', '[]', '[]', 0, 0, '0.0000', '0.0000', '[]', 0, '[]', '0.0000', '0.0000', '', '', 1, 0, 1, 1, NULL, '2020-07-03 05:24:47', '2020-07-08 18:21:39', NULL),
('b9d1bcc1-7ac5-47c4-bd66-11b038432168', 0, '', 0, '', 'Timed', 'HL Auction 15', 'Approved', 'N', 'N', 'N', 'N', 'N', 'N', '57d6f4d9-d34f-42b4-a23d-abed0051d831', NULL, NULL, NULL, NULL, NULL, 'Fine Art & Antiques', 0, 0, '', '{\"PlatformCode\":\"SR\",\"AuctionTypeAndListing\":\"Timed\",\"CategoryName\":\"Fine Art & Antiques\",\"Private\":true}', '', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'SGD', NULL, 'Automatic', 'Test', 'Test', '', '346575676', 'www.hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'Hotlotz Increment Table', '0.0000', 0, 0, 20, '0.2000', '0.2000', 0, 5, '', '2020-07-05 14:00:00', '2020-09-30 16:00:00', '[]', '[]', '[]', 0, 0, '0.0000', '0.0000', '[]', 0, '[]', '0.0000', '0.0000', '', '', 0, 0, 1, 1, NULL, '2020-07-03 05:27:57', '2020-07-08 18:21:41', NULL),
('cf7f220f-f225-490d-944b-e66d190e4f84', 0, '', 0, '', 'Timed', 'HL Auction 18', 'Published', 'N', 'N', 'N', 'N', 'N', 'N', '10fda0db-3493-4005-9d8a-abed0052db82', NULL, NULL, NULL, NULL, NULL, 'Collectables', 0, 0, '', '{\"PlatformCode\":\"SR\",\"AuctionTypeAndListing\":\"Timed\",\"CategoryName\":\"Collectables\",\"Private\":true}', '', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'SGD', NULL, 'Automatic', 'test', 'test', '', '9565453', 'www.hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'Hotlotz Increment Table', '0.0000', 0, 0, 20, '0.2000', '0.2000', 0, 5, '', '2020-07-06 12:00:00', '2020-09-30 15:00:00', '[]', '[]', '[]', 0, 0, '0.0000', '0.0000', '[]', 0, '[]', '0.0000', '0.0000', '', '', 0, 0, 1, 1, NULL, '2020-07-03 05:31:37', '2020-09-03 09:55:27', NULL),
('d17d7215-eb18-48d8-b422-49a13cf442da', 0, '', 0, '', 'Timed', 'HL Auction 11', 'Approved', 'N', 'N', 'N', 'N', 'N', 'N', 'a485fd66-c9ed-46bc-8294-abed0050acae', NULL, NULL, NULL, NULL, NULL, 'Clocks, Watches & Jewellery', 0, 0, '', '{\"PlatformCode\":\"SR\",\"AuctionTypeAndListing\":\"Timed\",\"CategoryName\":\"Clocks, Watches & Jewellery\",\"Private\":true}', '', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'SGD', NULL, 'Automatic', 'Test', 'Test', '', '68435342', 'www.hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'Hotlotz Increment Table', '0.0000', 0, 0, 20, '0.2000', '0.2000', 0, 5, '', '2020-07-06 10:00:00', '2020-09-30 14:00:00', '[]', '[]', '[]', 0, 0, '0.0000', '0.0000', '[]', 0, '[]', '0.0000', '0.0000', '', '', 1, 0, 1, 1, NULL, '2020-07-03 05:23:42', '2020-07-08 18:21:43', NULL),
('d6304dda-2dc7-4bf1-b8b4-98f9380c052e', 0, '', 0, '', 'Timed', 'HL Auction 6', 'Approved', 'N', 'N', 'N', 'N', 'N', 'N', '1631f468-96be-4c3d-bd9b-abed004ec7c3', NULL, NULL, NULL, NULL, NULL, 'Collectables', 0, 0, '', '{\"PlatformCode\":\"SR\",\"AuctionTypeAndListing\":\"Timed\",\"CategoryName\":\"Collectables\",\"Private\":true}', '', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'SGD', NULL, 'Automatic', 'Test', 'Test', '', '576867463', 'www.hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'Hotlotz Increment Table', '0.0000', 0, 0, 20, '0.2000', '0.2000', 0, 5, '', '2020-07-05 07:00:00', '2020-09-30 18:00:00', '[]', '[]', '[]', 0, 0, '0.0000', '0.0000', '[]', 0, '[]', '0.0000', '0.0000', '', '', 0, 0, 1, 1, NULL, '2020-07-03 05:16:47', '2020-07-08 18:21:45', NULL),
('e491a41a-01cd-407f-ba83-69d0e484ab9f', 0, '', 0, '', 'Timed', 'HL Auction 20', 'Approved', 'N', 'N', 'N', 'N', 'N', 'N', '6a246195-92f6-4e0f-a339-abed00535df9', NULL, NULL, NULL, NULL, NULL, 'Clocks, Watches & Jewellery', 0, 0, '', '{\"PlatformCode\":\"SR\",\"AuctionTypeAndListing\":\"Timed\",\"CategoryName\":\"Clocks, Watches & Jewellery\",\"Private\":true}', '', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'SGD', NULL, 'Automatic', 'test', 'test', '', '965353522', 'www.hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'hello@hotlotz.com', 'Hotlotz Increment Table', '0.0000', 0, 0, 20, '0.2000', '0.2000', 0, 5, '', '2020-07-05 13:00:00', '2020-09-30 13:30:00', '[]', '[]', '[]', 0, 0, '0.0000', '0.0000', '[]', 0, '[]', '0.0000', '0.0000', '', '', 0, 0, 1, 1, NULL, '2020-07-03 05:33:30', '2020-07-08 18:21:51', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auctions`
--
ALTER TABLE `auctions`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
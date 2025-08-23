-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 23, 2025 at 06:29 AM
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
-- Database: `ample`
--

-- --------------------------------------------------------

--
-- Table structure for table `catagory`
--

CREATE TABLE `catagory` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `created_by` int(2) DEFAULT NULL,
  `update_at` date DEFAULT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `catagory`
--

INSERT INTO `catagory` (`id`, `name`, `description`, `created_by`, `update_at`, `create_at`) VALUES
(1, 'Herbal', 'herbs', 1, NULL, '2023-07-21 12:29:08');

-- --------------------------------------------------------

--
-- Table structure for table `distributor_stocks`
--

CREATE TABLE `distributor_stocks` (
  `id` int(10) UNSIGNED NOT NULL,
  `suppliar_id` int(10) NOT NULL,
  `product_id` varchar(100) NOT NULL,
  `role_id` int(10) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `suppliar_name` varchar(100) NOT NULL,
  `product_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `distributor_stocks`
--

INSERT INTO `distributor_stocks` (`id`, `suppliar_id`, `product_id`, `role_id`, `stock`, `updated_at`, `suppliar_name`, `product_name`) VALUES
(32, 1, '56', 1, 10, '2025-08-22 11:42:31', 'Agus Hendra', 'product 10'),
(33, 46, '56', 0, 1073, '2025-08-22 11:42:31', 'Agus Hendra 2', 'product 10'),
(34, 47, '56', 0, 112, '2025-08-20 12:39:09', 'thomas', 'product 10'),
(35, 48, '56', 0, 0, '2025-08-11 21:06:58', 'lombok', 'product 10'),
(36, 49, '56', 0, 60, '2025-08-12 02:18:03', 'Distributor Cobaan', 'product 10'),
(37, 50, '56', 0, 35, '2025-08-17 17:01:24', 'Agen', 'product 10'),
(38, 51, '56', 2, 1001, '2025-08-18 16:03:22', 'ABC', 'product 10'),
(39, 45, '57', 1, 60, '2025-08-18 15:37:07', 'Agus Hendra', 'produk baru'),
(40, 50, '57', 0, 10, '2025-08-14 17:29:02', 'Agen', 'produk baru'),
(41, 1, '57', 1, 972, '2025-08-22 07:41:34', 'Agus Hendra', 'produk baru'),
(42, 46, '57', 0, 60, '2025-08-18 17:56:57', 'Agus Hendra 2', 'produk baru'),
(43, 55, '56', 2, 10, '2025-08-18 01:31:50', 'TEST ABC', 'product 10'),
(44, 47, '57', 0, 20, '2025-08-20 12:39:09', 'thomas', 'produk baru'),
(45, 60, '56', 0, 1, '2025-08-21 09:33:58', 'agus hendra', 'product 10'),
(46, 60, '57', 0, 1, '2025-08-21 09:33:58', 'agus hendra', 'produk baru'),
(47, 61, '56', 0, 1, '2025-08-21 09:34:11', 'agus hendra', 'product 10'),
(48, 61, '57', 0, 1, '2025-08-21 09:34:11', 'agus hendra', 'produk baru'),
(49, 62, '56', 0, 2, '2025-08-21 09:47:53', 'tESTING 2', 'product 10'),
(50, 63, '56', 0, 10, '2025-08-21 10:03:19', 'Testing 3', 'product 10'),
(51, 64, '56', 0, 10, '2025-08-22 07:03:49', 'Agus Hendra', 'product 10'),
(52, 65, '56', 0, 10, '2025-08-22 07:04:56', 'Agus Hendra', 'product 10'),
(53, 66, '56', 0, 10, '2025-08-22 07:05:02', 'Agus Hendra', 'product 10'),
(54, 67, '56', 0, 10, '2025-08-22 07:05:18', 'Agus Hendra', 'product 10'),
(55, 68, '56', 0, 2, '2025-08-22 07:22:36', 'Agus Hendra', 'product 10'),
(56, 69, '56', 0, 2, '2025-08-22 07:22:45', 'Agus Hendra', 'product 10'),
(57, 70, '56', 0, 2, '2025-08-22 07:24:06', 'Agus Hendra', 'product 10'),
(58, 71, '57', 0, 2, '2025-08-22 07:25:17', 'Testingg', 'produk baru'),
(59, 72, '57', 0, 2, '2025-08-22 07:25:38', 'Testingg', 'produk baru'),
(60, 73, '56', 0, 21, '2025-08-22 07:38:23', 'ABC', 'product 10'),
(61, 75, '56', 0, 2, '2025-08-22 07:40:22', 'ABC', 'product 10'),
(62, 76, '57', 0, 2, '2025-08-22 07:41:34', 'woiii', 'produk baru');

-- --------------------------------------------------------

--
-- Table structure for table `expense`
--

CREATE TABLE `expense` (
  `id` int(11) NOT NULL,
  `ex_date` date NOT NULL,
  `expense_for` varchar(50) NOT NULL,
  `amount` float(15,2) NOT NULL DEFAULT 0.00,
  `expense_cat` int(10) NOT NULL,
  `ex_description` text NOT NULL,
  `added_by` int(4) DEFAULT NULL,
  `added_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_catagory`
--

CREATE TABLE `expense_catagory` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `added_by` int(4) NOT NULL,
  `added_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `factory_products`
--

CREATE TABLE `factory_products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_id` varchar(50) NOT NULL,
  `brand_name` varchar(50) NOT NULL,
  `catagory_id` int(11) NOT NULL,
  `catagory_name` varchar(100) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `quantity` int(10) NOT NULL,
  `alert_quantity` int(4) NOT NULL,
  `product_expense` float(15,2) NOT NULL DEFAULT 0.00,
  `sell_price` float(15,2) NOT NULL DEFAULT 0.00,
  `added_by` int(4) NOT NULL,
  `last_update_at` date NOT NULL,
  `added_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL,
  `invoice_number` varchar(100) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(50) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `net_total` float(15,2) NOT NULL DEFAULT 0.00,
  `return_status` tinyint(1) NOT NULL DEFAULT 0,
  `last_update` date DEFAULT NULL,
  `suppliar_id` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`id`, `invoice_number`, `customer_id`, `customer_name`, `order_date`, `net_total`, `return_status`, `last_update`, `suppliar_id`) VALUES
(22, 'INV-68973523690D7', 0, 'Penjualan Pribadi', '2025-08-09', 1200000.00, 0, '2025-08-09', 46),
(23, 'INV-6897398C6026B', 0, 'Penjualan Pribadi', '2025-08-09', 1200000.00, 0, '2025-08-09', 46),
(24, 'INV-68975E5AE31A6', 0, 'Penjualan Pribadi', '2025-08-09', 120000.00, 0, '2025-08-09', 46),
(25, 'INV-689832812A43A', 0, 'Penjualan Pribadi', '2025-08-10', 110000.00, 0, '2025-08-10', 46),
(26, 'INV-689A5148F338B', 0, 'Penjualan Pribadi', '2025-08-12', 100000.00, 0, '2025-08-12', 45),
(27, 'INV-689A620424FEC', 0, 'Penjualan Pribadi', '2025-08-12', 110000.00, 0, '2025-08-12', 49),
(28, 'INV-689A9F90B7506', 50, 'Agen', '2025-08-12', 110000.00, 0, '2025-08-12', 49),
(29, 'INV-689AA0CD27628', 0, 'Penjualan Pribadi', '2025-08-12', 75000.00, 0, '2025-08-12', 50),
(30, 'INV-689AA2B165288', 50, 'Agen', '2025-08-12', 110000.00, 0, '2025-08-12', 49),
(34, 'INV-689AA45BEA975', 50, 'Agen', '2025-08-12', 110000.00, 0, '2025-08-12', 49),
(35, 'INV-689AA4DA4E7B2', 0, 'Penjualan Pribadi', '2025-08-12', 150000.00, 0, '2025-08-12', 50),
(36, 'INV-689E1CDE587E8', 50, 'Agen', '2025-08-14', 260000.00, 0, '2025-08-14', 45),
(41, 'INV-689E2429BD26B', 0, 'Penjualan Pribadi', '2025-08-14', 280000.00, 0, '2025-08-14', 45),
(43, 'INV-68A20AE42033A', 50, 'Agen', '2025-08-17', 130000.00, 0, '2025-08-17', 46),
(44, 'INV-68A2410DA5199', 47, 'thomas', '2025-08-18', 100000.00, 0, '2025-08-18', 57),
(45, 'INV-68A24285113CD', 46, 'Agus Hendra 2', '2025-08-18', 230000.00, 0, '2025-08-18', 57),
(46, 'INV-68A245F27B354', 46, 'Agus Hendra 2', '2025-08-18', 110000.00, 0, '2025-08-18', 57),
(47, 'INV-68A24AD2956ED', 46, 'Agus Hendra 2', '2025-08-18', 11000.00, 0, '2025-08-18', 57),
(48, 'INV-68A24E36AB4D7', 47, 'thomas', '2025-08-18', 100000.00, 0, '2025-08-18', 57),
(50, 'INV-68A24E6C34E1F', 47, 'thomas', '2025-08-18', 10000.00, 0, '2025-08-18', 57),
(51, 'INV-68A29AB512699', 47, 'thomas', '2025-08-18', 10000.00, 0, '2025-08-18', 57),
(52, 'INV-68A30DF5DC518', 47, 'thomas', '2025-08-18', 10000.00, 0, '2025-08-18', 57),
(54, 'INV-68A348A30D823', 47, 'thomas', '2025-08-18', 100000.00, 0, '2025-08-18', 45),
(55, 'INV-68A34ECAEFBE1', 51, 'ABC', '2025-08-18', 10000.00, 0, '2025-08-18', 45),
(60, 'INV-68A52C4A94666', 46, 'Agus Hendra 2', '2025-08-20', 11000.00, 0, '2025-08-20', 1),
(62, 'INV-68A52DFB41303', 0, 'Penjualan Pribadi', '2025-08-20', 15000.00, 0, '2025-08-20', 47),
(63, 'INV-68A52E963A4AD', 0, 'Penjualan Pribadi', '2025-08-20', 140000.00, 0, '2025-08-20', 47),
(64, 'INV-68A6E80617CC5', 60, 'agus hendra', '2025-08-21', 29000.00, 0, '2025-08-21', 1),
(65, 'INV-68A6E81300CAE', 61, 'agus hendra', '2025-08-21', 29000.00, 0, '2025-08-21', 1),
(66, 'INV-68A6EB49050AD', 62, 'tESTING 2', '2025-08-21', 30000.00, 0, '2025-08-21', 1),
(67, 'INV-68A6EEE7DE9AE', 63, 'Testing 3', '2025-08-21', 150000.00, 0, '2025-08-21', 1),
(68, 'INV-68A81655EDE6B', 64, 'Agus Hendra', '2025-08-22', 150000.00, 0, '2025-08-22', 1),
(69, 'INV-68A81698798B6', 65, 'Agus Hendra', '2025-08-22', 150000.00, 0, '2025-08-22', 1),
(70, 'INV-68A8169EB0DBC', 66, 'Agus Hendra', '2025-08-22', 150000.00, 0, '2025-08-22', 1),
(71, 'INV-68A816AE89D50', 67, 'Agus Hendra', '2025-08-22', 150000.00, 0, '2025-08-22', 1),
(72, 'INV-68A81ABC4C9B8', 68, 'Agus Hendra', '2025-08-22', 30000.00, 0, '2025-08-22', 1),
(73, 'INV-68A81AC589908', 69, 'Agus Hendra', '2025-08-22', 30000.00, 0, '2025-08-22', 1),
(74, 'INV-68A81B16E9C77', 70, 'Agus Hendra', '2025-08-22', 30000.00, 0, '2025-08-22', 1),
(75, 'INV-68A81B5D13136', 71, 'Testingg', '2025-08-22', 28000.00, 0, '2025-08-22', 1),
(76, 'INV-68A81B72C2B22', 72, 'Testingg', '2025-08-22', 28000.00, 0, '2025-08-22', 1),
(77, 'INV-68A81E6F6F1C1', 73, 'ABC', '2025-08-22', 315000.00, 0, '2025-08-22', 1),
(79, 'INV-68A81EE6690AD', 75, 'ABC', '2025-08-22', 30000.00, 0, '2025-08-22', 1),
(80, 'INV-68A81F2EB0984', 76, 'woiii', '2025-08-22', 28000.00, 0, '2025-08-22', 1),
(81, 'INV-68A857A786E02', 46, 'Agus Hendra 2', '2025-08-22', 11000.00, 0, '2025-08-22', 1);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_details`
--

CREATE TABLE `invoice_details` (
  `id` int(11) NOT NULL,
  `invoice_no` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `price` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `invoice_details`
--

INSERT INTO `invoice_details` (`id`, `invoice_no`, `pid`, `product_name`, `price`, `quantity`) VALUES
(22, 22, 56, 'product 10', '12000', 100),
(23, 23, 56, 'product 10', '12000', 100),
(24, 24, 56, 'product 10', '12000', 10),
(25, 25, 56, 'product 10', '11000', 10),
(26, 26, 56, 'product 10', '10000', 10),
(27, 27, 56, 'product 10', '11000', 10),
(28, 28, 56, 'product 10', '11000', 10),
(29, 29, 56, 'product 10', '13000', 5),
(30, 30, 56, 'product 10', '11000', 10),
(34, 34, 56, 'product 10', '11000', 10),
(35, 35, 56, 'product 10', '13000', 10),
(36, 36, 56, 'product 10', '13000', 10),
(37, 36, 57, 'produk baru', '13000', 10),
(38, 41, 57, 'produk baru', '14000', 10),
(39, 41, 57, 'produk baru', '14000', 10),
(41, 43, 56, 'product 10', '13000', 10),
(42, 44, 56, 'product 10', '10000', 10),
(43, 45, 56, 'product 10', '11000', 10),
(44, 45, 57, 'produk baru', '12000', 10),
(45, 46, 56, 'product 10', '11000', 10),
(46, 47, 56, 'product 10', '11000', 1),
(47, 48, 56, 'product 10', '10000', 10),
(48, 50, 56, 'product 10', '10000', 1),
(49, 51, 56, 'product 10', '10000', 1),
(50, 52, 56, 'product 10', '10000', 1),
(51, 54, 57, 'produk baru', '10000', 10),
(52, 55, 56, 'product 10', '10000', 1),
(53, 60, 56, 'product 10', '11000', 1),
(54, 62, 56, 'product 10', '15000', 1),
(55, 63, 57, 'produk baru', '14000', 10),
(56, 64, 56, 'product 10', '15000', 1),
(57, 64, 57, 'produk baru', '14000', 1),
(58, 65, 56, 'product 10', '15000', 1),
(59, 65, 57, 'produk baru', '14000', 1),
(60, 66, 56, 'product 10', '15000', 2),
(61, 67, 56, 'product 10', '15000', 10),
(62, 68, 56, 'product 10', '15000', 10),
(63, 69, 56, 'product 10', '15000', 10),
(64, 70, 56, 'product 10', '15000', 10),
(65, 71, 56, 'product 10', '15000', 10),
(66, 72, 56, 'product 10', '15000', 2),
(67, 73, 56, 'product 10', '15000', 2),
(68, 74, 56, 'product 10', '15000', 2),
(69, 75, 57, 'produk baru', '14000', 2),
(70, 76, 57, 'produk baru', '14000', 2),
(71, 77, 56, 'product 10', '15000', 21),
(72, 79, 56, 'product 10', '15000', 2),
(73, 80, 57, 'produk baru', '14000', 2),
(74, 81, 56, 'product 10', '11000', 1);

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `id` int(11) NOT NULL,
  `member_id` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `con_num` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `total_buy` float(15,2) NOT NULL DEFAULT 0.00,
  `total_paid` float(15,2) NOT NULL DEFAULT 0.00,
  `total_due` float(15,2) NOT NULL DEFAULT 0.00,
  `reg_date` date NOT NULL,
  `update_by` int(8) DEFAULT NULL,
  `update_at` date DEFAULT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `distributor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`id`, `member_id`, `name`, `company`, `address`, `con_num`, `email`, `total_buy`, `total_paid`, `total_due`, `reg_date`, `update_by`, `update_at`, `create_at`, `distributor_id`) VALUES
(1, 'C1689940620', 'Agus Hendra', 'Agent 1', 'palmerah haji senin', '082110582597', 'agushendra29@gmail.com', 19000.00, 19000.00, 0.00, '2023-07-21', 1, '2025-07-10', '2023-07-21 11:57:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `publish_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `category`, `content`, `publish_date`, `created_at`, `updated_at`) VALUES
(1, 'testing 2', 'testing1', 'testinggg', '2025-02-08', '2025-08-09 14:27:52', '2025-08-19 07:29:12'),
(2, 'testing 2', 'testing 2', 'woiiiii', '2025-02-01', '2025-08-09 14:28:36', '2025-08-09 15:58:36');

-- --------------------------------------------------------

--
-- Table structure for table `paymethode`
--

CREATE TABLE `paymethode` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `added_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `paymethode`
--

INSERT INTO `paymethode` (`id`, `name`, `added_by`, `added_time`) VALUES
(1, 'PhonePe', NULL, '2023-06-27 04:28:58'),
(2, 'Gpay', NULL, '2023-06-27 04:29:29'),
(3, 'Bank Transfer', NULL, '2023-06-27 04:29:29'),
(4, 'Credit Card', NULL, '2023-06-27 04:30:08'),
(5, 'Debit Card', NULL, '2023-06-27 04:30:08');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_id` varchar(50) DEFAULT NULL,
  `catagory_id` int(10) NOT NULL,
  `sell_price_hd` varchar(10) DEFAULT NULL,
  `sell_price_d` varchar(10) DEFAULT NULL,
  `added_by` int(4) DEFAULT NULL,
  `last_update_at` date NOT NULL,
  `added_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `sell_price_a` varchar(20) NOT NULL,
  `sell_price_r` varchar(20) NOT NULL,
  `updated_by` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `product_id`, `catagory_id`, `sell_price_hd`, `sell_price_d`, `added_by`, `last_update_at`, `added_time`, `sell_price_a`, `sell_price_r`, `updated_by`) VALUES
(56, 'product 10', NULL, 1, '10000', '11000', 23, '2025-08-18', '2025-08-08 20:53:18', '13000', '15000', 45),
(57, 'produk baru', NULL, 1, '10000', '12000', 25, '0000-00-00', '2025-08-14 12:33:26', '13000', '14000', 0);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` int(11) UNSIGNED NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `product_id` int(255) NOT NULL,
  `quantity` int(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_reason` text DEFAULT NULL,
  `suppliar_id` int(10) NOT NULL,
  `invoice_number` varchar(100) NOT NULL,
  `po_id` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `status`, `total_amount`, `product_id`, `quantity`, `created_at`, `approved_at`, `rejected_reason`, `suppliar_id`, `invoice_number`, `po_id`) VALUES
(20, 'approved', 1000000.00, 56, 100, '2025-08-09 10:10:58', '2025-08-09 11:44:20', NULL, 46, 'INV-68973494AFB7A', '0'),
(21, 'approved', 10000000.00, 56, 1000, '2025-08-09 13:07:16', '2025-08-09 14:39:45', NULL, 46, 'INV-68975DB1211B5', '0'),
(22, 'approved', 1000000.00, 56, 100, '2025-08-10 07:23:41', '2025-08-10 09:06:09', NULL, 47, 'INV-6898610107E1F', '0'),
(23, 'approved', 10000000.00, 56, 1000, '2025-08-11 02:40:51', '2025-08-11 04:18:47', NULL, 48, 'INV-68996F273F432', '0'),
(24, 'approved', 1000000.00, 56, 100, '2025-08-11 19:43:24', '2025-08-11 21:22:50', NULL, 49, 'INV-689A5F2A63B8F', '0'),
(25, 'approved', 100000.00, 57, 10, '2025-08-17 02:37:51', '2025-08-18 02:19:20', NULL, 46, 'INV-68A28DA89F345', '0'),
(26, 'pending', 100000.00, 56, 10, '2025-08-20 10:47:09', NULL, NULL, 47, '-', '0'),
(27, 'pending', 100000.00, 56, 10, '2025-08-20 10:47:19', NULL, NULL, 47, '-', '0'),
(28, 'pending', 100000.00, 57, 10, '2025-08-20 10:47:19', NULL, NULL, 47, '-', '0'),
(29, 'approved', 100000.00, 56, 10, '2025-08-20 10:48:31', '2025-08-20 12:39:09', NULL, 47, 'INV-68A5C1ED816DC', 'PO_68a5bd17'),
(30, 'approved', 100000.00, 57, 10, '2025-08-20 10:48:31', '2025-08-20 12:39:09', NULL, 47, 'INV-68A5C1ED816DC', 'PO_68a5bd17'),
(32, 'approved', 100000.00, 56, 10, '2025-08-20 10:53:46', '2025-08-20 12:38:52', NULL, 47, 'INV-68A5C1DC04515', 'PO_68a5be52'),
(33, 'approved', 100000.00, 57, 10, '2025-08-20 10:53:46', '2025-08-20 12:38:52', NULL, 47, 'INV-68A5C1DC04515', 'PO_68a5be52');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `id` int(11) NOT NULL,
  `purchase_order_id` int(11) DEFAULT NULL,
  `product_id` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_order_items`
--

INSERT INTO `purchase_order_items` (`id`, `purchase_order_id`, `product_id`, `quantity`) VALUES
(4, 4, '8', 1000),
(5, 5, '8', 100);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_payment`
--

CREATE TABLE `purchase_payment` (
  `id` int(11) NOT NULL,
  `suppliar_id` int(11) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_amount` float(15,2) NOT NULL DEFAULT 0.00,
  `payment_type` varchar(20) DEFAULT NULL,
  `pay_description` text NOT NULL,
  `added_by` int(4) DEFAULT NULL,
  `last_update` date DEFAULT NULL,
  `added_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `purchase_payment`
--

INSERT INTO `purchase_payment` (`id`, `suppliar_id`, `payment_date`, `payment_amount`, `payment_type`, `pay_description`, `added_by`, `last_update`, `added_time`) VALUES
(4, 6, '2025-07-23', 1000000.00, 'Bank Transfer', '', 1, NULL, '2025-07-20 02:51:49');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_products`
--

CREATE TABLE `purchase_products` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `purchase_suppliar` int(11) DEFAULT NULL,
  `suppliar_name` varchar(255) DEFAULT NULL,
  `prev_quantity` int(11) DEFAULT NULL,
  `purchase_quantity` int(11) DEFAULT NULL,
  `purchase_price` float(15,2) DEFAULT 0.00,
  `purchase_sell_price` float(15,2) DEFAULT 0.00,
  `purchase_subtotal` float(15,2) DEFAULT 0.00,
  `prev_total_due` float(15,2) DEFAULT 0.00,
  `purchase_net_total` float(15,2) DEFAULT 0.00,
  `purchase_paid_bill` float(15,2) DEFAULT 0.00,
  `purchase_due_bill` float(15,2) DEFAULT 0.00,
  `purchase_pamyent_by` varchar(20) DEFAULT NULL,
  `return_status` varchar(50) NOT NULL DEFAULT 'no',
  `added_by` int(4) DEFAULT NULL,
  `added_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return`
--

CREATE TABLE `purchase_return` (
  `id` int(11) NOT NULL,
  `sell_id` int(11) DEFAULT NULL,
  `suppliar_id` int(11) DEFAULT NULL,
  `suppliar_name` varchar(50) NOT NULL,
  `return_date` date NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `return_quantity` int(11) NOT NULL,
  `subtotal` float(15,2) NOT NULL DEFAULT 0.00,
  `discount` float(15,2) NOT NULL DEFAULT 0.00,
  `netTotal` float(15,2) NOT NULL DEFAULT 0.00,
  `create_by` int(4) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rewards`
--

CREATE TABLE `rewards` (
  `id` int(11) NOT NULL,
  `nama_reward` varchar(255) NOT NULL,
  `periode_hadiah_dari` date NOT NULL,
  `periode_hadiah_sampai` date NOT NULL,
  `role_id` int(11) NOT NULL,
  `jumlah_point` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `max_redeem` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rewards`
--

INSERT INTO `rewards` (`id`, `nama_reward`, `periode_hadiah_dari`, `periode_hadiah_sampai`, `role_id`, `jumlah_point`, `created_at`, `max_redeem`) VALUES
(1, 'Mobil Avanza 2019', '2025-08-28', '2025-09-28', 3, 10000, '2025-08-12 02:32:35', 0),
(2, 'Testing 1', '2025-02-08', '2025-02-08', 3, 1000, '2025-08-12 04:40:29', 0),
(3, 'Mobil Avanza 2019', '2025-02-01', '2025-02-01', 3, 1000, '2025-08-12 05:05:45', 0),
(4, 'Woi', '2025-02-08', '2026-02-08', 5, 1000, '2025-08-15 01:29:04', 0);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `level`, `name`) VALUES
(1, 1, 'HO'),
(2, 2, 'Head Distributor'),
(3, 3, 'Distributor'),
(4, 4, 'Agen'),
(5, 5, 'Reseller'),
(10, 10, 'Super Admin');

-- --------------------------------------------------------

--
-- Table structure for table `sell_payment`
--

CREATE TABLE `sell_payment` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `payment_date` date NOT NULL,
  `payment_amount` float(15,2) NOT NULL DEFAULT 0.00,
  `payment_type` varchar(20) NOT NULL,
  `pay_description` text NOT NULL,
  `added_by` int(4) NOT NULL,
  `last_update` date DEFAULT NULL,
  `added_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sell_return`
--

CREATE TABLE `sell_return` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `amount` float(15,2) NOT NULL DEFAULT 0.00,
  `added_by` int(11) DEFAULT NULL,
  `added_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `designation` varchar(50) DEFAULT NULL,
  `con_no` varchar(15) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `added_by` int(4) DEFAULT NULL,
  `added_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `name`, `designation`, `con_no`, `email`, `address`, `added_by`, `added_time`) VALUES
(1, 'Staff 1', 'Manager', '0821081230813', 'destination1@gmail.com', 'tempel', 0, '2023-07-21 12:36:40');

-- --------------------------------------------------------

--
-- Table structure for table `stock_logs`
--

CREATE TABLE `stock_logs` (
  `id` int(11) NOT NULL,
  `suppliar_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `action_type` enum('add','edit') NOT NULL,
  `old_quantity` int(11) DEFAULT NULL,
  `new_quantity` int(11) NOT NULL,
  `changed_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_logs`
--

INSERT INTO `stock_logs` (`id`, `suppliar_id`, `product_id`, `action_type`, `old_quantity`, `new_quantity`, `changed_by`, `created_at`, `note`) VALUES
(1, 45, 56, 'add', NULL, 1000, 26, '2025-08-09 19:42:27', 'Initial stock added'),
(2, 45, 56, 'add', 0, 50, 25, '2025-08-10 09:38:09', 'Stock added'),
(3, 45, 56, 'add', 50, 150, 25, '2025-08-10 09:38:28', 'Stock added'),
(4, 45, 56, '', 150, 140, 25, '2025-08-10 09:38:52', 'Stock reduced'),
(5, 45, 56, 'add', 140, 150, 25, '2025-08-10 09:40:53', 'Stock added'),
(6, 45, 56, '', 150, 130, 25, '2025-08-10 09:42:17', 'Stock reduced'),
(7, 46, 56, 'add', 990, 1090, 25, '2025-08-10 09:42:45', 'Stock added'),
(8, 45, 56, 'add', 80, 1080, 25, '2025-08-11 11:11:55', 'Stock added'),
(9, 48, 56, '', 1000, 0, 25, '2025-08-12 04:06:58', 'Stock reduced'),
(10, 45, 56, 'add', 70, 100, 25, '2025-08-12 04:14:08', 'Stock added'),
(11, 51, 56, 'add', NULL, 1000, 25, '2025-08-12 09:59:23', 'Initial stock added'),
(12, 45, 56, 'add', 0, 10, 25, '2025-08-14 02:47:56', 'Stock added'),
(13, 45, 57, 'add', NULL, 100, 25, '2025-08-14 19:34:40', 'Initial stock added'),
(14, 1, 57, 'add', NULL, 10, 25, '2025-08-18 00:27:00', 'Initial stock added'),
(15, 55, 56, 'add', NULL, 10, 35, '2025-08-18 08:31:50', 'Initial stock added'),
(16, 1, 57, 'add', 0, 10, 35, '2025-08-18 09:18:43', 'Stock added'),
(17, 1, 56, 'add', 57, 107, 35, '2025-08-18 16:49:25', 'Stock added'),
(18, 1, 56, '', 105, 115, 25, '2025-08-19 00:55:03', 'Stock updated'),
(19, 46, 57, '', 20, 30, 25, '2025-08-19 00:56:49', 'Stock updated'),
(20, 46, 57, '', 30, 40, 25, '2025-08-19 00:56:55', 'Stock updated'),
(21, 46, 57, '', 40, 50, 25, '2025-08-19 00:56:57', 'Stock updated'),
(22, 46, 57, '', 50, 60, 25, '2025-08-19 00:56:57', 'Stock updated'),
(23, 1, 56, '', 115, 125, 25, '2025-08-19 00:57:26', 'Stock updated'),
(24, 1, 56, '', 125, 115, 25, '2025-08-19 01:19:10', 'Stock reduced'),
(25, 1, 57, 'add', 0, 1000, 25, '2025-08-20 19:38:46', 'Stock updated');

-- --------------------------------------------------------

--
-- Table structure for table `suppliar`
--

CREATE TABLE `suppliar` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `nik` varchar(20) NOT NULL,
  `bank` varchar(20) NOT NULL,
  `rekening` varchar(20) NOT NULL,
  `company` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `con_num` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `update_at` date DEFAULT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role_id` int(11) NOT NULL,
  `address_ktp` varchar(250) NOT NULL,
  `total_point` int(100) NOT NULL,
  `suppliar_code` varchar(15) NOT NULL,
  `is_active` int(11) NOT NULL,
  `nama_rekening` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `suppliar`
--

INSERT INTO `suppliar` (`id`, `parent_id`, `name`, `nik`, `bank`, `rekening`, `company`, `address`, `con_num`, `email`, `date_of_birth`, `update_by`, `update_at`, `create_at`, `role_id`, `address_ktp`, `total_point`, `suppliar_code`, `is_active`, `nama_rekening`) VALUES
(1, NULL, 'Agus Hendra', '2100913103', 'BCA', '5271321857', NULL, 'kost 10zaa', '082110582597', 'agushendra28@gmail.com', '2025-08-28', 23, '2025-08-20', '2025-08-09 11:16:27', 10, 'Jl. H. Sennin Blok H Senin No.10 10, RT.10/RW.12, Palmerah, Kec. Palmerah, Kota Jakarta Barat', 143, '000001', 1, 'woiiilahhhadadada'),
(46, 47, 'Agus Hendra 2', '21091041901950', 'BCA', '591849128918', NULL, 'kost 10z', '08130130', 'agushendra29@gmail.com', '2025-09-28', 23, '2025-08-20', '2025-08-09 11:26:57', 3, 'Jl. H. Sennin Blok H Senin No.10 10, RT.10/RW.12, Palmerah, Kec. Palmerah, Kota Jakarta Barat', 130, '', 0, 'BANK'),
(47, NULL, 'thomas', '123456789', 'BCA', '123456789', NULL, 'bla bla bla', '08123456789', '111@gmail.com', '1986-04-01', 25, '2025-08-19', '2025-08-10 08:33:54', 2, 'bla bla bla', 11, '000002', 1, 'Thomas'),
(48, 1, 'lombok', '2109103913', 'BCA', '52713291839', NULL, 'jalan lombok', '08113091391', 'lombok@gmail.com', '2025-08-28', 25, NULL, '2025-08-11 04:00:08', 3, 'jalan lombok', 0, '', 0, ''),
(49, 51, 'Distributor Cobaan', '2109013130193', 'BCA', '5271321857', NULL, 'kost 10z', '082110582597', 'distributor1@gmail.com', '2025-08-28', 25, NULL, '2025-08-11 21:10:58', 3, 'Jl. H. Sennin Blok H Senin No.10 10, RT.10/RW.12, Palmerah, Kec. Palmerah, Kota Jakarta Barat', 40, '', 0, ''),
(50, NULL, 'Agen', '210913901930109', 'BCA', '5029510951059', NULL, 'kost 10z', '1230193010193019', 'agen29@gmail.com', '2025-08-28', 25, NULL, '2025-08-12 01:38:07', 4, 'Jl. H. Sennin Blok H Senin No.10 10, RT.10/RW.12, Palmerah, Kec. Palmerah, Kota Jakarta Barat', 15, '', 0, ''),
(51, NULL, 'ABC', '501910391', 'BCA', '5271328138183', NULL, 'kost 10z', '080193019310', 'abc@gmail.com', '2025-08-12', 25, NULL, '2025-08-12 02:58:54', 2, 'Jl. H. Sennin Blok H Senin No.10 10, RT.10/RW.12, Palmerah, Kec. Palmerah, Kota Jakarta Barat', 0, '', 0, ''),
(52, NULL, 'fandy gobloj', '21039120393', 'BRI', '23120319301', NULL, 'abcdefghijk', '0801930193019313', 'abc@gmail.com', '1999-07-29', 25, NULL, '2025-08-17 02:29:53', 2, 'abcdefghijk', 0, '', 0, ''),
(53, NULL, 'Abc', '20391039103910312321', 'BCA', '1031903190391', NULL, 'kost 10z', '090139019313', 'agushendra29@gmail.com', '2025-08-28', 25, NULL, '2025-08-17 02:35:15', 2, 'Jl. H. Sennin Blok H Senin No.10 10, RT.10/RW.12, Palmerah, Kec. Palmerah, Kota Jakarta Barat', 0, '', 1, ''),
(54, NULL, 'TEST ABC', '2019310310393456', 'BRI', '01930193109313', NULL, 'testtinggg', '09103910391039', 'TESTABC@GMAIL.COM', '2025-08-28', 25, NULL, '2025-08-17 02:54:24', 2, 'testinggg', 0, '', 1, ''),
(55, 1, 'TEST ABC', '2019310310393456', 'BRI', '01930193109313', NULL, 'testtinggg', '09103910391039', 'TESTABC@GMAIL.COM', '2025-08-28', 25, NULL, '2025-08-17 02:54:57', 2, 'testinggg', 0, '', 0, ''),
(56, NULL, 'Agus Hendra', '2109104190195056', 'BCA', '50124014', NULL, 'kost 10z', '01930139103', 'agushendra29@gmail.com', '2025-08-28', 25, NULL, '2025-08-17 17:36:27', 3, 'Jl. H. Sennin Blok H Senin No.10 10, RT.10/RW.12, Palmerah, Kec. Palmerah, Kota Jakarta Barat', 0, '', 1, ''),
(57, 47, 'Agus Hendra', '2109104190195056', 'BCA', '4019019401940149', NULL, 'kost 10z', '01301390193', 'agushendra29@gmail.com', '2025-08-28', 25, '2025-08-18', '2025-08-17 17:40:27', 3, 'Jl. H. Sennin Blok H Senin No.10 10, RT.10/RW.12, Palmerah, Kec. Palmerah, Kota Jakarta Barat', 54, '000057', 1, 'woiii'),
(58, 47, 'Test 123', '2103910391311111', 'BCA', '041039103913', NULL, 'testtt', '0910391039', 'test@gmail.com', '1995-02-08', 25, NULL, '2025-08-18 17:22:02', 3, 'testt lah', 0, '000058', 0, 'Test 123'),
(60, NULL, 'agus hendra', '1234567890123456', 'BCA', '01391039103', NULL, 'no 10z', '090193103', 'agushendra29@gmail.com', '2025-08-22', 25, NULL, '2025-08-21 09:33:58', 5, 'haji senin', 0, '000060', 1, 'Head Office'),
(61, NULL, 'agus hendra', '1234567890123456', 'BCA', '01391039103', NULL, 'no 10z', '090193103', 'agushendra29@gmail.com', '2025-08-22', 25, NULL, '2025-08-21 09:34:10', 5, 'haji senin', 0, '000061', 1, 'Head Office'),
(62, NULL, 'tESTING 2', '2109120319311111', 'BCA', '52901931313', NULL, 'kost 10z', '08001309130', 'agushendra29@gmail.com', '2025-08-28', 25, NULL, '2025-08-21 09:47:53', 5, 'Jl. H. Sennin Blok H Senin No.10 10, RT.10/RW.12, Palmerah, Kec. Palmerah, Kota Jakarta Barat', 0, '000062', 1, 'Head Office 20'),
(63, NULL, 'Testing 3', '1234567890123456', 'BCA', '12303091031203', NULL, 'woiii', '09012931039', 'testing5@gmail.com', '2025-02-08', 25, NULL, '2025-08-21 10:03:19', 5, 'woiii', 0, '000063', 1, '50913013013'),
(64, NULL, 'Agus Hendra', '2109120319311111', 'BCA', '1031930139', NULL, 'kost 10z', '01302193019', 'agushendra29@gmail.com', '2025-08-21', 25, NULL, '2025-08-22 07:03:49', 5, 'Jl. H. Sennin Blok H Senin No.10 10, RT.10/RW.12, Palmerah, Kec. Palmerah, Kota Jakarta Barat', 10, '000064', 1, 'woiii'),
(65, NULL, 'Agus Hendra', '1234567890123456', 'BRI', '081039103103', NULL, 'kost 10z', '0912301930193', 'agushendra29@gmail.com', '2005-08-28', 25, NULL, '2025-08-22 07:04:56', 5, 'Jl. H. Sennin Blok H Senin No.10 10, RT.10/RW.12, Palmerah, Kec. Palmerah, Kota Jakarta Barat', 10, '000065', 1, 'AHUAHDUH'),
(66, NULL, 'Agus Hendra', '1234567890123456', 'BRI', '081039103103', NULL, 'kost 10z', '0912301930193', 'agushendra29@gmail.com', '2005-08-28', 25, NULL, '2025-08-22 07:05:02', 5, 'Jl. H. Sennin Blok H Senin No.10 10, RT.10/RW.12, Palmerah, Kec. Palmerah, Kota Jakarta Barat', 10, '000066', 1, 'AHUAHDUH'),
(67, NULL, 'Agus Hendra', '1234567890123456', 'BRI', '081039103103', NULL, 'kost 10z', '0912301930193', 'agushendra29@gmail.com', '2005-08-28', 25, NULL, '2025-08-22 07:05:18', 5, 'Jl. H. Sennin Blok H Senin No.10 10, RT.10/RW.12, Palmerah, Kec. Palmerah, Kota Jakarta Barat', 10, '000067', 1, 'AHUAHDUH'),
(68, NULL, 'Agus Hendra', '1234567889012356', 'BCA', '091038103913', NULL, 'kost 10z', '09123103091', 'agushendra29@gmail.com', '2005-08-29', 25, NULL, '2025-08-22 07:22:36', 5, 'Jl. H. Sennin Blok H Senin No.10 10, RT.10/RW.12, Palmerah, Kec. Palmerah, Kota Jakarta Barat', 2, '000068', 1, 'Head Office'),
(69, NULL, 'Agus Hendra', '1234567889012356', 'BCA', '091038103913', NULL, 'kost 10z', '09123103091', 'agushendra29@gmail.com', '2005-08-29', 25, NULL, '2025-08-22 07:22:45', 5, 'Jl. H. Sennin Blok H Senin No.10 10, RT.10/RW.12, Palmerah, Kec. Palmerah, Kota Jakarta Barat', 2, '000069', 1, 'Head Office'),
(70, NULL, 'Agus Hendra', '1234567889012356', 'BCA', '091038103913', NULL, 'kost 10z', '09123103091', 'agushendra29@gmail.com', '2005-08-29', 25, NULL, '2025-08-22 07:24:06', 5, 'Jl. H. Sennin Blok H Senin No.10 10, RT.10/RW.12, Palmerah, Kec. Palmerah, Kota Jakarta Barat', 2, '000070', 1, 'Head Office'),
(71, NULL, 'Testingg', '1234567890123456', 'BCA', '019301930193', NULL, 'kost 10z', '0193039023', 'agushendra29@gmail.com', '2025-08-08', 25, NULL, '2025-08-22 07:25:17', 5, 'Jl. H. Sennin Blok H Senin No.10 10, RT.10/RW.12, Palmerah, Kec. Palmerah, Kota Jakarta Barat', 2, '000071', 1, 'aksdakdadk'),
(72, NULL, 'Testingg', '1234567890123456', 'BCA', '019301930193', NULL, 'kost 10z', '0193039023', 'agushendra29@gmail.com', '2025-08-08', 25, NULL, '2025-08-22 07:25:38', 5, 'Jl. H. Sennin Blok H Senin No.10 10, RT.10/RW.12, Palmerah, Kec. Palmerah, Kota Jakarta Barat', 2, '000072', 1, 'aksdakdadk'),
(73, NULL, 'ABC', '1234567890123456', 'BCA', '0910391031930', NULL, 'woii', '0193109323', 'abc@gmail.com', '2025-08-04', 25, NULL, '2025-08-22 07:38:23', 5, 'woii', 21, '000073', 1, 'ADNAKDMAKDM'),
(74, NULL, 'ABC', '1234567890123456', 'BCA', '0910391031930', NULL, 'woii', '0193109323', 'abc@gmail.com', '2025-08-04', 25, NULL, '2025-08-22 07:40:09', 5, 'woii', 0, '000074', 1, 'ADNAKDMAKDM'),
(75, NULL, 'ABC', '1234567890123456', 'BCA', '0910391031930', NULL, 'woii', '0193109323', 'abc@gmail.com', '2025-08-04', 25, NULL, '2025-08-22 07:40:22', 5, 'woii', 2, '000075', 1, 'ADNAKDMAKDM'),
(76, NULL, 'woiii', '1234567890123456', 'BCA', '019309303290329', NULL, 'woi', '0193012930293', 'abc@gmail.com', '2025-08-11', 25, NULL, '2025-08-22 07:41:34', 5, 'woi', 2, '000076', 1, 'ABC');

-- --------------------------------------------------------

--
-- Table structure for table `suppliar_user`
--

CREATE TABLE `suppliar_user` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role_id` int(11) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_histories`
--

CREATE TABLE `transaction_histories` (
  `id` int(11) NOT NULL,
  `suppliar_id` int(11) NOT NULL,
  `type` enum('pembelian','penjualan','refund') NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `note` text DEFAULT NULL,
  `customer_id` int(12) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `invoice_number` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_histories`
--

INSERT INTO `transaction_histories` (`id`, `suppliar_id`, `type`, `product_id`, `quantity`, `created_at`, `note`, `customer_id`, `customer_name`, `invoice_number`) VALUES
(24, 45, 'pembelian', 56, 100, '2025-08-09 18:44:20', NULL, 46, 'Agus Hendra 2', 'INV-68973494AFB7A'),
(25, 46, 'penjualan', 56, 100, '2025-08-09 18:46:43', NULL, 0, 'Penjualan Pribadi', 'INV-68973523690D7'),
(26, 46, 'penjualan', 56, 100, '2025-08-09 19:05:32', NULL, 0, 'Penjualan Pribadi', 'INV-6897398C6026B'),
(27, 45, 'pembelian', 56, 1000, '2025-08-09 21:39:45', NULL, 46, 'Agus Hendra 2', 'INV-68975DB1211B5'),
(28, 46, 'penjualan', 56, 10, '2025-08-09 21:42:34', NULL, 0, 'Penjualan Pribadi', 'INV-68975E5AE31A6'),
(29, 45, 'refund', 56, 20, '2025-08-10 11:01:25', 'barang rusak', 46, 'Agus Hendra 2', 'INV-68973494AFB7A'),
(30, 46, 'penjualan', 56, 10, '2025-08-10 12:47:45', NULL, 0, 'Penjualan Pribadi', 'INV-689832812A43A'),
(31, 45, 'pembelian', 56, 100, '2025-08-10 16:06:09', NULL, 47, 'thomas', 'INV-6898610107E1F'),
(32, 45, 'refund', 56, 30, '2025-08-10 16:09:11', 'salah bayar', 47, 'thomas', 'INV-6898610107E1F'),
(33, 45, 'pembelian', 56, 1000, '2025-08-11 11:18:47', NULL, 48, 'lombok', 'INV-68996F273F432'),
(34, 45, 'penjualan', 56, 10, '2025-08-12 03:23:37', NULL, 0, 'Penjualan Pribadi', 'INV-689A5148F338B'),
(35, 45, 'pembelian', 56, 100, '2025-08-12 04:22:50', NULL, 49, 'Distributor Cobaan', 'INV-689A5F2A63B8F'),
(36, 49, 'penjualan', 56, 10, '2025-08-12 04:35:00', NULL, 0, 'Penjualan Pribadi', 'INV-689A620424FEC'),
(37, 49, 'penjualan', 56, 10, '2025-08-12 08:57:36', NULL, 50, 'Agen', 'INV-689A9F90B7506'),
(38, 50, 'penjualan', 56, 5, '2025-08-12 09:02:53', NULL, 0, 'Penjualan Pribadi', 'INV-689AA0CD27628'),
(39, 49, 'penjualan', 56, 10, '2025-08-12 09:10:57', NULL, 50, 'Agen', 'INV-689AA2B165288'),
(40, 49, 'pembelian', 56, 10, '2025-08-12 09:10:57', NULL, 50, 'Agen', 'INV-689AA2B165288'),
(44, 49, 'penjualan', 56, 10, '2025-08-12 09:18:03', NULL, 50, 'Agen', 'INV-689AA45BEA975'),
(45, 49, 'pembelian', 56, 10, '2025-08-12 09:18:03', NULL, 50, 'Agen', 'INV-689AA45BEA975'),
(46, 50, 'penjualan', 56, 10, '2025-08-12 09:20:10', NULL, 0, 'Penjualan Pribadi', 'INV-689AA4DA4E7B2'),
(47, 51, 'pembelian', 56, 1000, '2025-08-12 09:59:23', NULL, 51, 'ABC', '-'),
(48, 45, 'pembelian', 57, 100, '2025-08-14 19:34:40', NULL, 45, 'Agus Hendra', '-'),
(49, 45, 'penjualan', 56, 10, '2025-08-15 00:29:02', NULL, 50, 'Agen', 'INV-689E1CDE587E8'),
(50, 45, 'penjualan', 57, 10, '2025-08-15 00:29:02', NULL, 50, 'Agen', 'INV-689E1CDE587E8'),
(51, 45, 'penjualan', 57, 10, '2025-08-15 01:00:09', NULL, 0, 'Penjualan Pribadi', 'INV-689E2429BD26B'),
(52, 45, 'penjualan', 57, 10, '2025-08-15 01:00:09', NULL, 0, 'Penjualan Pribadi', 'INV-689E2429BD26B'),
(54, 46, 'penjualan', 56, 10, '2025-08-18 00:01:24', NULL, 50, 'Agen', 'INV-68A20AE42033A'),
(55, 1, 'pembelian', 57, 10, '2025-08-18 00:27:00', NULL, 1, 'Agus Hendra', '-'),
(56, 57, 'penjualan', 56, 10, '2025-08-18 03:52:29', NULL, 47, 'thomas', 'INV-68A2410DA5199'),
(57, 57, 'penjualan', 56, 10, '2025-08-18 03:58:45', NULL, 46, 'Agus Hendra 2', 'INV-68A24285113CD'),
(58, 57, 'penjualan', 57, 10, '2025-08-18 03:58:45', NULL, 46, 'Agus Hendra 2', 'INV-68A24285113CD'),
(59, 57, 'penjualan', 56, 10, '2025-08-18 04:13:22', NULL, 46, 'Agus Hendra 2', 'INV-68A245F27B354'),
(60, 57, 'penjualan', 56, 1, '2025-08-18 04:34:10', NULL, 46, 'Agus Hendra 2', 'INV-68A24AD2956ED'),
(61, 57, 'penjualan', 56, 10, '2025-08-18 04:48:38', NULL, 47, 'thomas', 'INV-68A24E36AB4D7'),
(62, 57, 'penjualan', 56, 1, '2025-08-18 04:49:32', NULL, 47, 'thomas', 'INV-68A24E6C34E1F'),
(63, 55, 'pembelian', 56, 10, '2025-08-18 08:31:50', NULL, 55, 'TEST ABC', '-'),
(64, 57, 'pembelian', 57, 10, '2025-08-18 09:19:20', NULL, 46, 'Agus Hendra 2', 'INV-68A28DA89F345'),
(65, 57, 'penjualan', 56, 1, '2025-08-18 10:15:01', NULL, 47, 'thomas', 'INV-68A29AB512699'),
(66, 57, 'penjualan', 56, 1, '2025-08-18 18:26:45', NULL, 47, 'thomas', 'INV-68A30DF5DC518'),
(67, 45, 'penjualan', 57, 10, '2025-08-18 22:37:07', NULL, 47, 'thomas', 'INV-68A348A30D823'),
(68, 45, 'penjualan', 56, 1, '2025-08-18 23:03:22', NULL, 51, 'ABC', 'INV-68A34ECAEFBE1'),
(69, 1, 'pembelian', 56, 10, '2025-08-19 00:55:03', NULL, 1, 'Agus Hendra', '-'),
(70, 46, 'pembelian', 57, 10, '2025-08-19 00:56:49', NULL, 46, 'Agus Hendra 2', '-'),
(71, 46, 'pembelian', 57, 10, '2025-08-19 00:56:55', NULL, 46, 'Agus Hendra 2', '-'),
(72, 46, 'pembelian', 57, 10, '2025-08-19 00:56:57', NULL, 46, 'Agus Hendra 2', '-'),
(73, 46, 'pembelian', 57, 10, '2025-08-19 00:56:57', NULL, 46, 'Agus Hendra 2', '-'),
(74, 1, 'pembelian', 56, 10, '2025-08-19 00:57:26', NULL, 1, 'Agus Hendra', '-'),
(75, 1, 'penjualan', 56, 1, '2025-08-20 09:00:42', NULL, 46, 'Agus Hendra 2', 'INV-68A52C4A94666'),
(76, 47, 'penjualan', 56, 1, '2025-08-20 09:07:55', NULL, 0, 'Penjualan Pribadi', 'INV-68A52DFB41303'),
(77, 47, 'penjualan', 57, 10, '2025-08-20 09:10:30', NULL, 0, 'Penjualan Pribadi', 'INV-68A52E963A4AD'),
(78, 1, 'pembelian', 57, 1000, '2025-08-20 19:38:46', NULL, 1, 'Agus Hendra', '-'),
(79, 1, 'pembelian', 56, 10, '2025-08-20 19:38:52', NULL, 47, 'thomas', 'INV-68A5C1DC04515'),
(80, 1, 'pembelian', 57, 10, '2025-08-20 19:38:52', NULL, 47, 'thomas', 'INV-68A5C1DC04515'),
(81, 1, 'pembelian', 56, 10, '2025-08-20 19:39:09', NULL, 47, 'thomas', 'INV-68A5C1ED816DC'),
(82, 1, 'pembelian', 57, 10, '2025-08-20 19:39:09', NULL, 47, 'thomas', 'INV-68A5C1ED816DC'),
(83, 1, 'penjualan', 56, 1, '2025-08-21 16:33:58', NULL, 60, 'agus hendra', 'INV-68A6E80617CC5'),
(84, 1, 'penjualan', 57, 1, '2025-08-21 16:33:58', NULL, 60, 'agus hendra', 'INV-68A6E80617CC5'),
(85, 1, 'penjualan', 56, 1, '2025-08-21 16:34:11', NULL, 61, 'agus hendra', 'INV-68A6E81300CAE'),
(86, 1, 'penjualan', 57, 1, '2025-08-21 16:34:11', NULL, 61, 'agus hendra', 'INV-68A6E81300CAE'),
(87, 1, 'penjualan', 56, 2, '2025-08-21 16:47:53', NULL, 62, 'tESTING 2', 'INV-68A6EB49050AD'),
(88, 1, 'penjualan', 56, 10, '2025-08-21 17:03:19', NULL, 63, 'Testing 3', 'INV-68A6EEE7DE9AE'),
(89, 1, 'penjualan', 56, 10, '2025-08-22 14:03:49', NULL, 64, 'Agus Hendra', 'INV-68A81655EDE6B'),
(90, 1, 'penjualan', 56, 10, '2025-08-22 14:04:56', NULL, 65, 'Agus Hendra', 'INV-68A81698798B6'),
(91, 1, 'penjualan', 56, 10, '2025-08-22 14:05:02', NULL, 66, 'Agus Hendra', 'INV-68A8169EB0DBC'),
(92, 1, 'penjualan', 56, 10, '2025-08-22 14:05:18', NULL, 67, 'Agus Hendra', 'INV-68A816AE89D50'),
(93, 1, 'penjualan', 56, 2, '2025-08-22 14:22:36', NULL, 68, 'Agus Hendra', 'INV-68A81ABC4C9B8'),
(94, 1, 'penjualan', 56, 2, '2025-08-22 14:22:45', NULL, 69, 'Agus Hendra', 'INV-68A81AC589908'),
(95, 1, 'penjualan', 56, 2, '2025-08-22 14:24:06', NULL, 70, 'Agus Hendra', 'INV-68A81B16E9C77'),
(96, 1, 'penjualan', 57, 2, '2025-08-22 14:25:17', NULL, 71, 'Testingg', 'INV-68A81B5D13136'),
(97, 1, 'penjualan', 57, 2, '2025-08-22 14:25:38', NULL, 72, 'Testingg', 'INV-68A81B72C2B22'),
(98, 1, 'penjualan', 56, 21, '2025-08-22 14:38:23', NULL, 73, 'ABC', 'INV-68A81E6F6F1C1'),
(99, 1, 'penjualan', 56, 2, '2025-08-22 14:40:22', NULL, 75, 'ABC', 'INV-68A81EE6690AD'),
(100, 1, 'penjualan', 57, 2, '2025-08-22 14:41:34', NULL, 76, 'woiii', 'INV-68A81F2EB0984'),
(101, 1, 'penjualan', 56, 1, '2025-08-22 18:42:31', NULL, 46, 'Agus Hendra 2', 'INV-68A857A786E02');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `role_id` int(10) DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `last_update_at` int(11) DEFAULT NULL,
  `added_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `suppliar_id` int(10) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `suppliar_code` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `role_id`, `update_by`, `last_update_at`, `added_date`, `suppliar_id`, `is_active`, `suppliar_code`) VALUES
(25, 'agushendra28@gmail.com', 'admin123', 10, NULL, NULL, '2025-08-09 11:16:27', 1, 1, '000001'),
(26, 'agushendra29@gmail.com', 'admin', 3, NULL, NULL, '2025-08-09 11:26:57', 46, 1, ''),
(27, '111@gmail.com', 'admin', 2, NULL, NULL, '2025-08-10 08:33:54', 47, 1, '000002'),
(28, 'lombok@gmail.com', 'lom28082025', 3, NULL, NULL, '2025-08-11 04:00:08', 48, 1, ''),
(29, 'distributor1@gmail.com', 'admin', 3, NULL, NULL, '2025-08-11 21:10:58', 49, 1, ''),
(30, 'agen29@gmail.com', 'age28082025', 4, NULL, NULL, '2025-08-12 01:38:07', 50, 1, ''),
(31, 'abc@gmail.com', 'abc12082025', 2, NULL, NULL, '2025-08-12 02:58:54', 51, 1, ''),
(32, 'abc@gmail.com', 'fan29071999', 2, NULL, NULL, '2025-08-17 02:29:53', 52, 1, ''),
(33, 'agushendra29@gmail.com', 'abc28082025', 2, NULL, NULL, '2025-08-17 02:35:15', 53, 1, ''),
(34, 'agushendra29@gmail.com', 'agu28082025', 3, NULL, NULL, '2025-08-17 17:36:27', 56, 1, ''),
(35, 'agushendra29@gmail.com', 'agu28082025', 3, NULL, NULL, '2025-08-17 17:40:27', 57, 1, '000057'),
(36, 'test@gmail.com', 'admin', 3, NULL, NULL, '2025-08-18 17:22:02', 58, 1, '000058'),
(37, 'agushendra29@gmail.com', 'agu22082025', 5, NULL, NULL, '2025-08-21 09:33:58', 60, 1, '000060'),
(38, 'agushendra29@gmail.com', 'agu22082025', 5, NULL, NULL, '2025-08-21 09:34:11', 61, 1, '000061'),
(39, 'agushendra29@gmail.com', 'tes28082025', 5, NULL, NULL, '2025-08-21 09:47:53', 62, 1, '000062'),
(40, 'testing5@gmail.com', 'tes08022025', 5, NULL, NULL, '2025-08-21 10:03:19', 63, 1, '000063'),
(41, 'agushendra29@gmail.com', 'agu21082025', 5, NULL, NULL, '2025-08-22 07:03:49', 64, 1, '000064'),
(42, 'agushendra29@gmail.com', 'agu28082005', 5, NULL, NULL, '2025-08-22 07:04:56', 65, 1, '000065'),
(43, 'agushendra29@gmail.com', 'agu28082005', 5, NULL, NULL, '2025-08-22 07:05:02', 66, 1, '000066'),
(44, 'agushendra29@gmail.com', 'agu28082005', 5, NULL, NULL, '2025-08-22 07:05:18', 67, 1, '000067'),
(45, 'agushendra29@gmail.com', 'agu29082005', 5, NULL, NULL, '2025-08-22 07:22:36', 68, 1, '000068'),
(46, 'agushendra29@gmail.com', 'agu29082005', 5, NULL, NULL, '2025-08-22 07:22:45', 69, 1, '000069'),
(47, 'agushendra29@gmail.com', 'agu29082005', 5, NULL, NULL, '2025-08-22 07:24:06', 70, 1, '000070'),
(48, 'agushendra29@gmail.com', 'tes08082025', 5, NULL, NULL, '2025-08-22 07:25:17', 71, 1, '000071'),
(49, 'agushendra29@gmail.com', 'tes08082025', 5, NULL, NULL, '2025-08-22 07:25:38', 72, 1, '000072'),
(50, 'abc@gmail.com', 'abc04082025', 5, NULL, NULL, '2025-08-22 07:38:23', 73, 1, '000073'),
(51, 'abc@gmail.com', 'abc04082025', 5, NULL, NULL, '2025-08-22 07:40:09', 74, 1, '000074'),
(52, 'abc@gmail.com', 'abc04082025', 5, NULL, NULL, '2025-08-22 07:40:22', 75, 1, '000075'),
(53, 'abc@gmail.com', 'woi11082025', 5, NULL, NULL, '2025-08-22 07:41:34', 76, 1, '000076');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `catagory`
--
ALTER TABLE `catagory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `distributor_stocks`
--
ALTER TABLE `distributor_stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense_catagory`
--
ALTER TABLE `expense_catagory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `factory_products`
--
ALTER TABLE `factory_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_details`
--
ALTER TABLE `invoice_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_no` (`invoice_no`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `member_id` (`member_id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paymethode`
--
ALTER TABLE `paymethode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_payment`
--
ALTER TABLE `purchase_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_products`
--
ALTER TABLE `purchase_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_return`
--
ALTER TABLE `purchase_return`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rewards`
--
ALTER TABLE `rewards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sell_payment`
--
ALTER TABLE `sell_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sell_return`
--
ALTER TABLE `sell_return`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_logs`
--
ALTER TABLE `stock_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliar`
--
ALTER TABLE `suppliar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliar_user`
--
ALTER TABLE `suppliar_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_histories`
--
ALTER TABLE `transaction_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `catagory`
--
ALTER TABLE `catagory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `distributor_stocks`
--
ALTER TABLE `distributor_stocks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `expense`
--
ALTER TABLE `expense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `expense_catagory`
--
ALTER TABLE `expense_catagory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `factory_products`
--
ALTER TABLE `factory_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `invoice_details`
--
ALTER TABLE `invoice_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `paymethode`
--
ALTER TABLE `paymethode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `purchase_payment`
--
ALTER TABLE `purchase_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `purchase_products`
--
ALTER TABLE `purchase_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchase_return`
--
ALTER TABLE `purchase_return`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rewards`
--
ALTER TABLE `rewards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sell_payment`
--
ALTER TABLE `sell_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sell_return`
--
ALTER TABLE `sell_return`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stock_logs`
--
ALTER TABLE `stock_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `suppliar`
--
ALTER TABLE `suppliar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `suppliar_user`
--
ALTER TABLE `suppliar_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `transaction_histories`
--
ALTER TABLE `transaction_histories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `suppliar`
--
ALTER TABLE `suppliar`
  ADD CONSTRAINT `fk_suppliar_role` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `suppliar_user`
--
ALTER TABLE `suppliar_user`
  ADD CONSTRAINT `suppliar_user_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

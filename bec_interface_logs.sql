-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 29, 2022 at 04:33 PM
-- Server version: 10.1.35-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bec_interface_logs`
--

-- --------------------------------------------------------

--
-- Table structure for table `channels_logs`
--

CREATE TABLE `channels_logs` (
  `id` int(11) NOT NULL,
  `action` set('get','get_all','create','update','delete') DEFAULT NULL,
  `status` varchar(7) DEFAULT NULL,
  `message` text,
  `json` mediumtext,
  `date_upd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `customer_area_logs`
--

CREATE TABLE `customer_area_logs` (
  `id` int(11) NOT NULL,
  `action` set('get','get_all','create','update','delete') DEFAULT NULL,
  `status` varchar(7) DEFAULT NULL,
  `message` text,
  `json` mediumtext,
  `date_upd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `customer_grade_logs`
--

CREATE TABLE `customer_grade_logs` (
  `id` int(11) NOT NULL,
  `action` set('get','get_all','create','update','delete') DEFAULT NULL,
  `status` varchar(7) DEFAULT NULL,
  `message` text,
  `json` mediumtext,
  `date_upd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer_grade_logs`
--

INSERT INTO `customer_grade_logs` (`id`, `action`, `status`, `message`, `json`, `date_upd`) VALUES
(1, 'create', 'error', 'id (7) already exists', '{\r\n    \"id\" : 7,\r\n    \"name\" : \"VIP\"\r\n}', '2022-06-16 10:40:17'),
(2, 'update', 'success', NULL, '{\r\n    \"id\" : 7,\r\n    \"name\" : \"VIPX\"\r\n}', '2022-06-16 10:40:39'),
(3, 'update', 'success', NULL, '{\r\n    \"id\" : 7,\r\n    \"name\" : \"VIP\"\r\n}', '2022-06-16 10:40:55');

-- --------------------------------------------------------

--
-- Table structure for table `customer_group_logs`
--

CREATE TABLE `customer_group_logs` (
  `id` int(11) NOT NULL,
  `action` set('get','get_all','create','update','delete') DEFAULT NULL,
  `status` varchar(7) DEFAULT NULL,
  `message` text,
  `json` mediumtext,
  `date_upd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `customer_logs`
--

CREATE TABLE `customer_logs` (
  `id` int(11) NOT NULL,
  `action` set('get','get_all','create','update','delete') DEFAULT NULL,
  `status` varchar(7) DEFAULT NULL,
  `message` text,
  `json` mediumtext,
  `date_upd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer_logs`
--

INSERT INTO `customer_logs` (`id`, `action`, `status`, `message`, `json`, `date_upd`) VALUES
(1, 'create', 'error', NULL, '{    \r\n    \"CardName\": \"ร้านขายอุปกรณ์ไฟฟ้า\",\r\n    \"CardType\": \"C\",\r\n    \"GroupCode\": 104,\r\n    \"SlpCode\": 18,\r\n    \"RegionCode\": 2,\r\n    \"TypeCode\": 1,\r\n    \"GradeCode\": 1,\r\n    \"CreditLine\": 500000,\r\n    \"Status\": \"N\"\r\n}', '2022-06-16 21:14:35'),
(2, 'create', 'error', 'Missing required parameter : CardCode', '{    \r\n    \"CardName\": \"ร้านขายอุปกรณ์ไฟฟ้า\",\r\n    \"CardType\": \"C\",\r\n    \"GroupCode\": 104,\r\n    \"SlpCode\": 18,\r\n    \"RegionCode\": 2,\r\n    \"TypeCode\": 1,\r\n    \"GradeCode\": 1,\r\n    \"CreditLine\": 500000,\r\n    \"Status\": \"N\"\r\n}', '2022-06-16 21:15:41'),
(3, 'create', 'success', NULL, '{    \r\n    \"CardCode\": \"CL-005\",\r\n    \"CardName\": \"ร้านขายอุปกรณ์ไฟฟ้า\",\r\n    \"CardType\": \"C\",\r\n    \"GroupCode\": 104,\r\n    \"SlpCode\": 18,\r\n    \"RegionCode\": 2,\r\n    \"TypeCode\": 1,\r\n    \"GradeCode\": 1,\r\n    \"CreditLine\": 500000,\r\n    \"Status\": \"N\"\r\n}', '2022-06-16 21:20:17'),
(4, 'update', 'success', NULL, '{\r\n    \"CardCode\": \"CL-005\",\r\n    \"CardName\": \"Index Living mall\",\r\n    \"CardType\": \"C\",\r\n    \"GroupCode\": 107,\r\n    \"GroupNum\": -1,\r\n    \"SlpCode\": 18,\r\n    \"RegionCode\": 2,\r\n    \"AreaCode\": 3,\r\n    \"TypeCode\": 1,\r\n    \"GradeCode\": 1,\r\n    \"CreditLine\": 500000,\r\n    \"Status\": \"N\"\r\n}', '2022-06-16 21:23:03'),
(5, 'update', 'success', NULL, '{\r\n    \"CardCode\": \"CL-005\",\r\n    \"CardName\": \"Index Living mall\",\r\n    \"CardType\": \"C\",\r\n    \"GroupCode\": 107,\r\n    \"GroupNum\": -1,\r\n    \"SlpCode\": 18,\r\n    \"RegionCode\": 2,\r\n    \"AreaCode\": 3,\r\n    \"TypeCode\": 1,\r\n    \"GradeCode\": 1,\r\n    \"CreditLine\": 500000,\r\n    \"Status\": \"N\"\r\n}', '2022-06-16 21:25:53');

-- --------------------------------------------------------

--
-- Table structure for table `customer_region_logs`
--

CREATE TABLE `customer_region_logs` (
  `id` int(11) NOT NULL,
  `action` set('get','get_all','create','update','delete') DEFAULT NULL,
  `status` varchar(7) DEFAULT NULL,
  `message` text,
  `json` mediumtext,
  `date_upd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer_region_logs`
--

INSERT INTO `customer_region_logs` (`id`, `action`, `status`, `message`, `json`, `date_upd`) VALUES
(1, 'create', 'success', NULL, '{\r\n    \"id\" : 7,\r\n    \"name\" : \"กรุงเทพฯ\"\r\n}', '2022-06-15 13:00:20'),
(2, 'delete', 'success', NULL, '{\"id\" : 7}', '2022-06-15 16:47:15');

-- --------------------------------------------------------

--
-- Table structure for table `customer_type_logs`
--

CREATE TABLE `customer_type_logs` (
  `id` int(11) NOT NULL,
  `action` set('get','get_all','create','update','delete') DEFAULT NULL,
  `status` varchar(7) DEFAULT NULL,
  `message` text,
  `json` mediumtext,
  `date_upd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer_type_logs`
--

INSERT INTO `customer_type_logs` (`id`, `action`, `status`, `message`, `json`, `date_upd`) VALUES
(1, 'create', 'success', NULL, '{\r\n    \"id\" : 1,\r\n    \"name\" : \"โคงการใหญ่\"\r\n}', '2022-06-16 11:18:27'),
(2, 'create', 'error', 'id (7) already exists', '{\r\n   \"id\" : 7,\r\n   \"name\" : \"Test\"\r\n}', '2022-06-23 12:46:51');

-- --------------------------------------------------------

--
-- Table structure for table `employee_logs`
--

CREATE TABLE `employee_logs` (
  `id` int(11) NOT NULL,
  `action` set('get','get_all','create','update','delete') DEFAULT NULL,
  `status` varchar(7) DEFAULT NULL,
  `message` text,
  `json` mediumtext,
  `date_upd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employee_logs`
--

INSERT INTO `employee_logs` (`id`, `action`, `status`, `message`, `json`, `date_upd`) VALUES
(1, 'create', 'success', NULL, '{\r\n   \"EmpID\" : 1,\r\n   \"firstName\" : \"สุทัศ\",\r\n   \"lastName\" : \"สังข์สวัสดิ์5\",\r\n   \"middleName\" : null,\r\n   \"Active\" : \"N\"\r\n}', '2022-06-22 19:23:54'),
(2, 'update', 'error', 'Missing required parameter.', '{\r\n   \"EmpID\" : 1,\r\n   \"firstName\" : \"สุทัศ\",\r\n   \"lastName\" : \"สังข์สวัสดิ์\",\r\n   \"middleName\" : null,\r\n   \"Active\" : \"Y\"\r\n}', '2022-06-22 19:25:05'),
(3, 'update', 'error', 'Missing required parameter.', '{\r\n   \"EmpID\" : 1,\r\n   \"firstName\" : \"สุทัศ\",\r\n   \"lastName\" : \"สังข์สวัสดิ์\",\r\n   \"middleName\" : null,\r\n   \"Active\" : \"Y\"\r\n}', '2022-06-22 19:25:15'),
(4, 'update', 'error', 'Missing required parameter.', '{\r\n   \"EmpID\" : 1,\r\n   \"firstName\" : \"สุทัศ\",\r\n   \"lastName\" : \"สังข์สวัสดิ์\",\r\n   \"middleName\" : null,\r\n   \"Active\" : \"Y\"\r\n}', '2022-06-22 19:25:30'),
(5, 'update', 'success', NULL, '{\r\n   \"EmpID\" : 1,\r\n   \"firstName\" : \"สุทัศ\",\r\n   \"lastName\" : \"สังข์สวัสดิ์\",\r\n   \"middleName\" : null,\r\n   \"Active\" : \"Y\"\r\n}', '2022-06-22 19:26:05'),
(6, 'update', 'success', NULL, '{\r\n   \"EmpID\" : 1,\r\n   \"firstName\" : \"สุทัศ\",\r\n   \"lastName\" : \"สังข์สวัสดิ์\",\r\n   \"middleName\" : null,\r\n   \"Active\" : \"Y\"\r\n}', '2022-06-22 19:27:51');

-- --------------------------------------------------------

--
-- Table structure for table `payment_term_logs`
--

CREATE TABLE `payment_term_logs` (
  `id` int(11) NOT NULL,
  `action` set('get','get_all','create','update','delete') DEFAULT NULL,
  `status` varchar(7) DEFAULT NULL,
  `message` text,
  `json` mediumtext,
  `date_upd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment_term_logs`
--

INSERT INTO `payment_term_logs` (`id`, `action`, `status`, `message`, `json`, `date_upd`) VALUES
(1, 'create', 'success', NULL, '{\r\n    \"GroupNum\" : -1,\r\n    \"PymntGroup\" : \"-Cash Basic--\"\r\n}', '2022-06-16 12:24:32'),
(2, 'create', 'error', 'GroupNum (-1) already exists', '{\r\n    \"GroupNum\" : -1,\r\n    \"PymntGroup\" : \"-Cash Basic--\"\r\n}', '2022-06-16 12:26:38');

-- --------------------------------------------------------

--
-- Table structure for table `products_logs`
--

CREATE TABLE `products_logs` (
  `id` int(11) NOT NULL,
  `action` set('get','get_all','create','update','delete') DEFAULT NULL,
  `status` varchar(7) DEFAULT NULL,
  `message` text,
  `json` mediumtext,
  `date_upd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products_logs`
--

INSERT INTO `products_logs` (`id`, `action`, `status`, `message`, `json`, `date_upd`) VALUES
(1, 'update', 'success', NULL, NULL, '2022-06-21 12:31:36'),
(2, 'update', 'success', NULL, NULL, '2022-06-21 12:32:00');

-- --------------------------------------------------------

--
-- Table structure for table `product_brand_logs`
--

CREATE TABLE `product_brand_logs` (
  `id` int(11) NOT NULL,
  `action` set('get','get_all','create','update','delete') DEFAULT NULL,
  `status` varchar(7) DEFAULT NULL,
  `message` text,
  `json` mediumtext,
  `date_upd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product_brand_logs`
--

INSERT INTO `product_brand_logs` (`id`, `action`, `status`, `message`, `json`, `date_upd`) VALUES
(1, 'update', 'success', NULL, '{\r\n    \"id\" : 1,\r\n    \"name\" : \"BLite\"\r\n}', '2022-06-17 21:20:45'),
(2, 'update', 'error', '\"BLite\" already exists.', '{\r\n    \"id\" : 1,\r\n    \"name\" : \"BLite\"\r\n}', '2022-06-17 21:23:18'),
(3, 'update', 'error', '\'BLite\' already exists.', '{\r\n    \"id\" : 1,\r\n    \"name\" : \"BLite\"\r\n}', '2022-06-17 21:24:18'),
(4, 'create', 'success', NULL, '{\r\n    \"id\" : 8,\r\n    \"name\" : \"Bangalow\"\r\n}', '2022-06-17 21:42:55');

-- --------------------------------------------------------

--
-- Table structure for table `product_category_logs`
--

CREATE TABLE `product_category_logs` (
  `id` int(11) NOT NULL,
  `action` set('get','get_all','create','update','delete') DEFAULT NULL,
  `status` varchar(7) DEFAULT NULL,
  `message` text,
  `json` mediumtext,
  `date_upd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `product_model_logs`
--

CREATE TABLE `product_model_logs` (
  `id` int(11) NOT NULL,
  `action` set('get','get_all','create','update','delete') DEFAULT NULL,
  `status` varchar(7) DEFAULT NULL,
  `message` text,
  `json` mediumtext,
  `date_upd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product_model_logs`
--

INSERT INTO `product_model_logs` (`id`, `action`, `status`, `message`, `json`, `date_upd`) VALUES
(1, 'create', 'success', NULL, '{\r\n    \"id\" : 5,\r\n    \"name\" : \"แผงโซล่าเซล Mono รุ่นประหยัด\"\r\n}', '2022-06-17 23:05:31');

-- --------------------------------------------------------

--
-- Table structure for table `product_type_logs`
--

CREATE TABLE `product_type_logs` (
  `id` int(11) NOT NULL,
  `action` set('get','get_all','create','update','delete') DEFAULT NULL,
  `status` varchar(7) DEFAULT NULL,
  `message` text,
  `json` mediumtext,
  `date_upd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `sale_person_logs`
--

CREATE TABLE `sale_person_logs` (
  `id` int(11) NOT NULL,
  `action` set('get','get_all','create','update','delete') DEFAULT NULL,
  `status` varchar(7) DEFAULT NULL,
  `message` text,
  `json` mediumtext,
  `date_upd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sale_person_logs`
--

INSERT INTO `sale_person_logs` (`id`, `action`, `status`, `message`, `json`, `date_upd`) VALUES
(1, 'update', 'success', NULL, '{\r\n   \"SlpCode\" : 8,\r\n   \"SlpName\" : \"Lazada Co., Ltd.\",\r\n   \"Active\" : \"Y\"\r\n}', '2022-06-22 13:20:57'),
(2, 'update', 'success', NULL, '{\r\n   \"SlpCode\" : 8,\r\n   \"SlpName\" : \"Lazada Co., Ltd.\",\r\n   \"Active\" : \"Y\"\r\n}', '2022-06-22 13:28:13'),
(3, 'update', 'success', NULL, '{\r\n   \"SlpCode\" : 8,\r\n   \"SlpName\" : \"Lazada Co., Ltd.\",\r\n   \"EmpID\" : 10,\r\n   \"Active\" : \"Y\"\r\n}', '2022-06-22 13:28:48');

-- --------------------------------------------------------

--
-- Table structure for table `uom_logs`
--

CREATE TABLE `uom_logs` (
  `id` int(11) NOT NULL,
  `action` set('get','get_all','create','update','delete') DEFAULT NULL,
  `status` varchar(7) DEFAULT NULL,
  `message` text,
  `json` mediumtext,
  `date_upd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `uom_logs`
--

INSERT INTO `uom_logs` (`id`, `action`, `status`, `message`, `json`, `date_upd`) VALUES
(1, 'create', 'success', NULL, '{\r\n    \"UomEntry\" : 1,\r\n    \"UomCode\" : \"PCS\",\r\n    \"UomName\" : \"PCS\"\r\n}', '2022-06-20 11:48:22');

-- --------------------------------------------------------

--
-- Table structure for table `vat_group_logs`
--

CREATE TABLE `vat_group_logs` (
  `id` int(11) NOT NULL,
  `action` set('get','get_all','create','update','delete') DEFAULT NULL,
  `status` varchar(7) DEFAULT NULL,
  `message` text,
  `json` mediumtext,
  `date_upd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `warehouse_logs`
--

CREATE TABLE `warehouse_logs` (
  `id` int(11) NOT NULL,
  `action` set('get','get_all','create','update','delete') DEFAULT NULL,
  `status` varchar(7) DEFAULT NULL,
  `message` text,
  `json` mediumtext,
  `date_upd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `zone_logs`
--

CREATE TABLE `zone_logs` (
  `id` int(11) NOT NULL,
  `action` set('get','get_all','create','update','delete') DEFAULT NULL,
  `status` varchar(7) DEFAULT NULL,
  `message` text,
  `json` mediumtext,
  `date_upd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `zone_logs`
--

INSERT INTO `zone_logs` (`id`, `action`, `status`, `message`, `json`, `date_upd`) VALUES
(1, 'create', 'success', NULL, '{\r\n   \"AbsEntry\" : 8,\r\n   \"BinCode\" : \"AFG-0000-A004\",\r\n   \"Descr\" : \"ล็อก A แถวที่ 4\",\r\n   \"WhsCode\" : \"AFG-0000\",\r\n   \"SysBin\" : \"N\"\r\n}', '2022-06-22 18:48:08'),
(2, 'create', 'error', 'BinAbs (8) already exists', '{\r\n   \"AbsEntry\" : 8,\r\n   \"BinCode\" : \"AFG-0000-A004\",\r\n   \"Descr\" : \"ล็อก A แถวที่ 4\",\r\n   \"WhsCode\" : \"AFG-0000\",\r\n   \"SysBin\" : \"N\"\r\n}', '2022-06-22 18:48:29'),
(3, 'create', 'error', '\'AFG-0000-A004\' already exists.', '{\r\n   \"AbsEntry\" : 9,\r\n   \"BinCode\" : \"AFG-0000-A004\",\r\n   \"Descr\" : \"ล็อก A แถวที่ 4\",\r\n   \"WhsCode\" : \"AFG-0000\",\r\n   \"SysBin\" : \"N\"\r\n}', '2022-06-22 18:48:43'),
(4, 'create', 'success', NULL, '{\r\n   \"AbsEntry\" : 9,\r\n   \"BinCode\" : \"AFG-0000-A005\",\r\n   \"Descr\" : \"ล็อก A แถวที่ 5\",\r\n   \"WhsCode\" : \"AFG-0000\",\r\n   \"SysBin\" : \"N\"\r\n}', '2022-06-22 18:49:02'),
(5, 'create', 'error', 'BinAbs (9) already exists', '{\r\n   \"AbsEntry\" : 9,\r\n   \"BinCode\" : \"AFG-0000-A008\",\r\n   \"Descr\" : \"ล็อก A แถวที่ 5\",\r\n   \"WhsCode\" : \"AFG-0000\",\r\n   \"SysBin\" : \"N\"\r\n}', '2022-06-22 18:49:16'),
(6, 'update', 'success', NULL, '{\r\n   \"AbsEntry\" : 9,\r\n   \"BinCode\" : \"AFG-0000-A008\",\r\n   \"Descr\" : \"ล็อก A แถวที่ 5\",\r\n   \"WhsCode\" : \"AFG-0000\",\r\n   \"SysBin\" : \"N\"\r\n}', '2022-06-22 18:49:35'),
(7, 'update', 'success', NULL, '{\r\n   \"AbsEntry\" : 8,\r\n   \"BinCode\" : \"AFG-0000-A008\",\r\n   \"Descr\" : \"ล็อก A แถวที่ 5\",\r\n   \"WhsCode\" : \"AFG-0000\",\r\n   \"SysBin\" : \"N\"\r\n}', '2022-06-22 18:50:12'),
(8, 'update', 'error', '\'AFG-0000-A008\' already exists.', '{\r\n   \"AbsEntry\" : 8,\r\n   \"BinCode\" : \"AFG-0000-A008\",\r\n   \"Descr\" : \"ล็อก A แถวที่ 5\",\r\n   \"WhsCode\" : \"AFG-0000\",\r\n   \"SysBin\" : \"N\"\r\n}', '2022-06-22 18:50:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `channels_logs`
--
ALTER TABLE `channels_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action` (`action`);

--
-- Indexes for table `customer_area_logs`
--
ALTER TABLE `customer_area_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action` (`action`);

--
-- Indexes for table `customer_grade_logs`
--
ALTER TABLE `customer_grade_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action` (`action`);

--
-- Indexes for table `customer_group_logs`
--
ALTER TABLE `customer_group_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action` (`action`);

--
-- Indexes for table `customer_logs`
--
ALTER TABLE `customer_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action` (`action`);

--
-- Indexes for table `customer_region_logs`
--
ALTER TABLE `customer_region_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action` (`action`);

--
-- Indexes for table `customer_type_logs`
--
ALTER TABLE `customer_type_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action` (`action`);

--
-- Indexes for table `employee_logs`
--
ALTER TABLE `employee_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action` (`action`);

--
-- Indexes for table `payment_term_logs`
--
ALTER TABLE `payment_term_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action` (`action`);

--
-- Indexes for table `products_logs`
--
ALTER TABLE `products_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action` (`action`);

--
-- Indexes for table `product_brand_logs`
--
ALTER TABLE `product_brand_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action` (`action`);

--
-- Indexes for table `product_category_logs`
--
ALTER TABLE `product_category_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action` (`action`);

--
-- Indexes for table `product_model_logs`
--
ALTER TABLE `product_model_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action` (`action`);

--
-- Indexes for table `product_type_logs`
--
ALTER TABLE `product_type_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action` (`action`);

--
-- Indexes for table `sale_person_logs`
--
ALTER TABLE `sale_person_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action` (`action`);

--
-- Indexes for table `uom_logs`
--
ALTER TABLE `uom_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action` (`action`);

--
-- Indexes for table `vat_group_logs`
--
ALTER TABLE `vat_group_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action` (`action`);

--
-- Indexes for table `warehouse_logs`
--
ALTER TABLE `warehouse_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action` (`action`);

--
-- Indexes for table `zone_logs`
--
ALTER TABLE `zone_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action` (`action`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `channels_logs`
--
ALTER TABLE `channels_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_area_logs`
--
ALTER TABLE `customer_area_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_grade_logs`
--
ALTER TABLE `customer_grade_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customer_group_logs`
--
ALTER TABLE `customer_group_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_logs`
--
ALTER TABLE `customer_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customer_region_logs`
--
ALTER TABLE `customer_region_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customer_type_logs`
--
ALTER TABLE `customer_type_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `employee_logs`
--
ALTER TABLE `employee_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payment_term_logs`
--
ALTER TABLE `payment_term_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products_logs`
--
ALTER TABLE `products_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_brand_logs`
--
ALTER TABLE `product_brand_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_category_logs`
--
ALTER TABLE `product_category_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_model_logs`
--
ALTER TABLE `product_model_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product_type_logs`
--
ALTER TABLE `product_type_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_person_logs`
--
ALTER TABLE `sale_person_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `uom_logs`
--
ALTER TABLE `uom_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vat_group_logs`
--
ALTER TABLE `vat_group_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warehouse_logs`
--
ALTER TABLE `warehouse_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `zone_logs`
--
ALTER TABLE `zone_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

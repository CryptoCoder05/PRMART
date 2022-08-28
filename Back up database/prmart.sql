-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 28, 2022 at 07:14 PM
-- Server version: 5.7.36
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `prmart`
--

-- --------------------------------------------------------

--
-- Table structure for table `add_pro_bar`
--

DROP TABLE IF EXISTS `add_pro_bar`;
CREATE TABLE IF NOT EXISTS `add_pro_bar` (
  `user_id` int(75) NOT NULL,
  `product_id` int(11) NOT NULL,
  `barcode` varchar(25) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2558 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `advertise`
--

DROP TABLE IF EXISTS `advertise`;
CREATE TABLE IF NOT EXISTS `advertise` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(75) NOT NULL,
  `title` varchar(186) NOT NULL,
  `image` varchar(500) NOT NULL,
  `featured` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `barcode_cart`
--

DROP TABLE IF EXISTS `barcode_cart`;
CREATE TABLE IF NOT EXISTS `barcode_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(75) NOT NULL,
  `product_id` int(20) NOT NULL,
  `title` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cost_price` decimal(10,0) NOT NULL,
  `MRP` int(50) NOT NULL,
  `selll_price` decimal(10,0) NOT NULL,
  `discount` float NOT NULL DEFAULT '0',
  `quantity` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `items` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `paid` tinyint(4) NOT NULL DEFAULT '0',
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `txn_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1572 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `barcode_transaction`
--

DROP TABLE IF EXISTS `barcode_transaction`;
CREATE TABLE IF NOT EXISTS `barcode_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(75) NOT NULL,
  `barcode_id` varchar(75) NOT NULL,
  `bill_no` varchar(50) NOT NULL,
  `title` varchar(256) NOT NULL,
  `items` varchar(75) NOT NULL,
  `cost_price` decimal(10,0) NOT NULL,
  `selll_price` decimal(10,0) NOT NULL,
  `qty` int(11) NOT NULL,
  `discount` float NOT NULL DEFAULT '0',
  `grand_total` decimal(10,0) NOT NULL,
  `txn_type` varchar(75) NOT NULL,
  `channel` varchar(10) NOT NULL DEFAULT 'offline',
  `paid` varchar(30) NOT NULL DEFAULT 'offline and online',
  `txn_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `months` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1088 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

DROP TABLE IF EXISTS `brand`;
CREATE TABLE IF NOT EXISTS `brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(75) NOT NULL,
  `brand` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=150 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`id`, `user_id`, `brand`) VALUES
(105, 69, 'BALTRA'),
(107, 85, 'MAMMY POKO '),
(108, 85, 'little angel'),
(109, 85, 'cuddlers'),
(110, 85, 'stayfree'),
(111, 69, 'Hoco'),
(112, 88, 'joy'),
(114, 88, 'gift'),
(115, 79, 'Yasuda'),
(116, 92, 'YASUDA'),
(117, 101, 'BALTRA'),
(118, 92, 'Samsung'),
(119, 92, 'Apple'),
(120, 105, 'Nivea'),
(121, 105, 'Ponds'),
(122, 112, 'Hisense'),
(123, 112, 'Goldstar'),
(124, 112, 'Jmary'),
(125, 112, 'Zomai'),
(126, 112, 'Caliber'),
(127, 112, 'DJI'),
(128, 112, 'Yunteng'),
(129, 112, 'OPPO'),
(130, 112, 'Redmi'),
(131, 112, 'TP Link'),
(132, 112, 'Simpex'),
(133, 112, 'DM'),
(134, 112, 'HiTech'),
(135, 112, '3120A'),
(136, 112, 'Phantom'),
(137, 112, 'Td'),
(138, 112, 'Octopus'),
(139, 117, 'FOGG'),
(140, 118, 'DELL'),
(141, 118, 'LENEVO'),
(142, 122, 'Hawkins'),
(143, 122, 'Realme'),
(144, 122, 'VIVO'),
(145, 122, 'POCO'),
(146, 123, 'Wega'),
(147, 123, 'Idea'),
(148, 124, 'Dental'),
(149, 123, 'SANSUI');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(75) NOT NULL,
  `items` text COLLATE utf8_unicode_ci,
  `qty` int(11) NOT NULL DEFAULT '1',
  `size` varchar(125) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `seller_id` int(11) NOT NULL,
  `expire_date` datetime DEFAULT NULL,
  `paid` tinyint(4) NOT NULL DEFAULT '0',
  `shipped` tinyint(4) NOT NULL DEFAULT '0',
  `count_cart` int(11) NOT NULL DEFAULT '0',
  `wishlist` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=219 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(75) NOT NULL,
  `categories` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=407 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `user_id`, `categories`, `parent`) VALUES
(313, 69, 'Heater ', 297),
(308, 69, 'Fan', 297),
(310, 69, 'ceiling fan', 297),
(311, 69, 'Table fan', 297),
(312, 69, 'Water dispenser ', 297),
(298, 69, 'Rice cooker', 297),
(297, 69, 'ELECTRONICS', 0),
(299, 69, 'Hose pipe', 297),
(301, 69, 'Cooker', 297),
(303, 69, 'Non stick ware', 297),
(304, 69, 'Electric kettle', 297),
(305, 69, 'Sandwich maker ', 297),
(306, 69, 'Delux rice cooker', 297),
(307, 69, 'Electric Fan ', 297),
(314, 69, 'Electric immerson rod', 297),
(315, 69, 'Water purifier', 297),
(316, 69, 'L.P.G stove crystal body', 297),
(317, 69, 'Hand blander &amp;chopper', 297),
(318, 69, 'Air frier', 297),
(319, 69, '  Electric jug', 297),
(338, 85, 'Mammy poko pants', 333),
(355, 0, 'Dispenser', 297),
(337, 85, 'Little angel', 333),
(336, 85, 'Himalaya pants', 333),
(325, 69, 'Induction cooker', 297),
(326, 69, 'Microwave&amp;OTG', 297),
(335, 85, 'cuddlers', 333),
(329, 69, 'Hot &amp; Normal', 297),
(330, 69, 'Mixer Grinder/JMG', 297),
(331, 69, 'Electric Gyser', 297),
(332, 69, 'Gamepad', 297),
(333, 85, 'COSMETIC', 0),
(334, 85, 'stayfree', 333),
(361, 0, 'Room Heater', 297),
(360, 0, 'Mixer Grinder', 389),
(357, 0, 'Halogen Heater', 297),
(362, 0, 'Speaker', 297),
(358, 0, 'Iron', 297),
(356, 0, 'Gas Geyser', 297),
(359, 0, 'Kettle', 297),
(363, 0, 'Vaccum flask', 297),
(373, 112, 'MEN&#039;S  FASHION', 0),
(369, 105, 'Body Loation', 333),
(370, 105, 'cream', 333),
(371, 105, 'facewash', 333),
(372, 112, 'TV', 297),
(374, 112, 'Shoes', 373),
(375, 112, 'Tripod/ camera stand', 297),
(376, 112, 'Toys, Kid&#039;s Fashion &amp; more', 0),
(377, 112, 'Stuffed toys', 376),
(378, 112, 'GROCERIES', 0),
(379, 112, 'SPORTS,  FITNESS  &amp;  OUTDOOR', 0),
(380, 112, 'Hair Trimmer', 297),
(381, 112, 'Water bottle flask', 297),
(382, 112, 'Lunch Box', 297),
(383, 112, 'Pressure Cooker', 389),
(384, 112, 'Drone', 297),
(385, 112, 'SURGICAL ITEMS', 0),
(386, 112, 'BOOKS , MUSIC &amp; MORE', 0),
(387, 112, 'Mobile Phones', 297),
(388, 112, 'Router', 297),
(389, 112, 'HOME &amp; KITCHEN&#039;S APPLIANCES', 0),
(390, 112, 'Kitchen &amp; Dining', 389),
(391, 112, 'Rotimaker', 297),
(392, 112, 'coffee maker', 297),
(393, 112, 'WOMEN&#039;S  FASHION', 0),
(394, 112, 'Stationary items', 386),
(395, 112, 'Books', 386),
(396, 112, 'Shoes', 393),
(397, 117, 'Eyeliner, Lipsticks &amp; Nail polish', 333),
(398, 117, 'HEALTH   &amp;  BEAUTY', 0),
(399, 117, 'Perfumes &amp; Body Spray', 398),
(400, 118, 'Laptop &amp; Ipad', 297),
(401, 118, 'Computers &amp;  Acessories', 297),
(402, 123, 'Frying pan', 389),
(403, 123, 'Tava', 389),
(404, 112, 'Fan Heater', 297),
(405, 124, 'Dental Books', 386),
(406, 105, 'Body lotion', 370);

-- --------------------------------------------------------

--
-- Table structure for table `credit_customer`
--

DROP TABLE IF EXISTS `credit_customer`;
CREATE TABLE IF NOT EXISTS `credit_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(75) NOT NULL,
  `bill_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(20) DEFAULT NULL,
  `credit_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `credit_cus_statement`
--

DROP TABLE IF EXISTS `credit_cus_statement`;
CREATE TABLE IF NOT EXISTS `credit_cus_statement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(75) NOT NULL,
  `bill_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `product` varchar(5000) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `credit_amt` int(50) DEFAULT NULL,
  `deposit_amt` int(50) DEFAULT NULL,
  `txn_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `expenditure`
--

DROP TABLE IF EXISTS `expenditure`;
CREATE TABLE IF NOT EXISTS `expenditure` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `user_id` int(75) NOT NULL,
  `exp_name` varchar(150) NOT NULL,
  `exp_amt` decimal(10,0) NOT NULL,
  `exp_date` date NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `parties`
--

DROP TABLE IF EXISTS `parties`;
CREATE TABLE IF NOT EXISTS `parties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(75) NOT NULL,
  `party_name` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `join_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `parties_payment`
--

DROP TABLE IF EXISTS `parties_payment`;
CREATE TABLE IF NOT EXISTS `parties_payment` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `user_id` int(75) NOT NULL,
  `party_id` int(50) NOT NULL,
  `bill_date` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `paid` double NOT NULL,
  `paid_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=132 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `parties_product`
--

DROP TABLE IF EXISTS `parties_product`;
CREATE TABLE IF NOT EXISTS `parties_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(75) NOT NULL,
  `title` varchar(255) NOT NULL,
  `cost_price` decimal(10,0) NOT NULL,
  `selll_price` decimal(10,0) NOT NULL,
  `party_id` varchar(50) NOT NULL,
  `party_bill_id` varchar(50) NOT NULL,
  `size` varchar(125) NOT NULL,
  `qty` varchar(50) NOT NULL,
  `txn_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=148 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `party_bill_no`
--

DROP TABLE IF EXISTS `party_bill_no`;
CREATE TABLE IF NOT EXISTS `party_bill_no` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `user_id` int(75) NOT NULL,
  `parties_id` int(20) NOT NULL,
  `bill_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `bill_date` date NOT NULL,
  `paid` int(50) NOT NULL DEFAULT '0',
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=80 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  `barcode` varchar(75) COLLATE utf8_unicode_ci DEFAULT NULL,
  `model_no` varchar(75) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `buy_price` decimal(10,0) DEFAULT '0',
  `selll_price` decimal(10,0) DEFAULT '0',
  `mrp_price` decimal(10,0) DEFAULT '0',
  `brand` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `categories` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sub_child_id` int(25) DEFAULT NULL,
  `image` text COLLATE utf8_unicode_ci,
  `description` text COLLATE utf8_unicode_ci,
  `featured` tinyint(4) NOT NULL DEFAULT '0',
  `sizes` text COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `txn_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=612 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `search`
--

DROP TABLE IF EXISTS `search`;
CREATE TABLE IF NOT EXISTS `search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `search_found` varchar(280) DEFAULT NULL,
  `search_not_found` varchar(280) DEFAULT NULL,
  `search_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `prod_details` text COLLATE utf8_unicode_ci NOT NULL,
  `address_details` text COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(175) COLLATE utf8_unicode_ci NOT NULL,
  `phone_no` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `payment_details` text COLLATE utf8_unicode_ci NOT NULL,
  `payment_mode` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `channel` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'online',
  `paid` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'offline and online',
  `pickup` tinyint(4) NOT NULL DEFAULT '0',
  `dispatched` tinyint(4) NOT NULL DEFAULT '0',
  `delivered` tinyint(4) NOT NULL DEFAULT '0',
  `txn_date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=96 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(75) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Birthdate` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `Gender` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `addr` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `village` varchar(35) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `district` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `phone_no` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `reg_code` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `shop_name` varchar(125) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `join_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime DEFAULT NULL,
  `permissions` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'customer',
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=130 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `last_name`, `email`, `password`, `Birthdate`, `Gender`, `addr`, `village`, `district`, `phone_no`, `reg_code`, `shop_name`, `join_date`, `last_login`, `permissions`, `deleted`) VALUES
(129, 'PRMART', NULL, 'prmart@gmail.com', '$2y$10$d.TsRfL/ht/UfBT1c1V/Z.CMWnktYLTieDAuR450PqxuLnjfTPxcC', '0', 'male', 'Ramgopalpur', '0', '0', '9825828666', '', 'PRMART', '2022-08-28 23:26:57', '2022-08-29 00:43:36', 'admin,seller', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

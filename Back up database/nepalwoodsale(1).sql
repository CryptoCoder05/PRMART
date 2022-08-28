-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 08, 2020 at 04:02 PM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nepalwoodsale`
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
) ENGINE=MyISAM AUTO_INCREMENT=127 DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM AUTO_INCREMENT=1141 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `barcode_cart`
--

INSERT INTO `barcode_cart` (`id`, `user_id`, `product_id`, `title`, `cost_price`, `MRP`, `selll_price`, `discount`, `quantity`, `items`, `paid`, `deleted`, `txn_date`) VALUES
(1140, 64, 229, 'Amaze Shoppee Wooden Wall Mounted Shelf Rack for Living Room Decor (Black) - Set of 4 (Design1A)', '599', 3000, '999', 0, '1', 'wall mounted', 0, 0, '2020-10-08 21:21:54');

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
) ENGINE=MyISAM AUTO_INCREMENT=662 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `barcode_transaction`
--

INSERT INTO `barcode_transaction` (`id`, `user_id`, `barcode_id`, `bill_no`, `title`, `items`, `cost_price`, `selll_price`, `qty`, `discount`, `grand_total`, `txn_type`, `channel`, `paid`, `txn_date`, `months`, `day`) VALUES
(653, 64, '229', 'OR894782', 'Amaze Shoppee Wooden Wall Mounted Shelf Rack for Living Room Decor (Black) - Set of 4 (Design1A)', 'wall mounted', '599', '999', 2, 200, '1798', 'cash', 'offline', 'offline and online', '2020-10-06 22:47:46', 10, 6),
(654, 64, '226', 'OR894782', 'Furny Mint L-Shaped Reversible Sofa', 'sofa', '10700', '15300', 1, 0, '15300', 'cash', 'offline', 'offline and online', '2020-10-06 22:47:46', 10, 6),
(655, 64, '223', 'OR894782', 'home by Nilkamal Era Single Seater Recliner', 'recliner', '20000', '24899', 1, 0, '24899', 'cash', 'offline', 'offline and online', '2020-10-06 22:47:46', 10, 6),
(656, 64, '233', 'OR381450', 'fast', 'a', '5', '10', 1, 0, '10', 'cash', 'offline', 'offline and online', '2020-10-07 21:42:12', 10, 6),
(657, 64, '234', 'OR381450', 'aaaaa', 's', '0', '15', 3, 0, '45', 'cash', 'offline', 'offline and online', '2020-10-07 21:42:12', 10, 7),
(658, 64, '229', 'OR240325', 'Amaze Shoppee Wooden Wall Mounted Shelf Rack for Living Room Decor (Black) - Set of 4 (Design1A)', 'wall mounted', '599', '999', 1, 0, '999', 'cash', 'offline', 'offline and online', '2020-10-08 09:46:25', 10, 7),
(659, 64, '224', 'OR240325', 'Aart Store Wall Shelf 3 Set Wall Mount Corner Wood Floating with Rack Home Decor for Living Room Bedroom Bathroom Kitchen ', 'floating rack', '200', '499', 1, 0, '499', 'cash', 'offline', 'offline and online', '2020-10-08 09:46:25', 10, 8),
(660, 64, '235', 'OR398258', 'bbb', 'm', '0', '15', 1, 0, '15', 'e-sewa', 'offline', 'offline and online', '2020-10-08 09:57:10', 10, 8),
(661, 64, '236', 'OR398258', 'ccc', 'M', '4', '5', 3, 7, '8', 'e-sewa', 'offline', 'offline and online', '2020-10-08 09:57:10', 10, 8);

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
) ENGINE=MyISAM AUTO_INCREMENT=104 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`id`, `user_id`, `brand`) VALUES
(100, 64, 'walnut'),
(101, 64, 'Beige'),
(102, 64, 'Aart'),
(103, 64, 'wenge');

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
) ENGINE=MyISAM AUTO_INCREMENT=184 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `items`, `qty`, `size`, `seller_id`, `expire_date`, `paid`, `shipped`, `count_cart`, `wishlist`) VALUES
(182, 68, '223', 2, 'recliner', 64, NULL, 0, 0, 0, 0),
(183, 68, '229', 3, 'wall mounted', 64, NULL, 0, 0, 0, 0);

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
) ENGINE=MyISAM AUTO_INCREMENT=272 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `user_id`, `categories`, `parent`) VALUES
(260, 64, 'Tier', 0),
(261, 64, 'book tier', 260),
(262, 64, 'Recliner', 0),
(263, 64, 'single seater', 262),
(264, 64, 'Rack', 0),
(265, 64, 'floating rack', 264),
(266, 64, 'Shoe rack', 264),
(267, 64, 'Sofa', 0),
(268, 64, 'L-shape', 267),
(269, 64, 'Table', 0),
(270, 64, 'Coffee Table', 269),
(271, 64, 'TV table', 269);

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
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `buy_price` decimal(10,0) DEFAULT '0',
  `selll_price` decimal(10,0) DEFAULT '0',
  `mrp_price` decimal(10,0) DEFAULT '0',
  `brand` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `categories` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` text COLLATE utf8_unicode_ci,
  `description` text COLLATE utf8_unicode_ci,
  `featured` tinyint(4) NOT NULL DEFAULT '0',
  `sizes` text COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `txn_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=237 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `user_id`, `barcode`, `title`, `buy_price`, `selll_price`, `mrp_price`, `brand`, `categories`, `image`, `description`, `featured`, `sizes`, `deleted`, `txn_date`) VALUES
(223, '64', '98415198', 'home by Nilkamal Era Single Seater Recliner', '20000', '24899', '49500', '101', '263', 'images/products/f39af30e05d970e3c00c082ca941ef89.jpg', '<h1>About this item</h1>\r\n\r\n<ul>\r\n	<li>Product Dimensions: Length (91 cm), Width (98 cm), Height (48 cm)</li>\r\n	<li>Primary Material: Micro Fiber, Upholstery Material: Fabric</li>\r\n	<li>Color: Beige, Style: Contemporary</li>\r\n	<li>Seating Capacity: Single Seat</li>\r\n	<li>No Assembly Required: The product is delivered in a pre-assembled state</li>\r\n	<li>Warranty: 1 year Warranty on Manufacturing defects</li>\r\n	<li>Wide arm rest provides comfort while seating</li>\r\n</ul>\r\n', 1, 'recliner:2:5', 0, '2020-09-23 20:16:37'),
(224, '64', '648751', 'Aart Store Wall Shelf 3 Set Wall Mount Corner Wood Floating with Rack Home Decor for Living Room Bedroom Bathroom Kitchen ', '200', '499', '1899', '102', '265', 'images/products/e8cbdd0ef5db5b57f7a99560364c2ef4.jpg', '<h1>About this item</h1>\r\n\r\n<ul>\r\n	<li>Material: Wood, Color: Brown</li>\r\n	<li>Made of High Quality Durable Long Life MDF With Matte Finish</li>\r\n	<li>Perfect for storage and wall display of CD, DVD, books, toys etc</li>\r\n	<li>Multiuse Braine FLoor Standing Corner Shelves can be used as Wall decor shelf and display in living and dining rooms or offices.</li>\r\n</ul>\r\n', 1, 'floating rack:19:20', 0, '2020-09-23 20:20:08'),
(222, '64', '436799', 'Nilkamal Checkers 3 Tier Book Shelf', '1500', '3099', '5900', '100', '261', 'images/products/8860d470cb5f431b79a7a45e837f3a4a.jpg', '<h1>About this item</h1>\r\n\r\n<ul>\r\n	<li>Product dimension: Height (108 centimeters) Length (60 centimeters) weight (24 centimeters)</li>\r\n	<li>Primary Material: Engineered Wood</li>\r\n	<li>Color: Walnut, Style: Contemporary</li>\r\n	<li>Requires Assembly</li>\r\n	<li>Warranty: 1 Year on manufacturing defects</li>\r\n	<li>Uniqueness and unconventional designs lie at the core of modern furniture and contemporary home decor</li>\r\n</ul>\r\n', 1, 'Melamine Finish:23:24', 0, '2020-09-23 20:11:26'),
(225, '64', '755484', 'Zemic 6 Layer Multipurpose Portable Folding Shoes Rack/Shoes Shelf/Shoes Cabinet with Wardrobe Cover, Easy Installation Stand for Shoes(Shoes Rack)(Shoes Rack, Shoes Racks for Home)_6 Layer Grey ', '500', '899', '8586', '101', '266', 'images/products/57ddae3c374d85a24c0019750c7f9b19.jpg', '<ul>\r\n	<li>SELECTED STRONG MATERIAL: Constructed from selected Non-woven Fabric, high quality steel tube and PP Plastic connectors, this shoe tower will offer you long term organization system</li>\r\n	<li>ABUNDANT STORAGE SPACE: Durable 6 tiers Shoe Rack can Store up to 24 pairs of shoes to keep your bedroom, hallway or mudroom well organized; Side pockets design for storing shoe brush, keys or other sundries</li>\r\n	<li>SASIMO SPACE SAVING SHOE ORGANIZER: Great this amazing shoe rack will suitable for your cubby walk-in closet, entryway or garage, with 6 3/4&quot; Layer Height for regular heels, sneakers or flats</li>\r\n	<li>DUSTPROOF &amp; EASY TO ASSEMBLE: Shoe rack with zippered cover keeps your shoes away from dust and un-viewable, with units snap together for easy assembly and no tool required</li>\r\n	<li>DIMENSIONS &ndash; (56 x 26 x 108 Cm), COLOUR &ndash; Multi-Color, MATERIAL &ndash; Metal and fiber material With Non Woven Fabric Cover</li>\r\n</ul>\r\n', 1, 'shoe rack:10:10', 0, '2020-09-23 20:23:01'),
(226, '64', '46199', 'Furny Mint L-Shaped Reversible Sofa', '10700', '15300', '25000', '101', '268', 'images/products/f40d0117982e5d8138fd4924a0a64972.jpg', '<ul>\r\n	<li>Primary Material: Solid wood, Upholstery Material: Premium Fabric &amp; Leatherette</li>\r\n	<li>Color: Beige-Brown, Style: Modern, Seating Capacity: Four seater. No Assembly Required: DIY Product, Warranty Details: The 12 months warranty on sofas is on the frame, structure &amp; mechanisms if any .The warranty covers manufacturing/ workmanship and material defects that have occurred during the warranty period. The warranty applies to furniture used under normal household conditions.</li>\r\n	<li>Wooden frame, upholstered sofa with high density foam, Solid framework that makes it a very strong and durable sofa</li>\r\n	<li>Get the best in class products made of quality materials for long term furniture needs.</li>\r\n	<li>Furny Tollfree : 1800-84-38769</li>\r\n</ul>\r\n', 1, 'sofa:19:20', 0, '2020-09-23 20:25:54'),
(227, '64', '494519', 'Furniture Cafe Engineered Wood Intersecting Wall Shelves/Shelf for Living Room | Set of 4 | White ', '1300', '1911', '3000', '100', '261', 'images/products/993fef027c2cc7058478139bcd981a70.jpg', '<ul>\r\n	<li>Made With High Durable Quality Engineered Wood</li>\r\n	<li>Dimensions: 25.500 inches H x 17.750 inches W x 4.000 inches D</li>\r\n	<li>Best Available Space And New Design To Enhance Look Of Your Home</li>\r\n	<li>Made With High Durable Material Gives You Assurance Of Stability Of Item</li>\r\n</ul>\r\n', 1, 'Wall Shelves:8:10', 0, '2020-09-23 20:28:11'),
(228, '64', '14468985', 'DeckUp Siena Coffee Table (Wenge, Matte Finish) ', '2200', '3299', '7000', '103', '270', 'images/products/50d217e5916d504120b787f06479b134.jpg', '<ul>\r\n	<li>Product Dimensions: Length (35 Inches / 90 CMs), Width (16 Inches / 40 CMs), Height (15 Inches / 37 CMs)</li>\r\n	<li>Primary Material: Engineered Wood with Laminate</li>\r\n	<li>Color: Wenge, Finish: Matte Finish, Style: Contemporary</li>\r\n	<li>Assembly Required: The product requires basic assembly and comes with DIY (Do-It-Yourself) assembly instructions</li>\r\n	<li>Care Instructions: Wipe it clean with a dry cloth. Do not use water. Wipe any spills immediately</li>\r\n</ul>\r\n', 1, 'coffee table:10:12', 0, '2020-09-23 20:50:11'),
(229, '64', '16516488', 'Amaze Shoppee Wooden Wall Mounted Shelf Rack for Living Room Decor (Black) - Set of 4 (Design1A)', '599', '999', '3000', '102', '265', 'images/products/4c8cdb1651e649215fab69c501d44d2d.jpg', '<ul>\r\n	<li>Floating Shelves Material Mdf, Color : Black</li>\r\n	<li>Shelving Unit Dimensions : Size: 47 Cm X 10 Cm X 65 Cm</li>\r\n	<li>Functional Storage Shelves - Useful For Adding Additional Shelving Space To Store And Organize Small Items Or Clutter In Bedroom, Bathroom, Kitchen And More, Great For Clearing Up The Counter</li>\r\n	<li>Simple Wall Shelf - Simple Design Floating Shelves For Bedroom Wall Decor Perfect For Displaying And Holding Collectibles, Small Plants, Stuffed Animals And More ã€‚</li>\r\n	<li>Super sturdy, easy to follow instruction and all mounting hardwares included, super easy to assemble and put up</li>\r\n</ul>\r\n', 1, 'wall mounted:6:12', 0, '2020-09-23 20:52:58'),
(230, '64', '48441565', 'Wudville Coober Engineered Wood TV Entertainment Wall Unit/Set Top Box Stand - Large ', '1500', '2399', '4299', '100', '271', 'images/products/21e390de755fc0565e1af89c29e239b7.jpg', '<h1>About this item</h1>\r\n\r\n<ul>\r\n	<li>Product dimensions: Length (105cm ) x Breadth (23.5cm ) x Height (23.5cm)</li>\r\n	<li>Made of Particle Board (High grade prelam engineering wood with natural wood grain finish.)</li>\r\n	<li>Product Color: Wenge, Product Style: Contemporary | Weight: 8 Kgs | Ideal TV Size- Upto 42 Inches.</li>\r\n	<li>Product Warranty :: All of our products are made with care and covered for 1 year against manufacturing defects.</li>\r\n	<li>Assembly or Installation: This is a DIY (Do-It-Yourself) item and comes with necessary hardware &amp; detailed installation guide. We&rsquo;d recommend hiring an experienced, professional for installation, however, if you&rsquo;re confident with intermediate DIY tasks, you could save time and money by installing it yourself.&nbsp; For on-call assistance please contact us @8800609609 / 8826006612 (Call/Whastapp).</li>\r\n</ul>\r\n', 1, 'tv table:11:12', 0, '2020-09-23 20:56:18'),
(231, '64', '5487516', 'SS ARTS Engineered Wood Living Room Coffee Table with Magazine Storage Stand (35 cm x 24 cm x 60 cm, White)', '1100', '1990', '2900', '100', '270', 'images/products/287acd3186ad207114c4f7452301ee2a.jpg', '<h1>About this item</h1>\r\n\r\n<ul>\r\n	<li>100% brand new and high quality; Material: Wood; Color: White</li>\r\n	<li>Style: modern &amp; stylish with hollow carved design; Easy by hand diy (do it yourself) installation</li>\r\n	<li>Anti-water and moisture, acid and pest resistant, contains no toxic chemicals or preservatives</li>\r\n	<li>Quality environmentally friendly wood-plastic plate material, hollow carved design</li>\r\n	<li>Product Dimensions (L x W x H): 35 cm x 24 cm x 60 cm</li>\r\n</ul>\r\n', 1, 'table:11:12', 0, '2020-09-23 21:05:03'),
(232, '64', '4651513', 'abc', '5', '10', '15', '102', '263', 'images/products/bca23158d936d2bf15cd0f86f9957e3b.jpg', '<p>srh</p>\r\n', 0, 'a:5:5', 0, '2020-10-06 21:15:51'),
(233, '64', '45343', 'fast', '5', '10', '15', '101', '263', 'images/products/aabdc92771eb10e08af35dd2b275d37b.jpg', '<p>afsg</p>\r\n', 0, 'a:14:15', 0, '2020-10-06 22:53:22'),
(234, '64', '4585658', 'aaaaa', '5', '15', '25', '101', '263', 'images/products/b1cd8d6a6525e10440c1b7bf35950865.jpg', '<p>gdzgn</p>\r\n', 0, 's:9:12', 0, '2020-10-07 21:41:10'),
(235, '64', '941319', 'bbb', '3', '15', '27', '101', '263', 'images/products/327f035c809a65de55f9ce4e9cab9fd1.jpg', '<p>sgshms</p>\r\n', 0, 'm:19:20', 0, '2020-10-08 09:47:33'),
(236, '64', '8414689', 'ccc', '4', '5', '6', '101', '268', 'images/products/99d68c05ccbd7f5e22bf81bca96153ff.jpg', '<p>agdbsg</p>\r\n', 0, 'M:17:20', 0, '2020-10-08 09:55:58');

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
) ENGINE=MyISAM AUTO_INCREMENT=90 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `customer_id`, `prod_details`, `address_details`, `email`, `phone_no`, `payment_details`, `payment_mode`, `channel`, `paid`, `pickup`, `dispatched`, `delivered`, `txn_date`) VALUES
(85, 68, '[{\"prod_id\":\"231\",\"qty\":\"1\",\"size\":\"table\",\"seller_id\":\"64\"},{\"prod_id\":\"226\",\"qty\":\"1\",\"size\":\"sofa\",\"seller_id\":\"64\"},{\"prod_id\":\"227\",\"qty\":\"1\",\"size\":\"Wall Shelves\",\"seller_id\":\"64\"}]', '{\"first_name\":\"Anirudh kumar\",\"last_name\":\"singh\",\"country\":\"Nepal\",\"state\":\"Mahottari\",\"city\":\"janakpur\",\"village\":\"khopi-09\"}', 'sanirudh120@gmail.com', '9844057518', '{\"subtotal\":\"19201\",\"delivery\":\"60\",\"discount\":\"60\",\"total\":\"19201\"}', 'COD', 'online', 'offline and online', 1, 1, 0, '2020-09-27 19:18:39'),
(88, 68, '[{\"prod_id\":\"230\",\"qty\":\"1\",\"size\":\"tv table\",\"seller_id\":\"64\"},{\"prod_id\":\"222\",\"qty\":\"1\",\"size\":\"Melamine Finish\",\"seller_id\":\"64\"}]', '{\"first_name\":\"prabin kumar\",\"last_name\":\"mahato\",\"country\":\"nepal\",\"state\":\"Mahottari\",\"city\":\"janakpur\",\"village\":\"Ramgopalpur -5\"}', 'prabin@gmail.com', '8217703511', '{\"subtotal\":\"5498\",\"delivery\":\"60\",\"discount\":\"60\",\"total\":\"5498\"}', 'COD', 'online', 'offline and online', 0, 0, 0, '2020-09-29 22:39:56'),
(89, 68, '[{\"prod_id\":\"229\",\"qty\":\"2\",\"size\":\"wall mounted\",\"seller_id\":\"64\"},{\"prod_id\":\"228\",\"qty\":\"2\",\"size\":\"coffee table\",\"seller_id\":\"64\"}]', '{\"first_name\":\"kaplu\",\"last_name\":\"singh\",\"country\":\"Nepal\",\"state\":\"Mahottari\",\"city\":\"janakpur\",\"village\":\"khopi-09\"}', 'kaplu@gmail.com', '85147852', '{\"subtotal\":\"5297\",\"delivery\":\"60\",\"discount\":\"60\",\"total\":\"5297\"}', 'COD', 'online', 'offline and online', 0, 0, 0, '2020-10-03 10:56:45');

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
) ENGINE=MyISAM AUTO_INCREMENT=69 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `last_name`, `email`, `password`, `Birthdate`, `Gender`, `addr`, `village`, `district`, `phone_no`, `reg_code`, `shop_name`, `join_date`, `last_login`, `permissions`, `deleted`) VALUES
(64, 'admin', NULL, 'admin@gmail.com', '$2y$10$5J00bK1I0D74y6kB9.is6ucvUoVLCstbCA0z7wCi9Uip8WrZm8tNu', '0', 'male', 'Khopi-09, mahottari', '0', '0', '9825828666', '', 'Nepalwoodsale', '2020-09-23 18:41:28', '2020-10-08 20:13:27', 'admin,seller', 0),
(66, 'Anirudh', 'singh', 'sanirudh120@gmail.com', '$2y$10$/R2Q6S7koWllfm2B3HSU2OkhrhGwiCjHV2nhREu34eudZUvLTyP9a', '0', '0', '0', '0', '0', '0', '0', '0', '2020-09-27 18:54:30', NULL, 'customer', 0),
(67, 'kaplu', 'singh', 'kaplu@gmail.com', '$2y$10$upwGqFeWx/HRc5zhkSCMXONCUO4RIP6AIVgsjWmPjvyB0HY/egLeO', '0', '0', '0', '0', '0', '0', '0', '0', '2020-09-27 18:58:21', NULL, 'customer', 0),
(68, 'prabin', 'mahato', 'prabin@gmail.com', '$2y$10$RFWPAinnCswXqY.yLQ6OFe5yWhEFOGk1uqjYDz7qSg0vKZCIm3tPi', '0', '0', '0', '0', '0', '9844057518', '0', '0', '2020-09-27 19:11:55', '2020-10-07 22:33:46', 'customer', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

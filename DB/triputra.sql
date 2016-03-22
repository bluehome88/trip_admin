-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Mar 16, 2016 at 07:29 PM
-- Server version: 5.5.42
-- PHP Version: 5.6.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `triputra`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categoryID` int(20) NOT NULL,
  `categoryName` varchar(200) CHARACTER SET utf8 NOT NULL,
  `categoryCode` varchar(20) CHARACTER SET utf8 NOT NULL,
  `storeID` int(200) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`categoryID`, `categoryName`, `categoryCode`, `storeID`, `active`) VALUES
(1, 't-Shirt', 'AJP', 1, 1),
(2, 'Category 2', 'CAT2', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `commentID` int(200) NOT NULL,
  `topicID` int(200) NOT NULL,
  `userID` int(11) NOT NULL,
  `contents` text CHARACTER SET utf8 NOT NULL,
  `date_added` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `newsID` int(200) NOT NULL,
  `newsTitle` varchar(200) CHARACTER SET utf8 NOT NULL,
  `newsContent` blob NOT NULL,
  `imgPath` varchar(200) CHARACTER SET utf8 NOT NULL,
  `date_added` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`newsID`, `newsTitle`, `newsContent`, `imgPath`, `date_added`, `date_updated`, `status`) VALUES
(7, 'hggbgfb', 0x6267626766, 'uploads/news/2016-01-08arnold-avatar.1df41c32.jpg', '2016-01-07 10:53:56', '2016-01-08 20:36:34', 1),
(8, '25842543546356', 0x363534333635343336353433363534, 'uploads/news/2016-01-10logo.png', '2016-01-07 10:54:15', '2016-01-10 19:38:27', 1),
(9, 'ppppqq', 0x707070700a66647361666473616664736166647361660a64736166647361, 'uploads/news/2016-01-08pro2.jpg', '2016-01-07 10:54:19', '2016-01-08 20:13:29', 1),
(10, '1233333333333333333333333333333333333333333333333333', 0x3132330a666473610a6664730a61660a64730a610a66647361, 'uploads/news/2016-01-08pro1.jpg', '2016-01-08 15:31:35', '2016-01-10 19:38:47', 1),
(11, '345', 0x3334350a0a666473616664730a6765776772770a677265770a677265770a677265776772657767, 'uploads/news/2016-01-08pro-1.jpg', '2016-01-08 15:31:46', '2016-01-08 19:53:40', 1),
(12, '456', 0x3435360a3334350a363738, 'uploads/news/2016-01-08pro-thumb-big.jpg', '2016-01-08 15:31:54', '2016-01-08 19:52:07', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderID` int(200) NOT NULL,
  `userID` int(200) NOT NULL,
  `orderDate` date NOT NULL,
  `orderQty` int(20) NOT NULL,
  `totalPrice` decimal(32,2) NOT NULL,
  `routeID` int(200) NOT NULL,
  `date_updated` datetime NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderID`, `userID`, `orderDate`, `orderQty`, `totalPrice`, `routeID`, `date_updated`, `active`) VALUES
(1, 2, '2016-03-16', 35, '20.00', 1, '2016-03-16 04:59:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_products`
--

CREATE TABLE `order_products` (
  `orderID` int(200) NOT NULL,
  `productID` int(200) NOT NULL,
  `qty` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_products`
--

INSERT INTO `order_products` (`orderID`, `productID`, `qty`) VALUES
(1, 1, 20),
(1, 2, 15);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `productID` int(200) NOT NULL,
  `product_code` varchar(32) NOT NULL DEFAULT '',
  `categoryID` int(32) NOT NULL,
  `product_name` varchar(512) NOT NULL DEFAULT '',
  `unit` varchar(32) NOT NULL DEFAULT '',
  `price` decimal(32,2) DEFAULT NULL,
  `min_price` decimal(32,2) DEFAULT NULL,
  `active` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`productID`, `product_code`, `categoryID`, `product_name`, `unit`, `price`, `min_price`, `active`) VALUES
(1, 'AJP-PGK', 1, 'PENGKI KARET HITAM / PCS', 'BH', '10.00', '10.00', 1),
(2, 'PRO_CODE1111', 2, 'Product !', 'block', '20.00', '20.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `route`
--

CREATE TABLE `route` (
  `routeID` int(100) NOT NULL,
  `storeID` int(200) NOT NULL,
  `route_date` datetime NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `route`
--

INSERT INTO `route` (`routeID`, `storeID`, `route_date`, `active`) VALUES
(1, 1, '0000-00-00 00:00:00', 1),
(2, 1, '2016-03-16 16:09:24', 1);

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `storeID` int(200) NOT NULL,
  `storeName` text CHARACTER SET utf8 NOT NULL,
  `storeAddress` text CHARACTER SET utf8 NOT NULL,
  `storeCity` text CHARACTER SET utf8 NOT NULL,
  `storePostcode` text CHARACTER SET utf8 NOT NULL,
  `storePhone` varchar(30) CHARACTER SET utf8 NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`storeID`, `storeName`, `storeAddress`, `storeCity`, `storePostcode`, `storePhone`, `active`) VALUES
(1, 'PT jays-from DB', 'District 3 - kk V233 34', 'Jakarta', '66779', '5677 8898', 1);

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE `topics` (
  `topicID` int(200) NOT NULL,
  `storeID` int(200) NOT NULL,
  `topicTitle` text CHARACTER SET utf8 NOT NULL,
  `date_added` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`topicID`, `storeID`, `topicTitle`, `date_added`, `date_updated`, `active`) VALUES
(1, 1, 'First Topic News', '2016-03-16 00:00:00', '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(20) NOT NULL,
  `firstName` varchar(50) CHARACTER SET utf8 NOT NULL,
  `lastName` varchar(50) CHARACTER SET utf8 NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 NOT NULL,
  `password` varchar(42) NOT NULL,
  `role` int(1) NOT NULL DEFAULT '3' COMMENT '1: super, 2: manager, 3: user',
  `date_added` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `customerCode` varchar(32) CHARACTER SET utf8 NOT NULL,
  `areaInfo` varchar(64) CHARACTER SET utf8 NOT NULL,
  `address1` varchar(512) CHARACTER SET utf8 NOT NULL,
  `address2` varchar(512) CHARACTER SET utf8 NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `firstName`, `lastName`, `username`, `email`, `password`, `role`, `date_added`, `date_updated`, `customerCode`, `areaInfo`, `address1`, `address2`, `active`) VALUES
(1, 'Super', 'Admin', 'admin', 'a@a.com', '123456789', 1, '2016-01-05 10:11:34', '2016-01-11 08:42:23', '', '', '', '', 1),
(2, 'Jennifer', 'Minely', '', 'ln1@triputra.com', '123456789', 1, '0000-00-00 00:00:00', '2016-01-08 12:16:26', '', '', '', '', 1),
(3, 'user2', 'lastname2', '', 'ln2@triputra.com', '123456789', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', 1),
(4, 'user3', 'lastname3', '', 'ln3@triputra.com', '123456789', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', 1),
(5, 'user4', 'lastname4', '', 'ln4@triputra.com', '123456789', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', 1),
(6, 'user5', 'lastname5', '', 'ln5@triputra.com', '123456789', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', 1),
(7, 'user6', 'lastname6', '', 'ln6@triputra.com', '123456789', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', 1),
(8, 'user7', 'lastname7', '', 'ln7@triputra.com', '123456789', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', 1),
(9, 'user8', 'lastname8', '', 'ln8@triputra.com', '123456789', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', 1),
(10, 'user9', 'lastname9', '', 'ln9@triputra.com', '123456789', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', 1),
(11, 'user11', 'lastname1', '', 'ln10@triputra.com', '123456789', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', 1),
(12, 'user10', 'lastname10', 'user10', '1@1.1', '123456789', 1, '0000-00-00 00:00:00', '2016-01-19 20:04:05', '', '', '', '', 1),
(54, 'aaa', 'user', 'aaa', '2@2.2', '123456789', 3, '2016-01-08 05:12:51', '2016-01-13 06:12:38', '', '', '', '', 1),
(55, 'Branch', 'Admin', 'aaaa', 'b@a.com', '123456789', 2, '2016-01-11 06:27:44', '2016-01-13 06:16:51', '', '', '', '', 1),
(56, 'Sergei', 'Davydov', '', 'sergei.dav87@gmail.com', 'flying0now!', 1, '2016-01-11 07:07:08', '2016-01-11 07:07:08', '', '', '', '', 1),
(57, 'rewqrewqre', 'wqrewqrewqrew', '', 'sergei.dav87@gmail.co', 'fdsafdsafdsafdsa', 1, '2016-01-11 07:07:26', '2016-01-13 06:10:24', '', '', '', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `x_customer`
--

CREATE TABLE `x_customer` (
  `customer_code` varchar(32) NOT NULL DEFAULT '',
  `customer_name` varchar(512) NOT NULL DEFAULT '',
  `address_1` varchar(512) NOT NULL DEFAULT '',
  `address_2` varchar(512) NOT NULL DEFAULT '',
  `sales_in_charge` varchar(32) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `x_customer`
--

INSERT INTO `x_customer` (`customer_code`, `customer_name`, `address_1`, `address_2`, `sales_in_charge`) VALUES
('A00001', 'A 1 - TAMAN PALEM CENGKARENG', 'KOMP CITY RESORT RUKAN MALIBU BLOK I NO.3-5', 'CENGKARENG - TAMAN PALEM (DEKAT HOTEL ASTON)', 'MSTK');

-- --------------------------------------------------------

--
-- Table structure for table `x_route`
--

CREATE TABLE `x_route` (
  `route_id` int(11) NOT NULL,
  `day` varchar(2) NOT NULL DEFAULT '',
  `address` varchar(512) NOT NULL,
  `sales` varchar(32) NOT NULL DEFAULT '',
  `customer` varchar(32) NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `x_route`
--

INSERT INTO `x_route` (`route_id`, `day`, `address`, `sales`, `customer`) VALUES
(11, 'MT', 'CENGKARENG - TAMAN PALEM (DEKAT HOTEL ASTON)', 'MSTK', 'A00001');

-- --------------------------------------------------------

--
-- Table structure for table `x_sales`
--

CREATE TABLE `x_sales` (
  `sales_code` varchar(32) NOT NULL DEFAULT '',
  `sales_name` varchar(512) NOT NULL DEFAULT '',
  `nik` varchar(512) NOT NULL DEFAULT '',
  `position` varchar(32) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `x_sales`
--

INSERT INTO `x_sales` (`sales_code`, `sales_name`, `nik`, `position`) VALUES
('MTSK', 'Moh. Totok Sulaiman Khan', '22-00099', 'SLS');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryID`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`commentID`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`newsID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`productID`);

--
-- Indexes for table `route`
--
ALTER TABLE `route`
  ADD PRIMARY KEY (`routeID`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`storeID`);

--
-- Indexes for table `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`topicID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `x_customer`
--
ALTER TABLE `x_customer`
  ADD PRIMARY KEY (`customer_code`);

--
-- Indexes for table `x_route`
--
ALTER TABLE `x_route`
  ADD PRIMARY KEY (`route_id`);

--
-- Indexes for table `x_sales`
--
ALTER TABLE `x_sales`
  ADD PRIMARY KEY (`sales_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryID` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `commentID` int(200) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `newsID` int(200) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderID` int(200) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `productID` int(200) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `route`
--
ALTER TABLE `route`
  MODIFY `routeID` int(100) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `storeID` int(200) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `topics`
--
ALTER TABLE `topics`
  MODIFY `topicID` int(200) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=58;
--
-- AUTO_INCREMENT for table `x_route`
--
ALTER TABLE `x_route`
  MODIFY `route_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

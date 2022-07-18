-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 18, 2022 at 03:27 PM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nopel_store`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `collectProductsByCategory` ()   BEGIN
	SELECT COUNT(*) FROM products;
    SELECT * FROM categories;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int(11) NOT NULL,
  `Ordering` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `QTY` int(11) NOT NULL,
  `Visibility` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1 Is Yes 0 Is No',
  `Allow_Comment` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1 Is Yes 0 Is No',
  `Allow_Ads` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1 Is Yes 0 Is No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Ordering`, `Name`, `Description`, `QTY`, `Visibility`, `Allow_Comment`, `Allow_Ads`) VALUES
(10, 1, 'Hand Made', '', 0, 1, 1, 1),
(11, 2, 'Computers', '', 0, 1, 1, 1),
(12, 3, 'Cell Phones', '', 0, 1, 1, 1),
(13, 4, 'Fashion', '', 0, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `ID` int(11) NOT NULL,
  `Comment` varchar(255) NOT NULL,
  `Product_id` int(11) NOT NULL,
  `Customer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `ID` int(11) NOT NULL,
  `Full_Name` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Phone` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Image` varchar(255) NOT NULL,
  `DateOfCreate` date NOT NULL,
  `Birthdate` date NOT NULL,
  `RegStatus` tinyint(4) NOT NULL DEFAULT 0,
  `Blocked` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`ID`, `Full_Name`, `Username`, `Email`, `Address`, `Phone`, `Password`, `Image`, `DateOfCreate`, `Birthdate`, `RegStatus`, `Blocked`) VALUES
(1, 'Nabil Hamada', 'nabil', 'n@n.com', 'kteeeer', '01118172639', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', '', '2022-02-26', '2000-07-24', 1, 0),
(6, 'Nopel', 'asdsad', 'Nabilhamada421@gmail.com', '', '01148599674', '7ee9da7d594cff9f134268a3ac98e10beba66194', '', '2022-02-26', '2013-02-14', 0, 1),
(7, 'Youssef Ismail Kamel', 'nshjdk', 'hgjh@jh.cl', '', '12121212121', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', '', '2022-03-12', '0000-00-00', 0, 0),
(8, 'Fahd Ahmed Rizq', 'zasaa', 'nahjjsk@ll.c', '', '17655762517', '21c43fbc3342c17394417a3f43b3ee7d44c0cedd', '', '2022-03-12', '0000-00-00', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `ID` int(11) NOT NULL,
  `Full_Name` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Phone` varchar(11) NOT NULL,
  `Country` varchar(255) NOT NULL,
  `City` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Password` text NOT NULL,
  `Birthdate` date NOT NULL,
  `DateOfHiring` date NOT NULL,
  `Role_ID` int(11) NOT NULL,
  `Section_ID` int(11) NOT NULL,
  `RegStatu` tinyint(4) NOT NULL DEFAULT 0,
  `Disabled` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`ID`, `Full_Name`, `Username`, `Email`, `Phone`, `Country`, `City`, `Address`, `Password`, `Birthdate`, `DateOfHiring`, `Role_ID`, `Section_ID`, `RegStatu`, `Disabled`) VALUES
(2, 'Nabil Hamada Ebrahim', 'nabilhamada', 'nabilhamada421@gmail.com', '01118172639', 'Egypt', 'Cairo', 'Saied Yassin Street, Qotury, Ayat Center', 'c129b324aee662b04eccf68babba85851346dff9', '2000-07-24', '2022-03-11', 1, 1, 1, 0),
(4, 'Youssef Ismail Kamel', 'yousef', 'youssef@gamil.com', '01148599674', 'Egypt', 'Giza', 'egtrdgdr', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', '2001-06-16', '2022-03-11', 1, 2, 1, 0),

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `ID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Time` time NOT NULL,
  `Status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 = New, 1 = Pending, 2 = Complete, 3 = Reject',
  `Customer_ID` int(11) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Coupon` varchar(255) NOT NULL DEFAULT 'NO-COUPON',
  `Discount` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`ID`, `Date`, `Time`, `Status`, `Customer_ID`, `Address`, `Coupon`, `Discount`) VALUES
(1, '2022-01-03', '00:37:00', 2, 1, 'Egypt - Giza - Ayyat - Qotory', 'NEW-COUPON-25', 25),
(2, '2022-02-09', '07:43:00', 3, 6, 'Egypt - Giza - Ayyat - Qotory', 'NO-COUPON', 0),
(4, '2022-03-14', '23:59:00', 0, 6, '', 'NO-COUPON', 0),
(5, '2022-03-09', '19:38:00', 0, 7, '', 'NO-COUPON', 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `ID` int(11) NOT NULL,
  `Order_ID` int(11) NOT NULL,
  `Product_ID` int(11) DEFAULT NULL,
  `Count` int(11) NOT NULL,
  `Price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`ID`, `Order_ID`, `Product_ID`, `Count`, `Price`) VALUES
(1, 1, NULL, 2, 46),
(2, 2, NULL, 1, 12),
(3, 1, NULL, 1, 6),
(4, 1, NULL, 5, 250),
(5, 4, NULL, 10, 2100),
(6, 2, NULL, 34, 1000),
(7, 5, NULL, 2, 600);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Brand` varchar(255) NOT NULL,
  `Model` varchar(255) NOT NULL,
  `Price` int(11) NOT NULL,
  `Discount` int(11) NOT NULL DEFAULT 0,
  `Amount` int(11) NOT NULL,
  `Rating` tinyint(10) NOT NULL,
  `Category` int(11) NOT NULL,
  `Allow_Comments` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 Is Yes 0 Is No',
  `Status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 Is Yes 0 Is No',
  `By_Emp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ID`, `Title`, `Description`, `Brand`, `Model`, `Price`, `Discount`, `Amount`, `Rating`, `Category`, `Allow_Comments`, `Status`, `By_Emp`) VALUES
(29, 'new', 'khklhkj', 'my brand', 'my model', 250, 0, 300, 0, 13, 1, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `product_image`
--

CREATE TABLE `product_image` (
  `ID` int(11) NOT NULL,
  `Product_ID` int(11) NOT NULL,
  `Image_Path` varchar(255) NOT NULL,
  `Flag` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Images Of Product';

--
-- Dumping data for table `product_image`
--

INSERT INTO `product_image` (`ID`, `Product_ID`, `Image_Path`, `Flag`) VALUES
(24, 29, '../data/uploads/products/623b83cd83fc97.48032816.jpeg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Access` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`ID`, `Name`, `Access`) VALUES
(1, 'Manager', 'Full'),
(2, 'Super Visor', 'FullWrite'),
(3, 'Team Leader', 'Write'),
(4, 'Agent', 'Read');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Mobile` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`ID`, `Name`, `Description`, `Mobile`) VALUES
(1, 'Inpound', 'no', '238435107'),
(2, 'Outpound', '', ''),
(5, 'Sales', '', '32'),
(8, 'المبيعات', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `ID` int(11) NOT NULL,
  `Emp_ID` int(11) NOT NULL DEFAULT 0,
  `Language` varchar(2) NOT NULL DEFAULT 'en',
  `Display_Mode` varchar(10) NOT NULL DEFAULT 'light'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`ID`, `Emp_ID`, `Language`, `Display_Mode`) VALUES
(1, 2, 'en', 'light');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `ID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `DateTime` datetime NOT NULL,
  `Finshed_Date` datetime NOT NULL,
  `Status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 = Wating OR New\r\n1 = Progress\r\n2 = Done',
  `For_Section` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`ID`, `Title`, `Description`, `DateTime`, `Finshed_Date`, `Status`, `For_Section`) VALUES
(1, 'Meeting with Ms. Bonnie from Themesberg SDe', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi pulvinar feugiat consequat.Duis lacus nibh, sagittis id varius vel, aliquet non augue.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi pulvinar feugiat consequat.Duis lacus nibh, sagittis id varius vel, aliquet non augue.Lorem ipsum dolor sit amet, consectetur adipiscing elit', '2022-03-10 20:08:07', '2022-03-11 01:41:44', 2, 1),
(2, 'Meeting with Ms. Bonnie from Themesberg SDe', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi pulvinar feugiat consequat.Duis lacus nibh, sagittis id varius vel, aliquet non augue.', '2022-03-10 20:08:07', '0000-00-00 00:00:00', 1, 1),
(4, 'testttttttt', 'ttttbjkmnb', '2022-03-10 22:41:57', '0000-00-00 00:00:00', 0, 1),
(5, 'First Task In This Section', 'Task For Outpound Section', '2022-03-10 22:53:14', '0000-00-00 00:00:00', 0, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `comment_product` (`Product_id`),
  ADD KEY `comment_customer` (`Customer_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Phone` (`Phone`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Phone` (`Phone`),
  ADD KEY `emp_role` (`Role_ID`),
  ADD KEY `section_emp` (`Section_ID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `order_customer` (`Customer_ID`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `order_det_of_orders` (`Order_ID`),
  ADD KEY `orders_products_id` (`Product_ID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `cate_product` (`Category`),
  ADD KEY `product_emp` (`By_Emp`);

--
-- Indexes for table `product_image`
--
ALTER TABLE `product_image`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Image` (`Image_Path`),
  ADD KEY `image_product` (`Product_ID`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Emp_ID` (`Emp_ID`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `task_section` (`For_Section`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `product_image`
--
ALTER TABLE `product_image`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comment_customer` FOREIGN KEY (`Customer_id`) REFERENCES `customers` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_product` FOREIGN KEY (`Product_id`) REFERENCES `products` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `emp_role` FOREIGN KEY (`Role_ID`) REFERENCES `roles` (`ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `section_emp` FOREIGN KEY (`Section_ID`) REFERENCES `sections` (`ID`) ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `order_customer` FOREIGN KEY (`Customer_ID`) REFERENCES `customers` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_det_of_orders` FOREIGN KEY (`Order_ID`) REFERENCES `orders` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_products_id` FOREIGN KEY (`Product_ID`) REFERENCES `products` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `cate_product` FOREIGN KEY (`Category`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_emp` FOREIGN KEY (`By_Emp`) REFERENCES `employee` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_image`
--
ALTER TABLE `product_image`
  ADD CONSTRAINT `image_product` FOREIGN KEY (`Product_ID`) REFERENCES `products` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `emp_settings` FOREIGN KEY (`Emp_ID`) REFERENCES `employee` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `task_section` FOREIGN KEY (`For_Section`) REFERENCES `sections` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

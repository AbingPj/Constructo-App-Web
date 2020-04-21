-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 29, 2018 at 05:56 AM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `constructo`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `acceptRequest` (IN `requestId_input` INT(11), IN `requestType_input` INT(11), IN `accomodatedBy_input` INT(11))  BEGIN
  
   DECLARE userId_input integer DEFAULT 0;
   DECLARE expiry timestamp DEFAULT NOW();
   
   UPDATE request SET status = 1 WHERE id =  requestId_input;
   INSERT INTO requestnotification(requestId,accomodatedBy)VALUES
   (requestId_input,accomodatedBy_input);
   
   SELECT `userId` INTO userId_input FROM `request` WHERE id = requestId_input;
   
   IF requestType_input = 1 THEN
    
     SELECT DATE_ADD(NOW(), INTERVAL 12 DAY) INTO expiry;
      INSERT INTO storeaccess (storeId,isFreetrial,expiryDate) VALUES (userId_input,1,expiry);
      
      INSERT storepaymentterm (storepaymentterm.storeId, storepaymentterm.paymentTerm) VALUES (userId_input,1);
      
      INSERT storepaymentterm (storepaymentterm.storeId, storepaymentterm.paymentTerm) VALUES (userId_input,2);
      
   ELSE 
   
       SELECT DATE_ADD(NOW(), INTERVAL 30 DAY) INTO expiry;
      INSERT INTO storeaccess (storeId,isFreetrial,expiryDate) VALUES (userId_input,2,expiry);
   END IF;
  
   
   END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `addPaymentTerm` (IN `isAdd_input` INT(1), `storeId_input` INT(11), `paymentTerm_input` INT(11))  BEGIN
  IF isAdd_input = 1 THEN
     UPDATE storepaymentterm SET storepaymentterm.isOffered = 1 WHERE storeId = storeId_input AND storepaymentterm.paymentTerm = paymentTerm_input;
     
  ELSE
      UPDATE storepaymentterm SET storepaymentterm.isOffered = 0 WHERE storeId = storeId_input AND storepaymentterm.paymentTerm = paymentTerm_input;
     
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `addToCart` (IN `in_customerId` INT(255), IN `in_storeId` INT(11), IN `in_option` INT(11), IN `in_prodId` INT(11), IN `in_qty` INT(11))  BEGIN
   DECLARE cartExist integer DEFAULT 0;
   DECLARE in_totalPrice float DEFAULT 0;
   DECLARE prodPrice float DEFAULT 0;
   DECLARE in_cartId integer DEFAULT 0;
   DECLARE prodExist integer DEFAULT 0;
   DECLARE prodPrevQty integer DEFAULT 0;
   DECLARE prodPrevPrice float DEFAULT 0;
   DECLARE newQty integer DEFAULT 0;
   DECLARE newPrice float DEFAULT 0;
   
   SELECT COUNT(id) INTO cartExist FROM customercart WHERE customercart.customerId = in_customerId AND customercart.storeId = in_storeId AND customercart.paymentOption = 0;
   
   IF cartExist = 0 THEN
     INSERT INTO customercart (customercart.customerId,customercart.storeId,customercart.paymentOption) VALUES   (in_customerId,in_storeId,in_option);
   END IF;
   
   SELECT customercart.id INTO in_cartId FROM customercart WHERE customercart.customerId = in_customerId AND  customercart.storeId = in_storeId AND paymentOption = 0;

   SELECT COUNT(cartId) INTO prodExist FROM cartitems WHERE prodId = in_prodId AND cartId = in_cartId;

   SELECT storeproduct.price INTO prodPrice  FROM storeproduct WHERE storeproduct.id = in_prodId;
   SET in_totalPrice = prodPrice * in_qty;
   
   IF prodExist = 0 THEN
      INSERT INTO cartitems (cartitems.cartId,cartitems.prodId,cartitems.Qty,cartitems.totalPrice) VALUES(in_cartId,in_prodId,in_qty,in_totalPrice); 
   ELSE
      SELECT Qty INTO prodPrevQty FROM cartitems WHERE prodId = in_prodId AND cartId = in_cartId;
      SELECT totalPrice INTO prodPrevPrice FROM cartitems WHERE prodId = in_prodId AND cartId = in_cartId;

      SET newQty = prodPrevQty + in_qty;
      SET newPrice = in_totalPrice + prodPrevPrice;
      UPDATE cartitems SET Qty = newQty, totalPrice = newPrice WHERE prodId = in_prodId AND cartId = in_cartId; 
   END IF;
   END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `cancellCustomerOrder` (IN `in_orderId` INT, IN `cancellType` INT)  BEGIN
DECLARE var_customerId integer DEFAULT 0;

SELECT customerId INTO var_customerId FROM customerorder WHERE id = in_orderId;

IF cancellType = 1 THEN
 UPDATE `user` SET user.isblocked = 1 WHERE user.id = var_customerId;   
END IF;
 UPDATE customerorder SET customerorder.orderstatus = 4 WHERE customerorder.id = in_orderId;
 INSERT INTO cancelledorder (cancelledorder.orderId,cancelledorder.cancellstatus) VALUES (in_orderId,cancellType);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkOutCart` (IN `in_cartId` INT, IN `in_paymentOption` INT, IN `in_deliverDate` TIMESTAMP, IN `in_deliverAddress` TEXT)  BEGIN
   DECLARE NumberOfproduct integer DEFAULT 0;
   DECLARE counter integer DEFAULT 0;
   DECLARE var_prodID integer DEFAULT 0;
   DECLARE var_prodQty integer DEFAULT 0;
   DECLARE var_totalPrice float DEFAULT 0;
   
   DECLARE orderExist integer DEFAULT 0;
   DECLARE var_customerId integer DEFAULT 0;
   DECLARE var_storeId integer DEFAULT 0;
   DECLARE var_orderId integer DEFAULT 0;
   
   SELECT customercart.customerId INTO var_customerId FROM customercart WHERE customercart.id = in_cartId;
   SELECT customercart.storeId INTO var_storeId FROM customercart WHERE customercart.id = in_cartId;
   
   
   
   SELECT COUNT(cartitems.cartId) INTO NumberOfproduct FROM cartitems WHERE cartitems.cartId = in_cartId;
   
   WHILE  counter < NumberOfproduct DO
   
   
   SELECT prodID INTO var_prodID FROM cartitems WHERE cartitems.cartId = in_cartID LIMIT 1 OFFSET counter;
   SELECT Qty INTO var_prodQty FROM cartitems WHERE cartitems.cartId = in_cartID LIMIT 1 OFFSET counter;
   SELECT totalPrice INTO var_totalPrice FROM cartitems WHERE cartitems.cartId = in_cartID LIMIT 1 OFFSET counter;
   
   SELECT COUNT(id) INTO orderExist FROM customerorder WHERE customerId = var_customerId AND storeId = var_storeId AND token = 0;
   
   IF orderExist = 0 THEN
    INSERT INTO customerorder (customerId,storeId,paymentOption,deliver_address,date_deliver) VALUES (var_customerId,var_storeId,in_paymentOption,in_deliverAddress,in_deliverDate);
   END IF;
  
   SELECT id INTO var_orderId FROM customerorder WHERE customerId = var_customerId AND storeId = var_storeId AND token = 0;
   
   INSERT INTO customerorderitem (orderId,customerId,storeId,prodId,prodQty,totalPrice) VALUES (var_orderId,var_customerId,var_storeId,var_prodId,var_prodQty,var_totalPrice);
   
   SET counter = counter + 1;
   
   
   END WHILE;
   UPDATE customercart SET customercart.paymentOption = in_paymentOption WHERE customercart.id = in_cartId;
   UPDATE customerorder SET token = 1 WHERE id = var_orderId;
   
   
   
   
   END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkOutSingleOrder` (IN `in_customerId` INTEGER, IN `in_storeId` INTEGER, IN `in_paymentOption` INTEGER, IN `in_prodId` INTEGER, IN `in_prodQty` INTEGER)  BEGIN
   DECLARE totalPrice Decimal(10,2) DEFAULT 0;
   DECLARE orderId integer DEFAULT 0;
   DECLARE prodPrice decimal(10,2) DEFAULT 0;
   
   INSERT INTO customerorder (customerorder.customerId, customerorder.storeId, customerorder.paymentOption) VALUES (in_customerId,in_storeId,in_paymentOption);
   
   SELECT id INTO orderId FROM customerorder WHERE customerorder.customerId = in_customerId AND customerorder.storeId = in_storeId AND customerorder.token =0;
   SELECT price INTO prodPrice FROM storeproduct WHERE storeproduct.id = in_prodId;
     
   SET totalPrice = prodPrice * in_prodQty;
   
   INSERT INTO customerorderitem (customerorderitem.orderId,customerorderitem.customerId,customerorderitem.storeId,customerorderitem.prodId,customerorderitem.prodQty,customerorderitem.totalPrice) VALUES (orderId,in_customerId,in_storeId,in_prodId,in_prodQty,totalPrice);
   
   UPDATE customerorder SET customerorder.token = 1 WHERE customerorder.id = orderId;
   
   
   
   END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `createAccount` (IN `firstname_input` VARCHAR(244), IN `lastname_input` VARCHAR(244), IN `email_input` VARCHAR(244), IN `phone_input` VARCHAR(244), IN `password_input` VARCHAR(244), IN `address_input` VARCHAR(244), IN `userType_input` INT(2))  BEGIN 
  DECLARE user_id integer DEFAULT 0;
  DECLARE expiry timestamp DEFAULT NOW();
  
 INSERT INTO user(`firstname`,`lastname`,`email`,`phone`,`password`,`address`,`userType`) VALUES (firstname_input,lastname_input,email_input,phone_input,password_input,address_input,userType_input);
  
  SELECT id INTO user_id FROM user WHERE `email` = email_input;
  
  IF userType_input = 3 THEN
  SELECT DATE_ADD(NOW(), INTERVAL 12 DAY) INTO expiry;
  INSERT INTO request (`userId`,`status`,`type`) VALUES (user_id,2,1);
  
  
  END IF;
 
 END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteCartProduct` (IN `in_cartId` INTEGER, IN `in_prodId` INTEGER)  BEGIN
   DECLARE cartHasItems integer DEFAULT 0;
   
   DELETE FROM cartitems WHERE prodId = in_prodId AND cartId = in_cartId;
   
   SELECT COUNT(cartId) INTO cartHasItems FROM cartitems WHERE cartId = in_cartId;
   
   IF cartHasItems = 0 THEN
     DELETE FROM customercart WHERE id = in_cartId;
   END IF;
   
   END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `rejectRequest` (IN `requestId_input` INT(11), IN `concern_input` TEXT, IN `accomodatedBy_input` INT(11))  BEGIN
   
   INSERT INTO requestconcern(requestId,concern) VALUES (requestId_input,concern_input);
   
   INSERT INTO requestnotification(requestId,accomodatedBy)VALUES
   (requestId_input,accomodatedBy_input);
   
   UPDATE `request` SET `status` = 3 WHERE `id` = requestId_input; 
   
   
   END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateCart` (IN `in_cartId` INT(255), IN `in_prodId` INT(11), IN `in_prodQty` INT(11), IN `in_qty` INT(11), IN `in_paymentOption` INT(11))  BEGIN
       DECLARE prodPrice float DEFAULT 0;
       DECLARE prodCalculatedPrice FLOAT DEFAULT 0;
       
       SELECT price into prodPrice FROM storeproduct where id = in_prodId;
       
      SET prodCalculatedPrice = prodPrice * in_prodQty;

      UPDATE cartitems SET Qty = in_qty, totalPrice = prodCalculatedPrice where prodId = in_prodId; 

      UPDATE customercart SET paymentOption = in_paymentOption where id = in_cartId;

   END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateCartProductQty` (IN `isIncrement` INT, IN `in_prodId` INT, IN `in_prodQty` INT, IN `in_cartId` INT)  BEGIN
   DECLARE newQty integer DEFAULT 0;
   DECLARE newPrice float DEFAULT 0;
   DECLARE prevPrice float DEFAULT 0;
   
   SELECT price INTO prevPrice FROM storeproduct WHERE id = in_prodId;
   
   IF isIncrement = 1 THEN
    SET newQty = in_prodQty + 1;
   ELSE 
    SET newQty = in_prodQty - 1;
   END IF;
   
    SET newPrice = prevPrice * newQty;
    UPDATE cartitems SET totalPrice = newPrice, Qty = newQty WHERE cartId = in_cartId AND prodId = in_prodId;
   
   END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cancelledorder`
--

CREATE TABLE `cancelledorder` (
  `orderId` int(22) NOT NULL,
  `cancellstatus` int(22) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cancelledorder`
--

INSERT INTO `cancelledorder` (`orderId`, `cancellstatus`) VALUES
(3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `cartitems`
--

CREATE TABLE `cartitems` (
  `cartId` int(11) NOT NULL,
  `prodId` int(11) NOT NULL,
  `Qty` int(11) NOT NULL,
  `totalPrice` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cartitems`
--

INSERT INTO `cartitems` (`cartId`, `prodId`, `Qty`, `totalPrice`) VALUES
(3, 1, 4, '600.00'),
(5, 9, 5, '1750.00'),
(6, 9, 7, '2450.00'),
(7, 9, 4, '1400.00'),
(8, 11, 10, '1500.00');

-- --------------------------------------------------------

--
-- Table structure for table `customercart`
--

CREATE TABLE `customercart` (
  `id` int(11) NOT NULL,
  `customerId` int(11) NOT NULL,
  `storeId` int(11) NOT NULL,
  `date_added` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `paymentOption` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customercart`
--

INSERT INTO `customercart` (`id`, `customerId`, `storeId`, `date_added`, `paymentOption`) VALUES
(3, 3, 2, '2018-12-10 11:44:38', 1),
(5, 3, 5, '2018-12-24 07:36:28', 1),
(6, 3, 5, '2018-12-24 13:16:02', 1),
(7, 3, 5, '2018-12-25 08:03:05', 1),
(8, 3, 5, '2018-12-27 04:34:06', 0);

-- --------------------------------------------------------

--
-- Table structure for table `customerorder`
--

CREATE TABLE `customerorder` (
  `id` int(11) NOT NULL,
  `customerId` int(11) NOT NULL,
  `storeId` int(11) NOT NULL,
  `orderstatus` int(11) NOT NULL DEFAULT '1',
  `date_ordered` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_deliver` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deliver_address` text,
  `paymentOption` int(11) NOT NULL,
  `token` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customerorder`
--

INSERT INTO `customerorder` (`id`, `customerId`, `storeId`, `orderstatus`, `date_ordered`, `date_deliver`, `deliver_address`, `paymentOption`, `token`) VALUES
(1, 3, 2, 3, '2018-12-11 01:23:35', '2019-01-01 19:28:00', NULL, 1, 1),
(2, 3, 2, 1, '2018-12-12 01:15:24', '2019-01-01 19:28:00', NULL, 1, 1),
(3, 3, 5, 4, '2018-12-24 13:15:45', '2019-01-01 19:28:00', 'Blk 39 Lot 14 San Address Davao City', 1, 1),
(4, 3, 5, 1, '2018-12-25 07:28:29', '2019-01-01 19:28:00', NULL, 1, 1),
(5, 3, 5, 3, '2018-12-25 08:05:31', '2018-12-26 07:28:00', NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `customerorderitem`
--

CREATE TABLE `customerorderitem` (
  `orderId` int(11) NOT NULL,
  `customerId` int(11) NOT NULL,
  `storeId` int(11) NOT NULL,
  `prodId` int(11) NOT NULL,
  `prodQty` int(11) NOT NULL,
  `totalPrice` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customerorderitem`
--

INSERT INTO `customerorderitem` (`orderId`, `customerId`, `storeId`, `prodId`, `prodQty`, `totalPrice`) VALUES
(1, 3, 2, 1, 4, '600.00'),
(2, 3, 2, 1, 3, '450.00'),
(3, 3, 5, 9, 5, '1750.00'),
(4, 3, 5, 9, 7, '2450.00'),
(5, 3, 5, 9, 4, '1400.00');

-- --------------------------------------------------------

--
-- Table structure for table `messagehead`
--

CREATE TABLE `messagehead` (
  `id` int(11) NOT NULL,
  `customerId` int(11) NOT NULL,
  `storeId` int(11) NOT NULL,
  `isSeen` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `messagehead`
--

INSERT INTO `messagehead` (`id`, `customerId`, `storeId`, `isSeen`) VALUES
(1, 3, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `messagethread`
--

CREATE TABLE `messagethread` (
  `msgId` int(11) NOT NULL,
  `message` text NOT NULL,
  `isFromStore` int(1) NOT NULL DEFAULT '0',
  `dateSended` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `messagethread`
--

INSERT INTO `messagethread` (`msgId`, `message`, `isFromStore`, `dateSended`) VALUES
(1, 'hey', 1, '2018-12-12 13:57:04'),
(1, 'helo', 0, '2018-12-13 01:35:33'),
(1, 'ng', 1, '2018-12-13 02:09:04'),
(1, 'hello there iam handy boy', 1, '2018-12-15 04:37:46'),
(1, 'hi lloyd lim', 1, '2018-12-15 04:39:34'),
(1, 'asdhasdhjasdhjasdasdhsadasdhjadhjasdakdhakjsdhajksdjasdhajsdasdjasdasdasdasdasdasdasdasdd', 0, '2018-12-15 07:00:48'),
(1, 'kyu', 0, '2018-12-15 09:18:16'),
(1, 'hello', 0, '2018-12-16 02:16:05'),
(1, 'heyy', 0, '2018-12-16 02:17:01'),
(1, 'aaa', 0, '2018-12-16 02:17:22'),
(1, 'heeyyy', 0, '2018-12-16 02:18:06'),
(1, 'aaaaa', 0, '2018-12-16 02:19:02'),
(1, 'hello iam a customer', 0, '2018-12-16 02:46:28'),
(1, 'iam a cusotmer again', 0, '2018-12-16 02:47:13'),
(1, 'heylo', 0, '2018-12-16 02:48:32'),
(1, 'asdadasd', 0, '2018-12-16 02:49:13'),
(1, 'asd', 0, '2018-12-16 02:51:32'),
(1, 'qqqq', 0, '2018-12-16 02:52:09'),
(1, 'aaaa', 0, '2018-12-16 02:52:20'),
(1, 'hello boy', 0, '2018-12-16 02:55:20'),
(1, 'hello boi', 0, '2018-12-16 03:50:57'),
(1, 'aa', 0, '2018-12-16 03:51:14'),
(1, 'aaaa', 0, '2018-12-16 03:51:36'),
(1, 'qaq', 0, '2018-12-16 03:51:46'),
(1, 'dfg', 0, '2018-12-16 03:52:48'),
(1, 'rty', 0, '2018-12-16 03:53:20'),
(1, 'hello', 0, '2018-12-16 03:55:10'),
(1, 'uyy', 0, '2018-12-16 03:55:53'),
(1, 'qwe', 0, '2018-12-16 03:56:49');

-- --------------------------------------------------------

--
-- Table structure for table `orderstatus`
--

CREATE TABLE `orderstatus` (
  `id` int(11) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orderstatus`
--

INSERT INTO `orderstatus` (`id`, `status`) VALUES
(1, 'Pending'),
(2, 'Delivered'),
(3, 'Fetched'),
(4, 'Cancelled');

-- --------------------------------------------------------

--
-- Table structure for table `orderstatus_cancelled`
--

CREATE TABLE `orderstatus_cancelled` (
  `id` int(11) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orderstatus_cancelled`
--

INSERT INTO `orderstatus_cancelled` (`id`, `status`) VALUES
(1, 'The customer did not show up during arrival.'),
(2, 'Either the delivery truck or the delivery personel is not available.'),
(3, 'The customer did not pick up the item before the due date has passed.'),
(4, 'There are no available item.');

-- --------------------------------------------------------

--
-- Table structure for table `paymentterm`
--

CREATE TABLE `paymentterm` (
  `id` int(11) NOT NULL,
  `term` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `paymentterm`
--

INSERT INTO `paymentterm` (`id`, `term`) VALUES
(1, 'Cash Upon Pick-up'),
(2, 'Cash On Delivery');

-- --------------------------------------------------------

--
-- Table structure for table `productcategory`
--

CREATE TABLE `productcategory` (
  `id` int(11) NOT NULL,
  `category` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `productcategory`
--

INSERT INTO `productcategory` (`id`, `category`) VALUES
(1, 'cements'),
(2, 'woods'),
(3, 'tools'),
(4, 'Sand'),
(5, 'Gravel'),
(6, 'Brick and Block'),
(7, 'Concrete'),
(8, 'Steel and Bars'),
(9, 'Plumbing'),
(10, 'Doors'),
(11, 'Plastic'),
(12, 'Plastering');

-- --------------------------------------------------------

--
-- Table structure for table `productstatus`
--

CREATE TABLE `productstatus` (
  `id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `productstatus`
--

INSERT INTO `productstatus` (`id`, `status`) VALUES
(0, 'unavailable'),
(1, 'available');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `categoryrequested` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`id`, `userId`, `status`, `type`, `date_added`, `categoryrequested`) VALUES
(1, 2, 1, 1, '2018-12-06 14:40:06', NULL),
(2, 4, 2, 1, '2018-12-19 23:46:24', NULL),
(3, 5, 1, 1, '2018-12-21 09:54:07', NULL),
(4, 7, 1, 1, '2018-12-23 10:58:02', NULL),
(5, 2, 1, 2, '2018-12-27 14:04:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `requestcategory`
--

CREATE TABLE `requestcategory` (
  `requestId` int(11) NOT NULL,
  `categoryrequested` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `requestconcern`
--

CREATE TABLE `requestconcern` (
  `requestId` int(11) NOT NULL,
  `concern` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `requestnotification`
--

CREATE TABLE `requestnotification` (
  `id` int(11) NOT NULL,
  `requestId` int(11) NOT NULL,
  `accomodatedBy` int(11) NOT NULL,
  `dateaccomodated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isViewable` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `requestnotification`
--

INSERT INTO `requestnotification` (`id`, `requestId`, `accomodatedBy`, `dateaccomodated`, `isViewable`) VALUES
(1, 1, 1, '2018-12-09 14:44:04', 1),
(2, 3, 1, '2018-12-21 09:55:57', 1),
(3, 4, 1, '2018-12-24 03:23:20', 1),
(4, 5, 1, '2018-12-27 14:06:40', 1);

-- --------------------------------------------------------

--
-- Table structure for table `requeststatus`
--

CREATE TABLE `requeststatus` (
  `id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `requeststatus`
--

INSERT INTO `requeststatus` (`id`, `status`) VALUES
(1, 'Accepted'),
(2, 'Pending'),
(3, 'with Concerns');

-- --------------------------------------------------------

--
-- Table structure for table `requesttype`
--

CREATE TABLE `requesttype` (
  `id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `requesttype`
--

INSERT INTO `requesttype` (`id`, `type`) VALUES
(1, 'Registration Request'),
(2, 'Subscription Request'),
(3, 'Concern');

-- --------------------------------------------------------

--
-- Table structure for table `storeaccess`
--

CREATE TABLE `storeaccess` (
  `storeId` int(11) NOT NULL,
  `isFreetrial` int(1) NOT NULL,
  `initDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expiryDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `storeaccess`
--

INSERT INTO `storeaccess` (`storeId`, `isFreetrial`, `initDate`, `expiryDate`) VALUES
(2, 1, '2018-12-09 14:44:04', '2018-12-21 14:44:04'),
(5, 1, '2018-12-21 09:55:57', '2019-01-02 09:55:57'),
(7, 1, '2018-12-24 03:23:20', '2019-01-05 03:23:20'),
(2, 2, '2018-12-27 14:06:40', '2019-01-26 14:06:40');

-- --------------------------------------------------------

--
-- Table structure for table `storeoffer`
--

CREATE TABLE `storeoffer` (
  `storeId` int(11) NOT NULL,
  `productCategory` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `storeoffer`
--

INSERT INTO `storeoffer` (`storeId`, `productCategory`) VALUES
(2, 2),
(2, 1),
(2, 3),
(2, 7),
(2, 10),
(2, 4),
(5, 1),
(5, 2);

-- --------------------------------------------------------

--
-- Table structure for table `storepaymentterm`
--

CREATE TABLE `storepaymentterm` (
  `storeId` int(11) NOT NULL,
  `paymentTerm` int(11) NOT NULL,
  `isOffered` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `storepaymentterm`
--

INSERT INTO `storepaymentterm` (`storeId`, `paymentTerm`, `isOffered`) VALUES
(2, 1, 1),
(2, 2, 1),
(5, 1, 1),
(5, 2, 1),
(7, 1, 0),
(7, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `storeproduct`
--

CREATE TABLE `storeproduct` (
  `id` int(11) NOT NULL,
  `storeId` int(11) NOT NULL,
  `image` text NOT NULL,
  `productCategory` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `isViewable` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `storeproduct`
--

INSERT INTO `storeproduct` (`id`, `storeId`, `image`, `productCategory`, `name`, `description`, `price`, `status`, `isViewable`) VALUES
(1, 2, 'cemex_1544835490.jpg', 1, 'cemex cemento', '11kgs per sack', '150.00', 1, 1),
(2, 2, 'lumber_1540713582_1545239119.jpg', 2, 'coco lumber', '11 pcs per bundle 11\'', '560.00', 1, 0),
(3, 2, 'Hammer_1545240338.jpg', 3, 'tekton hammer', 'stainless steel premium hammer', '360.00', 1, 1),
(5, 2, 'nails_1545240317.jpg', 7, 'acme premium nails', 'stainless steel 11\' , 30 pcs per box', '340.00', 1, 1),
(6, 2, 'riversand_1545240543.jpg', 4, 'River sand', '30 kgs dumalag river sand', '350.00', 1, 1),
(7, 2, 'sand_1545240617.jpg', 4, 'Premium Desert sand', 'Dubai export, 40Kgs per bag', '950.00', 1, 1),
(8, 5, 'Cement-Mixes-type-I-portland-holcim_1545386753.jpg', 1, 'holcim premium cement', '11kgs per sack', '550.00', 1, 0),
(9, 5, 'Cement-Mixes-type-I-portland-holcim_1545386819.jpg', 1, 'holcim cement', '11kgs per sack', '350.00', 1, 0),
(10, 2, 'Holcim-Excel-Cement_1545387011.png', 1, 'holcim cement', '11kgs per sack', '330.00', 1, 1),
(11, 5, 'Holcim-Excel-Cement_1545885220.png', 1, 'holcim cement', '11kgs', '150.00', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `firstname` varchar(20) NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `image` varchar(500) NOT NULL DEFAULT 'noprofilepic.jpg',
  `email` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `profile` text,
  `userType` int(11) NOT NULL,
  `emailToken` text,
  `phoneToken` text,
  `isVerified` int(1) NOT NULL DEFAULT '0',
  `isblocked` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `firstname`, `lastname`, `image`, `email`, `phone`, `password`, `address`, `profile`, `userType`, `emailToken`, `phoneToken`, `isVerified`, `isblocked`) VALUES
(1, 'admin', 'admin', 'noprofilepic.jpg', 'admin@gmail.com', '09334416006', 'admin', 'university of mindanao, matina', NULL, 1, NULL, NULL, 0, 0),
(2, 'handyboy', 'hardware', 'images_1545964150.jpg', 'handyboy@gmail.com', '09334416003', '12345', 'Matina Aplaya Road, Talomo, Davao City, Davao del Sur, Philippines', NULL, 3, NULL, NULL, 0, 1),
(3, 'lloyd', 'lim', 'blueeys_1545893344.jpg', 'lloyd@gmail.com', '09334416008', '12345', 'San Lorenzo Parish Church, Talomo Road, Talomo, Davao City, Davao del Sur, Philippines', NULL, 2, NULL, NULL, 0, 0),
(4, 'jon', 'doey', 'noprofilepic.jpg', 'jon@gmail.com', '09334450089', '12345', 'Carmelo Homes, Talomo, Davao City, Davao del Sur, Philippines', NULL, 3, NULL, NULL, 0, 0),
(5, 'citi', 'hardware', 'noprofilepic.jpg', 'citi@gmail.com', '09933446004', '12345', 'Ecoland Drive, Talomo, Davao City, Davao del Sur, Philippines', NULL, 3, NULL, NULL, 0, 0),
(6, 'carl', 'invoker', 'noprofilepic.jpg', 'carl@gmail.com', '09334416021', '12345', 'Ultra Homes Subdivision, Matina Aplaya, Davao City, Davao del Sur, Philippines', NULL, 2, NULL, NULL, 0, 0),
(7, 'FMJ', 'Enterprises', 'noprofilepic.jpg', 'fmj@gmail.com', '09334416122', '12345', 'Matina Aplaya Road, Talomo, Davao City, Davao del Sur, Philippines', NULL, 3, NULL, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `usertype`
--

CREATE TABLE `usertype` (
  `id` int(11) NOT NULL,
  `type` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usertype`
--

INSERT INTO `usertype` (`id`, `type`) VALUES
(1, 'admin'),
(2, 'customer'),
(3, 'store');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cartitems`
--
ALTER TABLE `cartitems`
  ADD KEY `cartId` (`cartId`),
  ADD KEY `prodId` (`prodId`);

--
-- Indexes for table `customercart`
--
ALTER TABLE `customercart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customerorder`
--
ALTER TABLE `customerorder`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customerorderitem`
--
ALTER TABLE `customerorderitem`
  ADD KEY `prodId` (`prodId`);

--
-- Indexes for table `messagehead`
--
ALTER TABLE `messagehead`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orderstatus`
--
ALTER TABLE `orderstatus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orderstatus_cancelled`
--
ALTER TABLE `orderstatus_cancelled`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paymentterm`
--
ALTER TABLE `paymentterm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productcategory`
--
ALTER TABLE `productcategory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productstatus`
--
ALTER TABLE `productstatus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `request_ibfk_2` (`type`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `requestconcern`
--
ALTER TABLE `requestconcern`
  ADD KEY `rqeuestId` (`requestId`);

--
-- Indexes for table `requestnotification`
--
ALTER TABLE `requestnotification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requeststatus`
--
ALTER TABLE `requeststatus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requesttype`
--
ALTER TABLE `requesttype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `storeaccess`
--
ALTER TABLE `storeaccess`
  ADD KEY `storeaccess_ibfk_1` (`storeId`);

--
-- Indexes for table `storeoffer`
--
ALTER TABLE `storeoffer`
  ADD KEY `storeId` (`storeId`),
  ADD KEY `productCategory` (`productCategory`);

--
-- Indexes for table `storepaymentterm`
--
ALTER TABLE `storepaymentterm`
  ADD KEY `storeId` (`storeId`),
  ADD KEY `paymentTerm` (`paymentTerm`);

--
-- Indexes for table `storeproduct`
--
ALTER TABLE `storeproduct`
  ADD PRIMARY KEY (`id`),
  ADD KEY `storeproduct_ibfk_2` (`productCategory`),
  ADD KEY `storeproduct_ibfk_3` (`status`),
  ADD KEY `storeproduct_ibfk_1` (`storeId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userType` (`userType`);

--
-- Indexes for table `usertype`
--
ALTER TABLE `usertype`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customercart`
--
ALTER TABLE `customercart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `customerorder`
--
ALTER TABLE `customerorder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `messagehead`
--
ALTER TABLE `messagehead`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `orderstatus`
--
ALTER TABLE `orderstatus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `orderstatus_cancelled`
--
ALTER TABLE `orderstatus_cancelled`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `paymentterm`
--
ALTER TABLE `paymentterm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `productcategory`
--
ALTER TABLE `productcategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `requestnotification`
--
ALTER TABLE `requestnotification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `requeststatus`
--
ALTER TABLE `requeststatus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `requesttype`
--
ALTER TABLE `requesttype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `storeproduct`
--
ALTER TABLE `storeproduct`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `usertype`
--
ALTER TABLE `usertype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`status`) REFERENCES `requeststatus` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `request_ibfk_2` FOREIGN KEY (`type`) REFERENCES `requesttype` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `request_ibfk_3` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `requestconcern`
--
ALTER TABLE `requestconcern`
  ADD CONSTRAINT `requestconcern_ibfk_1` FOREIGN KEY (`requestId`) REFERENCES `request` (`id`);

--
-- Constraints for table `storeaccess`
--
ALTER TABLE `storeaccess`
  ADD CONSTRAINT `storeaccess_ibfk_1` FOREIGN KEY (`storeId`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`userType`) REFERENCES `usertype` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

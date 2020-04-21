-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2018 at 04:13 PM
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `acceptRequest` (IN `requestId_input` INT(11), IN `requestType_input` INT(11))  BEGIN
  
   DECLARE userId_input integer DEFAULT 0;
   DECLARE expiry timestamp DEFAULT NOW();
   
   UPDATE request SET status = 1 WHERE id =  requestId_input;
   
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `rejectRequest` (IN `requestId_input` INT(11), IN `concern_input` TEXT)  BEGIN
   
   INSERT INTO requestconcern(requestId,concern) VALUES (requestId_input,concern_input);
   
   UPDATE `request` SET `status` = 3 WHERE `id` = requestId_input; 
   
   
   END$$

DELIMITER ;

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
  `type` int(11) NOT NULL
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

-- --------------------------------------------------------

--
-- Table structure for table `storeoffer`
--

CREATE TABLE `storeoffer` (
  `storeId` int(11) NOT NULL,
  `productCategory` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `storepaymentterm`
--

CREATE TABLE `storepaymentterm` (
  `storeId` int(11) NOT NULL,
  `paymentTerm` int(11) NOT NULL,
  `isOffered` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `storeproduct`
--

CREATE TABLE `storeproduct` (
  `id` int(11) NOT NULL,
  `storeId` int(11) NOT NULL,
  `productCategory` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `firstname` varchar(20) NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `email` varchar(20) NOT NULL,
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
-- AUTO_INCREMENT for table `paymentterm`
--
ALTER TABLE `paymentterm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `productcategory`
--
ALTER TABLE `productcategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
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
-- Constraints for table `storepaymentterm`
--
ALTER TABLE `storepaymentterm`
  ADD CONSTRAINT `storepaymentterm_ibfk_1` FOREIGN KEY (`storeId`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `storepaymentterm_ibfk_2` FOREIGN KEY (`paymentTerm`) REFERENCES `paymentterm` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`userType`) REFERENCES `usertype` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

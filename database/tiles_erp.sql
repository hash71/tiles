-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 20, 2016 at 04:09 AM
-- Server version: 5.6.21
-- PHP Version: 5.5.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tiles_erp`
--

-- --------------------------------------------------------

--
-- Table structure for table `bank_list`
--

CREATE TABLE IF NOT EXISTS `bank_list` (
`id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bf`
--

CREATE TABLE IF NOT EXISTS `bf` (
`id` int(12) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `house_id` int(12) NOT NULL,
  `bf` double(12,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bill_info`
--

CREATE TABLE IF NOT EXISTS `bill_info` (
  `bill_id` int(11) NOT NULL COMMENT 'use php time() to get timestamp as id',
  `client_id` int(11) DEFAULT NULL,
  `salesman_id` int(11) DEFAULT NULL,
  `cash` double(12,2) DEFAULT NULL,
  `cheque` double(12,2) DEFAULT NULL,
  `credit_card` double(12,2) DEFAULT NULL,
  `gross` double(12,2) NOT NULL,
  `net` double(12,2) NOT NULL,
  `less` double(12,2) NOT NULL DEFAULT '0.00',
  `bill_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `stock_clear` tinyint(1) NOT NULL DEFAULT '0',
  `cashback` double(12,2) DEFAULT '0.00',
  `adjust_gross` double(12,2) DEFAULT '0.00',
  `adjust_discount` double(12,2) DEFAULT '0.00',
  `tax` int(11) NOT NULL DEFAULT '1',
  `carrying_cost` double(12,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bill_product`
--

CREATE TABLE IF NOT EXISTS `bill_product` (
`id` int(11) NOT NULL,
  `bill_id` int(11) NOT NULL,
  `product_quantity` double(12,2) NOT NULL DEFAULT '0.00' COMMENT 'sft',
  `unit_sale_price` double(12,2) NOT NULL COMMENT 'sft',
  `product_code` varchar(255) NOT NULL,
  `adjust_unit_price` double(12,2) DEFAULT '0.00',
  `total_piece` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='under single bill multiple product';

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
`id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `cat_type` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `chalan_parent`
--

CREATE TABLE IF NOT EXISTS `chalan_parent` (
`id` int(10) unsigned NOT NULL,
  `parent_bill` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `salesman_id` int(11) NOT NULL,
  `clear` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chalan_product`
--

CREATE TABLE IF NOT EXISTS `chalan_product` (
`id` int(11) NOT NULL,
  `chalan_id` int(11) NOT NULL,
  `product_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `product_quantity` double(12,2) NOT NULL,
  `total_piece` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE IF NOT EXISTS `client` (
`client_id` int(11) NOT NULL,
  `client_name` varchar(100) NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `client_email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `due`
--

CREATE TABLE IF NOT EXISTS `due` (
  `bill_id` int(11) NOT NULL,
  `due_amount` double(12,2) NOT NULL,
  `client_id` int(11) DEFAULT NULL COMMENT 'unnecessary but using to find easily client due amount'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='whenever amount paid <  net then we insert in this table \nwhen due is paid (full or partial) we query for where bill_id = x and client_id = y update (due_amount -z)\nwe can check for 0 or < 0 for error checking';

-- --------------------------------------------------------

--
-- Table structure for table `due_transaction`
--

CREATE TABLE IF NOT EXISTS `due_transaction` (
`id` int(11) NOT NULL,
  `due_pay_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bill_id` int(11) NOT NULL,
  `cheque` double(12,2) DEFAULT NULL,
  `credit_card` double(12,2) DEFAULT NULL,
  `cash` double(12,2) DEFAULT NULL,
  `less` double(12,2) DEFAULT NULL,
  `house_id` int(11) DEFAULT NULL,
  `prev_due` double(12,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='update due table\n subtract (payment method + less )';

-- --------------------------------------------------------

--
-- Table structure for table `expense`
--

CREATE TABLE IF NOT EXISTS `expense` (
`id` int(11) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `description` text,
  `amount` double(12,2) DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `house_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `house`
--

CREATE TABLE IF NOT EXISTS `house` (
`house_id` int(11) NOT NULL,
  `house_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='house can be sales, stock, office anything';

-- --------------------------------------------------------

--
-- Table structure for table `hr`
--

CREATE TABLE IF NOT EXISTS `hr` (
`id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `started_working_on` date DEFAULT NULL,
  `salary` float(12,2) NOT NULL DEFAULT '0.00',
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='human resource information';

-- --------------------------------------------------------

--
-- Table structure for table `hr_payment`
--

CREATE TABLE IF NOT EXISTS `hr_payment` (
  `payment_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `payment_amount` double(12,2) NOT NULL DEFAULT '0.00',
  `payment_date` date NOT NULL,
  `comment` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `income`
--

CREATE TABLE IF NOT EXISTS `income` (
`id` int(11) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `description` text,
  `amount` double(12,2) DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `house_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lc_info`
--

CREATE TABLE IF NOT EXISTS `lc_info` (
`id` int(11) NOT NULL,
  `lc_number` varchar(100) NOT NULL,
  `lc_date` date NOT NULL,
  `lc_cost` double(12,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lc_product`
--

CREATE TABLE IF NOT EXISTS `lc_product` (
`id` int(11) NOT NULL,
  `lc_number` varchar(100) NOT NULL,
  `product_code` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL COMMENT 'how many pieces ',
  `unit_product_size` varchar(20) NOT NULL,
  `unit_product_cost` double(12,2) NOT NULL DEFAULT '0.00',
  `total_product_cost` double(12,2) DEFAULT '0.00',
  `wastage_before_stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='multiple products under 1 lc.\nthere may be unit cost or total cost for a particular product\nif given one other can be calculated\n';

-- --------------------------------------------------------

--
-- Table structure for table `owner_calculation`
--

CREATE TABLE IF NOT EXISTS `owner_calculation` (
  `product_code` varchar(255) NOT NULL,
  `buy_rate` double(12,2) NOT NULL DEFAULT '0.00',
  `total_sale` double(12,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `product_tracker`
--

CREATE TABLE IF NOT EXISTS `product_tracker` (
`id` int(11) NOT NULL,
  `product_code` varchar(255) NOT NULL,
  `total_sold_unit` int(11) NOT NULL DEFAULT '0',
  `wastage_after_stock` int(11) DEFAULT '0',
  `sample_quantity` int(11) DEFAULT '0' COMMENT 'if sample is sold then generate a new billand subtract from this colomn ',
  `box` int(12) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='after entering the stock.......\nlc_product table gets updated\nbut \nthere may be wastage starting from the stock to the delivery\n';

-- --------------------------------------------------------

--
-- Table structure for table `return_transaction`
--

CREATE TABLE IF NOT EXISTS `return_transaction` (
`id` int(11) NOT NULL,
  `bill_id` int(11) NOT NULL,
  `product_id` varchar(50) NOT NULL,
  `return_quantity` int(11) NOT NULL,
  `return_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `salesman_id` int(11) DEFAULT NULL,
  `return_transaction_id` int(11) NOT NULL COMMENT 'Similar id of this field means these transactions happens altogether'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='return can be 2 types\ndue return\nnormal return\nfor due return calculate how much client would have to pay \nfor noromal due return no money is returned. the client have to take equal or more products. \nin that case salesman should calculate less and create a new bill besides inserting the returned amount in the table \n\nin both cases add the return amount with the product sale in the product_tracker table';

-- --------------------------------------------------------

--
-- Table structure for table `role_name`
--

CREATE TABLE IF NOT EXISTS `role_name` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `role_permission`
--

CREATE TABLE IF NOT EXISTS `role_permission` (
  `role_id` int(11) NOT NULL,
  `permission` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `house_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='people who will interact with the web application';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bank_list`
--
ALTER TABLE `bank_list`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bf`
--
ALTER TABLE `bf`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bill_info`
--
ALTER TABLE `bill_info`
 ADD PRIMARY KEY (`bill_id`), ADD KEY `idx_bill_info_0` (`salesman_id`);

--
-- Indexes for table `bill_product`
--
ALTER TABLE `bill_product`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chalan_parent`
--
ALTER TABLE `chalan_parent`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chalan_product`
--
ALTER TABLE `chalan_product`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
 ADD PRIMARY KEY (`client_id`), ADD UNIQUE KEY `client_name` (`client_name`,`mobile_number`);

--
-- Indexes for table `due`
--
ALTER TABLE `due`
 ADD PRIMARY KEY (`bill_id`);

--
-- Indexes for table `due_transaction`
--
ALTER TABLE `due_transaction`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense`
--
ALTER TABLE `expense`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `house`
--
ALTER TABLE `house`
 ADD PRIMARY KEY (`house_id`);

--
-- Indexes for table `hr`
--
ALTER TABLE `hr`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_payment`
--
ALTER TABLE `hr_payment`
 ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `income`
--
ALTER TABLE `income`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lc_info`
--
ALTER TABLE `lc_info`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `pk_lc_info_0` (`lc_number`);

--
-- Indexes for table `lc_product`
--
ALTER TABLE `lc_product`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_lc` (`lc_number`);

--
-- Indexes for table `owner_calculation`
--
ALTER TABLE `owner_calculation`
 ADD PRIMARY KEY (`product_code`);

--
-- Indexes for table `product_tracker`
--
ALTER TABLE `product_tracker`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `return_transaction`
--
ALTER TABLE `return_transaction`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_name`
--
ALTER TABLE `role_name`
 ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `role_permission`
--
ALTER TABLE `role_permission`
 ADD KEY `idx_role_permission` (`role_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`id`), ADD KEY `id` (`id`), ADD KEY `id_2` (`id`), ADD KEY `id_3` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bank_list`
--
ALTER TABLE `bank_list`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bf`
--
ALTER TABLE `bf`
MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bill_product`
--
ALTER TABLE `bill_product`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `chalan_parent`
--
ALTER TABLE `chalan_parent`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `chalan_product`
--
ALTER TABLE `chalan_product`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `due_transaction`
--
ALTER TABLE `due_transaction`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `expense`
--
ALTER TABLE `expense`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `house`
--
ALTER TABLE `house`
MODIFY `house_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hr`
--
ALTER TABLE `hr`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `income`
--
ALTER TABLE `income`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lc_info`
--
ALTER TABLE `lc_info`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lc_product`
--
ALTER TABLE `lc_product`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `product_tracker`
--
ALTER TABLE `product_tracker`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `return_transaction`
--
ALTER TABLE `return_transaction`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `role_permission`
--
ALTER TABLE `role_permission`
ADD CONSTRAINT `fk_role_permission_role_name` FOREIGN KEY (`role_id`) REFERENCES `role_name` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

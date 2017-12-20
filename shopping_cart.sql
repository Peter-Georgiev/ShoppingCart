-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.25-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.4.0.5194
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for shopping_cart
CREATE DATABASE IF NOT EXISTS `shopping_cart` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `shopping_cart`;

-- Dumping structure for table shopping_cart.ban_ip
CREATE TABLE IF NOT EXISTS `ban_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.ban_ip: ~0 rows (approximately)
DELETE FROM `ban_ip`;
/*!40000 ALTER TABLE `ban_ip` DISABLE KEYS */;
/*!40000 ALTER TABLE `ban_ip` ENABLE KEYS */;

-- Dumping structure for table shopping_cart.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_3AF346685E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.categories: ~0 rows (approximately)
DELETE FROM `categories`;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` (`id`, `name`, `is_delete`) VALUES
	(1, 'LAPTOP', 0),
	(2, 'COMPUTER', 0),
	(3, 'TABLET', 0),
	(4, 'KEYBORD', 0),
	(5, 'MOUSE', 0),
	(6, 'MONITOR', 0),
	(7, 'SOFTWARE', 0);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;

-- Dumping structure for table shopping_cart.categories_discounts
CREATE TABLE IF NOT EXISTS `categories_discounts` (
  `category_id` int(11) NOT NULL,
  `discount_id` int(11) NOT NULL,
  PRIMARY KEY (`category_id`,`discount_id`),
  KEY `IDX_A384741912469DE2` (`category_id`),
  KEY `IDX_A38474194C7C611F` (`discount_id`),
  CONSTRAINT `FK_A384741912469DE2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `FK_A38474194C7C611F` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table shopping_cart.categories_discounts: ~0 rows (approximately)
DELETE FROM `categories_discounts`;
/*!40000 ALTER TABLE `categories_discounts` DISABLE KEYS */;
INSERT INTO `categories_discounts` (`category_id`, `discount_id`) VALUES
	(1, 1),
	(1, 3),
	(3, 2);
/*!40000 ALTER TABLE `categories_discounts` ENABLE KEYS */;

-- Dumping structure for table shopping_cart.discounts
CREATE TABLE IF NOT EXISTS `discounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `percent` decimal(10,2) NOT NULL,
  `is_user` tinyint(1) NOT NULL,
  `user_days` int(11) NOT NULL,
  `user_cash` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.discounts: ~0 rows (approximately)
DELETE FROM `discounts`;
/*!40000 ALTER TABLE `discounts` DISABLE KEYS */;
INSERT INTO `discounts` (`id`, `start_date`, `end_date`, `percent`, `is_user`, `user_days`, `user_cash`) VALUES
	(1, '2017-12-20 08:00:00', '2017-12-21 22:00:00', 35.00, 0, 0, 0.00),
	(2, '2017-12-17 00:00:00', '2017-12-19 00:00:00', 15.00, 0, 0, 0.00),
	(3, '2017-12-24 00:00:00', '2017-12-24 23:59:00', 45.00, 0, 0, 0.00),
	(4, '2017-12-20 00:00:00', '2017-12-22 00:00:00', 25.00, 0, 0, 0.00),
	(5, '2017-12-25 00:00:00', '2017-12-26 00:00:00', 15.00, 0, 0, 0.00),
	(6, '2017-12-16 00:00:00', '2017-12-17 00:00:00', 18.00, 0, 0, 0.00),
	(7, '2017-12-20 10:50:00', '2017-12-20 10:55:00', 5.00, 1, 0, 900.00);
/*!40000 ALTER TABLE `discounts` ENABLE KEYS */;

-- Dumping structure for table shopping_cart.documents
CREATE TABLE IF NOT EXISTS `documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_buy` tinyint(1) NOT NULL,
  `is_sale` tinyint(1) NOT NULL,
  `date_event` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.documents: ~0 rows (approximately)
DELETE FROM `documents`;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
INSERT INTO `documents` (`id`, `is_buy`, `is_sale`, `date_event`) VALUES
	(1, 1, 0, '2017-12-20 12:49:20'),
	(2, 1, 0, '2017-12-20 12:56:04'),
	(3, 1, 0, '2017-12-20 13:26:51'),
	(4, 1, 0, '2017-12-20 13:49:58');
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;

-- Dumping structure for table shopping_cart.payments
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `document_id` int(11) DEFAULT NULL,
  `qtty` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(5,2) NOT NULL,
  `payment` decimal(10,2) NOT NULL,
  `is_paid` tinyint(1) NOT NULL,
  `date_purchases` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_65D29B324584665A` (`product_id`),
  KEY `IDX_65D29B32A76ED395` (`user_id`),
  KEY `IDX_65D29B32C33F7837` (`document_id`),
  CONSTRAINT `FK_65D29B324584665A` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `FK_65D29B32A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_65D29B32C33F7837` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.payments: ~0 rows (approximately)
DELETE FROM `payments`;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` (`id`, `product_id`, `user_id`, `document_id`, `qtty`, `price`, `discount`, `payment`, `is_paid`, `date_purchases`) VALUES
	(4, 1, 5, 1, 1, 390.00, 35.00, 390.00, 1, '2017-12-20 10:52:00'),
	(5, 12, 5, 1, 1, 7.00, 0.00, 7.00, 1, '2017-12-20 10:52:22'),
	(11, 13, 2, 3, 1, 45.00, 0.00, 45.00, 1, '2017-12-20 13:23:34'),
	(12, 1, 1, 4, 1, 390.00, 35.00, 390.00, 1, '2017-12-20 13:49:52');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;

-- Dumping structure for table shopping_cart.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `qtty` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `date_added` datetime NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `most_wanted` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B3BA5A5A7E3C61F9` (`owner_id`),
  KEY `IDX_B3BA5A5A12469DE2` (`category_id`),
  CONSTRAINT `FK_B3BA5A5A12469DE2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `FK_B3BA5A5A7E3C61F9` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.products: ~0 rows (approximately)
DELETE FROM `products`;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` (`id`, `owner_id`, `category_id`, `name`, `model`, `qtty`, `price`, `date_added`, `is_delete`, `most_wanted`) VALUES
	(1, 1, 1, 'Lenovo', 'Legion Y500 /second hand', 1, 600.00, '2017-12-20 09:59:27', 0, 1000),
	(2, 1, 1, 'Asus', 'Rog G750/second hand', 4, 480.00, '2017-12-20 10:00:20', 0, 1000),
	(3, 1, 1, 'Acer', 'Predator GX20 /second hand', 6, 490.00, '2017-12-20 10:01:21', 0, 1000),
	(4, 2, 1, 'Lenovo', 'ThinkPad T430 /second hand', 2, 380.00, '2017-12-20 10:03:57', 0, 1000),
	(5, 1, 1, 'Fujitsu', 'LifeBook P702 /second hand', 5, 180.00, '2017-12-20 10:05:22', 0, 1000),
	(6, 1, 2, 'HP', 'ProDesk 600G1 /second hand', 2, 220.00, '2017-12-20 10:09:29', 0, 1000),
	(7, 1, 1, 'Dell', 'OptiPlex 780 /second hand', 2, 190.00, '2017-12-20 10:10:16', 0, 1000),
	(8, 2, 3, 'Lenovo', 'Tab 7', 2, 159.99, '2017-12-20 10:11:41', 0, 1000),
	(9, 2, 3, 'Prestigio', 'PMT377', 2, 135.99, '2017-12-20 10:14:01', 0, 1000),
	(10, 2, 4, 'Delux', 'DLK-6300U', 8, 10.99, '2017-12-20 10:15:36', 0, 1000),
	(11, 2, 1, 'Dell', 'KB522', 3, 45.00, '2017-12-20 10:16:17', 0, 1000),
	(12, 2, 5, 'Hama', 'AM-5400', 4, 7.00, '2017-12-20 10:17:46', 0, 10),
	(13, 1, 5, 'Trust', 'GTX 130', 2, 45.00, '2017-12-20 10:18:24', 0, 1000),
	(14, 2, 1, 'Lenovo', 'ThinkPad T430 /second hand', 1, 250.00, '2017-12-20 13:27:13', 0, 1000);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;

-- Dumping structure for table shopping_cart.products_discounts
CREATE TABLE IF NOT EXISTS `products_discounts` (
  `product_id` int(11) NOT NULL,
  `discount_id` int(11) NOT NULL,
  PRIMARY KEY (`product_id`,`discount_id`),
  KEY `IDX_AE2AE19E4584665A` (`product_id`),
  KEY `IDX_AE2AE19E4C7C611F` (`discount_id`),
  CONSTRAINT `FK_AE2AE19E4584665A` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `FK_AE2AE19E4C7C611F` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table shopping_cart.products_discounts: ~0 rows (approximately)
DELETE FROM `products_discounts`;
/*!40000 ALTER TABLE `products_discounts` DISABLE KEYS */;
INSERT INTO `products_discounts` (`product_id`, `discount_id`) VALUES
	(1, 4),
	(7, 5),
	(8, 6);
/*!40000 ALTER TABLE `products_discounts` ENABLE KEYS */;

-- Dumping structure for table shopping_cart.products_reviews
CREATE TABLE IF NOT EXISTS `products_reviews` (
  `product_id` int(11) NOT NULL,
  `review_id` int(11) NOT NULL,
  PRIMARY KEY (`product_id`,`review_id`),
  KEY `IDX_B24D11524584665A` (`product_id`),
  KEY `IDX_B24D11523E2E969B` (`review_id`),
  CONSTRAINT `FK_B24D11523E2E969B` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`),
  CONSTRAINT `FK_B24D11524584665A` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table shopping_cart.products_reviews: ~0 rows (approximately)
DELETE FROM `products_reviews`;
/*!40000 ALTER TABLE `products_reviews` DISABLE KEYS */;
INSERT INTO `products_reviews` (`product_id`, `review_id`) VALUES
	(1, 1),
	(1, 2);
/*!40000 ALTER TABLE `products_reviews` ENABLE KEYS */;

-- Dumping structure for table shopping_cart.reviews
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `message` longtext NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6970EB0F7E3C61F9` (`owner_id`),
  CONSTRAINT `FK_6970EB0F7E3C61F9` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.reviews: ~0 rows (approximately)
DELETE FROM `reviews`;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` (`id`, `owner_id`, `message`, `date_added`) VALUES
	(1, 1, 'Kofti', '2017-12-20 13:30:38'),
	(2, 1, 'Test', '2017-12-20 13:37:38');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;

-- Dumping structure for table shopping_cart.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_B63E2EC75E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.roles: ~0 rows (approximately)
DELETE FROM `roles`;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `name`) VALUES
	(1, 'ROLE_ADMIN'),
	(2, 'ROLE_EDIT'),
	(3, 'ROLE_USER');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

-- Dumping structure for table shopping_cart.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `reg_time` datetime NOT NULL,
  `cash` decimal(10,2) NOT NULL,
  `is_ban` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1483A5E9F85E0677` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.users: ~0 rows (approximately)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `username`, `password`, `first_name`, `last_name`, `reg_time`, `cash`, `is_ban`) VALUES
	(1, 'admin', '$2y$13$wMsAMck9wmpsT5d1ffZJduKsXbua9sqKn5.S5Hcx1kUzvd1ptVGJe', 'Administrator', NULL, '2017-11-16 09:40:12', 2110.00, 0),
	(2, 'peter', '$2y$13$JGrol5mhBLRYKaUJueXF3ehaM6gVEeNIOzwQA7hvsaQyyy/AQ3iQu', 'Peter', 'Georgiev', '2017-11-20 09:40:45', 708.00, 0),
	(3, 'minko', '$2y$13$/6MDxWlXr0Ewh8H7Gyf/HuW.rE9VmK3sgvamOhhOf3RgqFl2YWJeq', 'Minko', 'minkov', '2017-11-20 09:41:14', 1000.00, 0),
	(4, 'pesho', '$2y$13$ihJ0U3v2wZKPVQH2DOCZkecNLZThTxXXEAn47MyQuk8L1NLcyotsm', 'Petar', 'Petrov', '2017-12-10 09:41:35', 1500.00, 0),
	(5, 'ivo', '$2y$13$Paf/nis5VDzmwVxq5julH.hbBwv/9Yhql1qhnL8CoEk6rPS7yFWnS', 'Ivo', 'Ivanov', '2017-12-10 09:42:18', 403.00, 1),
	(6, 'inko', '$2y$13$DSct0tUmpEvd.9xwaDkpI.ddWN0Rwp/sTIIe.m7O5RtdUWsMXAbsu', 'Inko', 'Inkov', '2017-12-15 09:42:43', 600.00, 0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Dumping structure for table shopping_cart.users_roles
CREATE TABLE IF NOT EXISTS `users_roles` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `IDX_51498A8EA76ED395` (`user_id`),
  KEY `IDX_51498A8ED60322AC` (`role_id`),
  CONSTRAINT `FK_51498A8EA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_51498A8ED60322AC` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table shopping_cart.users_roles: ~0 rows (approximately)
DELETE FROM `users_roles`;
/*!40000 ALTER TABLE `users_roles` DISABLE KEYS */;
INSERT INTO `users_roles` (`user_id`, `role_id`) VALUES
	(1, 1),
	(2, 2),
	(3, 3),
	(4, 3),
	(5, 3),
	(6, 3);
/*!40000 ALTER TABLE `users_roles` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

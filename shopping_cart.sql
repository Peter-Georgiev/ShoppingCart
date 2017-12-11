-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.25-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.4.0.5191
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for shopping_cart
CREATE DATABASE IF NOT EXISTS `shopping_cart` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `shopping_cart`;

-- Dumping structure for table shopping_cart.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_3AF346685E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.categories: ~3 rows (approximately)
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` (`id`, `name`, `is_delete`) VALUES
	(1, 'LAPTOP', 0),
	(2, 'MONITOR', 0),
	(7, 'MOUSEs', 0);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.categories_discounts: ~0 rows (approximately)
/*!40000 ALTER TABLE `categories_discounts` DISABLE KEYS */;
INSERT INTO `categories_discounts` (`category_id`, `discount_id`) VALUES
	(1, 5),
	(1, 6);
/*!40000 ALTER TABLE `categories_discounts` ENABLE KEYS */;

-- Dumping structure for table shopping_cart.discounts
CREATE TABLE IF NOT EXISTS `discounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `percent` decimal(10,2) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table shopping_cart.discounts: ~0 rows (approximately)
/*!40000 ALTER TABLE `discounts` DISABLE KEYS */;
INSERT INTO `discounts` (`id`, `percent`, `start_date`, `end_date`) VALUES
	(1, 25.00, '2017-12-10 13:00:00', '2017-12-12 11:11:00'),
	(2, 33.00, '2017-12-11 00:00:00', '2017-12-13 00:00:00'),
	(4, 67.00, '2017-12-01 00:00:00', '2017-12-09 00:00:00'),
	(5, 17.00, '2017-12-09 00:00:00', '2017-12-17 00:00:00'),
	(6, 10.00, '2017-12-01 00:00:00', '2017-12-10 00:00:00'),
	(7, 11.00, '2017-12-10 00:00:00', '2017-12-11 00:00:00'),
	(8, 12.00, '2017-12-10 00:00:00', '2017-12-12 00:00:00');
/*!40000 ALTER TABLE `discounts` ENABLE KEYS */;

-- Dumping structure for table shopping_cart.documents
CREATE TABLE IF NOT EXISTS `documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_buy` tinyint(1) NOT NULL,
  `is_sale` tinyint(1) NOT NULL,
  `date_event` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.documents: ~8 rows (approximately)
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
INSERT INTO `documents` (`id`, `is_buy`, `is_sale`, `date_event`) VALUES
	(1, 1, 0, '2017-12-01 08:38:43'),
	(4, 1, 0, '2017-12-04 23:38:43'),
	(5, 1, 0, '2017-12-05 09:38:43'),
	(6, 1, 0, '2017-12-05 09:45:48'),
	(7, 1, 1, '2017-12-05 13:18:45'),
	(8, 1, 0, '2017-12-05 13:19:05'),
	(9, 1, 0, '2017-12-07 15:05:53'),
	(10, 1, 0, '2017-12-07 15:24:52'),
	(11, 1, 0, '2017-12-07 15:25:54'),
	(12, 1, 0, '2017-12-08 13:11:35'),
	(13, 1, 0, '2017-12-10 14:32:01'),
	(14, 1, 0, '2017-12-10 17:13:34'),
	(15, 1, 0, '2017-12-10 17:16:38'),
	(16, 1, 0, '2017-12-10 17:19:44'),
	(17, 1, 0, '2017-12-10 19:01:05'),
	(18, 1, 0, '2017-12-10 19:01:55'),
	(19, 1, 0, '2017-12-10 21:33:38'),
	(20, 1, 0, '2017-12-10 21:57:08');
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;

-- Dumping structure for table shopping_cart.payments
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `qtty` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(5,2) NOT NULL,
  `payment` decimal(10,2) NOT NULL,
  `is_paid` tinyint(1) NOT NULL,
  `date_purchases` datetime NOT NULL,
  `document_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_65D29B324584665A` (`product_id`),
  KEY `IDX_65D29B32A76ED395` (`user_id`),
  KEY `IDX_65D29B32C33F7837` (`document_id`),
  CONSTRAINT `FK_65D29B324584665A` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `FK_65D29B32A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_65D29B32C33F7837` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.payments: ~14 rows (approximately)
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` (`id`, `product_id`, `user_id`, `qtty`, `price`, `discount`, `payment`, `is_paid`, `date_purchases`, `document_id`) VALUES
	(3, 1, 3, 1, 343.00, 10.00, 340.08, 1, '2017-12-04 21:16:31', 7),
	(4, 1, 1, 1, 343.00, 10.00, 340.08, 1, '2017-12-04 21:33:51', 1),
	(5, 1, 1, 1, 343.00, 10.00, 340.08, 1, '2017-12-04 21:42:18', 1),
	(6, 2, 1, 1, 345.99, 10.00, 343.10, 1, '2017-12-04 21:42:24', 4),
	(7, 2, 2, 1, 345.99, 10.00, 343.10, 1, '2017-12-04 21:42:30', 4),
	(8, 1, 2, 1, 343.00, 10.00, 340.08, 1, '2017-12-05 09:38:38', 5),
	(9, 1, 1, 1, 343.00, 10.00, 340.08, 1, '2017-12-05 09:45:44', 6),
	(10, 5, 3, 1, 211.00, 10.00, 206.26, 1, '2017-12-05 13:18:30', 8),
	(11, 1, 3, 1, 343.00, 10.00, 340.08, 1, '2017-12-07 15:05:10', 9),
	(15, 2, 3, 1, 345.99, 10.00, 343.10, 1, '2017-12-07 15:16:49', 10),
	(16, 1, 3, 1, 343.00, 10.00, 340.08, 1, '2017-12-07 15:16:55', 11),
	(17, 5, 3, 1, 1211.00, 10.00, 1210.17, 1, '2017-12-07 15:25:42', 11),
	(18, 1, 3, 1, 343.00, 10.00, 340.08, 1, '2017-12-07 15:25:51', 11),
	(19, 1, 2, 1, 343.00, 10.00, 340.08, 1, '2017-12-07 21:09:19', 12),
	(20, 7, 2, 1, 343.10, 10.00, 340.19, 1, '2017-12-07 21:10:21', 12),
	(23, 11, 1, 1, 340.08, 10.00, 337.14, 1, '2017-12-10 14:31:29', 13),
	(24, 1, 1, 1, 343.00, 10.00, 340.08, 1, '2017-12-10 14:31:49', 13),
	(28, 1, 1, 1, 229.81, 10.00, 225.46, 1, '2017-12-10 17:09:58', 14),
	(29, 1, 1, 1, 343.00, 33.00, 333.38, 1, '2017-12-10 17:16:27', 15),
	(30, 1, 1, 1, 229.81, 33.00, 0.00, 1, '2017-12-10 17:19:25', 16),
	(31, 1, 1, 1, 229.81, 33.00, 0.00, 1, '2017-12-10 19:00:55', 17),
	(32, 1, 1, 1, 229.81, 33.00, 229.81, 1, '2017-12-10 19:01:51', 18),
	(43, 1, 1, 1, 284.69, 17.00, 284.69, 1, '2017-12-10 21:18:34', 19),
	(44, 5, 1, 1, 1211.00, 0.00, 1211.00, 1, '2017-12-10 21:18:41', 19),
	(45, 2, 1, 1, 307.93, 11.00, 307.93, 1, '2017-12-10 21:18:49', 19),
	(46, 2, 1, 1, 304.47, 12.00, 304.47, 1, '2017-12-10 21:57:04', 20);
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
  PRIMARY KEY (`id`),
  KEY `IDX_B3BA5A5A7E3C61F9` (`owner_id`),
  KEY `IDX_B3BA5A5A12469DE2` (`category_id`),
  CONSTRAINT `FK_B3BA5A5A12469DE2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `FK_B3BA5A5A7E3C61F9` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.products: ~11 rows (approximately)
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` (`id`, `owner_id`, `category_id`, `name`, `model`, `qtty`, `price`, `date_added`, `is_delete`) VALUES
	(1, 1, 1, 'Lartop Tochiba', 'TH 15\'6', 0, 343.00, '2017-12-04 13:00:02', 0),
	(2, 1, 2, 'LG', 'TV 17\'', 10, 345.99, '2017-12-04 15:40:52', 0),
	(5, 2, 1, 'HP', 'HP 13\'45', 13, 1211.00, '2017-12-05 13:18:02', 0),
	(6, 1, 2, 'ASUS', 'As 22', 32, 234.00, '2017-12-05 14:26:17', 0),
	(7, 1, 1, 'LG', 'TV 17\'', 0, 343.10, '2017-12-05 19:59:21', 0),
	(9, 2, 1, 'Lartop Tochiba123', 'TH 15\'6', 1, 340.08, '2017-12-05 20:01:35', 0),
	(10, 2, 2, 'Lartop DELL', 'TH 15\'6', 1, 340.08, '2017-12-05 20:02:32', 0),
	(11, 2, 1, 'Lartop Tochiba321541', 'TH 15\'6', 0, 340.08, '2017-12-05 20:04:23', 0),
	(12, 2, 7, 'Trust', 'GTX 130', 1, 35.99, '2017-12-05 21:49:01', 0),
	(13, 3, 7, 'Test23rwer', '42134234', 1, 123.44, '2017-12-07 15:04:32', 0),
	(14, 3, 1, 'Dell Minko', 'Star', 1, 123.44, '2017-12-07 21:26:49', 0);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.products_discounts: ~0 rows (approximately)
/*!40000 ALTER TABLE `products_discounts` DISABLE KEYS */;
INSERT INTO `products_discounts` (`product_id`, `discount_id`) VALUES
	(1, 1),
	(1, 2),
	(1, 4),
	(2, 7),
	(2, 8);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.products_reviews: ~0 rows (approximately)
/*!40000 ALTER TABLE `products_reviews` DISABLE KEYS */;
INSERT INTO `products_reviews` (`product_id`, `review_id`) VALUES
	(1, 32),
	(1, 33),
	(1, 34),
	(1, 35),
	(1, 36),
	(2, 37);
/*!40000 ALTER TABLE `products_reviews` ENABLE KEYS */;

-- Dumping structure for table shopping_cart.reviews
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` longtext NOT NULL,
  `owner_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6970EB0F7E3C61F9` (`owner_id`),
  CONSTRAINT `FK_6970EB0F7E3C61F9` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.reviews: ~0 rows (approximately)
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` (`id`, `message`, `owner_id`) VALUES
	(32, 'Good Oj', 1),
	(33, 'Good Oj', 1),
	(34, '5345345', 1),
	(35, 'fasdfsadf', 1),
	(36, 'fasdfsadf', 1),
	(37, 'rerer', 1);
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;

-- Dumping structure for table shopping_cart.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_B63E2EC75E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.roles: ~3 rows (approximately)
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.users: ~4 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `username`, `password`, `first_name`, `last_name`, `reg_time`, `cash`, `is_ban`) VALUES
	(1, 'admin', '$2y$13$mWk079JWEksb25vAib/KmOlW/WmKIyxG6wws89Fc5LdyQr44Kj3Bi', 'Administrator', NULL, '2017-12-04 12:57:08', 755.62, 0),
	(2, 'peter', '$2y$13$2DDpTXxP4YvKHf/QS1U/5Oely9S2cj3Mb8icXf2AALkjHwTaRrmAa', 'Петър', 'Георгиев', '2017-12-04 15:53:28', 547.90, 0),
	(3, 'minko', '$2y$13$A5aH8b37MCJ6hupcyLeONu0Z1mQKOD4hfvTmW0Sjzr2U0iaocmvR6', 'Minko', 'Ivanov', '2017-12-04 21:16:04', 1094.01, 0),
	(4, 'ivo', '$2y$13$fIMi4QqaOFr1J9ew54FpAee5bH2khH9TIHqPCBOW5KQeaaEMG7g4G', 'Ivo', NULL, '2017-12-05 14:25:15', 1233.00, 1);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.users_roles: ~4 rows (approximately)
/*!40000 ALTER TABLE `users_roles` DISABLE KEYS */;
INSERT INTO `users_roles` (`user_id`, `role_id`) VALUES
	(1, 1),
	(2, 2),
	(3, 3),
	(4, 3);
/*!40000 ALTER TABLE `users_roles` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

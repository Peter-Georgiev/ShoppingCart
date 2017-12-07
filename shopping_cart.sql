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

-- Dumping structure for table shopping_cart.documents
CREATE TABLE IF NOT EXISTS `documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_buy` tinyint(1) NOT NULL,
  `is_sale` tinyint(1) NOT NULL,
  `date_event` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

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
	(11, 1, 0, '2017-12-07 15:25:54');
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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

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
	(19, 1, 2, 1, 343.00, 0.00, 0.00, 0, '2017-12-07 21:09:19', NULL),
	(20, 7, 2, 1, 343.10, 0.00, 0.00, 0, '2017-12-07 21:10:21', NULL);
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

-- Dumping data for table shopping_cart.products: ~10 rows (approximately)
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` (`id`, `owner_id`, `category_id`, `name`, `model`, `qtty`, `price`, `date_added`, `is_delete`) VALUES
	(1, 1, 1, 'Lartop Tochiba', 'TH 15\'6', 8, 343.00, '2017-12-04 13:00:02', 0),
	(2, 1, 2, 'LG', 'TV 17\'', 12, 345.99, '2017-12-04 15:40:52', 0),
	(5, 2, 1, 'HP', 'HP 13\'45', 14, 1211.00, '2017-12-05 13:18:02', 0),
	(6, 1, 2, 'ASUS', 'As 22', 32, 234.00, '2017-12-05 14:26:17', 0),
	(7, 1, 1, 'LG', 'TV 17\'', 1, 343.10, '2017-12-05 19:59:21', 0),
	(8, 1, 1, 'Lartop Tochiba', 'TH 15\'6', 1, 340.08, '2017-12-05 19:59:44', 0),
	(9, 2, 1, 'Lartop Tochiba123', 'TH 15\'6', 1, 340.08, '2017-12-05 20:01:35', 0),
	(10, 2, 2, 'Lartop DELL', 'TH 15\'6', 1, 340.08, '2017-12-05 20:02:32', 0),
	(11, 2, 1, 'Lartop Tochiba321541', 'TH 15\'6', 1, 340.08, '2017-12-05 20:04:23', 0),
	(12, 2, 7, 'Trust', 'GTX 130', 1, 35.99, '2017-12-05 21:49:01', 0),
	(13, 3, 7, 'Test23rwer', '42134234', 1, 123.44, '2017-12-07 15:04:32', 0),
	(14, 3, 1, 'Dell Minko', 'Star', 1, 123.44, '2017-12-07 21:26:49', 0);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;

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
	(1, 24),
	(2, 22),
	(2, 23),
	(2, 26),
	(5, 25),
	(14, 27);
/*!40000 ALTER TABLE `products_reviews` ENABLE KEYS */;

-- Dumping structure for table shopping_cart.reviews
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` longtext NOT NULL,
  `owner_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6970EB0F7E3C61F9` (`owner_id`),
  CONSTRAINT `FK_6970EB0F7E3C61F9` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.reviews: ~0 rows (approximately)
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` (`id`, `message`, `owner_id`) VALUES
	(22, 'rqwerweqr', 1),
	(23, 'tesssssss', 1),
	(24, 'Offf\r\nfwqefwefwefwefwef\r\nfwefwefwewef', 2),
	(25, 'Kofti ne bachka', 2),
	(26, 'sdafsdaf', 2),
	(27, 'Leko Nadran', 3);
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
	(1, 'admin', '$2y$13$mWk079JWEksb25vAib/KmOlW/WmKIyxG6wws89Fc5LdyQr44Kj3Bi', 'Administrator', NULL, '2017-12-04 12:57:08', 809.03, 0),
	(2, 'peter', '$2y$13$2DDpTXxP4YvKHf/QS1U/5Oely9S2cj3Mb8icXf2AALkjHwTaRrmAa', 'Петър', 'Георгиев', '2017-12-04 15:53:28', 1234.00, 0),
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

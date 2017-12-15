-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.25-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.4.0.5192
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.categories: ~3 rows (approximately)
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` (`id`, `name`, `is_delete`) VALUES
	(1, 'LAPTOP', 0),
	(2, 'MONITOR', 0),
	(3, 'MOUSES', 0);
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

-- Dumping data for table shopping_cart.categories_discounts: ~2 rows (approximately)
/*!40000 ALTER TABLE `categories_discounts` DISABLE KEYS */;
INSERT INTO `categories_discounts` (`category_id`, `discount_id`) VALUES
	(1, 1),
	(1, 2);
/*!40000 ALTER TABLE `categories_discounts` ENABLE KEYS */;

-- Dumping structure for table shopping_cart.discounts
CREATE TABLE IF NOT EXISTS `discounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `percent` decimal(10,2) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `is_user` tinyint(1) NOT NULL,
  `user_days` int(11) DEFAULT NULL,
  `user_cash` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table shopping_cart.discounts: ~6 rows (approximately)
/*!40000 ALTER TABLE `discounts` DISABLE KEYS */;
INSERT INTO `discounts` (`id`, `percent`, `start_date`, `end_date`, `is_user`, `user_days`, `user_cash`) VALUES
	(1, 10.00, '2017-12-13 00:00:00', '2017-12-15 00:00:00', 0, NULL, NULL),
	(2, 25.00, '2017-12-15 00:00:00', '2017-12-16 00:00:00', 0, NULL, NULL),
	(3, 45.00, '2017-12-13 15:00:00', '2017-12-13 18:00:00', 0, NULL, NULL),
	(4, 40.00, '2017-12-13 18:00:00', '2017-12-13 22:00:00', 0, NULL, NULL),
	(5, 30.00, '2017-12-15 00:00:00', '2017-12-17 00:00:00', 1, 21, NULL),
	(7, 35.00, '2017-12-15 00:00:00', '2017-12-17 00:00:00', 1, NULL, 333.00);
/*!40000 ALTER TABLE `discounts` ENABLE KEYS */;

-- Dumping structure for table shopping_cart.documents
CREATE TABLE IF NOT EXISTS `documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_buy` tinyint(1) NOT NULL,
  `is_sale` tinyint(1) NOT NULL,
  `date_event` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.documents: ~4 rows (approximately)
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
INSERT INTO `documents` (`id`, `is_buy`, `is_sale`, `date_event`) VALUES
	(1, 1, 0, '2017-12-12 14:21:59'),
	(2, 1, 0, '2017-12-12 14:53:52'),
	(3, 1, 0, '2017-12-12 15:11:16'),
	(4, 1, 0, '2017-12-13 15:50:59'),
	(5, 1, 0, '2017-12-13 22:40:27');
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.payments: ~2 rows (approximately)
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` (`id`, `product_id`, `user_id`, `qtty`, `price`, `discount`, `payment`, `is_paid`, `date_purchases`, `document_id`) VALUES
	(5, 2, 1, 1, 444.00, 0.00, 444.00, 1, '2017-12-12 14:48:02', 2);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.products: ~4 rows (approximately)
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` (`id`, `owner_id`, `category_id`, `name`, `model`, `qtty`, `price`, `date_added`, `is_delete`) VALUES
	(2, 1, 1, 'Lartop Tochiba', 'TH 15\'6', 3, 444.00, '2017-12-12 14:21:45', 0),
	(3, 1, 2, 'Monitor AZ', 'AZ 22\'', 3, 655.00, '2017-12-12 14:53:40', 0),
	(4, 2, 1, 'Lartop Tochiba', 'TH 15\'6', 0, 244.20, '2017-12-13 15:51:34', 0),
	(5, 1, 1, 'Lartop Tochiba', 'TH 15\'6', 1, 255.00, '2017-12-15 09:51:01', 0),
	(6, 1, 1, 'Lartop Tochiba', 'TH 15\'6', 1, 344.00, '2017-12-15 18:06:16', 0);
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

-- Dumping data for table shopping_cart.products_discounts: ~2 rows (approximately)
/*!40000 ALTER TABLE `products_discounts` DISABLE KEYS */;
INSERT INTO `products_discounts` (`product_id`, `discount_id`) VALUES
	(2, 3),
	(3, 4);
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
	(2, 1);
/*!40000 ALTER TABLE `products_reviews` ENABLE KEYS */;

-- Dumping structure for table shopping_cart.reviews
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` longtext NOT NULL,
  `owner_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6970EB0F7E3C61F9` (`owner_id`),
  CONSTRAINT `FK_6970EB0F7E3C61F9` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.reviews: ~0 rows (approximately)
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` (`id`, `message`, `owner_id`) VALUES
	(1, 'Offff', 2);
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Dumping data for table shopping_cart.users: ~5 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `username`, `password`, `first_name`, `last_name`, `reg_time`, `cash`, `is_ban`) VALUES
	(1, 'admin', '$2y$13$mWk079JWEksb25vAib/KmOlW/WmKIyxG6wws89Fc5LdyQr44Kj3Bi', 'Administrator', NULL, '2017-12-04 12:57:08', 108162.27, 0),
	(2, 'peter', '$2y$13$2DDpTXxP4YvKHf/QS1U/5Oely9S2cj3Mb8icXf2AALkjHwTaRrmAa', 'Петър', 'Георгиев', '2017-12-04 15:53:28', 859.70, 0),
	(3, 'minko', '$2y$13$A5aH8b37MCJ6hupcyLeONu0Z1mQKOD4hfvTmW0Sjzr2U0iaocmvR6', 'Minko', 'Ivanov', '2017-12-04 21:16:04', 1094.01, 0),
	(4, 'ivo', '$2y$13$fIMi4QqaOFr1J9ew54FpAee5bH2khH9TIHqPCBOW5KQeaaEMG7g4G', 'Ivo', NULL, '2017-12-05 14:25:15', 1233.00, 1),
	(5, 'inko', '$2y$13$MBJTIC5rvUJt2m.s/qqIGeMGhREObaYSBzZ5/mdWVKpqD8rxhA3kC', 'Inko', 'Inkov', '2017-12-12 11:01:04', 2500.00, 0);
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

-- Dumping data for table shopping_cart.users_roles: ~5 rows (approximately)
/*!40000 ALTER TABLE `users_roles` DISABLE KEYS */;
INSERT INTO `users_roles` (`user_id`, `role_id`) VALUES
	(1, 1),
	(2, 2),
	(3, 3),
	(4, 3),
	(5, 3);
/*!40000 ALTER TABLE `users_roles` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

-- noinspection SqlNoDataSourceInspectionForFile

-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               10.1.14-MariaDB - mariadb.org binary distribution
-- ОС Сервера:                   Win64
-- HeidiSQL Версия:              9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры для таблица cargo_cabinets.company_document
CREATE TABLE IF NOT EXISTS `company_document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `document_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы cargo_cabinets.company_document: ~3 rows (приблизительно)
/*!40000 ALTER TABLE `company_document` DISABLE KEYS */;
REPLACE INTO `company_document` (`id`, `company_id`, `document_type_id`) VALUES
	(1, 32, 1),
	(2, 32, 2),
	(3, 32, 3);
/*!40000 ALTER TABLE `company_document` ENABLE KEYS */;


-- Дамп структуры для таблица cargo_cabinets.company_document_type
CREATE TABLE IF NOT EXISTS `company_document_type` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы cargo_cabinets.company_document_type: ~3 rows (приблизительно)
/*!40000 ALTER TABLE `company_document_type` DISABLE KEYS */;
REPLACE INTO `company_document_type` (`id`, `name`) VALUES
	(1, 'Счет на оплату'),
	(2, 'Поручение экспедитору'),
	(3, 'Договор экспедирования');
/*!40000 ALTER TABLE `company_document_type` ENABLE KEYS */;


-- Дамп структуры для таблица cargo_cabinets.company_tariff_type
CREATE TABLE IF NOT EXISTS `company_tariff_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(250) NOT NULL,
  `profit_coefficient` decimal(5,2) NOT NULL DEFAULT '1.00',
  `is_active` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы cargo_cabinets.company_tariff_type: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `company_tariff_type` DISABLE KEYS */;
REPLACE INTO `company_tariff_type` (`id`, `company_id`, `name`, `profit_coefficient`, `is_active`) VALUES
	(1, 32, 'Авто', 1.00, b'1'),
	(2, 32, 'Авиа', 1.00, b'1');
/*!40000 ALTER TABLE `company_tariff_type` ENABLE KEYS */;


-- Дамп структуры для таблица cargo_cabinets.payment_type_discount
CREATE TABLE IF NOT EXISTS `payment_type_discount` (
  `payment_type_id` int(11) NOT NULL,
  `discount_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `payer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы cargo_cabinets.payment_type_discount: ~10 rows (приблизительно)
/*!40000 ALTER TABLE `payment_type_discount` DISABLE KEYS */;
REPLACE INTO `payment_type_discount` (`payment_type_id`, `discount_id`, `company_id`, `payer_id`) VALUES
	(1, 1, 32, 1),
	(2, 1, 32, 1),
	(3, 1, 32, 1),
	(4, 1, 32, 1),
	(10, 2, 32, 1),
	(10, 2, 32, 2),
	(11, 2, 32, 2),
	(10, 2, 32, 3),
	(12, 2, 32, 3),
	(12, 2, 32, 2);
/*!40000 ALTER TABLE `payment_type_discount` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

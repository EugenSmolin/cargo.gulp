CREATE TABLE `company_activity` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `company_id` INT(11) NOT NULL DEFAULT '0',
  `is_active` BIT(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
  AUTO_INCREMENT=5
;

CREATE PROCEDURE populate_activity()
  BEGIN
    DECLARE count INT DEFAULT 1;
    WHILE count <= 150 DO
      INSERT INTO company_activity
      (company_id, is_active)
      VALUES (count, 0);
      SET count = count + 1;
    END WHILE;
  END;

EXECUTE populate_states();

DROP PROCEDURE IF EXISTS populate_activity;

CREATE TABLE `company_options` (
  `id` INT NOT NULL,
  `company_id` INT NOT NULL,
  `is_tariff_auto` BIT NOT NULL,
  `is_tariff_avia` BIT NOT NULL,
  `is_customer` BIT NOT NULL,
  `is_sender` BIT NOT NULL,
  `is_receiver` BIT NOT NULL,
  `customer_bank_card` BIT NOT NULL,
  `customer_qiwi_wallet` BIT NOT NULL,
  `customer_paycheck` BIT NOT NULL,
  `sender_bank_card` BIT NOT NULL,
  `sender_qiwi_wallet` BIT NOT NULL,
  `sender_paycheck` BIT NOT NULL,
  `receiver_bank_card` BIT NOT NULL,
  `receiver_qiwi_wallet` BIT NOT NULL,
  `receiver_paycheck` BIT NOT NULL,
  `discount_customer_bank_card` DECIMAL(5,2) NULL,
  `discount_customer_qiwi` DECIMAL(5,2) NULL,
  `discount_customer_paycheck` DECIMAL(5,2) NULL,
  `discount_sender_bank_card` DECIMAL(5,2) NULL,
  `discount_sender_qiwi` DECIMAL(5,2) NULL,
  `discount_sender_paycheck` DECIMAL(5,2) NULL,
  `discount_receiver_bank_card` DECIMAL(5,2) NULL,
  `discount_receiver_qiwi` DECIMAL(5,2) NULL,
  `discount_receiver_paycheck` DECIMAL(5,2) NULL,
  `tariff_auto_coeff` DECIMAL(7,4) NOT NULL,
  `tariff_avia_coeff` DECIMAL(7,4) NOT NULL,

  PRIMARY KEY (`id`));

CREATE TABLE `document_inserts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `contents` varchar(400) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);



CREATE DATABASE  IF NOT EXISTS `cargo_log` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `cargo_log`;
-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: cargo_log
-- ------------------------------------------------------
-- Server version	5.5.5-10.1.25-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `remote_addr` varbinary(16) DEFAULT NULL,
  `useragent` varchar(255) DEFAULT NULL,
  `client_country` varchar(15) DEFAULT NULL,
  `client_build` varchar(45) DEFAULT NULL,
  `client_version` varchar(45) DEFAULT NULL,
  `api_version` int(11) NOT NULL,
  `device_info` varchar(255) DEFAULT NULL,
  `device_id` varchar(255) NOT NULL,
  `os_version` varchar(100) DEFAULT NULL,
  `os_id` varchar(45) NOT NULL,
  `action` varchar(45) NOT NULL,
  `from` varchar(400) DEFAULT NULL,
  `where` varchar(400) DEFAULT NULL,
  `weight` float DEFAULT NULL,
  `volume` float DEFAULT NULL,
  `insurancePrice` float DEFAULT NULL,
  `compSite` varchar(255) DEFAULT NULL,
  `compName` varchar(255) DEFAULT NULL,
  `client_language` varchar(5) NOT NULL DEFAULT '',
  `apikey` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`action`,`os_id`,`device_id`,`api_version`,`datetime`,`client_language`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `addfields` (`compName`,`compSite`,`insurancePrice`,`volume`,`weight`,`where`(255),`from`(255)),
  KEY `apikey` (`apikey`)
) ENGINE=InnoDB AUTO_INCREMENT=554 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log`
--

LOCK TABLES `log` WRITE;
/*!40000 ALTER TABLE `log` DISABLE KEYS */;
INSERT INTO `log` VALUES (1,'2018-02-07 12:24:55','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(2,'2018-02-07 12:28:16','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(3,'2018-02-07 12:30:41','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(4,'2018-02-07 12:31:04','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(5,'2018-02-07 12:31:20','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(6,'2018-02-07 12:31:35','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(7,'2018-02-07 12:33:34','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(8,'2018-02-07 12:34:10','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(9,'2018-02-07 14:53:41','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(10,'2018-02-07 16:00:15','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(11,'2018-02-07 16:00:55','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(12,'2018-02-07 16:04:15','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(13,'2018-02-07 16:05:11','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(14,'2018-02-07 16:05:31','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(15,'2018-02-07 16:05:57','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(16,'2018-02-07 16:06:12','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(17,'2018-02-07 16:06:51','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(18,'2018-02-07 16:07:48','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(19,'2018-02-07 16:08:37','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(20,'2018-02-07 16:09:02','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(21,'2018-02-07 16:09:54','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(22,'2018-02-07 16:10:32','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(23,'2018-02-07 16:17:33','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(24,'2018-02-07 16:17:55','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(25,'2018-02-08 13:31:35','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(26,'2018-02-15 10:04:01','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','Москва','Санкт-Петербург',1,0.1,0,'','','ru','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(27,'2018-02-15 10:08:30','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','Москва','Санкт-Петербург',1,0.1,0,'','','ru','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(28,'2018-02-15 10:09:09','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','Киев','Львов',1,0.1,0,'','','ru','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(29,'2018-02-15 10:11:55','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','Москва','Санкт-Петербург',1,0.1,0,'','','ru','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(30,'2018-02-15 10:16:04','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','Москва','Санкт-Петербург',1,0.6,100,'','','ru','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(31,'2018-02-15 10:20:28','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','Москва','Санкт-Петербург',1,0.6,100,'','','ru','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(32,'2018-02-15 10:36:33','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','Москва','Санкт-Петербург',1,0.6,100,'','','ru','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(33,'2018-02-17 11:43:41','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','Москва','Санкт-Петербург',1,2,100,'','','ru','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(34,'2018-02-17 11:43:56','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','Москва','Санкт-Петербург',1,2,100,'','','ru','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(35,'2018-02-17 11:47:10','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','Москва','Санкт-Петербург',1,2,100,'','','ru','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(36,'2018-02-17 11:52:53','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(37,'2018-02-17 12:10:17','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(38,'2018-02-17 12:15:52','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(39,'2018-02-17 12:24:17','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(40,'2018-02-17 12:24:51','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(41,'2018-02-17 12:25:14','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(42,'2018-02-17 12:37:49','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(43,'2018-02-17 13:24:30','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(44,'2018-02-17 13:25:48','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(45,'2018-02-17 13:27:02','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(46,'2018-02-17 13:27:29','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(47,'2018-02-17 13:28:38','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(48,'2018-02-17 13:29:56','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(49,'2018-02-17 13:30:37','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(50,'2018-02-17 13:31:02','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(51,'2018-02-17 13:31:31','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(52,'2018-02-17 13:32:45','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(53,'2018-02-17 13:34:36','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(54,'2018-02-17 13:34:59','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(55,'2018-02-17 13:35:20','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(56,'2018-02-17 13:36:03','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(57,'2018-02-17 13:38:58','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(58,'2018-02-17 13:39:24','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(59,'2018-02-17 13:40:10','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(60,'2018-02-17 13:40:28','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(61,'2018-02-17 13:40:50','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(62,'2018-02-17 13:41:09','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(63,'2018-02-17 13:44:03','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(64,'2018-02-17 13:51:01','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','Москва','Санкт-Петербург',1,2,100,'','','ru','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(65,'2018-02-17 13:51:50','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','Москва','Санкт-Петербург',1,0.1,100,'','','ru','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(66,'2018-02-17 13:55:21','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','Москва','Санкт-Петербург',1,0.1,100,'','','ru','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(67,'2018-02-19 11:11:08','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(68,'2018-02-19 11:15:42','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(69,'2018-02-19 11:21:55','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(70,'2018-02-19 11:22:02','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(71,'2018-02-19 11:23:00','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(72,'2018-02-19 11:23:27','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(73,'2018-02-19 11:27:29','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(74,'2018-02-19 11:30:08','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(75,'2018-02-19 11:30:44','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(76,'2018-02-19 12:45:08','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(77,'2018-02-19 12:49:36','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(78,'2018-02-19 12:50:43','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(79,'2018-02-19 12:52:44','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(80,'2018-02-19 12:54:36','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(81,'2018-02-20 09:48:23','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(82,'2018-02-20 09:49:45','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(83,'2018-02-20 09:52:30','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(84,'2018-02-20 10:05:05','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(85,'2018-02-20 10:07:13','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(86,'2018-02-20 10:16:46','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(87,'2018-02-20 10:19:50','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(88,'2018-02-20 10:20:40','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(89,'2018-02-20 10:20:51','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(90,'2018-02-20 10:21:23','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(91,'2018-02-20 10:21:55','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(92,'2018-02-20 10:25:47','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(93,'2018-02-20 10:27:22','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(94,'2018-02-20 10:27:49','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(95,'2018-02-20 10:28:00','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(96,'2018-02-20 10:28:42','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(97,'2018-02-20 10:30:13','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(98,'2018-02-20 10:32:50','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(99,'2018-02-21 15:57:34','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(100,'2018-02-22 14:50:38','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(101,'2018-02-22 14:55:18','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(102,'2018-02-22 14:55:32','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(103,'2018-02-22 15:49:11','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(104,'2018-02-22 15:50:23','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(105,'2018-02-22 15:50:36','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(106,'2018-03-14 10:24:02','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(107,'2018-03-15 15:01:12','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(108,'2018-03-16 16:42:08','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(109,'2018-03-16 16:44:51','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(110,'2018-03-16 16:48:26','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(111,'2018-03-16 16:53:12','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(112,'2018-03-16 17:03:38','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(113,'2018-03-16 17:06:11','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(114,'2018-03-16 17:06:46','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(115,'2018-03-16 17:08:05','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(116,'2018-03-16 17:17:35','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(117,'2018-03-16 17:19:07','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(118,'2018-03-16 17:52:20','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(119,'2018-03-16 17:52:29','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(120,'2018-03-16 17:53:12','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(121,'2018-03-16 18:06:46','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(122,'2018-03-16 18:11:58','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(123,'2018-03-16 18:30:56','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(124,'2018-03-26 11:51:03','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(125,'2018-03-26 11:53:20','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(126,'2018-03-26 11:53:49','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(127,'2018-03-26 11:56:27','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(128,'2018-03-26 11:57:52','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(129,'2018-03-26 12:00:03','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(130,'2018-03-26 12:00:29','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(131,'2018-03-26 12:00:44','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(132,'2018-03-26 12:02:43','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(133,'2018-03-26 17:14:05','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(134,'2018-03-26 17:30:24','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(135,'2018-03-26 17:42:39','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(136,'2018-03-27 08:45:24','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(137,'2018-03-27 08:49:57','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(138,'2018-03-27 08:52:55','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(139,'2018-03-27 15:21:38','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(140,'2018-03-27 16:36:09','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(141,'2018-04-05 07:12:49','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(142,'2018-04-05 07:14:17','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(143,'2018-04-05 07:15:01','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(144,'2018-04-05 07:15:15','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(145,'2018-04-05 07:16:30','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(146,'2018-04-05 07:17:09','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(147,'2018-04-05 07:18:17','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(148,'2018-04-05 07:18:45','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(149,'2018-04-05 07:19:45','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(150,'2018-04-05 07:20:21','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(151,'2018-04-05 07:21:23','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(152,'2018-04-05 07:29:50','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(153,'2018-04-05 07:30:49','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(154,'2018-04-05 07:32:57','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(155,'2018-04-05 07:34:10','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(156,'2018-04-05 07:34:54','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(157,'2018-04-05 07:35:20','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(158,'2018-04-05 07:37:35','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(159,'2018-04-05 07:39:15','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(160,'2018-04-05 07:40:25','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(161,'2018-04-05 07:42:42','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(162,'2018-04-05 07:43:33','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(163,'2018-04-05 07:45:57','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(164,'2018-04-05 07:47:48','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(165,'2018-04-05 07:52:37','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(166,'2018-04-05 07:53:10','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(167,'2018-04-05 07:53:35','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(168,'2018-04-05 07:54:36','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(169,'2018-04-05 08:00:43','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(170,'2018-04-05 08:01:10','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(171,'2018-04-06 07:22:06','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(172,'2018-04-06 07:23:32','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(173,'2018-04-06 07:28:47','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(174,'2018-04-06 07:32:54','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(175,'2018-04-06 07:33:09','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(176,'2018-04-06 07:34:43','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(177,'2018-04-06 09:33:17','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(178,'2018-04-06 09:34:11','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(179,'2018-04-06 11:13:00','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','y5YiI1fPL51gtW3hygII3hHk3zmPSUH47rbduek3FbtMSPv6'),(180,'2018-04-06 11:13:15','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','y5YiI1fPL51gtW3hygII3hHk3zmPSUH47rbduek3FbtMSPv6'),(181,'2018-04-06 11:14:36','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','y5YiI1fPL51gtW3hygII3hHk3zmPSUH47rbduek3FbtMSPv6'),(182,'2018-04-11 10:49:48','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(183,'2018-04-16 08:34:22','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(184,'2018-04-17 10:55:36','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(185,'2018-04-17 10:56:05','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','y5YiI1fPL51gtW3hygII3hHk3zmPSUH47rbduek3FbtMSPv6'),(186,'2018-04-17 10:56:33','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','y5YiI1fPL51gtW3hygII3hHk3zmPSUH47rbduek3FbtMSPv6'),(187,'2018-04-17 10:56:53','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','y5YiI1fPL51gtW3hygII3hHk3zmPSUH47rbduek3FbtMSPv6'),(188,'2018-04-17 10:58:12','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','y5YiI1fPL51gtW3hygII3hHk3zmPSUH47rbduek3FbtMSPv6'),(189,'2018-04-17 10:59:20','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','y5YiI1fPL51gtW3hygII3hHk3zmPSUH47rbduek3FbtMSPv6'),(190,'2018-04-17 10:59:50','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','y5YiI1fPL51gtW3hygII3hHk3zmPSUH47rbduek3FbtMSPv6'),(191,'2018-04-17 11:00:44','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','y5YiI1fPL51gtW3hygII3hHk3zmPSUH47rbduek3FbtMSPv6'),(192,'2018-04-17 11:01:00','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','y5YiI1fPL51gtW3hygII3hHk3zmPSUH47rbduek3FbtMSPv6'),(193,'2018-04-17 11:01:15','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','y5YiI1fPL51gtW3hygII3hHk3zmPSUH47rbduek3FbtMSPv6'),(194,'2018-04-17 11:01:50','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','y5YiI1fPL51gtW3hygII3hHk3zmPSUH47rbduek3FbtMSPv6'),(195,'2018-04-17 11:02:31','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','y5YiI1fPL51gtW3hygII3hHk3zmPSUH47rbduek3FbtMSPv6'),(196,'2018-04-17 11:02:50','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','y5YiI1fPL51gtW3hygII3hHk3zmPSUH47rbduek3FbtMSPv6'),(197,'2018-04-17 11:03:17','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','y5YiI1fPL51gtW3hygII3hHk3zmPSUH47rbduek3FbtMSPv6'),(198,'2018-04-17 11:03:27','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','y5YiI1fPL51gtW3hygII3hHk3zmPSUH47rbduek3FbtMSPv6'),(199,'2018-04-17 11:12:20','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','y5YiI1fPL51gtW3hygII3hHk3zmPSUH47rbduek3FbtMSPv6'),(200,'2018-04-17 11:12:47','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','y5YiI1fPL51gtW3hygII3hHk3zmPSUH47rbduek3FbtMSPv6'),(201,'2018-04-17 11:12:54','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','y5YiI1fPL51gtW3hygII3hHk3zmPSUH47rbduek3FbtMSPv6'),(202,'2018-04-17 11:12:59','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','y5YiI1fPL51gtW3hygII3hHk3zmPSUH47rbduek3FbtMSPv6'),(203,'2018-04-17 11:13:09','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','y5YiI1fPL51gtW3hygII3hHk3zmPSUH47rbduek3FbtMSPv6'),(204,'2018-04-17 14:31:04','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(205,'2018-04-17 14:36:01','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(206,'2018-04-17 14:36:46','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(207,'2018-04-17 14:37:20','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(208,'2018-04-17 14:38:24','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(209,'2018-04-17 14:39:44','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(210,'2018-04-17 14:43:43','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(211,'2018-04-17 14:45:16','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(212,'2018-04-17 14:47:23','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(213,'2018-04-19 07:23:45','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(214,'2018-04-19 07:25:34','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(215,'2018-04-19 07:27:35','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(216,'2018-04-20 09:34:56','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(217,'2018-04-25 09:01:28','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(218,'2018-04-25 09:02:46','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(219,'2018-04-25 09:03:23','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(220,'2018-04-25 09:04:29','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(221,'2018-04-26 09:09:44','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(222,'2018-04-26 09:10:24','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(223,'2018-04-26 09:11:59','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(224,'2018-04-26 09:13:17','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(225,'2018-04-26 09:14:02','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(226,'2018-04-26 09:14:46','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(227,'2018-04-26 09:18:15','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(228,'2018-04-26 09:19:43','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(229,'2018-04-26 09:45:32','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(230,'2018-04-26 13:08:56','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(231,'2018-04-26 13:10:00','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(232,'2018-04-26 14:27:51','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(233,'2018-05-10 13:25:54','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(234,'2018-05-11 10:05:13','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(235,'2018-05-11 10:08:11','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(236,'2018-05-11 10:10:02','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(237,'2018-05-11 10:10:46','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(238,'2018-05-11 10:18:50','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(239,'2018-05-11 10:18:58','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(240,'2018-05-11 10:19:30','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(241,'2018-05-11 10:33:09','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(242,'2018-05-11 10:37:44','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(243,'2018-05-11 10:37:55','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(244,'2018-05-11 10:40:00','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(245,'2018-05-11 10:42:08','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(246,'2018-05-11 10:49:14','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(247,'2018-05-11 10:49:36','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(248,'2018-05-11 10:50:41','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(249,'2018-05-11 10:52:51','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(250,'2018-05-11 10:54:00','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(251,'2018-05-11 11:00:06','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(252,'2018-05-11 11:00:27','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(253,'2018-05-11 11:01:23','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(254,'2018-05-11 11:03:21','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(255,'2018-05-11 11:03:36','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(256,'2018-05-11 11:03:54','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(257,'2018-05-11 11:29:18','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(258,'2018-05-11 11:31:36','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(259,'2018-05-11 11:33:37','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(260,'2018-05-11 11:34:49','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(261,'2018-05-11 11:35:38','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(262,'2018-05-11 11:35:48','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(263,'2018-05-11 11:36:15','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(264,'2018-05-11 11:36:28','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(265,'2018-05-11 11:36:44','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(266,'2018-05-11 11:37:11','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(267,'2018-05-11 11:37:49','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(268,'2018-05-11 11:38:10','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(269,'2018-05-17 12:27:38','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(270,'2018-05-17 12:28:12','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(271,'2018-05-17 12:28:50','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(272,'2018-05-17 12:29:03','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(273,'2018-05-17 12:29:57','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(274,'2018-05-17 12:33:30','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(275,'2018-05-17 12:35:08','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(276,'2018-05-17 12:35:59','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(277,'2018-05-17 12:36:21','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(278,'2018-05-17 12:36:46','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(279,'2018-05-17 12:37:06','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(280,'2018-05-17 12:37:42','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(281,'2018-05-17 12:37:59','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(282,'2018-05-17 12:38:27','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(283,'2018-05-17 12:38:52','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(284,'2018-05-17 12:39:43','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(285,'2018-05-17 12:41:24','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(286,'2018-05-17 12:42:03','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(287,'2018-05-17 12:42:49','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(288,'2018-05-17 12:44:19','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(289,'2018-05-17 12:48:12','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(290,'2018-05-17 12:48:28','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(291,'2018-05-17 12:54:57','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(292,'2018-05-17 12:56:04','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(293,'2018-05-18 10:17:32','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(294,'2018-05-18 10:19:45','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(295,'2018-05-18 10:22:51','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(296,'2018-05-18 10:24:20','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(297,'2018-05-18 10:24:41','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(298,'2018-05-18 10:26:15','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(299,'2018-05-18 10:40:21','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(300,'2018-05-18 10:52:07','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(301,'2018-05-18 10:54:43','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(302,'2018-05-18 10:55:08','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(303,'2018-05-18 10:57:34','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(304,'2018-05-18 10:58:18','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(305,'2018-05-18 10:58:25','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(306,'2018-05-18 11:00:20','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(307,'2018-05-18 11:00:55','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(308,'2018-05-18 11:03:45','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(309,'2018-05-18 11:04:24','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(310,'2018-05-18 11:05:29','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(311,'2018-05-18 11:06:26','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(312,'2018-05-18 12:02:11','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(313,'2018-05-18 12:02:49','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(314,'2018-05-18 12:03:33','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(315,'2018-05-18 12:30:10','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(316,'2018-05-18 12:30:51','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(317,'2018-05-18 12:35:19','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(318,'2018-05-18 12:36:01','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(319,'2018-05-18 12:39:15','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(320,'2018-05-18 12:39:35','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(321,'2018-05-18 12:40:50','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(322,'2018-05-18 12:41:09','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(323,'2018-05-18 12:41:50','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(324,'2018-05-18 12:42:06','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(325,'2018-05-18 12:43:40','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(326,'2018-05-18 12:44:01','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(327,'2018-05-18 12:44:17','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(328,'2018-05-18 12:44:28','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(329,'2018-05-18 12:44:37','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(330,'2018-05-18 12:45:28','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(331,'2018-05-18 12:45:44','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(332,'2018-05-18 12:46:14','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(333,'2018-05-18 12:46:21','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(334,'2018-05-18 12:48:41','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(335,'2018-05-18 12:48:51','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(336,'2018-05-18 12:57:10','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(337,'2018-05-18 12:58:04','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(338,'2018-05-18 12:58:16','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(339,'2018-05-18 12:59:28','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(340,'2018-05-18 13:19:10','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(341,'2018-05-18 13:19:22','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(342,'2018-05-18 13:20:45','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(343,'2018-05-18 13:33:10','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(344,'2018-05-18 13:43:02','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(345,'2018-05-18 13:43:48','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(346,'2018-05-18 13:45:08','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(347,'2018-05-18 13:46:01','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(348,'2018-05-18 13:46:21','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(349,'2018-05-18 13:46:31','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(350,'2018-05-18 13:47:18','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(351,'2018-05-18 13:47:45','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(352,'2018-05-18 13:47:54','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(353,'2018-05-18 13:48:07','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(354,'2018-05-18 13:48:15','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(355,'2018-05-18 13:48:26','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(356,'2018-05-18 13:48:33','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(357,'2018-05-18 13:48:46','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(358,'2018-05-18 13:48:57','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(359,'2018-05-18 13:49:27','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(360,'2018-05-18 14:20:20','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(361,'2018-05-18 14:20:36','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(362,'2018-05-18 14:20:54','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(363,'2018-05-18 14:21:05','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(364,'2018-05-18 14:21:37','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(365,'2018-05-18 14:21:54','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(366,'2018-05-18 14:22:07','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(367,'2018-05-18 14:22:33','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(368,'2018-05-18 14:22:48','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(369,'2018-05-18 14:23:08','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(370,'2018-05-18 14:24:09','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(371,'2018-05-18 14:24:22','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(372,'2018-05-18 14:24:36','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(373,'2018-05-18 14:24:45','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(374,'2018-05-18 14:25:39','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(375,'2018-05-18 14:26:03','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(376,'2018-05-18 14:26:11','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(377,'2018-05-18 14:26:23','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(378,'2018-05-18 14:26:38','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(379,'2018-05-18 14:26:58','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(380,'2018-05-18 14:27:10','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(381,'2018-05-18 14:27:19','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(382,'2018-05-18 14:27:43','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(383,'2018-05-18 14:27:55','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(384,'2018-05-18 14:36:56','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(385,'2018-05-18 14:37:56','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(386,'2018-05-18 14:38:29','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(387,'2018-05-18 14:42:48','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(388,'2018-05-18 14:43:05','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(389,'2018-05-18 14:43:21','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(390,'2018-05-18 14:44:02','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(391,'2018-05-18 14:44:32','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(392,'2018-05-18 14:45:50','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(393,'2018-05-18 15:11:19','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(394,'2018-05-18 15:12:25','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(395,'2018-05-18 15:13:31','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(396,'2018-05-18 15:13:48','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(397,'2018-05-18 15:15:04','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(398,'2018-05-18 15:15:24','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(399,'2018-05-22 09:21:53','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(400,'2018-05-22 10:25:57','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(401,'2018-05-30 08:54:56','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(402,'2018-05-30 08:56:22','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(403,'2018-05-30 08:57:33','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(404,'2018-05-30 08:58:51','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(405,'2018-05-30 08:59:05','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(406,'2018-05-30 08:59:33','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(407,'2018-05-30 09:00:03','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(408,'2018-05-30 09:00:19','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(409,'2018-05-30 09:00:53','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(410,'2018-05-30 09:02:44','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(411,'2018-05-30 09:02:57','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(412,'2018-05-30 09:03:26','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(413,'2018-05-30 09:03:49','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(414,'2018-05-30 09:04:33','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(415,'2018-05-30 09:08:01','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(416,'2018-05-30 09:10:31','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(417,'2018-05-30 09:11:12','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(418,'2018-05-30 09:12:50','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(419,'2018-05-30 09:13:13','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(420,'2018-05-30 09:13:29','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(421,'2018-05-30 09:13:49','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(422,'2018-05-30 09:15:22','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(423,'2018-05-30 09:16:04','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(424,'2018-05-30 09:17:07','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(425,'2018-05-30 09:17:57','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(426,'2018-05-30 09:18:29','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(427,'2018-05-31 10:06:26','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(428,'2018-05-31 10:10:58','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(429,'2018-05-31 10:11:10','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(430,'2018-05-31 10:11:44','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(431,'2018-05-31 10:12:19','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(432,'2018-05-31 10:13:09','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(433,'2018-05-31 10:14:03','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(434,'2018-05-31 10:15:28','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(435,'2018-05-31 10:18:03','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(436,'2018-05-31 10:19:23','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(437,'2018-05-31 10:21:30','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(438,'2018-05-31 10:22:02','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(439,'2018-05-31 10:23:26','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(440,'2018-05-31 10:24:10','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(441,'2018-05-31 10:24:39','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(442,'2018-05-31 10:25:13','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(443,'2018-05-31 10:25:52','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(444,'2018-05-31 10:26:20','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(445,'2018-05-31 10:27:05','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(446,'2018-05-31 10:28:00','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(447,'2018-05-31 10:28:27','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(448,'2018-05-31 10:29:35','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(449,'2018-05-31 10:30:49','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(450,'2018-05-31 10:31:12','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(451,'2018-05-31 10:31:28','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(452,'2018-05-31 10:31:41','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(453,'2018-05-31 10:32:20','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(454,'2018-05-31 10:32:39','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(455,'2018-05-31 10:33:02','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(456,'2018-05-31 10:33:26','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(457,'2018-05-31 10:33:41','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(458,'2018-05-31 10:41:01','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(459,'2018-05-31 10:54:40','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(460,'2018-05-31 10:57:49','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(461,'2018-05-31 11:11:19','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(462,'2018-05-31 11:14:37','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(463,'2018-05-31 11:14:53','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(464,'2018-05-31 11:18:52','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(465,'2018-05-31 11:48:28','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(466,'2018-06-01 06:40:46','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(467,'2018-06-01 07:32:52','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(468,'2018-06-01 07:35:58','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(469,'2018-06-01 07:40:30','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(470,'2018-06-01 07:42:32','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(471,'2018-06-01 07:47:28','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(472,'2018-06-01 07:52:08','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(473,'2018-06-01 07:59:59','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(474,'2018-06-01 08:04:55','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(475,'2018-06-01 08:11:30','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(476,'2018-06-01 08:15:18','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(477,'2018-06-01 08:44:08','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(478,'2018-06-01 08:48:45','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(479,'2018-06-01 08:56:50','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(480,'2018-06-01 08:57:45','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(481,'2018-06-01 08:58:06','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(482,'2018-06-01 11:01:24','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(483,'2018-06-01 11:02:31','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(484,'2018-06-01 11:13:24','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(485,'2018-06-01 11:15:50','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(486,'2018-06-01 11:16:54','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(487,'2018-06-01 11:18:23','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(488,'2018-06-01 11:22:00','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(489,'2018-06-01 11:37:56','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(490,'2018-06-01 11:38:31','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(491,'2018-06-01 11:38:53','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(492,'2018-06-01 11:39:06','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(493,'2018-06-01 11:39:38','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(494,'2018-06-01 11:39:56','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(495,'2018-06-01 11:40:10','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(496,'2018-06-01 11:40:32','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(497,'2018-06-01 11:41:46','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(498,'2018-06-01 11:42:13','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(499,'2018-06-01 11:42:40','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(500,'2018-06-01 11:43:41','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(501,'2018-06-01 11:44:31','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(502,'2018-06-01 11:45:47','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(503,'2018-06-01 11:47:49','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(504,'2018-06-01 11:50:10','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(505,'2018-06-01 11:51:06','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(506,'2018-06-01 11:51:44','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(507,'2018-06-01 11:52:05','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(508,'2018-06-01 11:52:46','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(509,'2018-06-01 11:53:28','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(510,'2018-06-01 11:59:07','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(511,'2018-06-01 12:24:01','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(512,'2018-06-01 12:25:39','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(513,'2018-06-01 12:26:29','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(514,'2018-06-01 12:29:06','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(515,'2018-06-01 12:30:36','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(516,'2018-06-01 12:32:37','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(517,'2018-06-01 12:33:00','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(518,'2018-06-01 12:42:25','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(519,'2018-06-01 12:43:14','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(520,'2018-06-01 12:44:06','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(521,'2018-06-04 11:44:59','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(522,'2018-06-04 11:46:45','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(523,'2018-06-04 11:47:26','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(524,'2018-06-05 09:45:00','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(525,'2018-06-07 07:47:08','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(526,'2018-06-07 15:06:08','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(527,'2018-06-07 15:09:03','\0\0','PostmanRuntime/7.1.1','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(528,'2018-06-25 10:31:55','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(529,'2018-06-25 10:34:35','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(530,'2018-06-25 10:35:24','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(531,'2018-06-25 10:35:53','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(532,'2018-06-25 10:36:20','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(533,'2018-06-25 10:36:51','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(534,'2018-06-25 10:37:27','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(535,'2018-06-25 10:44:02','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(536,'2018-06-25 10:44:56','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(537,'2018-06-25 10:48:41','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(538,'2018-06-25 10:51:17','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(539,'2018-06-25 10:52:01','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(540,'2018-06-25 13:39:29','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(541,'2018-06-25 13:42:51','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(542,'2018-06-25 13:44:09','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(543,'2018-06-25 13:44:54','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(544,'2018-06-25 13:46:14','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(545,'2018-06-25 14:24:27','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(546,'2018-06-25 14:33:14','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(547,'2018-06-25 14:37:46','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(548,'2018-06-25 14:38:30','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(549,'2018-06-26 08:35:58','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(550,'2018-06-26 08:38:07','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(551,'2018-06-26 08:40:24','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','get_calculation','','',0,0,0,'','','unkno','RN42O4ntxJJen8GBixIf5BGCMPwwie'),(552,'2018-06-26 12:16:57','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','','','',0,0,0,'','','unkno',''),(553,'2018-06-26 12:17:13','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','','','',0,0,0,'','','unkno','');
/*!40000 ALTER TABLE `log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_old25122016`
--

DROP TABLE IF EXISTS `log_old25122016`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_old25122016` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `remote_addr` varbinary(16) DEFAULT NULL,
  `useragent` varchar(255) DEFAULT NULL,
  `client_country` varchar(15) DEFAULT NULL,
  `client_build` varchar(45) DEFAULT NULL,
  `client_version` varchar(45) DEFAULT NULL,
  `api_version` int(11) NOT NULL,
  `device_info` varchar(255) DEFAULT NULL,
  `device_id` varchar(255) NOT NULL,
  `os_version` varchar(100) DEFAULT NULL,
  `os_id` varchar(45) NOT NULL,
  `action` varchar(45) NOT NULL,
  `from` varchar(400) DEFAULT NULL,
  `where` varchar(400) DEFAULT NULL,
  `weight` float DEFAULT NULL,
  `volume` float DEFAULT NULL,
  `insurancePrice` float DEFAULT NULL,
  `compSite` varchar(255) DEFAULT NULL,
  `compName` varchar(255) DEFAULT NULL,
  `client_language` varchar(5) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`,`action`,`os_id`,`device_id`,`api_version`,`datetime`,`client_language`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `addfields` (`compName`,`compSite`,`insurancePrice`,`volume`,`weight`,`where`(255),`from`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_old25122016`
--

LOCK TABLES `log_old25122016` WRITE;
/*!40000 ALTER TABLE `log_old25122016` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_old25122016` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `web_log`
--

DROP TABLE IF EXISTS `web_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `remote_addr` varbinary(16) DEFAULT NULL,
  `useragent` varchar(255) DEFAULT NULL,
  `client_country` varchar(15) DEFAULT NULL,
  `client_build` varchar(45) DEFAULT NULL,
  `client_version` varchar(45) DEFAULT NULL,
  `api_version` int(11) NOT NULL,
  `device_info` varchar(255) DEFAULT NULL,
  `device_id` varchar(255) NOT NULL,
  `os_version` varchar(100) DEFAULT NULL,
  `os_id` varchar(45) NOT NULL,
  `action` varchar(45) NOT NULL,
  `from` varchar(400) DEFAULT NULL,
  `where` varchar(400) DEFAULT NULL,
  `weight` float DEFAULT NULL,
  `volume` float DEFAULT NULL,
  `insurancePrice` float DEFAULT NULL,
  `compId` int(11) DEFAULT NULL,
  `client_language` varchar(5) NOT NULL DEFAULT '',
  `apikey` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`action`,`os_id`,`device_id`,`api_version`,`datetime`,`client_language`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `addfields` (`compId`,`insurancePrice`,`volume`,`weight`,`where`(255),`from`(255)),
  KEY `apikey` (`apikey`)
) ENGINE=InnoDB AUTO_INCREMENT=564 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_log`
--

LOCK TABLES `web_log` WRITE;
/*!40000 ALTER TABLE `web_log` DISABLE KEYS */;
INSERT INTO `web_log` VALUES (552,'2018-06-26 12:45:34','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','','','',0,0,0,15,'unkno',''),(553,'2018-06-26 12:46:06','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','','','',0,0,0,15,'unkno',''),(554,'2018-06-26 12:51:15','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','','','',0,0,0,3,'unkno',''),(555,'2018-06-26 12:52:02','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','','','',0,0,0,1,'unkno',''),(556,'2018-06-26 12:52:07','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','','','',0,0,0,1,'unkno',''),(557,'2018-06-26 12:52:13','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','','','',0,0,0,2,'unkno',''),(558,'2018-06-26 12:52:24','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','','','',0,0,0,4,'unkno',''),(559,'2018-06-26 12:52:30','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','','','',0,0,0,4,'unkno',''),(560,'2018-06-26 12:52:33','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','','','',0,0,0,5,'unkno',''),(561,'2018-06-26 12:52:36','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','','','',0,0,0,7,'unkno',''),(562,'2018-06-26 12:52:39','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','','','',0,0,0,7,'unkno',''),(563,'2018-06-26 12:52:44','\0\0','PostmanRuntime/7.1.5','unknown','unknown','unknown',1,'unknown','unknown','unknown','unknown','','','',0,0,0,8,'unkno','');
/*!40000 ALTER TABLE `web_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'cargo_log'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-06-27 10:09:07

ALTER TABLE `orders` ADD `stat_id` INT;
UPDATE `orders` SET `stat_id` = 1 where id;

CREATE TABLE `company_tariff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `company_tariff` (`name`) VALUES
('Авто'),
('Авиа')

ALTER TABLE `accounts` ADD `is_deleted` INT;
UPDATE `accounts` SET `is_deleted` = 0;

ALTER TABLE `payment_type_discount` ADD `discount_value` DECIMAL (4, 2);
ALTER TABLE `payment_type_discount` DROP COLUMN `discount_id`;

CREATE TABLE `cargo_cabinets`.`companies` (
  `id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NULL,
  `module_name` VARCHAR(45) NULL);

LOCK TABLES `companies` WRITE;
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
INSERT INTO `companies` VALUES (109,'2GO Supply Chain',NULL),(1,'American Airlines Cargo',NULL),(2,'ABS group',NULL),(121,'ACE Courier Services',NULL),(132,'Адель Сервиc',NULL),(7,'AIR21',NULL),(32,'Деловые Линии',NULL);
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;
UNLOCK TABLES;

-- Дамп структуры для таблица cargo_cabinets.companies
CREATE TABLE IF NOT EXISTS `companies` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `module_name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icon_url` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phones` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы cargo_cabinets.companies: ~8 rows (приблизительно)
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
INSERT INTO `companies` (`id`, `name`, `module_name`, `icon_url`, `country`, `phones`, `email`) VALUES
	(109, '2GO Supply Chain', NULL, NULL, NULL, NULL, NULL),
	(1, 'American Airlines Cargo', NULL, NULL, NULL, NULL, NULL),
	(2, 'ABS group', NULL, NULL, NULL, NULL, NULL),
	(121, 'ACE Courier Services', NULL, NULL, NULL, NULL, NULL),
	(132, 'Адель Сервиc', NULL, NULL, NULL, NULL, NULL),
	(7, 'AIR21', NULL, NULL, NULL, NULL, NULL),
	(32, 'Деловые Линии', NULL, 'https://www.dellin.ru/assets/layout/logo-c9d249def2b4e71903781c92cc7fb21d1b3f2a047dbf81d7411e3b69bb7bd4b7.svg', 'RU', NULL, NULL),
	(59, 'Первая Экспедиционная Компания', '', 'https://pecom.ru/img/logo.png', 'RU', NULL, NULL);
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;

CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iso` char(2) NOT NULL,
  `name` varchar(80) NOT NULL,
  `nicename` varchar(80) NOT NULL,
  `iso3` char(3) DEFAULT NULL,
  `numcode` smallint(6) DEFAULT NULL,
  `phonecode` int(5) NOT NULL,
  `name_rus` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

--
-- Dumping data for table `country`
--

INSERT INTO `countries` (`id`, `iso`, `name`, `nicename`, `iso3`, `numcode`, `phonecode`, `name_rus`) VALUES
(1, 'AF', 'AFGHANISTAN', 'Afghanistan', 'AFG', 4, 93, 'Афганистан'),
(2, 'AL', 'ALBANIA', 'Albania', 'ALB', 8, 355, 'Албания'),
(3, 'DZ', 'ALGERIA', 'Algeria', 'DZA', 12, 213, 'Алжир'),
(4, 'AS', 'AMERICAN SAMOA', 'American Samoa', 'ASM', 16, 1684, ''),
(5, 'AD', 'ANDORRA', 'Andorra', 'AND', 20, 376, 'Андорра'),
(6, 'AO', 'ANGOLA', 'Angola', 'AGO', 24, 244, 'Ангола'),
(7, 'AI', 'ANGUILLA', 'Anguilla', 'AIA', 660, 1264, ''),
(8, 'AQ', 'ANTARCTICA', 'Antarctica', NULL, NULL, 0, 'Антарктика'),
(9, 'AG', 'ANTIGUA AND BARBUDA', 'Antigua and Barbuda', 'ATG', 28, 1268, ''),
(10, 'AR', 'ARGENTINA', 'Argentina', 'ARG', 32, 54, 'Аргентина'),
(11, 'AM', 'ARMENIA', 'Armenia', 'ARM', 51, 374, 'Армения'),
(12, 'AW', 'ARUBA', 'Aruba', 'ABW', 533, 297, ''),
(13, 'AU', 'AUSTRALIA', 'Australia', 'AUS', 36, 61, 'Австралия'),
(14, 'AT', 'AUSTRIA', 'Austria', 'AUT', 40, 43, 'Австрия'),
(15, 'AZ', 'AZERBAIJAN', 'Azerbaijan', 'AZE', 31, 994, 'Азербайджан'),
(16, 'BS', 'BAHAMAS', 'Bahamas', 'BHS', 44, 1242, 'Багамы'),
(17, 'BH', 'BAHRAIN', 'Bahrain', 'BHR', 48, 973, 'Бахрейн'),
(18, 'BD', 'BANGLADESH', 'Bangladesh', 'BGD', 50, 880, 'Бангладеш'),
(19, 'BB', 'BARBADOS', 'Barbados', 'BRB', 52, 1246, 'Барбадос'),
(20, 'BY', 'BELARUS', 'Belarus', 'BLR', 112, 375, 'Беларусь'),
(21, 'BE', 'BELGIUM', 'Belgium', 'BEL', 56, 32, 'Бельгия'),
(22, 'BZ', 'BELIZE', 'Belize', 'BLZ', 84, 501, ''),
(23, 'BJ', 'BENIN', 'Benin', 'BEN', 204, 229, ''),
(24, 'BM', 'BERMUDA', 'Bermuda', 'BMU', 60, 1441, 'Бермуды'),
(25, 'BT', 'BHUTAN', 'Bhutan', 'BTN', 64, 975, 'Бутан'),
(26, 'BO', 'BOLIVIA', 'Bolivia', 'BOL', 68, 591, 'Боливия'),
(27, 'BA', 'BOSNIA AND HERZEGOVINA', 'Bosnia and Herzegovina', 'BIH', 70, 387, 'Босния и Герцеговина'),
(28, 'BW', 'BOTSWANA', 'Botswana', 'BWA', 72, 267, 'Ботсвана'),
(29, 'BV', 'BOUVET ISLAND', 'Bouvet Island', NULL, NULL, 0, ''),
(30, 'BR', 'BRAZIL', 'Brazil', 'BRA', 76, 55, 'Бразилия'),
(31, 'IO', 'BRITISH INDIAN OCEAN TERRITORY', 'British Indian Ocean Territory', NULL, NULL, 246, ''),
(32, 'BN', 'BRUNEI DARUSSALAM', 'Brunei Darussalam', 'BRN', 96, 673, ''),
(33, 'BG', 'BULGARIA', 'Bulgaria', 'BGR', 100, 359, 'Болгария'),
(34, 'BF', 'BURKINA FASO', 'Burkina Faso', 'BFA', 854, 226, 'Буркина Фасо'),
(35, 'BI', 'BURUNDI', 'Burundi', 'BDI', 108, 257, ''),
(36, 'KH', 'CAMBODIA', 'Cambodia', 'KHM', 116, 855, 'Камбоджа'),
(37, 'CM', 'CAMEROON', 'Cameroon', 'CMR', 120, 237, 'Камерун'),
(38, 'CA', 'CANADA', 'Canada', 'CAN', 124, 1, 'Канада'),

(39, 'CV', 'CAPE VERDE', 'Cape Verde', 'CPV', 132, 238, ''),

(40, 'KY', 'CAYMAN ISLANDS', 'Cayman Islands', 'CYM', 136, 1345, 'Каймановы Острова'),

(41, 'CF', 'CENTRAL AFRICAN REPUBLIC', 'Central African Republic', 'CAF', 140, 236, 'Центрально-африканская республика'),

(42, 'TD', 'CHAD', 'Chad', 'TCD', 148, 235, 'Чад'),

(43, 'CL', 'CHILE', 'Chile', 'CHL', 152, 56, 'Чили'),

(44, 'CN', 'CHINA', 'China', 'CHN', 156, 86, 'Китай'),

(45, 'CX', 'CHRISTMAS ISLAND', 'Christmas Island', NULL, NULL, 61, ''),

(46, 'CC', 'COCOS (KEELING) ISLANDS', 'Cocos (Keeling) Islands', NULL, NULL, 672, ''),

(47, 'CO', 'COLOMBIA', 'Colombia', 'COL', 170, 57, 'Колумбия'),

(48, 'KM', 'COMOROS', 'Comoros', 'COM', 174, 269, ''),

(49, 'CG', 'CONGO', 'Congo', 'COG', 178, 242, 'Конго'),

(50, 'CD', 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'Congo, the Democratic Republic of the', 'COD', 180, 242, 'Конго, Демократическая республика'),

(51, 'CK', 'COOK ISLANDS', 'Cook Islands', 'COK', 184, 682, ''),

(52, 'CR', 'COSTA RICA', 'Costa Rica', 'CRI', 188, 506, 'Коста Рика'),

(53, 'CI', 'COTE D''IVOIRE', 'Cote D''Ivoire', 'CIV', 384, 225, 'Кот-д’Ивуар'),

(54, 'HR', 'CROATIA', 'Croatia', 'HRV', 191, 385, 'Хорватия'),

(55, 'CU', 'CUBA', 'Cuba', 'CUB', 192, 53, 'Куба'),

(56, 'CY', 'CYPRUS', 'Cyprus', 'CYP', 196, 357, 'Кипр'),

(57, 'CZ', 'CZECH REPUBLIC', 'Czech Republic', 'CZE', 203, 420, 'Чехия'),

(58, 'DK', 'DENMARK', 'Denmark', 'DNK', 208, 45, 'Дания'),

(59, 'DJ', 'DJIBOUTI', 'Djibouti', 'DJI', 262, 253, ''),

(60, 'DM', 'DOMINICA', 'Dominica', 'DMA', 212, 1767, 'Доминика'),

(61, 'DO', 'DOMINICAN REPUBLIC', 'Dominican Republic', 'DOM', 214, 1809, 'Доминиканская республика'),

(62, 'EC', 'ECUADOR', 'Ecuador', 'ECU', 218, 593, 'Эквадор'),

(63, 'EG', 'EGYPT', 'Egypt', 'EGY', 818, 20, 'Египет'),

(64, 'SV', 'EL SALVADOR', 'El Salvador', 'SLV', 222, 503, 'Эль Сальвадор'),

(65, 'GQ', 'EQUATORIAL GUINEA', 'Equatorial Guinea', 'GNQ', 226, 240, 'Экваториальная Гвинея'),

(66, 'ER', 'ERITREA', 'Eritrea', 'ERI', 232, 291, 'Эритрея'),

(67, 'EE', 'ESTONIA', 'Estonia', 'EST', 233, 372, 'Эстония'),

(68, 'ET', 'ETHIOPIA', 'Ethiopia', 'ETH', 231, 251, 'Эфиопия'),

(69, 'FK', 'FALKLAND ISLANDS (MALVINAS)', 'Falkland Islands (Malvinas)', 'FLK', 238, 500, 'Фолклендские острова (Мальвины)'),

(70, 'FO', 'FAROE ISLANDS', 'Faroe Islands', 'FRO', 234, 298, 'Фарерские Острова'),

(71, 'FJ', 'FIJI', 'Fiji', 'FJI', 242, 679, 'Фиджи'),

(72, 'FI', 'FINLAND', 'Finland', 'FIN', 246, 358, 'Финляндия'),

(73, 'FR', 'FRANCE', 'France', 'FRA', 250, 33, 'Франция'),

(74, 'GF', 'FRENCH GUIANA', 'French Guiana', 'GUF', 254, 594, ''),

(75, 'PF', 'FRENCH POLYNESIA', 'French Polynesia', 'PYF', 258, 689, 'Французская Полинезия'),

(76, 'TF', 'FRENCH SOUTHERN TERRITORIES', 'French Southern Territories', NULL, NULL, 0, ''),

(77, 'GA', 'GABON', 'Gabon', 'GAB', 266, 241, 'Габон'),

(78, 'GM', 'GAMBIA', 'Gambia', 'GMB', 270, 220, 'Гамбия'),

(79, 'GE', 'GEORGIA', 'Georgia', 'GEO', 268, 995, 'Грузия'),

(80, 'DE', 'GERMANY', 'Germany', 'DEU', 276, 49, 'Германия'),

(81, 'GH', 'GHANA', 'Ghana', 'GHA', 288, 233, 'Гана'),

(82, 'GI', 'GIBRALTAR', 'Gibraltar', 'GIB', 292, 350, 'Гибралтар'),

(83, 'GR', 'GREECE', 'Greece', 'GRC', 300, 30, 'Греция'),

(84, 'GL', 'GREENLAND', 'Greenland', 'GRL', 304, 299, 'Гренландия'),

(85, 'GD', 'GRENADA', 'Grenada', 'GRD', 308, 1473, 'Гренада'),

(86, 'GP', 'GUADELOUPE', 'Guadeloupe', 'GLP', 312, 590, 'Гваделупа'),

(87, 'GU', 'GUAM', 'Guam', 'GUM', 316, 1671, 'Гуам'),

(88, 'GT', 'GUATEMALA', 'Guatemala', 'GTM', 320, 502, 'Гватемала'),

(89, 'GN', 'GUINEA', 'Guinea', 'GIN', 324, 224, 'Гвинея'),

(90, 'GW', 'GUINEA-BISSAU', 'Guinea-Bissau', 'GNB', 624, 245, ''),

(91, 'GY', 'GUYANA', 'Guyana', 'GUY', 328, 592, 'Гайана'),

(92, 'HT', 'HAITI', 'Haiti', 'HTI', 332, 509, 'Гаити'),

(93, 'HM', 'HEARD ISLAND AND MCDONALD ISLANDS', 'Heard Island and Mcdonald Islands', NULL, NULL, 0, ''),

(94, 'VA', 'HOLY SEE (VATICAN CITY STATE)', 'Holy See (Vatican City State)', 'VAT', 336, 39, ''),

(95, 'HN', 'HONDURAS', 'Honduras', 'HND', 340, 504, 'Гондурас'),

(96, 'HK', 'HONG KONG', 'Hong Kong', 'HKG', 344, 852, 'Гонконг'),

(97, 'HU', 'HUNGARY', 'Hungary', 'HUN', 348, 36, 'Венгрия'),

(98, 'IS', 'ICELAND', 'Iceland', 'ISL', 352, 354, 'Исландия'),

(99, 'IN', 'INDIA', 'India', 'IND', 356, 91, 'Индия'),

(100, 'ID', 'INDONESIA', 'Indonesia', 'IDN', 360, 62, 'Индонезия'),

(101, 'IR', 'IRAN, ISLAMIC REPUBLIC OF', 'Iran, Islamic Republic of', 'IRN', 364, 98, 'Иран'),

(102, 'IQ', 'IRAQ', 'Iraq', 'IRQ', 368, 964, 'Ирак'),

(103, 'IE', 'IRELAND', 'Ireland', 'IRL', 372, 353, 'Ирландия'),

(104, 'IL', 'ISRAEL', 'Israel', 'ISR', 376, 972, 'Израиль'),

(105, 'IT', 'ITALY', 'Italy', 'ITA', 380, 39, 'Италия'),

(106, 'JM', 'JAMAICA', 'Jamaica', 'JAM', 388, 1876, 'Ямайка'),

(107, 'JP', 'JAPAN', 'Japan', 'JPN', 392, 81, 'Япония'),

(108, 'JO', 'JORDAN', 'Jordan', 'JOR', 400, 962, 'Иордания'),

(109, 'KZ', 'KAZAKHSTAN', 'Kazakhstan', 'KAZ', 398, 7, 'Казахстан'),

(110, 'KE', 'KENYA', 'Kenya', 'KEN', 404, 254, 'Кения'),

(111, 'KI', 'KIRIBATI', 'Kiribati', 'KIR', 296, 686, ''),

(112, 'KP', 'KOREA, DEMOCRATIC PEOPLE''S REPUBLIC OF', 'Korea, Democratic People''s Republic of', 'PRK', 408, 850, 'Корейская Народно-Демократическая Республика'),

(113, 'KR', 'KOREA, REPUBLIC OF', 'Korea, Republic of', 'KOR', 410, 82, 'Корея, Республика'),

(114, 'KW', 'KUWAIT', 'Kuwait', 'KWT', 414, 965, 'Кувейт'),

(115, 'KG', 'KYRGYZSTAN', 'Kyrgyzstan', 'KGZ', 417, 996, 'Киргизия'),

(116, 'LA', 'LAO PEOPLE''S DEMOCRATIC REPUBLIC', 'Lao People''s Democratic Republic', 'LAO', 418, 856, ''),

(117, 'LV', 'LATVIA', 'Latvia', 'LVA', 428, 371, 'Латвия'),

(118, 'LB', 'LEBANON', 'Lebanon', 'LBN', 422, 961, 'Ливан'),

(119, 'LS', 'LESOTHO', 'Lesotho', 'LSO', 426, 266, ''),

(120, 'LR', 'LIBERIA', 'Liberia', 'LBR', 430, 231, 'Либерия'),

(121, 'LY', 'LIBYAN ARAB JAMAHIRIYA', 'Libyan Arab Jamahiriya', 'LBY', 434, 218, ''),

(122, 'LI', 'LIECHTENSTEIN', 'Liechtenstein', 'LIE', 438, 423, 'Лихтенштейн'),

(123, 'LT', 'LITHUANIA', 'Lithuania', 'LTU', 440, 370, 'Литва'),

(124, 'LU', 'LUXEMBOURG', 'Luxembourg', 'LUX', 442, 352, 'Люксембург'),

(125, 'MO', 'MACAO', 'Macao', 'MAC', 446, 853, 'Макао'),

(126, 'MK', 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'Macedonia, the Former Yugoslav Republic of', 'MKD', 807, 389, 'Македония'),

(127, 'MG', 'MADAGASCAR', 'Madagascar', 'MDG', 450, 261, 'Мадагаскар'),

(128, 'MW', 'MALAWI', 'Malawi', 'MWI', 454, 265, ''),

(129, 'MY', 'MALAYSIA', 'Malaysia', 'MYS', 458, 60, 'Малайзия'),

(130, 'MV', 'MALDIVES', 'Maldives', 'MDV', 462, 960, 'Мальдивы'),

(131, 'ML', 'MALI', 'Mali', 'MLI', 466, 223, 'Мали'),

(132, 'MT', 'MALTA', 'Malta', 'MLT', 470, 356, 'Мальта'),

(133, 'MH', 'MARSHALL ISLANDS', 'Marshall Islands', 'MHL', 584, 692, 'Маршалловы острова'),

(134, 'MQ', 'MARTINIQUE', 'Martinique', 'MTQ', 474, 596, ''),

(135, 'MR', 'MAURITANIA', 'Mauritania', 'MRT', 478, 222, 'Мавритания'),

(136, 'MU', 'MAURITIUS', 'Mauritius', 'MUS', 480, 230, ''),

(137, 'YT', 'MAYOTTE', 'Mayotte', NULL, NULL, 269, ''),

(138, 'MX', 'MEXICO', 'Mexico', 'MEX', 484, 52, 'Мексика'),

(139, 'FM', 'MICRONESIA, FEDERATED STATES OF', 'Micronesia, Federated States of', 'FSM', 583, 691, 'Микронезия, Федеративные Штаты'),

(140, 'MD', 'MOLDOVA, REPUBLIC OF', 'Moldova, Republic of', 'MDA', 498, 373, 'Молдова'),

(141, 'MC', 'MONACO', 'Monaco', 'MCO', 492, 377, 'Монако'),

(142, 'MN', 'MONGOLIA', 'Mongolia', 'MNG', 496, 976, 'Монголия'),

(143, 'MS', 'MONTSERRAT', 'Montserrat', 'MSR', 500, 1664, 'Монтсеррат'),

(144, 'MA', 'MOROCCO', 'Morocco', 'MAR', 504, 212, 'Марокко'),

(145, 'MZ', 'MOZAMBIQUE', 'Mozambique', 'MOZ', 508, 258, 'Мозамбик'),

(146, 'MM', 'MYANMAR', 'Myanmar', 'MMR', 104, 95, 'Мьянма'),

(147, 'NA', 'NAMIBIA', 'Namibia', 'NAM', 516, 264, 'Намибия'),

(148, 'NR', 'NAURU', 'Nauru', 'NRU', 520, 674, ''),

(149, 'NP', 'NEPAL', 'Nepal', 'NPL', 524, 977, 'Непал'),

(150, 'NL', 'NETHERLANDS', 'Netherlands', 'NLD', 528, 31, 'Нидерланды'),

(151, 'AN', 'NETHERLANDS ANTILLES', 'Netherlands Antilles', 'ANT', 530, 599, 'Нидерладнские Антиллы'),

(152, 'NC', 'NEW CALEDONIA', 'New Caledonia', 'NCL', 540, 687, 'Новая Каледония'),

(153, 'NZ', 'NEW ZEALAND', 'New Zealand', 'NZL', 554, 64, 'Новая Зеландия'),

(154, 'NI', 'NICARAGUA', 'Nicaragua', 'NIC', 558, 505, 'Никарагуа'),

(155, 'NE', 'NIGER', 'Niger', 'NER', 562, 227, 'Нигер'),

(156, 'NG', 'NIGERIA', 'Nigeria', 'NGA', 566, 234, 'Нигерия'),

(157, 'NU', 'NIUE', 'Niue', 'NIU', 570, 683, ''),

(158, 'NF', 'NORFOLK ISLAND', 'Norfolk Island', 'NFK', 574, 672, 'Остров Норфолк'),

(159, 'MP', 'NORTHERN MARIANA ISLANDS', 'Northern Mariana Islands', 'MNP', 580, 1670, ''),

(160, 'NO', 'NORWAY', 'Norway', 'NOR', 578, 47, 'Норвегия'),

(161, 'OM', 'OMAN', 'Oman', 'OMN', 512, 968, 'Оман'),

(162, 'PK', 'PAKISTAN', 'Pakistan', 'PAK', 586, 92, 'Пакистан'),

(163, 'PW', 'PALAU', 'Palau', 'PLW', 585, 680, 'Палау'),

(164, 'PS', 'PALESTINIAN TERRITORY, OCCUPIED', 'Palestinian Territory, Occupied', NULL, NULL, 970, 'Палестина'),

(165, 'PA', 'PANAMA', 'Panama', 'PAN', 591, 507, 'Панама'),

(166, 'PG', 'PAPUA NEW GUINEA', 'Papua New Guinea', 'PNG', 598, 675, 'Папуга Новая Гвинея'),

(167, 'PY', 'PARAGUAY', 'Paraguay', 'PRY', 600, 595, 'Парагвай'),

(168, 'PE', 'PERU', 'Peru', 'PER', 604, 51, 'Перу'),

(169, 'PH', 'PHILIPPINES', 'Philippines', 'PHL', 608, 63, 'Филиппины'),

(170, 'PN', 'PITCAIRN', 'Pitcairn', 'PCN', 612, 0, ''),

(171, 'PL', 'POLAND', 'Poland', 'POL', 616, 48, 'Польша'),

(172, 'PT', 'PORTUGAL', 'Portugal', 'PRT', 620, 351, 'Португалия'),

(173, 'PR', 'PUERTO RICO', 'Puerto Rico', 'PRI', 630, 1787, 'Пуерто Рико'),

(174, 'QA', 'QATAR', 'Qatar', 'QAT', 634, 974, 'Катар'),

(175, 'RE', 'REUNION', 'Reunion', 'REU', 638, 262, ''),

(176, 'RO', 'ROMANIA', 'Romania', 'ROM', 642, 40, 'Румыния'),

(177, 'RU', 'RUSSIAN FEDERATION', 'Russian Federation', 'RUS', 643, 70, 'Российская Федерация'),

(178, 'RW', 'RWANDA', 'Rwanda', 'RWA', 646, 250, 'Руанда'),

(179, 'SH', 'SAINT HELENA', 'Saint Helena', 'SHN', 654, 290, 'Острова Святой Елены, Вознесения и Тристан-да-Кунья'),

(180, 'KN', 'SAINT KITTS AND NEVIS', 'Saint Kitts and Nevis', 'KNA', 659, 1869, ''),

(181, 'LC', 'SAINT LUCIA', 'Saint Lucia', 'LCA', 662, 1758, 'Санта Лючия'),

(182, 'PM', 'SAINT PIERRE AND MIQUELON', 'Saint Pierre and Miquelon', 'SPM', 666, 508, ''),

(183, 'VC', 'SAINT VINCENT AND THE GRENADINES', 'Saint Vincent and the Grenadines', 'VCT', 670, 1784, ''),

(184, 'WS', 'SAMOA', 'Samoa', 'WSM', 882, 684, 'Самоа'),

(185, 'SM', 'SAN MARINO', 'San Marino', 'SMR', 674, 378, 'Сан Марино'),

(186, 'ST', 'SAO TOME AND PRINCIPE', 'Sao Tome and Principe', 'STP', 678, 239, ''),

(187, 'SA', 'SAUDI ARABIA', 'Saudi Arabia', 'SAU', 682, 966, 'Саудовская Аравия'),

(188, 'SN', 'SENEGAL', 'Senegal', 'SEN', 686, 221, 'Сенегал'),

(189, 'CS', 'SERBIA AND MONTENEGRO', 'Serbia and Montenegro', NULL, NULL, 381, 'Сербия и Монтенегро'),

(190, 'SC', 'SEYCHELLES', 'Seychelles', 'SYC', 690, 248, 'Сейшельские Острова'),

(191, 'SL', 'SIERRA LEONE', 'Sierra Leone', 'SLE', 694, 232, 'Сьерра-Леоне'),

(192, 'SG', 'SINGAPORE', 'Singapore', 'SGP', 702, 65, 'Сингапур'),

(193, 'SK', 'SLOVAKIA', 'Slovakia', 'SVK', 703, 421, 'Словакия'),

(194, 'SI', 'SLOVENIA', 'Slovenia', 'SVN', 705, 386, 'Словения'),

(195, 'SB', 'SOLOMON ISLANDS', 'Solomon Islands', 'SLB', 90, 677, 'Соломоновы Острова'),

(196, 'SO', 'SOMALIA', 'Somalia', 'SOM', 706, 252, 'Сомали'),

(197, 'ZA', 'SOUTH AFRICA', 'South Africa', 'ZAF', 710, 27, 'Южноафриканская Республика'),

(198, 'GS', 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'South Georgia and the South Sandwich Islands', NULL, NULL, 0, ''),

(199, 'ES', 'SPAIN', 'Spain', 'ESP', 724, 34, 'Испания'),

(200, 'LK', 'SRI LANKA', 'Sri Lanka', 'LKA', 144, 94, 'Шри-Ланка'),

(201, 'SD', 'SUDAN', 'Sudan', 'SDN', 736, 249, 'Судан'),

(202, 'SR', 'SURINAME', 'Suriname', 'SUR', 740, 597, 'Суринам'),

(203, 'SJ', 'SVALBARD AND JAN MAYEN', 'Svalbard and Jan Mayen', 'SJM', 744, 47, ''),

(204, 'SZ', 'SWAZILAND', 'Swaziland', 'SWZ', 748, 268, ''),

(205, 'SE', 'SWEDEN', 'Sweden', 'SWE', 752, 46, 'Швеция'),

(206, 'CH', 'SWITZERLAND', 'Switzerland', 'CHE', 756, 41, 'Швейцария'),

(207, 'SY', 'SYRIAN ARAB REPUBLIC', 'Syrian Arab Republic', 'SYR', 760, 963, 'Сирийская Арабская Республика'),

(208, 'TW', 'TAIWAN, PROVINCE OF CHINA', 'Taiwan, Province of China', 'TWN', 158, 886, 'Тайвань'),

(209, 'TJ', 'TAJIKISTAN', 'Tajikistan', 'TJK', 762, 992, 'Таджикистан'),

(210, 'TZ', 'TANZANIA, UNITED REPUBLIC OF', 'Tanzania, United Republic of', 'TZA', 834, 255, 'Танзания'),

(211, 'TH', 'THAILAND', 'Thailand', 'THA', 764, 66, 'Тайланд'),

(212, 'TL', 'TIMOR-LESTE', 'Timor-Leste', NULL, NULL, 670, ''),

(213, 'TG', 'TOGO', 'Togo', 'TGO', 768, 228, 'Того'),

(214, 'TK', 'TOKELAU', 'Tokelau', 'TKL', 772, 690, 'Токелау'),

(215, 'TO', 'TONGA', 'Tonga', 'TON', 776, 676, 'Тонга'),

(216, 'TT', 'TRINIDAD AND TOBAGO', 'Trinidad and Tobago', 'TTO', 780, 1868, 'Тринидад и Тобаго'),

(217, 'TN', 'TUNISIA', 'Tunisia', 'TUN', 788, 216, 'Тунис'),

(218, 'TR', 'TURKEY', 'Turkey', 'TUR', 792, 90, 'Турция'),

(219, 'TM', 'TURKMENISTAN', 'Turkmenistan', 'TKM', 795, 7370, 'Туркменистан'),

(220, 'TC', 'TURKS AND CAICOS ISLANDS', 'Turks and Caicos Islands', 'TCA', 796, 1649, ''),

(221, 'TV', 'TUVALU', 'Tuvalu', 'TUV', 798, 688, 'Тувалу'),

(222, 'UG', 'UGANDA', 'Uganda', 'UGA', 800, 256, 'Уганда'),

(223, 'UA', 'UKRAINE', 'Ukraine', 'UKR', 804, 380, 'Украина'),

(224, 'AE', 'UNITED ARAB EMIRATES', 'United Arab Emirates', 'ARE', 784, 971, 'Объединённые Арабские Эмираты'),

(225, 'GB', 'UNITED KINGDOM', 'United Kingdom', 'GBR', 826, 44, 'Великобритания'),

(226, 'US', 'UNITED STATES', 'United States', 'USA', 840, 1, 'Соединённые Штаты Америки'),

(227, 'UM', 'UNITED STATES MINOR OUTLYING ISLANDS', 'United States Minor Outlying Islands', NULL, NULL, 1, ''),

(228, 'UY', 'URUGUAY', 'Uruguay', 'URY', 858, 598, 'Уругвай'),

(229, 'UZ', 'UZBEKISTAN', 'Uzbekistan', 'UZB', 860, 998, 'Узбекистан'),

(230, 'VU', 'VANUATU', 'Vanuatu', 'VUT', 548, 678, 'Ванату'),

(231, 'VE', 'VENEZUELA', 'Venezuela', 'VEN', 862, 58, 'Венесуела'),

(232, 'VN', 'VIET NAM', 'Viet Nam', 'VNM', 704, 84, 'Вьетнам'),

(233, 'VG', 'VIRGIN ISLANDS, BRITISH', 'Virgin Islands, British', 'VGB', 92, 1284, ''),

(234, 'VI', 'VIRGIN ISLANDS, U.S.', 'Virgin Islands, U.s.', 'VIR', 850, 1340, ''),

(235, 'WF', 'WALLIS AND FUTUNA', 'Wallis and Futuna', 'WLF', 876, 681, ''),

(236, 'EH', 'WESTERN SAHARA', 'Western Sahara', 'ESH', 732, 212, 'Западная Сахара'),

(237, 'YE', 'YEMEN', 'Yemen', 'YEM', 887, 967, 'Йемен'),

(238, 'ZM', 'ZAMBIA', 'Zambia', 'ZMB', 894, 260, 'Замбия'),

(239, 'ZW', 'ZIMBABWE', 'Zimbabwe', 'ZWE', 716, 263, 'Зимбабве');


-- --------------------------------------------------------
-- Хост:                         185.93.109.171
-- Версия сервера:               10.2.14-MariaDB-10.2.14+maria~jessie - mariadb.org binary distribution
-- ОС Сервера:                   debian-linux-gnu
-- HeidiSQL Версия:              9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры для таблица cargo_cabinets.company_errors
CREATE TABLE IF NOT EXISTS `company_errors` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `message` varchar(400) COLLATE utf8_unicode_ci NOT NULL,
  `msg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_read` bit(1) NOT NULL DEFAULT b'0',
  `request_json` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `request_url` text COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `cargo_cabinets`.`applications` (
  `order_id` INT NOT NULL,
  `transport_internal_number` VARCHAR(45) NOT NULL,
  `application_number` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`order_id`));

ALTER TABLE `companies` ADD COLUMN `application_prefix` VARCHAR(3)

ALTER TABLE `user_discounts`
	ADD COLUMN `user_id` INT NULL AFTER `cat_id`;

ALTER TABLE `accounts` ADD COLUMN `jurFormId` INT AFTER `jurForm`;

SET SQL_SAFE_UPDATES = 0;
UPDATE `accounts` SET `jurFormId` = 0
UPDATE `accounts` SET `jurFormId` = 5 WHERE `jurForm` = 'ООО' OR `jurForm` = 'Общество с ограниченной ответственностью';
SET SQL_SAFE_UPDATES = 1;

ALTER TABLE `companies` ADD COLUMN `site` VARCHAR(100)

ALTER TABLE `user_discounts` ADD COLUMN `is_forever` INT DEFAULT 0;
UPDATE `user_discounts` SET `is_forever`  = 1 WHERE `user_id` IS NOT NULL;

CREATE TABLE `contact` (
	`id` BIGINT(20) NOT NULL AUTO_INCREMENT,
	`account_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`phone_number` VARCHAR(255) NOT NULL COLLATE 'utf8_unicode_ci',
	`email` VARCHAR(255) NOT NULL COLLATE 'utf8_unicode_ci',
	`is_legal_entity` TINYINT(1) NOT NULL DEFAULT '0',
	`person_first_name` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`person_second_name` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`person_last_name` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`person_document_type_id` INT(11) NULL DEFAULT NULL,
	`person_document_number` VARCHAR(30) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`company_name` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`company_form_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`company_inn` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`company_phone` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`company_email` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`company_address` VARCHAR(150) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`company_address_cell` VARCHAR(10) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`contact_person_first_name` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`contact_person_second_name` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=InnoDB
AUTO_INCREMENT=117
;

CREATE TABLE `contact_statistic` (
	`contact_id` INT(11) NOT NULL,
	`sender_count` INT(11) NULL DEFAULT '0',
	`recipient_count` INT(11) NULL DEFAULT '0',
	PRIMARY KEY (`contact_id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=InnoDB
;

ALTER TABLE `accounts` MODIFY `passportnum` VARCHAR(50);

UPDATE `jur_form` SET `short_name` = 'ТОС' WHERE `id` = 9;
UPDATE `jur_form` SET `short_name` = 'ФГУП' WHERE `id` = 33;
UPDATE `jur_form` SET `short_name` = 'ФКП' WHERE `id` = 29;

ALTER TABLE `companies` ADD COLUMN `use_prefix` INT AFTER `application_prefix`;
UPDATE `companies` SET `use_prefix` = 1 WHERE `id` = 32;

CREATE TABLE `cargo_cabinets`.`order_discount` (
  `order_id` INT NOT NULL,
  `discount_id` INT NULL,
  `value` FLOAT(10,2) NULL,
  PRIMARY KEY (`order_id`),
  UNIQUE INDEX `id_UNIQUE` (`order_id` ASC));

INSERT INTO `order_discount` (`order_id`, `discount_id`, `value`) VALUES (1409, 1, 60.61);

ALTER TABLE `accounts` ADD COLUMN `jurFormId` INT AFTER `isJur`;

ALTER TABLE `accounts` ADD COLUMN `docTypeId` INT AFTER `jurFormId`;

ALTER TABLE `accounts` CHANGE `passportnum` `passportnum` VARCHAR(12) NOT NULL;

ALTER TABLE `accounts` CHANGE `phone` `phone` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;


CREATE TABLE IF NOT EXISTS `dpd_kladr` (
  `id` int(32) NOT NULL,
  `cityId` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `countryCode` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `countryName` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `regionCode` int(2) NOT NULL,
  `regionName` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `cityCode` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `cityName` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `abbreviation` varchar(1) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `dpd_kladr`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `dpd_kladr`
  MODIFY `id` int(32) NOT NULL AUTO_INCREMENT;

ALTER TABLE `order_payment` CHANGE `isPayed` `isPayed` INT(1) NOT NULL;

CREATE TABLE `orders` (
	`id` BIGINT(20) NOT NULL AUTO_INCREMENT,
	`recipient_id` BIGINT(20) NOT NULL DEFAULT '1',
	`sender_id` BIGINT(20) NOT NULL DEFAULT '1',
	`recipient_address` VARCHAR(250) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`sender_address` VARCHAR(250) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`is_derival_with_courier` BIT(1) NOT NULL DEFAULT b'0',
	`is_arrival_with_courier` BIT(1) NOT NULL DEFAULT b'0',
	`client_id` BIGINT(20) NOT NULL,
	`companyID` INT(11) NOT NULL,
	`timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`cargo_name` VARCHAR(255) NOT NULL COLLATE 'utf8_unicode_ci',
	`city_from_id` INT(11) NOT NULL DEFAULT '1',
	`city_to_id` INT(11) NOT NULL DEFAULT '1',
	`cargo_from` VARCHAR(255) NOT NULL COLLATE 'utf8_unicode_ci',
	`cargo_to` VARCHAR(255) NOT NULL COLLATE 'utf8_unicode_ci',
	`cargo_weight` FLOAT NOT NULL,
	`cargo_vol` FLOAT NOT NULL,
	`cargo_length` FLOAT NOT NULL,
	`cargo_width` FLOAT NOT NULL,
	`cargo_height` FLOAT NOT NULL,
	`cargo_value` FLOAT NOT NULL,
	`cargo_price` DECIMAL(10,2) NOT NULL,
	`cargo_method` VARCHAR(255) NOT NULL COLLATE 'utf8_unicode_ci',
	`cargo_site` VARCHAR(255) NOT NULL COLLATE 'utf8_unicode_ci',
	`cargo_desired_date` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`cargo_delivery_date` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`comment` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`cargoDangerClass` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`cargo_danger_class_id` INT(11) NOT NULL DEFAULT '1',
	`cargo_temperature_id` INT(11) NOT NULL DEFAULT '1',
	`cargoTemperature` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`cargo_good_name` VARCHAR(255) NOT NULL COLLATE 'utf8_unicode_ci',
	`addOptions` TEXT NULL COLLATE 'utf8_unicode_ci',
	`company_internal_number` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`serialized_fields` TEXT NOT NULL COLLATE 'utf8_unicode_ci',
	`payment_type_id` INT(11) NOT NULL DEFAULT '1',
	`payer_type_id` INT(11) NOT NULL DEFAULT '1',
	`cargo_vol_unit_name` VARCHAR(10) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`cargo_weight_unit_name` VARCHAR(10) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`order_status_id` INT(11) NOT NULL DEFAULT '0',
	`orginal_price` DECIMAL(10,2) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	INDEX `sender` (`client_id`, `cargo_weight`, `cargo_from`, `cargo_to`, `companyID`) USING BTREE,
	INDEX `FK_orders_recipients` (`recipient_id`),
	INDEX `FK_orders_city` (`city_to_id`),
	INDEX `FK_orders_city_2` (`city_from_id`),
	CONSTRAINT `FK_orders_city` FOREIGN KEY (`city_to_id`) REFERENCES `city` (`id`),
	CONSTRAINT `FK_orders_city_2` FOREIGN KEY (`city_from_id`) REFERENCES `city` (`id`),
	CONSTRAINT `FK_orders_recipients` FOREIGN KEY (`recipient_id`) REFERENCES `recipients` (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1859
;

ALTER TABLE `orders`
	ADD COLUMN `orginal_price` DECIMAL(10,2) NOT NULL DEFAULT '0' AFTER `order_status_id`;

ALTER TABLE `orders` CHANGE `orginal_price` `original_price` DECIMAL(10,2) NOT NULL DEFAULT '0.00';

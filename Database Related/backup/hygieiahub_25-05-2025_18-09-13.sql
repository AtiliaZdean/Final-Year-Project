-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: hygieiahub
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `additional_service`
--

DROP TABLE IF EXISTS `additional_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `additional_service` (
  `service_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(300) DEFAULT NULL,
  `price_RM` decimal(5,2) NOT NULL,
  `duration_hour` decimal(3,2) NOT NULL,
  PRIMARY KEY (`service_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `additional_service`
--

LOCK TABLES `additional_service` WRITE;
/*!40000 ALTER TABLE `additional_service` DISABLE KEYS */;
INSERT INTO `additional_service` VALUES (2,'sweep','',70.00,1.00),(3,'Mop','',80.00,1.00),(4,'Window Cleaning','Includes interior & exterior (up to 2nd floor)',100.00,1.50),(6,'wiping','',70.00,1.00),(7,'Sofa Cleaning','Fabric or leather sofa (up to 3 seats)',130.00,2.00);
/*!40000 ALTER TABLE `additional_service` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER before_insert_service
BEFORE INSERT ON additional_service
FOR EACH ROW
BEGIN
    -- Check if the service name already exists
    IF EXISTS (SELECT 1 FROM additional_service WHERE LOWER(name) = LOWER(NEW.name)) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Service name already exists.';
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER after_insert_service
AFTER INSERT ON additional_service
FOR EACH ROW
BEGIN
    INSERT INTO additional_service_log(
        service_id, action, name, new_description, new_price_RM, new_duration_hour, made_by
    ) VALUES (
        NEW.service_id, 'INSERT', NEW.name, NEW.description, NEW.price_RM, NEW.duration_hour, @made_by
    );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER after_update_service
AFTER UPDATE ON additional_service
FOR EACH ROW
BEGIN
    INSERT INTO additional_service_log(
        service_id, action,
        name,
        old_description, new_description,
        old_price_RM, new_price_RM,
        old_duration_hour, new_duration_hour,
        made_by
    ) VALUES (
        OLD.service_id, 'UPDATE',
        OLD.name,
        OLD.description, NEW.description,
        OLD.price_RM, NEW.price_RM,
        OLD.duration_hour, NEW.duration_hour,
        @made_by
    );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER after_delete_service
AFTER DELETE ON additional_service
FOR EACH ROW
BEGIN
    INSERT INTO additional_service_log(
        service_id, action, name, old_description, old_price_RM, old_duration_hour, made_by
    ) VALUES (
        OLD.service_id, 'DELETE', OLD.name, OLD.description, OLD.price_RM, OLD.duration_hour, @made_by
    );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `additional_service_log`
--

DROP TABLE IF EXISTS `additional_service_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `additional_service_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) DEFAULT NULL,
  `action` varchar(6) NOT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `made_by` varchar(100) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `old_description` varchar(300) DEFAULT NULL,
  `new_description` varchar(300) DEFAULT NULL,
  `old_price_RM` decimal(5,2) DEFAULT NULL,
  `new_price_RM` decimal(5,2) DEFAULT NULL,
  `old_duration_hour` decimal(4,2) DEFAULT NULL,
  `new_duration_hour` decimal(4,2) DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `additional_service_log`
--

LOCK TABLES `additional_service_log` WRITE;
/*!40000 ALTER TABLE `additional_service_log` DISABLE KEYS */;
INSERT INTO `additional_service_log` VALUES (1,1,'INSERT','2025-05-07 22:55:52','Admin Atilia','gffggf',NULL,'',NULL,100.00,NULL,1.00),(2,1,'UPDATE','2025-05-09 03:11:06','Admin Atilia','1','','',100.00,0.00,1.00,1.00),(3,1,'DELETE','2025-05-09 03:50:35','Admin Atilia','1','',NULL,0.00,NULL,1.00,NULL),(4,2,'INSERT','2025-05-09 03:51:02','Admin Atilia','sweep',NULL,'',NULL,0.00,NULL,1.00),(5,3,'INSERT','2025-05-14 15:53:56','Admin Atilia','Mop',NULL,'',NULL,0.00,NULL,1.00),(6,4,'INSERT','2025-05-14 15:54:46','Admin Atilia','Window Cleaning',NULL,'Includes interior & exterior (up to 2nd floor)',NULL,0.00,NULL,1.50),(7,5,'INSERT','2025-05-14 16:00:13','Admin Atilia','Dusting',NULL,'',NULL,0.00,NULL,1.00),(8,6,'INSERT','2025-05-15 08:06:53','Admin Atilia','wiping',NULL,'',NULL,0.00,NULL,1.00),(9,3,'UPDATE','2025-05-15 08:08:12','Admin Atilia','Mop','','',0.00,0.00,1.00,1.00),(10,5,'DELETE','2025-05-15 08:10:54','Admin Atilia','Dusting','',NULL,0.00,NULL,1.00,NULL),(11,3,'UPDATE','2025-05-16 15:34:51','Admin Atilia','Mop','','',0.00,0.00,1.00,1.00),(12,4,'UPDATE','2025-05-16 15:45:53','Admin Atilia','Window Cleaning','Includes interior & exterior (up to 2nd floor)','Includes interior & exterior (up to 2nd floor)',0.00,0.00,1.50,1.50),(13,3,'UPDATE','2025-05-16 16:01:27','Admin Atilia','Mop','','',0.00,0.00,1.00,1.00),(14,2,'UPDATE','2025-05-25 15:40:22','Admin Selina','sweep','','',0.00,70.00,1.00,1.00),(15,3,'UPDATE','2025-05-25 15:41:01','Admin Selina','Mop','','',0.00,80.00,1.00,1.00),(16,4,'UPDATE','2025-05-25 15:41:19','Admin Selina','Window Cleaning','Includes interior & exterior (up to 2nd floor)','Includes interior & exterior (up to 2nd floor)',0.00,100.00,1.50,1.50),(17,6,'UPDATE','2025-05-25 15:41:35','Admin Selina','wiping','','',0.00,70.00,1.00,1.00),(18,7,'INSERT','2025-05-25 16:01:26','Admin Selina','Sofa Cleaning',NULL,'Fabric or leather sofa (up to 3 seats)',NULL,20.00,NULL,1.00),(19,7,'UPDATE','2025-05-25 16:04:30','Admin Selina','Sofa Cleaning','Fabric or leather sofa (up to 3 seats)','Fabric or leather sofa (up to 3 seats)',20.00,130.00,1.00,2.00);
/*!40000 ALTER TABLE `additional_service_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking`
--

DROP TABLE IF EXISTS `booking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `total_area_sqft` decimal(20,4) NOT NULL,
  `no_of_bedrooms` int(2) NOT NULL,
  `no_of_bathrooms` int(2) NOT NULL,
  `no_of_livingroooms` int(2) NOT NULL,
  `size_of_kitchen_sqft` decimal(20,4) NOT NULL,
  `pet` varchar(3) NOT NULL,
  `tools` varchar(3) NOT NULL,
  `no_of_cleaners` int(2) NOT NULL,
  `custom_request` varchar(300) DEFAULT NULL,
  `total_RM` decimal(6,2) NOT NULL,
  `scheduled_date` date NOT NULL,
  `scheduled_time` time NOT NULL,
  `status` varchar(9) NOT NULL,
  PRIMARY KEY (`booking_id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking`
--

LOCK TABLES `booking` WRITE;
/*!40000 ALTER TABLE `booking` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_cleaner`
--

DROP TABLE IF EXISTS `booking_cleaner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_cleaner` (
  `booking_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  PRIMARY KEY (`booking_id`,`staff_id`),
  KEY `staff_id` (`staff_id`),
  CONSTRAINT `booking_cleaner_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`),
  CONSTRAINT `booking_cleaner_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_cleaner`
--

LOCK TABLES `booking_cleaner` WRITE;
/*!40000 ALTER TABLE `booking_cleaner` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking_cleaner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_service`
--

DROP TABLE IF EXISTS `booking_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_service` (
  `booking_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `quantity` int(2) DEFAULT NULL,
  PRIMARY KEY (`booking_id`,`service_id`),
  KEY `service_id` (`service_id`),
  CONSTRAINT `booking_service_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`),
  CONSTRAINT `booking_service_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `additional_service` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_service`
--

LOCK TABLES `booking_service` WRITE;
/*!40000 ALTER TABLE `booking_service` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking_service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(11) NOT NULL,
  `address` varchar(300) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` VALUES (1,'Peter Parker','peter@gmail.com','$2y$10$512yAvTwuWtodfujAfHNeuOA2SDiPVy5X8xGilP9Qt/RxANqfn2Ba','0173647364','99, Taman Desa Duranta','seremban','negeri sembilan','2025-05-07 02:03:15');
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `time_rating` decimal(3,2) NOT NULL,
  `communication_rating` decimal(3,2) NOT NULL,
  `satisfaction_rating` decimal(3,2) NOT NULL,
  `comment` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`feedback_id`),
  KEY `booking_id` (`booking_id`),
  CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback`
--

LOCK TABLES `feedback` WRITE;
/*!40000 ALTER TABLE `feedback` DISABLE KEYS */;
/*!40000 ALTER TABLE `feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `amount_RM` decimal(6,2) NOT NULL,
  `status` varchar(9) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `payment_date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`payment_id`),
  KEY `booking_id` (`booking_id`),
  CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment`
--

LOCK TABLES `payment` WRITE;
/*!40000 ALTER TABLE `payment` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone_number` varchar(11) NOT NULL,
  `branch` varchar(100) NOT NULL,
  `role` varchar(7) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `status` varchar(9) NOT NULL DEFAULT 'active',
  `cleaner_picture` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`staff_id`),
  UNIQUE KEY `UNIQUE_EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff`
--

LOCK TABLES `staff` WRITE;
/*!40000 ALTER TABLE `staff` DISABLE KEYS */;
INSERT INTO `staff` VALUES (1,'Admin Atilia','admin@hygieiahub.com','$2y$10$azX9xcd.5QZuuMlBvEDqrumCCXFcHd03HkKTTawHV7ENhdMI7UKyW','0123456789','Seremban','admin','2025-05-07 02:14:07','active',NULL),(2,'Cleaner Aishah',NULL,NULL,'0112233445','Seremban','cleaner','2025-05-12 02:05:53','active',NULL),(3,'Cleaner Faiz',NULL,NULL,'0193344556','Ayer Keroh','cleaner','2025-05-12 02:05:53','active',NULL),(4,'Cleaner Baek','','$2y$10$y7WGm7KTKvMJz2PQ9uwLC.uE3kAkt3dtavnxTaDesrFpLynkqk/Z.','0164732647','Ayer Keroh','cleaner','2025-05-17 22:15:16','active',NULL),(5,'Admin Selina','adminselina@hygieiahub.com','$2y$10$BQAgPudOuq6yWUZskuT7cuTQzvFU2TRCsZ7uL0EKPRXNMeUHA3Trm','0133746374','Ayer Keroh','admin','2025-05-17 22:32:34','active',NULL);
/*!40000 ALTER TABLE `staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff_log`
--

DROP TABLE IF EXISTS `staff_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) DEFAULT NULL,
  `action` varchar(12) DEFAULT NULL,
  `made_at` datetime DEFAULT current_timestamp(),
  `made_by` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `old_email` varchar(100) DEFAULT NULL,
  `old_phone_number` varchar(11) DEFAULT NULL,
  `old_branch` varchar(100) DEFAULT NULL,
  `old_role` varchar(7) DEFAULT NULL,
  `old_status` varchar(9) DEFAULT NULL,
  `new_email` varchar(100) DEFAULT NULL,
  `new_phone_number` varchar(11) DEFAULT NULL,
  `new_branch` varchar(100) DEFAULT NULL,
  `new_role` varchar(7) DEFAULT NULL,
  `new_status` varchar(9) DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff_log`
--

LOCK TABLES `staff_log` WRITE;
/*!40000 ALTER TABLE `staff_log` DISABLE KEYS */;
INSERT INTO `staff_log` VALUES (1,NULL,'failed','2025-05-17 16:21:22','System',NULL,'peter@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(2,1,'login','2025-05-17 16:46:22','Admin Atilia','Admin Atilia',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,NULL,'failed login','2025-05-17 17:26:00','Unknown',NULL,'non-exist',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4,NULL,'failed login','2025-05-17 17:29:23','Admin Atilia',NULL,'admin@hygieiahub.com',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL),(5,1,'failed login','2025-05-17 17:31:32','Admin Atilia',NULL,'admin@hygieiahub.com',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL),(6,1,'login','2025-05-17 17:31:49','Admin Atilia','Admin Atilia',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(7,2,'update','2025-05-17 17:37:35','Admin Atilia','Cleaner Aishah',NULL,'0112233445','Seremban','cleaner','active','','0112233445','Seremban','cleaner','in-active'),(8,2,'update','2025-05-17 17:43:28','Admin Atilia','Cleaner Aishah','','0112233445','Seremban','cleaner','in-active','','0112233445','Seremban','cleaner','active'),(9,2,'update','2025-05-17 17:49:52','Admin Atilia','Cleaner Aishah','','0112233445','Seremban','cleaner','active','','0112233445','Seremban','cleaner','in-active'),(10,2,'update','2025-05-17 17:51:28','Admin Atilia','Cleaner Aishah','','0112233445','Seremban','cleaner','in-active','','0112233445','Seremban','cleaner','active'),(11,4,'insert','2025-05-17 22:15:16','Admin Atilia','Cleaner Baek',NULL,NULL,NULL,NULL,NULL,'','0164732647','Ayer Keroh','cleaner','active'),(12,5,'insert','2025-05-17 22:32:34','Admin Atilia','Admin Selina',NULL,NULL,NULL,NULL,NULL,'adminselina@hygieiahub.com','0133746374','Ayer Keroh','admin','active'),(13,3,'update','2025-05-17 23:11:15','Admin Atilia','Cleaner Faiz',NULL,'0193344556','Ayer Keroh','cleaner','active',NULL,'0193344556','Ayer Keroh','cleaner','in-active'),(14,3,'update','2025-05-17 23:47:23','Admin Atilia','Cleaner Faiz',NULL,'0193344556','Ayer Keroh','cleaner','in-active',NULL,'0193344556','Ayer Keroh','cleaner','active'),(15,5,'login','2025-05-18 20:23:23','Admin Selina','Admin Selina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(16,5,'login','2025-05-19 00:41:20','Admin Selina','Admin Selina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(17,5,'login','2025-05-19 00:59:33','Admin Selina','Admin Selina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(18,5,'login','2025-05-19 19:50:14','Admin Selina','Admin Selina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(19,0,'failed login','2025-05-19 21:45:34','Unknown',NULL,'',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL),(20,5,'login','2025-05-19 21:45:46','Admin Selina','Admin Selina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(21,5,'login','2025-05-19 21:55:34','Admin Selina','Admin Selina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(22,5,'login','2025-05-19 21:59:12','Admin Selina','Admin Selina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(23,5,'login','2025-05-19 22:02:03','Admin Selina','Admin Selina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(24,5,'login','2025-05-20 21:44:36','Admin Selina','Admin Selina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(25,5,'login','2025-05-24 12:51:06','Admin Selina','Admin Selina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(26,5,'login','2025-05-25 23:25:37','Admin Selina','Admin Selina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `staff_log` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-26  0:09:15

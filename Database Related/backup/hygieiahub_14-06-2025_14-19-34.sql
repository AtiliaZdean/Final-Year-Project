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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `additional_service`
--

LOCK TABLES `additional_service` WRITE;
/*!40000 ALTER TABLE `additional_service` DISABLE KEYS */;
INSERT INTO `additional_service` VALUES (9,'Deep Cleaning','Intense cleaning including stains, grouts, under furniture',120.00,3.50),(10,'Move In/Out Cleaning','Full-service cleaning for empty homes before/after moving',150.00,4.00),(11,'Post-Renovation Cleaning','Heavy-duty cleaning to remove debris, dust, and paint stains',180.00,5.00),(12,'Kitchen Deep Clean','Degreasing kitchen walls, cabinets, stove, and appliances',80.00,2.00),(13,'Bathroom Intensive Clean','Scrubbing tiles, toilets, sinks, mirrors, and removing mold',40.00,1.50),(14,'Pet Area Cleaning','Cleaning and sanitizing areas pets stay in',30.00,0.50),(15,'Carpet Shampooing','Shampooing and vacuuming carpets and rugs',40.00,1.00),(16,'Window Cleaning','Cleaning of glass windows and sliding doors',25.00,0.50);
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
        NEW.service_id, 'Add', NEW.name, NEW.description, NEW.price_RM, NEW.duration_hour, @made_by
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
        OLD.service_id, 'Update',
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
        OLD.service_id, 'Delete', OLD.name, OLD.description, OLD.price_RM, OLD.duration_hour, @made_by
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
  `made_at` datetime NOT NULL DEFAULT current_timestamp(),
  `made_by` varchar(100) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `old_description` varchar(300) DEFAULT NULL,
  `new_description` varchar(300) DEFAULT NULL,
  `old_price_RM` decimal(5,2) DEFAULT NULL,
  `new_price_RM` decimal(5,2) DEFAULT NULL,
  `old_duration_hour` decimal(4,2) DEFAULT NULL,
  `new_duration_hour` decimal(4,2) DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `additional_service_log`
--

LOCK TABLES `additional_service_log` WRITE;
/*!40000 ALTER TABLE `additional_service_log` DISABLE KEYS */;
INSERT INTO `additional_service_log` VALUES (1,1,'Add','2025-05-08 06:55:52','Admin Atilia','gffggf',NULL,'',NULL,100.00,NULL,1.00),(2,1,'Update','2025-05-09 11:11:06','Admin Atilia','1','','',100.00,0.00,1.00,1.00),(3,1,'Delete','2025-05-09 11:50:35','Admin Atilia','1','',NULL,0.00,NULL,1.00,NULL),(4,2,'Add','2025-05-09 11:51:02','Admin Atilia','sweep',NULL,'',NULL,0.00,NULL,1.00),(5,3,'Add','2025-05-14 23:53:56','Admin Atilia','Mop',NULL,'',NULL,0.00,NULL,1.00),(6,4,'Add','2025-05-14 23:54:46','Admin Atilia','Window Cleaning',NULL,'Includes interior & exterior (up to 2nd floor)',NULL,0.00,NULL,1.50),(7,5,'Add','2025-05-15 00:00:13','Admin Atilia','Dusting',NULL,'',NULL,0.00,NULL,1.00),(8,6,'Add','2025-05-15 16:06:53','Admin Atilia','wiping',NULL,'',NULL,0.00,NULL,1.00),(9,3,'Update','2025-05-15 16:08:12','Admin Atilia','Mop','','',0.00,0.00,1.00,1.00),(10,5,'Delete','2025-05-15 16:10:54','Admin Atilia','Dusting','',NULL,0.00,NULL,1.00,NULL),(11,3,'Update','2025-05-16 23:34:51','Admin Atilia','Mop','','',0.00,0.00,1.00,1.00),(12,4,'Update','2025-05-16 23:45:53','Admin Atilia','Window Cleaning','Includes interior & exterior (up to 2nd floor)','Includes interior & exterior (up to 2nd floor)',0.00,0.00,1.50,1.50),(13,3,'Update','2025-05-17 00:01:27','Admin Atilia','Mop','','',0.00,0.00,1.00,1.00),(14,2,'Update','2025-05-25 23:40:22','Admin Selina','sweep','','',0.00,70.00,1.00,1.00),(15,3,'Update','2025-05-25 23:41:01','Admin Selina','Mop','','',0.00,80.00,1.00,1.00),(16,4,'Update','2025-05-25 23:41:19','Admin Selina','Window Cleaning','Includes interior & exterior (up to 2nd floor)','Includes interior & exterior (up to 2nd floor)',0.00,100.00,1.50,1.50),(17,6,'Update','2025-05-25 23:41:35','Admin Selina','wiping','','',0.00,70.00,1.00,1.00),(18,7,'Add','2025-05-26 00:01:26','Admin Selina','Sofa Cleaning',NULL,'Fabric or leather sofa (up to 3 seats)',NULL,20.00,NULL,1.00),(19,7,'Update','2025-05-26 00:04:30','Admin Selina','Sofa Cleaning','Fabric or leather sofa (up to 3 seats)','Fabric or leather sofa (up to 3 seats)',20.00,130.00,1.00,2.00),(20,2,'Delete','2025-05-28 15:52:26','Admin Atilia','sweep','',NULL,70.00,NULL,1.00,NULL),(21,3,'Delete','2025-05-28 15:53:36','Admin Atilia','Mop','',NULL,80.00,NULL,1.00,NULL),(22,4,'Delete','2025-05-28 15:53:54','Admin Atilia','Window Cleaning','Includes interior & exterior (up to 2nd floor)',NULL,100.00,NULL,1.50,NULL),(23,6,'Delete','2025-05-28 15:54:04','Admin Atilia','wiping','',NULL,70.00,NULL,1.00,NULL),(24,7,'Delete','2025-05-28 15:54:14','Admin Atilia','Sofa Cleaning','Fabric or leather sofa (up to 3 seats)',NULL,130.00,NULL,2.00,NULL),(25,8,'Add','2025-05-28 16:01:39','Admin Atilia','Basic Home Cleaning',NULL,'General cleaning for bedrooms, bathrooms, living rooms, and kitchen',NULL,60.00,NULL,2.00),(26,9,'Add','2025-05-28 16:02:34','Admin Atilia','Deep Cleaning',NULL,'Intense cleaning including stains, grouts, under furniture',NULL,120.00,NULL,3.50),(27,10,'Add','2025-05-28 16:07:15','Admin Atilia','Move In/Out Cleaning',NULL,'Full-service cleaning for empty homes before/after moving',NULL,150.00,NULL,4.00),(28,11,'Add','2025-05-28 16:07:54','Admin Atilia','Post-Renovation Cleaning',NULL,'Heavy-duty cleaning to remove debris, dust, and paint stains',NULL,180.00,NULL,5.00),(29,12,'Add','2025-05-28 16:08:52','Admin Atilia','Kitchen Deep Clean',NULL,'Degreasing kitchen walls, cabinets, stove, and appliances',NULL,80.00,NULL,2.00),(30,13,'Add','2025-05-28 16:09:31','Admin Atilia','Bathroom Intensive Clean',NULL,'Scrubbing tiles, toilets, sinks, mirrors, and removing mold',NULL,50.00,NULL,1.50),(31,14,'Add','2025-05-28 16:21:05','Admin Atilia','Pet Area Cleaning',NULL,'Cleaning and sanitizing areas pets stay in',NULL,30.00,NULL,0.50),(32,15,'Add','2025-05-28 16:21:36','Admin Atilia','Carpet Shampooing',NULL,'Shampooing and vacuuming carpets and rugs',NULL,40.00,NULL,1.00),(33,16,'Add','2025-05-28 16:23:23','Admin Atilia','Window Cleaning',NULL,'Cleaning of glass windows and sliding doors',NULL,25.00,NULL,0.50),(34,8,'Delete','2025-05-28 20:52:55','Admin Atilia','Basic Home Cleaning','General cleaning for bedrooms, bathrooms, living rooms, and kitchen',NULL,60.00,NULL,2.00,NULL),(35,13,'Update','2025-06-14 14:11:32','Admin Selina','Bathroom Intensive Clean','Scrubbing tiles, toilets, sinks, mirrors, and removing mold','Scrubbing tiles, toilets, sinks, mirrors, and removing mold',50.00,40.00,1.50,1.50);
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
  `no_of_cleaners` int(2) NOT NULL,
  `custom_request` varchar(300) DEFAULT NULL,
  `total_RM` decimal(6,2) NOT NULL,
  `estimated_duration_hour` decimal(4,2) NOT NULL,
  `scheduled_date` date NOT NULL,
  `scheduled_time` time NOT NULL,
  `status` varchar(9) NOT NULL,
  `note` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`booking_id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking`
--

LOCK TABLES `booking` WRITE;
/*!40000 ALTER TABLE `booking` DISABLE KEYS */;
INSERT INTO `booking` VALUES (5,3,1200.0000,3,2,1,150.0000,'Yes',2,'',355.10,3.27,'2025-06-30','10:00:00','Cancelled','Cancellation due to customer\'s request.'),(6,3,1200.0000,1,1,1,150.0000,'No',1,'',362.52,7.25,'2025-07-16','12:00:00','Cancelled','Cancellation due to customer\'s request.'),(7,1,1050.0000,2,1,1,100.0000,'No',1,'',374.71,7.85,'2025-07-23','11:00:00','Pending',NULL),(8,1,1050.0000,2,2,1,100.0000,'No',1,'',266.59,5.15,'2025-05-06','10:00:00','Completed',''),(9,1,1050.0000,2,2,1,100.0000,'No',2,'',404.39,4.58,'2025-03-18','09:00:00','Completed',''),(10,5,1200.0000,3,2,1,150.0000,'Yes',2,'',312.70,2.77,'2025-05-12','11:00:00','Completed',''),(11,5,1200.0000,3,2,1,150.0000,'Yes',2,'',339.20,3.02,'2025-04-16','11:00:00','Completed',''),(13,6,1200.0000,2,2,1,200.0000,'Yes',2,'Please do not touch the store.',350.86,3.08,'2025-06-13','10:00:00','Pending',NULL);
/*!40000 ALTER TABLE `booking` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `BookingStatus` AFTER UPDATE ON `booking` FOR EACH ROW BEGIN
    
    IF OLD.status != NEW.status OR OLD.note != NEW.note THEN
        
        IF NEW.note LIKE '%auto-cancelled%' THEN
            
            INSERT INTO booking_log (
                booking_id,
                made_at,
                made_by,
                old_status,
                new_status,
                new_note
            ) VALUES (
                NEW.booking_id,
                NOW(),
                'system',
                OLD.status,
                NEW.status,
                NEW.note
            );
        ELSE
            
            INSERT INTO booking_log (
                booking_id, made_at, made_by,
                old_status, new_status, new_note
            ) VALUES (
                NEW.booking_id, NOW(), 
                COALESCE(@current_user, 'unknown'),
                OLD.status, NEW.status,
                NEW.note
            );
        END IF;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

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
INSERT INTO `booking_cleaner` VALUES (5,3),(5,4),(6,11),(7,10),(8,2),(9,9),(9,13),(10,2),(10,10),(11,9),(11,10),(13,3),(13,11);
/*!40000 ALTER TABLE `booking_cleaner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_log`
--

DROP TABLE IF EXISTS `booking_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `made_at` datetime NOT NULL DEFAULT current_timestamp(),
  `made_by` varchar(100) NOT NULL,
  `old_status` varchar(9) DEFAULT NULL,
  `new_status` varchar(9) DEFAULT NULL,
  `new_note` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_log`
--

LOCK TABLES `booking_log` WRITE;
/*!40000 ALTER TABLE `booking_log` DISABLE KEYS */;
INSERT INTO `booking_log` VALUES (1,5,'2025-06-05 16:54:19','Admin Selina',NULL,NULL,NULL),(2,5,'2025-06-05 16:56:19','Admin Selina',NULL,NULL,NULL),(3,5,'2025-06-05 17:05:27','Admin Selina',NULL,NULL,NULL),(4,5,'2025-06-05 17:10:12','Admin Selina',NULL,NULL,NULL),(5,5,'2025-06-05 17:14:08','Admin Selina',NULL,NULL,NULL),(6,5,'2025-06-05 17:15:20','Admin Selina',NULL,NULL,NULL),(7,5,'2025-06-05 17:25:31','Admin Selina',NULL,NULL,NULL),(8,5,'2025-06-05 17:29:25','Admin Selina',NULL,NULL,NULL),(9,5,'2025-06-05 17:34:11','Admin Selina',NULL,NULL,NULL),(10,5,'2025-06-05 18:21:38','Admin Selina',NULL,NULL,NULL),(11,5,'2025-06-05 18:23:25','Admin Selina',NULL,NULL,NULL),(12,5,'2025-06-05 18:27:41','Admin Selina',NULL,NULL,NULL),(13,5,'2025-06-05 18:30:09','Admin Selina',NULL,NULL,NULL),(14,6,'2025-06-05 18:34:31','Admin Selina',NULL,NULL,NULL),(15,5,'2025-06-05 18:41:27','Admin Selina',NULL,NULL,NULL),(16,9,'2025-06-09 15:06:31','Peter Parker',NULL,NULL,NULL),(17,8,'2025-06-09 15:10:47','Admin Atilia',NULL,NULL,NULL),(18,11,'2025-06-09 17:13:03','Admin Atilia',NULL,NULL,NULL),(19,10,'2025-06-09 17:13:58','Admin Atilia',NULL,NULL,NULL),(20,5,'2025-06-11 22:04:20','Admin Selina','Cancelled','Cancelled','Cancellation due to customer\'s request.'),(21,6,'2025-06-11 22:47:41','Admin Selina','Pending','Cancelled','Cancellation due to customer\'s request.');
/*!40000 ALTER TABLE `booking_log` ENABLE KEYS */;
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
INSERT INTO `booking_service` VALUES (5,14),(5,15),(6,9),(7,10),(8,15),(9,9),(9,13),(10,14),(11,14),(11,16),(13,14),(13,15);
/*!40000 ALTER TABLE `booking_service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `branch_booking`
--

DROP TABLE IF EXISTS `branch_booking`;
/*!50001 DROP VIEW IF EXISTS `branch_booking`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `branch_booking` AS SELECT
 1 AS `booking_id`,
  1 AS `customer_id`,
  1 AS `total_area_sqft`,
  1 AS `no_of_bedrooms`,
  1 AS `no_of_bathrooms`,
  1 AS `no_of_livingroooms`,
  1 AS `size_of_kitchen_sqft`,
  1 AS `pet`,
  1 AS `no_of_cleaners`,
  1 AS `custom_request`,
  1 AS `total_RM`,
  1 AS `estimated_duration_hour`,
  1 AS `scheduled_date`,
  1 AS `scheduled_time`,
  1 AS `status`,
  1 AS `note` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `branch_staff`
--

DROP TABLE IF EXISTS `branch_staff`;
/*!50001 DROP VIEW IF EXISTS `branch_staff`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `branch_staff` AS SELECT
 1 AS `staff_id`,
  1 AS `name`,
  1 AS `email`,
  1 AS `phone_number`,
  1 AS `branch`,
  1 AS `role`,
  1 AS `status` */;
SET character_set_client = @saved_cs_client;

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` VALUES (1,'Peter Parker','peter@gmail.com','$2y$10$512yAvTwuWtodfujAfHNeuOA2SDiPVy5X8xGilP9Qt/RxANqfn2Ba','0173647364','99, Taman Desa Duranta','seremban','negeri sembilan','2025-05-07 02:03:15'),(3,'Mary Jane','mary@email.com','$2y$10$PjLO5kyD72I8hpc9ThPYfufS57crMWYHs0Ass6EQ23wXGWfLWWxh2','0167367264','33, taman rumpai','ayer keroh','melaka','2025-05-29 00:06:22'),(4,'Farhana','farhana@email.com','$2y$10$8bDymvJw2YYIA7BIf/lfO.Udjfp.EA3XK3bY7e3ExWoZ.X3NPhykq','0147487329','No 71, Taman Pelangi','nilai','Negeri Sembilan','2025-06-02 16:39:57'),(5,'Misha Zainuddin','misha@email.com','$2y$10$NIFFSP1qWp4tHh0Ee4etBuVfFpUN4souBlr4yzt0y0WtFl.hEA3pi','0123242368','47, Taman Ros','Seremban','Negeri Sembilan','2025-06-09 17:00:24'),(6,'Evelyn Han','evelyn@email.com','$2y$10$.MUSMnp7CWDqLNxxwb01E.HRUWwoUSP9udPEAKOaq2CqIx.zkkvMa','0172837237','13, Taman Bukit Utama','Ayer Keroh','Melaka','2025-06-12 00:37:23');
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
  `booking_id` int(11) NOT NULL,
  `status` varchar(12) NOT NULL,
  `payment_date` datetime DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `booking_id` (`booking_id`),
  CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment`
--

LOCK TABLES `payment` WRITE;
/*!40000 ALTER TABLE `payment` DISABLE KEYS */;
INSERT INTO `payment` VALUES (1,5,'Cancelled',NULL),(2,6,'Cancelled',NULL),(3,7,'Pending',NULL),(4,8,'Completed','2025-05-06 15:10:47'),(5,9,'Completed','2025-03-18 15:06:31'),(6,10,'Completed','2025-05-12 17:13:58'),(7,11,'Completed','2025-04-16 17:13:03'),(9,13,'Pending',NULL);
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
  `status` varchar(9) NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`staff_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff`
--

LOCK TABLES `staff` WRITE;
/*!40000 ALTER TABLE `staff` DISABLE KEYS */;
INSERT INTO `staff` VALUES (1,'Admin Atilia','adminatilia@hygieiahub.com','$2y$10$azX9xcd.5QZuuMlBvEDqrumCCXFcHd03HkKTTawHV7ENhdMI7UKyW','0123456789','Seremban','Admin','2025-05-07 02:14:07','Active'),(2,'Cleaner Aishah',NULL,NULL,'0112233445','Seremban','Cleaner','2025-05-12 02:05:53','Active'),(3,'Cleaner Faiz',NULL,NULL,'0193344556','Ayer Keroh','Cleaner','2025-05-12 02:05:53','Active'),(4,'Cleaner Baek','','$2y$10$y7WGm7KTKvMJz2PQ9uwLC.uE3kAkt3dtavnxTaDesrFpLynkqk/Z.','0164732647','Ayer Keroh','Cleaner','2025-05-17 22:15:16','Active'),(5,'Admin Selina','adminselina@hygieiahub.com','$2y$10$BQAgPudOuq6yWUZskuT7cuTQzvFU2TRCsZ7uL0EKPRXNMeUHA3Trm','0133746374','Ayer Keroh','Admin','2025-05-17 22:32:34','Active'),(9,'Cleaner Melur','','$2y$10$3uN889csF6W1uG.eb7eecOhYRwTRseWGp.CShb7UY8GNMw6zGr8dG','0137236473','Seremban','Cleaner','2025-05-28 22:52:53','Active'),(10,'Cleaner Kim','','$2y$10$b3nO.j9FsBnm6fh.mB0Xd.a9bnFTKmSMhxa8zeMTaiYgfEYVoXOg6','0172432434','Seremban','Cleaner','2025-05-28 22:53:36','Active'),(11,'Cleaner Anjali','','$2y$10$8m6SMNlnXuRnLB2DhjjyN.Yfm0C8d80uxGFBGNgigtejImrl1V18W','0173242342','Ayer Keroh','Cleaner','2025-05-29 10:12:57','Active'),(12,'Cleaner Aiman','','$2y$10$tJpxXIMyMDeucI8/HW9kNukZMiK/81oXQ2h6UESz27oGl1dMrsbBa','0173827483','Ayer Keroh','Cleaner','2025-05-29 10:13:42','Active'),(13,'Cleaner Michael','','$2y$10$aOgrPNYvQVCHVobUjeqWbu8yXy3Dgy0E0tdU.vNuTf1xcEECK2LaC','0172832949','Seremban','Cleaner','2025-06-03 18:40:36','Active'),(14,'Cleaner Azizi','','$2y$10$L/OUYnJ.nsAcLhqQIXy50eRQKbu8lTYprFBok9Ha4VUJBdCArAIaG','0137462378','Seremban','Cleaner','2025-06-10 21:16:03','In-Active');
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
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff_log`
--

LOCK TABLES `staff_log` WRITE;
/*!40000 ALTER TABLE `staff_log` DISABLE KEYS */;
INSERT INTO `staff_log` VALUES (1,NULL,'failed','2025-05-17 16:21:22','System',NULL,'peter@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(2,1,'Login','2025-05-17 16:46:22','Admin Atilia','Admin Atilia',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,NULL,'Failed Login','2025-05-17 17:26:00','Unknown',NULL,'non-exist',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4,NULL,'Failed Login','2025-05-17 17:29:23','Admin Atilia',NULL,'admin@hygieiahub.com',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL),(5,1,'Failed Login','2025-05-17 17:31:32','Admin Atilia',NULL,'admin@hygieiahub.com',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL),(6,1,'Login','2025-05-17 17:31:49','Admin Atilia','Admin Atilia',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(7,2,'Update','2025-05-17 17:37:35','Admin Atilia','Cleaner Aishah',NULL,'0112233445','Seremban','cleaner','active','','0112233445','Seremban','cleaner','in-active'),(8,2,'Update','2025-05-17 17:43:28','Admin Atilia','Cleaner Aishah','','0112233445','Seremban','cleaner','in-active','','0112233445','Seremban','cleaner','active'),(9,2,'Update','2025-05-17 17:49:52','Admin Atilia','Cleaner Aishah','','0112233445','Seremban','cleaner','active','','0112233445','Seremban','cleaner','in-active'),(10,2,'Update','2025-05-17 17:51:28','Admin Atilia','Cleaner Aishah','','0112233445','Seremban','cleaner','in-active','','0112233445','Seremban','cleaner','active'),(11,4,'Register','2025-05-17 22:15:16','Admin Atilia','Cleaner Baek',NULL,NULL,NULL,NULL,NULL,'','0164732647','Ayer Keroh','cleaner','active'),(12,5,'Register','2025-05-17 22:32:34','Admin Atilia','Admin Selina',NULL,NULL,NULL,NULL,NULL,'adminselina@hygieiahub.com','0133746374','Ayer Keroh','admin','active'),(13,3,'Update','2025-05-17 23:11:15','Admin Atilia','Cleaner Faiz',NULL,'0193344556','Ayer Keroh','cleaner','active',NULL,'0193344556','Ayer Keroh','cleaner','in-active'),(14,3,'Update','2025-05-17 23:47:23','Admin Atilia','Cleaner Faiz',NULL,'0193344556','Ayer Keroh','cleaner','in-active',NULL,'0193344556','Ayer Keroh','cleaner','active'),(15,5,'Login','2025-05-18 20:23:23','Admin Selina','Admin Selina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(16,5,'Login','2025-05-19 00:41:20','Admin Selina','Admin Selina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(17,5,'Login','2025-05-19 00:59:33','Admin Selina','Admin Selina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(18,5,'Login','2025-05-19 19:50:14','Admin Selina','Admin Selina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(19,0,'Failed Login','2025-05-19 21:45:34','Unknown',NULL,'',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL),(20,5,'Login','2025-05-19 21:45:46','Admin Selina','Admin Selina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(21,5,'Login','2025-05-19 21:55:34','Admin Selina','Admin Selina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(22,5,'Login','2025-05-19 21:59:12','Admin Selina','Admin Selina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(23,5,'Login','2025-05-19 22:02:03','Admin Selina','Admin Selina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(24,5,'Login','2025-05-20 21:44:36','Admin Selina','Admin Selina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(25,5,'Login','2025-05-24 12:51:06','Admin Selina','Admin Selina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(26,5,'Login','2025-05-25 23:25:37','Admin Selina','Admin Selina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(27,1,'Login','2025-05-27 21:49:09','Admin Atilia','Admin Atilia',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(28,1,'Login','2025-05-28 15:50:44','Admin Atilia','Admin Atilia',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(29,9,'Register','2025-05-28 22:52:53','Admin Atilia','Cleaner Melur',NULL,NULL,NULL,NULL,NULL,'','0137236473','Seremban','cleaner','active'),(30,10,'Register','2025-05-28 22:53:36','Admin Atilia','Cleaner Kim',NULL,NULL,NULL,NULL,NULL,'','0172432434','Seremban','cleaner','active'),(31,1,'Login','2025-05-29 10:07:31','Admin Atilia','Admin Atilia',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(32,11,'Register','2025-05-29 10:12:57','Admin Atilia','Cleaner Anjali',NULL,NULL,NULL,NULL,NULL,'','0173242342','Ayer Keroh','cleaner','active'),(33,12,'Register','2025-05-29 10:13:42','Admin Atilia','Cleaner Aiman',NULL,NULL,NULL,NULL,NULL,'','0173827483','Ayer Keroh','cleaner','active'),(34,1,'Login','2025-06-01 16:18:51','Admin Atilia','Admin Atilia',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(35,1,'Login','2025-06-02 16:54:15','Admin Atilia','Admin Atilia',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(36,1,'Login','2025-06-03 15:32:46','Admin Atilia','Admin Atilia',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(37,1,'Login','2025-06-03 17:38:42','Admin Atilia','Admin Atilia',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(38,1,'Login','2025-06-03 17:53:24','Admin Atilia','Admin Atilia',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(39,13,'Register','2025-06-03 18:40:36','Admin Atilia','Cleaner Michael',NULL,NULL,NULL,NULL,NULL,'','0172832949','Seremban','Cleaner','Active'),(40,1,'Login','2025-06-04 00:27:23','Admin Atilia','Admin Atilia',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(41,5,'Login','2025-06-04 15:21:22','Admin Selina','Admin Selina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(42,1,'Login','2025-06-06 03:31:12','Admin Atilia','Admin Atilia',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(43,1,'Login','2025-06-09 12:57:14','Admin Atilia','Admin Atilia',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(44,0,'Failed Login','2025-06-09 15:02:56','Unknown',NULL,'peter@gmail.com',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL),(45,1,'Login','2025-06-09 15:03:10','Admin Atilia','Admin Atilia',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(46,1,'Login','2025-06-09 15:10:17','Admin Atilia','Admin Atilia',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(47,1,'Login','2025-06-09 16:43:20','Admin Atilia','Admin Atilia',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(48,1,'Login','2025-06-10 20:49:26','Admin Atilia','Admin Atilia',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(49,14,'Register','2025-06-10 21:16:03','Admin Atilia','Cleaner Azizi',NULL,NULL,NULL,NULL,NULL,'','0137462378','Seremban','Cleaner','Active'),(50,14,'Update','2025-06-10 21:55:34','Admin Atilia','Cleaner Azizi','','0137462378','Seremban','Cleaner','Active','','0137462378','Seremban',NULL,'In-Active'),(51,5,'Login','2025-06-11 20:56:32','Admin Selina','Admin Selina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(52,5,'Login','2025-06-11 22:23:56','Admin Selina','Admin Selina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(53,1,'Login','2025-06-12 11:20:54','Admin Atilia','Admin Atilia',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(54,1,'Update','2025-06-13 00:02:12','','Admin Atilia','admin@hygieiahub.com','0123456789','Seremban','Admin','Active','admin@hygieiahub.com','0123456789','',NULL,''),(55,1,'Update','2025-06-13 00:02:38','','Admin Atilia','admin@hygieiahub.com','0123456789','Seremban','Admin','Active','adminatilia@hygieiahub.com','0123456789','',NULL,''),(56,1,'Failed Login','2025-06-14 13:58:21','Admin Atilia',NULL,'adminatilia@hygieiahub.com',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL),(57,1,'Failed Login','2025-06-14 13:59:46','Admin Atilia',NULL,'adminatilia@hygieiahub.com',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL),(58,1,'Login','2025-06-14 14:00:16','Admin Atilia','Admin Atilia',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(59,1,'Update','2025-06-14 14:01:26','','Admin Atilia','adminatilia@hygieiahub.com','0123456789','Seremban','Admin','Active','adminatilia@hygieiahub.com','0123456789','',NULL,''),(60,5,'Login','2025-06-14 14:01:53','Admin Selina','Admin Selina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `staff_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `branch_booking`
--

/*!50001 DROP VIEW IF EXISTS `branch_booking`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `branch_booking` AS select `b`.`booking_id` AS `booking_id`,`b`.`customer_id` AS `customer_id`,`b`.`total_area_sqft` AS `total_area_sqft`,`b`.`no_of_bedrooms` AS `no_of_bedrooms`,`b`.`no_of_bathrooms` AS `no_of_bathrooms`,`b`.`no_of_livingroooms` AS `no_of_livingroooms`,`b`.`size_of_kitchen_sqft` AS `size_of_kitchen_sqft`,`b`.`pet` AS `pet`,`b`.`no_of_cleaners` AS `no_of_cleaners`,`b`.`custom_request` AS `custom_request`,`b`.`total_RM` AS `total_RM`,`b`.`estimated_duration_hour` AS `estimated_duration_hour`,`b`.`scheduled_date` AS `scheduled_date`,`b`.`scheduled_time` AS `scheduled_time`,`b`.`status` AS `status`,`b`.`note` AS `note` from (`booking` `b` join `customer` `c` on(`b`.`customer_id` = `c`.`customer_id`)) where `c`.`city` = `get_current_branch`() */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `branch_staff`
--

/*!50001 DROP VIEW IF EXISTS `branch_staff`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `branch_staff` AS select `staff`.`staff_id` AS `staff_id`,`staff`.`name` AS `name`,`staff`.`email` AS `email`,`staff`.`phone_number` AS `phone_number`,`staff`.`branch` AS `branch`,`staff`.`role` AS `role`,`staff`.`status` AS `status` from `staff` where `staff`.`branch` = `get_current_branch`() */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-14 14:19:37

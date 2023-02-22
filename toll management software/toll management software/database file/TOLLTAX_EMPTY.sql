-- MySQL dump 10.13  Distrib 8.0.22, for Win64 (x86_64)
--
-- Host: localhost    Database: tolltax_filled
-- ------------------------------------------------------
-- Server version	8.0.22

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `barcode_entries`
--

DROP TABLE IF EXISTS `barcode_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `barcode_entries` (
  `SNO` int NOT NULL AUTO_INCREMENT,
  `DATE` date DEFAULT NULL,
  `TIME` varchar(45) DEFAULT NULL,
  `ID` varchar(45) DEFAULT NULL,
  `NAME` varchar(255) DEFAULT NULL,
  `V_TYPE` varchar(45) DEFAULT NULL,
  `V_NUMBER` varchar(45) DEFAULT NULL,
  `J_TYPE` varchar(45) DEFAULT NULL,
  `DIRECTION` varchar(45) DEFAULT NULL,
  `BOOTH` varchar(45) DEFAULT NULL,
  `OLD_RECEIPT_NO` varchar(255) DEFAULT NULL,
  `NEW_RECEIPT_NO` varchar(255) DEFAULT NULL,
  `SHIFT` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`SNO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barcode_entries`
--

LOCK TABLES `barcode_entries` WRITE;
/*!40000 ALTER TABLE `barcode_entries` DISABLE KEYS */;
/*!40000 ALTER TABLE `barcode_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booth_sno`
--

DROP TABLE IF EXISTS `booth_sno`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booth_sno` (
  `BOOTH` varchar(100) NOT NULL,
  `SNO` int DEFAULT NULL,
  PRIMARY KEY (`BOOTH`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booth_sno`
--

LOCK TABLES `booth_sno` WRITE;
/*!40000 ALTER TABLE `booth_sno` DISABLE KEYS */;
INSERT INTO `booth_sno` VALUES ('L1',0),('L2',0),('L3',0),('L4',0);
/*!40000 ALTER TABLE `booth_sno` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cash_up`
--

DROP TABLE IF EXISTS `cash_up`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cash_up` (
  `DATE` date NOT NULL,
  `TIME` varchar(10) NOT NULL,
  `ID` varchar(255) NOT NULL,
  `SHIFT` varchar(50) NOT NULL,
  `SYS_COLLECTION` double NOT NULL,
  `CORRECT_TOLL` double NOT NULL,
  `RS1` int NOT NULL,
  `RS2` int NOT NULL,
  `RS5` int NOT NULL,
  `RS10` int NOT NULL,
  `RS20` int NOT NULL,
  `RS50` int NOT NULL,
  `RS100` int NOT NULL,
  `RS200` int NOT NULL,
  `RS500` int NOT NULL,
  `RS2000` int NOT NULL,
  `CASH_COLLECTION` double NOT NULL,
  `RECOVERY_AMOUNT` double NOT NULL,
  `BOOTH` varchar(20) NOT NULL,
  `NAME` varchar(100) NOT NULL,
  `MANUAL_COLLECTION` double NOT NULL,
  `CASHUP_BY` text,
  PRIMARY KEY (`DATE`,`TIME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cash_up`
--

LOCK TABLES `cash_up` WRITE;
/*!40000 ALTER TABLE `cash_up` DISABLE KEYS */;
/*!40000 ALTER TABLE `cash_up` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `monthly_pass`
--

DROP TABLE IF EXISTS `monthly_pass`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `monthly_pass` (
  `PASS_NO` int NOT NULL AUTO_INCREMENT,
  `TIME` varchar(10) NOT NULL,
  `DATE` date NOT NULL,
  `NAME` varchar(50) NOT NULL,
  `MOBILE_NO` varchar(255) NOT NULL,
  `ADDRESS` varchar(255) NOT NULL,
  `VEHICLE_TYPE` varchar(50) NOT NULL,
  `VEHICLE_NUMBER` varchar(20) NOT NULL,
  `FROM_DATE` varchar(10) NOT NULL,
  `TO_DATE` varchar(10) NOT NULL,
  `AMOUNT` double NOT NULL,
  PRIMARY KEY (`PASS_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `monthly_pass`
--

LOCK TABLES `monthly_pass` WRITE;
/*!40000 ALTER TABLE `monthly_pass` DISABLE KEYS */;
/*!40000 ALTER TABLE `monthly_pass` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sp_exemption`
--

DROP TABLE IF EXISTS `sp_exemption`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sp_exemption` (
  `SNO` int NOT NULL AUTO_INCREMENT,
  `NAME` varchar(100) NOT NULL,
  `VEHICLE_TYPE` varchar(100) NOT NULL,
  `VEHICLE_NUMBER` varchar(100) NOT NULL,
  `MOBILE` varchar(100) NOT NULL,
  `ADDRESS` varchar(255) NOT NULL,
  PRIMARY KEY (`SNO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sp_exemption`
--

LOCK TABLES `sp_exemption` WRITE;
/*!40000 ALTER TABLE `sp_exemption` DISABLE KEYS */;
/*!40000 ALTER TABLE `sp_exemption` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `ID` varchar(20) NOT NULL,
  `TYPE` varchar(25) NOT NULL,
  `NAME` varchar(50) NOT NULL,
  `USERNAME` varchar(50) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `DATE` date NOT NULL,
  `TIME` varchar(10) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('1111','audit','audit','audit','b81f37a043a6f767e7c94d105f4bd31282f3ecc20680bb9d09bd93461cf4c863','2021-02-09','09:28:45PM'),('12341234','accountant','Accountant Accountant','accountant','29d8c99ec25b271007f05eace87ec00959746687aa96ed783dd7a2f3bfdb398b','2020-11-05','10:23:26am'),('12345','staff','Aniket Thani','aniket7','1562206543da764123c21bd524674f0a8aaf49c8a89744c97352fe677f7e4006','2021-01-11','11:43:21pm'),('1234567','administrator','MKTPL','mktpl','8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918','0000-00-00','11:18:22pm'),('7777','pass manager','shardul thakur','pmanager','7d474614c4563954be867fcdaaee260f793650d9f8c7ab75329fba4c92e862f5','2021-01-12','07:16:25pm'),('78512','staff','shreyashp55','shreyashp55','1562206543da764123c21bd524674f0a8aaf49c8a89744c97352fe677f7e4006','2021-02-12','12:50:16pm');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicle_details`
--

DROP TABLE IF EXISTS `vehicle_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehicle_details` (
  `SNO` int NOT NULL AUTO_INCREMENT,
  `DATE` date NOT NULL,
  `TIME` varchar(10) NOT NULL,
  `EMPLOYEE_ID` int NOT NULL,
  `NAME` varchar(50) NOT NULL,
  `V_TYPE` varchar(50) DEFAULT NULL,
  `V_NUMBER` varchar(20) DEFAULT NULL,
  `JOURNEY_TYPE` varchar(50) NOT NULL,
  `TOLL` float DEFAULT NULL,
  `IMAGE` varchar(200) NOT NULL,
  `SHIFT` varchar(10) NOT NULL,
  `BOOTH_NO` int NOT NULL,
  `RECEIPT_NO` varchar(255) NOT NULL,
  `CORRECT_TOLL` double NOT NULL,
  `VALIDATED` int NOT NULL,
  `DIRECTION` varchar(50) NOT NULL,
  `VALIDATED_BY` text,
  `OLD_V_TYPE` varchar(255) NOT NULL,
  PRIMARY KEY (`SNO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_details`
--

LOCK TABLES `vehicle_details` WRITE;
/*!40000 ALTER TABLE `vehicle_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `vehicle_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicle_type`
--

DROP TABLE IF EXISTS `vehicle_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehicle_type` (
  `VEHICLE_TYPE` varchar(50) NOT NULL,
  `TOLL_AMOUNT` float NOT NULL,
  `SNO` varchar(45) NOT NULL,
  PRIMARY KEY (`SNO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_type`
--

LOCK TABLES `vehicle_type` WRITE;
/*!40000 ALTER TABLE `vehicle_type` DISABLE KEYS */;
INSERT INTO `vehicle_type` VALUES ('CAR',25,'1'),('LCV',65,'2'),('BUS',130,'3'),('TRUCK',160,'4'),('M-EXLE',315,'5'),('NA',0,'6');
/*!40000 ALTER TABLE `vehicle_type` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-03-09 15:14:12

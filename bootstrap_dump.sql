-- MySQL dump 10.13  Distrib 5.7.24, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: bootstrap
-- ------------------------------------------------------
-- Server version	5.7.24

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
-- Current Database: `bootstrap`
--

CREATE DATABASE IF NOT EXISTS `bootstrap` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `bootstrap`;

--
-- Table structure for table `user_credentials`
--

DROP TABLE IF EXISTS `user_credentials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_credentials` (
  `user_id` varchar(36) DEFAULT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_credentials`
--

LOCK TABLES `user_credentials` WRITE;
/*!40000 ALTER TABLE `user_credentials` DISABLE KEYS */;
INSERT INTO `user_credentials` VALUES ('67cbd2e3-e7e4-11ea-bb02-5cea1d6b9907','admin'),('aae826f1-e8a0-11ea-bb02-5cea1d6b9907','pass'),('3d3453ae-e8ab-11ea-bb02-5cea1d6b9907','pass'),('1e240a63-eab9-11ea-bb02-5cea1d6b9907','12345678'),('2a1c2703-eab9-11ea-bb02-5cea1d6b9907','12345678'),('3bd88c08-eab9-11ea-bb02-5cea1d6b9907','12345678'),('3da698b8-eaba-11ea-bb02-5cea1d6b9907','12345678'),('4b1c6149-eaba-11ea-bb02-5cea1d6b9907','12345678'),('569a75bb-eaba-11ea-bb02-5cea1d6b9907','12345678'),('65649fd7-eaba-11ea-bb02-5cea1d6b9907','12345678'),('7b90c0d0-eaba-11ea-bb02-5cea1d6b9907','12345678'),('8ffe847f-eaba-11ea-bb02-5cea1d6b9907','12345678'),('9cf47668-eaba-11ea-bb02-5cea1d6b9907','12345678');
/*!40000 ALTER TABLE `user_credentials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` varchar(36) NOT NULL,
  `fname` varchar(35) DEFAULT NULL,
  `lname` varchar(35) DEFAULT NULL,
  `email_address` varchar(35) DEFAULT NULL,
  `supervisor_id` varchar(36) DEFAULT NULL,
  `is_supervisor` tinyint(1) NOT NULL DEFAULT '0',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT CURRENT_TIMESTAMP,
  `user_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('1e240a63-eab9-11ea-bb02-5cea1d6b9907','test','test','test@test.com',NULL,0,0,'2020-08-30 13:05:38','2020-08-30 13:06:06','test1'),('2a1c2703-eab9-11ea-bb02-5cea1d6b9907','test','test','test@test.com',NULL,0,0,'2020-08-30 13:05:58','2020-08-30 13:05:58','test2'),('3bd88c08-eab9-11ea-bb02-5cea1d6b9907','test','test','test@test.com',NULL,0,0,'2020-08-30 13:06:28','2020-08-30 13:06:28','test3'),('3da698b8-eaba-11ea-bb02-5cea1d6b9907','supervisor','supervisor','supervisor1@supervisor1.com',NULL,1,0,'2020-08-30 13:13:40','2020-08-30 13:13:40','supervisor1'),('4b1c6149-eaba-11ea-bb02-5cea1d6b9907','supervisor','supervisor','supervisor2@supervisor2.com',NULL,1,0,'2020-08-30 13:14:03','2020-08-30 13:14:03','supervisor2'),('569a75bb-eaba-11ea-bb02-5cea1d6b9907','user','user','user@user.com',NULL,0,0,'2020-08-30 13:14:22','2020-08-30 13:14:22','user'),('65649fd7-eaba-11ea-bb02-5cea1d6b9907','admin','admin','test@test.com',NULL,0,1,'2020-08-30 13:14:47','2020-08-30 13:14:47','admindt'),('67cbd2e3-e7e4-11ea-bb02-5cea1d6b9907','admin','admin','vitya.r.93@gmail.com',NULL,1,1,'2020-08-26 22:37:56','2020-08-30 13:16:45','admin'),('7b90c0d0-eaba-11ea-bb02-5cea1d6b9907','test','test','test@test.co',NULL,0,0,'2020-08-30 13:15:24','2020-08-30 13:15:24','test4'),('8ffe847f-eaba-11ea-bb02-5cea1d6b9907','test','test','test@test.com',NULL,0,0,'2020-08-30 13:15:58','2020-08-30 13:15:58','test5'),('9cf47668-eaba-11ea-bb02-5cea1d6b9907','test','test','test@test.com',NULL,0,0,'2020-08-30 13:16:20','2020-08-30 13:16:20','user2');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vacations`
--

DROP TABLE IF EXISTS `vacations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vacations` (
  `id` varchar(36) NOT NULL,
  `requested_by_id` varchar(36) DEFAULT NULL,
  `approver_id` varchar(36) DEFAULT NULL,
  `decision` varchar(30) DEFAULT NULL,
  `description` text,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `number_of_days` int(11) NOT NULL DEFAULT '1',
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vacations`
--

LOCK TABLES `vacations` WRITE;
/*!40000 ALTER TABLE `vacations` DISABLE KEYS */;
INSERT INTO `vacations` VALUES ('00f84c55-e969-11ea-bb02-5cea1d6b9907','00f82395-e969-11ea-bb02-5cea1d6b9907',NULL,'Approved','trololo','2020-09-01','2020-09-10',8,'2020-08-28 20:59:38','2020-08-28 20:59:38'),('0f9933c7-21f4-498b-b196-af3737a77d80','67cbd2e3-e7e4-11ea-bb02-5cea1d6b9907',NULL,'Approved','sdr','2020-08-31','2020-08-01',-15,'2020-08-29 01:08:12','2020-08-29 01:08:12'),('1ce482f8-3e99-4493-a17f-0eb3f2566f00','67cbd2e3-e7e4-11ea-bb02-5cea1d6b9907',NULL,NULL,'Test','2020-08-25','2020-08-31',5,'2020-08-29 23:11:23','2020-08-29 23:11:23'),('3280bde2-2414-4174-821a-340e312cc2a9','67cbd2e3-e7e4-11ea-bb02-5cea1d6b9907',NULL,NULL,'TEST HTML','2020-09-01','2020-09-15',11,'2020-08-29 23:51:41','2020-08-29 23:51:41'),('4c9d6fb2-52f3-4d77-a415-830780f0f6a5','67cbd2e3-e7e4-11ea-bb02-5cea1d6b9907',NULL,NULL,'teeeest','2020-08-04','2020-08-25',16,'2020-08-29 23:23:15','2020-08-29 23:23:15'),('6f74a042-df72-42b4-a048-636cbb0b9837','67cbd2e3-e7e4-11ea-bb02-5cea1d6b9907',NULL,'Rejected','df','2020-08-01','2020-08-31',21,'2020-08-29 01:07:01','2020-08-29 23:20:26'),('8c08ca39-154a-42f9-a046-a8573a321aed','67cbd2e3-e7e4-11ea-bb02-5cea1d6b9907',NULL,NULL,'','2020-08-13','2020-10-29',56,'2020-08-29 00:51:53','2020-08-29 00:51:53'),('a060b5be-bbd7-499a-97e6-fe288f6213db','67cbd2e3-e7e4-11ea-bb02-5cea1d6b9907',NULL,NULL,'','2020-09-16','2020-08-31',-6,'2020-08-29 00:51:36','2020-08-29 00:51:36'),('a0a12694-e96b-11ea-bb02-5cea1d6b9907','67cbd2e3-e7e4-11ea-bb02-5cea1d6b9907',NULL,NULL,'sDzd','2020-08-04','2020-08-29',19,'2020-08-28 21:18:25','2020-08-28 21:18:25'),('a14a1eaa-83dd-4e34-8409-724a1303ac12','67cbd2e3-e7e4-11ea-bb02-5cea1d6b9907',NULL,NULL,'seggfdgd','2020-08-27','2020-08-11',-6,'2020-08-29 01:06:33','2020-08-29 01:06:33'),('bd07424e-e956-11ea-bb02-5cea1d6b9907','bd071821-e956-11ea-bb02-5cea1d6b9907',NULL,NULL,'trololo','2020-09-01','2020-09-10',8,'2020-08-28 18:48:53','2020-08-28 18:48:53'),('c1ef038a-9b0b-4284-ba6f-f8c32e49357d','67cbd2e3-e7e4-11ea-bb02-5cea1d6b9907',NULL,NULL,'df','2020-08-31','2020-07-06',-39,'2020-08-29 01:04:42','2020-08-29 01:04:42'),('fb14f4d9-5ace-4f28-b005-5c83513b2664','67cbd2e3-e7e4-11ea-bb02-5cea1d6b9907',NULL,NULL,'sfsf','2020-08-31','2020-08-26',3,'2020-08-29 01:02:27','2020-08-29 01:02:27');
/*!40000 ALTER TABLE `vacations` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-08-30 13:35:11

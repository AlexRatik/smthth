-- MySQL dump 10.13  Distrib 8.0.32, for Linux (x86_64)
--
-- Host: localhost    Database: users
-- ------------------------------------------------------
-- Server version	8.0.32-0ubuntu0.22.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=199 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'John Doe','john@example.com','male','active'),(87,'asdq','asdfa@kd.efe','male','active'),(99,'qwer','asdfad@md.ew','male','active'),(101,'qwer','asfdfad@md.ew','male','active'),(102,'qwer','asfdfdad@md.ew','male','active'),(115,'sdg','asda@md.ew','male','active'),(117,'sdg','asfda@md.ew','male','active'),(128,'sdsa','asasdda@md.ew','male','active'),(130,'sdsa','asafsdda@md.ew','male','active'),(132,'sdsa','asafasdda@md.ew','male','active'),(134,'sdsa','asafafsdda@md.ew','male','active'),(137,'sdsa','asfafafsdda@md.ew','male','active'),(139,'asfw','asdsfa@md.ew','male','active'),(141,'Jana Doe','jaraf_ddode@mail.kr','female','active'),(143,'Jana Doe','jaraf_dddode@mail.kr','female','active'),(144,'string','user@example.com','male','active'),(147,'string','usher@example.com','male','active'),(151,'sdknsgs','sdfs@md.re','male','active'),(152,'John Doe','johndoe@exsaple.com','male','active'),(153,'Jane Doe','janedoe@exsmple.com','female','active'),(154,'John Smith','johnsmith@exsmple.com','male','inactive'),(156,'Jahn Doe','jahn@example.com','female','inactive'),(159,'John Dol','john_dol@example.com','male','active'),(188,'John Doe','d9essqn2bp@inn.com','male','active'),(189,'Jane Doe','lde9aohc7o@inn.com','female','active'),(190,'John Smith','5chjspggos@inn.com','male','inactive'),(191,'John Doe','yb0ac4rh23@inn.com','male','active'),(192,'Jane Doe','4sqfnu6f7x@inn.com','female','active'),(193,'John Smith','ciljw6leoq@inn.com','male','inactive'),(194,'John Doe','bmv6j4bhuu@inn.com','male','active'),(195,'Jane Doe','1lonukaek3@inn.com','female','active'),(196,'John Smith','zegwdesek5@inn.com','male','inactive'),(198,'string','userhk@example.com','male','active');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-03-27 15:37:58

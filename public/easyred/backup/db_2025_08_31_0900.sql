/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.13-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: imhotion_db
-- ------------------------------------------------------
-- Server version	10.11.13-MariaDB-0ubuntu0.24.04.1

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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES
('laravel-cache-client1@imhotion.com|178.116.84.49','i:1;',1756621636),
('laravel-cache-client1@imhotion.com|178.116.84.49:timer','i:1756621636;',1756621636);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `deliveries`
--

DROP TABLE IF EXISTS `deliveries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `deliveries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `size_bytes` bigint(20) unsigned NOT NULL DEFAULT 0,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `deliveries_project_id_expires_at_index` (`project_id`,`expires_at`),
  CONSTRAINT `deliveries_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deliveries`
--

LOCK TABLES `deliveries` WRITE;
/*!40000 ALTER TABLE `deliveries` DISABLE KEYS */;
INSERT INTO `deliveries` VALUES
(1,1,'Zip package','deliveries/sample-a82c30ac-673d-35c6-83ec-b5c753a1ba88.zip',62840583,NULL,'2025-08-31 01:14:38','2025-08-31 01:14:38'),
(2,2,'Zip package','deliveries/sample-70092f17-7c23-3cb5-bfbb-95ff80686f4e.zip',146688338,NULL,'2025-08-31 01:14:38','2025-08-31 01:14:38'),
(3,3,'Zip package','deliveries/sample-b970d3b3-cbd0-3358-a5c1-10e9dc854f0e.zip',54223910,NULL,'2025-08-31 01:14:38','2025-08-31 01:14:38'),
(4,4,'Zip package','deliveries/sample-0a75af3b-a1b4-3c9a-94ee-e3978a652a39.zip',41255919,NULL,'2025-08-31 01:14:38','2025-08-31 01:14:38'),
(5,5,'Zip package','deliveries/sample-59c927bd-6b30-361c-bfb7-6309838b4675.zip',140193002,NULL,'2025-08-31 01:14:38','2025-08-31 01:14:38'),
(6,6,'Zip package','deliveries/sample-a38a2254-c4ff-3d01-81bc-275bd7e7d7a7.zip',29939799,NULL,'2025-08-31 01:14:38','2025-08-31 01:14:38'),
(7,7,'Zip package','deliveries/sample-59cd8b2d-dc75-3922-b173-29f2469542a1.zip',122004565,NULL,'2025-08-31 01:15:30','2025-08-31 01:15:30'),
(8,8,'Zip package','deliveries/sample-df3c4050-398f-38cf-9733-669ddca4de8e.zip',61552956,NULL,'2025-08-31 01:15:30','2025-08-31 01:15:30'),
(9,9,'Zip package','deliveries/sample-00f0ad93-c42a-3eda-aff0-f7123c6d29d3.zip',137867662,NULL,'2025-08-31 01:15:30','2025-08-31 01:15:30'),
(10,10,'Zip package','deliveries/sample-bc813b69-1d38-34a8-a6ae-db9b62500f95.zip',24856843,NULL,'2025-08-31 01:15:30','2025-08-31 01:15:30'),
(11,11,'Zip package','deliveries/sample-e1d04cfa-b2e9-3eb2-830b-6d2e9e073ca6.zip',84934155,NULL,'2025-08-31 01:15:30','2025-08-31 01:15:30'),
(12,12,'Zip package','deliveries/sample-bb7c803f-f2ac-368b-83f0-1d8477f77bc7.zip',78529910,NULL,'2025-08-31 01:15:30','2025-08-31 01:15:30'),
(13,13,'Preview ZIP','deliveries/sample-13.zip',25000000,NULL,'2025-08-31 01:26:26','2025-08-31 01:26:26'),
(14,14,'Preview ZIP','deliveries/sample-14.zip',25000000,NULL,'2025-08-31 01:26:26','2025-08-31 01:26:26'),
(15,15,'Preview ZIP','deliveries/sample-15.zip',25000000,NULL,'2025-08-31 01:26:26','2025-08-31 01:26:26'),
(16,16,'Preview ZIP','deliveries/sample-16.zip',25000000,NULL,'2025-08-31 01:26:26','2025-08-31 01:26:26');
/*!40000 ALTER TABLE `deliveries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2025_08_31_100000_create_projects_table',2),
(5,'2025_08_31_100100_create_deliveries_table',2),
(6,'2025_08_31_045509_add_role_to_users_table',3),
(7,'2024_06_01_000000_add_role_to_users_table',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `projects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `topic` varchar(255) DEFAULT NULL,
  `due_at` date DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'in_progress',
  `progress` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `projects_user_id_status_index` (`user_id`,`status`),
  KEY `projects_due_at_index` (`due_at`),
  CONSTRAINT `projects_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES
(1,3,'Nobis non ut','Qui','2025-10-03','in_progress',100,'2025-08-31 01:14:38','2025-08-31 01:14:38'),
(2,3,'Qui et repudiandae','Voluptatem','2025-10-03','new',87,'2025-08-31 01:14:38','2025-08-31 01:14:38'),
(3,3,'Necessitatibus repellendus temporibus','Dolor','2025-09-24','canceled',75,'2025-08-31 01:14:38','2025-08-31 01:14:38'),
(4,4,'Aperiam rem dolorum','Voluptas','2025-09-25','in_progress',48,'2025-08-31 01:14:38','2025-08-31 01:14:38'),
(5,4,'Odit in harum','Et','2025-09-17','canceled',88,'2025-08-31 01:14:38','2025-08-31 01:14:38'),
(6,4,'Occaecati sunt quia','Et','2025-09-28','in_progress',60,'2025-08-31 01:14:38','2025-08-31 01:14:38'),
(7,5,'Sed sint voluptas','Quae','2025-10-07','completed',69,'2025-08-31 01:15:30','2025-08-31 01:15:30'),
(8,5,'Ab sunt odio','Quo','2025-09-18','in_progress',94,'2025-08-31 01:15:30','2025-08-31 01:15:30'),
(9,5,'Quos ipsam aut','Laudantium','2025-10-01','new',66,'2025-08-31 01:15:30','2025-08-31 01:15:30'),
(10,6,'Eum vel est','Quia','2025-09-09','in_progress',46,'2025-08-31 01:15:30','2025-08-31 01:15:30'),
(11,6,'Praesentium numquam quos','Facere','2025-09-23','canceled',46,'2025-08-31 01:15:30','2025-08-31 01:15:30'),
(12,6,'Consectetur animi et','Officiis','2025-10-09','new',17,'2025-08-31 01:15:30','2025-08-31 01:15:30'),
(13,1,'Admin Project A','Shot 01','2025-09-07','in_progress',60,'2025-08-31 01:26:26','2025-08-31 01:26:26'),
(14,1,'Admin Project B','Shot 02','2025-08-30','completed',100,'2025-08-31 01:26:26','2025-08-31 01:26:26'),
(15,7,'Client1 Product Animation','Camera pass','2025-09-10','in_progress',40,'2025-08-31 01:26:26','2025-08-31 01:26:26'),
(16,8,'Client2 VFX Cleanup','Scene 12','2025-09-05','completed',100,'2025-08-31 01:26:26','2025-08-31 01:26:26');
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'client',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'Admin','admin@imhotion.com','2025-08-31 00:08:36','$2y$12$MWuYyEQfo8/4/mB0TOA2SOUHpegdwKKretdeePN7yFCQIPEjmt58C','8ztNtQls3Hsi59F3VYEUtasWEgwzfktbWvi0E9Irtth4HHeXOoEJoGh2PEpb','2025-08-31 00:08:37','2025-08-31 03:43:27','admin'),
(2,'Test User','test@example.com','2025-08-31 01:14:38','$2y$12$ObtfzxppdPo5HS2Q4r82JeU6dtwSaRmHOmTn7tN9UQPeWUELpK8s2',NULL,'2025-08-31 01:14:38','2025-08-31 01:14:38','client'),
(3,'Prof. Garett Glover','xframi@example.com','2025-08-31 01:14:38','$2y$12$5yDQA/qcD2Rvm65WCjBL7ubSliwirSvJDn2L.WLiiOjXaEFg.dU1q','0d89EraAQQ','2025-08-31 01:14:38','2025-08-31 01:14:38','client'),
(4,'Liana Fay I','margot.roberts@example.org','2025-08-31 01:14:38','$2y$12$5yDQA/qcD2Rvm65WCjBL7ubSliwirSvJDn2L.WLiiOjXaEFg.dU1q','YCIDbRhSyb','2025-08-31 01:14:38','2025-08-31 01:14:38','client'),
(5,'Aglae Larkin','pgreenholt@example.net','2025-08-31 01:15:29','$2y$12$GV1cUDUxq7624o.Icd1qrew4rebxEOec5Es.kKjop/xVcrxdivUaO','lwiIl5Cxq3','2025-08-31 01:15:30','2025-08-31 01:15:30','client'),
(6,'Rosie Collins','sraynor@example.net','2025-08-31 01:15:30','$2y$12$GV1cUDUxq7624o.Icd1qrew4rebxEOec5Es.kKjop/xVcrxdivUaO','wS02zeRtgK','2025-08-31 01:15:30','2025-08-31 01:15:30','client'),
(7,'Client One','client1@example.com','2025-08-31 01:26:26','$2y$12$0tinL2QcMsNqcK3iVzG2uuSx5NjQ/IUZ160AJgxDJz4MvYTGv0L3C',NULL,'2025-08-31 01:26:26','2025-08-31 03:43:27','client'),
(8,'Client Two','client2@example.com','2025-08-31 01:26:26','$2y$12$84vqSxhKRZ/JfMVzOTVQjufKeNiikyBNS.KwpYbwIktMVCZP329C6',NULL,'2025-08-31 01:26:26','2025-08-31 03:43:27','client');
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

-- Dump completed on 2025-08-31  9:00:01

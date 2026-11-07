-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: smart_palao
-- ------------------------------------------------------
-- Server version	8.4.3

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
-- Current Database: `smart_palao`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `smart_palao` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `smart_palao`;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
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
-- Table structure for table `chambers`
--

DROP TABLE IF EXISTS `chambers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chambers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Incubator Alpha',
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Roxas (Main Hatchery)',
  `setpoint` decimal(4,2) NOT NULL DEFAULT '37.50',
  `critical_temp` decimal(4,2) NOT NULL DEFAULT '38.50',
  `heater_state` tinyint(1) NOT NULL DEFAULT '0',
  `fan_pwm` tinyint unsigned NOT NULL DEFAULT '50',
  `mode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'AUTO',
  `batch_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'BATCH-2026-D01',
  `batch_day` smallint unsigned NOT NULL DEFAULT '14',
  `duck_breed` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pateros Mallard (Itik Pinas)',
  `egg_count` int unsigned NOT NULL DEFAULT '120',
  `camera_stream_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'http://192.168.1.120:81/stream',
  `api_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'palao_esp32_secret_key_2026',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chambers`
--

LOCK TABLES `chambers` WRITE;
/*!40000 ALTER TABLE `chambers` DISABLE KEYS */;
INSERT INTO `chambers` VALUES (1,'Incubator Alpha (Roxas Main)','Roxas (Main Hatchery)',37.50,38.50,1,45,'AUTO','BATCH-2026-D01',14,'Pateros Mallard (Itik Pinas)',120,'http://192.168.1.120:81/stream','palao_esp32_secret_key_2026','2026-11-06 18:28:09','2026-11-06 18:28:09'),(2,'Hatcher Beta (Roxas Unit 2)','Roxas (Main Hatchery)',37.00,38.20,0,70,'AUTO','BATCH-2026-D02',26,'Pateros Mallard (Itik Pinas)',96,'http://192.168.1.121:81/stream','palao_esp32_beta_secret','2026-11-06 18:28:09','2026-11-06 18:28:09');
/*!40000 ALTER TABLE `chambers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_11_03_111934_create_personal_access_tokens_table',1),(5,'2026_11_03_120000_create_smart_palao_tables',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `size` enum('Large','Medium','Small') COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_price` decimal(8,2) NOT NULL,
  `quantity` int unsigned NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,1,'Large',180.00,2,360.00,'2026-11-06 18:28:09','2026-11-06 18:28:09'),(2,1,2,'Medium',150.00,1,150.00,'2026-11-06 18:28:09','2026-11-06 18:28:09'),(3,2,4,'Large',210.00,6,1260.00,'2026-11-06 18:28:09','2026-11-06 18:28:09'),(4,3,5,'Medium',180.00,2,360.00,'2026-11-06 18:28:09','2026-11-06 18:28:09'),(5,3,6,'Small',145.00,2,290.00,'2026-11-06 18:28:09','2026-11-06 18:28:09'),(6,4,2,'Medium',150.00,1,150.00,'2026-11-06 18:28:09','2026-11-06 18:28:09'),(7,4,1,'Large',180.00,1,180.00,'2026-11-06 18:28:09','2026-11-06 18:28:09');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `branch_location` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Roxas',
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivery_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Cash on Delivery',
  `status` enum('Pending','Processing','Shipped','Delivered') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `total_amount` decimal(10,2) NOT NULL,
  `delivery_fee` decimal(8,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_branch_location_status_index` (`branch_location`,`status`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'SP-ROX-20261103-A101',2,'Roxas','Maria Santos','buyer@gmail.com','+63 928 555 7788','Barangay Paclasan, Roxas, Oriental Mindoro','GCash','Pending',510.00,50.00,'Please leave with security if not home. Call upon arrival.','2026-11-06 18:28:09','2026-11-06 18:28:09'),(2,'SP-BOR-20261103-B202',NULL,'Boracay','Boracay Haven Resort Kitchen','hotel@boracayhaven.com','+63 919 444 8822','Station 2 Beachfront, Boracay Island','Bank Transfer','Processing',1260.00,75.00,'For morning breakfast buffet service. Deliver by 7:00 AM.','2026-11-06 18:28:09','2026-11-06 18:28:09'),(3,'SP-BOR-20261102-C303',NULL,'Boracay','Chef Alejandro Cruz','chef@boracaygourmet.ph','+63 917 333 1199','Diniwid Road, Barangay Yapak, Boracay','Cash on Delivery','Shipped',705.00,75.00,'Rider is on electric trike courier route.','2026-11-06 18:28:09','2026-11-06 18:28:09'),(4,'SP-ROX-20261101-D404',2,'Roxas','Maria Santos','buyer@gmail.com','+63 928 555 7788','Barangay Paclasan, Roxas, Oriental Mindoro','Cash on Delivery','Delivered',330.00,50.00,'Successfully delivered to front gate.','2026-11-06 18:28:09','2026-11-06 18:28:09');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `branch_location` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Roxas',
  `size` enum('Large','Medium','Small') COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `stock` int unsigned NOT NULL DEFAULT '0',
  `weight_range` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_branch_location_size_unique` (`branch_location`,`size`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Roxas','Large',180.00,180,'65g - 75g per egg','Grade-A jumbo duck eggs from free-range Roxas farm paddocks. Superb yolk density, ideal for gourmet cooking, salting, or premium balut.','/images/egg-large.png','2026-11-06 18:28:09','2026-11-06 18:28:09'),(2,'Roxas','Medium',150.00,260,'55g - 64g per egg','Standard premium duck eggs carefully incubated with real-time camera inspection for peak membrane freshness and fertility.','/images/egg-medium.png','2026-11-06 18:28:09','2026-11-06 18:28:09'),(3,'Roxas','Small',120.00,110,'45g - 54g per egg','Nutrient-rich pullet duck eggs from young hens. Excellent value pack for daily farm-to-table breakfast and baking.','/images/egg-small.png','2026-11-06 18:28:09','2026-11-06 18:28:09'),(4,'Boracay','Large',210.00,85,'65g - 75g per egg','Island-fulfilled premium jumbo duck eggs delivered fresh daily to Boracay resorts, restaurants, and residents.','/images/egg-large.png','2026-11-06 18:28:09','2026-11-06 18:28:09'),(5,'Boracay','Medium',180.00,140,'55g - 64g per egg','Fresh island stock of medium duck eggs with rich golden yolk. Sourced directly from our Roxas incubation hub.','/images/egg-medium.png','2026-11-06 18:28:09','2026-11-06 18:28:09'),(6,'Boracay','Small',145.00,60,'45g - 54g per egg','Fresh pullet duck eggs available for express island drop-off across Boracay Stations 1, 2, and 3.','/images/egg-small.png','2026-11-06 18:28:09','2026-11-06 18:28:09');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sensor_readings`
--

DROP TABLE IF EXISTS `sensor_readings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sensor_readings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `chamber_id` bigint unsigned NOT NULL,
  `thermal_grid_json` json DEFAULT NULL,
  `ambient_temp` decimal(4,2) NOT NULL,
  `humidity` decimal(4,2) NOT NULL,
  `heater_active` tinyint(1) NOT NULL DEFAULT '0',
  `fan_pwm` tinyint unsigned NOT NULL DEFAULT '0',
  `is_thermal_runaway` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sensor_readings_chamber_id_created_at_index` (`chamber_id`,`created_at`),
  CONSTRAINT `sensor_readings_chamber_id_foreign` FOREIGN KEY (`chamber_id`) REFERENCES `chambers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sensor_readings`
--

LOCK TABLES `sensor_readings` WRITE;
/*!40000 ALTER TABLE `sensor_readings` DISABLE KEYS */;
INSERT INTO `sensor_readings` VALUES (1,1,'[37.16, 37.16, 37.15, 37.31, 37.28, 37.18, 37.16, 37.18, 37.24, 37.11, 37.17, 37.32, 37.22, 37.18, 37.11, 37.03, 37.1, 37.28, 37.35, 37.36, 37.47, 37.28, 37.2, 37.29, 37.21, 37.17, 37.36, 37.45, 37.5, 37.25, 37.3, 37.29, 37.3, 37.36, 37.43, 37.44, 37.53, 37.36, 37.34, 37.24, 37.08, 37.29, 37.27, 37.32, 37.25, 37.24, 37.23, 37.16, 37.26, 37.18, 37.17, 37.28, 37.24, 37.33, 37.25, 37.16, 37.16, 37.13, 37.21, 37.16, 37.27, 37.11, 37.11, 37.15]',37.27,65.80,1,35,0,'2026-11-06 17:28:09','2026-11-06 17:28:09'),(2,1,'[37.25, 37.18, 37.4, 37.36, 37.26, 37.22, 37.33, 37.29, 37.42, 37.4, 37.46, 37.48, 37.37, 37.37, 37.33, 37.36, 37.33, 37.51, 37.58, 37.61, 37.41, 37.57, 37.49, 37.36, 37.27, 37.55, 37.41, 37.7, 37.51, 37.4, 37.46, 37.35, 37.44, 37.52, 37.52, 37.48, 37.7, 37.41, 37.44, 37.41, 37.25, 37.33, 37.51, 37.59, 37.63, 37.48, 37.36, 37.22, 37.29, 37.48, 37.51, 37.54, 37.45, 37.3, 37.26, 37.38, 37.33, 37.18, 37.36, 37.39, 37.45, 37.38, 37.25, 37.25]',37.42,65.80,1,35,0,'2026-11-06 17:30:09','2026-11-06 17:30:09'),(3,1,'[37.29, 37.54, 37.59, 37.48, 37.58, 37.43, 37.51, 37.29, 37.43, 37.43, 37.61, 37.47, 37.55, 37.56, 37.48, 37.31, 37.59, 37.47, 37.64, 37.58, 37.55, 37.64, 37.53, 37.37, 37.47, 37.5, 37.57, 37.73, 37.81, 37.71, 37.66, 37.52, 37.44, 37.5, 37.72, 37.69, 37.75, 37.76, 37.49, 37.4, 37.56, 37.46, 37.71, 37.73, 37.71, 37.67, 37.55, 37.36, 37.4, 37.6, 37.44, 37.5, 37.57, 37.61, 37.43, 37.34, 37.25, 37.49, 37.45, 37.4, 37.38, 37.5, 37.53, 37.44]',37.55,67.00,0,55,0,'2026-11-06 17:32:09','2026-11-06 17:32:09'),(4,1,'[37.39, 37.45, 37.63, 37.52, 37.47, 37.63, 37.53, 37.34, 37.52, 37.61, 37.5, 37.74, 37.55, 37.62, 37.46, 37.39, 37.59, 37.51, 37.58, 37.58, 37.71, 37.76, 37.51, 37.6, 37.58, 37.64, 37.8, 37.85, 37.88, 37.6, 37.74, 37.67, 37.56, 37.57, 37.61, 37.73, 37.77, 37.72, 37.63, 37.58, 37.47, 37.59, 37.68, 37.69, 37.6, 37.71, 37.64, 37.53, 37.58, 37.44, 37.51, 37.62, 37.74, 37.49, 37.45, 37.47, 37.4, 37.46, 37.6, 37.55, 37.63, 37.42, 37.43, 37.4]',37.61,65.50,0,55,0,'2026-11-06 17:34:09','2026-11-06 17:34:09'),(5,1,'[37.41, 37.5, 37.61, 37.62, 37.56, 37.58, 37.45, 37.32, 37.57, 37.51, 37.61, 37.66, 37.66, 37.61, 37.45, 37.56, 37.5, 37.66, 37.63, 37.78, 37.68, 37.67, 37.57, 37.57, 37.46, 37.58, 37.6, 37.85, 37.78, 37.77, 37.55, 37.44, 37.48, 37.65, 37.58, 37.73, 37.75, 37.69, 37.51, 37.45, 37.62, 37.52, 37.61, 37.8, 37.78, 37.71, 37.46, 37.48, 37.43, 37.45, 37.49, 37.65, 37.59, 37.54, 37.48, 37.42, 37.37, 37.57, 37.48, 37.46, 37.56, 37.49, 37.43, 37.35]',37.59,66.70,0,55,0,'2026-11-06 17:36:09','2026-11-06 17:36:09'),(6,1,'[37.52, 37.75, 37.79, 37.67, 37.81, 37.77, 37.7, 37.72, 37.66, 37.79, 37.77, 37.94, 37.82, 37.77, 37.74, 37.75, 37.75, 37.83, 37.77, 37.86, 37.97, 37.77, 37.75, 37.7, 37.72, 37.84, 37.91, 38.06, 37.88, 37.78, 37.74, 37.76, 37.7, 37.84, 37.96, 37.92, 38.07, 37.94, 37.73, 37.7, 37.7, 37.73, 37.76, 37.91, 37.83, 37.95, 37.72, 37.66, 37.6, 37.84, 37.69, 37.9, 37.87, 37.75, 37.69, 37.74, 37.52, 37.79, 37.79, 37.81, 37.86, 37.7, 37.57, 37.65]',37.80,67.50,0,55,0,'2026-11-06 17:38:09','2026-11-06 17:38:09'),(7,1,'[37.49, 37.61, 37.69, 37.67, 37.76, 37.79, 37.66, 37.63, 37.57, 37.85, 37.87, 37.72, 37.71, 37.78, 37.65, 37.79, 37.78, 37.85, 37.83, 37.86, 37.8, 37.88, 37.71, 37.69, 37.77, 37.76, 37.9, 37.86, 38.04, 37.85, 37.87, 37.64, 37.75, 37.72, 37.79, 38.03, 37.86, 37.82, 37.69, 37.62, 37.65, 37.68, 37.78, 37.99, 37.88, 37.75, 37.79, 37.77, 37.77, 37.83, 37.74, 37.75, 37.87, 37.81, 37.72, 37.61, 37.57, 37.61, 37.68, 37.66, 37.72, 37.72, 37.79, 37.64]',37.79,67.40,0,55,0,'2026-11-06 17:40:09','2026-11-06 17:40:09'),(8,1,'[37.69, 37.58, 37.78, 37.76, 37.6, 37.79, 37.61, 37.62, 37.62, 37.73, 37.74, 37.9, 37.77, 37.86, 37.84, 37.61, 37.64, 37.74, 37.77, 37.97, 37.82, 37.79, 37.82, 37.76, 37.78, 37.87, 37.97, 38.06, 37.87, 37.95, 37.84, 37.78, 37.71, 37.72, 37.97, 37.84, 38, 37.86, 37.73, 37.62, 37.64, 37.69, 37.71, 37.91, 37.97, 37.9, 37.83, 37.69, 37.65, 37.72, 37.69, 37.83, 37.79, 37.65, 37.77, 37.59, 37.67, 37.64, 37.71, 37.71, 37.63, 37.73, 37.62, 37.71]',37.78,66.30,0,55,0,'2026-11-06 17:42:09','2026-11-06 17:42:09'),(9,1,'[37.5, 37.53, 37.64, 37.7, 37.76, 37.69, 37.67, 37.5, 37.63, 37.59, 37.72, 37.66, 37.75, 37.67, 37.63, 37.55, 37.73, 37.75, 37.74, 37.91, 37.79, 37.77, 37.63, 37.73, 37.63, 37.86, 37.74, 37.96, 37.81, 37.88, 37.69, 37.6, 37.58, 37.65, 37.83, 38, 37.93, 37.77, 37.8, 37.69, 37.76, 37.67, 37.84, 37.82, 37.91, 37.66, 37.8, 37.67, 37.62, 37.57, 37.73, 37.64, 37.79, 37.63, 37.57, 37.53, 37.49, 37.7, 37.59, 37.78, 37.58, 37.61, 37.57, 37.59]',37.72,65.50,0,55,0,'2026-11-06 17:44:09','2026-11-06 17:44:09'),(10,1,'[37.4, 37.56, 37.42, 37.5, 37.47, 37.55, 37.47, 37.33, 37.53, 37.57, 37.53, 37.55, 37.66, 37.67, 37.39, 37.46, 37.47, 37.67, 37.74, 37.55, 37.57, 37.61, 37.49, 37.4, 37.48, 37.67, 37.72, 37.8, 37.61, 37.7, 37.66, 37.54, 37.43, 37.47, 37.63, 37.84, 37.85, 37.61, 37.5, 37.45, 37.47, 37.65, 37.53, 37.64, 37.74, 37.52, 37.48, 37.47, 37.42, 37.45, 37.59, 37.7, 37.48, 37.56, 37.49, 37.37, 37.3, 37.41, 37.52, 37.6, 37.61, 37.55, 37.34, 37.36]',37.57,65.20,0,55,0,'2026-11-06 17:46:09','2026-11-06 17:46:09'),(11,1,'[37.6, 37.57, 37.55, 37.53, 37.57, 37.54, 37.59, 37.43, 37.64, 37.59, 37.57, 37.69, 37.81, 37.62, 37.56, 37.58, 37.53, 37.6, 37.75, 37.78, 37.88, 37.61, 37.74, 37.58, 37.63, 37.74, 37.81, 37.75, 37.82, 37.83, 37.72, 37.66, 37.58, 37.7, 37.73, 37.82, 37.78, 37.83, 37.81, 37.62, 37.54, 37.61, 37.72, 37.76, 37.65, 37.77, 37.77, 37.54, 37.48, 37.64, 37.71, 37.74, 37.6, 37.55, 37.65, 37.45, 37.4, 37.45, 37.48, 37.63, 37.53, 37.63, 37.48, 37.37]',37.67,64.40,0,55,0,'2026-11-06 17:48:09','2026-11-06 17:48:09'),(12,1,'[37.39, 37.46, 37.3, 37.3, 37.48, 37.27, 37.46, 37.17, 37.38, 37.44, 37.43, 37.47, 37.57, 37.51, 37.34, 37.29, 37.43, 37.48, 37.46, 37.5, 37.65, 37.59, 37.48, 37.27, 37.43, 37.48, 37.47, 37.68, 37.71, 37.49, 37.53, 37.44, 37.37, 37.57, 37.5, 37.71, 37.67, 37.59, 37.56, 37.41, 37.38, 37.53, 37.63, 37.44, 37.46, 37.5, 37.47, 37.32, 37.41, 37.36, 37.44, 37.5, 37.55, 37.4, 37.31, 37.23, 37.36, 37.36, 37.31, 37.48, 37.49, 37.47, 37.3, 37.2]',37.46,65.20,1,35,0,'2026-11-06 17:50:09','2026-11-06 17:50:09'),(13,1,'[37.07, 37.29, 37.34, 37.24, 37.19, 37.16, 37.29, 37.18, 37.25, 37.3, 37.23, 37.25, 37.27, 37.24, 37.3, 37.33, 37.24, 37.3, 37.39, 37.47, 37.53, 37.45, 37.25, 37.23, 37.27, 37.3, 37.56, 37.49, 37.44, 37.49, 37.26, 37.38, 37.23, 37.44, 37.39, 37.58, 37.39, 37.52, 37.36, 37.19, 37.37, 37.32, 37.46, 37.41, 37.38, 37.49, 37.38, 37.35, 37.17, 37.26, 37.27, 37.46, 37.47, 37.26, 37.4, 37.3, 37.15, 37.28, 37.34, 37.23, 37.22, 37.38, 37.18, 37.18]',37.35,64.30,1,35,0,'2026-11-06 17:52:09','2026-11-06 17:52:09'),(14,1,'[37.3, 37.34, 37.38, 37.29, 37.33, 37.24, 37.39, 37.28, 37.22, 37.37, 37.29, 37.49, 37.38, 37.5, 37.34, 37.4, 37.29, 37.42, 37.5, 37.5, 37.55, 37.47, 37.35, 37.28, 37.32, 37.32, 37.43, 37.57, 37.55, 37.53, 37.51, 37.47, 37.42, 37.43, 37.63, 37.59, 37.52, 37.47, 37.41, 37.38, 37.33, 37.46, 37.42, 37.6, 37.6, 37.37, 37.39, 37.44, 37.29, 37.47, 37.31, 37.32, 37.4, 37.52, 37.44, 37.42, 37.24, 37.38, 37.28, 37.26, 37.26, 37.38, 37.38, 37.19]',37.42,64.40,1,35,0,'2026-11-06 17:54:09','2026-11-06 17:54:09'),(15,1,'[37.16, 37.34, 37.35, 37.41, 37.29, 37.34, 37.24, 37.24, 37.18, 37.31, 37.28, 37.3, 37.53, 37.39, 37.23, 37.22, 37.27, 37.37, 37.52, 37.44, 37.46, 37.49, 37.3, 37.24, 37.43, 37.36, 37.48, 37.48, 37.53, 37.53, 37.39, 37.31, 37.3, 37.45, 37.55, 37.65, 37.47, 37.6, 37.34, 37.34, 37.27, 37.38, 37.51, 37.44, 37.46, 37.47, 37.38, 37.4, 37.27, 37.28, 37.47, 37.31, 37.46, 37.43, 37.26, 37.39, 37.34, 37.28, 37.25, 37.25, 37.4, 37.22, 37.17, 37.22]',37.40,64.00,1,35,0,'2026-11-06 17:56:09','2026-11-06 17:56:09'),(16,1,'[37.1, 37.18, 37.24, 37.17, 37.29, 37.11, 37.17, 37.1, 37.28, 37.32, 37.31, 37.3, 37.26, 37.25, 37.21, 37.05, 37.33, 37.39, 37.43, 37.45, 37.38, 37.24, 37.4, 37.28, 37.12, 37.2, 37.47, 37.57, 37.37, 37.41, 37.33, 37.34, 37.12, 37.43, 37.42, 37.41, 37.57, 37.45, 37.38, 37.24, 37.27, 37.32, 37.37, 37.42, 37.37, 37.46, 37.26, 37.27, 37.14, 37.28, 37.19, 37.31, 37.33, 37.39, 37.28, 37.12, 37.22, 37.26, 37.24, 37.27, 37.31, 37.25, 37.16, 37.13]',37.29,63.30,1,35,0,'2026-11-06 17:58:09','2026-11-06 17:58:09'),(17,1,'[36.96, 37.18, 37.1, 37.14, 37.04, 37.18, 37.18, 37.16, 37.18, 37.1, 37.13, 37.26, 37.3, 37.23, 37.06, 37.1, 37.22, 37.24, 37.27, 37.33, 37.24, 37.23, 37.29, 37.19, 37.14, 37.24, 37.19, 37.29, 37.39, 37.39, 37.22, 37.14, 37.21, 37.17, 37.28, 37.42, 37.27, 37.35, 37.2, 37.16, 37.18, 37.09, 37.37, 37.39, 37.22, 37.36, 37.2, 37.13, 37, 37.05, 37.28, 37.19, 37.33, 37.31, 37.13, 37.13, 37.14, 37.16, 37.03, 37.13, 37.25, 37.22, 37.07, 37]',37.22,64.00,1,35,0,'2026-11-06 18:00:09','2026-11-06 18:00:09'),(18,1,'[36.95, 37.21, 37.21, 37.16, 37.21, 37.06, 37.1, 37.15, 37.1, 37.18, 37.17, 37.37, 37.34, 37.1, 37.06, 37.1, 37.14, 37.22, 37.4, 37.28, 37.31, 37.35, 37.2, 37.12, 37.09, 37.33, 37.2, 37.49, 37.36, 37.39, 37.25, 37.08, 37.09, 37.26, 37.38, 37.32, 37.27, 37.29, 37.36, 37.2, 37.05, 37.31, 37.38, 37.27, 37.42, 37.31, 37.1, 37.13, 37.1, 37.1, 37.28, 37.27, 37.37, 37.34, 37.12, 37.11, 37.12, 37.22, 37.18, 37.22, 37.24, 37.09, 37.21, 37.1]',37.23,63.80,1,35,0,'2026-11-06 18:02:09','2026-11-06 18:02:09'),(19,1,'[37.24, 37.25, 37.39, 37.24, 37.38, 37.35, 37.19, 37.08, 37.19, 37.21, 37.24, 37.33, 37.33, 37.25, 37.23, 37.35, 37.26, 37.32, 37.5, 37.47, 37.47, 37.52, 37.32, 37.21, 37.26, 37.47, 37.44, 37.59, 37.48, 37.32, 37.4, 37.2, 37.22, 37.41, 37.32, 37.5, 37.54, 37.48, 37.25, 37.38, 37.29, 37.34, 37.29, 37.4, 37.36, 37.3, 37.4, 37.26, 37.14, 37.19, 37.42, 37.32, 37.32, 37.4, 37.25, 37.26, 37.26, 37.32, 37.17, 37.34, 37.24, 37.37, 37.12, 37.17]',37.35,62.70,1,35,0,'2026-11-06 18:04:09','2026-11-06 18:04:09'),(20,1,'[37.18, 37.36, 37.41, 37.3, 37.32, 37.21, 37.35, 37.15, 37.15, 37.31, 37.31, 37.38, 37.52, 37.45, 37.35, 37.39, 37.29, 37.28, 37.54, 37.39, 37.44, 37.4, 37.43, 37.39, 37.36, 37.35, 37.37, 37.61, 37.59, 37.49, 37.36, 37.29, 37.42, 37.37, 37.44, 37.66, 37.52, 37.46, 37.31, 37.41, 37.39, 37.45, 37.44, 37.49, 37.47, 37.55, 37.27, 37.21, 37.18, 37.42, 37.49, 37.31, 37.4, 37.26, 37.29, 37.15, 37.31, 37.27, 37.19, 37.25, 37.44, 37.22, 37.37, 37.12]',37.39,64.20,1,35,0,'2026-11-06 18:06:09','2026-11-06 18:06:09'),(21,1,'[37.32, 37.21, 37.24, 37.31, 37.32, 37.41, 37.37, 37.28, 37.32, 37.32, 37.52, 37.39, 37.55, 37.37, 37.3, 37.31, 37.36, 37.51, 37.47, 37.54, 37.59, 37.56, 37.48, 37.48, 37.39, 37.56, 37.63, 37.63, 37.5, 37.53, 37.51, 37.44, 37.41, 37.42, 37.64, 37.7, 37.71, 37.65, 37.45, 37.32, 37.27, 37.35, 37.45, 37.48, 37.41, 37.47, 37.49, 37.32, 37.24, 37.26, 37.4, 37.41, 37.36, 37.48, 37.37, 37.21, 37.32, 37.39, 37.36, 37.31, 37.42, 37.33, 37.3, 37.37]',37.44,63.90,1,35,0,'2026-11-06 18:08:09','2026-11-06 18:08:09'),(22,1,'[37.21, 37.46, 37.46, 37.51, 37.31, 37.34, 37.44, 37.37, 37.46, 37.33, 37.55, 37.52, 37.46, 37.52, 37.44, 37.44, 37.37, 37.35, 37.54, 37.68, 37.54, 37.59, 37.44, 37.51, 37.3, 37.57, 37.45, 37.7, 37.73, 37.5, 37.42, 37.52, 37.37, 37.51, 37.57, 37.72, 37.68, 37.57, 37.5, 37.34, 37.41, 37.51, 37.44, 37.57, 37.51, 37.57, 37.36, 37.3, 37.34, 37.41, 37.38, 37.52, 37.52, 37.35, 37.37, 37.3, 37.38, 37.28, 37.48, 37.46, 37.48, 37.29, 37.46, 37.38]',37.47,63.60,1,35,0,'2026-11-06 18:10:09','2026-11-06 18:10:09'),(23,1,'[37.62, 37.56, 37.63, 37.58, 37.58, 37.58, 37.68, 37.59, 37.55, 37.69, 37.59, 37.82, 37.73, 37.73, 37.68, 37.54, 37.54, 37.58, 37.85, 37.82, 37.8, 37.66, 37.74, 37.53, 37.58, 37.66, 37.79, 37.96, 37.95, 37.79, 37.82, 37.64, 37.59, 37.77, 37.77, 37.9, 37.78, 37.86, 37.65, 37.57, 37.7, 37.68, 37.79, 37.73, 37.71, 37.73, 37.75, 37.66, 37.66, 37.54, 37.63, 37.78, 37.72, 37.75, 37.68, 37.47, 37.54, 37.64, 37.57, 37.73, 37.65, 37.64, 37.66, 37.52]',37.70,64.90,0,55,0,'2026-11-06 18:12:09','2026-11-06 18:12:09'),(24,1,'[37.58, 37.71, 37.78, 37.76, 37.65, 37.65, 37.63, 37.5, 37.6, 37.57, 37.84, 37.81, 37.81, 37.85, 37.66, 37.55, 37.63, 37.76, 37.81, 37.82, 37.77, 37.82, 37.71, 37.61, 37.67, 37.68, 37.8, 37.83, 37.93, 37.81, 37.72, 37.61, 37.59, 37.82, 37.81, 37.89, 37.84, 37.84, 37.74, 37.73, 37.64, 37.73, 37.76, 37.89, 37.83, 37.9, 37.62, 37.72, 37.58, 37.72, 37.75, 37.87, 37.78, 37.68, 37.7, 37.5, 37.6, 37.54, 37.72, 37.7, 37.58, 37.55, 37.52, 37.58]',37.74,65.60,0,55,0,'2026-11-06 18:14:09','2026-11-06 18:14:09'),(25,1,'[37.44, 37.49, 37.74, 37.76, 37.71, 37.75, 37.54, 37.49, 37.54, 37.74, 37.59, 37.77, 37.8, 37.77, 37.64, 37.66, 37.69, 37.82, 37.8, 37.8, 37.91, 37.69, 37.62, 37.67, 37.64, 37.7, 37.82, 37.91, 37.76, 37.84, 37.72, 37.64, 37.69, 37.72, 37.76, 37.95, 37.94, 37.9, 37.63, 37.58, 37.63, 37.71, 37.87, 37.91, 37.91, 37.65, 37.79, 37.53, 37.51, 37.72, 37.7, 37.85, 37.62, 37.81, 37.61, 37.57, 37.43, 37.68, 37.6, 37.55, 37.76, 37.68, 37.57, 37.54]',37.71,65.30,0,55,0,'2026-11-06 18:16:09','2026-11-06 18:16:09'),(26,1,'[37.38, 37.43, 37.51, 37.63, 37.59, 37.7, 37.54, 37.44, 37.58, 37.69, 37.74, 37.7, 37.75, 37.75, 37.69, 37.43, 37.5, 37.62, 37.62, 37.73, 37.76, 37.78, 37.72, 37.69, 37.72, 37.59, 37.76, 37.88, 37.84, 37.82, 37.63, 37.53, 37.58, 37.58, 37.78, 37.88, 37.77, 37.78, 37.66, 37.72, 37.69, 37.72, 37.76, 37.68, 37.79, 37.75, 37.67, 37.68, 37.42, 37.69, 37.54, 37.69, 37.66, 37.53, 37.71, 37.64, 37.55, 37.42, 37.62, 37.56, 37.51, 37.55, 37.65, 37.4]',37.66,65.60,0,55,0,'2026-11-06 18:18:09','2026-11-06 18:18:09'),(27,1,'[37.55, 37.43, 37.69, 37.5, 37.5, 37.57, 37.61, 37.45, 37.54, 37.65, 37.54, 37.68, 37.72, 37.54, 37.69, 37.58, 37.68, 37.61, 37.77, 37.67, 37.8, 37.72, 37.55, 37.62, 37.55, 37.57, 37.79, 37.75, 37.92, 37.84, 37.8, 37.6, 37.5, 37.6, 37.84, 37.94, 37.85, 37.85, 37.61, 37.55, 37.53, 37.72, 37.71, 37.66, 37.84, 37.7, 37.58, 37.66, 37.52, 37.57, 37.62, 37.78, 37.77, 37.76, 37.63, 37.49, 37.52, 37.65, 37.53, 37.64, 37.68, 37.58, 37.55, 37.6]',37.66,66.10,0,55,0,'2026-11-06 18:20:09','2026-11-06 18:20:09'),(28,1,'[37.48, 37.43, 37.6, 37.47, 37.59, 37.59, 37.38, 37.53, 37.49, 37.67, 37.67, 37.75, 37.68, 37.68, 37.55, 37.41, 37.6, 37.6, 37.73, 37.67, 37.67, 37.67, 37.52, 37.48, 37.64, 37.59, 37.62, 37.9, 37.73, 37.65, 37.6, 37.57, 37.66, 37.62, 37.6, 37.67, 37.78, 37.82, 37.55, 37.51, 37.45, 37.68, 37.58, 37.65, 37.78, 37.66, 37.69, 37.52, 37.47, 37.47, 37.53, 37.74, 37.69, 37.54, 37.55, 37.52, 37.43, 37.45, 37.46, 37.59, 37.5, 37.56, 37.52, 37.56]',37.62,65.60,0,55,0,'2026-11-06 18:22:09','2026-11-06 18:22:09'),(29,1,'[37.52, 37.61, 37.65, 37.7, 37.63, 37.66, 37.63, 37.45, 37.66, 37.52, 37.68, 37.67, 37.58, 37.77, 37.55, 37.52, 37.51, 37.76, 37.71, 37.68, 37.76, 37.7, 37.7, 37.55, 37.53, 37.71, 37.66, 37.73, 37.84, 37.81, 37.74, 37.61, 37.61, 37.65, 37.81, 37.73, 37.95, 37.87, 37.57, 37.68, 37.52, 37.66, 37.81, 37.74, 37.72, 37.72, 37.55, 37.68, 37.51, 37.55, 37.7, 37.59, 37.6, 37.54, 37.5, 37.66, 37.47, 37.45, 37.6, 37.49, 37.53, 37.69, 37.55, 37.6]',37.67,67.30,0,55,0,'2026-11-06 18:24:09','2026-11-06 18:24:09'),(30,1,'[37.56, 37.59, 37.49, 37.69, 37.64, 37.66, 37.54, 37.55, 37.62, 37.57, 37.57, 37.67, 37.72, 37.68, 37.6, 37.6, 37.63, 37.61, 37.61, 37.87, 37.75, 37.65, 37.68, 37.58, 37.61, 37.75, 37.67, 37.83, 37.72, 37.8, 37.59, 37.5, 37.5, 37.58, 37.79, 37.71, 37.71, 37.87, 37.75, 37.71, 37.54, 37.6, 37.67, 37.83, 37.77, 37.61, 37.71, 37.67, 37.62, 37.61, 37.55, 37.78, 37.77, 37.58, 37.64, 37.45, 37.42, 37.58, 37.68, 37.61, 37.57, 37.47, 37.53, 37.53]',37.67,67.00,0,55,0,'2026-11-06 18:26:09','2026-11-06 18:26:09'),(31,1,'[37.44, 37.3, 37.47, 37.55, 37.38, 37.37, 37.33, 37.21, 37.48, 37.5, 37.43, 37.55, 37.44, 37.61, 37.48, 37.38, 37.54, 37.37, 37.56, 37.56, 37.59, 37.55, 37.6, 37.32, 37.54, 37.56, 37.59, 37.78, 37.68, 37.47, 37.6, 37.47, 37.51, 37.4, 37.56, 37.64, 37.63, 37.71, 37.63, 37.45, 37.49, 37.59, 37.61, 37.71, 37.56, 37.6, 37.4, 37.46, 37.47, 37.38, 37.57, 37.61, 37.5, 37.59, 37.47, 37.48, 37.38, 37.28, 37.42, 37.4, 37.39, 37.32, 37.46, 37.41]',37.50,66.40,0,55,0,'2026-11-06 18:28:09','2026-11-06 18:28:09');
/*!40000 ALTER TABLE `sensor_readings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('owner','buyer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'buyer',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Don Palao (Farm Owner)','owner@smartpalao.com',NULL,'$2y$12$gnhSGwwP0vuXt22.PsxnQe0OLoPygREhB4noWdMQXTQAyIQ4x8iLC','owner','+63 917 888 2026','Palao Farmstead, Roxas, Oriental Mindoro, Philippines',NULL,'2026-11-06 18:28:08','2026-11-06 18:28:08'),(2,'Maria Santos (Egg Buyer)','buyer@gmail.com',NULL,'$2y$12$co1VnKtRuCu2WtsoHcI3VuItYaL/aQt8i2pKOCXaOi6ZUKixGtipa','buyer','+63 928 555 7788','Villa de Oro Beach Resort, Station 2, Boracay Island, Malay, Aklan',NULL,'2026-11-06 18:28:09','2026-11-06 18:28:09');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vitality_logs`
--

DROP TABLE IF EXISTS `vitality_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vitality_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `chamber_id` bigint unsigned NOT NULL,
  `batch_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BATCH-2026-D01',
  `grid_position` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Day 14 Candling Inspection',
  `result` enum('viable','non-viable') COLLATE utf8mb4_unicode_ci NOT NULL,
  `candling_day` smallint unsigned DEFAULT '14',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vitality_logs_chamber_id_foreign` (`chamber_id`),
  CONSTRAINT `vitality_logs_chamber_id_foreign` FOREIGN KEY (`chamber_id`) REFERENCES `chambers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vitality_logs`
--

LOCK TABLES `vitality_logs` WRITE;
/*!40000 ALTER TABLE `vitality_logs` DISABLE KEYS */;
INSERT INTO `vitality_logs` VALUES (1,1,'BATCH-2026-D01','Day 14 Mid-Term Candling (Batch Assessment)','viable',14,'ESP32-CAM optical candling confirms 95% viable embryo development with robust chorioallantoic vascular networks across the clutch.','2026-11-06 18:28:09','2026-11-06 18:28:09'),(2,1,'BATCH-2026-D01','Day 10 Vascular Web Audit','viable',10,'Strong spider blood vessel branches clearly visible under candling backlight. Air cells well-formed and stable.','2026-11-06 18:28:09','2026-11-06 18:28:09'),(3,1,'BATCH-2026-D01','Day 7 Fertility Screening (Initial Candling)','viable',7,'Embryonic heart pulsation confirmed under camera magnification. 6 infertile / clear eggs culled from tray.','2026-11-06 18:28:09','2026-11-06 18:28:09'),(4,1,'BATCH-2026-D01','Day 3 Pre-Incubation Quality Check','viable',3,'Egg shell integrity and membrane density verified under white LED inspection before setter phase.','2026-11-06 18:28:09','2026-11-06 18:28:09');
/*!40000 ALTER TABLE `vitality_logs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-07 10:28:57

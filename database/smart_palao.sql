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
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Gloria (Main Farm)',
  `setpoint` decimal(4,2) NOT NULL DEFAULT '37.50',
  `critical_temp` decimal(4,2) NOT NULL DEFAULT '38.50',
  `heater_state` tinyint(1) NOT NULL DEFAULT '0',
  `fan_pwm` tinyint unsigned NOT NULL DEFAULT '50',
  `mode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'AUTO',
  `batch_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'BATCH-2026-D01',
  `batch_day` smallint unsigned NOT NULL DEFAULT '14',
  `duck_breed` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pateros Mallard (Itik Pinas)',
  `egg_count` int unsigned NOT NULL DEFAULT '120',
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
INSERT INTO `chambers` VALUES (1,'Incubator Alpha (Gloria Main)','Gloria (Main Farm)',37.50,38.50,1,45,'AUTO','BATCH-2026-D01',14,'Pateros Mallard (Itik Pinas)',120,'palao_esp32_secret_key_2026','2026-11-03 03:42:10','2026-11-03 03:42:10'),(2,'Hatcher Beta (Gloria Unit 2)','Gloria (Main Farm)',37.00,38.20,0,70,'AUTO','BATCH-2026-D02',26,'Pateros Mallard (Itik Pinas)',96,'palao_esp32_beta_secret','2026-11-03 03:42:10','2026-11-03 03:42:10');
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
INSERT INTO `order_items` VALUES (1,1,1,'Large',180.00,2,360.00,'2026-11-03 03:42:10','2026-11-03 03:42:10'),(2,1,2,'Medium',150.00,1,150.00,'2026-11-03 03:42:10','2026-11-03 03:42:10'),(3,2,4,'Large',210.00,6,1260.00,'2026-11-03 03:42:10','2026-11-03 03:42:10'),(4,3,5,'Medium',180.00,2,360.00,'2026-11-03 03:42:10','2026-11-03 03:42:10'),(5,3,6,'Small',145.00,2,290.00,'2026-11-03 03:42:10','2026-11-03 03:42:10'),(6,4,2,'Medium',150.00,1,150.00,'2026-11-03 03:42:10','2026-11-03 03:42:10'),(7,4,1,'Large',180.00,1,180.00,'2026-11-03 03:42:10','2026-11-03 03:42:10');
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
  `branch_location` enum('Gloria','Boracay') COLLATE utf8mb4_unicode_ci NOT NULL,
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
INSERT INTO `orders` VALUES (1,'SP-GLO-20261103-A101',2,'Gloria','Maria Santos','buyer@gmail.com','+63 928 555 7788','Barangay Maligaya, Gloria, Oriental Mindoro','GCash','Pending',510.00,50.00,'Please leave with security if not home. Call upon arrival.','2026-11-03 03:42:10','2026-11-03 03:42:10'),(2,'SP-BOR-20261103-B202',NULL,'Boracay','Boracay Haven Resort Kitchen','hotel@boracayhaven.com','+63 919 444 8822','Station 2 Beachfront, Boracay Island','Bank Transfer','Processing',1260.00,75.00,'For morning breakfast buffet service. Deliver by 7:00 AM.','2026-11-03 03:42:10','2026-11-03 03:42:10'),(3,'SP-BOR-20261102-C303',NULL,'Boracay','Chef Alejandro Cruz','chef@boracaygourmet.ph','+63 917 333 1199','Diniwid Road, Barangay Yapak, Boracay','Cash on Delivery','Shipped',705.00,75.00,'Rider is on electric trike courier route.','2026-11-03 03:42:10','2026-11-03 03:42:10'),(4,'SP-GLO-20261101-D404',2,'Gloria','Maria Santos','buyer@gmail.com','+63 928 555 7788','Barangay Maligaya, Gloria, Oriental Mindoro','Cash on Delivery','Delivered',330.00,50.00,'Successfully delivered to front gate.','2026-11-03 03:42:10','2026-11-03 03:42:10');
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
  `branch_location` enum('Gloria','Boracay') COLLATE utf8mb4_unicode_ci NOT NULL,
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
INSERT INTO `products` VALUES (1,'Gloria','Large',180.00,180,'65g - 75g per egg','Grade-A jumbo duck eggs from free-range Gloria farm paddocks. Superb yolk density, ideal for gourmet cooking, salting, or premium balut.','/images/egg-large.png','2026-11-03 03:42:10','2026-11-03 03:42:10'),(2,'Gloria','Medium',150.00,260,'55g - 64g per egg','Standard premium duck eggs carefully calibrated in our IoT incubation hatchery for peak membrane freshness and fertility.','/images/egg-medium.png','2026-11-03 03:42:10','2026-11-03 03:42:10'),(3,'Gloria','Small',120.00,110,'45g - 54g per egg','Nutrient-rich pullet duck eggs from young hens. Excellent value pack for daily farm-to-table breakfast and baking.','/images/egg-small.png','2026-11-03 03:42:10','2026-11-03 03:42:10'),(4,'Boracay','Large',210.00,85,'65g - 75g per egg','Island-fulfilled premium jumbo duck eggs delivered fresh daily to Boracay resorts, restaurants, and residents.','/images/egg-large.png','2026-11-03 03:42:10','2026-11-03 03:42:10'),(5,'Boracay','Medium',180.00,140,'55g - 64g per egg','Fresh island stock of medium duck eggs with rich golden yolk. Sourced directly from our Gloria incubation hub.','/images/egg-medium.png','2026-11-03 03:42:10','2026-11-03 03:42:10'),(6,'Boracay','Small',145.00,60,'45g - 54g per egg','Fresh pullet duck eggs available for express island drop-off across Boracay Stations 1, 2, and 3.','/images/egg-small.png','2026-11-03 03:42:10','2026-11-03 03:42:10');
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
  `thermal_grid_json` json NOT NULL,
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
INSERT INTO `sensor_readings` VALUES (1,1,'[37.09, 37.23, 37.21, 37.4, 37.24, 37.24, 37.13, 37.14, 37.23, 37.35, 37.38, 37.44, 37.31, 37.44, 37.26, 37.32, 37.27, 37.3, 37.39, 37.38, 37.48, 37.5, 37.37, 37.18, 37.24, 37.41, 37.31, 37.4, 37.41, 37.35, 37.4, 37.35, 37.26, 37.26, 37.52, 37.39, 37.6, 37.45, 37.46, 37.24, 37.26, 37.45, 37.49, 37.51, 37.42, 37.36, 37.28, 37.38, 37.18, 37.33, 37.39, 37.28, 37.34, 37.37, 37.19, 37.3, 37.19, 37.14, 37.33, 37.18, 37.37, 37.29, 37.24, 37.25]',37.34,66.10,1,35,0,'2026-11-03 02:42:10','2026-11-03 02:42:10'),(2,1,'[37.24, 37.35, 37.39, 37.28, 37.36, 37.28, 37.32, 37.35, 37.29, 37.45, 37.38, 37.34, 37.45, 37.33, 37.36, 37.41, 37.39, 37.51, 37.47, 37.53, 37.52, 37.53, 37.28, 37.27, 37.29, 37.39, 37.62, 37.67, 37.68, 37.56, 37.36, 37.34, 37.36, 37.46, 37.39, 37.68, 37.51, 37.54, 37.48, 37.44, 37.33, 37.41, 37.35, 37.51, 37.45, 37.42, 37.47, 37.21, 37.21, 37.31, 37.47, 37.36, 37.54, 37.48, 37.25, 37.33, 37.26, 37.25, 37.29, 37.23, 37.32, 37.23, 37.26, 37.28]',37.41,66.00,1,35,0,'2026-11-03 02:44:10','2026-11-03 02:44:10'),(3,1,'[37.33, 37.36, 37.46, 37.48, 37.4, 37.36, 37.29, 37.2, 37.29, 37.44, 37.46, 37.62, 37.57, 37.59, 37.51, 37.36, 37.5, 37.39, 37.52, 37.46, 37.6, 37.59, 37.57, 37.48, 37.48, 37.51, 37.66, 37.65, 37.56, 37.64, 37.6, 37.38, 37.4, 37.5, 37.69, 37.72, 37.67, 37.63, 37.51, 37.39, 37.28, 37.51, 37.44, 37.57, 37.5, 37.62, 37.45, 37.33, 37.33, 37.4, 37.37, 37.57, 37.46, 37.42, 37.47, 37.43, 37.22, 37.43, 37.36, 37.37, 37.32, 37.28, 37.29, 37.23]',37.48,65.80,1,35,0,'2026-11-03 02:46:10','2026-11-03 02:46:10'),(4,1,'[37.57, 37.45, 37.56, 37.62, 37.67, 37.63, 37.6, 37.5, 37.44, 37.61, 37.59, 37.59, 37.77, 37.56, 37.52, 37.58, 37.5, 37.69, 37.63, 37.71, 37.8, 37.72, 37.66, 37.45, 37.55, 37.63, 37.65, 37.82, 37.88, 37.62, 37.77, 37.62, 37.5, 37.77, 37.62, 37.71, 37.87, 37.77, 37.63, 37.56, 37.46, 37.63, 37.67, 37.63, 37.62, 37.66, 37.59, 37.47, 37.53, 37.49, 37.63, 37.62, 37.67, 37.56, 37.67, 37.47, 37.35, 37.46, 37.53, 37.6, 37.63, 37.65, 37.51, 37.57]',37.63,66.20,0,55,0,'2026-11-03 02:48:10','2026-11-03 02:48:10'),(5,1,'[37.37, 37.54, 37.44, 37.43, 37.61, 37.63, 37.55, 37.48, 37.5, 37.44, 37.52, 37.53, 37.5, 37.54, 37.48, 37.51, 37.51, 37.47, 37.65, 37.76, 37.61, 37.54, 37.65, 37.52, 37.43, 37.68, 37.66, 37.76, 37.66, 37.59, 37.59, 37.55, 37.65, 37.67, 37.72, 37.74, 37.74, 37.62, 37.59, 37.57, 37.4, 37.51, 37.66, 37.57, 37.56, 37.68, 37.63, 37.49, 37.53, 37.49, 37.55, 37.56, 37.53, 37.68, 37.52, 37.51, 37.39, 37.52, 37.4, 37.58, 37.55, 37.5, 37.46, 37.41]',37.59,66.50,0,55,0,'2026-11-03 02:50:10','2026-11-03 02:50:10'),(6,1,'[37.51, 37.61, 37.66, 37.68, 37.77, 37.81, 37.77, 37.67, 37.75, 37.61, 37.74, 37.85, 37.83, 37.68, 37.67, 37.74, 37.69, 37.67, 37.79, 37.87, 37.93, 37.87, 37.81, 37.67, 37.65, 37.86, 38, 37.95, 37.83, 37.8, 37.74, 37.81, 37.85, 37.9, 37.98, 38, 37.84, 37.98, 37.75, 37.79, 37.72, 37.84, 37.87, 37.85, 37.98, 37.73, 37.85, 37.7, 37.55, 37.81, 37.82, 37.91, 37.82, 37.73, 37.77, 37.56, 37.73, 37.62, 37.73, 37.69, 37.75, 37.76, 37.71, 37.71]',37.79,66.10,0,55,0,'2026-11-03 02:52:10','2026-11-03 02:52:10'),(7,1,'[37.53, 37.45, 37.6, 37.64, 37.5, 37.61, 37.53, 37.55, 37.5, 37.6, 37.54, 37.6, 37.8, 37.69, 37.57, 37.62, 37.52, 37.67, 37.73, 37.75, 37.65, 37.63, 37.59, 37.53, 37.7, 37.77, 37.83, 37.76, 37.9, 37.85, 37.77, 37.71, 37.71, 37.71, 37.65, 37.92, 37.85, 37.75, 37.62, 37.71, 37.68, 37.64, 37.81, 37.72, 37.8, 37.82, 37.61, 37.65, 37.58, 37.71, 37.61, 37.77, 37.72, 37.77, 37.71, 37.61, 37.52, 37.47, 37.49, 37.54, 37.73, 37.55, 37.55, 37.38]',37.67,65.60,0,55,0,'2026-11-03 02:54:10','2026-11-03 02:54:10'),(8,1,'[37.51, 37.63, 37.81, 37.87, 37.63, 37.67, 37.76, 37.67, 37.72, 37.66, 37.86, 37.8, 37.76, 37.86, 37.68, 37.63, 37.71, 37.88, 37.74, 37.96, 38.01, 37.92, 37.7, 37.69, 37.85, 37.85, 37.79, 37.96, 38, 37.88, 37.89, 37.84, 37.79, 37.82, 37.89, 37.85, 37.91, 37.88, 37.82, 37.72, 37.7, 37.92, 37.9, 37.91, 37.94, 37.83, 37.69, 37.66, 37.59, 37.69, 37.77, 37.72, 37.88, 37.69, 37.8, 37.59, 37.72, 37.64, 37.77, 37.79, 37.71, 37.77, 37.6, 37.64]',37.81,65.80,0,55,0,'2026-11-03 02:56:10','2026-11-03 02:56:10'),(9,1,'[37.65, 37.61, 37.62, 37.76, 37.78, 37.79, 37.54, 37.61, 37.69, 37.75, 37.66, 37.73, 37.73, 37.83, 37.79, 37.7, 37.63, 37.67, 37.77, 37.74, 37.78, 37.75, 37.74, 37.68, 37.63, 37.77, 37.89, 37.86, 37.81, 37.92, 37.67, 37.68, 37.75, 37.87, 37.74, 37.8, 37.97, 37.77, 37.68, 37.59, 37.71, 37.68, 37.71, 37.87, 37.87, 37.92, 37.64, 37.65, 37.52, 37.79, 37.76, 37.87, 37.86, 37.71, 37.71, 37.7, 37.64, 37.57, 37.77, 37.75, 37.75, 37.57, 37.65, 37.47]',37.76,65.70,0,55,0,'2026-11-03 02:58:10','2026-11-03 02:58:10'),(10,1,'[37.37, 37.58, 37.66, 37.57, 37.49, 37.51, 37.65, 37.46, 37.46, 37.51, 37.61, 37.61, 37.57, 37.55, 37.51, 37.66, 37.61, 37.71, 37.6, 37.87, 37.85, 37.73, 37.62, 37.67, 37.61, 37.75, 37.64, 37.9, 37.85, 37.67, 37.64, 37.6, 37.72, 37.77, 37.66, 37.85, 37.89, 37.86, 37.62, 37.55, 37.58, 37.62, 37.81, 37.8, 37.67, 37.77, 37.59, 37.53, 37.44, 37.66, 37.53, 37.67, 37.61, 37.68, 37.67, 37.65, 37.57, 37.62, 37.7, 37.63, 37.48, 37.56, 37.6, 37.54]',37.66,66.10,0,55,0,'2026-11-03 03:00:10','2026-11-03 03:00:10'),(11,1,'[37.57, 37.62, 37.48, 37.6, 37.45, 37.51, 37.45, 37.47, 37.55, 37.66, 37.6, 37.74, 37.53, 37.57, 37.45, 37.63, 37.58, 37.66, 37.58, 37.79, 37.77, 37.8, 37.63, 37.58, 37.49, 37.55, 37.74, 37.84, 37.74, 37.7, 37.7, 37.67, 37.49, 37.74, 37.81, 37.67, 37.84, 37.76, 37.71, 37.48, 37.5, 37.57, 37.71, 37.62, 37.69, 37.6, 37.63, 37.53, 37.55, 37.6, 37.68, 37.57, 37.54, 37.67, 37.45, 37.45, 37.36, 37.54, 37.48, 37.63, 37.45, 37.57, 37.52, 37.46]',37.63,64.90,0,55,0,'2026-11-03 03:02:10','2026-11-03 03:02:10'),(12,1,'[37.33, 37.42, 37.27, 37.44, 37.5, 37.5, 37.33, 37.34, 37.23, 37.32, 37.46, 37.57, 37.38, 37.57, 37.35, 37.29, 37.38, 37.41, 37.41, 37.44, 37.53, 37.46, 37.38, 37.47, 37.46, 37.39, 37.48, 37.71, 37.73, 37.66, 37.54, 37.52, 37.43, 37.44, 37.67, 37.72, 37.56, 37.45, 37.41, 37.32, 37.4, 37.46, 37.46, 37.56, 37.52, 37.58, 37.49, 37.36, 37.22, 37.49, 37.5, 37.5, 37.47, 37.33, 37.52, 37.43, 37.38, 37.22, 37.29, 37.48, 37.34, 37.47, 37.32, 37.36]',37.46,65.30,1,35,0,'2026-11-03 03:04:10','2026-11-03 03:04:10'),(13,1,'[37.16, 37.27, 37.42, 37.29, 37.22, 37.33, 37.21, 37.15, 37.3, 37.38, 37.31, 37.45, 37.46, 37.37, 37.42, 37.38, 37.36, 37.47, 37.42, 37.52, 37.55, 37.45, 37.47, 37.29, 37.39, 37.43, 37.42, 37.49, 37.5, 37.53, 37.47, 37.25, 37.23, 37.28, 37.5, 37.61, 37.63, 37.59, 37.41, 37.3, 37.4, 37.43, 37.33, 37.57, 37.37, 37.54, 37.27, 37.38, 37.15, 37.38, 37.34, 37.31, 37.39, 37.29, 37.32, 37.24, 37.21, 37.15, 37.23, 37.41, 37.39, 37.41, 37.37, 37.31]',37.38,63.70,1,35,0,'2026-11-03 03:06:10','2026-11-03 03:06:10'),(14,1,'[37.03, 37.1, 37.34, 37.17, 37.15, 37.17, 37.21, 37.05, 37.29, 37.37, 37.3, 37.36, 37.21, 37.23, 37.13, 37.3, 37.25, 37.23, 37.42, 37.31, 37.32, 37.31, 37.4, 37.26, 37.16, 37.23, 37.38, 37.36, 37.4, 37.35, 37.26, 37.24, 37.3, 37.37, 37.43, 37.54, 37.59, 37.3, 37.34, 37.32, 37.3, 37.39, 37.47, 37.39, 37.5, 37.26, 37.18, 37.24, 37.29, 37.31, 37.38, 37.43, 37.3, 37.19, 37.22, 37.13, 37.12, 37.18, 37.15, 37.16, 37.34, 37.11, 37.25, 37.19]',37.31,63.90,1,35,0,'2026-11-03 03:08:10','2026-11-03 03:08:10'),(15,1,'[37.04, 37.14, 37.19, 37.26, 37.29, 37.03, 37.17, 36.96, 37.21, 37.1, 37.14, 37.33, 37.2, 37.15, 37.06, 37.17, 37.03, 37.29, 37.27, 37.21, 37.2, 37.16, 37.32, 37.09, 37.18, 37.29, 37.43, 37.44, 37.37, 37.42, 37.2, 37.27, 37.22, 37.27, 37.34, 37.34, 37.4, 37.22, 37.36, 37.13, 37.03, 37.21, 37.25, 37.25, 37.23, 37.3, 37.29, 37.1, 37.21, 37.13, 37.32, 37.3, 37.19, 37.11, 37.16, 37.19, 37.1, 37.23, 37.09, 37.2, 37.16, 37.21, 37.04, 37.16]',37.23,64.90,1,35,0,'2026-11-03 03:10:10','2026-11-03 03:10:10'),(16,1,'[37.03, 37.03, 37.12, 37.29, 37.24, 37.1, 37.05, 37.19, 37.03, 37.13, 37.23, 37.35, 37.19, 37.26, 37.22, 37.15, 37.17, 37.22, 37.21, 37.35, 37.28, 37.27, 37.25, 37.29, 37.27, 37.27, 37.38, 37.37, 37.53, 37.25, 37.16, 37.32, 37.28, 37.18, 37.34, 37.38, 37.48, 37.39, 37.21, 37.22, 37.09, 37.29, 37.42, 37.37, 37.38, 37.26, 37.27, 37.3, 37.14, 37.18, 37.17, 37.4, 37.26, 37.36, 37.18, 37.12, 37.05, 37.24, 37.18, 37.12, 37.3, 37.07, 37.19, 36.96]',37.26,63.80,1,35,0,'2026-11-03 03:12:10','2026-11-03 03:12:10'),(17,1,'[36.97, 37.16, 37.07, 37.1, 37.14, 37.13, 37.1, 36.95, 37.08, 37.07, 37.33, 37.27, 37.39, 37.34, 37.11, 37.19, 37.24, 37.23, 37.24, 37.36, 37.44, 37.3, 37.12, 37.11, 37.24, 37.31, 37.23, 37.29, 37.51, 37.34, 37.27, 37.09, 37.12, 37.17, 37.23, 37.4, 37.4, 37.26, 37.26, 37.22, 37.15, 37.21, 37.4, 37.41, 37.31, 37.36, 37.36, 37.14, 37.13, 37.07, 37.31, 37.2, 37.16, 37.35, 37.27, 37.04, 37.15, 37.1, 37.25, 37.23, 37.25, 37.19, 37.23, 37.16]',37.25,64.40,1,35,0,'2026-11-03 03:14:10','2026-11-03 03:14:10'),(18,1,'[37.1, 37.32, 37.29, 37.36, 37.2, 37.15, 37.25, 37.24, 37.16, 37.37, 37.43, 37.38, 37.45, 37.22, 37.18, 37.22, 37.22, 37.44, 37.33, 37.32, 37.33, 37.35, 37.33, 37.23, 37.19, 37.36, 37.49, 37.41, 37.48, 37.56, 37.43, 37.37, 37.28, 37.36, 37.42, 37.59, 37.42, 37.48, 37.26, 37.22, 37.38, 37.33, 37.34, 37.37, 37.51, 37.36, 37.32, 37.24, 37.29, 37.18, 37.4, 37.47, 37.28, 37.22, 37.37, 37.35, 37.08, 37.26, 37.15, 37.18, 37.18, 37.2, 37.13, 37.2]',37.35,64.00,1,35,0,'2026-11-03 03:16:10','2026-11-03 03:16:10'),(19,1,'[37.17, 37.05, 37.2, 37.3, 37.32, 37.18, 37.14, 37.19, 37.11, 37.24, 37.21, 37.22, 37.33, 37.16, 37.16, 37.13, 37.23, 37.2, 37.38, 37.31, 37.4, 37.38, 37.36, 37.09, 37.21, 37.4, 37.32, 37.52, 37.55, 37.3, 37.26, 37.3, 37.18, 37.36, 37.41, 37.45, 37.31, 37.43, 37.22, 37.24, 37.2, 37.15, 37.42, 37.29, 37.37, 37.44, 37.32, 37.25, 37.14, 37.11, 37.28, 37.38, 37.23, 37.24, 37.09, 37.03, 37.15, 37.06, 37.24, 37.25, 37.28, 37.11, 37.19, 37.21]',37.27,62.70,1,35,0,'2026-11-03 03:18:10','2026-11-03 03:18:10'),(20,1,'[37.24, 37.28, 37.24, 37.33, 37.26, 37.26, 37.34, 37.21, 37.22, 37.23, 37.34, 37.4, 37.24, 37.36, 37.17, 37.17, 37.27, 37.25, 37.36, 37.44, 37.37, 37.43, 37.28, 37.2, 37.38, 37.44, 37.46, 37.47, 37.39, 37.44, 37.4, 37.32, 37.26, 37.28, 37.43, 37.44, 37.48, 37.53, 37.37, 37.33, 37.15, 37.29, 37.47, 37.4, 37.39, 37.35, 37.24, 37.33, 37.21, 37.38, 37.36, 37.47, 37.48, 37.4, 37.34, 37.21, 37.26, 37.14, 37.34, 37.22, 37.2, 37.35, 37.31, 37.13]',37.34,62.80,1,35,0,'2026-11-03 03:20:10','2026-11-03 03:20:10'),(21,1,'[37.38, 37.34, 37.27, 37.5, 37.4, 37.47, 37.2, 37.34, 37.44, 37.39, 37.48, 37.42, 37.47, 37.5, 37.49, 37.27, 37.41, 37.36, 37.59, 37.65, 37.5, 37.46, 37.35, 37.33, 37.41, 37.4, 37.54, 37.56, 37.54, 37.43, 37.36, 37.39, 37.4, 37.51, 37.63, 37.6, 37.56, 37.55, 37.48, 37.41, 37.27, 37.4, 37.43, 37.62, 37.54, 37.44, 37.46, 37.24, 37.43, 37.37, 37.33, 37.43, 37.48, 37.32, 37.43, 37.4, 37.19, 37.36, 37.27, 37.34, 37.32, 37.39, 37.32, 37.23]',37.44,63.50,1,35,0,'2026-11-03 03:22:10','2026-11-03 03:22:10'),(22,1,'[37.28, 37.43, 37.39, 37.56, 37.36, 37.35, 37.27, 37.23, 37.28, 37.47, 37.44, 37.5, 37.51, 37.62, 37.51, 37.28, 37.33, 37.41, 37.68, 37.62, 37.5, 37.52, 37.55, 37.45, 37.34, 37.63, 37.69, 37.77, 37.66, 37.55, 37.63, 37.35, 37.33, 37.63, 37.66, 37.79, 37.65, 37.55, 37.55, 37.42, 37.33, 37.4, 37.58, 37.64, 37.64, 37.67, 37.45, 37.39, 37.5, 37.33, 37.6, 37.47, 37.51, 37.43, 37.44, 37.46, 37.26, 37.27, 37.42, 37.55, 37.51, 37.37, 37.38, 37.37]',37.51,63.70,0,55,0,'2026-11-03 03:24:10','2026-11-03 03:24:10'),(23,1,'[37.54, 37.48, 37.44, 37.57, 37.56, 37.46, 37.57, 37.42, 37.53, 37.48, 37.64, 37.51, 37.66, 37.56, 37.51, 37.55, 37.51, 37.5, 37.67, 37.67, 37.68, 37.61, 37.55, 37.63, 37.49, 37.5, 37.67, 37.87, 37.85, 37.66, 37.63, 37.49, 37.53, 37.67, 37.66, 37.81, 37.65, 37.76, 37.7, 37.66, 37.45, 37.6, 37.55, 37.62, 37.8, 37.53, 37.63, 37.64, 37.36, 37.58, 37.61, 37.56, 37.68, 37.65, 37.5, 37.56, 37.34, 37.49, 37.6, 37.57, 37.44, 37.55, 37.49, 37.42]',37.60,64.50,0,55,0,'2026-11-03 03:26:10','2026-11-03 03:26:10'),(24,1,'[37.55, 37.78, 37.76, 37.64, 37.67, 37.7, 37.56, 37.53, 37.78, 37.68, 37.68, 37.75, 37.72, 37.66, 37.76, 37.59, 37.71, 37.82, 37.74, 37.92, 37.76, 37.88, 37.72, 37.77, 37.66, 37.77, 37.94, 38.02, 38.02, 37.77, 37.82, 37.62, 37.65, 37.81, 37.86, 37.87, 37.9, 37.88, 37.81, 37.78, 37.77, 37.71, 37.9, 37.84, 37.88, 37.95, 37.87, 37.63, 37.7, 37.66, 37.88, 37.86, 37.77, 37.81, 37.81, 37.72, 37.66, 37.65, 37.75, 37.81, 37.78, 37.81, 37.73, 37.53]',37.78,63.70,0,55,0,'2026-11-03 03:28:10','2026-11-03 03:28:10'),(25,1,'[37.46, 37.74, 37.68, 37.7, 37.64, 37.69, 37.5, 37.53, 37.52, 37.62, 37.74, 37.72, 37.66, 37.77, 37.74, 37.61, 37.78, 37.75, 37.74, 37.74, 37.8, 37.71, 37.68, 37.6, 37.57, 37.87, 37.95, 37.82, 37.87, 37.73, 37.67, 37.77, 37.73, 37.85, 37.83, 37.93, 37.96, 37.86, 37.73, 37.64, 37.61, 37.76, 37.84, 37.83, 37.83, 37.76, 37.83, 37.67, 37.74, 37.7, 37.64, 37.69, 37.85, 37.64, 37.79, 37.71, 37.5, 37.5, 37.56, 37.68, 37.69, 37.58, 37.54, 37.52]',37.74,64.80,0,55,0,'2026-11-03 03:30:10','2026-11-03 03:30:10'),(26,1,'[37.59, 37.63, 37.82, 37.82, 37.85, 37.59, 37.64, 37.51, 37.55, 37.63, 37.67, 37.69, 37.7, 37.71, 37.72, 37.6, 37.6, 37.71, 37.72, 37.77, 37.99, 37.87, 37.88, 37.75, 37.83, 37.72, 37.85, 37.84, 37.9, 37.8, 37.8, 37.74, 37.8, 37.71, 37.93, 38.05, 38.07, 37.89, 37.73, 37.85, 37.78, 37.87, 37.83, 37.95, 38, 37.84, 37.81, 37.72, 37.64, 37.81, 37.88, 37.85, 37.83, 37.82, 37.65, 37.77, 37.6, 37.57, 37.67, 37.64, 37.63, 37.74, 37.7, 37.55]',37.79,66.50,0,55,0,'2026-11-03 03:32:10','2026-11-03 03:32:10'),(27,1,'[37.58, 37.65, 37.65, 37.75, 37.75, 37.72, 37.7, 37.65, 37.63, 37.71, 37.9, 37.97, 37.93, 37.77, 37.73, 37.73, 37.73, 37.78, 37.99, 37.99, 37.97, 37.76, 37.94, 37.69, 37.65, 37.75, 38.01, 37.88, 37.92, 37.87, 37.83, 37.82, 37.78, 37.92, 37.97, 38.06, 37.97, 38.01, 37.79, 37.78, 37.67, 37.87, 37.81, 37.97, 37.84, 37.98, 37.81, 37.68, 37.67, 37.87, 37.91, 37.75, 37.88, 37.81, 37.76, 37.74, 37.62, 37.62, 37.63, 37.74, 37.79, 37.64, 37.8, 37.76]',37.83,65.40,0,55,0,'2026-11-03 03:34:10','2026-11-03 03:34:10'),(28,1,'[37.46, 37.48, 37.6, 37.71, 37.49, 37.6, 37.51, 37.56, 37.6, 37.54, 37.55, 37.57, 37.57, 37.63, 37.68, 37.55, 37.69, 37.58, 37.65, 37.66, 37.83, 37.84, 37.62, 37.7, 37.52, 37.67, 37.74, 37.82, 37.89, 37.65, 37.8, 37.71, 37.69, 37.8, 37.66, 37.95, 37.82, 37.87, 37.8, 37.69, 37.65, 37.67, 37.8, 37.75, 37.67, 37.67, 37.7, 37.65, 37.45, 37.66, 37.62, 37.79, 37.79, 37.72, 37.58, 37.61, 37.59, 37.59, 37.55, 37.57, 37.64, 37.64, 37.65, 37.39]',37.67,65.30,0,55,0,'2026-11-03 03:36:10','2026-11-03 03:36:10'),(29,1,'[37.41, 37.49, 37.68, 37.59, 37.52, 37.63, 37.54, 37.52, 37.53, 37.71, 37.76, 37.76, 37.81, 37.62, 37.71, 37.53, 37.72, 37.77, 37.71, 37.73, 37.74, 37.85, 37.69, 37.68, 37.75, 37.72, 37.66, 37.96, 37.74, 37.83, 37.69, 37.74, 37.75, 37.75, 37.78, 37.75, 37.92, 37.73, 37.75, 37.58, 37.6, 37.76, 37.67, 37.83, 37.74, 37.77, 37.56, 37.73, 37.53, 37.62, 37.62, 37.78, 37.81, 37.71, 37.74, 37.5, 37.57, 37.65, 37.55, 37.71, 37.52, 37.52, 37.59, 37.44]',37.69,66.70,0,55,0,'2026-11-03 03:38:10','2026-11-03 03:38:10'),(30,1,'[37.55, 37.52, 37.59, 37.57, 37.64, 37.46, 37.43, 37.44, 37.55, 37.52, 37.49, 37.55, 37.58, 37.71, 37.44, 37.56, 37.46, 37.72, 37.58, 37.59, 37.77, 37.61, 37.63, 37.41, 37.43, 37.65, 37.76, 37.73, 37.79, 37.74, 37.68, 37.48, 37.63, 37.53, 37.77, 37.75, 37.67, 37.71, 37.66, 37.67, 37.44, 37.59, 37.56, 37.75, 37.82, 37.71, 37.5, 37.55, 37.46, 37.56, 37.71, 37.63, 37.6, 37.55, 37.58, 37.42, 37.38, 37.46, 37.58, 37.63, 37.45, 37.44, 37.57, 37.33]',37.61,66.40,0,55,0,'2026-11-03 03:40:10','2026-11-03 03:40:10'),(31,1,'[37.26, 37.3, 37.41, 37.44, 37.42, 37.34, 37.36, 37.3, 37.27, 37.29, 37.53, 37.46, 37.52, 37.47, 37.46, 37.23, 37.31, 37.35, 37.45, 37.45, 37.55, 37.39, 37.3, 37.38, 37.41, 37.52, 37.45, 37.68, 37.52, 37.63, 37.39, 37.4, 37.38, 37.52, 37.51, 37.59, 37.49, 37.45, 37.32, 37.36, 37.36, 37.33, 37.36, 37.44, 37.46, 37.41, 37.51, 37.43, 37.19, 37.35, 37.36, 37.34, 37.51, 37.48, 37.27, 37.21, 37.13, 37.35, 37.35, 37.35, 37.25, 37.37, 37.4, 37.19]',37.42,67.20,1,35,0,'2026-11-03 03:42:10','2026-11-03 03:42:10');
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
INSERT INTO `sessions` VALUES ('0aLMI9wSfA2BSCQxepsG7ue2NtbQSY7spqFLQixv',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoia2tOVGJUdnVzNk5ZRnpLRVNvb25LbXBROXRzWVMxbTg4OXh5aGh0MiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo1OiJzdG9yZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MTU6InNlbGVjdGVkX2JyYW5jaCI7czo2OiJHbG9yaWEiO3M6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6IjA2NjEyMmE3YWFjYjA1ODkwMzdhYTg4ZjEzOGE2ZjM1M2JhNGU4NGJiNWM5YTUzY2M1MDVkMDI3NWI4ZWQ0OTkiO30=',1793708035),('hQr1GMnlKJrWM1ktvSoPBGdn1lgv4C6jmQqblF0K',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.143.1 Chrome/150.0.7871.250 Electron/43.7.7 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiM0RVa1RRTld0bXVsT0JnY2tqUktHT2htcTY2VDJ2aWlnRE5xbHNSSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo1OiJzdG9yZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1793707951);
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
INSERT INTO `users` VALUES (1,'Don Palao (Farm Owner)','owner@smartpalao.com',NULL,'$2y$12$orxYy0DHAzjRNx8o9LUXi.dbv8.ZT6xJpS81koCFoVb0FV9SfkWp2','owner','+63 917 888 2026','Palao Farmstead, Gloria, Oriental Mindoro, Philippines',NULL,'2026-11-03 03:42:09','2026-11-03 03:42:09'),(2,'Maria Santos (Egg Buyer)','buyer@gmail.com',NULL,'$2y$12$meWlE3DCukfc4gFMZW7xpuBoqd9dGuPtwE61yvpjACv/Ufrh837oa','buyer','+63 928 555 7788','Villa de Oro Beach Resort, Station 2, Boracay Island, Malay, Aklan',NULL,'2026-11-03 03:42:10','2026-11-03 03:42:10');
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
  `grid_position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `result` enum('viable','non-viable') COLLATE utf8mb4_unicode_ci NOT NULL,
  `candling_day` smallint unsigned DEFAULT '14',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vitality_logs_chamber_id_foreign` (`chamber_id`),
  CONSTRAINT `vitality_logs_chamber_id_foreign` FOREIGN KEY (`chamber_id`) REFERENCES `chambers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vitality_logs`
--

LOCK TABLES `vitality_logs` WRITE;
/*!40000 ALTER TABLE `vitality_logs` DISABLE KEYS */;
INSERT INTO `vitality_logs` VALUES (1,1,'BATCH-2026-D01','Row 1, Col 1','viable',14,'Distinct heart pulsation and dark eye spot visible.','2026-11-03 03:42:10','2026-11-03 03:42:10'),(2,1,'BATCH-2026-D01','Row 1, Col 4','viable',14,'Strong spider vascular web filling 60% of egg.','2026-11-03 03:42:10','2026-11-03 03:42:10'),(3,1,'BATCH-2026-D01','Row 2, Col 3','viable',14,'Active embryo movement under high-intensity LED.','2026-11-03 03:42:10','2026-11-03 03:42:10'),(4,1,'BATCH-2026-D01','Row 2, Col 7','non-viable',14,'Blood ring detected. Early embryonic cessation.','2026-11-03 03:42:10','2026-11-03 03:42:10'),(5,1,'BATCH-2026-D01','Row 3, Col 2','viable',14,'Dense vascularization, clear air cell boundary.','2026-11-03 03:42:10','2026-11-03 03:42:10'),(6,1,'BATCH-2026-D01','Row 3, Col 5','viable',14,'Normal 14-day duck embryo size, clear allantois.','2026-11-03 03:42:10','2026-11-03 03:42:10'),(7,1,'BATCH-2026-D01','Row 4, Col 4','viable',14,'Core cluster egg showing excellent thermal emission.','2026-11-03 03:42:10','2026-11-03 03:42:10'),(8,1,'BATCH-2026-D01','Row 5, Col 2','non-viable',14,'Clear / Infertile egg. Transferred out of chamber.','2026-11-03 03:42:10','2026-11-03 03:42:10'),(9,1,'BATCH-2026-D01','Row 5, Col 6','viable',14,'Vigorous vascular tree, well-defined air pocket.','2026-11-03 03:42:10','2026-11-03 03:42:10'),(10,1,'BATCH-2026-D01','Row 6, Col 3','viable',14,'Excellent embryo development, responsive to candling.','2026-11-03 03:42:10','2026-11-03 03:42:10'),(11,1,'BATCH-2026-D01','Row 7, Col 5','viable',14,'Optimal development corresponding to Day 14.','2026-11-03 03:42:10','2026-11-03 03:42:10'),(12,1,'BATCH-2026-D01','Row 8, Col 2','viable',14,'Embryo active with robust blood vessels.','2026-11-03 03:42:10','2026-11-03 03:42:10');
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

-- Dump completed on 2026-11-03 20:16:47

/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.13-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: member4vm
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
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `articles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(191) NOT NULL,
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
('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab','i:1;',1766892153),
('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer','i:1766892153;',1766892153),
('laravel-cache-auth.roles.list','O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:5:{i:0;O:28:\"VmEngine\\SynAuth\\Models\\Role\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:5:\"roles\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:2:\"id\";i:1;s:4:\"name\";s:9:\"Developer\";s:4:\"slug\";s:3:\"dev\";s:5:\"level\";i:0;s:11:\"based_on_id\";N;s:11:\"description\";s:57:\"Developer/Superuser role with full access to all features\";s:10:\"created_at\";s:19:\"2025-12-15 06:42:13\";s:10:\"updated_at\";s:19:\"2025-12-15 06:42:13\";}s:11:\"\0*\0original\";a:8:{s:2:\"id\";i:1;s:4:\"name\";s:9:\"Developer\";s:4:\"slug\";s:3:\"dev\";s:5:\"level\";i:0;s:11:\"based_on_id\";N;s:11:\"description\";s:57:\"Developer/Superuser role with full access to all features\";s:10:\"created_at\";s:19:\"2025-12-15 06:42:13\";s:10:\"updated_at\";s:19:\"2025-12-15 06:42:13\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:5:\"level\";s:7:\"integer\";s:11:\"based_on_id\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:1:{i:0;s:12:\"delete_token\";}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:5:{i:0;s:4:\"name\";i:1;s:4:\"slug\";i:2;s:5:\"level\";i:3;s:11:\"based_on_id\";i:4;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:1;O:28:\"VmEngine\\SynAuth\\Models\\Role\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:5:\"roles\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:2:\"id\";i:2;s:4:\"name\";s:13:\"Administrator\";s:4:\"slug\";s:5:\"admin\";s:5:\"level\";i:5;s:11:\"based_on_id\";N;s:11:\"description\";s:43:\"Administrator role with elevated privileges\";s:10:\"created_at\";s:19:\"2025-12-15 06:42:13\";s:10:\"updated_at\";s:19:\"2025-12-15 06:42:13\";}s:11:\"\0*\0original\";a:8:{s:2:\"id\";i:2;s:4:\"name\";s:13:\"Administrator\";s:4:\"slug\";s:5:\"admin\";s:5:\"level\";i:5;s:11:\"based_on_id\";N;s:11:\"description\";s:43:\"Administrator role with elevated privileges\";s:10:\"created_at\";s:19:\"2025-12-15 06:42:13\";s:10:\"updated_at\";s:19:\"2025-12-15 06:42:13\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:5:\"level\";s:7:\"integer\";s:11:\"based_on_id\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:1:{i:0;s:12:\"delete_token\";}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:5:{i:0;s:4:\"name\";i:1;s:4:\"slug\";i:2;s:5:\"level\";i:3;s:11:\"based_on_id\";i:4;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:2;O:28:\"VmEngine\\SynAuth\\Models\\Role\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:5:\"roles\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:2:\"id\";i:3;s:4:\"name\";s:4:\"User\";s:4:\"slug\";s:4:\"user\";s:5:\"level\";i:10;s:11:\"based_on_id\";N;s:11:\"description\";s:41:\"Standard user role with basic permissions\";s:10:\"created_at\";s:19:\"2025-12-15 06:42:13\";s:10:\"updated_at\";s:19:\"2025-12-15 06:42:13\";}s:11:\"\0*\0original\";a:8:{s:2:\"id\";i:3;s:4:\"name\";s:4:\"User\";s:4:\"slug\";s:4:\"user\";s:5:\"level\";i:10;s:11:\"based_on_id\";N;s:11:\"description\";s:41:\"Standard user role with basic permissions\";s:10:\"created_at\";s:19:\"2025-12-15 06:42:13\";s:10:\"updated_at\";s:19:\"2025-12-15 06:42:13\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:5:\"level\";s:7:\"integer\";s:11:\"based_on_id\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:1:{i:0;s:12:\"delete_token\";}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:5:{i:0;s:4:\"name\";i:1;s:4:\"slug\";i:2;s:5:\"level\";i:3;s:11:\"based_on_id\";i:4;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:3;O:28:\"VmEngine\\SynAuth\\Models\\Role\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:5:\"roles\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:2:\"id\";i:5;s:4:\"name\";s:6:\"Client\";s:4:\"slug\";s:6:\"client\";s:5:\"level\";i:10;s:11:\"based_on_id\";N;s:11:\"description\";s:38:\"Role for Mod Project Management Client\";s:10:\"created_at\";s:19:\"2025-12-21 07:58:03\";s:10:\"updated_at\";s:19:\"2025-12-21 07:58:03\";}s:11:\"\0*\0original\";a:8:{s:2:\"id\";i:5;s:4:\"name\";s:6:\"Client\";s:4:\"slug\";s:6:\"client\";s:5:\"level\";i:10;s:11:\"based_on_id\";N;s:11:\"description\";s:38:\"Role for Mod Project Management Client\";s:10:\"created_at\";s:19:\"2025-12-21 07:58:03\";s:10:\"updated_at\";s:19:\"2025-12-21 07:58:03\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:5:\"level\";s:7:\"integer\";s:11:\"based_on_id\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:1:{i:0;s:12:\"delete_token\";}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:5:{i:0;s:4:\"name\";i:1;s:4:\"slug\";i:2;s:5:\"level\";i:3;s:11:\"based_on_id\";i:4;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:4;O:28:\"VmEngine\\SynAuth\\Models\\Role\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:5:\"roles\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:2:\"id\";i:4;s:4:\"name\";s:5:\"Guest\";s:4:\"slug\";s:5:\"guest\";s:5:\"level\";i:15;s:11:\"based_on_id\";N;s:11:\"description\";s:35:\"Guest role with minimal permissions\";s:10:\"created_at\";s:19:\"2025-12-15 06:42:13\";s:10:\"updated_at\";s:19:\"2025-12-15 06:42:13\";}s:11:\"\0*\0original\";a:8:{s:2:\"id\";i:4;s:4:\"name\";s:5:\"Guest\";s:4:\"slug\";s:5:\"guest\";s:5:\"level\";i:15;s:11:\"based_on_id\";N;s:11:\"description\";s:35:\"Guest role with minimal permissions\";s:10:\"created_at\";s:19:\"2025-12-15 06:42:13\";s:10:\"updated_at\";s:19:\"2025-12-15 06:42:13\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:5:\"level\";s:7:\"integer\";s:11:\"based_on_id\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:1:{i:0;s:12:\"delete_token\";}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:5:{i:0;s:4:\"name\";i:1;s:4:\"slug\";i:2;s:5:\"level\";i:3;s:11:\"based_on_id\";i:4;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}',1766662977),
('laravel-cache-boost.roster.scan','a:2:{s:6:\"roster\";O:21:\"Laravel\\Roster\\Roster\":3:{s:13:\"\0*\0approaches\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:11:\"\0*\0packages\";O:32:\"Laravel\\Roster\\PackageCollection\":2:{s:8:\"\0*\0items\";a:11:{i:0;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:5:\"^12.0\";s:10:\"\0*\0package\";E:37:\"Laravel\\Roster\\Enums\\Packages:LARAVEL\";s:14:\"\0*\0packageName\";s:17:\"laravel/framework\";s:10:\"\0*\0version\";s:7:\"12.44.0\";s:6:\"\0*\0dev\";b:0;}i:1;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:0;s:13:\"\0*\0constraint\";s:6:\"v0.3.8\";s:10:\"\0*\0package\";E:37:\"Laravel\\Roster\\Enums\\Packages:PROMPTS\";s:14:\"\0*\0packageName\";s:15:\"laravel/prompts\";s:10:\"\0*\0version\";s:5:\"0.3.8\";s:6:\"\0*\0dev\";b:0;}i:2;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:0;s:13:\"\0*\0constraint\";s:6:\"v3.7.3\";s:10:\"\0*\0package\";E:38:\"Laravel\\Roster\\Enums\\Packages:LIVEWIRE\";s:14:\"\0*\0packageName\";s:17:\"livewire/livewire\";s:10:\"\0*\0version\";s:5:\"3.7.3\";s:6:\"\0*\0dev\";b:0;}i:3;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:6:\"^1.7.0\";s:10:\"\0*\0package\";E:34:\"Laravel\\Roster\\Enums\\Packages:VOLT\";s:14:\"\0*\0packageName\";s:13:\"livewire/volt\";s:10:\"\0*\0version\";s:6:\"1.10.1\";s:6:\"\0*\0dev\";b:0;}i:4;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:4:\"^3.8\";s:10:\"\0*\0package\";E:38:\"Laravel\\Roster\\Enums\\Packages:LARASTAN\";s:14:\"\0*\0packageName\";s:17:\"larastan/larastan\";s:10:\"\0*\0version\";s:5:\"3.8.1\";s:6:\"\0*\0dev\";b:1;}i:5;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:0;s:13:\"\0*\0constraint\";s:6:\"v0.5.1\";s:10:\"\0*\0package\";E:33:\"Laravel\\Roster\\Enums\\Packages:MCP\";s:14:\"\0*\0packageName\";s:11:\"laravel/mcp\";s:10:\"\0*\0version\";s:5:\"0.5.1\";s:6:\"\0*\0dev\";b:1;}i:6;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:5:\"^1.24\";s:10:\"\0*\0package\";E:34:\"Laravel\\Roster\\Enums\\Packages:PINT\";s:14:\"\0*\0packageName\";s:12:\"laravel/pint\";s:10:\"\0*\0version\";s:6:\"1.26.0\";s:6:\"\0*\0dev\";b:1;}i:7;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:5:\"^1.41\";s:10:\"\0*\0package\";E:34:\"Laravel\\Roster\\Enums\\Packages:SAIL\";s:14:\"\0*\0packageName\";s:12:\"laravel/sail\";s:10:\"\0*\0version\";s:6:\"1.51.0\";s:6:\"\0*\0dev\";b:1;}i:8;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:4:\"^4.1\";s:10:\"\0*\0package\";E:34:\"Laravel\\Roster\\Enums\\Packages:PEST\";s:14:\"\0*\0packageName\";s:12:\"pestphp/pest\";s:10:\"\0*\0version\";s:5:\"4.2.0\";s:6:\"\0*\0dev\";b:1;}i:9;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:0;s:13:\"\0*\0constraint\";s:6:\"12.5.3\";s:10:\"\0*\0package\";E:37:\"Laravel\\Roster\\Enums\\Packages:PHPUNIT\";s:14:\"\0*\0packageName\";s:15:\"phpunit/phpunit\";s:10:\"\0*\0version\";s:6:\"12.5.3\";s:6:\"\0*\0dev\";b:1;}i:10;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:0;s:13:\"\0*\0constraint\";s:0:\"\";s:10:\"\0*\0package\";E:41:\"Laravel\\Roster\\Enums\\Packages:TAILWINDCSS\";s:14:\"\0*\0packageName\";s:11:\"tailwindcss\";s:10:\"\0*\0version\";s:6:\"4.1.11\";s:6:\"\0*\0dev\";b:0;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:21:\"\0*\0nodePackageManager\";E:43:\"Laravel\\Roster\\Enums\\NodePackageManager:NPM\";}s:9:\"timestamp\";i:1766877435;}',1766963835);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(191) NOT NULL,
  `owner` varchar(191) NOT NULL,
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
-- Table structure for table `client_categories`
--

DROP TABLE IF EXISTS `client_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_categories_created_by_foreign` (`created_by`),
  KEY `client_categories_updated_by_foreign` (`updated_by`),
  KEY `client_categories_name_index` (`name`),
  CONSTRAINT `client_categories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `client_categories_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_categories`
--

LOCK TABLES `client_categories` WRITE;
/*!40000 ALTER TABLE `client_categories` DISABLE KEYS */;
INSERT INTO `client_categories` VALUES
(1,'Dinas','Government institutions and agencies','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(2,'BUMN','State-owned enterprises','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(3,'Perusahaan Swasta','Private companies and corporations','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(4,'Kementerian','Ministries and governmental departments','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(5,'Personal','Individual clients and freelancers','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(6,'Yayasan/Lembaga','Foundations and non-profit organizations','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(7,'Instansi Pendidikan','Educational institutions and schools','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(8,'Koperasi','Cooperative organizations','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(9,'Other','Other types of clients','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL);
/*!40000 ALTER TABLE `client_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `client_category_id` bigint(20) unsigned DEFAULT NULL,
  `company` varchar(191) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(191) DEFAULT NULL,
  `director` varchar(191) DEFAULT NULL,
  `pic` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `services` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`services`)),
  `social_media` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`social_media`)),
  `status` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '0=Prospect Client, 1=Active Client',
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `is_proposal` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clients_created_by_foreign` (`created_by`),
  KEY `clients_updated_by_foreign` (`updated_by`),
  KEY `clients_user_id_index` (`user_id`),
  KEY `clients_client_category_id_index` (`client_category_id`),
  KEY `clients_company_index` (`company`),
  KEY `clients_city_index` (`city`),
  KEY `clients_status_index` (`status`),
  KEY `clients_active_index` (`active`),
  KEY `clients_is_proposal_index` (`is_proposal`),
  KEY `clients_status_active_index` (`status`,`active`),
  CONSTRAINT `clients_client_category_id_foreign` FOREIGN KEY (`client_category_id`) REFERENCES `client_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `clients_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `clients_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `clients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=470 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES
(1,3,3,'PT. CAA Telco','Jl. Cipete Raya No.2, RT.8/RW.4, Cipete Sel., Kec. Cilandak, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12410','Jakarta','Ferry Syah','Urwatul Wutsqo','0211234567890','[\"1\"]','{\"website\":\"https:\\/\\/imamuda.id\\/\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',1,5),
(2,4,1,'Dinas Perkebunan Provinsi Jawa Barat','Jl. Surapati No.67, Sadang Serang, Kecamatan Coblong, Kota Bandung, Jawa Barat 40133','Bandung','Dadi Gun Gun Cahyadi','Agus','08112115500',NULL,'{\"website\":\"simponi.project.4visionmedia.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,1,'2025-12-26 02:03:48','2025-12-26 02:03:48',NULL,1),
(3,6,3,'CV Four Vision Media','-','-',NULL,'Ihsan Firdaus','081220771010',NULL,'{\"website\":\"bumdes.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,1),
(4,7,7,'Yayasan Bahtera','Ruko Karya Ganda Asri No. 17 C\r\nJalan Ceger Raya RT 001/003 Kel Pondok Karya, Kec Pondok Aren, Kota Tangerang Selatan, Banten 15425, Indonesia','Tangerang Selatan',NULL,'Roni','08231545552',NULL,'{\"website\":\"bahterafoundation.org\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(5,8,3,'CV Pesona Nusantara','Ruko Bumi Fajar Indah, Jl. Fajar Niaga No. 63 Jaka Sampurna Bekasi Barat, Bekasi - Jawa Barat','Bekasi',NULL,'Agung','081282461199',NULL,'{\"website\":\"kristakosmetik.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,1),
(6,9,3,'PT. Bekisar','Jl. Cipete Raya No. 2C Jakarta Selatan 12410','Jakarta',NULL,'Huda','087782584009',NULL,'{\"website\":\"ptbekisar.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(7,10,3,'PT. BPR CHRISTA JAYA PERDANA','Jalan Frans Seda No.16 Patululi, Oebobo, Kota Kupang,Nusa Tenggara Timur. 85111','Kupang',NULL,'Mario','08113822422',NULL,'{\"website\":\"bankchrista.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(8,11,5,'Ma\'had Annida Al Islamy Bekasi','JL. K.H. MANSYUR NO.91 KEL. BEKASI JAYA KEC. BEKASI TIMUR 17112','Bekasi',NULL,'Adie Al Hasan','081319989888','[\"1\"]','{\"website\":\"almarhalah.sch.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(9,12,3,'PT. Primadinamika','BSD City, Ruko Sektor VII Blok RK/15, 15321','Tangerang Selatan',NULL,'Thomas Kangaran','08176779530',NULL,'{\"website\":\"primadinamika.co.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(10,13,3,'Cephas Music School','PLAZA SENTRAL LT 3 JL. JEND. SUDIRMAN KAV.47, JAKARTA 12930','Jakarta',NULL,'Joel Cephas','021-5710969','[\"1\"]','{\"website\":\"cephasmusicschool.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(11,14,3,'Pesantren Al Ihsan Baleendah','Jl. Adipati Agung No.40, Baleendah, Bandung, Jawa Barat 40375','Bandung',NULL,'Sidiq','085222827811',NULL,'{\"website\":\"pesantrenalihsanbe.or.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(12,15,3,'PT. Cahaya Arif Abadi','Jl. Cipete Raya No. 2C Jakarta Selatan 12410','Jakarta',NULL,'Huda','087782584009',NULL,'{\"website\":\"ghuraf.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(13,16,1,'Dinas KUK Prov Jawa Barat','Soekarno-Hatta St No.705, Jatisari, Buahbatu, Bandung City, West Java 40286','Bandung',NULL,'Ervin','08119404041','[\"2\"]','{\"website\":\"ratkoperasi.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(14,17,5,'Pribumi Online Store','Jl. Pendidikan no.77 Cinangka Pondok Cabe, Jakarta 1656','Jakarta',NULL,'Brizadly','082111011968',NULL,'{\"website\":\"pribumi.co.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(15,18,3,'Baznas Provinsi NTB','Jl. Pejangik No. 12 (Komplek Kantor Gubernur NTB) Masjid Al-Amin Lt. 1 Provinsi Nusa Tenggara Barat','Mataram',NULL,'Yadi','087786685005',NULL,'{\"website\":\"baznasntb.or.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(16,19,5,'Rahayu Partners','Gedung Palma One Lantai 9 Suite 909 Jl. H.R Rasuna Said Kav. X2/4 Kuningan, Jakarta Selatan 12590','Jakarta',NULL,'Temi','081317620838','[\"1\"]','{\"website\":\"elvira.rahayupartners.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,333),
(17,20,3,'PT. Profita Raya','Jln. Kopo Bihbul Km. 6.7 No. 70 Bandung','Bandung',NULL,'Kevin Kusnadi','08112007732',NULL,'{\"website\":\"profitaraya.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(18,21,3,'Serikat Perusahaan Pers (Pusat) Indonesia','Jl. Kebon Sirih No.32-34 Jakarta 10110','Jakarta',NULL,'Agusnia Ayu','081210155281','[\"1\"]','{\"website\":\"spsindonesia.org\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(19,22,3,'PT Total Guna Energi','GF, a, 02 , Goden Plaza Fatmawati , JL. RS. Fatmawati No. 15 , Cipete Selatan, Cilandak, 12410 Jakarta Selatan, DKI Jakarta, RT.8/RW.6, South Gandaria, Cilandak, South Jakarta City, Jakarta 12420','Jakarta',NULL,'Trinoto','081584204500',NULL,'{\"website\":\"thegreeneffects.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(20,23,3,'New Champion Motor','Jl. Soekarno-Hatta No.291, Situsaeur, Bojongloa Kidul, Kota Bandung, Jawa Barat 40234','Bandung',NULL,'Husni Mubarok','081390027002',NULL,'{\"website\":\"newchampionmotor.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(21,24,3,'PT Global Kriya Nusantara','JL Cibiru Hilir Kav 194','Bandung',NULL,'HANDOKO','8122166163','[\"1\"]','{\"website\":\"www.kriyanusantara.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(22,25,3,'PT. Timah Industri','Jl. Eropa 1 Kav A3 Kawasan Industrial Estate, Kotasari, Cilegon, Kota Cilegon, Banten 42435','Cilegon',NULL,'Aqil','081310002287','[\"1\"]','{\"website\":\"timahindustri.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(23,26,3,'PT. BPR Artha Mitra Kencana','Soekarno-Hatta St No.291, Situsaeur, Bojongloa Kidul, Bandung City, West Java 40234','Bandung',NULL,'Vincent','085659619096',NULL,'{\"website\":\"bpramk.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(24,27,3,'PT Radiance','l. Raya Menceng No.39, RT.3/RW.11, Tegal Alur, Kec. Kalideres, Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta 11820','Jakarta',NULL,'Muntaha Hidayat','0895330155555','[\"1\"]','{\"website\":\"radiancecopyrent.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(25,28,3,'Rumah Sakit Cahya Kawaluyan','Jl. Parahyangan KM. 3, Kota Baru Parahyangan, Padalarang, Kab. Bandung Barat 40553','Bandung',NULL,'Erlinawati','087824166348','[\"1\"]','{\"website\":\"rscahyakawaluyan.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(26,29,3,'Ayam Goreng Eyang Uti','Perumahan Bukit Dago Cluster Casablanca, Blk. E9 No.mor 3a, Rawakalong, Kec. Gn. Sindur, Bogor, Jawa Barat 16340','Bogor',NULL,'Laely Indah Lestari','082118228818',NULL,'{\"website\":\"eyanguti.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(27,30,3,'PT Indo Bintang Mandiri','Delta Silicon 3 Jl, Soka Blok F-20 No. 8B-5 Lippo Cikarang, Bekasi, Indonesia','Bekasi',NULL,'Myta','087888800318',NULL,'{\"website\":\"indobintangmandiri.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(28,31,3,'PT. Masadenta','JL. Sumedang, No. 10, Laswi, Kacapiring, Batununggal, Kota Bandung, Jawa Barat 40271','Bandung',NULL,'Eka','085222137496','[\"1\"]','{\"website\":\"www.masadenta.co.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(29,32,3,'PT. Tata Megah Perkasa','Kawasan Niaga Metro Trade Centre, Jl. Soekarno-Hatta Blok C No.590, Sekejati, Buahbatu, Kota Bandung, Jawa Barat 40286','Bandung',NULL,'Dadang Arifin S.','08122151234','[\"1\"]','{\"website\":\"tatamegahperkasa.co.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(30,33,3,'PT. Hanjaya Karya Lininusa','Jl. H.M. Arsyad KM.9 RT 019 RW 008 Pelangsian, Mentawa Baru Ketapang Kab. Kotawaringin Timur Kalimantan Tengah','Kotawaringin',NULL,'Maryanto Pohan','081233009999',NULL,'{\"website\":\"hanjaya.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(31,34,3,'Kadin Provinsi Jawa Barat','Jl. Sukabumi No.42, Kacapiring, Kec. Batununggal, Kota Bandung, Jawa Barat 40271','Bandung',NULL,'Eko Kadin','081224792489',NULL,'{\"website\":\"kadinjabar.or.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(32,35,3,'Jasa Tirta II','JALAN LURAH KAWI NO.1 JATILUHURIPURWAKARTA','PURWAKARTA',NULL,'ER INA RISMAYANTI','08128276295','[\"1\"]','{\"website\":\"humasjasatirta2.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(33,36,5,'Faatiha','- tdk aktif','-',NULL,'Rian Handrian','081222444018',NULL,'{\"website\":\"faatiha.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(34,37,3,'PT. CITANDUY PERKASA','Wisma BNI 46 - Kota BNI, Jl. Jend. Sudirman No.Kav 1, RT.10/RW.9, Karet Tengsin, Kecamatan Tanah Abang, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10220','Jakarta',NULL,'Helen','0212512833',NULL,'{\"website\":\"fokluksau.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(35,38,3,'PT. ARIES UTAMA EKAGRIYA','Sentra BRI, Jl. Jend. Sudirman No.Kav 44-46, RT.14/RW.1, Bend. Hilir, Kecamatan Tanah Abang, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10210','Jakarta',NULL,'Yuti','215700111',NULL,'{\"website\":\"purikencana.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(36,39,7,'YAYASAN PARAHYANGAN SATYA','Jl. Sriwijaya Baru 4 No.61, Setiamanah, Kec. Cimahi Tengah, Kota Cimahi, Jawa Barat 40524','Cimahi',NULL,'Hanief','0817226986',NULL,'{\"website\":\"thebiggestsundial.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(37,40,3,'PT. SWADHARMA PRIMAUTAMA','Wisma BNI 46 Kota BNI Lantai LG, Jalan Jendral Sudirman Kav 1, RT.10/RW.11, Karet Tengsin, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10220','Jakarta',NULL,'Yuyun','087780277721',NULL,'{\"website\":\"jsosuites.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(38,41,3,'Macro World Mania','- tdk aktif','-',NULL,'Andiyan Luthfi','08128314443',NULL,'{\"website\":\"macroworldmania.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(39,42,5,'Balindah Spa','- tdk aktif','-',NULL,'Jun Budijanto','0811848889',NULL,'{\"website\":\"balindahspa.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(40,44,9,'Koperasi Syariah Jabar','- tdk aktif','-',NULL,'Hidayat Al Mas','081227166133',NULL,'{\"website\":\"koperasisyariahjabar.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(41,46,3,'CV Gradient','Jl. Situ Batu No.C18, Cijagra, Kec. Lengkong, Kota Bandung, Jawa Barat 40265','Bandung',NULL,'Usan','081322945149',NULL,'{\"website\":\"cvgradient.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(42,48,5,'Fatih Fira','Sasakbubur, Mekarmukti, Cihampelas, Mekarmukti, Kec. Cihampelas, Kabupaten Bandung Barat, Jawa Barat 40562','Bandung',NULL,'RIZA FAISAL IRAWAN, S.KOM','08122799117','[\"1\",\"6\"]','{\"website\":\"fatihfirra.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(43,49,5,'PT Napalima','l. Boulevard Artha Gading, RT.18/RW.8, Klp. Gading Bar., Kec. Klp. Gading, Kota Jkt Utara, Daerah Khusus Ibukota Jakarta 14240','Jakarta',NULL,'Fandi','081233173075',NULL,'{\"website\":\"napalima.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(44,50,3,'Dislitbang TNI AD','l. Matraman Raya No.147, RW.9, Palmeriam, Kec. Matraman, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13140','Jakarta',NULL,'Amzah','08124845858',NULL,'{\"website\":\"dislitbangtniad.org\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(45,51,3,'Dekopinwil Jabar','Soekarno-Hatta St No.729A, Jatisari, Buahbatu, Bandung City, West Java 40286','Bandung',NULL,'Tuti','089656097548',NULL,'{\"website\":\"dekopinwiljabar.org\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(46,53,5,'Papandayan Hotel','Jl. Gatot Subroto No.83, Malabar, Kec. Lengkong, Kota Bandung, Jawa Barat 40262','Bandung',NULL,'Ricky Stanley','081931444941',NULL,'{\"website\":\"tpjazzbandung.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(47,54,3,'KOMUNITAS INSAN MUDA','JL. SANINTEN RT 02/12 KEL.TENJOLAYA KEC. PASIR JAMBU KAB BDG','KABUPATEN BANDUNG',NULL,'Asep Wahidin','81320726428','[\"1\"]','{\"website\":\"digitalpreneur.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(48,55,5,'RACING4NET','JL. WIRA ANGUN-ANGUN NO.32, BANDUNG','Bandung',NULL,'Edwin','8112268608','[\"1\"]','{\"website\":\"racing4.net\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(49,56,3,'Kangaroo Travel','Jl. Cipto Mangunkusumo No.157, Sengkotek, Kec. Loa Janan Ilir, Kota Samarinda, Kalimantan Timur 75243','Samarinda',NULL,'Adit Rachman','081283180505',NULL,'{\"website\":\"kangaroo.co.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(50,57,5,'JBW Lawyers','JAUTAMA BUILDING, Lantai 5, \r\nJl. Teuku Cik Di Tiro 1 no. 6 , Menteng, Jakarta 10350 - Indonesia\r\nTelp: +62 21 3190 4488, Fax: +62 62 21 3190 3388','Jakarta',NULL,'Danti','081908906393',NULL,'{\"website\":\"jbw-lawyers.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(51,58,5,'CU Keling Kumang','JL.YC.Oevang Uray No.33, Kelurahan Baning Kota Kecamatan Sintang Kabupaten Sintang Kal-Bar, Baning Kota, Kec. Sintang, Kabupaten Sintang, Kalimantan Barat 78611','Pontianak',NULL,'Ridwan','082154387126',NULL,'{\"website\":\"kelingkumanggroup.co.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(52,59,3,'HIMKI Indonesia','Griya Inti Sentosa - Jl. Griya Agung Blok M3 No. 22 Sunter, Jakarta 14350 Indonesia','Jakarta',NULL,'Maulana','085840030371',NULL,'{\"website\":\"himki-indonesia.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(53,60,3,'KPU Kota Payakumbuh','Jl. RKY. Rasuna Said, Tiakar, Balai Nan Tuo, Payakumbuh Tim., Kota Payakumbuh, Sumatera Barat 26217','Payakumbuh',NULL,'Apip Saepudin','0811207300',NULL,'{\"website\":\"kpu-payakumbuhkota.go.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(54,61,3,'Dekopinda Subang','Jl. Suka Melang No.2, Cigadung, Kec. Subang, Kabupaten Subang, Jawa Barat 41211','Subang',NULL,'Daeng','081320528333',NULL,'{\"website\":\"dekopindasubang.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(55,62,3,'PT. Alamanda Makmur Mitra','Jl. Raya Pangalengan No.486, Margahurip, Banjaran, Bandung, Jawa Barat 40377','Bandung',NULL,'Krisna','08562328100',NULL,'{\"website\":\"alamandaregency.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(56,63,5,'Travel Hebat','Jalan Mangga 41 Sukajadi, Pekanbaru. Riau, Indonesia','Pekanbaru',NULL,'Junaidi','0811753750',NULL,'{\"website\":\"travelhebat.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(57,64,3,'PT. PESONA SCIENTIFIC','KOPO MAS REGENSI BLOK 8 F-1, BANDUNG 40223','Bandung',NULL,'Nita','08156054072','[\"1\"]','{\"website\":\"alatpeternakan.co.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(58,65,5,'KOPERASI JASA BAITUTTAMWIL BERKAH UMAT','Jl. Ciganitri No. 2 Desa Cipagalo Kec. Bojong Soang Kab. Bandung, Jawa Barat','Bandung','Dr.Latief Awaludin,M,A','Zein','081809962350','[\"1\",\"2\"]','{\"website\":\"bmtberkahumat.com, https:\\/\\/berkahumat.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(59,66,3,'PT. MIURA INDONESIA','Jl. Harapan Raya. Lot KK-10 karawang Jawa Barat (Kawasan Karawang International Industrial City / KIIC)','Karawang',NULL,'Riki','08988235276',NULL,'{\"website\":\"miura.co.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(60,67,9,'BPR CENTRAL PITOBY','Jl. Jendral Sudirman No. 136 Kupang Nusa Tenggara Timur','Kupang',NULL,'Frengky','081339454565',NULL,'{\"website\":\"bprcentralpitoby.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(61,68,3,'PT JAKARTA DAYA MOTTAMA DIPTAKA','Jl. Teuku Cik Ditiro 1 No.6, RT.1/RW.2, Gondangdia, Menteng, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10350','Jakarta',NULL,'Renny','08111919191',NULL,'{\"website\":\"djautamagroup.co.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(62,69,3,'YAYASAN KESEJAHTERAAN PEGAWAI BANK BJB','Jl. PHH. Mustofa No.31 Bandung 40124','Bandung',NULL,'Fajar','081809718831',NULL,'{\"website\":\"ykpbankbjb.or.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(63,70,3,'BIDANG PENYULUHAN DISTAN PROVINSI JAWA BARAT','Jl. Surapati No.71, Sadang Serang, Kecamatan Coblong, Kota Bandung, Jawa Barat 40133','Bandung',NULL,'-','123456789',NULL,'{\"website\":\"penyuluhjabar.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(64,71,7,'PT. Carolina Sahabat Nusantara','Jl. Tembaga Raya No. 38 BI Kemayoran, Jakarta Pusat DKI Jakarta, Indonesia 10640','Jakarta',NULL,'Mayang','087878253535',NULL,'{\"website\":\"csngroup.co.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(65,74,3,'PT. BPR Danamas Belu','Jl. Pramuka, Pasar baru - Atambua Kabupaten Belu - NTT','Atambua',NULL,'Fatima','081236729473',NULL,'{\"website\":\"bprdanamasbelu.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(66,75,3,'PT. BPR BINA USAHA DANA','Jl. Batu Ata No. 5, Kel Puken Tobi Wangi Bao, Kec Larantuka, Kab Flores Timur, Nusa Tenggara Timur','Flores',NULL,'Mario','082247813400',NULL,'{\"website\":\"bprbinausahadana.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(67,76,3,'PT. MEDIA LINTAS FREKUENSI','Jl Raya Kramat 7-9 Ged Sentra Kramat Lt 2 Bl B/12, Jakarta','Jakarta',NULL,'Eka','085716554321',NULL,'{\"website\":\"billingmlf.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(68,78,3,'Darmaga Sunda','Jl. Raya Nagreg No.km 37, Citaman, Nagreg, Bandung, Jawa Barat 40215','Bandung',NULL,'Sisca','081214444944',NULL,'{\"website\":\"darmagasunda.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(69,79,3,'NDT Instruments','Jl. Sirsak No.27, RT.2/RW.4, Ciganjur, Kec. Jagakarsa, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12630','Jakarta',NULL,'Chestharianto','02129430001',NULL,'{\"website\":\"ndt-instruments.co.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(70,82,5,'PT. Setya Langgeng Sentosa','QUADRA CENTRUM\r\nJl. Taman Aries Blok E1.3, Meruya, Jakarta Barat 11620 INDONESIA','Jakarta',NULL,'Marcel','0215700880',NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(71,84,3,'KPUD Kab. Bandung','Jl. Sindang Wargi, Soreang, Kec. Soreang, Bandung, Jawa Barat 40911','Bandung',NULL,NULL,NULL,'[\"1\",\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',1,5),
(72,85,3,'The Indonesian Iron & Steel Industry Association','Jl. Jend. Gatot Subroto Kav 54 Jakarta Selatan','Jakarta','Silmy Karim','pak Bagas dan Pak ROy','021-5235501',NULL,'{\"website\":\"https:\\/\\/www.iisia.or.id\\/\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,1,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,1),
(73,86,3,'PT. MANGGALA NARASHANSHA','Kencana Tower Level Mezzanine Jl. Raya Meruya Ilir, Meruya Utara, Kembangan. DKI Jakarta - Indonesia','Jakarta',NULL,'RINA','081290714393','[\"1\"]','{\"website\":\"https:\\/\\/www.ptgalashansha.co.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(74,87,3,'Politeknik Pariwisata Palembang','Jalan Sapta Pesona No. 10 Kec. Silabranti, Komplek Jakabaring Sport City','Kota Palembang',NULL,NULL,'07116201234','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(75,88,3,'RACIKITO.COM','NUANSA MAS ESTATE BLOK G-20, CIPAMOKOLAN,RANCASARIA, KOTA BANDUNG','Bandung',NULL,'YANI DAHYANI','087827403432','[\"1\"]','{\"website\":\"racikito.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(76,89,8,'Dinas Perikanan Kab. Sorong','Komplek Kantor Bupati Jl. Klamono Km 24, Aimas Kabupaten Sorong Propinsi Papua Barat','Sorong',NULL,'pak Ronald','081248719906','[\"1\",\"2\"]','{\"website\":\"http:\\/\\/diskansorongkab.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,1,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(77,90,5,'SMESCO Indonesia','Jl. Gatot Subroto No.Kav. 94, RT.11/RW.3, Pancoran, Kec. Pancoran, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12780','Jakarta',NULL,'Wientor / Ade Budiman','081802255528 / 08568998789','[\"1\",\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,1,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(78,91,1,'BKKPD HALSEL','Halmahera Selatan','Halmahera Selatan',NULL,'Pak Tauviq','0821-8775-1383','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,1,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(79,92,3,'Kampung Inggris Sorong','Kabupaten Sorong','Aimas',NULL,'Abam','081398681823','[\"1\"]','{\"website\":\"http:\\/\\/www.sorongenglishvillage.com\\/\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(80,93,6,'BPPT','Gedung B.J. Habibie Lantai 14 Jl. M.H Thamrin No. 8 Jakarta Pusat DKI Jakarta 10340','Jakarta',NULL,'WAHYU HIDAYAT (SIPAMER), NUGROHO (TECH DIA)','81385906209 / 81312158780','[\"2\"]','{\"website\":\"bppt.go.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(81,94,3,'disnakertrans','Jalan Soekarno-Hatta 532','Bandung',NULL,'Rivaldi','89692259645','[\"1\"]','{\"website\":\"jobfairjabar.id\\/\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(82,95,5,'LPMP Sumsel','Jl. Lintas Palembang-Indralaya. Ogan Ilir. Sumsel','Kota Palembang',NULL,'siapa','00000','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(83,96,1,'BIRO KSLN (KERJA SAMA LUAR NEGERI)','Jl. Diponegoro No.22, Citarum, Kec. Bandung Wetan, Kota Bandung, Jawa Barat 40115','Bandung','Herry','Biya','081214528975','[\"1\",\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,1),
(84,97,6,'KOPERASI MICRO BERBAGI KEBAIKAN','Jl. Teuku Umar No 59c Tanjungbalai - Sumatera Utara','KOTA TANJUNG BALAI','Muhammad Haris Siahaan','Pak Rahmat','+62 852-7795-9928, 0623 93182','[\"2\"]','{\"website\":\"https:\\/\\/micro.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(85,98,3,'KOPERASI CATUR BHAKTI KANWIL DITJEN PERBENDAHARAAN PROV.DKI JAKARTA','Jalan Otista Raya No. 53-55 Jakarta Timur','KOTA JAKARTA TIMUR','PAULI HAMONANGAN','Pak Pauli','0852 1716 2714','[\"2\"]','{\"website\":\"https:\\/\\/caturbhakti.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(86,99,9,'Koperasi Konsumen Kunci Sukses Bersama','Management Office Kuningan City Mall Jl Prof Dr. Satrio Kav 18','KOTA JAKARTA SELATAN','Andriansyah','Pak Andriansyah','+62 811-854-021','[\"2\"]','{\"website\":\"https:\\/\\/k3sb.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(87,100,9,'KOPERASI KARYAWAN PT. ARGAPURA \"MANDIRI SEJAHTERA\"','Jl. Manis II No. 17, Kawasan Industri Manis, Tagerang 15136','KOTA TANGGERANG','Rully Prasethio','Pak Rully','021-5918944','[\"2\"]','{\"website\":\"https:\\/\\/argapura.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(88,101,9,'BMT NU Pesawaran','Jl. Jend A. Yani Kel. Bagelen, Kec.?Gedong Tataan, \r\nKab. Pesawaran, Lampung, Lampung, 35366','KABUPATEN LAMPUNG SELATAN','SALAMU SOLIKHIN','Pak Bayhaque','+62 812-9156-6522','[\"2\"]','{\"website\":\"https:\\/\\/pesawaran.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(89,102,3,'KPRI â€œGardeniaâ€ Kebun Raya Cibodas - LIPI','Jl. Kebun Raya Cibodas, Cimacan, Cipanas, Kabupaten Cianjur, Jawa Barat 43253','KABUPATEN CIANJUR','Ahmad Jaeni Ashari, S.Hut.','Pak Rustam','+62 877-2009-2375','[\"2\"]','{\"website\":\"https:\\/\\/kprigardenia.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(90,103,3,'KOPERASI PENDIDIK DAN TENAGA KEPENDIDIKAN JAKARTA','Gedung Warna-Warni Lantai 2, Jalan Melati No 2, RT 012 RW 002','KOTA JAKARTA UTARA','UBAIDILLAH','Pak M Rinaldi','+62 895-3107-7328','[\"2\"]','{\"website\":\"https:\\/\\/kptk.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(91,105,3,'Koperasi Kian Sejahtera Bersama','Jalan Kolonel Sugiono No 30 RT 001 RW 003, Malang','KOTA MALANG','Adrian Suharko','Ibu Kevinnisa','+62 858-4307-9540','[\"2\"]','{\"website\":\"https:\\/\\/ksb.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(92,106,9,'Koperasi Wirausaha Jabar Sejahtera (WJS)','jln sukarno hatta no 708 bandung','KOTA BANDUNG','NELLY MELYANI','Ibu Teti','+62 852-2011-7566','[\"2\"]','{\"website\":\"https:\\/\\/wjs.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(93,107,9,'KOPERASI JASA RAHARJA','Jl. Soekarno Hatta No.689 A, Jatisari, Buahbatu, Kota Bandung, Jawa Barat 40286','KOTA BANDUNG','Dwi Marliana Rahayu','Ibu Septi','+62 819-3134-4946','[\"2\"]','{\"website\":\"https:\\/\\/jasaraharja.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(94,108,9,'Koperasi Dewi Sri Dinas Tanaman Pangan & Hortikultura Provinsi Jawa Barat','Jl. Surapati No.71, Sadang Serang, Coblong, Kota Bandung, Jawa Barat 40133','KOTA BANDUNG','Ir. ETI MULYATI, MM','Pak Duki','+62 856-2407-3969','[\"2\"]','{\"website\":\"https:\\/\\/dewisri.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(95,109,9,'Koperasi Sinergitas Lima Pilar','Jalan Lapang Tembak No. 19, Sukarajin I RT 10 RW 13, Kelurahan Cikutra, Kecamatan Cibeunying Kidul, Kota Bandung','KOTA BANDUNG','Sumiyati','Ibu Sulistia','+62 878-2565-6582','[\"2\"]','{\"website\":\"https:\\/\\/abcgm.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(96,110,9,'LAMALEPA  Maju Jaya Abadi','KOMP.TOWNHOUSE  GLORY HOME BLOK A1 NO.07 KEL.SADAI KEC. BENGKONG KOTA BATAM PROV. KEPRI','KOTA BATAM','AGUSTINUS KIA PATI','Ibu Maria','+62 823-8424-5002','[\"2\"]','{\"website\":\"https:\\/\\/lamalepa.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(97,111,1,'KOPERASI CEMARA KARYA PRATAMA','Jalan Raya Kosambi No.43','KABUPATEN KARAWANG','Budi Mulyono','Pak Dani','089514662540','[\"2\"]','{\"website\":\"\\u00a0https:\\/\\/cekap.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(98,112,9,'KPRI Manghayu Sejahtera','Surabaya','KOTA SURABAYA','Drs. Agus Haris, MM','Pak Buntar','+62 813-3328-2707','[\"2\"]','{\"website\":\"https:\\/\\/manghayusejahtera.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(99,113,3,'KSP Mulia','\"Jl. Jendral Sudirman No.61\r\nKomplek Ruko Salatiga Plaza G-11\"','KOTA SALATIGA','ADJI KURNIAWAN','Ibu Bethany','+62 856-9722-2222','[\"2\"]','{\"website\":\"https:\\/\\/kspmulia.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(100,114,9,'KOPERASI BHAKTI ARTHAMA FISKAL','Jl. Dr Wahidin Raya No. 1 Jakarta Pusat','KOTA JAKARTA PUSAT','M Bara Ampera','Pak Ari','+62 822-1320-4222','[\"2\"]','{\"website\":\"https:\\/\\/kbaf.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(101,115,3,'Koperasi Karya Guna Gemilang','-','KABUPATEN BADUNG','I Kadek Sudana','Pak I Gede','+62 897-0218-946','[\"2\"]','{\"website\":\"https:\\/\\/nanda.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(102,119,9,'KOPERASI KARYAWAN PT TUGU REASURANSI INDONESIA','Jl KH Wahid Hasyim No 4a, Kebon Sirih - Menteng, Jakarta Pusat 10340','KOTA JAKARTA PUSAT','MULIA HIZKI','Ibu Tessa','081311369269','[\"2\"]','{\"website\":\"https:\\/\\/kopkartugure.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(103,120,9,'Koperasi Karyawan Daya Guna New Armada Magelang','Jl Sarwo edi Wibowo no 11 Banyurojo Mertoyudan Magelang','KABUPATEN MAGELANG','JOHAN ARIF KOESTANTO','I Gede Arya Wijaya, S.H','0897 0218 946','[\"2\"]','{\"website\":\"https:\\/\\/dayaguna.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(104,121,9,'RIO JAYA','Lembor Manggarai Barat, NTT','KABUPATEN  MANGGARAI BARAT','Yosep Spandi','Ibu Claudia','+62 813-9292-6440','[\"2\"]','{\"website\":\"https:\\/\\/riojaya.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(105,122,3,'KOPKAR SUMBER SEJAHTERA AGRICON','\"Agricon Building\r\nJl. Siliwangi No. 68 Bogor\"','KOTA BOGOR','Ir. Awaluddin','Ibu Cada','+62 815-1317-0009','[\"2\"]','{\"website\":\"https:\\/\\/kssag.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(106,123,9,'KOPERASI KARYAWAN HMSI','Jl Gatot subroto km 8,5 jatake, Tangerang','KOTA TANGERANG','HARJONO SWANOPATI','Pak Taufik','+62 811-8512-054','[\"2\"]','{\"website\":\"https:\\/\\/kopkarhmsi.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(107,124,3,'KOPKAR PT Cosma Mitra Sejahtera','Jalan Sempur Kaler No 10, Bogor','KOTA BOGOR','Nanang Ismail Kosim','Pak Tresna','+62 877-1114-9567','[\"2\"]','{\"website\":\"https:\\/\\/cms.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(108,125,9,'KOPERASI SM Gudang Garam','Jalan Jend Ahmad Yani No 79, Jakarta Pusat','KOTA JAKARTA PUSAT','MOH SUNARNO','Ibu Ari','+62 877-7018-8855','[\"2\"]','{\"website\":\"https:\\/\\/cms.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(109,126,9,'KOPKAR PERKASA LONDON SUMATERA','Jl. Veteran No 335 76','KOTA PALEMBANG','Hendra Wijaya','Ibu Betty','+62 813-6756-9675','[\"2\"]','{\"website\":\"https:\\/\\/kopkarperkasa.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(110,127,3,'Koperasi Keluarga Besar Dispenda Provinsi Jawa Barat (KKBD)','Jalan Soekarno Hatta No 528 Bandung','KOTA BANDUNG','Dra Hj Emma Siti Fatima, M.Si','Pak Jaja','+62 813-2188-3800','[\"2\"]','{\"website\":\"http:\\/\\/kkbd.koperasijabar.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(111,128,9,'KOPKAR PT Chitose Internasional','Jl. Industri 3 No.5 Leuwigajah','KOTA CIMAHI','siapa','Pak Andi','+62 857-2296-4416','[\"2\"]','{\"website\":\"http:\\/\\/kopkarptchitose.koperasijabar.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(112,129,9,'KOPERASI KOPIG NUSANTARA CEMERLANG','Wisma BCA - BSD City, Wing A lantai 1, Jl. Kapten Soebidjanto Djojohadikusumo - Tangerang Selatan','KOTA TANGERANG SELATAN','Sri Purwanti','Ibu Nike','+62 819-0191-9927','[\"2\"]','{\"website\":\"https:\\/\\/kopig.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(113,130,9,'Koperasi Bina Makmur','SMAN 1 Majenang Jl Raya Pahonjean KP 07 Majenang Cilacap','KABUPATEN CILACAP','Masrudin, M.Kom','Pak Lulu','+62 858-8888-8563','[\"2\"]','{\"website\":\"https:\\/\\/binamakmur.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(114,131,3,'KOPERASI KORPRI PUPR','Gedung Heritage Lantai Dasar, Jalan Pattimura No 20 Kebayoran Baru, Jakarta Selatan','KOTA JAKARTA SELATAN','Dra. Retno Triyanti Handayani, MM','Pak Suyatin','+62 818-0867-0863','[\"2\"]','{\"website\":\"https:\\/\\/korpripupr.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(115,132,9,'Koperasi Karyawan PT Pelabuhan Indonesia IV','Jl. Soekarno No. 1 Makassar','KOTA MAKASSAR','Muhammad Irfan','Pak Yusman','+62 812-4248-3894','[\"2\"]','{\"website\":\"https:\\/\\/pelindo4.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(116,133,9,'Koperasi Jasa Asta Sari Amertha','Jl. Siwa No 8','KABUPATEN BADUNG','I Gede Arya Wijaya','Pak I Gede','0897 0218 946','[\"2\"]','{\"website\":\"https:\\/\\/asa.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(117,134,9,'Ajushi','Batununggal','Bandung','Pak Fajar','Ibu Zayyn','000',NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(118,135,3,'Alamanda Regency','Jl. Asia Afrika No. 133-137 Wisma Monex Lt 9 , Kebon Pisang, Sumur Bandung','BANDUNG',NULL,NULL,NULL,'[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(119,137,9,'Annapola Group','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(120,138,5,'Asosiasi Mebel Indonesia','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(121,139,5,'Atsea Program','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(122,142,3,'Badan Penelitian dan Pengembangan Pertanian','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(123,143,7,'Bandung Industri','TAMAN HOLIS INDAH 2 RUKO 2A NO. 15 BANDUNG','0',NULL,'HERY MULYANA','08122369729','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(124,144,5,'Bappeda Kab. Kolaka','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(125,145,5,'Bengkel Sehat','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(126,146,7,'BIRD Hotel Group','JL. DR. DJUNDJUNAN NO.143-149, SKAGALIH,SUKAJADI, BANDUNG 40173','BANDUNG',NULL,'GATOT PRIJATNA',NULL,'[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(127,147,5,'BKAD Halmahera Selatan','Halmahera Selatan','Halmahera Selatan',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(128,148,1,'BKD Kabupaten Kudus','Kudus','Kudus',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(129,149,5,'Boarding School Pribadi','JL. PHH MUSTOFA NO. 41 BANDUNG','KOTA BANDUNG',NULL,'ANGKY SEFYANTO','081573207231','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(130,150,3,'BP Konsuil Wilayah Banten','Banten','Banten',NULL,'H. ANWAR MARTADIHARDJA','0254-213388','[\"1\",\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(131,151,3,'BPR AMK Bandung','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(132,153,3,'BPR Christa Jaya','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(133,154,3,'BTC Hotel','Jl. Surya Sumantri No.91 Bandung','BANDUNG',NULL,NULL,'(022) 88885151','[\"1\",\"7\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(134,155,3,'Bumdes AFTA Prokop Sukanagara','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(135,156,3,'BUMDES Maluku Tenggara','Jalan Langgur Wearlilir Kabupaten Maluku Tenggara, Provinsi Maluku','maluku',NULL,'Savsavubun',NULL,'[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(136,157,7,'Bumdes Mekarwangi','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(137,158,3,'Bumdes Ramah Abadi','Indramayu','Indramayu',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(138,160,3,'PT. Cipta Media Kreasi Mandiri','Jl. Rajamantri tengah 4 No.12 Bandung','KOTA BANDUNG',NULL,'Dede Koswara / ilwan Syahwildan','022 7302565','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(139,162,7,'Costra Development Center','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(140,163,7,'PT. Carolin Sahabat Nusantara (Olindo)','JL. Tembaga Raya No. 38B1 Harapan Mulya, Kemayoran, Jakarta Pusat','Jakarta',NULL,NULL,NULL,'[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(141,164,7,'CV Andalus','JL.SOEKARNO HATTA NO.56 PAYAKUMBUH, SUMATERA BARAT','Payakumbuh',NULL,'APIP SAEPUDIN','8172306499','[\"1\",\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(142,166,7,'CV Maluku News','JL. A.M. SANGADJI, GANG KAYU BUAH, NO.32 AMBON','Maluku',NULL,'Sahlan Heiluth','081343044400','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(143,168,3,'Departemen Pekerjaan Umum','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(144,169,3,'Departement Keuangan RI','Jl. Diponegoro No. 22 Bandung Wetan Kota Bandung','BANDUNG',NULL,'ikhwan',NULL,'[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(145,170,3,'Departement Luar Negeri RI','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(146,171,3,'Desa Bojong Malaka','Jalan Desa Bojongmalaka, Kabupaten Bandung Selatan, Indonesia.','KABUPATEN BANDUNG',NULL,'Engkus','082116831076','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(147,172,3,'Dinas KUMKM Provinsi Jawa Barat','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,1,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(148,173,3,'Dinas Pendidikan Bone Bolango','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(149,174,1,'Dinas Pendidikan Kabupaten Lima Pulu Kota','Kabupaten Lima Puluh Kota','Kabupaten Lima Puluh Kota',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(150,175,3,'Dinas Perhubungan Lima Puluh Kota','Kab. Lima Puluh Kota','Kab. Lima Puluh Kota',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(151,176,3,'UAMTC KANTOR NURTANIO BANDUNG','JL. PAJAJARAN NO.219 LANUD HUSEIN D. BANDUNG','KOTA BANDUNG',NULL,'MELIA EKA L., S.T., M.T','08156039598',NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(152,177,3,'Dinas Pertanian Provinsi Jawa Barat','Jl. Surapati No.71  Bandung','KOTA BANDUNG',NULL,NULL,'(022) 2503884','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(153,178,3,'Dinas Tanaman Pangan dan Hortikultura Provinsi Jawa Barat','Jl. Surapati No.71, Sadang Serang, Kecamatan Coblong, Kota Bandung,','BANDUNG',NULL,'IR. Eti Mulyati, M.M','(022) 2503884','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(154,179,3,'Dinas Tata Bangunan dan Pemukiman','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(155,180,1,'Direktorat Metrologi Bandung','Jl. Pasteur No. 27 Bandung','Bandung',NULL,NULL,'(022) 4203597',NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(156,183,1,'ELCORPS','Jl. Cigondewah Kaler Bandung Kulon','BANDUNG',NULL,'Bastomi febrianto','085956426874','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(157,184,1,'Epicentrum Mall','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(158,185,1,'Fakultas Teknik Universitas Nurtaino Bandung','Bandung, Jawa Barat','Bandung',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(159,186,3,'PT DWITUNGGAL BUMIASRI','Jl. Cimbuleuit No.42 A','Bandung',NULL,'YOSI SAPUTRA','0227507000','[\"1\",\"7\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(160,187,1,'Galeri Shoofis Wallpaper','Hegar Asri Residence, Jl. R.A.A Wiranata Kusumah No.Ruko 2, Baleendah, Bandung, West Java 40375','Kabupaten Bandung',NULL,'Ade firmansyah','087839404085','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(161,188,1,'Grand Setiabudi Hotel','Setiabudi, Bandung','Bandung',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(162,189,1,'PT. Berkah Anugrah Hotel','Jl. Sersan Bajuri No. 102 Cihideung, Kab Bandung','BANDUNG',NULL,NULL,'(022) 87802745','[\"1\",\"7\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(163,190,3,'Indonesia Business Links','MENARA KARTIKA CHANDRA, JL. GATOT SUBROTO KAV. 18-20, JAKARTA','JAKARTA',NULL,'TATANG SOLIHIN','081316470252','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(164,191,8,'Institut Agama Islam Negeri NAD','Aceh','Lhokseumawe',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(165,192,3,'Jakarta Service Office','Jakarta','Jakarta',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(166,193,3,'Kabupaten Sorong','Jl. Klamono Km 24. Komp. Kantor Bupati Klamesen Kabupaten Sorong','Kabupaten Sorong, Papua Barat',NULL,'Pak Ronald','081248719906',NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(167,195,5,'Kementerian Luar Negeri','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(168,196,8,'KEMENTERIAN PEMBERDAYAAN PEREMPUAN DAN PERLINDUNGAN ANAK REPUBLIK INDONESIA','Jl. Medan Merdeka Barat No.15, RT.2/RW.3, Gambir, Kecamatan Gambir, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10110','Jakarta',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(169,197,3,'Kementrian ESDM','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(170,198,3,'Kertabumi Power','JL. MATRAMAN RAYA NO.19 RT 01/04, PALMERAH, MATRAMAN, JAKARTA TIMUR 13140','KOTA JAKARTA TIMUR',NULL,'MZA ARIFIN',NULL,'[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(171,199,3,'Kids Republic Jakarta','JL. CIPINANG BALI 1 NO.5A, JATINEGARA','KOTA JAKARTA TIMUR',NULL,'ZITA ANJANI','811113108','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(172,200,3,'Klinik Hadiana Sehat','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(173,201,3,'Klinik Siliwangi Kesdam Cimahi','Jl. Stasiun C3 No.181 Cimahi','CIMAHI',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(174,202,8,'Klinik Wirasakti Kesdam 3 Siliwangi','Jl. Aceh Bandung','CIMAHI',NULL,NULL,NULL,'[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(175,203,3,'Komisi Pemilihan Umum','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(176,204,5,'Komunitas Foto Budaya','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(177,205,4,'Komunitas Macro PhotoGraphy','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(178,206,4,'Komunitas Muslim','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(179,207,4,'Komunitas Pendidikan Kita','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(180,208,5,'KOPERASI ASTRA INTERNATIONAL','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(181,209,3,'Koperasi Bali Surya Dewata','Bali','Bali',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(182,210,3,'Koperasi BMT EL Bangkit','0','0','Yudi Yusaminudin','Yudi Yusaminudin',NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(183,211,3,'Koperasi Cipta Inti Samudra \"CIS\"','Jalan Permata Arcamanik Blok A No.3 RT.007 RW.008','KOTA BANDUNG','Maylan','Maylan','022-20542999','[\"2\"]','{\"website\":\"https:\\/\\/cis.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(184,212,3,'Koperasi Energi Mulia Nusantara','Ruko Kebayoran Arcade 5 Blok F.3/15 Lt. 3, RT. 003/ RW. 004 Jl. CBD Kebayoran Boulevard Bintaro Jaya Sektor 7, Kelurahan Pondok Pucung, Kecamatan Pondok Aren, Tangerang Selatan, Banten','Banten','Beta Ayler','Beta Ayler','0858 9030 0568','[\"2\"]','{\"website\":\"https:\\/\\/ken.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(185,213,3,'Koperasi Jasa Karyawan PT. PLN Area Bekasi dan Cikarang','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(186,214,3,'Koperasi Jasa Keuangan Syariah BMT EL Tazkiyyah','Jln.Maleer V no.20 Rt.02 Rw.01, Batununggal, Kota Bandung, Jawa Barat 40274','KOTA BANDUNG','Dr.H.Ahmad Hasan Ridwan','Dr.H.Ahmad Hasan Ridwan',NULL,'[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(187,215,3,'Koperasi Jasa Rancage Jawa Barat','Jl. Soekarno Hatta No. 729 C Bandung','KOTA BANDUNG','H.Daeng M.Makmur Thahrir,S.E','H.Daeng M.Makmur Thahrir,S.E','0896 5609 7548','[\"2\"]','{\"website\":\"https:\\/\\/rancage.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(188,216,5,'Koperasi Karyawan BGA','Jakarta Selatan','Kota Jakarta Selatan',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(189,217,3,'Koperasi Karyawan PT Seiwa Indonesia','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(190,218,9,'Koperasi Karyawan PT Transmarco Indonesia','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(191,219,9,'Koperasi Karyawan PT. RODA PRIMA LANCAR \"Kopkar RPL\"','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(192,220,9,'Koperasi Karyawan Sejahtera Mandiri BPSDM','Gedung Dinas Teknis Abdul Muis Lantai 8\r\nJl. Abdul Muis No.66 Jakarta Pusat','KOTA JAKARTA PUSAT',NULL,NULL,NULL,'[\"2\"]','{\"website\":\"https:\\/\\/ksm.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(193,221,9,'Koperasi KOPWANTERA Bekasi','Taman cikunir Indah Blok B8 No. 5 RT 03  RW 11 Kelurahan Jakamulya, Kecamatan Bekasi Selatan Kota Bekasi','Bekasi','Dra.Yatti Surtiyati Arief','Dra.Yatti Surtiyati Arief','021-8207047','[\"2\"]','{\"website\":\"https:\\/\\/kopwantera.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(194,223,9,'Koperasi Mikro Sumut','Sumatera Utara','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(195,224,3,'Koperasi Nanda Trans','Bali','Kota Denpasar',NULL,'I Gede Arya Wijaya, S.H','08970218946','[\"2\"]','{\"website\":\"https:\\/\\/nanda.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(196,225,9,'Koperasi Pegawai BPKP Provinsi Jawa Barat','Jl. Cibeureum no. 50 Bandung','KOTA BANDUNG','Imam Himawan Suhendra','Imam Himawan Suhendra',NULL,'[\"2\"]','{\"website\":\"https:\\/\\/bpkpjabar.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(197,226,9,'Koperasi PRIMKOPAU Pukadara Subang','Kalijati Barat, Kalijati, Kalijati Bar., Subang, Kabupaten Subang, Jawa Barat 41271','Subang','Kapten Lek Hermawan','Kapten Lek Hermawan',NULL,'[\"2\"]','{\"website\":\"https:\\/\\/primkopausubang.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(198,227,9,'Koperasi Pusat Koperasi Wanita (PUSKOWAN) Jawa Barat','Jl. Soekarno-Hatta No. 641 Kel. Sukapura, Kec. Kiaracondong, Kota Bandung, Jawa Barat','KOTA BANDUNG','Hj.Dewi Pancanita Arifin,S.E','Hj.Dewi Pancanita Arifin,S.E',NULL,'[\"2\"]','{\"website\":\"https:\\/\\/puskowan.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(199,228,3,'Koperasi Selaras','Graha Selaras, Jalan KH.Mas Mansyur No. 59, Jakarta Pusat','Kota Jakarta Pusat','Alexander GEE','Kadek Aldo Nagata','02131909100','[\"2\"]','{\"website\":\"https:\\/\\/selaras.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(200,229,3,'Koperasi Simpan Pinjam Pembiayaan Syariah Perambabulan','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(201,230,3,'Koperasi Wanita Binangkit','Jalan Soekarno-Hatta 705 Bandung','KOTA BANDUNG','Robyana Mertasudira','Robyana Mertasudira',NULL,'[\"2\"]','{\"website\":\"https:\\/\\/binangkit.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(202,231,9,'KOPTI Kabupaten Kuningan','Kuningan','Kabupaten kuningan',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(203,232,9,'Korps Pasukan Khas TNI AU','JL. HERCULES II KOMPLEK PANGKALAN UDARA SULAIMAN MARGAHAYU, BANDUNG','KOTA BANDUNG',NULL,'DEDDY BADARUDIN','081809374436','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(204,233,3,'Koyota Industrial Group','Jalan Raya Narogong No.91, Bekasi - Indonesia','Bekasi',NULL,'Hani Yahya Sieban','(021) 82633333','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(205,234,9,'Krista Kosmetik','Ruko Bumi Fajar Indah, Jl. Fajar Niaga No. 63,CJakasampurna, Bekasi','Bekasi',NULL,NULL,NULL,'[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(206,235,9,'KUKM PERINDAG KOTA BANDUNG','Bandung','Kota Bandung',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(207,236,5,'Ladja Hotel','Jl.YC.Oevang Uray No.33, Kec.Sintang, Kabupaten Sintang, Kalimantan Barat','Kalimantan',NULL,'Engel Bertrus Bonny','082231111135','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(208,237,9,'Laely Photography','0','0',NULL,'LAELY INDAH LESTARI','82118228818','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(209,238,9,'Lembaga Penjaminan Mutu Pendidikan','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(210,239,9,'LPMP Halmahera','Halmahera','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(211,240,9,'LPMP Jawa Barat','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(212,241,9,'SMAN 1 Garut','Garut','Kabupaten Garut',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(213,242,3,'PT. Satya Parahyangan Resort','Jl. Raya Parahyangan Km 1,8 Kota Baru Parahyangan Padalarang, Kab Bandung Barat','Padalarang',NULL,'Bunga Faity P','022 6803778','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(214,243,3,'Max Q Group','Jl. Asia Afrika No. 133-137 Wisma Monex Lt 9 , Kebon Pisang, Sumur Bandung','BANDUNG',NULL,'ronald atila','08164211212','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(215,244,3,'Megapolitan Mall','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(216,245,5,'MIZAN','JL. Jakarta No.24 Kebonwaru, Bandung','BANDUNG',NULL,'Dedi Suprianto','081321317493','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(217,246,3,'MQ Indonesia','Jl. Gegerkalong Girang, Isola, Kec. Sukasari, Kota Bandung, Jawa Barat','BANDUNG',NULL,'Boyke febrian Moh','08557897999','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(218,247,5,'Pemerintah Maluku Tenggara','Jalan Langgur Wearlilir Kabupaten Maluku Tenggara, Provinsi Maluku','Maluku Tenggara',NULL,'Savsavubun',NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(219,250,5,'PLN (PERSERO) UIP2B UP2B - JAWA BARAT','JL. Moh. Toha KM. 4, Komplek PLN Cigereleng, Bandung, 40255','BANDUNG',NULL,NULL,NULL,'[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(220,251,7,'PLN Persero','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(221,252,6,'Polres Kota Payakumbuh','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(222,253,3,'Poltekpar Medan','Jl. Rumah Sakit H. No.12, Kenangan Baru, Kec. Percut Sei Tuan, Kabupaten Deli Serdang, Sumatera Utara 20371','Medan',NULL,NULL,'62616632182','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(223,254,7,'Portal Berita Nusaina News','JL. RAYA KEBUN CENGKEH, AMBON MALUKU','Ambon',NULL,'MUHAMMAD RUMUAR,S.SOS','81247888034','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(224,255,3,'Portal Maroni - Radio','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(225,256,3,'PROV. JAWA BARAT','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(226,258,5,'PT Maha Parma Indonesia','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(227,260,5,'PT Solusindo Jaya Abadi','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(228,261,3,'PT. Aditya Sejahtera Mandiri','Jl. Kebayunan No.18 Tapas, Cimanggis, Depok Jawa Barat','depok',NULL,'Minda P pilson','0817170036','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(229,262,3,'PT. Astellas Indonesia','WISMA KYOEI PRINCE LT.11 JL. JEND. SUDIRMAN KAV 3 KARET TENGSIN TANAH ABANG, JAKARTA PUSAT 10220','KOTA JAKARTA PUSAT',NULL,'ERPANI TAUFAN','021-5724344','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(230,263,3,'PT. Aurora Indo Asia','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(231,264,3,'PT. Axis Cipta Kreasi','JL. GUNUNG SAHARI RAYA NO. 1 JAKARTA PUSAT','KOTA JAKARTA PUSAT',NULL,'VICTOR WILIAM','021-62317050','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(232,265,3,'PT. Batara Titan Kencana - Xtrans Travel','JL. WIROSARI 1, BLORA, MENTENG, JAKARTA PUSAT','KOTA JAKARTA PUSAT',NULL,'Firmansyah','081360310830','[\"1\",\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(233,266,3,'PT. Batin Eka Perkasa','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(234,267,3,'PT. Batununggal Indah Perkasa','Jl. Batununggal Indah XI No.2','BANDUNG',NULL,'Yosi Saputra','022 7507000','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(235,268,5,'PT. Bongman International','Jl. Dahlia I Blok D2 No. 1 Taman Modern, Cakung, jaktim','Jakarta',NULL,'Mr. KIM','0214682 6682','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(236,269,3,'PT. Bumi Inti Selaras','JL. RAYA PERJUANGAN NO.1 KOMP.KEDOYA CENTER BLOK D-7, JAKARTA BARAT','Jakarta',NULL,'OSCAR WIRABUANA','08138758758','[\"1\"]','{\"website\":\"www.ptbis.co.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(237,270,3,'PT. BUMN Hijau Lestari','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(238,271,3,'PT. Delapan Kargo Ekspres','JL. HUSEIN SASTRANEGARA RUKO8C DUTA GARDEN, TANGERANG','KOTA TANGERANG',NULL,'INDAH TRI WAHYUNI',NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(239,272,3,'PT. Eska Tours - eskatours Travel','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(240,273,3,'PT. Geo Ace','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(241,274,3,'PT. Heikel Media','Jl. Banyak Niaga Kulon No.7, Cipeundeuy, Padalarang, Kabupaten Bandung Barat, Jawa Barat 40553','BANDUNG',NULL,'Revi Kuswara','0858 61961290','[\"1\"]','{\"website\":\"www.heikelmedia.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(242,275,3,'PT. Hijrah Modal Mikro','JL. RS FATMAWATI KOMP. DUTA MAS BLOK D2 NO.9-10 ITC, FATMAWATI, JAKARTA SELATAN','KOTA JAKARTA SELATAN',NULL,'MUHAMAD ARFAN AMARULLAH',NULL,'[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(243,277,3,'PT. Inti Bagas Perkasa','Jl. Kuningan Raya No.28, Antapani Tengah, Kec. Antapani, Kota Bandung, Jawa Barat 40291','BANDUNG',NULL,'Priyono, SE, Saptotanojo H. SE','0233881016','[\"1\"]','{\"website\":\"www.intibagasperkasa.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(244,278,3,'PT. Jakarta Jetset Power System','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(245,279,3,'PT. Jasa Marga','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(246,280,3,'PT. LAPI ITB','Gedung B ITB, Jl. Ganesa No.15-B, Lb. Siliwangi, Kecamatan Coblong, Kota Bandung, Jawa Barat 40132','Kota Bandung','Muhammad Hariyadi Setiawan','Yuli','022 2534178','[\"1\"]','{\"website\":\"lapi-itb.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,1),
(247,282,3,'PT. Lapisindo','JL. JEND SUDIRMAN KAV 3, KARET TENGSIN, TANAH ABANG, JAKARTA','Jakarta',NULL,'RAPINDO HUTAGALUNG','021194370','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(248,283,3,'PT. LASER','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(249,284,3,'PT. Mandira Dian Semesta','JL. Jakarta No.24 Kebonwaru, Bandung','BANDUNG',NULL,'Dedi Suprianto','081321317493','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(250,285,3,'PT. Manito World','JL. SILIWANGI KAMPUNG BENDA RT 01/01, DS.BENDA KEC. CICURUG, SUKABUMI','Sukabumi',NULL,'DEDI ASDENI','0266-6721301-05','[\"1\"]','{\"website\":\"WWW.MANITOWORLD.COM\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(251,286,3,'PT. Martakusuma Sakti','MENARA KARYA 28TH FLOOR JL. HR. RASUNA SAID BLOK X-5, KAV 1-2','Jakarta',NULL,'SANDY MARTAKUSUMA','087885202900','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(252,287,3,'PT. Masri M Syariah','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(253,288,3,'PT. Nano Coating Indonesia','KAWASAN INDUSTRI JABABEKA II JL. INDUSTRI SELATAN 7 BLOK FF 1C,','cikarang',NULL,'ANTONIUS JAKA SATWANTO','08129567634','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(254,289,3,'PT. Pasundan Utama Televisi (STV Bandung)','KOMP. SETRASARI MALL BLOK B4 NO.68 BANDUNG','BANDUNG',NULL,'SATRIO ARIWIBOWO','08122038877','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(255,290,3,'PT. Portibitrans Nusaindo Express','SENATAMA BUILDING 3RD FL, UNIT 302 JL. KWITANG RAYA NO. 8','Jakarta',NULL,'DRS. PANGIHUTAN S','0217897522','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(256,292,3,'REMINDO PRIMA MITRA','Jl. Gunung Sahari 2/14 E Jakarta Pusat 10610  - Indonesia','KOTA JAKARTA PUSAT',NULL,'Engkun Kurniadi, Arief Waskito, Laurencia','081 9098 89190, 081 944997770','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(257,293,3,'PT. Rudolf Chemicals','JABABEKA INDUSTRIAL PARK KAV.K-2 FGH, CIKARANG','cikarang',NULL,'KURNIAWAN HENDRIANTO','08154558208',NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(258,294,3,'PT. Salisma Group','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(259,295,3,'PT. Sandin Group','SENATAMA BUILDING 3RD FL, UNIT 302 JL. KWITANG RAYA NO. 8 JAKARTA','Jakarta',NULL,'R. Royce Granda','0817833070','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(260,296,3,'PT. Shirotol Jannah','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(261,297,3,'PT. Siwa Lima News','JL. RAYA DIPONEGORO NO. 20, LRG MAYANG AMBON','Ambon',NULL,'ONGKY LOUHENAPESSY','081293988404','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(262,298,3,'PT. Social Advisory Indonesia','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(263,299,3,'PT. Solarens Ledindo','Kaw Industri De Prima Tera B2 3B, Jl. Sapan Gede Bage Bandung','BANDUNG',NULL,'Melisa Bouginskia buser','022 87301403','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(264,300,3,'PT. Tekno Asia','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(265,301,3,'PT. Telehouse Engineering','JL. AH NASUTION NO.236','BANDUNG',NULL,'HENDRA','022 7802700','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(266,302,3,'PT. Total Guna Energi','Legenda Wisata Zona Marcopolo Blok A22-25 Wanaherang, Gunung Putri Bogor','KOTA BOGOR','Trinoto Widiasmoro','Danang Widyoko','081314200525','[\"1\"]','{\"website\":\"www.thegreeneffects.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(267,303,3,'Pusat Pameran Iptek Kota Baru Parahyangan','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(268,304,3,'PT. SATYARAYA KERAMINDOINDAH','Jl. Jend Sudirman Kav 1 jakarta 10220','Jakarta',NULL,'YOHANES TUTUKO WIBOWO','08569036947','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(269,305,3,'RPLDN','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(270,306,3,'Samudera Photography','JL. RADEN PATAH NO. 28, BANDUNG','KOTA BANDUNG',NULL,'AKHMAD ARDIYANSYAH','08122106647','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(271,307,3,'Sanggar Talitha','JL. TAMAN CILANDAK V/B4 JAKARTA SELATAN','JAKARTA SELATAN',NULL,'SONI SONTANI','08122035112','[\"1\"]','{\"website\":\"www.sanggartalitha.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(272,308,5,'Satuan Polisi Pamong Praja','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(273,309,3,'Sekolah Menengah Atas Situjuah','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(274,310,3,'Sekolah Menengah Kejuruan Negeri','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(275,311,3,'Sekolah tinggi Agama Islam Persis','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(276,312,3,'Sekolah Tinggi Pariwisata Bandung','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(277,313,3,'Sikallaya LTD Thailand','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(278,314,3,'Kementrian Pemberdayaan Perempuan dan Perlindungan Anak RI (KPPPA)','Jl. Medan Merdeka Barat No.15, RT.2/RW.3, Gambir, Kecamatan Gambir, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10110','Jakarta',NULL,NULL,NULL,'[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(279,315,3,'SMAN 1 Gorontalo','JL. MH. TAMRIN NO. 8 KEL. IPILO KEC KOTA TIMUR 96112','KOTA TIMUR0',NULL,'SYAIFUL KADIR,S.Pd, M. Pd','0435-821247','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(280,316,3,'SMAN 1 Kabila','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(281,317,3,'SMPN 1 Gtlo','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(282,318,3,'SMPN 1 Kabila','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(283,319,3,'SMPN 27 Siak','JL. SULTAN SYARIF KASIM DS. SABAK PERMAI KEC. SABAK AUH, SIAK, Pekanbaru, Riau 28762','Pekanbaru',NULL,'HJ. HAMIDAH ADENAN',NULL,'[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(284,321,3,'SMPN 8 Gorontalo','JL. MADURA KEC. KOTA TENGAH KODEPOS 96217','Kota Tengah',NULL,'H. MARTEHEN MARUNDENG, M.PD','0435-823329','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(285,322,3,'Sekolah Tinggi Islam persis Indonesia','Jl. Ciganitri No.2 Bojongsoang, Kab Bandung','KAB BANDUNG',NULL,'Ibu Zein',NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(286,323,5,'STIT Al- Ihsan','Jl. Adipatiagung No.40 baleendah Kab Bandung','KAB BANDUNG',NULL,'Sidik Rilman','022 594 6293','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(287,324,5,'The Majesty Hotel','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(288,325,5,'Trapperpad Sweden','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(289,326,5,'Twie E-Sport Arena','0','0',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(290,327,8,'Universitas Krisnadwipayana Jakarta','Jakarta','Jakarta',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(291,328,8,'Universitas Pendidikan Indonesia','Bandung','Kota Bandung',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(292,329,8,'Yayasan Al Ihsan','Jl. Adipatiagung No.40 baleendah Kab Bandung','KAB BANDUNG',NULL,'Sidik Rilman','022 594 6293','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(293,330,8,'Yayasan Ghuraf','JL. CIASEM II NO. 4','JAKARTA SELATAN','M.ARIEF HIDAYAT','M.ARIEF HIDAYAT','081510909099','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(294,331,3,'Yayasan Mizan Amanah','JL. POJOK UTARA II NO 45 KEC. SETIA AMANAH CIMAHI TENGAH','CIMAHI',NULL,'UNANG HENDRAYANA','022-92598858','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(295,332,4,'Zelza Bedlinen Product','0','0',NULL,'BRIZALDY ARIFIN','081911793592','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(296,334,7,'Koperasi Sehat Sejahtera','Jalan Kesambi Baru No. 2B, Kecamatan Kesambi, Kota Cirebon, Jawa Barat, 45132','Cirebon',NULL,'Bu Heni','085224695256','[\"3\"]','{\"website\":\"sehatsejahtera.koperasijabar.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(297,337,7,'KOPERASI ARTO MULYO LESTARI','Jalan Pantai Indah Kapuk II, The Centro Metrobroadway Blok B55','Jakarta','LIE ARIESANDI SETYONO','Candra','087860204678','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(298,338,7,'Koperasi Karyawan RSBD','\"RSIA Duren Tiga \r\nJl Duren Tiga Raya No 5, Pancoran, Jakarta Selatan\"','KOTA JAKARTA SELATAN','Renold Darmansyah','Renold Darmansyah','0','[\"2\"]','{\"website\":\"https:\\/\\/rsbd.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(299,339,7,'Koperasi Karyawan PT Intercallin','Jalan Daan Mogot KM 11, Cengkareng, Jakarta Barat','Jakarta Barat','Asep Sarif Hidayat','Asep Sarif Hidayat','021-5447253','[\"2\"]','{\"website\":\"https:\\/\\/intercallin.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(300,340,7,'Koperasi PKK Nusa Indah','Jl.anggrek I Kp.sukasari rt04/01 Ds.ciwareng Kec.babakancikao','Purwakarta','Intan','Intan','087879749674','[\"2\"]','{\"website\":\"https:\\/\\/pkknusaindah.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(301,341,7,'Koperasi PKRI Sejahtera Pasawahan','Jalan Terusan Kapten Halim Desa parakan Salam No 49, Kecamatan Pondok Salam Kabupaten Purwakarta','Purwakarta','0','0','08190946060','[\"2\"]','{\"website\":\"https:\\/\\/kprisejahtera.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(302,342,8,'Koperasi Gapoktan Mukti Jaya','KP. KRAJAN I RT 001/RW 001, Kabupaten Purwakarta','Purwakarta','0','0','0','[\"2\"]','{\"website\":\"https:\\/\\/muktijaya.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(303,343,3,'Koperasi Solidaritas Agape','Jl. Beringin Rt.006 Rw. 002 Desa Jatimekar, Kabupaten Purwakarta','Purwakarta','Ibu Dwi','Ibu Dwi','082135032955','[\"2\"]','{\"website\":\"https:\\/\\/koperasiagape.smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(304,344,3,'Koperasi Tahu Tempe Indonesia Kab.Kuningan','Jl. Jend. Sudirman No. 128 Kuningan','Kabupaten Kuningan','H.Jono Sugiono','H.Jono Sugiono','0','[\"2\"]','{\"website\":\"https:\\/\\/koptikuningan.aplikasikoperasi.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(305,347,5,'KANGAROO TRAVEL&TRANSPORT','JL. DR. JUNJUNAN NO.24, BANDUNG','KOTA BANDUNG',NULL,'BENNY LIONG','811171227','[\"1\",\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(306,348,3,'BPMPD KALIMANTAN SELATAN','JL. DI. PANJAITAN, BANJARMASIN, KALIMANTAN SELATAN','BANJARMASIN',NULL,NULL,NULL,'[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(307,351,8,'EMWE CONCEPT','MOCH IWAN NO.6','KOTA BANDUNG',NULL,'HARI MUHAMMAD RAMDHAN','022-5222486','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(308,361,8,'CV Andista Intermedia Pratama','Jalan Pocut Baren No. 45B, Banda Aceh','Aceh',NULL,'Marfikar','+62 812 6984 230','[\"1\"]','{\"website\":\"www.acehbesarkab.go.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(309,362,7,'Census Of Marine Life Indonesia','Gd. Balitbang KP lt.1, Jl. Pasir Putih No 1, Ancol Timur, Jakarta Utara','KOTA JAKARTA UTARA',NULL,'Dr. Tonny Wagey/Arlyana Meiliza','021-64717215',NULL,'{\"website\":\"www.coml-indonesia.org\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(310,363,7,'Keris Foto','Permata Residence B4 Jl. H. Bara. Pangkalan Jati Baru. Cinere 16154','JAKARTA',NULL,'Cakra Wira Wiyata','08129376566','[\"1\"]','{\"website\":\"www.kerisfoto.com\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(311,364,7,'KORPASKHAS ANGKATAN UDARA','JL. HERCULER 2 NO.3 LANUD SULAIMAN MARGAHAYU, BANDUNG','KOTA BANDUNG',NULL,'KAPTEN LEK ILHAM PERMANA','08112129259','[\"1\"]','{\"website\":\"WWW.PASKHAS.MIL.ID\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(312,365,3,'PT. GLOBAL MULTIMEDIA NETWORK','LYMAN WING WISMA 46 KOTA BNI, JL.JEND. SUDIRMAAN KAV-1, JAKARTA PUSAT','KOTA JAKARTA PUSAT',NULL,'JUNAEDY HERTANTO','021 5723777','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(313,366,9,'PT. BANDUNG MEKTAN TEKNIK','JL. INFANTRI 145 B CILAME PERMAI BANDUNG BARAT','KOTA BANDUNG',NULL,'ASDAR PURNAMA','0','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(314,367,3,'PRODEC STP BANDUNG','JL. DR. SETIABUDHI NO.186 BANDUNG 40141','KOTA BANDUNG',NULL,'ZULKIFLI HARHAP','0222036067','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(315,368,3,'PT. CENTURY21','JL. SUNDA NO. 55 BANDUNG','KOTA BANDUNG',NULL,NULL,'FUJI ISKANDAR','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(316,369,9,'THE MINISTRY OF HEALTH OF DEMOCRATIC REPUBLIC OF TIMOR-LESTE','PALACIO DES CINZAS, RUA DE CAICOLI, CAIXA POSTAL 374, DILI, TIMOR-LESTE','RUA DE CAICOLI',NULL,'DUARTE XIMENES,SKM.,MM',NULL,'[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(317,370,9,'FS925SILVER','KELAPA GADING JAKARTA UTARA','KOTA JAKARTA UTARA',NULL,'FRANKLIN MANGUNDAP','8179266677','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(318,371,3,'CROPPERMEDIA','JL. MELAWAI RAYA NO.111, JAKARTA 12130','KOTA JAKARTA PUSAT',NULL,'SATRIA ARI WIBOWO','83875459718',NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(319,372,9,'PT. TIRTA BHUVANA ABADI','JAKARTA','JAKARTA',NULL,NULL,NULL,'[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(320,373,9,'MUSEUM PENDIDIKAN NASIONAL','JL. DR.SETIABUDHI NO.229 BANDUNG 40154','KOTA BANDUNG',NULL,'DR. LILI ADI WIBOWO, S.SOS., SP.PD.,M.M',NULL,'[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(321,374,9,'PT RANCANG BUANA PERSADA','RUKO VILLA TELAGA CIBINONG NO.3 RT 02/02 KEL. HARAPAN JAYA KEC. CIBINONG','BOGOR',NULL,'ANGGA DHARMAWANGSYAH',NULL,'[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(322,375,9,'PT VELVET TEKSTIL INDONESIA','JL. RAYA LASWI NO.22 MAJALAYA','MAJALAYA',NULL,'SUJIWO WINARNO','81279747999','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(323,376,9,'GAGASAN RIAU','JL. SIDODADI NO.34, TENGKARENG UTARA, HARAPAN RAYA','Pekanbaru',NULL,'ADY KUSWANTO','8527295140','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(324,377,1,'Dinas Koperasi Kota Bandung','Jl. Kawaluyaan No.2 Bandung','KOTA BANDUNG',NULL,NULL,NULL,'[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(325,378,3,'Balai Diklat Tambang Bawah Tanah Maluku Tengara','Jln.Sokearno Hatta, Sawahlunto, Sumatra Barat','Sawahlunto',NULL,NULL,NULL,'[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(326,379,3,'PT. MULTI HANNA KREASINDO','JL RAYA NAROGONG KM. 12 RT 03/02 NO.23, CIKIWUL, BANTAR GEBANG, BEKASI','Bekasi',NULL,'ARCAYUDI',NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(327,381,3,'Bukit Biak','JL. TEBET BARAT X NO.33, TEBET, JAKARTA SELATAN','KOTA JAKARTA SELATAN','MUHAMMAD ARBI','MUHAMMAD ARBI','081289171867','[\"1\",\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(328,384,3,'Djautama Group','Jl. Teuku Cik Ditiro I No.6 Menteng Jakarta Pusat','KOTA JAKARTA PUSAT',NULL,'Tirta Atmadja Widjaja','021 3190112','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(329,385,1,'Dinas Koperasi dan Usaha Kecil Kab Bandung','Komplek Pemda Kabupaten Bandung, JL. Raya Soreang, KM. 17, Pamekaran, Soreang, Bandung','Soreang',NULL,'Rahadian','087822491879','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(330,386,4,'Kementrian Perhutanan Republik Indonesia','Kementrian Perhutanan Republik Indonesia 	Jl. Gatot Subroto Gd. Manggala Wanabakti Lt 6 Blok I Jakarta Pusat','KOTA JAKARTA PUSAT',NULL,NULL,'0215730191','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(331,387,3,'PT. EKO WANATUR INDONESIA','JL. PERMATA RAYA NO.8, TAMAN ROYAL, TANAH TINGGI, TANGERANG','KOTA TANGERANG',NULL,'JUMARI','021-80682223','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(332,388,3,'PT. BLUE ROYALE PRODUCTION','GRAND WIJAYA CENTER H-31, KEBAYORAN, JAKARTA SELATAN','KOTA JAKARTA SELATAN',NULL,'ADRIAN HERBOWO','0811881703','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(333,389,8,'Setda TK I Prov Jawa Barat (Bag Biro pemerintah dan Kerjasama Luar Negeri)','Jl. Diponegoro No. 22 Bandung Wetan Kota Bandung','KOTA BANDUNG',NULL,NULL,NULL,'[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(334,390,3,'PT. COGINDO DAYA BERSAMA','Jl. Raya Pasar Minggu No. 190, Pejaten Barat, Pasar Minggu, RT.1/RW.1, Pejaten Bar., Kec. Ps. Minggu, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12510','KOTA JAKARTA SELATAN',NULL,NULL,'085212053966','[\"1\",\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,1),
(335,392,3,'PT.Sekarmenuh Eventama','Jl. Tanimbar No.45, Dauh Puri Klod, Kec. Denpasar Bar., Kota Denpasar, Bali 80114','Denpasar',NULL,'Pak Naindra','081805574949','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(336,393,3,'Rully P','Jl. Sena 2 No. 23 Cibinong Kab Bogor','Bogor',NULL,'Pak Rully','082126789980','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,1),
(337,394,5,'Speakoud','???','???','???','Pak Arifin','08121085805','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,NULL),
(338,400,3,'Lanud AU Sulaiman','Jalan Otter No.7, Lanud Sulaiman, Margahayu, Bandung.','Bandung',NULL,'Ilham Permana','081809737209','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',5,5),
(339,422,3,'KSPPS CITRA PASUNDAN UTAMA','Jalan Sumatera No 41, Kota Bandung','Bandung',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',NULL,NULL),
(340,423,3,'Koperasi Hasna Medika Cirebon','Jl. Raden Gilap No. 08, Palimanan Timur, Palimanan, Kabupaten Cirebon, Jawa Barat','Kabupaten Cirebon',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',NULL,NULL),
(341,424,3,'PT. SATYA LANGGENG SENTOSA','GEDUNG QUADRA CENTRUM E1.3 JL. TAMAN ARIES Blok - No.- RT:009 RW:003 Kel.MERUYA UTARA, Kec.KEMBANGAN, Kota/Kab.JAKARTA BARAT, DKI JAKARTA, 11620','Jakarta','Boris','Alvin','085887265218',NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',333,NULL),
(342,425,3,'KSP AMAN DIGITAL INDONESIA','Gedung Cohive 101, Jl. Mega Kuningan Barat, Jakarta Selatan','Jakarta Selatan',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:48','2025-12-26 02:03:48',NULL,NULL),
(343,426,3,'KOPERASI SEMPEKAT SEMPAWAT','Jl. Paulus Doy Lambeng RT. 02, Kelurahan Simpang Raya, Kecamatan Barong Tongkok, Kabupaten Kutai Barat, Provinsi Kalimantan Timur, Indonesia','Kabupaten Kutai Barat',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(344,427,5,'PT. LYMAN DEVINDO','Lyman Wing Kota BNI, Jl. Jend Sudirman Kav.1 Karet Tengsin, Tanah Abang - Jakarta Pusat 10220','Jakarta','Boris','Yuli',NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',333,NULL),
(345,428,5,'KOPKAR PRIMADONA','Jl WJ Lalamentik No 102, Kelurahan Oebobo, Kupang, NTT','Kupang',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(346,430,3,'PT. KARYA IMTAQ','Jl. Perintis Kemerdekaan No. 2-4 Bandung','Bandung',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(347,432,3,'BRI Kanwil Malang','Malang','Malang',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(348,433,3,'Koperasi Berkarya Sukses Sejahtera','ATRIA @Sudirman Lt 16, Jl. Jend. Sudirman No Kav. 33A, Tanah Abang, Jakarta Pusat, 10220','Bandung',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(349,434,3,'PT. Yasufuku Indonesia','Jalan Jabeka KM 10, Kab Bekasi','Kabupaten Bekasi',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(350,435,3,'BRI Kanwil Banjarmasin','Banjarmasin, Kalimantan Selatan','Banjarmasin',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(351,436,1,'KOPERASI KARYAWAN RUMAH SAKIT PUPUK KALTIM','Jalan Oksigen No. 1 Komplek Rumah Sakit Pupuk Kaltim, Bontang','Bontang',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(352,437,1,'Koperasi Konsumen Syariah Griya Trust','Jl. WR. Supratman No. 4 Mataram 83127','Mataram',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(353,442,3,'Koperasi Karyawan Pacific Place Jakarta','Pacific Place Mall Lt. B1 SCBD Jl. Jend. Sudirman Kav. 52-53 Senayan, Kebayoran Baru, Jakarta Selatan','Jakarta Selatan','Tri Shanti','Nurhamsyah','087736170436','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,5),
(354,443,5,'KOPERASI KONSUMEN PERANGKAT DESA INDONESIA (KOPERDES)','Jl. Budaya RT.004 RW.005 Kel. Cililitan Kec. Kramat Jati, Jakarta Timur','Jakarta Timur',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(355,453,9,'KSPPS BMT ANFA\' NU','Jalan Kalimas Dusun Kauman RT 04 RW 04 Pucang','Kabupaten Magelang',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(356,454,9,'Kopkarco','Jl Jababeka Raya Blok F 19-28, Cikarang Utara â€“ Bekasi','Bekasi',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(357,455,3,'KOPERASI TRI SUKSES MAKMUR','Bintaro Utama 3 Blok AC No. 1, Pondok Aren, Tangerang Selatan','Tangerang',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(358,456,1,'KOPERASI KARYAWAN \"UNTUNG BERSAMA\"','Kalilom Lor Indah Gg. Mangga 17 A, Tanah Kali Kedinding, Kenjeran, Surabaya','Surabaya',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(359,457,4,'KOPERASI KARYAWAN RS GATOEL','Jl. Empunala 279D RT.001 RW.001, Balongsari, Magersari, Mojokerto','Mojokerto',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(360,459,3,'KOPERASI KARYAWAN AQUA CITEUREUP','Jl. Mercedes Benz RT 04 RW 04 Desa Cicadas Kecamatan gunung Putri Kabupaten Bogor','Bogor',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(361,460,3,'KOPERASI SERBA USAHA PUTRA MITRA MANDIRI','Jl. Hayam Wuruk No.128, Ling.Merapa, Kel. Sempulsari Kec.Kaliwates, Kab. Jember','Kab. Jember',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(362,461,8,'Smartcoop','Jl. Batununggal Indah Raya No. 429, Batununggal, Kec. Bandung Kidul, Kota Bandung, Jawa Barat 40266','Bandung','Muhammad Ihsan Firdaus','Shofia Zahra','081322185293','[\"3\"]','{\"website\":\"smartcoop.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',0,0,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(363,463,3,'KOPERASI PELITA INDOSEJAHTERA','Pasar Fresh Market PIK B No. 1 dan 2, Jakarta Utara, DKI Jakarta','Jakarta Utara',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(364,464,9,'KOPERASI KELUARGA KARUNIA SEJAHTERA (K3S)','Jl. Raya Sukomulyo KM.24, Gresik','Gresik',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(365,465,3,'OPERASI PEGAWAI KANTOR PUSAT DIREKTORAT JENDERAL BEA DAN CUKAI','Jl. Jend. Ahmad Yani Jakarta 13230 Kotak Pos 225, Jakarta Timur 13013','Jakarta Timur',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(366,466,5,'Perumda Paljaya','Jl. Sultan Agung No.1, RT.12/RW.6, Guntur, Kecamatan Setiabudi, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12980','DKI Jakarta',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(367,467,5,'KOPERASI SUMBER REJEKI','Jl. Cemara Raya No. 5D, Cengkareng, Jakarta Barat','Jakarta Barat',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(368,468,9,'KOPERASI SIMPAN PINJAM USAHA MANDIRI','Jl. Pemuda, Ruko Graha Mas Pemuda Blok AD 10-11, Jakarta Timur','Jakarta Timur',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(369,469,9,'KOPERASI SIMPAN PINJAM BUANA ARTHA SEJAHTERA','di Jl. Candi Kencana Raya No.102 RT.03 RW.09 Kel. Kalipancur, Kec. Ngaliyan, Semarang','Semarang',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(370,470,3,'PT MIDTRANS INDONESIA','BANDUNG','Kab Bandung','wildan','pic','34434',NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,1,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,1),
(371,471,9,'KOPERASI KARYAWAN MAKMUR','Jl. Raya Cileungsi - Jonggol KM.6 No.4, Kel. Gandoang, Kec. Cileungsi,\r\nKab. Bogor, Jawa Bara','Bogor',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(372,473,4,'KOPERASI PRIMER PRAJAMUKTI 1 KEMENDAGRI','Jl. Medan Merdeka Utara No. 7, Jakarta','JAKARTA',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(373,474,3,'PT. Bank Rakyat Indonesia (Persero) Tbk','Jl. Jenderal Sudirman Kav. 44-46, Jakarta - 10210','DKI Jakarta',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(374,475,9,'KOPERASI SYARIAH SUARA HATI','Ruko Citra Harmoni Blok TC â€“ 09, Kecamatan Taman, Kabupaten Sidoarjo','Jawa Timur',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(375,476,9,'Koperasi Jasa Wisata Nusantara','Jl. Mentor 1 No. 01, Kel. Sukaraja, Kec. Cicendo, Kota Bandung','Bandung',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(376,477,8,'KOPONTREN AL-MUHAJIRIN','Jl. Veteran No. 155 RT.041/RW.005, Kab. Purwakarta','Purwakarta',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(377,478,9,'KOPERASI PRIMKOPOL BATANG','Jl. Gajah Mada No.200, Batang','Jawa Timur',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(378,479,9,'KOPERASI KARYAWAN MITRA MAKMUR','Jl. Tambak Aji II/8 Semarang','Semarang',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(379,480,5,'Sampoerna Strategic','Gedung Sampoerna Strategic Lantai Ground, Jalan Jend. Sudirman Kav 45-46, Jakarta Selatan','Jakarta Selatan',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(380,481,3,'KOPERASI PEGAWAI PT. SINKORA INDONESIA LESTARI','Perum Bumi Cagak Mekar RT.072 RW. 010, Desa Curugrendeng, Kec. Jalancagak, Kab. Subang','Subang',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(381,482,3,'KPRI KOGUSDA KOTA SOLOK','Jl. Dt. Perpatih Nan Sabatang No.26, Kel. Air Mati, Kec. Tanjung Barapan, Kota Solok','Solok',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(382,483,9,'KOPERASI PRODUSEN PESAT UNIFIT INDONESIA','KOMPLEK VILLA PERMATA D8 NO.12 RT.001 RW.001, BINONG, CURUG, KAB. TANGERANG, BANTEN','Tangerang',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(383,484,9,'KOPERASI \"MAGERI URIP\" KOPKAMU','Jalan Taman Siswa 149, Yogyakarta','Yogyakarta',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(384,485,9,'KOPERASI BISSMIKA USAHA MAKMUR NUSANTARA (BUMN)','Jl. I Gusti Ngurah Rai Rukan No. 07 RT/RW 02/07 Kec. Duren Sawit Kel. Pondok Kopi Jakarta Timur 13460','Jakarta',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(385,486,9,'KOPERASI PEMASARAN PEGAWAI NEGERI SYARIAH DINAS KESEHATAN KAB. AGAM (KPPNS DINAS KESEHATAN KAB.AGAM)','Jl. VII Suku Pasar Inpres Padang Baru, Surabayo, Lubuk Basung, Kab. Agam, Sumatera Barat','Sumatera Barat',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(386,487,3,'KOPERASI KSP WAHANA MULIA','Jl. Paledang Kompleks Duta Marta, Kav. 17, RT 04 RW 02, Kel. Cempaka, Kec. Andir','Bandung','Suwito','Suwito','082125671957','[\"3\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(387,488,9,'KOPERASI SUMBER GRAHA SEJAHTERA','Jalan Raya Serang KM 25, Desa Tobat Balaraja, Tangerang','Tangerang',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',NULL,NULL),
(388,489,9,'Koperasi Karyawan Al Muhajirien','Jalan Cendana 6 No. 16 Perumahan Jakapermai, Kel. Jakasampurna, Kec. Bekasi Barat, Kota Bekasi','Bekasi',NULL,NULL,'021-8855758','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(389,490,3,'KSP RANGKUL TEMAN JAKARTA','Gd. Setiabudi 2 Lantai 5 Suite 502A, HR Rasuna Said Kav 62, Kel. Setiabudi Kec. Setiabudi, Jakarta Selatan','Jakarta Selatan',NULL,'Doni Yahya','08118074989','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(390,491,9,'KUD Sumber Alam','Kp. Manggis RT. 04, RW. 04, Desa Darmaga, Kecamatan Darmaga, Bogor','Bogor',NULL,'Yayat Supriyatna','082111106834','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',5,NULL),
(391,492,3,'Koperasi Produsen Karya Al Mahmud Mubarok','Perum Bale Nanjeur Kp. Karang Sari RT.01 RW.01, Desa\r\nSukamantri, Kec. Ciawi Kab. Tasikmalaya','Tasikmalaya','YAYAN NURINSAN, BE., ST., M.ENG,','Adhitya Nala Sumarlan Putra','081220923252','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',5,NULL),
(392,493,3,'KPRI Kancawinaya Klari','Jalan Raya Kosambi Duren KM 11  Kecamatan Klari Kabupaten Karawang','Karawang',NULL,'Pak Hasim','08129975256','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',5,NULL),
(393,494,9,'KOPERASI PRAJA SEJAHTERA','Jl. Awang Long, Bontang Baru, Bontang Utara, Kota Bontang, Kalimantan Timur, 75325','Kota Bontang',NULL,NULL,NULL,'[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',5,NULL),
(394,495,3,'Koperasi Bina Warga','JL. PEMUDA NO.135 RT.004 RW.O4 KRANJI, BEKASI BARAT','BEKASI BARAT',NULL,'Pak Rudi','081389163296','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',5,NULL),
(395,496,9,'PT Rajawali Nusantara','Gedung RNI, Jl. Denpasar Raya Kav DIII Kuningan Jakarta Selatan','KOTA JAKARTA SELATAN','Nurdin Iswahjudi','Adhi Nusatria','085888822865','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',5,NULL),
(396,497,3,'Victus Restaurant','1, Jl. Tebet Barat I No.4, RT.1/RW.2, West Tebet, Tebet, South Jakarta City, Jakarta 12810','KOTA JAKARTA SELATAN','Arief Hidayat','Muhammad Arsha','087725261114','[\"1\"]','{\"website\":\"victus.id\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',5,NULL),
(397,500,3,'PLN','dimana aja','dimana aja','Bu Sofa','Pak Fery','0989898','[\"1\",\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',5,5),
(398,501,9,'Dinas Pendidikan Sorong','Jalan Burung Kurana No. 01, Remu Utara, Distrik Sorong, Kota Sorong, Papua Bar. 98412','Kota Sorong',NULL,'Pak Ronald',NULL,'[\"1\"]','{\"website\":\"http:\\/\\/sagusorongkab.id\\/\",\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',5,NULL),
(399,503,9,'Kemenparekraf','Jl. Medan Merdeka Barat No. 17, RT/RW 02/03, Gambir, Daerah Khusus Ibukota Jakarta 10110, Indonesia.','Jakarta Pusat',NULL,'Arief','0811-895-6767','[\"1\",\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',5,NULL),
(400,508,3,'PT. PRIMA MULTIKARE INDONESIA','Solo','Solo',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',0,0,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(401,509,3,'POLDA JAWA BARAT','BAndung','Bandung','DUmmy',NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',0,0,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(402,510,9,'PT. KAWAMURA INDAH','Karawang','karawang','Bimo','Didi','811182898','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',0,0,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(403,511,9,'PT. MITRA SIGMA TEKINDO','Ruko Niaga Citra Gran R015 Blok- No.5, RT. 002/RW. 007, Kel. Jati Sampurna, Kec. Jati Sampurna Kota/Kab. Bekasi','Bekasi',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',0,0,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,1),
(404,512,3,'PT. MITRA KARINA JAYA','Solo','Solo',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',0,0,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(405,513,3,'KSU Rezky Mandiri Pratama','Dusun Paranga Desa Kapita Kec. Bangkala kab. Jeneponto\r\nKode Pos 92532','KABUPATEN JENEPONTO',NULL,NULL,'12345','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',5,NULL),
(406,514,9,'KOPERASI KONSUMEN PRAJA SEJAHTERA JAWA BARAT','Jl. Karawitan No 54B Bandung','Kota Bandung',NULL,NULL,'09090900','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',5,NULL),
(407,515,3,'Primkopad Korem 102 Kartika Panju Panjung','Jalan Damang Leman No. 1 Kel. Menteng, Kec. Jekan Raya','Palangkaraya','Ahmad Sogiarto','Ahmad Sogiarto','085249165149','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',5,1),
(408,516,9,'KOPERASI JASA BERSAMA WIKA REKON','JL. DR. SAHARDJO NO.149/F, MANGGARAI SELATAN, TEBET - JAKARTA SELATAN','KOTA JAKARTA SELATAN',NULL,'Ririn Andriani','081311254405','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',5,5),
(409,517,9,'Koperasi Miftahul Huda Asror','Perum Pondok Bestari Indah Blok E6/230.Ds.Landungsari Kec.Dau Kab.Malang','Kab. Malang','Rizki Ferbrianto Supriadi','Zainal Arifin','0857-9080-7794','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',5,NULL),
(410,523,3,'PT. Integra Solusi Mandiri','Jl. Aditiawarman I No.2, RT 6 RW 2,\r\nMelawai, Kec. Kby. Baru, Kota Jakarta Selatan,\r\nDaerah Khusus Ibukota Jakarta 12160.','jakarta',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(411,526,9,'PT. Starcom Solusindo','Plaza 3 Pondok Indah D-3\r\nJl. TB. Simatupang, Pondok Pinang\r\nJakarta Selatan','Jakarta',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',0,0,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',525,NULL),
(412,527,9,'PT RDN Artha Sentosa','Jln. Buah Batu No. 95-99, Cijagra, Lengkong, Kota Bandung, Jawa Barat 40262','Bandung','Aaron','Aaron','08989101690',NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(413,528,9,'PT. Garuda Dumai','Dumai','Dumai',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',0,0,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',525,NULL),
(414,529,9,'KOPERASI FAKULTAS EKONOMI UNJ','Jl. Rawamangun Muka, RT.11/RW.14, Rawamangun,  Pulo Gadung, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13220','Jakarta Timur','Ayatulloh','Ayatulloh','085722997644','[\"3\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(415,530,9,'STIESA Subang - Yayasan Sutaatmadja','Jl. Otto Iskandardinata No.76, Karanganyar, Kec. Subang, Kabupaten Subang, Jawa Barat 41211','Kab. Subang','-','Egi Milmayadi Thahir, S.P., M.M','0260411065',NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,1),
(416,531,4,'Sekretariat CCM Indonesia - Kementerian Kesehatan Republik Indonesia','Jl. Percetakan Negara No.29, Jakarta Pusat','Jakarta Pusat','-','Fattia Rakhmaliani','021-4240538',NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',0,0,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(417,532,9,'TECTONA ART GALLERY','Surabaya','Surabaya',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',0,0,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(418,533,9,'PT. ERNA DJULIAWATI (LYMAN GROUP)','Jakarta','Jakarta',NULL,NULL,NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',0,0,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(419,535,5,'Koperasi Amanah Abdi Nagari','Dinas Koperasi UKM Kabupaten Bekasi, Gedung B4 Komplek Perkantoran Pemerintah Kabupaten Bekasi, Desa Sukamahi, Kecamatan Cikarang Pusat, Kabupaten Bekasi','Kabupaten Bekasi',NULL,'Asep','087879057389','[\"3\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(420,536,9,'Koperasi Berkah Mandiri Bersama','Jalan Pelda Suryanta, Kecamatan Citamiang, Kelurahan Naggeleng, Kota Sukabumi','Sukabumi',NULL,'Parman','082312822919',NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(421,537,9,'KOPERASI KAKAPO','Jl. Afrika No.2 Kawasan Industri Krakatau, Cilegon, Banten 42443','Cilegon',NULL,'Irfan','087883052360',NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(422,538,9,'Koperasi Wisata Belanja Nusantara','Jl. Mentor 1 No. 01, Kel. Sukaraja, Kec. Cicendo, Kota Bandung','Bandug',NULL,'Gia','081223387840',NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(423,539,6,'Bpk. Jaelani','Kp. Cipeer Gg. Babakan Lamping No. 93','Kab. Bandung','Jaelani','Jaelani','081214148213',NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,1),
(424,540,3,'Bpk. Sandy','Cherry Field CLuster Crysta 99B','Bandung','Sandy','Sandy',NULL,'[\"8\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,1),
(425,541,9,'Bpk. Aufa','Tama Kopo Indah II Blok A5, No. 19','Bandung','Aufa','Aufa','08112226010','[\"8\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,1),
(426,542,9,'Hasna Noer','Jl. Cijotang Mandiri I No. 4, Cimenyan Bandung','Bandung','Hasna','Hasna','082117068574','[\"8\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(427,543,9,'Adi Widodo','Komp. Cherry Field Cluster Grand Calista No. 168D, Ciganitri, Bojongsoang, Kab. Bandung','Bandung','Adi Widodo','Adi Widodo','0811884420','[\"8\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(428,544,3,'PT. DINAMIS ARIF ABADI','Jakarta','Jakarta','Arif','Pak Fazar','0852-8835-0405','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,1,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(429,545,9,'Sonia / Alan','Apartemen The Suites Metro, Tower E Lt 5 no 10','Bandung','Sonia','Sonia','081333000812','[\"8\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(430,546,4,'LPK Yayasan Sundagaiya','Komp. Jaka Purwa B-30 RT.02 RW.05 Kujang Sari Bandung Kidul Bandung 40287','Bandung','Gilang','Gilang','081224864806','[\"8\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(431,551,3,'Dwiki / Bpk Kusmana Hartadji','Komplek Griya Bandung Indah Blok G1 No 33a','Bandung','Bpk Kusmana Hartadji','Dwiki','082216921934','[\"8\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(432,552,9,'Indah','Crysta 99E','Bandung','Indah','Indah','082129555308','[\"8\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(433,555,9,'PT. PLN INDONESIA POWER','Jl. Jend. Gatot Subroto Kav. 18 Kuningan Timur, Kecamatan Setiabudi Jakarta Selatan 12950, Indonesia','Jakarta','-','Bayu Isnanto','+62 813-2423-0009',NULL,'{\"website\":\"-\",\"linkedin\":\"-\",\"instagram\":\"-\",\"facebook\":\"-\",\"twitter\":\"-\",\"youtube\":\"-\",\"pinterest\":\"-\"}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(434,556,8,'PT. Inspiro','Jakarta','Jakarta',NULL,'Mba Else dan Pak Sherif',NULL,NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',0,0,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(435,557,9,'Aldi','Jl. Ciherang No.16, Bojongkunci, Kec. Pameungpeuk, Kabupaten Bandung, Jawa Barat 40376','Bandung','Aldi','Aldi','089655238972','[\"8\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(436,558,9,'CV. Eling Jaya Group','Jl. Sadakeling No.39, Burangrang, Kec. Lengkong, Kota Bandung, Jawa Barat 40262','Bandung','Egi','Egi','081220000608','[\"8\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(437,559,5,'CV Pembayun Sri Persada','Jl. Tarum Barat I Blok M1-97, Kaw. Industri Jababeka, Cikarang, Bekasi, Jawa Barat, Indonesia','Bekasi','Haryadi','Haryadi','081318610224','[\"3\",\"4\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(438,560,3,'CV Mutiara','Jl Terusan Pasar Baru Eabang, Desa Sabang, Kec. Bacan Timur Halmahera Selatan','Halmahera Selatan','Tauviq','Tauviq','082187751383','[\"5\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(439,561,3,'Koperasi Warga Mulya','Perum Bukit Panorama Indah Blok A3 No. 39, RT/RW 01/13, Kel. Ciseureuh, Kec. Purwakarta, Kab. Purwakarta','Kab. Purwakarta','Ibu Lilis','Ibu Lilis','081804096940','[\"3\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(440,562,9,'PT. Arkamaya','JL. KARANG ARUM 1 NO 23 RT OO5 RW OO1, KEL. MELATIWANGI, KEC. CILENGKRANG, KAB. BANDUNG JAWA BARAT','Bandung','Irfan Satriadarma','Irfan Satriadarma','08123456789','[\"8\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(441,563,9,'Falwaseda R','Jl. Kancra Dalam 1 No. 9. RT/RW 002/008, Kel. Burangrang, Kec. Lengkong','Bandung','Falwaseda','Falwaseda','087723150799','[\"8\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(442,565,6,'Arean Group CV','-','Jakarta Pusat','Muhammad Naufal','Muhammad Naufal','81339971728',NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',0,0,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(443,566,1,'PT. Surya Tama Medika','Jl. Mekar Agung No. 38, Bandung, Jawa Barat','Bandung','Imel','Imel','082320905600','[\"8\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(444,567,9,'Bpk. Azis','Jl. Renang No. 14, Cisaranten Endah, Arcamanik','Bandung','Azis','Azis','085722442442','[\"8\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(445,568,9,'Bpk. Tete','Kp. Patrolsari RW. 04, Kec. Arjasari','Bandung','Tete','Tete','082315312380','[\"8\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(446,569,9,'Koperasi Pegawai Pemerintah Provinsi (KPPP) Jawa Barat','Jl.Diponegoro No. 22 Citarum','Bandung','Teti','Teti','081320434244','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(447,570,9,'Koperasi Konsumen Sigap Nusantara KSN','Jl. Jendral Ahmad Yani No 66','Jakarta Pusat','Agus Sofyan Effend','Agus Sofyan Effend','08111377277','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(448,571,3,'Koperasi Solusi Taka','Komplek Perumahan Kideco, Batu Kajang, Paser Kaltim','Batu Kajang','Aditya','Aditya','082196523939','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(449,572,3,'KOPKAR PYID','Jl. Raya Cakung Cilincing Km. 1,5 Jakarta - 13910','Jakarta Timur','Rony Adhy Nugroho','Rony Adhy Nugroho','08568634821','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(450,573,3,'PRIMKOPTI INDRAMAYU','Jalan Gatot Subroto No. 29, Indramayu','Indramayu','Moch Zain Hasan','Moch Zain Hasan','08122191192','[\"2\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(451,574,9,'Bpk. DR. Pikir Wisnu Wijayanto','Komplek Cherry Field Cluster Crysta, Bandung','Bandung','DR. Pikir Wisnu Wijayanto','DR. Pikir Wisnu Wijayanto','085103879393','[\"8\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(452,577,9,'DIVISI GENERAL AFFAIR & ADMINISTRATION - CITCOM HARMONI BERSAMA','-','-','-','-','-','[\"1\"]','{\"website\":\"-\",\"linkedin\":\"-\",\"instagram\":\"-\",\"facebook\":\"-\",\"twitter\":\"-\",\"youtube\":\"-\",\"pinterest\":\"-\"}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,1),
(453,578,3,'Koperasi Usaha Mandiri','Jalan Villa Mas Indah Blok B2/1, Perwira, Bekasi Utara','Bekasi','Pengurus Koperasi','Pengurus Koperasi','08123456789','[\"3\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(454,579,5,'BBPJN (Balai Besar Pelaksanaan Jalan Nasional) DKI Jakarta - Jawa Barat','-','-','-','-','-',NULL,'{\"website\":\"-\",\"linkedin\":\"-\",\"instagram\":\"-\",\"facebook\":\"-\",\"twitter\":\"-\",\"youtube\":\"-\",\"pinterest\":\"-\"}',0,0,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(455,580,3,'PT Haguro Inspira Mandiri','Foresta Business Loft 1 unit 15, Jl. BSD Raya Utama, Pagedangan, Tangerang Regency, Banten 15339','Tangerang','Vincent','Rizki Arif','081285637533','[\"8\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(456,581,3,'PT Badak Mas Makmur Sejahtera','Jl. Cibadak No.184, Cibadak, Kec. Astanaanyar, Kota Bandung, Jawa Barat 40241','Bandung',NULL,'Dewia Bonita','+62 823-2173-3660',NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',0,0,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(457,582,6,'Ibu Fiona','Jl. Otto Iskandardinata No.75 - 77, Karanganyar, Kec. Subang, Kabupaten Subang, Jawa Barat 41211','Subang','Ibu Fiona','Ibu Fiona','082220881111','[\"8\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(458,583,1,'PT RAMORA ERA MANDIRI','Kawasan Industri Jababeka 1, Jl.Jababeka XVI block U19 Karangbaru, Cikarang Utara','-','-','-','-',NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,1,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(459,584,3,'PT Joob Global Indonesia','We Work Noble House Lantai 30, Kota Jakarta Selatan','Jakarta','Imaduddin Amin','Imaduddin Amin','-',NULL,'{\"website\":\"-\",\"linkedin\":\"-\",\"instagram\":\"-\",\"facebook\":\"-\",\"twitter\":\"-\",\"youtube\":\"-\",\"pinterest\":\"-\"}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(460,585,4,'BNPB','-','-','-','Ranto Nelwan T','-',NULL,'{\"website\":\"-\",\"linkedin\":\"-\",\"instagram\":\"-\",\"facebook\":\"-\",\"twitter\":\"-\",\"youtube\":\"-\",\"pinterest\":\"-\"}',0,0,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(461,586,3,'Maruzome Koi','-','-','-','-','-','[\"1\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(462,587,3,'Tunas Toyota','-','-','-','-','-',NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',0,0,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(463,588,3,'PT Nadika Listrik Mandiri','Jakarta','Jakarta','-','Sebastian James Setiawan','-',NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',0,0,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(464,589,3,'Kak Ayu','Jl. Wijaya Kusumah 2 No.72, Melong, Kec. Cimahi Sel., Kota Cimahi, Jawa Barat 40534','Bandung','Kak Ayu','Kak Ayu','895343553444','[\"8\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,1),
(465,590,3,'Forum','-','-','-','-','75676575675',NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',0,0,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(466,592,3,'Masjid Al Multazam','Komplek Cherry Field, Ciganitri, Bandung','Bandung','Pengurus Masjid','Pengurus Masjid','86547139357','[\"8\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(467,593,9,'Widodo Setiadarmaji','-','-','Widodo Setiadarmaji','Widodo Setiadarmaji','-',NULL,'{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',0,0,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(468,594,3,'BUMDES Bina Sejahtera','Jln. Segara Anak, Desa Kembang Kerang, Kec. Aikmel Kab. Lombok Timur','Lombok Timur','Nasoan','Ahmad mizan tamim','087761583621','[\"3\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(469,595,9,'Bpk Aji','Jl. Mekar Agung No. 38, Mekarwangi','Bandung','Aji','Aji','81524043033','[\"8\"]','{\"website\":null,\"linkedin\":null,\"instagram\":null,\"facebook\":null,\"twitter\":null,\"youtube\":null,\"pinterest\":null}',1,1,0,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL);
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configs`
--

DROP TABLE IF EXISTS `configs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `configs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(191) NOT NULL DEFAULT 'global',
  `key` varchar(191) NOT NULL,
  `value` text DEFAULT NULL,
  `label` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `configs_group_key_unique` (`group`,`key`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configs`
--

LOCK TABLES `configs` WRITE;
/*!40000 ALTER TABLE `configs` DISABLE KEYS */;
INSERT INTO `configs` VALUES
(1,'pm','client_role_id','5',NULL,'2025-12-21 00:58:03','2025-12-21 00:58:03'),
(2,'pm','documents..number_format','{INDEX}/{PROJECT_CODE}/4VM/{CLIENT_ID}-{CNC}/{MM}-{YYYY}',NULL,'2025-12-26 20:55:42','2025-12-26 20:55:42'),
(3,'pm','documents..next_c_index','1',NULL,'2025-12-26 20:55:42','2025-12-26 20:55:42'),
(4,'pm','documents..next_nc_index','1',NULL,'2025-12-26 20:55:42','2025-12-26 20:55:42'),
(5,'pm','documents.sph.number_format','{INDEX}/{PROJECT_CODE}/{DOC_CODE}/4VM/{CLIENT_ID}-{CNC}/{MM}-{YYYY}',NULL,'2025-12-26 20:55:42','2025-12-27 05:05:17'),
(6,'pm','documents.sph.next_c_index','1',NULL,'2025-12-26 20:55:42','2025-12-26 20:55:42'),
(7,'pm','documents.sph.next_nc_index','1',NULL,'2025-12-26 20:55:42','2025-12-26 20:55:42'),
(8,'pm','documents.spk.number_format','{INDEX}/{PROJECT_CODE}/{DOC_CODE}/4VM/{CLIENT_ID}-{CNC}/{MM}-{YYYY}',NULL,'2025-12-26 20:55:42','2025-12-27 05:05:17'),
(9,'pm','documents.spk.next_c_index','1',NULL,'2025-12-26 20:55:42','2025-12-26 20:55:42'),
(10,'pm','documents.sow.number_format','{INDEX}/{PROJECT_CODE}/{DOC_CODE}/4VM/{CLIENT_ID}-{CNC}/{MM}-{YYYY}',NULL,'2025-12-26 20:55:42','2025-12-27 05:05:17'),
(11,'pm','documents.sow.next_c_index','1',NULL,'2025-12-26 20:55:42','2025-12-26 20:55:42'),
(12,'pm','documents.tor.number_format','{INDEX}/{PROJECT_CODE}/{DOC_CODE}/4VM/{CLIENT_ID}-{CNC}/{MM}-{YYYY}',NULL,'2025-12-26 20:55:42','2025-12-27 05:05:17'),
(13,'pm','documents.tor.next_c_index','1',NULL,'2025-12-26 20:55:42','2025-12-26 20:55:42'),
(14,'pm','documents.uc.number_format','{INDEX}/{PROJECT_CODE}/{DOC_CODE}/4VM/{CLIENT_ID}-{CNC}/{MM}-{YYYY}',NULL,'2025-12-26 20:55:42','2025-12-27 05:05:17'),
(15,'pm','documents.uc.next_c_index','1',NULL,'2025-12-26 20:55:42','2025-12-26 20:55:42'),
(16,'pm','documents.bp.number_format','{INDEX}/{PROJECT_CODE}/{DOC_CODE}/4VM/{CLIENT_ID}-{CNC}/{MM}-{YYYY}',NULL,'2025-12-26 20:55:42','2025-12-27 05:05:17'),
(17,'pm','documents.bp.next_c_index','1',NULL,'2025-12-26 20:55:42','2025-12-26 20:55:42'),
(18,'pm','documents.inv.number_format','{INDEX}/{PROJECT_CODE}/{DOC_CODE}/4VM/{CLIENT_ID}-{CNC}/{MM}-{YYYY}',NULL,'2025-12-26 20:55:42','2025-12-27 05:05:17'),
(19,'pm','documents.inv.next_c_index','1',NULL,'2025-12-26 20:55:42','2025-12-26 20:55:42'),
(20,'pm','documents.rcp.number_format','{INDEX}/{PROJECT_CODE}/{DOC_CODE}/4VM/{CLIENT_ID}-{CNC}/{MM}-{YYYY}',NULL,'2025-12-26 20:55:42','2025-12-27 05:05:17'),
(21,'pm','documents.rcp.next_c_index','1',NULL,'2025-12-26 20:55:42','2025-12-26 20:55:42'),
(22,'pm','documents.bast.number_format','{INDEX}/{PROJECT_CODE}/{DOC_CODE}/4VM/{CLIENT_ID}-{CNC}/{MM}-{YYYY}',NULL,'2025-12-26 20:55:42','2025-12-27 05:05:17'),
(23,'pm','documents.bast.next_c_index','1',NULL,'2025-12-26 20:55:42','2025-12-26 20:55:42'),
(24,'pm','documents.panduan.number_format','{INDEX}/{PROJECT_CODE}/{DOC_CODE}/4VM/{CLIENT_ID}-{CNC}/{MM}-{YYYY}',NULL,'2025-12-26 20:55:42','2025-12-27 05:05:17'),
(25,'pm','documents.panduan.next_c_index','1',NULL,'2025-12-26 20:55:42','2025-12-26 20:55:42'),
(26,'pm','documents.cr.number_format','{INDEX}/{PROJECT_CODE}/{DOC_CODE}/4VM/{CLIENT_ID}-{CNC}/{MM}-{YYYY}',NULL,'2025-12-26 20:55:42','2025-12-27 05:05:17'),
(27,'pm','documents.cr.next_c_index','1',NULL,'2025-12-26 20:55:42','2025-12-26 20:55:42'),
(28,'pm','documents.backup.number_format','{INDEX}/{PROJECT_CODE}/{DOC_CODE}/4VM/{CLIENT_ID}-{CNC}/{MM}-{YYYY}',NULL,'2025-12-26 20:55:42','2025-12-27 05:05:17'),
(29,'pm','documents.backup.next_c_index','1',NULL,'2025-12-26 20:55:42','2025-12-26 20:55:42'),
(30,'pm','documents.uat.number_format','{INDEX}/{PROJECT_CODE}/{DOC_CODE}/4VM/{CLIENT_ID}-{CNC}/{MM}-{YYYY}',NULL,'2025-12-26 20:55:42','2025-12-27 05:05:17'),
(31,'pm','documents.uat.next_c_index','1',NULL,'2025-12-26 20:55:42','2025-12-26 20:55:42'),
(32,'pm','documents.ba.number_format','{INDEX}/{PROJECT_CODE}/{DOC_CODE}/4VM/{CLIENT_ID}-{CNC}/{MM}-{YYYY}',NULL,'2025-12-26 20:55:42','2025-12-27 05:05:17'),
(33,'pm','documents.ba.next_c_index','1',NULL,'2025-12-26 20:55:42','2025-12-26 20:55:42');
/*!40000 ALTER TABLE `configs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `divisions`
--

DROP TABLE IF EXISTS `divisions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `divisions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `divisions_created_by_foreign` (`created_by`),
  KEY `divisions_updated_by_foreign` (`updated_by`),
  KEY `divisions_name_index` (`name`),
  CONSTRAINT `divisions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `divisions_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `divisions`
--

LOCK TABLES `divisions` WRITE;
/*!40000 ALTER TABLE `divisions` DISABLE KEYS */;
INSERT INTO `divisions` VALUES
(1,'Project','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(2,'Product','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(3,'Digital Marketing','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(4,'4VM Studio','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(5,'4VM Cloud Services','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(6,'Training & Consultancy','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL);
/*!40000 ALTER TABLE `divisions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) NOT NULL,
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
  `id` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
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
  `queue` varchar(191) NOT NULL,
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
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `languages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `language_code` varchar(10) NOT NULL DEFAULT 'en',
  `key` varchar(191) NOT NULL,
  `value` text DEFAULT NULL,
  `label` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `languages_language_code_key_unique` (`language_code`,`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `languages`
--

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `login_attempts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(191) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text DEFAULT NULL,
  `successful` tinyint(1) NOT NULL DEFAULT 0,
  `failure_reason` varchar(191) DEFAULT NULL,
  `attempted_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `login_attempts_user_id_foreign` (`user_id`),
  KEY `login_attempts_email_successful_index` (`email`,`successful`),
  KEY `login_attempts_attempted_at_index` (`attempted_at`),
  KEY `login_attempts_ip_address_index` (`ip_address`),
  KEY `login_attempts_email_index` (`email`),
  CONSTRAINT `login_attempts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_attempts`
--

LOCK TABLES `login_attempts` WRITE;
/*!40000 ALTER TABLE `login_attempts` DISABLE KEYS */;
INSERT INTO `login_attempts` VALUES
(1,'theadods@gmail.com',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0',0,'user_not_found','2025-12-15 04:24:22','2025-12-15 04:24:22','2025-12-15 04:24:22'),
(2,'theadods@gmail.com',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0',0,'user_not_found','2025-12-15 04:24:31','2025-12-15 04:24:31','2025-12-15 04:24:31'),
(3,'theadods@gmail.com',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0',1,NULL,'2025-12-15 04:24:59','2025-12-15 04:24:59','2025-12-15 04:24:59'),
(4,'admin@gmail.com',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0',0,'invalid_password','2025-12-21 01:33:11','2025-12-21 01:33:11','2025-12-21 01:33:11'),
(5,'admin@gmail.com',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0',0,'invalid_password','2025-12-21 01:34:30','2025-12-21 01:34:30','2025-12-21 01:34:30'),
(6,'admin@gmail.com',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0',1,NULL,'2025-12-21 01:37:54','2025-12-21 01:37:54','2025-12-21 01:37:54'),
(7,'vmdev',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0',0,'user_not_found','2025-12-24 21:11:31','2025-12-24 21:11:31','2025-12-24 21:11:31'),
(8,'admin@gmail.com',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0',0,'invalid_password','2025-12-24 21:11:53','2025-12-24 21:11:53','2025-12-24 21:11:53'),
(9,'admin@gmail.com',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0',0,'invalid_password','2025-12-24 21:12:04','2025-12-24 21:12:04','2025-12-24 21:12:04'),
(10,'admin@gmail.com',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0',1,NULL,'2025-12-24 21:12:54','2025-12-24 21:12:54','2025-12-24 21:12:54'),
(11,'admin@gmail.com',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0',0,'invalid_password','2025-12-25 20:19:48','2025-12-25 20:19:48','2025-12-25 20:19:48'),
(12,'admin@gmail.com',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0',0,'invalid_password','2025-12-25 20:20:00','2025-12-25 20:20:00','2025-12-25 20:20:00'),
(13,'admin@gmail.com',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0',0,'invalid_password','2025-12-25 20:20:33','2025-12-25 20:20:33','2025-12-25 20:20:33'),
(14,'admin@gmail.com',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0',0,'invalid_password','2025-12-25 20:20:43','2025-12-25 20:20:43','2025-12-25 20:20:43'),
(15,'admin@gmail.com',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0',1,NULL,'2025-12-25 20:22:37','2025-12-25 20:22:37','2025-12-25 20:22:37');
/*!40000 ALTER TABLE `login_attempts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
(4,'2025_11_15_093236_create_todos_table',2),
(5,'2025_12_06_074704_create_configs_table',2),
(6,'2025_12_06_083345_create_languages_table',2),
(7,'2024_01_01_000001_add_auth_fields_to_users_table',3),
(8,'2024_01_01_000002_create_roles_table',3),
(9,'2024_01_01_000003_create_role_permissions_table',3),
(10,'2024_01_01_000004_create_role_user_table',3),
(11,'2024_01_01_000006_create_login_attempts_table',3),
(12,'2024_01_01_000007_create_user_activities_table',3),
(13,'2024_01_01_000008_create_password_reset_tokens_table',3),
(14,'2024_01_01_000009_add_avatar_to_users_table',3),
(15,'2025_11_01_030931_add_status_to_users_table',3),
(16,'2025_11_08_131025_add_email_verified_at_index_to_users_table',3),
(17,'2025_11_09_000010_add_email_change_fields_to_users_table',3),
(18,'2025_12_14_000000_add_username_to_users_table',3),
(42,'2025_12_20_095257_create_divisions_table',4),
(43,'2025_12_20_095258_create_client_categories_table',4),
(44,'2025_12_20_095258_create_sub_divisions_table',4),
(45,'2025_12_20_095259_create_clients_table',4),
(46,'2025_12_20_095300_create_projects_table',4),
(47,'2025_12_26_120000_modify_projects_table_for_crud',4),
(48,'2025_12_26_120001_fix_projects_status_field',4),
(49,'2025_12_26_140000_create_project_categories_table',4),
(50,'2025_12_26_150000_add_project_category_id_to_projects_table',4),
(52,'2025_12_27_221508_create_project_documents_table',5);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
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
-- Table structure for table `project_categories`
--

DROP TABLE IF EXISTS `project_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `code` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_categories_name_unique` (`name`),
  UNIQUE KEY `project_categories_code_unique` (`code`),
  KEY `project_categories_created_by_foreign` (`created_by`),
  KEY `project_categories_updated_by_foreign` (`updated_by`),
  KEY `project_categories_code_index` (`code`),
  CONSTRAINT `project_categories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `project_categories_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_categories`
--

LOCK TABLES `project_categories` WRITE;
/*!40000 ALTER TABLE `project_categories` DISABLE KEYS */;
INSERT INTO `project_categories` VALUES
(1,'Web Compro','WEB','Company profile website development',NULL,NULL,'2025-12-26 02:00:10','2025-12-26 02:00:10'),
(2,'Web-based Application','AW','Web-based application development',NULL,NULL,'2025-12-26 02:00:10','2025-12-26 02:00:10'),
(3,'Web-based Application + Mobile','AWM','Web-based application with mobile support',NULL,NULL,'2025-12-26 02:00:10','2025-12-26 02:00:10'),
(4,'Mobile Application','AM','Mobile app development (iOS/Android)',NULL,NULL,'2025-12-26 02:00:10','2025-12-26 02:00:10'),
(5,'General','UM','General Project',NULL,NULL,'2025-12-26 02:00:10','2025-12-26 02:00:10'),
(6,'Maintenance & Support','MS','Ongoing maintenance and support services',NULL,NULL,'2025-12-26 02:00:10','2025-12-26 02:00:10'),
(7,'Digital Marketing','DM','Digital marketing services including SEO, SEM, social media marketing',NULL,NULL,'2025-12-26 02:00:10','2025-12-26 02:00:10'),
(8,'Website Redesign, Hosting, and Maintenance','RHMS','Website redesign, hosting, and maintenance services',NULL,NULL,'2025-12-26 02:00:10','2025-12-26 02:00:10');
/*!40000 ALTER TABLE `project_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_documents`
--

DROP TABLE IF EXISTS `project_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_documents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `project_id` bigint(20) unsigned NOT NULL,
  `type` enum('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14') NOT NULL,
  `number` varchar(191) NOT NULL,
  `notes` text DEFAULT NULL,
  `file_path` varchar(191) NOT NULL,
  `file_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`file_info`)),
  `version` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_documents_user_id_foreign` (`user_id`),
  KEY `project_documents_client_id_foreign` (`client_id`),
  KEY `project_documents_project_id_foreign` (`project_id`),
  KEY `project_documents_created_by_foreign` (`created_by`),
  CONSTRAINT `project_documents_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_documents_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `project_documents_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_documents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_documents`
--

LOCK TABLES `project_documents` WRITE;
/*!40000 ALTER TABLE `project_documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_documents` ENABLE KEYS */;
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
  `client_id` bigint(20) unsigned DEFAULT NULL,
  `client_pic` varchar(191) DEFAULT NULL,
  `client_pic_phone` varchar(191) DEFAULT NULL,
  `division_id` bigint(20) unsigned NOT NULL,
  `project_category_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `project_start` date DEFAULT NULL,
  `project_end` date DEFAULT NULL,
  `planned_delivery_date` date DEFAULT NULL,
  `actual_delivery_date` date DEFAULT NULL,
  `warranty_end_date` date DEFAULT NULL,
  `year` year(4) NOT NULL,
  `sub_division` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sub_division`)),
  `manager` varchar(191) DEFAULT NULL,
  `project_manager_id` bigint(20) unsigned DEFAULT NULL,
  `assigned_team` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`assigned_team`)),
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `source_of_sales` tinyint(3) unsigned NOT NULL,
  `services` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`services`)),
  `nominal` decimal(15,2) DEFAULT NULL,
  `budget` decimal(15,2) DEFAULT NULL,
  `down_payment` decimal(15,2) DEFAULT NULL,
  `total_terms` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT 'Including down payment as first term',
  `payment_terms` text DEFAULT NULL,
  `contract_signed_at` timestamp NULL DEFAULT NULL,
  `client_approved_at` timestamp NULL DEFAULT NULL,
  `url` text DEFAULT NULL,
  `staging_url` text DEFAULT NULL,
  `production_url` text DEFAULT NULL,
  `repository_url` text DEFAULT NULL,
  `server_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`server_info`)),
  `maintenance_type` tinyint(3) unsigned DEFAULT NULL,
  `quality_score` tinyint(3) unsigned DEFAULT NULL,
  `client_satisfaction` tinyint(3) unsigned DEFAULT NULL,
  `old_status` int(10) unsigned NOT NULL DEFAULT 0,
  `progress` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `status` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `priority` tinyint(3) unsigned NOT NULL DEFAULT 2,
  `risk_level` tinyint(3) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `projects_created_by_foreign` (`created_by`),
  KEY `projects_updated_by_foreign` (`updated_by`),
  KEY `projects_user_id_index` (`user_id`),
  KEY `projects_division_id_index` (`division_id`),
  KEY `projects_year_index` (`year`),
  KEY `projects_status_index` (`old_status`),
  KEY `projects_division_id_year_index` (`division_id`,`year`),
  KEY `projects_user_id_status_index` (`user_id`,`old_status`),
  KEY `projects_client_id_index` (`client_id`),
  KEY `projects_project_manager_id_index` (`project_manager_id`),
  KEY `projects_priority_index` (`priority`),
  KEY `projects_project_start_index` (`project_start`),
  KEY `projects_project_end_index` (`project_end`),
  KEY `projects_planned_delivery_date_index` (`planned_delivery_date`),
  KEY `projects_actual_delivery_date_index` (`actual_delivery_date`),
  KEY `projects_project_category_id_foreign` (`project_category_id`),
  CONSTRAINT `projects_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `projects_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `projects_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `projects_project_category_id_foreign` FOREIGN KEY (`project_category_id`) REFERENCES `project_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `projects_project_manager_id_foreign` FOREIGN KEY (`project_manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `projects_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `projects_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES
(1,4,NULL,NULL,NULL,1,NULL,'Website Dinas Perkebunan Jawa Barat',NULL,NULL,NULL,NULL,NULL,NULL,2018,'[1,2]',NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,'http://disbun.jabarprov.go.id/',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,4,2,NULL,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,1),
(2,4,NULL,NULL,NULL,1,NULL,'Aplikasi SIMPONI',NULL,NULL,NULL,NULL,NULL,NULL,2019,'[3]',NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,'http://simponi.disbun.jabarprov.go.id/',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,4,2,NULL,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(3,3,NULL,NULL,NULL,1,NULL,'Website & Android Application',NULL,NULL,NULL,NULL,NULL,NULL,2019,'[1,2,3,4]',NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,'https://imamuda.id/',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,1,2,NULL,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,1),
(4,3,NULL,NULL,NULL,1,NULL,'Aplikasi Keuangan Imam Muda','<p>Aplikasi ini ada dua bagian, mobile apps dan website apps. untuk mobile apps bisa di install di playstore</p>',NULL,NULL,NULL,NULL,NULL,2020,'[4]',NULL,NULL,NULL,NULL,6,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,'http://ealrgc.westjava.id/',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,1,2,NULL,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,1),
(5,6,NULL,NULL,NULL,1,NULL,'Aplikasi Icalan',NULL,NULL,NULL,NULL,NULL,NULL,2020,'[3]',NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,'www.icalan.id',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,2,NULL,'2025-12-26 02:03:49','2025-12-26 02:03:49',83,NULL),
(6,90,NULL,NULL,NULL,1,NULL,'SFC (SMESCO Fulfillment Center)',NULL,NULL,NULL,NULL,NULL,NULL,2022,'[3]',NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,2,NULL,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,1),
(7,91,NULL,NULL,NULL,1,NULL,'PENGEMBANGAN SEKI 2023',NULL,NULL,NULL,NULL,NULL,NULL,2023,'[2,3]',NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,'https://seki-halsel.halmaheraselatankab.com/',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,1,2,NULL,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(8,89,NULL,NULL,NULL,1,NULL,'Aplikasi Sipekan 2023 (pengembangan)',NULL,NULL,NULL,NULL,NULL,NULL,2023,'[3]',NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,'www.sipekan.id',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,3,2,NULL,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(9,172,NULL,NULL,NULL,1,NULL,'SIngakota V.2 (Pengembangan 2023)',NULL,NULL,NULL,NULL,NULL,NULL,2023,'[2]',NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,'www.singakota.jabarprov.go.id',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,1,2,NULL,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,NULL),
(10,90,NULL,NULL,NULL,1,NULL,'Pengembangan SFC',NULL,NULL,NULL,NULL,NULL,NULL,2023,'[3]',NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,'https://sfc.smesco.go.id/',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,1,2,NULL,'2025-12-26 02:03:49','2025-12-26 02:03:49',1,1),
(11,6,3,'Ihsan Firdaus','081220771010',1,1,'Website 4VM v4','',NULL,NULL,NULL,NULL,NULL,2025,'[1]','',NULL,'[]','[]',1,'[1,3,4,5,6]',20000000.00,25000000.00,20000000.00,1,'Full',NULL,NULL,'','','','','[]',NULL,NULL,NULL,0,0,0,1,NULL,'2025-12-26 02:58:24','2025-12-26 06:47:39',1,1),
(12,6,3,'Ihsan Firdaus','081220771010',1,1,'Website 4VM v3','',NULL,NULL,NULL,NULL,NULL,2025,'[]','',NULL,'[]','[]',1,'[1,3,4,5,6]',NULL,NULL,NULL,1,'',NULL,NULL,'','','','','[]',NULL,NULL,NULL,0,0,0,2,NULL,'2025-12-26 06:39:11','2025-12-26 06:39:11',1,NULL);
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) unsigned NOT NULL,
  `module_slug` varchar(191) NOT NULL,
  `feature_slug` varchar(191) NOT NULL,
  `sub_feature_slug` varchar(191) DEFAULT NULL,
  `can_create` tinyint(1) NOT NULL DEFAULT 0,
  `can_read` tinyint(1) NOT NULL DEFAULT 0,
  `can_update` tinyint(1) NOT NULL DEFAULT 0,
  `can_delete` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_unique` (`role_id`,`module_slug`,`feature_slug`,`sub_feature_slug`),
  KEY `role_permissions_module_slug_feature_slug_index` (`module_slug`,`feature_slug`),
  KEY `role_permissions_module_slug_index` (`module_slug`),
  CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permissions`
--

LOCK TABLES `role_permissions` WRITE;
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
INSERT INTO `role_permissions` VALUES
(1,3,'auth','profile',NULL,0,1,1,0,'2025-12-14 23:42:13','2025-12-14 23:42:13'),
(2,4,'auth','profile',NULL,0,1,0,0,'2025-12-14 23:42:13','2025-12-14 23:42:13'),
(3,2,'auth','users',NULL,1,1,1,1,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(4,2,'auth','roles',NULL,1,1,1,1,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(5,2,'auth','permissions',NULL,1,1,1,1,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(6,2,'auth','acl-rules',NULL,1,1,1,1,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(7,2,'auth','login-attempts',NULL,0,1,0,0,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(8,2,'auth','activity_log',NULL,0,1,0,0,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(9,2,'auth','profile',NULL,0,1,1,0,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(10,2,'auth','settings',NULL,0,1,1,0,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(11,2,'home','index',NULL,0,1,0,0,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(12,2,'todos','index',NULL,0,1,0,0,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(13,2,'pm','project',NULL,1,1,1,1,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(14,2,'pm','client',NULL,1,1,1,1,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(15,2,'pm','master',NULL,0,1,0,0,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(16,2,'pm','master','division',1,1,1,1,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(17,2,'pm','master','client_category',1,1,1,1,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(18,2,'pm','master','project_category',1,1,1,1,'2025-12-25 23:50:08','2025-12-25 23:50:08');
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_user`
--

DROP TABLE IF EXISTS `role_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_user_user_id_role_id_unique` (`user_id`,`role_id`),
  KEY `role_user_user_id_index` (`user_id`),
  KEY `role_user_role_id_index` (`role_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2817 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_user`
--

LOCK TABLES `role_user` WRITE;
/*!40000 ALTER TABLE `role_user` DISABLE KEYS */;
INSERT INTO `role_user` VALUES
(1,1,1,'2025-12-14 23:42:13','2025-12-14 23:42:13'),
(2,3,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(3,4,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(4,6,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(5,7,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(6,8,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(7,9,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(8,10,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(9,11,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(10,12,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(11,13,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(12,14,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(13,15,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(14,16,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(15,17,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(16,18,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(17,19,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(18,20,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(19,21,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(20,22,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(21,23,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(22,24,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(23,25,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(24,26,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(25,27,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(26,28,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(27,29,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(28,30,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(29,31,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(30,32,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(31,33,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(32,34,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(33,35,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(34,36,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(35,37,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(36,38,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(37,39,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(38,40,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(39,41,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(40,42,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(41,44,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(42,46,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(43,48,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(44,49,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(45,50,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(46,51,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(47,53,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(48,54,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(49,55,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(50,56,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(51,57,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(52,58,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(53,59,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(54,60,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(55,61,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(56,62,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(57,63,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(58,64,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(59,65,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(60,66,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(61,67,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(62,68,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(63,69,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(64,70,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(65,71,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(66,74,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(67,75,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(68,76,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(69,78,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(70,79,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(71,82,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(72,84,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(73,85,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(74,86,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(75,87,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(76,88,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(77,89,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(78,90,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(79,91,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(80,92,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(81,93,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(82,94,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(83,95,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(84,96,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(85,97,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(86,98,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(87,99,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(88,100,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(89,101,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(90,102,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(91,103,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(92,105,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(93,106,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(94,107,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(95,108,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(96,109,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(97,110,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(98,111,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(99,112,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(100,113,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(101,114,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(102,115,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(103,119,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(104,120,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(105,121,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(106,122,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(107,123,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(108,124,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(109,125,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(110,126,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(111,127,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(112,128,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(113,129,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(114,130,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(115,131,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(116,132,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(117,133,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(118,134,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(119,135,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(120,137,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(121,138,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(122,139,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(123,142,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(124,143,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(125,144,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(126,145,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(127,146,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(128,147,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(129,148,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(130,149,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(131,150,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(132,151,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(133,153,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(134,154,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(135,155,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(136,156,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(137,157,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(138,158,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(139,160,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(140,162,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(141,163,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(142,164,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(143,166,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(144,168,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(145,169,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(146,170,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(147,171,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(148,172,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(149,173,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(150,174,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(151,175,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(152,176,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(153,177,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(154,178,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(155,179,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(156,180,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(157,183,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(158,184,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(159,185,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(160,186,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(161,187,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(162,188,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(163,189,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(164,190,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(165,191,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(166,192,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(167,193,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(168,195,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(169,196,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(170,197,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(171,198,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(172,199,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(173,200,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(174,201,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(175,202,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(176,203,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(177,204,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(178,205,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(179,206,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(180,207,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(181,208,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(182,209,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(183,210,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(184,211,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(185,212,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(186,213,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(187,214,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(188,215,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(189,216,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(190,217,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(191,218,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(192,219,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(193,220,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(194,221,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(195,223,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(196,224,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(197,225,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(198,226,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(199,227,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(200,228,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(201,229,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(202,230,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(203,231,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(204,232,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(205,233,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(206,234,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(207,235,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(208,236,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(209,237,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(210,238,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(211,239,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(212,240,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(213,241,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(214,242,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(215,243,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(216,244,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(217,245,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(218,246,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(219,247,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(220,250,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(221,251,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(222,252,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(223,253,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(224,254,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(225,255,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(226,256,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(227,258,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(228,260,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(229,261,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(230,262,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(231,263,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(232,264,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(233,265,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(234,266,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(235,267,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(236,268,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(237,269,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(238,270,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(239,271,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(240,272,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(241,273,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(242,274,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(243,275,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(244,277,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(245,278,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(246,279,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(247,280,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(248,282,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(249,283,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(250,284,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(251,285,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(252,286,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(253,287,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(254,288,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(255,289,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(256,290,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(257,292,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(258,293,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(259,294,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(260,295,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(261,296,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(262,297,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(263,298,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(264,299,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(265,300,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(266,301,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(267,302,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(268,303,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(269,304,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(270,305,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(271,306,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(272,307,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(273,308,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(274,309,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(275,310,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(276,311,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(277,312,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(278,313,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(279,314,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(280,315,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(281,316,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(282,317,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(283,318,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(284,319,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(285,321,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(286,322,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(287,323,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(288,324,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(289,325,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(290,326,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(291,327,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(292,328,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(293,329,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(294,330,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(295,331,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(296,332,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(297,334,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(298,337,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(299,338,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(300,339,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(301,340,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(302,341,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(303,342,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(304,343,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(305,344,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(306,347,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(307,348,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(308,351,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(309,361,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(310,362,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(311,363,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(312,364,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(313,365,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(314,366,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(315,367,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(316,368,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(317,369,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(318,370,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(319,371,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(320,372,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(321,373,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(322,374,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(323,375,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(324,376,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(325,377,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(326,378,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(327,379,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(328,381,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(329,384,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(330,385,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(331,386,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(332,387,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(333,388,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(334,389,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(335,390,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(336,392,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(337,393,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(338,394,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(339,400,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(340,422,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(341,423,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(342,424,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(343,425,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(344,426,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(345,427,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(346,428,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(347,430,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(348,432,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(349,433,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(350,434,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(351,435,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(352,436,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(353,437,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(354,442,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(355,443,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(356,453,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(357,454,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(358,455,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(359,456,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(360,457,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(361,459,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(362,460,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(363,461,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(364,463,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(365,464,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(366,465,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(367,466,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(368,467,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(369,468,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(370,469,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(371,470,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(372,471,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(373,473,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(374,474,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(375,475,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(376,476,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(377,477,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(378,478,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(379,479,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(380,480,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(381,481,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(382,482,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(383,483,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(384,484,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(385,485,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(386,486,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(387,487,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(388,488,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(389,489,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(390,490,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(391,491,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(392,492,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(393,493,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(394,494,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(395,495,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(396,496,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(397,497,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(398,500,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(399,501,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(400,503,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(401,508,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(402,509,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(403,510,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(404,511,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(405,512,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(406,513,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(407,514,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(408,515,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(409,516,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(410,517,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(411,523,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(412,526,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(413,527,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(414,528,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(415,529,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(416,530,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(417,531,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(418,532,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(419,533,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(420,535,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(421,536,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(422,537,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(423,538,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(424,539,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(425,540,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(426,541,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(427,542,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(428,543,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(429,544,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(430,545,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(431,546,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(432,551,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(433,552,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(434,555,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(435,556,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(436,557,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(437,558,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(438,559,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(439,560,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(440,561,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(441,562,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(442,563,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(443,565,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(444,566,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(445,567,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(446,568,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(447,569,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(448,570,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(449,571,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(450,572,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(451,573,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(452,574,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(453,577,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(454,578,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(455,579,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(456,580,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(457,581,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(458,582,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(459,583,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(460,584,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(461,585,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(462,586,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(463,587,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(464,588,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(465,589,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(466,590,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(467,592,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(468,593,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(469,594,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(470,595,5,'2025-12-21 01:03:55','2025-12-21 01:03:55'),
(1409,596,5,'2025-12-25 02:10:13','2025-12-25 02:10:13');
/*!40000 ALTER TABLE `role_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `slug` varchar(191) NOT NULL,
  `level` int(11) NOT NULL DEFAULT 10,
  `based_on_id` bigint(20) unsigned DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_slug_unique` (`slug`),
  KEY `roles_based_on_id_foreign` (`based_on_id`),
  KEY `roles_slug_index` (`slug`),
  KEY `roles_level_index` (`level`),
  CONSTRAINT `roles_based_on_id_foreign` FOREIGN KEY (`based_on_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES
(1,'Developer','dev',0,NULL,'Developer/Superuser role with full access to all features','2025-12-14 23:42:13','2025-12-14 23:42:13'),
(2,'Administrator','admin',5,NULL,'Administrator role with elevated privileges','2025-12-14 23:42:13','2025-12-14 23:42:13'),
(3,'User','user',10,NULL,'Standard user role with basic permissions','2025-12-14 23:42:13','2025-12-14 23:42:13'),
(4,'Guest','guest',15,NULL,'Guest role with minimal permissions','2025-12-14 23:42:13','2025-12-14 23:42:13'),
(5,'Client','client',10,NULL,'Role for Mod Project Management Client','2025-12-21 00:58:03','2025-12-21 00:58:03');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(191) NOT NULL,
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
INSERT INTO `sessions` VALUES
('4LrqfHWZPvlxKV0vfdmEZCBu4y6sy7oT06s04yoP',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:146.0) Gecko/20100101 Firefox/146.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoieUIzWXIwRGVsWW5sTHVycjd0QTNqYndRZldPb0Q3d3d6azg0MUxhYiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNToiaHR0cDovL21lbWJlcjR2bS50ZXN0L3BtL3Byb2plY3QvMTIiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozNToiaHR0cDovL21lbWJlcjR2bS50ZXN0L3BtL3Byb2plY3QvMTIiO3M6NToicm91dGUiO3M6MjU6ImJhY2tlbmQucG0ucHJvamVjdC5kZXRhaWwiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766888917),
('5wIJ2qCZNxbfrKIwaTdYzwZU0Ljj7FiFdDHTPxDh',1,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:146.0) Gecko/20100101 Firefox/146.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNnlVRVc3THg5dVI1ZWtJQWY0bUU0ZVE2cUQ1VHVia0g2RnRFN3JPdyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozNToiaHR0cDovL21lbWJlcjR2bS50ZXN0L3BtL3Byb2plY3QvMTIiO3M6NToicm91dGUiO3M6MjU6ImJhY2tlbmQucG0ucHJvamVjdC5kZXRhaWwiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjY6ImRUb2tlbiI7czozMjoiYTY5MmIzMTNhNWIyZTdlNzYyNWVhMGY0YmVjNTE2YjQiO30=',1766841315),
('fvJXYiExoB1kDCGrsJm46mo35Wg7TbXo7gRN5oQF',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:146.0) Gecko/20100101 Firefox/146.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiemE2ZVVvVFR5bXllZkFCN1lwZlU5cXlybFR6WGpkUHFXZUVFdHBZdiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9tZW1iZXI0dm0udGVzdC9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMDoiYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766840957),
('k1De8otDAxrKjtfrvBsizQFqCBkZH9E4M6TOZwkV',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:146.0) Gecko/20100101 Firefox/146.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSnRZSW5zeVNXSDNFU3Y4bTFmS0FLN1Nua2h6MTJBcTFwMFVmNTVNcCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNToiaHR0cDovL21lbWJlcjR2bS50ZXN0L3BtL3Byb2plY3QvMTIiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozNToiaHR0cDovL21lbWJlcjR2bS50ZXN0L3BtL3Byb2plY3QvMTIiO3M6NToicm91dGUiO3M6MjU6ImJhY2tlbmQucG0ucHJvamVjdC5kZXRhaWwiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766840957),
('PxMhbrAn1d6jo5ESKOz8UhlvmghPXNaUOmV7VcXN',1,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:146.0) Gecko/20100101 Firefox/146.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTTdzNHNGMjNJUzh6emNjSGZuWFRFWndCOFJEd1lEZ3RESGluRW83RSI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozNToiaHR0cDovL21lbWJlcjR2bS50ZXN0L3BtL3Byb2plY3QvMTIiO3M6NToicm91dGUiO3M6MjU6ImJhY2tlbmQucG0ucHJvamVjdC5kZXRhaWwiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjY6ImRUb2tlbiI7czozMjoiY2FlMzZhZDI4N2FjNjcwZmNmODE3M2VhNjZmZGJlYjIiO30=',1766892109),
('Y4UbfYyDo9tu4MRhm84b99OA8dHVaSUHpIBvNbrp',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:146.0) Gecko/20100101 Firefox/146.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTTd4aFhFU3RFbjdFSjlwVVVXUFZ5SW9yc3R0NW5EQ2U1cDloVkQ2RSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9tZW1iZXI0dm0udGVzdC9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMDoiYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766888917);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sub_divisions`
--

DROP TABLE IF EXISTS `sub_divisions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sub_divisions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `division_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sub_divisions_created_by_foreign` (`created_by`),
  KEY `sub_divisions_updated_by_foreign` (`updated_by`),
  KEY `sub_divisions_division_id_name_index` (`division_id`,`name`),
  CONSTRAINT `sub_divisions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sub_divisions_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sub_divisions_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_divisions`
--

LOCK TABLES `sub_divisions` WRITE;
/*!40000 ALTER TABLE `sub_divisions` DISABLE KEYS */;
INSERT INTO `sub_divisions` VALUES
(1,1,'Corporate Website','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(2,1,'Customize Website','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(3,1,'Web Base Application','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(4,1,'Mobile Application','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(5,2,'Smartcoop','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(6,2,'ProBumdes','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(7,2,'E-GOVline','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(8,3,'Maintenance Website','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(9,3,'Admin / Updating Website','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(10,3,'SEO','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(11,3,'SMO','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(12,3,'MPO','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(13,3,'Digital Advetising','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(14,3,'Customer Service','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(15,4,'Design Logo, Stationary, Marketing Tools','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(16,4,'Design Company Profile Cetak','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(17,4,'Foto Produk','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(18,4,'Foto Corporate Profile','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(19,4,'Video Corporate Profile','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(20,5,'Kebutuhan Sewa Server','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(21,5,'Security Server','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(22,5,'Pembelian domain','2025-12-26 02:00:10','2025-12-26 02:00:10',1,NULL),
(23,6,'IT Seminar','2025-12-26 02:03:48','2025-12-26 02:03:48',1,NULL),
(24,6,'Workshop','2025-12-26 02:03:48','2025-12-26 02:03:48',1,NULL),
(25,6,'Bimbingan Teknis','2025-12-26 02:03:48','2025-12-26 02:03:48',1,NULL),
(26,6,'Konsultasi & Analisa Teknis','2025-12-26 02:03:48','2025-12-26 02:03:48',1,NULL);
/*!40000 ALTER TABLE `sub_divisions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `todos`
--

DROP TABLE IF EXISTS `todos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `todos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) NOT NULL,
  `detail` text DEFAULT NULL,
  `tag` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tag`)),
  `due_date` timestamp NULL DEFAULT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `todos_completed_index` (`completed`),
  KEY `todos_due_date_index` (`due_date`)
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `todos`
--

LOCK TABLES `todos` WRITE;
/*!40000 ALTER TABLE `todos` DISABLE KEYS */;
INSERT INTO `todos` VALUES
(1,'Qui aut et fugit molestiae animi nesciunt totam assumenda id optio.','Consequuntur sed quia dolore reprehenderit. Voluptates reiciendis dicta est minima aliquam ratione. Quis recusandae commodi consequuntur vel ut aut possimus.','[]',NULL,0,NULL,'2025-12-21 00:56:51','2025-12-21 00:56:51'),
(2,'Voluptatem quaerat sit fuga voluptas corporis consequatur.',NULL,'[]','2025-12-25 08:26:33',0,NULL,'2025-12-21 00:56:51','2025-12-21 00:56:51'),
(3,'Pariatur eius doloremque quia quia libero.','Sint culpa quia dignissimos dolorem ipsam ullam ut. Debitis magnam dolor velit id corporis porro. Est odio quaerat quia cumque accusamus ut sed. In eum repudiandae molestiae delectus.','[]','2025-12-21 10:18:58',0,NULL,'2025-12-21 00:56:51','2025-12-21 00:56:51'),
(4,'Fuga omnis molestiae sint quam voluptatem et et.','Ut vero quaerat culpa nemo aut et. Vero vitae sed sed sunt. Nihil ut natus non eos qui qui temporibus. Odio suscipit dolorem voluptatem cum eum iure.','[\"Work\",\"Shopping\"]','2026-01-01 05:32:25',0,NULL,'2025-12-21 00:56:51','2025-12-21 00:56:51'),
(5,'Eaque pariatur quasi nam incidunt.','Nesciunt sed ut neque quibusdam. Deleniti sit voluptas autem. Est ad aut tempora distinctio.','[\"Project\",\"Health\"]',NULL,0,NULL,'2025-12-21 00:56:51','2025-12-21 00:56:51'),
(6,'Magni qui voluptas amet eius quisquam rem deleniti laboriosam a.','Et quam molestiae eveniet velit eveniet aliquam error. Fuga non voluptatum neque cupiditate et voluptates.','[\"Learning\",\"Shopping\"]',NULL,1,'2025-12-17 12:05:54','2025-12-21 00:56:51','2025-12-21 00:56:51'),
(7,'Ea qui tempora fugiat voluptatem libero provident.',NULL,'[\"Learning\",\"Shopping\"]','2025-12-14 02:55:24',1,'2025-12-17 14:09:39','2025-12-21 00:56:51','2025-12-21 00:56:51'),
(8,'Earum eos rerum dolore.','Quidem qui iusto voluptatibus aut ex. Repellat qui rerum aliquam magnam sed facere. Vel placeat et similique magni blanditiis.','[\"Health\",\"Shopping\",\"Learning\"]','2026-01-18 19:30:17',1,'2025-12-19 17:51:05','2025-12-21 00:56:51','2025-12-21 00:56:51'),
(9,'Dolorum ea et numquam odio voluptatem perspiciatis.','Aut assumenda et ut odio et in. Asperiores dolores qui dignissimos vitae. Qui sit aliquam voluptatum.','[\"Shopping\"]','2025-12-12 08:36:39',0,NULL,'2025-12-21 00:56:51','2025-12-21 00:56:51'),
(10,'Minima possimus quam perspiciatis repellat est qui.',NULL,'[]','2025-12-07 22:28:58',0,NULL,'2025-12-21 00:56:51','2025-12-21 00:56:51'),
(11,'Consequuntur eos neque et esse non eum exercitationem quos omnis.',NULL,'[]',NULL,0,NULL,'2025-12-21 00:56:51','2025-12-21 00:56:51'),
(12,'Ea labore numquam.','Et voluptas sit animi sit veniam alias. Id laborum cum aut similique earum. Maiores voluptatem exercitationem praesentium nam dolorem aut facilis. Commodi ut voluptatibus aliquam maxime.','[\"Personal\",\"Urgent\"]',NULL,0,NULL,'2025-12-21 00:56:51','2025-12-21 00:56:51'),
(13,'Consequuntur iusto distinctio aut sed aut.','Asperiores vitae fuga cupiditate. Provident laborum et cumque velit. Sequi nostrum qui eos blanditiis. Ducimus aliquam iure qui.','[\"Learning\",\"Shopping\"]','2025-12-29 02:41:41',0,NULL,'2025-12-21 00:56:51','2025-12-21 00:56:51'),
(14,'Et qui nostrum.',NULL,'[\"Work\",\"Health\",\"Urgent\"]','2025-12-25 01:13:32',0,NULL,'2025-12-21 00:56:51','2025-12-21 00:56:51'),
(15,'Dolorum placeat quo cum.','Aut officiis voluptatem voluptates. Odit veritatis dolorem provident pariatur. Occaecati natus voluptatum dignissimos in maiores quia.','[\"Learning\"]',NULL,1,'2025-12-09 01:22:36','2025-12-21 00:56:51','2025-12-21 00:56:51'),
(16,'Maiores et incidunt eos aliquam libero et.','Occaecati aut asperiores et fugiat neque consectetur labore. Aspernatur sunt et repellat. Illum nulla in beatae molestiae illo est. Qui quo ut eaque facilis porro error perferendis.','[]','2026-01-05 21:24:53',1,'2025-12-15 04:29:18','2025-12-21 00:56:51','2025-12-21 00:56:51'),
(17,'Voluptates enim dolores omnis quidem quia est inventore occaecati voluptate ab.',NULL,'[]','2025-12-26 18:10:28',1,'2025-12-17 00:15:16','2025-12-21 00:56:51','2025-12-21 00:56:51'),
(18,'Consectetur consequatur nihil fuga nihil debitis.','Non ipsa ut vel eaque. Quasi quidem ea cumque doloremque quas consectetur. Provident quidem aut ducimus quis commodi. Alias aut maiores enim enim ad ullam est.','[\"Shopping\"]','2026-01-05 10:54:26',0,NULL,'2025-12-21 00:56:51','2025-12-21 00:56:51'),
(19,'Aut sit.',NULL,'[\"Urgent\",\"Learning\"]','2026-01-09 22:44:31',0,NULL,'2025-12-21 00:56:51','2025-12-21 00:56:51'),
(20,'Omnis velit odit et mollitia quisquam aut.','Consequatur dolorum perferendis dolorem et quae. Dolor esse nam quidem error. Perspiciatis cupiditate debitis accusamus quasi doloribus.','[\"Personal\",\"Work\"]',NULL,1,'2025-12-20 18:17:11','2025-12-21 00:56:51','2025-12-21 00:56:51'),
(21,'Aut expedita distinctio architecto quo corrupti impedit quo occaecati.','Quis earum explicabo qui sed deserunt natus sed. Quia eum odit blanditiis maxime doloribus dolorem. Eum sint nulla mollitia sequi aut. Saepe alias maiores incidunt doloribus. Consectetur eveniet aliquid amet.','[\"Urgent\",\"Personal\",\"Shopping\"]','2025-12-29 21:11:44',0,NULL,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(22,'Eos expedita quo et sequi eius doloribus magnam.','Sed sint corrupti cupiditate. Non iure nam velit beatae et. Asperiores et delectus nemo quo.','[\"Work\",\"Shopping\",\"Health\"]',NULL,0,NULL,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(23,'Nihil quo facilis.','Et aperiam consequatur cupiditate molestiae amet explicabo aut. Enim et non facilis error eos nihil. Voluptates id magnam nemo vitae doloribus.','[\"Personal\"]',NULL,0,NULL,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(24,'Incidunt omnis illum enim quo sint quos.','Dignissimos et ut sint aut necessitatibus rerum autem asperiores. Tempora odit repellendus similique iste aliquid explicabo facere. Maxime dignissimos iure consequatur. Voluptatibus quam non rerum quas neque nemo dolores.','[\"Work\",\"Project\",\"Health\"]',NULL,0,NULL,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(25,'Incidunt rerum harum tempora dolorem nostrum.','Odit dignissimos eum tempora vitae error ducimus maiores. Rerum alias dolorum totam ut. Aut officiis voluptatem maiores consequuntur quas. Occaecati eaque id nulla aliquid nisi.','[\"Urgent\",\"Health\",\"Learning\"]','2026-01-17 03:06:38',0,NULL,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(26,'Quo voluptatem unde odit aperiam quo rerum est praesentium.','Voluptatem voluptate voluptatem sit nam deserunt dolorem. Eos molestias quaerat ut doloremque omnis.','[]','2026-01-22 21:31:23',1,'2025-12-14 16:43:04','2025-12-25 23:50:08','2025-12-25 23:50:08'),
(27,'Sunt rerum qui aut eius quisquam ratione excepturi voluptatem voluptatem inventore.','Et veritatis aliquam qui et qui deserunt. Nobis quas labore dolore illo nihil. Nisi ab nulla qui repellat sunt accusamus exercitationem.','[\"Urgent\"]','2026-01-01 19:13:34',1,'2025-12-23 09:55:09','2025-12-25 23:50:08','2025-12-25 23:50:08'),
(28,'Sit eius reprehenderit eius expedita dolor consequatur id tempora.','Qui impedit aliquam aperiam quaerat vitae quasi. Laboriosam numquam nam exercitationem facere ad sapiente. Maiores voluptatem aut quisquam dolores autem a. Commodi debitis non deserunt reiciendis corporis.','[\"Urgent\",\"Work\"]','2025-12-22 09:23:55',1,'2025-12-24 04:38:12','2025-12-25 23:50:08','2025-12-25 23:50:08'),
(29,'Corporis autem sit cum aut.','Occaecati consectetur velit quia voluptatem nam quia. Saepe nulla veritatis aut mollitia. Eaque est reprehenderit quo repellendus ullam.','[\"Urgent\",\"Learning\",\"Health\"]','2025-12-13 22:44:34',0,NULL,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(30,'Laboriosam et nobis omnis earum esse.','Laudantium accusamus consequatur nulla quia consequatur culpa hic. Architecto in sint vel eius. Omnis cupiditate ipsum animi itaque. Debitis et dolore ducimus culpa totam quos necessitatibus.','[]','2025-12-20 07:17:13',0,NULL,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(31,'Ea ex perferendis sapiente tenetur sunt voluptatem nostrum.',NULL,'[\"Urgent\",\"Learning\",\"Work\"]',NULL,0,NULL,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(32,'Dolor consequatur iusto qui adipisci consequatur.','Vel ipsam corporis ut inventore quos qui alias. Rerum soluta aut omnis fugit totam nam.','[\"Shopping\",\"Project\",\"Learning\"]','2026-01-10 03:20:25',0,NULL,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(33,'Et repellendus architecto sit autem eligendi laborum consequatur vel similique numquam.','Repudiandae tempore minus et fugiat. Id quidem vel quia dicta.','[]','2025-12-19 03:27:37',0,NULL,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(34,'Ut numquam officia est alias et consequuntur tenetur.',NULL,'[\"Project\"]',NULL,1,'2025-12-14 07:36:13','2025-12-25 23:50:08','2025-12-25 23:50:08'),
(35,'Sapiente ut voluptatem nam numquam dicta sed officiis est rerum.','Sit iste facilis tempore laboriosam. Ad et pariatur tempore. Porro saepe facilis beatae nihil illum ut non. Consequuntur suscipit itaque sed adipisci itaque autem deserunt. Dolores sed optio velit quisquam delectus.','[\"Work\",\"Personal\",\"Urgent\"]',NULL,0,NULL,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(36,'Consectetur velit consequatur beatae veniam doloribus.',NULL,'[]','2026-01-10 13:13:33',0,NULL,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(37,'Vero earum eligendi corrupti quae provident aspernatur.',NULL,'[]','2026-01-17 02:34:36',0,NULL,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(38,'Quis dignissimos ut ipsam.',NULL,'[]','2026-01-17 11:09:36',0,NULL,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(39,'Corrupti quia praesentium at qui magni.','Maxime qui praesentium eos et asperiores. Libero labore consequatur eligendi et corporis. Unde nam accusamus quo voluptates aut.','[\"Learning\"]','2026-01-12 12:36:53',0,NULL,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(40,'Occaecati perferendis velit nemo perspiciatis eos saepe.','Distinctio officia earum enim quis eum corrupti. Optio minus sapiente non enim omnis et eveniet.','[\"Learning\",\"Work\",\"Urgent\"]','2026-01-11 07:47:56',0,NULL,'2025-12-25 23:50:08','2025-12-25 23:50:08'),
(41,'Praesentium fuga enim dolorem quos similique sunt.','Repellat veniam sit impedit libero maiores. Exercitationem aut nostrum deserunt et dignissimos provident rem. Debitis sint rerum vel voluptatum dolor. Ullam qui eum blanditiis.','[\"Shopping\"]','2026-01-12 19:10:59',0,NULL,'2025-12-25 23:50:34','2025-12-25 23:50:34'),
(42,'Possimus non voluptas in optio.','Optio corrupti rerum omnis qui voluptatibus. Quia consequuntur culpa id asperiores alias.','[\"Work\",\"Project\",\"Learning\"]','2025-12-27 07:37:19',0,NULL,'2025-12-25 23:50:34','2025-12-25 23:50:34'),
(43,'Voluptatibus nisi sed.','Animi non est nobis fuga est. Perferendis qui quae perferendis dolorum voluptatum vel. Natus iusto et in totam voluptas rem. Deserunt vel voluptatem nulla quasi architecto numquam vel.','[\"Urgent\",\"Health\",\"Learning\"]',NULL,0,NULL,'2025-12-25 23:50:34','2025-12-25 23:50:34'),
(44,'Aut quibusdam sequi itaque voluptas maxime aut.','Commodi illo iure eos praesentium soluta quo asperiores. Fuga quam ut cum voluptas excepturi. Quia quis dolores cupiditate doloribus magnam enim. Et exercitationem provident est aut.','[\"Work\",\"Learning\",\"Personal\"]','2026-01-01 10:12:36',0,NULL,'2025-12-25 23:50:34','2025-12-25 23:50:34'),
(45,'Qui fugiat.','Accusamus provident qui aut. Quis soluta mollitia enim porro et eveniet eveniet. Esse consequuntur et eos praesentium ut ut quidem. Vel est libero labore magni. Et sunt in voluptatem dolor aut repudiandae itaque.','[\"Learning\",\"Health\",\"Shopping\"]','2026-01-24 10:24:16',0,NULL,'2025-12-25 23:50:34','2025-12-25 23:50:34'),
(46,'Autem sequi est voluptatem atque enim architecto enim rerum.',NULL,'[]','2026-01-19 00:36:03',1,'2025-12-18 14:37:01','2025-12-25 23:50:34','2025-12-25 23:50:34'),
(47,'Sit ipsum autem sequi praesentium.','Voluptatum beatae totam velit ad porro velit minus vitae. Cum perferendis aperiam deleniti suscipit illo. Suscipit voluptates officia sit aliquid quis facilis. Assumenda non itaque iure velit sit.','[\"Learning\"]','2025-12-24 03:59:04',1,'2025-12-19 23:56:26','2025-12-25 23:50:34','2025-12-25 23:50:34'),
(48,'Culpa ipsa commodi.','Error molestiae laudantium optio rerum dolore recusandae. Et temporibus sit voluptatibus iure optio nostrum nam. Sit non expedita et odit natus dolor eveniet at. Natus cumque earum id doloremque veniam.','[\"Shopping\",\"Project\"]',NULL,1,'2025-12-15 08:49:18','2025-12-25 23:50:34','2025-12-25 23:50:34'),
(49,'Rem voluptatibus nam ut.',NULL,'[\"Shopping\",\"Health\",\"Personal\"]','2025-12-19 09:14:26',0,NULL,'2025-12-25 23:50:34','2025-12-25 23:50:34'),
(50,'Non aperiam nemo voluptates sunt ea aspernatur fugiat eum.',NULL,'[]','2025-12-24 02:50:51',0,NULL,'2025-12-25 23:50:34','2025-12-25 23:50:34'),
(51,'Exercitationem laudantium aperiam debitis eaque qui explicabo corporis.','Ut enim in error. Odit officiis soluta perferendis cupiditate. Eaque accusantium blanditiis vero modi.','[\"Learning\"]','2026-01-12 19:25:43',0,NULL,'2025-12-25 23:50:34','2025-12-25 23:50:34'),
(52,'Minima modi sit eum molestiae.','Deleniti ullam praesentium eaque molestiae consequatur. Voluptas cum et rerum magni. Aut dignissimos odit molestiae.','[]',NULL,0,NULL,'2025-12-25 23:50:34','2025-12-25 23:50:34'),
(53,'Maxime nisi cumque.','At sequi dolor dicta alias veritatis soluta blanditiis magnam. Rerum at qui ut cum nobis molestiae. Aut sunt corrupti aut architecto nostrum eos ratione tempore.','[\"Work\",\"Shopping\"]','2025-12-31 17:48:28',1,'2025-12-12 01:08:30','2025-12-25 23:50:34','2025-12-25 23:50:34'),
(54,'Quasi odit voluptatem.',NULL,'[\"Personal\"]',NULL,0,NULL,'2025-12-25 23:50:34','2025-12-25 23:50:34'),
(55,'Ad provident ad facilis placeat cupiditate sit cupiditate et.',NULL,'[\"Project\",\"Shopping\",\"Learning\"]','2026-01-19 12:30:02',1,'2025-12-20 00:36:09','2025-12-25 23:50:34','2025-12-25 23:50:34'),
(56,'Nesciunt libero rerum labore libero.','Accusamus est quis error sapiente ea optio. Quisquam fugit molestias et dignissimos omnis architecto. Error ex blanditiis ut. Et et sit quos officia.','[\"Work\"]','2025-12-26 04:53:28',0,NULL,'2025-12-25 23:50:34','2025-12-25 23:50:34'),
(57,'Doloremque est sint quibusdam eos in nesciunt repellat blanditiis molestias.',NULL,'[\"Work\"]',NULL,0,NULL,'2025-12-25 23:50:34','2025-12-25 23:50:34'),
(58,'Est cupiditate cupiditate architecto dolor.','Similique doloribus accusamus veritatis non quas. Libero voluptates quo aut sapiente et deleniti. In voluptatem quae doloribus qui voluptatum vel. Exercitationem consequatur quaerat dolores iusto quia sit labore iure.','[\"Shopping\"]',NULL,0,NULL,'2025-12-25 23:50:34','2025-12-25 23:50:34'),
(59,'Inventore dolore laborum ipsam tempora.','Tempore eos sit impedit incidunt laboriosam similique. Odit numquam sit autem cupiditate cum harum dolores. Iusto accusantium et eveniet.','[]',NULL,0,NULL,'2025-12-25 23:50:34','2025-12-25 23:50:34'),
(60,'Dolorem neque et.','Aut quasi corrupti dolores. Modi adipisci porro quia nihil culpa omnis minima. Amet sit suscipit velit et.','[\"Work\"]','2026-01-05 08:37:30',0,NULL,'2025-12-25 23:50:34','2025-12-25 23:50:34'),
(61,'Harum incidunt cumque repudiandae optio quibusdam aut hic accusamus.','Pariatur ea in consequatur dicta. Labore quidem incidunt id est accusamus. Illo perferendis et aut necessitatibus aut dicta blanditiis ab.','[\"Urgent\",\"Health\",\"Learning\"]',NULL,0,NULL,'2025-12-26 00:42:10','2025-12-26 00:42:10'),
(62,'Laborum sed molestiae nostrum est tempore qui.','Et sunt voluptatem ut commodi qui. Vel quisquam aperiam quo est blanditiis asperiores. Et sint harum sint ex.','[\"Project\",\"Work\",\"Learning\"]','2025-12-29 18:46:43',0,NULL,'2025-12-26 00:42:10','2025-12-26 00:42:10'),
(63,'Porro voluptas dolores et.','Ullam est qui quibusdam maxime est. Iste sunt tempora eos atque saepe. Laudantium qui velit sint hic.','[\"Health\",\"Urgent\"]',NULL,0,NULL,'2025-12-26 00:42:10','2025-12-26 00:42:10'),
(64,'Quas omnis itaque ut qui corrupti.','Placeat et rerum quidem cupiditate. Ut omnis id maxime molestias veniam sunt. Consequatur sequi sint et inventore iste. Veritatis nemo iste ut non.','[]',NULL,0,NULL,'2025-12-26 00:42:10','2025-12-26 00:42:10'),
(65,'Labore reprehenderit nisi vero aut.',NULL,'[]',NULL,0,NULL,'2025-12-26 00:42:10','2025-12-26 00:42:10'),
(66,'Praesentium beatae id ullam tempore adipisci et unde.','Suscipit rem architecto debitis vel consequatur nihil. Atque doloribus id harum dolor eos accusamus harum. Voluptatem nulla ad aspernatur sit saepe corporis veritatis. Incidunt ipsum debitis veritatis optio.','[]','2026-01-14 15:48:23',1,'2025-12-21 19:18:56','2025-12-26 00:42:10','2025-12-26 00:42:10'),
(67,'Doloribus sed ipsum.','Provident placeat et voluptas quibusdam distinctio. Quasi quo id asperiores perspiciatis illum. Enim eos qui repudiandae doloremque.','[\"Project\",\"Learning\",\"Personal\"]','2026-01-01 07:28:52',1,'2025-12-21 06:12:10','2025-12-26 00:42:10','2025-12-26 00:42:10'),
(68,'Aspernatur suscipit omnis corrupti.','Reprehenderit et neque eveniet sunt. Id libero dolor numquam dignissimos eum et et. Reiciendis aspernatur dolorum reprehenderit non. Et tempora fugit animi aliquid.','[\"Work\",\"Shopping\",\"Learning\"]','2026-01-03 13:23:26',1,'2025-12-22 21:12:01','2025-12-26 00:42:10','2025-12-26 00:42:10'),
(69,'Eos placeat repudiandae quia.','Nisi officiis eaque beatae ea aliquam quo. Nisi consequatur velit ad aut tempore voluptatum commodi. Laboriosam nostrum et in. Voluptatibus ut voluptas natus in fuga et quia. Sit ratione est pariatur sit repudiandae unde.','[\"Work\",\"Project\"]','2025-12-19 05:15:25',0,NULL,'2025-12-26 00:42:10','2025-12-26 00:42:10'),
(70,'Sint possimus saepe reiciendis.',NULL,'[\"Work\"]','2025-12-13 21:11:10',0,NULL,'2025-12-26 00:42:10','2025-12-26 00:42:10'),
(71,'Adipisci sunt voluptatibus deserunt omnis et doloremque velit.','Veritatis dolorem quis magni nihil placeat perferendis quis minima. Veritatis neque et ipsum consequatur aperiam et. Veritatis aperiam quia aut. Ut aut itaque et.','[\"Project\"]','2026-01-22 14:57:27',0,NULL,'2025-12-26 00:42:10','2025-12-26 00:42:10'),
(72,'Eligendi dolorem est suscipit hic et.',NULL,'[\"Learning\",\"Personal\"]','2025-12-27 09:17:02',0,NULL,'2025-12-26 00:42:10','2025-12-26 00:42:10'),
(73,'Cumque fuga quia laborum laboriosam ut qui corporis nobis.','Velit animi maxime est adipisci sint consectetur voluptatem et. Et nihil illo et corporis consequatur qui. Ipsam perferendis optio voluptas veritatis.','[\"Work\"]',NULL,1,'2025-12-17 23:58:08','2025-12-26 00:42:10','2025-12-26 00:42:10'),
(74,'Minus qui error voluptatem a deserunt et ducimus possimus.','Recusandae et cum ipsam numquam rerum. Ex optio reiciendis aut et laboriosam. Repudiandae perferendis laborum incidunt quo suscipit quod incidunt. Et nihil ex vel aut quas quae rem.','[\"Personal\",\"Work\",\"Urgent\"]',NULL,0,NULL,'2025-12-26 00:42:10','2025-12-26 00:42:10'),
(75,'Ut sed amet qui odio impedit blanditiis.',NULL,'[\"Urgent\"]','2025-12-28 13:04:46',0,NULL,'2025-12-26 00:42:10','2025-12-26 00:42:10'),
(76,'Aut porro aliquid quis enim.','Quis itaque maiores sint similique. Et quas et quidem harum vel odit sapiente. Sequi neque asperiores quam voluptas assumenda similique quo. Reprehenderit voluptates maiores nisi non qui doloribus.','[\"Work\"]','2026-01-06 11:25:17',1,'2025-12-13 00:39:50','2025-12-26 00:42:10','2025-12-26 00:42:10'),
(77,'Molestiae voluptas quia explicabo numquam.','Dolorem pariatur delectus ex magni aut explicabo. Error quo deserunt saepe voluptas velit soluta omnis.','[\"Project\"]','2026-01-09 10:29:14',0,NULL,'2025-12-26 00:42:10','2025-12-26 00:42:10'),
(78,'Impedit rem omnis omnis.','Et ut eveniet aut autem consequuntur. Nihil magni non quis blanditiis nam soluta est.','[\"Project\",\"Health\",\"Shopping\"]','2026-01-18 06:54:15',0,NULL,'2025-12-26 00:42:10','2025-12-26 00:42:10'),
(79,'Velit provident maxime optio a vel architecto qui necessitatibus.',NULL,'[\"Personal\"]','2025-12-31 03:32:02',0,NULL,'2025-12-26 00:42:10','2025-12-26 00:42:10'),
(80,'Hic ea voluptas dolorum consequatur qui.','Delectus sequi voluptatem doloribus rerum. Labore modi sed est ipsa voluptatem. Soluta et quasi et dolore nulla.','[]','2026-01-04 23:30:27',0,NULL,'2025-12-26 00:42:10','2025-12-26 00:42:10'),
(81,'Consequatur esse deserunt sit amet ea.',NULL,'[]',NULL,0,NULL,'2025-12-26 00:44:35','2025-12-26 00:44:35'),
(82,'Asperiores consequatur quis.','Laudantium libero ab vel esse vitae qui nisi. Eos eum laborum ipsum sit. Qui expedita et praesentium tempora.','[\"Learning\"]','2025-12-31 15:28:50',0,NULL,'2025-12-26 00:44:35','2025-12-26 00:44:35'),
(83,'Nulla dignissimos aliquam voluptas alias enim.',NULL,'[\"Work\"]',NULL,0,NULL,'2025-12-26 00:44:35','2025-12-26 00:44:35'),
(84,'Sed voluptatem aut.','Earum quod officia ipsam non corporis non. Omnis delectus odio aspernatur corporis eveniet.','[\"Urgent\",\"Work\"]',NULL,0,NULL,'2025-12-26 00:44:35','2025-12-26 00:44:35'),
(85,'Iste commodi officia sed nulla ratione.','Impedit voluptatem qui mollitia facilis dolorem optio. Omnis similique temporibus ad ab quo recusandae. Natus accusamus pariatur aut quas perferendis ducimus vel et.','[\"Health\"]','2026-01-11 09:30:08',0,NULL,'2025-12-26 00:44:35','2025-12-26 00:44:35'),
(86,'Libero dicta error quis.','Temporibus soluta qui non. Totam a ea saepe dolor. Perferendis alias tempora esse repellat a et repudiandae quia.','[\"Personal\"]','2026-01-09 18:36:08',1,'2025-12-20 06:14:40','2025-12-26 00:44:35','2025-12-26 00:44:35'),
(87,'Quia ut est voluptatibus.','Blanditiis autem odio voluptatem aut. Natus dolorum dicta aut sapiente aut quia. Repellat quidem sit nihil incidunt.','[\"Personal\",\"Project\",\"Health\"]',NULL,1,'2025-12-21 14:02:22','2025-12-26 00:44:35','2025-12-26 00:44:35'),
(88,'Corporis porro eveniet modi reiciendis impedit assumenda vero.','Earum recusandae consequatur officiis quaerat accusantium harum. Earum quia aut voluptate corrupti. Et non nihil accusamus dicta labore in.','[\"Project\",\"Health\"]',NULL,1,'2025-12-19 12:39:06','2025-12-26 00:44:35','2025-12-26 00:44:35'),
(89,'Vel ipsum perspiciatis magnam corrupti.',NULL,'[]','2025-12-16 12:53:57',0,NULL,'2025-12-26 00:44:35','2025-12-26 00:44:35'),
(90,'Ullam voluptatem quia.','Eius eligendi eum sit et dolore eos. Minima voluptatibus eos velit veniam autem exercitationem. Deleniti non nesciunt dolores.','[\"Project\"]','2025-12-18 22:33:25',0,NULL,'2025-12-26 00:44:35','2025-12-26 00:44:35'),
(91,'Nemo quis quia voluptatem quia eius.','Consequuntur praesentium at velit labore aliquam in. Delectus sunt magni aliquid et et nisi. Autem veniam enim dolor id aut minima nulla. Aut magni quo quia voluptas odio pariatur.','[]','2026-01-12 18:27:10',1,'2025-12-18 17:09:05','2025-12-26 00:44:35','2025-12-26 00:44:35'),
(92,'Et sed excepturi enim aut aliquam dolorum.','Voluptatum ut minima rerum optio dolores id. Eaque ea exercitationem occaecati pariatur numquam corrupti dolore. Cum minima molestias quidem aut nemo. Voluptatem sit nihil fugiat nostrum occaecati autem ipsum. Voluptatem quisquam voluptate dolores ea voluptas.','[\"Shopping\",\"Project\",\"Health\"]','2026-01-02 08:34:51',0,NULL,'2025-12-26 00:44:35','2025-12-26 00:44:35'),
(93,'Quia voluptas nobis temporibus.','Vel dolorem nobis autem officiis atque est. Architecto natus vitae quia quia ut. Consequatur qui ut nulla soluta incidunt quo.','[\"Project\",\"Work\",\"Shopping\"]','2026-01-10 17:24:19',1,'2025-12-12 02:00:59','2025-12-26 00:44:35','2025-12-26 00:44:35'),
(94,'Ut atque provident quia culpa beatae.','Ut voluptatem vel porro. Est ut aliquid fugit molestias consectetur nisi aut fugiat. Aut eum qui libero consequatur.','[\"Urgent\"]',NULL,0,NULL,'2025-12-26 00:44:35','2025-12-26 00:44:35'),
(95,'Repellat eum amet qui.','Repudiandae debitis maxime a vitae aut magnam. Et et iure quia et aliquid. Architecto culpa repellat cupiditate consequatur voluptatibus suscipit.','[\"Work\",\"Learning\",\"Health\"]','2026-01-02 14:03:55',0,NULL,'2025-12-26 00:44:35','2025-12-26 00:44:35'),
(96,'Tempora itaque laboriosam tempora et fuga.',NULL,'[]','2025-12-30 14:14:47',0,NULL,'2025-12-26 00:44:35','2025-12-26 00:44:35'),
(97,'Veniam similique libero ut perferendis blanditiis dignissimos et optio quia.','Aut consequatur mollitia illum qui nisi earum. Possimus nihil omnis ipsa animi architecto hic quia. Dolor nam et qui.','[\"Personal\",\"Shopping\",\"Health\"]','2025-12-23 16:51:10',1,'2025-12-23 04:26:19','2025-12-26 00:44:35','2025-12-26 00:44:35'),
(98,'Aut ut doloribus.','Repudiandae sunt ab est ex sit soluta id. Magnam in nemo sed sunt. Optio veritatis rem voluptas vitae. Similique veritatis qui soluta incidunt. Dicta ullam nostrum velit ea enim at rerum.','[\"Personal\",\"Learning\",\"Work\"]','2025-12-21 22:23:57',0,NULL,'2025-12-26 00:44:35','2025-12-26 00:44:35'),
(99,'Earum qui libero sequi expedita perspiciatis.','Iste qui dolores eum facilis. Alias reiciendis blanditiis tempore nemo cupiditate. Voluptas non laudantium nam veritatis rerum dolores aperiam. Rem iusto ipsam ipsam aut.','[\"Learning\",\"Shopping\"]',NULL,0,NULL,'2025-12-26 00:44:35','2025-12-26 00:44:35'),
(100,'Repudiandae ducimus quae voluptas molestias sapiente veniam.','Maiores autem facere saepe possimus. Qui labore est dignissimos libero tenetur quia aut consequatur. Et nostrum esse delectus quia nulla itaque architecto. Quod commodi nihil rerum vel nisi cumque error.','[\"Health\",\"Project\"]',NULL,1,'2025-12-15 00:25:15','2025-12-26 00:44:35','2025-12-26 00:44:35'),
(101,'Omnis et non quaerat.','Aspernatur nesciunt in aut nisi dolor voluptatem aut. Et laudantium dolor nulla aut ad quas dolores. Ut et est porro repellendus repellendus.','[\"Personal\"]',NULL,0,NULL,'2025-12-26 02:00:10','2025-12-26 02:00:10'),
(102,'Aut minus doloribus ducimus tempore distinctio.',NULL,'[\"Project\"]','2026-01-24 21:25:04',0,NULL,'2025-12-26 02:00:10','2025-12-26 02:00:10'),
(103,'Commodi voluptas laborum iure non eos.','A ducimus nulla ratione id rem dolorem. Aut eum repellendus fugiat fugit sequi. Veniam non veritatis eum.','[\"Work\",\"Personal\",\"Learning\"]','2026-01-08 22:08:17',0,NULL,'2025-12-26 02:00:10','2025-12-26 02:00:10'),
(104,'Aut eligendi ut animi modi et.','Quaerat omnis voluptatibus est esse et laborum aut voluptas. Consequatur eveniet eum omnis. Sunt minima omnis non quas enim et expedita. Eum dignissimos tenetur consequatur natus facere.','[\"Health\",\"Project\"]','2025-12-23 02:15:16',0,NULL,'2025-12-26 02:00:10','2025-12-26 02:00:10'),
(105,'Vel ratione qui est praesentium ea sit odit ipsum doloribus minima.','Dicta molestiae ipsum sunt corrupti ipsum. Architecto nihil debitis non magni beatae qui. Tenetur harum eveniet ducimus odit laboriosam ut cumque. Inventore qui praesentium molestiae accusamus nihil odio.','[\"Project\",\"Learning\",\"Shopping\"]','2025-12-29 18:36:29',0,NULL,'2025-12-26 02:00:10','2025-12-26 02:00:10'),
(106,'Facilis sit qui repellendus qui perspiciatis cupiditate.','Nisi aliquid et eum ab sit ut qui. Quo eius qui qui quia molestias occaecati et.','[\"Personal\",\"Shopping\"]','2026-01-07 09:05:37',1,'2025-12-21 12:52:59','2025-12-26 02:00:10','2025-12-26 02:00:10'),
(107,'Qui omnis omnis vel dolore cum labore libero officia harum quas.','Fugit rem autem voluptas beatae tenetur quisquam sequi. Voluptatem eum optio repellat ea qui. At quidem omnis enim et perspiciatis. Molestias excepturi error delectus ea enim rem eum at. Et quia repudiandae voluptate hic et a recusandae.','[\"Project\",\"Urgent\",\"Shopping\"]',NULL,1,'2025-12-17 00:53:01','2025-12-26 02:00:10','2025-12-26 02:00:10'),
(108,'Adipisci ratione quo consequuntur illo culpa et voluptate.','Est quaerat nam accusamus maxime minus autem. Tempora ipsam tempora voluptates error doloribus ut aspernatur. Hic reprehenderit ipsam nisi eligendi magni adipisci. Magni minus molestiae sint eum sed.','[]',NULL,1,'2025-12-24 19:18:16','2025-12-26 02:00:10','2025-12-26 02:00:10'),
(109,'Quasi corporis modi temporibus.','Qui et porro quos sunt molestias. Illum distinctio eveniet perspiciatis aut cupiditate. Odio molestias cumque quia ut atque. Impedit nobis impedit suscipit rerum minus laudantium. Ab iste repudiandae maxime recusandae quaerat officiis.','[\"Work\",\"Urgent\"]','2025-12-21 06:09:43',0,NULL,'2025-12-26 02:00:10','2025-12-26 02:00:10'),
(110,'Voluptate repellat qui at iure.','Aspernatur sapiente quis incidunt rerum nam fugit qui corporis. Voluptas consequuntur iusto et ducimus quas officiis ea. Et qui sint qui.','[\"Learning\",\"Urgent\"]','2025-12-25 00:24:35',0,NULL,'2025-12-26 02:00:10','2025-12-26 02:00:10'),
(111,'Ea dolorem et qui iusto molestias.','Ratione a ut aspernatur aperiam rerum. Minus dolorum deserunt dignissimos porro. Est voluptas possimus beatae minus et voluptatem.','[\"Health\",\"Shopping\",\"Learning\"]','2026-01-10 16:50:34',0,NULL,'2025-12-26 02:00:10','2025-12-26 02:00:10'),
(112,'Rerum sunt molestiae nemo nulla cupiditate consectetur.','Quo iure maxime dignissimos rem error. Sit eum omnis aut et modi vero. Eum expedita repellendus culpa nesciunt tempore. Distinctio non reprehenderit adipisci quisquam ducimus vitae molestias.','[\"Urgent\",\"Work\",\"Project\"]','2026-01-05 17:48:00',1,'2025-12-21 00:15:31','2025-12-26 02:00:10','2025-12-26 02:00:10'),
(113,'Quis eius dolorem rerum sit officia consequatur qui laborum facere.','Voluptatibus molestias blanditiis voluptatem blanditiis iusto qui quia. Quidem corporis officia eum aliquid quia quod. Aut reiciendis debitis recusandae alias praesentium.','[\"Project\",\"Health\"]','2026-01-08 23:24:58',0,NULL,'2025-12-26 02:00:10','2025-12-26 02:00:10'),
(114,'Aliquam quo perferendis ut excepturi voluptate mollitia voluptas.','Exercitationem nobis consequatur nisi alias dignissimos qui quo sequi. Similique quia voluptatibus maxime similique. Ut aut aut quis et. Quo et et ipsa voluptatem fugiat non quos vitae.','[]',NULL,1,'2025-12-22 01:47:59','2025-12-26 02:00:10','2025-12-26 02:00:10'),
(115,'Odio rem enim quasi impedit voluptates voluptas.',NULL,'[\"Project\",\"Health\",\"Work\"]','2025-12-20 22:08:08',0,NULL,'2025-12-26 02:00:10','2025-12-26 02:00:10'),
(116,'Deserunt sunt nihil cupiditate.',NULL,'[\"Health\",\"Work\",\"Project\"]','2026-01-09 13:01:05',1,'2025-12-24 17:26:03','2025-12-26 02:00:10','2025-12-26 02:00:10'),
(117,'Aut accusamus ea.','Voluptas reiciendis incidunt enim ab minima libero eaque. Sapiente ullam ea voluptas suscipit facilis dolor aut. Molestias distinctio nemo et qui magni quae. Quaerat aut ipsum totam voluptatem nam accusantium.','[\"Personal\",\"Work\",\"Project\"]',NULL,1,'2025-12-15 21:51:04','2025-12-26 02:00:10','2025-12-26 02:00:10'),
(118,'Ea debitis culpa voluptas delectus eveniet quibusdam.',NULL,'[\"Learning\",\"Work\"]',NULL,1,'2025-12-19 16:08:28','2025-12-26 02:00:10','2025-12-26 02:00:10'),
(119,'Quia sit corrupti omnis commodi nemo.','Eligendi corporis deserunt cum et veniam et. Et architecto et deserunt est eaque nulla dolorum. Temporibus beatae consequatur dolores doloremque quis. Temporibus temporibus ut vel consequuntur.','[\"Urgent\",\"Health\",\"Project\"]','2025-12-21 13:57:58',0,NULL,'2025-12-26 02:00:10','2025-12-26 02:00:10'),
(120,'Amet quos ipsa optio nam vitae harum odio.','Nemo et eos magni quisquam nihil libero voluptas. Aut doloribus eius exercitationem est non aperiam minus. Id similique sint cum.','[\"Project\",\"Personal\",\"Health\"]',NULL,0,NULL,'2025-12-26 02:00:10','2025-12-26 02:00:10');
/*!40000 ALTER TABLE `todos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_activities`
--

DROP TABLE IF EXISTS `user_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_activities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `action` varchar(191) NOT NULL,
  `module` varchar(191) DEFAULT NULL,
  `feature` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_activities_user_id_index` (`user_id`),
  KEY `user_activities_module_feature_index` (`module`,`feature`),
  KEY `user_activities_action_index` (`action`),
  KEY `user_activities_created_at_index` (`created_at`),
  CONSTRAINT `user_activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_activities`
--

LOCK TABLES `user_activities` WRITE;
/*!40000 ALTER TABLE `user_activities` DISABLE KEYS */;
INSERT INTO `user_activities` VALUES
(1,1,'2fa_code_sent','auth','authentication','Two-factor authentication code sent via email',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0','2025-12-15 04:24:49','2025-12-15 04:24:49'),
(2,1,'login','auth','authentication','User logged in successfully',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0','2025-12-15 04:24:59','2025-12-15 04:24:59'),
(3,1,'2fa_code_sent','auth','authentication','Two-factor authentication code sent via email',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0','2025-12-21 01:36:21','2025-12-21 01:36:21'),
(4,1,'login','auth','authentication','User logged in successfully',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0','2025-12-21 01:37:54','2025-12-21 01:37:54'),
(5,1,'2fa_code_sent','auth','authentication','Two-factor authentication code sent via email',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0','2025-12-24 21:12:27','2025-12-24 21:12:27'),
(6,1,'login','auth','authentication','User logged in successfully',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0','2025-12-24 21:12:54','2025-12-24 21:12:54'),
(7,1,'user.activated','auth','auth.users','Toggled user status: Adods Corp → Active\n{\n    \"target_user_id\": 596,\n    \"old_status\": 0,\n    \"new_status\": 1\n}',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0','2025-12-25 03:50:36','2025-12-25 03:50:36'),
(8,1,'user.deactivated','auth','auth.users','Toggled user status: Adods Corp → Inactive\n{\n    \"target_user_id\": 596,\n    \"old_status\": 1,\n    \"new_status\": 0\n}',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0','2025-12-25 03:50:39','2025-12-25 03:50:39'),
(9,1,'2fa_code_sent','auth','authentication','Two-factor authentication code sent via email',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0','2025-12-25 20:22:13','2025-12-25 20:22:13'),
(10,1,'login','auth','authentication','User logged in successfully',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0','2025-12-25 20:22:37','2025-12-25 20:22:37');
/*!40000 ALTER TABLE `user_activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `username` varchar(191) DEFAULT NULL,
  `pending_email` varchar(191) DEFAULT NULL,
  `email_change_token` varchar(191) DEFAULT NULL,
  `email_change_requested_at` timestamp NULL DEFAULT NULL,
  `avatar` varchar(191) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `status_changed_at` timestamp NULL DEFAULT NULL,
  `status_changed_by` bigint(20) unsigned DEFAULT NULL,
  `status_reason` text DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `two_factor_secret` varchar(191) DEFAULT NULL,
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `two_factor_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `blocked_until` timestamp NULL DEFAULT NULL,
  `failed_login_attempts` int(11) NOT NULL DEFAULT 0,
  `last_failed_login_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`),
  KEY `users_blocked_until_index` (`blocked_until`),
  KEY `users_two_factor_enabled_index` (`two_factor_enabled`),
  KEY `users_status_index` (`status`),
  KEY `users_status_changed_by_foreign` (`status_changed_by`),
  KEY `users_email_verified_at_index` (`email_verified_at`),
  KEY `users_email_change_token_index` (`email_change_token`),
  KEY `users_username_index` (`username`),
  CONSTRAINT `users_status_changed_by_foreign` FOREIGN KEY (`status_changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=597 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'Super Admin','admin@gmail.com','4vmdev',NULL,NULL,NULL,'super-admin-20200702160507.svg','2019-12-31 17:00:00',1,'2019-12-31 17:00:00',NULL,NULL,'$2y$10$tDpcYUZgGpoIhQXbZZnbHetaajraVKvVnEQZORQVGT/iRI2yqVTDS',NULL,0,NULL,'DzEBO0gvSgcLbBP9pbzmmxKsmMEXUr6cUJNk5VuAbgxDhSozhXZmBhyDjAlQ',NULL,0,NULL,'2020-07-02 08:01:58','2025-11-12 09:06:25'),
(2,'Support 4VM','support@gmail.com','4vmsup',NULL,NULL,NULL,'support-4vm-20200702160553.svg','2019-12-31 17:00:00',1,'2019-12-31 17:00:00',NULL,NULL,'$2y$10$D6ivMT92yLfR05LA34pRIO1wHtVoXl.JuRqi0GqS7XbDTQ4p1N8Tu',NULL,0,NULL,NULL,NULL,0,NULL,'2020-07-02 08:01:58','2024-11-11 10:13:55'),
(3,'Website Imamuda','admin@imamuda.id','imamuda',NULL,NULL,NULL,'urwatul-wutsqo-20200702160711.jpeg','2020-07-02 08:40:37',1,'2020-07-02 09:11:12',NULL,NULL,'$2y$10$uJH/F8FOC8LCCvdXFyXGJOglVaiWFJZiYQsHlvGC6ktZVVwo9GFRG',NULL,0,NULL,NULL,NULL,0,NULL,'2020-07-02 08:40:37','2021-04-28 04:41:19'),
(4,'Dinas Perkebunan Provinsi Jawa Barat','agus.sutirman@gmail.com','disbun',NULL,NULL,NULL,'dinas-perkebunan-20200702155924.png','2020-07-02 08:57:50',1,'2023-09-15 04:00:53',NULL,NULL,'$2y$10$IoF6IqW7NMI5D8e8IAEoCucW5LdqNrfi0g1trTxAv2RWG//dRPp6a',NULL,0,NULL,NULL,NULL,0,NULL,'2020-07-02 08:57:42','2023-09-15 04:00:53'),
(5,'Support 2','support2@gmail.com','support',NULL,NULL,NULL,NULL,'2020-08-13 04:00:05',1,'2020-08-13 04:00:05',NULL,NULL,'$2y$10$2SP3xJo2Cf/oKt8iuwyrYeJC17dR3ARpA869vV1jeo9z2enA2igIa',NULL,0,NULL,'AU5T8J8u132giJ4z1Hakeii9HHLIdO3ELqWmKVaynR0wT6zQq8bbd8koElvC',NULL,0,NULL,'2020-08-13 04:00:05','2022-08-02 04:19:29'),
(6,'Bumdes','cloud@4visionmedia.com','Bumdes',NULL,NULL,NULL,NULL,'2020-08-13 04:17:00',1,'2020-08-13 04:17:00',NULL,NULL,'$2y$10$SIOIqCBPUMzpUI/2b5tg2er4HA10aTyWwCaGEqaAW0.RaM4cqx7X.',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 04:17:00','2021-04-09 07:29:54'),
(7,'Yayasan Bahtera','beut.roni@gmail.com','yayasanbahtera',NULL,NULL,NULL,NULL,'2020-08-13 04:26:49',1,'2020-08-13 04:26:49',NULL,NULL,'$2y$10$T1XiraPhn6oW6UoaJ7P/qegev.2t/pNf6lkEsXVdAKCoOKeknhNfi',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 04:26:49','2020-08-13 04:26:49'),
(8,'Website Krista Kosmetik','pesona_7768@yahoo.co.id','kristakosmetik',NULL,NULL,NULL,NULL,'2020-08-13 04:29:35',1,'2020-08-13 04:29:35',NULL,NULL,'$2y$10$3.fZh1nSANGuU8XdMXjrGORQFONaf3Ik.bGD6CYAbGLlxzNKE0y0q',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 04:29:35','2021-04-28 04:34:18'),
(9,'PT. Bekisar','musa.maurizki@gmail.com','ptbekisar',NULL,NULL,NULL,NULL,'2020-08-13 04:33:48',1,'2020-08-13 04:33:48',NULL,NULL,'$2y$10$pJ4KT0ajT.ayiuHMttkwEOQ8deRtZEDN0vYjdcUgNwT9HuljdJl/e',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 04:33:48','2020-08-13 04:33:48'),
(10,'PT. BPR CHRISTA JAYA PERDANA','mariomosa15@gmail.com','bankchristajkp',NULL,NULL,NULL,NULL,'2020-08-13 04:37:28',1,'2020-08-13 04:37:28',NULL,NULL,'$2y$10$sG7ImcHAmjzjBeZIH5WMEObQkG4F/A9/fqAX4MIAv09hC9EzbNVju',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 04:37:28','2020-08-14 03:34:24'),
(11,'Website Ma\'had Annida Al Islamy Bekasi','adielhasan47@gmail.com','mahadannidabks',NULL,NULL,NULL,NULL,'2020-08-13 04:41:16',1,'2020-08-13 04:41:16',NULL,NULL,'$2y$10$1.DgTwrHKoodiO31jtAyMe3vnqVUDHx1HslAGagEQqf5/bkg.LeBy',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 04:41:16','2021-04-28 04:05:05'),
(12,'PT. PRIMA DINAMIKA INDONESIA','thomas.kangaran@gmail.com','ptprimadinamika',NULL,NULL,NULL,NULL,'2020-08-13 04:43:34',1,'2020-08-13 04:43:34',NULL,NULL,'$2y$10$wF0LEmnx/STs7fEu/3NEP.HYl.R0xYEWrPK5aBxuSpN4K1tUuITcq',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 04:43:34','2020-08-14 03:48:17'),
(13,'Website Cephas Music School','joelchong.ky@gmail.com','cephasmusic',NULL,NULL,NULL,NULL,'2020-08-13 04:55:16',1,'2020-08-13 04:55:16',NULL,NULL,'$2y$10$Gw5XWGCkhDQhV8PCOUWC8uzL4b/.nrjQUim2MW/nzXM9UxpjbLU2m',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 04:55:16','2021-04-26 06:25:44'),
(14,'Software Keuangan Pesantren Al Ihsan Baleendah','sidik.rilman@yahoo.com','pesalihsan',NULL,NULL,NULL,NULL,'2020-08-13 04:58:26',1,'2020-08-13 04:58:26',NULL,NULL,'$2y$10$2ie7B8yWGjqsOG9rwiO/nuCP27S5GZ/0vk1SuLA4z/1c48zoRkuAS',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 04:58:26','2021-04-28 03:46:21'),
(15,'PT. Cahaya Arif Abadi','khairul.triutomo@gmail.com','ptcahayaabadi',NULL,NULL,NULL,NULL,'2020-08-13 05:02:07',1,'2020-08-13 05:02:07',NULL,NULL,'$2y$10$A5h9cbt.44sOkwHnx5AigO4/F6oqcj53hrSakqCooXJguCcziq0yS',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 05:02:07','2020-08-14 03:21:16'),
(16,'Aplikasi Dinas KUK Prov Jawa Barat','viendiesel@gmail.com','dinaskukprovjabar',NULL,NULL,NULL,NULL,'2020-08-13 05:04:50',1,'2020-08-13 05:04:50',NULL,NULL,'$2y$10$Sn7nPJS3ZhcUq5KK8IiR4u9m27BrcFtbpUhlxRPSw8NPRIfNX1mJC',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 05:04:50','2021-04-28 04:20:51'),
(17,'Pribumi Online Store','brizadly@pribumi.co.id','ptpribumiindo',NULL,NULL,NULL,NULL,'2020-08-13 05:07:18',1,'2020-08-13 05:07:18',NULL,NULL,'$2y$10$eKfrugyeiYEo9tEzD/fe8eBihNZPdYjPYnXtv2f5YmG4TZhj1./wS',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 05:07:18','2020-08-14 03:45:15'),
(18,'Baznas Provinsi NTB','yadi.koesmayadi@gmail.com','baznasntb',NULL,NULL,NULL,NULL,'2020-08-13 05:09:11',1,'2020-08-13 05:09:11',NULL,NULL,'$2y$10$Gn18NBE4KFNA/Ihy0fyFxuG7F2uYxd7gVj3U0lCkguCyWSKUu6bAW',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 05:09:11','2020-08-14 07:21:40'),
(19,'Website Rahayu Partners','temi@elvira.rahayupartners.id','rahayupartners',NULL,NULL,NULL,NULL,'2020-08-13 05:16:27',1,'2020-08-13 05:16:27',NULL,NULL,'$2y$10$fHNz4F.IwZQQs36jtEXGO.bp6YNxmiqKt4yv.OpIWAIqEPjEuXi/.',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 05:16:27','2022-01-17 06:58:07'),
(20,'Website PT. Profita Raya','1stkevin.kusnadi@gmail.com','profitaraya',NULL,NULL,NULL,NULL,'2020-08-13 06:08:33',1,'2020-08-13 06:08:33',NULL,NULL,'$2y$10$Z/NJCtWb/87rDfppzx363.CJsbaC6joneZJGsQf/1fQ/Hk3sRlB5a',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 06:08:33','2021-04-28 02:58:06'),
(21,'Website Serikat Perusahaan Pers (Pusat) Indonesia','agusniaayu.ayu17@gmail.com','spsindo',NULL,NULL,NULL,NULL,'2020-08-13 06:10:02',1,'2020-08-13 06:10:02',NULL,NULL,'$2y$10$SdLDobQiBEUyrK3jiIEaT..Uubv/5sXVmH/OJZ.i/kFgYqr8xYdke',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 06:10:02','2021-04-26 07:16:17'),
(22,'PT Total Guna Energi','trinoto.widiasmoro@gmail.com','pttotal',NULL,NULL,NULL,NULL,'2020-08-13 06:12:02',1,'2020-08-13 06:12:02',NULL,NULL,'$2y$10$RA0xHTaaRdixA5Hv9WNd7.uv4o.9qTrcJHNcQWjt5omJi0j6VAuaG',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 06:12:02','2020-08-13 06:12:02'),
(23,'Website PT. New Champion Motor','husninjs@gmail.com','nchampionmotor',NULL,NULL,NULL,NULL,'2020-08-13 06:13:35',1,'2020-08-13 06:13:35',NULL,NULL,'$2y$10$2ZxPWxCojsO08ZWGa2PeIuPVVVvXGxr62qEyq3w8ApjoYFjHnlh6S',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 06:13:35','2021-04-28 02:56:49'),
(24,'Website Kriya Nusantara','admkriya@gmail.com','globalkriya',NULL,NULL,NULL,NULL,'2020-08-13 06:15:42',1,'2020-08-13 06:15:42',NULL,NULL,'$2y$10$ecR2EMM057s2/bywi3SbAuE.Ur0niHWqP4K9sSBtlD6gxdlkrYNIq',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 06:15:42','2021-04-26 07:54:37'),
(25,'Website PT. Timah Industri','m.akil@timahindustri.com','timahindustri',NULL,NULL,NULL,NULL,'2020-08-13 06:16:55',1,'2020-08-13 06:16:55',NULL,NULL,'$2y$10$RzOunJoy0QdXsC2SAntO3uigWz8HCd4LH.JBBIzc/hjQIJsrQs50S',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 06:16:55','2021-04-28 02:34:54'),
(26,'PT. BPR Artha Mitra Kencana','dev.de.one@gmail.com','bpramk',NULL,NULL,NULL,NULL,'2020-08-13 06:29:44',1,'2020-08-13 06:29:44',NULL,NULL,'$2y$10$2mk.j1il0EUZG0qMFd0mx.yD5jCIsE0g6Ut4/ausA45q72UMEnKlO',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 06:29:44','2020-08-14 06:20:17'),
(27,'Website PT. Radiance','muntahahidayat.rdc@gmail.com','ptradiance',NULL,NULL,NULL,NULL,'2020-08-13 06:32:02',1,'2020-08-13 06:32:02',NULL,NULL,'$2y$10$jOszsn5neAqqzgMy/j/6TOWyGm7du7Qup54Mn3uoNIwE9fs8aQora',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 06:32:02','2021-04-28 02:33:52'),
(28,'Website RS Cahya Kawaluyan','marketing@rscahyakawaluyan.com','rsckawaluyan',NULL,NULL,NULL,NULL,'2020-08-13 06:39:12',1,'2020-08-13 06:39:12',NULL,NULL,'$2y$10$PMi14ynFOhOI9NiRkVkK9u.QyXmNEoch5pGc5m7zK6k.3pym8i0JC',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 06:39:12','2021-04-28 04:15:23'),
(29,'Ayam Goreng Eyang Uti','laely_lestarri@yahoo.com','eyanguti',NULL,NULL,NULL,NULL,'2020-08-13 06:41:30',1,'2020-08-13 06:41:30',NULL,NULL,'$2y$10$YKq6uP5rv/ajiDXxMNfgN..TrLIYfBd/Y8TJVuX9QYphtBiJyhrD.',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 06:41:30','2020-08-13 06:41:30'),
(30,'Website PT. Indo Bintang Mandiri','ariftriwaskito10@gmail.com','indobm',NULL,NULL,NULL,NULL,'2020-08-13 06:43:26',1,'2020-08-13 06:43:26',NULL,NULL,'$2y$10$i2jyq7pLsK3WCPNsmuI5Se3U/WXANNCxmRPevaDmQmH2tCKMcaUbi',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 06:43:26','2021-04-28 03:46:49'),
(31,'Website PT. Masadenta','masadenta_bdg@yahoo.com','masadenta',NULL,NULL,NULL,NULL,'2020-08-13 06:47:25',1,'2020-08-13 06:47:25',NULL,NULL,'$2y$10$r5H7kb8uR5XBo3oS5mEXBeOTHd3w3id3nCyYUVwXVByqv25SI4Un6',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 06:47:25','2021-04-26 07:58:46'),
(32,'Website PT. Tata Megah Perkasa','DAFINARYA@YAHOO.CO.ID','tatamegah',NULL,NULL,NULL,NULL,'2020-08-13 06:49:20',1,'2020-08-13 06:49:20',NULL,NULL,'$2y$10$nJXKPcgeYqXmsdFmWtMrk.MeV1KYutEK9mZoMBhdIw3b3Jnv3tf/.',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 06:49:20','2021-04-26 06:11:15'),
(33,'Website PT. Hanjaya Karya Lininusa','marayanto@hanjaya.co','hanjaya',NULL,NULL,NULL,NULL,'2020-08-13 06:50:42',1,'2020-08-13 06:50:42',NULL,NULL,'$2y$10$pEP0H6GFRCWbbJdvDgqdNuAa1WkgcK2jJJF/n/aBWrqoBVnFQA.8i',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 06:50:42','2021-04-28 04:01:32'),
(34,'Kadin Provinsi Jawa Barat','sekretariat.kadinjabar@gmail.com','kadinjabar',NULL,NULL,NULL,NULL,'2020-08-13 06:53:34',1,'2020-08-13 06:53:34',NULL,NULL,'$2y$10$6g4otmS4U6MqwY21f4kNpeQqTL0E8f8FLXfwl2bSnxe8HMxRhs1BG',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 06:53:34','2020-08-14 06:09:41'),
(35,'Website Humas Jasa Tirta II','humas@jasatirta2.co.id','jasatirta',NULL,NULL,NULL,NULL,'2020-08-13 06:55:55',1,'2020-08-13 06:55:55',NULL,NULL,'$2y$10$1ttkNo/omT6cDsoOH0RRQejWCr20RtLugUvgduVIT3OmrWzLy3cTi',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 06:55:55','2021-04-28 03:26:00'),
(36,'Faatiha','rian.handrian@gmail.com','faatiha',NULL,NULL,NULL,NULL,'2020-08-13 07:02:25',1,'2020-08-13 07:02:25',NULL,NULL,'$2y$10$t4y5vNC1HSsqpBjGg1Z.jupZuO6fQLxUUgyrvfo/ZL3d9vXt0Yefe',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 07:02:25','2020-08-13 07:02:25'),
(37,'PT. CITANDUY PERKASA','accounting@fokluksau.com','citanduyperkasa',NULL,NULL,NULL,NULL,'2020-08-13 07:07:31',1,'2020-08-13 07:07:31',NULL,NULL,'$2y$10$OtzbyhftwQ9Ns06I4ZQr3Om2GyD.17218rpGCiPqAwVVQ7wzYx99u',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 07:07:31','2020-08-13 07:07:31'),
(38,'PT. ARIES UTAMA EKAGRIYA','yuti@lyman.co.id','ariesutama',NULL,NULL,NULL,NULL,'2020-08-13 07:10:15',1,'2020-08-13 07:10:15',NULL,NULL,'$2y$10$kiJHdkuL8/D1a65Ds3Cf1etbjSIp1YRmVGAx/IyAWnqWl568H8cp.',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 07:10:15','2020-08-13 07:10:15'),
(39,'YAYASAN PARAHYANGAN SATYA','htw@thebiggestsundial.com','yayasanparahyangan',NULL,NULL,NULL,NULL,'2020-08-13 07:11:44',1,'2020-08-13 07:11:44',NULL,NULL,'$2y$10$ny//HJd7D9r7SKaxiInCmORAtDV1/FwTsy/X9D/74cIUpEj0fyxDa',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 07:11:44','2020-08-13 07:11:44'),
(40,'PT. SWADHARMA PRIMAUTAMA','pga@wisma46.com','swadharma',NULL,NULL,NULL,NULL,'2020-08-13 07:35:58',1,'2020-08-13 07:35:58',NULL,NULL,'$2y$10$.wRfH7OM4/mfBXvKan1SSuJpmdvQRTyBKPDu3G.zOOIG/jI4WmOj.',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 07:35:58','2020-08-13 07:35:58'),
(41,'WEBSITE MACROMANIA.NET','andiyanlutfi@gmail.com','macroworld',NULL,NULL,NULL,NULL,'2020-08-13 07:41:17',1,'2020-08-13 07:41:17',NULL,NULL,'$2y$10$PvbDrLX8nBRPsuYhBexkuujtOAJmQHG0UfBZoK5GjnqAtZ2pwluo.',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 07:41:17','2021-04-21 08:49:47'),
(42,'Balindah Spa','jlbudijanto@gmail.com','balindahspa',NULL,NULL,NULL,NULL,'2020-08-13 07:42:55',1,'2020-08-13 07:42:55',NULL,NULL,'$2y$10$/UrEHpGTsv10q/XG2lAyHOh9P3R1vcLcb.sIJegD/FeRbReukw1ie',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 07:42:55','2020-08-13 07:42:55'),
(44,'Koperasi Syariah Jabar','surhidayat@gmail.com','kopsyariahjabar',NULL,NULL,NULL,NULL,'2020-08-13 07:48:02',1,'2020-08-13 07:48:02',NULL,NULL,'$2y$10$pDzJ.TzWL6wwQK7zVxyWAu6XTUfX66f5lfIT/ClF3GNbwt/V3d4XW',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 07:48:02','2020-08-13 07:48:02'),
(46,'CV Gradient','usan.gradient@gmail.com','cvgradient',NULL,NULL,NULL,NULL,'2020-08-13 07:51:35',1,'2020-08-13 07:51:35',NULL,NULL,'$2y$10$VJ9x2OuFtsHOgeI32046TORa/.76fyFK6jF6Ua.edtzqP4vlnWCvm',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 07:51:35','2020-08-13 07:51:35'),
(48,'Website Online Store Fatih&Firra','zhofirra@gmail.com','fatihfira',NULL,NULL,NULL,NULL,'2020-08-13 07:59:44',1,'2020-08-13 07:59:44',NULL,NULL,'$2y$10$SeligX3dAhXkkjctF1.Prebc1sVPJk3tkTtzKEdo0kqefUpmR5kqO',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 07:59:44','2021-04-28 02:53:18'),
(49,'PT Napalima','fandhi.irawan@gmail.com','napalima',NULL,NULL,NULL,NULL,'2020-08-13 08:01:02',1,'2020-08-13 08:01:02',NULL,NULL,'$2y$10$zcikXQJ8VP.1qRCBhj8nNeLcYg6Z8vjUwdOMLgWZFayI7Vr/7XYoS',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 08:01:02','2020-08-13 08:01:02'),
(50,'Dislitbang TNI AD','subdisorgsismet54@gmail.com','dislitbangtniad',NULL,NULL,NULL,NULL,'2020-08-13 08:02:23',1,'2020-08-13 08:02:23',NULL,NULL,'$2y$10$UuBZ7.t7K2aFaAoczlt68.v82LwhsLKTq4GP46CS9w.xqkPiymKKm',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 08:02:23','2020-08-13 08:02:23'),
(51,'Dekopinwil Jabar','toety.imoet@gmail.com','dekopinwiljabar',NULL,NULL,NULL,NULL,'2020-08-13 08:03:54',1,'2020-08-13 08:03:54',NULL,NULL,'$2y$10$RyaHJ8BLG.NFlvpdTpdiZOCjfMJadXgsQvuz0mZjI0trfRfKhUok6',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 08:03:54','2020-08-13 08:03:54'),
(53,'WEBSITE PROFILE THE PAPANDAYAN JAZZ BANDUNG','creative@thepapandayan.com','papandayanhotel',NULL,NULL,NULL,NULL,'2020-08-13 08:06:46',1,'2020-08-13 08:06:46',NULL,NULL,'$2y$10$BQ0RfpZjzZDg13HeViNnEu/a4fPC4O4HCwL3EGJqNXEeyZersrYqe',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 08:06:46','2021-04-28 03:24:41'),
(54,'Website Digital Preneur Community','asdinalmufiid@gmail.com','digitalpreneur',NULL,NULL,NULL,NULL,'2020-08-13 08:08:52',1,'2020-08-13 08:08:52',NULL,NULL,'$2y$10$Z.hZYSGCVo2N3No61j3OZen/UA7jZsL2YrETcuwVNaW6GIkXyeWzS',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 08:08:52','2021-04-28 03:23:11'),
(55,'Website Racing4NET','edwynts@gmail.com','racing4',NULL,NULL,NULL,NULL,'2020-08-13 08:10:09',1,'2020-08-13 08:10:09',NULL,NULL,'$2y$10$kPEdKsIQsPUOOZ.Be1qkfucR8JRPLA5J7zp2DuRdrqOuDrNnGsbTS',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 08:10:09','2021-04-28 02:54:01'),
(56,'Kangaroo Travel','it.support@kangaroo.co.id','kangarootravel',NULL,NULL,NULL,NULL,'2020-08-13 08:12:14',1,'2020-08-13 08:12:14',NULL,NULL,'$2y$10$yL.vfjRIDMwD2z6r72WwU.zkeLh8EZGvZpot4gMXGaF2tXQ3.hzoy',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 08:12:14','2020-08-14 03:35:45'),
(57,'JBW Lawyers','adm@jbw-lawyers.com','jbwlawyers',NULL,NULL,NULL,NULL,'2020-08-13 08:15:53',1,'2020-08-13 08:15:53',NULL,NULL,'$2y$10$HaMXE8I7zKyWTAHMtbpiG.btFbsHnSfcA.dMvVHBLUZzb.u/pihHO',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 08:15:53','2020-08-13 08:15:53'),
(58,'Website CU Keling Kumang','ridwandrosera@gmail.com','kelingkumang',NULL,NULL,NULL,NULL,'2020-08-13 08:18:58',1,'2020-08-13 08:18:58',NULL,NULL,'$2y$10$Wl6Hxf/rmrMah.58MAkz3OeDCoz3.NvzYUIwOY367jqYyQ4uKhWI2',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 08:18:58','2021-04-28 03:36:51'),
(59,'Website HIMKI Indonesia','maulanass727@gmail.com','himki',NULL,NULL,NULL,NULL,'2020-08-13 08:20:55',1,'2020-08-13 08:20:55',NULL,NULL,'$2y$10$rBjm.B9y5M.MYCOMUi7Noesr/eO5ZDRMAGi.ZcZ/y71xocieR54rW',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 08:20:55','2021-04-28 04:31:57'),
(60,'KPU Kota Payakumbuh','supersarap@gmail.com','kpupayakumbuh',NULL,NULL,NULL,NULL,'2020-08-13 08:23:01',1,'2020-08-13 08:23:01',NULL,NULL,'$2y$10$9U/MaqdHOUeEsPmX81fE1O9F92igrF6WecBvVEbN.e/xwhiwkWHIm',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 08:23:01','2020-08-13 08:23:01'),
(61,'Dekopinda Subang','dmmt.thahir@gmail.com','dekopindasubang',NULL,NULL,NULL,NULL,'2020-08-13 08:24:43',1,'2020-08-13 08:24:43',NULL,NULL,'$2y$10$DU0sPtoiUAXRdAWWsqRO7.vaMfuszF/hV737VpxUz3.yekSJIAGza',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 08:24:43','2020-08-13 08:24:43'),
(62,'PT. Alamanda Makmur Mitra','dhipa66@gmail.com','alamandamakmur',NULL,NULL,NULL,NULL,'2020-08-13 08:26:53',1,'2020-08-13 08:26:53',NULL,NULL,'$2y$10$HxDB2NZNBV8jECMiQx1ZGeQWO3KPw/DU91kzZle2WC99Mezq2aaKy',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 08:26:53','2020-08-14 03:21:58'),
(63,'Travel Hebat','junaidisjpku@gmail.com','travelhebat',NULL,NULL,NULL,NULL,'2020-08-13 08:30:02',1,'2020-08-13 08:30:02',NULL,NULL,'$2y$10$rLfkwGiWM9oa0XlfRdJacuWL0jMR/2m2Z/fACqr7tMsaO6XvrSnQC',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-13 08:30:02','2020-08-13 08:30:02'),
(64,'WEBSITE PT. PESONA SCIENTIFIC','CVPESONA@BDG.CENTRIN.NET.ID','pesonascientific',NULL,NULL,NULL,NULL,'2020-08-14 03:29:17',1,'2020-08-14 03:29:17',NULL,NULL,'$2y$10$cplCvTSQaGCpTUibLuIFOuX2vZQGQBHMwqCO519EBNWUrOc224btS',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-14 03:29:18','2021-04-26 06:20:27'),
(65,'KOPERASI JASA BAITUTTAMWIL BERKAH UMAT','berkahumat318@gmail.com','kopberkahumat',NULL,NULL,NULL,NULL,'2020-08-14 03:31:32',1,'2020-08-14 03:31:32',NULL,NULL,'$2y$10$uS1LJ63kKEhf6JqVJWZCwuJw/m5i8v9.EkRV9RoXah18SPMxkQyF2',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-14 03:31:32','2021-04-16 06:28:32'),
(66,'WEBSITE PT. MIURA INDONESIA','riki.amaliana@miuraz.com','miuraindo',NULL,NULL,NULL,NULL,'2020-08-14 03:40:42',1,'2020-08-14 03:40:42',NULL,NULL,'$2y$10$oRXF17ascX3Oi2yHBArs7u5qNDrr4ZCozr0/G90L629/Z3jPrZW9.',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-14 03:40:42','2021-04-28 04:03:37'),
(67,'WEBSITE BPR CENTRAL PITOBY','franklin.id@gmail.com','bprcentral',NULL,NULL,NULL,NULL,'2020-08-14 03:59:34',1,'2020-08-14 03:59:34',NULL,NULL,'$2y$10$0qBBacTsrXj6bUGXnh2GB.4z9S3/YFySw78EtwAhP5V1epPyFIlpu',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-14 03:59:34','2021-04-28 04:16:53'),
(68,'PT JAKARTA DAYA MOTTAMA DIPTAKA','Finance@djautamagroup.co.id','jktdaya',NULL,NULL,NULL,NULL,'2020-08-14 07:15:27',1,'2020-08-14 07:15:27',NULL,NULL,'$2y$10$SOh3VlxWHbLCwclFwZW8P.RgGVjncWuvx6gczjeViTDFGYoWm3pey',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-14 07:15:27','2020-08-14 07:15:27'),
(69,'YAYASAN KESEJAHTERAAN PEGAWAI BANK BJB','fajar@ykpbankbjb.or.id','yayasanpgwbjb',NULL,NULL,NULL,NULL,'2020-08-14 07:33:34',1,'2020-08-14 07:33:34',NULL,NULL,'$2y$10$B5/07Es4msISPxuXMWHc6OnP8NW4HGfSK9EohsYJBFQ9wfynrdAie',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-14 07:33:34','2020-08-14 07:33:34'),
(70,'BIDANG PENYULUHAN DISTAN PROVINSI JAWA BARAT','distan@jabarprov.go.id','bidangpenyuluhjabar',NULL,NULL,NULL,NULL,'2020-08-14 07:37:28',1,'2020-08-14 07:37:28',NULL,NULL,'$2y$10$qm0w8J.UEAtF4xyJ0dZyyuc8cEqygmwVXXPG3idpGgGv0gqlYrPNi',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-14 07:37:28','2021-06-15 04:04:42'),
(71,'PT. Carolina Sahabat Nusantara','carolina@4vm.net','carolinanusantara',NULL,NULL,NULL,NULL,'2020-08-14 07:39:28',1,'2020-08-14 07:39:28',NULL,NULL,'$2y$10$l2eNpT9HyYLavEHdyZTvv.7RffHobtJM254KSgANia/AU5EauRmDm',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-14 07:39:28','2020-08-14 07:39:28'),
(74,'Website PT. BPR Danamas Belu','bpr_danamasbelu@yahoo.com','bprbelu',NULL,NULL,NULL,NULL,'2020-08-14 07:46:07',1,'2020-08-14 07:46:07',NULL,NULL,'$2y$10$pt54mgT092/nN9bVmthVTuChrfHSdn5LHExdia9PnAqODDAAnXhFO',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-14 07:46:07','2021-04-28 04:18:09'),
(75,'PT. BPR BINA USAHA DANA','bprbinausahadana1@gmail.com','bprbud',NULL,NULL,NULL,NULL,'2020-08-14 07:47:34',1,'2020-08-14 07:47:34',NULL,NULL,'$2y$10$ejdDpaZnKBdmXB3dooxI7Oq5JwB6ByrKoMbMjBPVYmxXkPrm/hHMC',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-14 07:47:34','2021-04-05 07:24:36'),
(76,'PT. MEDIA LINTAS FREKUENSI','uta_mlf@yahoo.com','medialintas',NULL,NULL,NULL,NULL,'2020-08-14 07:48:45',1,'2020-08-14 07:48:45',NULL,NULL,'$2y$10$XVV6o3IFIyV.evwIONY56u3QQ4l9nb78wL4VFD0VSHCT4KBGpZjGa',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-14 07:48:45','2020-08-14 07:48:45'),
(78,'Darmaga Sunda','restodarmagasunda@gmail.com','darmagasunda',NULL,NULL,NULL,NULL,'2020-08-18 07:47:07',1,'2020-08-18 07:47:07',NULL,NULL,'$2y$10$q7OCFlqR5mmLQVPdRcsAuuYk9WDA2KVLKpeGjda8ADHSm2LFtQmsm',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-18 07:47:07','2022-06-13 07:22:39'),
(79,'NDT Instruments','entral@ndt-instruments.com','ndtinstruments',NULL,NULL,NULL,NULL,'2020-08-18 07:58:36',1,'2020-08-18 07:58:36',NULL,NULL,'$2y$10$1w9RpbqULlz.9QvSflZOF.X3kH80SgfBZN.hO415xCMyQobUgUrqi',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-18 07:58:36','2021-06-15 03:58:25'),
(82,'PT. Setya Langgeng Sentosa','export@romanceramics.com','romangranit',NULL,NULL,NULL,NULL,'2020-08-18 08:16:38',1,'2020-08-18 08:16:38',NULL,NULL,'$2y$10$Ukl1/zqz4irDgI.fD2rM3uYlc3yt7sCw3jRxkE7QfZkfpeSTg47RG',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-18 08:16:38','2021-06-15 03:58:44'),
(83,'Ihsan Firdaus','ihsan@4visionmedia.com','ihsan@4visionmedia.com',NULL,NULL,NULL,NULL,'2020-08-19 04:14:31',1,'2020-08-19 04:14:31',NULL,NULL,'$2y$10$0Nl6VJ1XXN7lvBS7anJ2IuDSdsCOrjvfHLtdF5QLDsKCNyupjM0rm',NULL,0,NULL,NULL,NULL,0,NULL,'2020-08-19 04:14:32','2021-04-08 09:28:41'),
(84,'KPUD Kabupaten Bandung','kpudkabbdg@mail.com','kpudkabbdg',NULL,NULL,NULL,NULL,'2021-01-14 10:10:17',1,'2021-01-14 10:10:17',NULL,NULL,'$2y$10$9UyALeM9s9AY5UHT082yYOfDswjv9yHCoNlkkOltYIRvJjtrRnw9e',NULL,0,NULL,NULL,NULL,0,NULL,'2021-01-14 10:10:17','2021-01-14 10:10:17'),
(85,'Website The Indonesian Iron & Steel Industry Association (IISIA)','info@iisia.or.id','iisia',NULL,NULL,NULL,NULL,'2021-02-15 07:22:34',1,'2021-02-15 07:22:34',NULL,NULL,'$2y$10$LIwgyFqS8/Ozi1e9G8o.ZOQzn6/aQRpyuxlg1ZrpIYO5S.1rd4VY2',NULL,0,NULL,NULL,NULL,0,NULL,'2021-02-15 07:22:34','2025-03-12 05:36:03'),
(86,'Website PT. Galashansha','info@ptgalashansha.co.id','galashansha',NULL,NULL,NULL,NULL,'2021-02-15 07:27:23',1,'2021-02-15 07:27:23',NULL,NULL,'$2y$10$Sg1mgCHwOPggKTtBZlq0KugGZVR96SaVUmo2Kba1lqSBtX4JkBQSG',NULL,0,NULL,NULL,NULL,0,NULL,'2021-02-15 07:27:23','2021-04-28 04:08:28'),
(87,'Politeknik Pariwisata Palembang','info@poltekpar-palembang.ac.id','poltekpar',NULL,NULL,NULL,NULL,'2021-02-15 07:56:15',1,'2021-02-15 07:56:15',NULL,NULL,'$2y$10$QuKxv8aJQFBSDYLspD4poOBruXY1PrTKBYSjtBX7Daf4GIn8TRg/i',NULL,0,NULL,NULL,NULL,0,NULL,'2021-02-15 07:56:15','2021-02-15 07:56:15'),
(88,'Website Racikito','racikito@4vm.com','racikito',NULL,NULL,NULL,NULL,'2021-02-15 08:02:28',1,'2021-02-15 08:02:28',NULL,NULL,'$2y$10$RB9Roy/274bS.XVyosBQp.PA3q6ahejKosZBPPS6vDQBCYMahG/TW',NULL,0,NULL,NULL,NULL,0,NULL,'2021-02-15 08:02:28','2021-04-28 04:09:08'),
(89,'Aplikasi Sistem Informasi Pelayanan Perikanan dan Website Portal Dinas Perikanan Kabupaten Sorong','info@diskankabupatensorong','dinasperikanansorong',NULL,NULL,NULL,NULL,'2021-02-15 08:07:08',1,'2021-02-15 08:07:08',NULL,NULL,'$2y$10$1TYqgQuz54GiaCN9MjjJmuwU9M6TX9vRmuRmu2TigRjlbug4OfB6u',NULL,0,NULL,NULL,NULL,0,NULL,'2021-02-15 08:07:08','2022-02-08 10:26:36'),
(90,'SMESCO Indonesia','info@smescoindonesia.com','smesco',NULL,NULL,NULL,NULL,'2021-02-17 04:33:52',1,'2021-02-17 04:33:52',NULL,NULL,'$2y$10$bNQuYtEfaaK/9aWOT0Tx4usnIa82I5GlEB5ZkODRtLeBVTVpcTP3S',NULL,0,NULL,NULL,NULL,0,NULL,'2021-02-17 04:33:52','2021-02-17 04:33:52'),
(91,'BKPPD HALSEL','info@bkppdhalses.com','BKPPD HALSEL',NULL,NULL,NULL,NULL,'2021-02-17 04:37:20',1,'2021-02-17 04:37:20',NULL,NULL,'$2y$10$64z//TOcolhw6o93iy0mludx090sSspUwUUJlA07b2PlHvqlFOB/y',NULL,0,NULL,NULL,NULL,0,NULL,'2021-02-17 04:37:20','2022-02-08 10:19:22'),
(92,'Kampung Inggris Sorong','info@sorongenglishvillage.com','kampunginggrissorong',NULL,NULL,NULL,NULL,'2021-02-17 04:40:57',1,'2021-02-17 04:40:57',NULL,NULL,'$2y$10$Ovira3vXDzaATt5/PQS7HeBpTsLc1RYCs6WrVq9ZJonBnTvlbMuia',NULL,0,NULL,NULL,NULL,0,NULL,'2021-02-17 04:40:57','2021-02-18 07:30:25'),
(93,'BPPT Project','humas@bppt.go.id','BPPTproject',NULL,NULL,NULL,NULL,'2021-02-17 04:56:10',1,'2021-02-17 04:56:10',NULL,NULL,'$2y$10$PIxonSFAusZSIVsXavoAC.B1kLbpW11uQMR0tnuQNkaefLIftrGtC',NULL,0,NULL,NULL,NULL,0,NULL,'2021-02-17 04:56:10','2021-02-17 04:56:10'),
(94,'Disnakertrans Jawa Barat','disnakertrans@jabarprov.go.id','jobfairjabar',NULL,NULL,NULL,NULL,'2021-02-17 05:38:35',1,'2021-02-17 05:38:35',NULL,NULL,'$2y$10$GjYUKvFoJSePKU5i7aYPpeoxBwQ9P58bGb1VDgJfT7rX3YwXPKwYW',NULL,0,NULL,NULL,NULL,0,NULL,'2021-02-17 05:38:35','2022-06-07 09:58:06'),
(95,'Aplikasi Sistem Informasi Pelayanan LPMP Sumsel','lpmpsumsel@kemdikbud.go.id','LPMPSUMSEL',NULL,NULL,NULL,NULL,'2021-02-17 06:22:03',1,'2021-02-17 06:22:03',NULL,NULL,'$2y$10$BZNLSSErSw5P1E33uDUQ2.8EodyznbIWEMiotYIHK0G3ClX6oimeq',NULL,0,NULL,NULL,NULL,0,NULL,'2021-02-17 06:22:03','2021-04-28 04:19:42'),
(96,'BIRO KSLN (KERJA SAMA LUAR NEGERI)','info@earlgc.westjava.id','kslnwestjava',NULL,NULL,NULL,NULL,'2021-02-17 06:24:26',1,'2021-02-17 06:24:26',NULL,NULL,'$2y$10$Wjjob6GZeXUmw3zNKwfd8OJabfddIHvQ2b4TjiACC3FqCg4eTk4fi',NULL,0,NULL,NULL,NULL,0,NULL,'2021-02-17 06:24:26','2022-02-23 12:06:25'),
(97,'KOPERASI MICRO BERBAGI KEBAIKAN','info@koperasimicro.id','KOPERASIMICRO',NULL,NULL,NULL,NULL,'2021-03-01 07:37:50',1,'2021-03-01 07:37:50',NULL,NULL,'$2y$10$vwO9s8St22XoOJ0MK2iySOoJr5B.wDT5mDO9.ADxHvI1qW4dvjwA6',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 07:37:50','2021-04-16 06:31:10'),
(98,'KOPERASI CATUR BHAKTI KANWIL DITJEN PERBENDAHARAAN PROV.DKI JAKARTA','t.monang@gmail.com','caturbhakti',NULL,NULL,NULL,NULL,'2021-03-01 07:39:24',1,'2021-03-01 07:39:24',NULL,NULL,'$2y$10$pkO9dM0aXDJSzE06ys4tz.0amIYJ4vc1F3.A9eOMhrcwFPPtu1MUO',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 07:39:24','2021-04-16 06:33:51'),
(99,'Koperasi Konsumen Kunci Sukses Bersama','konsumenkunci@smartcoop.com','konsumenkunci',NULL,NULL,NULL,NULL,'2021-03-01 07:40:39',1,'2021-03-01 07:40:39',NULL,NULL,'$2y$10$oWmGC5U796e7HmfppOkg4uC0BPqBh23c/ut2bAqIFp5wqNUpDwqCa',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 07:40:39','2021-04-16 06:38:44'),
(100,'KOPERASI KARYAWAN PT. ARGAPURA \"MANDIRI SEJAHTERA\"','koperasi.argapura@argapura.com','argapura',NULL,NULL,NULL,NULL,'2021-03-01 07:41:55',1,'2021-03-01 07:41:55',NULL,NULL,'$2y$10$7BkNx9/bFEVmPBs415EjMuDxeORuC9o8Y/IvmQuaS4JcISoR2CWsG',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 07:41:55','2021-04-16 06:41:23'),
(101,'BMT NU Pesawaran','NUpesawaran@smartcoop.com','nupesawaran',NULL,NULL,NULL,NULL,'2021-03-01 07:43:27',1,'2021-03-01 07:43:27',NULL,NULL,'$2y$10$jmUJ0Acam0kYjz7sHi3ABeXqFfxjzh4lc5o2WHZU7DzYwOuF0lidC',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 07:43:27','2021-03-01 07:43:27'),
(102,'KPRI â€œGardeniaâ€ Kebun Raya Cibodas - LIPI','Gardenia@smartcoop.com','gardenia',NULL,NULL,NULL,NULL,'2021-03-01 07:44:39',1,'2021-03-01 07:44:39',NULL,NULL,'$2y$10$GbqaatCxHMw9sANdgPq7hOuGHIm8rVU8odstL8twvx3vkjlIPisi2',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 07:44:39','2021-03-01 07:44:39'),
(103,'KOPERASI PENDIDIK DAN TENAGA KEPENDIDIKAN JAKARTA','kptk@smartcoop.id','kptkjkt',NULL,NULL,NULL,NULL,'2021-03-01 07:48:39',1,'2021-03-01 07:48:39',NULL,NULL,'$2y$10$TqAgmwqOaAdo1/.JTs6hmODGM4FCAB4Ayq4eqtd5z4HjMAtCHBWr.',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 07:48:39','2021-03-01 07:48:39'),
(105,'Koperasi Kian Sejahtera Bersama','kiansejahterabersama@gmail.com','ksbjabar',NULL,NULL,NULL,NULL,'2021-03-01 07:56:33',1,'2021-03-01 07:56:33',NULL,NULL,'$2y$10$ExBH11ro/uRgU/36sKo99e08/EbCNxFdaPmUwv.trSDQgZV8jltcS',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 07:56:33','2021-04-16 06:32:05'),
(106,'Koperasi Wirausaha Jabar Sejahtera (WJS)','WJS@smartcoop.id','WJSJabar',NULL,NULL,NULL,NULL,'2021-03-01 07:57:38',1,'2021-03-01 07:57:38',NULL,NULL,'$2y$10$.Loj4izFyYD50kvPYoHMMOQtcGIhLfMd5BUt0MlLwSTyTKhjDGWry',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 07:57:38','2021-04-16 06:32:33'),
(107,'KOPERASI JASA RAHARJA','jasaraharja@smartcoop.id','jasaraharja',NULL,NULL,NULL,NULL,'2021-03-01 07:58:43',1,'2021-03-01 07:58:43',NULL,NULL,'$2y$10$0I3xhoMYIfe4nOC0gX8oAOrKJLbWBCzVCeYTJI/uhoGUckKoryXW.',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 07:58:43','2021-03-01 07:58:43'),
(108,'Koperasi Dewi Sri Dinas Tanaman Pangan & Hortikultura Provinsi Jawa Barat','sridinas@smartcoop.id','sridinas',NULL,NULL,NULL,NULL,'2021-03-01 07:59:47',1,'2021-03-01 07:59:47',NULL,NULL,'$2y$10$C8mvVrFeGAwRN4fUwIN9mOKgnEG3KCqm2/sgqqL8ePg3uqBcvkYrW',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 07:59:47','2021-03-01 07:59:47'),
(109,'Koperasi Sinergitas Lima Pilar','limapilar@smartcoop.id','limapilar',NULL,NULL,NULL,NULL,'2021-03-01 08:01:01',1,'2021-03-01 08:01:01',NULL,NULL,'$2y$10$zC75/AUBDfwK99sCOqo0tOp/GcfkSWx3Z1I4RxIBBbQYTBBL2ViA.',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 08:01:01','2021-03-01 08:01:01'),
(110,'Koperasi LAMALEPA  Maju Jaya Abadi','lamalepabatam@gmail.com','lamapela',NULL,NULL,NULL,NULL,'2021-03-01 08:02:06',1,'2021-03-01 08:02:06',NULL,NULL,'$2y$10$rmsasKLLhDHgL0I3VhYcx.iWDle9STPhaBRh30INnUZ.PyjaGnKHC',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 08:02:06','2021-04-16 06:39:22'),
(111,'KOPERASI CEMARA KARYA PRATAMA','cekap@smartcoop.id','cemarakarya',NULL,NULL,NULL,NULL,'2021-03-01 08:03:19',1,'2021-03-01 08:03:19',NULL,NULL,'$2y$10$rF6/lleOailSFdjELkGYaOKqIcjmF5bhl1dW2cOCeiBMfUvt.asEi',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 08:03:19','2021-03-01 08:03:19'),
(112,'KPRI Manghayu Sejahtera','manghayusejahtera@smartcoop.id','manghayusejahtera',NULL,NULL,NULL,NULL,'2021-03-01 08:05:08',1,'2021-03-01 08:05:08',NULL,NULL,'$2y$10$C91bqEHEqc.xiQpEhddpWONU0sE7WMTjEVUXsv7q7L3tSpx6/r3tO',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 08:05:08','2021-03-01 08:05:08'),
(113,'KSP Mulia','kspmuliasl3@gmail.com','kspmulia',NULL,NULL,NULL,NULL,'2021-03-01 08:06:19',1,'2021-03-01 08:06:19',NULL,NULL,'$2y$10$1I0NqzESRjnLaRYIiOzza.l0u8cMA7gOvBlW.2PMjLu.dhwJgWWbm',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 08:06:19','2021-04-20 08:34:06'),
(114,'KOPERASI BHAKTI ARTHAMA FISKAL','kba.bkf@gmail.com','kopkbaf',NULL,NULL,NULL,NULL,'2021-03-01 08:07:52',1,'2021-03-01 08:07:52',NULL,NULL,'$2y$10$mrFs5Q9noAF/XOBicg8.buvG8I9O5MMsL08iOQX2EsG0Aa6661eIK',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 08:07:52','2021-04-20 08:36:11'),
(115,'Koperasi Karya Guna Gemilang','nanda@smartcoop.id','nanda',NULL,NULL,NULL,NULL,'2021-03-01 08:09:05',1,'2021-03-01 08:09:05',NULL,NULL,'$2y$10$RubSOjVSwtksQNrm6FJ.auizwkpGbhnylk0I3qMqOCI4qmuup6/j6',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 08:09:05','2021-03-01 08:09:05'),
(119,'KOPERASI KARYAWAN PT TUGU REASURANSI INDONESIA','kopkartugure@smartcoop.id','kopkartugure',NULL,NULL,NULL,NULL,'2021-03-01 08:38:20',1,'2021-03-01 08:38:20',NULL,NULL,'$2y$10$CgdECz7QhswoSN8vxNF1bO/nhCbqDSkgHeoKD5oVu7EyHJcgPVjG2',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 08:38:20','2021-03-01 08:38:20'),
(120,'Koperasi Karyawan Daya Guna New Armada Magelang','dayaguna@smartcoop.id','dayaguna',NULL,NULL,NULL,NULL,'2021-03-01 08:39:53',1,'2021-03-01 08:39:53',NULL,NULL,'$2y$10$WOPLhzzGhrJ0TOEiBwql.uK6F2mgvRujJAtwAMWtJ1bUEBfdSMoMe',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 08:39:53','2021-03-01 08:39:53'),
(121,'RIO JAYA','triyaniclaudia@gmaiI.com','RIOJAYA',NULL,NULL,NULL,NULL,'2021-03-01 08:41:06',1,'2021-03-01 08:41:06',NULL,NULL,'$2y$10$R0FoZ38nfCGw2fcEHlCjQeHvUHHLBvstWrhV1uuNIIJdFnnsEyNh.',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 08:41:06','2021-04-20 08:33:10'),
(122,'KOPKAR SUMBER SEJAHTERA AGRICON','kssag@smartcoop.id','AGRICON',NULL,NULL,NULL,NULL,'2021-03-01 08:42:26',1,'2021-03-01 08:42:26',NULL,NULL,'$2y$10$1E.1Da8wHDt4CYDCsA3KeeCPyTJFKUeL0c20VI5JYBIBxAoAxwwdK',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 08:42:26','2021-03-01 08:42:26'),
(123,'KOPERASI KARYAWAN HMSI','taofik@hino.co.id','kopkarhmsi',NULL,NULL,NULL,NULL,'2021-03-01 08:43:39',1,'2021-03-01 08:43:39',NULL,NULL,'$2y$10$V73oSyNCKg6ll9cWsHfg7OIWAYsGg3RkYxPyxuAWcIUIG3DIh5D0G',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 08:43:39','2021-04-20 08:34:45'),
(124,'KOPKAR PT Cosma Mitra Sejahtera','cms@smartcoop.id','cosmamitra',NULL,NULL,NULL,NULL,'2021-03-01 08:45:12',1,'2021-03-01 08:45:12',NULL,NULL,'$2y$10$oaEJ5nf.HBKQb8rpper8i.OgTduK9Qyo2xJYoxs.YZmVy0JccTja2',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 08:45:12','2021-03-01 08:45:12'),
(125,'KOPERASI SM Gudang Garam','cms2@smartcoop.id','GudangGaram',NULL,NULL,NULL,NULL,'2021-03-01 08:47:14',1,'2021-03-01 08:47:14',NULL,NULL,'$2y$10$ae9dH8EaGFy.K6vTSfGSfOmnY9cSW4SNR/gCkXxW7O89U72TTCWBu',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 08:47:14','2021-03-01 08:47:14'),
(126,'KOPKAR PERKASA LONDON SUMATERA','kopkarperkasa@smartcoop.id','kopkarperkasa',NULL,NULL,NULL,NULL,'2021-03-01 08:48:46',1,'2021-03-01 08:48:46',NULL,NULL,'$2y$10$FzU.afSvTdxFlP/82MhKJeA61kgEvS4uFTV9LNMayDlJlZeOYXH5e',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 08:48:46','2021-03-01 08:48:46'),
(127,'Koperasi Keluarga Besar Dispenda Provinsi Jawa Barat (KKBD)','kkbd@koperasijabar.com','koperasijabar',NULL,NULL,NULL,NULL,'2021-03-01 08:49:59',1,'2021-03-01 08:49:59',NULL,NULL,'$2y$10$KYXB96mkyUOGCmFkgLbyNetuaqthtHjK1c7buWFsik.HLi1qDOk8i',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 08:49:59','2021-03-01 08:49:59'),
(128,'KOPKAR PT Chitose Internasional','kopkarptchitos@koperasijabar.com','kopChitose',NULL,NULL,NULL,NULL,'2021-03-01 08:53:04',1,'2021-03-01 08:53:04',NULL,NULL,'$2y$10$i.4nBn6rtrkzToK3WI04aO.DviUfAf8sA/NA5gqoyxgrpnLlnNpBq',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 08:53:04','2021-03-01 08:53:04'),
(129,'KOPERASI KOPIG NUSANTARA CEMERLANG','kopig@smartcoop.id','kopignus',NULL,NULL,NULL,NULL,'2021-03-01 08:54:13',1,'2021-03-01 08:54:13',NULL,NULL,'$2y$10$8HiFMu3DyWZAlo4jLHEw0.dVyBS8hvPG2XXh7NHUFMA7RqKbytSNa',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 08:54:13','2021-03-01 08:54:13'),
(130,'Koperasi Bina Makmur','binamakmur@smartcoop.id','binakopmakmur',NULL,NULL,NULL,NULL,'2021-03-01 08:55:36',1,'2021-03-01 08:55:36',NULL,NULL,'$2y$10$tOHiZleUB/dxfOVr6Ty8Xe6B6LkDa2fx5H1O8PPKXUth6ARNMTsKi',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 08:55:36','2021-03-01 08:55:36'),
(131,'KOPERASI KORPRI PUPR','korpripupr@smartcoop.id','korpripupr',NULL,NULL,NULL,NULL,'2021-03-01 08:56:58',1,'2021-03-01 08:56:58',NULL,NULL,'$2y$10$4Qxf8PX.3.ZVrTv9UQDJ/u29XWjhtbHXsyGKUHC1BuXBBxLTisE.2',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 08:56:58','2021-03-01 08:56:58'),
(132,'Koperasi Karyawan PT Pelabuhan Indonesia IV','pelindo4@smartcoop.id','pelindo4',NULL,NULL,NULL,NULL,'2021-03-01 08:58:03',1,'2021-03-01 08:58:03',NULL,NULL,'$2y$10$E0X/KAtpPBr8FTl/xTZvWu3vkZJHaLhm5dktotHAYByS8IW7T4Chu',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 08:58:03','2021-03-01 08:58:03'),
(133,'Koperasi Jasa Asta Sari Amertha','info@asa.id','jasaasa',NULL,NULL,NULL,NULL,'2021-03-01 08:59:29',1,'2021-03-01 08:59:29',NULL,NULL,'$2y$10$EcLRA2DTgI6zgksxbsUWT.e1GK47YjSGY1TEJKUG/DwkCwK.YdwsO',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-01 08:59:29','2021-04-20 08:15:51'),
(134,'Ajushi','ajushi@4vm.com','ajushi',NULL,NULL,NULL,NULL,'2021-03-04 05:14:20',1,'2021-03-04 05:14:20',NULL,NULL,'$2y$10$oku7rmpSnVxXTrUkICisIOsfaysjdQCXU3SrIme0tiIsFxr46kW4S',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:14:21','2021-03-04 05:14:21'),
(135,'Website Alamanda Regency','Alamanda@4vm.com','Alamanda',NULL,NULL,NULL,NULL,'2021-03-04 05:15:23',1,'2021-03-04 05:15:23',NULL,NULL,'$2y$10$jyvwZDrC0f3oFA.pTigOLuAAYbdTufNi68rgdJcSh1afy5pVmgWRa',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:15:23','2021-04-28 03:52:52'),
(137,'Annapola Group','Annapolagroup@4vm.com','annapola',NULL,NULL,NULL,NULL,'2021-03-04 05:16:59',1,'2021-03-04 05:16:59',NULL,NULL,'$2y$10$sak5nG8yYlCywQqd65QwkOBiKAyFTuezFSei4bYNhqUP0qhsCTXzy',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:16:59','2021-03-04 05:16:59'),
(138,'Asosiasi Mebel Indonesia','AMI@4vm.com','mebelindo',NULL,NULL,NULL,NULL,'2021-03-04 05:18:01',1,'2021-03-04 05:18:01',NULL,NULL,'$2y$10$8a/j8cGT9YPgMoTR8ZweUuEyLUujRimlrkG8prhsLStJ4HrD52372',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:18:01','2021-03-04 05:18:01'),
(139,'Atsea Program','infoatsea2@pemsea.org','atsea',NULL,NULL,NULL,NULL,'2021-03-04 05:18:31',1,'2021-03-04 05:18:31',NULL,NULL,'$2y$10$8XY6uhiaymZjy7m37s.B3.1QZVmC0pnQeSgq80vs3mhY1rQGlyHzW',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:18:31','2021-06-15 03:59:17'),
(142,'Badan Penelitian dan Pengembangan Pertanian','bppertanian@4vm.com','bppertanian',NULL,NULL,NULL,NULL,'2021-03-04 05:20:35',1,'2021-03-04 05:20:35',NULL,NULL,'$2y$10$CtT.DkGvVdYVHhH3DYSbse7e8YF.uwkhAodxGILUisr2DvpX5y5cu',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:20:35','2021-03-04 05:20:35'),
(143,'Bandung Industri','bindustri@4vm.com','bindustri',NULL,NULL,NULL,NULL,'2021-03-04 05:21:07',1,'2021-03-04 05:21:07',NULL,NULL,'$2y$10$nmzYbXPRuTkPow1YvPFoiuf/4HIHg8v4CR0gU6iK5ulv9tjxV/g86',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:21:07','2021-04-26 06:05:53'),
(144,'Bappeda Kab. Kolaka','bappedakolaka@4vm.com','bappedakolaka',NULL,NULL,NULL,NULL,'2021-03-04 05:21:54',1,'2021-03-04 05:21:54',NULL,NULL,'$2y$10$gM7IWeeg438LlaPB9t7rbeka9Tf/XBA5sKG0gG978Lc7udpl1gUTW',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:21:54','2021-03-04 05:21:54'),
(145,'Bengkel Sehat','bengkelsehat@4vm.com','bengkelsehat',NULL,NULL,NULL,NULL,'2021-03-04 05:22:25',1,'2021-03-04 05:22:25',NULL,NULL,'$2y$10$STbbYs7DTYP5EzH9AmQcju0BU2vE73MgEzAwQermtCmEyjx7NuDiy',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:22:25','2021-03-04 05:22:25'),
(146,'Website & Video BIRD Hotel Group','sales@birdhotels.co.id','birdhotel',NULL,NULL,NULL,NULL,'2021-03-04 05:23:09',1,'2021-03-04 05:23:09',NULL,NULL,'$2y$10$1MKBTZ1j/sJXXtAYcCrj2OOUBI1P6ELGiZ6qjSAHPUiHTdvydyvuG',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:23:09','2021-06-15 03:59:28'),
(147,'BKAD Halmahera Selatan','info@bkd.malutprov.go.id','bkadhalsel',NULL,NULL,NULL,NULL,'2021-03-04 05:23:52',1,'2021-03-04 05:23:52',NULL,NULL,'$2y$10$2ffsyiAl7XxMI9Aq9RO87.8GUxit6alLt9WZqy347jol41wIfaD1W',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:23:52','2021-06-15 03:59:40'),
(148,'BKD Kabupaten Kudus','bkdkudus@4vm.com','bkdkudus',NULL,NULL,NULL,NULL,'2021-03-04 05:24:28',1,'2021-03-04 05:24:28',NULL,NULL,'$2y$10$jnV79mDNO8dnBp5Wwd0HA.wWOW0O3lrUYhaWlLMWLI0pv14NFQfT2',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:24:28','2021-03-04 05:24:28'),
(149,'Website Boarding School Pribadi','info@pribadibandung.sch.id','bspribadi',NULL,NULL,NULL,NULL,'2021-03-04 05:25:17',1,'2021-03-04 05:25:17',NULL,NULL,'$2y$10$hK.Kym92vubG.xobyu9l5OzwIuafxnBuetIShtbhK2EP6KV5lODVe',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:25:17','2021-06-15 03:59:51'),
(150,'Slide Presentasi & Website BP Konsuil Wilayah Banten','BP_KONSUILBANTEN@YAHOO.COM','bpkonsuil',NULL,NULL,NULL,NULL,'2021-03-04 05:26:12',1,'2021-03-04 05:26:12',NULL,NULL,'$2y$10$9jZVq7p8eYw1S2l7hurLFu68XUlAsUOkYLskR2/AaDXwTAwZTQgJ2',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:26:12','2021-04-26 06:24:39'),
(151,'BPR AMK Bandung','info@bpramk.com','bpramk1',NULL,NULL,NULL,NULL,'2021-03-04 05:27:18',1,'2021-03-04 05:27:18',NULL,NULL,'$2y$10$nfhzrH.5XMkXCnTUcfK6/.BfpxURbvvS8fHfRJ0JRUY3Qeeren41q',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:27:18','2021-06-15 04:01:16'),
(153,'BPR Christa Jaya','info@bprchristajaya.com','christajaya',NULL,NULL,NULL,NULL,'2021-03-04 05:28:17',1,'2021-03-04 05:28:17',NULL,NULL,'$2y$10$aeFuArGYhrIlj3cmmnL6h.VGPST5kffVoV7Jxzq0QPdUbYaqIejJu',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:28:17','2021-06-15 04:02:27'),
(154,'Website & Video BTC Hotel','btchotel@4vm.com','btchotel',NULL,NULL,NULL,NULL,'2021-03-04 05:28:48',1,'2021-03-04 05:28:48',NULL,NULL,'$2y$10$k9KT9W1W1heFz9X6L4YWZ.o6cxqsx/CVqqD/HkH7/8aI7TS9JtMSm',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:28:48','2021-04-28 03:34:13'),
(155,'Bumdes AFTA Prokop Sukanagara','bundesafta@4vm.com','bumdesafta',NULL,NULL,NULL,NULL,'2021-03-04 05:29:13',1,'2021-03-04 05:29:13',NULL,NULL,'$2y$10$6qRBCEuIa8rx4JHU2cbMouQkK4fcdeu4TZnFGGJ8ReJyBbYWyeP9e',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:29:13','2021-03-04 05:29:13'),
(156,'BUMDES Maluku Tenggara','bumdesmalukuteng@4vm.com','bumdesmaluku',NULL,NULL,NULL,NULL,'2021-03-04 05:29:44',1,'2021-03-04 05:29:44',NULL,NULL,'$2y$10$zr12H5MgHQggrEoPDEDfHuyhIiEN5M7sm4sHIVpBSX49lIXi/Q6oC',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:29:44','2021-03-04 05:29:44'),
(157,'Bumdes Mekarwangi','bumdesmekarwangi@4vm.com','bundesmekarwangi',NULL,NULL,NULL,NULL,'2021-03-04 05:30:16',1,'2021-03-04 05:30:16',NULL,NULL,'$2y$10$p3cvSlyvfYVauXx5iYDtI.fhQkxMNvWdXsWnif0ubchyToJaC3Rju',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:30:16','2021-03-04 05:30:16'),
(158,'Bumdes Ramah Abadi','ramahabadi@4vm.com','ramahabadi',NULL,NULL,NULL,NULL,'2021-03-04 05:30:55',1,'2021-03-04 05:30:55',NULL,NULL,'$2y$10$lYH08Q/nr5JtdcyHFQM4lO3OeJMAVNi0akFbLmfnfLW9EFZeswvT2',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:30:55','2021-03-04 05:30:55'),
(160,'Website PT. Cipta Media Kreasi Mandiri','ilwansyahwildan@gmail.com','ciptamedia',NULL,NULL,NULL,NULL,'2021-03-04 05:31:55',1,'2021-03-04 05:31:55',NULL,NULL,'$2y$10$dfckyeD3NUV758nb8GRLWufOZePtri67b0qa.EfD7d3urHAOU9yC.',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:31:55','2021-04-28 03:55:18'),
(162,'Costra Development Center','costradc@4vm.com','costradc',NULL,NULL,NULL,NULL,'2021-03-04 05:32:58',1,'2021-03-04 05:32:58',NULL,NULL,'$2y$10$PbQv6wFI9Qd/bdKWVKomIebDk0hEo.KEIke4/iPN.iQDP/yxOJeae',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:32:58','2021-03-04 05:32:58'),
(163,'Website CSN Indonesia','csnid@4vm.com','csnid',NULL,NULL,NULL,NULL,'2021-03-04 05:33:45',1,'2021-03-04 05:33:45',NULL,NULL,'$2y$10$Y86Z6W7vS6QHeASiM/UFVeQpjUEbUmXg9kkIG/0NAx25rq3dxzpNK',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:33:45','2021-04-28 04:40:02'),
(164,'WEB PORTAL DAN APLIKASI WEB OFFICE Andalus','cv.andalus001@gmail.com','andalus',NULL,NULL,NULL,NULL,'2021-03-04 05:34:12',1,'2021-03-04 05:34:12',NULL,NULL,'$2y$10$7iapwjTUDUynGAZ20/.aGuAdAzIvY5JlV6i8miNPe.XIDRvXnK8ge',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:34:12','2021-06-15 04:03:08'),
(166,'Website Portal Berita Maluku News','newmaluku@gmail.com','malukunews',NULL,NULL,NULL,NULL,'2021-03-04 05:35:10',1,'2021-03-04 05:35:10',NULL,NULL,'$2y$10$xsjG8BvJ441aLiVKLv33oeMyytQ7V58F5zHms4bqaozc1oQdyC/ty',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:35:10','2021-04-28 04:10:52'),
(168,'Departemen Pekerjaan Umum','DPUindo@4vm.com','DPUindo',NULL,NULL,NULL,NULL,'2021-03-04 05:36:07',1,'2021-03-04 05:36:07',NULL,NULL,'$2y$10$sMb90MIxdv4eQs9OZAPcEuNePYoW/skcr7Tla8R6mgxtmhyswlnrq',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:36:07','2021-03-04 05:36:07'),
(169,'Departement Keuangan RI','depkeuri@4vm.com','depkeuri',NULL,NULL,NULL,NULL,'2021-03-04 05:36:44',1,'2021-03-04 05:36:44',NULL,NULL,'$2y$10$VvPUd4iEZmXj/nkcza2zQuxlVlE1KmNis8RaeVzA8K0WC9JqI4eWS',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:36:44','2021-03-04 05:36:44'),
(170,'Departement Luar Negeri RI','depluri@4vm.com','depluri',NULL,NULL,NULL,NULL,'2021-03-04 05:37:18',1,'2021-03-04 05:37:18',NULL,NULL,'$2y$10$VqslG.65QkWyha73OZMdeu/KtS0thBAOeBirqxgn9FAD7HWNr07Wy',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:37:18','2021-03-04 05:37:18'),
(171,'BUMDES Desa Bojong Malaka','koes.12tea@gmail.com','desabojongmalaka',NULL,NULL,NULL,NULL,'2021-03-04 05:37:50',1,'2021-03-04 05:37:50',NULL,NULL,'$2y$10$qAQML5Y22mMNU0odxOQlT.upVfLsgXglpK8V1RVOEiX/O8gPe6QUq',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:37:50','2021-04-28 03:38:03'),
(172,'Dinas KUMKM Provinsi Jawa Barat','diskumkm@jabarprov.go.id','dinaskumkm',NULL,NULL,NULL,NULL,'2021-03-04 05:38:53',1,'2021-03-04 05:38:53',NULL,NULL,'$2y$10$4fx5Um0ZecyqI5ovbgUvMOEVcnwIJ/2rbNA7TtpNP.F9tza55yEX2',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:38:53','2021-06-15 04:03:23'),
(173,'Dinas Pendidikan Bone Bolango','disdikbonebolango@gmail.com','bonebola',NULL,NULL,NULL,NULL,'2021-03-04 05:39:18',1,'2021-03-04 05:39:18',NULL,NULL,'$2y$10$ciO09Jvqo3gtDDoYrl3INOb5p7t/ZeivenSN/x3H9l2lOiqq9Gc72',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:39:18','2021-06-15 04:03:34'),
(174,'Dinas Pendidikan Kabupaten Lima Pulu Kota','admin@disdik.limapuluhkota.go.id','limapuluhkota',NULL,NULL,NULL,NULL,'2021-03-04 05:40:18',1,'2021-03-04 05:40:18',NULL,NULL,'$2y$10$dCxvTqds86lFh9zfmoVGy.0IL1dcYRWLh0pYXiNKAaPB4nDRcjXpq',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:40:18','2021-06-15 04:03:52'),
(175,'Dinas Perhubungan Lima Puluh Kota','limapuluhkotahub@4vm.com','limapuluhkotahub',NULL,NULL,NULL,NULL,'2021-03-04 05:40:54',1,'2021-03-04 05:40:54',NULL,NULL,'$2y$10$UYuly2Br.Vd4j1jbFEzXI.UkAMIRmyAfDjEDR7aztGcISvOwtAVr6',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:40:54','2021-03-04 05:40:54'),
(176,'SISTEM INFORMASI PKPK KAB. BULUNGAN','dispusbulungan@4vm.com','dispusbulungan',NULL,NULL,NULL,NULL,'2021-03-04 05:42:34',1,'2021-03-04 05:42:34',NULL,NULL,'$2y$10$duChxFr4dty7G/FG6OHQ0uJbyQu3ApeVDGCgHcMEvTsUhgLvb0z7.',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:42:34','2021-04-26 08:02:11'),
(177,'Aplikasi Pelaporan Dinas Pertanian Provinsi Jawa Barat','disperjabar@4vm.com','disperjabar',NULL,NULL,NULL,NULL,'2021-03-04 05:43:58',1,'2021-03-04 05:43:58',NULL,NULL,'$2y$10$dJnNsI0LlVe8dFFSV.p3B.aiIA5OJNjhBx7tD9axW69cJtZQqT2E.',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:43:58','2021-04-28 04:25:59'),
(178,'Dinas Tanaman Pangan dan Hortikultura Provinsi Jawa Barat','dinastphjabar@4vm.com','dinastph',NULL,NULL,NULL,NULL,'2021-03-04 05:45:09',1,'2021-03-04 05:45:09',NULL,NULL,'$2y$10$7pCzjI9sQ1YLX9LGKvP/RubzalVE0S2eKEWYyYOYk19diS0YipKg.',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:45:09','2021-03-16 06:14:54'),
(179,'Dinas Tata Bangunan dan Pemukiman','distape@4vm.com','distape',NULL,NULL,NULL,NULL,'2021-03-04 05:45:50',1,'2021-03-04 05:45:50',NULL,NULL,'$2y$10$kAGDBc8V43NaJn9YEy8fZOwQqGpoQZvbARfFd/lIR4xB7XbYlk/yW',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:45:50','2021-03-04 05:45:50'),
(180,'Software Aplikasi 1 Pintu & Aplikasi Penilaian','metrologibdg@4vm.com','metrologibdg',NULL,NULL,NULL,NULL,'2021-03-04 05:46:32',1,'2021-03-04 05:46:32',NULL,NULL,'$2y$10$1.XRd7rUfHzuhNeOKH.JhuKlzqZV9gzdgLyaX7ZyKqA3S1xgAjrQm',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:46:32','2021-04-28 04:26:52'),
(183,'ELCORPS','tomi@elcorps.com','elcorps',NULL,NULL,NULL,NULL,'2021-03-04 05:48:22',1,'2021-03-04 05:48:22',NULL,NULL,'$2y$10$4qPlvIKI0Vs60hP4k4u3g.Gr3Qe4aLqNFvhAkASAMdEcrF7gmlau.',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:48:22','2022-06-07 09:56:14'),
(184,'Epicentrum Mall','epicentrum@4vm.com','epicentrummall',NULL,NULL,NULL,NULL,'2021-03-04 05:48:48',1,'2021-03-04 05:48:48',NULL,NULL,'$2y$10$tVuFbc32mesdSgLu7XaMgO68zdlliB4Wdm/czHS9KVjQPtHPi5Ht6',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:48:48','2021-03-04 05:48:48'),
(185,'Fakultas Teknik Universitas Nurtaino Bandung','ft@unnur.ac.id','uninurtaino',NULL,NULL,NULL,NULL,'2021-03-04 05:49:31',1,'2021-03-04 05:49:31',NULL,NULL,'$2y$10$enL8h8hfXfSrCBWQ5UcxMu7StJuGechyon17Id/O9qIqQceK9gTu6',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:49:31','2021-06-15 04:04:59'),
(186,'Galeri Ciumbuleuit','galcimbu@4vm.com','galcimbu',NULL,NULL,NULL,NULL,'2021-03-04 05:50:04',1,'2021-03-04 05:50:04',NULL,NULL,'$2y$10$ShUYziPt92Pn2hRnle/hsuyJlANmgT1Eb3OCs6YiSNlb6l7DvCeU.',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:50:04','2021-03-16 06:06:21'),
(187,'Website Galeri Shoofis Wallpaper','galerishoofis@4vm.com','galerishoofis',NULL,NULL,NULL,NULL,'2021-03-04 05:50:36',1,'2021-03-04 05:50:36',NULL,NULL,'$2y$10$3t9aMe1XfacvxSJVRpBLWOh0A2MHCFkNazY9Hqo8vD.OV.tju2luG',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:50:36','2021-04-28 04:18:37'),
(188,'Grand Setiabudi Hotel','reservation@grandsetiabudihotel.co.id','setiabudihotel',NULL,NULL,NULL,NULL,'2021-03-04 05:51:05',1,'2021-03-04 05:51:05',NULL,NULL,'$2y$10$kA4UbyB9ETAPqOyE/QsqqOUW.Uf3qSUu.MwL5Khk9yFJ5FM4dU60m',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:51:05','2021-06-15 04:05:13'),
(189,'PT. Berkah Anugrah Hotel','GFresort@4vm.com','gfresort',NULL,NULL,NULL,NULL,'2021-03-04 05:51:35',1,'2021-03-04 05:51:35',NULL,NULL,'$2y$10$udy8YC7.FtTCLv3sIUrqleAB21.1veWvQLACi7LuV30rzWd3GkXg6',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:51:35','2022-06-07 09:55:37'),
(190,'WEBSITE IBL INTERNATIONAL','TATANG.SOLIHIN@IBL.OR.ID','IBLink',NULL,NULL,NULL,NULL,'2021-03-04 05:52:01',1,'2021-03-04 05:52:01',NULL,NULL,'$2y$10$YZpgo6E8dqp6MTQbv2mVzu76Tv1j8H8yGV.pwo9QI5padwYMnot.i',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:52:01','2021-04-26 04:13:39'),
(191,'Institut Agama Islam Negeri NAD','IAIN@4vm.com','InstitutNAD',NULL,NULL,NULL,NULL,'2021-03-04 05:53:31',1,'2021-03-04 05:53:31',NULL,NULL,'$2y$10$54z78bPEOYzj8jstdE7qfOku1eqF/7xDaty4LF8jGhrSFBZ6N.ntG',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:53:31','2021-03-04 05:53:31'),
(192,'Jakarta Service Office','JSO@4vm.com','Jakartaservice',NULL,NULL,NULL,NULL,'2021-03-04 05:53:58',1,'2021-03-04 05:53:58',NULL,NULL,'$2y$10$Euk5sohCfKNNxyUakV9rL.L.Hz4oiS77/N8i2Og/eM5TKYaUhxo3y',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:53:58','2021-03-04 05:53:58'),
(193,'Kabupaten Sorong','kabsorong@4vm.com','kabsorong',NULL,NULL,NULL,NULL,'2021-03-04 05:54:40',1,'2021-03-04 05:54:40',NULL,NULL,'$2y$10$zldZzFwjMw0WyzlVLBHuN.Vc3XHdkur02s.VOtr4CeMUCiqpbWdnK',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:54:40','2021-03-04 05:54:40'),
(195,'Kementerian Luar Negeri','kontak-kami@kemlu.go.id','kemenlu',NULL,NULL,NULL,NULL,'2021-03-04 05:56:29',1,'2021-03-04 05:56:29',NULL,NULL,'$2y$10$Du531.7L0rvXKBuFH9wvMuzBiHX.3fwcN66HsWKFJsFHErKwdlmGe',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:56:29','2021-06-15 04:05:24'),
(196,'KEMENTERIAN PEMBERDAYAAN PEREMPUAN DAN PERLINDUNGAN ANAK REPUBLIK INDONESIA','KPPAI@4vm.com','kppai',NULL,NULL,NULL,NULL,'2021-03-04 05:57:14',1,'2021-03-04 05:57:14',NULL,NULL,'$2y$10$XnAbWVPk6ZH9eJVTMzUniuOEhy9v./sqD9F2vm1xGf.DnN13ca5y.',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:57:14','2021-03-04 05:57:14'),
(197,'Kementrian ESDM','klik@esdm.go.id','kemesdm',NULL,NULL,NULL,NULL,'2021-03-04 05:57:37',1,'2021-03-04 05:57:37',NULL,NULL,'$2y$10$jgxvBzZmCKmm7TeKpV4FK.N.olTNQ0P2uVy/FPx9NhkO2GPnU4KeG',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:57:37','2021-06-15 04:05:35'),
(198,'Website PT. Kertabumi Teknindo','kertabumipow@4vm.com','kertabumipow',NULL,NULL,NULL,NULL,'2021-03-04 05:58:10',1,'2021-03-04 05:58:10',NULL,NULL,'$2y$10$YC4T2eugN9OcdtYd16hz9OGpyLFS4pMn66bl4p4tMbZlfc5a7kgiu',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:58:10','2021-04-28 03:45:27'),
(199,'Website Kids Republic Jakarta','ZITA.ANJANI@KIDSREPUBLIC','kidsrepublic',NULL,NULL,NULL,NULL,'2021-03-04 05:58:36',1,'2021-03-04 05:58:36',NULL,NULL,'$2y$10$AbXLzOWUlYjvGpD/5BVVmOWOyygsNMDadQHPhLRmMxDKXkzJJfFAG',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:58:36','2021-04-28 02:50:50'),
(200,'Klinik Hadiana Sehat','hadianasehat@4vm.com','hadianasehat',NULL,NULL,NULL,NULL,'2021-03-04 05:59:01',1,'2021-03-04 05:59:01',NULL,NULL,'$2y$10$QKqNk.lEVKykhyJNpfYREuEC1/HDYSXNhQHkQTvSPquHC1f4RPy6G',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:59:01','2021-03-04 05:59:01'),
(201,'Aplikasi Manajemen Klinik Siliwangi Kesdam Cimahi','kesdamcimahi@4vm.com','kesdamcimahi',NULL,NULL,NULL,NULL,'2021-03-04 05:59:35',1,'2021-03-04 05:59:35',NULL,NULL,'$2y$10$iOpwlN.I0S62OG49yD0CF.PmNpVKG87MsojdEbJN58pG/RV8aXz7m',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 05:59:35','2021-04-28 04:20:15'),
(202,'Klinik Wirasakti Kesdam 3 Siliwangi','wirasaktikesdam@4vm.com','wirasaktikesdam',NULL,NULL,NULL,NULL,'2021-03-04 06:00:35',1,'2021-03-04 06:00:35',NULL,NULL,'$2y$10$2p4X2HgQia/uHQHUYPop2uEpqYZQUY/sdxqwLLGktDO92rwG1Hc9e',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:00:35','2021-03-04 06:00:35'),
(203,'Komisi Pemilihan Umum','KPUI@4vm.com','KPUID',NULL,NULL,NULL,NULL,'2021-03-04 06:01:05',1,'2021-03-04 06:01:05',NULL,NULL,'$2y$10$oJefn28W9pzXvJnsLLFD8.8O1dhYqVj5wa8eA3Op0cEEqQVTyHNvO',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:01:05','2021-03-04 06:01:05'),
(204,'Komunitas Foto Budaya','fotobudaya@4vm.com','fotobudaya',NULL,NULL,NULL,NULL,'2021-03-04 06:01:37',1,'2021-03-04 06:01:37',NULL,NULL,'$2y$10$zFKrWq09wNJG3xnEsDov5.Y79T46QQ2aiUyYKPpBIY6RY/4xt2vR.',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:01:37','2021-03-04 06:01:37'),
(205,'Komunitas Macro PhotoGraphy','macrophoto@4vm.com','macrophoto',NULL,NULL,NULL,NULL,'2021-03-04 06:02:07',1,'2021-03-04 06:02:07',NULL,NULL,'$2y$10$lv70.QLiPPENsxcNW1iLDur3NdzKfWtDApW5MZyOotD.gmw3aKYOC',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:02:07','2021-03-04 06:02:07'),
(206,'Komunitas Muslim','komumu@4vm.com','komumu',NULL,NULL,NULL,NULL,'2021-03-04 06:02:42',1,'2021-03-04 06:02:42',NULL,NULL,'$2y$10$qAp4H3hC24YHuKXUmz0slOCmrdwqMRiB.OXWFEUTvx2hxNOkqoV/u',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:02:42','2021-03-04 06:02:42'),
(207,'Komunitas Pendidikan Kita','kopenkit@4vm.com','kopenkit',NULL,NULL,NULL,NULL,'2021-03-04 06:03:03',1,'2021-03-04 06:03:03',NULL,NULL,'$2y$10$/EaB6ZegnNPeADV21e6lC.0xDsa9Ic2rJg9K6A2gpbYTRftnajTa2',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:03:03','2021-03-04 06:03:03'),
(208,'KOPERASI ASTRA INTERNATIONAL','astrainter@4vm.com','astrainter',NULL,NULL,NULL,NULL,'2021-03-04 06:03:37',1,'2021-03-04 06:03:37',NULL,NULL,'$2y$10$sFdSDyAwx7HD.17g8ahew.X/AdX56ZuahQOZi1L8wtSd6Y7jhOlv6',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:03:37','2021-03-04 06:03:37'),
(209,'Koperasi Bali Surya Dewata','balisurya@4vm.com','balisurya',NULL,NULL,NULL,NULL,'2021-03-04 06:04:07',1,'2021-03-04 06:04:07',NULL,NULL,'$2y$10$nL7pkp6FPTdAoJuNEzcA9e62RvYqlHOidcGrcMQ5.yJcQWSwp5w/K',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:04:07','2021-03-04 06:04:07'),
(210,'Koperasi BMT EL Bangkit','bmtelbangkit@4vm.com','bmtelbangkit',NULL,NULL,NULL,NULL,'2021-03-04 06:04:28',1,'2021-03-04 06:04:28',NULL,NULL,'$2y$10$40M42.0kCPUF2LqyQtkIKO2R6i1v5Ifb7c2nfpKktKWi/S12FrPcy',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:04:28','2021-04-14 07:21:25'),
(211,'Koperasi Cipta Inti Samudra \"CIS\"','koperasiciptaintisamudra@gmail.com','ciptaintisamudra',NULL,NULL,NULL,NULL,'2021-03-04 06:05:05',1,'2021-03-04 06:05:05',NULL,NULL,'$2y$10$4eemgbQ7/MH61WlJ0jSEHeYamQZMcPv.3FhWTd7hN6ePcbgBbJj6S',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:05:05','2021-04-16 06:34:44'),
(212,'Koperasi Energi Mulia Nusantara','koperasi.ken@gmail.com','KEMNusa',NULL,NULL,NULL,NULL,'2021-03-04 06:05:47',1,'2021-03-04 06:05:47',NULL,NULL,'$2y$10$sChf/FpMSIKSn9yubLqHv.3vJzdlvy5i.b21M3wsQ70xVb73VQkdG',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:05:47','2021-04-16 06:06:26'),
(213,'Koperasi Jasa Karyawan PT. PLN Area Bekasi dan Cikarang','kopjapln@4vm.com','kopjapln',NULL,NULL,NULL,NULL,'2021-03-04 06:06:24',1,'2021-03-04 06:06:24',NULL,NULL,'$2y$10$ZL2RKPVUQgR2ElUZlyNx2e0qLeuz4G9yjhL2wTvZ4NKm03JG7kLQa',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:06:24','2021-03-04 06:06:24'),
(214,'Koperasi Jasa Keuangan Syariah BMT EL Tazkiyyah','bmteltazkiyah@gmail.com','bmteltazkiyyah',NULL,NULL,NULL,NULL,'2021-03-04 06:07:17',1,'2021-03-04 06:07:17',NULL,NULL,'$2y$10$B5nzcCH5njNRxLU.JzXQ/O8zLnRTg3DjB06QAw2R5oE2KgcPwqy7q',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:07:17','2021-04-16 06:23:47'),
(215,'Koperasi Jasa Rancage Jawa Barat','koperasijasarancagejabar@gmail.com','kopjarancage',NULL,NULL,NULL,NULL,'2021-03-04 06:07:45',1,'2021-03-04 06:07:45',NULL,NULL,'$2y$10$FvsFq44suuG7WcQYc42JHO4REDKdBES2dVaX8TD4EmtHlzRatch8G',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:07:45','2021-04-16 06:18:57'),
(216,'Koperasi Karyawan BGA','kopkarbga@4vm.com','koppkarbga',NULL,NULL,NULL,NULL,'2021-03-04 06:08:26',1,'2021-03-04 06:08:26',NULL,NULL,'$2y$10$rgkYqFOaIYP1WpAoyTUM2erInusuiREBZYTTi5w6k1lJ6VcQW5ZnG',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:08:26','2021-03-04 06:08:26'),
(217,'Koperasi Karyawan PT Seiwa Indonesia','kopkarseiwa@4vm.com','kopkarseiwa',NULL,NULL,NULL,NULL,'2021-03-04 06:09:15',1,'2021-03-04 06:09:15',NULL,NULL,'$2y$10$OmWjBz6JaIt7ESpD3zYYnOPi4MM2NPm6MEL/mt5o8BxKm9duAsyLa',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:09:15','2021-03-04 06:09:15'),
(218,'Koperasi Karyawan PT Transmarco Indonesia','kopkartrans@4vm.com','koppkartrans',NULL,NULL,NULL,NULL,'2021-03-04 06:09:42',1,'2021-03-04 06:09:42',NULL,NULL,'$2y$10$yG.Tu20X3rEqoqQDsBrJsOKlPMfbEwEwHe65lkYy/ZWwnlmAU9QL2',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:09:42','2021-03-04 06:09:42'),
(219,'Koperasi Karyawan PT. RODA PRIMA LANCAR \"Kopkar RPL\"','kopkarrpl@4vm.com','kopkarrpl',NULL,NULL,NULL,NULL,'2021-03-04 06:10:14',1,'2021-03-04 06:10:14',NULL,NULL,'$2y$10$jE.HdmEzaIkzi53SyIBwXeCOswHia0AMfJ9aisoUHWHBYTYMorZtq',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:10:14','2021-03-04 06:10:14'),
(220,'Koperasi Karyawan Sejahtera Mandiri BPSDM','koperasi.sejahteramandiri.bpsdm@gmail.com','kopkarbpsdm',NULL,NULL,NULL,NULL,'2021-03-04 06:10:48',1,'2021-03-04 06:10:48',NULL,NULL,'$2y$10$UBIpyCWKpfOZFfZzdX.keOGdjHT5ebenekVsMDRL5LF/Zclx1kwt6',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:10:48','2021-04-16 06:40:32'),
(221,'Koperasi Wanita Sejahtera (KOPWANTERA)','kopwantera@yahoo.com','kopwantera',NULL,NULL,NULL,NULL,'2021-03-04 06:11:23',1,'2021-03-04 06:11:23',NULL,NULL,'$2y$10$RdFZevB7dMRYJ9SUHo6lBeWnH3EbESTN/12iRYqHj2FNynKDSH9J2',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:11:23','2021-04-16 06:29:57'),
(223,'Koperasi Mikro Sumut','komiksumut@4vm.com','komiksumut',NULL,NULL,NULL,NULL,'2021-03-04 06:13:40',1,'2021-03-04 06:13:40',NULL,NULL,'$2y$10$hVq13Ef.zC9ICVluHevvv.TZ8/SFIlk9SU.ANGZrKhjfNC57CnPnm',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:13:40','2021-03-04 06:13:40'),
(224,'Koperasi Nanda Trans','kopnan@4vm.com','kopnan',NULL,NULL,NULL,NULL,'2021-03-04 06:14:03',1,'2021-03-04 06:14:03',NULL,NULL,'$2y$10$dfV8NJRCToJiSSIlSNrDne2DXcdiBR2b1jecO06mv2PE6.Kjsnj9i',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:14:03','2021-04-20 08:18:37'),
(225,'Koperasi Pegawai BPKP Provinsi Jawa Barat','koppegpw10@gmail.com','kopbpkp',NULL,NULL,NULL,NULL,'2021-03-04 06:14:27',1,'2021-03-04 06:14:27',NULL,NULL,'$2y$10$ZKim4lMFBH6AxWTkZYz4TOOS4cvcwzOTFBCcJIJ73gQLegmID1RTa',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:14:27','2021-04-16 06:27:30'),
(226,'Koperasi PRIMKOPAU Pukadara Lanud Suryadarma','primkopau@4vm.com','primkopau',NULL,NULL,NULL,NULL,'2021-03-04 06:14:54',1,'2021-03-04 06:14:54',NULL,NULL,'$2y$10$X3eoG8bD4R58xeEI.Dyhu.FInw39HEOjGdG7mzLPg7RMF3gmTQCi.',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:14:54','2021-04-16 06:21:57'),
(227,'Koperasi Pusat Koperasi Wanita (PUSKOWAN) Jawa Barat','puskowanjabar@gmail.com','puskowan',NULL,NULL,NULL,NULL,'2021-03-04 06:15:19',1,'2021-03-04 06:15:19',NULL,NULL,'$2y$10$JfTFnOfP40JrBBUW1mDPsO0qOH.unjVReD5.vlrdvp1InqIZd2Qi2',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:15:19','2021-04-16 06:08:49'),
(228,'Koperasi Selaras','selaras@4vm.com','selaras',NULL,NULL,NULL,NULL,'2021-03-04 06:15:46',1,'2021-03-04 06:15:46',NULL,NULL,'$2y$10$04sLS.mH80Q7O3K5n1d5jejjBpfI1lQjG/bGAR0o3mraVVYCo6U1K',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:15:46','2021-04-20 08:20:32'),
(229,'Koperasi Simpan Pinjam Pembiayaan Syariah Perambabulan','kopsiperam@4vm.com','kopsiperam',NULL,NULL,NULL,NULL,'2021-03-04 06:16:12',1,'2021-03-04 06:16:12',NULL,NULL,'$2y$10$JdLiqFXAC8STplUbU/WQS.HQROksUprjXNMmjiGWM2Jmtby0cpUHO',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:16:12','2021-03-04 06:16:12'),
(230,'Koperasi Wanita Binangkit','kopwanbin@4vm.com','kopwanbin',NULL,NULL,NULL,NULL,'2021-03-04 06:16:47',1,'2021-03-04 06:16:47',NULL,NULL,'$2y$10$luOGsT5i/qH5Sia5JhWQJOviYtkj./SX36UgiGOaEi.fKwydED642',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:16:47','2021-04-16 06:09:56'),
(231,'KOPTI Kabupaten Kuningan','koptikun@4vm.com','koptikun',NULL,NULL,NULL,NULL,'2021-03-04 06:17:12',1,'2021-03-04 06:17:12',NULL,NULL,'$2y$10$y16PUAFdGWtX10C9ZPIZzOg2ons1NeDsu62hTcdeXhpLic6urmo7u',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:17:12','2021-03-04 06:17:12'),
(232,'Website Korps Pasukan Khas TNI AU','DISPENKORPAKHAS@GMAIL.COM','korptniau',NULL,NULL,NULL,NULL,'2021-03-04 06:17:42',1,'2021-03-04 06:17:42',NULL,NULL,'$2y$10$e/z4usxcuiZPbDWCS1h.neTtzlVy9ZWYmLzReh3vbJIf1MCawwZYy',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:17:42','2021-04-28 03:50:02'),
(233,'Website Koyota Industrial Group','info@koyotaig.com','koyota',NULL,NULL,NULL,NULL,'2021-03-04 06:18:07',1,'2021-03-04 06:18:07',NULL,NULL,'$2y$10$qzv0KlcK6A3KP4pJa8AiMO6/1t.WUVmlNsyvqx3NTJZEZ7NzxWOQi',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:18:07','2021-06-15 04:05:47'),
(234,'Website Krista Kosmetik','info@kristanusantara.com','kristakos',NULL,NULL,NULL,NULL,'2021-03-04 06:18:29',1,'2021-03-04 06:18:29',NULL,NULL,'$2y$10$1Hr.bTsKeHc3l9S0sU5XR.yxMD2xulf3S54kORfjaIP6El0szvoCC',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:18:29','2021-06-15 04:05:58'),
(235,'KUKM PERINDAG KOTA BANDUNG','kukmperindag@4vm.com','kukmperindag',NULL,NULL,NULL,NULL,'2021-03-04 06:19:13',1,'2021-03-04 06:19:13',NULL,NULL,'$2y$10$TXVRf1yElihLx2sr7oak9eKBXdLzjOm27ynS6H37Lfy./k3pxzsNS',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:19:13','2021-03-04 06:19:13'),
(236,'Website Ladja Hotel','customerservices@ladjahotel.com','ladjahotel',NULL,NULL,NULL,NULL,'2021-03-04 06:19:35',1,'2021-03-04 06:19:35',NULL,NULL,'$2y$10$dZEmJaeuOxZgPT2nsNpczexr3LWfzeZRtYtI6it.HpRSIWZE10aGe',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:19:35','2021-06-15 04:06:08'),
(237,'Website Laely Photography','laelyphoto@4vm.com','laelyphoto',NULL,NULL,NULL,NULL,'2021-03-04 06:20:11',1,'2021-03-04 06:20:11',NULL,NULL,'$2y$10$xzmBH6fQY/0LXXNe.VX0kuJD4U9HLbsWBRAZIRh6PQ0EE63GIJxg2',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:20:11','2021-04-28 02:54:47'),
(238,'Lembaga Penjaminan Mutu Pendidikan','LPMPID@4vm.com','LPMPID',NULL,NULL,NULL,NULL,'2021-03-04 06:20:37',1,'2021-03-04 06:20:37',NULL,NULL,'$2y$10$I4pXtoHdJK0rGs8K67jw/ensKl32PUSZYp.as7J5hSAcfK/LtjRfK',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:20:37','2021-03-04 06:20:37'),
(239,'LPMP Halmahera','info@lpmpmalut.id','LPMPhal',NULL,NULL,NULL,NULL,'2021-03-04 06:21:09',1,'2021-03-04 06:21:09',NULL,NULL,'$2y$10$KwsCcl3k1PP2gpviyxfL1.TYlGq/X1Urg4ipxpbscfBh0ukagLwqW',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:21:09','2021-06-15 04:06:49'),
(240,'LPMP Jawa Barat','lpmpjabar@4vm.com','lpmpjabar',NULL,NULL,NULL,NULL,'2021-03-04 06:21:34',1,'2021-03-04 06:21:34',NULL,NULL,'$2y$10$/ZPHDx0ANoHdYl/OuUWGfeSapR//OZz4rGPpGhjroCbGp4YYQDhAW',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:21:34','2021-03-04 06:21:34'),
(241,'SMAN 1 Garut','sma1garut@4vm.com','sma1garut',NULL,NULL,NULL,NULL,'2021-03-04 06:22:01',1,'2021-03-04 06:22:01',NULL,NULL,'$2y$10$zBEdDX6NNiW6.y0gCmrmQu/CF7STuEWnVS.HhsyDjUoJhu7RTCYe6',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:22:01','2021-03-04 06:22:01'),
(242,'Website Mason Pine Hotel','MARCOM@MASONPINEHOTELdotCOM','masonhotel',NULL,NULL,NULL,NULL,'2021-03-04 06:23:09',1,'2021-03-04 06:23:09',NULL,NULL,'$2y$10$ynOEc9Q3ykcE9rsI611/G.Lhhyw7WK4DVjh0L5kyI6nQB46Ud/YaS',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:23:09','2021-04-28 03:47:53'),
(243,'Website, Desain Logo, Video & Foto Profil Max Q Group','ratila@maxqgroup.net','maxqgroup',NULL,NULL,NULL,NULL,'2021-03-04 06:23:38',1,'2021-03-04 06:23:38',NULL,NULL,'$2y$10$vwNcFYFMsovDk2tTtLsuWeSVJ5tfiTBnESFfk3/xcmbclbVmOmmjC',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:23:38','2021-04-28 04:16:23'),
(244,'Megapolitan Mall','marketing@malmetropolitan.com','megapolitanmall',NULL,NULL,NULL,NULL,'2021-03-04 06:24:15',1,'2021-03-04 06:24:15',NULL,NULL,'$2y$10$8y1Zq8XZiUNCuy.3PxZc8e8Pihdy57/sYD10dr1t3MAGDLpuW3qaW',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:24:15','2021-06-15 04:07:01'),
(245,'Aplikasi e-Learning MIZAN','dedi.suprianto@mizan.com','mizan',NULL,NULL,NULL,NULL,'2021-03-04 06:24:40',1,'2021-03-04 06:24:40',NULL,NULL,'$2y$10$yKzk51p.pda.NU1So9ATpOlEXG76EhhXyxWsirY63czpC5POa2meC',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:24:40','2021-04-28 04:38:40'),
(246,'Website MQ Indonesia','boykefm@gmail.com','MQIndo',NULL,NULL,NULL,NULL,'2021-03-04 06:25:50',1,'2021-03-04 06:25:50',NULL,NULL,'$2y$10$Qr9E2Sx.G3piU6y7jdbOyeXK/eZjNQy6j45qrqGxmnFh.WxA0O0sK',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:25:50','2021-04-28 04:41:54'),
(247,'Pemerintah Maluku Tenggara','pemmaluku@4vm.com','pemmaluku',NULL,NULL,NULL,NULL,'2021-03-04 06:26:52',1,'2021-03-04 06:26:52',NULL,NULL,'$2y$10$AGLEKoRQkiMHRWm9Vc8JOuIP2Ih6BF6ZqDy5m8wr1QJ6F3JjK2COm',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:26:52','2021-03-04 06:26:52'),
(250,'Aplikasi SIDARAJA PLN (PERSERO) UIP2B UP2B - JAWA BARAT','PLNUIP2B@4vmcom','PLNUIP2B',NULL,NULL,NULL,NULL,'2021-03-04 06:29:13',1,'2021-03-04 06:29:13',NULL,NULL,'$2y$10$CvZOQWGYphyj7gonIbGBAOF0oB6Z8bG4u48csw9rgYXt7LNLPIIgq',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:29:13','2021-04-28 04:35:23'),
(251,'PLN Persero','pln123@pln.co.id','PLNPERSERO',NULL,NULL,NULL,NULL,'2021-03-04 06:30:01',1,'2021-03-04 06:30:01',NULL,NULL,'$2y$10$JtnQ/4YFZkPjXFP43js.nusqdo61blmc2/97ZpzW9jvCgOjFpyMcS',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 06:30:01','2021-06-15 04:07:13'),
(252,'Polres Kota Payakumbuh','polrespayakumbuh71@gmail.com','polrespayakumbuh',NULL,NULL,NULL,NULL,'2021-03-04 07:16:06',1,'2021-03-04 07:16:06',NULL,NULL,'$2y$10$3XB8E1JsJatZzGCZpeYRbu1xrBoFN6f5ldxq1FWZfhb1HCc0.TPLK',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:16:06','2021-06-15 04:07:26'),
(253,'Poltekpar Medan','info@poltekparmedan.ac.id','Poltekparmedan',NULL,NULL,NULL,NULL,'2021-03-04 07:18:07',1,'2021-03-04 07:18:07',NULL,NULL,'$2y$10$ikgYh1EaDfiKZdlfbGok.OgEN66/VDmEo1wdTN.P/x7xun.Y9khAe',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:18:07','2021-03-04 07:18:07'),
(254,'Website Portal Berita Nusaina News','NUSAINAONLINE@YAHOO.COM','pbnews',NULL,NULL,NULL,NULL,'2021-03-04 07:37:55',1,'2021-03-04 07:37:55',NULL,NULL,'$2y$10$n5SjLBiVkEl/5nIXEiQpDOYoV5BDqw4OWnAk6CdDTtaxeQnPR6pPe',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:37:55','2021-04-28 02:58:52'),
(255,'Portal Maroni - Radio','portalmaroni@4vm.com','portalmaroni',NULL,NULL,NULL,NULL,'2021-03-04 07:38:27',1,'2021-03-04 07:38:27',NULL,NULL,'$2y$10$Cd/ixVo8lGQtLIKJrwz.Uu2popiAc5ZQluB1cefQRuYlVaOzATwG2',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:38:27','2021-03-04 07:38:27'),
(256,'PROV. JAWA BARAT','provjabar@4vm.com','provjabar',NULL,NULL,NULL,NULL,'2021-03-04 07:38:55',1,'2021-03-04 07:38:55',NULL,NULL,'$2y$10$yd5/6FOR1EuRoHrM6iYDUeRWH8bs3q98KQef6.GDI5xOx92B6W78C',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:38:55','2021-03-04 07:38:55'),
(258,'PT Maha Parma Indonesia','mahaparma@4vm.com','mahaparma',NULL,NULL,NULL,NULL,'2021-03-04 07:39:46',1,'2021-03-04 07:39:46',NULL,NULL,'$2y$10$VYgaspH96f8sDUCpf7C7M./Fieb6wCoxgVzZRP.XpcgiPTlJ1MWty',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:39:46','2021-03-04 07:39:46'),
(260,'PT Solusindo Jaya Abadi','support@indonetwork.co.id','solusindo',NULL,NULL,NULL,NULL,'2021-03-04 07:40:56',1,'2021-03-04 07:40:56',NULL,NULL,'$2y$10$RpTfDYhmMtDPjiBHOCQcNeYcAiL3NdRTVPROj/Tas3ng8X/VQVTXy',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:40:56','2021-06-15 04:07:39'),
(261,'Website PT. Aditya Sejahtera Mandiri','minda.pilson@ams-group.co.id','adityasejahtera',NULL,NULL,NULL,NULL,'2021-03-04 07:41:22',1,'2021-03-04 07:41:22',NULL,NULL,'$2y$10$s5AUZp.EYpZCw1FK3Fwq7e3qGM5NZ3h2vbul0WeMWRPHjEhpYLIV.',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:41:22','2021-04-28 03:42:21'),
(262,'Website PT. Astellas Indonesia','ain_mi@astellas.com','astellas',NULL,NULL,NULL,NULL,'2021-03-04 07:41:51',1,'2021-03-04 07:41:51',NULL,NULL,'$2y$10$QvkJMRQH.T7mtGTOFPXERe/JMNDnucv4l7CYsg7UBbTcooaa/v7hO',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:41:51','2021-06-15 04:07:49'),
(263,'Website PT. Aurora Indo Asia','auroraindo@4vm','auroraindo',NULL,NULL,NULL,NULL,'2021-03-04 07:42:19',1,'2021-03-04 07:42:19',NULL,NULL,'$2y$10$cwQwSx4H1ik3xruXRw93HukG8k0iWyOVdSHPzjB8VgxRdZdJ4I8jC',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:42:19','2021-04-28 02:40:29'),
(264,'Sistem KIOSK PT. Axis Cipta Kreasi','AXISCIPTAKREASI@YAHOO.COM','axiscipta',NULL,NULL,NULL,NULL,'2021-03-04 07:42:44',1,'2021-03-04 07:42:44',NULL,NULL,'$2y$10$.jWRUOJlqZVTPs.knaH6reKuPVL3zHhGE2UC9FNcZsOCN4MbNOyKa',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:42:44','2021-04-28 02:41:37'),
(265,'PT. Batara Titan Kencana','firman@bdtbt.esdm.go.id','xtrans',NULL,NULL,NULL,NULL,'2021-03-04 07:43:16',1,'2021-03-04 07:43:16',NULL,NULL,'$2y$10$vS7Sx0I2xq1osx28T1uw6ur.wMygKQ1EmKzPU/8Nr0JTYKIBWFCgm',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:43:16','2022-06-07 09:55:58'),
(266,'PT. Batin Eka Perkasa','batineka@4vm.com','batineka',NULL,NULL,NULL,NULL,'2021-03-04 07:43:45',1,'2021-03-04 07:43:45',NULL,NULL,'$2y$10$vQ6htV3Jr.mWyHLsw6tudeo421moyr0bzPlasKi6mJvrbxCzeCN.6',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:43:45','2021-03-04 07:43:45'),
(267,'PT. Batununggal Indah Perkasa','yosi.saputra@batununggalindah.com','batuindahperkasa',NULL,NULL,NULL,NULL,'2021-03-04 07:46:00',1,'2021-03-04 07:46:00',NULL,NULL,'$2y$10$4KaCqZTiAD.RW4Qgb0rHMeuSKqBENK6tlAThtVqJ3xUJGbx.UBDIy',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:46:00','2021-04-28 04:02:02'),
(268,'Aplikasi Penjualan Bongman International','gm@bongman-garment.com','bongman',NULL,NULL,NULL,NULL,'2021-03-04 07:46:24',1,'2021-03-04 07:46:24',NULL,NULL,'$2y$10$OrCOvSe51ZPEef9jSBlOaOa5W4RtwnLt.5il2.bQxyaNfzr7M8hKu',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:46:24','2021-04-26 07:58:17'),
(269,'Website PT. Bumi Inti Selaras','info@ptbis.co.id','bumiselaras',NULL,NULL,NULL,NULL,'2021-03-04 07:46:54',1,'2021-03-04 07:46:54',NULL,NULL,'$2y$10$rifXNzlCqn31.A7nN.nTRuKHEYfIhkXlN8wALR2b82sgKl9DUHIGK',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:46:54','2021-06-15 04:08:05'),
(270,'PT. BUMN Hijau Lestari','bumnhijau@4vm.com','bumnhijau',NULL,NULL,NULL,NULL,'2021-03-04 07:47:21',1,'2021-03-04 07:47:21',NULL,NULL,'$2y$10$DlVqL0l4DVu8dpWa48OSUeI4jfk3FLWLpLbVqeZalI.CGxiqWt7V.',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:47:21','2021-03-04 07:47:21'),
(271,'Website PT. Delapan Kargo Ekspres','delapankargo@4vm.com','delapankargo',NULL,NULL,NULL,NULL,'2021-03-04 07:47:56',1,'2021-03-04 07:47:56',NULL,NULL,'$2y$10$IZVfKZESDkEVtBgNyOyNmOhMhBANay.sWa4psq1dmL/f1eBgd4Bfi',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:47:56','2021-04-28 03:43:50'),
(272,'PT. Eska Tours - eskatours Travel','eskatours@4vm.com','eskatours',NULL,NULL,NULL,NULL,'2021-03-04 07:48:25',1,'2021-03-04 07:48:25',NULL,NULL,'$2y$10$/SDCVIGmm1Wl/tBj5OlXw.omVWMgNnUiEdQXmcv7MJhKG61UB31vO',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:48:25','2021-03-04 07:48:25'),
(273,'PT. Geo Ace','info@geoace.co.id','geoace',NULL,NULL,NULL,NULL,'2021-03-04 07:48:55',1,'2021-03-04 07:48:55',NULL,NULL,'$2y$10$RZis5Yon5ud3sJt/YTNg..Xcy54ez8T.VF7EHjt4tB7JOlkj81dKq',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:48:55','2021-06-15 04:08:17'),
(274,'PT. Heikel Media','heikelmedia@4vm.com','heikelmedia',NULL,NULL,NULL,NULL,'2021-03-04 07:49:59',1,'2021-03-04 07:49:59',NULL,NULL,'$2y$10$QifEg176Z/rW8MNTULXFqOpazY0FAvS9b/1xjWr2ctZ985npbrRZ2',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:49:59','2021-04-26 07:53:36'),
(275,'PT. Hijrah Modal Mikro','AMARULLAH_HIJRAH@YAHOO.CO.ID','hijrahmikro',NULL,NULL,NULL,NULL,'2021-03-04 07:50:30',1,'2021-03-04 07:50:30',NULL,NULL,'$2y$10$hsm70UqZwc1sL43tXpwncObtTKXgfi9qgnJaWvTk9Hi/2t1/8gjW.',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:50:30','2021-04-26 04:08:53'),
(277,'PT. Inti Bagas Perkasa','priyo_psa@yahoo.co.id','intibagas',NULL,NULL,NULL,NULL,'2021-03-04 07:51:31',1,'2021-03-04 07:51:31',NULL,NULL,'$2y$10$O1tm/pzV8o2OjzI.kSHau.IOhxydshGwWaiPKx0MBFJuILYh/F1hm',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:51:31','2021-04-26 07:15:47'),
(278,'PT. Jakarta Jetset Power System','jakartajetsetpower@4vm.com','jakartajetsetpower',NULL,NULL,NULL,NULL,'2021-03-04 07:52:12',1,'2021-03-04 07:52:12',NULL,NULL,'$2y$10$zmoOnGvLuBVZsahaIW5TTu7BXB.V8qlkg8AS9b5ykVgAmlPFFYiRC',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:52:12','2021-03-04 07:52:12'),
(279,'PT. Jasa Marga','jasamarga@4vm.com','jasamarga',NULL,NULL,NULL,NULL,'2021-03-04 07:52:47',1,'2021-03-04 07:52:47',NULL,NULL,'$2y$10$5cI83zt/p/96SxNqs.cBs.dWLGVRQ8vUM3276ZZTPIQeQ/sBaLDKG',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:52:47','2021-03-04 07:52:47'),
(280,'PT. Lapi ITB','office@lapi-itb.com','lapiitb',NULL,NULL,NULL,NULL,'2021-03-04 07:53:22',1,'2021-03-04 07:53:22',NULL,NULL,'$2y$10$d6G54n0RRPEnURveCL6r0eWLqyOFyEb4M4hN1cf27vnNBl6.KTYjO',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:53:22','2022-02-15 09:57:21'),
(282,'PT. Lapisindo','RAPINDO.HUTAGALNG@LAPISINDO.CO.ID','lapisindo',NULL,NULL,NULL,NULL,'2021-03-04 07:54:25',1,'2021-03-04 07:54:25',NULL,NULL,'$2y$10$PFHvbxwNs8K5/5j9UpmyHepXJwgWtEQazc8IBkiz559P6Zz3qzXYq',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:54:25','2021-04-26 06:13:26'),
(283,'PT. LASER','laserindo@4vm.com','laserindo',NULL,NULL,NULL,NULL,'2021-03-04 07:54:51',1,'2021-03-04 07:54:51',NULL,NULL,'$2y$10$K.kT/wr45O8Ux9lJHZXcDeaJLZzTdaiGrlnZmyFUEth3iLxQosRgW',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:54:51','2021-03-04 07:54:51'),
(284,'PT. Mandira Dian Semesta','info@mandira.id','mandiradian',NULL,NULL,NULL,NULL,'2021-03-04 07:56:00',1,'2021-03-04 07:56:00',NULL,NULL,'$2y$10$r2hreHR9L3bW2LGj7bix3epuSOAE5sDWyribXsN38sUZ5DRosMpQC',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:56:00','2021-06-15 04:08:27'),
(285,'Website PT. Manito World','DEDI@MANITOWORL.COM','manito',NULL,NULL,NULL,NULL,'2021-03-04 07:56:30',1,'2021-03-04 07:56:30',NULL,NULL,'$2y$10$5w9gizbxb8YbKa5FmuoSfeY5DxttdimsUBEcFWuPO6Xhps0nfUEEW',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:56:30','2021-04-26 07:59:51'),
(286,'PT. Martakusuma Sakti','SANDY@MARTAKUSUMA-SAKTI','martakusuma',NULL,NULL,NULL,NULL,'2021-03-04 07:56:57',1,'2021-03-04 07:56:57',NULL,NULL,'$2y$10$PANNpjzARMG8pFrsWFPDveVjT231aHFcrsn8opB6pZ5g5B4IxBQMe',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:56:57','2021-04-26 06:11:47'),
(287,'PT. Masri M Syariah','masrim@4vm.com','masrim',NULL,NULL,NULL,NULL,'2021-03-04 07:58:04',1,'2021-03-04 07:58:04',NULL,NULL,'$2y$10$upC8142AFcLLFCPzak30SeOnaKdMWG/B9kva0r3Px8rTJJziXygxe',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:58:04','2021-03-04 07:58:04'),
(288,'WEBSITE PT. NANO COATING INDONESIA','nanocoatingindo@4vm.com','nanocoating',NULL,NULL,NULL,NULL,'2021-03-04 07:58:41',1,'2021-03-04 07:58:41',NULL,NULL,'$2y$10$7eXlnfU0.F7laBP/0JSSVuge.8qX8dix060zTRlGPLAoBJXN9w.MG',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:58:41','2021-04-21 08:53:13'),
(289,'Website STV Bandung','pasundanutama@4vm.com','pasundanutama',NULL,NULL,NULL,NULL,'2021-03-04 07:59:06',1,'2021-03-04 07:59:06',NULL,NULL,'$2y$10$k5clf7ZlVAw2gi7E0wfBQ.dH6IVhvqFuHjTn7/7U4nmW3ZoXspIoG',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 07:59:06','2021-04-26 06:06:59'),
(290,'Website PT. Portibitrans Nusaindo Express','career@portibitransnex.com','portibitrans',NULL,NULL,NULL,NULL,'2021-03-04 08:00:11',1,'2021-03-04 08:00:11',NULL,NULL,'$2y$10$DP7N2mK4we8XtJSlr2UWCe5Lw9LOE9k4uLK4G8Wy51xwzmmJ29x7K',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:00:11','2021-06-15 04:08:40'),
(292,'Website PT. RPM Brake','arif.ibp@gmail.com','rpmbrake',NULL,NULL,NULL,NULL,'2021-03-04 08:03:41',1,'2021-03-04 08:03:41',NULL,NULL,'$2y$10$KHZzrPg7EeVQ2imwrg1K0uoi.SSU1slBs4n.OEwfWvPBUZfAiLoyS',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:03:41','2021-04-26 07:11:40'),
(293,'PT. Rudolf Chemicals','sales@rudolf.co.id','rudolf',NULL,NULL,NULL,NULL,'2021-03-04 08:04:11',1,'2021-03-04 08:04:11',NULL,NULL,'$2y$10$P.h/057sHSegsk5OJsGAjuVuaej3U/knC04TrihFirNRZ5cFdA1L6',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:04:11','2021-06-15 04:08:52'),
(294,'PT. Salisma Group','salisma@4vm.com','salisma',NULL,NULL,NULL,NULL,'2021-03-04 08:04:37',1,'2021-03-04 08:04:37',NULL,NULL,'$2y$10$3yyvW7RL8MsCeQCNcBu5SOJcyj1N/QxK.LKhK2rZjtarUomTl.95q',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:04:37','2021-03-04 08:04:37'),
(295,'PT. Sandin Group','info@sandinhequip.com','sandin',NULL,NULL,NULL,NULL,'2021-03-04 08:05:05',1,'2021-03-04 08:05:05',NULL,NULL,'$2y$10$Nwwfuhz54Y3Drbz13YQpTOhp.z3QX.0Pd1li8f/EFCCJMa9UxDEcO',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:05:05','2021-06-15 04:14:01'),
(296,'PT. Shirotol Jannah','shirotol@4vm.com','shirotoljannah',NULL,NULL,NULL,NULL,'2021-03-04 08:05:26',1,'2021-03-04 08:05:26',NULL,NULL,'$2y$10$5KkK3IocYnFvSYqYq4ADM.9EBQ8ys1EcBtgF88JQaY2SYDKxsurqS',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:05:26','2021-03-04 08:05:26'),
(297,'Website Berita Siwa Lima News','redaksi@siwalimanews.com','siwalima',NULL,NULL,NULL,NULL,'2021-03-04 08:05:48',1,'2021-03-04 08:05:48',NULL,NULL,'$2y$10$Gv/i84YBdH8XbZFqV6quAudBQtPhgiMN85csG9MNuY9dzo/XmAQhS',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:05:48','2021-06-15 04:14:11'),
(298,'PT. Social Advisory Indonesia','socialadv@4vm.com','socialadv',NULL,NULL,NULL,NULL,'2021-03-04 08:06:17',1,'2021-03-04 08:06:17',NULL,NULL,'$2y$10$CkLiEnN98XNhTnGjOMqAy.7hSFoYogIlVfaNk.jzimJxpK631T2FK',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:06:17','2021-03-04 08:06:17'),
(299,'Website PT. Solarens Ledindo','sales2@solarens.com','solarens',NULL,NULL,NULL,NULL,'2021-03-04 08:06:39',1,'2021-03-04 08:06:39',NULL,NULL,'$2y$10$SCmQfbm..pRoE8k9SIFMPOYcdVSpLFPacK2eQ6Xjqs1975BNobtdi',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:06:39','2021-06-15 04:14:24'),
(300,'PT. Tekno Asia','teknoasia@4vm.com','teknoasia',NULL,NULL,NULL,NULL,'2021-03-04 08:07:32',1,'2021-03-04 08:07:32',NULL,NULL,'$2y$10$9FDetbH8UAguIkYi4kDubOojDiikJv4uU2qhpedUexOSquO473mAC',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:07:32','2021-03-04 08:07:32'),
(301,'Website PT. Telehouse Engineering','telehouse@telehouse-eng.com','telehouse',NULL,NULL,NULL,NULL,'2021-03-04 08:10:01',1,'2021-03-04 08:10:01',NULL,NULL,'$2y$10$DZkEc5nTNDFtCBt1z0mUNu7RxpAEHSlHGqsXnIk9rNiHRniyS8ouy',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:10:01','2021-06-15 04:14:34'),
(302,'Website The Green Effect','trinotow@yahoo.co.id','totalguna',NULL,NULL,NULL,NULL,'2021-03-04 08:11:02',1,'2021-03-04 08:11:02',NULL,NULL,'$2y$10$tae07eVUIURTqSuonJrYZu734T9Kdz5s9rkTEJhPTdWKl4jVldOP2',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:11:02','2021-04-26 07:14:22'),
(303,'Pusat Pameran Iptek Kota Baru Parahyangan','pameranparahyangan@4vm.com','pameranpar',NULL,NULL,NULL,NULL,'2021-03-04 08:11:32',1,'2021-03-04 08:11:32',NULL,NULL,'$2y$10$Wrq7vhoT9gaj/2/SSpWkL.JuXzDKEFdPBnayktSHwtgv82IIoW8Va',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:11:32','2021-03-04 08:11:32'),
(304,'Website Roman Granit Jakarta','MARKETING.IT@ROMANCERAMICS.COM','romangranitjkt',NULL,NULL,NULL,NULL,'2021-03-04 08:11:57',1,'2021-03-04 08:11:57',NULL,NULL,'$2y$10$TG5PdDgGsNoXwicxUPLM0ujQllPqbq4YtErJcLynuvXPUwyJuG.1.',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:11:58','2021-04-28 02:31:13'),
(305,'RPLDN','RPLDN@4vm.com','RPLDN',NULL,NULL,NULL,NULL,'2021-03-04 08:12:29',1,'2021-03-04 08:12:29',NULL,NULL,'$2y$10$wkXHvvIqYFrouJGvbZhrPe6z8pDFkDT6/qQk1zY.gymRED2dNE0UK',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:12:29','2021-03-04 08:12:29'),
(306,'Website Samudera Photography','samuderaphoto@4vm.com','samuderaphoto',NULL,NULL,NULL,NULL,'2021-03-04 08:12:49',1,'2021-03-04 08:12:49',NULL,NULL,'$2y$10$JmhO3Ghv3xMda5sW.4w0S.mFH17jSGFiU67628lri7FSqkD3gWbFW',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:12:49','2021-04-26 06:04:08'),
(307,'Website Sanggar Talitha','sanggartali@4vm.com','sanggartali',NULL,NULL,NULL,NULL,'2021-03-04 08:13:08',1,'2021-03-04 08:13:08',NULL,NULL,'$2y$10$ewbBM0Iu/kwPpfKIqHEpYuAlUVRNT5p9BJhdc6xGqaxRwYXp2y8oa',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:13:08','2021-04-26 07:55:34'),
(308,'Satuan Polisi Pamong Praja','satpolpp@4vm.com','satpolpp',NULL,NULL,NULL,NULL,'2021-03-04 08:13:32',1,'2021-03-04 08:13:32',NULL,NULL,'$2y$10$sd9mf5osglJ5xcuePwR8N.YfZKjzlqhvtTyP19bc4av79heWfynLS',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:13:32','2021-03-04 08:13:32'),
(309,'Sekolah Menengah Atas Situjuah','SMAsitujuah@4vm.com','SMAsitujuah',NULL,NULL,NULL,NULL,'2021-03-04 08:13:58',1,'2021-03-04 08:13:58',NULL,NULL,'$2y$10$8lsKO1o91nnqpt1B23AR2e6rvyTwDuQEQ/ARzfszMq85.PeduGMkS',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:13:58','2021-03-04 08:13:58'),
(310,'Sekolah Menengah Kejuruan Negeri','SMKN@4vm.com','SMKNe',NULL,NULL,NULL,NULL,'2021-03-04 08:14:34',1,'2021-03-04 08:14:34',NULL,NULL,'$2y$10$6/JSy7i5XERvhjj7yIg20uRQwtLsd9ASzQzvdKSCwsgBX/.R2pcjK',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:14:34','2021-03-04 08:14:34'),
(311,'Sekolah tinggi Agama Islam Persis','STAIPersis@4vm.com','STAIpersis',NULL,NULL,NULL,NULL,'2021-03-04 08:14:58',1,'2021-03-04 08:14:58',NULL,NULL,'$2y$10$QJ3bztIlYI9LPhqBhgXI0uXo7bDDuOved6I5hQIBhGeGj/c.mR/6G',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:14:58','2021-03-04 08:14:58'),
(312,'Sekolah Tinggi Pariwisata Bandung','contact@stp-bandung.ac.id','STPBdg',NULL,NULL,NULL,NULL,'2021-03-04 08:15:50',1,'2021-03-04 08:15:50',NULL,NULL,'$2y$10$jWNPQGxNw3kxW/9Aj3l5weV6FDKSLrFoJ5UGXH2b6jG5e/oqi.rdC',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:15:50','2021-06-15 04:14:55'),
(313,'Sikallaya LTD Thailand','sikallaya@4vm.com','sikallaya',NULL,NULL,NULL,NULL,'2021-03-04 08:16:15',1,'2021-03-04 08:16:15',NULL,NULL,'$2y$10$tBT2pyaGwCq9aMhNxx2NcOtvXQ..Bohq1MqbiNQE8SPpHSP39mPFG',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:16:15','2021-03-04 08:16:15'),
(314,'KPPPA (Kementrian Pemberdayaan Perempuan dan Perlindungan Anak)','sikepa@4vm.com','sikepa',NULL,NULL,NULL,NULL,'2021-03-04 08:16:36',1,'2021-03-04 08:16:36',NULL,NULL,'$2y$10$nxt.FomKT16tofD.9lVlwu8Z7yVaOqq44NHeIG7jbO38oEdGgVUpO',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:16:36','2022-06-07 09:53:44'),
(315,'SMAN 1 Gorontalo','smagtlo@4vm.com','smagtlo',NULL,NULL,NULL,NULL,'2021-03-04 08:17:00',1,'2021-03-04 08:17:00',NULL,NULL,'$2y$10$4HuZG/MeGrbBD/6w1AFzRe3X4TEoL2YzpifiocW0NCSgB8zmuYc2S',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:17:00','2021-04-21 08:38:56'),
(316,'SMAN 1 Kabila','smakabil@4vm.com','smakabil',NULL,NULL,NULL,NULL,'2021-03-04 08:17:27',1,'2021-03-04 08:17:27',NULL,NULL,'$2y$10$Z72SKMUR.xYKokEBiM1MQunL2H2pVpG1G82ePYQUkFxEPXsAcwEue',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:17:27','2021-03-04 08:17:27'),
(317,'SMPN 1 Gorontalo','smp1gtlo@4vm.com','smp1gtlo',NULL,NULL,NULL,NULL,'2021-03-04 08:18:57',1,'2021-03-04 08:18:57',NULL,NULL,'$2y$10$3jlYR13DgJ82.Ib5M8lJv.ObxO0bC2zccrgLSgC0L1ectpdppACEy',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 08:18:57','2021-03-08 04:13:57'),
(318,'SMPN 1 Kabila','smp1kabila@4vm.com','smp1kabila',NULL,NULL,NULL,NULL,'2021-03-04 09:45:23',1,'2021-03-04 09:45:23',NULL,NULL,'$2y$10$9O8eREtQ2cVcXeVT2u4nuucLkxhgtVNbPSmLyqs8zm0NdjoW1BsBW',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 09:45:23','2021-03-04 09:45:23'),
(319,'Website SMPN 27 Siak','smpsiak@4vm.com','smpsiak',NULL,NULL,NULL,NULL,'2021-03-04 09:46:03',1,'2021-03-04 09:46:03',NULL,NULL,'$2y$10$HXDwJAQRV3p30ei/srHwqe2dJF.Ev1X9jLvKuqyxPlzSeHH68D2Ci',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 09:46:03','2021-04-26 06:08:57'),
(321,'SMPN 8 Gorontalo','smpn8gtlo@4vm.com','smpn8gtlo',NULL,NULL,NULL,NULL,'2021-03-04 09:47:15',1,'2021-03-04 09:47:15',NULL,NULL,'$2y$10$LYoIVbLxXE68DUdJEL5ed.XMmk1zqwIpprxc/crkiqBjl2TNrMde.',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 09:47:15','2022-06-07 09:54:46'),
(322,'STAIPI','staipi@4vm.com','staipi',NULL,NULL,NULL,NULL,'2021-03-04 09:47:42',1,'2021-03-04 09:47:42',NULL,NULL,'$2y$10$7eg2oV.63lgAdr56TUZkdO/1dxjaH11idrwYpbZlJFiwoboqtff9C',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 09:47:42','2022-06-07 09:54:34'),
(323,'STIT Al - Ihsan','stit@gmail.com','stitihsan',NULL,NULL,NULL,NULL,'2021-03-04 09:48:12',1,'2021-03-04 09:48:12',NULL,NULL,'$2y$10$gAU4QQ5iHMPCNFrC653LJu9k3gwjFpH2TAvNjQ0wTCkmpwsilO0sS',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 09:48:12','2021-06-15 04:15:24'),
(324,'The Majesty Hotel','reservation@themajestyhotel.co.id','majestyhotel',NULL,NULL,NULL,NULL,'2021-03-04 09:48:34',1,'2021-03-04 09:48:34',NULL,NULL,'$2y$10$dXVp6S.A8eHqYcnbnRwSuOvcjoTHCgqQTIeAh1YlqJfvEdmKoUNp.',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 09:48:34','2021-06-15 04:15:35'),
(325,'Website Trapperpad','trapperpad@4vm.com','trapperpad',NULL,NULL,NULL,NULL,'2021-03-04 09:49:12',1,'2021-03-04 09:49:12',NULL,NULL,'$2y$10$orFakqFF8/PYDKgX1vduWuISAN4fWbSA/G6/QE5z5/o2CSYvd3VAy',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 09:49:12','2021-04-26 07:46:11'),
(326,'Twie E-Sport Arena','official.twieesportarena@4vm.com','twiesport',NULL,NULL,NULL,NULL,'2021-03-04 09:49:42',1,'2021-03-04 09:49:42',NULL,NULL,'$2y$10$PlBNNZ1bELNaQLZ5BZYC2.wGQ589lI6hr2oPCmMT6BDDV7I5hSYgG',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 09:49:42','2021-04-05 07:18:27'),
(327,'Universitas Krisnadwipayana Jakarta','marketing@unkris.ac.id','krisnadwipayana',NULL,NULL,NULL,NULL,'2021-03-04 09:50:13',1,'2021-03-04 09:50:13',NULL,NULL,'$2y$10$qUJ1J.t9MiM7DJOyV4vMBeX1gQ9c2TZ.jCLyD67vp9WGiEiaG7Qa.',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 09:50:14','2021-06-15 04:15:48'),
(328,'Universitas Pendidikan Indonesia','sekuniv_upi@upi.edu','upibandung',NULL,NULL,NULL,NULL,'2021-03-04 09:50:41',1,'2021-03-04 09:50:41',NULL,NULL,'$2y$10$JmrUUxDt9f8DK2efGZIOT.WJyNj0qAAsIWoGl4WAqz04fsfkmBPkW',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 09:50:41','2021-06-15 04:16:05'),
(329,'Yayasan Al Ihsan','yayasanihsan@4vm.com','yayasanihsan',NULL,NULL,NULL,NULL,'2021-03-04 09:51:30',1,'2021-03-04 09:51:30',NULL,NULL,'$2y$10$U9p7pX/mnK1HL5POdZVhvuf2qIFQbLo3bWODm1GgFI09pCVnlH67W',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 09:51:30','2021-03-15 03:40:24'),
(330,'Yayasan Ghuraf','M-ARIEF@CAA-TELCO.COM','ghuraf',NULL,NULL,NULL,NULL,'2021-03-04 09:51:55',1,'2021-03-04 09:51:55',NULL,NULL,'$2y$10$mHbEbYSDHzodr4g2k4Bb5u7LCYUlSsJFS9fCpjDYVnNE1k5XHzgnC',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 09:51:55','2022-06-07 09:55:08'),
(331,'Yayasan Mizan Amanah','info@mizanamanah.or.id','yayasanmizahamanah',NULL,NULL,NULL,NULL,'2021-03-04 09:52:42',1,'2021-03-04 09:52:42',NULL,NULL,'$2y$10$ZmuwN/MJo6uRVvqdMe/KaO5uL.HUuiu.MknHBHrt2Q4CoC92UtF8S',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 09:52:42','2021-04-05 07:26:57'),
(332,'Website Zelza Bedlinen Product','BRIZALDY@ZELZA-BEDLINEN.COM','zelzabelinen',NULL,NULL,NULL,NULL,'2021-03-04 09:53:12',1,'2021-03-04 09:53:12',NULL,NULL,'$2y$10$bIA/U777q4XluRHNQEkO5.kkGQaUHx257WM7SORCcr9pNFqkVIUKK',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-04 09:53:12','2021-04-26 04:15:02'),
(333,'Regy M Azis','regy@4visionmedia.com','regy@4visionmedia.com',NULL,NULL,NULL,NULL,'2021-03-12 08:00:26',1,'2021-03-12 08:00:26',NULL,NULL,'$2y$10$v3E1eJePHRsVGfqbkGSHluQyJbRDGR6lPMibqND5rR6dImGaagLiO',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-12 08:00:26','2022-08-01 10:32:46'),
(334,'Koperasi Sehat Sejahtera','kopsehat@4vm.com','kopsehatsejahtera',NULL,NULL,NULL,NULL,'2021-03-26 07:32:08',1,'2021-03-26 07:32:08',NULL,NULL,'$2y$10$rgkAPao.xP8DShx4ocsjMuX5bA9v6cIK8Kj82GRelaeo4rmEvkgfm',NULL,0,NULL,NULL,NULL,0,NULL,'2021-03-26 07:32:08','2021-03-26 07:32:08'),
(337,'KOPERASI ARTO MULYO LESTARI','artomulyolestari@gmail.com','KOPERASI ARTO MULYO',NULL,NULL,NULL,NULL,'2021-04-12 07:16:27',1,'2021-04-12 07:16:27',NULL,NULL,'$2y$10$Omy8TuukpEJquDreUFBlGeM.Ewpp3Vfu41zKiXmhG8xWPDquEuBom',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-12 07:16:27','2021-04-12 07:16:27'),
(338,'Koperasi Karyawan RSBD','rsbd@4vm.com','koprsbd',NULL,NULL,NULL,NULL,'2021-04-16 06:37:07',1,'2021-04-16 06:37:07',NULL,NULL,'$2y$10$qed8o.OvhDgr4T4PzaQwee7IQSJaI8QhOm.xrL2AB.95.WStlTkem',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-16 06:37:08','2021-04-16 06:37:08'),
(339,'Koperasi Karyawan PT Intercallin','intercallin@4vm.com','intercallin',NULL,NULL,NULL,NULL,'2021-04-20 08:13:40',1,'2021-04-20 08:13:40',NULL,NULL,'$2y$10$YdU3LpBr3pZ.v0RC3X5xquwNsm.0zx2LizWHBIkF2AjtliaagChFK',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-20 08:13:40','2021-04-20 08:13:40'),
(340,'Koperasi PKK Nusa Indah','intansarid083@gmail.com','KoperasiPKK',NULL,NULL,NULL,NULL,'2021-04-20 08:26:52',1,'2021-04-20 08:26:52',NULL,NULL,'$2y$10$tyqcswcLttFhr61bgCAcqO0bs07pQougySbfi5UPD7GkrQWnWG8F2',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-20 08:26:52','2021-04-20 08:26:52'),
(341,'Koperasi PKRI Sejahtera Pasawahan','pkri@4vm.com','pkripasawahan',NULL,NULL,NULL,NULL,'2021-04-20 08:28:16',1,'2021-04-20 08:28:16',NULL,NULL,'$2y$10$rRMwgruMp1I1iQXNds5.f.efSE1vMlX0GSn5z/t4vFuHGfArEwHaG',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-20 08:28:16','2021-04-20 08:28:16'),
(342,'Koperasi Gapoktan Mukti Jaya','gapoktan@4vm.com','KoperasiGapoktan',NULL,NULL,NULL,NULL,'2021-04-20 08:29:24',1,'2021-04-20 08:29:24',NULL,NULL,'$2y$10$kby2KZyjm0/hioFOdleTUefM1o39EeY1RHyFESGzeCuOrCZnFY2r.',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-20 08:29:24','2021-04-20 08:29:24'),
(343,'Koperasi Solidaritas Agape','koperasisolidaritas281104@gmail.com','agape',NULL,NULL,NULL,NULL,'2021-04-20 08:30:34',1,'2021-04-20 08:30:34',NULL,NULL,'$2y$10$iz.t/94WeXipsOx1N4haqeZM1vA2evnM2YJd13uPj07KIqlDSCdM.',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-20 08:30:34','2021-04-20 08:30:34'),
(344,'Koperasi Tahu Tempe Indonesia Kab.Kuningan','triyaniclaudia@gmail.com','tahutempe@4vm.com',NULL,NULL,NULL,NULL,'2021-04-20 08:31:52',1,'2021-04-20 08:31:52',NULL,NULL,'$2y$10$tzW0QVi5wcLaXlF2gQ4Z0e2wfxtPU6VW1PqljmDQdV8.4iTjNOeGS',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-20 08:31:52','2021-04-20 08:31:52'),
(347,'WEB PORTAL DAN APLIKASI WEB OFFICE Kangaroo Travel','kangaroo@4vm.com','kangarooo',NULL,NULL,NULL,NULL,'2021-04-21 08:33:24',1,'2021-04-21 08:33:24',NULL,NULL,'$2y$10$aisYUQrKmmw2PJCzLi9seOoRw7MYs0.0pc0rrBiFu3AdiaIJ8JxN.',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-21 08:33:24','2021-04-21 08:33:24'),
(348,'WEBSITE BPMPD KALIMANTAN SELATAN','dinaspmdkalsel@gmail.com','bpmpd',NULL,NULL,NULL,NULL,'2021-04-21 08:40:34',1,'2021-04-21 08:40:34',NULL,NULL,'$2y$10$wnwVIv9tFvYQBXABmgOqSuCAEX3OF88QF.Dxa.7I/aIee8COIe1bm',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-21 08:40:34','2021-06-15 04:16:29'),
(351,'EMWE CONCENT ONLINE STORE','HARRY@EMWECONCEPT.COM','emwewe',NULL,NULL,NULL,NULL,'2021-04-21 08:44:19',1,'2021-04-21 08:44:19',NULL,NULL,'$2y$10$vXUgYs46EhdNpkaIdbZd0O/zOuPiFl6WdCUB3cRErnOtk8D6d47cq',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-21 08:44:19','2022-06-07 09:57:08'),
(361,'Website Kab. Aceh Besar','me@marfikar.net','acehbesar',NULL,NULL,NULL,NULL,'2021-04-26 07:37:25',1,'2021-04-26 07:37:25',NULL,NULL,'$2y$10$tn3EAX0sBj/.05yFfJBWJum6wbkLc3BKvO7faaKMtoG4LMfl2z5AW',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-26 07:37:25','2021-04-26 07:37:25'),
(362,'Website Census Of Marine Life Indonesia','twagey@gmail.com','COMLIndo',NULL,NULL,NULL,NULL,'2021-04-26 07:50:20',1,'2021-04-26 07:50:20',NULL,NULL,'$2y$10$sR6gH6FOM5NSq01qNl75Luu4gjbNUuVtBjBPUoGHvGUN0MXsR7Gay',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-26 07:50:20','2021-04-26 07:50:20'),
(363,'Website Keris Foto','cakra.wiyata@gmail.com','kerisfoto',NULL,NULL,NULL,NULL,'2021-04-26 07:57:16',1,'2021-04-26 07:57:16',NULL,NULL,'$2y$10$L1iBXvvEwOz8QFFn3AxGVeKl9tMgmjyEjq8C60mrgE6Mc11Zjf1xm',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-26 07:57:16','2021-04-26 07:57:16'),
(364,'WEBSITE KORPASKHAS ANGKATAN UDARA','ILHAM532423@gmail.com','KORPASKHAS',NULL,NULL,NULL,NULL,'2021-04-26 08:06:22',1,'2021-04-26 08:06:22',NULL,NULL,'$2y$10$aZBisqpEIRD3qgJih.UbvuMf3rd/euSXvLC06cBC0RseunT4kUIdq',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-26 08:06:22','2021-04-26 08:06:22'),
(365,'WEBSITE PUSPA IPTEK - THE BIGGEST SUNDIAL','biggestsundial@4vm.com','thebiggestsundial',NULL,NULL,NULL,NULL,'2021-04-28 02:33:16',1,'2021-04-28 02:33:16',NULL,NULL,'$2y$10$2s2o.BwbAtCyzBIuLNpuMe41l7DBucHsGLYT1e32t7yjfX6cn003.',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-28 02:33:16','2021-04-28 02:33:16'),
(366,'Website PT. Bandung Mektan','bdgmektan@4vm.com','bdgmektan',NULL,NULL,NULL,NULL,'2021-04-28 02:37:22',1,'2021-04-28 02:37:22',NULL,NULL,'$2y$10$erKpgtW4/p2.7ezMjNtgQOenktYLSx.iwyM3wbGMkptRzdcKPSA.m',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-28 02:37:22','2021-04-28 02:37:22'),
(367,'Website Prodec STP Bandung','ZULKIFLI@PRODEC-STPBANDUNG.COM','prodecstp',NULL,NULL,NULL,NULL,'2021-04-28 02:38:42',1,'2021-04-28 02:38:42',NULL,NULL,'$2y$10$7.UMqTXuyidIrjcZD7IBVur4V4s8LnxrYFszaT.CyC/JITfa0wIf.',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-28 02:38:42','2021-04-28 02:38:42'),
(368,'WEBSITE GALLERY 55 PROPERTY','FUJIISKANDAR@HOTMAIL.CO.ID','gallery55',NULL,NULL,NULL,NULL,'2021-04-28 02:44:52',1,'2021-04-28 02:44:52',NULL,NULL,'$2y$10$SdfygVCJqQ8Xo0/y1eNtJusmJ7wSYXyZWyvH7.WVV1UDQUhTTQvYy',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-28 02:44:52','2021-04-28 02:44:52'),
(369,'HUMAN RESOURCE SOFTWARE TIMOR-LESTE','managementsoftware@4vm.com','timorlestesoft',NULL,NULL,NULL,NULL,'2021-04-28 02:47:09',1,'2021-04-28 02:47:09',NULL,NULL,'$2y$10$pu.Pv5UZuaVRmeRJsAMmmezHdaM6es4mehtFMbk2L3QLKkU3EGtBK',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-28 02:47:09','2021-04-28 02:47:09'),
(370,'PEMBUATAN WEBSITE ONLINE STORE FS925SILVER','FRANKLIN_MANGUNDAP@HOTMAIL.COM','FS925SILVER',NULL,NULL,NULL,NULL,'2021-04-28 02:48:30',1,'2021-04-28 02:48:30',NULL,NULL,'$2y$10$DkGtKbOnFnmnWfQGhwIADuWwj7VO7XIqXG2MZnPCorx7.uI8SB5wG',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-28 02:48:30','2021-04-28 02:48:30'),
(371,'PENYEDIAAN HARDWARE & SOFTWARE CROPPERMEDIA','CROPPERMEDIA@4VM.COM','CROPPERMEDIA',NULL,NULL,NULL,NULL,'2021-04-28 02:52:29',1,'2021-04-28 02:52:29',NULL,NULL,'$2y$10$DzuNaouNUmSzA0E8V.vEVuVX4XSQMcCYuPNakl2IcxlQt/FQaxm7y',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-28 02:52:29','2021-04-28 02:52:29'),
(372,'WEBSITE PT. TIRTA BHUVANA ABADI','BHUVANA@4VM.COM','BHUVANA',NULL,NULL,NULL,NULL,'2021-04-28 02:56:18',1,'2021-04-28 02:56:18',NULL,NULL,'$2y$10$11GdLFsOg8PybH7AWk68nOwX99DBSjc8p/8KlYU.uWyFbzU.FQ/ye',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-28 02:56:18','2021-04-28 02:56:18'),
(373,'WEBSITE MUSEUM PENDIDIKAN NASIONAL','museumpendidikannasional@upi.edu','museumnasional',NULL,NULL,NULL,NULL,'2021-04-28 03:16:45',1,'2021-04-28 03:16:45',NULL,NULL,'$2y$10$wb2rwLF8MUi/mKW9ds/pRuGIlBXtsUGVFpAobRYCE9vvMbqPWUfIO',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-28 03:16:45','2021-06-15 04:16:41'),
(374,'WEBSITE PORTAL INFORMASI DINAS TATA BANGUNAN&PEMUKIMAN KAB. BOGOR','tatabangunanbogor@4vm.com','tatabangunanbogor',NULL,NULL,NULL,NULL,'2021-04-28 03:19:50',1,'2021-04-28 03:19:50',NULL,NULL,'$2y$10$hvJl4pkPxr0c8EKjgBAQwe7eEs7p.3VfowygTG0105W/fNLGdpCcm',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-28 03:19:50','2021-04-28 03:19:50'),
(375,'APLIKASI PAYROLL DAN ABSENSI PT.VELVET TEKSTIL INDONESIA','JIWO.WINARNI@GMAIL.COM','velvetindo',NULL,NULL,NULL,NULL,'2021-04-28 03:27:20',1,'2021-04-28 03:27:20',NULL,NULL,'$2y$10$23PqDhWyhi46eanUZQncje29/YJGr1Zj2LW3fzNaaOGORsEpG.WFS',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-28 03:27:20','2021-04-28 03:27:20'),
(376,'Website Gagasan Riau','redaksi@gagasanriau.com','gagasanriau',NULL,NULL,NULL,NULL,'2021-04-28 03:29:38',1,'2021-04-28 03:29:38',NULL,NULL,'$2y$10$taBsLBgEBf8nBWBia03umefj9DyPVFAwbGSBZ.nBfbtfmi122d5um',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-28 03:29:38','2021-06-15 04:16:50'),
(377,'Updating Website Dinas Koperasi Kota Bandung','dinaskumkm.bdg@gmail.com','dinaskoperasi',NULL,NULL,NULL,NULL,'2021-04-28 03:35:27',1,'2021-04-28 03:35:27',NULL,NULL,'$2y$10$leC//0BFUwx/yqFBnfCGzejyPc7bsXBWZh8XP4sA6fwpV8F.cgTe6',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-28 03:35:27','2021-06-15 04:17:19'),
(378,'Website Diklat Tambang Maluku Tenggara','diklattambang@4vm.com','diklattambang',NULL,NULL,NULL,NULL,'2021-04-28 03:41:34',1,'2021-04-28 03:41:34',NULL,NULL,'$2y$10$T2EgxAGhMSdyFdjhQza5nOYX5gv43eWEjvq5UNxQqVQIY9eTMazx6',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-28 03:41:34','2021-04-28 03:41:34'),
(379,'APLIKASI HUMAN RESOURCE & GENERAL AFFAIR PT. MULTI HANNA KREASINDO','ARCAYUDI@LIVE.COM','multihanna',NULL,NULL,NULL,NULL,'2021-04-28 03:51:28',1,'2021-04-28 03:51:28',NULL,NULL,'$2y$10$BmqdnOtfes2aTc/9uSM2CeOCr4TBtWYINFd4n5WvJTSDellB22dOC',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-28 03:51:28','2021-04-28 03:51:28'),
(381,'Website & Aplikasi e-Ternak Bukit Biak','ARBIMUHAMAD@HOTMAIL.COM','bukitbiak',NULL,NULL,NULL,NULL,'2021-04-28 04:07:44',1,'2021-04-28 04:07:44',NULL,NULL,'$2y$10$KX4lqgJ.0tbsyOR97zsC0O32tR2Zvgcaf/iICMqt7md0fxejEVnXq',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-28 04:07:44','2021-04-28 04:07:44'),
(384,'Website Djautama Group','renny@napalima.com','djautama',NULL,NULL,NULL,NULL,'2021-04-28 04:14:40',1,'2021-04-28 04:14:40',NULL,NULL,'$2y$10$z1QT7kSaSh7IwnoW121fpOALp2ICUosnFDy9fQ.QnMbzVOxdP7n86',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-28 04:14:40','2021-04-28 04:14:40'),
(385,'Website Dinas Koperasi Kab. Bandung','diskopukm.bdgkab@gmail.com','koperasikabbandung',NULL,NULL,NULL,NULL,'2021-04-28 04:24:41',1,'2021-04-28 04:24:41',NULL,NULL,'$2y$10$EJH4fOJceUNyGg7V/C3ET.ZkMfh56K0Oz/jdIWChI95nujs8t3Duy',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-28 04:24:41','2021-06-15 04:17:30'),
(386,'Pengembangan Modul Data Perusahaan Aplikasi Sistem Sehati Direktorat Usaha Hutan Produksi Kementrian Perhutanan','pusdatin@menlhk.go.id','menlhkindo',NULL,NULL,NULL,NULL,'2021-04-28 04:29:26',1,'2021-04-28 04:29:26',NULL,NULL,'$2y$10$.55G4/URaxvZztJRU4A36.oCgg41CZxtfPycoc2d5sIZrpNYWBmmi',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-28 04:29:26','2021-04-28 04:29:26'),
(387,'WEBSITE WILD INDONESIA JOURNEY','wildindo@4vm.com','wildindo',NULL,NULL,NULL,NULL,'2021-04-28 04:31:23',1,'2021-04-28 04:31:23',NULL,NULL,'$2y$10$EphbPo03BhHpJaAAsEsZwuK7fSfYzL7KGnAJgCIOos3.9VD3dRRbG',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-28 04:31:23','2021-04-28 04:31:23'),
(388,'Website PT. Blue Royale Production','AHERBOWO@BRPBRAKES.COM','blueroyale',NULL,NULL,NULL,NULL,'2021-04-28 04:33:21',1,'2021-04-28 04:33:21',NULL,NULL,'$2y$10$hj6UcGKIHURM9JHTc8bRFODdHFVzOdqpL.R7T1JlVRHaNlyL5.qjO',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-28 04:33:21','2021-04-28 04:33:21'),
(389,'RPDLN','rpdln@4vm.com','rpdln4vm',NULL,NULL,NULL,NULL,'2021-04-28 04:44:10',1,'2021-04-28 04:44:10',NULL,NULL,'$2y$10$tifa1DeDn85K8yVHY7rbeeB5b3uBz8pSBa00J4x6Bwm1G9w3Uix5q',NULL,0,NULL,NULL,NULL,0,NULL,'2021-04-28 04:44:10','2021-04-28 04:44:10'),
(390,'PT. PLN IP Services (COGINDO DAYA BERSAMA)','admin@4visionmedia.com','Cogindo',NULL,NULL,NULL,NULL,'2021-08-06 08:21:09',1,'2021-08-06 08:21:09',NULL,NULL,'$2y$10$.hSxfdYWPeeZQLN7o3zya.W5dGyHDiF3HZ/.uTv9LQYWyN4vl/Thu',NULL,0,NULL,NULL,NULL,0,NULL,'2021-08-06 08:21:09','2025-02-10 03:35:34'),
(392,'Virtual Event (Iscod) / PERDOKSI','perdoksi@4visionmedia.com','perdoksi',NULL,NULL,NULL,NULL,'2021-08-26 04:44:04',1,'2021-08-26 04:44:04',NULL,NULL,'$2y$10$7sc4fW0JqE3fp8psXt6M9OAz7gS1JDSK8j.YIqyVhnzqH8f5IcZaa',NULL,0,NULL,NULL,NULL,0,NULL,'2021-08-26 04:44:04','2021-08-26 04:44:04'),
(393,'Database B-6','databaseb6@4visionmedia.com','Database B-6',NULL,NULL,NULL,NULL,'2021-08-26 04:45:26',1,'2021-08-26 04:45:26',NULL,NULL,'$2y$10$8/WHoRKYCEhptTjW9MtlMu9I0i5CQLi4udzmrcARWThl4IWFWvdgK',NULL,0,NULL,NULL,NULL,0,NULL,'2021-08-26 04:45:26','2021-08-26 04:45:26'),
(394,'Speakoud','speakoud@4visionmedia.com','Speakoud',NULL,NULL,NULL,NULL,'2021-08-26 04:46:36',1,'2021-08-26 04:46:36',NULL,NULL,'$2y$10$76Rx0ZrfltGp6PhTPKRDlOu8Myi9uLJ1afjrjS1vIf0IntE9B2wWy',NULL,0,NULL,NULL,NULL,0,NULL,'2021-08-26 04:46:36','2021-08-26 04:46:36'),
(400,'Lanud AU Sulaiman','permana_ilham@outlook.com','Lanud AU Sulaiman',NULL,NULL,NULL,NULL,'2021-11-23 07:08:05',1,'2021-11-23 07:08:05',NULL,NULL,'$2y$10$dnLJ1j82eirPmDYwEI5loegTXQiaFYqaTCLfAHUrF1vEk1SekICX.',NULL,0,NULL,NULL,NULL,0,NULL,'2021-11-23 07:08:05','2021-11-26 12:15:17'),
(422,'KSPPS CITRA PASUNDAN UTAMA','syifa.bmtcpu@gmail.com','pasundan',NULL,NULL,NULL,NULL,'2022-01-10 07:49:36',1,'2022-01-10 07:49:36',NULL,NULL,'$2y$10$LMontGoPWhZztKaTl7ochua9IEZirpqVFOa09prO9nARcvan9koMu',NULL,0,NULL,NULL,NULL,0,NULL,'2022-01-10 07:49:37','2022-01-10 07:49:37'),
(423,'Koperasi Hasna Medika Cirebon','koperasihasnamedikagroup@gmail.com','Koperasi Hasna Medika Cirebon',NULL,NULL,NULL,NULL,'2022-01-12 04:19:20',1,'2022-01-12 04:19:20',NULL,NULL,'$2y$10$x/vmWuBZUBeZGk4eQukpvuMN9kQUu5essiXTKrxLPIT8qociPLsRG',NULL,0,NULL,NULL,NULL,0,NULL,'2022-01-12 04:19:20','2022-01-12 04:19:20'),
(424,'Roman Granit','m.romangranit@gmail.com','romangra',NULL,NULL,NULL,NULL,'2022-01-13 09:37:43',1,'2022-01-13 09:37:43',NULL,NULL,'$2y$10$TBBK1fcPX4pFIll2nP1AdO44ufGPR85EAzUyNmN/iN4C2VNoPfAAC',NULL,0,NULL,NULL,NULL,0,NULL,'2022-01-13 09:37:43','2022-01-13 09:37:43'),
(425,'KSP AMAN DIGITAL INDONESIA','lasma@aman.digital','KSP AMAN DIGITAL INDONESIA',NULL,NULL,NULL,NULL,'2022-01-14 07:05:36',1,'2022-01-14 07:05:36',NULL,NULL,'$2y$10$vp1hRvJ0LNHagZbMKAltIeRewIyemF5Z4bx.FP4DHblYxCnG5B3QC',NULL,0,NULL,NULL,NULL,0,NULL,'2022-01-14 07:05:36','2022-01-14 07:05:36'),
(426,'KOPERASI SEMPEKAT SEMPAWAT','agustinussupiantognr1@gmail.com','KOPERASI SEMPEKAT SEMPAWAT',NULL,NULL,NULL,NULL,'2022-01-14 07:10:05',1,'2022-01-14 07:10:05',NULL,NULL,'$2y$10$jp70a6p9zJk/vlynwNMQLuiMHrIHcdzfy88CbbARfskNVyC/Nio8C',NULL,0,NULL,NULL,NULL,0,NULL,'2022-01-14 07:10:05','2022-01-14 07:10:05'),
(427,'Puri Apartment','email@lyman.co.id','puriapartment',NULL,NULL,NULL,NULL,'2022-01-17 04:41:28',1,'2022-01-17 04:41:28',NULL,NULL,'$2y$10$PRPohg3XBVVLRgzf7HSmVOpJWK6inzjZtzL2.lzWDrH9NtqYOdUIC',NULL,0,NULL,NULL,NULL,0,NULL,'2022-01-17 04:41:28','2022-01-17 04:41:28'),
(428,'KOPKAR PRIMADONA','fritsdimu@gmail.com','KOPKAR PRIMADONA',NULL,NULL,NULL,NULL,'2022-01-19 06:18:34',1,'2022-01-19 06:18:34',NULL,NULL,'$2y$10$4BJilgMtfLLi2fIy7xTwk.n6hC6h6R.R0G.myAzw.PVr6EScykTyC',NULL,0,NULL,NULL,NULL,0,NULL,'2022-01-19 06:18:34','2022-01-19 06:18:34'),
(430,'PT. KARYA IMTAQ','finance@4visionmedia.com','finance@4visionmedia.com',NULL,NULL,NULL,NULL,'2022-01-20 03:23:40',1,'2022-01-20 03:23:40',NULL,NULL,'$2y$10$pwrGvt9z9gT78MYDZjuDL.KJfc6w6G.NAdc6gow8FAT1lT28JDQTy',NULL,0,NULL,NULL,NULL,0,NULL,'2022-01-20 03:23:40','2022-01-20 03:23:40'),
(432,'BRI Kanwil Malang','malang@gmail.com','BRI Kanwil Malang',NULL,NULL,NULL,NULL,'2022-01-24 06:37:45',1,'2022-01-24 06:37:45',NULL,NULL,'$2y$10$zb2DnBpjiXTMBAKKqHsYlOE8j9tC8.UCKzidoZCTKDagjlHLYqhR6',NULL,0,NULL,NULL,NULL,0,NULL,'2022-01-24 06:37:45','2022-01-24 06:37:45'),
(433,'Koperasi Berkarya Sukses Sejahtera','rudi@koperasiberkaryasukses.com','Koperasi Berkarya Sukses Sejahtera',NULL,NULL,NULL,NULL,'2022-01-24 07:08:20',1,'2022-01-24 07:08:20',NULL,NULL,'$2y$10$RTncKGjmel5Dot5/NtfZpOqfaetNx9AK/aq/y20qnJKj86qiZiz62',NULL,0,NULL,NULL,NULL,0,NULL,'2022-01-24 07:08:20','2022-01-24 07:08:20'),
(434,'Koperasi Karyawan PT. Yasufuku Indonesia','yasufuku@gmail.com','Koperasi Karyawan PT. Yasufuku Indonesia',NULL,NULL,NULL,NULL,'2022-01-26 04:26:37',1,'2022-01-26 04:26:37',NULL,NULL,'$2y$10$uKpMT6aTV4HsmKg1bPNiHOBLxu8oDIYO1fVj.4Imord5GJDHGNnzW',NULL,0,NULL,NULL,NULL,0,NULL,'2022-01-26 04:26:37','2022-01-26 04:26:37'),
(435,'BRI Kanwil Banjarmasin','kanwilbanjarmasin@gmail.com','BRI Kanwil Banjarmasin',NULL,NULL,NULL,NULL,'2022-01-28 03:44:17',1,'2022-01-28 03:44:17',NULL,NULL,'$2y$10$EDlQF3m4Pe00EjBOM3KB2u9bJK7BkUCrJBoHmwArETfmOnF06N8Ju',NULL,0,NULL,NULL,NULL,0,NULL,'2022-01-28 03:44:18','2022-01-28 03:44:18'),
(436,'KOPERASI KARYAWAN RUMAH SAKIT PUPUK KALTIM','ichsany20137478@gmail.com','KOPERASI KARYAWAN RUMAH SAKIT PUPUK KALTIM',NULL,NULL,NULL,NULL,'2022-01-28 03:56:01',1,'2022-01-28 03:56:01',NULL,NULL,'$2y$10$lvJGtJPgk6MsBsztEvm4QuED5PBbAo0sxWGU6XdaruhcM1K9co/.C',NULL,0,NULL,NULL,NULL,0,NULL,'2022-01-28 03:56:02','2022-01-28 03:56:02'),
(437,'Koperasi Konsumen Syariah Griya Trust','griyatrust@gmail.com','Koperasi Konsumen Syariah Griya Trust',NULL,NULL,NULL,NULL,'2022-02-02 09:00:41',1,'2022-02-02 09:00:41',NULL,NULL,'$2y$10$6y.GSZAmud/8ynbRZUXaDO5DhI7FqKUUVTQ356CvJH06TlfgRhuui',NULL,0,NULL,NULL,NULL,0,NULL,'2022-02-02 09:00:41','2022-02-02 09:00:41'),
(442,'Koperasi Karyawan Pacific Place Jakarta','koperasi@pacificplace.co.id','pacificplace',NULL,NULL,NULL,NULL,'2022-02-03 10:06:48',1,'2022-02-03 10:06:48',NULL,NULL,'$2y$10$VZHl2JUOZBiONQPPlxf2JeUCvyUyUgsXoKdmr3PBTIVWOjMfzr1wy',NULL,0,NULL,NULL,NULL,0,NULL,'2022-02-03 10:06:48','2022-02-03 10:06:48'),
(443,'KOPERASI KONSUMEN PERANGKAT DESA INDONESIA (KOPERDES)','yudifir168@gmail.com','koperdesind@4visionmedia.com',NULL,NULL,NULL,NULL,'2022-02-04 08:29:48',1,'2023-09-15 04:00:57',NULL,NULL,'$2y$10$26IhUOfNF3jrqkxlZe.ib.2KyzuaE1I3vw6UjfyEZi02biZjUGtFC',NULL,0,NULL,NULL,NULL,0,NULL,'2022-02-04 08:29:49','2023-09-15 04:00:57'),
(453,'KSPPS BMT ANFA\' NU','bmtanfa@gmail.com','KSPPS BMT ANFA\' NU',NULL,NULL,NULL,NULL,'2022-02-15 03:26:28',1,'2022-02-15 03:26:28',NULL,NULL,'$2y$10$8whasOICzIL9AmFWEoxkge7o9JLxBxp8lF3q0deY1GACRD1BFmF2m',NULL,0,NULL,NULL,NULL,0,NULL,'2022-02-15 03:26:28','2022-02-15 03:26:28'),
(454,'Kopkarco','Kopkarco@gmail.com','Kopkarco',NULL,NULL,NULL,NULL,'2022-02-15 03:30:40',1,'2022-02-15 03:30:40',NULL,NULL,'$2y$10$2WCsn4OI3aOC1yHa8CMKzuY.CbEKAXjaMzRyCN9Z4PWF.Adui2OXW',NULL,0,NULL,NULL,NULL,0,NULL,'2022-02-15 03:30:40','2022-02-15 03:30:40'),
(455,'KOPERASI TRI SUKSES MAKMUR','makmur12345@4visionmedia.com','makmur12345@4visionmedia.com',NULL,NULL,NULL,NULL,'2022-02-15 09:08:29',1,'2022-02-15 09:08:29',NULL,NULL,'$2y$10$dnv2uHd9NaD03evVaWQoLOmP1qnr.DEYYG4cDq36slfd7HXjNt6qC',NULL,0,NULL,NULL,NULL,0,NULL,'2022-02-15 09:08:29','2022-02-15 09:08:29'),
(456,'KOPERASI KARYAWAN \"UNTUNG BERSAMA\"','kopkarub@4visionmedia.com','kopkarub@4visionmedia.com',NULL,NULL,NULL,NULL,'2022-02-17 03:22:29',1,'2022-02-17 03:22:29',NULL,NULL,'$2y$10$JCG3Iuzsk/mpV15kD9Gqwu/b7qave0e/zgJtzmi/n6tnsQO7bz0Yq',NULL,0,NULL,NULL,NULL,0,NULL,'2022-02-17 03:22:29','2022-02-17 03:22:29'),
(457,'KOPERASI KARYAWAN RS GATOEL','dwi2.sp@gmail.com','kopkarrsg@4visionmedia.com',NULL,NULL,NULL,NULL,'2022-02-18 04:05:41',1,'2022-02-18 04:05:41',NULL,NULL,'$2y$10$GPnMl/Neb.TZCv0WG0kIHugXgyyoLfHxxAQ9rtmCGrL/xSpHMmRdS',NULL,0,NULL,NULL,NULL,0,NULL,'2022-02-18 04:05:41','2022-02-18 04:05:41'),
(459,'KOPERASI KARYAWAN AQUA CITEUREUP','kkac@4visionmedia.com','kkac@4visionmedia.com',NULL,NULL,NULL,NULL,'2022-02-22 04:35:59',1,'2022-02-22 04:35:59',NULL,NULL,'$2y$10$gFFHo0z8aoDnQRFiDpcEruO1yi/hDoZC4XZwB3Xl21nOwFcyqleUW',NULL,0,NULL,NULL,NULL,0,NULL,'2022-02-22 04:35:59','2022-02-22 04:35:59'),
(460,'KOPERASI SERBA USAHA PUTRA MITRA MANDIRI','putramitramandiri@gmail.com','mitramandiri@smartcoop.com',NULL,NULL,NULL,NULL,'2022-02-23 10:31:33',1,'2022-02-23 10:31:33',NULL,NULL,'$2y$10$94ShxT7IEmEj/eGrK8p1O.J.1s6aUWZK.MDsTP2niRXrIgwPf4nui',NULL,0,NULL,NULL,NULL,0,NULL,'2022-02-23 10:31:33','2022-02-23 10:31:33'),
(461,'Smartcoop','info@smartcoop.id','smartcoop',NULL,NULL,NULL,NULL,'2022-03-01 08:39:30',0,'2022-03-01 08:39:30',NULL,NULL,'$2y$10$EM87OuvFdxoAwBMWhWCUf.XCEPDmf2y8HWgWTgDBt.aoRH0L8tIf6',NULL,0,NULL,NULL,NULL,0,NULL,'2022-03-01 08:39:30','2022-03-01 08:57:42'),
(463,'KOPERASI PELITA INDOSEJAHTERA','pelitaindosejahterakoperasi@gmail.com','pelita@4visionmedia.com',NULL,NULL,NULL,NULL,'2022-03-02 09:14:45',1,'2022-03-02 09:14:45',NULL,NULL,'$2y$10$i5bo1dQ34LlZzsW8YPtuqOuSehPQh1O2F.Zf0ahHfMIYSdI5/tjq.',NULL,0,NULL,NULL,NULL,0,NULL,'2022-03-02 09:14:46','2022-03-02 09:14:46'),
(464,'KOPERASI KELUARGA KARUNIA SEJAHTERA (K3S)','sanjupuput@gmail.com','k3s@4visionmedia.com',NULL,NULL,NULL,NULL,'2022-03-04 06:11:05',1,'2022-03-04 06:11:05',NULL,NULL,'$2y$10$vguhX4uMzUaxY49lmLqzLOHp6TjL6yFs0uxPf4tqF6waoArPqroxq',NULL,0,NULL,NULL,NULL,0,NULL,'2022-03-04 06:11:05','2022-03-04 06:11:05'),
(465,'OPERASI PEGAWAI KANTOR PUSAT DIREKTORAT JENDERAL BEA DAN CUKAI','kopesat.djbc@gmail.com','beacukai@4visionmedia.com',NULL,NULL,NULL,NULL,'2022-03-04 06:45:35',1,'2022-03-04 06:45:35',NULL,NULL,'$2y$10$djLmgGVzG9dMf3Vsc3p4CeQl1RS1zFObjxcvlwTxOcJZ8Q7XQy2nS',NULL,0,NULL,NULL,NULL,0,NULL,'2022-03-04 06:45:35','2022-03-04 06:45:35'),
(466,'KOPERASI KARYAWAN PAL JAYA','koperasi@paljaya.com','KOPERASI KARYAWAN PAL JAYA',NULL,NULL,NULL,NULL,'2022-03-04 08:17:30',1,'2022-03-04 08:17:30',NULL,NULL,'$2y$10$vV3MilTKgHTkeucC/vqwOekovihcO2iw.7eGy8iHKdaA7N1xbVtmW',NULL,0,NULL,NULL,NULL,0,NULL,'2022-03-04 08:17:30','2022-03-04 08:17:30'),
(467,'KOPERASI SUMBER REJEKI','ksr123@gmail.com','ksr@4visionmedia.com',NULL,NULL,NULL,NULL,'2022-03-05 10:47:27',1,'2022-03-05 10:47:27',NULL,NULL,'$2y$10$4lT5RuPdPTLX39oDOliOH.jEkrBj79RKAOYCW3YNbv3GzWVqiJ0kK',NULL,0,NULL,NULL,NULL,0,NULL,'2022-03-05 10:47:28','2022-03-05 10:47:28'),
(468,'KOPERASI SIMPAN PINJAM USAHA MANDIRI','kspum@gmail.com','kspum@4visionmedia.com',NULL,NULL,NULL,NULL,'2022-03-07 11:44:59',1,'2022-03-07 11:44:59',NULL,NULL,'$2y$10$ppUMd0z4i9HG6UnqiY.yv.KRaYZ8TuTRzZzVGWOBe.RgEyFdV8vB6',NULL,0,NULL,NULL,NULL,0,NULL,'2022-03-07 11:44:59','2022-03-07 11:44:59'),
(469,'KOPERASI SIMPAN PINJAM BUANA ARTHA SEJAHTERA','kspbas@gmail.com','kspbas@4visionmedia.com',NULL,NULL,NULL,NULL,'2022-03-09 04:27:42',1,'2022-03-09 04:27:42',NULL,NULL,'$2y$10$5yUKvOmxsoUyJ3Jh5xTNbO.PkFgkDtdC5uLMR6eB47q96jhPIJn0m',NULL,0,NULL,NULL,NULL,0,NULL,'2022-03-09 04:27:42','2022-03-09 04:27:42'),
(470,'testing client midtrans','testingmidtrans@gmail.com','testingmid',NULL,NULL,NULL,NULL,'2022-03-09 04:30:26',1,'2022-03-09 04:30:26',NULL,NULL,'$2y$10$7EY4S8E1c3T1qYUP/nI1b.AA5SB7h0qEouMkX6.xD5Hq94ojZwiB2',NULL,0,NULL,NULL,NULL,0,NULL,'2022-03-09 04:30:26','2022-03-11 04:25:08'),
(471,'KOPERASI KARYAWAN MAKMUR','nurjanah05111997@gmail.com','kkm@4visionmedia.com',NULL,NULL,NULL,NULL,'2022-03-15 03:59:39',1,'2022-03-15 03:59:39',NULL,NULL,'$2y$10$HAkEoCyonu7.DG2jhAqkYuIoPZdI4MsPOzqR/ZgcEcJtQntZBQy2a',NULL,0,NULL,NULL,NULL,0,NULL,'2022-03-15 03:59:39','2022-03-15 03:59:39'),
(473,'KOPERASI PRIMER PRAJAMUKTI 1 KEMENDAGRI','agusalim66817@gmail.com','prajamukti@4visionmedia.com',NULL,NULL,NULL,NULL,'2022-03-18 09:30:52',1,'2022-03-18 09:30:52',NULL,NULL,'$2y$10$occK6j6v3brd80bcSxoJV.nZ2pEh9hmxp.m.jVzx8X.0qD11ZlnoW',NULL,0,NULL,NULL,NULL,0,NULL,'2022-03-18 09:30:52','2022-03-18 09:30:52'),
(474,'PT. Bank Rakyat Indonesia (Persero) Tbk','mfd@gmail.com','PT. Bank Rakyat Indonesia (Persero) Tbk',NULL,NULL,NULL,NULL,'2022-03-23 06:24:57',1,'2022-03-23 06:24:57',NULL,NULL,'$2y$10$7pg2Fi6r9wYSr0SRZGV05eX/0qI/hodLtVFBKWLmxKQ/nBGc9AXYa',NULL,0,NULL,NULL,NULL,0,NULL,'2022-03-23 06:24:57','2022-03-23 06:24:57'),
(475,'KOPERASI SYARIAH SUARA HATI','kossuhasyariah@gmail.com','kossuha@4visionmedia.com',NULL,NULL,NULL,NULL,'2022-03-25 03:21:29',1,'2022-03-25 03:21:29',NULL,NULL,'$2y$10$TASdQ469Oh1UdjyahGu8vux5CK1lxxoHAaUGZ3jQ8ZZVExheGmbvW',NULL,0,NULL,NULL,NULL,0,NULL,'2022-03-25 03:21:29','2022-03-25 03:21:29'),
(476,'Koperasi Jasa Wisata Nusantara','kwbn@gmail.com','Koperasi Jasa Wisata Nusantara',NULL,NULL,NULL,NULL,'2022-03-25 06:52:59',1,'2022-03-25 06:52:59',NULL,NULL,'$2y$10$MyXOj3mhJqJzK96kuS/NcOuSnAYQiYJYvB8GkO2gviPn7ZkFvvYym',NULL,0,NULL,NULL,NULL,0,NULL,'2022-03-25 06:52:59','2022-03-25 06:52:59'),
(477,'KOPONTREN AL-MUHAJIRIN','kopontrenalmuhajirin@gmail.com','KOPONTREN AL-MUHAJIRIN',NULL,NULL,NULL,NULL,'2022-03-25 06:57:17',1,'2022-03-25 06:57:17',NULL,NULL,'$2y$10$uAyCwqKQmHkT7tuLObcwnunNCYCksKmnY0RtQ0qQZse6s2cy36Qfi',NULL,0,NULL,NULL,NULL,0,NULL,'2022-03-25 06:57:17','2022-03-25 06:57:17'),
(478,'KOPERASI PRIMKOPOL BATANG','primkoppolres.btg@gmail.com','primkopol@4visionmedia.com',NULL,NULL,NULL,NULL,'2022-03-25 07:24:39',1,'2022-03-25 07:24:39',NULL,NULL,'$2y$10$5CGcWdNn5z0ZJuLY11pN2utf7W9V/NrB9E8c2bMVt18ZwDNoe0jO.',NULL,0,NULL,NULL,NULL,0,NULL,'2022-03-25 07:24:39','2022-03-25 07:24:39'),
(479,'KOPERASI KARYAWAN MITRA MAKMUR','kopkarmahadaya@gmail.com','mitramakmur@4visionmedia.com',NULL,NULL,NULL,NULL,'2022-04-01 03:09:08',1,'2022-04-01 03:09:08',NULL,NULL,'$2y$10$rv83v2ykrRHeaG/pfylJ0Ov0ouLXb1/5aTroa0dvQKu2TPgZII8.i',NULL,0,NULL,NULL,NULL,0,NULL,'2022-04-01 03:09:08','2022-04-01 03:09:08'),
(480,'KOPERASI KONSUMEN KARYAWAN SAMPOERNA STRATEGIC','jaja.sumarja@yahoo.co.id','KOPERASI KONSUMEN KARYAWAN SAMPOERNA STRATEGIC',NULL,NULL,NULL,NULL,'2022-04-01 08:39:52',1,'2022-04-01 08:39:52',NULL,NULL,'$2y$10$mx33B2OIdsZxExtUTzCR9.09oWGm2qEwCUWhDOksMXT4sOT.ke39C',NULL,0,NULL,NULL,NULL,0,NULL,'2022-04-01 08:39:53','2022-04-01 08:39:53'),
(481,'KOPERASI PEGAWAI PT. SINKORA INDONESIA LESTARI','kopegptsil@gmail.com','sinkona@4visionmedia.com',NULL,NULL,NULL,NULL,'2022-04-04 07:29:09',1,'2022-04-04 07:29:09',NULL,NULL,'$2y$10$orktzOoAuEyFsqa2q6Ouf.CBS/Z3lrDV6FplFtBZ/2DuWLOsR177u',NULL,0,NULL,NULL,NULL,0,NULL,'2022-04-04 07:29:09','2022-04-04 07:29:09'),
(482,'KPRI KOGUSDA KOTA SOLOK','kogusdakotasolok@gmail.com','kogusdakotasolok@4visionmedia.com',NULL,NULL,NULL,NULL,'2022-04-08 09:30:17',1,'2022-04-08 09:30:17',NULL,NULL,'$2y$10$SyQjIAl84ytsayRcZrIsmu.H/texMk7wyD1tSB7CEW2gDZh4dmUrm',NULL,0,NULL,NULL,NULL,0,NULL,'2022-04-08 09:30:17','2022-04-08 09:30:17'),
(483,'KOPERASI PRODUSEN PESAT UNIFIT INDONESIA','unifit@gmail.com','KOPERASI PRODUSEN PESAT UNIFIT INDONESIA',NULL,NULL,NULL,NULL,'2022-04-12 03:26:09',1,'2022-04-12 03:26:09',NULL,NULL,'$2y$10$VFfHAmSHKHeaYmjCceqiOOQ4km/XTFQGJoK30wAXwOhk78rPmemfq',NULL,0,NULL,NULL,NULL,0,NULL,'2022-04-12 03:26:09','2022-04-12 03:26:09'),
(484,'KOPERASI \"MAGERI URIP\" KOPKAMU','mageriurip@gmail.com','mageriurip@4visionmedia.com',NULL,NULL,NULL,NULL,'2022-04-12 10:24:01',1,'2022-04-12 10:24:01',NULL,NULL,'$2y$10$dMFYQFhp2vw8m54CXfLRYeNL/Xdf7QTlQAJ9TRl31XbIrpa2B3bT6',NULL,0,NULL,NULL,NULL,0,NULL,'2022-04-12 10:24:02','2022-04-12 10:24:02'),
(485,'KOPERASI BISSMIKA USAHA MAKMUR NUSANTARA (BUMN)','bumnkoperasi@gmail.com','bumnkoperasi@4visionmedia.com',NULL,NULL,NULL,NULL,'2022-04-13 05:44:16',1,'2022-04-13 05:44:16',NULL,NULL,'$2y$10$vnunyRh/7Ss6CDxaiiJsW.yWge0LuP1O/JEAMCE46X7sal9lFTmL6',NULL,0,NULL,NULL,NULL,0,NULL,'2022-04-13 05:44:17','2022-04-13 05:44:17'),
(486,'KOPERASI PEMASARAN PEGAWAI NEGERI SYARIAH DINAS KESEHATAN KAB. AGAM (KPPNS DINAS KESEHATAN KAB.AGAM)','zulmihatinelvira1981@gmail.com','kppnsagam@4visionmedia.com',NULL,NULL,NULL,NULL,'2022-04-13 06:35:02',1,'2022-04-13 06:35:02',NULL,NULL,'$2y$10$RyT0/Dsb8PUme9.ckD9DhuaOUfS3vMG/BCQo/T/DTuByGtf88QwUm',NULL,0,NULL,NULL,NULL,0,NULL,'2022-04-13 06:35:02','2022-04-13 06:35:02'),
(487,'KOPERASI KSP WAHANA MULIA','kspwahana@mail.com','kspwahanamulia',NULL,NULL,NULL,NULL,'2022-04-13 08:15:19',1,'2022-04-13 08:15:19',NULL,NULL,'$2y$10$f69rzxQi16hU5AH.fTe9PON/cNKoB08WWdJjtnPNGUcJthpreEEHi',NULL,0,NULL,NULL,NULL,0,NULL,'2022-04-13 08:15:19','2022-04-13 08:15:19'),
(488,'KOPERASI SUMBER GRAHA SEJAHTERA','bara.gitas@gmail.com','KOPERASI SUMBER GRAHA SEJAHTERA',NULL,NULL,NULL,NULL,'2022-04-13 08:31:05',1,'2022-04-13 08:31:05',NULL,NULL,'$2y$10$J/8.zQbauCLkGUn/.n1eTOYW/3i1Myh5iDZnxdMoL9LlS1fw6rbnO',NULL,0,NULL,NULL,NULL,0,NULL,'2022-04-13 08:31:05','2022-04-13 08:31:05'),
(489,'Koperasi Karyawan Al Muhajirien','koperasi.almuhajirien@gmail.com','kopkarAlmuhajirien',NULL,NULL,NULL,NULL,'2022-04-18 03:58:49',1,'2022-04-18 03:58:49',NULL,NULL,'$2y$10$V5.HBf8WM2LZZEc/qDWsQu7.OCFKu4tcDEux9BVru77i7WAlggxym',NULL,0,NULL,NULL,NULL,0,NULL,'2022-04-18 03:58:50','2022-04-18 03:58:50'),
(490,'KSP RANGKUL TEMAN JAKARTA','doni.yahya@rangkulteman.id','KSPRangkulteman',NULL,NULL,NULL,NULL,'2022-04-18 03:59:59',1,'2022-04-18 03:59:59',NULL,NULL,'$2y$10$9jIONB8GVl.1T75IRmHeOeohp2hC1gbKj00W1Chp09cb2WLNqNQei',NULL,0,NULL,NULL,NULL,0,NULL,'2022-04-18 03:59:59','2022-04-18 03:59:59'),
(491,'KUD Sumber Alam','kudsumberalam@yahoo.co.id','KUDSumberAlam',NULL,NULL,NULL,NULL,'2022-04-20 06:23:08',1,'2022-04-20 06:23:08',NULL,NULL,'$2y$10$c9GF/EhEBDrKGHS6dyi2c.gtSeasyCWYR1YhxwJlWBdjowsRgQeji',NULL,0,NULL,NULL,NULL,0,NULL,'2022-04-20 06:23:08','2022-04-20 06:23:08'),
(492,'Koperasi Produsen Karya Al Mahmud Mubarok','admin@kopkalam.com','kopkalam',NULL,NULL,NULL,NULL,'2022-05-10 08:06:53',1,'2022-05-10 08:06:53',NULL,NULL,'$2y$10$qPt1TRcR3gtzhSsaKZCMPeHOqZEcwhLgTK0FOaqgjcqFQZc5MPhAC',NULL,0,NULL,NULL,NULL,0,NULL,'2022-05-10 08:06:53','2022-05-10 08:06:53'),
(493,'KPRI Kancawinaya Klari','asiemm66@gmail.com','KPRIkancawinayaklari',NULL,NULL,NULL,NULL,'2022-05-17 07:09:03',1,'2022-05-17 07:09:03',NULL,NULL,'$2y$10$t/QutdbjE2zkl7Kf330i/uDYDxfcmZx5pxUhJqXcYs0awXoZsrylO',NULL,0,NULL,NULL,NULL,0,NULL,'2022-05-17 07:09:03','2022-05-17 07:09:03'),
(494,'KOPERASI PRAJA SEJAHTERA','bontang@gmail.com','koperasipraja',NULL,NULL,NULL,NULL,'2022-05-30 03:43:46',1,'2022-05-30 03:43:46',NULL,NULL,'$2y$10$jAmFcD1USl9OXzl/AZkZmuXb3OJep32QABOOd7BcWY1kwIJ281GFa',NULL,0,NULL,NULL,NULL,0,NULL,'2022-05-30 03:43:46','2022-05-30 03:43:46'),
(495,'Koperasi Bina Warga','kop_kbw@yahoo.com','kopbinawarga',NULL,NULL,NULL,NULL,'2022-06-02 05:02:22',1,'2022-06-02 05:02:22',NULL,NULL,'$2y$10$nOPuQfYV7FRp/OA9oF1Qhu1sPPC8BO/291wT54ZMJX6twYl//qetW',NULL,0,NULL,NULL,NULL,0,NULL,'2022-06-02 05:02:23','2022-06-02 05:02:23'),
(496,'Koperasi Konsumen PT Rajawali Nusantara','kokarindo@rni.co.id','kokarindo',NULL,NULL,NULL,NULL,'2022-06-02 06:46:49',1,'2022-06-02 06:46:49',NULL,NULL,'$2y$10$jOi7JGWrzJG8BPkonfAJz.mbFW.xTE0YLVhY7a775IISs3FW3T3r2',NULL,0,NULL,NULL,NULL,0,NULL,'2022-06-02 06:46:49','2022-06-02 06:46:49'),
(497,'Victus','info@victus.id','victusrestaurant',NULL,NULL,NULL,NULL,'2022-06-07 04:38:23',1,'2022-06-07 04:38:23',NULL,NULL,'$2y$10$4YnNIW9WuRkDfzD.306.qupG.I61FDmOKNTWeoJ8maYycEaSwLej6',NULL,0,NULL,NULL,NULL,0,NULL,'2022-06-07 04:38:23','2022-06-07 04:38:23'),
(500,'SIDARAJA','sidaraja@4vm.com','SidarajaApp',NULL,NULL,NULL,NULL,'2022-06-07 09:29:18',1,'2022-06-07 09:29:18',NULL,NULL,'$2y$10$Cf1U4TODZ9vzRlXFWB.FKeRAD8TZzM3u98oeu0P//clEuxbcGxS2G',NULL,0,NULL,NULL,NULL,0,NULL,'2022-06-07 09:29:18','2022-06-07 09:56:25'),
(501,'Dinas Pendidikan Sorong','dinassorong@4vm.com','dinaspendidikansorong',NULL,NULL,NULL,NULL,'2022-06-09 05:02:49',1,'2022-06-09 05:02:49',NULL,NULL,'$2y$10$YJGTTBqBgR2a1iZETWRhAew7.APDWyJETpr3jbsZCLf6VY7Bkk/Iu',NULL,0,NULL,NULL,NULL,0,NULL,'2022-06-09 05:02:50','2022-06-09 05:02:50'),
(503,'Kemenparekraf','info@kemenparekraf.go.id','kemenparekraf',NULL,NULL,NULL,NULL,'2022-06-09 05:11:23',1,'2022-06-09 05:11:23',NULL,NULL,'$2y$10$EzzbLq.D8nZLOYey7O1II.RECqYaRnZ.IgbHO1sFWdWE.9se6ntPG',NULL,0,NULL,NULL,NULL,0,NULL,'2022-06-09 05:11:23','2022-06-09 05:11:23'),
(504,'PT. Mitra Karina Jaya Olin Bags','olin@mail.com','mitrakarinajaya',NULL,NULL,NULL,NULL,'2022-06-13 06:17:02',0,'2022-06-13 06:17:02',NULL,NULL,'$2y$10$ZEe8seyAPEtiXh0g6qYQWejdy/OBNvFLBiX3vBm9ftKRcEnXa3Zo.',NULL,0,NULL,NULL,NULL,0,NULL,'2022-06-13 06:17:02','2022-06-13 06:17:02'),
(508,'PT. PRIMA MULTIKARE INDONESIA','multikare@mail.com','multikare',NULL,NULL,NULL,NULL,'2022-06-13 07:34:59',0,'2022-06-13 07:34:59',NULL,NULL,'$2y$10$ZlImaHE7t/h5F7Q6zEeAm.a762TPKyevdd6d6f1CJVbLm.R8dQ7L.',NULL,0,NULL,NULL,NULL,0,NULL,'2022-06-13 07:34:59','2022-06-13 07:34:59'),
(509,'POLDA JAWA BARAT','polda@mail.com','polda',NULL,NULL,NULL,NULL,'2022-06-13 07:40:23',0,'2022-06-13 07:40:23',NULL,NULL,'$2y$10$DSKLiXbe4sLo.vaK9YD/iuSOZU1UpmtpEo4AdUSZJze6qJ94OPyQK',NULL,0,NULL,NULL,NULL,0,NULL,'2022-06-13 07:40:23','2022-06-13 07:40:23'),
(510,'PT. KAWAMURA INDAH','didi.s@kawamura-indah.co.id','kawamura',NULL,NULL,NULL,NULL,'2022-06-13 08:12:47',0,'2022-06-13 08:12:47',NULL,NULL,'$2y$10$A2jaAIw5BkV6SnguX.3BEenhGpjh3V6td0F51gFGh95kr1SASSHxG',NULL,0,NULL,NULL,NULL,0,NULL,'2022-06-13 08:12:47','2022-06-13 08:12:47'),
(511,'PT. MITRA SIGMA TEKINDO','sigma@mail.com','mitrasigma',NULL,NULL,NULL,NULL,'2022-06-13 08:47:26',0,'2022-06-13 08:47:26',NULL,NULL,'$2y$10$oAbYv7nZYssgLnAUIlDhaelRRa8yutI3n8r.BEOJt/NMazxmclZ0u',NULL,0,NULL,NULL,NULL,0,NULL,'2022-06-13 08:47:26','2022-06-13 08:47:26'),
(512,'PT. MITRA KARINA JAYA','olinbags@mail.com','mitrakarina@mail.com',NULL,NULL,NULL,NULL,'2022-06-13 08:57:18',0,'2022-06-13 08:57:19',NULL,NULL,'$2y$10$xt7x2FjPWwdT5IDcRrDUpOFa0KJTkHBUTgnfJCCOCGk9WwPqM/.aS',NULL,0,NULL,NULL,NULL,0,NULL,'2022-06-13 08:57:19','2022-06-13 08:57:19'),
(513,'KSU REZKY MANDIRI PRATAMA','RMP@4vm.com','KSURMP',NULL,NULL,NULL,NULL,'2022-06-13 09:25:34',1,'2022-06-13 09:25:34',NULL,NULL,'$2y$10$14WJBbHO.6/E/TK1DjBMFeLnmsFUhchOvKIvseIr4dGp3rtv0mWo6',NULL,0,NULL,NULL,NULL,0,NULL,'2022-06-13 09:25:34','2022-06-13 09:25:34'),
(514,'KOPERASI KONSUMEN PRAJA SEJAHTERA JAWA BARAT','prajajabar@4vm.com','kopprajajabar',NULL,NULL,NULL,NULL,'2022-06-13 09:28:58',1,'2022-06-13 09:28:58',NULL,NULL,'$2y$10$VM5qRDwvcCFABVrCWkgY7OtQw259dz3xW.K3BKHF3IYTvcEmhbdwy',NULL,0,NULL,NULL,NULL,0,NULL,'2022-06-13 09:28:58','2022-06-13 09:28:58'),
(515,'Primkopad Korem 102 Kartika Panju Panjung','primkopad59@gmail.com','primkopadkorem102',NULL,NULL,NULL,NULL,'2022-06-13 09:29:50',1,'2022-06-13 09:29:50',NULL,NULL,'$2y$10$hxRgewP6kuYrhX3E2JTqd.cej0.qWGw1cteBHnhqZDkh89Rq85I5u',NULL,0,NULL,NULL,NULL,0,NULL,'2022-06-13 09:29:50','2022-06-16 04:34:01'),
(516,'KOPERASI JASA BERSAMA WIKA REKON','koperasi@wikarekon.co.id','kopwikarekon',NULL,NULL,NULL,NULL,'2022-06-24 06:53:40',1,'2022-06-24 06:53:40',NULL,NULL,'$2y$10$uyBMbQSN1jmjFLR1CQMcReOnjWGyFr9nO2Xn7/M871/3eBLpRg7Fa',NULL,0,NULL,NULL,NULL,0,NULL,'2022-06-24 06:53:40','2022-06-24 06:53:40'),
(517,'Koperasi Miftahul Huda Asror','zainal.avin01@gmail.com','Miftahulhudaasror',NULL,NULL,NULL,NULL,'2022-07-04 04:04:14',1,'2022-07-04 04:04:14',NULL,NULL,'$2y$10$4lB9WoAUf9RCX/rNEAgKQOZeopg1Do1a05ElL6uUE825IhEy88Vuq',NULL,0,NULL,NULL,NULL,0,NULL,'2022-07-04 04:04:16','2022-07-04 04:04:16'),
(523,'PT. Integra Solusi Mandiri','marketing@integrasolution.id','ptism',NULL,NULL,NULL,NULL,'2022-07-07 09:16:33',1,'2023-05-26 07:08:59',NULL,NULL,'$2y$10$TPDhm/2KDVLI9m4qrej2xeDge.w1ygVchPtER1QqkfjVeWR4fAsF2',NULL,0,NULL,NULL,NULL,0,NULL,'2022-07-07 09:16:33','2023-05-26 07:08:59'),
(524,'manager','manager4vm@gmail.com','manager4vmdev',NULL,NULL,NULL,NULL,'2022-08-10 09:28:31',1,'2022-08-10 09:28:31',NULL,NULL,'$2y$10$YLVvJa0JyWH6QQtFxld8O.xV9j/rfee.tOVqS.C1j6IPEeDVgfA1O',NULL,0,NULL,NULL,NULL,0,NULL,'2022-08-10 09:28:31','2022-08-19 05:35:43'),
(525,'Sholeh Effendy','sholeh@4visionmedia.com','sholeh',NULL,NULL,NULL,NULL,'2022-08-10 10:25:15',1,'2022-08-10 10:25:15',NULL,NULL,'$2y$10$9llVs.Pa42Z05cTbqVx/1ufRUEutNvIWDOq9HGQ700dSuAndyOSru',NULL,0,NULL,NULL,NULL,0,NULL,'2022-08-10 10:25:15','2024-11-03 14:02:35'),
(526,'PT. Starcom Solusindo','sales@starcom.net','starcom',NULL,NULL,NULL,NULL,'2022-08-18 09:23:14',0,'2022-08-18 09:23:14',NULL,NULL,'$2y$10$u7bF4nv4mOgOdap0na6DyuTHmaSvCZthVQelqroTWhoTftXthkAfG',NULL,0,NULL,NULL,NULL,0,NULL,'2022-08-18 09:23:14','2022-08-18 09:23:14'),
(527,'RDN Copyrent','info@rdncopyrent.com','rdncopyrent',NULL,NULL,NULL,NULL,'2022-10-11 07:43:49',1,'2022-10-11 07:43:49',NULL,NULL,'$2y$10$xtCoAyiayvZBOPEol8R7C.nBVPFGV393giUfhebyOa2TwsUDTJvnK',NULL,0,NULL,NULL,NULL,0,NULL,'2022-10-11 07:43:49','2022-10-11 07:43:49'),
(528,'PT. Garuda Dumai','belumadaemail@mail.com','garudadumai',NULL,NULL,NULL,NULL,'2022-10-12 06:59:55',0,'2022-10-12 06:59:55',NULL,NULL,'$2y$10$65WZvR2jM6Vz6s.QwV9VFe2ECOlD7OTIOgjjXl0/WGmzshtpCkPhu',NULL,0,NULL,NULL,NULL,0,NULL,'2022-10-12 06:59:55','2022-10-12 06:59:55'),
(529,'KOPERASI FAKULTAS EKONOMI UNJ','feunj@email.com','kopfeunj',NULL,NULL,NULL,NULL,'2022-10-12 09:42:19',1,'2022-10-12 09:42:19',NULL,NULL,'$2y$10$J/gJaCJcBBUvTBVV7L6lmOZJDpmYuqysG53/J2ugiZIoM44teS58.',NULL,0,NULL,NULL,NULL,0,NULL,'2022-10-12 09:42:19','2022-10-12 09:42:19'),
(530,'STIESA Subang - Yayasan Sutaatmadja','info@stiesa.ac.id','stiesa',NULL,NULL,NULL,NULL,'2022-12-15 06:40:51',1,'2025-08-29 13:44:08',NULL,NULL,'$2y$10$vATu/YEX6vKh1rwEqK3rAeoaomLfXoEdWk/PMIktAjwENTdqhknT6',NULL,0,NULL,NULL,NULL,0,NULL,'2022-12-15 06:40:51','2025-08-29 13:44:08'),
(531,'Sekretariat CCM Indonesia','sekretariat.ccm@gmail.com','ccmindo',NULL,NULL,NULL,NULL,'2022-12-20 04:06:13',0,'2022-12-20 04:06:13',NULL,NULL,'$2y$10$nE/93N4MDOHZ4vjAwvDAwu/0AWo6oTUu2GP7reTypXRf7HfY8b1Ku',NULL,0,NULL,NULL,NULL,0,NULL,'2022-12-20 04:06:13','2022-12-20 04:06:13'),
(532,'TECTONA ART GALLERY','tectona@mail.com','tectona@mail.com',NULL,NULL,NULL,NULL,'2023-01-09 04:21:12',0,'2023-01-09 04:21:12',NULL,NULL,'$2y$10$lyzM7chUPwxxVshwCKMKC.nWijGLjnMqFJB9YySK/QufGhlILyE1S',NULL,0,NULL,NULL,NULL,0,NULL,'2023-01-09 04:21:12','2023-01-09 04:21:12'),
(533,'Bu Evita','timber@mail.com','timber@mail.com',NULL,NULL,NULL,NULL,'2023-01-09 08:09:13',0,'2023-01-09 08:09:13',NULL,NULL,'$2y$10$Y5QrZmyJi4tETX7GXUCsauWOPaoR0VyRNiJYKTFLssjE9KL03O8be',NULL,0,NULL,NULL,NULL,0,NULL,'2023-01-09 08:09:13','2023-01-09 08:09:13'),
(535,'Koperasi Amanah Abdi Nagari','amanahabdi@gmail.com','abdinagari',NULL,NULL,NULL,NULL,'2023-01-20 07:52:10',1,'2023-01-20 07:52:10',NULL,NULL,'$2y$10$Zmi8nBTZJ9Lt9RlBSWInRu5NHmM3hA5YYpSh/8AT2xkDYp2HeS5SK',NULL,0,NULL,NULL,NULL,0,NULL,'2023-01-20 07:52:10','2023-01-20 07:52:10'),
(536,'Koperasi Berkah Mandiri Bersama','berkahmandiri@gmail.com','berkahmandiri',NULL,NULL,NULL,NULL,'2023-01-20 08:01:45',1,'2023-01-20 08:01:45',NULL,NULL,'$2y$10$/7MIjLvM16EPt51T040ToObgI1Dyt/uyUDWpyJQOG.b.1P2yF./j6',NULL,0,NULL,NULL,NULL,0,NULL,'2023-01-20 08:01:45','2023-01-20 08:01:45'),
(537,'KOPERASI KAKAPO','kakapo@gmail.com','kakapo',NULL,NULL,NULL,NULL,'2023-01-20 08:48:30',1,'2023-01-20 08:48:30',NULL,NULL,'$2y$10$a/KCsHaAP/S26/SuEn.xbu6/QciuEHnfawNOs9QOi5uEyqiajTU7G',NULL,0,NULL,NULL,NULL,0,NULL,'2023-01-20 08:48:30','2023-01-20 08:48:30'),
(538,'Koperasi Wisata Belanja Nusantara','kwbnusantara@gmail.com','kwbnusantara',NULL,NULL,NULL,NULL,'2023-01-20 08:59:51',1,'2023-01-20 08:59:51',NULL,NULL,'$2y$10$s79ut7MMFfyJfjrlNYiH..qWXeR0qLBJdS8ZwdfYTUvxWLlLNwqi2',NULL,0,NULL,NULL,NULL,0,NULL,'2023-01-20 08:59:51','2023-01-20 08:59:51'),
(539,'CCTV Jaelani','jaelani@mail.com','cctvjaelani',NULL,NULL,NULL,NULL,'2023-04-03 04:17:47',1,'2023-04-03 04:17:47',NULL,NULL,'$2y$10$.YiU8eqd3vQRQh0vw6Yqv.7tjZqhDk/x0dAQ.xaDmIvJn7Edst3cW',NULL,0,NULL,NULL,NULL,0,NULL,'2023-04-03 04:17:47','2023-04-03 04:17:47'),
(540,'CCTV Sandy','cctvsandy@mail.com','cctvsandy',NULL,NULL,NULL,NULL,'2023-04-08 07:17:52',1,'2023-04-08 07:17:52',NULL,NULL,'$2y$10$pBzmRwIiVlnGczVBEdKh7OGHoQnwEw6HAGG2ZvCGXwGu4.b7SOkdS',NULL,0,NULL,NULL,NULL,0,NULL,'2023-04-08 07:17:52','2023-04-08 07:17:52'),
(541,'CCTV Aufa','aufacctv@mail.com','aufacctv',NULL,NULL,NULL,NULL,'2023-04-13 10:17:13',1,'2023-04-13 10:17:13',NULL,NULL,'$2y$10$k4HpMq0Lww3iEBOp22zuO.MJLkWPV.HiPdZQ04ltrKpI0kcp5Adnm',NULL,0,NULL,NULL,NULL,0,NULL,'2023-04-13 10:17:13','2023-04-13 10:17:25'),
(542,'CCTV Hasna','cctvhasna@mail.com','cctvhasna',NULL,NULL,NULL,NULL,'2023-04-19 11:12:47',1,'2023-04-19 11:12:47',NULL,NULL,'$2y$10$r1t1u3K0eEaLW23cCjX3L.L7BTUXuoktc3HW.m1v0fD1sVu8kYhAa',NULL,0,NULL,NULL,NULL,0,NULL,'2023-04-19 11:12:47','2023-04-19 11:12:47'),
(543,'CCTV Adi Widodo','cctvadi@mail.com','cctvadi',NULL,NULL,NULL,NULL,'2023-06-14 09:33:24',1,'2023-06-14 09:33:24',NULL,NULL,'$2y$10$5t2zFW..YrxRxZ57PTxni.IOtYqezFGIT1rlDy4GDyurrBJ63r.uK',NULL,0,NULL,NULL,NULL,0,NULL,'2023-06-14 09:33:24','2023-06-14 09:33:24'),
(544,'PT. DAA','daa@mail.com','daa@mail.com',NULL,NULL,NULL,NULL,'2023-07-14 06:54:53',1,'2023-07-14 06:54:53',NULL,NULL,'$2y$10$KE1JnLRk8f9j1KZAHa7lFO69.X4iPkpPOtkBYnh9Bfjk4EfGKk5Gu',NULL,0,NULL,NULL,NULL,0,NULL,'2023-07-14 06:54:53','2023-07-14 06:54:53'),
(545,'CCTV Sonia','cctvsonia@mail.com','cctvsonia',NULL,NULL,NULL,NULL,'2023-07-22 07:13:05',1,'2023-07-22 07:13:05',NULL,NULL,'$2y$10$9ElRFx4MoE6lTlQfYuIaQORYRuO96xGQ92MB6UOQOgzeuVU5PLD.e',NULL,0,NULL,NULL,NULL,0,NULL,'2023-07-22 07:13:05','2023-07-22 07:13:05'),
(546,'CCTV Sundagaiya','info@sundagaiya.com','cctvsundagaiya',NULL,NULL,NULL,NULL,'2023-08-15 02:56:17',1,'2023-08-15 02:56:17',NULL,NULL,'$2y$10$9Qj.vfGtZcX3kp25t2dRi.IeeN1WLPU3dDHUK4Q2QfPtwN1BWmAJC',NULL,0,NULL,NULL,NULL,0,NULL,'2023-08-15 02:56:17','2023-08-15 02:56:17'),
(551,'CCTV Dwiki / Pak Tutus','cctvdwiki@mail.com','cctvdwiki',NULL,NULL,NULL,NULL,'2023-08-28 03:15:36',1,'2023-08-28 03:15:36',NULL,NULL,'$2y$10$jrtZAhQ.kjRus8dy2E9uC.fncT0Yzr/h/WSwHD0ArYUTFNRiB.uNW',NULL,0,NULL,NULL,NULL,0,NULL,'2023-08-28 03:15:37','2023-08-28 03:15:37'),
(552,'CCTV Indah','cctvindah@mail.com','cctvindah',NULL,NULL,NULL,NULL,'2023-09-07 10:52:16',1,'2023-09-07 10:52:16',NULL,NULL,'$2y$10$eJu/HBg8K5wuE5wgGCjpXuxtRlasW4LQr1I7Rbmg1OQGD8PWmy4J2',NULL,0,NULL,NULL,NULL,0,NULL,'2023-09-07 10:52:16','2023-09-07 10:52:16'),
(555,'PT. PLN INDONESIA POWER','bayu.isnantoputro@plnindonesiapower.co.id','pln-ip',NULL,NULL,NULL,NULL,'2023-09-13 03:39:03',1,'2024-10-24 09:54:51',NULL,NULL,'$2y$10$nTlm6p4zf5dnHwPzUym84u5m1L73odm6ButGMo49Vd0hL212IyRhu',NULL,0,NULL,NULL,NULL,0,NULL,'2023-09-13 03:39:03','2024-10-24 09:54:51'),
(556,'PT. Inspiro','insprio@mail.com','inspiro',NULL,NULL,NULL,NULL,'2023-09-13 08:44:22',0,'2023-09-13 08:44:22',NULL,NULL,'$2y$10$lwnrM5IQsoKsobnYTTfO9OBf9ZgxG8g3fJvj9O7hnqOkGPkHJeeDa',NULL,0,NULL,NULL,NULL,0,NULL,'2023-09-13 08:44:22','2023-09-13 08:44:22'),
(557,'CCTV Aldi','cctvaldi@mail.com','cctvaldi',NULL,NULL,NULL,NULL,'2023-09-24 11:42:50',1,'2023-09-24 11:42:50',NULL,NULL,'$2y$10$hAd0QJScgVX0d2xDa5UxqeS.0JdMfIIAjXQQHx7iJTettP8wkm6rC',NULL,0,NULL,NULL,NULL,0,NULL,'2023-09-24 11:42:50','2023-09-24 11:42:50'),
(558,'CCTV Egi','cctvegi@mail.com','cctveg',NULL,NULL,NULL,NULL,'2023-10-18 06:40:29',1,'2023-10-18 06:40:29',NULL,NULL,'$2y$10$blW1KKaLqvLlJRis0rTpyu1YtWLIN.6Mmb92wTtIBzvQJLxQs.K8a',NULL,0,NULL,NULL,NULL,0,NULL,'2023-10-18 06:40:29','2023-10-18 06:40:29'),
(559,'CV Pembayun Sri Persada','finance@pembayunsripersada.com','cvpembayun',NULL,NULL,NULL,NULL,'2023-10-20 08:44:30',1,'2023-10-20 08:44:30',NULL,NULL,'$2y$10$2dv3RP/wpy137LfjVsOqReHppma7VhLHLgyxBPOLWd7ff0OfJR83.',NULL,0,NULL,NULL,NULL,0,NULL,'2023-10-20 08:44:30','2023-10-20 08:44:30'),
(560,'CV Mutiara','cvmutiara@mail.com','cvmutiara',NULL,NULL,NULL,NULL,'2023-10-31 03:54:39',1,'2023-10-31 03:54:39',NULL,NULL,'$2y$10$ZN1KZPY8GMXtDF8M14T/7u2jUU6wrNTggN/P7h6R9I7GtGFmf1Ije',NULL,0,NULL,NULL,NULL,0,NULL,'2023-10-31 03:54:39','2023-10-31 03:54:39'),
(561,'Koperasi Warga Mulya','kopwargamulya@mail.com','kopwargamulya',NULL,NULL,NULL,NULL,'2023-11-10 04:20:51',1,'2023-11-10 04:20:51',NULL,NULL,'$2y$10$Obms7Mtfczxtn3NKbnrNvOMS50Wmm/btjpkQvzDc.FiyDs/yieqw.',NULL,0,NULL,NULL,NULL,0,NULL,'2023-11-10 04:20:51','2023-11-10 04:20:51'),
(562,'PT. Arkamaya','arkayamaya@mail.com','arkamaya',NULL,NULL,NULL,NULL,'2023-11-17 03:31:59',1,'2023-11-17 03:31:59',NULL,NULL,'$2y$10$8T5j/WYZpOJGv9ryZo5IPO7t8rYzhR9pyBuO0khlLvWaHNJAbqZl.',NULL,0,NULL,NULL,NULL,0,NULL,'2023-11-17 03:31:59','2023-11-17 03:31:59'),
(563,'CCTV Falwaseda','cctvfalwa@mail.com','cctvfalwa',NULL,NULL,NULL,NULL,'2023-11-21 07:17:15',1,'2023-11-21 07:17:15',NULL,NULL,'$2y$10$11VBL8Q5iNNNnZPQ.SgL.uN6XhcTbvNwO0lo6tJSVQn1wQ13OBz..',NULL,0,NULL,NULL,NULL,0,NULL,'2023-11-21 07:17:15','2023-11-21 07:17:15'),
(565,'Arean Group CV','arean@mail.com','arean',NULL,NULL,NULL,NULL,'2023-11-23 09:32:33',0,'2023-11-23 09:32:33',NULL,NULL,'$2y$10$AeWxiSMRUtwvMQZS1uQcB.nlUmELgRW6G334IL2kad7IbdWbRXnja',NULL,0,NULL,NULL,NULL,0,NULL,'2023-11-23 09:32:33','2023-11-23 09:32:33'),
(566,'CCTV Surya Tama Medika','cctvsuryatama@mail.com','cctvsuryatama',NULL,NULL,NULL,NULL,'2023-12-11 22:09:43',1,'2023-12-11 22:09:43',NULL,NULL,'$2y$10$fOVEgDxcJcOAax8OJQAxJ.XxrGJjgi0FfyZRU/ALBWXr.tKUGK/e.',NULL,0,NULL,NULL,NULL,0,NULL,'2023-12-11 22:09:43','2023-12-11 22:09:43'),
(567,'CCTV Azis','cctvazis@gmail.com','cctvazis',NULL,NULL,NULL,NULL,'2024-01-30 14:29:37',1,'2024-01-30 14:29:37',NULL,NULL,'$2y$10$/NR9TXh4eH/ukJ.ItWxPBe/KQgXwwK5K9H8KPZxt4eb2AnkCtAAMS',NULL,0,NULL,NULL,NULL,0,NULL,'2024-01-30 14:29:37','2024-01-30 14:29:37'),
(568,'CCTV Tete Arjasari','cctvtete@mail.com','cctvtete',NULL,NULL,NULL,NULL,'2024-02-01 10:09:17',1,'2024-02-01 10:09:17',NULL,NULL,'$2y$10$IIw2RFXNEkIMscOfO5bCUOSHdnoO5/XVkCDNVGOLH8mUAVD4bTojm',NULL,0,NULL,NULL,NULL,0,NULL,'2024-02-01 10:09:17','2024-02-01 10:09:17'),
(569,'Koperasi Pegawai Pemerintah Provinsi (KPPP) Jawa Barat','kppp.jabar@gmail.com','kpppjabar',NULL,NULL,NULL,NULL,'2024-02-07 07:47:24',1,'2024-02-07 07:47:24',NULL,NULL,'$2y$10$6.3h9KN9Kicfkc5j4g9PnerKqkiVZ3u2hMI5MJQ1DDU78wgYMohMO',NULL,0,NULL,NULL,NULL,0,NULL,'2024-02-07 07:47:24','2024-02-07 07:47:24'),
(570,'Koperasi Konsumen Sigap Nusantara KSN','info@kopsigapnusantara.com','kopsigap',NULL,NULL,NULL,NULL,'2024-02-07 07:51:52',1,'2024-02-07 07:51:52',NULL,NULL,'$2y$10$NvxJar3fX.4jxsL9kAbWZOxD7E9kNru77KzSK.D7n/yhaT9mTw/gW',NULL,0,NULL,NULL,NULL,0,NULL,'2024-02-07 07:51:52','2024-02-07 07:51:52'),
(571,'Koperasi Solusi Taka','solusitaka@mail.com','kopsolusitaka',NULL,NULL,NULL,NULL,'2024-02-07 08:04:04',1,'2024-02-07 08:04:04',NULL,NULL,'$2y$10$JgkstbB.VJ81tWYZdW3DxudIfgcd5wJVI3J6oIuXCAWmrtbX2OH6C',NULL,0,NULL,NULL,NULL,0,NULL,'2024-02-07 08:04:04','2024-02-07 08:04:04'),
(572,'KOPKAR PYID','nursatria.a@id.yusen-logistics.com','kopkarpyid',NULL,NULL,NULL,NULL,'2024-02-07 08:08:13',1,'2024-02-07 08:08:13',NULL,NULL,'$2y$10$Hep2J35QtHhmmmUKIGsW/.OWd/A0yw4kJMlmy8eluVh6PH0L1.dnG',NULL,0,NULL,NULL,NULL,0,NULL,'2024-02-07 08:08:13','2024-02-07 08:08:13'),
(573,'PRIMKOPTI INDRAMAYU','hmochzainhasan@gmail.com','primkoptiindramayu',NULL,NULL,NULL,NULL,'2024-02-07 08:12:35',1,'2024-02-07 08:12:35',NULL,NULL,'$2y$10$y4PwglYlkhbu2ZkyvJgx.uq4JJze43o0T0vzVHY3JZDj6j8ypTl6m',NULL,0,NULL,NULL,NULL,0,NULL,'2024-02-07 08:12:35','2024-02-07 08:12:35'),
(574,'CCTV Wisnu','cctvwisnu@mail.com','cctvwisnu',NULL,NULL,NULL,NULL,'2024-02-10 01:53:30',1,'2024-02-10 01:53:30',NULL,NULL,'$2y$10$9DdDym2ZkIc7p7Z/TnHS.uPUGAyHe8wCjN1PQi5xSo4t1wACOx33S',NULL,0,NULL,NULL,NULL,0,NULL,'2024-02-10 01:53:30','2024-02-10 01:53:30'),
(577,'CITCOM HARMONI BERSAMA','support@citcom.id','citcombdg',NULL,NULL,NULL,NULL,'2024-04-25 08:55:37',1,'2024-04-25 08:55:37',NULL,NULL,'$2y$10$hnga/uMRWaAaiPGNPcH40OvoritWCgOIe4Npv8O9Sj0XRcuItXI4q',NULL,0,NULL,NULL,NULL,0,NULL,'2024-04-25 08:55:37','2024-04-29 10:03:48'),
(578,'Koperasi Usaha Mandiri','koperasiusahamandiri@mail.com','kopusahamandiri',NULL,NULL,NULL,NULL,'2024-10-11 03:38:57',1,'2024-10-11 03:38:57',NULL,NULL,'$2y$10$fuB7mK.BdtqVv1LDvgV8L.XdH7HdYuDKpOPo2h.TEJAcsj8cnx0Ly',NULL,0,NULL,NULL,NULL,0,NULL,'2024-10-11 03:38:57','2024-10-11 03:38:57'),
(579,'BBPJN (Balai Besar Pelaksanaan Jalan Nasional) DKI Jakarta - Jawa Barat','bbpjn@mail.com','bbpjn',NULL,NULL,NULL,NULL,'2024-11-22 12:02:24',0,'2024-11-22 12:02:24',NULL,NULL,'$2y$10$bjBQf31idX28fv1ihGFsj.hx4a8FY/LkRnWIbGbJ2/QREMM86bGv6',NULL,0,NULL,NULL,NULL,0,NULL,'2024-11-22 12:02:24','2024-11-22 12:02:24'),
(580,'PT Haguro Inspira Mandiri','haguro@mail.com','haguroinspiramandiri',NULL,NULL,NULL,NULL,'2024-11-29 13:26:41',1,'2024-11-29 13:26:41',NULL,NULL,'$2y$10$50MMAbKyv.7WNAOP2itemeUecLZWLuTNuqGLMvmoYlPquY9wBELpy',NULL,0,NULL,NULL,NULL,0,NULL,'2024-11-29 13:26:41','2024-11-29 13:26:41'),
(581,'PT Badak Mas Makmur Sejahtera','badakmas184@gmail.com','badakmas',NULL,NULL,NULL,NULL,'2024-12-02 04:00:31',0,'2024-12-02 04:00:31',NULL,NULL,'$2y$10$vknfnVMiVkSFHJeUzFL7b.uQ7kpI8FRETbdOd3xxT2pYBiHOvHeKW',NULL,0,NULL,NULL,NULL,0,NULL,'2024-12-02 04:00:31','2024-12-02 04:00:31'),
(582,'CCTV Fiona','cctvfiona@mail.com','cctvfiona',NULL,NULL,NULL,NULL,'2025-01-09 06:50:12',1,'2025-01-09 06:50:12',NULL,NULL,'$2y$10$MKBNchMYxzcw0F3og2q1HuzcNWBrCMtLmYk69S4nf9bc1fquUEmxe',NULL,0,NULL,NULL,NULL,0,NULL,'2025-01-09 06:50:12','2025-01-09 06:50:12'),
(583,'PT RAMORA ERA MANDIRI','rem@mail.com','ptrem',NULL,NULL,NULL,NULL,'2025-01-16 05:26:22',1,'2025-01-16 05:26:22',NULL,NULL,'$2y$10$19I0XRsSaGBdJiVWGImLuesunBdztUBldb81qh0WVNZXMk76wWI0i',NULL,0,NULL,NULL,NULL,0,NULL,'2025-01-16 05:26:22','2025-01-21 11:16:49'),
(584,'PT Joob Global Indonesia','imaduddin@forjl.com','joobid',NULL,NULL,NULL,NULL,'2025-01-17 08:03:53',1,'2025-01-17 08:03:53',NULL,NULL,'$2y$10$Xqxkeo.1LM3Rz1DUPJLDjuFd.t7Iy90hNXbnhSg1Q7ET7uZvtZ9Dq',NULL,0,NULL,NULL,NULL,0,NULL,'2025-01-17 08:03:53','2025-01-17 08:03:53'),
(585,'Ranto Nelwan T','mail@mail.com','ranto',NULL,NULL,NULL,NULL,'2025-01-22 08:59:34',0,'2025-01-22 08:59:34',NULL,NULL,'$2y$10$FLkNXlRdKGNexyKhm1j7kOvc3cBGzv2Hn8zyAi3zBqVFb3xuHxdHa',NULL,0,NULL,NULL,NULL,0,NULL,'2025-01-22 08:59:34','2025-01-22 08:59:34'),
(586,'Maruzome Koi','maruzome@mail.com','maruzome',NULL,NULL,NULL,NULL,'2025-02-13 07:44:00',1,'2025-02-13 07:44:00',NULL,NULL,'$2y$10$mfCcp9AB1BY9HZ6AOxvR..bqaPlncBL4LOF16ZT4RyHwhBnBzQg06',NULL,0,NULL,NULL,NULL,0,NULL,'2025-02-13 07:44:00','2025-02-13 07:44:00'),
(587,'Tunas Toyota','tunas@mail.com','tunastoyota',NULL,NULL,NULL,NULL,'2025-04-10 07:16:36',0,'2025-04-10 07:16:36',NULL,NULL,'$2y$10$lfN8pRkuB4caoa/iSzEM..Nse0o9jVdjUCqskpEFJL70wti9gSVfK',NULL,0,NULL,NULL,NULL,0,NULL,'2025-04-10 07:16:36','2025-04-10 07:16:36'),
(588,'PT Nadika Listrik Mandiri','nadika@mail.com','nadika',NULL,NULL,NULL,NULL,'2025-04-11 09:27:21',0,'2025-04-11 09:27:21',NULL,NULL,'$2y$10$Js2DgvGlj67mV1Yo12ja9OPt1V0oYBA6r6xEgeRyZe4RmJfKvHZDq',NULL,0,NULL,NULL,NULL,0,NULL,'2025-04-11 09:27:21','2025-04-11 09:27:21'),
(589,'CCTV Ayu','cctvayucijerah@mail.com','cctvayucijerah',NULL,NULL,NULL,NULL,'2025-04-11 10:46:21',1,'2025-04-11 10:46:21',NULL,NULL,'$2y$10$9oCRuz8VxQ9FJJYpjCP/aOZd4jhGVXh2jXJZWbsM3lKPquinxKrYS',NULL,0,NULL,NULL,NULL,0,NULL,'2025-04-11 10:46:21','2025-04-11 10:46:21'),
(590,'Web Forum','forum@mail.com','forum',NULL,NULL,NULL,NULL,'2025-05-06 09:36:23',0,'2025-05-06 09:36:23',NULL,NULL,'$2y$10$A6/yYuaWZ.8O7yAUiYxNkeW8MGudXZDKSfQINEQ00mw00iDHmYNf6',NULL,0,NULL,NULL,NULL,0,NULL,'2025-05-06 09:36:23','2025-05-06 09:36:23'),
(592,'Al Multazam','cctvalmultazam@mail.com','cctvalmultazam',NULL,NULL,NULL,NULL,'2025-06-03 09:38:30',1,'2025-06-03 09:38:30',NULL,NULL,'$2y$10$ZQ2CYVhLrFiOvFe3xNbc7.MacxMiXCgzmOdc/4T8rxcPOAzPmABEy',NULL,0,NULL,NULL,NULL,0,NULL,'2025-06-03 09:38:30','2025-06-03 09:38:30'),
(593,'Widodo Setiadarmaji','mail@mail2.com','widodo',NULL,NULL,NULL,NULL,'2025-06-24 09:17:21',0,'2025-06-24 09:17:21',NULL,NULL,'$2y$10$2JXBllmkWLz4T3p67ntY9eTgkuw1awNkCqgOHEijvcljYarg9.z8K',NULL,0,NULL,NULL,NULL,0,NULL,'2025-06-24 09:17:21','2025-06-24 09:17:21'),
(594,'BUMDES Bina Sejahtera','bumdesbisa4@gmail.com','bumdesbinasejahtera',NULL,NULL,NULL,NULL,'2025-09-22 11:29:58',1,'2025-09-22 11:29:58',NULL,NULL,'$2y$10$ob/sVR34ilnbI8p2EPgIIu5FQW3a0Ro3l3zxCnlvOW1rfpurnYx1y',NULL,0,NULL,NULL,NULL,0,NULL,'2025-09-22 11:29:58','2025-09-22 11:29:58'),
(595,'CCTV Aji (PT. STM)','cctvaji@mail.com','cctvaji',NULL,NULL,NULL,NULL,'2025-10-13 07:00:16',1,'2025-10-13 07:00:16',NULL,NULL,'$2y$10$elsheU.JL6NGV/3Pz4reH.cLKn4XL7gCWlNA2uJBfLmmrO0kXGJhu',NULL,0,NULL,NULL,NULL,0,NULL,'2025-10-13 07:00:16','2025-10-13 07:00:16'),
(596,'Adods Corp','theadods@gmail.com','adods',NULL,NULL,NULL,NULL,NULL,0,'2025-12-25 03:50:39',1,NULL,'$2y$12$gabfu0qfLQwL8v6pqSGC1O/sZDOfzDLV5lfLS7hktCnfdutuLYu4W',NULL,0,NULL,NULL,NULL,0,NULL,'2025-12-25 02:10:13','2025-12-25 03:50:39');
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

-- Dump completed on 2025-12-29 11:34:02

-- MySQL dump 10.13  Distrib 5.6.28, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: universo
-- ------------------------------------------------------
-- Server version	5.6.28-0ubuntu0.15.04.1

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
-- Table structure for table `ads`
--

DROP TABLE IF EXISTS `ads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ads` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `code` text,
  `status` varchar(255) DEFAULT NULL,
  `order` int(10) DEFAULT NULL,
  `zone_id` int(10) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ads`
--

LOCK TABLES `ads` WRITE;
/*!40000 ALTER TABLE `ads` DISABLE KEYS */;
/*!40000 ALTER TABLE `ads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `analytics`
--

DROP TABLE IF EXISTS `analytics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `analytics` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `visitors` int(10) DEFAULT NULL,
  `pageviews` int(10) DEFAULT NULL,
  `site_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=250 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `analytics`
--

LOCK TABLES `analytics` WRITE;
/*!40000 ALTER TABLE `analytics` DISABLE KEYS */;
/*!40000 ALTER TABLE `analytics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` int(10) DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  `site_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=814 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories_translations`
--

DROP TABLE IF EXISTS `categories_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories_translations` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `category_id` int(10) DEFAULT NULL,
  `language_id` int(10) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `permalink` varchar(255) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5642 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories_translations`
--

LOCK TABLES `categories_translations` WRITE;
/*!40000 ALTER TABLE `categories_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `channels`
--

DROP TABLE IF EXISTS `channels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `channels` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `url` text,
  `file` varchar(255) DEFAULT NULL,
  `permalink` varchar(255) DEFAULT NULL,
  `mapping_class` varchar(255) DEFAULT NULL,
  `embed` int(1) DEFAULT NULL,
  `nvideos` int(10) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `is_compressed` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `channels`
--

LOCK TABLES `channels` WRITE;
/*!40000 ALTER TABLE `channels` DISABLE KEYS */;
INSERT INTO `channels` VALUES (1,'PornHub','http://www.pornhub.com/pornhub.com-db.zip','pornhub.com-db.csv','pornhub','\\App\\Feeds\\PornHubFeed',1,2209660,'pornhub.png',1),(2,'YouPorn','http://www.youporn.com/YouPorn-Embed-Videos-Dump.zip','YouPorn-Embed-Videos-Dump.csv','youporn','\\App\\Feeds\\YouPornFeed',1,585614,'youporn.png',1),(3,'xHamster','http://partners.xhamster.com/2export.php?ch=!&cnt=3&tcnt=5&rt=3&url=on&dlm=%7C&tl=on&vid=on&ttl=on&chs=on&sz=on','xhamster.dump','xhamster','\\App\\Feeds\\xHamsterFeed',0,5001,'xhamster.png',0),(4,'Tube8','http://www.tube8.com/api.php?action=webMaster&orientation=straight&categories=6,13,12,7,4,11,5,22,1,14,8,2,10,3&count=100&size=small&rating=80000&delimiter=%7C&fields=url,categories,rating,title,tags,duration,pornstars,thumbnail&period=all&order=tr&format=CSV&utm_source=paid&utm_medium=hubtraffic&utm_campaign=hubtraffic_assassinsporn','tube8.dump','tube8','\\App\\Feeds\\Tube8Feed',0,100,'tube8.png',0);
/*!40000 ALTER TABLE `channels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8_unicode_ci NOT NULL,
  `queue` text COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_reserved_reserved_at_index` (`queue`,`reserved`,`reserved_at`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `languages` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `code` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `languages`
--

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
INSERT INTO `languages` VALUES (1,'Spanish',1,'es'),(2,'English',1,'en'),(3,'Italian',1,'it'),(4,'German',1,'de'),(5,'French',1,'fr'),(6,'Brazilian',1,'br');
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES ('2014_10_12_000000_create_users_table',1),('2014_10_12_100000_create_password_resets_table',2),('2016_05_30_222208_create_jobs_table',2),('2016_05_30_224838_create_failed_jobs_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scene_category`
--

DROP TABLE IF EXISTS `scene_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scene_category` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `category_id` int(10) DEFAULT NULL,
  `scene_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43183 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scene_category`
--

LOCK TABLES `scene_category` WRITE;
/*!40000 ALTER TABLE `scene_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `scene_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scene_tag`
--

DROP TABLE IF EXISTS `scene_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scene_tag` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tag_id` int(10) DEFAULT NULL,
  `scene_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_st_1` (`tag_id`),
  KEY `fk_st_2` (`scene_id`),
  CONSTRAINT `fk_st_1` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_st_2` FOREIGN KEY (`scene_id`) REFERENCES `scenes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=145738 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scene_tag`
--

LOCK TABLES `scene_tag` WRITE;
/*!40000 ALTER TABLE `scene_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `scene_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scene_translations`
--

DROP TABLE IF EXISTS `scene_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scene_translations` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `scene_id` int(10) DEFAULT NULL,
  `language_id` int(10) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `permalink` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_str_1` (`scene_id`),
  KEY `fk_str_2` (`language_id`),
  KEY `fk_tr_3` (`title`),
  CONSTRAINT `fk_str_1` FOREIGN KEY (`scene_id`) REFERENCES `scenes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_str_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=108963 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scene_translations`
--

LOCK TABLES `scene_translations` WRITE;
/*!40000 ALTER TABLE `scene_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `scene_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scenes`
--

DROP TABLE IF EXISTS `scenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scenes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `preview` varchar(255) DEFAULT NULL,
  `thumbs` text,
  `thumb_index` int(10) DEFAULT NULL,
  `iframe` text,
  `status` int(1) DEFAULT NULL,
  `duration` int(10) DEFAULT NULL,
  `rate` float(10,2) DEFAULT NULL,
  `views` int(10) DEFAULT NULL,
  `channel_id` int(10) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `site_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33836 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scenes`
--

LOCK TABLES `scenes` WRITE;
/*!40000 ALTER TABLE `scenes` DISABLE KEYS */;
/*!40000 ALTER TABLE `scenes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scenes_clicks`
--

DROP TABLE IF EXISTS `scenes_clicks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scenes_clicks` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `scene_id` int(10) DEFAULT NULL,
  `referer` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_scli_1` (`scene_id`),
  CONSTRAINT `fk_scli_1` FOREIGN KEY (`scene_id`) REFERENCES `scenes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=180 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scenes_clicks`
--

LOCK TABLES `scenes_clicks` WRITE;
/*!40000 ALTER TABLE `scenes_clicks` DISABLE KEYS */;
/*!40000 ALTER TABLE `scenes_clicks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sentences`
--

DROP TABLE IF EXISTS `sentences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sentences` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sentence` text,
  `url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5267 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sentences`
--

LOCK TABLES `sentences` WRITE;
/*!40000 ALTER TABLE `sentences` DISABLE KEYS */;
/*!40000 ALTER TABLE `sentences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_category`
--

DROP TABLE IF EXISTS `site_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_category` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `site_id` int(10) DEFAULT NULL,
  `category_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_category`
--

LOCK TABLES `site_category` WRITE;
/*!40000 ALTER TABLE `site_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `site_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_tag`
--

DROP TABLE IF EXISTS `site_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_tag` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `site_id` int(10) DEFAULT NULL,
  `tag_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_lt_1` (`site_id`),
  KEY `fk_lt2` (`tag_id`),
  CONSTRAINT `fk_lt2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_lt_1` FOREIGN KEY (`site_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_tag`
--

LOCK TABLES `site_tag` WRITE;
/*!40000 ALTER TABLE `site_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `site_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sites`
--

DROP TABLE IF EXISTS `sites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sites` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `language_id` varchar(255) DEFAULT NULL,
  `domain` varchar(255) DEFAULT NULL,
  `ga_account` varchar(255) DEFAULT NULL,
  `iframe_site_id` int(10) DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  `google_analytics` varchar(255) DEFAULT NULL,
  `head_billboard` varchar(255) DEFAULT NULL,
  `title_index` varchar(255) DEFAULT NULL,
  `title_category` varchar(255) DEFAULT NULL,
  `title_tag` varchar(255) DEFAULT NULL,
  `title_topscenes` varchar(255) DEFAULT NULL,
  `description_index` varchar(255) DEFAULT NULL,
  `description_category` varchar(255) DEFAULT NULL,
  `description_tag` varchar(255) DEFAULT NULL,
  `description_topscenes` varchar(255) DEFAULT NULL,
  `have_domain` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sites`
--

LOCK TABLES `sites` WRITE;
/*!40000 ALTER TABLE `sites` DISABLE KEYS */;
/*!40000 ALTER TABLE `sites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tag_translations`
--

DROP TABLE IF EXISTS `tag_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tag_translations` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tag_id` int(10) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `permalink` varchar(255) DEFAULT NULL,
  `language_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tt_1` (`tag_id`),
  KEY `fk_tt_2` (`language_id`),
  KEY `idx_name` (`name`),
  CONSTRAINT `fk_tt_1` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_tt_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=54358 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tag_translations`
--

LOCK TABLES `tag_translations` WRITE;
/*!40000 ALTER TABLE `tag_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `tag_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` int(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `site_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11159 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags_clicks`
--

DROP TABLE IF EXISTS `tags_clicks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags_clicks` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tag_id` int(10) DEFAULT NULL,
  `referer` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tags_clicks_1` (`tag_id`),
  CONSTRAINT `fk_sclicks_1` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags_clicks`
--

LOCK TABLES `tags_clicks` WRITE;
/*!40000 ALTER TABLE `tags_clicks` DISABLE KEYS */;
/*!40000 ALTER TABLE `tags_clicks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `titles`
--

DROP TABLE IF EXISTS `titles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `titles` (
  `id` int(19) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `language_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1127 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `titles`
--

LOCK TABLES `titles` WRITE;
/*!40000 ALTER TABLE `titles` DISABLE KEYS */;
/*!40000 ALTER TABLE `titles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tweets`
--

DROP TABLE IF EXISTS `tweets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tweets` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `description` varchar(140) DEFAULT NULL,
  `scene_id` int(10) DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tweets`
--

LOCK TABLES `tweets` WRITE;
/*!40000 ALTER TABLE `tweets` DISABLE KEYS */;
/*!40000 ALTER TABLE `tweets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (3,'eduardo','eduardo.rzeronte@gmail.com','$2y$10$SAw7tXWkVsEJZ4aaVDyzYOIBqgtqg/dq2fobc5iEh9UZR1ic/1j5.','eiO7dnQgM2aDTxmxYnq1sAYZebEewKehFfuzYLft5X765JWXzlFzuRJp0mFp','2016-05-30 20:24:22','2016-06-04 23:45:38');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `words`
--

DROP TABLE IF EXISTS `words`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `words` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `word` varchar(255) DEFAULT NULL,
  `language_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6160 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `words`
--

LOCK TABLES `words` WRITE;
/*!40000 ALTER TABLE `words` DISABLE KEYS */;
/*!40000 ALTER TABLE `words` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `words_synonym`
--

DROP TABLE IF EXISTS `words_synonym`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `words_synonym` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `word_id` int(10) DEFAULT NULL,
  `word` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25415 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `words_synonym`
--

LOCK TABLES `words_synonym` WRITE;
/*!40000 ALTER TABLE `words_synonym` DISABLE KEYS */;
/*!40000 ALTER TABLE `words_synonym` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zones`
--

DROP TABLE IF EXISTS `zones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zones` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zones`
--

LOCK TABLES `zones` WRITE;
/*!40000 ALTER TABLE `zones` DISABLE KEYS */;
INSERT INTO `zones` VALUES (1,'Home',NULL,'home'),(2,'Search',NULL,'search'),(3,'Video',NULL,'video');
/*!40000 ALTER TABLE `zones` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-06-05 18:33:56

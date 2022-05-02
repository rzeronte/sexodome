-- MySQL dump 10.13  Distrib 5.7.16, for Linux (x86_64)
--
-- Host: localhost    Database: universo
-- ------------------------------------------------------
-- Server version	5.7.16-0ubuntu0.16.04.1

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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `analytics`
--

LOCK TABLES `analytics` WRITE;
/*!40000 ALTER TABLE `analytics` DISABLE KEYS */;
INSERT INTO `analytics` VALUES (16,'2016-08-13',0,0,47),(17,'2016-08-14',2,5,47),(18,'2016-08-15',8,28,47);
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
  `nscenes` int(10) DEFAULT NULL,
  `cache_order` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_categori_site_id` (`site_id`),
  CONSTRAINT `cat_fk_site_id` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_categori_site_id` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10684 DEFAULT CHARSET=latin1;
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
  PRIMARY KEY (`id`),
  KEY `ct_fk_cat` (`category_id`),
  CONSTRAINT `ct_fk_cat` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=56794 DEFAULT CHARSET=latin1;
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
  `url_deleted` varchar(500) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `permalink` varchar(255) DEFAULT NULL,
  `mapping_class` varchar(255) DEFAULT NULL,
  `embed` int(1) DEFAULT NULL,
  `nvideos` int(10) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `is_compressed` int(1) DEFAULT NULL,
  `compressed_filename` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `channels`
--

LOCK TABLES `channels` WRITE;
/*!40000 ALTER TABLE `channels` DISABLE KEYS */;
INSERT INTO `channels` VALUES (1,'PornHub','http://www.pornhub.com/webmasters/dump_output?keywords=&categories=104&count=10000&size=small&rating=All&delimeter=%7C&fields=embed,url,categories,rating,username,title,tags,duration,pornstars,thumbnail,flipbook&period=all_time&order=mr&format=csv&utm_source=paid&utm_medium=hubtraffic&utm_campaign=hubtraffic_assassinsporn','http://www.pornhub.com/deleted.csv','pornhub.dump','pornhub','\\App\\Feeds\\PornHubFeed',0,667,'pornhub.png',0,NULL),(2,'YouPorn','http://www.youporn.com/videodump/output/?keywords=&orientation=all&categories=63,1,2,3,4,6,7,5,51,9,52,10,11,12,13,37,15,16,44,8,48,17,42,18,62,19,20,58,50,21,65,46,22,23,66,25,41,40,49,26,29,64,55,28,36,56,57,30,53,43,61,54,31,27,60,39,47,59,32,38,33,34,35,45&limit=100&thumbnailsize=160x120&rating=All&delimiter=%7C&fields=embed,url,categories,rating,user_name,title,tags,duration,pornstars,main_thumbnail,flipbook&period=alltime&orderby=rating&exportformat=CSV&utm_source=paid&utm_medium=hubtraffic&utm_campaign=hubtraffic_assassinsporn','http://www.youporn.com/deleted.csv','youporn.dump','youporn','\\App\\Feeds\\YouPornFeed',0,101,'youporn.png',0,NULL),(3,'xHamster','http://partners.xhamster.com/2export.php?ch=!&cnt=3&tcnt=5&rt=3&url=on&dlm=%7C&tl=on&vid=on&ttl=on&chs=on&sz=on',NULL,'xhamster.dump','xhamster','\\App\\Feeds\\xHamsterFeed',0,5001,'xhamster.png',0,NULL),(4,'Tube8','http://www.tube8.com/api.php?action=webMaster&orientation=all&count=100&size=small&rating=0&delimiter=%7C&fields=url,categories,rating,username,title,tags,duration,pornstars,thumbnail,flipbook&period=all&order=lt&format=CSV&utm_source=paid&utm_medium=hubtraffic&utm_campaign=hubtraffic_assassinsporn','http://tube8.com/deleted.csv','tube8.dump','tube8','\\App\\Feeds\\Tube8Feed',0,100,'tube8.png',0,NULL),(5,'Redtube','http://www.redtube.com/_status/export.php?search=&categories=1.2.3.4.5.6.7.8.9.11.12.13.14.15.16.17.18.19.20.21.22.23.24.25.26.27.28.29.30.31.32.33.34.35&limit=10000&thumb_size=s&min_rating=2&delimiter=%7C&embed=&include_url=1&include_categories=1&include_duration=1&include_video_id=1&include_title=1&include_tags=1&include_added=1&thumbs_in_one_row=1&added_before_x_days=0&thumbs=5&order_by=time&export=csv&do=export&utm_source=paid&utm_medium=hubtraffic&utm_campaign=hubtraffic_assassinsporn','http://widgets.redtube.com/_affiliate/saved/download/export_offline_url.csv','redtube.dump','redtube','\\App\\Feeds\\RedtubeFeed',0,10001,'redtube.png',0,NULL),(6,'HDZog','http://direct.hdzog.com/dump/hdzog_video.csv',NULL,'hdzog.dump','hdzog','\\App\\Feeds\\HDZogFeed',0,75149,'hdzog.png',0,NULL),(7,'gaytube','http://www.gaytube.com/api/webmasters/search/?search=&category=&count=5000&thumbsize=all&period=alltime&ordering=rating&utm_source=paid&utm_medium=hubtraffic&utm_campaign=hubtraffic_assassinsporn',NULL,'gaytube.dump','gaytube','\\App\\Feeds\\gaytubeFeed',0,5000,'gaytube.png',0,NULL),(8,'Spankwire','http://www.spankwire.com/api/HubTrafficApiCall?data=searchVideos&status=active&search=&count=100&thumbsize=all&period=Year&ordering=tr&output=json&utm_source=paid&utm_medium=hubtraffic&utm_campaign=hubtraffic_assassinsporn',NULL,'spankwire.dump','spankwire','\\App\\Feeds\\SpankwireFeed',0,100,'spankwire.png',0,NULL),(9,'TheGay','http://direct.thegay.com/dump/thegay_video.csv',NULL,'thegay.dump','thegay','\\App\\Feeds\\TheGayFeed',0,97707,'thegay.png',0,NULL),(10,'HClips','http://direct.hclips.com/dump/privatehomeclips_video.csv',NULL,'hclips.dump','hclips','\\App\\Feeds\\HClipsFeed',1,1,'hclips.png',0,NULL);
/*!40000 ALTER TABLE `channels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cronjobs`
--

DROP TABLE IF EXISTS `cronjobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cronjobs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `site_id` int(10) NOT NULL,
  `channel_id` int(10) DEFAULT NULL,
  `params` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cronjobs`
--

LOCK TABLES `cronjobs` WRITE;
/*!40000 ALTER TABLE `cronjobs` DISABLE KEYS */;
INSERT INTO `cronjobs` VALUES (11,47,1,'{\"feed_name\":\"PornHub\",\"site_id\":\"47\",\"max\":\"1\",\"duration\":\"60\",\"tags\":\"false\"}'),(12,47,1,'{\"feed_name\":\"PornHub\",\"site_id\":\"47\",\"max\":\"1\",\"duration\":\"\",\"categories\":\"false\",\"tags\":\"false\",\"only_with_pornstars\":\"false\"}'),(13,47,2,'{\"feed_name\":\"YouPorn\",\"site_id\":\"47\",\"max\":\"1\",\"duration\":\"\",\"categories\":\"false\",\"tags\":\"false\",\"only_with_pornstars\":\"false\"}'),(14,47,6,'{\"feed_name\":\"HDZog\",\"site_id\":\"47\",\"max\":\"1\",\"duration\":\"\",\"categories\":\"false\",\"tags\":\"false\",\"only_with_pornstars\":\"false\"}');
/*!40000 ALTER TABLE `cronjobs` ENABLE KEYS */;
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
-- Table structure for table `infojobs`
--

DROP TABLE IF EXISTS `infojobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `infojobs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `feed_id` int(10) DEFAULT NULL,
  `site_id` int(10) DEFAULT NULL,
  `finished` int(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `finished_at` datetime DEFAULT NULL,
  `serialized` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `infojobs`
--

LOCK TABLES `infojobs` WRITE;
/*!40000 ALTER TABLE `infojobs` DISABLE KEYS */;
INSERT INTO `infojobs` VALUES (56,1,47,1,'2016-08-14 20:51:10','2016-08-14 20:52:42','{\"feed_name\":\"PornHub\",\"site_id\":\"47\",\"max\":\"100\",\"duration\":\"\",\"tags\":\"false\",\"categories\":\"false\",\"only_with_pornstars\":\"true\"}'),(57,1,47,1,'2016-08-14 20:51:28','2016-08-15 18:03:47','{\"feed_name\":\"PornHub\",\"site_id\":\"47\",\"max\":\"100\",\"duration\":\"\",\"tags\":\"false\",\"categories\":\"false\",\"only_with_pornstars\":\"true\"}'),(58,1,47,1,'2016-08-14 20:57:09','2016-08-15 18:03:48','{\"feed_name\":\"PornHub\",\"site_id\":\"47\",\"max\":\"100\",\"duration\":\"\",\"tags\":\"false\",\"categories\":\"false\",\"only_with_pornstars\":\"true\"}'),(59,2,47,1,'2016-08-15 18:04:53','2016-08-15 18:04:53','{\"feed_name\":\"YouPorn\",\"site_id\":\"47\",\"max\":\"1\",\"duration\":\"\",\"tags\":\"false\",\"categories\":\"false\",\"only_with_pornstars\":\"false\"}'),(60,1,47,1,'2016-08-15 18:05:08','2016-08-15 18:05:14','{\"feed_name\":\"PornHub\",\"site_id\":\"47\",\"max\":\"1\",\"duration\":\"\",\"tags\":\"false\",\"categories\":\"false\",\"only_with_pornstars\":\"false\"}'),(61,3,47,1,'2016-08-15 18:05:23','2016-08-15 18:05:33','{\"feed_name\":\"xHamster\",\"site_id\":\"47\",\"max\":\"5\",\"duration\":\"\",\"tags\":\"false\",\"categories\":\"false\",\"only_with_pornstars\":\"false\"}');
/*!40000 ALTER TABLE `infojobs` ENABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
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
-- Table structure for table `popunders`
--

DROP TABLE IF EXISTS `popunders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `popunders` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `site_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `popunders`
--

LOCK TABLES `popunders` WRITE;
/*!40000 ALTER TABLE `popunders` DISABLE KEYS */;
INSERT INTO `popunders` VALUES (21,NULL,'http://www.terra.es',47),(22,NULL,'http://www.google.com',47);
/*!40000 ALTER TABLE `popunders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pornstars`
--

DROP TABLE IF EXISTS `pornstars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pornstars` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `thumbnail` varchar(500) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `site_id` int(10) DEFAULT NULL,
  `permalink` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pornstars`
--

LOCK TABLES `pornstars` WRITE;
/*!40000 ALTER TABLE `pornstars` DISABLE KEYS */;
/*!40000 ALTER TABLE `pornstars` ENABLE KEYS */;
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `scene_category_index` (`category_id`,`scene_id`),
  CONSTRAINT `sc_fk_cat1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=65156 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scene_category`
--

LOCK TABLES `scene_category` WRITE;
/*!40000 ALTER TABLE `scene_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `scene_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scene_pornstar`
--

DROP TABLE IF EXISTS `scene_pornstar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scene_pornstar` (
  `scene_id` int(10) NOT NULL,
  `pornstar_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scene_pornstar`
--

LOCK TABLES `scene_pornstar` WRITE;
/*!40000 ALTER TABLE `scene_pornstar` DISABLE KEYS */;
INSERT INTO `scene_pornstar` VALUES (42995,17),(43005,18),(43015,19),(43025,20),(43035,21),(43045,22),(43056,23),(43066,26),(43076,27),(43086,28),(43196,29),(43206,30),(43217,31),(43223,32),(43227,33),(43235,34),(43239,35),(43239,36),(43249,33),(43250,37),(43251,38),(43253,37),(43259,39),(43262,40),(43263,41),(43268,42),(43276,43),(43281,44),(43281,45),(43283,46),(43283,38),(43289,47),(43292,48),(43293,49),(43294,50),(43295,51),(43305,52),(43316,53),(43322,54),(43326,55),(43334,56),(43338,57),(43338,58),(43348,55),(43349,59),(43359,60),(43363,61),(43367,62),(43377,63),(43385,64),(43389,65),(43390,66),(43391,67),(43392,68),(43394,69),(43395,70),(43396,71),(43397,72),(43398,73),(43399,74),(43400,75),(43400,76),(43401,73),(43402,77),(43403,78),(43404,79),(43405,80),(43441,81),(43445,82),(43445,83),(43455,84),(43456,85),(43457,86),(43459,85),(43465,87),(43468,88),(43469,89),(43474,90),(43482,91),(43487,92),(43487,93),(43489,94),(43489,86),(43495,95),(43498,96),(43499,97),(43500,98),(43501,99),(43501,100),(43502,101),(43502,102),(43503,103),(43504,104),(43505,105),(43506,104),(43507,106),(43508,107),(43509,108),(43509,109),(43510,108),(43511,108),(43512,110),(43513,108),(43514,111),(43514,112),(43514,113),(43514,114),(43515,115),(43516,116),(43516,104),(43517,108),(43518,108),(43519,108),(43520,117),(43521,108),(43522,118),(43522,119),(43522,120),(43522,121),(43523,108),(43524,122),(43525,123),(43526,124),(43527,125),(43528,103),(43529,126),(43530,108),(43531,104),(43532,104),(43533,104),(43534,127),(43641,128),(43642,129),(43643,130),(43644,131),(43645,132),(43645,133),(43645,134),(43652,135),(43652,136),(43672,137),(43674,138),(43677,139),(43685,140),(43687,141),(43689,142),(43690,143),(43691,144),(43691,145),(43691,146),(43695,147),(43696,148),(43697,149),(43697,150),(43697,151),(43704,152),(43704,153),(43724,154),(43726,155),(43729,156),(43737,157),(43739,158),(43741,159),(43742,160),(43743,161),(43743,162),(43743,163),(43750,164),(43750,165),(43770,166),(43772,167),(43775,168),(43783,169),(43785,170),(43787,171),(43788,172),(43789,173),(43789,174),(43789,175),(43796,176),(43796,177),(43816,178),(43818,179),(43821,180),(43829,181),(43831,182),(43833,183),(43834,184),(43835,185),(43835,186),(43835,187),(43842,188),(43842,189),(43862,190),(43864,191),(43867,192),(43875,193),(43877,194),(43879,195),(43880,196),(43881,197),(43882,198),(43883,199),(43884,200),(43884,201),(43884,202),(43891,203),(43891,204),(43911,205),(43913,206),(43916,207),(43924,208),(43926,209),(43928,210),(43929,211),(43930,212),(43930,213),(43930,214),(43937,215),(43937,216),(43957,217),(43959,218),(43962,219),(43970,220),(43972,221),(43974,222),(43975,223),(43975,224),(43975,225),(43982,226),(43982,227),(44002,228),(44004,229),(44007,230),(44015,231),(44017,232),(44033,233),(44036,234),(44038,235),(44040,236),(44042,237),(44043,238),(44044,239),(44046,240),(44046,241),(44048,237),(44049,237),(44051,242),(44052,243),(44060,244),(44061,245),(44064,246),(44065,247),(44066,248),(44067,249),(44068,250),(44069,251),(44071,252),(44071,253),(44075,254),(44076,255),(44077,256),(44080,257),(44081,258),(44082,259),(44083,260),(44087,261),(44088,258),(44090,262),(44090,263),(44090,264),(44091,265),(44094,265),(44095,266),(44098,267),(44104,268),(44107,269),(44109,270),(44109,232),(44110,271),(44112,272),(44113,273),(44113,274),(44113,275),(44117,276),(44118,277),(44118,278),(44118,279),(44125,280),(44125,281),(44131,282),(44132,283),(44132,284),(44132,285),(44139,286),(44139,287),(44142,288),(44143,289),(44143,290),(44143,291),(44150,292),(44150,293),(44163,294),(44164,295),(44164,296),(44164,297),(44171,298),(44171,299),(44191,300),(44193,301),(44196,302),(44204,303),(44206,304),(44222,305),(44225,306),(44227,307),(44229,308),(44231,309),(44232,310),(44233,311),(44235,312),(44235,313),(44237,309),(44238,309),(44240,314),(44241,315),(44249,316),(44250,317),(44253,318),(44254,319),(44255,320),(44256,321),(44257,322),(44258,323),(44260,324),(44260,325),(44264,326),(44265,327),(44266,328),(44269,329),(44270,330),(44271,331),(44272,332),(44276,333),(44277,330),(44279,334),(44279,335),(44279,336),(44280,337),(44283,337),(44284,338),(44287,339),(44293,340),(44296,341),(44298,342),(44298,304),(44299,343),(44300,344),(44301,345),(44302,333),(44303,316),(44304,346),(44305,319),(44306,347),(44307,322),(44308,348),(44308,349),(44309,350),(44312,351),(44313,311),(44314,337),(44315,337),(44316,295),(44318,319),(44319,348),(44320,300),(44323,352),(44325,353),(44326,349),(44326,354),(44326,355),(44327,303),(44328,295),(44328,296),(44328,303),(44328,297),(44329,295),(44329,296),(44329,297),(44330,356),(44332,319),(44333,357),(44333,358),(44334,359),(44336,348),(44336,342),(44337,303),(44338,360),(44340,319),(44341,361),(44343,362),(44343,363),(44343,364),(44344,365),(44346,295),(44347,324),(44347,325),(44349,320),(44349,366),(44349,299),(44353,367),(44354,367),(44355,368),(44355,369),(44356,295),(44357,370),(44358,371),(44359,372),(44360,373),(44361,374),(44362,375),(44362,368),(44364,376),(44365,377),(44366,378),(44367,379),(44368,378),(44370,380),(44371,380),(44372,372),(44373,370),(44375,295),(44376,295),(44376,379),(44377,376),(44378,373),(44379,374),(44380,377),(44380,381),(44381,371),(44382,308),(44385,382),(44388,360),(44389,383),(44389,384),(44390,385),(44392,308),(44393,385),(44394,386),(44394,387),(44394,299),(44395,388),(44396,389),(44396,390),(44397,391),(44397,392),(44399,393),(44400,394),(44400,395),(44401,396),(44401,397),(44402,383),(44402,384),(44403,398),(44403,345),(44403,394),(44404,399),(44405,400),(44405,401),(44405,334),(44405,402),(44406,345),(44407,403),(44427,404),(44428,405),(44428,406),(44429,407),(44429,408),(44429,325),(44430,325),(44432,409),(44433,405),(44434,407),(44435,406),(44437,396),(44438,396),(44439,408),(44440,311),(44440,410),(44440,411),(44441,405),(44441,406),(44444,397),(44445,412),(44445,413),(44449,414),(44450,415),(44450,416),(44450,417),(44453,416),(44453,418),(44454,419),(44454,420),(44455,421),(44455,422),(44456,328),(44456,423),(44457,296),(44458,424),(44459,425),(44460,426),(44461,319),(44462,427),(44463,374),(44464,428),(44466,429),(44466,430),(44467,315),(44468,430),(44469,379),(44470,304),(44471,431),(44472,432),(44473,433),(44474,295),(44475,434),(44476,343),(44478,435),(44479,310),(44479,436),(44479,437),(44480,438),(44481,439),(44481,440),(44481,372),(44482,334),(44482,335),(44482,336),(44483,441),(44484,442),(44485,443),(44486,444),(44492,445),(44496,446),(44497,447),(44497,448),(44500,449),(44502,450),(44502,451),(44502,452),(44503,453),(44503,454),(44518,455),(44519,456),(44520,457),(44522,458),(44523,459),(44533,460),(44538,461),(44539,462),(44539,463),(44539,464),(44540,465),(44544,466),(44545,467),(44545,468),(44556,469),(44556,470),(44556,471),(44561,472),(44562,473),(44563,474),(44564,475),(44565,476),(44575,477),(44584,478),(44584,479),(44587,480),(44590,481),(44594,465),(44596,482),(44598,462),(44599,483),(44601,484),(44601,485),(44603,486),(44603,487),(44611,488),(44617,489),(44617,490),(44622,462),(44622,491),(44622,471),(44630,492),(44631,493),(44631,494),(44644,495),(44652,496),(44653,497),(44656,498),(44666,478),(44668,499),(44670,478),(44672,500),(44676,501),(44676,502),(44685,503),(44686,504),(44690,505),(44699,506),(44721,507),(44723,508),(44730,509),(44732,510),(44732,511),(44740,512),(44740,513),(44751,514),(44756,515),(44758,516),(44759,517),(44762,518),(44762,519),(44765,520),(44768,479),(44769,521),(44780,522),(44783,523),(44783,524),(44789,525),(44796,526),(44800,527),(44809,528),(44812,529),(44812,530),(44817,531),(44817,532),(44821,533),(44823,534),(44823,535),(44825,536),(44829,509),(44831,537),(44837,538),(44837,539),(44847,540),(44854,474),(44855,541),(44859,542),(44859,543),(44859,544),(44882,545),(44886,529),(44894,546),(44894,547),(44896,548),(44899,549),(44902,550),(44902,551),(44902,552),(44904,553),(44906,554),(44914,555),(44917,556),(44920,557),(44921,558),(44925,559),(44931,560),(44933,465),(44939,561),(44944,562),(44945,563),(44947,564),(44960,565),(44962,566),(44964,545),(44968,525),(44969,511),(44970,567),(44970,568),(44992,569),(44992,570),(44995,571),(44996,572),(44997,573),(45011,574),(45021,575),(45021,576),(45037,577),(45041,578),(45042,579),(45044,580),(45048,581),(45061,582),(45061,583),(45065,584),(45066,585),(45070,586),(45071,587),(45078,588),(45089,589),(45099,590),(45102,591),(45129,536),(45132,592),(45132,593),(45151,456),(45162,594),(45162,595),(45164,596),(45164,597),(45175,598),(45175,548),(45183,599),(45186,600),(45187,601),(45189,602),(45189,603),(45197,604),(45198,605),(45205,606),(45208,607),(45208,608),(45210,609),(45211,610),(45211,611),(45217,612),(45217,613),(45227,614),(45255,565),(45270,615),(45270,616),(45273,617),(45273,618),(45274,619),(45279,601),(45279,620),(45297,621),(45297,622),(45301,623),(45303,484),(45303,624),(45305,625),(45305,626),(45306,627),(45308,571),(45309,628),(45309,629),(45315,474),(45316,489),(45316,630),(45316,490),(45321,631),(45330,632),(45330,633),(45331,634),(45331,635),(45332,636),(45337,637),(45339,638),(45346,639),(45353,640),(45358,641),(45362,445),(45363,642),(45365,643),(45367,644),(45367,645),(45373,646),(45377,647),(45383,648),(45383,649),(45386,650),(45386,651),(45388,652),(45388,653),(45389,654),(45390,655),(45391,474),(45391,656),(45392,657),(45394,658),(45395,484),(45397,659),(45397,660),(45398,661),(45398,662),(45400,663),(45403,664),(45412,665),(45413,666),(45424,667),(45424,668),(45431,669),(45434,525),(45439,630),(45439,670),(45447,484),(45447,671),(45451,672),(45451,673),(45452,674),(45453,675),(45456,533),(45462,676),(45462,677),(45462,650),(45463,678),(45464,679),(45466,680),(45466,681),(45467,682),(45468,552),(45468,683),(45471,684),(45471,685),(45472,686),(45472,592),(45498,687),(45514,688),(45515,689),(45516,690),(45516,661),(45521,691),(45522,692),(45522,693),(45524,694),(45528,523),(45528,496),(45530,463),(45531,477),(45532,695),(45545,696),(45550,601),(45553,697),(45554,698),(45555,699),(45555,700),(45556,701),(45557,702),(45557,703),(45558,704),(45558,705),(45559,706),(45559,707),(45560,708),(45560,709),(45562,710),(45565,711),(45565,653),(45578,712),(45585,630),(45585,713),(45588,463),(45588,714),(45609,715),(45611,716),(45611,717),(45612,718),(45613,448),(45621,719),(45622,720),(45623,721),(45623,722),(45627,723),(45630,724),(45630,725),(45632,726),(45632,679),(45634,727),(45637,728),(45637,729),(45653,730),(45655,731),(45667,732),(45668,733),(45670,734),(45670,735),(45670,601),(45671,630),(45671,736),(45671,737),(45671,738),(45672,739),(45672,644),(45673,740),(45673,741),(45677,678),(45682,742),(45682,743),(45682,741),(45696,744),(45697,745),(45700,746),(45711,747),(45720,748),(45720,749),(45722,750),(45734,749),(45741,751),(45741,752),(45741,753),(45743,754),(45753,755),(45753,756),(45756,757),(45757,758),(45758,577),(45761,759),(45761,760),(45766,487),(45771,614),(45783,749),(45792,477),(45797,761),(45797,555),(45797,762),(45799,763),(45801,764),(45801,765),(45801,472),(45803,672),(45808,766),(45808,767),(45812,768),(45819,749),(45822,769),(45825,770),(45828,771),(45829,772),(45831,773),(45832,774),(45852,775),(45852,776),(45856,486),(45863,777),(45864,558),(45869,778),(45870,779),(45875,780),(45875,765),(45882,781),(45887,457),(45894,655),(45896,782),(45898,758),(45899,783),(45900,784),(45902,785),(45904,758),(45906,786),(45909,472),(45928,787),(45928,653),(45930,788),(45930,555),(45946,497),(45963,789),(45966,790),(45967,614),(45968,791),(45968,792),(45970,793),(45975,794),(45979,641),(45991,795),(45992,796),(45998,797),(45999,798),(46001,799),(46002,800),(46006,801),(46008,802),(46009,525),(46009,803),(46012,567),(46016,463),(46019,804),(46019,805),(46024,806),(46028,807),(46029,801),(46030,808),(46030,809),(46031,808),(46031,809),(46033,477),(46034,484),(46034,810),(46037,484),(46037,811),(46039,812),(46039,813),(46041,814),(46042,815),(46044,816),(46044,685),(46045,817),(46045,552),(46046,780),(46046,741),(46054,818),(46068,819),(46069,820),(46069,821),(46070,822),(46095,823),(46097,824),(46097,490),(46098,825),(46099,826),(46101,597),(46101,749),(46111,827),(46117,534),(46117,828),(46126,829),(46140,830),(46140,831),(46154,832),(46154,489),(46166,833),(46180,834),(46181,835),(46197,836),(46201,837),(46205,838),(46206,532),(46241,839),(46242,655),(46244,840),(46250,841),(46250,552),(46254,702),(46256,842),(46257,843),(46260,844),(46262,845),(46262,846),(46265,847),(46269,848),(46279,545),(46280,529),(46288,774),(46289,849),(46290,624),(46290,850),(46292,851),(46293,852),(46295,780),(46296,679),(46303,824),(46303,853),(46305,463),(46310,854),(46313,855),(46314,856),(46318,857),(46318,858),(46320,859),(46321,836),(46322,727),(46323,860),(46326,861),(46326,668),(46329,462),(46337,862),(46338,863),(46338,864),(46341,865),(46341,444),(46342,866),(46342,867),(46343,544),(46346,706),(46346,868),(46347,869),(46347,870),(46349,489),(46351,871),(46353,611),(46361,450),(46363,710),(46364,754),(46368,872),(46371,873),(46373,764),(46373,791),(46374,874),(46374,875),(46377,465),(46378,876),(46378,539),(46384,877),(46384,680),(46398,878),(46402,879),(46411,878),(46411,593),(46419,880),(46420,605),(46420,822),(46420,557),(46420,765),(46420,544),(46420,881),(46429,776),(46429,809),(46442,882),(46443,883),(46452,628),(46460,884),(46460,885),(46461,731),(46479,537),(46479,886),(46487,736),(46488,808),(46488,605),(46502,878),(46515,887),(46520,528),(46538,888),(46538,530),(46539,878),(46539,889),(46540,890),(46542,891),(46547,892),(46563,892),(46564,751),(46572,564),(46574,671),(46579,893),(46579,894),(46581,895),(46585,896),(46585,528),(46586,897),(46594,898),(46600,630),(46600,899),(46601,900),(46602,901),(46604,902),(46606,903),(46609,904),(46612,905),(46613,906),(46614,907),(46614,544),(46615,908),(46616,807),(46617,909),(46618,910),(46621,911),(46630,912),(46640,913),(46642,914),(46644,915),(46646,916),(46647,560),(46647,917),(46648,918),(46650,919),(46656,750),(46660,920),(46660,921),(46666,533),(46667,915),(46667,595),(46668,922),(46673,923),(46677,657),(46677,489),(46690,924),(46693,925),(46694,926),(46705,453),(46707,927),(46707,552),(46711,928),(46725,548),(46725,929),(46727,806),(46775,930),(46776,931),(46777,736),(46777,932),(46781,933),(46789,871),(46811,756),(46818,934),(46819,935),(46820,936),(46847,915),(46862,937),(46862,938),(46871,939),(46877,940),(46890,806),(46899,671),(46900,941),(46900,650),(46912,942),(46914,510),(46914,943),(46916,944),(46917,611),(46920,945),(46926,915),(46928,946),(46930,663),(46930,947),(46935,948),(46936,949),(46936,950),(46940,951),(46940,952),(46942,953),(46947,954),(46947,624),(46948,955),(46948,956),(46948,957),(46948,523),(46950,958),(46952,915),(46953,560),(46957,959),(46960,778),(46960,552),(46967,960),(46968,749),(46982,961),(46986,710),(47005,731),(47006,608),(47006,548),(47011,897),(47029,612),(47037,892),(47053,566),(47053,962),(47061,637),(47061,570),(47066,963),(47066,964),(47068,965),(47068,848),(47089,966),(47094,493),(47094,803),(47094,952),(47111,782),(47112,967),(47114,968),(47116,969),(47116,804),(47116,970),(47116,568),(47118,873),(47120,971),(47129,972),(47153,973),(47153,974),(47159,883),(47190,901),(47190,975),(47194,976),(47195,668),(47195,977),(47200,978),(47201,979),(47202,980),(47203,671),(47203,852),(47227,981),(47239,731),(47239,627),(47241,800),(47242,982),(47247,627),(47250,983),(47250,627),(47253,984),(47253,985),(47254,986),(47254,627),(47255,987),(47259,627),(47259,448),(47260,988),(47261,627),(47261,989),(47263,534),(47263,627),(47275,990),(47275,486),(47308,650),(47314,628),(47314,991),(47317,992),(47318,993),(47319,993),(47321,994),(47322,509),(47335,995),(47343,996),(47370,997),(47370,998),(47372,999),(47373,528),(47375,1000),(47376,657),(47376,566),(47384,1001),(47385,627),(47385,1002),(47387,1003),(47424,1004),(47439,1005),(47449,1006),(47450,656),(47454,756),(47455,1007),(47462,1008),(47470,1009),(47473,1010),(47474,956),(47474,1011),(47477,1012),(47484,594),(47486,709),(47486,842),(47486,508),(47486,552),(47487,1013),(47489,479),(47492,1014),(47510,1015),(47517,1016),(47517,545),(47517,508),(47568,1017),(47568,1018),(47596,1019),(47600,499),(47603,1020),(47604,566),(47609,1021),(47616,519),(47618,560),(47618,668),(47618,530),(47618,1022),(47618,532),(47620,630),(47620,1023),(47621,474),(47621,1024),(47623,1025),(47625,1026),(47626,458),(47629,760),(47629,562),(47631,1027),(47634,1028),(47635,1029),(47636,1030),(47636,1031),(47638,462),(47639,1032),(47639,670),(47640,1033),(47645,1034),(47645,595),(47658,529),(47659,1035),(47659,1036),(47660,912),(47665,1037),(47665,627),(47674,1038),(47678,1039),(47678,1040),(47679,946),(47704,1041),(47705,1042),(47706,995),(47706,791),(47708,514),(47708,1043),(47709,852),(47710,1044),(47711,1045),(47713,599),(47713,541),(47716,605),(47716,631),(47719,1046),(47719,1047),(47726,1048),(47726,1049),(47732,1050),(47733,1051),(47736,809),(47739,564),(47740,564),(47744,545),(47744,971),(47745,1052),(47748,1053),(47749,1054),(47750,478),(47751,1055),(47754,1056),(47754,1057),(47755,1058),(47756,765),(47756,565),(47757,1059),(47758,1060),(47761,495),(47763,1061),(47764,894),(47764,1062),(47765,1063),(47767,1064),(47772,558),(47774,819),(47775,1065),(47775,1066),(47775,1067),(47775,1068),(47777,1044),(47789,1069),(47802,1070),(47802,1028),(47804,986),(47804,627),(47806,1071),(47807,722),(47843,1072),(47844,1073),(47881,1074),(47881,1075),(47885,865),(47889,1076),(47896,655),(47896,900),(47901,650),(47901,560),(47903,662),(47904,1077),(47905,1078),(47914,1079),(47914,1080),(47920,973),(47920,1081),(47926,1082),(47956,1021),(47959,983),(47959,627),(47960,1083),(47963,780),(47963,1020),(47964,865),(47967,1084),(47967,834),(47968,1085),(47970,1086),(47977,1085),(47978,970),(47978,1087),(47979,605),(47980,1088),(47980,479),(47981,605),(47985,1089),(47985,1090),(47988,1091),(47992,1092),(48003,545),(48009,689),(48013,1093),(48016,1094),(48016,558),(48016,1095),(48018,1096),(48025,1097),(48026,1098),(48026,1099),(48026,1062),(48032,1100),(48032,529),(48032,847),(48032,1101),(48032,532),(48035,1102),(48040,1103),(48048,1104),(48050,1105),(48054,909),(48056,1106),(48056,1107),(48061,1043),(48067,973),(48067,993),(48069,668),(48074,928),(48076,1108),(48077,627),(48083,1109),(48090,608),(48090,1110),(48108,1111),(48108,619),(48108,1112),(48148,689),(48148,944),(48150,1113),(48152,713),(48154,893),(48154,1114),(48155,1115),(48156,632),(48157,918),(48159,975),(48161,1116),(48161,1117),(48163,1118),(48168,1119),(48171,1120),(48172,1121),(48172,1122),(48173,679),(48176,1123),(48183,1124),(48186,1125),(48189,1126),(48193,913),(48195,643),(48196,1127),(48199,1128),(48210,1129),(48213,1130),(48214,1056),(48216,1131),(48218,816),(48220,654),(48221,1132),(48227,1133),(48230,1067),(48233,608),(48233,971),(48266,677),(48276,1081),(48289,760),(48294,1134),(48295,555),(48301,1135),(48301,996),(48305,1136),(48310,1137),(48310,1138),(48310,1139),(48311,501),(48325,1140),(48325,1141),(48331,888),(48334,1142),(48336,677),(48336,931),(48339,1143),(48357,1144),(48361,905),(48366,1145),(48368,1146),(48369,892),(48371,1147),(48378,1148),(48378,983),(48378,627),(48382,1149),(48382,1150),(48383,681),(48385,985),(48386,719),(48387,1151),(48392,1152),(48394,1072),(48398,762),(48399,1153),(48413,657),(48417,1154),(48417,809),(48426,1155),(48429,1156),(48430,1157),(48434,1158),(48436,471),(48438,612),(48450,680),(48450,1159),(48451,935),(48451,553),(48453,500),(48454,1160),(48461,1161),(48462,1162),(48498,889),(48498,1163),(48517,1164),(48517,1165),(48517,991),(48535,994),(48544,980),(48544,792),(48600,1166),(48608,1167),(48616,1168),(48616,708),(48638,1043),(48718,1169),(48728,1170),(48729,1136),(48729,1171),(48729,1172),(48758,800),(48765,1173),(48772,1174),(48780,955),(48780,463),(48781,1175),(48782,703),(48782,680),(48785,1176),(48786,921),(48787,1177),(48790,1178),(48791,954),(48791,552),(48795,1140),(48798,1179),(48799,731),(48799,1180),(48802,534),(48802,627),(48807,1181),(48808,1182),(48808,1183),(48808,1184),(48808,1185),(48809,787),(48809,1186),(48810,923),(48811,1187),(48813,1180),(48813,958),(48815,933),(48817,512),(48821,1188),(48823,502),(48825,991),(48827,1189),(48829,764),(48833,1190),(48836,1191),(48846,1192),(48848,701),(48862,650),(48913,1193),(48926,683),(48932,1194),(48938,1195),(48951,1196),(48952,1197),(48975,1198),(48980,1199),(48981,812),(48997,770),(49034,1200),(49043,928),(49055,539),(49055,1028),(49075,627),(49075,989),(49144,594),(49144,642),(49186,975),(49208,1201),(49209,1202),(49277,1203),(49277,1204),(49282,931),(49283,982),(49344,1006),(49418,1205),(49457,1084),(49457,490),(49460,775),(49460,1101),(49461,1206),(49462,1207),(49462,1208),(49463,749),(49466,650),(49469,1209),(49469,616),(49470,1210),(49472,1072),(49474,916),(49475,1211),(49475,574),(49479,1212),(49480,1033),(49480,1213),(49500,1214),(49520,1215),(49520,1216),(49526,1217),(49534,1218),(49534,528),(49565,865),(49584,812),(49585,472),(49588,777),(49588,952),(49629,1219),(49629,660),(49635,1052),(49642,1220),(49666,1221),(49679,1150),(49680,615),(49684,1222),(49684,1223),(49685,1224),(49686,1225),(49700,653),(49732,637),(49740,1226),(49740,1138),(49743,1227),(49743,1228),(49743,444),(49745,900),(49746,508),(49748,1229),(49749,1230),(49749,1231),(49749,1232),(49749,1233),(49750,1234),(49750,539),(49752,1235),(49757,1114),(49760,1236),(49760,479),(49763,1237),(49764,1238),(49771,1048),(49772,1239),(49796,1240),(49797,987),(49843,1241),(49843,635),(49844,560),(49844,1242),(49846,1243),(49847,631),(49847,1244),(49851,1245),(49854,1246),(49860,1247),(49860,1248),(49862,1249),(49865,1250),(49866,1251),(49866,1252),(49866,1253),(49898,1254),(49898,448),(49904,1255),(49904,1256),(49904,838),(49919,596),(49919,1257),(49951,1258),(49955,443),(49959,1124),(49961,548),(49962,918),(49963,884),(49967,631),(49968,1259),(49973,619),(49973,471),(49974,1260),(49976,1261),(49979,1262),(49981,630),(49981,479),(49984,1263),(49984,662),(49985,605),(49985,834),(49994,1264),(49994,446),(49997,447),(49997,680),(49998,1265),(50000,676),(50000,1266),(50004,635),(50006,638),(50010,731),(50010,677),(50012,1070),(50014,1267),(50016,1268),(50020,545),(50022,1192),(50027,1269),(50029,860),(50031,1045),(50037,848),(50039,1270),(50040,450),(50042,1271),(50050,772),(50058,1149),(50058,999),(50059,1272),(50059,779),(50066,498),(50084,532),(50085,1032),(50094,1273),(50098,1274),(50098,1275),(50103,1276),(50104,1277),(50104,864),(50110,448),(50129,1278),(50139,1181),(50145,1279),(50149,1280),(50150,1281),(50165,915),(50168,1282),(50174,564),(50176,892),(50177,666),(50178,812),(50180,818),(50182,1152),(50184,1283),(50187,1284),(50189,1285),(50190,1286),(50193,1287),(50198,1288),(50198,457),(50201,457),(50202,1289),(50203,809),(50205,1020),(50206,1287),(50215,974),(50224,1061),(50224,1290),(50227,1187),(50228,1199),(50229,896),(50230,1291),(50232,1058),(50232,1292),(50238,1293),(50247,812),(50264,565),(50268,1294),(50271,1295),(50272,941),(50272,1296),(50276,1297),(50276,1298),(50278,1024),(50279,1299),(50280,1300),(50283,907),(50283,713),(50285,1301),(50285,582),(50289,1302),(50290,1048),(50295,713),(50297,1053),(50297,818),(50299,901),(50302,749),(50303,1303),(50308,1304),(50311,1305),(50315,1306),(50315,668),(50319,1307),(50329,646),(50337,1308),(50345,1309),(50345,1310),(50359,1311),(50373,1058),(50373,1087),(50373,1312),(50392,995),(50392,1002),(50399,566),(50399,1313),(50406,580),(50411,1314),(50411,1315),(50415,1316),(50417,834),(50418,627),(50418,490),(50436,722),(50436,677),(50452,463),(50461,921),(50465,1033),(50473,1317),(50482,1318),(50506,928),(50510,611),(50548,1319),(50553,701),(50556,1320),(50562,1321),(50563,545),(50565,533),(50571,1322),(50577,486),(50578,1107),(50579,1323),(50580,882),(50580,952),(50582,1324),(50582,824),(50620,734),(50626,474),(50626,1249),(50634,812),(50634,813),(50645,941),(50645,1015),(50650,525),(50655,791),(50659,1119),(50660,1325),(50661,484),(50661,1326),(50662,1327),(50662,560),(50663,463),(50663,762),(50665,653),(50679,848),(50679,809),(50687,1328),(50688,1329),(50688,1330),(50688,1331),(50714,770),(50714,1332),(50714,1333),(50714,472),(50726,787),(50733,1011),(50736,1171),(50746,1251),(50761,533),(50767,1032),(50768,1334),(50770,1335),(50772,619),(50773,1336),(50776,1337),(50778,1102),(50780,1338),(50781,1339),(50782,1340),(50782,1341),(50783,1342),(50785,488),(50801,1343),(50839,1250),(50843,923),(50844,594),(50849,558),(50857,1344),(50858,1345),(50859,671),(50859,732),(50865,1346),(50866,1347),(50867,974),(50868,811),(50870,939),(50871,1348),(50872,758),(50875,825),(50875,553),(50878,873),(50883,503),(50884,1349),(50884,1143),(50885,1350),(50889,1351),(50891,816),(50896,848),(50896,532),(50932,1352),(50947,1353),(50947,571),(50947,1354),(50947,858),(50955,1355),(50972,893),(50976,1356),(50979,1158),(50986,1357),(50988,545),(50989,1358),(50991,1359),(50993,1360),(50996,1361),(50997,799),(51021,1303),(51040,1362),(51046,463),(51049,1363),(51049,1186),(51051,689),(51059,777),(51079,1327),(51079,1364),(51079,448),(51079,490),(51081,1365),(51082,1366),(51084,929),(51087,1367),(51097,481),(51097,1368),(51109,1369),(51109,1370),(51123,1371),(51131,1276),(51136,697),(51146,706),(51152,697),(51154,761),(51156,1372),(51158,1373),(51159,1374),(51162,1375),(51170,1376),(51175,1377),(51177,1378),(51177,1204),(51178,650),(51180,489),(51207,1379),(51208,553),(51211,1119),(51212,1380),(51214,560),(51237,1381),(51238,913),(51239,946),(51243,692),(51245,834),(51246,1382),(51254,1383),(51262,1384),(51263,1385),(51265,1317),(51265,941),(51270,812),(51276,494),(51276,500),(51277,500),(51277,662),(51281,1268),(51281,523),(51284,1386),(51286,1387),(51288,472),(51289,1388),(51293,1389),(51294,749),(51297,1390),(51303,1391),(51303,809),(51303,548),(51304,1392),(51309,897),(51312,1393),(51313,630),(51313,632),(51317,539),(51317,448),(51318,1394),(51320,1248),(51321,1395),(51326,1396),(51330,1397),(51332,1398),(51332,474),(51333,777),(51337,873),(51338,1399),(51353,1400),(51374,941),(51374,1002),(51379,671),(51379,1401),(51381,1402),(51384,555),(51397,896),(51398,1403),(51406,787),(51416,537),(51416,1404),(51427,1405),(51431,788),(51431,722),(51431,1249),(51433,1406),(51434,753),(51442,826),(51445,1407),(51446,1408),(51447,474),(51472,529),(51478,1268),(51481,1409),(51482,1147),(51485,509),(51489,555),(51492,808),(51493,808),(51504,1410),(51517,1411),(51518,1100),(51530,834),(51539,1412),(51540,555),(51541,783),(51542,1413),(51543,1414),(51546,732),(51546,1415),(51547,644),(51547,1416),(51548,622),(51548,1417),(51548,919),(51550,1194),(51552,1418),(51553,1419),(51553,787),(51554,501),(51559,872),(51565,889),(51565,1420),(51572,1421),(51578,1422),(51579,958),(51579,1423),(51583,897),(51590,809),(51597,1424),(51605,599),(51620,1087),(51621,957),(51625,1425),(51629,945),(51642,1213),(51654,970),(51669,1426),(51673,764),(51673,958),(51676,1427),(51690,1428),(51690,1429),(51692,1430),(51701,1431),(51709,1432),(51716,1433),(51719,1434),(51732,1435),(51732,1436),(51741,772),(51753,1437),(51757,589),(51764,838),(51785,489),(51785,1438),(51794,873),(51795,610),(51795,1439),(51795,1037),(51796,1440),(51796,884),(51797,1441),(51797,1440),(51797,884),(51800,719),(51802,958),(51804,498),(51804,539),(51806,832),(51806,823),(51808,759),(51810,1427),(51811,1442),(51813,1443),(51813,1444),(51815,454),(51816,1445),(51817,1446),(51818,1447),(51820,878),(51821,1448),(51823,822),(51824,565),(51830,1449),(51831,1450),(51831,685),(51832,925),(51833,1451),(51834,1452),(51841,1453),(51841,1454),(51843,450),(51844,1455),(51850,1456),(51863,1101),(51895,1457),(51900,1458),(51905,560),(51935,787),(51941,1459),(51953,769),(51962,614),(51964,953),(51966,661),(51966,1368),(51968,1460),(51975,1101),(51991,1461),(51996,448),(51999,454),(52025,1462),(52035,1463),(52050,913),(52055,1048),(52056,1304),(52057,825),(52085,1464),(52087,1465),(52104,1402),(52110,1144),(52110,1466),(52193,1251),(52196,1088),(52254,1467),(52254,1468),(52507,1469),(52556,931),(52558,1161),(52606,637),(52606,1446),(52621,1355),(52621,1470),(52644,1471),(52644,1472),(52644,1473),(52683,484),(52683,749),(52684,1474),(52688,1475),(52688,963),(52696,1476),(52705,772),(52709,1422),(52710,873),(52713,1477),(52717,591),(52717,1478),(52730,1479),(52740,1480),(52748,589),(52753,1388),(52764,1481),(52765,1482),(52765,1483),(52765,1233),(52769,560),(52793,710),(52798,1484),(52802,582),(52802,565),(52803,591),(52803,777),(52804,680),(52804,1485),(52805,484),(52805,777),(52805,810),(52805,1037),(52806,743),(52809,1033),(52809,941),(52814,1486),(52820,635),(52820,592),(52821,497),(52834,1251),(52835,1053),(52837,1487),(52837,814),(52838,1488),(52843,1489),(52845,1490),(52851,1491),(52856,1492),(52857,1493),(52860,1494),(52861,1495),(52864,1152),(52867,1496),(52868,774),(52870,1497),(52872,1387),(52880,534),(52880,1148),(52880,1498),(52889,704),(52899,1499),(52900,450),(52900,662),(52921,1500),(52948,985),(52950,1501),(52962,1346),(52962,544),(52980,1418),(52980,668),(52980,448),(52981,1268),(52981,952),(52993,1502),(52993,950),(52999,1503),(53000,1504),(53001,1505),(53012,1263),(53024,1506),(53025,1507),(53025,796),(53037,1508),(53040,1509),(53041,1379),(53041,1510),(53051,1511),(53082,1512),(53084,1513),(53085,489),(53087,489),(53088,450),(53088,451),(53089,1514),(53099,1515),(53104,1492),(53104,555),(53104,670),(53107,623),(53116,1516),(53124,610),(53124,479),(53132,1517),(53132,469),(53133,1518),(53137,1519),(53143,1520),(53152,772),(53165,1521),(53166,1522),(53174,1053),(53178,1190),(53179,996),(53183,1208),(53186,1523),(53204,1521),(53213,933),(53229,1524),(53237,1525),(53253,525),(53253,913),(53254,1526),(53254,577),(53255,1195),(53256,1037),(53273,1527),(53279,1147),(53287,1446),(53328,1516),(53329,1528),(53334,1115),(53334,1529),(53337,631),(53338,1530),(53343,1531),(53344,896),(53344,1532),(53349,1533),(53350,731),(53350,1534),(53353,1535),(53356,1536),(53358,663),(53358,1537),(53358,762),(53362,654),(53365,801),(53366,1538),(53386,896),(53386,931),(53387,1539),(53387,1048),(53396,1540),(53412,508),(53413,503),(53414,994),(53416,1045),(53418,1541),(53420,1542),(53424,1542),(53428,1496),(53429,1543),(53432,532),(53433,574),(53439,1544),(53439,1545),(53439,1233),(53440,859),(53446,525),(53463,655),(53511,1062),(53514,558),(53514,881),(53520,657),(53521,824),(53521,1277),(53522,1546),(53522,1309),(53522,1547),(53523,1548),(53523,834),(53524,1549),(53524,1550),(53525,1549),(53525,1551),(53525,1552),(53525,1553),(53528,680),(53528,1554),(53532,1553),(53533,1555),(53537,525),(53546,787),(53547,1556),(53547,1035),(53548,751),(53564,1337),(53564,616),(53566,1141),(53569,1101),(53569,971),(53574,875),(53574,958),(53579,1557),(53585,1001),(53590,655),(53622,1053),(53636,1558),(53648,641),(53656,1559),(53656,548),(53658,1560),(53674,519),(53692,564),(53704,1531),(53708,709),(53708,659),(53710,1167),(53710,1561),(53710,1562),(53710,1563),(53713,1535),(53714,1564),(53715,1565),(53715,664),(53716,1566),(53716,628),(53717,804),(53717,1567),(53717,762),(53718,1057),(53719,995),(53720,1568),(53721,1569),(53722,1570),(53724,1571),(53726,1572),(53730,1573),(53731,485),(53734,888),(53750,1247),(53757,1574),(53766,769),(53772,1419),(53772,448),(53773,669),(53774,1192),(53785,1363),(53785,1186),(53785,984),(53808,1575),(53808,741),(53809,1576),(53810,1577),(53811,1578),(53813,1579),(53841,519),(53841,1580),(53842,1581),(53842,668),(53843,882),(53844,704),(53853,772),(53857,607),(53857,1200),(53858,1187),(53859,560),(53863,627),(53863,862),(53864,1033),(53865,635),(53865,568),(53867,495),(53870,1188),(53871,1582),(53872,1583),(53875,1584),(53878,899),(53880,1585),(53881,1586),(53882,1249),(53886,1587),(53889,1588),(53893,1195),(53893,530),(53903,855),(53904,1589),(53909,1120),(53909,1028),(53912,1590),(53912,1591),(53918,448),(53919,1592),(53920,701),(53923,567),(53924,1593),(53936,1594),(53937,1595),(53945,1596),(53946,857),(53961,1071),(53986,1597),(53991,611),(54009,1598),(54012,495),(54012,1591),(54013,1599),(54018,1600),(54018,1402),(54023,1601),(54025,1602),(54031,1603),(54038,1604),(54038,1605),(54076,1537),(54080,460),(54094,1606),(54098,589),(54099,601),(54101,950),(54112,562),(54112,884),(54122,1607),(54122,1608),(54122,1609),(54127,1610),(54129,1611),(54131,1612),(54156,490),(54157,1613),(54158,1614),(54162,1615),(54165,594),(54167,1120),(54169,539),(54170,1616),(54172,677),(54176,685),(54177,1617),(54178,1268),(54179,529),(54179,1021),(54182,1618),(54183,1384),(54185,955),(54186,1619),(54187,1620),(54209,477),(54209,481),(54224,462),(54225,787),(54225,532),(54229,1408),(54230,1621),(54251,1622),(54253,1268),(54253,611),(54253,1623),(54253,791),(54256,1624),(54263,1625),(54263,1626),(54264,1627),(54268,662),(54269,671),(54269,1628),(54279,1629),(54281,465),(54297,1630),(54351,756),(54360,611),(54365,1578),(54366,1507),(54368,928),(54376,1631),(54378,898),(54379,1049),(54387,698),(54394,1101),(54394,1632),(54400,777),(54400,1633),(54411,1634),(54412,701),(54413,464),(54415,646),(54416,1496),(54428,566),(54429,536),(54432,1635),(54433,1636),(54458,560),(54473,879),(54478,788),(54478,1637),(54480,607),(54480,1032),(54705,1),(54705,2),(54706,3),(54707,4),(54707,5),(54707,6),(54708,7),(54708,8),(54708,9),(54709,10),(54709,2),(54710,11),(54710,12),(54711,13),(54712,1),(54712,14),(54713,15),(54714,16),(54715,17),(54715,18),(54715,19),(54716,20),(54717,21),(54720,20),(54720,22),(54720,23),(54721,24),(54722,25),(54722,26),(54724,27),(54726,28),(54727,29),(54728,30),(54728,31),(54728,32),(54729,33),(54730,34),(54730,35),(54732,20),(54732,36),(54733,37),(54734,36),(54735,38),(54735,39),(54735,40),(54736,41),(54737,42),(54737,43),(54737,7),(54737,44),(54739,45),(54739,46),(54739,47),(54742,48),(54742,49),(54743,50),(54743,51),(54743,52),(54744,53),(54744,54),(54745,55),(54746,56),(54747,18),(54748,17),(54750,57),(54750,45),(54751,36),(54752,3),(54754,58),(54755,59),(54756,60),(54757,56),(54758,53),(54758,54),(54759,3),(54761,61),(54762,62),(54767,63),(54768,64),(54768,5),(54769,17),(54770,65),(54771,66),(54771,67),(54772,5),(54772,68),(54774,69),(54774,70),(54774,54),(54775,71),(54777,72),(54777,73),(54778,67),(54780,74),(54781,75),(54781,76),(54783,1),(54783,77),(54783,72),(54785,78),(54785,71),(54786,79),(54787,80),(54788,25),(54788,26),(54789,81),(54792,57),(54793,59),(54794,13),(54797,82),(54798,83),(54800,25),(54801,84),(54805,85),(54808,86);
/*!40000 ALTER TABLE `scene_pornstar` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=89618 DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=277019 DEFAULT CHARSET=utf8;
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
  `url` varchar(500) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `duration` int(10) DEFAULT NULL,
  `rate` float(10,2) DEFAULT NULL,
  `views` int(10) DEFAULT NULL,
  `channel_id` int(10) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `site_id` int(10) DEFAULT NULL,
  `cache_order` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_preview` (`preview`)
) ENGINE=InnoDB AUTO_INCREMENT=61852 DEFAULT CHARSET=utf8;
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
  `ua` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_scli_1` (`scene_id`),
  CONSTRAINT `fk_scli_1` FOREIGN KEY (`scene_id`) REFERENCES `scenes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
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
  PRIMARY KEY (`id`),
  KEY `sc_fk1` (`category_id`),
  CONSTRAINT `sc_fk1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
  `contact_email` varchar(255) DEFAULT NULL,
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
  `title_pornstars` varchar(255) DEFAULT NULL,
  `title_pornstar` varchar(255) DEFAULT NULL,
  `description_pornstars` varchar(255) DEFAULT NULL,
  `description_pornstar` varchar(255) DEFAULT NULL,
  `have_domain` int(1) DEFAULT NULL,
  `color` varchar(15) DEFAULT NULL,
  `color2` varchar(15) DEFAULT NULL,
  `color3` varchar(15) DEFAULT NULL,
  `color4` varchar(15) DEFAULT NULL,
  `color5` varchar(15) DEFAULT NULL,
  `color6` varchar(15) DEFAULT NULL,
  `color7` varchar(15) DEFAULT NULL,
  `color8` varchar(15) DEFAULT NULL,
  `color9` varchar(15) DEFAULT NULL,
  `color10` varchar(15) DEFAULT NULL,
  `color11` varchar(15) DEFAULT NULL,
  `color12` varchar(15) DEFAULT NULL,
  `banner_script1` varchar(500) DEFAULT NULL,
  `banner_script2` varchar(500) DEFAULT NULL,
  `banner_script3` varchar(500) DEFAULT NULL,
  `banner_mobile1` varchar(500) DEFAULT NULL,
  `banner_video1` varchar(500) DEFAULT NULL,
  `banner_video2` varchar(500) DEFAULT NULL,
  `button2_url` varchar(255) DEFAULT NULL,
  `button1_text` varchar(255) DEFAULT NULL,
  `button2_text` varchar(255) DEFAULT NULL,
  `button1_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sites`
--

LOCK TABLES `sites` WRITE;
/*!40000 ALTER TABLE `sites` DISABLE KEYS */;
INSERT INTO `sites` VALUES (47,'demo','2','demo.sexodome.loc','',48,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,'<script type=\"text/javascript\">\r\nvar ad_idzone = \"2211967\",\r\n	 ad_width = \"300\",\r\n	 ad_height = \"250\";\r\n</script>\r\n<script type=\"text/javascript\" src=\"https://ads.exoclick.com/ads.js\"></script>\r\n<noscript><a href=\"http://main.exoclick.com/img-click.php?idzone=2211967\" target=\"_blank\"><img src=\"https://syndication.exoclick.com/ads-iframe-display.php?idzone=2211967&output=img&type=300x250\" width=\"300\" height=\"250\"></a></noscript>','<script type=\"text/javascript\">\r\nvar ad_idzone = \"2211967\",\r\n	 ad_width = \"300\",\r\n	 ad_height = \"250\";\r\n</script>\r\n<script type=\"text/javascript\" src=\"https://ads.exoclick.com/ads.js\"></script>\r\n<noscript><a href=\"http://main.exoclick.com/img-click.php?idzone=2211967\" target=\"_blank\"><img src=\"https://syndication.exoclick.com/ads-iframe-display.php?idzone=2211967&output=img&type=300x250\" width=\"300\" height=\"250\"></a></noscript>','<script type=\"text/javascript\">\r\nvar ad_idzone = \"2211967\",\r\n	 ad_width = \"300\",\r\n	 ad_height = \"250\";\r\n</script>\r\n<script type=\"text/javascript\" src=\"https://ads.exoclick.com/ads.js\"></script>\r\n<noscript><a href=\"http://main.exoclick.com/img-click.php?idzone=2211967\" target=\"_blank\"><img src=\"https://syndication.exoclick.com/ads-iframe-display.php?idzone=2211967&output=img&type=300x250\" width=\"300\" height=\"250\"></a></noscript>','<noscript><a href=\"http://main.exoclick.com/img-click.php?idzone=2231135\" target=\"_blank\"><img src=\"https://syndication.exoclick.com/ads-iframe-display.php?idzone=2231135&output=img&type=300x100\" width=\"300\" height=\"100\"></a></noscript>','<script type=\"text/javascript\">\r\nvar ad_idzone = \"2241777\",\r\n	 ad_width = \"300\",\r\n	 ad_height = \"250\";\r\n</script>\r\n<script type=\"text/javascript\" src=\"https://ads.exoclick.com/ads.js\"></script>\r\n<noscript><a href=\"http://main.exoclick.com/img-click.php?idzone=2241777\" target=\"_blank\"><img src=\"https://syndication.exoclick.com/ads-iframe-display.php?idzone=2241777&output=img&type=300x250\" width=\"300\" height=\"250\"></a></noscript>','','','Live Sex','','http://www.terra.es'),(48,NULL,'2','dominiodepruebas.loc2',NULL,NULL,3,'','','','','',NULL,NULL,'','',NULL,NULL,'','','','',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','<script type=\"text/javascript\">\r\nvar ad_idzone = \"2231135\",\r\n	 ad_width = \"300\",\r\n	 ad_height = \"100\";\r\n</script>\r\n<script type=\"text/javascript\" src=\"https://ads.exoclick.com/ads.js\"></script>\r\n<script type=\"text/javascript\">\r\nvar ad_idzone = \"2231135\",\r\n	 ad_width = \"300\",\r\n	 ad_height = \"100\";\r\n</script>\r\n<script type=\"text/javascript\" src=\"https://ads.exoclick.com/ads.js\"></script>\r\n<noscript><a href=\"http://main.exoclick.com/img-click.php?idzone=2231135\" target=\"_blank\"><img src=\"https://sy',NULL,NULL,'','','',''),(49,'demo','2','p.es.es','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,'<script type=\"text/javascript\">\r\nvar ad_idzone = \"2211967\",\r\n	 ad_width = \"300\",\r\n	 ad_height = \"250\";\r\n</script>\r\n<script type=\"text/javascript\" src=\"https://ads.exoclick.com/ads.js\"></script>\r\n<noscript><a href=\"http://main.exoclick.com/img-click.php?idzone=2211967\" target=\"_blank\"><img src=\"https://syndication.exoclick.com/ads-iframe-display.php?idzone=2211967&output=img&type=300x250\" width=\"300\" height=\"250\"></a></noscript>','<script type=\"text/javascript\">\r\nvar ad_idzone = \"2211967\",\r\n	 ad_width = \"300\",\r\n	 ad_height = \"250\";\r\n</script>\r\n<script type=\"text/javascript\" src=\"https://ads.exoclick.com/ads.js\"></script>\r\n<noscript><a href=\"http://main.exoclick.com/img-click.php?idzone=2211967\" target=\"_blank\"><img src=\"https://syndication.exoclick.com/ads-iframe-display.php?idzone=2211967&output=img&type=300x250\" width=\"300\" height=\"250\"></a></noscript>','<script type=\"text/javascript\">\r\nvar ad_idzone = \"2211967\",\r\n	 ad_width = \"300\",\r\n	 ad_height = \"250\";\r\n</script>\r\n<script type=\"text/javascript\" src=\"https://ads.exoclick.com/ads.js\"></script>\r\n<noscript><a href=\"http://main.exoclick.com/img-click.php?idzone=2211967\" target=\"_blank\"><img src=\"https://syndication.exoclick.com/ads-iframe-display.php?idzone=2211967&output=img&type=300x250\" width=\"300\" height=\"250\"></a></noscript>','<noscript><a href=\"http://main.exoclick.com/img-click.php?idzone=2231135\" target=\"_blank\"><img src=\"https://syndication.exoclick.com/ads-iframe-display.php?idzone=2231135&output=img&type=300x100\" width=\"300\" height=\"100\"></a></noscript>','<script type=\"text/javascript\">\r\nvar ad_idzone = \"2241777\",\r\n	 ad_width = \"300\",\r\n	 ad_height = \"250\";\r\n</script>\r\n<script type=\"text/javascript\" src=\"https://ads.exoclick.com/ads.js\"></script>\r\n<noscript><a href=\"http://main.exoclick.com/img-click.php?idzone=2241777\" target=\"_blank\"><img src=\"https://syndication.exoclick.com/ads-iframe-display.php?idzone=2241777&output=img&type=300x250\" width=\"300\" height=\"250\"></a></noscript>','','','Live Sex','','http://www.terra.es'),(50,'demo','2','ese.ese.es','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(51,'demo','2','ese.akel','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(52,'demo','2','werwer','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(53,'demo','2','werwrsg13','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(54,'demo','2','sr23','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(55,'demo','2','fdsf','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(56,'demo','2','3123','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(57,'demo','2','rwer3','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(58,'demo','2','rwer','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(59,'demo','2','435','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(60,'demo','2','we','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(61,'demo','2','erwe','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(62,'demo','2','er','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(63,'demo','2','we','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(64,'demo','2','paki','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(65,'demo','2','2123','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(66,'demo','2','qwe','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(67,'demo','2','pepe.papi','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(68,'demo','2','sdf','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(69,'demo','2','asdfadf','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(70,'demo','2','87sdf','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(71,'demo','2','juas.juas.es','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(72,'demo','2','wer','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(73,'demo','2','wer','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(74,'demo','2','wer','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(75,'demo','2','erwerw342','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(76,'demo','2','r23r','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(77,'demo','2','wer','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(78,'demo','2','wwer','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(79,'demo','2','wer','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(80,'demo','2','wer','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(81,'demo','2','wer','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(82,'demo','2','rer','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(83,'demo','2','rewer','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(84,'demo','2','kasdjfadsfjñasdf','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(85,'demo','2','dominiodepruebas.loc2','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(86,'demo','2','dominiodepruebas.loc','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(87,'demo','2','rwerer324234','',47,3,'prueba@prueba.com','','test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es'),(88,'Copy of demo.sexodome.loc','2','fulane.ane.es',NULL,47,3,'prueba@prueba.com',NULL,'test billboard','123 in {domain}','123','123','123','321','123','123','123','title pornstars','title pornstar {pornstar}','description pornstars','description pornstar',0,'#ffffff','#000000','#e7e7e7','#ffffff','#ffd200','#0000ff','#00ff30','#ffa100','#ff2600','#ff0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Live Sex','','http://www.terra.es');
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
) ENGINE=InnoDB AUTO_INCREMENT=143314 DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=25989 DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (3,'eduardo','eduardo.rzeronte@gmail.com','$2y$10$SAw7tXWkVsEJZ4aaVDyzYOIBqgtqg/dq2fobc5iEh9UZR1ic/1j5.','e8AMC53BvhO3vdeZTft1y06gie5raNQVGlg3ecTk8fPcehJKWn5Ein9NCWAj','2016-05-30 20:24:22','2016-09-08 12:39:07'),(4,'jose','jose@jose.es','$2y$10$SAw7tXWkVsEJZ4aaVDyzYOIBqgtqg/dq2fobc5iEh9UZR1ic/1j5.',NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
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

-- Dump completed on 2017-01-23  5:26:16

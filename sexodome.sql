/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50628
Source Host           : localhost:3306
Source Database       : universo

Target Server Type    : MYSQL
Target Server Version : 50628
File Encoding         : 65001

Date: 2016-06-05 18:28:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ads
-- ----------------------------
DROP TABLE IF EXISTS `ads`;
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

-- ----------------------------
-- Records of ads
-- ----------------------------

-- ----------------------------
-- Table structure for analytics
-- ----------------------------
DROP TABLE IF EXISTS `analytics`;
CREATE TABLE `analytics` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `visitors` int(10) DEFAULT NULL,
  `pageviews` int(10) DEFAULT NULL,
  `site_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=250 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of analytics
-- ----------------------------

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` int(10) DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  `site_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=814 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of categories
-- ----------------------------

-- ----------------------------
-- Table structure for categories_translations
-- ----------------------------
DROP TABLE IF EXISTS `categories_translations`;
CREATE TABLE `categories_translations` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `category_id` int(10) DEFAULT NULL,
  `language_id` int(10) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `permalink` varchar(255) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5642 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of categories_translations
-- ----------------------------

-- ----------------------------
-- Table structure for channels
-- ----------------------------
DROP TABLE IF EXISTS `channels`;
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

-- ----------------------------
-- Records of channels
-- ----------------------------
INSERT INTO `channels` VALUES ('1', 'PornHub', 'http://www.pornhub.com/pornhub.com-db.zip', 'pornhub.com-db.csv', 'pornhub', '\\App\\Feeds\\PornHubFeed', '1', '2209660', 'pornhub.png', '1');
INSERT INTO `channels` VALUES ('2', 'YouPorn', 'http://www.youporn.com/YouPorn-Embed-Videos-Dump.zip', 'YouPorn-Embed-Videos-Dump.csv', 'youporn', '\\App\\Feeds\\YouPornFeed', '1', '585614', 'youporn.png', '1');
INSERT INTO `channels` VALUES ('3', 'xHamster', 'http://partners.xhamster.com/2export.php?ch=!&cnt=3&tcnt=5&rt=3&url=on&dlm=%7C&tl=on&vid=on&ttl=on&chs=on&sz=on', 'xhamster.dump', 'xhamster', '\\App\\Feeds\\xHamsterFeed', '0', '5001', 'xhamster.png', '0');
INSERT INTO `channels` VALUES ('4', 'Tube8', 'http://www.tube8.com/api.php?action=webMaster&orientation=straight&categories=6,13,12,7,4,11,5,22,1,14,8,2,10,3&count=100&size=small&rating=80000&delimiter=%7C&fields=url,categories,rating,title,tags,duration,pornstars,thumbnail&period=all&order=tr&format=CSV&utm_source=paid&utm_medium=hubtraffic&utm_campaign=hubtraffic_assassinsporn', 'tube8.dump', 'tube8', '\\App\\Feeds\\Tube8Feed', '0', '100', 'tube8.png', '0');

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8_unicode_ci NOT NULL,
  `queue` text COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
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

-- ----------------------------
-- Records of jobs
-- ----------------------------

-- ----------------------------
-- Table structure for languages
-- ----------------------------
DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `code` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of languages
-- ----------------------------
INSERT INTO `languages` VALUES ('1', 'Spanish', '1', 'es');
INSERT INTO `languages` VALUES ('2', 'English', '1', 'en');
INSERT INTO `languages` VALUES ('3', 'Italian', '1', 'it');
INSERT INTO `languages` VALUES ('4', 'German', '1', 'de');
INSERT INTO `languages` VALUES ('5', 'French', '1', 'fr');
INSERT INTO `languages` VALUES ('6', 'Brazilian', '1', 'br');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('2014_10_12_000000_create_users_table', '1');
INSERT INTO `migrations` VALUES ('2014_10_12_100000_create_password_resets_table', '2');
INSERT INTO `migrations` VALUES ('2016_05_30_222208_create_jobs_table', '2');
INSERT INTO `migrations` VALUES ('2016_05_30_224838_create_failed_jobs_table', '3');

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for scene_category
-- ----------------------------
DROP TABLE IF EXISTS `scene_category`;
CREATE TABLE `scene_category` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `category_id` int(10) DEFAULT NULL,
  `scene_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43183 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of scene_category
-- ----------------------------

-- ----------------------------
-- Table structure for scene_tag
-- ----------------------------
DROP TABLE IF EXISTS `scene_tag`;
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

-- ----------------------------
-- Records of scene_tag
-- ----------------------------

-- ----------------------------
-- Table structure for scene_translations
-- ----------------------------
DROP TABLE IF EXISTS `scene_translations`;
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

-- ----------------------------
-- Records of scene_translations
-- ----------------------------

-- ----------------------------
-- Table structure for scenes
-- ----------------------------
DROP TABLE IF EXISTS `scenes`;
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

-- ----------------------------
-- Records of scenes
-- ----------------------------

-- ----------------------------
-- Table structure for scenes_clicks
-- ----------------------------
DROP TABLE IF EXISTS `scenes_clicks`;
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

-- ----------------------------
-- Records of scenes_clicks
-- ----------------------------

-- ----------------------------
-- Table structure for sentences
-- ----------------------------
DROP TABLE IF EXISTS `sentences`;
CREATE TABLE `sentences` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sentence` text,
  `url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5267 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of sentences
-- ----------------------------

-- ----------------------------
-- Table structure for site_category
-- ----------------------------
DROP TABLE IF EXISTS `site_category`;
CREATE TABLE `site_category` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `site_id` int(10) DEFAULT NULL,
  `category_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of site_category
-- ----------------------------

-- ----------------------------
-- Table structure for site_tag
-- ----------------------------
DROP TABLE IF EXISTS `site_tag`;
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

-- ----------------------------
-- Records of site_tag
-- ----------------------------

-- ----------------------------
-- Table structure for sites
-- ----------------------------
DROP TABLE IF EXISTS `sites`;
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

-- ----------------------------
-- Records of sites
-- ----------------------------

-- ----------------------------
-- Table structure for tag_translations
-- ----------------------------
DROP TABLE IF EXISTS `tag_translations`;
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

-- ----------------------------
-- Records of tag_translations
-- ----------------------------

-- ----------------------------
-- Table structure for tags
-- ----------------------------
DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` int(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `site_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11159 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tags
-- ----------------------------

-- ----------------------------
-- Table structure for tags_clicks
-- ----------------------------
DROP TABLE IF EXISTS `tags_clicks`;
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

-- ----------------------------
-- Records of tags_clicks
-- ----------------------------

-- ----------------------------
-- Table structure for titles
-- ----------------------------
DROP TABLE IF EXISTS `titles`;
CREATE TABLE `titles` (
  `id` int(19) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `language_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1127 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of titles
-- ----------------------------

-- ----------------------------
-- Table structure for tweets
-- ----------------------------
DROP TABLE IF EXISTS `tweets`;
CREATE TABLE `tweets` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `description` varchar(140) DEFAULT NULL,
  `scene_id` int(10) DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tweets
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
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

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('3', 'eduardo', 'eduardo.rzeronte@gmail.com', '$2y$10$SAw7tXWkVsEJZ4aaVDyzYOIBqgtqg/dq2fobc5iEh9UZR1ic/1j5.', 'eiO7dnQgM2aDTxmxYnq1sAYZebEewKehFfuzYLft5X765JWXzlFzuRJp0mFp', '2016-05-30 22:24:22', '2016-06-05 01:45:38');

-- ----------------------------
-- Table structure for words
-- ----------------------------
DROP TABLE IF EXISTS `words`;
CREATE TABLE `words` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `word` varchar(255) DEFAULT NULL,
  `language_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6160 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of words
-- ----------------------------

-- ----------------------------
-- Table structure for words_synonym
-- ----------------------------
DROP TABLE IF EXISTS `words_synonym`;
CREATE TABLE `words_synonym` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `word_id` int(10) DEFAULT NULL,
  `word` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25415 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of words_synonym
-- ----------------------------

-- ----------------------------
-- Table structure for zones
-- ----------------------------
DROP TABLE IF EXISTS `zones`;
CREATE TABLE `zones` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zones
-- ----------------------------
INSERT INTO `zones` VALUES ('1', 'Home', null, 'home');
INSERT INTO `zones` VALUES ('2', 'Search', null, 'search');
INSERT INTO `zones` VALUES ('3', 'Video', null, 'video');

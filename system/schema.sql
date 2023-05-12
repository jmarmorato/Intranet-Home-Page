DROP TABLE IF EXISTS `caldav`;
CREATE TABLE `caldav` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `summary` varchar(1000) NOT NULL,
  `start` int(255) NOT NULL,
  `end` int(255) NOT NULL,
  `color` varchar(100) NOT NULL,
  `calendar` varchar(100) NOT NULL,
  `recurring` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `nws_alerts`;
CREATE TABLE `nws_alerts` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `alert` varchar(255) NOT NULL,
  `description` TEXT,
  `instruction` TEXT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `nws_current`;
CREATE TABLE `nws_current` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `text_description` varchar(255) NOT NULL,
  `temperature` varchar(255) NOT NULL,
  `icon` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `nws_forecast`;
CREATE TABLE `nws_forecast` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(75) NOT NULL,
  `icon` varchar(1000) NOT NULL,
  `is_daytime` int(1) NOT NULL,
  `temperature` int(4) NOT NULL,
  `forecast` varchar(1000) NOT NULL,
  `detailed` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `piwigo`;
CREATE TABLE `piwigo` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(1000) NOT NULL,
  `comment` varchar(1000) DEFAULT NULL,
  `href` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `rss`;
CREATE TABLE `rss` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `feed` varchar(1000) NOT NULL,
  `title` varchar(1000) NOT NULL,
  `href` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

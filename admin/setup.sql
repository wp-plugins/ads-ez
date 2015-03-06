CREATE TABLE IF NOT EXISTS `{prefix}administrator` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `{prefix}banners` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `md5` varchar(32) NOT NULL UNIQUE,
  `file` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `target` varchar(255) NOT NULL,
  `category` int(4) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `width` int(4) NOT NULL,
  `height` int(4) NOT NULL,
  `size` varchar(9) NOT NULL,
  `weight` int(4) NOT NULL  DEFAULT 100,
  `expiry` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `{prefix}htmlAds` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL UNIQUE,
  `html` text NOT NULL,
  `category` int(4) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `width` int(4) NOT NULL,
  `height` int(4) NOT NULL,
  `size` varchar(9) NOT NULL,
  `weight` int(4) NOT NULL DEFAULT 100,
  `expiry` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `{prefix}categories` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL UNIQUE,
  `comment` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

INSERT IGNORE INTO `{prefix}categories` (`id`, `name`, `comment`, `active`)
VALUES
	(1,'category-1','Default category 1', 1),
	(2,'category-2','Default category 2', 1),
	(3,'category-3','Default category 3', 1);

CREATE TABLE `{prefix}options_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL UNIQUE,
  `value` text NOT NULL,
  `desc` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

FLUSH TABLES

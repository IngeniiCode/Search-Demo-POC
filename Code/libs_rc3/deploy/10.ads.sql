
DROP TABLE IF EXISTS `adsFound`;
CREATE TABLE `adsFound` (
  `itemId` char(22) NOT NULL DEFAULT '0',
  `url` varchar(512) NOT NULL DEFAULT 'Search',
  `found` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY (`itemId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


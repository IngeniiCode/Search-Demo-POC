

DROP TABLE IF EXISTS `adsClicked`;
CREATE TABLE `adsClicked` (
  `ip` char(16) NOT NULL DEFAULT '',
  `id` char(22) NOT NULL DEFAULT ' -- guest -- ',
  `itemId` char(22) NOT NULL DEFAULT ' -- NO ID FOUND --',
  `lastClicked` timestamp NOT NULL,
  UNIQUE KEY (`ip`,`id`,`itemId`),
  KEY(`itemId`),
  KEY(`lastClicked`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `adsClickedData`;
CREATE TABLE `adsClickedData` (
  `itemId` char(22) NOT NULL DEFAULT ' -- NO ID FOUND --',
  `json` varchar(1024) NOT NULL,
  UNIQUE KEY (`itemId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


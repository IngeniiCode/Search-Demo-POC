
rename table searchSaved to searchSaved_Old;

DROP TABLE IF EXISTS `searchSaved`;
CREATE TABLE `searchSaved` (
  `srchKey` char(22) NOT NULL DEFAULT '',
  `searchId` char(22) NOT NULL DEFAULT '',
  `id` char(22) NOT NULL DEFAULT '0',
  `name` varchar(127) NOT NULL DEFAULT 'Search',
  `saved` timestamp,
  UNIQUE KEY (`srchKey`),
  KEY(`searchId`),
  KEY(`id`),
  KEY(`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


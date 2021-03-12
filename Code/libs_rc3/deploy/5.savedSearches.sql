
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

DROP TABLE IF EXISTS `searchDefaults`;
CREATE TABLE `searchDefaults` (
  `searchId` char(22) NOT NULL DEFAULT '',
  `name` varchar(127) NOT NULL DEFAULT 'Search', 
  `search` text,
  `created` timestamp,
  UNIQUE KEY (`searchId`),
  KEY(`searchId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO searchDefaults SET searchId='car',name='Car Search',search='{"type":"car","mode":"cta","titleOnly":true}',created=NOW();
INSERT INTO searchDefaults SET searchId='motorcycle',name='Motorcycle Search',search='{"type":"motorcycle","mode":"mca","titleOnly":true}',created=NOW();
INSERT INTO searchDefaults SET searchId='truck',name='Truck Search',search='{"type":"truck","mode":"cta","titleOnly":true}',created=NOW();
INSERT INTO searchDefaults SET searchId='boat',name='Boat Search',search='{"type":"boat","mode":"boo","titleOnly":true}',created=NOW();

DROP TABLE IF EXISTS `searchDownloads`;
CREATE TABLE `searchDownloads` (
  `searchId` char(22) NOT NULL DEFAULT '',
  `id` char(22) NOT NULL DEFAULT '0',
  `downloaded` timestamp,
  KEY(`searchId`),
  KEY(`id`),
  KEY(`downloaded`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

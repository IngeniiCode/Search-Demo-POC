
/* move current table aside */
rename table adsClicked to adsClickedBKP;

DROP TABLE IF EXISTS `adsClicked`;
CREATE TABLE `adsClicked` (
  `ip` char(16) NOT NULL DEFAULT '',
  `id` char(22) NOT NULL DEFAULT ' -- guest -- ',
  `itemId` char(22) NOT NULL DEFAULT ' -- NO ID FOUND --',
  `clicked` timestamp NOT NULL,
  KEY(`ip`),
  KEY(`itemId`),
  KEY(`clicked`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


/* copy the data back */
INSERT IGNORE INTO adsClicked SELECT * FROM adsClickedBKP;



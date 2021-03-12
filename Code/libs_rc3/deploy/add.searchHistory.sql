
DROP TABLE IF EXISTS `searchHistory`;
CREATE TABLE `searchHistory` (
  `ip` varchar(16) NOT NULL,
  `when` timestamp,
  `terms` varchar(64),
  `search` varchar(255),
  `useragent` varchar(128) NOT NULL,
  KEY (`ip`),
  KEY (`when`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




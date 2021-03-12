
DROP TABLE IF EXISTS `userAgent`;
CREATE TABLE `userAgent` (
  `agentId` char(22) NOT NULL,
  `useragent` varchar(254) NOT NULL,
  UNIQUE KEY(`agentId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `refererSite`;
CREATE TABLE `refererSite` (
  `refId` char(22) NOT NULL,
  `url` text NOT NULL,
  UNIQUE KEY(`refId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



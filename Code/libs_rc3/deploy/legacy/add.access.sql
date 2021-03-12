RENAME TABLE accessLog to accessLog2;

DROP TABLE IF EXISTS `accessLog`;
CREATE TABLE `accessLog` (
  `ip` char(16) NOT NULL,
  `id` char(22) NOT NULL DEFAULT '';
  `when` timestamp,
  `page` varchar(64),
  `useragent` varchar(128) NOT NULL,
  KEY(`ip`),
  KEY(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




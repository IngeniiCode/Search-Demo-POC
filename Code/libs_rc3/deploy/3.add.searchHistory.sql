RENAME table searchHistory to searchHistory3;

DROP TABLE IF EXISTS `searchHistory`;
CREATE TABLE `searchHistory` (
  `ip` char(16) NOT NULL,
  `id` char(22) NOT NULL DEFAULT '',
  `dtime` timestamp,
  `type`  varchar(8),
  `terms` varchar(64),
  `searchId` char(22),
  KEY (`ip`),
  KEY (`id`),
  KEY (`dtime`),
  KEY (`searchId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `searchRequest`;
CREATE TABLE `searchRequest` (
  `searchId` char(22) NOT NULL DEFAULT '',
  `search` varchar(512),
  UNIQUE KEY (`searchId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO searchRequest SET searchId='car',search='{"type":"car","mode":"cta","titleOnly":true}';
INSERT INTO searchRequest SET searchId='motorcycle',search='{"type":"motorcycle","mode":"mca","titleOnly":true}';
INSERT INTO searchRequest SET searchId='truck',search='{"type":"truck","mode":"cta","titleOnly":true}';
INSERT INTO searchRequest SET searchId='boat',search='{"type":"boat","mode":"boo","titleOnly":true}';




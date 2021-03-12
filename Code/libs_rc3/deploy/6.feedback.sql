
DROP TABLE IF EXISTS `feedback`;
CREATE TABLE `feedback` (
  `feedbackId` char(22) NOT NULL DEFAULT '',
  `id` char(22) NOT NULL DEFAULT '0',
  `fbsent` timestamp,
  `data` text,
  KEY (`feedbackId`),
  KEY(`id`),
  KEY(`fbsent`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


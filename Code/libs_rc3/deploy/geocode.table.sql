
DROP TABLE IF EXISTS `geoCode`;
CREATE TABLE `geoCode` (
    `zip` char(5) primary key,
    `city` varchar(64),
    `state` char(2),
    `latitude` varchar(12),
    `longitude` varchar(12),
    `timezone` int,
    `dst` char(1)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

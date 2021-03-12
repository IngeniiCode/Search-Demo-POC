
DROP TABLE IF EXISTS `searchZone`;
CREATE TABLE `searchZone` (
  `zone_id` int NOT NULL,
  `zone_name` varchar(32) NOT NULL DEFAULT '',
  `zone_desc` varchar(127) NOT NULL DEFAULT '',
  KEY (`zone_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO searchZone (zone_id,zone_name,zone_desc) VALUES
(1,'Zone 1','Zone 1'),
(2,'Zone 2','Zone 2'),
(3,'Zone 3','Zone 3'),
(4,'Zone 4','Zone 4'),
(5,'Zone 5','Zone 5'),
(6,'Zone 6','Zone 6'),
(7,'Zone 7','Zone 7');

DROP TABLE IF EXISTS `state`;
CREATE TABLE `state` (
  `state_id` int NOT NULL,
  `zone_id` int NOT NULL,
  `state_code` char(2) NOT NULL DEFAULT '  ',
  `state_name` varchar(32) NOT NULL DEFAULT '',
  KEY (`state_id`),
  KEY (`zone_id`),
  KEY (`state_code`),
  KEY (`state_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO state (state_id,state_name,state_code,zone_id) VALUES
(1 ,'Alabama','AL', 5 ),
(2,'Alaska','AK',2),
(3,'Arizona','AZ', 1 ),
(4,'Arkansas','AR', 5 ),
(5,'California','CA',1),
(6,'Colorado','CO',3 ),
(7,'Connecticut','CT', 7),
(8,'Delaware','DE', 6 ),
(9 ,'Florida','FL', 5 ),
(10 ,'Georgia','GA', 5 ),
(11 ,'Hawaii','HI', 1 ),
(12 ,'Idaho','ID', 2 ),
(13 ,'Illinois','IL', 4 ),
(14 ,'Indiana','IN', 6 ),
(15 ,'Iowa','IA', 4 ),
(16 ,'Kansas','KS', 3 ),
(17,'Kentucky','KY', 6 ),
(18 ,'Louisiana','LA', 5 ),
(19 ,'Maine','ME', 7 ),
(20 ,'Maryland','MD', 6 ),
(21,'Massachusetts','MA', 7 ),
(22 ,'Michigan','MI', 6 ),
(23 ,'Minnesota','MN',4 ),
(24,'Mississippi','MS', 5 ),
(25,'Missouri','MO',4 ),
(26 ,'Montana','MT', 2 ),
(27 ,'Nebraska','NE', 4 ),
(28 ,'Nevada','NV', 1 ),
(29 ,'New Hampshire','NH', 7 ),
(30 ,'New Jersey','NJ', 7 ),
(31 ,'New Mexico','NM', 3 ),
(32 ,'New York','NY', 7 ),
(33 ,'North Carolina','NC', 6 ),
(34 ,'North Dakota','ND', 4 ),
(35 ,'Ohio','OH', 6 ),
(36 ,'Oklahoma','OK', 3 ),
(37 ,'Oregon','OR', 2 ),
(38 ,'Pennsylvania[','PA', 7 ),
(39 ,'Rhode Island','RI', 7 ),
(40 ,'South Carolina','SC', 5 ),
(41 ,'South Dakota','SD', 4 ),
(42 ,'Tennessee','TN', 6 ),
(43 ,'Texas','TX', 3 ),
(44 ,'Utah','UT',1 ),
(45 ,'Vermont','VT', 7 ),
(46 ,'Virginia','VA', 6 ),
(47 ,'Washington','WA', 2 ),
(48 ,'West Virginia','WV', 6 ),
(49 ,'Wisconsin','WI', 4 ),
(50 ,'Wyoming','WY', 2 ),
(51 ,'Washington D.C.','DC', 6);

DROP TABLE IF EXISTS `searchRegionCL`;
CREATE TABLE `searchRegionCL` (
  `cl_region_key` varchar(32) NOT NULL,
  `region_name` varchar(96) NOT NULL DEFAULT '',
  `state_id` char(2) NOT NULL DEFAULT '  ',
  `zone_id` int NOT NULL,
  `lat` float NOT NULL DEFAULT 0.00,
  `lon` float NOT NULL DEFAULT 0.00,
  KEY (`cl_region_key`),
  KEY (`state_id`),
  KEY (`zone_id`),
  KEY (`state_id`),
  KEY (`lat`),
  KEY (`lon`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

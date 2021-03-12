
DROP TABLE IF EXISTS `itemMFG`;
CREATE TABLE `itemMFG` (
  `mfgId` int(11) NOT NULL,
  `mfgName` varchar(96) NOT NULL,
  `mfgData` text,
  PRIMARY KEY (`mfgId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- SCRUB DATA --
DELETE FROM itemMFG WHERE mfgId IN(10,11);
-- ADD A BUGUS MFG --
insert into itemMFG (mfgId,mfgName,mfgData) VALUES
(10,'ABC Helmet Co.',''),
(11,'ACME Racing Helmets.','');



DROP TABLE IF EXISTS `item`;
CREATE TABLE `item` (
  `itemId` int(11) NOT NULL,
  `itemModel` varchar(96) NOT NULL,
  `typeId` int(11) NOT NULL,
  `mfgId`  int(11) NOT NULL,
  PRIMARY KEY (`itemId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- SCRUB DATA --
DELETE FROM item WHERE mfgId IN(10,11);

insert  into item (itemId,itemModel,typeId,mfgId) VALUES
(1010,'Racer X',3,10),
(1011,'Street King',3,10),
(1012,'Blazer',3,11);



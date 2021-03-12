
--
-- Table structure for table `surveyResponseSummary`
--

DROP TABLE IF EXISTS `surveyResponseSummary`;
CREATE TABLE `surveyResponseDetails` (
  `responseId`  varchar(26) not null primary key,
  `surveyId`  int(4) NOT NULL,
  `itemId`    int(11) NOT NULL,
  `overallRating`  int(4) NOT NULL,
  KEY `surveyIdx` (`surveyId`),
  KEY `itemIdx` (`itemId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


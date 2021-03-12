
--
-- Table structure for table `survey`
--

DROP TABLE IF EXISTS `surveyResponse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `surveyResponse` (
  `when`  timestamp DEFAULT '0000-00-00 00:00:00',
  `ip`    varchar(18) DEFAULT NULL,
  `data`  text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


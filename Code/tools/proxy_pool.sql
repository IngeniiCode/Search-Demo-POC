--
-- Table structure for table `proxyPool`
--

DROP TABLE IF EXISTS `proxyPool`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proxyPool` (
  `host` varchar(32) NOT NULL DEFAULT '0.0.0.0',
  `port` int(8) NOT NULL DEFAULT 80,
  `protocol` enum('http','socks','socks5') NOT NULL DEFAULT 'http',
  `status` enum('new','up','down') NOT NULL DEFAULT 'new',
  `cl_safe` enum('yes','no','ut') NOT NULL DEFAULT 'ut',
  `anon` enum('yes','no','ut') NOT NULL DEFAULT 'ut',
  `added_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tested_on` TIMESTAMP NULL,
  UNIQUE KEY `proxy_id` (`host`,`port`),
  KEY `status` (`status`),
  KEY `anon` (`anon`),
  KEY `protocol` (`protocol`),
  KEY `cl_safe` (`cl_safe`),
  KEY `added_on` (`added_on`),
  KEY `tested_on` (`tested_on`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;




#DROP TABLE IF EXISTS `profile`;
CREATE TABLE `profile` (
  `id`  char(22) NOT NULL DEFAULT '',
  `fb_id`  int NULL,
  `status` enum('pending','verified','expired','blocked') NOT NULL default 'pending',
  `uname` varchar(255) NOT NULL,
  `pswdenc` varchar(48) NOT NULL,
  `added` timestamp NOT NULL,
  `verified` timestamp NOT NULL,
  `updated` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY (`fb_id`),
  KEY (`uname`),
  KEY (`status`),
  KEY (`pswdenc`),
  KEY (`added`),
  KEY (`verified`),
  KEY (`updated`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


#DROP TABLE IF EXISTS `profileType`;
CREATE TABLE `profileType` (
  `id`  char(22) NOT NULL DEFAULT '',
  `type` enum('free','basic','premium','pro','admin','api') NOT NULL DEFAULT 'free',
  `updated` timestamp NOT NULL,
  `updated_id` char(22) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


#DROP TABLE IF EXISTS `profileVerify`;
CREATE TABLE `profileVerify` (
  `id`  char(22) NOT NULL DEFAULT '',
  `type` enum('verify_account','pwd_reset') NOT NULL DEFAULT 'verify_account',
  `key` char(43) NOT NULL DEFAULT '',
  `verified` timestamp NOT NULL,
  `exipres` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY (`type`),
  KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#DROP TABLE IF EXISTS `logins`;
CREATE TABLE `logins` (
  `ip` varchar(16) NOT NULL,
  `when` timestamp,
  `username` varchar(255),
  `data`  text,
  `useragent` varchar(128) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


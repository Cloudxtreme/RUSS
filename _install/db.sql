--
-- Table structure for table `active_guests`
--

CREATE TABLE IF NOT EXISTS `active_guests` (
  `ip` varchar(15) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `active_users`
--

CREATE TABLE IF NOT EXISTS `active_users` (
  `username` varchar(30) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `banned_users`
--

CREATE TABLE IF NOT EXISTS `banned_users` (
  `username` varchar(30) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `banned_ip` (
  `ip` varchar(30) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(30) NOT NULL,
  `password` varchar(40) default NULL,
  `usersalt` varchar(8) NOT NULL,
  `userid` varchar(32) default NULL,
  `userlevel` tinyint(1) unsigned NOT NULL,
  `email` varchar(50) default NULL,
  `name` varchar(50) default NULL,
  `surname` varchar(50) default NULL,
  `twitter` varchar(50) default NULL,
  `facebook` varchar(50) default NULL,
  `google` varchar(50) default NULL,
  `linkedin` varchar(50) default NULL,
  `website` varchar(50) default NULL,
  `icq` varchar(50) default NULL,
  `skype` varchar(50) default NULL,
  `gtalk` varchar(50) default NULL,
  `phone` varchar(50) default NULL,
  `timestamp` int(11) unsigned NOT NULL,
  `actkey` varchar(35) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `regdate` int(11) unsigned NOT NULL,
  `privacy` varchar(5) NOT NULL default 'Y',
  `online` varchar(5) NOT NULL default 'Y',
  `showcomments` varchar(5) NOT NULL default 'Y',
  `allowcalls` varchar(5) NOT NULL default 'N',
  `signature` TEXT NOT NULL default '',
  PRIMARY KEY  (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `messages` (
	`id` int(11) NOT NULL auto_increment,
	`user` varchar(100) NOT NULL default '',
	`towho` varchar(100) NOT NULL default '',
	`msg` varchar(255) NOT NULL default '',
	`status` varchar(10) NOT NULL default '',
    `ctime` varchar(30) NOT NULL default '',
	`reporter` varchar(100) NOT NULL default '',
	PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `comments` (
	`id` int(11) NOT NULL auto_increment,
	`user` varchar(100) NOT NULL default '',
	`url` varchar(100) NOT NULL default '',
	`msg` varchar(255) NOT NULL default '',
	`status` varchar(10) NOT NULL default '',
    `ctime` varchar(30) NOT NULL default '',
	`reporter` varchar(100) NOT NULL default '',
	PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tickets` (
	`id` int(11) NOT NULL auto_increment,
	`tid` varchar(50) NOT NULL default '',
	`user` varchar(100) NOT NULL default '',
	`email` varchar(100) NOT NULL default '',
    `category` varchar(100) NOT NULL default '',
    `timedate` varchar(100) NOT NULL default '',
    `subject` varchar(255) NOT NULL default '',
	`report` TEXT NOT NULL default '',
    `IP` varchar(100) NOT NULL default '',
    `ownermail` varchar(255) NOT NULL default '',
    `techmail` varchar(255) NOT NULL default '',
	PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `meta` (
	`id` int(11) NOT NULL auto_increment,
	`tid` varchar(255) NOT NULL default '',
    `status` varchar(255) NOT NULL default '',
	`tech` varchar(255) NOT NULL default '',
    `assigned` varchar(100) NOT NULL default '',
    `rating` varchar(50) NOT NULL default '',
    `notes` TEXT NOT NULL default '',
    `owner` varchar(100) NOT NULL default '',
	PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

 CREATE TABLE IF NOT EXISTS `product` (
  	`id` int(11) NOT NULL auto_increment,
    `prod` varchar(255) NOT NULL default '',
	`description` varchar(255) NOT NULL default '',
	PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

 CREATE TABLE IF NOT EXISTS `category` (
  	`id` int(11) NOT NULL auto_increment,
    `catprod` varchar(255) NOT NULL default '',
	`catname` varchar(255) NOT NULL default '',
	`catdesc` TEXT NOT NULL default '',
	PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `kdb` (
	`id` int(11) NOT NULL auto_increment,
	`category` varchar(255) NOT NULL default '',
    `question` varchar(255) NOT NULL default '',
	`answer` TEXT NOT NULL default '',
	PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

 CREATE TABLE IF NOT EXISTS `Stat_Day` (
  	`id` int(11) NOT NULL auto_increment,
	`day` varchar(10) NOT NULL default '',
	`user` int(10) NOT NULL default '0',
	`view` int(10) NOT NULL default '0',
	PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `Stat_IPs` (
	`id` int(11) NOT NULL auto_increment,
	`ip` varchar(15) NOT NULL default '',
	`time` int(20) NOT NULL default '0',
	`online` int(20) NOT NULL default '0',
	PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `Stat_Page` (
	`id` int(11) NOT NULL auto_increment,
	`day` varchar(10) NOT NULL default '',
	`page` varchar(255) NOT NULL default '',
	`view` int(10) NOT NULL default '0',
	PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `Stat_Referer` (
	`id` int(11) NOT NULL auto_increment,
	`day` varchar(10) NOT NULL default '',
	`referer` varchar(255) NOT NULL default '',
	`view` int(10) NOT NULL default '0',
	PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `Stat_Keyword` (
	`id` int(11) NOT NULL auto_increment,
	`day` varchar(10) NOT NULL default '',
	`keyword` varchar(255) NOT NULL default '',
	`view` int(10) NOT NULL default '0',
	PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `Stat_Language` (
	`id` int(11) NOT NULL auto_increment,
	`day` varchar(10) NOT NULL default '',
	`language` varchar(2) NOT NULL default '',
	`view` int(10) NOT NULL default '0',
	PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- END STATIC --------------------------------------------------------

    -- --------------------------------------------------------

      --
      -- Table structure for table `configuration`
      --

      CREATE TABLE IF NOT EXISTS `configuration` (
        `config_name` varchar(20) NOT NULL,
        `config_value` varchar(50) NOT NULL,
        KEY `config_name` (`config_name`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

      --
      -- Dumping data for table `configuration`
      --

      INSERT INTO `configuration` (`config_name`, `config_value`) VALUES
      ('ACCOUNT_ACTIVATION', '1'),
      ('TRACK_VISITORS', '1'),
      ('max_user_chars', '30'),
      ('min_user_chars', '5'),
      ('max_pass_chars', '100'),
      ('min_pass_chars', '6'),
      ('EMAIL_FROM_NAME', 'RUMSY'),
      ('EMAIL_FROM_ADDR', 'stoiljkovic@inserbia.info'),
      ('EMAIL_WELCOME', '0'),
      ('SITE_NAME', 'RUMSY'),
      ('SITE_DESC', 'PHP MySQL User Membership System'),
      ('WEB_ROOT', 'http://localhost/RedIcon/RUSS/'),
      ('ENABLE_CAPTCHA', '1'),
      ('ENABLE_COMMENTS', '1'),
      ('ENABLE_MESSAGES', '1'),
      ('ENABLE_CALLS', '1'),
      ('COOKIE_EXPIRE', '100'),
      ('COOKIE_PATH', '/'),
      ('home_page', 'index.php'),
      ('ALL_LOWERCASE', '0'),
      ('USER_TIMEOUT', '10'),
      ('GUEST_TIMEOUT', '5');

      -- --------------------------------------------------------
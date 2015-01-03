<?php
/**
 * OTHER
 */
define("LANGUAGE", "en");				// Default Language ("sr" - Serbian, "en" - English,...)
define("CACHER", 604800);               // Cache Time in seconds: 86400 1 day; 604800 7 days; 2592000 30 days

date_default_timezone_set('Europe/Belgrade'); // Default timezone - http://php.net/manual/en/timezones.php

// DO NOT EDIT BELLOW --------------------------------------------------------------->

/**
 * Database Table Constants - these constants
 * hold the names of all the database tables used
 * in the script.
 */
define("TBL_USERS", "users");
define("TBL_ACTIVE_USERS",  "active_users");
define("TBL_ACTIVE_GUESTS", "active_guests");
define("TBL_BANNED_USERS",  "banned_users");
define("TBL_BANNED_IP",  "banned_ip");
define("TBL_CONFIGURATION", "configuration");
define("TBL_COMMENTS", "comments");
define("TBL_MESSAGES", "messages");
define("TBL_TICKETS", "tickets");
define("TBL_CATEGORY", "category");
define("TBL_KDB", "kdb");
define("TBL_META", "meta"); 
define("TBL_PRODUCTS", "product");
define("VERSIONCT","2015.1Beta");
define("SCRIPTAUTHOR", "REDICON");
define("SCRIPTNAME", "RedIcon User Support System");
define("SHORTNAME", "RUSS");
define("RURL", dirname("http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']) ));
define("RPATH", dirname(dirname(str_replace('\\', '/', __FILE__))."/"));



/**
 * Special Names and Level Constants - the admin
 * page will only be accessible to the user with
 * the admin name and also to those users at the
 * admin user level. Feel free to change the names
 * and level constants as you see fit, you may
 * also add additional level specifications.
 * Levels must be digits between 0-9.
 */
define("ADMIN_NAME", "admin");
define("GUEST_NAME", "Guest");
define("ADMIN_LEVEL", 9);
define("EDITOR_LEVEL", 8);
define("BANNED_LEVEL", 7);
define("GOLD_LEVEL", 6);
define("SILVER_LEVEL", 5);
define("BRONZE_LEVEL", 4);
define("REGUSER_LEVEL", 3);
define("ADMIN_ACT", 2);
define("ACT_EMAIL", 1);
define("GUEST_LEVEL", 0);

/**
 * Timeout Constants - these constants refer to
 * the maximum amount of time (in minutes) after
 * their last page fresh that a user and guest
 * are still considered active visitors.
 */
define("USER_TIMEOUT", 2);
define("GUEST_TIMEOUT", 5);

/** END STATIC **/

/**
 * Database Constants - these constants are required
 * in order for there to be a successful connection
 * to the MySQL database. Make sure the information is
 * correct.
 */

define('DB_SERVER', 'localhost');       // MySQL Server address. Usually localhost
define('DB_USER', 'root');       // MySQL Database username
define('DB_PASS', 'root');       // MySQL Database password
define('DB_NAME', 'RUSS');           // MySQL Database name
define('PATHER', 'http://localhost/RedIcon/RUSS');              // PATH
?>
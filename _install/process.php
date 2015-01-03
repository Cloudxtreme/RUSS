<?php
if(isset($_POST['server']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['name'])) {
    $dbdata = "
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
      ('EMAIL_FROM_NAME', 'RUSS Support'),
      ('EMAIL_FROM_ADDR', '".$_POST['webmail']."'),
      ('EMAIL_WELCOME', '0'),
      ('SITE_NAME', 'RUSS Support (Beta)'),
      ('SITE_DESC', 'PHP MySQL User Support System'),
      ('WEB_ROOT', '".$_POST['url']."/'),
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

      -- --------------------------------------------------------";

$condata = "
/**
 * Database Constants - these constants are required
 * in order for there to be a successful connection
 * to the MySQL database. Make sure the information is
 * correct.
 */

define('DB_SERVER', '".$_POST['server']."');       // MySQL Server address. Usually localhost
define('DB_USER', '".$_POST['username']."');       // MySQL Database username
define('DB_PASS', '".$_POST['password']."');       // MySQL Database password
define('DB_NAME', '".$_POST['name']."');           // MySQL Database name
define('PATHER', '".$_POST['url']."');              // PATH
?>";

    $ret = file_put_contents('./db.sql', $dbdata, FILE_APPEND | LOCK_EX);
    $fet = file_put_contents('../admin/include/constants.php', $condata, FILE_APPEND | LOCK_EX);
    if($ret === false) {
        die('There was an error writing this file');
    }
    else {
        chmod("../admin/include/constants.php", 0644);
        header("Location: installer.php");
    }
}
else {
   die('no post data to process');
}
?>
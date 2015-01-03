<?php
if (!defined('VERSIONCT')) {
    die;
}?>
<?php
  global $langid;
  if(isset($_GET['lang']))
          {
          $langid = $_GET['lang'];
          $_SESSION['langid'] = $langid;
          setcookie('langid', $langid, time() + (3600 * 24 * 30));
          }
    else if(isset($_SESSION['langid']))
          {
          $langid = $_SESSION['langid'];
          }
    else if(isset($_COOKIE['langid']))
          {
          $langid = $_COOKIE['langid'];
          }
    else
          {
          $langid = LANGUAGE;
          }

switch ($langid) {
  case $langid:
  $lang_file = $langid.'.php';
  break;

  default:
  $lang_file = LANGUAGE.'.php';

}

include 'include/lang/'.$lang_file;

?>
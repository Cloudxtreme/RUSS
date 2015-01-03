<?php
if(isSet($_GET['theme']))
{
$theme = $_GET['theme'];

// register the session and set the cookie
$_SESSION['theme'] = $theme;

setcookie('theme', $theme, time() + (3600 * 24 * 30));
}
else if(isSet($_SESSION['theme']))
{
$theme = $_SESSION['theme'];
}
else if(isSet($_COOKIE['theme']))
{
$theme = $_COOKIE['theme'];
}
else
{
$theme = 'cosmo';
}

switch ($theme) {
  case 'cerulean':
  $theme_file = 'cerulean';
  break;

  case 'cosmo':
  $theme_file = 'cosmo';
  break;

  case 'cyborg':
  $theme_file = 'cyborg';
  break;

  case 'darkly':
  $theme_file = 'darkly';
  break;

  case 'flatly':
  $theme_file = 'flatly';
  break;

  case 'journal':
  $theme_file = 'journal';
  break;

  case 'lumen':
  $theme_file = 'lumen';
  break;

  case 'paper':
  $theme_file = 'paper';
  break;

  case 'readable':
  $theme_file = 'readable';
  break;

  case 'sandstone':
  $theme_file = 'sandstone';
  break;

  case 'simplex':
  $theme_file = 'simplex';
  break;

  case 'slate':
  $theme_file = 'slate';
  break;

  case 'spacelab':
  $theme_file = 'spacelab';
  break;

  case 'superhero':
  $theme_file = 'superhero';
  break;

  case 'united':
  $theme_file = 'united';
  break;

  case 'yeti':
  $theme_file = 'yeti';
  break;

  default:
  $theme_file = 'cosmo';

}

echo "<link href='include/cache.php?css=".$theme_file."' rel='stylesheet'>";

?>
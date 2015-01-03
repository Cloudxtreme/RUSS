<?php
    require_once('include/constants.php');
    include_once('include/language.php');
    $iversion = 0;
    $version = VERSIONCT;
    $url = "http://redicon.eu/version/RUSS.txt";
    if ( $fp = @fopen($url, 'r') ) {
      $read = fgetcsv($fp);
      fclose($fp); //always a good idea to close the file connection
    } else {
      $read[0] = $version;
    }
    $updateneeded = ($read[0] > $version) ? 1 : 0;
    $iversion = $read[0];
?>
<?php
if (!defined('VERSIONCT')) {
    die;
}
  include ('versioncontrol.php');
?>
<footer>
    <div class="pull-left">

    <p><a href="http://redicon.eu" target="_blank"><?php echo SCRIPTNAME;?></a></p>

    </div>
    <div class="pull-right text-right">

    <p><?php echo $lang['YOUR_VERSION']; ?>: <strong><?php echo $version; ?></strong> | <?php echo $lang['LATEST_VERSION']; ?>: <strong><?php if ($updateneeded) { echo "<a href='http://redicon.eu/latest' target='_blank'>".$iversion."</a>"; } else { echo $iversion." ".$lang['UPTO_DATE']; } ?></strong></p>
</div>
</footer>
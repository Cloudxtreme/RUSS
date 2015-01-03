<?php
if (!defined('VERSIONCT')) {
    die;
}
$counter = 0; $waitstuff = 0; $waituser = 0; $hold = 0; $progress = 0; $closed = 0; $resolved = 0; $opened = 0; $finished = 0; $rattot = 0; $rate = 0;

$info = $database->showMeta('*');

if (isset($info[0]['id'])) {
foreach ($info as $info) {

            $entry = $database->showTicket($info['tid']);

                if( ($info['rating']) != 0) { $rattot = $rattot + 1; $rate = $rate + $info['rating'];}

                if( ($info['status']) == 1) { $counter = $counter + 1; $waitstuff = $waitstuff + 1; $opened = $opened + 1;}
                else if( ($info['status']) == 2) { $counter = $counter + 1; $waituser = $waituser + 1; $opened = $opened + 1;}
                else if( ($info['status']) == 3) { $counter = $counter + 1; $hold = $hold + 1; $opened = $opened + 1;}
                else if( ($info['status']) == 4) { $counter = $counter + 1; $progress = $progress + 1; $opened = $opened + 1;}
                else if( ($info['status']) == 5) { $counter = $counter + 1; $closed = $closed + 1; $finished = $finished + 1;}
                else if( ($info['status']) == 0) { $counter = $counter + 1; $resolved = $resolved + 1; $finished = $finished + 1;}
            }

}
?>
<div class="positioner"><?php echo $lang['HOME']; ?></div>
<div class="container-fluid">

<div class="row members">
<div class="col-md-4"><div class="alert alert-success" role="alert"><i class="fa fa-users fa-3x pull-left"></i><p><?php echo $lang['MEMBER']; ?> <?php echo $lang['MEMBER']; ?> <?php echo $lang['TOTAL']; ?>: <strong class="pull-right"><?php echo $database->getNumMembers(); ?></strong></p></div></div>
<div class="col-md-4"><div class="alert alert-info" role="alert"><i class="fa fa-user fa-3x pull-left"></i><p><?php echo $lang['MEMBER']; ?> <?php echo $lang['ONLINE']; ?>: <strong class="pull-right"><?php echo $database->num_active_users; ?></strong></p></div></div>
<div class="col-md-4"><div class="alert alert-warning" role="alert"><i class="fa fa-male fa-3x pull-left"></i><p><?php echo $lang['LAST_REGISTERED']; ?>: <strong class="regmoder"><?php echo $database->getLastUserRegisteredName(); ?> @ <?php echo date("m-d-Y, g:i a", $database->getLastUserRegisteredDate()); ?></strong></p></div></div>
</div>

<div class="row members">
<div class="col-md-4"><a href="../ticket.php"><div class="alert alert-success" role="alert"><i class="fa fa-ticket fa-3x pull-left"></i><p><?php echo $lang['TICKETS']; ?> <?php echo $lang['TOTAL']; ?>: <strong class="pull-right"><?php echo $counter; ?></strong></p></div></a></div>
<div class="col-md-4"><a href="../ticket.php"><div class="alert alert-danger" role="alert"><i class="fa fa-tachometer fa-3x pull-left"></i><p><?php echo $lang['TICKETS']; ?> <?php echo $lang['OPENED']; ?> <?php echo $lang['TOTAL']; ?>: <strong class="pull-right"><?php echo $opened; ?></strong></p></div></a></div>
<div class="col-md-4"><a href="../ticket.php"><div class="alert alert-warning" role="alert"><i class="fa fa-thumbs-up fa-3x pull-left"></i><p><?php echo $lang['AVERAGE_RATE']; ?>: <strong class="pull-right"><?php if (($rate == 0) or ($rattot == 0)) {echo "/";} else {echo round($rate / $rattot, 2)." / 5";} ?></strong></p></div></a></div>

</div>

<div class="row">
<!-- STATS -->
        <?php $style = "dark"; // Counter Style "dark" or "light"
        $show = "totally"; // Counter shows "totally"  or "last24h"  visitors
        $size = "big"; // Size of the counter "small" or "big"

        $reload=3 * 60 * 60; // Reload lock in seconds (3 * 60 * 60 => 3 hours)
        $online=3*60; // online time in seconds (3 * 60 => 3 minutes)
        $oldentries=30; // delete Visitor infos after x days (7 => 7 days)
        $db_prefix = 'Stat_';
        //
        // End of settings
        //

        // connect to database
        $serverID = @mysql_connect(DB_SERVER, DB_USER, DB_PASS);
        if(!$serverID) {echo "The DB server is not reachable!"; exit;}
        $datenbank = @mysql_select_db(DB_NAME, $serverID);
        if(!$datenbank) {echo "The database was not found!"; exit;}
        ?>

          <div class="col-md-6">
            <h3><?php echo $lang['ONE_VIEW']; ?></h3>
        	<table width="100%" border="0" cellpadding="5" cellspacing="0" class="oneview">
              <tr valign="top">
              <?PHP
        	  $abfrage=mysql_query("select sum(user),sum(view) from ".$db_prefix."Day");
        	  $visitors=mysql_result($abfrage,0,0);
        	  $visits=mysql_result($abfrage,0,1);
        	  mysql_free_result($abfrage);
        	  echo "<td width=\"30%\">Visitors</td><td width=\"20%\">$visitors</td>\n";
        	  echo "<td width=\"30%\">Visits</td><td width=\"20%\">$visits</td>\n";
        	  ?>
        	  </tr>
        	  <tr valign="top">
        	  <?PHP
        	  // Online
        	  $time = time();
        	  $isonline=$time-(3*60);  // 3 Minuten Online Zeit
        	  $abfrage=mysql_query("select count(id) from ".$db_prefix."IPs where online>='$isonline'");
        	  $online=mysql_result($abfrage,0,0);
        	  mysql_free_result($abfrage);
        	  echo "<td>Online</td><td>$online</td>\n";
        	  echo "<td>&nbsp;</td><td>&nbsp;</td>\n";
        	  ?>
        	  </tr>
        	  <tr valign="top">
        	  <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
        	  </tr>
        	  <tr valign="top">
        	  <?PHP
        	  // Bounce
        	  $abfrage=mysql_query("select count(id) from ".$db_prefix."IPs");
        	  $total=mysql_result($abfrage,0,0);
              if ($total == 0) {$total = 1;}
        	  mysql_free_result($abfrage);
        	  $abfrage=mysql_query("select count(id) from ".$db_prefix."IPs where online=time");
        	  $onepage=mysql_result($abfrage,0,0);
        	  mysql_free_result($abfrage);
        	  echo "<td>Bounce</td><td>".round(($onepage/$total)*100,2)."%</td>\n";
        	  // Page/User and 7 days averange
        	  $from_day=date("Y.m.d",$time  -(7*24*60*60));
        	  $to_day=date("Y.m.d",$time  - (24*60*60)); // <= ohne heute
        	  $abfrage=mysql_query("select AVG(user),(sum(view)/sum(user)) from ".$db_prefix."Day where day>='$from_day' AND day<='$to_day'");
        	  $avg_7=round(mysql_result($abfrage,0,0),2);
        	  $page_user=round(mysql_result($abfrage,0,1),1);
        	  mysql_free_result($abfrage);
        	  echo "<td>Page/Visitor</td><td>$page_user</td>\n";
        	  ?>
        	  </tr>
        	  <tr valign="top">
        	  <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
        	  </tr>
        	  <tr valign="top">
        	  <?PHP
        	  echo"		<td>&Oslash; 7 days</td>\n";
        	  echo"		<td>$avg_7</td>\n";
        	  // 30 days average
        	  $from_day=date("Y.m.d",$time -(30*24*60*60));
        	  $to_day=date("Y.m.d",$time - (24*60*60)); // <= ohne heute
        	  $abfrage=mysql_query("select AVG(user) from ".$db_prefix."Day where day>='$from_day' AND day<='$to_day'");
        	  $avg_30=round(mysql_result($abfrage,0,0),2);
        	  mysql_free_result($abfrage);
        	  echo"		<td>&Oslash; 30 days</td>\n";
        	  echo"		<td>$avg_30</td>\n";
        	  ?>
        	  </tr>
        	  <tr valign="top">
        	  <?PHP

        	  $sel_timestamp = mktime(0, 0, 0, date("n"), date("j"), date("Y"));
        	  $sel_tag = date("Y.m.d",$sel_timestamp);
        	  $abfrage=mysql_query("select sum(user) from ".$db_prefix."Day where day='$sel_tag'");
        	  $today=mysql_result($abfrage,0,0);
        	  if ($today=="") $today=0;
        	  mysql_free_result($abfrage);
        	  echo "<td>Today</td><td>$today</td>\n";

        	  $anfangTag = mktime(0, 0, 0, date('n'), date('j'), date('Y')) - 24*60*60 ;
        	  $endeTag = $time - 24*60*60 ;
        	  $abfrage=mysql_query("select count(id) from ".$db_prefix."IPs where time>='$anfangTag' AND time<=$endeTag");
        	  $yesterday=mysql_result($abfrage,0,0);
        	  mysql_free_result($abfrage);
        	  echo "<td>Yesterday (".date("G:i",$time).")</td><td>$yesterday</td>\n";
        	  ?>
        	  </tr>
            </table>
          </div>
          <div class="col-md-6">
            <h3><?php echo $lang['LAST_24']; ?> </h3>
        	<table height="200" width="100%" cellpadding="0" cellspacing="0" align="right">
        	<tr valign="bottom" height="180">
        	<?PHP

        	$bar_nr=0;
        	$bar_mark="";
        	for($Stunde=23; $Stunde>=0; $Stunde--)
        		{
        		$anfangStunde = mktime(date("H")-$Stunde, 0, 0, date("n"), date("j"), date("Y")) ;
        		$endeStunde = mktime(date("H")-$Stunde, 59, 59, date("n"), date("j"), date("Y")) ;
        		$abfrage=mysql_query("select count(id) from ".$db_prefix."IPs where time>='$anfangStunde' AND time<=$endeStunde");
        		$User=mysql_result($abfrage,0,0);
        		mysql_free_result($abfrage);

        		$bar[$bar_nr] = $User;
        		$bar_title[$bar_nr] = date("G:i",$anfangStunde)." - ".date("G:i",$endeStunde);
        		if (date("H")-$Stunde == 0) $bar_mark = $bar_nr;
        		$bar_nr++;
        		}
        	// Diagram
        	for($i=0; $i<$bar_nr; $i++)
        		{
        		$value=$bar[$i];
        		if ($value == "") $value = 0;
        		if (max($bar) > 0) {$bar_hight=round((170/max($bar))*$value);} else $bar_hight = 0;
        		if ($bar_hight == 0) $bar_hight = 1;
        		if ($bar_mark == "$i" ) { echo "<td style=\"border-left: #FF0000 1px dotted;\" width=\"19\">";}
        		else echo "<td width=\"19\">";
        		echo "<div class=\"bar zoomInUp animated\" style=\"height:".$bar_hight."px;\" title=\"".$bar_title[$i]." - $value Visitors\"></div></td>\n";
        		}

        	?>
            </tr><tr height="20">
        	<td colspan="6" width="25%" class="timeline"><?PHP echo date("G:i",mktime(date("H")-23, 0, 0, date("n"), date("j"), date("Y"))); ?></td>
        	<td colspan="6" width="25%" class="timeline"><?PHP echo date("G:i",mktime(date("H")-17, 0, 0, date("n"), date("j"), date("Y"))); ?></td>
        	<td colspan="6" width="25%" class="timeline"><?PHP echo date("G:i",mktime(date("H")-11, 0, 0, date("n"), date("j"), date("Y"))); ?></td>
        	<td colspan="6" width="25%" class="timeline"><?PHP echo date("G:i",mktime(date("H")-5, 0, 0, date("n"), date("j"), date("Y"))); ?></td>
        	</tr></table>
          </div>
        </div>


</div>
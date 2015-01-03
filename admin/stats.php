<?php
if (!defined('VERSIONCT')) {
    die;
}?>
<div class="container-fluid">
<div class="positioner"><?php echo $lang['STATS']; ?></div>



<!-- STATS -->
        <?php
        $style = "dark"; // Counter Style "dark" or "light"
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

          <div class="middle">
            <h3><?php echo $lang['ONE_VIEW']; ?></h3>
        	<table width="100%" border="0" cellpadding="5" cellspacing="0" class="oneview">
              <tr valign="top">
              <?PHP
        	  // Gesamt Besucher ermitteln
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
        	  // 30 days averange
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
        	  // Gesamt User Heute
        	  $sel_timestamp = mktime(0, 0, 0, date("n"), date("j"), date("Y"));
        	  $sel_tag = date("Y.m.d",$sel_timestamp);
        	  $abfrage=mysql_query("select sum(user) from ".$db_prefix."Day where day='$sel_tag'");
        	  $today=mysql_result($abfrage,0,0);
        	  if ($today=="") $today=0;
        	  mysql_free_result($abfrage);
        	  echo "<td>Today</td><td>$today</td>\n";
        	  // gestern zur gleichen Zeit
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
          <div class="middle">
            <h3><?php echo $lang['LAST_24']; ?></h3>
        	<table height="200" width="100%" cellpadding="0" cellspacing="0" align="right">
        	<tr valign="bottom" height="180">
        	<?PHP
        	// User der letzten 24 Stunden abfragen
        	$bar_nr=0;
        	$bar_mark="";
        	for($Stunde=23; $Stunde>=0; $Stunde--)
        		{
        		$anfangStunde = mktime(date("H")-$Stunde, 0, 0, date("n"), date("j"), date("Y")) ;
        		$endeStunde = mktime(date("H")-$Stunde, 59, 59, date("n"), date("j"), date("Y")) ;
        		$abfrage=mysql_query("select count(id) from ".$db_prefix."IPs where time>='$anfangStunde' AND time<=$endeStunde");
        		$User=mysql_result($abfrage,0,0);
        		mysql_free_result($abfrage);
        		// Diagramm vorbereiten, Array erstellen
        		$bar[$bar_nr] = $User;
        		$bar_title[$bar_nr] = date("G:i",$anfangStunde)." - ".date("G:i",$endeStunde);
        		if (date("H")-$Stunde == 0) $bar_mark = $bar_nr;
        		$bar_nr++;
        		}
        	// Diagramm
        	for($i=0; $i<$bar_nr; $i++)
        		{
        		$value=$bar[$i];
        		if ($value == "") $value = 0;
        		if (max($bar) > 0) {$bar_hight=round((170/max($bar))*$value);} else $bar_hight = 0;
        		if ($bar_hight == 0) $bar_hight = 1;
        		if ($bar_mark == "$i" ) { echo "<td style=\"border-left: #FF0000 1px dotted;\" width=\"19\">";}
        		else echo "<td width=\"19\">";
        		echo "<div class=\"bar zoomIn animated\" style=\"height:".$bar_hight."px;\" title=\"".$bar_title[$i]." - $value Visitors\"></div></td>\n";
        		}

        	?>
            </tr><tr height="20">
        	<td colspan="6" width="25%" class="timeline"><?PHP echo date("G:i",mktime(date("H")-23, 0, 0, date("n"), date("j"), date("Y"))); ?></td>
        	<td colspan="6" width="25%" class="timeline"><?PHP echo date("G:i",mktime(date("H")-17, 0, 0, date("n"), date("j"), date("Y"))); ?></td>
        	<td colspan="6" width="25%" class="timeline"><?PHP echo date("G:i",mktime(date("H")-11, 0, 0, date("n"), date("j"), date("Y"))); ?></td>
        	<td colspan="6" width="25%" class="timeline"><?PHP echo date("G:i",mktime(date("H")-5, 0, 0, date("n"), date("j"), date("Y"))); ?></td>
        	</tr></table>
          </div>
          <div style="clear:both"></div>
          <div class="full">
            <h3><?php echo $lang['STAT_LAST30']; ?></h3>
        	<table height="230" width="100%" cellpadding="0" cellspacing="0" align="right">
        	<tr valign="bottom" height="210">
        	<?PHP
        	// User der letzten 30 Tage abfragen
        	$bar_nr=0;
        	$bar_mark="";
        	for($day=29; $day>=0; $day--)
        		{
        		$sel_timestamp = mktime(0, 0, 0, date("n"), date("j")-$day, date("Y"));
        		$sel_tag = date("Y.m.d",$sel_timestamp);
        		$abfrage=mysql_query("select sum(user) from ".$db_prefix."Day where day='$sel_tag'");
        		$User=mysql_result($abfrage,0,0);
        		mysql_free_result($abfrage);

        		$bar[$bar_nr]=$User; // Im Array Speichern
        		$bar_title[$bar_nr] = date("j.M.Y",$sel_timestamp);

        		if (date("j")-$day == 1) $bar_mark = $bar_nr;
        		if ( date("w", $sel_timestamp) == 6 OR date("w", $sel_timestamp)== 0) {$weekend[$bar_nr]=true;}
        		else {$weekend[$bar_nr]=false;}

        		$bar_nr++;
        		}
        	// Diagramm
        	for($i=0; $i<$bar_nr; $i++)
        		{
        		$value=$bar[$i];
        		if ($value == "") $value = 0;
        		if (max($bar) > 0) {$bar_hight=round((200/max($bar))*$value);} else $bar_hight = 0;
        		if ($bar_hight == 0) $bar_hight = 1;
        		if ($bar_mark == "$i" ) { echo "<td style=\"border-left: #FF0000 1px dotted;\" width=\"31\">";}
        		else echo "<td width=\"31\">";
        		echo "<div class=\"bar zoomIn animated\" style=\"height:".$bar_hight."px;\" title=\"".$bar_title[$i]." - $value Visitors\"></div></td>\n";
        		}
        	?>
            </tr><tr height="20">
        	<td colspan="6" class="timeline"><?PHP echo date("j.M",mktime(0, 0, 0, date("n"), date("j")-29, date("Y"))); ?></td>
        	<td colspan="6" class="timeline"><?PHP echo date("j.M",mktime(0, 0, 0, date("n"), date("j")-23, date("Y"))); ?></td>
        	<td colspan="6" class="timeline"><?PHP echo date("j.M",mktime(0, 0, 0, date("n"), date("j")-17, date("Y"))); ?></td>
        	<td colspan="6" class="timeline"><?PHP echo date("j.M",mktime(0, 0, 0, date("n"), date("j")-11, date("Y"))); ?></td>
        	<td colspan="6" class="timeline"><?PHP echo date("j.M",mktime(0, 0, 0, date("n"), date("j")-5, date("Y"))); ?></td>
        	</tr></table>
          </div>

          <!-- VISITORS -->

          <div class="middle">
    <h3><?php echo $lang['STAT_REF']; ?></h3>
	<table width="100%" border="0" cellpadding="5" cellspacing="0" class="oneview">
	<tr>
      <td width="30"><strong>Nr.</strong></td>
      <td width="280"><strong>Referrer</strong></td>
      <td width="120"><strong>percent</strong></td>
    </tr>
    <?PHP
	// gesammt Referrer
	$abfrage=mysql_query("select sum(view) from ".$db_prefix."Referer");
	$ges_referer=mysql_result($abfrage,0,0);
	mysql_free_result($abfrage);
	// Top Refferrer
	$nr = 1;
	$abfrage=mysql_query("SELECT referer, SUM(view) AS views from ".$db_prefix."Referer GROUP BY referer ORDER BY views DESC LIMIT 0, 10");
	while($row=mysql_fetch_array($abfrage))
	  	{
		$referer=htmlspecialchars($row['referer']);
		if(strlen($referer) > 35){$shortreferer=substr($referer,0,30)."<a href=\"#\" title=\"$referer\">...</a>";}
		else {$shortreferer=$referer;}
		$views=$row['views'];
		$percent = (100/$ges_referer)*$views;
		if ($percent < 0.1 ) $percent = round($percent,2);
		else $percent = round($percent,1);
		$bar_width = round((100/$ges_referer)*$views);
		echo"	<tr>\n";
		echo"		<td>$nr</td>\n";
		echo"		<td>$shortreferer</td>\n";
		echo"		<td nowrap><div class=\"vbar\" style=\"width:".$bar_width."px;\" title=\"$views Visitors\" >&nbsp;$percent%</div></td>\n";
		echo"	</tr>\n";
		$nr++;
		}
	mysql_free_result($abfrage);
	?>
    </table>
  </div>
  <div class="middle">
    <h3><?php echo $lang['STAT_PAGE']; ?></h3>
	<table width="100%" cellpadding="5" cellspacing="0" class="oneview">
  	<tr>
      <td width="30"><strong>Nr.</strong></td>
      <td width="280"><strong>Page</strong></td>
      <td width="120"><strong>percent</strong></td>
  	</tr>
<?PHP
	// gesammt Pages
	$abfrage=mysql_query("select sum(view) from ".$db_prefix."Page");
	$ges_page=mysql_result($abfrage,0,0);
	mysql_free_result($abfrage);
	// Top Pages
	$nr = 1;
	$abfrage=mysql_query("SELECT page, SUM(view) AS views from ".$db_prefix."Page GROUP BY page ORDER BY views DESC LIMIT 0, 10");
	while($row=mysql_fetch_array($abfrage))
		{
		$page=htmlspecialchars($row['page']);
		if(strlen($page) > 35){$shortpage="<a href=\"#\" title=\"$page\">...</a>".substr($page,strlen($page)-30,strlen($page)); }
		else {$shortpage=$page;}
		$views=$row['views'];
		$percent = (100/$ges_page)*$views;
		if ($percent < 0.1 ) $percent = round($percent,2);
		else $percent = round($percent,1);
		$bar_width = round((100/$ges_page)*$views);
		echo"	<tr>\n";
		echo"		<td>$nr</td>\n";
		echo"		<td>$shortpage</td>\n";
		echo"		<td nowrap><div class=\"vbar\" style=\"width:".$bar_width."px;\" title=\"$views Visits\" >&nbsp;$percent%</div></td>\n";
		echo"	</tr>\n";
		$nr++;
		}
	mysql_free_result($abfrage);
?>
	</table>
  </div>
  <div style="clear:both"></div>
   <div class="middle">
    <h3><?php echo $lang['STAT_KEYWORD']; ?></h3>
	<table width="100%" border="0" cellpadding="5" cellspacing="0" class="oneview">
      <tr>
        <td width="30"><strong>Nr.</strong></td>
        <td width="280"><strong>Keywords</strong></td>
        <td width="120"><strong>percent</strong></td>
      </tr>
	<?PHP
	// gesammt keywords
	$abfrage=mysql_query("select sum(view) from ".$db_prefix."Keyword");
	$ges_keyword=mysql_result($abfrage,0,0);
	mysql_free_result($abfrage);
	// Top Keywords
	$nr = 1;
	$abfrage=mysql_query("SELECT keyword, SUM(view) AS views from ".$db_prefix."Keyword GROUP BY keyword ORDER BY views DESC LIMIT 0, 10");
	while($row=mysql_fetch_array($abfrage))
		{
		$keyword=urldecode($row['keyword']);
		if(strlen($keyword) > 35){$shortkeyword=substr($keyword,0,30)."<a href=\"#\" title=\"$keyword\">...</a>";}
		else {$shortkeyword=$keyword;}
		$views=$row['views'];
		$percent = (100/$ges_keyword)*$views;
		if ($percent < 0.1 ) $percent = round($percent,2);
		else $percent = round($percent,1);
		$bar_width = round((100/$ges_keyword)*$views);
		echo"	<tr>\n";
		echo"		<td>$nr</td>\n";
		echo"		<td>$shortkeyword</td>\n";
		echo"		<td nowrap><div class=\"vbar\" style=\"width:".$bar_width."px;\" title=\"$views Visitors\" >&nbsp;$percent%</div></td>\n";
		echo"	</tr>\n";
		$nr++;
		}
	mysql_free_result($abfrage);
	?>
    </table>
  </div>
  <div class="middle">
    <h3><?php echo $lang['STAT_LANG']; ?></h3>
	<table width="100%" border="0" cellpadding="5" cellspacing="0" class="oneview">
      <tr>
        <td width="30"><strong>Nr.</strong></td>
        <td width="280"><strong>Language</strong></td>
        <td width="120"><strong>percent</strong></td>
      </tr>
	<?PHP
	// gesammt Languages
	$abfrage=mysql_query("select sum(view) from ".$db_prefix."Language");
	$ges_language=mysql_result($abfrage,0,0);
	mysql_free_result($abfrage);
	// Code to Language
	$code2lang = array(
	'ar'=>'Arabic',
	'bn'=>'Bengali',
	'bg'=>'Bulgarian',
	'zh'=>'Chinese',
	'cs'=>'Czech',
	'da'=>'Danish',
	'en'=>'English',
	'et'=>'Estonian',
	'fi'=>'Finnish',
	'fr'=>'French',
	'de'=>'German',
	'el'=>'Greek',
	'hi'=>'Hindi',
	'id'=>'Indonesian',
	'it'=>'Italian',
	'ja'=>'Japanese',
	'kg'=>'Korean',
	'nb'=>'Norwegian',
	'nl'=>'Nederlands',
	'pl'=>'Polish',
	'pt'=>'Portuguese',
	'ro'=>'Romanian',
	'ru'=>'Russian',
	'sr'=>'Serbian',
	'sk'=>'Slovak',
	'es'=>'Spanish',
	'sv'=>'Swedish',
	'th'=>'Thai',
	'tr'=>'Turkish',
	''=>'');
	// Top Languages
	$nr = 1;
	$abfrage=mysql_query("SELECT language, SUM(view) AS views from ".$db_prefix."Language GROUP BY language ORDER BY views DESC LIMIT 0, 10");
	while($row=mysql_fetch_array($abfrage))
		{
		$language=$row['language'];
		if (array_key_exists($language,$code2lang)) $language=$code2lang[$language];
		$views=$row['views'];
		$percent = (100/$ges_language)*$views;
		if ($percent < 0.1 ) $percent = round($percent,2);
		else $percent = round($percent,1);
		$bar_width = round((100/$ges_language)*$views);
		echo"	<tr>\n";
		echo"		<td>$nr</td>\n";
		echo"		<td>$language</td>\n";
		echo"		<td nowrap><div class=\"vbar\" style=\"width:".$bar_width."px;\" title=\"$views Visitors\" >&nbsp;$percent%</div></td>\n";
		echo"	</tr>\n";
		$nr++;
		}
	mysql_free_result($abfrage);
	?>
	</table>
  </div>

  <!-- HISTORY -->

  <?PHP
    // Get Month and Year
    $time=time();
    $show_month=date("n",$time);
    $show_year=date("Y",$time);
    ?>

    <div class="middle">
    <h3><?php echo $lang['STAT_HISTORY']; ?></h3>
      <?PHP
	  // Gesamt Besucher ermitteln
	  $abfrage=mysql_query("select sum(user),sum(view),min(day),avg(user) from ".$db_prefix."Day");
	  $visitors=mysql_result($abfrage,0,0);
	  $visits=mysql_result($abfrage,0,1);
	  $since=mysql_result($abfrage,0,2);
	  $since=str_replace(".", "-", $since);
	  $since=strtotime($since);
	  $since=date("d F Y",$since);
	  $total_avg=round(mysql_result($abfrage,0,3),2);
	  mysql_free_result($abfrage);
	  ?>
	<table width="100%" border="0" cellpadding="5" cellspacing="0" class="oneview">
      <tr valign="top">
	  <td colspan="4"><strong>Total since <?PHP echo $since;?></strong></td>
	  </tr>
	  <tr valign="top">
	  <td width="30%">Visitors</td><td width="20%"><?PHP echo $visitors; ?></td>
	  <td width="30%">Visits</td><td width="20%"><?PHP echo $visits; ?></td>
	  </tr>
	  <tr valign="top">
	  <td width="30%">&Oslash; Day</td><td width="20%"><?PHP echo $total_avg; ?></td>
	  <td width="30%">&nbsp;</td><td width="20%">&nbsp;</td>
	  </tr>
	</table>
	<br />
		  <?PHP
	  // selected Moth
	  $sel_timestamp = mktime(0, 0, 0, $show_month, 1, $show_year);
	  $sel_month = date("Y.m.%",$sel_timestamp);
	  $abfrage=mysql_query("select sum(user), sum(view), avg(user) from ".$db_prefix."Day where day LIKE '$sel_month'");
	  $visitors=mysql_result($abfrage,0,0);
	  $visits=mysql_result($abfrage,0,1);
	  $day_avg=round(mysql_result($abfrage,0,2),2);
	  mysql_free_result($abfrage);
	  ?>
	<table width="100%" border="0" cellpadding="5" cellspacing="0" class="oneview">
	  <tr valign="top">
		<td colspan="4"><strong>Selected is <?PHP echo date("F Y",mktime(0, 0, 0, $show_month, 1, $show_year)); ?></strong></td>
	  </tr>
	  <tr valign="top">
	    <td>Visitors</td><td><?PHP echo $visitors; ?></td><td>Visits</td><td><?PHP echo $visits; ?></td>
	  </tr>
	  <tr valign="top">
		<td>&Oslash; Day</td><td><?PHP echo $day_avg; ?></td><td>&nbsp;</td><td>&nbsp;</td>
	  </tr>
    </table>
  </div>
  <div class="middle">
    <h3>
	<?PHP
	echo "Year ".date("Y",mktime(0, 0, 0, $show_month, 1, $show_year));

	$back_month=date("n",mktime(0, 0, 0, $show_month, 1, $show_year-1));
	$back_yaer=date("Y",mktime(0, 0, 0, $show_month, 1, $show_year-1));
	$next_month=date("n",mktime(0, 0, 0, $show_month, 1, $show_year+1));
	$next_yaer=date("Y",mktime(0, 0, 0, $show_month, 1, $show_year+1));


	?>
	</h3>
	<table height="200" width="100%" cellpadding="0" cellspacing="0" align="right">
	<tr valign="bottom" height="180">
	<?PHP
	// Max Month
	$abfrage=mysql_query("select LEFT(day,7) as month, sum(user) as user_month from ".$db_prefix."Day GROUP BY month ORDER BY user_month DESC LIMIT 1");
	$max_month=mysql_result($abfrage,0,1);
	// Monat abfragen
	$bar_nr=0;
	for($month=1; $month<=12; $month++)
		{
		$sel_timestamp = mktime(0, 0, 0, $month, 1, $show_year);
		$sel_month = date("Y.m.%",$sel_timestamp);
		$abfrage=mysql_query("select sum(user) from ".$db_prefix."Day where day LIKE '$sel_month'");
		$User=mysql_result($abfrage,0,0);
		mysql_free_result($abfrage);

		$bar[$bar_nr]=$User; // Im Array Speichern
		$bar_title[$bar_nr] = date("M.Y",$sel_timestamp);
		$bar_month[$bar_nr]=$month;

		$bar_nr++;
		}
	// Diagramm
	for($i=0; $i<$bar_nr; $i++)
		{
		$value=$bar[$i];
		if ($value == "") $value = 0;
		if ($max_month > 0) {$bar_hight=round((170/$max_month)*$value);} else $bar_hight = 0;
		if ($bar_hight == 0) $bar_hight = 1;

		echo "<td width=\"38\">";

		echo "<div class=\"bar zoomIn animated\" style=\"height:".$bar_hight."px;\" title=\"".$bar_title[$i]." - $value Visitors\"></div>";
		echo "</td>\n";
		}
	?>
    </tr><tr height="20">
	<td colspan="3" width="25%" class="timeline"><?PHP echo date("M.Y",mktime(0, 0, 0, 1, 1, $show_year)); ?></td>
	<td colspan="3" width="25%" class="timeline"><?PHP echo date("M.Y",mktime(0, 0, 0, 4, 1, $show_year)); ?></td>
	<td colspan="3" width="25%" class="timeline"><?PHP echo date("M.Y",mktime(0, 0, 0, 7, 1, $show_year)); ?></td>
	<td colspan="3" width="25%" class="timeline"><?PHP echo date("M.Y",mktime(0, 0, 0, 10, 1, $show_year)); ?></td>
	</tr></table>
  </div>
  <div style="clear:both"></div>
  <div class="full">
    <h3>
	<?PHP
	echo date("F Y",mktime(0, 0, 0, $show_month, 1, $show_year));

	$back_month=date("n",mktime(0, 0, 0, $show_month-1, 1, $show_year));
	$back_yaer=date("Y",mktime(0, 0, 0, $show_month-1, 1, $show_year));
	$next_month=date("n",mktime(0, 0, 0, $show_month+1, 1, $show_year));
	$next_yaer=date("Y",mktime(0, 0, 0, $show_month+1, 1, $show_year));


	?>
	</h3>
	<table height="230" width="100%" cellpadding="0" cellspacing="0" align="right">
	<tr valign="bottom" height="210">
	<?PHP
	// Ausgewählten Monat anzeigen
	$bar_nr=0;
	$month_days=date('t',mktime(0,0,0,$show_month,1,$show_year));
	for($day=1; $day<=$month_days; $day++)
		{
		$sel_timestamp = mktime(0, 0, 0, $show_month, $day, $show_year);
		$sel_tag = date("Y.m.d",$sel_timestamp);
		$abfrage=mysql_query("select sum(user) from ".$db_prefix."Day where day='$sel_tag'");
		$User=mysql_result($abfrage,0,0);
		mysql_free_result($abfrage);

		$bar[$bar_nr]=$User; // Im Array Speichern
		$bar_title[$bar_nr] = date("j.M.Y",$sel_timestamp);

		if (date("j")-$day == 1) $bar_mark = $bar_nr;
		if ( date("w", $sel_timestamp) == 6 OR date("w", $sel_timestamp)== 0) {$weekend[$bar_nr]=true;}
		else {$weekend[$bar_nr]=false;}

		$bar_nr++;
		}
	// Diagramm
	for($i=0; $i<$bar_nr; $i++)
		{
		$value=$bar[$i];
		if ($value == "") $value = 0;
		if (max($bar) > 0) {$bar_hight=round((200/max($bar))*$value);} else $bar_hight = 0;
		if ($bar_hight == 0) $bar_hight = 1;
		echo "<td width=\"30\">";
		echo "<div class=\"bar zoomIn animated\" style=\"height:".$bar_hight."px;\" title=\"".$bar_title[$i]." - $value Visitors\"></div></td>\n";
		}
	?>
    </tr><tr height="20">
	<td colspan="6" class="timeline"><?PHP echo date("j.M",mktime(0, 0, 0, $show_month, 1, $show_year)); ?></td>
	<td colspan="6" class="timeline"><?PHP echo date("j.M",mktime(0, 0, 0, $show_month, 7, $show_year)); ?></td>
	<td colspan="6" class="timeline"><?PHP echo date("j.M",mktime(0, 0, 0, $show_month, 13, $show_year)); ?></td>
	<td colspan="6" class="timeline"><?PHP echo date("j.M",mktime(0, 0, 0, $show_month, 19, $show_year)); ?></td>
	<td colspan="7" class="timeline"><?PHP echo date("j.M",mktime(0, 0, 0, $show_month, 25, $show_year)); ?></td>
	</tr></table>
  </div>


</div>
<?php
$Address = 'your.domain.name/folder'; // Stats script root folder > Example: www.mydomain.name/stats
$Hostname = 'x.x.x.x'; // MySQL DB Server IP/Domain
$Username = 'user'; // MySQL Username
$Password = 'pass'; // MySQL Password
$CharacterDatabase = 'characters'; // must contains characters table
$RealmDatabase = 'realmd';  // must contains zone_coordinates and accounts table
$TrinityDatabase = 'world';  // for uptime stats
$ManagerDatabase = 'mmfpm'; // Used MiniManager's 'dbc_zones' table to get zone names
$RealmName = 'Realm Name - Online Players';
$DatabaseEncoding = 'utf8';

  $realm_db = mysql_connect($Hostname, $Username, $Password);
    mysql_select_db($RealmDatabase, $realm_db);
  $db_result = mysql_query("SET NAMES $DatabaseEncoding", $realm_db);
  
  $Trinity_db = mysql_connect($Hostname, $Username, $Password, TRUE);
    mysql_select_db($CharacterDatabase, $Trinity_db);
  $db_result = mysql_query("SET NAMES $DatabaseEncoding", $Trinity_db);
  
  $manager_db = mysql_connect($Hostname, $Username, $Password);
    mysql_select_db($ManagerDatabase, $manager_db);
  $db_result = mysql_query("SET NAMES $DatabaseEncoding", $manager_db);
  
  $maxplayers_query = mysql_query("SELECT `maxplayers` FROM $RealmDatabase.`uptime` ORDER BY `maxplayers` DESC LIMIT 1", $realm_db)or die(mysql_error());
  $maxplayers_results =  mysql_fetch_array($maxplayers_query);
  $maxplayers = $maxplayers_results['maxplayers'];

  $player_query = mysql_query("SELECT (SELECT COUNT(guid) FROM $CharacterDatabase.`characters` WHERE race IN(2,5,6,8,10) AND `online`='1') as horde, (SELECT COUNT(guid) FROM $CharacterDatabase.`characters` WHERE race IN(1,3,4,7,11) AND `online`='1') as alliance FROM $CharacterDatabase.`characters`", $Trinity_db)or die(mysql_error()); 
  $player_results = mysql_fetch_array($player_query); 
  $horde =  $player_results['horde'];
  $alliance =  $player_results['alliance'];
  $total = $horde + $alliance;
  
  echo "<div align=\"center\" class=\"news-title\">". $RealmName ."</div><br>
  <table border=\"0\" align=\"center\" cellspacing=\"0\" cellpadding=\"3\" class=\"news-table-dark\">
  <tr>
    <td align=\"left\" width=\"120px\"><b>Players Online:</b></td>
	<td align=\"left\" width=\"30px\">". $total ."</td>
    <td align=\"left\" width=\"60px\"><b>Alliance:</b></td>
	<td align=\"left\" width=\"30px\">". $alliance ."</td>
  </tr>
  <tr>
    <td align=\"left\" width=\"120px\"><b>Max Players:</b></td>
	<td align=\"left\" width=\"30px\">". $maxplayers ."</td>
    <td align=\"left\" width=\"60px\"><b>Horde:</b></td>
	<td align=\"left\" width=\"30px\">". $horde ."</td>
  </tr>
  </table><br>
<table border=\"0\" align=\"center\" cellspacing=\"0\" cellpadding=\"3\" class=\"news-table-dark\">
  <tr bgcolor=\"#666666\">
    <td align=\"center\" class=\"navigation\"><b>#</b></td>
	<td align=\"left\" class=\"navigation\"><b>Name</b></td>
	<td align=\"center\" class=\"navigation\"><b>R</b></td>
	<td align=\"center\" class=\"navigation\"><b>C</b></td>
	<td align=\"center\" class=\"navigation\"><b>Lvl</b></td>
  <td align=\"center\" class=\"navigation\"><b>Lat</b></td>
  </tr>";
	// Assign GM tag is GM
	
	$db_result = mysql_query("SELECT * FROM $CharacterDatabase.`characters` WHERE `online`='1' and `extra_flags`<'5' ORDER BY `level` DESC", $Trinity_db)or die(mysql_error());
	$row = 0;
    while($result = mysql_fetch_array($db_result)) {
	$row++;
	// Define level colors
	if ($result['level'] < 10) {
	$level_out = "<font color=\"#CFCFCF\"><b>". $result['level'] ."</b></font>";
	}
	elseif ($result['level'] < 20) {
	$level_out = "<font color=\"#EDEDED\"><b>". $result['level'] ."</b></font>";
	}
	elseif ($result['level'] < 30) {
	$level_out = "<font color=\"#F5DC6E\"><b>". $result['level'] ."</b></font>";
	}
	elseif ($result['level'] < 40) {
	$level_out = "<font color=\"#FFBF00\"><b>". $result['level'] ."</b></font>";
	}
	elseif ($result['level'] < 50) {
	$level_out = "<font color=\"#FF6600\"><b>". $result['level'] ."</b></font>";
	}
	elseif ($result['level'] < 60) {
	$level_out = "<font color=\"#FF4D00\"><b>". $result['level'] ."</b></font>";
	}
	elseif ($result['level'] < 70) {
	$level_out = "<font color=\"#FF6B1C\"><b>". $result['level'] ."</b></font>";
	}
	else {
	$level_out = "<font color=\"#FF0000\"><b>". $result['level'] ."</b></font>";
	}
	
	if ($result['latency'] < 1) {
		$latency = "<font color=\"#FFFFFF\" size=\"1\">N/A</font>";
	}
	elseif ($result['latency'] < 200) {
		$latency = "<font color=\"#09FF00\" size=\"1\">". $result['latency'] ." ms</font>";
	}
	elseif ($result['latency'] < 350) {
		$latency = "<font color=\"#FFF700\" size=\"1\">". $result['latency'] ." ms</font>";
	}
	elseif ($result['latency'] < 500) {
		$latency = "<font color=\"#FF8000\" size=\"1\">". $result['latency'] ." ms</font>";
	}
	else {
		$latency = "<font color=\"#D60000\" size=\"1\">". $result['latency'] ." ms</font>";
	}
		
	if ($css != "On"){ $css = "On"; }else{ $css = "Off"; }
	
	echo "<tr class=\"{$css}\">
	<td align=\"left\" width=\"25px\">". $row ."</td>
	<td align=\"left\" width=\"180px\"><b>". $result['name'] ."</b></td>
    <td align=\"center\" valign=\"middle\" width=\"30px\"><img src=\"http://". $Address ."img/icon/race/". $result['race'] ."-".$result['gender'].".gif\"></td>
    <td align=\"center\" valign=\"middle\" width=\"30px\"><img src=\"http://". $Address ."img/icon/class/". $result['class'] .".gif\"></td>
    <td align=\"center\" valign=\"middle\" width=\"30px\">". $level_out ."</td>
    <td align=\"center\" valign=\"middle\" width=\"45px\">". $latency ."</td>
    </tr>";
	}
  echo "</table>";
?> 
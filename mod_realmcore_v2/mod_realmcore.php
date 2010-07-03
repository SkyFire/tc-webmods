<div align="center">.: Server Status :.</div>
<?php
// General server settings
$address = "your.domain.name";
// Enter your Core server IP or Domain
$ip_world = "x.x.x.x";
// Enter your Core server port (8085 = Deafult TCWorld Port)
$port_world = "8085";
// Enter your Realm server IP or Domain
$ip_auth = "x.x.x.x";
// Enter your Realm server port (3724 = Deafult TCRealm Port)
$port_auth = "3724";

if (! $sock = @fsockopen($ip_world, $port_world, $num, $error, 3)) 
echo '<table width=\"100%\" border=0 cellspacing=0 cellpadding=3>
  <tr>
    <td align=\"left\" valign=\"middle\">Game Server:</td>
	<td align=\"left\" valign=\"middle\"><img src="http://'. $address .'/mod_realmcore/images/wow_off.png"></td></tr>'; 
else{ 
echo '<table width=\"100%\" border=0 cellspacing=0 cellpadding=3>
  <tr>
    <td align=\"left\" valign=\"middle\">Game Server:</td>
	<td align=\"left\" valign=\"middle\"><img src="http://'. $address .'/mod_realmcore/images/wow_on.png"></td></tr>'; 
fclose($sock);
} 

if (! $sock = @fsockopen($ip_auth, $port_auth, $num, $error, 3)) 
echo '<table width=\"100%\" border=0 cellspacing=0 cellpadding=3>
  <tr>
    <td align=\"left\" valign=\"middle\">Login Server:</td>
	<td align=\"left\" valign=\"middle\"><img src="http://'. $address .'/mod_realmcore/images/wow_off.png"></td></tr></table>'; 
else{ 
echo '<table width=\"100%\" border=0 cellspacing=0 cellpadding=3>
  <tr>
    <td align=\"left\" valign=\"middle\">Login Server:</td>
	<td align=\"left\" valign=\"middle\"><img src="http://'. $address .'/mod_realmcore/images/wow_on.png"></td></tr></table>'; 
fclose($sock);
}
?>

<?php
// MySQL settings
$WoWHostname = "x.x.x.x"; // MySQL server address
$WoWUsername = "user"; // MySQL username
$WoWPassword = "pass"; // MySQL password
$CharacterDatabase = 'characters'; // TC characters database
$RealmDatabase = 'realmd'; // TC relamd database
$WorldDatabase = 'world'; // TC world database
$CharacterDatabaseEncoding = 'utf8'; // database character encoding

// DO NOT EDIT BELOW HERE IF YOU DON'T KNOW WHAT IT IS!!!
$WoWconn = mysql_connect($WoWHostname, $WoWUsername, $WoWPassword) or die('Connection failed: ' . mysql_error());

mysql_select_db($CharacterDatabase, $WoWconn) or die('Select DB failed: ' . mysql_error());

$sql = "SELECT * FROM `characters` WHERE `online` = 1 ORDER BY `name`";
$result = mysql_query($sql, $WoWconn) or die('Query failed: ' . mysql_error());

$count = 0;
?>

<?php
// Do the show
$realm_db = mysql_connect($WoWHostname, $WoWUsername, $WoWPassword);
mysql_select_db($RealmDatabase, $realm_db);
$db_result = mysql_query("SET NAMES $CharacterDatabaseEncoding", $realm_db);

$world_db = mysql_connect($WoWHostname, $WoWUsername, $WoWPassword, TRUE);
mysql_select_db($CharacterDatabase, $world_db);
$db_result = mysql_query("SET NAMES $CharacterDatabaseEncoding", $world_db);

$gamebuild_query = mysql_query("SELECT `gamebuild` FROM $RealmDatabase.`realmlist`", $world_db)or die(mysql_error());
$gamebuild_results = mysql_fetch_array($gamebuild_query);

if ($gamebuild_results['gamebuild'] > '11403') {
	$gamebuild = "3.3.3a (" .$gamebuild_results['gamebuild']. ")";
}
else {
	$gamebuild = $gamebuild_results['gamebuild'];
}

$uptime_query = mysql_query("SELECT * FROM $RealmDatabase.`uptime` ORDER BY `starttime` DESC LIMIT 1", $realm_db)or die(mysql_error()); 
$uptime_results = mysql_fetch_array($uptime_query);
//Current uptime
if ($uptime_results['uptime'] > 86400) { //days
    $uptime =  round(($uptime_results['uptime'] / 24 / 60 / 60),2)." Days";
}
elseif($uptime_results['uptime'] > 3600) { //hours
    $uptime =  round(($uptime_results['uptime'] / 60 / 60),2)." Hours";
}
else { //minutes
    $uptime =  round(($uptime_results['uptime'] / 60),2)." Minutes";
}

$total_uptime_query = mysql_query("SELECT (SELECT SUM(uptime) FROM $RealmDatabase.`uptime`)as total_uptime", $realm_db)or die(mysql_error()); 
$total_uptime_results = mysql_fetch_array($total_uptime_query);
//Total uptime
if ($total_uptime_results['total_uptime'] > 86400) { //days
    $total_uptime =  round(($total_uptime_results['total_uptime'] / 24 / 60 / 60),1)." Days";
}
elseif($uptime_results['uptime'] > 3600) { //hours
    $total_uptime =  round(($total_uptime_results['total_uptime'] / 60 / 60),1)." Hours";
}
else { //minutes
    $total_uptime =  round(($total_uptime_results['total_uptime'] / 60),1)." Minutes";
}

$uptime_since_query = mysql_query("SELECT starttime FROM uptime ORDER BY starttime ASC LIMIT 1", $realm_db)or die(mysql_error());
$uptime_since_results = mysql_fetch_array($uptime_since_query);
$uptime_since_counter = (time() - $uptime_since_results['starttime']);
$uptime_since_counter_result = round(($uptime_since_counter / 24 / 60 / 60),1)." Days";

$total_offline = round(($uptime_since_counter - $total_uptime_results['total_uptime']) / 60 / 60 / 24,1)." Days";

$maxplayers_query = mysql_query("SELECT `maxplayers` FROM $RealmDatabase.`uptime` ORDER BY `maxplayers` DESC LIMIT 1", $realm_db)or die(mysql_error());
$maxplayers_results =  mysql_fetch_array($maxplayers_query);
$maxplayers = $maxplayers_results['maxplayers'];

$player_query = mysql_query("SELECT (SELECT COUNT(guid) FROM $CharacterDatabase.`characters` WHERE race IN(2,5,6,8,10) AND `online`='1') as horde, (SELECT COUNT(guid) FROM $CharacterDatabase.`characters` WHERE race IN(1,3,4,7,11) AND `online`='1') as alliance FROM $CharacterDatabase.`characters`", $world_db)or die(mysql_error()); 
$player_results = mysql_fetch_array($player_query); 
$horde =  $player_results['horde'];
$alliance =  $player_results['alliance'];
$total = $horde + $alliance;

$total_player_query = mysql_query("SELECT (SELECT COUNT(guid) FROM $CharacterDatabase.`characters` WHERE race IN(2,5,6,8,10)) as total_horde, (SELECT COUNT(guid) FROM $CharacterDatabase.`characters` WHERE race IN(1,3,4,7,11)) as total_alliance FROM $CharacterDatabase.`characters`", $world_db)or die(mysql_error()); 
$total_player_results = mysql_fetch_array($total_player_query); 
$total_horde =  $total_player_results['total_horde'];
$total_alliance =  $total_player_results['total_alliance'];
$total_all = $total_horde + $total_alliance;

$account_query = mysql_query("SELECT (SELECT COUNT(id) FROM $RealmDatabase.`account`) as aid", $realm_db)or die(mysql_error()); 
$account_result = mysql_fetch_array($account_query);
$account = $account_result['aid'];

echo "<br><div align=\"center\" class=\"table-title\">.: Server Details :.</div>
<table width=\"100%\" border=0 cellspacing=0 cellpadding=3>
  <tr>
    <td align=\"left\" valign=\"middle\" bgcolor=\"#3f3f3f\">Version:</td>
    <td align=\"left\" valign=\"middle\">".$gamebuild."</td>
  </tr>
  <tr>
    <td align=\"left\" valign=\"middle\">Server age:</td>
    <td align=\"left\" valign=\"middle\">".$uptime_since_counter_result."</td>
  </tr>
  <tr>
    <td align=\"left\" valign=\"middle\" bgcolor=\"#3f3f3f\">Uptime:</td>
    <td align=\"left\" valign=\"middle\">".$uptime."</td>
  </tr>
  <tr>
    <td align=\"left\" valign=\"middle\">Online:</td>
    <td align=\"left\" valign=\"middle\">".$total_uptime."</td>
  </tr>
  <tr>
    <td align=\"left\" valign=\"middle\" bgcolor=\"#3f3f3f\">Offline:</td>
    <td align=\"left\" valign=\"middle\">".$total_offline."</td>
  </tr>
  <tr>
    <td align=\"left\" valign=\"middle\">Players:</td>
    <td align=\"left\" valign=\"middle\">".$total."</td>
  </tr>
  <tr>
    <td align=\"left\" valign=\"middle\" bgcolor=\"#3f3f3f\">Max online:</td>
    <td align=\"left\" valign=\"middle\">".$maxplayers."</td>
  </tr>
  <tr>
    <td align=\"left\" valign=\"middle\">Accounts:</td>
    <td align=\"left\" valign=\"middle\">".$account."</td>
  </tr>
  <tr>
    <td align=\"left\" valign=\"middle\" bgcolor=\"#3f3f3f\">Characters:</td>
    <td align=\"left\" valign=\"middle\">".$total_all."</td>
  </tr>
  </table>
  <br>
  <table width=\"120\" border=0 cellspacing=0 cellpadding=3>
  <tr>
    <td align=\"center\" valign=\"bottom\"><div align=center><img src=\"http://".$address."/mod_realmcore/images/alliance_small.gif\"><br><b><FONT COLOR=cyan>Alliance</font></b></div></td>
    <td align=\"center\" valign=\"bottom\"><div align=center><img src=\"http://".$address."/mod_realmcore/images/horde_small.gif\"><br><b><FONT COLOR=red>Horde</font></b></div></td>
  </tr>
  <tr>
    <td align=\"center\" valign=\"bottom\"><b><div align=center>".$alliance."</b>/".$total_alliance."</div></td>
    <td align=\"center\" valign=\"bottom\"><b><div align=center>".$horde."</b>/".$total_horde."</div></td>
  </tr>
</table>";
?>
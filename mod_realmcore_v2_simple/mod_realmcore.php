<?php
// General server settings
$address = "your.domain.name";
// Enter your Core server IP or Domain
$ip_world = "x.x.x.x";
// Enter your Core server port (8085 = Deafult TCWorld Port)
$port_world = "8085";
// Enter your Realm server IP or Domain
$ip_auth = "x.x.x.x";
// Enter your Realm server port (3724 = Deafult TCWorld Port)
$port_auth = "3724";

if (! $sock = @fsockopen($ip_world, $port_world, $num, $error, 3)) 
echo '<table width=\"100%\" border=0 cellspacing=0 cellpadding=3>
  <tr>
    <td align=\"left\" valign=\"middle\">Game Server:</td>
	<td align=\"left\" valign=\"middle\"><img src="http://'.$address.'/mod_realmcore/images/wow_off.png"></td></tr>'; 
else{ 
echo '<table width=\"100%\" border=0 cellspacing=0 cellpadding=3>
  <tr>
    <td align=\"left\" valign=\"middle\">Game Server:</td>
	<td align=\"left\" valign=\"middle\"><img src="http://'.$address.'/mod_realmcore/images/wow_on.png"></td></tr>'; 
fclose($sock);
} 

if (! $sock = @fsockopen($aip_auth, $port_auth, $num, $error, 3)) 
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
$WoWUsername = "username"; // MySQL username
$WoWPassword = "password"; // MySQL password
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
$realm_db = mysql_connect($WoWHostname, $WoWUsername, $WoWPassword);
mysql_select_db($RealmDatabase, $realm_db);
$db_result = mysql_query("SET NAMES $CharacterDatabaseEncoding", $realm_db);

$world_db = mysql_connect($WoWHostname, $WoWUsername, $WoWPassword, TRUE);
mysql_select_db($CharacterDatabase, $world_db);
$db_result = mysql_query("SET NAMES $CharacterDatabaseEncoding", $world_db);
 
$uptime_query = mysql_query("SELECT * FROM $RealmDatabase.`uptime` ORDER BY `starttime` DESC LIMIT 1", $realm_db)or die(mysql_error()); 
$uptime_results = mysql_fetch_array($uptime_query); 
$maxplayers =  $uptime_results['maxplayers'];
if ($uptime_results['uptime'] > 86400) { //days
    $uptime =  round(($uptime_results['uptime'] / 24 / 60 / 60),2)." Days";
}
elseif($uptime_results['uptime'] > 3600) { //hours
    $uptime =  round(($uptime_results['uptime'] / 60 / 60),2)." Hours";
}
else { //minutes
    $uptime =  round(($uptime_results['uptime'] / 60),2)." Minutes";
}

$player_query = mysql_query("SELECT (SELECT COUNT(guid) FROM $CharacterDatabase.`characters` WHERE race IN(2,5,6,8,10) AND `online`='1') as horde, (SELECT COUNT(guid) FROM $CharacterDatabase.`characters` WHERE race IN(1,3,4,7,11) AND `online`='1') as alliance FROM $CharacterDatabase.`characters`", $world_db)or die(mysql_error()); 
$player_results = mysql_fetch_array($player_query); 
$horde =  $player_results['horde'];
$alliance =  $player_results['alliance'];
$total = $horde + $alliance;

echo "<table width=\"100%\" border=0 cellspacing=0 cellpadding=3>
  <tr>
    <td align=\"left\" valign=\"middle\">Uptime:</td>
    <td align=\"left\" valign=\"middle\">" . $uptime . "</td>
  <tr>
    <td align=\"left\" valign=\"middle\">Players online:</td>
    <td align=\"left\" valign=\"middle\"><b>" . $total . "</b></td>
  </tr>
  </tr>
  <tr>
    <td align=\"left\" valign=\"middle\">Max online:</td>
    <td align=\"left\" valign=\"middle\"><b>" . $maxplayers . "</b></td>
  </tr>
  </table>
  <br>
  <table width=\"120\" border=0 cellspacing=0 cellpadding=3>
  <tr>
    <td align=\"center\" valign=\"bottom\"><div align=center><img src=\"http://".$address."/cms/modules/mod_realmcore/images/alliance_small.gif\"><br><b><FONT COLOR=blue>Alliance</font></b></div></td>
    <td align=\"center\" valign=\"bottom\"><div align=center><img src=\"http://".$address."/cms/modules/mod_realmcore/images/horde_small.gif\"><br><b><FONT COLOR=red>Horde</font></b></div></td>
  </tr>
  <tr>
    <td align=\"center\" valign=\"bottom\"><b><div align=center>" . $alliance . "</div></b></td>
    <td align=\"center\" valign=\"bottom\"><b><div align=center>" . $horde . "</div></b></td>
  </tr>
</table>";
?>
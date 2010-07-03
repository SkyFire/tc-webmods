<?php
$Hostname = '192.168.1.50';
$Username = 'root';
$Password = 'mysql42bgzs';
$CharacterDatabase = 'characters'; // must contains characters table
$RealmDatabase = 'realmd';  // must contains zone_coordinates and accounts table
$TrinityDatabase = 'world';  // for uptime stats
$ManagerDatabase = 'mmfpm'; // Used MiniManager's 'dbc_zones' table to get zone names
$RealmName = 'Draco WoW';
$DatabaseEncoding = 'utf8';

$Output = WoWEmuStat();
 Header('Content-Type: text/xml');   // Needed only for direct generation of xml file
 Header('Content-Length: '.strlen($Output)); // Needed only for direct generation of xml file
echo($Output);
function WoWEmuStat()
{
  global $Hostname, $Username, $Password, $CharacterDatabase, $EmuVersion, $Owner, $RealmName, $realm_db, $DatabaseEncoding, $RealmDatabase, $TrinityDatabase;
 
  $realm_db = mysql_connect($Hostname, $Username, $Password);
    mysql_select_db($RealmDatabase, $realm_db);
  $db_result = mysql_query("SET NAMES $DatabaseEncoding", $realm_db);
  
  $Trinity_db = mysql_connect($Hostname, $Username, $Password, TRUE);
    mysql_select_db($CharacterDatabase, $Trinity_db);
  $db_result = mysql_query("SET NAMES $DatabaseEncoding", $Trinity_db);
  
  $manager_db = mysql_connect($Hostname, $Username, $Password);
    mysql_select_db($ManagerDatabase, $manager_db);
  $db_result = mysql_query("SET NAMES $DatabaseEncoding", $manager_db);
  
  $uptime_query = mysql_query("SELECT * FROM $RealmDatabase.`uptime` ORDER BY `starttime` DESC LIMIT 1", $Trinity_db)or die(mysql_error()); 
  $uptime_results = mysql_fetch_array($uptime_query); 
  $maxplayers =  $uptime_results['maxplayers'];
  if ($uptime_results['uptime'] > 86400) { //days
    $uptime =  round(($uptime_results['uptime'] / 24 / 60 / 60),2)." Days";
  } elseif($uptime_results['uptime'] > 3600) { //hours
    $uptime =  round(($uptime_results['uptime'] / 60 / 60),2)." Hours";
  } else { //minutes
    $uptime =  round(($uptime_results['uptime'] / 60),2)." Minutes";
  }
  
  $dbversion_query = mysql_query("SELECT * FROM $TrinityDatabase.`version` LIMIT 1", $Trinity_db)or die(mysql_error()); 
  $dbversion_results = mysql_fetch_array($dbversion_query); 
  $dbversion =  $dbversion_results['version'];
  $player_query = mysql_query("SELECT (SELECT COUNT(guid) FROM $CharacterDatabase.`characters` WHERE race IN(2,5,6,8,10) AND `online`='1') as horde, (SELECT COUNT(guid) FROM $Database.`characters` WHERE race IN(1,3,4,7,11) AND `online`='1') as alliance FROM $Database.`characters`", $Trinity_db)or die(mysql_error()); 
  $player_results = mysql_fetch_array($player_query); 
  $horde =  $player_results['horde'];
  $alliance =  $player_results['alliance'];
  $total = $horde + $alliance;
   
   
$Result =
'<?xml version="1.0" encoding="UTF-8" ?>'."\r\n".
'<?xml-stylesheet type="text/xsl" href="stat.xsl" ?>'."\r\n".
"  <serverpage>\r\n".
"    <status>\r\n".
"      <platform>".$dbversion."</platform>\r\n".
"      <servername>".$RealmName."</servername>\r\n".
"      <uptime>".$uptime."</uptime>\r\n".
"      <oplayers>".$total."</oplayers>\r\n".
"      <alliance>".$alliance."</alliance>\r\n".
"      <horde>".$horde."</horde>\r\n".
"      <peakcount>".$maxplayers."</peakcount>\r\n".
"    </status>\r\n".
"    <gms>\r\n";
  $db_result = mysql_query("SELECT * FROM `characters` WHERE `online`='1' AND `extra_flags` > 4 ORDER BY `name`", $Trinity_db)or die(mysql_error()); //only grabs GMs with their GM tag turned on
    while($result = mysql_fetch_array($db_result)) {
    // Get GM level by character
    $db_result2 = mysql_query("SELECT gmlevel FROM `account_access` WHERE `id`='".$result['account']."'", $realm_db);
    $result2 = mysql_fetch_array($db_result2);
           $Result .= "      <gmplr>\r\n".
    "        <name>".$result['name']."</name>\r\n".
    "        <race>".$result['race']."</race>\r\n".
    "        <class>".$result['class']."</class>\r\n".
    "        <gender>".$result['gender']."</gender>\r\n".
    "        <level>".$result['level']."</level>\r\n".
    "        <map>".$result['map']."</map>\r\n". 
    "        <areaid>".$result['zone']."</areaid>\r\n".  //requires extra table not in Trinity by default (zone_coordinates)
    "        <ping>".$result['latency']."</ping>\r\n".  //ping not stored in DB by Trinity at this time.
    "        <permissions>".$result2['gmlevel']."</permissions>\r\n".
    //"        <ip>178.12.14.2</ip>\r\n".  //no need for IPs
    "      </gmplr>\r\n";
  }
$Result .= "    </gms>\r\n";
$Result .= "    <sessions>\r\n";
 
  $db_result = mysql_query("SELECT * FROM `characters` WHERE `online`='1' AND `extra_flags` <= 4 ORDER BY `name`", $Trinity_db)or die(mysql_error());
    
    while($result = mysql_fetch_array($db_result))
    {
           $Result .= "      <plr>\r\n".
    "        <name>".$result['name']."</name>\r\n".
    "        <race>".$result['race']."</race>\r\n".
    "        <class>".$result['class']."</class>\r\n".
    "        <gender>".$result['gender']."</gender>\r\n".
    "        <level>".$result['level']."</level>\r\n".
    "        <map>".$result['map']."</map>\r\n". 
    "        <areaid>".$result['zone']."</areaid>\r\n".  //requires extra table not in Trinity by default (zone_coordinates)
    "        <ping>".$result['latency']."</ping>\r\n".  //ping not stored in DB by Trinity at this time.
    //"        <ip>178.12.14.2</ip>\r\n".  //no need for IPs
    "      </plr>\r\n";
  }
  $Result .= "    </sessions>\r\n".
   "  </serverpage>\r\n";
   return($Result);
}
?> 
<?php
// MYSQL Settings
$host            = "your.domain.name";        // IP or domain for mysql connection
$user            = "user";            // db login username
$password        = "pass";        // db login password
$db_chars        = "characters";        // name of the chars db
$db_realm        = "realmd";        // name of the realm db

$limit            = 5;                // how many characters to list in query

$img_base = "mod_richest/c_icons/";                // images path, with trailing slash

$result_name_realm = "Your Server Name";

define(_LIST_RICH,"<br>Top 5 Richest<br><span class=\"navigation\">[GM's are excluded]</span>");

define(_NAME,"name");
define(_RACE,"race");
define(_CLASS,"class");
define(_MONYES,"money");

define(_DETAIL_CHAR, "view char details");


// define these, 
// array, example $def_ru['tauren'][$row['race']] = '';
$def_ru['character_race'][$row['race']] = '';
$def_ru['character_class'][$row['class']] = '';
$site_defines['character_gender'][$gender] = '';


// feel free to remove these functions
// but make sure u remove them all in script
function OpenTitle(){
    echo "<h2>";
}
function CloseTitle() {
    echo "</h2>\r\n";
}

function OpenTable() {
}
function CloseTable() {
}

function OpenBody() {
}
function CloseBody() {
}

function openshapka() {
}
function closeshapka() {

}

OpenTitle();
print "$result_name_realm";
print "<font size=\"2\">"._LIST_RICH."</font>"; // lol </br>
CloseTitle();
OpenTable();

$ConnectDB = mysql_connect($host,$user,$password) OR DIE("'Unable to connect to a database...'");
mysql_select_db($db_chars,$ConnectDB) or die(mysql_error());

$top_char="SELECT `guid` , `account`, `name` , `race` , `class` , `gender` , `level`, `money` FROM `characters` WHERE `account` NOT IN (SELECT `id` FROM ".$db_realm.".`account_access`) ORDER BY `money` DESC LIMIT ".$limit."";

$top_res = mysql_query($top_char) or die(mysql_error());
OpenBody();

while($row = mysql_fetch_array($top_res))
{
    $gender = $row['gender'];
    $race = $def_ru['character_race'][$row['race']];
    $guid = $row['guid'];
    $class = $def_ru['character_class'][$row['class']];
    $gender_post = $site_defines['character_gender'][$gender];
    $top_name = $row['name'];
    $top_money = $row['money'];
    $lvl = $row['level'];
    $money_gold = (int)($top_money/10000);
    $total_money = $top_money - ($money_gold*10000);
    $money_silver = (int)($total_money/100);
    $money_cooper = $total_money - ($money_silver*100);
// Match class id with proper class name
    if($row['class'] == 1) {
    	$class_name = "Warrior";
    }
    elseif ($row['class'] == 2) {
    	$class_name = "Paladin";
    }
    elseif ($row['class'] == 3) {
    	$class_name = "Hunter";
    }
    elseif ($row['class'] == 4) {
    	$class_name = "Rogue";
    }
    elseif ($row['class'] == 5) {
    	$class_name = "Priest";
    }
    elseif ($row['class'] == 6) {
    	$class_name = "Death Knight";
    }
    elseif ($row['class'] == 7) {
    	$class_name = "Shaman";
    }
    elseif ($row['class'] == 8) {
    	$class_name = "Mage";
    }
    elseif ($row['class'] == 9) {
    	$class_name = "Warlock";
    }
    elseif ($row['class'] == 11) {
    	$class_name = "Druid";
    }
// Generate race thumbnail picture
if($gender==0) $temp=0;
if($gender==1) $temp=1;
if($row['race'] == 1)  {$raceimg= "<img src='".$img_base."1-$temp.gif' title= \"Human: $lvl\">";} else
if($row['race'] == 3)  {$raceimg= "<img src='".$img_base."3-$temp.gif' title= \"Dwarf: $lvl\">";} else
if($row['race'] == 4)  {$raceimg= "<img src='".$img_base."4-$temp.gif' title= \"Night Elf: $lvl\">";} else
if($row['race'] == 7)  {$raceimg= "<img src='".$img_base."7-$temp.gif' title= \"Gnome: $lvl\">";} else
if($row['race'] == 11) {$raceimg= "<img src='".$img_base."11-$temp.gif' title= \"Dranei: $lvl\">";} else
if($row['race'] == 8)  {$raceimg= "<img src='".$img_base."8-$temp.gif' title= \"Troll: $lvl\">";} else
if($row['race'] == 2)  {$raceimg= "<img src='".$img_base."2-$temp.gif' title= \"Orc: $lvl\">";} else
if($row['race'] == 5)  {$raceimg= "<img src='".$img_base."5-$temp.gif' title= \"Undead: $lvl\">";} else
if($row['race'] == 6)  {$raceimg= "<img src='".$img_base."6-$temp.gif' title= \"Tauren: $lvl\">";} else
if($row['race'] == 10) {$raceimg= "<img src='".$img_base."10-$temp.gif' title= \"Blood Elf: $lvl\">";}
// Generate class thumbnail picture
if($row['class'] == 1)  {$classimg= "<img src='".$img_base."1.gif' title= \"$class_name\">";} else
if($row['class'] == 2)  {$classimg= "<img src='".$img_base."2.gif' title= \"$class_name\">";} else
if($row['class'] == 3)  {$classimg= "<img src='".$img_base."3.gif' title= \"$class_name\">";} else
if($row['class'] == 4)  {$classimg= "<img src='".$img_base."4.gif' title= \"$class_name\">";} else
if($row['class'] == 5)  {$classimg= "<img src='".$img_base."5.gif' title= \"$class_name\">";} else
if($row['class'] == 6)  {$classimg= "<img src='".$img_base."6.gif' title= \"$class_name\">";} else
if($row['class'] == 7)  {$classimg= "<img src='".$img_base."7.gif' title= \"$class_name\">";} else
if($row['class'] == 8)  {$classimg= "<img src='".$img_base."8.gif' title= \"$class_name\">";} else
if($row['class'] == 9)  {$classimg= "<img src='".$img_base."9.gif' title= \"$class_name\">";} else
if($row['class'] == 11) {$classimg= "<img src='".$img_base."11.gif' title= \"$class_name\">";}

print "<table align=\"center\" cellSpacing=\"0\" cellPadding=\"2\" width=\"100%\" border=\"0\" class=\"news-table-dark\">
<tr>
<td align=\"left\"><b>$top_name</b></td>
<td align=\"right\" valign=\"middle\">$raceimg $classimg</td>
</tr>
</table>
<table align=\"center\" cellSpacing=\"0\" cellPadding=\"2\" width=\"100%\" border=\"0\" class=\"news-table-light\">
<tr>
<td align=\"right\" valign=\"top\">
<b>".$money_gold."</b><img src='".$img_base."gold.gif'>
<b>".$money_silver."</b><img src='".$img_base."silver.gif'>
<b>".$money_cooper."</b>
<img src='".$img_base."copper.gif'></td>
</tr>
</table><br>";
}
//print "<br>";
mysql_free_result($top_res);
mysql_close($ConnectDB);
CloseBody();
CloseTable();

?>
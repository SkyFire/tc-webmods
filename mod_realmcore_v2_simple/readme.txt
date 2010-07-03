Installation: Open file "mod_realmcore.php" with you favourite editor and edit lines as described in the file itself.

==================================================
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

// MySQL settings
$WoWHostname = "x.x.x.x"; // MySQL server address
$WoWUsername = "root"; // MySQL username
$WoWPassword = "mysql42bgzs"; // MySQL password
$CharacterDatabase = 'characters'; // TC characters database
$RealmDatabase = 'realmd'; // TC relamd database
$WorldDatabase = 'world'; // TC world database
$CharacterDatabaseEncoding = 'utf8'; // database character encoding
==================================================

Once done, save and close the file.
Archive the whole directory "mod_realmcore" to file named "mod_realmcore_v2.zip".
Go to you website and login to Joomla Administrator, then go to "Extensions -> Install/Uninstall".
Now browse to your *.zip as "Upload Package File" and hit "Upload".
If you didnt change anything else it should say "Module Installed Successfuly".
Now go to "Extensions -> Module Manager" and search for "Server Status" module, click on its name in the list to access settings.
Set "Position:" to either left or right (nothing else will work at the moment, sorry).
Set "Order:" to be the last item listed and don't forget to set "Enabled:" to Yes.
Click "Save" and voila, you have Server Status on your Joomla page.
++++++++++++++++++++++++++++++++++++++++++++++++++
You can easily use this script on any php powered website.
Extract the zip file somewhere on your PC and edit them properly like written above. Then copy the whole folder on your web server.
Then you simply include "mod_realmcore.php" where you want it to be shown on the page.

Example include on index.php page:

<table width="180" border="0" cellpadding="5" cellspacing="0">
        <tr>
        <td align="center" valign="middle">
        <?php 
        include 'mod_realmcore/mod_realmcore.php';
        ?>
        </td>
        </tr>
</table>
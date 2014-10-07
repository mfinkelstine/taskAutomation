<?php

#include 'function.php';

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = 'a1b2c3';
$dbname = 'automation';

global $link;
$link = mysql_connect($dbhost, $dbuser, $dbpass) or die('Error connecting to mysql');

mysql_select_db($dbname);
mysql_check_connection($link, $dbname);

function mysql_check_connection($link, $dbname) {
	//echo "</br><label style=\"color:red\"> Checking Database Conectivity "  . mysql_error(). "</label>";
	if (!mysql_ping($link)) {
		echo "Failed to connect to MySQL: " . $dbname;
		nl(1);
		exit;
	} //else {
	//echo "<label style=\"color:blue\"> Database Connected : " .$dbname." </label>";
	//}
}
return $link;
?>

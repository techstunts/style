<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = 'mysqlpass';
$dbname = 'istylrwd_istyleyou';
$conn = mysql_connect($dbhost, $dbuser, $dbpass);
if(! $conn )
{
  die('Could not connect: ' . mysql_error());
}
else{
	mysql_select_db($dbname);
}
?>
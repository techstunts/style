<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'istylrwd_istyleyou';
$conn = mysql_connect($dbhost, $dbuser, $dbpass);
if(! $conn )
{
  die('Could not connect: ' . mysql_error());
}
else{
	mysql_select_db($dbname);
}


$debug = false;
if($debug) {
    $base = substr(basename($_SERVER['REQUEST_URI']), 0, strpos(basename($_SERVER['REQUEST_URI']), ".php"));
    $log_file = "debug/" . $base . ".log";
    file_put_contents($log_file, PHP_EOL . var_export($_REQUEST, true), FILE_APPEND);
}



?>

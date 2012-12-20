<?php 
session_start();
ob_start();

$mysql_server = "localhost";
$mysql_user = "precis53_hwaj";
$mysql_password = "panda!!";
$mysql_database = "precis53_roomiou"; //CORRECT THIS

$connection = mysql_connect("$mysql_server","$mysql_user","$mysql_password") or die ("Unable to establish a DB connection");
$db = mysql_select_db("$mysql_database") or die ("Unable to establish a DB connection");
?>
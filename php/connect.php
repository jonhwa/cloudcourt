<?php 
session_start();
ob_start();

function pg_connection_string_from_database_url() {
	extract(parse_url($_ENV["DATABASE_URL"]));
	return "user=$user password=$pass host=$host dbname=" . substr($path, 1);
}

//Establish the connection
$pg_conn = pg_connect(pg_connection_string_from_database_url());
?>
<?php
require('connect.php');

$event_id = $_GET['id'];
$query = "SELECT * FROM courtschedule WHERE schedule_id='$event_id'";
$result = pg_query($query);
$row = pg_fetch_assoc($result);
$start = $row['start_time'];
$end = $row['end_time'];
$member = $row['member_id1'];
$name = getName($member);

//Make it XML output so jQuery functions can be used on it
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="iso-8859-1"?><data>';
echo '<start>'.$start.'</start><end>'.$end.'</end>';
echo '<member>'.$name.'</member>';
echo '</data>';
?>
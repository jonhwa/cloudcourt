<?php
require('connect.php');

$event_id = $_GET['event_id'];

//Get start and end times as passed by AJAX function
$startDay = $_GET['startday'];
$startMonth = $_GET['startmonth'];
$startYear = $_GET['startyear'];
$startHour = $_GET['starthour'];
$startMin = $_GET['startmin'];
$endDay = $_GET['endday'];
$endMonth = $_GET['endmonth'];
$endYear = $_GET['endyear'];
$endHour = $_GET['endhour'];
$endMin = $_GET['endmin'];

//Construct postgresql start and end timestamps
$start = $startYear.'-'.$startMonth.'-'.$startDay.' '.$startHour.':'.$startMin.':00';
$end = $endYear.'-'.$endMonth.'-'.$endDay.' '.$endHour.':'.$endMin.':00';

//Change reservation's start and end times
$query = "UPDATE courtschedule SET start_time = '$start', end_time = '$end' WHERE schedule_id = '$event_id'";
pg_query($query);
?>
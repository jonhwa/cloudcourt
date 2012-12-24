<?php
/*require('connect.php');
require('functions.php');

$member_id = $_GET['member_id'];
$club_id = $_SESSION['user_id'];

//Make it XML output so jQuery functions can be used on it
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="iso-8859-1"?><data>';

//Test if member_id is a valid integer
if (!is_int(intval($member_id))) {
	echo '<error>Member ID has to be an integer</error>';
} else {
	//Test if member_id exists in the database
	$query = "SELECT first_name FROM members WHERE member_id = '$member_id'";
	$result = pg_query($query);
	if (pg_num_rows($result) != 1) {
		echo '<error>Member ID cannot be found</error>';
	} else {
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

		$query = "INSERT INTO courtschedule (club_id, start_time, end_time, member_id1) VALUES ('$club_id', '$start', '$end', '$member_id')":
		$result = pg_query($query);

		//Get the ID of the inserted reservation and return it
		$event_id = pg_last_oid($result);

		$name = getName($member_id);
		echo '<id>'.$event_id.'</id><name>'.$name.'</name>';
	}
}
echo '</data>';*/
echo '<error>hello</error>';
?>
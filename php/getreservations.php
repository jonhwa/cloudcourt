<?php
require("php/connect.php");
$user = $_SESSION['user_id'];
echo 'hello.';
exit;

//Get date as passed in from AJAX function
$month = $_GET['month'];
$day = $_GET['day'];
$year = $_GET['year'];

//Construct postgresql timestamp and interval
$timestamp = $year.'-'.$month.'-'.$day.' 00:00:00';
$interval = '30 days';

//Query all court reservations within $interval of the current day
$query = "SELECT * FROM courtschedule WHERE @(start_time - timestamp '$timestamp') <= interval '$interval' AND club_id = '$user'";
$result = pg_query($query);
for ($row = pg_fetch_assoc($result)) {
	echo '<div>';
	$id = $row['schedule_id'];
	$start_time = $row['start_time'];
	$end_time = $row['end_time'];
	echo '<span class="id">'.$id.'</span><span class="start">'.$start_time.'</span><span class="end">'.$end_time.'</span>';
	
	//Loop through all four member_id fields, printing out the relevant ones
	for ($i = 0; $i < 4; $i++) {
		$n = $i + 1;
		$string = 'member_id'.$n;
		$member = $row[$string];
		$name = getName($member);
		echo '<span class="member_id">'.$member.'</span><span class=member_name">'.$name.'</span>';
	}
	echo '</div>';
}

function getName($member_id) {
	$query = "SELECT first_name, last_name FROM members WHERE member_id='$member_id'";
	$result = pg_query($query);
	$row = pg_fetch_assoc($result);
	$name = $row['first_name'].' '.$row['last_name'];
	return $name;
}

?>
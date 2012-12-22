<?php
require("connect.php");
$user = $_SESSION['user_id'];

//Get date as passed in from AJAX function
$month = $_GET['month'];
$day = $_GET['day'];
$year = $_GET['year'];

//Construct postgresql timestamp and interval
$timestamp = $year.'-'.$month.'-'.$day.' 00:00:00';
$interval = '30 days';

//Make it XML output so jQuery functions can be used on it
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="iso-8859-1"?><data>';

//Query all court reservations within $interval of the current day
$query = "SELECT * FROM courtschedule WHERE (start_time - timestamp '$timestamp' <= interval '$interval' OR timestamp '$timestamp' - start_time <= interval '$interval') AND club_id = '$user'";
$result = pg_query($query);
while ($row = pg_fetch_assoc($result)) {
	echo '<div>';
	$id = htmlspecialchars($row['schedule_id']);
	$start_time = htmlspecialchars($row['start_time']);
	$end_time = htmlspecialchars($row['end_time']);
	echo '<id>'.$id.'</id><start>'.$start_time.'</start><end>'.$end_time.'</end>';
	
	//Just get the first member for display purposes
	$member_id = htmlspecialchars($row['member_id1']);
	$name = htmlspecialchars(getName($member_id));
	echo '<memberid>'.$member_id.'</memberid><membername>'.$name.'</membername>';

	//Loop through all four member_id fields, printing out the relevant ones
	/*for ($i = 0; $i < 4; $i++) {
		$n = $i + 1;
		$string = 'member_id'.$n;
		$member = htmlspecialchars($row[$string]);
		
		if ($member != '') {
			$name = htmlspecialchars(getName($member));
			echo '<memberid>'.$member.'</memberid><membername>'.$name.'</membername>';
		}
	}*/
	echo '</div></data>';
}

function getName($member_id) {
	$query = "SELECT first_name, last_name FROM members WHERE member_id='$member_id'";
	$result = pg_query($query);
	$row = pg_fetch_assoc($result);
	$name = $row['first_name'].' '.$row['last_name'];
	return $name;
}

?>
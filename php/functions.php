<?php
function getName($member_id) {
	$query = "SELECT first_name, last_name FROM members WHERE member_id='$member_id'";
	$result = pg_query($query);
	$row = pg_fetch_assoc($result);
	$name = $row['first_name'].' '.$row['last_name'];
	return $name;
}
?>
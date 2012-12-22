<?php
	include_once("php/connect.php");
	if (!isset($_SESSION['user_id'])) {
		header("Location: index.php");
	}
	$user = $_SESSION['user_id'];

	$courts = array();
	$courtschedule = array();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title>CloudCourt</title>

	<link rel="stylesheet" type="text/css" href="css/reset.css"/>
	<link rel="stylesheet" type="text/css" href="css/960_24_col.css"/>
	<link rel="stylesheet" type="text/css" href="css/fullcalendar.css"/>

	<script type="text/javascript" src="js/jquery-1.8.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.23.custom.min.js"></script>
	<script type="text/javascript" src="js/fullcalendar.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#calendar').fullCalendar({
				height: 700,
				defaultView: 'agendaWeek',
				header: {
					left: 'prev title',
					center: 'agendaWeek,agendaDay',
					right: 'today next'
				},
				allDaySlot: false,
				defaultEventMinutes: 90,

				events: function(start, end, callback) {
					var today = new Date();
					$.ajax({
						url: 'php/getreservations.php',
						dataType: 'xml',
						data: {
							day: today.getDate(),
							month: today.getMonth() + 1,
							year: today.getFullYear(),
						},
						error: function(jqXHR, textStatus, errorThrown) {
							alert('AJAX call failed: ' + textStatus + ' ' + errorThrown);
						},
						success: function(data) {
							alert('Success: ' + data);
							var events = [];
							console.log(data);
							alert('Text1: ' + $(data).find('data').find('div').attr('start') + 'Text2: ' + $(data).find('div').attr('start'));
							$(data).find('div').each(function() {
								var event_id = $(".id").text();
								var startTime = $(".start").text();
								alert('start: ' + startTime);
								var endTime = $(".end").text();
								var memberID = $(".member_id").text();
								var memberName = $(".member_name").text();
								events.push({
									id: event_id,
									title: 'Member reservation by ' + memberName,
								})
							});
						}
					})
				}
			})
		})
	</script>
</head>

<body>
	<div id="header">
		<!-- LOGO -->
		<h1>Logo</h1>

		<!-- NAV -->
		<ul id="nav">
			<li><a href="dashboard.php">Dashboard</a></li>
			<li><a href="php/logout.php">Logout</a></li>
		</ul>
	</div>

	<div id="main">
		<!-- COURT SCHEDULE -->
		<div id="schedule">
			<div id="dateheader">
				<span class="link leftalign">Yesterday</span>
				<span class="centeralign"><!--DATE--></span>
				<span class="link rightalign">Tomorrow</span>
			</div>
			<div id="calendar">
				<?php
					/*$week = 60 * 60 * 24 * 7;
					$query = "SELECT * FROM courtschedule WHERE timestart > '$week' ORDER BY timestart asc";
					$result = mysql_query($query) or die(mysql_error());
					while ($row = mysql_fetch_assoc($result)) {
						$memberid = $row['member_id'];
						$name = $row['name'];
						$timestart = $row['timestart'];
						$timeend = $row['timeend'];
						//Create a "reservation" object and put it in an array, with the court number as the key
					}

					$query = "SELECT num_courts FROM clubs WHERE id='$user'";
					$result = mysql_query($query) or die(mysql_error());
					$num_courts = mysql_fetch_assoc($result)['num_courts'];
					for ($i = 0; $i < $num_courts; $i++) {
						echo '<div class="court">';
						//Loop through all reservations for the court and print them out
						echo '</div>';
					}*/
				?>
			</div>
		</div>

		<!-- RANKING LADDERS -->


		<!-- TOURNAMENTS -->


	</div>
</body>
</html>
<?php
	include_once("php/connect.php");
	if (!isset($_SESSION['user_id'])) {
		header("Location: index.php");
	}
	$user = $_SESSION['user_id'];

	$query = "SELECT open_time, close_time FROM clubs WHERE club_id='$user'";
	$result = pg_query($query);
	$row = pg_fetch_assoc($result);
	$open = $row['open_time'];
	$close = $row['close_time'];

	$hour = substr($open, 0, 2);
	$minute = substr($open, 2, 2);
	$open = $hour.':'.$minute;

	$hour = substr($close, 0, 2);
	$minute = substr($close, 2, 2);
	$close = $hour.':'.$minute;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title>CloudCourt</title>

	<link rel="stylesheet" type="text/css" href="css/reset.css"/>
	<link rel="stylesheet" type="text/css" href="css/960_24_col.css"/>
	<link rel="stylesheet" type="text/css" href="css/south-street.css"/>
	<link rel="stylesheet" type="text/css" href="css/fullcalendar.css"/>

	<script type="text/javascript" src="js/jquery-1.8.3.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.9.2.custom.min.js"></script>
	<script type="text/javascript" src="js/fullcalendar.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#calendar').fullCalendar({
				theme: true,
				defaultView: 'agendaWeek',
				header: {
					left: 'prev title',
					center: 'agendaWeek,agendaDay',
					right: 'today next'
				},
				editable: true,
				minTime: $('#open').value(),
				maxTime: $('#close').value(),
				allDaySlot: false,
				allDayDefault: false,
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
							var events = [];
							$(data).find('div').each(function() {
								var event_id = $(this).find('id').text();
								var startTime = $(this).find('start').text();
								startTime = startTime.replace(' ', 'T');
    							startTime = startTime + 'Z';
								var endTime = $(this).find('end').text();
								endTime = endTime.replace(' ', 'T');
    							endTime = endTime + 'Z';
								var memberID = $(this).find('memberid').text();
								var memberName = $(this).find('membername').text();
								events.push({
									id: event_id,
									title: 'Member reservation by ' + memberName,
									start: startTime,
									end: endTime,
									backgroundColor: '#67B021',
									borderColor: '#327e04'
								});
							});
							callback(events);
						}
					});
				}
			});
		})
	</script>
</head>

<body>
	<input type="hidden" id="open" value="<?php echo $open ?>"/>
	<input type="hidden" id="close" value="<?php echo $close ?>"/>
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
			<div id="calendar" style="width: 1100px;"></div>
		</div>

		<!-- RANKING LADDERS -->


		<!-- TOURNAMENTS -->


	</div>
</body>
</html>
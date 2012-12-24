<?php
	include_once("php/connect.php");
	if (!isset($_SESSION['user_id'])) {
		header("Location: index.php");
	}
	$user = $_SESSION['user_id'];

	//Query to get open time, close time, and res_min/res_max in minutes
	$query = "SELECT EXTRACT(EPOCH FROM res_min)/60 AS min, EXTRACT (EPOCH FROM res_max)/60 AS max, open_time, close_time FROM clubs WHERE club_id='$user'";
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

	$resMin = $row['min'];
	$resMax = $row['max'];
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
	<link rel="stylesheet" type="text/css" href="css/dashboard.css"/>

	<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.9.2.custom.min.js"></script>
	<script type="text/javascript" src="js/fullcalendar.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			var calendar, start, end;

			//Initialize dialog
			$('#dialog').dialog({ 
				autoOpen: false,
				resizable: false,
				title: 'Create Reservation',
				buttons: {
					"Create": function() {
						var member_id = $('#member').val();
						if (member_id == '') {
							calendar.fullCalendar('unselect');
						} else {
							//Submit new reservation via AJAX
							$.ajax({
								url: '',
								dataType: 'xml',
								data: {

								},
								error: function(jqXHR, textStatus, errorThrown) {
									alert('AJAX call failed: ' + textStatus + ' ' + errorThrown);
								},
								success: function(data) {

								}
							});

							calendar.fullCalendar('renderEvent',
								{
									id: '',
									title: '',
									start: '',
									end: '',
									backgroundColor: '#67B021',
									borderColor: '#327e04'
								},
								true //make the event "stick" 
							);
						}

						$(this).dialog('close');
					},
					Cancel: function() {
						calendar.fullCalendar('unselect');
						$(this).dialog('close');
					}
				}
			});

			//Build calendar
			calendar = $('#calendar').fullCalendar({
				//Set defaults
				theme: true,
				defaultView: 'agendaWeek',
				header: {
					left: 'prev title',
					center: 'agendaWeek,agendaDay',
					right: 'today next'
				},
				editable: true,
				allDaySlot: false,
				allDayDefault: false,
				defaultEventMinutes: 90,

				//Set min and max time according to specified opening and closing times
				minTime: $('#open').val(),
				maxTime: $('#close').val(),

				//Generate list of events from AJAX call to the database table courtschedule
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
				},
				
				//When a user selects a slot on the calendar, ask to create an event
				selectable: true,
				selectHelper: true,
				select: function(startDate, endDate) {
					start = new Date(startDate);
					end = new Date(endDate);

					var string = calendar.formatDate(start, "MMM d h:mmtt");
					string += " - " + calendar.formatDate(end, "h:mmtt");
					$('#when').text('When: ' + string);

					$('#dialog').dialog("open");
				},

				//When an event is dragged and dropped, submit change to database
				eventDrop: function(event, dayDelta, minuteDelta, allDay, revertFunc) {
					moveReservation(event, revertFunc);
				},

				//When an event is resized, submit change to database
				eventResize: function(event, dayDelta, minuteDelta, revertFunc) {
					moveReservation(event, revertFunc);
				}
			});
		})

		function moveReservation(event, revertFunc) {
			var event_id = event['id'];
			
			if (eventIsValid(event)) {
				start = new Date(event['start']);
				end = new Date(event['end']);

				//Use AJAX call to submit changes to the event without refreshing the paeg
				$.ajax({
					url: 'php/movereservation.php',
					data: {
						event_id: event_id,
						startday: start.getDate(),
						startmonth: (start.getMonth() + 1),
						startyear: start.getFullYear(),
						starthour: start.getHours(),
						startmin: (start.getMinutes()<10?'0':'') + start.getMinutes(),
						endday: end.getDate(),
						endmonth: (end.getMonth() + 1),
						endyear: end.getFullYear(),
						endhour: end.getHours(),
						endmin: (end.getMinutes()<10?'0':'') + end.getMinutes()
					},
					error: function(jqXHR, textStatus, errorThrown) {
						alert('There was a problem moving the reservation: ' + errorThrown);
					}
				});
			} else {
				alert("The reservation can't be moved there.");
				revertFunc();
			}
		}

		function eventIsValid(event) {
			//Initialize constraints for the specific club
			var minHour = parseInt($('#open').val().slice(0, 2));
			var minMin = parseInt($('#open').val().slice(3));
			var minInMin = minHour * 60 + minMin;

			var maxHour = parseInt($('#close').val().slice(0, 2));
			var maxMin = parseInt($('#close').val().slice(3));
			var maxInMin = maxHour * 60 + maxMin;

			//Get the start and end times
			var startTime = event['start'];
			var endTime = event['end'];

			var start = new Date(startTime);
			var startHour = start.getHours();
			var startMin = start.getMinutes();
			var startInMin = startHour * 60 + startMin;

			var end = new Date(endTime);
			var endHour = end.getHours();
			var endMin = end.getMinutes();
			var endInMin = endHour * 60 + endMin;

			//Compare start and end times to make sure they are within the open and closing hours
			if (startInMin < minInMin || endInMin > maxInMin) {
				alert("start: " + startInMin + " min: " + minInMin + " end: " + endInMin + " max: " + maxInMin);
				return false;
			}

			//Get max and min reservation length constraints (in minutes)
			var resMin = parseInt($('#resMin').val());
			var resMax = parseInt($('#resMax').val());

			//Length of the reservation in minutes
			var length = endInMin - startInMin;

			//Compare reservation length with constraints
			if (length < resMin || length > resMax) {
				alert("length: " + length + " min: " + resMin + " max: " + resMax);
				return false;
			}

			//If passed all the above tests, return true
			return true;
		}
	</script>
</head>

<body>
	<!-- Hidden fields for use in FullCalendar setup -->
	<input type="hidden" id="open" value="<?php echo $open ?>"/>
	<input type="hidden" id="close" value="<?php echo $close ?>"/>
	<input type="hidden" id="resMin" value="<?php echo $resMin ?>"/>
	<input type="hidden" id="resMax" value="<?php echo $resMax ?>"/>
	
	<!-- Div that holds information for the dialog box -->
	<div id="dialog">
		<span id="when"></span></br>
		<span id="member">Member ID: <input type="text" id="memberInput" /></span>
	</div>

	<div id="header">
		<!-- LOGO -->
		<h1>CloudCourt</h1>

		<!-- NAV -->
		<ul id="nav">
			<li><a href="dashboard.php">Dashboard</a></li>
			<li><a href="php/logout.php">Logout</a></li>
		</ul>
	</div>

	<div id="main">
		<!-- COURT SCHEDULE -->
		<div id="body">
			<div id="sidebar"><span class="button" onclick="">Book Court</span></div>
			<div id="calendar"></div>
		</div>

		<!-- RANKING LADDERS -->


		<!-- TOURNAMENTS -->


	</div>
</body>
</html>
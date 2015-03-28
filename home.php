<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link href="stylesheet2.css" rel="stylesheet" type="text/css"/>
	<script>
	</script>
</head>
<body>
	<div class="heading"> PAC Reservations</div>
	<?php
	date_default_timezone_set("GMT");
	$dayOfWeek=date("N");
	$weekName=array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
	$weekNameShort=array("SUN","MON","TUE","WED","THU","FRI","SAT");
	echo'<form action="bookings.php" method="post">
		<button type="submit" name="day" value='.$weekNameShort[($dayOfWeek)%7].'>'.$weekName[($dayOfWeek)%7].'</button>
		<button type="submit" name="day" value='.$weekNameShort[($dayOfWeek+1)%7].'>'.$weekName[($dayOfWeek+1)%7].'</button>
		<button type="submit" name="day" value='.$weekNameShort[($dayOfWeek+2)%7].'>'.$weekName[($dayOfWeek+2)%7].'</button>
		<button type="submit" name="day" value='.$weekNameShort[($dayOfWeek+3)%7].'>'.$weekName[($dayOfWeek+3)%7].'</button>
		<button type="submit" name="day" value='.$weekNameShort[($dayOfWeek+4)%7].'>'.$weekName[($dayOfWeek+4)%7].'</button>
		<button type="submit" name="day" value='.$weekNameShort[($dayOfWeek+5)%7].'>'.$weekName[($dayOfWeek+5)%7].'</button>
		<button type="submit" name="day" value='.$weekNameShort[($dayOfWeek+6)%7].'>'.$weekName[($dayOfWeek+6)%7].'</button>
		<input type="hidden" name="formTest" value=0>
	</form>
		';
	?>
	
</body>
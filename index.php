<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link href="stylesheet2.css" rel="stylesheet" type="text/css"/>
	<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
	<script>
	</script>
</head>
<body>
	<img src="logo.svg" id="logo" width="60%">
	<?php
	date_default_timezone_set("GMT");
	$dayOfWeek=date("N");
	$weekName=array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
	$weekNameShort=array("SUN","MON","TUE","WED","THU","FRI","SAT");
	echo'<form action="bookings.php" method="post">
		<button type="submit" name="day" id="button_1" value="0">'.$weekName[($dayOfWeek)%7].'<br>'.date("d.m.y",time()).'</button>
		<button type="submit" name="day" id="button_2" value="1">'.$weekName[($dayOfWeek+1)%7].'<br>'.date("d.m.y",time()+ (1 * 24 * 60 * 60)).'</button>
		<button type="submit" name="day" id="button_3" value="2">'.$weekName[($dayOfWeek+2)%7].'<br>'.date("d.m.y",time()+ (2 * 24 * 60 * 60)).'</button>
		<button type="submit" name="day" id="button_4" value="3">'.$weekName[($dayOfWeek+3)%7].'<br>'.date("d.m.y",time()+ (3 * 24 * 60 * 60)).'</button>
		<button type="submit" name="day" id="button_5" value="4">'.$weekName[($dayOfWeek+4)%7].'<br>'.date("d.m.y",time()+ (4 * 24 * 60 * 60)).'</button>
		<button type="submit" name="day" id="button_6" value="5">'.$weekName[($dayOfWeek+5)%7].'<br>'.date("d.m.y",time()+ (5 * 24 * 60 * 60)).'</button>
		<button type="submit" name="day" id="button_7" value="6">'.$weekName[($dayOfWeek+6)%7].'<br>'.date("d.m.y",time()+ (6 * 24 * 60 * 60)).'</button>
		<input type="hidden" name="formTest" value=0>
	</form>
		';
	?>

</body>
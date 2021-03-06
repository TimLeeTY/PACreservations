<?php
session_start();
$email = $_SESSION["email"];
$start = $_SESSION["startTime"];
$time = $_SESSION["duration"];
$end=$start+$time;
$room = $_SESSION["room"];
$name = $_SESSION["name"];
$day= $_SESSION["day"];
$roomName = array("PAC1"=>"PAC Room 1", "PAC2"=>"PAC Room 2", "PAC3"=>"PAC Room 3", "CHAMBER"=>"the Chamber Music Room");
$dayName= array("MON"=>"Monday", "TUE"=>"Tuesday", "WED"=>"Wednesday", "THU"=>"Thursday", "FRI"=>"Friday", "SAT"=>"Saturday", "SUN"=>"Sunday");
function minute($x){
	if ((15*($x%4))==0){
		return ('00');
	} else{
		return (15*($x%4));
	}
}
$msg='Dear '.$name."\n Thank you for using the PAC booking service. \n You have successfully booked ".$roomname[$room]." starting from ".(floor(($start)/4)+17).':'.minute($start)." till ".(floor(($end)/4)+17).':'.minute($end)." this coming ".$dayName[$day].".\n If you have any questions or wish to cancel your booking, please contact 00tlee@brightoncollege.net. \n\n Best Wishes, \n Tim";
mail($email, "Your Reservation", $msg);
?>
<!DOCTYPE html>
<html>
<head>
	<style>
	body {
		padding:0px;
		margin:0px;
		background-image:url("backdrop.svg");
		background-position:center top;
		font-family:Sans-Serif;
	}
	div.heading{
		height:17vh;
		vertical-align:center;
		color:#FFFFFF;
		font-size:6em;
		font-family: Lobster, Cursive;
		text-align:center;
		background-color: rgba(60,60,60,0.95);
		margin-bottom:1em;
		padding-top:30px;
	}
	</style>
	<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
</head>
<body>
<div class="heading">
<span class="heading">Success</span>	<a href="../PACreservations">
	<svg x="0px" y="0px" width="50px" height="50px" viewBox="0 0 360 360" enable-background="new 0 0 360 360" xml:space="preserve">
	<g>
		<path fill="#FFFFFF" d="M151.9,209.9c0,2.2,0,4.2,0,6.1c0,29.8,0,59.6,0,89.5c0,6.9-0.4,7.3-7.4,7.3c-22.5,0-45,0-67.5,0
			c-6.2,0-6.9-0.7-6.9-6.9c0-36,0-72-0.1-108c0-3.5,1.1-5.8,4-7.9c33.9-23.6,67.7-47.3,101.5-71.1c3.3-2.3,5.8-2.2,9,0.1
			c33.5,23.6,67.1,47.1,100.7,70.5c2.9,2,4.1,4.4,4.1,7.9c-0.1,36.1,0,72.3-0.1,108.5c0,6.2-0.7,6.9-6.9,6.9c-22.5,0-45,0-67.5,0
			c-6.3,0-6.8-0.5-6.8-6.9c0-30.3-0.1-60.6,0.1-91c0-4-0.9-5.5-5.2-5.4c-15.3,0.3-30.7,0.1-46,0.1C155.5,209.6,154,209.8,151.9,209.9
			z"/>
		<path fill="#FFFFFF" d="M179.8,49c1.6,0.8,3.2,1.4,4.5,2.3c45.3,31.7,90.6,63.4,135.8,95.1c4.7,3.3,4.9,4.6,1.6,9.3
			c-5.3,7.7-10.7,15.3-16,22.9c-3.4,4.9-4.8,5.2-9.7,1.7c-37.2-26.1-74.5-52.1-111.6-78.3c-3.5-2.5-5.7-2.2-9,0.1
			c-36.9,26-73.9,51.8-110.8,77.7c-5.9,4.1-6.7,3.9-10.7-1.8c-5.1-7.2-10.1-14.4-15.2-21.7c-3.9-5.6-3.7-6.6,1.7-10.4
			c38.9-27.2,77.7-54.4,116.6-81.6c6.1-4.3,12.3-8.6,18.4-12.9C176.7,50.5,178.2,49.9,179.8,49z"/>
		<path fill="#FFFFFF" d="M288.8,77.6c0,7.5,0.1,15-0.1,22.5c-0.1,1.7-1,4.3-2.3,4.9c-1.3,0.7-4,0.1-5.4-0.8
			c-10.3-7-20.6-14.1-30.6-21.5c-1.6-1.2-2.8-3.8-2.9-5.8c-0.4-7.3-0.1-14.6-0.1-22c0-4.3,2-6.3,6.5-6.2c9.5,0.2,19,0,28.5,0.1
			c5.6,0,6.5,1,6.5,6.8C288.8,62.9,288.8,70.2,288.8,77.6z"/>
	</g>
	</svg>
	</a>
</div>
</body>
</html>
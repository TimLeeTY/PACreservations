<?php 
session_start();
$start = $_SESSION["startTime"];
$time = $_SESSION["duration"];
$room = $_SESSION["room"];
$name = $_SESSION["name"];
$email = $_SESSION["email"];
$day= $_SESSION["day"];
$color= $_SESSION["color"];
$house=$_SESSION["house"];
$year=$_SESSION["year"];
$conn = new mysqli('localhost', 'leetimmy', '______', 'reservations'); //password removed
$flag=0;
for ($i = $start; $i <= ($start+$time); $i++){
	$sql='SELECT booked FROM '.$day.'_'.$room.' WHERE timeID='.$i;
	$result = $conn->query($sql) or die('failed conection');
	$row = $result->fetch_assoc();
	if ($row[booked]==1){
		$flag=1;
		break;
	};
};
if ($flag==1){
	header("Location: error.html");
	die();
} else {
	$sql2='UPDATE '.$day.'_'.$room.' SET name="'.$name.'", booked=1, color="'.$color.'",length='.$time.', email="'.$email.'", house="'.$house.'", year='.$year.' WHERE timeID='.$start;
	if ($conn->query($sql2) === FALSE) {
	    $flag=1;
	}
	for ($i = ($start+1); $i < ($start+$time); $i++){
		$sql3='UPDATE '.$day.'_'.$room.' SET booked=1 WHERE timeID='.$i;
		if ($conn->query($sql3) === FALSE) {
			$flag=1;
			break;
		}
	};
	if($flag){
		header("Location: error.html");
		die();
	} else{
		header("Location: success.php");
		die();
	}
}
?>
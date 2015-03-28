<?php 
session_start();
?>
<!DOCTYPE html>
<html>

<head>
	<title>Submit</title>
	<link href="stylesheet.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<?php 
$start = $_SESSION["startTime"];
$time = $_SESSION["time"];
$room = $_SESSION["room"];
$name = $_SESSION["name"];
$email = $_SESSION["email"];
$day= $_SESSION["day"];
$color= $_SESSION["color"];
$house=$_SESSION["house"];
$year=$_SESSION["year"];
$conn = new mysqli('localhost', 'leetimmy', 'poohpooh', 'reservations');
$flag=0;
for ($i = $start; $i <= ($start+$time); $i++){
	$sql='SELECT booked FROM '.$day.'_'.$room.' WHERE timeID='.$i;
	echo $sql;
	$result = $conn->query($sql) or die('failed conection');
	$row = $result->fetch_assoc();
	if ($row[booked]==1){
		$flag=1;
		break;
	};
};
if ($flag==1){
	echo 'it seems the time slot you chose has been taken';
} else {
	$sql2='UPDATE '.$day.'_'.$room.' SET name="'.$name.'", booked=1, color="'.$color.'",length='.$time.', email="'.$email.'", house="'.$house.'", year='.$year.' WHERE timeID='.$start;
	echo $sql2;
	if ($conn->query($sql2) === TRUE) {
	    echo "Record updated successfully";
	} else {
	    echo "Error updating record: " . $conn->error;
	};
	for ($i = ($start+1); $i < ($start+$time); $i++){
		$sql3='UPDATE '.$day.'_'.$room.' SET booked=1 WHERE timeID='.$i;
		if ($conn->query($sql3) === TRUE) {
		    echo "Record updated successfully";
		} else {
		    echo "Error updating record: " . $conn->error;
			break;
		};
	};
}

?>
</body>
</html>
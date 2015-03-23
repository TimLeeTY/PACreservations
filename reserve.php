<!DOCTYPE html>
<html>

<head>
	<title>Submit</title>
	<script src="jquery-1.11.2.min.js"></script>
	<script src="script.js"></script>
	<link href="stylesheet.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<?php 
$start = $_POST["startTime"];
$time = $_POST["time"];
$room = $_POST["room"];
$name = $_POST["name"];
$email = $_POST["email"];
$day= $_POST["day"];
$color= $_POST["color"];
$conn = new mysqli('localhost', 'leetimmy', 'poohpooh', 'reservations');
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
	echo 'it seems the time slot you chose has been taken';
} else {
	$sql2='UPDATE '.$day.'_'.$room.' SET name="'.$name.'", booked=1, color="'.$color.'",length='.$time.', email="'.$email.'" WHERE timeID='.$start;
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
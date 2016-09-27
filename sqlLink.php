<?php
$servername = "127.0.0.1";
$username = "leetimmy";
$password = "______"; //passowrd removed
$dbname = "reservations";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$roomName = array("PAC1", "PAC2", "PAC3", "CHAMBER");
$weekName=array("SUN","MON","TUE","WED","THU","FRI","SAT");
foreach ($weekName as $day){
	foreach ($roomName as $i){
		$sql = 
		'TRUNCATE TABLE '.$day.'_'.$i;
		if ($conn->query($sql) === TRUE) {
		    echo "Tables refreshed";
		} else {
		    echo "Error: " . $sql . "<br>" . $conn->error;
		};
		$sql='INSERT INTO '.$day.'_'.$i.' (booked, length) VALUES (0,0)';
		for ($x=0; $x<=18; $x++) {
			if ($conn->query($sql) === TRUE) {
			    echo "New records created successfully";
			} else {
			    echo "Error: " . $sql . "<br>" . $conn->error;
			};
		};
	}
}


$conn->close();
?>
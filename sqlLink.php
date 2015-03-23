<?php
$servername = "127.0.0.1";
$username = "leetimmy";
$password = "poohpooh";
$dbname = "reservations";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$roomName = array("PAC1", "PAC2", "PAC3", "CHAMBER");
foreach ($roomName as $i){
	$sql = 
	'CREATE TABLE Mon_'.$i.' (
	timeID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	booked BOOLEAN,
	length INT(6),
	name VARCHAR(30),
	email VARCHAR(50),
	house VARCHAR(30),
	year VARCHAR(30),
	color VARCHAR(30),
	reg_date TIMESTAMP);';
	if ($conn->query($sql) === TRUE) {
	    echo "New records created successfully";
	} else {
	    echo "Error: " . $sql . "<br>" . $conn->error;
	};
	$sql='INSERT INTO Mon_'.$i.' (booked, length) VALUES (0,0)';
	for ($x=0; $x<=18; $x++) {
		if ($conn->query($sql) === TRUE) {
		    echo "New records created successfully";
		} else {
		    echo "Error: " . $sql . "<br>" . $conn->error;
		};
	};


	
}


$conn->close();
?>
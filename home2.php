<!DOCTYPE html>
<html>
<head>
	<title>Monday</title>
	<link href="stylesheet.css" rel="stylesheet" type="text/css"/>
	<script>
	function showForm(x,room) {
	   	var selectID="select"+x +"_"+room;
		var formID="form_"+room;	
		document.getElementById(selectID).selected = true;
		document.getElementById(formID).style.display="inline";
	};
	function closeForm(room) {
		var formID="form_"+room;
		document.getElementById(formID).style.display="none";
	}
	
	</script>
</head>
<body>
	
<?php
	$day='MON';
	$servername = "localhost";
	$username = "leetimmy";
	$password = "poohpooh";
	$dbname = "reservations";
	$conn = new mysqli($servername, $username, $password, $dbname);
	$roomName = array("PAC1"=>"PAC Room 1", "PAC2"=>"PAC Room 2", "PAC3"=>"PAC Room 3", "CHAMBER"=>"Chamber Music Room");
	echo'<h1>'.$day.'</h1>';
	function createForm($room){
		global $conn, $day, $roomName;
		$result = $conn->query('SELECT timeID, booked FROM '.$day.'_'.$room) or die('failed conection');
		echo'<div class="formBack" id="form_'.$room.'" >
			<div class="formFront">
			<form action="reserve.php" method="post" >
			<h2>'.$roomName[$room].'</h1>
			<input type="hidden" name="room" value='.$room.'>
			<input type="hidden" name="day" value='.$day.'>
			Name: <input type="text" name="name"> <br>
			E-mail: <input type="text" name="email"><br>
			Color: <input type="color" name="color" value="#FFFFFF"><br>
			Start Time: <select name="startTime">';
		if ($result->num_rows > 0) {
		    while($row = $result->fetch_assoc()) {
				if ($row["booked"]==0) {
					echo '<option value='.$row[timeID].' id="select'.$row[timeID].'_'.$room.'">'.$row[timeID].'</option>'."\n\t\t\t";
				}			
			}
		};
	echo '</select><br>
		Duration: <select name="time">
			<option value="1">15</option>
			<option value="2">30</option>
			<option value="3">45</option>
			<option value="4">60</option>
		</select> minutes
		<button type="button" onclick="closeForm(\''.$room.'\')" id=cancel>Cancel</button>
		<button type="submit" id=submit>Submit</button>
		</form>
		</div></div>';
	};
	foreach (array("PAC1","PAC2","PAC3","CHAMBER") as $room) {
		createForm($room);
	};
echo'
	<div id="tableDiv">
	<table style="display:visible">
	<tr class= "times ">
	<td class="headcol" border="0"></td>
	<th>17:00</th>
	<th>17:15</th>
	<th>17:30</th>
	<th>17:45</th>
	<th>18:00</th>
	<th>18:15</th>
	<th>18:30</th>
	<th>18:45</th>
	<th>19:00</th>
	<th>19:15</th>
	<th>19:30</th>
	<th>19:45</th>
	<th>20:00</th>
	<th>20:15</th>
	<th>20:30</th>
	<th>20:45</th>
	<th>21:00</th>
	<th>21:15</th>
	<th>21:30</th>
	</tr>';
	function createRow($room){
		global $conn, $roomName, $day;
		echo '<tr> <th class="headcol"><div class="headSpan">'.$roomName[$room].'</div></th>'."\n" ;
		$result = $conn->query('SELECT timeID, booked, name, email, length, color FROM '.$day.'_'.$room) or die('failed conection');
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()){
				if ($row["booked"]==0) {
					echo '<td onclick="showForm('.$row[timeID].',\''.$room.'\')" style="background-color:white"> <div class="empty"></div></td>'."\n";
				} elseif ($row["length"]>0) {
					echo'<td colspan= '.$row["length"] .'> <div class="occupied" style="background-color:'.$row["color"].'"> <span class="occupiedText">' .$row["name"] .'<br> ' .$row["email"] .'</span> </div> </td>'."\n";
				}
			}
			echo '</tr>';
		}
	}
		
	foreach (array("PAC1","PAC2","PAC3","CHAMBER") as $room) {
		createRow($room);
	};
	echo "</table></div>";
?>
</body>
</html>
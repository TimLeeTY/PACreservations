<!DOCTYPE html>
<html>
<head>
	<title>Timetable</title>
	<link href="stylesheet.css" rel="stylesheet" type="text/css"/>
	<script>
	function showForm(x,room) {
	   	var selectID="select"+x +"_"+room;
		var formID="div_"+room;
		document.getElementById(selectID).selected = true;
		formChange(room);
		document.getElementById(formID).style.display="inline";
	};
	function closeForm(room) {
		var formID="div_"+room;
		document.getElementById(formID).style.display="none";
	};
	
	<?php
	//varible carried from selection in home page, should maybe use a php session instead
	$day=$_POST["day"];
	$servername = "localhost";
	$username = "leetimmy";
	$password = "poohpooh";
	$dbname = "reservations";
	$conn = new mysqli($servername, $username, $password, $dbname);
	$roomName = array("PAC1"=>"PAC Room 1", "PAC2"=>"PAC Room 2", "PAC3"=>"PAC Room 3", "CHAMBER"=>"Chamber Music Room");
	$dayName= array("MON"=>"Monday", "TUE"=>"Tuesday", "WED"=>"Wednesday", "THU"=>"Thursday", "FRI"=>"Friday", "SAT"=>"Saturday", "SUN"=>"Sunday");
	//creates list of timeID=>booked for all rooms in GLOBAL scope 
	//records the number of free spaces looped in a list in an array ${"countlist".$room}
	$roomNameAbrv= array("PAC1","PAC2","PAC3","CHAMBER");
	foreach ($roomNameAbrv as $name){
		${'bookedTime'.$name} = array();
		$result = $conn->query('SELECT timeID, booked FROM '.$day.'_'.$name) or die('failed conection');
		if ($result->num_rows > 0) {
			${"countlist".$name}=array();
		    while ($row = $result->fetch_assoc()) {
				${"bookedTime".$name}=array_merge(${"bookedTime".$name}, array($row["timeID"]=>$row["booked"]));
				if($row["booked"]==0 and count(${"countlist".$name})!=0 and end(${"countlist".$name})!=0){
					${"countlist".$name}[count(${"countlist".$name})-1]+=1;
				}elseif($row["booked"]==1){
					array_push(${"countlist".$name},0);
				}elseif(end(${"countlist".$name})==0 or count(${"countlist".$name})==0){ 
					array_push(${"countlist".$name},1);
				};
			};
		};
	};
	
	//function for creating the number of "duration" options based on ${"countlist".$room}, should output into js function "showForm"
	function createOption($room){
		global ${"countlist".$room};
		$count=0;
		$overall=1;
		foreach(${"countlist".$room} as $i){
			if ($i!=0){
				while ($count<$i){
					echo"\t\t".'if (selected=='.$overall.'){'."\n\t\t\t".'document.getElementById("formTime'.$room.'").options.length=0;'."\n";
					for ($x=1; $x<=min(($i-$count),8); $x++){
						echo"\t\t\t".'document.getElementById("formTime'.$room.'").options['.$x.']=new Option("'.(($x)*15).'", "'.($x).'", false, false);'."\n";
					};
					$count+=1;
					$overall+=1;
					echo "\t\t} \n";
				};
			}else{
				$overall+=1;
			};
			$count=0;
			
		};
	};	
	//function for creating forms for rooms with abbreviated room name of $room
	function createForm($room){
		global $conn, $day, $roomName, $dayName, ${"bookedTime".$room};
		echo'<div class="formBack" id="div_'.$room.'" >
			<div class="formFront">
			<form action="reserve.php" method="post" id="form_'.$room.'" >
			<h2>'.$dayName[$day].', '.$roomName[$room].'</h2>
			<input type="hidden" name="room" value='.$room.'>
			<input type="hidden" name="day" value='.$day.'>
			Name: <input type="text" name="name"> <br>
			E-mail: <input type="text" name="email"><br>
			Color: <input type="color" name="color" value="#FFFFFF"><br>
			Start Time: <select name="startTime" id="formStartTime'.$room.'" onchange="formChange(\''.$room.'\')">';
		foreach (${"bookedTime".$room} as $timeID=>$booked){
			if ($booked==0) {
				echo '<option value='.($timeID+1).' id="select'.($timeID+1).'_'.$room.'">'.($timeID+1).'</option>'."\n\t\t\t";
			};			
		};
		echo '</select><br>
			Duration: <select name="time" id="formTime'.$room.'"> </select> minutes
			<button type="button" onclick="closeForm(\''.$room.'\')" id=cancel>Cancel</button>
			<button type="submit" id=submit>Submit</button>
			</form>
			</div></div>';
	};
	//function for creating rows in table for rooms with abbreviated room name of $room
	function createRow($room){
		global $conn, $roomName, $day;
		echo '<tr> <th class="headcol"><div class="headSpan">'.$roomName[$room].'</div></th>'."\n" ;
		$result = $conn->query('SELECT timeID, booked, name, email, length, color FROM '.$day.'_'.$room) or die('failed conection');
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()){
				if ($row["booked"]==0) {
					if ($row[timeID] % 2 ==0){
						$altColor="rgb(240,240,240)";
					} else {
						$altColor="rgb(255,255,255)";
					};
					echo '<td onclick="showForm('.$row[timeID].',\''.$room.'\')" style="background-color:'.$altColor.'"> <div class="empty"></div></td>'."\n";
				} elseif ($row["length"]>0) {
					echo'<td colspan= '.$row["length"].'> <div class="occupied" style="background-color:'.$row["color"].'"> <span class="occupiedText">'.$row["name"] .'<br> ' .$row["email"] .'</span> </div> </td>'."\n";
				}
			}
			echo '</tr>';
		}
	};	
	?>
function formChange(room) {
		var timeID="formStartTime"+room;
		var selected=document.getElementById(timeID).value;
		<?php
		foreach ($roomNameAbrv as $room) {
			echo"\n\t".'if (room=="'.$room.'"){'."\n";
			createOption($room);
			echo'}';
		};
		?>
	}

	</script>
</head>
<body>
	
<?php

echo'<div class="heading">'.$dayName[$day].'</div>';

foreach ($roomNameAbrv as $room) {
	createForm($room);
};
echo'
<div id="tableDiv">
<table style="display:visible">
<tr class= "times ">
<th class="headcol" id="cornerBlock"></th>
<th class="headrow">17:00</th>
<th class="headrow">17:15</th>
<th class="headrow">17:30</th>
<th class="headrow">17:45</th>
<th class="headrow">18:00</th>
<th class="headrow">18:15</th>
<th class="headrow">18:30</th>
<th class="headrow">18:45</th>
<th class="headrow">19:00</th>
<th class="headrow">19:15</th>
<th class="headrow">19:30</th>
<th class="headrow">19:45</th>
<th class="headrow">20:00</th>
<th class="headrow">20:15</th>
<th class="headrow">20:30</th>
<th class="headrow">20:45</th>
<th class="headrow">21:00</th>
<th class="headrow">21:15</th>
<th class="headrow">21:30</th>
</tr>';

	
foreach (array("PAC1","PAC2","PAC3","CHAMBER") as $room) {
	createRow($room);
};
echo "</table></div>";
?>
</body>
</html>
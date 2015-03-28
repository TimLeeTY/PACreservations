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
	//serverside form validation start:
	$name = $email= "";
	$color="#FFFFFF";
	function test_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}
	if ($_POST["formTest"]==1) {
		$formErr=0;
		if(empty($_POST["name"])){
			$formErr=1;			
		} else{
			$name=test_input($_POST["name"]);
			if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
				$formErr=1;	
			}
		}
		if (empty($_POST["email"])) {
			$formErr=1;			
		} else {
			$email=test_input($_POST["email"]);
		    // check if e-mail address is well-formed
		    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$formErr=1; 
		    }
		}
		if (empty($_POST["time"])) {
		    $formErr=1;	
		} else {
		    
		}
		if ($formErr){
			$startTime=$_POST["startTime"];
			$time=$_POST["time"];
			$house=$_POST["house"];
			$year=$_POST["year"];
			$color=$_POST["color"];
		}else{
			$_SESSION["email"]=$email;
			$_SESSION["name"]=$name;
			$_SESSION["startTime"]=$_POST["startTime"];
			$_SESSION["time"]=$_POST["time"];
			$_SESSION["house"]=$_POST["house"];
			$_SESSION["year"]=$_POST["year"];
			$_SESSION["color"]=$_POST["color"];
			$_SESSION["room"]=$_POST["room"];
			header('reserve.php'); 
		}
	}
	//serverside form validaiton end:
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
	foreach ($roomNameAbrv as $room){
		${'bookedTime'.$room} = array();
		$result = $conn->query('SELECT timeID, booked FROM '.$day.'_'.$room) or die('failed conection');
		if ($result->num_rows > 0) {
			${"countlist".$room}=array();
		    while ($row = $result->fetch_assoc()) {
				${"bookedTime".$room}=array_merge(${"bookedTime".$room}, array($row["timeID"]=>$row["booked"]));
				if($row["booked"]==0 and count(${"countlist".$room})!=0 and end(${"countlist".$room})!=0){
					${"countlist".$room}[count(${"countlist".$room})-1]+=1;
				}elseif($row["booked"]==1){
					array_push(${"countlist".$room},0);
				}elseif(end(${"countlist".$room})==0 or count(${"countlist".$room})==0){ 
					array_push(${"countlist".$room},1);
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
		global $day, $roomName, $dayName, $name, $email, $color, ${"bookedTime".$room};
		echo'<div class="formBack" id="div_'.$room.'" >
			<div class="formFront">
			<form action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post" id="form_'.$room.'" >
			<h2>'.$dayName[$day].', '.$roomName[$room].'</h2>
			<input type="hidden" name="formTest" value="1">
			<input type="hidden" name="room" value="'.$room.'">
			<input type="hidden" name="day" value="'.$day.'">
			Name: 
			<input type="text" name="name" value="'.$name.'"><span class="formError" id="nameErr"></span><br>
			E-mail: 
			<input type="text" name="email" value="'.$email.'"><span class="formError" id="emailErr"></span><br>
			Form: 
			<select name="year">
				<option id="year_1_'.$room.'" value="1">4th </option>
				<option id="year_2_'.$room.'" value="2">L5th </option>
				<option id="year_3_'.$room.'" value="3">U5th </option>
				<option id="year_4_'.$room.'" value="4">L6th </option>
				<option id="year_5_'.$room.'" value="5">U6th </option>
			</select><br>
			House: 
			<select name="house">
				<option id="Heads_'.$room.'" value="Heads">Heads</option>
				<option id="Abraham_'.$room.'" value="Abraham">Abraham</option>
				<option id="School_'.$room.'" value="School">School</option>
				<option id="Fenwick_'.$room.'" value="Fenwick">Fenwick</option>
				<option id="New_'.$room.'" value="New">New</option>
				<option id="Other_'.$room.'" value="Other">Other</option>
			</select><br>
			Colour: 
			<input type="color" name="color" value="'.$color.'"><br>
			Start Time: 
			<select name="startTime" id="formStartTime'.$room.'" onchange="formChange(\''.$room.'\')">
			';
		foreach (${"bookedTime".$room} as $timeID=>$booked){
			if ($booked==0) {
				if ((15*($timeID%4))==0){
					$minute='00';
				} else{
					$minute=(15*($timeID%4));
				}
				echo '	<option value='.($timeID+1).' id="select'.($timeID+1).'_'.$room.'">'.(floor(($timeID)/4)+17).':'.$minute.'</option>'."\n\t\t\t";
			};			
		};
		echo '</select><br>
			Duration: <select name="time" id="formTime'.$room.'"> </select> minutes <span class="formError" id="timeErr"></span>
			<button type="button" onclick="closeForm(\''.$room.'\')" class="cancel">Cancel</button>
			<button type="submit" class="submit">Submit</button>
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
					$border="1px grey solid";
					$altColor="rgb(255,255,255)";
					if ($row[timeID] % 2 ==0){
						$altColor="rgb(240,240,240)";
					}elseif ($row[timeID] % 4==1){
						$border="2px grey solid";
					};
					echo '<td onclick="showForm('.$row[timeID].',\''.$room.'\')" style="background-color:'.$altColor.'; border-left:'.$border.'"> <div class="empty"></div></td>'."\n";
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
if ($formErr){
	echo'<script> 
		document.getElementById("div_'.$_POST["room"].'").style.display="inline";
		document.getElementById("select'.$startTime.'_'.$_POST["room"].'").selected = true;
		document.getElementById("'.$house.'_'.$_POST["room"].'").selected = true;
		document.getElementById("year_'.$year.'_'.$_POST["room"].'").selected=true;
		formChange("'.$_POST["room"].'");
		</script>';
}
?>
</body>
</html>
<?php
session_start();
//serverside form validation start:
$name = $email= "";
$color="#ffa18d";
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  return $data;
}
if ($_POST["formTest"]==1) {
	$formErr=0;
	if(empty($_POST["name"])){
		$formErr=1;
	} else{
		$name=test_input($_POST["name"]);
		if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
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
	if (empty($_POST["duration"])) {
	    $formErr=1;	
	}
	if ($formErr){
		$startTime=$_POST["startTime"];
		$time=$_POST["duration"];
		$house=$_POST["house"];
		$year=$_POST["year"];
		$color=$_POST["color"];
	} else{
		//to be sent off to update DB
		$_SESSION["email"]=$email;
		$_SESSION["name"]=$name;
		$_SESSION["startTime"]=$_POST["startTime"];
		$_SESSION["duration"]=$_POST["duration"];
		$_SESSION["house"]=$_POST["house"];
		$_SESSION["year"]=$_POST["year"];
		$_SESSION["color"]=$_POST["color"];
		$_SESSION["room"]=$_POST["room"];
		$_SESSION["day"]=$_POST["day"];
		header('Location: reserve.php'); 
		die();
	}
}
//serverside form validaiton end
date_default_timezone_set("GMT");
$conn = new mysqli('localhost', 'leetimmy', '______', 'reservations'); //password removed
$roomName = array("PAC1"=>"PAC Room 1", "PAC2"=>"PAC Room 2", "PAC3"=>"PAC Room 3", "CHAMBER"=>"Chamber Music Room");
$dayOfWeek=date("N");
$weekNameShort=array("SUN","MON","TUE","WED","THU","FRI","SAT");
$dayName= array("MON"=>"Monday", "TUE"=>"Tuesday", "WED"=>"Wednesday", "THU"=>"Thursday", "FRI"=>"Friday", "SAT"=>"Saturday", "SUN"=>"Sunday");
$yearList=array("4th Form", "Lower 5th", "Upper 5th", "Lower 6th", "Upper 6th");
$day=$weekNameShort[($_POST["day"]+$dayOfWeek)%7];
//creates list of timeID=>booked for all rooms in GLOBAL scope 
//records the number of free spaces looped in a list in an array ${"countlist".$room}
foreach ($roomName as $room => $x){
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
//function for creating forms for rooms with abbreviated room name of $room
function createForm($room){
	global $day, $roomName, $dayName, $name, $email, $color, ${"bookedTime".$room};
	echo'
<div class="formBack" id="div_'.$room.'" >
	<div class="formFront">
		<form action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" onsubmit="return validateFormSubmit(\''.$room.'\')" method="post" id="form_'.$room.'" >
			<h2>'.$dayName[$day].', '.$roomName[$room].'</h2>
			<input type="hidden" name="formTest" value="1">
			<input type="hidden" name="room" value="'.$room.'">
			<input type="hidden" name="day" value="'.$day.'">
			<input type="text" name="name" value="'.$name.'" placeholder="Name" class="inputBox" pattern="^[A-Za-z\s]+$" title="only letters and white space"><br><span class="formErr" id="'.$room.'nameErr"></span><br>
			<input type="email" name="email" value="'.$email.'" placeholder="Email" class="inputBox"><br><span class="formErr" id="'.$room.'emailErr"></span><br>
			Form: 
			<select name="year">
				<option id="year_0_'.$room.'" value="0">4th </option>
				<option id="year_1_'.$room.'" value="1">L5th </option>
				<option id="year_2_'.$room.'" value="2">U5th </option>
				<option id="year_3_'.$room.'" value="3">L6th </option>
				<option id="year_4_'.$room.'" value="4">U6th </option>
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
			echo '<option value='.($timeID+1).' id="select'.($timeID+1).'_'.$room.'">'.(floor(($timeID)/4)+17).':'.$minute.'</option>'."\n\t\t\t\t";
		};			
	};
	echo '</select><br>
			Duration: <select name="duration" id="formTime'.$room.'" required> </select> minutes <br><span class="formErr" id="'.$room.'timeErr"></span>
			<button type="button" onclick="closeForm(\''.$room.'\')" class="cancel">Cancel</button>
			<button type="submit" class="submit">Submit</button>
		</form>
	</div>
</div>';
};
//function for creating the number of "duration" options based on ${"countlist".$room}, outputs into js function "showForm"
function createOption($room){
	global ${"countlist".$room};
	$count=0;
	$overall=1;
	echo"\t\t\t\tswitch(selected){\n";
	foreach(${"countlist".$room} as $i){
		if ($i!=0){
			while ($count<$i){
				echo"\t\t\t\t\t".'case "'.$overall.'":'."\n\t\t\t\t\t\t".'document.getElementById("formTime'.$room.'").options.length=0;'."\n";
				for ($x=1; $x<=min(($i-$count),8); $x++){
					echo"\t\t\t\t\t\t".'document.getElementById("formTime'.$room.'").options['.$x.']=new Option("'.(($x)*15).'", "'.$x.'", false, false);'."\n";
				};
				echo"\t\t\t\t\t\tbreak;\n";
				$count+=1;
				$overall+=1;
			};
		}else{
			$overall+=1;
		};
		$count=0;	
	};
	echo"\t\t\t\t\tbreak;\n\t\t\t\t}\n";
};
//function for creating rows in table for rooms with abbreviated room name of $room
function createRow($room){
	global $conn, $roomName, $day, $yearList;
	echo "\n\t\t".'<tr> 
			<th class="headcol">
				<div class="headSpan">'.$roomName[$room].'</div>
			</th>'."\n" ;
	$result = $conn->query('SELECT timeID, booked, name, email, length, color, house, year FROM '.$day.'_'.$room) or die('failed conection');
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
				echo "\t\t\t".'<td onclick="showForm('.$row[timeID].',\''.$room.'\')" style="background-color:'.$altColor.'; border-left:'.$border.'"> <div class="empty"></div></td>'."\n";
			} elseif ($row["length"]>0) {
				echo "\t\t\t".'<td colspan= '.$row["length"].' class="occupied"> <div class="occupied" style="background-color:'.$row["color"].'"> <span class="occupiedText">'.$row["name"].'<br>'.$row["house"].', '.$yearList[$row["year"]].'</span> </div> </td>'."\n";
			}
		}
		echo "\t\t".'</tr>';
	}
};	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Timetable</title>
	<link href="stylesheet.css" rel="stylesheet" type="text/css"/>
	<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
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
	    document.getElementById(room+"nameErr").innerText="";
	    document.getElementById(room+"emailErr").innerText="";
	    document.getElementById(room+"timeErr").innerText="";
	};
	function formChange(room) {
		var timeID="formStartTime"+room;
		var selected=document.getElementById(timeID).value;
		switch (room){<?php
		foreach ($roomName as $room => $x) {
			echo"\n\t\t\t".'case "'.$room.'":'."\n";
			createOption($room);
			echo"\t\t\t\tbreak;";
		};
		echo"\n\t\t\tbreak;\n\t\t}\n"
		?>
	};
	
	function validateFormSubmit(room) {
		var formID="form_"+room;
	    var formName = document.forms[formID]["name"].value;
	    var formEmail = document.forms[formID]["email"].value;
	    var formTime = document.forms[formID]["duration"].value;
	    document.getElementById(room+"nameErr").innerText="";
	    document.getElementById(room+"emailErr").innerText="";
	    document.getElementById(room+"timeErr").innerText="";
		var flag = true;
		if (formName == null || formName == "" || /^[A-Za-z\s]+$/.test(formName)==false)  {
	        document.getElementById(room+"nameErr").innerText="Name must not be empty and should only contain letters and whitespaces";
	        flag = false;
	    }
	    if (formEmail == null || formEmail == "") {
	        document.getElementById(room+"emailErr").innerText="Email must not be empty and should be a valid email";
	        flag = false;
	    }
	    if (formTime == null || formTime == "") {
	        document.getElementById(room+"timeErr").innerText="Duration must not be empty";
	        flag = false;
	    }
		return flag;
	};
	</script>
</head>
<body>
<div class="heading">
<?php
	echo'<span class="heading">'.$dayName[$day].'</span> <span class="date">'.date("d.m.y",time()+ ($_POST["day"] * 24 * 60 * 60)).'</span>';
?>
	<a href="../PACreservations">
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
<?php foreach ($roomName as $room => $x) {
	createForm($room);
};
echo'
<div id="tableDiv">
	<table style="display:visible">
		<tr class= "times ">
			<th class="headcol" id="cornerBlock"><div class="headSpan"> Start time</div></th>
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
foreach ($roomName as $room => $x) {
	createRow($room);
};
echo "\n\t</table>\n</div>\n";
if ($formErr){
	echo'
<script> 
	document.getElementById("'.$house.'_'.$_POST["room"].'").selected = true;
	document.getElementById("year_'.$year.'_'.$_POST["room"].'").selected=true;
	document.getElementById("select'.$startTime.'_'.$_POST["room"].'").selected = true;
	formChange("'.$_POST["room"].'");
	document.getElementById("div_'.$_POST["room"].'").style.display="inline";
</script>';
}
?>
</body>
</html>
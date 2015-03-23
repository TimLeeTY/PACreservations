<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<script src="jquery-1.11.2.min.js"></script>
	<script src="script.js"></script>
	<link href="stylesheet.css" rel="stylesheet" type="text/css"/>
</head>
<body>
	<form id="form_PAC1" action="reserve.php" method="post" style="display:viisble">
		Name: <input type="text" name="name"> <br>
		E-mail: <input type="text" name="email"><br>
		Start Time: <select autofocus= name="startTime">
			<?php
			$servername = "localhost";
			$username = "leetimmy";
			$password = "poohpooh";
			$dbname = "reservations";

			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);
			// Check connection
			if ($conn->connect_error) {
			    die("Connection failed: " . $conn->connect_error);
			} 

			$sql = "SELECT timeID, booked FROM MON_PAC1";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
			    while($row = $result->fetch_assoc()) {
					if ($row["booked"]==0) {
						echo "\n \t\t\t<option value=\"".$row[timeID]."\" id=\"".$row[timeID]."\">".$row[timeID]."</option>"	;
					}			
				}
			}
			?>
		
		</select>
		Duration: <select name="time">
			<option value="1">15</option>
			<option value="2">30</option>
			<option value="3">45</option>
			<option value="4">60</option>
		</select>
		<input type="submit">
	</form>
	
	<table class="main">	
	<tr class="times">
		<td border="0"></td>
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
	</tr>
	<tr class="rooms">
		<th>PAC 1</th>
		<?php
		$servername = "localhost";
		$username = "leetimmy";
		$password = "poohpooh";
		$dbname = "reservations";

		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		} 

		$sql = "SELECT timeID, booked, name, email FROM MON_PAC1";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
		    while($row = $result->fetch_assoc()) {
				if ($row["booked"]==0) {
					echo "\n\t\t<td onclick= \" showForm(".$row[timeID].") \" > </td>";
				} elseif ($row["length"]>=0) {
					echo
						"<td colspan= " .$row["length"] ."> <div style=\"background-color:\"red\" \"> <p>" .$row["name"] ."</p> <p>" .$row["email"] ."</p> </div> </td>";
				}
			}
		}
		?>
	
	</tr>
	<tr class="rooms">
		<th>PAC 2</th>
		<?php
		$servername = "localhost";
		$username = "leetimmy";
		$password = "poohpooh";
		$dbname = "reservations";

		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		} 

		$sql = "SELECT timeID, booked, name, email FROM MON_PAC1";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
		    while($row = $result->fetch_assoc()) {
				if ($row["booked"]==0) {
					echo "<td onclick= \" showForm(".$row[timeID].") \" > </td>";
				} elseif ($row["length"]>=0) {
					echo
						"<td colspan= " .$row["length"] ."> <div style=\"background-color:\"red\" \"> <p>" .$row["name"] ."</p> <p>" .$row["email"] ."</p> </div> </td>";
				}
			}
		}
		?>
	</tr>
	<tr class="rooms">
		<th>PAC 3</th>
		<?php
		$servername = "localhost";
		$username = "leetimmy";
		$password = "poohpooh";
		$dbname = "reservations";

		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		} 

		$sql = "SELECT timeID, booked, name, email FROM MON_PAC1";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
		    while($row = $result->fetch_assoc()) {
				if ($row["booked"]==0) {
					echo "<td onclick= \" showForm(".$row[timeID].") \" > </td>";
				} elseif ($row["length"]>=0) {
					echo
						"<td colspan= " .$row["length"] ."> <div style=\"background-color:\"red\" \"> <p>" .$row["name"] ."</p> <p>" .$row["email"] ."</p> </div> </td>";
				}
			}
		}
		?>
	</tr>
	<tr class="rooms">
		<th>Chamber Room</th>
		<?php
		$servername = "localhost";
		$username = "leetimmy";
		$password = "poohpooh";
		$dbname = "reservations";

		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		} 

		$sql = "SELECT timeID, booked, name, email FROM MON_PAC1";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
		    while($row = $result->fetch_assoc()) {
				if ($row["booked"]==0) {
					echo "<td onclick= \" showForm(".$row[timeID].") \" > </td>";
				} elseif ($row["length"]>=0) {
					echo
						"<td colspan= " .$row["length"] ."> <div style=\"background-color:\"red\" \"> <p>" .$row["name"] ."</p> <p>" .$row["email"] ."</p> </div> </td>";
				}
			}
		}
		?>
	</tr>
		</table>
</body>	
</html>
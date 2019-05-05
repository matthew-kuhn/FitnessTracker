<?php

			 // retrieve session information
			session_start();

			// if no username set, then redirect to login
			if(!isset($_SESSION['myusername'])){
				header("location:index.php");
			}
			if(isset($_SESSION['myusername'])){
			@ $db = new mysqli('localhost', 'root', '', 'FitnessTracker');

			if (mysqli_connect_errno()) 
             { 
               echo 'ERROR: Could not connect to database.  Error is '.mysqli_connect_error();
               exit;
             }

			$query = "SELECT WPlan FROM User WHERE UserID = ?";

			$statement = $db->prepare($query);
			$statement->bind_param('s', $_SESSION['myusername']);
			$statement->execute(); 
			$statement->store_result();
			$statement->bind_result($_SESSION['WPlan']);
			$statement->fetch();

			if(strlen($_SESSION['WPlan']) > 1){
				$query = "SELECT * FROM IN_Wplan WHERE WPlan_Name = ? Order by Day Asc;";
				$statement = $db->prepare($query);
				$statement->bind_param('s', $_SESSION['WPlan']);
				$statement->execute();
				$plan = array();
				$results = $statement->get_result();

				while ($row = $results->fetch_assoc()) {
					array_push($plan, $row);
				}
				
				$query = "SELECT * FROM `Workout Plan`;";
				$plans = array();
				$statement = $db->prepare($query);
				$statement->execute();
				$results = $statement->get_result();

				while ($row = $results->fetch_assoc()) {
					array_push($plans, $row);
				}

				$query = "SELECT Exercise_Name FROM Exercise;";

				$statement = $db->prepare($query);
				$statement->execute();
				$results = $statement->get_result();
				$exercises = array();
				while($row = $results->fetch_assoc()){
					array_push($exercises, $row);
				}

				$query = "SELECT Workout_Name From Workout;";
			
				$statement = $db->prepare($query);
				$statement->execute();
				$results = $statement->get_result();
				$allWorkouts = array();
				while($row = $results->fetch_assoc()){
					array_push($allWorkouts, $row);
				}

				$query = "SELECT Session.Exercise_Name as Exercise_Name, Exercise_Desc, Reps, Sets FROM Session, Exercise where session.exercise_name = exercise.exercise_name AND workout_name = ?;";

				$statement = $db->prepare($query);
				$statement->bind_param('s', $plan[0]['W_NAME']);
				$statement->execute();
				$currentWorkout = array();
				$results = $statement->get_result();
				while ($row = $results->fetch_assoc()) {
					array_push($currentWorkout, $row);
				}

			}
		}

?>
<html lang="en-US">
	<head>
		<title>Workouts Info</title>
		<meta charset="utf-8">
		<meta name="author" content="Matthew Kuhn, Andrea-Cristiano Seazzu, Noah White">
		<meta name="description" content="Page for viewing and creating workouts">
		<link rel="stylesheet" type="text/css" href="fit.css">
		<link rel="shortcut icon" type="image/png" href="favicon.ico">
		<script type="text/javascript">
			function pageUpdate(){
				var url = "workout_update.php";
				var request = new XMLHttpRequest();
				request.onload = function() {
					var response = document.getElementById('weekly');
					response.value = request.response;
					if(request.status == 404){
						document.getElementById('weekly').innerHTML = "<h2>Error 404</h2>";
					}else{
						document.getElementById('weekly').innerHTML = "<h2>Weekly Overview</h2><h3>Click the Workout Name to view details</h3>"; 
					}
					document.getElementById('weekly').innerHTML += "<table id='plan_table' style='margin:auto' cellpadding='5'>";
					var plan = JSON.parse(request.response);
					for(var i = 0; i < plan.length; i++){
						switch(plan[i]['Day']){
							case 1:
								document.getElementById('plan_table').innerHTML += "<tr><th>Sunday:</th><th></th><th></th></tr>";
								break;
							case 2:
								document.getElementById('plan_table').innerHTML +=  "<tr><th>Monday:</th><th></th><th></th></tr>";
								break;
							case 3:
								document.getElementById('plan_table').innerHTML +=  "<tr><th>Tuesday:</th><th></th><th></th></tr>";
								break;
							case 4:
								document.getElementById('plan_table').innerHTML +=  "<tr><th>Wednesday:</th><th></th><th></th></tr>";
								break;
							case 5:
								document.getElementById('plan_table').innerHTML +=  "<tr><th>Thursday:</th><th></th><th></th></tr>";
								break;
							case 6:
								document.getElementById('plan_table').innerHTML +=  "<tr><th>Friday:</th><th></th><th></th></tr>";
								break;
							case 7:
								document.getElementById('plan_table').innerHTML +=  "<tr><th>Saturday:</th><th></th><th></th></tr>";
								break;
						}
						document.getElementById('plan_table').innerHTML +=  "<tr><td style='text-align:center'><button onclick='workoutChange(\""+plan[i]['W_NAME']+"\")' style='background:none!important; color:inherit; border:none; padding:0!important; font: inherit; cursor: pointer;'>"+plan[i]['W_NAME']+"</button></td></tr>";
					}
				}

				var content = document.getElementById('WPlan').value;
				content = {planName : content};
				content = JSON.stringify(content);
				request.open('POST', url, true);
        		request.send(content);
			}

			function workoutChange(name){
				var url = "workout_update.php";
				var request = new XMLHttpRequest();
				request.onload = function() {
					var response = document.getElementById('daily');
					response.value = request.response;
					if(request.status == 404){
						document.getElementById('daily').innerHTML = "<h2>Error 404</h2>";
					}else{
						document.getElementById('daily').innerHTML = "<h2>Workout Close View</h2>"; 
					}
					document.getElementById('daily').innerHTML += "<table id='day_table' style='margin: auto; border-spacing: 10px; table-layout: fixed;'>";
					var workout = JSON.parse(request.response);
					document.getElementById('day_table').innerHTML += "<caption>"+name+"</caption>";
					document.getElementById('day_table').innerHTML += "<tr><th style='text-align: left'>Exercise</td><th style='text-align: left'>Description</td><th style='text-align: left'>Reps</td><th style='text-align: left'>Sets</td></tr>";
					for(var i = 0; i < workout.length; i++){
						document.getElementById('day_table').innerHTML += "<tr><td>"+workout[i]['Exercise_Name']+"</td><td>"+workout[i]['Exercise_Desc']+"</td><td>"+workout[i]['Reps']+"</td><td>"+workout[i]['Sets']+"</td></tr>"
					}	
				}
				var content = name
				content = {W_Name : content};
				content = JSON.stringify(content);
				request.open('POST', url, true);
        		request.send(content);
			}
		</script>
	</head>
	<body>
		<header>
			<h1>FitTrak</h1>
			<nav>
				<a href="workouts.php">Workout Info</a>
				<a href="food.php">Food Info</a>
				<a href="tracking.php">Tracking</a>
				<a href="index.php">Home</a>
				<a style="float: right" href="logout.php">Sign Out</a>
				<p style="float: right"><?php echo $_SESSION['myusername'];?></p>
			</nav>
		</header>
		<div id="weekly" style="text-align: center; width: 66%">
			<h2>Weekly Overview</h2>
			<!-- this will be connected to the database and use php to display the weekly overview -->
				<?php
				if(!isset($plan)){
				?>
				<h3>Please Choose a plan</h3>
				<?php
				}
				else{
				?>
				<h3>Click the Workout Name to view details</h3>
				<?php
					print "<table style='margin:auto; padding-bottom:5px;' cellpadding='5'>";
					for($i = 0; $i<count($plan); $i++){
						switch ($plan[$i]['Day']) {
							case 1:
								print "<tr><th>Sunday:</th><th></th><th></th></tr>";
								break;
							case 2:
								print "<tr><th>Monday:</th><th></th><th></th></tr>";
								break;
							case 3:
								print "<tr><th>Tuesday:</th><th></th><th></th></tr>";
								break;
							case 4:
								print "<tr><th>Wednesday:</th><th></th><th></th></tr>";
								break;
							case 5:
								print "<tr><th>Thursday:</th><th></th><th></th></tr>";
								break;
							case 6:
								print "<tr><th>Friday:</th><th></th><th></th></tr>";
								break;
							case 7:
								print "<tr><th>Saturday:</th><th></th><th></th></tr>";
								break;
						}
						print "<tr><td style='text-align:center'><button onclick='workoutChange(\"".$plan[$i]['W_NAME']."\")' style='background:none!important; color:inherit; border:none; padding:0!important; font: inherit; cursor: pointer;'>".$plan[$i]['W_NAME']."</button></td></tr>";
					}
					print "</table>";
				}

				?>
		</div>
		<div id="daily" style="text-align: center; width: 66%">
			<h2>Workout Close View</h2>
			<!-- this will be changed based on which day from the left panel gets clicked -->
			<?php
			if(!isset($currentWorkout)){
			?>
			<h3>Please Choose A Plan</h3>
			<?php
			}
			else{
				print "<table id='day_table' style='margin: auto; border-spacing: 10px; table-layout: fixed;'>";
				print "<caption>".$plan[0]['W_NAME']."</caption>";
				print "<tr><th style='text-align: left'>Exercise</td><th style='text-align: left'>Description</td><th style='text-align: left'>Reps</td><th style='text-align: left'>Sets</td></tr>";
				for($i = 0; $i < count($currentWorkout); $i++){
					print "<tr><td>".$currentWorkout[$i]['Exercise_Name']."</td><td>".$currentWorkout[$i]['Exercise_Desc']."</td><td>".$currentWorkout[$i]['Reps']."</td><td>".$currentWorkout[$i]['Sets']."</td></tr>";
				}
				print "</table>";
			}
			?>
		</div>
		<div id="planSelect" style="text-align: center; width: 66%">
			<h2>Current Workout Plan</h2>
			<select id="WPlan" style="display: block; margin: 0 auto; margin-top: 5px; margin-bottom: 5px;">
						<?php
							for($i = 0; $i < count($plans); $i++){
								if($plans[$i]['Workout_Plan_Name'] === $_SESSION['WPlan']){
									print "<option selected='selected' value='".$_SESSION['WPlan']."'>".$_SESSION['WPlan']."</option>";
								}
								else{
									print "<option value='".$plans[$i]['Workout_Plan_Name']."'>".$plans[$i]['Workout_Plan_Name']."</option>";
								}
							}

						?>
					</select>
					<input type="submit" name="submit1" value="Select Plan" onclick="pageUpdate()" style="display: block; margin: 0 auto; margin-bottom: 5px">
		</div>
		<div id='exercise_creator' style="text-align: center; width:66%">
			<h2>Create a New Exercise</h2>
			<form method="post" action="create_workout.php">
				<table style="margin:auto">
					<tr>
						<td>Exercise Name:</td>
						<td><input type="text" name="Exercise_Name" required maxlength="50"></td>
					</tr>
					<tr>
						<td>Exercise Description:</td>
						<td><input type="text" name="Exercise_Desc" required maxlength="140"></td>
					</tr>
					<tr>
						<td colspan="2" style="text-align:center"><input type="submit" name="Submit"></td>
					</tr>
				</table>
			</form>
		</div>
		<div id='workout_creator' style="text-align: center; width:66%">
			<h2>Create a New Workout</h2>
			<form method="post" action="create_workout.php">
				<table style="margin:auto">
					<tr>
						<td></td>
						<td>Workout Name:</td>
						<td><input type="text" name="W_Name" required maxlength="50"></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td>Workout Type:</td>
						<td><input type="text" name="Type" required maxlength="20"></td>
						<td></td>
					</tr>
					<tr>
						<td>Exercise 1:</td>
						<td>
							<select name="Exercise[0]">
								
							</select>
						</td>
						<td><input type="number" name=""></td>
					</tr>
					<tr>
						<td colspan="2" style="text-align:center"><input type="submit" name="submit"></td>
					</tr>
				</table>
			</form>			
		</div>
		<div id="plan_creator" style="text-align: center; width:66%">
			<form method="post" action="create_workout.php">
				<table>
					<tr>
						<td></td>
					</tr>
				</table>				
			</form>
		</div>
	</body>
</html>
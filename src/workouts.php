<?php

			  // retrieve session information
			  session_start();

			  // if no username set, then redirect to login
			  if(!isset($_SESSION['myusername'])){
			  	header("location:index.php");
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
			function changePlans(action)
		      {
		        if(action == "existing"){
		        	// document.getElementById('planSelect').innerHTML = "hello";
		        	//change workouts on page
		        }
		        else{
		        	//change bottom div to display form to create new plan
		        }
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
			</nav>
		</header>
		<div id="weekly" style="text-align: center; width: 66%">
			<h2><?php echo("Week Overview") ?></h2>
			<!-- this will be connected to the database and use php to display the weekly overview -->
				<ul style="display: inline-block; list-style: none">
					<li>Day 1: <?php echo "Chest and shoulders"?></li>
					<li>Day 2: <?php echo "3 Mile run"?></li>
					<li>Day 3: <?php echo "Back and legs"?></li>
					<li>Day 4: <?php echo "10 sprint intervals" ?></li>
					<li>Day 5: <?php echo "Legs"?></li>
				</ul>
		</div>
		<div id="monthly" style="text-align: center; width: 66%">
			<h2>Workout Close View</h2>
			<!-- this will be changed based on which day from the left panel gets clicked -->
				<table style="margin: auto; border-spacing: 10px; table-layout: fixed;">
					<caption>Day 1: Chest and Shoulders</caption>
					<tr>
						<th style="text-align: left">Exercise</td>
						<th style="text-align: left">Description</td>
						<th style="text-align: left">Reps</td>
						<th style="text-align: left">Sets</td>
					</tr>
					<tr>
						<td>Bench Press</td>
						<td>Flat Barbell Bench Press</td>
						<td>5-8</td>
						<td>5</td>
					</tr>
					<tr>
						<td>Incline Bench Press</td>
						<td>On incline bench, with dumbbells</td>
						<td>8-12</td>
						<td>4</td>
					</tr>
					<tr>
						<td>Overhead press</td>
						<td>standing overhead barbell press, hands shoulder width</td>
						<td>8-10</td>
						<td>4</td>
					</tr>
					<tr>
						<td>Front raises</td>
						<td>Dumbbell in each hand, raise arms to shoulder-height, palms down</td>
						<td>15-20</td>
						<td>3</td>
					</tr>
				</table>
		</div>
		<div id="planSelect" style="text-align: center; width: 66%">
			<h2>Current Workout Plan</h2>
			<form name="existingPlan" method="?" action="" onsubmit="return changePlans('existing')" style="margin: auto;">
				<select style="display: block; margin: 0 auto; margin-top: 5px; margin-bottom: 5px;">
					<option>PowerLifting</option>
					<option>Bodybuilding</option>
					<option>Running</option>
					<option>Calisthenics</option>
				</select>
				<input type="submit" name="submit" value="Select Plan" style="display: block; margin: 0 auto; margin-bottom: 5px">
			</form>
			<form name="newPlan" method="?" action="" onsubmit="return changePlans('new')">
				<input type="submit" name="submit" value="New Plan" style="display: block; margin: 0 auto">
			</form>
		</div>
	</body>
</html>
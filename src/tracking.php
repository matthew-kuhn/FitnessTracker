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
		<title>Personal Tracker</title>
		<meta charset="utf-8">
		<meta name="author" content="Matthew Kuhn, Andrea-Cristiano Seazzu, Noah White">
		<meta name="description" content="The page for tracking workout and food data">
		<link rel="stylesheet" type="text/css" href="fit.css">
		<link rel="shortcut icon" type="image/png" href="favicon.ico">
		<script type="text/javascript">
			 function checkForm(){

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
		<div id="Weekly_Summary" style="margin: 0 auto; width: 50%;">
			<h2>The Week So Far</h2>
				<table style="margin: auto;">
					<tr>
						<th style="text-align: left">Day</th>
						<th style="text-align: left">Calories</th>
						<th style="text-align: left">Weight (lbs)</th>
					</tr>
					<tr>
						<td>Monday</td>
						<td style="text-align: right">1900</td>
						<td style="text-align: right">157</td>
					</tr>
					<tr>
						<td>Tuesday</td>
						<td style="text-align: right">2150</td>
						<td style="text-align: right">156</td>
					</tr>
					<tr>
						<td>Wednesday</td>
						<td style="text-align: right">2000</td>
						<td style="text-align: right">157</td>
					</tr>
				</table>
		</div>
		<div id="Tracking_Input" style="margin: 0 auto; width: 50%;">
			<h2>Today's Tracking</h2>
				<form id="tracker" method="post" action="" onsubmit="return checkForm()">
					<table style="margin: 0 auto">
						<tr>
							<td>Calories:</td>
							<td><input type="text" name="cals"></td>
						</tr>
						<tr>
							<td>Weight:</td>
							<td><input type="text" name="weight"></td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: center"><input type="submit" name="submit" value="Record Data"></td>
						</tr>
					</table>
				</form>
		</div>
	</body>
</html>
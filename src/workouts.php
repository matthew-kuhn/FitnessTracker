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
			function checkLoginForm()
		      {
		        var userName = document.forms["login_form"]["myusername"].value;

		        if (userName == "") 
		        {
		          document.getElementById('formFeedback').innerHTML = "ERROR: User Name must be specified.";
		          return false;
		        }
		        else
		          return true;
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
		<div id="weekly" style="float:left; width: 48%; margin-top: 15px; margin-bottom: 15px">
			<h2><?php echo("content to display weekly workout schedule") ?></h2>
				<p>Day 1</p>
				<p>day 2</p>
				<p>day 3</p>
				<p>day 4</p>
				<p>day 5</p>
				<p>day 6</p>
				<p>day 7</p>
		</div>
		<div id="monthly" style="float:right; width: 48%; margin-top: 15px; margin-bottom: 15px">
			<h2>Display Monthly stuff maybe</h2>
				<p>still havent decided if there is anything better to put here</p>
		</div>
		<div id="planSelect" style="clear: both; width: 50%;">
			<h2>Current Workout Plan</h2>
			<form>
				
			</form>
			<form>
				
			</form>
		</div>
	</body>
</html>
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

			$query = "SELECT * FROM Day WHERE UserID = ? Order By Date limit 7";

			$statement = $db->prepare($query);
			$statement->bind_param('s', $_SESSION['myusername']);
			$statement->execute(); 
			$results = $statement->get_result();

			if(!isset($results)){
				$tracked = false;
			}
			else{
				$tracked = true;
				$days = array();
				while ($row = $results->fetch_assoc()) {
					array_push($days, $row);
				}
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
		<div id="Weekly_Summary" style="margin: 0 auto; width: 66%;">
			<?php

			if(!$tracked){

			?>	
			<h2>Start Tracking Today!</h2>
			<?php

			}else{

			?>
			<h2>The Week So Far</h2>
				<table style="margin: auto;">
					<tr>
						<th style="text-align: left">Day</th>
						<th style="text-align: left">Calories</th>
						<th style="text-align: left">Weight (lbs)</th>
					</tr>
					<?php
						for($i = 0; $i < count($days); $i++){
							print "<tr><td>".substr($days[$i]['Date'], 0, 10)."</td><td>".$days[$i]['Calories']."</td><td>".$days[$i]['Weight']."</td></tr>";
						}


					?>
				</table>
			<?php

			}

			?>
		</div>
		<div id="Tracking_Input" style="margin: 0 auto; width: 66%;">
			<h2>Today's Tracking</h2>
				<form id="tracker" method="post" action="track_update.php" onsubmit="return checkForm()">
					<h2 id="formFeedback">
						<?php
						if(isset($_GET['TrackError'])){print "There was an error with the database";}
						if(isset($_GET['TrackSuccess'])){print "Tracked Successfully";}
						?>
					</h2>
					<table style="margin: 0 auto">
						<tr>
							<td>Calories:</td>
							<td><input type="number" name="cals" required></td>
						</tr>
						<tr>
							<td>Weight:</td>
							<td><input type="number" step=".1" name="weight" required></td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: center" id="dateRow"><input type="date" name="date" id="date" required></td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: center"><input type="submit" value="Record Data"></td>
						</tr>
					</table>
				</form>
		</div>
	</body>
</html>
<?php } ?>
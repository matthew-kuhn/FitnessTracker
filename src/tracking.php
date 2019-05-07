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

			$query = "SELECT * FROM Day WHERE UserID = ? Order By Date DESC limit 7";

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
			$days = array_reverse($days);

?>
<html lang="en-US">
	<head>
		<title>Personal Tracker</title>
		<meta charset="utf-8">
		<meta name="author" content="Matthew Kuhn, Andrea-Cristiano Seazzu, Noah White">
		<meta name="description" content="The page for tracking workout and food data">
		<link rel="stylesheet" type="text/css" href="fit.css">
		<link rel="shortcut icon" type="image/png" href="favicon.ico">
		<script src="../node_modules/Chart.js/dist/chart.bundle.js"></script>
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
				<p style="float: right"><?php echo $_SESSION['myusername'];?></p>
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
			<h2>Your Past 7 Days</h2>
				<table style="margin: auto;">
					<tr>
						<th style="text-align: left">Day</th>
						<th style="text-align: left">Calories</th>
						<th style="text-align: left">Weight (lbs)</th>
					</tr>
					<?php
						for($i = 0; $i < count($days); $i++){
							print "<tr><td>".substr($days[$i]['Date'], 0, 10)."</td><td style='text-align:right'>".$days[$i]['Calories']."</td><td style='text-align:right'>".$days[$i]['Weight']."</td></tr>";
						}


					?>
				</table>
				<canvas id="weekChart" width="400" height="400" style="margin: auto"></canvas>
				<script type="text/javascript">
					var days = <?php echo json_encode($days); ?>;
					var ctx = document.getElementById('weekChart');
					var labelList = [];
					var weights = [];
					var cals = [];
					for (var i = 0; i< days.length ; i++) {
						cals.push(days[i]['Calories']);
					}
					for (var i = 0; i<days.length;i++) {
						weights.push(days[i]['Weight']);
					}
					for(var i = 0; i < days.length; i++){
						labelList.push(days[i]["Date"].substring(5,10));
					}
					var chart = new Chart(ctx, {
							type : "bar",
							data: {
								labels : labelList,
								datasets:[{
									label : "Weight",
									yAxisID: 'A',
									data : weights,
									backgroundColor : [
										"rgba(255, 26, 26, .2)",
										"rgba(255, 26, 26, .2)",
										"rgba(255, 26, 26, .2)",
										"rgba(255, 26, 26, .2)",
										"rgba(255, 26, 26, .2)",
										"rgba(255, 26, 26, .2)",
										"rgba(255, 26, 26, .2)"
									],
									borderColor : [
										"rgba(255, 26, 26, 1)",
										"rgba(255, 26, 26, 1)",
										"rgba(255, 26, 26, 1)",
										"rgba(255, 26, 26, 1)",
										"rgba(255, 26, 26, 1)",
										"rgba(255, 26, 26, 1)",
										"rgba(255, 26, 26, 1)"
									],

									borderWidth : 1
								},
								{
									label : "Calories" , 
									yAxisID : 'B',
									data : cals,
									fill : false,
									backgroundColor :[
										"rgba(26, 102, 255, .2)"
									],
									borderColor : [
										"rgba(26, 102, 255, 1)"
									],

									type: 'line'
								}]
							},
							options: {
						        scales: {
						        	xAxes:[{
						        		scaleLabel : {
						            		display : true,
						            		labelString : 'Date'
						            	},
						        	}],
						            yAxes: [{
						            	id: 'A',
						            	scaleLabel : {
						            		display : true,
						            		labelString : 'Weight (Lbs)'
						            	},
								        type: 'linear',
								        position: 'left',
						                ticks: {
						                	suggestedMax : 300,
						                    beginAtZero: true
						                }
						            },
						            {
						            	id: 'B',
						            	scaleLabel : {
						            		display : true,
						            		labelString : 'Calories'
						            	},
								        type: 'linear',
								        position: 'right',
								        ticks: {
								        	suggestedMax : 3500,
						                    beginAtZero: true
						                }
						            }]
						        }
		   					 }
						}
					)
				</script>
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
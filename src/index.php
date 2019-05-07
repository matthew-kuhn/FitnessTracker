<?php
	
			// retrieve session information
			session_start();

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

			$query = "SELECT * FROM Day WHERE UserID = ? Order By Date DESC limit 30";

			$statement = $db->prepare($query);
			$statement->bind_param('s', $_SESSION['myusername']);
			$statement->execute(); 
			$results = $statement->get_result();

			if(!isset($results)){
				$tracked = false;
			}
			else{
				$tracked = true;
				$month = array();
				while ($row = $results->fetch_assoc()) {
					array_push($month, $row);
				}
			}
			$month = array_reverse($month);

			$query = "SELECT * FROM Day WHERE UserID = ? Order By Date DESC limit 365";

			$statement = $db->prepare($query);
			$statement->bind_param('s', $_SESSION['myusername']);
			$statement->execute(); 
			$results = $statement->get_result();

			if(!isset($results)){
				$tracked = false;
			}
			else{
				$tracked = true;
				$year = array();
				while ($row = $results->fetch_assoc()) {
					array_push($year, $row);
				}
			}
			$year = array_reverse($year);
		}
?>
<html lang = "en-US">
	<head>
		<title>Fitness Tracker</title>
		<meta charset="utf-8">
		<meta name="author" content="Matthew Kuhn, Andrea-Cristiano Seazzu, Noah White">
		<meta name="description" content="The homepage for the fitness tracking website">
		<link rel="stylesheet" type="text/css" href="fit.css">
		<link rel="shortcut icon" type="image/png" href="favicon.ico">
		<script src="../node_modules/Chart.js/dist/chart.bundle.js"></script>
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

		      function checkNewUser(){
		      	var username = document.forms["new_user"]["newusername"].value;
		      	var password = document.forms["new_user"]["newpassword"].value;
		      	var password_conf = document.forms["new_user"]["newpassword_conf"].value;
		      	var fname = document.forms["new_user"]["firstname"].value;
		      	var lname = document.forms["new_user"]["lastname"].value;
		      	var pass_regex = new RegExp("^[a-zA-Z0-9!@#\$%\^\&*\)\(+=._-]{6,32}$");
		      	var name_regex = new RegExp("^[a-zA-Z-]{1,20}$");
		      	var username_regex = new RegExp("^[a-zA-Z0-9!@#\$%\^\&*\)\(+=._-]{6,50}$");

		      	// alert(username);
		      	if(!username_regex.test(username)){
		      		document.getElementById('formFeedback').innerHTML = "Error: Username does not fit requirements";
		      		document.getElementById('newusername').style.cssText = "border: 3px solid red";
		      		return false;
		      	}
		      	else if(password != password_conf){
		      		document.getElementById('formFeedback').innerHTML = "Error: Passwords do not match";
		      		document.getElementById('newpassword').style.cssText = "border: 3px solid red";
		      		document.getElementById('newpassword_conf').style.cssText = "border: 3px solid red";
		      		return false;
		      	}
		      	else if(!pass_regex.test(password)){
		      		document.getElementById('formFeedback').innerHTML = "Error: Password does not fit requirements";
		      		document.getElementById('newpassword').style.cssText = "border: 3px solid red";
		      		document.getElementById('newpassword_conf').style.cssText = "border: 3px solid red";
		      		return false;
		      	}
		      	else if(!name_regex.test(fname)){
		      		document.getElementById('formFeedback').innerHTML = "Error: Name longer than 20 chars, or does not contain only letters";
		      		document.getElementById('firstname').style.cssText = "border: 3px solid red";
		      		return false;
		      	}
		      	else if(!name_regex.test(lname)){
		      		document.getElementById('formFeedback').innerHTML = "Error: Name longer than 20 chars, or does not contain only letters";
		      		document.getElementById('lastname').style.cssText = "border: 3px solid red";
		      		return false;
		      	}
		      	else{
		      		return true;
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
				<a style="float: right" href="logout.php">Sign Out</a>
				<?php if(isset($_SESSION['myusername'])){ ?>
				<p style="float: right"><?php echo $_SESSION['myusername'];?></p>
			<?php } ?>
			</nav>
		</header>
		<div id="info">
			<h2>About this site</h2>
				<p>This site contains different tools for recording and tracking fitness information <br>
				Users may choose from pre-built diet and fitness plans, or they can craft their own</p>
		</div>
		<div id="login">
			<?php

			  // if no username set, then redirect to login
			  if(isset($_SESSION['myusername'])){
			?>
			<h2>Welcome, <?php echo $_SESSION['fname'] ?></h2>
				<h3>Your past 7 Days</h3>
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
				<h3>Your past 30 Days</h3>
				<canvas id="monthChart" width="400" height="400" style="margin: auto"></canvas>
				<script type="text/javascript">
					var month = <?php echo json_encode($month); ?>;
					var ctx = document.getElementById('monthChart');
					var labelList = [];
					var weights = [];
					var cals = [];
					for (var i = 0; i< month.length ; i++) {
						cals.push(month[i]['Calories']);
					}
					for (var i = 0; i<month.length;i++) {
						weights.push(month[i]['Weight']);
					}
					for(var i = 0; i < month.length; i++){
						labelList.push(month[i]["Date"].substring(5,10));
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
										"rgba(255, 26, 26, .2)",
										"rgba(255, 26, 26, .2)",
										"rgba(255, 26, 26, .2)",
										"rgba(255, 26, 26, .2)",
										"rgba(255, 26, 26, .2)",
										"rgba(255, 26, 26, .2)",
										"rgba(255, 26, 26, .2)",
										"rgba(255, 26, 26, .2)",
										"rgba(255, 26, 26, .2)",
										"rgba(255, 26, 26, .2)",
										"rgba(255, 26, 26, .2)",
										"rgba(255, 26, 26, .2)",
										"rgba(255, 26, 26, .2)",
										"rgba(255, 26, 26, .2)",
										"rgba(255, 26, 26, .2)",
										"rgba(255, 26, 26, .2)",
										"rgba(255, 26, 26, .2)",
										"rgba(255, 26, 26, .2)",
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
										"rgba(255, 26, 26, 1)",
										"rgba(255, 26, 26, 1)",
										"rgba(255, 26, 26, 1)",
										"rgba(255, 26, 26, 1)",
										"rgba(255, 26, 26, 1)",
										"rgba(255, 26, 26, 1)",
										"rgba(255, 26, 26, 1)",
										"rgba(255, 26, 26, 1)",
										"rgba(255, 26, 26, 1)",
										"rgba(255, 26, 26, 1)",
										"rgba(255, 26, 26, 1)",
										"rgba(255, 26, 26, 1)",
										"rgba(255, 26, 26, 1)",
										"rgba(255, 26, 26, 1)",
										"rgba(255, 26, 26, 1)",
										"rgba(255, 26, 26, 1)",
										"rgba(255, 26, 26, 1)",
										"rgba(255, 26, 26, 1)",
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
				<h3>Your past 365 Days</h3>
				<canvas id="yearChart" width="400" height="400" style="margin: auto"></canvas>
				<script type="text/javascript">
					var year = <?php echo json_encode($year); ?>;
					var ctx = document.getElementById('yearChart');
					var labelList = [];
					var weights = [];
					var cals = [];
					for (var i = 0; i< year.length ; i++) {
						cals.push(year[i]['Calories']);
					}
					for (var i = 0; i<year.length;i++) {
						weights.push(year[i]['Weight']);
					}
					for(var i = 0; i < year.length; i++){
						labelList.push(year[i]["Date"].substring(5,10));
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
										"rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)","rgba(255, 26, 26, .2)","rgba(255, 26, 26, .2)","rgba(255, 26, 26, .2)","rgba(255, 26, 26, .2)",   "rgba(255, 26, 26, .2)","rgba(255, 26, 26, .2)","rgba(255, 26, 26, .2)","rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)", "rgba(255, 26, 26, .2)"
										],
									borderColor : [
										 "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)", "rgba(255, 26, 26, 1)"
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
			<?php } else {?>
			<!-- login form or create user form-->
			<form name="loginForm" method="post" id="login_form" action="checklogin.php" onsubmit="return checkLoginForm()">
				<table border="0" cellpadding="3" cellspacing="1" style="margin-left: 400px; padding-top: 5px">
					<h2 id="formFeedback"><?php 
                          if (isset($_GET['err'])) {echo 'ERROR: Username - password not valid.'; }
                          if (isset($_GET['newUserSuccess'])) {echo'New User created successfully';}
                        ?></h2>
                  <tr>
                  	<td style="width:80px; text-align: right">Username:</td>
                  	<td ><input type="text" name="myusername" id="myusername" required ></td>
                  </tr>
                  <tr>
                  	<td style="width:80px; text-align: right">Password:</td>
                  	<td><input type="password" name="mypassword" id="mypassword" required></td>
                  </tr>
                  <tr >
                  	<td style="text-align: center;" colspan="2"><input type="submit" name="submit" value="Login"></td>
            </form>
                  </tr>	
              <form method="post" id="new_user" action="create_user.php" onsubmit="return checkNewUser();">
                  <tr>
                  	<td style="width: 110px; text-align: right">New Username:</td>
                  	<td><input type="text" name="newusername" id="newusername" required></td>
                  	<td>Min 6, Max 50 Chars</td>                 	
                  </tr>	
                  <tr>
                  	<td style="width: 110px; text-align: right">New Password:</td>
                  	<td><input type="password" name="newpassword" id="newpassword" required></td>
                  	<td> (More than 6 characters, Less than 32)</td>
                  </tr>
                  <tr>
                  	<td style="width:130px; text-align: right">Confirm Password:</td>
                  	<td><input type="password" name="newpassword_conf" id="newpassword_conf" required></td>
                  </tr>
                  <tr>
                  	<td style="text-align: right">First Name:</td>
                  	<td><input type="text" name="firstname" id="firstname" required></td>
                  </tr>
                  <tr>
                  	<td style="text-align: right">Last Name:</td>
                  	<td><input type="text" name="lastname" id="lastname" required></td>
                  </tr>
                  <tr>
                  	<td colspan="2" style="text-align: center"><input type="submit" name="submit" value="Create New User"></td>
                  </tr>

              </form>			
				</table>
			<?php } ?>
		</div>
	</body>
</html>
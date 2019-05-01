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

			$query = "SELECT NPlan FROM User WHERE UserID = ?";

			$statement = $db->prepare($query);
			$statement->bind_param('s', $_SESSION['myusername']);
			$statement->execute(); 
			$statement->store_result();
			$statement->bind_result($_SESSION['NPlan']);
			$statement->fetch();

			if(strlen($_SESSION['NPlan']) > 1){

			$query = "SELECT * FROM IN_NUTPLAN WHERE NUT_PLAN_NAME = ? ORDER BY Day ASC, Meal_num ASC;";

			$statement = $db->prepare($query);
			$statement->bind_param('s', $_SESSION['NPlan']);
			$statement->execute();
			$data = array();
			$results = $statement->get_result();

			while ($row = $results->fetch_assoc()) {
				array_push($data, $row);
			}
		

			$foodLoad = $data;

		}
?>
<html lang="en-US">
	<head>
		<title>Food Info</title>
		<meta charset="utf-8">
		<meta name="author" content="Matthew Kuhn, Andrea-Cristiano Seazzu, Noah White">
		<meta name="description" content="The page for viewing and creating foods">
		<meta name="documentation" content="https://stackoverflow.com/questions/8734503/how-can-i-center-an-unordered-list-with-dynamic-content-within-div for centering lists">
		<link rel="stylesheet" type="text/css" href="fit.css">
		<link rel="shortcut icon" type="image/png" href="favicon.ico">
		<script type="text/javascript">
			function pageUpdate(method){
				var url = "food_update.php";
				var request = new XMLHttpRequest();
				request.onload = function() {
		          var response = document.getElementById('weekly_meals');
		          response.value = request.response;
		          if(request.status == 404){
		          	document.getElementById('weekly_meals').innerHTML = "<h2>Error 404</h2>";
		          }else{
		          	document.getElementById('weekly_meals').innerHTML = "<h2>Your Weekly Plan</h2>"; 
		          }
		          	document.getElementById('weekly_meals').innerHTML += "<table id='food_table' style='margin:auto'>";
		          	var food = JSON.parse(request.response);
		          	var groceries = food[1];
		          	var food = food[0];
		          	document.getElementById('grocery_list').innerHTML = groceries["Tilapia"];
			        var prevDay = food[0]['Day'];
			        var currDay = food[0]['Day'];
			        var prevMeal = food[0]['Meal_num'];
			        var currMeal = food[0]['Meal_num'];
		          	for (var i = 0; i < food.length; i++) {
			          	currDay = food[i]['Day'];
			          	currMeal = food[i]['Meal_num'];
			          	if(currDay != prevDay || i == 0){
							switch(food[i]['Day']){
								case 1:
									document.getElementById('food_table').innerHTML += "<tr><th>Sunday</th><th></th><th></th></tr>";
									break;
								case 2:
									document.getElementById('food_table').innerHTML +=  "<tr><th>Monday</th><th></th><th></th></tr>";
									break;
								case 3:
									document.getElementById('food_table').innerHTML +=  "<tr><th>Tuesday</th><th></th><th></th></tr>";
									break;
								case 4:
									document.getElementById('food_table').innerHTML +=  "<tr><th>Wednesday</th><th></th><th></th></tr>";
									break;
								case 5:
									document.getElementById('food_table').innerHTML +=  "<tr><th>Thursday</th><th></th><th></th></tr>";
									break;
								case 6:
									document.getElementById('food_table').innerHTML +=  "<tr><th>Friday</th><th></th><th></th></tr>";
									break;
								case 7:
									document.getElementById('food_table').innerHTML +=  "<tr><th>Saturday</th><th></th><th></th></tr>";
									break;
							}
						}
						if(currMeal != prevMeal || i == 0){
							switch(food[i]['Meal_num']){
								case 1:
									document.getElementById('food_table').innerHTML +=  "<tr><td></td><td>Breakfast:</td><td>"+food[i]['M_Name']+"</td></tr>";
									break;
								case 2:
									document.getElementById('food_table').innerHTML +=  "<tr><td></td><td>Lunch:</td><td>"+food[i]['M_Name']+"</td></tr>";
									break;
								case 3:
									document.getElementById('food_table').innerHTML +=  "<tr><td></td><td>Dinner:</td><td>"+food[i]['M_Name']+"</td></tr>";
									break;
							}
						}else{
							document.getElementById('food_table').innerHTML +=  "<tr><td></td><td></td><td>"+food[i]['M_Name']+"</td></tr>";
						}

						prevDay = currDay;
						prevMeal = currMeal;
					}

					// document.getElementById('weekly_meals').innerHTML +=  "</table>";				
				
		          // document.getElementById('weekly_meals').innerHTML += "<p>"+request.response+"</p>";
		          
		          // var status = document.getElementById('status');
		          // status.value = request.status;
		        };
				if(method == 'POST'){
					var content = document.getElementById('mealPlan').value;
					content = {planName : content};
					content = JSON.stringify(content);
				}

				request.open(method, url, true);
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
		<div id="grocery_list" style="text-align: center; width: 66%">
			<h2>Your Grocery List</h2>
			<ul style="display: inline-block; list-style: none">
					<li><?php echo "chicken breast 12 oz"?></li>
					<li><?php echo "turkey breast 12 oz"?></li>
					<li><?php echo "chicken wing 12 oz"?></li>
					<li><?php echo "turkey wing 12 oz"?></li>
				</ul>
		</div>
		 <div id="weekly_meals" style="text-align:center; width:66%">
			<?php
				if(strlen($_SESSION['NPlan']) < 1){
			?>
			<h2>Please Select a Plan</h2>

			<?php		
				}
				else{
					print "<h2>Your Weekly Plan</h2>";
					print "<table style='margin: auto'>";
					$prevDay = $foodLoad[0]['Day'];
					$currDay = $foodLoad[0]['Day'];
					$prevMeal = $foodLoad[0]['Meal_num'];
					$currMeal = $foodLoad[0]['Meal_num'];
					for($i = 0; $i < count($foodLoad); $i ++){
						$currDay = $foodLoad[$i]['Day'];
						$currMeal = $foodLoad[$i]['Meal_num'];
						if($currDay !== $prevDay || $i == 0){
						switch($foodLoad[$i]['Day']){
							case 1:
								print "<tr><th>Sunday</th><th></th><th></th></tr>";
								break;
							case 2:
								print "<tr><th>Monday</th><th></th><th></th></tr>";
								break;
							case 3:
								print "<tr><th>Tuesday</th><th></th><th></th></tr>";
								break;
							case 4:
								print "<tr><th>Wednesday</th><th></th><th></th></tr>";
								break;
							case 5:
								print "<tr><th>Thursday</th><th></th><th></th></tr>";
								break;
							case 6:
								print "<tr><th>Friday</th><th></th><th></th></tr>";
								break;
							case 7:
								print "<tr><th>Saturday</th><th></th><th></th></tr>";
								break;
						}
					}
					if($currMeal !== $prevMeal || $i == 0){
						switch($foodLoad[$i]['Meal_num']){
							case 1:
								print "<tr><td></td><td>Breakfast:</td><td>".$foodLoad[$i]['M_Name']."</td></tr>";
								break;
							case 2:
								print "<tr><td></td><td>Lunch:</td><td>".$foodLoad[$i]['M_Name']."</td></tr>";
								break;
							case 3:
								print "<tr><td></td><td>Dinner:</td><td>".$foodLoad[$i]['M_Name']."</td></tr>";
								break;
						}
					}else{
						print "<tr><td></td><td></td><td>".$foodLoad[$i]['M_Name']."</td></tr>";
					}

						$prevDay = $currDay;
						$prevMeal = $currMeal;
					}

					print "</table>";				
				}

			}
			?>
		</div>
		<div id="meal_plans" style="width:66%">
			<h2>Current Meal Plan <!-- This is where  we will print the name from the DB--></h3>
				<!-- <form name="mealPlan"  id="mealPlan" onsubmit="pageUpdate('GET')" style="margin: auto;"> -->
					<!-- populate this select with all existing meal plans -->
					<select id="mealPlan" style="display: block; margin: 0 auto; margin-top: 5px; margin-bottom: 5px;">
						<option value="Chicken Only">Chicken Only</option>
						<option selected="selected" value="Basic Plan">Basic Plan</option>
						<option>Russia (potato only)</option>
						<option>O A T S</option>
					</select>
					<input type="submit" name="submit1" value="Select Plan" onclick="pageUpdate('POST')" style="display: block; margin: 0 auto; margin-bottom: 5px">
				<!-- </form> -->
				<!-- <form name="newPlan" id ="newPlan" method="?" action="" onsubmit="pageUpdate('POST')"> -->
					<input type="submit" name="submit" value="Create New Plan" onclick="pageUpdate('POST')" style="display: block; margin: 0 auto">
				<!-- </form> -->
		</div> 
	</body>
</html>
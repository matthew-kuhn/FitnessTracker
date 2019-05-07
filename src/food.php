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
			
			$query = "Select Ingredient_Name, Sum(ingredient_amount) as Amount from (Select Ingredient_Name, Ingredient_Amount, Meal_Name from Recipe) as x, (Select M_Name from In_Nutplan where Nut_Plan_Name = ?) as y where x.Meal_Name = y.M_Name group by x.ingredient_name;";

		
			$data1 = array();

			$statement = $db->prepare($query);
			$statement->bind_param('s', $_SESSION['NPlan']);
			$statement->execute();

			$results = $statement->get_result();

			while ($row = $results->fetch_assoc()) {
				array_push($data1, $row);
			}

			$newdata = array();

			$query2 = "SELECT Ingredient_Name, Unit, Amount*? As Total, Amount*?*`Price/Unit` As Price From ingredient where Ingredient_Name = ?;";

			for($i = 0; $i < count($data1); $i++){
				$statement2 = $db->prepare($query2);
				$statement2->bind_param("iis", $data1[$i]['Amount'], $data1[$i]['Amount'], $data1[$i]['Ingredient_Name']);
				$statement2->execute();

				$results = $statement2->get_result();

				while ($row = $results->fetch_assoc()) {
					array_push($newdata, $row);
				}
			}

			$query = "SELECT * from `Nutrition Plan`;";

			$plans = array();

			$statement = $db->prepare($query);
			$statement->execute();

			$results = $statement->get_result();

			while ($row = $results->fetch_assoc()) {
				array_push($plans, $row);
			}


			$query = "SELECT Ingredient_Name FROM Ingredient;";

			$statement = $db->prepare($query);
			$statement->execute();

			$results = $statement->get_result();
			$ingredients = array();
			while($row = $results->fetch_assoc()){
				array_push($ingredients, $row);
			}

			$query = "SELECT Meal_Name From Meal;";
			
			$statement = $db->prepare($query);
			$statement->execute();

			$results = $statement->get_result();
			$meals = array();
			while($row = $results->fetch_assoc()){
				array_push($meals, $row);
			}

			
			$groceries = $newdata;
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
			var ingredientCount = 1;
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
		          	// alert(request.response);
		          	var food = JSON.parse(request.response);
		          	var groceries = food[1];
		          	// alert(JSON.stringify(groceries));
		          	var food = food[0];
		          	// document.getElementById('grocery_list').innerHTML = groceries[1]['Ingredient_Name'];
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
					document.getElementById('grocery_list').innerHTML = "<h2>Grocery List</h2>";
					document.getElementById('grocery_list').innerHTML += "<table style='margin:auto; padding-bottom:5px' id='grocery_table'>";
					document.getElementById('grocery_table').innerHTML += "<tr><th>Ingredient Name:</th><th>Ingredient Amount:</th><th>Ingredient Unit:</th><th>Price:</th></tr>";
					var sum = 0
					for(var i = 0; i < groceries.length; i++){
						var price = parseFloat(groceries[i]["Price"]);
						sum += price;
						price = price.toFixed(2);
						// alert(groceries[i]['Total']);
						var total = parseFloat(groceries[i]['Total']);
						total = total.toFixed(2);
						document.getElementById('grocery_table').innerHTML += "<tr><td>"+groceries[i]['Ingredient_Name']+"</td><td style='text-align:right'>"+total+"</td><td>"+groceries[i]['Unit']+"</td><td>$"+price+"</td></tr>";
					}
						document.getElementById('grocery_table').innerHTML+= "<tr><td></td><td></td><th>Total:</th><td>$"+sum.toFixed(2)+"</td></tr>";

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
			function addIngredient(){
				var table = document.getElementById('mealMaker');
				ingredientCount += 1;
				var row = table.insertRow(table.rows.length-3);
				row.id = 'Ingredient'+ingredientCount;
				var cell1 = row.insertCell(0);
				var cell2 = row.insertCell(1);
				var cell3 = row.insertCell(2);
				var cell4 = row.insertCell(3);
				cell3.innerHTML = "Servings:";
				cell4.innerHTML = "<input type='number' name='Ingredient"+ingredientCount+"Servings' required>";
				var ingredients = <?php echo json_encode($ingredients); ?>;
				
				cell1.innerHTML = "Ingredient " + ingredientCount +":";
				cell2.innerHTML = "<select id='Ingredient"+ingredientCount+"select' name='Ingredient"+ ingredientCount+"'>";
				for(var i = 0; i < ingredients.length; i++){
					document.getElementById('Ingredient'+ingredientCount+'select').innerHTML += "<option>" + ingredients[i]['Ingredient_Name'] + "</option>";
				}
							
			}
			function removeIngredient(){
				var element = document.getElementById("Ingredient" + ingredientCount);
				element.parentNode.removeChild(element);
				ingredientCount -= 1;
			}
			var day1 = 0;
			var day2 = 0;
			var day3 = 0;
			var day4 = 0;
			var day5 = 0;
			var day6 = 0;
			var day7 = 0;
			function addFood(day, meal, food){
				document.getElementById(day+""+meal+""+food+"btn").disabled = true;
				var table = document.getElementById('Meal_Table');
				if(day == 1){
					if(day1 > 0 && meal > 1){
						var row = table.insertRow((day-1)*4 + (meal) + food+ (day1-food+1));
					}
					else{
						var row = table.insertRow((day-1)*4 + (meal) + food);
					}
					// var row = table.insertRow((day-1)*4 + (meal) + food);
				}if(day == 2){
					if(day2 > 0 && meal > 1){
						var row = table.insertRow((day-1)*4 + (meal) + food+day1+ (day2-food+1));
					}
					else{
						var row = table.insertRow((day-1)*4 + (meal) + food+day1);
					}
				}if(day == 3){
					if(day3 > 0 && meal > 1){
						var row = table.insertRow((day-1)*4 + (meal) + food+day1+day2+ (day3-food+1));
					}
					else{
						var row = table.insertRow((day-1)*4 + (meal) + food+day1+day2);
					}
				}if(day == 4){
					if(day4 > 0 && meal > 1){
						var row = table.insertRow((day-1)*4 + (meal) + food+day1+day2+day3+ (day4-food+1));
					}
					else{
						var row = table.insertRow((day-1)*4 + (meal) + food+day1+day2+day3);
					}
				}if(day == 5){
					if(day5 > 0 && meal > 1){
						var row = table.insertRow((day-1)*4 + (meal) + food+day1+day2+day3+day4+ (day5-food+1));
					}
					else{
						var row = table.insertRow((day-1)*4 + (meal) + food+day1+day2+day3+day4);
					}
				}if(day == 6){
					if(day6 > 0 && meal > 1){
						var row = table.insertRow((day-1)*4 + (meal) + food+day1+day2+day3+day4+day5+ (day6-food+1));
					}
					else{
						var row = table.insertRow((day-1)*4 + (meal) + food+day1+day2+day3+day4+day5);
					}
				}if(day == 7){
					if(day7 > 0 && meal > 1){
						var row = table.insertRow((day-1)*4 + (meal) + food+day1+day2+day3+day4+day5+day6+ (day7-food+1));
					}
					else{
						var row = table.insertRow((day-1)*4 + (meal) + food+day1+day2+day3+day4+day5+day6);
					}
				}
				row.id = ""+day+""+meal+""+(food+1);
				var cell1 = row.insertCell(0);
				var cell2 = row.insertCell(1);
				var cell3 = row.insertCell(2);
				var cell4 = row.insertCell(3);
				cell1.innerHTML = '<input type="button" name="Add Food" id="'+day+''+meal+''+(food+1)+'btn" Value="Add Food" onclick="addFood('+day+', '+meal+', '+(food+1)+')">';
				cell1.style = "text-align: right"
				cell2.innerHTML = "Meal "+meal+":";
				cell3.innerHTML = "<select id='"+day+""+meal+""+(food+1)+"select' name='Day["+day+"]["+meal+"]["+(food+1)+"]'>";
				var meals = <?php echo json_encode($meals); ?>;
				for(var i = 0; i < meals.length; i++){
					document.getElementById(""+day+""+meal+""+(food+1)+"select").innerHTML += "<option>" + meals[i]['Meal_Name'] + "</option>";
				}
				cell4.innerHTML = "<input type='button' name='Remove Food' value='Remove Food' onclick='removeFood("+day+", "+meal+", "+(food+1)+")'>";
				if(day == 1){
					day1 +=  1;
				}if(day == 2){
					day2 += 1;
				}if(day == 3){
					day3 += 1;
				}if(day == 4){
					day4 += 1;
				}if(day == 5){
					day5+=1;
				}if(day == 6){
					day6+=1;
				}if(day == 7){
					day7+=1;
				}
			}

			function removeFood(day, meal, food){
				if(day == 1){
					day1 -=  1;
				}if(day == 2){
					day2 -= 1;
				}if(day == 3){
					day3 -= 1;
				}if(day == 4){
					day4 -= 1;
				}if(day == 5){
					day5 -= 1;
				}if(day == 6){
					day6 -= 1;
				}if(day == 7){
					day7 -= 1;
				}
				var element = document.getElementById(""+day+""+meal+""+food);
				element.parentNode.removeChild(element);
				document.getElementById(""+day+""+meal+""+(food-1)+"btn").disabled = false;
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
			<?php 
				if(!isset($groceries)||count($groceries) === 0){
				?>

				<h2>Please Select a Plan</h2>
			<?php 
				}
				else{
					print "<h2>Grocery List</h2>";
					print "<table style='margin:auto; padding-bottom:5px'>";
					print "<tr><th>Ingredient Name:</th><th>Ingredient Amount:</th><th>Ingredient Unit:</th><th>Price:</th></tr>";
					$sum = 0;
					for($i = 0; $i<count($groceries); $i++){
						printf("<tr><td>".$groceries[$i]['Ingredient_Name']."</td><td style='text-align:right'>".$groceries[$i]['Total']."</td><td>".$groceries[$i]['Unit']."</td><td>$%0.2f</td></tr>", $groceries[$i]["Price"]);
						$sum+=$groceries[$i]["Price"];
					}
					printf("<tr><td></td><td></td><th>Total:</th><td>$%0.2f</td></tr>", $sum);
					print "</table>";
				}
				?>
			
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
					<select id="mealPlan" style="display: block; margin: 0 auto; margin-top: 5px; margin-bottom: 5px;">
						<?php
							for($i = 0; $i < count($plans); $i++){
								if($plans[$i]['Nutrition_Plan_Name'] === $_SESSION['NPlan']){
									print "<option selected='selected' value='".$_SESSION['NPlan']."'>".$_SESSION['NPlan']."</option>";
								}
								else{
									print "<option value='".$plans[$i]['Nutrition_Plan_Name']."'>".$plans[$i]['Nutrition_Plan_Name']."</option>";
								}
							}

						?>
					</select>
					<input type="submit" name="submit1" value="Select Plan" onclick="pageUpdate('POST')" style="display: block; margin: 0 auto; margin-bottom: 5px">
		</div>
		<div id="ingredientCreator" style="width: 66%">
			<h2>Create a New Ingredient</h2>
			<h2 id="formFeedback"><?php
				if(isset($_GET['IngredientError'])){ print "Error Creating Ingredent";}
				if(isset($_GET['IngredientCreation'])){print "Ingredient Creation Success";}
			?>	
			</h2>
			<form method="post" action="create_food.php">
				<table style="margin: auto">
					<tr>
						<td>Name:</td>
						<td><input type="text" name="Ingredient_Name" required maxlength="50"></td>
					</tr>
					<tr>
						<td>Unit</td>
						<td><input type="text" name="Unit" required maxlength="15"></td>
						<td>(ex. Oz or Lb)</td>
					</tr>
					<tr>
						<td>Price/Unit:</td>
						<td><input type="number" name="Price/unit" step=".01" required></td>
						<td>(ex. .4 for 40 cents/Lb)</td>
					</tr>
					<tr>
						<td>Serving Size:</td>
						<td><input type="number" step=".1" name="Amount" required></td>
						<td>(ex. if unit is Oz and serving size is 8 Oz, enter 8)</td>
					</tr>
					<tr>
						<td colspan="3" style="text-align: center"><input type="Submit" name="Submit"></td>
					</tr>
				</table>
			</form>
		</div>
		<div id="mealCreator" style="width: 66%">
			<h2>Create a New Meal</h2>
			<h2 id="formFeedback"><?php
				if(isset($_GET['MealError'])){ print "Error Creating Meal";}
				if(isset($_GET['foodCreation'])){print "Meal Creation Success";}
			?>	
			</h2>
			<form method="post" action="create_food.php">
				<table id="mealMaker" style="margin:auto">
					<tr>
						<td>Meal Name:</td>
						<td><input type="text" name="Meal_Name" required maxlength="50"></td>
						<td>Protein (g):</td>
						<td><input type="number" name="Protein" required></td>
					</tr>
					<tr>
						<td>Fat (g):</td>
						<td><input type="number" name="Fat" required></td>
						<td>Carbs (g):</td>
						<td><input type="number" name="Carbs" required></td>
					</tr>
					<tr>
						<td>Ingredient 1:</td>
						<td>
							<select name="Ingredient1">
								<?php
								for($i = 0; $i < count($ingredients); $i++){
									print "<option>".$ingredients[$i]['Ingredient_Name']."</option>";
								}
								?>
							</select>
						</td>
						<td>Servings:</td>
						<td><input type="number" name="Ingredient1Servings" required></td>
					</tr>
					<tr>
						<td></td>
						<td colspan="2" style="text-align: center"><input type="button" name="Add Ingredient" value="Add ingredient" onclick="addIngredient()"></td>
					</tr>
					<tr>
						<td></td>
						<td colspan="2" style="text-align: center"><input type="button" name="Remove Ingredient" value="Remove Ingredient" onclick="removeIngredient()"></td>
					</tr>
					<tr>
						<td></td>
						<td colspan="2" style="text-align: center"><input type="submit" name="Submit"></td>
					</tr>
				</table>
			</form>
		</div>
		<div id="planCreator" style="width:66%">
			<h2>Create a New Meal Plan</h2>
			<h2 id="formFeedback"><?php
				if(isset($_GET['PlanError']) && $_GET['PlanError'] == 1){ print "Error Creating Plan - Plan Name Already Exists";}
				if(isset($_GET['PlanError']) && $_GET['PlanError'] == 2){ print "Error Creating Plan - Could not insert one or more meals";}
				if(isset($_GET['PlanError']) && $_GET['PlanError'] > 2){print "Error Creating Plan";}
				if(isset($_GET['PlanCreation'])){print "Plan Creation Success";}
			?>	
			</h2>
			<form method="post" action="create_food.php">
				<table style="margin:auto" id='Meal_Table'>
					<tr>
						<th>Sunday:</th>
					</tr>
					<tr>
						<td style="text-align: right"><input type="button" id="111btn" name="Add Food" Value="Add Food" onclick="addFood(1, 1, 1)"></td>
						<td>Meal 1:</td>
						<td>
							<!-- <input type="hidden" name="Day" value="1"><input type="hidden" name="Meal" value="1"> -->
							<select name='Day[1][1][1]'> 
								<?php
								for($i = 0; $i < count($meals); $i++){
									print "<option>".$meals[$i]['Meal_Name']."</option>";
								}	
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td style="text-align: right"><input type="button" id="121btn" name="Add Food" Value="Add Food" onclick="addFood(1, 2, 1)"></td>
						<td>Meal 2:</td>
						<td>
							<!-- <input type="hidden" name="Day" value="1"><input type="hidden" name="Meal" value="2"> -->
							<select name="Day[1][2][1]">
								<?php
								for($i = 0; $i < count($meals); $i++){
									print "<option>".$meals[$i]['Meal_Name']."</option>";
								}	
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td style="text-align: right"><input type="button" id="131btn" name="Add Food" Value="Add Food" onclick="addFood(1, 3, 1)"></td>
						<td>Meal 3:</td>
						<td>
							<!-- <input type="hidden" name="Day" value="1"><input type="hidden" name="Meal" value="3"> -->
							<select name="Day[1][3][1]">
								<?php
								for($i = 0; $i < count($meals); $i++){
									print "<option>".$meals[$i]['Meal_Name']."</option>";
								}	
								?>
							</select>
						</td>
					</tr>
					<tr>
						<th>Monday:</th>
					</tr>
					<tr>
						<td style="text-align: right"><input type="button" id="211btn" name="Add Food" Value="Add Food" onclick="addFood(2, 1, 1)"></td>
						<td>Meal 1:</td>
						<td>
							<!-- <input type="hidden" name="Day" value="2"><input type="hidden" name="Meal" value="1"> -->
							<select name='Day[2][1][1]'> 
								<?php
								for($i = 0; $i < count($meals); $i++){
									print "<option>".$meals[$i]['Meal_Name']."</option>";
								}	
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td style="text-align: right"><input type="button" id="221btn" name="Add Food" Value="Add Food" onclick="addFood(2, 2, 1)"></td>
						<td>Meal 2:</td>
						<td>
							<!-- <input type="hidden" name="Day" value="2"><input type="hidden" name="Meal" value="2"> -->
							<select name="Day[2][2][1]">
								<?php
								for($i = 0; $i < count($meals); $i++){
									print "<option>".$meals[$i]['Meal_Name']."</option>";
								}	
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td style="text-align: right"><input type="button" id="231btn" name="Add Food" Value="Add Food" onclick="addFood(2, 3, 1)"></td>
						<td>Meal 3:</td>
						<td>
							<!-- <input type="hidden" name="Day" value="2"><input type="hidden" name="Meal" value="3"> -->
							<select name="Day[2][3][1]">
								<?php
								for($i = 0; $i < count($meals); $i++){
									print "<option>".$meals[$i]['Meal_Name']."</option>";
								}	
								?>
							</select>
						</td>
					</tr>
					<tr>
						<th>Tuesday:</th>
					</tr>
					<tr>
						<td style="text-align: right"><input type="button" id="311btn" name="Add Food" Value="Add Food" onclick="addFood(3, 1, 1)"></td>
						<td>Meal 1:</td>
						<td>
							<!-- <input type="hidden" name="Day" value="3"><input type="hidden" name="Meal" value="1"> -->
							<select name='Day[3][1][1]'> 
								<?php
								for($i = 0; $i < count($meals); $i++){
									print "<option>".$meals[$i]['Meal_Name']."</option>";
								}	
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td style="text-align: right"><input type="button" id="321btn" name="Add Food" Value="Add Food" onclick="addFood(3,2, 1)"></td>
						<td>Meal 2:</td>
						<td>
							<!-- <input type="hidden" name="Day" value="3"><input type="hidden" name="Meal" value="2"> -->
							<select name="Day[3][2][1]">
								<?php
								for($i = 0; $i < count($meals); $i++){
									print "<option>".$meals[$i]['Meal_Name']."</option>";
								}	
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td style="text-align: right"><input type="button" id="331btn" name="Add Food" Value="Add Food" onclick="addFood(3, 3, 1)"></td>
						<td>Meal 3:</td>
						<td>
							<!-- <input type="hidden" name="Day" value="3"><input type="hidden" name="Meal" value="3"> -->
							<select name="Day[3][3][1]">
								<?php
								for($i = 0; $i < count($meals); $i++){
									print "<option>".$meals[$i]['Meal_Name']."</option>";
								}	
								?>
							</select>
						</td>
					</tr>
					<tr>
						<th>Wednesday:</th>
					</tr>
					<tr>
						<td style="text-align: right"><input type="button" id="411btn" name="Add Food" Value="Add Food" onclick="addFood(4, 1, 1)"></td>
						<td>Meal 1:</td>
						<td>
							<!-- <input type="hidden" name="Day" value="4"><input type="hidden" name="Meal" value="1"> -->
							<select name='Day[4][1][1]'> 
								<?php
								for($i = 0; $i < count($meals); $i++){
									print "<option>".$meals[$i]['Meal_Name']."</option>";
								}	
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td style="text-align: right"><input type="button" id="421btn" name="Add Food" Value="Add Food" onclick="addFood(4, 2, 1)"></td>
						<td>Meal 2:</td>
						<td>
							<!-- <input type="hidden" name="Day" value="4"><input type="hidden" name="Meal" value="2"> -->
							<select name="Day[4][2][1]">
								<?php
								for($i = 0; $i < count($meals); $i++){
									print "<option>".$meals[$i]['Meal_Name']."</option>";
								}	
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td style="text-align: right"><input type="button" id="431btn" name="Add Food" Value="Add Food" onclick="addFood(4, 3, 1)"></td>
						<td>Meal 3:</td>
						<td>
							<!-- <input type="hidden" name="Day" value="4"><input type="hidden" name="Meal" value="3"> -->
							<select name="Day[4][3][1]">
								<?php
								for($i = 0; $i < count($meals); $i++){
									print "<option>".$meals[$i]['Meal_Name']."</option>";
								}	
								?>
							</select>
						</td>
					</tr>
					<tr>
						<th>Thursday:</th>
					</tr>
					<tr>
						<td style="text-align: right"><input type="button" id="511btn" name="Add Food" Value="Add Food" onclick="addFood(5, 1, 1)"></td>
						<td>Meal 1:</td>
						<td>
							<!-- <input type="hidden" name="Day" value="5"><input type="hidden" name="Meal" value="1"> -->
							<select name='Day[5][1][1]'> 
								<?php
								for($i = 0; $i < count($meals); $i++){
									print "<option>".$meals[$i]['Meal_Name']."</option>";
								}	
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td style="text-align: right"><input type="button" id="521btn" name="Add Food" Value="Add Food" onclick="addFood(5, 2, 1)"></td>
						<td>Meal 2:</td>
						<td>
							<!-- <input type="hidden" name="Day" value="5"><input type="hidden" name="Meal" value="2"> -->
							<select name="Day[5][2][1]">
								<?php
								for($i = 0; $i < count($meals); $i++){
									print "<option>".$meals[$i]['Meal_Name']."</option>";
								}	
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td style="text-align: right"><input type="button" id="531btn" name="Add Food" Value="Add Food" onclick="addFood(5, 3, 1)"></td>
						<td>Meal 3:</td>
						<td>
							<!-- <input type="hidden" name="Day" value="5"><input type="hidden" name="Meal" value="3"> -->
							<select name="Day[5][3][1]">
								<?php
								for($i = 0; $i < count($meals); $i++){
									print "<option>".$meals[$i]['Meal_Name']."</option>";
								}	
								?>
							</select>
						</td>
					</tr>
					<tr>
						<th>Friday:</th>
					</tr>
					<tr>
						<td style="text-align: right"><input type="button" id="611btn" name="Add Food" Value="Add Food" onclick="addFood(6, 1, 1)"></td>
						<td>Meal 1:</td>
						<td>
							<!-- <input type="hidden" name="Day" value="6"><input type="hidden" name="Meal" value="1"> -->
							<select name='Day[6][1][1]'> 
								<?php
								for($i = 0; $i < count($meals); $i++){
									print "<option>".$meals[$i]['Meal_Name']."</option>";
								}	
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td style="text-align: right"><input type="button" id="621btn" name="Add Food" Value="Add Food" onclick="addFood(6, 2, 1)"></td>
						<td>Meal 2:</td>
						<td>
							<!-- <input type="hidden" name="Day" value="6"><input type="hidden" name="Meal" value="2"> -->
							<select name="Day[6][2][1]">
								<?php
								for($i = 0; $i < count($meals); $i++){
									print "<option>".$meals[$i]['Meal_Name']."</option>";
								}	
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td style="text-align: right"><input type="button" id="631btn" name="Add Food" Value="Add Food" onclick="addFood(6, 3, 1)"></td>
						<td>Meal 3:</td>
						<td>
							<!-- <input type="hidden" name="Day" value="6"><input type="hidden" name="Meal" value="3"> -->
							<select name="Day[6][3][1]">
								<?php
								for($i = 0; $i < count($meals); $i++){
									print "<option>".$meals[$i]['Meal_Name']."</option>";
								}	
								?>
							</select>
						</td>
					</tr>
					<tr>
						<th>Saturday:</th>
					</tr>
					<tr>
						<td style="text-align: right"><input type="button" id="711btn" name="Add Food" Value="Add Food" onclick="addFood(7, 1, 1)"></td>
						<td>Meal 1:</td>
						<td>
							<!-- <input type="hidden" name="Day" value="7"><input type="hidden" name="Meal" value="1"> -->
							<select name='Day[7][1][1]'> 
								<?php
								for($i = 0; $i < count($meals); $i++){
									print "<option>".$meals[$i]['Meal_Name']."</option>";
								}	
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td style="text-align: right"><input type="button" id="721btn" name="Add Food" Value="Add Food" onclick="addFood(7, 2, 1)"></td>
						<td>Meal 2:</td>
						<td>
							<!-- <input type="hidden" name="Day" value="7"><input type="hidden" name="Meal" value="2"> -->
							<select name="Day[7][2][1]">
								<?php
								for($i = 0; $i < count($meals); $i++){
									print "<option>".$meals[$i]['Meal_Name']."</option>";
								}	
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td style="text-align: right"><input type="button" id="731btn" name="Add Food" Value="Add Food" onclick="addFood(7, 3, 1)"></td>
						<td>Meal 3:</td>
						<td>
							<!-- <input type="hidden" name="Day" value="7"><input type="hidden" name="Meal" value="3"> -->
							<select name="Day[7][3][1]">
								<?php
								for($i = 0; $i < count($meals); $i++){
									print "<option>".$meals[$i]['Meal_Name']."</option>";
								}	
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="text-align:center">Plan Name:</td>
						<td colspan="1" style="text-align:center"><input type="text" name="Plan_Name" required></td>
					</tr>
					<tr>
						<td colspan="3" style="text-align: center"><input type="submit" name="Submit"></td>
					</tr>
				</table>
			</form>
		</div> 
	</body>
</html>
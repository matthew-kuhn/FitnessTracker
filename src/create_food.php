<?php

	@ $db = new mysqli('LOCALHOST', 'root', '', 'FitnessTracker');

	if(mysqli_connect_errno()){
		echo 'Error: could not connect to database, code: '.mysqli_connect_errno();
		exit;
	}

	#creating a plan
	if(isset($_POST['Plan_Name'])){
		$query = "SELECT * FROM `Nutrition Plan` where Nutrition_Plan_Name = ?;";

		$planName = stripslashes($_POST['Plan_Name']);
		$planName = $db->real_escape_string($planName);

		$statement = $db->prepare($query);
		$statement->bind_param('s', $planName);
		$statement->execute();
		$statement->store_result();
		if($statement->num_rows > 0){
			header("location:food.php?PlanError=1");
			exit;
		}

		$query = "INSERT INTO `Nutrition Plan` (Nutrition_Plan_Name) VALUES (?);";
		$statement = $db->prepare($query);
		$statement->bind_param('s', $planName);
		$statement->execute();
		if ($statement->errno <> 0)
		{
			$statement->close();
			$db->close();
			header("location:food.php?PlanError=1");
			exit;
		}

		$query = "INSERT INTO IN_NUTPLAN (NUT_PLAN_NAME, M_NAME, DAY, MEAL_NUM) VALUES (?,?,?,?);";

		for($i = 1; $i <= 7; $i ++){
			for($j = 1; $j <= 3; $j ++){
				$keys = array_keys($_POST['Day'][$i][$j]);
				for($k = 0; $k < count($keys); $k++){
					$statement = $db->prepare($query);
					$statement->bind_param("ssii", $planName, $_POST['Day'][$i][$j][$keys[$k]], $i, $j);
					$statement->execute();
					if ($statement->errno <> 0)
					{
						$statement->close();
						$db->close();
						header("location:food.php?PlanError=2");
						exit;
					}
				}
			}
		}

		header("location:food.php?PlanCreation=1");
	}

	#creating a meal
	else if(isset($_POST['Meal_Name'])){

		$query = "INSERT INTO MEAL (Meal_Name, Protein, Fat, Carbs) VALUES (?,?,?,?);";

		$mealName = stripslashes($_POST['Meal_Name']);
		$mealName = $db->real_escape_string($mealName);

		$protein = stripslashes($_POST['Protein']);
		$protein = $db->real_escape_string($protein);

		$fat = stripslashes($_POST['Fat']);
		$fat = $db->real_escape_string($fat);

		$carbs = stripslashes($_POST['Carbs']);
		$carbs = $db->real_escape_string($carbs);

		$statement = $db->prepare($query);
		$statement->bind_param("siii", $mealName, $protein, $fat, $carbs);
		$statement->execute();
		if ($statement->errno <> 0)
		{
			$statement->close();
			$db->close();
			header("location:food.php?MealError=1");
			exit;
		}

		$query = "INSERT INTO Recipe (Ingredient_Name, Meal_Name, Ingredient_Amount) VALUES (?, ?, ?);";
		$count = 0;
		$other = 0;
		foreach($_POST as $item){
			if($count > 3 && $count < count($_POST) -2){
				if($count % 2 === 0){
					$statement = $db->prepare($query);
					$postget = 'Ingredient'.($count-3 -$other).'Servings';
					$other += 1;
					$statement->bind_param("ssi", $item, $_POST['Meal_Name'], $_POST[$postget]);
					$statement->execute();

					if ($statement->errno <> 0)
					{
						$statement->close();
						$db->close();
						header("location:food.php?MealError=2");
						exit;
					}
				}
			}
			$count ++;
		}

		header("location:food.php?foodCreation=1");
		exit;
	}

	#creating an ingredient
	else if(isset($_POST['Ingredient_Name'])){

		$query = "INSERT INTO Ingredient (Ingredient_Name, `Price/Unit`, Unit, Amount) VALUES (?,?,?,?);";

		$statement = $db->prepare($query);

		$ingredientName = stripslashes($_POST['Ingredient_Name']);
		$ingredientName = $db->real_escape_string($ingredientName);

		$price = stripslashes($_POST['Price/unit']);
		$price = $db->real_escape_string($price);

		$unit = stripslashes($_POST['Unit']);
		$unit = $db->real_escape_string($unit);

		$Amount = stripslashes($_POST['Amount']);
		$Amount = $db->real_escape_string($Amount);

		$statement->bind_param("sdsi", $ingredientName, $price, $unit, $Amount);

		$statement->execute();

		if ($statement->errno <> 0)
		{
			$statement->close();
			$db->close();
			header("location:food.php?IngredientError=1");
			exit;
		}

		header("location:food.php?IngredientCreation=1");
		exit;
	}
	else{
	header("location:food.php?creationFail=1");
	}
?>
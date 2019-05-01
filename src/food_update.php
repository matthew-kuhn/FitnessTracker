<?php
	
	session_start();

	function getPlan($planName){
		global $db;

		$query = "SELECT * FROM IN_NUTPLAN WHERE NUT_PLAN_NAME = ? ORDER BY Day ASC, Meal_num ASC;";

		$statement = $db->prepare($query);
		$statement->bind_param('s', $planName);
		$statement->execute();
		$data = array();
		$results = $statement->get_result();

		while ($row = $results->fetch_assoc()) {
			array_push($data, $row);
		}
	
		return $data;

	}

	function getGroceries($plan, $planName){
		global $db;

		$query = "SELECT Ingredient_Name, SUM(Ingredient_Amount) as Amount FROM Recipe WHERE Meal_Name in (Select M_Name From IN_NUTPLAN where Nut_Plan_Name = ?) GROUP BY Ingredient_Name;";

		
		$data = array();

		$statement = $db->prepare($query);
		$statement->bind_param('s', $planName);
		$statement->execute();

		$results = $statement->get_result();

		while ($row = $results->fetch_assoc()) {
			array_push($data, $row);
		}

		$newdata = array();

		for($i = 0; $i < count($data); $i++){
			$newdata[$data[$i]['Ingredient_Name']] = $data[$i]['Amount'];
		}

		return $newdata;
	}

	function changeMealPlan($planName){
		global $db;

		$query = "UPDATE User SET NPlan = ? WHERE UserID = ?;";

		$statement = $db->prepare($query);

		$statement->bind_param('ss', $planName, $_SESSION['myusername']);
		$statement->execute();

		$results = $statement->get_result();

		return $results;
	}

	function createPlan($plan){
		global $db;

		$query = "INSERT INTO `Nutrition Plan` (Nutrition_Plan_Name) VALUES ?;";

		$meals = "INSERT INTO IN_NUTPLAN (Nut_Plan_Name, M_Name, Day, Meal_num) Values (?,?,?,?);";
	}

	function createMeal($meal){

	}

	function getMeal($mealName){
		global $db;
		$query = "SELECT * FROM Meal WHERE Meal_Name = ?;";

		$statement = $db->prepare($query);

		$statement->bind_param('s', $mealName);

		$statement->execute();

		$results = $statement->get_result();
		
	}

	function createIngredient($ingredient){

	}

	function getIngredient($ingredientName){
		global $db;
		$query = "SELECT * FROM Ingredient WHERE Ingredient_Name = ?;";

		$statement = $db->prepare($query);

		$statement->bind_param('s', $ingredientName);

		$statement->execute();

		$results = $statement->get_result();
	}

	function createRecipe($recipe){

	}

	function getRecipe($recipe){
		global $db;
		$query = "SELECT * FROM Recipe WHERE Meal_Name = ? AND Ingredient_Name = ?;";

		$statement = $db->prepare($query);

		$statement->bind_param('s', $mealName);
		#gonna need a for loop to get all the ingredients for a given recipe
		$statement->execute();

		$results = $statement->get_result();
	}

	$db = new mysqli('LOCALHOST', 'root', '', 'FitnessTracker');

	header('Content-Type: application/json');
	switch($_SERVER['REQUEST_METHOD']){
		case 'GET':
			if(isset($_REQUEST['planName']) && 0 < strlen($_REQUEST['planName'])){
				$data = getPlan($_REQUEST['planName']);
				changeMealPlan($_REQUEST['planName']);
				if(!isset($data)){
					http_response_code(404);
					die();
				}
			}
			else if (isset($_REQUEST['ingredientList']) && 0 < strlen($_REQUEST['ingredientList']) && isset($_REQUEST['mealName']) && 0 < strlen($_REQUEST['mealName'])) {
				$data = getRecipe($_REQUEST['recipeName']);
				if(!isset($data)){
					http_response_code(404);
					die();
				}
			}
			else if(isset($_REQUEST['mealName']) && 0 < strlen($_REQUEST['mealName'])){
				$data = getMeal($_REQUEST['mealName']);
				if(!isset($data)){
					http_response_code(404);
					die();
				}
			}
			else if(isset($_REQUEST['ingredientName']) && 0 < strlen($_REQUEST['ingredientName'])){
				$data = getIngredient($_REQUEST['ingredientName']);
				if(!isset($data)){
					http_response_code(404);
					die();
				}
			}
			if(!isset($data)){
				http_response_code(404);
				die();
			}

			echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
			break;

		case 'POST':
			$data = json_decode(file_get_contents('php://input'), true);

				
			if(isset($_REQUEST['plan']) && strlen($_REQUEST['plan']) > 0){
				createPlan($plan);
				http_response_code(201);
			}
			else if(isset($data['planName']) && 0 < strlen($data['planName'])){

				changeMealPlan($data['planName']);

				$info = getPlan($data['planName']);
				$groceries = getGroceries($info, $data['planName']);

				$data = array();

				array_push($data, $info);
				array_push($data, $groceries);

				http_response_code(201);

				echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
				
			}
			else if(isset($_REQUEST['meal']) && strlen($_REQUEST['meal']) > 0){
				createMeal($meal);
				http_response_code(201);
			}
			else if(isset($_REQUEST['ingredient']) && strlen($_REQUEST['ingredient']) > 0){
				createIngredient($ingredient);
				http_response_code(201);
			}
			else if(isset($_REQUEST['recipe']) && strlen($_REQUEST['recipe']) > 0){
				$recipe = json_decode(file_get_contents('php://input'), true);
				createRecipe($recipe);
				http_response_code(201);
			}
			// http_response_code(201);
			break;

	}

?>
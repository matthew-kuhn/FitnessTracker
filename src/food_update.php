<?php

	function getPlan($planName){
		global $db;

		$query = "SELECT * FROM IN_NUTPLAN WHERE NUT_PLAN_NAME = ? ORDER BY Day ASC, Meal_num DESC;"

		$statement = $db->prepare($query);

		if (! empty($params)) {
		$statement->bind_param(str_repeat('s', count($params)), ...$params);
		}
		$statement->execute();

		$data = array();

		$results = $statement->get_result();

		return $results;

	}

	function changeMealPlan($planName){
		global $db;

		$query = ""
	}

	function createPlan($plan){

	}

	function createMeal($meal){

	}

	function getMeal($mealName){
		
	}

	function createIngredient($ingredient){

	}

	function getIngredient($ingredientName){

	}

	function createRecipe($recipe){

	}

	function getRecipe($recipeName){

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
			else if (isset($_REQUEST['recipeName']) && 0 < strlen($_REQUEST['recipeName'])) {
				$data = getRecipe($_REQUEST['recipeName']);
				if(!isset($data)){
					http_response_code(404);
					die();
				}
			}

			echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
			break;

		case 'POST':
			if(isset($_REQUEST['plan']) && strlen($_REQUEST['plan']) > 0){
				$plan = json_decode(file_get_contents('php://input'), true);
				createPlan($plan);
			}
			else if(isset($_REQUEST['meal']) && strlen($_REQUEST['meal']) > 0){
				$meal = json_decode(file_get_contents('php://input'), true);
				createMeal($meal);
			}
			else if(isset($_REQUEST['ingredient']) && strlen($_REQUEST['ingredient']) > 0){
				$ingredient = json_decode(file_get_contents('php://input'), true);
				createIngredient($ingredient);
			}
			else if(isset($_REQUEST['recipe']) && strlen($_REQUEST['recipe']) > 0){
				$recipe = json_decode(file_get_contents('php://input'), true);
				createRecipe($recipe);
			}
			http_response_code(201);
			break;

	}

?>
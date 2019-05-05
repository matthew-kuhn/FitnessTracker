<?php
	session_start();

	function getPlan($planName){
		global $db;

		$query = "SELECT * FROM IN_WPLAN WHERE WPLAN_NAME = ? ORDER BY Day ASC";

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

	function changeWorkoutPlan($planName){
		global $db;

		$query = "UPDATE User SET WPlan = ? WHERE UserID = ?;";

		$statement = $db->prepare($query);

		$statement->bind_param('ss', $planName, $_SESSION['myusername']);
		$statement->execute();

		$results = $statement->get_result();

		return $results;
	}

	function getWorkout($name){
		global $db;

		$query = "SELECT Session.Exercise_Name as Exercise_Name, Exercise_Desc, Reps, Sets FROM Session, Exercise where session.exercise_name = exercise.exercise_name AND workout_name = ?;";

		$statement = $db->prepare($query);
		$statement->bind_param('s', $name);
		$statement->execute();
		$data = array();
		$results = $statement->get_result();

		while ($row = $results->fetch_assoc()) {
			array_push($data, $row);
		}
	
		return $data;
	}

	$db = new mysqli('LOCALHOST', 'root', '', 'FitnessTracker');

	header('Content-Type: application/json');
	switch($_SERVER['REQUEST_METHOD']){
		
		case 'POST':
			$data = json_decode(file_get_contents('php://input'), true);

				
			if(isset($data['planName']) && 0 < strlen($data['planName'])){

				changeWorkoutPlan($data['planName']);

				$info = getPlan($data['planName']);

				http_response_code(201);

				echo json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
				
			}
			else if(isset($data['W_Name'])){
				$info = getWorkout($data['W_Name']);

				http_response_code(201);

				echo json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
			}
			
			break;

	}

?>
<?php

	@ $db = new mysqli('LOCALHOST', 'root', '', 'FitnessTracker');

	if(mysqli_connect_errno()){
		echo 'Error: could not connect to database, code: '.mysqli_connect_errno();
		exit;
	}

	#create new plan
	if(isset($_POST['Plan_Name'])){
		$query = "INSERT INTO `Workout Plan` (Workout_Plan_Name) VALUES ?;";

		$planName = stripslashes($_POST["Plan_Name"]);
		$planName = $db->real_escape_string($planName);

		$statement = $db->prepare($query);
		$statement->bind_param("s", $planName);
		$statement->execute();
		if ($statement->errno <> 0)
		{
			$statement->close();
			$db->close();
			header("location:workouts.php?planError=1");
			exit;
		}

		$query = "INSERT INTO In_wplan (W_Name, WPlan_Name, Day) VALUES (?,?,?);";

		$keys = array_keys($_POST['Workout']);
		for($i = 0; $i < count($keys); $i++){
			$statement = $db->prepare($query);
			$statement->bind_param("ssi", $_POST['Workout'][$keys[$i]]['name'], $planName, $_POST['Workout'][$keys[$i]]);
			$statement->execute();
			if ($statement->errno <> 0)
			{
				$statement->close();
				$db->close();
				header("location:workouts.php?planError=2");
				exit;
			}
		}
		header("location:workouts.php?planSuccess=1");
		exit();
	}

	#create new workout
	else if(isset($_POST['W_Name'])){
		$query = "INSERT INTO Workout (Workout_Name, Type) VALUES (?,?);";

		$statement = $db->prepare($query);

		$wname = stripslashes($_POST['W_Name']);
		$wname = $db->real_escape_string($wname);

		$wtype = stripslashes($_POST['Type']);
		$wtype = $db->real_escape_string($wtype);

		$statement->bind_param("ss", $wname, $wtype);
		$statement->execute();
		if ($statement->errno <> 0)
		{
			$statement->close();
			$db->close();
			header("location:workouts.php?workoutError=1");
			exit;
		}

		$query = "INSERT INTO Session (Workout_Name, Exercise_Name, Reps, Sets) VALUES (?,?,?,?);";
		for($i = 0; $i < count(array_keys($_POST['Exercise'])); $i++){
			$statement->bind_param("ssii", $wname, $_POST['Exercise']['name'], $_POST['Exercise']['reps'], $_POST['Exercise']['sets']);
			$statement->execute();
			if ($statement->errno <> 0)
			{
				$statement->close();
				$db->close();
				header("location:workouts.php?workoutError=2");
				exit;
			}
		}
		header("location:workouts.php?workoutSuccess=1");
	}	

	#create new exercise
	else if(isset($_POST['Exercise_Name'])){
		$query = "INSERT INTO Exercise (Exercise_Name, Exercise_Desc) VALUES (?,?);";

		$statement = $db->prepare($query);

		$ename = stripslashes($_POST['Exercise_Name']);
		$ename = $db->real_escape_string($ename);

		$edesc = stripslashes($_POST['Exercise_Desc']);
		$edesc = $db->real_escape_string($edesc);

		$statement->bind_param("ss", $ename, $edesc);
		$statement->execute();

		if ($statement->errno <> 0)
		{
			$statement->close();
			$db->close();
			header("location:workouts.php?ExerciseError=1");
			exit;
		}

		header("location:workouts.php?exerciseSuccess=1");
		exit;
	}
	
	else{
		header('location:workouts.php?creationFail=1');
	}
?>
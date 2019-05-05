<?php
	session_start();

	@ $db = new mysqli('LOCALHOST', 'root', '', 'FitnessTracker');

	if(mysqli_connect_errno()){
		echo 'Error: could not connect to database, code: '.mysqli_connect_errno();
		exit;
	}

	$query = "INSERT INTO Day (Date, UserID, Weight, Calories) VALUES (?,?,?,?);";

	$statement = $db->prepare($query);
	$statement->bind_param("ssii", $_POST['date'], $_SESSION['myusername'], $_POST['weight'], $_POST['cals']);
	$statement->execute();
	if ($statement->errno <> 0)
	{
		$statement->close();
		$db->close();
		header("location:tracking.php?TrackError=1");
		exit;
	}

	header("location:tracking.php?TrackSuccess=1");

?>

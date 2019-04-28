<?php

	@ $db = new mysqli('LOCALHOST', 'root', '', 'FitnessTracker');

	if(mysqli_connect_errno()){
		echo 'Error: could not connect to database, code: '.mysqli_connect_errno();
		exit;
	}

	$newUsername = stripslashes($_POST['newusername']);
	$newUsername = $db->real_escape_string($newUsername);

	$newPassword = stripslashes($_POST['newpassword']);
	$newPassword = $db->real_escape_string($newPassword);

	$fname = stripslashes($_POST['firstname']);
	$fname = $db->real_escape_string($fname);

	$lname = stripslashes($_POST['lastname']);
	$lname = $db->real_escape_string($lname);

	$newPassword = hash('sha256', $newPassword);

	$checkQuery = "SELECT * FROM User WHERE UserID = ?";

	$checkStmt = $db->prepare($checkQuery);

	$checkStmt->bind_param("s", $newUsername);

	$checkStmt->execute();

	$checkStmt->store_result();

	if ( ($checkStmt->errno <> 0) || ($checkStmt->num_rows > 0) )
	{
	$checkStmt->close();
	header("location:index.php?newUserError=2");
	exit;
	}

	$checkStmt->close();

	// set up a prepared statement to insert the new user info

	$query = "INSERT INTO User (UserID, Password, First_Name, Last_Name) VALUES ( ?, ?, ?, ?)";

	$stmt = $db->prepare($query);

	$stmt->bind_param("ssss", $newUsername, $newPassword, $fname, $lname);

	$stmt->execute();

	if ($stmt->errno <> 0)
	{
	$stmt->close();
	$db->close();
	header("location:index.php?newUserError=3");
	exit;
	}

	$stmt->close();

  	$db->close();

	header("location:index.php?newUserSuccess=1");
?>

<?php

	@ $db = new mysqli('LOCALHOST', 'root', '', 'FitnessTracker');

	if(mysqli_connect_errno()){
		echo 'Error: could not connect to database, code: '.mysqli_connect_errno();
		exit;
	}

	$newUsername = stripslashes($_POST['newUsername']);
  $newUsername = $db->real_escape_string($newUsername);

  $newPassword = stripslashes($_POST['newPassword']);
  $newPassword = $db->real_escape_string($newPassword);

  $newPassword = hash('sha256', $newPassword);

  $checkQuery = "SELECT * FROM USERS WHERE userName = ?";

  $checkStmt = $db->prepare($checkQuery);

  $checkStmt->bind_param("s", $newUsername);

  $checkStmt->execute();

  $checkStmt->store_result();
?>

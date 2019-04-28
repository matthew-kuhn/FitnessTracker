<?php
	
	session_start();

	if(isset($_POST['myusername']) && isset($_POST['mypassword'])){

		@ $db = new mysqli('LOCALHOST', 'root', '', 'FitnessTracker');

		if(mysqli_connect_errno()){
			echo "ERROR: could not connect to database with code: ".mysqli_connect_errno();
			exit;
		}

		$myusername = stripslashes($_POST['myusername']);
		$myusername = $db->real_escape_string($myusername);

		$mypassword = stripslashes($_POST['mypassword']);
		$mypassword = $db->real_escape_string($mypassword);

		$mypassword = hash('sha256', $mypassword);

		$query = "SELECT * FROM USER WHERE (UserID = ?) AND (Password = ?)";

		$stmt = $db->prepare($query);

		$stmt->bind_param("ss", $myusername, $mypassword);

		echo "".$myusername." ".$mypassword;
		// exit;

		$stmt->execute();

		// $stmt->store_result();

		$result = mysqli_stmt_get_result($stmt);

		if (($result->num_rows == 0) )
   		{
	      $stmt->close();
	      header("location:index.php?err=1");
	      exit;
    	}

    	$stmt->close();

    	$_SESSION['myusername']=$_POST['myusername'];
	    header("location:index.php"); 
	    exit;

	    }
else  // username and/or password were not sent
  {
    header("location:index.php?err=2");
    exit;
  }

?>

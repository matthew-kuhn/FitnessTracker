<?php
	
	session_start();

	$_SESSION['myusername']=$_POST['myusername'];

	header("location:index.php");

?>

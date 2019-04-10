<html lang = "en-US">
	<head>
		<title>Fitness Tracker</title>
		<meta charset="utf-8">
		<meta name="author" content="Matthew Kuhn, Andrea-Cristiano Seazzu, Noah White">
		<meta name="description" content="The homepage for the fitness tracking website">
		<link rel="stylesheet" type="text/css" href="fit.css">
		<link rel="shortcut icon" type="image/png" href="favicon.ico">
		<script type="text/javascript">
			function checkLoginForm()
		      {
		        var userName = document.forms["login_form"]["myusername"].value;

		        if (userName == "") 
		        {
		          document.getElementById('formFeedback').innerHTML = "ERROR: User Name must be specified.";
		          return false;
		        }
		        else
		          return true;
		      }
		</script>
	</head>
	<body>
		<header>
			<h1>FitTrak</h1>
			<nav>
				<a href="workouts.php">Workout Info</a>
				<a href="food.php">Food Info</a>
				<a href="tracking.php">Tracking</a>
				<a style="float: right" href="logout.php">Sign Out</a>
			</nav>
		</header>
		<div id="info">
			<h2>About this site</h2>
				<p>This site contains different tools for recording and tracking fitness information <br>
				Users may choose from pre-built diet and fitness plans, or they can craft their own</p>
		</div>
		<div id="login">
			<?php

			  // retrieve session information
			  session_start();

			  // if no username set, then redirect to login
			  if(isset($_SESSION['myusername'])){
			?>
			<p>we are logged in</p>
			<?php } else {?>
			<!-- login form or create user form-->
			<form name="loginForm" method="post" id="login_form" action="checklogin.php" onsubmit="return checkLoginForm()">
				<table border="0" cellpadding="3" cellspacing="1" style="margin-left: 400px; padding-top: 5px">
                  <tr>
                  	<td style="width:80px">Username:</td>
                  	<td ><input type="text" name="myusername" id="myusername" required ></td>
                  </tr>
                  <tr>
                  	<td style="width:80px">Password:</td>
                  	<td><input type="password" name="mypassword" id="mypassword" required></td>
                  </tr>
                  <tr >
                  	<td style="text-align: right"><input type="submit" name="submit" value="Login"></td>
            </form>
                  	<td style="text-align: left;">
                  		<form name="newUser" method="get" id="newUser" action="create_user.php" onsubmit=" ? ">
                  			<input type="submit" name="newUser" value="Create New User" style="margin-top:15px">
                  		</form>
                  	</td>
                  </tr>					
				</table>
			<?php } ?>
		</div>
	</body>

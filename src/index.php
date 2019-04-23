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

		      function checkNewUser(){
		      	var username = document.forms["new_user"]["newusername"].value;
		      	var password = document.forms["new_user"]["newpassword"].value;
		      	var password_conf = document.forms["new_user"]["newpassword_conf"].value;
		      	var fname = document.forms["new_user"]["firstname"].value;
		      	var lname = document.forms["new_user"]["lastname"].value;
		      	var pass_regex = new RegExp("^[a-zA-Z0-9!@#\$%\^\&*\)\(+=._-]{6,32}$");
		      	var name_regex = new RegExp("^[a-zA-Z]{1,20}$");
		      	var username_regex = new RegExp("^[a-zA-Z0-9!@#\$%\^\&*\)\(+=._-]{6,50}$");

		      	// alert(username);
		      	if(!username_regex.test(username)){
		      		document.getElementById('formFeedback').innerHTML = "Error: Username does not fit requirements";
		      		document.getElementById('newusername').style.cssText = "border: 3px solid red";
		      		return false;
		      	}
		      	else if(password != password_conf){
		      		document.getElementById('formFeedback').innerHTML = "Error: Passwords do not match";
		      		document.getElementById('newpassword').style.cssText = "border: 3px solid red";
		      		document.getElementById('newpassword_conf').style.cssText = "border: 3px solid red";
		      		return false;
		      	}
		      	else if(!pass_regex.test(password)){
		      		document.getElementById('formFeedback').innerHTML = "Error: Password does not fit requirements";
		      		document.getElementById('newpassword').style.cssText = "border: 3px solid red";
		      		document.getElementById('newpassword_conf').style.cssText = "border: 3px solid red";
		      		return false;
		      	}
		      	else if(!name_regex.test(fname)){
		      		document.getElementById('formFeedback').innerHTML = "Error: Name longer than 20 chars, or does not contain only letters";
		      		document.getElementById('firstname').style.cssText = "border: 3px solid red";
		      		return false;
		      	}
		      	else if(!name_regex.test(lname)){
		      		document.getElementById('formFeedback').innerHTML = "Error: Name longer than 20 chars, or does not contain only letters";
		      		document.getElementById('lastname').style.cssText = "border: 3px solid red";
		      		return false;
		      	}
		      	else{
		      		return true;
		      	}
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
			<h2>Welcome, <?php echo $_SESSION['myusername'] ?></h2>
				<p>add a summary of current week, from database</p>
				<form style="display: block; margin: auto">
					<select style="display: block; margin: 0 auto; margin-bottom: 5px">
						<option>Weekly Summary</option>
						<option>Monthly Summary</option>
						<option>Yearly Summary</option>
					</select>
					<input type="submit" name="submit" style="display: block; margin: 0 auto">
				</form>
			<?php } else {?>
			<!-- login form or create user form-->
			<form name="loginForm" method="post" id="login_form" action="checklogin.php" onsubmit="return checkLoginForm()">
				<table border="0" cellpadding="3" cellspacing="1" style="margin-left: 400px; padding-top: 5px">
					<h2 id="formFeedback"><?php 
                          if (isset($_GET['err'])) {echo 'ERROR: Username - password not valid.'; }
                        ?><h2>
                  <tr>
                  	<td style="width:80px; text-align: right">Username:</td>
                  	<td ><input type="text" name="myusername" id="myusername" required ></td>
                  </tr>
                  <tr>
                  	<td style="width:80px; text-align: right">Password:</td>
                  	<td><input type="password" name="mypassword" id="mypassword" required></td>
                  </tr>
                  <tr >
                  	<td style="text-align: center;" colspan="2"><input type="submit" name="submit" value="Login"></td>
            </form>
                  </tr>	
              <form method="post" id="new_user" action="create_user.php" onsubmit="return checkNewUser();">
                  <tr>
                  	<td style="width: 110px; text-align: right">New Username:</td>
                  	<td><input type="text" name="newusername" id="newusername" required></td>
                  	<td>Min 6, Max 50 Chars</td>                 	
                  </tr>	
                  <tr>
                  	<td style="width: 110px; text-align: right">New Password:</td>
                  	<td><input type="password" name="newpassword" id="newpassword" required></td>
                  	<td> (More than 6 characters, Less than 32)</td>
                  </tr>
                  <tr>
                  	<td style="width:130px; text-align: right">Confirm Password:</td>
                  	<td><input type="password" name="newpassword_conf" id="newpassword_conf" required></td>
                  </tr>
                  <tr>
                  	<td style="text-align: right">First Name:</td>
                  	<td><input type="text" name="firstname" id="firstname" required></td>
                  </tr>
                  <tr>
                  	<td style="text-align: right">Last Name:</td>
                  	<td><input type="text" name="lastname" id="lastname" required></td>
                  </tr>
                  <tr>
                  	<td colspan="2" style="text-align: center"><input type="submit" name="submit" value="Create New User"></td>
                  </tr>

              </form>			
				</table>
			<?php } ?>
		</div>
	</body>

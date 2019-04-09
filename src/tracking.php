<html lang="en-US">
	<head>
		<title>Personal Tracker</title>
		<meta charset="utf-8">
		<meta name="author" content="Matthew Kuhn, Andrea-Cristiano Seazzu, Noah White">
		<meta name="description" content="The page for tracking workout and food data">
		<link rel="stylesheet" type="text/css" href="fit.css">
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
				<a href="index.php">Home</a>
				<a href="workouts.php">Workout Info</a>
				<a href="food.php">Food Info</a>
				<a href="tracking.php">Tracking</a>
				<a style="float: right" href="logout.php">Sign Out</a>
			</nav>
		</header>
	</body>
</html>
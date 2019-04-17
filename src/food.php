<html lang="en-US">
	<head>
		<title>Food Info</title>
		<meta charset="utf-8">
		<meta name="author" content="Matthew Kuhn, Andrea-Cristiano Seazzu, Noah White">
		<meta name="description" content="The page for viewing and creating foods">
		<meta name="documentation" content="https://stackoverflow.com/questions/8734503/how-can-i-center-an-unordered-list-with-dynamic-content-within-div for centering lists">
		<link rel="stylesheet" type="text/css" href="fit.css">
		<link rel="shortcut icon" type="image/png" href="favicon.ico">
		<script type="text/javascript">
			
		</script>
	</head>
	<body>
		<header>
			<h1>FitTrak</h1>
			<nav>
				<a href="workouts.php">Workout Info</a>
				<a href="food.php">Food Info</a>
				<a href="tracking.php">Tracking</a>
				<a href="index.php">Home</a>
				<a style="float: right" href="logout.php">Sign Out</a>
			</nav>
		</header>
		<div id="grocery_list" style="float:left; width: 48%; margin-top: 15px; margin-bottom: 15px">
			<h2 style="margin-top: 0px">Your Grocery List</h2>
				<ul style="float:left; left: 50%; position: relative;">
					<li style="float:left; right: 50%; position: relative; clear: both"><?php echo "chicken breast 12 oz"?></li>
					<li style="float:left; right: 50%; position: relative; clear: both"><?php echo "turkey breast 12 oz"?></li>
					<li style="float:left; right: 50%; position: relative; clear: both"><?php echo "chicken wing 12 oz"?></li>
					<li style="float:left; right: 50%; position: relative; clear: both"><?php echo "turkey wing 12 oz"?></li>
				</ul>
		</div>
		<div id="weekly_meals" style="float:right; width: 48%; margin-top: 15px; margin-bottom: 15px">
			<h2 style="margin-top: 0px">Your Weekly Menu</h2>
				<ul style="float:left; left: 50%; position: relative;">
					<li style="float:left; right: 50%; position: relative; clear: both">Day 1: <?php echo "baked chicken breast every meal"?></li>
					<li style="float:left; right: 50%; position: relative; clear: both">Day 2: <?php echo "baked turkey breast every meal"?></li>
					<li style="float:left; right: 50%; position: relative; clear: both">Day 3: <?php echo "baked chicken wing every meal"?></li>
					<li style="float:left; right: 50%; position: relative; clear: both">Day 4: <?php echo "baked turkey wing every meal"?></li>
					<li style="float:left; right: 50%; position: relative; clear: both">Day 5: <?php echo "baked chicken breast every meal"?></li>
					<li style="float:left; right: 50%; position: relative; clear: both">Day 6: <?php echo "baked chicken wing every meal"?></li>
					<li style="float:left; right: 50%; position: relative; clear: both">Day 7: <?php echo "baked turkey breast every meal"?></li>
				</ul>
		</div>
		<div id="meal_plans" style="clear: both; width: 50%;">
			<h2 style="margin-top: 0px">Meal Plans</h2>
				<h3>Current Meal Plan</h3>
		</div>
	</body>
</html>
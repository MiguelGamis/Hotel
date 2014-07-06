<!doctype html>

<?php
require_once('session.php');
?>

<html>

	<head>

		<title>Sales</title>
		
		<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
		 	
		<link rel="stylesheet" type="text/css" href="includes/style2.css" />

	</head>
	
	<body>
	
	<div id="header">
		<h1>Sterling Cooper Draper Price</h1>
		<h2>Hotels and Resorts</h2>
		<?php
		echo "<h3>Welcome, " . $_SESSION['em_fname'] . " ". $_SESSION['em_lname'] . "</h3>";
		?>
	</div>
	
	<div id="navigation">
		<ul>
			<li><a href="hotel_status.php">Home Page</a></li>
			<li><a href="view_users.php">View Users</a></li>
			<li><a href="calendar_start.php">Reservation Metric</a></li>
			<li><a href="statistics.php">Sales</a></li>
			<li><a href="logout.php">Logout</a></li>
		</ul>
	</div>
	
	
	<div id="div1"></div>
	<script type="text/javascript" src="jsgraph.js"></script>
	
	</body>
</html>

	
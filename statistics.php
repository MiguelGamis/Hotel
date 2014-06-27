<!doctype html>

<?php
require_once('session.php');

?>

<html>

	<head>

		<title>Sales</title>
		
		<script src="Chart.js-master/Chart.js"></script>
		
		<link rel="stylesheet" href="includes/style2.css" media="screen" />
		
		<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
		
		<link rel="stylesheet" type="text/css" href="jsgraph.css" />

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
	
	<div id="content">
	
	
	
	<canvas id="canvas1" height="450" width="800"></canvas>
	
	<script src="Chart.js-master/Chart.js"></script>
	
	<script>

		var barChartData = {
			labels : ["January","February","March","April","May","June","July"],
			datasets : [
				{
					fillColor : "rgba(220,220,220,0.5)",
					strokeColor : "rgba(220,220,220,1)",
					data : [65,59,90,81,56,55,40]
				},
				{
					fillColor : "rgba(151,187,205,0.5)",
					strokeColor : "rgba(151,187,205,1)",
					data : [28,48,40,19,96,27,100]
				}
			]
			
		}

	var myLine = new Chart(document.getElementById("canvas").getContext("2d")).Bar(barChartData);
	
	</script>
	
	</div>
	
	</body>
</html>

	
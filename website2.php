<?php

require_once('session.php');

require_once('connection.php');


$credentials = @mysqli_query($dbc, "select * from hotelmanageraccount where manager_id = " . $_SESSION["managerid"]);

if(!$credentials){
echo $_SESSION["managerid"];
}

while ($row = mysqli_fetch_Array($credentials, MYSQLI_BOTH)){
		$_SESSION["em_lname"] = $row["lname"];	
		$_SESSION["em_fname"] = $row["fname"];
		$_SESSION["branch"] = $row["branch_no"];
}

?>

<!- DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xthml-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

	<title><?php echo $page_title; ?></title>
	
	<link rel="stylesheet" href="includes/style2.css" media="screen" />
	
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>

</head>

<body>
	
	<div id="header">
		<h1>Sterling Cooper Draper Price</h1>
		<h2>Hotels and Resorts</h2>
		<?php
		echo "<h3>Welcome, " . $_SESSION['em_fname'] . " ". $_SESSION['em_lname'] . " (Manager View)</h3>";
		?>
	</div>
	
	<div id="navigation">
		<ul>
			<li><a href="hotel_status.php">Home Page</a></li>
			<li><a href="view_users.php">View Users</a></li>
			<li><a href="reservationmetric.php">Reservation Metric</a></li>
			<li><a href="elitemembers.php">Elite Members</a></li>
			<li><a href="logout.php">Logout</a></li>
		</ul>
	</div>
	
	<div id="content"><!-- Start of the page-specfic content. -->
	
	<!-- Script 3.2 - header.html -->
	
	
</body>
	
</html>


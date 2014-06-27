<?php

$page_title = "Client View";

require_once('session.php');

if(!$_SESSION["username"]){
 header( 'Location: lobby.php' );
}

require_once('connection.php');

$q = "select * from clientaccount where client_id = " . $_SESSION["username"];
$credentials = @mysqli_query($dbc, $q);
while ($row = MYSQLI_Fetch_Array($credentials, MYSQLI_ASSOC)){
		$_SESSION["cl_lname"] = $row["cl_lname"];	
		$_SESSION["cl_fname"] = $row["cl_fname"];
		$_SESSION["credit_card"] = $row["credit_card"];
		$_SESSION["password"] = $row["password"];
		if(array_key_exists("phone_number",$row)){
			$_SESSION["phone_number"] = $row["phone_number"];
		}
		if(array_key_exists("email",$row)){
			$_SESSION["email"] = $row["email"];
		}
}

?>

<!- DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xthml-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

	<title><?php echo 'website'; ?></title>
	
	<link rel="stylesheet" href="includes/style.css" media="screen" />
	
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>

</head>

<body>
	
	<div id="header">
		<h1>Sterling Cooper Draper Pryce</h1>
		<h2>Hotels and Resorts</h2>
		<?php
		echo "<h3>Welcome, " . $_SESSION['cl_fname'] . " ". $_SESSION['cl_lname'] . "</h3>";
		?>
	</div>
	
	<div id="navigation">
		<ul>
			<li><a href="my_reservations.php">Home Page</a></li>
			<li><a href="reservation.php">Make A Reservation</a></li>
			<li><a href="unpaid.php">Pay a bill</a></li>
			<li><a href="changeinfo.php">Edit My Profile</a></li>
			<li><a href="logout.php">Logout</a></li>
		</ul>
	</div>
	
	<div id="content"><!-- Start of the page-specfic content. -->
	
	<!-- Script 3.2 - header.html -->
	
	
</body>
	
</html>


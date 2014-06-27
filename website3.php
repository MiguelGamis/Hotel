<?php

$page_title = 'Manager View';

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

include('includes/header2.html');
?>

<?php

$page_title = 'Update Information'; //put the title of the page here

include('includes/header.html'); //has the styles and session/db conn stuff
?>

<h1>Update Information</h1>

<form action="changeinfo.php" method="post">

<fieldset><legend>Enter new information:</legend>

<p>New Credit Card Number: <input type="text" name="newcnum" id="cnum" maxlength="16" /> </p>

<p>New password: <input type="text" name="newpw" maxlength="20" /> </p>

<p>New Phone number: <input type="text" name="newphone" maxlength="12" /> </p>

<p>New Address: <input type="text" name="newaddr" maxlength="60" /> </p>

<div> <input type="submit" name="submit" value="Submit Updated Information" /> </div>

</fieldset>

</form>

<?php
if(isset($_POST['submit'])){
if(is_numeric($_POST['newcnum']) && (strlen($_POST['newcnum']) == 16)){
	if($dbc){
		@mysqli_query($dbc, "UPDATE ClientAccount SET credit_card = " . $_POST['newcnum'] ." WHERE client_id = " . $_SESSION['username']);
		echo "Your Credit Card number has been updated";
		mysqli_close($dbc);

	} else {
		echo "cannot connect";
		$e = OCI_Error(); // For OCILogon errors pass no handle
		echo htmlentities($e['message']);
	}
} else {
	echo "Invalid Credit Card number";
}
if(strlen($_POST['newpw']) >= 5){
	if($dbc){
		@mysqli_query($dbc, "UPDATE ClientAccount " . "SET password = '" . ($_POST['newpw']) . "' Where client_id = " . ($_SESSION["username"]));
		echo "\r Your password has been updated";
		mysqli_close($dbc);
	} else {
		echo "cannot connect";
		$e = MYSQLI_Error(); // For OCILogon errors pass no handle
		echo htmlentities($e['message']);
	}
} else {
	echo "\r Password not secure enough";
}
if(strlen($_POST['newphone']) == 12){
	if($dbc){
		@mysqli_query($dbc, "UPDATE ClientAccount " . "SET phone_number = '" . ($_POST['newphone']) . "' Where client_id = " . ($_SESSION["username"]));
		echo "\r Your phone number has been updated";
		mysqli_close($dbc);
		
	} else {
		echo "cannot connect";
		$e = MYSQLI_Error(); // For OCILogon errors pass no handle
		echo htmlentities($e['message']);
	}
} else {
	echo "\r Invalid phone number";
}
if(strlen($_POST['newaddr']) >= 7){
	if($dbc){
		@mysqli_query($dbc, "UPDATE ClientAccount " . "SET address = '" . ($_POST['newaddr']) . "' Where client_id = " . ($_SESSION["username"]));
		echo "\r Your address has been updated";
		mysqli_close($dbc);
	} else {
		echo "cannot connect";
		$e = MYSQLI_Error(); // For OCILogon errors pass no handle
		echo htmlentities($e['message']);
	}
} else {
	echo "\r Invalid address";
}
}

include ('includes/footer.html');

?>
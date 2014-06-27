<?php # Script 9.3 - edit_user.php

// This page is for editing a user record.
// This page is accessed through view_users.php.

$page_title = 'Edit a User';

require_once('session.php');

include ('includes/header2.html');

echo '<h1>Edit a User</h1>';

// Check for a valid user ID, through GET or POST:
if ( (isset($_GET['id'])) && (is_numeric ($_GET['id'])) ) { // From view_users.php
	$id = $_GET['id'];
} 
else if ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form submission.
	$id = $_POST['id'];
} 
else 
{ // No valid ID, kill the script.
echo '<p class="error">This page has been accessed in error.</p>';
include ('includes/footer.html');
exit();
}

require_once ('connection.php');

// Check if the form has been submitted:
if (isset($_POST['submitted'])) {

$errors = array();

// Check for a first name:
if (empty($_POST['cl_fname'])) {
$errors[] = 'You forgot to enter a first name.';

} else {
$fn = $_POST['cl_fname'];
}

// Check for a last name:
if (empty($_POST['cl_lname'])) {
$errors[] = 'You forgot to enter a last name.';
} else {
$ln = $_POST['cl_lname'];
}

// Check for a password:
if (empty($_POST['password'])) {
$errors[] = 'You forgot to enter a password.';
} else {
$pw = $_POST['password'];
}

if(empty($_POST['credit_card'])){
$errors[] = 'You forgot to enter a credit card number.';
} elseif(!is_numeric($_POST['credit_card'])) {
$errors[] = 'Credit card number entered was not numeric';
} elseif(strlen($_POST['credit_card'])!=16) {
$errors[] = 'Credit card number must be 16 digits long';
}
else {
$cc = $_POST['credit_card'];
}

if (empty($errors)) { 

	$checkuniqueCCquery = "SELECT * from clientaccount where client_id <> " . $id . " and credit_card = " . $_POST['credit_card'];
	$checkuniqueCCresult = @mysqli_query($dbc, $checkuniqueCCquery);
	$i = 0;
	while ($row = mysqli_fetch_array($checkuniqueCCresult, MYSQLI_BOTH)){
			$i++;
	}
	if($i == 0)
	{
		// Make the query:
		$q = "UPDATE ClientAccount SET cl_fname='" . $fn . "', 
		cl_lname='" . $ln . "', password='" . $pw . "', credit_card=" . $cc . ",
		phone_number='" . $_POST['phone_number'] . "', email='" . $_POST['email'] .
		"' WHERE client_id =" . $id;
		$r = @mysqli_query($dbc ,$q);
		
		mysqli_commit($dbc);
		
		echo '<p><b>The user has been edited.</b></p><p></p>';
	}
	else 
	{
		echo '<p class="error">Credit Card number is already in use.</p>
			 <p>Please try again.</p><br />';
	}
} else { // Report the errors.

echo '<p class="error">The following error(s) occurred:<br />';
foreach ($errors as $msg) { // Print each error.
echo " - $msg<br />\n";
}
echo '</p><p>Please try again.</p><br />';


} // End of if (empty($errors)) IF.

} // End of submit conditional.

// Always show the form...

// Retrieve the user's information:
$q = "SELECT * FROM clientaccount WHERE client_id = " . $id;

$r = @mysqli_query($dbc ,$q);

$j=0;

while ($row = mysqli_fetch_array($r, MYSQLI_BOTH)) {
	$cl_fname = $row['cl_fname'];
	$cl_lname = $row['cl_lname'];	
	$password = $row['password'];
	$credit_card = $row['credit_card'];
	$phone_number = $row['phone_number'];
	$email = $row['email'];
	$j++;
}

if ($j == 1) { // Create FORM if valid client ID if corresponds to only one tuple, show the form.

echo '<h3>Client ' . $id . ' Information:</h3>

<form action="edit_user.php" method="post">

<p>First Name: <input type="text" name="cl_fname" size="20" maxlength="20" value="' . $cl_fname . '" /></p>

<p>Last Name: <input type="text" name="cl_lname" size="20" maxlength="30" value="' . $cl_lname . '" /></p>

<p>Password: <input type="text" name="password" size="20" maxlength="20" value="' . $password . '" /> </p>

<p>Credit Card Number: <input type="text" name="credit_card" size="16" maxlength="16" value="' . $credit_card . '" /> </p>

<p>Phone Number: <input type="text" name="phone_number" size="12" maxlength="12" value="' . $phone_number . '" /> </p>

<p>E-mail Address:</p>

<p><textarea name="email" rows="2" cols="30" maxlength="60">' . $email . '</textarea></p>

<p><input type="submit" name="submit" value="Update" /></p>

<input type="hidden" name="submitted" value="TRUE" />

<input type="hidden" name="id" value="' . $id . '" />

</form>';

} else { // Not a valid client ID. Spew error message.

	echo '<p class="error">This page has been accessed in error.</p>';
	
}

mysqli_close($dbc);

	echo '<p><a href="view_users.php">Back to Registered Users</a></p>';

include ('includes/footer.html');
?>
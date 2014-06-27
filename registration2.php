<?php 

$page_title = 'Register';

include ('includes/header.html');

include ('connection.php');

?>
<h1>Register</h1>
<table style="width:600px">
<form action="registration.php" method="post">
<tr>
	<td> <p>Email Address:</p> </td>
	<td> <p><input type="text" name="username" size="20" maxlength="50" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>" /></p> </td>
</tr>
<tr>
	<td> <p>First Name:</p> </td>
	<td> <p><input type="text" name="first_name" size="15" maxlength="25" value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name']; ?>" /></p></td>
</tr>
<tr>
	<td> <p>Last Name:</p> </td>
	<td> <p><input type="text" name="last_name" size="15" maxlength="25" value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name']; ?>" /></p></td>
</tr>
<tr>	
	<td> <p>Password:</p> </td>
	<td> <p><input type="password" name="pass1" size="10" maxlength="25" /></p> </td>
</tr>
<tr>	
	<td> <p>Confirm Password:</p> </td>
	<td> <p><input type="password" name="pass2" size="10" maxlength="25" /></p> </td>
</tr>
<tr>	
	<td> <p>Credit Card Number:</p> </td>
	<td> <p><input type="text" name="card" size="8" maxlength="16" /></p> </td>
</tr>
<tr>
	<td> <p>Phone Number:</p> </td>
	<td> <p><input type="text" name="" size="10" maxlength="20" /></p> </td>
</tr>
</table>

<p><input type="submit" name="submit" value="Register" /></p> <input type="hidden" name="submitted" value="TRUE" />
</form>

<?php

// Check if the form has been submitted:
if (isset($_POST['submitted'])) {

	$errors = array(); // Initialize an error array.

	// Check for a first name:
	if (empty($_POST['username'])) {
	$errors[] = 'You forgot to enter your email.';
	} 
	else {
	$e = trim($_POST['username']);
	}
	
	// Check for a first name:
	if (empty($_POST['first_name'])) {
	$errors[] = 'You forgot to enter your first name.';
	} 
	else {
	$fn = trim($_POST['first_name']);
	}

	// Check for a last name:
	if (empty($_POST['last_name'])) {
	$errors[] = 'You forgot to enter your last name.';
	} 
	else {
	$ln = trim($_POST['last_name']);
	}

	// Check for a password and match against the confirmed password:
	if (!empty($_POST['pass1'])) {
		if ($_POST['pass1'] != $_POST['pass2']) {
		$errors[] = 'Your password did not match the confirmed password.';
		} 
		else {
		$p = trim($_POST['pass1']);
		}
	} else {
	$errors[] = 'You forgot to enter your password.';
	}

	// Check for a credit card number
	if(empty($_POST['card'])){
		$errors[] = 'You forgot to enter your credit card number.';
	} elseif(!is_numeric($_POST['card'])) {
		$errors[] = 'Credit card number entered was not numeric';
	} elseif(strlen($_POST['card'])!=16) {
		$errors[] = 'Credit card number must be 16 numbers long';
	} else {
		require_once ('connection.php');	

		$q = "SELECT * FROM ClientAccount WHERE credit_card = " . $_POST['card'];
		$r = @mysqli_query ($dbc, $q); // Run the query.
		if ($r) { // If it ran OK.

		$num_rows = 0;
		
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)){
		$num_rows++;
		}
		
		if($num_rows > 0){
			$errors = 'Credit card number entered is already registered';
		} else {
			$card = trim($_POST['card']);
		}

		} else { // If it did not run OK.
		// Public message:
		echo '<h1>System Error</h1> <p class="error">You could not be registered due to a system error.</p>';

		// Debugging message:
		echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';

		}
	}
	
	// Check for phone number:
	if (empty($_POST['phone_number'])) {
	$pn = '';
	} 
	else {
	$pn = trim($_POST['phone_number']);
	}
	
if (empty($errors)) { // If everything’s OK.

// Register the user in the database...

// Make the query:
$q = "INSERT INTO ClientAccount (username, cl_fname, cl_lname, credit_card, password, registration_date, email, phone_number) VALUES ('$e', '$fn','$ln', $card, '$p', NOW(), '$e', '$pn')";
$r = @mysqli_query ($dbc, $q); // Run the query.
if ($r) { // If it ran OK.

// Print a message:
echo '<h1>Thank you!</h1>
<p>You are now registered</p><p><br /></p>';

} else { // If it did not run OK.
// Public message:
echo '<h1>System Error</h1> <p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';

// Debugging message:
echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';

} // End of if ($r) IF.

mysqli_close($dbc); // Close the database connection.

} else { // Report the errors.

echo '<h1>Error!</h1>
<p class="error">The following error(s) occurred:<br />';

// Print each error.

foreach ($errors as $message) {

	echo " - $message<br />\n";
	
}
echo '</p><p>Please fill out the form again.</p><p><br /></p>';

} // End of if (empty($errors)) IF.

} // End of the main Submit conditional.
?>

<a href='lobby.php'>Back to main lobby</a>

<?php

include ('includes/footer.html');
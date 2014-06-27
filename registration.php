<?php 

$page_title = 'Register';

include ('includes/header.html');

require_once('connection.php');

?>
<h1>Register</h1>
<form action="registration.php" method="post">
<p>Email Address: <input type="text" name="username" size="20" maxlength="50" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>" /> </p>
<p>First Name: <input type="text" name="first_name" size="15" maxlength="25" value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name']; ?>" /></p>
<p>Last Name: <input type="text" name="last_name" size="15" maxlength="25" value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name']; ?>" /></p>
<p>Password: <input type="password" name="pass1" size="10" maxlength="25" /></p>
<p>Confirm Password: <input type="password" name="pass2" size="10" maxlength="25" /></p>
<p>Credit Card Number: <input type="text" name="card" size="8" maxlength="16" /></p>
<p>Phone Number: <input type="text" name="phone_number" size="10" maxlength="20" /></p>

<p><input type="submit" name="submit" value="Register" /></p> <input type="hidden" name="submitted" value="TRUE" />
</form>

<?php

// Check if the form has been submitted:
if (isset($_POST['submitted'])) {

	$errors = array(); // Initialize an error array.

//-----------Check for a e-mail:----------------------------------------------------------------
	if (empty($_POST['username'])) {
		$errors[] = 'You forgot to enter your email.';
	} 
	else {
		$q = "SELECT COUNT(*) as count FROM ClientAccount WHERE email = '" . $_POST['username'] . "'";
		$r = @mysqli_query ($dbc, $q); // Run the query.
		if ($r) { // If it ran OK.
		
			while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)){
				$num_rows = (int) $row['count'];
			}
			echo 'THE COUNT IS ' . $num_rows;
			if($num_rows == 0){
				$e = trim($_POST['username']);
			} else {
				$errors[] = 'That e-mail address has already been taken.';
			}
			
		} else { // If it did not run OK.
		// Public message:
		echo '<h1>System Error</h1> <p class="error">You could not be registered due to a system error.</p>';

		// Debugging message:
		echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';

		}
	}
	
//-----------Check for a first name:----------------------------------------------------------------
	if (empty($_POST['first_name'])) {
	$errors[] = 'You forgot to enter your first name.';
	} 
	else {
	$fn = trim($_POST['first_name']);
	}

//-----------Check for a last name:-----------------------------------------------------------------
	if (empty($_POST['last_name'])) {
	$errors[] = 'You forgot to enter your last name.';
	} 
	else {
	$ln = trim($_POST['last_name']);
	}

//----------Check for a password and match against the confirmed password:--------------------------
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

//----------------------Check for a credit card number-------------------------------------------------
	if(empty($_POST['card'])){
		$errors[] = 'You forgot to enter your credit card number.';
	} elseif(!is_numeric($_POST['card'])) {
		$errors[] = 'Credit card number entered was not numeric';
	} elseif(strlen($_POST['card'])!= 16) {
		$errors[] = 'Credit card number must be 16 numbers long';
	} else {	
		$q = "SELECT COUNT(*) as count FROM ClientAccount WHERE credit_card = '" . $_POST['card'] . "'";
		$r = @mysqli_query ($dbc, $q); // Run the query.
		if ($r) { // If it ran OK.
		
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)){
			$num_rows = (int) $row['count'];;
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
	
//--------------------------------Check for phone number:----------------------------------------------------------
	if (empty($_POST['phone_number'])) {
	$pn = NULL;
	} 
	else {
	$pn = trim($_POST['phone_number']);
	}

//-------------------------------Check for errors------------------------------------------------------------------
if (empty($errors)) {

	$q = "INSERT INTO ClientAccount (username, cl_fname, cl_lname, credit_card, password, registration_date, email, phone_number) 
		  VALUES ('$e', '$fn','$ln', $card, '$p', NOW(), '$e', '$pn')";
	$r = @mysqli_query ($dbc, $q); // Run the query.
	if ($r) { // If it ran OK.
		
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

foreach($errors as $msg) {

	echo " - $msg<br />\n";
	
}
echo '</p><p>Please fill out the form again.</p><p><br /></p>';

} // End of if (empty($errors)) IF.

} // End of the main Submit conditional.
?>

<a href='lobby.php'>Back to main lobby</a>

<?php

include ('includes/footer.html');

?>
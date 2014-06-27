<?php

$page_title = "Delete a User";

include('includes/header2.html');

echo '<h1>Delete a User</h1>';

// Check for a valid user ID, through GET or POST:
if ( (isset($_GET['id'])) && is_numeric($_GET['id']) ) { // From view_users.php
$id = $_GET['id'];
} else if ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form submission.
$id = $_POST['id'];
} else { // No valid ID, kill the script.
echo '<p class="error">This page has been accessed in error.</p>';
include ('includes/footer.html');
exit();
}

// Check if the form has been submitted:
if (isset($_POST['submitted'])) {

if ($_POST['sure'] == 'Yes') { // Delete the record.
$q = "DELETE FROM clientaccount WHERE client_id =" . $id;
$r = @mysqli_query($dbc, $q);
mysqli_commit($dbc);
	echo '<p>The user has been deleted.</p>';
} 
else 
{
echo '<p>The user has NOT been deleted.</p>';
}

} else { // Show the form.

// Retrieve the user's information:
$q = "SELECT * FROM clientaccount WHERE client_id=" . $id;
$r = @mysqli_query($dbc, $q);

$i = 0;

while ($row = mysqli_fetch_array($r, MYSQLI_BOTH)) {
	$name = $row["cl_lname"] . ', ' . $row["cl_fname"];
	$i++;
}

if ($i == 1) { // Valid user ID, show the form.

// Get the user's information:
$row = mysqli_fetch_array($r, MYSQLI_BOTH);

// Create the form:
echo '<form action="delete_user.php" method="post">

<h3>Client ID: ' . $id . ' Name: ' . $name . '</h3>

<p>Are you sure you want to delete this user?<br />

<input type="radio" name="sure" value="Yes" /> Yes
<input type="radio" name="sure" value="No" checked="checked" /> No</p>

<p><input type="submit" name="submit" value="Delete" /></p>

<input type="hidden" name="submitted" value="TRUE" />

<input type="hidden" name="id" value="' . $id . '" />

</form>';

} else { // Not a valid user ID.
echo '<p class="error">This page has been accessed in error.</p>';
}

} // End of the main submission conditional.

mysqli_close($dbc);

echo '<p><a href="view_users.php">Back to Registered Users</a></p>';

include ('includes/footer.html');
?>




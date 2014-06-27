<?php

$page_title = "Cancel a Reservation";

include('includes/header.html');

echo '<h1>Cancel a Reservation</h1>';

if ( (isset($_GET['resid'])) && is_numeric($_GET['resid']) ) { // From view_users.php
$resid = $_GET['resid'];
} else if ( (isset($_POST['resid'])) && (is_numeric($_POST['resid'])) ) { // Form submission.
$resid = $_POST['resid'];
} else { // No valid ID, kill the script.
echo '<p class="error">This page has been accessed in error.</p>';
include ('includes/footer.html');
exit();
}

require_once('oracle.php');

// Check if the form has been submitted:
if (isset($_POST['submitted'])) {

if ($_POST['sure'] == 'Yes') { // Delete the record.
$q = "DELETE FROM reservation WHERE reservation_id = {$resid}";
$r = executePlainSQL($q);
OCICommit($db_conn);
	echo '<p>The reservation has been deleted.</p>';
} 
else 
{
echo '<p>The reservation has NOT been deleted.</p>';
}

} else { // Show the form.

// Retrieve the user's information:
$q = "SELECT * FROM reservation r, hotelbranch_manages hb WHERE hb.branch_no = r.branch_no AND r.reservation_id={$resid}";
$r = executePlainSQL($q);

$i = 0;

while ($row = OCI_Fetch_Array($r, OCI_BOTH)) {
	$room = $row["BRANCH_NAME"] . ', Room No. : ' . $row["ROOM_ID"];
	$i++;
}

if ($i == 1) { // Valid user ID, show the form.

// Get the user's information:
$row = OCI_Fetch_Array($r, OCI_BOTH);

// Create the form:
echo '<form action="delete_reservation.php" method="post">

<h3>Reservation: ' . $room . '</h3>

<p>Are you sure you want to cancel reservation?<br />

<input type="radio" name="sure" value="Yes" /> Yes
<input type="radio" name="sure" value="No" checked="checked" /> No</p>

<p><input type="submit" name="submit" value="Cancel Reservation" /></p>

<input type="hidden" name="submitted" value="TRUE" />

<input type="hidden" name="resid" value="' . $resid . '" />

</form>';

} else { // Not a valid reservation ID.
echo '<p class="error">This page has been accessed in error.</p>';
}

} // End of the main submission conditional.

OCILogoff($db_conn);

echo '<p><a href="my_reservations.php">Back to My Reservations</a></p>';

include ('includes/footer.html');
?>




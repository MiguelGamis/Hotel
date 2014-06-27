<?php

require_once('session.php');

if(!isset($_SESSION["username"])){
 header( 'Location: lobby.php' );
}

$page_title = 'Edit Reservation';

include ('includes/header.html');

echo '<h1>Edit Reservation</h1>';

// Check for a valid registration ID, through GET or POST:
// From my_reservations.php
if ( (isset($_GET['resid'])) && (is_numeric ($_GET['resid'])) ) 
{ 

	$resid = $_GET['resid'];

} 
else if ( (isset($_POST['resid'])) && (is_numeric($_POST['resid'])) ) 
{

	$resid = $_POST['resid'];

} 
else 
{ 
echo '<p class="error">This page has been accessed in error.</p>';

include ('includes/footer.html');

exit();
}

require_once ('connection.php');

$date = getDate();
$today = $date[0];
$todayasstring = date('Y', $today) . '-' . date('m', $today) . '-' . date('d', $today);
$todayfulltext = date('F', $today) . ' ' . date('d', $today) . ', ' . date('Y', $today);

//---------------------- Check if the form has been submitted: -------------------------
if (isset($_POST['submitted'])) {

	$errors = array();

	if (empty($_POST['start_date']) OR $_POST['start_date']=='0000-00-00') 
	{

		$errors[] = 'Please enter a start date.';

	} 
	elseif($_POST['start_date'] < $todayasstring) 
	{

		$errors[] = 'New requested start date has already passed.';

	} 
	elseif((!empty($_POST['lowerbound'])) && ($_POST['start_date'] <= $_POST['lowerbound']))
	{

		$errors[] = 'The start date is in conflict with another reservation.';
		
	}	
	else 
	{

		$sd = $_POST['start_date'];
		
	}

	if (empty($_POST['end_date']) OR $_POST['end_date']=='0000-00-00') 
	{

		$errors[] = 'You forgot to enter an end date.';

	} 
	elseif((!empty($_POST['upperbound'])) && ($_POST['end_date']>=$_POST['upperbound']))
	{ 

		$errors[] = 'The end date is in conflict with another reservation.';

	} 
	else 
	{

		$ed = $_POST['end_date'];
		
	}


	if (empty($errors)) { // If everything's OK.

		// Make the query:
		$updatequery = "UPDATE Reservation SET start_date= DATE '" . $sd . "', end_date = DATE '" . $ed . "' WHERE reservation_id = " . $resid;
		
		$r = @mysqli_query($dbc, $updatequery);

		echo '<p>Your reservation has been edited. </p>' . ' sd is ' . $_POST['start_date'] . ' afd is ' . $_POST['lowerbound'] . ' ed is ' . $_POST['end_date'] . ' b4d is ' . $_POST['upperbound'];

	} else { // Report the errors.

		echo '<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) {
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p>';

	} // End of if (empty($errors)) IF.

} // End of submit conditional.

// Retrieve the reservation information:
$reservationquery = "SELECT * FROM reservation r, hotelbranch hb WHERE r.branch_no = hb.branch_no and reservation_id = " . $resid;

$result = @mysqli_query($dbc, $reservationquery);

$j = 0;

while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
	$branch_name = $row['branch_name'];
	$branch_addr = $row['branch_addr'];
	$room_id = $row['room_id'];
	$start_date = $row['start_date'];
	$end_date = $row['end_date'];	
	$j++;
}

if ($j == 1) { // Valid reservation ID, show the form.

echo '<form action="edit_reservation.php" method="post">

<p>Hotel Branch: <b>' . $branch_name . '</b></p>  

<p>Hotel Address: <b>' . $branch_addr . '</b></p>

<p>Room Number: <b>' . $room_id . '</b></p>';


$lowerboundquery = "SELECT max(r2.end_date) FROM reservation r1, reservation r2 where r1.reservation_id = " . $resid . " 
					and r1.room_id = r2.room_id and r1.branch_no = r2.branch_no and r1.start_date > r2.end_date";

$lowerboundresult = @mysqli_query($dbc, $lowerboundquery );

$lowerbound = @mysqli_fetch_row($lowerboundresult);

if($lowerbound[0]){
	if($lowerbound[0] > date("Y-m-d")){
		echo '<p style="color:red">This room is free from: ' . date("F j, Y" ,strtotime($lowerbound[0] . ' + 1 day')) . '</p>';
	}
}

$upperboundquery = "SELECT min(r2.start_date) FROM reservation r1, reservation r2 where r1.reservation_id = " . $resid . " 
					and r1.room_id = r2.room_id and r1.branch_no = r2.branch_no and r1.end_date < r2.start_date";

$upperboundresult = @mysqli_query($dbc, $upperboundquery);

$upperbound = @mysqli_fetch_row($upperboundresult);

if($upperbound[0]){
	echo '<p style="color:red">This room is free until: ' . date("F j, Y" ,strtotime($upperbound[0] . ' - 1 day')) . '</p>';
}

echo '<p>Today\'s date: <b>' . date("F j, Y, g:i a") . '</b></p>

<p><b>Duration: </b>';

require_once('calendar/tc_calendar.php');
      
	  $myCalendar = new tc_calendar("start_date", true, false);
	  $myCalendar->setIcon("calendar/images/iconCalendar.gif");
	  $myCalendar->setDate(date('d', strtotime($start_date))
			, date('m', strtotime($start_date))
			, date('Y', strtotime($start_date)));
	  $myCalendar->setPath("calendar/");
	  $myCalendar->setYearInterval(2013, 2020);
	  $myCalendar->setAlignment('left', 'bottom'); 
	  $myCalendar->setDatePair('sd', 'ed', $end_date);
	  $myCalendar->writeScript();	
		  
	  $myCalendar = new tc_calendar("end_date", true, false);
	  $myCalendar->setIcon("calendar/images/iconCalendar.gif");
	  $myCalendar->setDate(date('d', strtotime($end_date))
           , date('m', strtotime($end_date))
           , date('Y', strtotime($end_date)));
	  $myCalendar->setPath("calendar/");
	  $myCalendar->setYearInterval(2013, 2020);
	  $myCalendar->setAlignment('left', 'bottom');
	  
	  $myCalendar->setDatePair('sd', 'ed', $start_date);
	  $myCalendar->writeScript();
	  
echo '&nbsp<a href="http://www.triconsole.com/php/calendar_datepicker.php">Copyright</a></p>

<p><input type="submit" name="submit" value="Update" /></p>

<input type="hidden" name="submitted" value="TRUE" />

<input type="hidden" name="resid" value="' . $resid . '" />

<input type="hidden" name="lowerbound" value="' . $lowerbound[0]. '" />

<input type="hidden" name="upperbound" value="' . $upperbound[0]. '" />

</form>';

} else { // Not a valid reservation ID.
echo '<p class="error">This page has been accessed in error.</p>';
}

mysqli_close($dbc);

echo '<p><a href="my_reservations.php">Back to My Reservations</a></p>';

include ('includes/footer.html');
?>
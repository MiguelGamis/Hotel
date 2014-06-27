<?php

$page_title = "Confirming Reservation";

require_once('session.php');

include_once('includes/header.html');

require_once('connection.php');

$id = $_SESSION['username'];

echo "<h1>Confirming Reservation</h1>";

if(isset($_GET['sd']) && isset($_GET['ed']) && isset($_GET['br']) && isset($_GET['rm']) && isset($_GET['tp'])) 
{
	$startingdate = $_GET['sd'];
	$endingdate = $_GET['ed'];
	$branch_no = $_GET['br'];
	$room_id = $_GET['rm'];
	$room_type = $_GET['tp'];
}
elseif(isset($_POST['branch_no']) && isset($_POST['room_id']) && isset($_POST['startingdate']) && isset($_POST['endingdate']) && isset($_POST['room_type']))
{
	$branch_no = $_POST['branch_no'];
	$room_id = $_POST['room_id'];
	$startingdate = $_POST['startingdate'];
	$endingdate = $_POST['endingdate'];
	$room_type = $_POST['room_type'];
} 
else 
{
	echo '<p class="error">This page has been accessed in error.</p>';
	echo '<a href="website.php">Back to the home page</a>';
	include('includes/footer.html');
	exit();
}

if(isset($_GET['sd']) && isset($_GET['ed']) && isset($_GET['br']) && isset($_GET['rm']) && isset($_GET['tp'])){

$q1 = "SELECT * from HotelBranch br, Room rm where br.branch_no = " . $branch_no . " and rm.room_id = " . $room_id;

$r1 = @mysqli_query($dbc, $q1);

$i = 0;
while ($row = mysqli_fetch_array($r1, MYSQLI_BOTH)) {
	$hotelbranch = $row['branch_name'];
	$hoteladdress = $row['branch_addr'];
	$price_rate = $row['price_rate'];
	$i++;
}

if($i == 1){

	$duration = ceil( (strtotime($endingdate) - strtotime($startingdate) + 1)/(60 * 60 * 24) );
	
	$total = $duration * $price_rate;

	$regularordeluxe = ($room_type == 'dlx' ? 'Deluxe' : 'Regular' );
	
	echo '<fieldset><legend>Please check the parameters of your reservation:</legend>
	
		<form action="confirm_reservation.php" method="post">
	
		<h3>Your reservation is for:</h3>
		
		<p>Hotel Branch: <b>' . $hotelbranch . '</b></p>
		
		<p>Room: <b>' . $regularordeluxe . ' No. ' . $room_id . '</b></p>
		
		<p>Address: <b>' . $hoteladdress . '</b></p>
		
		<p>Duration: from <b>' . $startingdate . '</b> to <b>' . $endingdate . '</b></p>
		
		<p>At a daily rate of: <b>$' . $price_rate . '/day for a tentative total of $' . $total . '</b></p>
		
		<p> <input type="radio" name="sure" value="Yes" /> Yes
		<input type="radio" name="sure" value="No" checked="checked" /> No</p>
		
		<p> <input type="submit" name="submit" value="Submit" />
		
		<input type="hidden" name="submitted" value="TRUE" />
		<input type="hidden" name="branch_no" value="' . $branch_no . '" />
		<input type="hidden" name="room_id" value="' . $room_id . '" />
		<input type="hidden" name="startingdate" value="' . $startingdate . '" />
		<input type="hidden" name="endingdate" value="' . $endingdate . '" />
		<input type="hidden" name="room_type" value="' . $room_type . '" />
		
		</p>
		
		</form>
		
		</fieldset style="width:500">';
		
		echo '<p><a href="reservation.php">Back to Reservation</a></p>';
		
} else{// Not a valid user ID.

echo '<p class="error">This page has been accessed in error.</p>';

}

}

if (isset($_POST['submitted'])) {

if ($_POST['sure'] == 'Yes') {
		
	$q2 = "insert into Reservation (client_id, room_id, branch_no, start_date, end_date) values ($id, $room_id, $branch_no, DATE '$startingdate', DATE '$endingdate')";
	$r2 = @mysqli_query($dbc, $q2);
	
	if($r2){
		echo '<p>Reservation Completed</p>';	
		mysqli_close($dbc);
	} else {
		echo '<p class="error">Your reservation could not be confirmed due to database connection issues. We are sorry for this inconvinience.</p>';
	}
	
	echo '<p><a href="my_reservations.php">Back to My Reservations</a></p>';
}

}

include ('includes/footer.html');

?>
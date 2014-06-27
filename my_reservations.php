<?php 

$page_title = 'My Reservations';

require_once('session.php');

include_once('includes/header.html');

require_once('connection.php');

echo '<h1>My Reservations</h1><p></p>';

$id = $_SESSION["username"];

//Number of records to show per page
$display = 10;

// Determine how many pages there are...

if(isset($_GET['p']) && is_numeric($_GET['p'])) {

$pages = $_GET['p'];

} else { // Need to determine 

// Count the number of records:

$reservationsquery = 'SELECT COUNT(client_id) FROM reservation where client_id = ' . $id;

$result = @mysqli_query($dbc, $reservationsquery); 

while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
	$records = (int) $row[0];
}

//Number of pages

if(0 == $records){
	echo '<p>You currently have no reservations with us</p>';
	echo '<p><form method="get" action="reservation.php">
			  <button type="submit">Make a Reservation</button>
		  </form></p>';
	include ('includes/footer.html');
	exit();
}
if($records > $display){

$pages = ceil ($records/$display);

} else {
	
	$pages = 1;
	
}

} // End of p IF.

// Determine where in the database to start returning results...
if (isset($_GET['s']) && is_numeric($_GET['s'])) {

	$start = $_GET['s'];

} else {

	$start = 0;

}

// Determine the sort...
// Default is by registration date.
$sort = (isset($_GET['sort'])) ? $_GET ['sort'] : 'sd';

//Determine the sorting order:

switch($sort){	

	case 'rm':
	$order_by = 'room_id ASC';
	break;
	
	case 'ed':
	$order_by = 'end_date DESC';
	break;
	
	case 'sd':
	$order_by = 'start_date DESC';
	break;
	
	default:
	$order_by = 'start_date ASC';
	$sort = 'sd';
	break;
	
}

//Make a query

$q = "SELECT start_date, end_date, branch_name, branch_addr, rm.room_id, price_rate, reservation_id FROM clientaccount c, 
 reservation rv, hotelbranch hb, room rm WHERE hb.branch_no = rm.branch_no AND c.client_id = rv.client_id 
 AND rv.room_id = rm.room_id AND c.client_id = {$id} ORDER BY {$order_by}";

$r = @mysqli_query($dbc ,$q);

$todayasstring = date("y-m-d");

//Table header
if($r){

echo '<table align="center" cellspacing="1" cellpadding="5" width="100%">

<tr height="40px">
	
	<th align="left"><b><a href="my_reservations.php?sort=sd"><b>Start Date<b></ th>
	
	<th align="left"><b><a href="my_reservations.php?sort=ed"><b>End Date<b></ th>

	<th align="left"><b>Hotel Name<b></ th>
	
	<th align="left"><b>Hotel Address<b></ th>
	
	<th align="left"><b><a href="my_reservations.php?sort=rm">RoomNo.</a></b></ th>
	
	<th align="left"><b>Daily Rate<b></ th>
	
	<th align="left"><b>Change</b></th>
	
	<th align="left"><b>Cancel</b></th>

</hr>

';

// Fetch and print all the records...

$bg = '#eeeeee';
$j = 0;

while($row = mysqli_fetch_array($r, MYSQLI_BOTH)) {
	
	if($j>=$start && $j<($start+$display)){
	
	$bg = ($bg == '#eeeeee' ? '#ffffff' : '#eeeeee');
	
	echo '<tr bgcolor="' . $bg . '" height="40px">';
	
	echo '<td align="left" >' . date("M j, Y" ,strtotime($row['start_date'])) . '</td>';
	
	echo '<td align="left" >' . date("M j, Y" ,strtotime($row['end_date'])) . '</td>
	
	<td align="left">' . $row['branch_name'] . '</td>
	
	<td align="left" >' . $row['branch_addr'] . '</td>
	
	<td align="center">' . $row['room_id'] . '</td>
	
	<td align="left">$' . $row['price_rate'] . '/day</td>';

	if($row['end_date']>=$todayasstring){
	
	echo '<td align="left"><a href="edit_reservation.php?resid=' . $row['reservation_id'] . '">Change</a></td>';
	
	} else {
	
	echo '<td align="center"> - </td>';
	
	}
	
	if($row['start_date']>=$todayasstring) {
	
		echo '<td align="left"><a href="delete_reservation.php?resid=' . $row['reservation_id'] . '">Cancel</a></td>';
	
	} else {
	
		echo '<td align="center"> - </td>';
	
	}
	
		echo '</tr>';
	
	}
	
	$j++;

} 

echo '</table>';

} else {
echo '<p>You currently have no reservations with Sterling Cooper Draper Price</p><p></p>';
}

mysqli_free_result($r);

mysqli_close($dbc);

if ($pages > 1) {

echo '<br /><p>';
$current_page = ($start/$display) + 1;

// If it's not the first page, make a Previous button:
if ($current_page != 1) {
echo '<a href="my_reservations.php?s=' . ($start - $display) . '&p=' . $pages .'&sort=' . $sort . '">Previous</a> ';
}

// Make all the numbered pages:
for ($i = 1; $i <= $pages; $i++) {
if ($i != $current_page) {
echo '<a href="my_reservations.php?s=' .
(($display * ($i - 1))) . '&p=' .
$pages . '&sort=' . $sort . '">' . $i
. '</a> ';
} else {
echo $i . ' ';
}
} // End of FOR loop.

// If it's not the last page, make a Next button:
if ($current_page != $pages) {
echo '<a href="my_reservations.php?s=' .
($start + $display) . '&p=' . $pages .
'&sort=' . $sort . '">Next</a>';
 }

 echo '</p>'; // Close the paragraph.
 }

include ('includes/footer.html');
?>

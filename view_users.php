<?php 

$page_title = 'View the Curent Users';

require_once('session.php');

include('includes/header2.html');

require_once('connection.php');

echo '<h1>Registered Users</h1><p></p>';

//Number of records to show per page
$display = 10;

// Determine how many pages there are...

if(isset($_GET['p']) && is_numeric($_GET['p'])) {

$pages = $_GET['p'];

} else { // Need to determine 

// Count the number of records:

$q = "SELECT COUNT(client_id) FROM clientaccount";

$r = @mysqli_query($dbc, $q); 

while ($row = mysqli_fetch_array($r, MYSQLI_BOTH)) {
	$records = (int) $row[0];
}

//Calculate the number of pages...

if($records > $display){ // More than 1 page

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
$sort = (isset($_GET['sort'])) ? $_GET
['sort'] : 'id';

//Determine the sorting order:

switch($sort){
	case 'ln':
	$order_by = 'cl_lname ASC';
	break;
	
	case 'fn':
	$order_by = 'cl_fname ASC'; 
	break;
	
	case 'id':
	$order_by = 'client_id ASC';
	break;
	
	default:
	$order_by = 'client_id ASC';
	$sort = 'id';
	break;
}

//Make a query

$q = "SELECT * FROM clientaccount ORDER BY {$order_by}";

$r = @mysqli_query($dbc, $q);

//Table header

echo '<table align="center" cellspacing="0" cellpadding="5" width="100%">

<tr>

	<th align="left"><b>Edit</b></th>
	
	<th align="left"><b>Delete</b></th>
	
	<th align="left"><b><a href="view_users.php?sort=id">Client ID</a></b></th>
	
	<th align="left"><b><a href="view_users.php?sort=ln">Last Name</a></b></th>
	
	<th align="left"><b><a href="view_users.php?sort=fn">First Name</a></b></th>
	
	<th align="left"><b>Credit Card No.<b></ th>
	
	<th align="left"><b>Phone No.<b></ th>
	
	<th align="left"><b>Address<b></ th>

</tr>

';

// Fetch and print all the records...

$bg = '#eeeeee';
$j = 0;
while($row = mysqli_fetch_array($r, MYSQLI_BOTH)) {
	
	if($j>=$start && $j<($start+$display)){
	
	$bg = ($bg == '#eeeeee' ? '#ffffff' : '#eeeeee');

	echo '<tr bgcolor="' . $bg . '" height="40px">
	
	<td align="left"><a href="edit_user.php?id=' . $row['client_id'] . '">Edit</a></td>
	
	<td align="left"><a href="delete_user.php?id=' . $row['client_id'] . '">Delete</a></td>
	
	<td align="center">' . $row['client_id'] . '</td>
	
	<td align="left">' . $row['cl_lname'] . '</td>

	<td align="left">' . $row['cl_fname'] . '</td>
	
	<td align="left">' . $row['credit_card'] . '</td>
	
	<td align="left">' . $row['phone_number'] . '</td>
	
	<td align="left" width="140px">' . $row['email'] . '</td>

	</tr>
	
	';
	}
	
	$j++;

} 

echo '</table>';

mysqli_free_result($r);

mysqli_close($dbc);

if ($pages > 1) {

echo '<br /><p>';
$current_page = ($start/$display) + 1;

// If it's not the first page, make a Previous button:
if ($current_page != 1) {
echo '<a href="view_users.php?s=' .
($start - $display) . '&p=' . $pages .
'&sort=' . $sort . '">Previous</a> ';
}

// Make all the numbered pages:
for ($i = 1; $i <= $pages; $i++) {
if ($i != $current_page) {
echo '<a href="view_users.php?s=' .
(($display * ($i - 1))) . '&p=' .
$pages . '&sort=' . $sort . '">' . $i
. '</a> ';
} else {
echo $i . ' ';
}
}

// If it's not the last page, make a Next button:
if ($current_page != $pages) {
echo '<a href="view_users.php?s=' .
($start + $display) . '&p=' . $pages .
'&sort=' . $sort . '">Next</a>';
 }

 echo '</p>';

 }

include ('includes/footer.html');
?>

<?php 

$page_title = 'Hotel Status';

include('session.php');

include('includes/header2.html');

require_once('connection.php');

if(array_key_exists("change_branch", $_POST)){
	$hotelbranch = $_POST["branch"];
} else {
	$hotelbranch = $_SESSION["branch"];
}

$branchnamequery = 'SELECT branch_name from hotelbranch where branch_no = ' . $hotelbranch;

$branchnameresult = @mysqli_query($dbc, $branchnamequery); 

while ($row = mysqli_fetch_array($branchnameresult, MYSQLI_BOTH)) {
	$branchIworkat = $row[0];
}

echo '<h1>Branch No.' . $hotelbranch . ': ' . $branchIworkat . ' Status</h1>';

echo '<form action="hotel_status.php" method="POST"><select name="branch">';
	$q = "select * from hotelbranch";
	$hotelbranches = @mysqli_query($dbc, $q);
	while ($row = mysqli_Fetch_Array($hotelbranches, MYSQLI_ASSOC)) {
			if($hotelbranch == $row["branch_no"]){
				echo '<option value="' . $row["branch_no"] . '" selected="selected">' . $row["branch_name"] . ' - ' . $row["branch_addr"] . '</option>\n';
			} else {
				echo '<option value="' . $row["branch_no"] . '">' . $row["branch_name"] . ' - ' . $row["branch_addr"] . '</option>\n';
			}
		}

echo '</select><input type="submit" name="change_branch" value="Change Branch" /></form>';

echo '<p><b>Reservations in the week ahead:</b></p>';

//Number of records to show per page
$display = 30;

$daysahead = 7;
$today = date("Y-m-d");
$reachday = date("Y-m-d",strtotime($today . "+ $daysahead day"));

// Determine how many pages there are...

if(isset($_GET['p']) && is_numeric($_GET['p'])) {

$pages = $_GET['p'];

} else { // Need to determine 

// Count the number of records:

$q1= "SELECT room_id, branch_no FROM room where branch_no = " . $hotelbranch;

$r1 = @mysqli_query($dbc, $q1); 

$records = mysqli_num_rows($r1);

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
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'id';

//Determine the sorting order:

switch($sort){
	case 'id':
	$order_by = 'room_id ASC';
	break;
	
	default:
	$order_by = 'client_id ASC';
	$sort = 'id';
	break;
}

$q = "DROP VIEW IF EXISTS v";

$r = @mysqli_query($dbc, $q);

$q = "CREATE VIEW v AS (SELECT * FROM reservation where ((start_date BETWEEN DATE '$today' and DATE '$reachday') or 
(end_date BETWEEN DATE '$today' and DATE '$reachday')) and branch_no = 4 ORDER BY room_id,start_date)";

$r = @mysqli_query($dbc, $q);

$q = "DROP VIEW IF EXISTS w";

$r = @mysqli_query($dbc, $q);

$q = "CREATE VIEW w AS (SELECT room_id FROM room where branch_no = $hotelbranch)";

$r = @mysqli_query($dbc, $q);

$q = "SELECT * FROM w left outer join v on v.room_id = w.room_id order by 1, 6";

$r = @mysqli_query($dbc, $q);

//Table header

echo '<table align="center" cellspacing="0" cellpadding="5" width="100%">

<tr>
	
	<th align="center"><b>Room ID</b></th>';

for ($i = 0; $i < $daysahead; $i++) {
    echo '<th align="left"><p>' . DATE("Mj", strtotime($today . "+ $i day")). '</p><th>';
}

echo '</tr>';

// Fetch and print all the records...

$bg = '#eeeeee';
$j = 0;


while($row = mysqli_fetch_array($r, MYSQLI_BOTH)) {
	
	if($j>=$start && $j<($start+$display)){
	
	$bg = ($bg == '#eeeeee' ? '#ffffff' : '#eeeeee');
	
	echo '<tr bgcolor="' . $bg . '" height="10px">';
	echo '<td align="center">' . $row[0] . '</td>';
	
	
	for($z = 0; $z < $daysahead; $z++){
		$zthday = date("Y-m-d", strtotime($today . "+ $z day"));
		if( $row['start_date'] <= $zthday && $zthday <= $row['end_date'] ){
			echo '<td align="center"><a href="edit_user.php?id=' . $row['client_id'] . '"> Client ' . $row['client_id'] . '</a><td>';
		} else{
			echo '<td align="center"><p style="color:' . $bg . '">' . date("Mj", strtotime($today . "+ $z day")). '</p><td>';
		}
	}
	
	echo '</tr>';
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
echo '<a href="hotel_status.php?s=' .
($start - $display) . '&p=' . $pages .
'&sort=' . $sort . '">Previous</a> ';
}

// Make all the numbered pages:
for ($i = 1; $i <= $pages; $i++) {
if ($i != $current_page) {
echo '<a href="hotel_status.php?s=' .
(($display * ($i - 1))) . '&p=' .
$pages . '&sort=' . $sort . '">' . $i
. '</a> ';
} else {
echo $i . ' ';
}
}

// If it's not the last page, make a Next button:
if ($current_page != $pages) {
echo '<a href="hotel_status.php?s=' .
($start + $display) . '&p=' . $pages .
'&sort=' . $sort . '">Next</a>';
 }

 echo '</p>';

 }
?>

<?php 
include ('includes/footer.html');
?>

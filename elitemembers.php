<?php

// Manager query to see "elite" clients (clients with reservations at all hotels)
// add full file

$page_title = 'Elite Members Query'; //put the title of the page here

include('session.php');

include('includes/header2.html'); //has the styles and session/db conn stuff

include('oracle.php'); //contains the querying functions from oracle-test from the tutorial

?>
<h1>Sterling Cooper Draper Price Elite Members</h1>

<fieldset><legend>Clients have stayed in all hotel branches:</legend>

<p></p>

<?php

$stid = oci_parse($db_conn, 'SELECT C.client_id, C.cl_lname, C.cl_fname
FROM ClientAccount C
WHERE C.client_id IN
(SELECT DISTINCT R.client_id
FROM RESERVATION R
WHERE NOT EXISTS 
((SELECT HBM.branch_no
FROM HotelBranch_Manages HBM)
MINUS
(SELECT R2.branch_no
FROM Reservation R2 
WHERE R2.client_id = R.client_id)))');
//$id = $_SESSION["username"];

//oci_bind_by_name($stid, ':id', $id);

$r = oci_execute($stid);  // executes and commits
if ($r){

$bg = '#eeeeee';

echo "<table border='1'>";
echo '<tr><th align="center">Client Id</th><th align="center">Last Name</th><th align="center">First Name</th></tr>';
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {

	$bg = ($bg == '#eeeeee' ? '#ffffff' : '#eeeeee');

    echo '<tr bgcolor="' . $bg . '" height="40px">';
    foreach ($row as $item) {
        echo "    <td align='center' width='100px'>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>";
    }
    echo "</tr>";
}
echo "</table>";
}
?>

</fieldset style="width:500">

<?php
if($db_conn){

//form handling and database related querying should be here


} else {
	echo "cannot connect";
	$e = OCI_Error(); // For OCILogon errors pass no handle
	echo htmlentities($e['message']);
}

include ('includes/footer.html');

?>
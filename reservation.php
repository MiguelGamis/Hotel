<?php
ini_set('display_error', 1);

$page_title = 'Make A Reservation.';

$availablerooms;

require_once('session.php');

include('includes/header.html');

require_once('connection.php');

function print_Rooms($result) { //printsRooms for Reservations
	
	echo '<div id="available-reservations">';
	if(!$result){
		echo '<p class="error">There are no available rooms that meets your request. We are very sorry for this inconvience.</p>';
	} else{
	global $hotelbranch;
	
	echo '<form method="POST"><p><br>Available Rooms:<br></p>';
	echo '<p><table align="center" cellspacing="0" cellpadding="20" width="75%">';
	echo '<tr><th>Hotel Branch</th><th>Room Type</th><th>Room No.</th><th>Day Rate</th></tr>';
	
	$bg = '#eeeeee';
	
	while ($row = mysqli_Fetch_Array($result, MYSQLI_ASSOC)) {
	
		$bg = ($bg == '#eeeeee' ? '#ffffff' : '#eeeeee');
		
		echo '<tr bgcolor="' . $bg . '">
		
		<td align="left">' . $hotelbranch[$row["branch_no"]] . '</td>
		
		<td align="center">' . $row['room_type'] . '</td>
		
		<td align="center">' . $row["room_id"] . '</td>
		
		<td align="left">$' . $row["price_rate"] . '/day</td>
		
		<td align="center"><a href="confirm_reservation.php?br=' . $row['branch_no'] . '&rm=' . $row['room_id'] . '&tp=' . $row['room_type'] . '&sd=' . $_POST['sd'] . '&ed=' . $_POST['ed'] . '">Book Room</a></td></tr>' ;
		
	}
	echo '</table></p></form>';
	}
	echo '</div>';
}


?>

<h1>Make A Reservation</h1>

<div id="hotel-wrap">

<div id="hotel-search-wrap">

<form action="reservation.php" method="POST">

<fieldset><legend>Please choose which Hotel Branch location:</legend>

<p><b>Hotel Branch:</b>
<select name="hotelbranch">
<?php
	$q = "select * from hotelbranch";
	$hotelbranches = @mysqli_query($dbc, $q);
	while ($row = mysqli_Fetch_Array($hotelbranches, MYSQLI_ASSOC)) {
		$hotelbranch[$row['branch_no']] = "<b>" . $row['branch_name'] . "</b> - " . $row['branch_addr'] . ", " . $row['branch_city'];
		}
	if(isset($_POST['hotelbranch'])){
		$selected_hotel = $_POST['hotelbranch'];
	}
	
	foreach($hotelbranch as $key => $value){
		if($key == $selected_hotel){
			echo '<option value="' . $key . '" selected="selected">' . $value . '</option>\n';
		} else {
			echo '<option value="' . $key . '">' . $value . '</option>\n';
		}
	}
?>
</select></p>

<p><b>Room Type:</b> <input type="radio" name="roomtype" value="B" checked="checked"/> Both <input type="radio" name="roomtype" value="R" /> Regular <input type="radio" name="roomtype" value="D" /> Deluxe </p>

<p><b>Duration: </b>
<?php
require('calendar/tc_calendar.php');

	  $date = getDate();
	  $today = $date[0];
	  $todayasstring = date('Y', $today) . '-' . date('m', $today) . '-' . date('d', $today);
      
	  $sd_default = $todayasstring;
      $ed_default = $todayasstring;
	  $myCalendar = new tc_calendar("sd", true, false);
	  $myCalendar->setIcon("calendar/images/iconCalendar.gif");
	  if(array_key_exists('sd',$_POST)) {
	  $myCalendar->setDate(date('d', strtotime($_POST['sd']))
            , date('m', strtotime($_POST['sd']))
            , date('Y', strtotime($_POST['sd'])));
	  } else{
	  $myCalendar->setDate(date('d', strtotime($sd_default))
            , date('m', strtotime($sd_default))
            , date('Y', strtotime($sd_default)));
	  }
	  $myCalendar->setPath("calendar/");
	  $myCalendar->setYearInterval(2013, 2020);
	  $myCalendar->setAlignment('left', 'bottom');
	  
	  $myCalendar->setDatePair('sd', 'ed', $todayasstring);
	  $myCalendar->writeScript();	  
	  $myCalendar = new tc_calendar("ed", true, false);
	  $myCalendar->setIcon("calendar/images/iconCalendar.gif");
	  if(array_key_exists('ed',$_POST)) {
	  $myCalendar->setDate(date('d', strtotime($_POST['ed']))
            , date('m', strtotime($_POST['ed']))
            , date('Y', strtotime($_POST['ed'])));
	  } else {
	  $myCalendar->setDate(date('d', strtotime($ed_default))
           , date('m', strtotime($ed_default))
           , date('Y', strtotime($ed_default)));
	  }
	  $myCalendar->setPath("calendar/");
	  $myCalendar->setYearInterval(2013, 2020);
	  $myCalendar->setAlignment('left', 'bottom');
	  
	  $myCalendar->setDatePair('sd', 'ed', $todayasstring);
	  $myCalendar->writeScript();
?>
&nbsp <a href="http://www.triconsole.com/php/calendar_datepicker.php"> Copyright</a></p>

</fieldset style="width:500">

<div position="center"><input type="submit" name="findroom" value="Find available rooms" /></div>

</div>

<div id="hotel-image-wrap">

<div style=" border: 8px solid #666;
            border-radius: 30px;
            -moz-border-radius: 30px;
            -khtml-border-radius: 30px;
            -webkit-border-radius: 30px;
            width: 285px;
            height: 175px;
            background: url('hotel_images/pacific_rim.jpg');" />
</div>

</div>

</div>	

</form>

<?php
if($dbc){
	if( array_key_exists('findroom', $_POST) ){
		if( !($_POST['sd']=='0000-00-00' or $_POST['ed']=='0000-00-00') ){
			if($_POST['sd'] >= $todayasstring ){
			
				global $regularrooms;
				$requestedbranch = $_POST['hotelbranch'];
				$requestedstartdate = $_POST['sd'];
				$requestedenddate = $_POST['ed'];
				$requestedroomtype = $_POST['roomtype'];
				
				switch($_POST['roomtype']){
					case 'D':
					$roomfilter = ' AND room_type = "Deluxe" ';
					break;
					case 'R':
					$roomfilter = ' AND room_type = "Regular" ';
					break;
					default:
					$roomfilter = ' ';
					break;
				}
				
				$q1 = "DROP view IF EXISTS RM";

				@mysqli_query($dbc, $q1);
				
				$q2 = "CREATE VIEW RM AS SELECT * FROM Room NATURAL LEFT OUTER JOIN Reservation WHERE branch_no = " . $requestedbranch;

				@mysqli_query($dbc, $q2);
				
				$q3 = "
				select * from rm WHERE 
				(NOT EXISTS(select * from reservation r where r.branch_no = rm.branch_no and r.room_id = rm.room_id and 
				((DATE'" . $requestedstartdate . "' BETWEEN r.start_date AND r.end_date) 
				OR (DATE '" . $requestedenddate . "' BETWEEN r.start_date AND r.end_date))) 
				OR rm.start_date IS NULL) " . $roomfilter . " order by price_rate, room_id";
				
				$availablerooms = @mysqli_query($dbc, $q3);
				
				print_Rooms($availablerooms);
				
				mysqli_free_result($availablerooms);
				
				$q4 = "DROP view IF EXISTS RM";
				
				@mysqli_query($dbc, $q4);
				
				mysqli_close($dbc);
			}
			else{  
				echo '<p class="error">Error: Requested start date has already passed<br />';
			}
		} else {
			echo '<p class="error">Error: Please enter both start date and end date<br />';
		}
		/*if($_POST){
			 header( 'Location: reservation.php' );
		}*/
	}
} else {
	echo '<p class="error">Cannot connect to database</p>';
}
?>

<?php
echo '<div style="float : bottom;">';
include ('includes/footer.html');
echo '</div>';
?>

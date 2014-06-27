<?php

$page_title = "Calendar View";

require_once('session.php');

include('includes/header2.html');

//$showmonth = $_POST['showmonth'];
//$showyear = $_POST['showyear'];
$showmonth = 9;
$showyear = 2012;

$day_count = cal_days_in_month(CAL_GREGORIAN, $showmonth, $showyear);
$pre_days = date('w', mktime(0, 0, 0, $showmonth, 1, $showyear));
$post_days = (6 - (date('w', mktime(0, 0, 0, $showmonth, $day_count, $showyear))));

echo '<div id="calendar_wrap">';
echo '<div class="title_bar">';
echo '<div class="previous_month"></div>';
echo '<div class="show_month">' . $showmonth . '/' . $showyear . '</div>';
echo '<div class="next_month"></div>';
echo '</div>';

echo '<div class="week_days">';
echo '<div class="days_of_week">Sun</div>';
echo '<div class="days_of_week">Mon</div>';
echo '<div class="days_of_week">Tue</div>';
echo '<div class="days_of_week">Wed</div>';
echo '<div class="days_of_week">Thu</div>';
echo '<div class="days_of_week">Fri</div>';
echo '<div class="days_of_week">Sat</div>';
echo '<div class="clear"></div>';
echo '</div>';

if($pre_days != 0) {
	for($i=1; $i<=$pre_days; $i++){
		echo '<div class="non_cal_day"></div>';
	}
}

$i = 1;
while($i <= $day_count){

	echo '<div class="cal_day">';
	echo '<div class="day_heading">' . $i . '</div>';
	echo '</div>';
	$i++;
	
}

if($pre_days != 0) {
	for($i=1; $i<=$post_days; $i++){
		echo '<div class="non_cal_day"></div>';
	}
}

echo '</div>';

?>
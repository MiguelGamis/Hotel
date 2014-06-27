<?php
require_once('cursefilter.php');

$cursefilter = new cursefilter;

//-----------------------------------

$string = "I am a jerky buttface, I also know a lot of other jerks";

$clean_str = $cursefilter->clean($string);

echo $clean_str . '<br>';

echo $cursefilter->clean('You frikkin\' idiot buttface');

$band[] = 'Jermaine';

$band[] = 'breat';

$band[] = 'hart';

$days = array( 1 => 'Sun', 'Mon', 'Tue');

echo  $days[2] . 'Castle';

foreach($band as $value){
	echo 'Hey everybody, let\'s welcome ' . $value . '<br>';
}

$var = true;

$var2 = ($var) ? 5 : 6;

echo "The value of \$var2 is " . $var2;
?>
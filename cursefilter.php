<?php

class cursefilter {
	function clean($str){
		$cursewords = array("jerk","buttface", "idiot");

		$replacers = array("j**k", "cuteface", "id**t");

		$cleanStr = str_ireplace($cursewords, $replacers, $str);

		return $cleanStr;
	}
}
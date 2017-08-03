<?php
	
	function __autoload($classname){
		$filename = __HOME__ . '/class/'.mb_strtolower($classname).'.class';
		include_once $filename;
	}

?>

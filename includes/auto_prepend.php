<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

	header('Content-Type: text/html; charset=utf-8');
	mb_internal_encoding("UTF-8");

	include_once $_SERVER["DOCUMENT_ROOT"]."/includes/"."_config.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/includes/"."autoload_classes.php";

?>

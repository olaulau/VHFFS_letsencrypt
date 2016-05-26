<?php

function app_base_path() {
// 	echo $_SERVER['PHP_SELF'] . "<br/>";
// 	echo $_SERVER['SCRIPT_NAME'] . "<br/>";
// 	echo $_SERVER['SCRIPT_FILENAME'] . "<br/>";
// 	echo "<br/>";
// 	echo __FILE__ . "<br/>";
// 	echo __DIR__ . "<br/>";
// 	echo "<br/>";
	
	$webroot = str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['SCRIPT_FILENAME']);
// 	echo $webroot . "<br/>";
	
	$app_base_path = dirname(str_replace($webroot, '', __DIR__));
// 	echo $app_base_path . "<br/>"; exit;
	return $app_base_path;
}

$app_base_path = app_base_path();

session_set_cookie_params(0, $app_base_path);
session_start();

require_once __DIR__ . '/config.inc.php';
require_once __DIR__ . '/functions.inc.php';


spl_autoload_register(function ($class) {
	include __DIR__ . '/../classes/' . $class . '.class.php';
});
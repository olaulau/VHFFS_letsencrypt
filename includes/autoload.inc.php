<?php

require_once __DIR__ . '/config.inc.php';
require_once __DIR__ . '/functions.inc.php';


spl_autoload_register(function ($class) {
	include __DIR__ . '/../classes/' . $class . '.class.php';
});
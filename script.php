#! /usr/bin/php
<?php

require_once __DIR__.'/includes/config.inc.php';
require_once __DIR__.'/includes/functions.inc.php';


//  check we are root
if (posix_getuid() !== 0) {
	echo "It seems you don't have root rights. \n";
	echo "You should try running if with 'sudo'. \n";
	die;
	
} 


//  load content from JSON file
$content = json_decode(file_get_contents($conf['content_filename']), TRUE);
// echo "<pre>"; print_r($content); echo "/<pre>";

treat_content($content);

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

//  generate commands
verify_parameters($content);
$commands = get_commands($content);
echo "<pre>"; print_r($commands); echo "/<pre>"; die;


//  let's encrypt command execution
echo '<h3>let\'s encrypt command :</h3> <pre>' . $commands['le_command'] . '</pre>' . "\n";
exec($commands['le_command'], $output, $return_var);
if($return_var !== 0) {
	foreach ($output as $o) {
		echo $o . "\n";
	}
	echo 'RETURN = ' .  $return_var . "\n";
	die;
}

//  nginx config file writing
echo '<h3>nginx config :</h3> <pre>' . $commands['ng_conf'] . '</pre>' . "\n";
echo '<h3>nginx config file :</h3> <pre>' . $commands['ng_conf_file'] . '</pre>' . "\n";
$return_var = file_put_contents($commands['ng_conf_file'], $commands['ng_conf']);
if($return_var !== 0) {
	echo 'RETURN = ' .  $return_var . "\n";
	die;
}
else {
	echo 'config file written.' . "\n";
}

//  nginx config enabling
echo '<h3>nginx config enable :</h3> <pre>';
foreach ($commands['ng_conf_enable'] as $command) {
	echo $command . "\n";
}
echo '</pre>' . "\n";
foreach ($commands['ng_conf_enable'] as $command) {
	exec($command, $output, $return_var);
	if($return_var !== 0) {
		foreach ($output as $o) {
			echo $o . "\n";
		}
		echo 'RETURN = ' .  $return_var . "\n";
		die;
	}
}

//  nginx config reload
echo '<h3>nginx config reload :</h3> <pre>' . $commands['ng_conf_activation'] . '</pre>' . "\n";
exec($commands['ng_conf_activation'], $output, $return_var);
if($return_var !== 0) {
	foreach ($output as $o) {
		echo $o . "\n";
	}
	echo 'RETURN = ' .  $return_var . "\n";
	die;
}

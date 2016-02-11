#! /usr/bin/php
<?php

require_once __DIR__.'/includes/config.inc.php';
require_once __DIR__.'/includes/functions.inc.php';

//  load content from JSON file
$content = json_decode(file_get_contents($conf['content_filename']), TRUE);
// echo "<pre>"; print_r($content); echo "/<pre>";

//  generate commands
verify_parameters($content);
$commands = get_commands($content);
echo "<pre>"; print_r($commands); echo "/<pre>"; die;



echo '<h3>let\'s encrypt command :</h3> <pre>' . $commands['le_command'] . '</pre>' . "\n";
exec($commands['le_command'], $output, $return_var);
foreach ($output as $o) {
	echo $o . "\n";
}
echo 'RETURN = ' .  $return_var . "\n";

echo '<h3>nginx config :</h3> <pre>' . $commands['ng_conf'] . '</pre>' . "\n";
echo '<h3>nginx config file :</h3> <pre>' . $commands['ng_conf_file'] . '</pre>' . "\n";
file_put_contents($commands['ng_conf_file'], $commands['ng_conf']);
echo 'config file written.' . "\n";

echo '<h3>nginx config enable :</h3> <pre>' . $commands['ng_conf_enable'] . '</pre>' . "\n";
exec($commands['ng_conf_enable'], $output, $return_var);
foreach ($output as $o) {
	echo $o . "\n";
}
echo 'RETURN = ' .  $return_var . "\n";

echo '<h3>nginx config reload :</h3> <pre>' . $commands['ng_conf_activation'] . '</pre>' . "\n";
exec($commands['ng_conf_activation'], $output, $return_var);
foreach ($output as $o) {
	echo $o . "\n";
}
echo 'RETURN = ' .  $return_var . "\n";

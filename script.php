#! /usr/bin/php
<?php

require_once __DIR__.'/includes/config.inc.php';

//  load content from JSON file
$content = json_decode(file_get_contents($conf['content_filename']), TRUE);
// echo "<pre>";
// print_r($content);
// echo "/<pre>";

echo '<h3>let\'s encrypt command :</h3> <pre>' . $content['le_command'] . '</pre>' . "\n";
exec($content['le_command'], $output, $return_var);
foreach ($output as $o) {
	echo $o . "\n";
}
echo 'RETURN = ' .  $return_var . "\n";

echo '<h3>nginx config :</h3> <pre>' . $content['ng_conf'] . '</pre>' . "\n";
echo '<h3>nginx config file :</h3> <pre>' . $content['ng_conf_file'] . '</pre>' . "\n";
file_put_contents($content['ng_conf_file'], $content['ng_conf']);
echo 'config file written.' . "\n";

echo '<h3>nginx config enable :</h3> <pre>' . $content['ng_conf_enable'] . '</pre>' . "\n";
exec($content['ng_conf_enable'], $output, $return_var);
foreach ($output as $o) {
	echo $o . "\n";
}
echo 'RETURN = ' .  $return_var . "\n";

echo '<h3>nginx config reload :</h3> <pre>' . $content['ng_conf_activation'] . '</pre>' . "\n";
exec($content['ng_conf_activation'], $output, $return_var);
foreach ($output as $o) {
	echo $o . "\n";
}
echo 'RETURN = ' .  $return_var . "\n";

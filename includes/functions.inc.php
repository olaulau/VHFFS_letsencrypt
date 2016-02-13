<?php

require_once 'config.inc.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;


function verify_parameters($array) {
	if( empty($array['email']) || empty($array['domain']) || empty($array['webroot-path']) || empty($array['rsa-key-size']) ) {
		die('parameter problem');
	}
	// TODO : check params
	
}


function get_commands($array) {
	global $conf;
	$content = array();
	
	$content['le_command'] =
	$conf['letsencrypt_path'] . '/letsencrypt-auto certonly -v --text --agree-tos --renew-by-default \
--email "' . $array['email'] . '" \
--domain "' . $array['domain'] . '" \
--rsa-key-size ' . $array['rsa-key-size'] . ' \
--webroot --webroot-path "' . $array['webroot-path'] . '"';
	
	$content['ng_conf'] =
'server {
   listen 443;
   server_name ' . $array['domain'] . ';
   ssl on;
   ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
   ssl_certificate /etc/letsencrypt/live/' . $array['domain'] . '/cert.pem;
   ssl_certificate_key /etc/letsencrypt/live/' . $array['domain'] . '/privkey.pem;
	
   location / {
	proxy_pass http://' . $array['domain'] . ';
	proxy_set_header        X-Forwarded-For $proxy_add_x_forwarded_for;
	proxy_set_header        X-Forwarded-Proto $scheme;
   	add_header              Front-End-Https   on;
   }
}';
	
	$content['ng_conf_file'] = '/etc/nginx/sites-available/' . $array['domain'] . '';
	
	$content['ng_conf_enable'] = array(
			'rm -f /etc/nginx/sites-enabled/' . $array['domain'], 
			'ln -s /etc/nginx/sites-available/' . $array['domain'] . ' /etc/nginx/sites-enabled/' . $array['domain']
	);			
	
	$content['ng_conf_activation'] = 'service nginx reload';
	
	return $content;
}


function treat_content($content) {
// 	global $conf;
	
	verify_parameters($content);
	
	//  generate commands
	$commands = get_commands($content);
// 	echo "<pre>"; print_r($commands); echo "/<pre>"; return;
	
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
}


function put_content_into_queue($content) {
	global $conf;
	$conn = new AMQPConnection($conf['rabbitmq_host'], $conf['rabbitmq_post'], $conf['rabbitmq_user'], $conf['rabbitmq_pass'], $conf['rabbitmq_vhost']);
	$ch = $conn->channel();
	
	$ch->queue_declare($conf['rabbitmq_queue'], false, true, false, false);
	$ch->exchange_declare($conf['rabbitmq_exchange'], 'direct', false, true, false);
	$ch->queue_bind($conf['rabbitmq_queue'], $conf['rabbitmq_exchange']);
	
	$msg_body = json_encode($content);
	$msg = new AMQPMessage($msg_body, array('content_type' => 'text/plain', 'delivery_mode' => 2));
	$ch->basic_publish($msg, $conf['rabbitmq_exchange']);
	
	$ch->close();
	$conn->close();
}
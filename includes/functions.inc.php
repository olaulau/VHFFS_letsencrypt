<?php

require_once __DIR__ . '/../includes/autoload.inc.php';
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
--domain "www.' . $array['domain'] . '" \
--rsa-key-size ' . $array['rsa-key-size'] . ' \
--webroot --webroot-path "' . $array['webroot-path'] . '" 2>&1';
	
	$content['ng_conf'] =
'server {
   listen 443;
   server_name ' . $array['domain'] . ';
   ssl on;
   ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
   ssl_certificate /etc/letsencrypt/live/' . $array['domain'] . '/fullchain.pem;
   ssl_certificate_key /etc/letsencrypt/live/' . $array['domain'] . '/privkey.pem;
	
   location / {
	proxy_pass http://' . $array['domain'] . ';
	proxy_set_header        X-Forwarded-For $proxy_add_x_forwarded_for;
	proxy_set_header        X-Forwarded-Proto $scheme;
   	add_header              Front-End-Https   on;
   }
}
			
server {
   listen 443;
   server_name www.' . $array['domain'] . ';
   ssl on;
   ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
   ssl_certificate /etc/letsencrypt/live/' . $array['domain'] . '/fullchain.pem;
   ssl_certificate_key /etc/letsencrypt/live/' . $array['domain'] . '/privkey.pem;
	
   location / {
	proxy_pass http://www.' . $array['domain'] . ';
	proxy_set_header        X-Forwarded-For $proxy_add_x_forwarded_for;
	proxy_set_header        X-Forwarded-Proto $scheme;
   	add_header              Front-End-Https   on;
   }
}
';
	
	$content['ng_conf_file'] = '/etc/nginx/sites-available/' . $array['domain'] . '';
	
	$content['ng_conf_enable'] = array(
			'rm -f /etc/nginx/sites-enabled/' . $array['domain'], 
			'ln -s /etc/nginx/sites-available/' . $array['domain'] . ' /etc/nginx/sites-enabled/' . $array['domain']
	);			
	
	$content['ng_conf_activation'] = 'service nginx reload';
	
	return $content;
}


/**
 * 
 * @param unknown $content
 * @return string error
 */
function treat_content($content) {
	echo PHP_EOL;
	echo '-----------------------------------------------------' . PHP_EOL;
	echo "DATE = " . date(DateTime::COOKIE) . PHP_EOL;
	echo 'begining of treatment.' . PHP_EOL;
	echo PHP_EOL;
	
	verify_parameters($content);
	
	//  generate commands
	$commands = get_commands($content);
// 	echo "<pre>"; print_r($commands); echo "/<pre>"; return;
	$error_buffer = '';
	
	//  let's encrypt command execution
	echo 'let\'s encrypt command : ' . PHP_EOL;
	echo $commands['le_command'] . PHP_EOL;
	exec($commands['le_command'], $output, $return_var);
	if($return_var !== 0) {
		foreach ($output as $o) {
			echo $o . PHP_EOL;
			$error_buffer .= $o . PHP_EOL;
		}
		echo 'RETURN = ' .  $return_var . PHP_EOL;
		$error_buffer .= 'RETURN = ' .  $return_var . PHP_EOL;
		return $error_buffer;
	}
	
	//  nginx config file writing
	echo 'nginx config : ' . PHP_EOL;
	echo $commands['ng_conf'] . PHP_EOL;
	echo 'nginx config file : ' . PHP_EOL;
	echo $commands['ng_conf_file'] . PHP_EOL;
	$return_var = file_put_contents($commands['ng_conf_file'], $commands['ng_conf']);
	if($return_var === FALSE) {
		echo 'RETURN = ' .  $return_var . PHP_EOL;
		$error_buffer .= 'RETURN = ' .  $return_var . PHP_EOL;
		return($error_buffer);
	}
	else {
		echo 'config file written.' . PHP_EOL;
	}
	
	//  nginx config enabling
	echo 'nginx config enable : ' . PHP_EOL;
	foreach ($commands['ng_conf_enable'] as $command) {
		echo $command . PHP_EOL;
	}
	echo PHP_EOL;
	foreach ($commands['ng_conf_enable'] as $command) {
		exec($command, $output, $return_var);
		if($return_var !== 0) {
			foreach ($output as $o) {
				echo $o . PHP_EOL;
				$error_buffer .= $o . PHP_EOL;;
			}
			echo 'RETURN = ' .  $return_var . PHP_EOL;
			$error_buffer .= 'RETURN = ' .  $return_var . PHP_EOL;
			return($error_buffer);
		}
	}
	
	//  nginx config reload
	echo 'nginx config reload : ' . PHP_EOL;
	echo $commands['ng_conf_activation'] . PHP_EOL;
	exec($commands['ng_conf_activation'], $output, $return_var);
	if($return_var !== 0) {
		foreach ($output as $o) {
			echo $o . PHP_EOL;
			$error_buffer .= $o . PHP_EOL;
		}
		echo 'RETURN = ' .  $return_var . PHP_EOL;
		$error_buffer .= 'RETURN = ' .  $return_var . PHP_EOL;
		return($error_buffer);
	}
	
	echo PHP_EOL;
	echo 'end of treatment.' . PHP_EOL;
	echo "DATE = " . date(DateTime::COOKIE) . PHP_EOL;
	echo '-----------------------------------------------------' . PHP_EOL;
	echo PHP_EOL;
	return(NULL);
}


function put_content_into_queue($content) {
	global $conf;
	$conn = new AMQPConnection($conf['rabbitmq_host'], $conf['rabbitmq_port'], $conf['rabbitmq_user'], $conf['rabbitmq_pass'], $conf['rabbitmq_vhost']);
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

function create_renew_cert($servername) {
	global $conf;
	// get missing data's from VHFFS database
	$owner = VHFFS::get_owner_user_from_httpd_servername($servername);
	$content = array(
			'domain' => $servername,
			'rsa-key-size' => $conf['rsa-key-size'],
			'email' => $owner->mail,
			'webroot-path' => VHFFS::get_webrootpath_from_servername($servername)
	);
	verify_parameters($content);
	
	//  put content into queue
	put_content_into_queue($content);
}
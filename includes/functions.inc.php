<?php

require_once __DIR__ . '/../includes/autoload.inc.php';
require_once __DIR__ . '/../vendor/autoload.php';


use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;


function verify_parameters($infos) {
	if( empty($infos['email']) || empty($infos['domain']) || empty($infos['webroot-path']) || empty($infos['rsa-key-size']) ) {
		die('parameter problem');
	}
	// TODO : check params
	
}


function check_sibling_DNS($domain) {
	$res1 = gethostbyname($domain);
	$res2 = gethostbyname('www.' . $domain);
	if($res1 === $res2)
		return true;
	else
		return false;
}


function get_commands($infos, $renew=FALSE) {
	global $conf;
	$commands = array();
	
	//  checks if the domain with WWW also resolves to the same host
	$add_www_domain = check_sibling_DNS($infos['domain']);
	
	$commands['le_command'] =
	$conf['letsencrypt_path'] . '/' . $conf['letsencrypt_bin'] . ' certonly -v --text --renew-by-default \
';
	if(!$renew)
		$commands['le_command'] .=
'--email "' . $infos['email'] . '" --agree-tos \
';
		$commands['le_command'] .=
'--domain "' . $infos['domain'] . '" \
';
	if($add_www_domain)
		$commands['le_command'] .= 
'--domain "www.' . $infos['domain'] . '" \
';
	$commands['le_command'] .=
'--rsa-key-size ' . $infos['rsa-key-size'] . ' \
--webroot --webroot-path "' . $infos['webroot-path'] . '" 2>&1';
	
	$commands['clean_command'] = 'rmdir ' . $infos['webroot-path'] . '/.well-known/';
	
	$commands['ng_conf'] =
'server {
	listen 443;
	server_name ' . $infos['domain'] . ';
	ssl on;
	ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
	ssl_certificate /etc/letsencrypt/live/' . $infos['domain'] . '/fullchain.pem;
	ssl_certificate_key /etc/letsencrypt/live/' . $infos['domain'] . '/privkey.pem;
	
	location / {
		proxy_pass http://' . $infos['domain'] . ';
		proxy_set_header        X-Forwarded-For $proxy_add_x_forwarded_for;
		proxy_set_header        X-Forwarded-Proto $scheme;
		add_header              Front-End-Https   on;
   }
}
';
	if($add_www_domain)
		$commands['ng_conf'] .= '
server {
	listen 443;
	server_name ' . 'www.' . $infos['domain'] . ';
	ssl on;
	ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
	ssl_certificate /etc/letsencrypt/live/' . $infos['domain'] . '/fullchain.pem;
	ssl_certificate_key /etc/letsencrypt/live/' . $infos['domain'] . '/privkey.pem;
	
	location / {
		proxy_pass http://' . 'www.' . $infos['domain'] . ';
		proxy_set_header        X-Forwarded-For $proxy_add_x_forwarded_for;
		proxy_set_header        X-Forwarded-Proto $scheme;
		add_header              Front-End-Https   on;
   }
}
';
	
	$commands['ng_conf_file'] = '/etc/nginx/sites-available/' . $infos['domain'];
	
	$commands['ng_conf_enable'] = array(
		'rm -f /etc/nginx/sites-enabled/' . $infos['domain'], 
		'ln -s /etc/nginx/sites-available/' . $infos['domain'] . ' /etc/nginx/sites-enabled/' . $infos['domain']
	);			
	
	$commands['ng_conf_activation'] = 'service nginx reload';
	
	return $commands;
}


/**
 * 
 * @param unknown $infos
 * @return string error
 */
function create_cert($infos, $renew=FALSE) {
	echo PHP_EOL;
	echo '-----------------------------------------------------' . PHP_EOL;
	echo "DATE = " . date(DateTime::COOKIE) . PHP_EOL;
	echo 'begining of treatment.' . PHP_EOL;
	echo PHP_EOL;
	
	verify_parameters($infos);
	
	//  generate commands
	$commands = get_commands($infos, $renew);
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
	
	//  let's encrypt command execution
	echo 'clean command : ' . PHP_EOL;
	echo $commands['clean_command'] . PHP_EOL;
	exec($commands['clean_command'], $output, $return_var);
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


function get_queue_length() {
	global $conf;
	$conn = new AMQPConnection($conf['rabbitmq_host'], $conf['rabbitmq_port'], $conf['rabbitmq_user'], $conf['rabbitmq_pass'], $conf['rabbitmq_vhost']);
	$ch = $conn->channel();
	$res = $ch->queue_declare($conf['rabbitmq_queue'], false, true, false, false);
	$ch->close();
	$conn->close();
	return $res[1];
}


function put_item_into_queue($action, $infos) {
	global $conf;
	$conn = new AMQPConnection($conf['rabbitmq_host'], $conf['rabbitmq_port'], $conf['rabbitmq_user'], $conf['rabbitmq_pass'], $conf['rabbitmq_vhost']);
	$ch = $conn->channel();
	
	$ch->queue_declare($conf['rabbitmq_queue'], false, true, false, false);
	$ch->exchange_declare($conf['rabbitmq_exchange'], 'direct', false, true, false);
	$ch->queue_bind($conf['rabbitmq_queue'], $conf['rabbitmq_exchange']);
	
	$item = array(
		'action' => $action,
		'infos' => $infos,
	);
	
	$msg_body = json_encode($item);
	$msg = new AMQPMessage($msg_body, array('content_type' => 'text/plain', 'delivery_mode' => 2));
	$ch->basic_publish($msg, $conf['rabbitmq_exchange']);
	
	$ch->close();
	$conn->close();
}

function ask_for_cert($action, $servername) {
	global $conf;
	//  get missing data's from VHFFS database
	$owner = VHFFS::get_owner_user_from_httpd_servername($servername);
	$infos = array(
			'domain' => $servername,
			'rsa-key-size' => $conf['rsa-key-size'],
			'email' => $owner->mail,
			'webroot-path' => VHFFS::get_webrootpath_from_servername($servername)
	);
	verify_parameters($infos);
	
	//  put item into queue
	put_item_into_queue($action, $infos);
}

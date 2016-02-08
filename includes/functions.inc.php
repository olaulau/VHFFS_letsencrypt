<?php

require_once 'config.inc.php';


function verify_parameters() {
	if( empty($_POST['email']) || empty($_POST['domain']) || empty($_POST['webroot-path']) || empty($_POST['rsa-key-size']) ) {
		die('parameter problem');
	}
	// TODO : check params
	
}


function get_commands() {
	global $conf;
	$content = array();
	
	$content['le_command'] =
	$conf['letsencrypt_path'] . '/letsencrypt-auto certonly -v --text --agree-tos --renew-by-default \
--email "' . $_POST['email'] . '" \
--domain "' . $_POST['domain'] . '" \
--rsa-key-size ' . $_POST['rsa-key-size'] . ' \
--webroot --webroot-path "' . $_POST['webroot-path'] . '"';
	
	$content['ng_conf'] =
'server {
   listen 443;
   server_name ' . $_POST['domain'] . ';
   ssl on;
   ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
   ssl_certificate /etc/letsencrypt/live/' . $_POST['domain'] . '/cert.pem;
   ssl_certificate_key /etc/letsencrypt/live/' . $_POST['domain'] . '/privkey.pem;
	
   location / {
	proxy_pass http://' . $_POST['domain'] . ';
	proxy_set_header        X-Forwarded-For $proxy_add_x_forwarded_for;
	proxy_set_header        X-Forwarded-Proto $scheme;
   	add_header              Front-End-Https   on;
   }
}';
	
	$content['ng_conf_file'] = '/etc/nginx/sites-available/' . $_POST['domain'] . '';
	
	$content['ng_conf_enable'] = 'ln -s /etc/nginx/sites-available/' . $_POST['domain'] . ' /etc/nginx/sites-enabled/' . $_POST['domain'] . '';
	
	$content['ng_conf_activation'] = 'service nginx reload';
	
	return $content;
}


<?php

require_once 'config.inc.php';


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


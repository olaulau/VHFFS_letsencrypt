#! /usr/bin/php
<?php

//  execute this script in a daily crontab to enable auto-renew of your VHFFS let's encrypt certificates

require_once __DIR__ . '/includes/autoload.inc.php';
require_once __DIR__ . '/vendor/autoload.php';


// query DB, loop domains to renew
$servernames = VHFFS_letsencrypt::get_servernames_to_renew();

foreach ($servernames as $servername) {
	create_renew_cert($servername); //TODO invoke a renew method instead of create
	/*
	./letsencrypt-auto certonly -v --text --domain domain.com --rsa-key-size 4096 --webroot --webroot-path /path
	service nginx reload
	
	diff : pas --email, et pas --agree-tos
	on peut pt etre retirer --rsa-key-size
	 */
}

//TODO if vhffs_httpd cannot be found, revoke certificate and remove config from nginx

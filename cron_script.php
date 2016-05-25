#! /usr/bin/php
<?php

//  execute this script in a daily crontab to enable auto-renew of your VHFFS let's encrypt certificates

require_once __DIR__ . '/includes/autoload.inc.php';
require_once __DIR__ . '/vendor/autoload.php';


// query DB, loop domains to renew
$servernames = VHFFS_letsencrypt::get_servernames_to_renew();

foreach ($servernames as $servername) {
	ask_for_cert('renew', $servername); //TODO invoke a renew method instead of create
}

//TODO if vhffs_httpd cannot be found, revoke certificate and remove config from nginx

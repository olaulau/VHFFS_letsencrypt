<?php

// copy to config.inc.php and fill-in values

//let's encrypt
$conf['letsencrypt_path'] = '/root/letsencrypt/';
$conf['letsencrypt_bin'] = 'letsencrypt-auto'; // probably certbot
$conf['rsa-key-size'] = 4096;
$conf['le_expiration_delay'] = 90;
$conf['le_recommended_renewal'] = 60;

// admin users, open auth/passwd.php to create a password hash
$conf['admins'] = array(
		'admin' => ''
);

//  RabbitMQ
$conf['rabbitmq_host'] = 'localhost';
$conf['rabbitmq_port'] = 5672;
$conf['rabbitmq_user'] = 'guest';
$conf['rabbitmq_pass'] = 'guest';
$conf['rabbitmq_vhost'] = '/';
$conf['rabbitmq_exchange'] = 'router';
$conf['rabbitmq_queue'] = 'msgs';
define('AMQP_DEBUG', false);

//  PostgreSQL (VHFFS)
$conf['postgresql_host'] = 'localhost';
$conf['postgresql_port'] = '5432';
$conf['postgresql_dbname'] = 'vhffs';
$conf['postgresql_schema'] = 'public';
$conf['postgresql_username'] = 'vhffs';
$conf['postgresql_password'] = '';

$conf['postgresql_tablename'] = 'vhffs_letsencrypt';

$conf['webarea_root'] = '/data/web';

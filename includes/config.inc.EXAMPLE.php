<?php

// copy to config.inc.php and fill-in values

$conf['letsencrypt_path'] = '/root/letsencrypt';

$conf['rsa-key-sizes'] = array(2048, 4096);
$conf['default_rsa-key-sizes'] = 4096;

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
$conf['postgresql_schema'] = 'vhffs';
$conf['postgresql_username'] = 'vhffs';
$conf['postgresql_password'] = '';

$conf['webarea_root'] = '/data/web';

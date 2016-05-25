#! /usr/bin/php
<?php

require_once __DIR__ . '/includes/autoload.inc.php';
require_once __DIR__ . '/vendor/autoload.php';


use PhpAmqpLib\Connection\AMQPConnection;


//  check we are root
if (posix_getuid() !== 0) {
	echo "It seems you don't have root rights. \n";
	echo "You should try running it with 'sudo'. \n";
	die;
	
} 


//  consume the queue
$consumer_tag = 'consumer';

$conn = new AMQPConnection($conf['rabbitmq_host'], $conf['rabbitmq_port'], $conf['rabbitmq_user'], $conf['rabbitmq_pass'], $conf['rabbitmq_vhost']);
$ch = $conn->channel();
$ch->queue_declare($conf['rabbitmq_queue'], false, true, false, false);
$ch->exchange_declare($conf['rabbitmq_exchange'], 'direct', false, true, false);
$ch->queue_bind($conf['rabbitmq_queue'], $conf['rabbitmq_exchange']);

/**
 * @param \PhpAmqpLib\Message\AMQPMessage $msg
*/
function process_message($msg)
{
	//  load content queue
	$content = json_decode($msg->body, TRUE);
	// echo "<pre>"; print_r($content); echo "/<pre>";
	
	if($content['action'] === 'create') {
		$infos = $content['infos'];
		$db = new VHFFS();
		$vh = $db->get_httpd_from_servername($infos['domain']);
		$vl = VHFFS_letsencrypt::get_from_httpd_id($vh->httpd_id);
		if(empty($vl)) {
			$vl = new VHFFS_letsencrypt($vh->httpd_id);
		}
		
		$error = create_cert($infos);
		if(isset($error)) {
			$vl->cert_error($error);
		}
		else {
			$vl->cert_ok();
		}
		
		$msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
	}
}

$ch->basic_consume($conf['rabbitmq_queue'], $consumer_tag, false, false, false, false, 'process_message');

/**
 * @param \PhpAmqpLib\Channel\AMQPChannel $ch
 * @param \PhpAmqpLib\Connection\AbstractConnection $conn
*/
function shutdown($ch, $conn)
{
	$ch->close();
	$conn->close();
}

register_shutdown_function('shutdown', $ch, $conn);

//  Loop as long as the channel has callbacks registered
while (count($ch->callbacks)) {
	$ch->wait();
}

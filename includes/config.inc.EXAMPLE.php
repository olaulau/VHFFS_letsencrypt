<?php

// copy to config.inc.php and fill-in values

$conf['content_filename'] = 'content.json';
$conf['letsencrypt_path'] = '/root/letsencrypt';
$conf['rsa-key-sizes'] = array(2048, 4096);

// admin users, open auth/passwd.php to create a password hash
$conf['admins'] = array(
		'admin' => ''
);

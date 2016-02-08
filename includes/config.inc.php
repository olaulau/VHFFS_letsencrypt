<?php

// copy to config.inc.php and fill-in values

$conf['content_filename'] = 'content.json';
$conf['letsencrypt_path'] = '/root/letsencrypt';

// admin users, open auth/passwd.php to create a password hash
$conf['admins'] = array(
		'admin' => '$2y$10$WvLlDqrWML6d8drCLeuCxu00i5qdOFNCBQ9zA/HCvPnU0rJVwi0v.'
);

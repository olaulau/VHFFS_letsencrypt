<?php

require_once __DIR__ . '/../includes/autoload.inc.php';

Admin::restrict('./');

$domains = VHFFS::get_alpha_domains();

echo json_encode($domains);

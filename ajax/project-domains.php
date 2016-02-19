<?php
session_start();

require_once __DIR__.'/../includes/config.inc.php';
require_once __DIR__.'/../includes/admin.class.php';
require_once __DIR__ . '/../includes/VHFFS.class.php';

Admin::restrict('./');

$vhffs = new VHFFS();
$domains = $vhffs->get_project_domains();

echo json_encode($domains);

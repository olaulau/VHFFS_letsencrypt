<?php
session_start();

require_once __DIR__ . '/../includes/autoload.inc.php';

Admin::restrict('./');

$vhffs = new VHFFS();
$domains = $vhffs->get_project_domains();

echo json_encode($domains);

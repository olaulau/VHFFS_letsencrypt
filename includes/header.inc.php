<?php
require_once __DIR__ . '/autoload.inc.php';

Admin::restrict('./');

VHFFS::create_table_if_needed();

$pages =
	array(
		array(
			'label' => 'Home',
			'url' => 'index.php'
		),
		array(
			'label' => 'List',
			'url' => 'list_vhffs_domains.php'
		),
		array(
			'label' => 'Missing',
			'url' => 'missing_domains.php'
		),
		array(
			'label' => 'Create',
			'url' => 'create.php'
		),
	);

function listPages() {
	global $pages;
	foreach ($pages as $page) {
		?>
		<li class="<?= (basename($_SERVER['REQUEST_URI']) === $page['url']) ? 'active' : '' ?>"><a href="<?=$page['url'] ?>"><?=$page['label'] ?></a></li>
		<?php
	}
	
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title>VHFFS letsencrypt</title>
	
	<!-- Bootstrap -->
	<link href="../external/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="../external/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
	
	<link href="../index.css" rel="stylesheet">
	
	
	
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		    <![endif]-->
	
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="../external/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="../external/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>

	<nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">VHFFS - Let's Encrypt</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <?php
            listPages();
            ?>
			<!-- 
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li role="separator" class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
            </li>
             -->
          </ul>
          <a href="../auth/signout.php"><button type="button" class="btn btn-lg btn-danger pull-right" style="position: absolute; right: 0px;">Log out</button></a>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    
<?php
//  print_r($_POST);

if(!empty($_POST)) {
	// 	var_dump($_POST); die;
	if(!empty($_POST['domain'])) {
		//TODO verify posted domain
		ask_for_cert('create', $_POST['domain']);
		$_SESSION['messages'][] = 'content added to queue. it will be treated as soon as possible.';
	}
	else {
		$_SESSION['messages'][] = 'Please select a domain from the list.';
	}
	
}

?>

	<div id="messages"class="container" role="main">
		<div style="position: absolute;">
<?php
if(!empty($_SESSION['messages'])) {
	foreach ($_SESSION['messages'] as $message) {
		?>
		<div class="alert alert-success" role="alert">
			<?=$message?>
		</div>
		<?php
	}
	unset($_SESSION['messages']);
}
?>
		</div>
	</div>

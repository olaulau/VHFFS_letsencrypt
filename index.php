<?php
session_start();

require_once __DIR__.'/includes/functions.inc.php';
require_once __DIR__.'/includes/config.inc.php';
require_once __DIR__.'/includes/admin.class.php';
require_once __DIR__ . '/includes/VHFFS.class.php';

Admin::restrict('./');
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
	<link href="external/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="external/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
	
	<link href="index.css" rel="stylesheet">
	
	
	
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		    <![endif]-->
	
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="external/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="external/bootstrap/js/bootstrap.min.js"></script>
	
	<script src="index.js"></script>
</head>
<body>

	<div class="container" role="main">
<?php
if(!empty($_SESSION['messages'])) {
	foreach ($_SESSION['messages'] as $message) {
		?>
		<div class="alert alert-success " role="alert">
			<?=$message?>
		</div>
		<?php
	}
	unset($_SESSION['messages']);
}
?>
</div>

<?php
if(Admin::is_admin()) {
	?>
	<a href="auth/signout.php"><button type="button" class="btn btn-lg btn-primary pull-right">Log out</button></a>
	<?php
}
else {
	?>
	<a href="auth/signin.php"><button type="button" class="btn btn-lg btn-primary pull-right">Log in</button></a>
	<?php
}
?>

<?php
//  print_r($_POST);
if(empty($_POST)) {
	?>
	<form action="" method="POST">
		<fieldset>
		<legend>Informations :</legend>
			
			<div class="form-group">
				<label for="domain" class="col-sm-2 control-label">domain</label>
				<div class="col-sm-4"> <select  id="domains" class="form-control" name="domain"> </select> </div>
			</div> <br/>
			
			<div class="form-group">
				<label for="rsa-key-size" class="col-sm-2 control-label">rsa-key-size</label>
				<div class="col-sm-4">
					<select class="form-control" name="rsa-key-size">
<?php
foreach ($conf['rsa-key-sizes'] as $key_size) {
	if($key_size === $conf['default_rsa-key-sizes']) {
		echo
'						<option value="' . $key_size . '" selected="selected">' . $key_size . '</option>';
	}
	else {
		echo
'						<option value="' . $key_size . '">' . $key_size . '</option>';
		}
	}
?>
					</select>
				</div>
			</div> <br/>
			
			<div class="col-sm-6">
				<button class="btn btn-default form-control" type="submit"> SEND </button>
			</div>
		</fieldset>
	</form>
	<?php
}


else {
	$content = array(
			'domain' => $_POST['domain'],
			'rsa-key-size' => $_POST['rsa-key-size']
	);
	
	// get missing data's from VHFFS database
	$vhffs = new VHFFS();
	$owner = $vhffs->get_owner_user_from_httpd_servername($content['domain']);
	$content['email'] = $owner->mail;
	$content['webroot-path'] = $vhffs->get_webrootpath_from_servername($content['domain']);

	verify_parameters($content);
	
	//  put content into queue
	put_content_into_queue($content);
	echo 'content added to queue. it will be treated as soon as possible. <br/>';
}
?>
</body>
</html>

<?php
session_start();

require_once __DIR__.'/includes/functions.inc.php';
require_once __DIR__.'/includes/config.inc.php';
require_once __DIR__.'/includes/admin.class.php';

Admin::restrict('./');
?>

<!DOCTYPE html>
<html lang="en">
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
				<label for="email" class="col-sm-2 control-label">e-mail</label>
				<div class="col-sm-4"> <input type="email" name="email" value="" class="form-control" id="email" placeholder="e-mail"> </div>
			</div> <br/>
			
			<div class="form-group">
				<label for="domain" class="col-sm-2 control-label">domain</label>
				<div class="col-sm-4"> <input type="text" name="domain" value="" class="form-control" id="domain" placeholder="domain"> </div>
			</div> <br/>
			
			<div class="form-group">
				<label for="webroot-path" class="col-sm-2 control-label">webroot path</label>
				<div class="col-sm-4"> <input type="text" name="webroot-path" value="" class="form-control" id="webroot-path" placeholder="webroot path"> </div>
			</div> <br/>
			
			<div class="form-group">
				<label for="rsa-key-size" class="col-sm-2 control-label">rsa-key-size</label>
				<div class="col-sm-4">
					<select class="form-control" name="rsa-key-size">
<?php
foreach ($conf['rsa-key-sizes'] as $key_size) {
	echo 
'						<option value="' . $key_size . '">' . $key_size . '</option>';
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
	verify_parameters();
	
	$content = get_commands();
	
?><h2>to be executed by root</h2><?php
	
	echo '<h3>let\'s encrypt command :</h3> <pre>' . $content['le_command'] . '</pre>';
	
	echo '<h3>nginx config :</h3> <pre>' . $content['ng_conf'] . '</pre>';
	
	echo '<h3>nginx config file :</h3> <pre>' . $content['ng_conf_file'] . '</pre>';
	
	echo '<h3>nginx config enable :</h3> <pre>' . $content['ng_conf_enable'] . '</pre>';
	
	echo '<h3>nginx config reload :</h3> <pre>' . $content['ng_conf_activation'] . '</pre>';
	
	file_put_contents($conf['content_filename'] , json_encode($content));
	echo 'content saved into disk file. try running \'sudo php script.php\'.';
}
?>
</body>
</html>

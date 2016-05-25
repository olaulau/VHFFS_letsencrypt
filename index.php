<?php
session_start();

require_once __DIR__ . '/includes/autoload.inc.php';


Admin::restrict('./');

VHFFS::create_table_if_needed();
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

<a href="auth/signout.php"><button type="button" class="btn btn-lg btn-danger pull-right" style="position: absolute; right: 0px;">Log out</button></a>

<div style="text-align: center;">
	<h1 class="form-signin-heading">VHFFS - Let's Encrypt</h1>
	<h3 class="form-signin-heading">(automatic certificate request and install for VHFFS hosting)</h3>
</div>


<h4 class="form-signin-heading col-sm-offset-1">Please choose a domain from the list :</h4>

<form action="" method="POST" name="easyform" class="">
		
		<div class="row col-sm-4 col-md-offset-4 text-center form-group-lg">
			<label for="grouped_by_project">group domains by project</label>
			<input type="checkbox" id="grouped_by_project" name="grouped_by_project" />
			
			<div id="domains_alpha">
				<h4><label for="alpha-domain" class="">domains (alphabetical order)</label></h4>
				<select id="alpha-domain" class="form-control" name="alpha-domain"> </select>
			</div>
			
			<div id="domains_by_project">
				<h4><label for="project-domain" class="">domains (by project)</label></h4>
				<select id="project-domain" class="form-control" name="project-domain">	</select>
			</div>
		</div>

		
		<div class="row">&nbsp;</div>
		
		<input id="domain" name="domain" value="" style="display:none;">
		
		<div class="row">&nbsp;</div>
		
		<div class="row">&nbsp;</div>
		
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-10 col-sm-offset-1">
					<button class="btn btn-lg btn-success col-sm-8 col-sm-offset-2" type="submit"> <br/> SEND <br/> &nbsp; </button>
				</div>
			</div>
		</div>
		
</form>


</body>
</html>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Password hash generation</title>

    <!-- Bootstrap core CSS -->
    <link href="../external/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../external/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

      <form class="form-signin" action="passwd.php" method="post">
        <h2 class="form-signin-heading">Enter your pasword</h2>

        <input type="text" id="password" name="password" class="form-control" placeholder="Password" required value="<?= isset($_POST['password']) ? $_POST['password'] : '' ?>">
        
        <button class="btn btn-lg btn-success btn-block" type="submit">generate</button>
      </form>
	<?php
	if(!empty($_POST['password'])) {
		echo 'You can modify includes/config.inc.php with this value : <br/>';
		echo '<pre>' . password_hash($_POST['password'], PASSWORD_BCRYPT) . '</pre>';
		echo 'example : (in your includes/config.inc.php) <br/>';
		echo "<pre>" . PHP_EOL . 
    	"// admin users, open auth/passwd.php to create a password hash" . PHP_EOL . 
		"\$conf['admins'] = array(" . PHP_EOL . 
		"		'admin' => '" . password_hash($_POST['password'], PASSWORD_BCRYPT) . "'" . PHP_EOL . 
		");" . PHP_EOL . 
    	"</pre>";
		
	}
	?>

    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>

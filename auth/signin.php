<?php

require_once __DIR__ . '/../includes/autoload.inc.php';

if(!empty($_GET['redirect'])) {
	$redirect = $_GET['redirect'];
}
else {
	$redirect =$_SERVER['HTTP_REFERER'];
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sign in</title>

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
	<a href="passwd.php"><button type="button" class="btn btn-lg btn-info pull-right">generate password hash</button></a>
	
    <div class="container">

      <form class="form-signin" action="signin.post.php" method="post">
        <h2 class="form-signin-heading">Please sign in</h2>
        <label for="login" class="sr-only">Login</label>
        <input type="text" id="login" name="login" class="form-control" placeholder="Login" required autofocus>
        <label for="password" class="sr-only">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
<!--         <div class="checkbox"> -->
<!--           <label> -->
<!--             <input type="checkbox" value="remember-me"> Remember me -->
<!--           </label> -->
<!--         </div> -->
        <button class="btn btn-lg btn-success btn-block" type="submit">Sign in</button>
        <input type="hidden" name="redirect" value="<?= $redirect ?>">
      </form>

    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>

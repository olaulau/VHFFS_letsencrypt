<?php

require_once 'functions.inc.php';

?>
<html>
<head>
	
</head>
<body>
<?php
//  print_r($_POST);
if(empty($_POST)) {
	?>
	<form action="" method="POST">
	<fieldset>
  		<legend>Informations :</legend>
		<label for="email">e-mail</label> <input type="text" name="email" value="" /> <br/>
		<label for="domain">domain</label> <input type="text" name="domain" value="" /> <br/>
		<label for="webroot-path">webroot path</label> <input type="text" name="webroot-path" value="" /> <br/>
		
		<label for="rsa-key-size">rsa key size</label>
		<select name="rsa-key-size">
			<option value="2048">2048</option>
			<option value="4096">4096</option>
		</select> <br/>
		<button type="submit"> SEND </button>
	</fieldset>
	</form>
	<?php
}

else {
	verify_parameters();
	
	$content = get_commands();
	
?><h2> to be executed by root</h2><?php
	
	echo '<h3>let\'s encrypt command :</h3> <pre>' . $content['le_command'] . '</pre>';
	
	echo '<h3>nginx config :</h3> <pre>' . $content['ng_conf'] . '</pre>';
	
	echo '<h3>nginx config file :</h3> <pre>' . $content['ng_conf_file'] . '</pre>';
	
	echo '<h3>nginx config enable :</h3> <pre>' . $content['ng_conf_enable'] . '</pre>';
	
	echo '<h3>nginx config reload :</h3> <pre>' . $content['ng_conf_activation'] . '</pre>';
}
?>
</body>
</html>

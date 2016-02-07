<html>
<head>
	
</head>
<body>
<?php

//  print_r($_POST);
if(empty($_POST)) {
	?>
	<form action="" method="POST">
		<label for="email">e-mail</label> <input type="text" name="email" value="" /> <br/>
		<label for="domain">domain</label> <input type="text" name="domain" value="" /> <br/>
		<label for="webroot">webroot</label> <input type="text" name="webroot" value="" /> <br/>
		<button type="submit"> SEND </button>
	</form>
	<?php
}
else {
	// TODO : check params
	if( empty($_POST['email']) || empty($_POST['domain']) || empty($_POST['webroot']) ) {
		die('parameter problem');
	}
	
	////
	
?><h2> to be executed by root</h2><?php
	
	$le_command = 
'/root/letsenfrypt/letsencrypt-auto certonly -v --text --agree-tos --renew-by-default \
--email "' . $_POST['email'] . '" \
--domain "' . $_POST['domain'] . '" \
--rsa-key-size 4096 \
--webroot --webroot-path "' . $_POST['webroot'] . '"';
	echo '<h3>let\'s encrypt command :</h3> <pre>' . $le_command . '</pre>';
	
	$ng_conf = 
'server {
   listen 443;
   server_name ' . $_POST['domain'] . ';
   ssl on;
   ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
   ssl_certificate /etc/letsencrypt/live/' . $_POST['domain'] . '/cert.pem;
   ssl_certificate_key /etc/letsencrypt/live/' . $_POST['domain'] . '/privkey.pem;

   location / {
	proxy_pass http://' . $_POST['domain'] . ';
	proxy_set_header        X-Forwarded-For $proxy_add_x_forwarded_for;
	proxy_set_header        X-Forwarded-Proto $scheme;
   	add_header              Front-End-Https   on;
   }
}';
	echo '<h3>nginx config :</h3> <pre>' . $ng_conf . '</pre>';
	
	$ng_conf_file = '/etc/nginx/sites-available/' . $_POST['domain'] . '';
	echo '<h3>nginx config file :</h3> <pre>' . $ng_conf_file . '</pre>';
	
	$ng_conf_enable = 'ln -s /etc/nginx/sites-available/' . $_POST['domain'] . ' /etc/nginx/sites-enabled/' . $_POST['domain'] . '';
	echo '<h3>nginx config enable :</h3> <pre>' . $ng_conf_enable . '</pre>';
	
	$ng_conf_activation = 'service nginx reload';
	echo '<h3>nginx config reload :</h3> <pre>' . $ng_conf_activation . '</pre>';
}
?>
</body>
</html>





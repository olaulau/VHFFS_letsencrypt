# VHFFS_letsencrypt
Let's Encrypt automating for VHFFS hosting with Nginx HTTPS front

### Requirements :
- Let's Encrypt (`git clone https://github.com/letsencrypt/letsencrypt`)
- (VHFFS with WebArea service enabled)
	- Apache2 listening on HTTP port 80
- PHP >= 5.5
- Nginx with only a default config for HTTPS port 443
- RabbitMQ 
	- install : `apt-get install rabbitmq-server`
	- enable managment plugin : `rabbitmq-plugins enable rabbitmq_management`
	- go to admin : `http://localhost:15672/` with default account : guest / guest
	- you can create a new admin account, and then delete the default guest account
	- create a user account for your app, and a virtualhost if you want
	- fill-in the config file with those informations

### This project also make use of :
- bootstrap
- log4php
- jquery
- [php-amqpli](https://github.com/php-amqplib/php-amqplib)

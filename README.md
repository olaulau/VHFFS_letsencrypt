# VHFFS_letsencrypt
Let's Encrypt automating for VHFFS hosting with Nginx HTTPS front

### Requirements :
- [Let's Encrypt](https://letsencrypt.org/) (`git clone https://github.com/letsencrypt/letsencrypt`)
- [VHFFS](http://vhffs.org/) with [WebArea](http://vhffs.org/doc:installationguide:web-service] service enabled
	- [Apache2](https://httpd.apache.org/) listening on HTTP port 80
- [PHP](https://secure.php.net/) >= 5.5
- [Nginx](http://nginx.org/) with only a default config for HTTPS port 443
- [RabbitMQ](https://www.rabbitmq.com/)
	- install : `apt-get install rabbitmq-server`
	- enable managment plugin : `rabbitmq-plugins enable rabbitmq_management`
		- old distrib : `/usr/lib/rabbitmq/lib/rabbitmq_server-2.7.1/sbin/rabbitmq-plugins enable rabbitmq_management` and then `service rabbitmq-server restart`
	- go to admin : [http://localhost:15672/] with default account : `guest` / `guest`
		- old distrib : [http://localhost:55672/]
	- you can create a new admin account, and then delete the default guest account
	- create a user account for your app, and a virtualhost if you want
		- don't forget to give rights on the new virtualhost for your user
	- fill-in the config file with those informations

- [bootstrap](http://getbootstrap.com/)
### This project also make use of :
- [log4php](https://logging.apache.org/log4php/)
- [jquery](https://jquery.com/)
- [php-amqpli](https://github.com/php-amqplib/php-amqplib)

### Instructions :
- you have to start the `consumer_script.php` as root

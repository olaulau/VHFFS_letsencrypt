# VHFFS_letsencrypt
Let's Encrypt automating for VHFFS hosting with Nginx HTTPS front

### Requirements :
- [Let's Encrypt](https://letsencrypt.org/) (`git clone https://github.com/letsencrypt/letsencrypt`)
- [VHFFS](http://vhffs.org/) with [WebArea](http://vhffs.org/doc:installationguide:web-service) service enabled
	- [Apache2](https://httpd.apache.org/) listening on HTTP port 80
	- [PostGreSQL](http://www.postgresql.org/) with full access to the VHFFS database
- [PHP](https://secure.php.net/) >= 5.5
- [Nginx](http://nginx.org/) with only a default config for HTTPS port 443
- [RabbitMQ](https://www.rabbitmq.com/)  
	- install : `apt-get install rabbitmq-server`
	- enable managment plugin : `rabbitmq-plugins enable rabbitmq_management`
		- old distrib : `/usr/lib/rabbitmq/lib/rabbitmq_server-2.7.1/sbin/rabbitmq-plugins enable rabbitmq_management` and then `service rabbitmq-server restart`
	- go to admin : [http://localhost:15672/](http://localhost:15672/) with default account : `guest` / `guest`
		- old distrib : [http://localhost:55672/](http://localhost:55672/)
	- you can create a new admin account, and then delete the default guest account
	- create a user account for your app, and a virtualhost if you want
		- don't forget to give rights on the virtualhost for your user
	- fill-in the config file (`includes/config.inc.php`) with those informations

### This project also make use of :
- [bootstrap](http://getbootstrap.com/)
- [log4php](https://logging.apache.org/log4php/)
- [jquery](https://jquery.com/)
- [php-amqpli](https://github.com/php-amqplib/php-amqplib)

### Installation :
- just clone the project : `git clone https://github.com/olaulau/VHFFS_letsencrypt`
- don't forget to copy and modify the config file : `includes/config.inc.EXAMPLE.php`
- execute the create SQL queries in `notes.sql` on your VHFFS database

### Running :
- to consume the queue, you have to start the `consumer_script.php` as root :
	- `screen -S VHFFS_letsencrypt`
	- `./consumer_script.php | tee -a consumer_script.log`

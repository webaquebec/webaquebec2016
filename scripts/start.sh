#!/bin/bash

DBPASSWD="$(cat /www/conf/waq2016/DBPASSWD)"
DBUSRPASSWD="$(date +%s | sha256sum | base64 | head -c 32 ; echo)"
echo "$DBUSRPASSWD" > '/www/conf/waq2016/WPDBPASSWD'

# Clone project
git clone https://github.com/paulcote/2016.waq.paulcote.net.git /www/sites/waq2016

# Install & Run composer
cd /www/sites/waq2016
curl -sS https://getcomposer.org/installer | php
php composer.phar install

# MySQL Secure Installation as defined via: mysql_secure_installation
mysql -uroot -p$DBPASSWD -e "CREATE DATABASE waq2016 DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;"
mysql -uroot -p$DBPASSWD -e "CREATE USER 'waq2016'@'localhost' IDENTIFIED BY '$DBUSRPASSWD';"
mysql -uroot -p$DBPASSWD -e "GRANT ALL PRIVILEGES ON waq2016.* TO 'waq2016'@'localhost' WITH GRANT OPTION;"

WPKEYS="$(curl https://api.wordpress.org/secret-key/1.1/salt/)"

ENVFILE="<?php

define('DB_NAME', 'waq2016');
define('DB_USER', 'waq2016');
define('DB_PASSWORD', '$DBUSRPASSWD');
define('DB_HOST', 'localhost');

$WPKEYS

\$table_prefix  = 'waq16_';

define('WP_DEBUG', true);"
echo "$ENVFILE" > '/www/sites/waq2016/public/env.php'

chown -R www-data:www-data /www/sites/waq2016/public/uploads
service php5-fpm restart
service nginx restart

#!/bin/bash

motdwarn="#!/bin/sh

echo \"INSTALLATION HAS NOT YET FINISHED. LET IT BE.\""
echo "$motdwarn" > '/etc/update-motd.d/99-install-not-finished'
chmod +x /etc/update-motd.d/99-install-not-finished

# Set the Server Timezone to CST
echo "America/Montreal" > /etc/timezone
dpkg-reconfigure -f noninteractive tzdata

# Update basic image
apt-get update
apt-get -y upgrade

# Install Nginx
apt-get install -y nginx

# Install PHP5-FPM
apt-get install -y php5-fpm

# Install MySQL Server in a Non-Interactive mode. Default root password will be "root"
echo "mysql-server mysql-server/root_password password root" | sudo debconf-set-selections
echo "mysql-server mysql-server/root_password_again password root" | sudo debconf-set-selections
apt-get -y install mysql-server

mysql_secure_installation

# Install other Requirements
apt-get -y install php5-mysql curl git

# Create project folders
mkdir -p /www/sites/waq2016 /www/conf/waq2016 /www/logs/waq2016

# Download nginx conf
wget -O /www/conf/waq2016/nginx.conf https://github.com/paulcote/2016.waq.paulcote.net/raw/master/conf/nginx.conf

# Remove default and put WAQ conf
unlink /etc/nginx/sites-enabled/default
ln -s /www/conf/waq2016/nginx.conf /etc/nginx/sites-enabled/99-waq2016

rm /etc/update-motd.d/99-install-not-finished
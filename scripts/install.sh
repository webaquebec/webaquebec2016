#!/bin/bash

# Keep upstart from complaining
dpkg-divert --local --rename --add /sbin/initctl
ln -sf /bin/true /sbin/initctl

# Set the Server Timezone to CST
echo "America/Montreal" > /etc/timezone
dpkg-reconfigure -f noninteractive tzdata

# Update basic image
apt-get update
apt-get -y upgrade

# Basic Requirements
apt-get -y install mysql-server mysql-client nginx php5-fpm php5-mysql php-apc pwgen python-setuptools curl git unzip

# Wordpress Requirements
apt-get -y install php5-curl php5-gd php5-intl php-pear php5-imagick php5-imap php5-mcrypt php5-memcache php5-ming php5-ps php5-pspell php5-recode php5-sqlite php5-tidy php5-xmlrpc php5-xsl

# Create project folders
mkdir -p /www/sites/waq2016 /www/conf/waq2016 /www/logs/waq2016

# Download nginx conf
wget -O /www/conf/waq2016/nginx.conf https://github.com/paulcote/2016.waq.paulcote.net/raw/master/conf/nginx.conf

# Remove default and put WAQ conf
unlink /etc/nginx/sites-enabled/default
ln -s /www/conf/waq2016/nginx.conf /etc/nginx/sites-enabled/99-waq2016

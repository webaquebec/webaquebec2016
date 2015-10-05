#!/bin/bash
exec 3>&1 4>&2
trap 'exec 2>&4 1>&3' 0 1 2 3
exec 1>/tmp/install.log 2>&1
# Everything below will go to the file '/tmp/install.log':

DBPASSWD="$(date +%s | sha256sum | base64 | head -c 32 ; echo)"

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
echo "mysql-server mysql-server/root_password password $DBPASSWD" | sudo debconf-set-selections
echo "mysql-server mysql-server/root_password_again password $DBPASSWD" | sudo debconf-set-selections
apt-get -y install mysql-server

# Setup required database structure
mysql_install_db

# MySQL Secure Installation as defined via: mysql_secure_installation
mysql -uroot -p$DBPASSWD -e "DROP DATABASE test"
mysql -uroot -p$DBPASSWD -e "DELETE FROM mysql.user WHERE User='root' AND NOT IN ('localhost', '127.0.0.1', '::1')"
mysql -uroot -p$DBPASSWD -e "DELETE FROM mysql.user WHERE User=''"
mysql -uroot -p$DBPASSWD -e "DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%'"
mysql -uroot -p$DBPASSWD -e "FLUSH PRIVILEGES"

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

"$DBPASSWD" > /www/conf/waq2016/DBPASSWD

echo "Install is finished !"

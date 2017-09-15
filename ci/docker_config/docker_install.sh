#!/bin/bash

# We need to install dependencies only for Docker
[[ ! -e /.dockerenv ]] && exit 0

set -xe

# Set app directory path to variable
app_directory=`pwd`

# Set IP to variable
app_ip=`ip addr show eth0 | grep -Po 'inet \K[\d.]+'`

# Copy Supervisor config
cp ./ci/docker_config/supervisor.conf /etc/supervisor/conf.d/php-nginx-supervisor.conf

# Copy Nginx config files and set root path
cp ./ci/docker_config/app.conf /etc/nginx/sites-available/default
cp ./ci/docker_config/nginx.conf /etc/nginx/nginx.conf
sed -i 's,root %root_path%;,root '"$app_directory"';,' /etc/nginx/sites-available/default

# Enable connection to PHP from localhost
sed -i 's/;listen.allowed_clients = 127.0.0.1/listen.allowed_clients = 127.0.0.1/' /usr/local/etc/php-fpm.d/www.conf
sed -i 's/listen = 127.0.0.1:9000/listen = 9000/' /usr/local/etc/php-fpm.d/www.conf

# Replace Selenium app url with real app ip
sed -i 's,\url: http://.*,url: http://'"$app_ip"',' "$app_directory"/tests/acceptance.suite.yml

# Set full permissions to app folder
chmod -R 777 "$app_directory"
chmod -R 777 "$app_directory"/*.*

# Run Supervisor
service supervisor start


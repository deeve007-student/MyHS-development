#!/bin/bash

# We need to install dependencies only for Docker
[[ ! -e /.dockerenv ]] && exit 0

set -xe

# Set app directory path to variable
app_directory=`pwd`

# Set IP to variable
app_ip=`ip addr show eth0 | grep -Po 'inet \K[\d.]+'`

# Update Nginx host config
sed -i 's,root /var/www/backend/web;,root '"$app_directory"'/web;,' /etc/nginx/sites-available/default

# Replace Selenium app url with real app ip
sed -i 's,\url: http://.*,url: http://'"$app_ip"',' "$app_directory"/tests/acceptance.suite.yml

# Run Supervisor
service supervisor start

